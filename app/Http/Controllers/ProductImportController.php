<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FailedImport;
use App\Models\Product;
use App\Models\Store;
use App\Models\Veriant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    public function index()
    {
        return view('admin.product.import');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $rows = Excel::toArray([], $request->file('excel_file'))[0] ?? [];

        return response()->json($rows);
    }

    public function process(Request $request)
    {
        $rows = $request->input('rows', []);
        $results = [
            'success' => [],
            'failed' => [],
        ];

        $userData = getUserData();
        $store_id = $userData['store_id'] ?? null;
        $user_id = $userData['user_id'] ?? null;
        $customer_id = $userData['customer_id'] ?? null;

        if (!$store_id || !$user_id || !$customer_id) {
            return response()->json([
                'success' => [],
                'failed' => [
                    [
                        'index' => -1,
                        'row' => [],
                        'error' => 'Store/User information not found. Please login again.',
                    ]
                ]
            ], 422);
        }

        $store = Store::find($store_id);
        if (!$store) {
            return response()->json([
                'success' => [],
                'failed' => [
                    [
                        'index' => -1,
                        'row' => [],
                        'error' => 'Store not found.',
                    ]
                ]
            ], 422);
        }

        $lastInsertedProduct = null;

        foreach ($rows as $key => $row) {
            DB::beginTransaction();

            try {
                $type = strtolower(trim((string)($row['type'] ?? '')));

                $normalized = $this->normalizeRow($row);

                if (in_array($type, ['p', '1', 'product'])) {
                    $product = $this->importProductRow(
                        $normalized,
                        $store,
                        $store_id,
                        $user_id,
                        $customer_id
                    );

                    $lastInsertedProduct = $product;
                } else {
                    $product = $this->importVariantRow(
                        $normalized,
                        $store_id,
                        $lastInsertedProduct
                    );

                    $lastInsertedProduct = $product;
                }

                DB::commit();

                $results['success'][] = $key;
            } catch (\Throwable $e) {
                DB::rollBack();

                $results['failed'][] = [
                    'index' => $key,
                    'row' => $row,
                    'error' => $e->getMessage(),
                ];

                FailedImport::create([
                    'row_data' => json_encode($row),
                    'error' => $e->getMessage(),
                    'product_id' => null,
                ]);
            }
        }

        return response()->json($results);
    }

    private function normalizeRow(array $row): array
    {
        return [
            'type' => strtolower(trim((string)($row['type'] ?? ''))),
            'name' => trim((string)($row['name'] ?? '')),
            'description' => trim((string)($row['description'] ?? '')),
            'product_images' => trim((string)($row['product_images'] ?? '')),
            'category' => trim((string)($row['category'] ?? '')),
            'subcategory' => trim((string)($row['subcategory'] ?? '')),
            'sku' => trim((string)($row['sku'] ?? '')),
            'price' => $this->normalizeNumber($row['price'] ?? 0),
            'quantity' => $this->normalizeNumber($row['quantity'] ?? 0),

            'variant_type' => trim((string)($row['variant_type'] ?? '')),
            'variant_value' => trim((string)($row['variant_value'] ?? '')),
            'variant_quantity' => $this->normalizeNumber($row['variant_quantity'] ?? 0),
            'additional_price' => $this->normalizeNumber($row['additional_price'] ?? 0),
            'variant_image' => trim((string)($row['variant_image'] ?? '')),
        ];
    }

    private function normalizeNumber($value)
    {
        $value = trim((string)$value);

        if ($value === '') {
            return 0;
        }

        $value = str_replace(',', '', $value);

        return is_numeric($value) ? $value : 0;
    }

    private function importProductRow(array $row, Store $store, $store_id, $user_id, $customer_id): Product
    {
        $validator = Validator::make($row, [
            'name' => 'required|string',
            'description' => 'required|string',
            'sku' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'category' => 'required|string',
        ], [
            'name.required' => 'Product name is required.',
            'description.required' => 'Product description is required.',
            'sku.required' => 'SKU is required.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be numeric.',
            'quantity.required' => 'Quantity is required.',
            'quantity.numeric' => 'Quantity must be numeric.',
            'category.required' => 'Category is required.',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $existingSku = Product::where('store_id', $store_id)
            ->where('SKU', $row['sku'])
            ->first();

        if ($existingSku) {
            throw new \Exception("SKU '{$row['sku']}' already exists.");
        }

        $categoryData = $this->processCategoriesAndSubcategories(
            $row['category'],
            $row['subcategory'],
            $store_id,
            $user_id,
            $customer_id
        );

        $product = new Product();
        $product->name = $row['name'];
        $product->description = $row['description'];
        $product->gallery_image = $this->processProductImages($row['product_images']);
        $product->category = $categoryData['category_ids'];
        $product->subcategory = $categoryData['subcategory_ids'];
        $product->SKU = $row['sku'];
        $product->regular_price = $row['price'];
        $product->quantity = $row['quantity'];
        $product->status = 'active';
        $product->discount_type = 'no_discount';
        $product->promotional_price = 0;
        $product->tax_type = 'no_tax';
        $product->tax_rate = 0;
        $product->currency_id = $store->currency;
        $product->uid = $user_id;
        $product->customer_id = $customer_id;
        $product->store_id = $store_id;
        $product->creator = $user_id;
        $product->editor = $user_id;
        $product->best_sell = 0;
        $product->feature = 0;
        $product->pse = 0;
        $product->save();

        return $product;
    }

    private function importVariantRow(array $row, $store_id, ?Product $lastInsertedProduct): Product
    {
        if (empty($row['variant_type']) || empty($row['variant_value'])) {
            throw new \Exception('Variant type and variant value are required.');
        }

        $product = null;

        if (!empty($row['sku'])) {
            $product = Product::where('store_id', $store_id)
                ->where('SKU', $row['sku'])
                ->first();
        }

        if (!$product && $lastInsertedProduct) {
            $product = $lastInsertedProduct;
        }

        if (!$product) {
            throw new \Exception('Main product not found for this variant. Fix product row first or provide correct SKU.');
        }

        $variantTypes = array_map('trim', explode(',', $row['variant_type']));
        $variantValues = array_map('trim', explode(',', $row['variant_value']));
        $variantImages = array_map('trim', explode(',', $row['variant_image']));

        if (count($variantTypes) !== count($variantValues)) {
            throw new \Exception('Variant type and variant value count mismatch.');
        }

        $variantData = [
            'pid' => $product->id,
            'quantity' => $row['variant_quantity'] ?? 0,
            'additional_price' => $row['additional_price'] ?? 0,
        ];

        foreach ($variantTypes as $index => $type) {
            $field = strtolower(trim($type));
            $value = $variantValues[$index] ?? '';

            if ($field === '' || $value === '') {
                continue;
            }

            if (!in_array($field, ['color', 'size', 'unit', 'volume'])) {
                throw new \Exception("Unsupported variant type '{$field}'. Allowed: color, size, unit, volume.");
            }

            $variantData[$field] = $value;

            $image = $variantImages[$index] ?? '';
            $image = $this->cleanImagePath($image);

            if ($image !== '') {
                if ($field === 'color') {
                    $variantData['color_image'] = $image;
                } else {
                    $variantData['image'] = $image;
                }
            }
        }

        if (
            empty($variantData['color']) &&
            empty($variantData['size']) &&
            empty($variantData['unit']) &&
            empty($variantData['volume'])
        ) {
            throw new \Exception('No valid variant value found.');
        }

        Veriant::create($variantData);

        $totalVariantQty = (float) Veriant::where('pid', $product->id)->sum('quantity');
        if ($totalVariantQty > 0) {
            $product->quantity = $totalVariantQty;
            $product->save();
        }

        return $product;
    }

    public function processCategoriesAndSubcategories($categoryString, $subcategoryString, $store_id, $user_id, $customer_id)
    {
        $categoryIds = [];
        $subcategoryIds = [];

        $categoryNames = array_filter(array_map('trim', explode(',', (string)$categoryString)));

        foreach ($categoryNames as $categoryName) {
            $category = Category::where('store_id', $store_id)
                ->where('parent', 0)
                ->whereRaw('LOWER(name) = ?', [strtolower($categoryName)])
                ->first();

            if (!$category) {
                $category = new Category();
                $category->name = $categoryName;
                $category->parent = 0;
                $category->status = 'active';
                $category->position = 0;
                $category->uid = $user_id;
                $category->customer_id = $customer_id;
                $category->store_id = $store_id;
                $category->creator = $user_id;
                $category->editor = $user_id;
                $category->save();
            }

            $categoryIds[] = $category->id;
        }

        $subcategoryNames = array_filter(array_map('trim', explode(',', (string)$subcategoryString)));

        foreach ($subcategoryNames as $subcategoryName) {
            $parentId = end($categoryIds) ?: 0;

            $subcategory = Category::where('store_id', $store_id)
                ->where('parent', $parentId)
                ->whereRaw('LOWER(name) = ?', [strtolower($subcategoryName)])
                ->first();

            if (!$subcategory) {
                $subcategory = new Category();
                $subcategory->name = $subcategoryName;
                $subcategory->parent = $parentId;
                $subcategory->status = 'active';
                $subcategory->position = 0;
                $subcategory->uid = $user_id;
                $subcategory->customer_id = $customer_id;
                $subcategory->store_id = $store_id;
                $subcategory->creator = $user_id;
                $subcategory->editor = $user_id;
                $subcategory->save();
            }

            $subcategoryIds[] = $subcategory->id;
        }

        return [
            'category_ids' => implode(',', $categoryIds),
            'subcategory_ids' => implode(',', $subcategoryIds),
        ];
    }

    public function processProductImages($gallery_image)
    {
        if (empty($gallery_image)) {
            return "";
        }

        $productImageArray = explode(',', $gallery_image);

        $productImageArray = array_map(function ($image) {
            return $this->cleanImagePath($image);
        }, $productImageArray);

        $productImageArray = array_filter($productImageArray);

        return implode(',', $productImageArray);
    }

    private function cleanImagePath($image)
    {
        $image = trim((string)$image);

        if ($image === '') {
            return '';
        }

        $image = str_replace(env("APP_URL"), "", $image);

        return trim($image, '/');
    }
}