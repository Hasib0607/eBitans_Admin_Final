<?php

namespace App\Services\Storefront;

class StorefrontProductPresenter
{
    public function compact($product, ?array $fields = null): array
    {
        $regularPrice = (float) ($product->regular_price ?? 0);
        $promotionalPrice = (float) ($product->promotional_price ?? 0);
        $discountPrice = $regularPrice <= $promotionalPrice ? 0 : $promotionalPrice;
        $calculateRegularPrice = getPrice($regularPrice, $discountPrice, $product->discount_type);

        $payload = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => generateSlug($product->name, '-'),
            'image' => $this->firstProductImage($product),
            'regular_price' => $regularPrice,
            'price' => (float) $calculateRegularPrice,
            'calculate_regular_price' => (float) $calculateRegularPrice,
            'discount_price' => (float) $discountPrice,
            'discount_type' => $product->discount_type,
            'quantity' => (float) $product->quantity,
            'stock_status' => $product->stock_status ?? (((float) $product->quantity) > 0 ? 'in_stock' : 'out_of_stock'),
            'symbol' => $product->symbol,
            'brand_name' => $product->getBrand->name ?? '',
        ];

        return $this->onlyFields($payload, $fields);
    }

    public function detail($product, $variants, array $categories, array $subcategories, $layout = null): array
    {
        $images = $this->productImages($product);
        $averageRating = isset($product->reviews_avg_rating)
            ? (float) $product->reviews_avg_rating
            : ($product->reviews_count > 0 ? $product->reviews_sum_rating / $product->reviews_count : 0);
        $discountPrice = $product->regular_price <= $product->promotional_price ? 0 : $product->promotional_price;
        $calculateRegularPrice = getPrice($product->regular_price, $discountPrice, $product->discount_type);

        $variantPayload = $variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'pid' => $variant->pid,
                'color' => trim($variant->color ?? ''),
                'color_name' => trim($variant->getColor->name ?? ''),
                'size' => $variant->size,
                'volume' => $variant->volume,
                'unit' => $variant->unit,
                'quantity' => $variant->quantity,
                'additional_price' => $variant->additional_price,
                'image' => getPath($variant->image, 'assets/images/product'),
                'color_image' => getPath($variant->color_image, 'assets/images/product'),
                'symbol' => $variant->symbol,
                'code' => $variant->code,
            ];
        });

        $uniqueColors = $variantPayload
            ->filter(fn ($variant) => !empty(trim($variant['color'] ?? '')))
            ->map(fn ($variant) => [
                'color' => $variant['color'],
                'color_name' => $variant['color_name'],
                'color_image' => $variant['color_image'],
            ])
            ->unique('color')
            ->values()
            ->all();

        $productQuantity = ($product->stock_status === 'in_stock' || is_null($product->stock_status))
            ? (float) $product->quantity
            : 0;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'image' => $images,
            'rating' => $averageRating,
            'number_rating' => $product->reviews_count,
            'slug' => generateSlug($product->name, '-'),
            'description' => $product->description,
            'regular_price' => (float) $product->regular_price,
            'calculate_regular_price' => (float) $calculateRegularPrice,
            'discount_type' => $product->discount_type,
            'discount_price' => (float) $discountPrice,
            'category_id' => $product->category ?? '',
            'subcategory_id' => $product->subcategory ?? '',
            'category' => $categories,
            'subcategory' => $subcategories,
            'tax_type' => $product->tax_type,
            'tax_rate' => (float) $product->tax_rate,
            'quantity' => $productQuantity,
            'stock_status' => $product->stock_status,
            'pre_order_note' => $product->pre_order_note,
            'seo_keywords' => $product->seo_keywords,
            'weight' => $product->weight,
            'shipping_fee' => (float) $product->shipping_fee,
            'video_link' => $product->video_link ?? '',
            'SKU' => $product->SKU,
            'tags' => $product->tags,
            'product_link' => $product->product_link,
            'currency_id' => $product->currency_id,
            'symbol' => $product->symbol,
            'code' => $product->code,
            'position' => $product->position,
            'variant' => $variantPayload,
            'variant_color' => $uniqueColors,
            'brand_id' => $product->brand,
            'brand_name' => $product->getBrand->name ?? '',
            'supplier_id' => $product->supplier,
            'supplier_name' => $product->getSupplier->name ?? '',
            'layout' => $layout,
            'created_at' => $product->created_at ?? '',
        ];
    }

    public function productImages($product): array
    {
        $images = array_filter(array_merge(
            $this->csvValues($product->gallery_image),
            $this->csvValues($product->images)
        ));

        return array_values(array_map(
            fn ($image) => getPath($image, 'assets/images/product'),
            array_unique($images)
        ));
    }

    public function firstProductImage($product): ?string
    {
        $images = array_filter(array_merge(
            $this->csvValues($product->gallery_image),
            $this->csvValues($product->images)
        ));

        $image = reset($images);

        return $image ? getPath($image, 'assets/images/product') : null;
    }

    private function onlyFields(array $payload, ?array $fields): array
    {
        if (empty($fields)) {
            return $payload;
        }

        return array_intersect_key($payload, array_flip($fields));
    }

    private function csvValues($value): array
    {
        if (empty($value)) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', (string) $value)));
    }
}
