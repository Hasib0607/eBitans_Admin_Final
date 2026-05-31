<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\AcceptedPseProductRequest;
use App\Models\Pse\PseVisitorCounter;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use App\Models\StaticVisitor;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Carbon\Carbon;

class PseSuperAdminController extends Controller
{
    protected function getRequestedProduct($pseRequest)
    {
        return Product::select(
            'products.id',
            'products.name',
            'products.images AS productImage',
            'products.gallery_image',
            'products.store_id',
            'products.uid',
            'products.regular_price',
            'products.position',
            'products.barcode',
            'products.status',
            'products.pse_req_date',
            'products.created_at',
            'products.pse',
            'stores.expiry_date'
        )
            ->selectRaw('(SELECT name FROM categories WHERE id = products.category) AS main_category_name')
            ->selectRaw('(SELECT name FROM categories WHERE id = products.subcategory) AS subcategory_name')
            ->leftJoin('stores', 'products.uid', '=', 'stores.user_id')
            ->where('products.status', '!=', 'RecycleBin')
            ->orderBy('products.pse_req_date', 'desc')
            ->orderBy('products.created_at', 'desc')
            ->groupBy('products.id')
            ->where('products.pse', '=', $pseRequest);
    }

    /**
     * Checks the expiry date of a client's store.
     *
     * @param array $products The list of products to check
     * @return void
     */
    protected function checkClientExpiryStore(&$products)
    {
        foreach ($products as &$product) {
            $currentDate = Carbon::now();
            $expireDate = Carbon::parse($product->expiry_date);

            if ($currentDate->greaterThan($expireDate)) {
                $product->expiry_date = URL::to('/') . '/assets/status_icon/Wrong.png';
            } else {
                $product->expiry_date = URL::to('/') . '/assets/status_icon/Right.png';
            }

            $product->mainCategoryName = $product->mainCategory ? $product->mainCategory->name : 'N/A';
            $product->subCategoryName = $product->subCategory ? $product->subCategory->name : 'N/A';
        }
    }

    /**
     * Displays the index page with product and category data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = $this->getRequestedProduct(1)->paginate(20);

        $this->checkClientExpiryStore($products);

        $categories = Category::select('categories.*')
            ->whereNull('categories.store_id')
            ->whereNull('categories.customer_id')
            ->where('status', '!=', 'RecycleBin')
            ->get();

        return view('superadmin.pse.index', compact('products', 'categories'));
    }

    /**
     * Handle the request for accepting PSE products.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function pseAccepted(Request $request)
    {
        try {
            // Extract productId from the request
            $productId = $request->productId;
            // Initialize default URL
            $urls = "pse";
            if ($productId) {
                // Split the comma-separated productId string into an array
                $products = explode(',', $productId);

                foreach ($products as $product) {
                    // Find the product by its ID
                    $findProductId = Product::find($product);

                    $categoryIds = array_merge(['33829', '28619', '28622'], $request->categoryIds);

                    // Encode categoryIds into JSON format
                    $categories = json_encode($categoryIds, true);

                    // Check if categories are empty
                    if (empty($categories)) {
                        return response()->json([
                            'data' => $categories,
                            'status' => 'Category is not found.'
                        ]);
                    }

                    // Insert data into AcceptedPseProductRequest table
                    AcceptedPseProductRequest::create([
                        'product_id' => $product,
                        'category_id' => $categories,
                    ]);

                    // Update product information
                    $findProductId->pse = 2;
                    $findProductId->pse_status = "Accepted";
                    $findProductId->save();
                }

                // Return success response after accepting products
                return response()->json([
                    'status' => 'Accept these products successfully.'
                ]);
            } else {
                $today = Carbon::today();
                $tenDaysAfterExpiry = $today->copy()->addDays(10);

                $products = AcceptedPseProductRequest::leftJoin('pse_visitor_counters', 'pse_visitor_counters.product_id', '=', 'accepted_pse_product_requests.product_id')
                    ->leftJoin('products', 'products.id', '=', 'accepted_pse_product_requests.product_id')
                    ->leftJoin('stores', 'stores.id', '=', 'products.store_id') // Join with stores table
                    ->select(
                        'accepted_pse_product_requests.id',
                        'accepted_pse_product_requests.product_id',
                        'accepted_pse_product_requests.category_id',
                        'accepted_pse_product_requests.position as appr_position',
                        'accepted_pse_product_requests.status',
                        'products.name',
                        'products.images AS productImage',
                        'products.pse',
                        'products.SKU',
                        'products.uid',
                        'products.store_id',
                        'products.regular_price',
                        'products.barcode',
                        'stores.expiry_date', // Include expiry date from stores table
                        'stores.purchase_date'
                    )
                    ->selectRaw('(SELECT name FROM categories WHERE id = products.category) AS main_category_name')
                    ->selectRaw('(SELECT name FROM categories WHERE id = products.subcategory) AS subcategory_name')
                    ->selectRaw('COUNT(pse_visitor_counters.product_id) AS totalVisitor')
                    ->orderByDesc('totalVisitor')
                    ->orderBy('accepted_pse_product_requests.position', 'asc') // Order by position ascending
                    ->orderByRaw("CASE WHEN stores.purchase_date = '$today' THEN 0 ELSE 1 END") // Prioritize today's purchases
                    ->orderByRaw("CASE WHEN stores.purchase_date = 1 THEN 1 ELSE 0 END") // Prioritize active stores
                    ->orderBy('products.created_at', 'desc') // Show latest products first
                    ->where('products.pse', 2)
                    ->where('accepted_pse_product_requests.status', 1)
                    ->where(function ($query) use ($tenDaysAfterExpiry) {
                        $query->whereNull('stores.expiry_date')
                            ->orWhere('stores.expiry_date', '>', $tenDaysAfterExpiry);
                    })
                    ->groupBy('accepted_pse_product_requests.product_id')
                    ->paginate(20);

                // Update status of products after 10 days of store expiry
                $expiredStores = Store::where('expiry_date', '<', $tenDaysAfterExpiry)->pluck('id'); // Use 'id' instead of 'user_id'
                AcceptedPseProductRequest::whereIn('product_id', function ($query) use ($expiredStores) {
                    $query->select('id')
                        ->from('products')
                        ->whereIn('store_id', $expiredStores);
                })->update(['status' => 0]);

                $this->checkClientExpiryStore($products);

                $categories = Category::select('categories.*')
                    ->whereNull('categories.store_id')
                    ->whereNull('categories.customer_id')
                    ->where('status', '!=', 'RecycleBin')
                    ->get();

                // Return view with product and category data
                return view('superadmin.pse.accepted', compact('products', 'urls', 'categories'));
            }
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json([
                'data' => $e->getMessage(),
                'status' => '404'
            ]);
        }
    }

    /**
     * Updates the categories of accepted PSE products.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse JSON response indicating the status of the deletion process
     */
    public function pseAcceptedUpdate(Request $request)
    {
        try {
            // Extract productId from the request
            $productId = $request->productId;

            if ($productId) {
                // Split the comma-separated productId string into an array
                $products = explode(',', $productId);

                foreach ($products as $product) {
                    // Retrieve existing record from accepted_pse_product_requests table
                    $existingRecord = AcceptedPseProductRequest::where('product_id', $product)->first();
                    \Log::info('record ' . $existingRecord);
                    if ($existingRecord) {
                        \Log::info('exist record ' . $existingRecord);
                        // Update existing record
                        $existingRecord->category_id = json_encode($request->categoryIds);
                        $existingRecord->save();
                    } else {
                        \Log::info('new record ');
                        // Insert new record
                        AcceptedPseProductRequest::create([
                            'product_id' => $product,
                            'category_id' => json_encode($request->categoryIds)
                        ]);
                    }
                }

                // Return success response
                return response()->json([
                    'status' => 'Update pse products category successfully.'
                ]);
            }
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'error' => 'Internal server error.',
                'status' => 500
            ]);
        }
    }

    /**
     * Handles the request for rejecting PSE products.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function pseRejected(Request $request)
    {
        try {
            // Extract productId from the request
            $productId = $request->productId;
            // Initialize default URL
            $urls = "pse reject product";

            if ($productId) {
                // Split the comma-separated productId string into an array
                $products = explode(',', $productId);
                foreach ($products as $product) {
                    $product = Product::find($product);

                    // Check if categories are empty
                    if (empty($product)) {
                        return response()->json([
                            'data' => $product,
                            'status' => 'Product is not found.'
                        ]);
                    }

                    $product->pse = 3;
                    $product->update();
                }

                // Return success response after rejected products
                return response()->json([
                    'status' => 'Rejected these products successfully.'
                ]);
            } else {
                $products = $this->getRequestedProduct(3)->paginate(20);

                $this->checkClientExpiryStore($products);

                $categories = Category::select('categories.*')
                    ->whereNull('categories.store_id')
                    ->whereNull('categories.customer_id')
                    ->where('status', '!=', 'RecycleBin')
                    ->get();

                return view('superadmin.pse.index', compact('products', 'urls', 'categories'));
            }
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
                'status' => '404'
            ]);
        }
    }

    /**
     * Displays detailed information about a PSE product.
     *
     * @param int $id The ID of the PSE product
     * @return \Illuminate\View\View
     */
    public function pseView($id)
    {
        if (canSuperStaffAccess('pse')) {
            $urls = "pse";
            $product = Product::find($id);

            return view('superadmin.pse.view')->with('product', $product)->with('urls', $urls)->with('store_id', $id);
        }
    }

    /**
     * Handles the search functionality for super admins.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object
     * @return \Illuminate\View\View
     */
    public function superAdminListSearch(Request $request)
    {
        if (canSuperStaffAccess('pse')) {
            $query = $request->search;

            $results = Product::select('id', 'name', 'images', 'regular_price', 'barcode', 'status', 'created_at', 'pse')
                ->where('name', 'LIKE', '%' . $query . '%')
                ->where('pse', 1)
                ->orderBy('pse_req_date', 'desc')
                ->paginate(20);

            $data['products'] = $results;

            $data['categories'] = Category::select('categories.*')
                ->whereNull('categories.store_id')
                ->whereNull('categories.customer_id')
                ->where('status', '!=', 'RecycleBin')
                ->get();

            return view('superadmin.pse.search', $data);
        } else {
            return redirect()->view('superadmin.pse.view');
        }
    }

    /**
     * Handles the search functionality for super admins accepted pse product.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object
     * @return \Illuminate\View\View
     */
    public function superAdminAcceptedListSearch(Request $request)
    {
        try {
            // Check if the user is a super admin, if not, redirect to 404 page
            if (!canSuperStaffAccess('pse')) {
                return redirect()->route('404');
            }

            $query = $request->search;

            $today = Carbon::today();
            $tenDaysAfterExpiry = $today->copy()->addDays(10);

            $products = AcceptedPseProductRequest::leftJoin('products', 'products.id', '=', 'accepted_pse_product_requests.product_id')
                ->leftJoin('stores', 'stores.id', '=', 'products.store_id') // Join with stores table
                ->select(
                    'accepted_pse_product_requests.id',
                    'accepted_pse_product_requests.product_id',
                    'accepted_pse_product_requests.category_id',
                    'accepted_pse_product_requests.position as appr_position',
                    'accepted_pse_product_requests.status',
                    'products.name',
                    'products.images AS productImage',
                    'products.pse',
                    'products.SKU',
                    'products.uid',
                    'products.store_id',
                    'products.regular_price',
                    'products.barcode',
                    'stores.expiry_date', // Include expiry date from stores table
                    'stores.purchase_date'
                )
                ->selectRaw('(SELECT name FROM categories WHERE id = products.category) AS main_category_name')
                ->selectRaw('(SELECT name FROM categories WHERE id = products.subcategory) AS subcategory_name')
                ->orderBy('products.created_at', 'desc') // Show latest products first
                ->orderBy('accepted_pse_product_requests.position', 'asc') // Order by position ascending
                ->orderByRaw("CASE WHEN stores.purchase_date = '$today' THEN 0 ELSE 1 END") // Prioritize today's purchases
                ->orderByRaw("CASE WHEN stores.purchase_date = 1 THEN 1 ELSE 0 END") // Prioritize active stores
                ->where('products.pse', 2)
                ->where('accepted_pse_product_requests.status', 1)
                ->where('products.name', 'LIKE', '%' . $query . '%')
                ->where(function ($query) use ($tenDaysAfterExpiry) {
                    $query->whereNull('stores.expiry_date')
                        ->orWhere('stores.expiry_date', '>', $tenDaysAfterExpiry);
                })
                ->groupBy('accepted_pse_product_requests.product_id')
                ->paginate(20);

            // Update status of products after 10 days of store expiry
            $expiredStores = Store::where('expiry_date', '<', $tenDaysAfterExpiry)->pluck('id'); // Use 'id' instead of 'user_id'
            AcceptedPseProductRequest::whereIn('product_id', function ($query) use ($expiredStores) {
                $query->select('id')
                    ->from('products')
                    ->whereIn('store_id', $expiredStores);
            })->update(['status' => 0]);

            $this->checkClientExpiryStore($products);

            $categories = Category::select('categories.*')
                ->whereNull('categories.store_id')
                ->whereNull('categories.customer_id')
                ->where('status', '!=', 'RecycleBin')
                ->get();

            return view('superadmin.pse.accepted_search', compact('products', 'categories'));
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json([
                'data' => $e->getMessage(),
                'status' => '404'
            ]);
        }
    }

    /**
     * Handles the request to delete PSE products.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse JSON response indicating the status of the deletion process
     */
    public function pseDeleteProduct(Request $request)
    {
        try {
            // Extract productId from the request
            $productId = $request->productId;

            // Initialize default URL
            $urls = "pse delete product";

            // Find the accepted product request by its ID
            $acceptedProduct = AcceptedPseProductRequest::find($productId);

            // Check if the pse product not exists
            if (is_null($acceptedProduct)) {
                // Return JSON response indicating product not found
                return response()->json([
                    'status' => 'Product Not Found'
                ]);
            }

            // Find the corresponding product
            $product = Product::find($acceptedProduct->product_id);

            // Check if the product exists
            if (!is_null($product)) {
                // Update product's PSE status
                $product->pse = 3;
                $product->update();
            }

            // Delete the accepted product request
            $acceptedProduct->delete();

            // Return JSON response indicating successful deletion
            return response()->json([
                'status' => 'Product deleted successfully.'
            ]);
        } catch (\Exception $e) {
            // Return JSON response with error message and status code
            return response()->json([
                'data' => $e->getMessage(),
                'status' => '500' // Internal Server Error
            ]);
        }
    }

    /**
     * Updates the position of a PSE product.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function pseProductPosition(Request $request)
    {
        $value = $request->value;
        $id = $request->id;

        if (is_null($value)) {
            return response()->json([
                'data' => $value,
                'status' => 'There is a problem with the value'
            ]);
        }

        if (empty($id)) {
            return response()->json([
                'data' => $id,
                'status' => 'ID not found'
            ]);
        }

        $findProductOrNot = AcceptedPseProductRequest::where('id', $id)->first();

        if (empty($findProductOrNot)) {
            return response()->json([
                'data' => $findProductOrNot,
                'status' => 'Product not found.'
            ]);
        }

        $findProductOrNot->position = $value;
        $findProductOrNot->save();

        return response()->json([
            'data' => $findProductOrNot,
            'status' => 'Product Position Updated Successfully.'
        ]);
    }

    /**
     * Updates the status of a PSE product.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse
     */
    public function pseStatus(Request $request)
    {
        $value = $request->value;
        $id = $request->id;

        if (empty($id)) {
            return response()->json([
                'data' => $id,
                'status' => 'ID not found'
            ]);
        }

        if ($value != 'on') {
            return response()->json([
                'data' => $value,
                'status' => "Value Not Found."
            ]);
        }

        $findProductOrNot = AcceptedPseProductRequest::where('id', $id)->first();

        if (empty($findProductOrNot)) {
            return response()->json([
                'data' => $findProductOrNot,
                'status' => 'Product not found.'
            ]);
        }

        if ($findProductOrNot->status == 1) {
            $findProductOrNot->status = false;
            $findProductOrNot->save();

            return response()->json([
                'data' => $findProductOrNot,
                'status' => 'Product Inactive Updated Successfully.'
            ]);
        }

        if ($findProductOrNot->status == 0) {
            $findProductOrNot->status = true;
            $findProductOrNot->save();

            return response()->json([
                'data' => $findProductOrNot,
                'status' => 'Product Active Updated Successfully.'
            ]);
        }

        return response()->json([
            'data' => 404,
            'status' => 'Something wants wrong.'
        ]);
    }

    public function pseVisitor()
    {
        $staticVisitor = StaticVisitor::first();
        $visitors = PseVisitorCounter::select(
            'pse_visitor_counters.id as visitor_id',
            'pse_visitor_counters.appr_id',
            'stores.id',
            'stores.name',
            'stores.url',
            'headersettings.logo'
        )
            ->leftJoin('stores', 'stores.id', '=', 'pse_visitor_counters.store_id')
            ->leftJoin('headersettings', 'headersettings.store_id', '=', 'pse_visitor_counters.store_id')
            ->selectRaw('COUNT(pse_visitor_counters.store_id) AS totalVisitor')
            ->groupBy('pse_visitor_counters.store_id')
            ->orderByDesc('totalVisitor')
            ->paginate(20);

        return view('superadmin.pse.visitor', compact('visitors', 'staticVisitor'));
    }

    public function pseStoreVisitor($id)
    {
        $visitors = PseVisitorCounter::select(
            'pse_visitor_counters.id',
            'pse_visitor_counters.appr_id',
            'pse_visitor_counters.product_id',
            'products.images AS productImage',
            'products.name',
            'stores.name AS store_name',
            'stores.url'
        )
            ->leftJoin('products', 'products.id', '=', 'pse_visitor_counters.product_id')
            ->leftJoin('stores', 'stores.id', '=', 'pse_visitor_counters.store_id')
            ->selectRaw('COUNT(pse_visitor_counters.product_id) AS totalVisitor')
            ->where('pse_visitor_counters.store_id', $id)
            ->groupBy('pse_visitor_counters.product_id')
            ->orderBy('totalVisitor', 'DESC')
            ->paginate(20);
        $allVisitors = PseVisitorCounter::all();

        return view('superadmin.pse.visitor_details', compact('visitors', 'allVisitors'));
    }

    public function pseStaticVisitor(Request $request)
    {
        $value = (int)$request->value;

        if (is_null($value)) {
            return response()->json([
                'data' => $value,
                'status' => 'There is a problem with the value'
            ]);
        }
        $storeVisitor = StaticVisitor::firstOrNew();
        $storeVisitor->visitors = $value;
        $storeVisitor->save();

        return response()->json([
            'data' => "Visitor Store Successfully",
            'status' => 'Visitor Added Successfully.'
        ]);
    }

    public function superVisitorListSearch(Request $request)
    {
        try {
            // Check if the user is a super admin, if not, redirect to 404 page
            if (!canSuperStaffAccess('pse')) {
                return redirect()->route('404');
            }

            $query = $request->search;
            $data['staticVisitor'] = StaticVisitor::first();
            $data['visitors'] = PseVisitorCounter::select(
                'pse_visitor_counters.appr_id',
                'stores.id',
                'stores.name',
                'stores.url',
                'headersettings.logo'
            )
                ->leftJoin('stores', 'stores.id', '=', 'pse_visitor_counters.store_id')
                ->leftJoin('headersettings', 'headersettings.store_id', '=', 'pse_visitor_counters.store_id')
                ->where('stores.name', 'LIKE', '%' . $query . '%')
                ->selectRaw('COUNT(pse_visitor_counters.store_id) AS totalVisitor')
                ->groupBy('pse_visitor_counters.store_id')
                ->orderByDesc('totalVisitor')
                ->paginate(20);

            return view('superadmin.pse.visitor_search', $data);
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json([
                'data' => $e->getMessage(),
                'status' => '404'
            ]);
        }
    }
}
