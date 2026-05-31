<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\v2\SubdomainController;
use App\Http\Resources\LayoutDesignResource;
use App\Http\Traits\ActivityLogTraits;
use App\Models\AcceptedPseProductRequest;
use App\Models\BuyModulus;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Color;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Headersetting;
use App\Models\LayoutDesign;
use App\Models\Modulus;
use App\Models\ModulusPayment;
use App\Models\Plan;
use App\Models\Posplan;
use App\Models\Product;
use App\Models\ProductLayout;
use App\Models\Pse\PseVisitorCounter;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Store;
use App\Models\TempImage;
use App\Models\Veriant;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Image;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use WebPConvert\Convert\Exceptions\ConversionFailedException;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth')->except(["generateFacebookCatalogFeedURL"]);
    }

    public function index1($subdomain)
    {
        if ($subdomain != "blog") {
            return redirect('/payment');
        }
    }

    /**
     *
     * Display file import view
     *
     * @param Request $request
     * @return Application|Factory|View
     */


    /**
     *
     * Product import and save to the database
     *
     * @param Request $request
     * @return RedirectResponse
     */

    /**
     *
     * Product export
     *
     * @param Request $request
     * @return BinaryFileResponse
     */

    public function exportSelectedOrFilteredExcel(Request $request)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $query = Product::where('store_id', $store_id)
            ->where('status', '!=', 'RecycleBin');

        // Selected rows export
        if ($request->filled('ids')) {
            $ids = array_filter(explode(',', $request->ids));
            $query->whereIn('id', $ids);
        } else {

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('SKU', 'like', "%$search%");
                });
            }

            // Date filter
            if ($request->filled('formdate') && $request->filled('enddate')) {
                $query->whereBetween('created_at', [$request->formdate, $request->enddate]);
            }
        }

        $products = $query->orderBy('id', 'desc')->get();

        $data = [];
        $sl = 1;

        foreach ($products as $product) {
            $data[] = [
                $sl++,
                $product->name,
                $product->SKU,
                $product->description,
                $product->regular_price,
                $product->quantity,
                $product->status,
                optional($product->created_at)->format('d-m-Y')
            ];
        }

        return Excel::download(
    new class ($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {

            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [
                'SL',
                'Name',
                'SKU',
                'Description',
                'Price',
                'Quantity',
                'Status',
                'Created At'
                ];
            }
            },
            'products.xlsx'
        );
    }

    /**
     *
     *  Get role from session and set permission in session for the role
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function pro(Request $request)
    {
        Session::put('role', $request->id);
        $role = Role::where('id', Session::get('role'))->first();
        $permission = explode(',', $role->permission);
        Session::put('permission', $permission);
        $data = 1;

        return response()->json($data);
    }

    /**
     *
     * Display product list view
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        if ((Auth::user()->type == 'staff') && (!canAccess('product') && !canAccess('category') && !canAccess('subcategory') && !canAccess('brand') && !canAccess('attribute') && !canAccess('supplier'))) {
            return redirect()->route('staff.dashboard');
        }

        $urls = "product";

        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $customer = $userData['customer'];

        $limit = 0;
        $store = Store::with('current_currency')->find($store_id);
        $current_currency = $store->current_currency;
        if ($store->plan_id != 'NULL') {
            $plan = Plan::find($store->plan_id);
            if ($store->expiry_date >= Carbon::now()) {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date >= Carbon::now()) {
                        $posplan = Posplan::find($store->pos_plan_id);
                        if ($plan->product > $posplan->product) {
                            $limit = $plan->product ?? '';
                        } else {
                            $limit = $posplan->product ?? '';
                        }
                    } else {
                        $limit = $plan->product ?? '';
                    }
                } else {
                    $limit = $plan->product ?? '';
                }
            } else {
                $limit = $limit;
            }
        } else {
            if (isset($store->pos_plan_id)) {
                if ($store->pos_plan_expiry_date >= Carbon::now()) {
                    $posplan = Posplan::find($store->pos_plan_id);
                    $limit = $posplan->product ?? '';
                } else {
                    $limit = $limit;
                }
            } else {
                $limit = $limit;
            }
        }

        if (isset($store->digital_plan_id)) {
            if ($store->plan_id != 'NULL') {
                $plan = Plan::find($store->plan_id);
                if ($store->expiry_date >= Carbon::now()) {
                    if (isset($store->pos_plan_id)) {
                        if ($store->pos_plan_expiry_date >= Carbon::now()) {
                            $posplan = Posplan::find($store->pos_plan_id);
                            if ($plan->product > $posplan->product) {
                                $limit = $plan->product ?? '';
                            } else {
                                $limit = $posplan->product ?? '';
                            }
                        } else {
                            $limit = $plan->product ?? '';
                        }
                    } else {
                        $limit = $plan->product ?? '';
                    }
                } else {
                    $limit = $limit;
                }
            } else {
                if ($store->digital_plan_end_date >= Carbon::now()) {
                    $limit = 10000000;
                } else {
                    $limit = $limit;
                }
            }
        }

        //        $productQuery = Product::select("products.*", 'currencies.symbol')
//            ->join('currencies', 'products.currency_id', '=', 'currencies.id')
//            ->where('products.store_id', $customer->active_store)
//            ->where('products.status', '!=', 'RecycleBin')
//            ->orderBy('position', 'ASC')
//            ->when('products.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
//                function ($query) use ($current_currency) {
//                    $query->addSelect([
//                        DB::raw("ROUND(products.regular_price / currencies.rate * " . $current_currency->rate . " , 2) as regular_price"),
//                        DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / currencies.rate * " . $current_currency->rate . " , 2) ELSE products.tax_type END as promotional_price"),
//                        DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / currencies.rate * {$current_currency->rate}, 2) ELSE products.tax_type END as tax_rate"),
//                        DB::raw("'{$current_currency->symbol}' as symbol")
//                    ]);
//                })
//            ->when('products.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
//                function ($query) use ($store, $current_currency) {
//                    $query->addSelect([
//                        DB::raw("ROUND(products.regular_price / {$store->currency_rate}, 2) as regular_price"),
//                        DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / {$store->currency_rate}, 2) ELSE products.tax_type END as promotional_price"),
//                        DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / {$store->currency_rate}, 2) ELSE products.tax_type END as tax_rate"),
//                        DB::raw("'{$current_currency->symbol}' as symbol")
//                    ]);
//                })
//            ->take($limit)
//            ->latest();
//        $allProduct = $productQuery->get();
//        $product = $productQuery->paginate(20);


        $needsConversion = $store->currency != $current_currency->id;
        $productQuery = Product::select("products.*", 'currencies.symbol')
            ->join('currencies', 'products.currency_id', '=', 'currencies.id')
            ->where('products.store_id', $customer->active_store)
            ->where('products.status', '!=', 'RecycleBin')
            ->orderBy('position', 'ASC');

        if ($needsConversion) {
            if ($current_currency->customize_rate_status === 0) {
                $rate = $current_currency->rate;
                $symbol = $current_currency->symbol;

                $productQuery->addSelect([
                    DB::raw("ROUND(products.regular_price / currencies.rate * {$rate} , 2) as regular_price"),
                    DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / currencies.rate * {$rate} , 2) ELSE products.tax_type END as promotional_price"),
                    DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / currencies.rate * {$rate}, 2) ELSE products.tax_type END as tax_rate"),
                    DB::raw("'{$symbol}' as symbol")
                ]);
            } else {
                $rate = $store->currency_rate;
                $symbol = $current_currency->symbol;

                $productQuery->addSelect([
                    DB::raw("ROUND(products.regular_price / {$rate}, 2) as regular_price"),
                    DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / {$rate}, 2) ELSE products.tax_type END as promotional_price"),
                    DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / {$rate}, 2) ELSE products.tax_type END as tax_rate"),
                    DB::raw("'{$symbol}' as symbol")
                ]);
            }
        } else {
            // No conversion, just select symbol from currencies table
            $productQuery->addSelect('currencies.symbol');
        }

        $allProductQuery = clone $productQuery->latest();
        $allProduct = $allProductQuery->take($limit)->get();
        $product = $productQuery->paginate(20);

        // Store toptools count
        topToolsCount("Product", "box.png", "/products");

        $activity = " Browse Product List";
        $this->saveactivity($activity);

        return view('admin.product.index', [
            'currency' => $current_currency,
            'allProduct' => $allProduct,
            'products' => $product,
            'urls' => $urls,
            'limit' => $limit,
            'store_id' => $store_id,
            'tProduct' => $product->total(),
        ]);

    }

    public function getCustomLayoutDesign(Request $request)
    {
        return view('admin.product.share.layout-custom-design', [
            'product' => $request->product,
            'customizable' => $request->customizable,
            'title' => $request->title,
            'type' => $request->type,
            'index' => $request->index
        ]);
    }

    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function layoutProduct(Request $request)
    {
        if ((Auth::user()->type == 'staff') && (!canAccess('product') && !canAccess('category') && !canAccess('subcategory') && !canAccess('brand') && !canAccess('attribute') && !canAccess('supplier'))) {
            return redirect()->route('staff.dashboard');
        }

        $urls = "product";

        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $customer = $userData['customer'];

        $limit = 0;
        $store = Store::with('current_currency')->find($store_id);
        $current_currency = $store->current_currency;

        $modulusPayments = ModulusPayment::where("modulus_id", 121)->where("store_id", $store_id)->whereNotNull("status")->latest()->first();

        if (isset($modulusPayments->total_product) && !empty($modulusPayments->total_product)) {
            $limit = $modulusPayments->total_product ?? 0;
        }

        $from = $request->formdate;
        $to = $request->enddate;
        $search = $request->search;

        $productQuery = Product::convertCurrency($store->id)
            ->select('products.*') // Select only product fields
            ->where('products.store_id', $customer->active_store)
            ->where('products.status', '!=', 'RecycleBin')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('product_layouts as layout')
                    ->whereRaw('layout.product_id = products.id'); // Ensures product exists in layout
            })
            ->distinct() // Ensure unique products
            ->orderBy('products.created_at', 'desc'); // Use pagination

        $tProduct = $productQuery->get()->count();

        if ($from && !$to) {
            $productQuery->where('products.created_at', '>=', $from);
        } elseif (!$from && $to) {
            $productQuery->where('products.created_at', '<=', $to);
        } elseif ($from && $to) {
            if ($from == $to) {
                $productQuery->whereDate('products.created_at', $from);
            } else {
                $productQuery->whereBetween('products.created_at', [$from, $to]);
            }
        }

        if (!empty($search)) {
            $productQuery->where(function ($query) use ($search) {
                $query->where('products.name', 'like', "%{$search}%")
                    ->orWhere('products.description', 'like', "%{$search}%")
                    ->orWhere('products.sku', 'like', "%{$search}%");
            });
        }

        $allProduct = $productQuery->get();
        $product = $productQuery->paginate(20);

        // Store toptools count
        topToolsCount("Product", "box.png", "/products");

        $activity = " Browse Product List";
        $this->saveactivity($activity);
        $productCount = Product::where('creator', Auth::user()->id)->where('status', '!=', 'RecycleBin')->get();

        return view('admin.product.layout.index', [
            'currency' => $current_currency,
            'allProduct' => $allProduct,
            'products' => $product,
            'urls' => $urls,
            'limit' => $limit,
            'productcount' => $productCount,
            'store_id' => $store_id,
            'tProduct' => $tProduct,
        ]);

    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse|void
     */
    public function layoutEdit($id)
    {
        if (canAccess('product')) {
            $urls = "product";

            // Get user data
            $userData = getUserData();
            $store_id = $userData['store_id'];

            $name = "Product";
            $image = "box.png";
            $url = "/products";

            // Save top tools count
            topToolsCount($name, $image, $url);

            $moduleIsNull = ModulusStatus($store_id, 107);
            $customizable = ModulusStatus($store_id, 121);
            $activity = " Access Edit Product Page";
            $this->saveactivity($activity);
            $store = Store::with('current_currency')->find($store_id);
            $current_currency = $store->current_currency;
            $product = Product::with(['layout'])
                ->convertCurrency($store_id)
                ->where('products.id', $id)
                ->first();

            // Check product if not found then redirect
            if (empty($product)) {
                Session::flash("error", "Product not found!");
                return redirect()->route("admin.allproducts");
            }

            $final_product = new LayoutDesignResource($product);
            $final_product_json = $final_product->toJson();
            $final_product = json_decode($final_product_json, true);

            return view('admin.product.layout.edit')
                ->with('current_currency', $current_currency)
                ->with('product', $final_product)
                ->with('urls', $urls)
                ->with('store', $store)
                ->with('customizable', $customizable)
                ->with(['store_id' => $store_id, 'moduleIsNull' => $moduleIsNull]);
        }
    }


    public function layoutCreate()
    {
        if (canAccess('product')) {
            $urls = "product";

            // Get user data
            $userData = getUserData();
            $store_id = $userData['store_id'];

            // Store toptools count
            topToolsCount("Product", "box.png", "/products");

            $moduleIsNull = ModulusStatus($store_id, 107);
            $customizable = ModulusStatus($store_id, 121);

            $activity = " Access Create Product Page";
            $this->saveactivity($activity);

            $store = Store::with('current_currency')->find($store_id);
            $current_currency = $store->current_currency;

            $currency = Currency::join('stores', 'stores.currency', 'currencies.id')->where('stores.id', $store_id)->first('code');
            // dd($store_id);
            return view('admin.product.layout.create')
                ->with('urls', $urls)
                ->with('currency', $currency)
                ->with('customizable', $customizable)
                ->with('store', $store)
                ->with('current_currency', $current_currency)
                ->with(['store_id' => $store_id, 'moduleIsNull' => $moduleIsNull]);
        } else {
            return redirect('/');
        }
    }


    public function layoutItemRemove($id)
    {
        $productLayout = ProductLayout::where("id", $id)->first();
        if (!isset($productLayout)) {
            return redirect()->back()->with("error", "Record not found!");
        }

        $LayoutDesign = LayoutDesign::where("id", $productLayout->layout_design_id ?? NULL)->first();
        if (isset($LayoutDesign)) {
            $LayoutDesign->delete();
        }

        $productLayout->delete();

        return redirect()->back()->with("success", "Remove Item Successfully!");
    }

    /**
     *
     * Product search by store ID
     *
     * @return Application|Factory|View
     */
    public function productKhujo()
    {
        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

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
            ->where('pse_visitor_counters.store_id', $store_id)
            ->whereDate('pse_visitor_counters.created_at', now()->toDateString())
            ->groupBy('pse_visitor_counters.product_id')
            ->orderBy('totalVisitor', 'DESC')
            ->paginate(20);

        return view('admin.product_khujo.index', compact('visitors'));
    }

    /**
     * Display store weekly product report
     *
     * @return Application|Factory|View
     */
    public function weeklyReport()
    {
        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $startOfWeek = now()->startOfWeek(); // Start of current week
        $endOfWeek = now()->endOfWeek(); // End of current week

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
            ->where('pse_visitor_counters.store_id', $store_id)
            ->whereBetween(
                'pse_visitor_counters.created_at',
                [$startOfWeek, $endOfWeek]
            ) // Adjusted to select data for the last 7 days
            ->groupBy('pse_visitor_counters.product_id')
            ->orderBy('totalVisitor', 'DESC')
            ->paginate(20);

        return view('admin.product_khujo.weely', compact('visitors'));
    }

    /**
     *
     * Display store monthly product report
     *
     * @return Application|Factory|View
     */
    public function monthlyReport()
    {
        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

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
            ->where('pse_visitor_counters.store_id', $store_id)
            ->whereYear('pse_visitor_counters.created_at', now()->year)
            ->whereMonth('pse_visitor_counters.created_at', now()->month)
            ->groupBy('pse_visitor_counters.product_id')
            ->orderBy('totalVisitor', 'DESC')
            ->paginate(20);

        return view('admin.product_khujo.monthly', compact('visitors'));
    }

    /**
     *
     * Display store all visitor
     *
     * @return Application|Factory|View
     */
    public function allVisitor()
    {
        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

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
            ->where('pse_visitor_counters.store_id', $store_id)
            ->groupBy('pse_visitor_counters.product_id')
            ->orderBy('totalVisitor', 'DESC')
            ->paginate(20);
        return view('admin.product_khujo.visitor', compact('visitors'));
    }

    /**
     *
     * Product search
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function productSearch(Request $request)
    {
        $store_id = $request->store_id ?? "";
        $search = $request->search ?? "";
        $store = Store::with('current_currency')->find($store_id);
        $current_currency = $store->current_currency;
        $products = Product::select("products.*", 'currencies.symbol')
            ->join('currencies', 'products.currency_id', '=', 'currencies.id')
            ->where('products.store_id', $store_id)
            ->where(function ($query) use ($search) {
                $query->where('products.name', 'like', '%' . $search . '%')->orWhere('products.SKU', 'like', '%' . $search . '%');
            })
            ->where('products.status', '!=', 'RecycleBin')
            ->when(
                'products.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                function ($query) use ($current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(products.regular_price / currencies.rate * " . $current_currency->rate . " , 2) as regular_price"),
                        DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / currencies.rate * " . $current_currency->rate . " , 2) ELSE products.tax_type END as promotional_price"),
                        DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / currencies.rate * {$current_currency->rate}, 2) ELSE products.tax_type END as tax_rate"),
                        DB::raw("'{$current_currency->symbol}' as symbol")
                    ]);
                }
            )
            ->when(
                'products.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                function ($query) use ($store, $current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(products.regular_price / {$store->currency_rate}, 2) as regular_price"),
                        DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / {$store->currency_rate}, 2) ELSE products.tax_type END as promotional_price"),
                        DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / {$store->currency_rate}, 2) ELSE products.tax_type END as tax_rate"),
                        DB::raw("'{$current_currency->symbol}' as symbol")
                    ]);
                }
            )
            ->where('products.status', 'active')
            ->orderBy('products.position', 'DESC')
            ->latest();

        $searchResult = $products->get();

        return view('admin.product.searchProduct', [
            'products' => $searchResult,
            'store_id' => $request->store_id,
        ]);
    }

    /**
     *
     * Display product by product ID
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function viewproduct($id)
    {
        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $store = Store::with('current_currency')->find($store_id);
        $current_currency = $store->current_currency;

        $product = Product::select("products.*", 'currencies.symbol', 'currencies.id as currency')
            ->join('currencies', 'products.currency_id', '=', 'currencies.id')
            ->where('products.store_id', $store_id)
            ->where('products.id', $id)
            ->when(
                'products.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                function ($query) use ($current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(products.regular_price / currencies.rate * " . $current_currency->rate . " , 2) as regular_price"),
                        DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / currencies.rate * " . $current_currency->rate . " , 2) ELSE products.tax_type END as promotional_price"),
                        DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / currencies.rate * {$current_currency->rate}, 2) ELSE products.tax_type END as tax_rate"),
                        DB::raw("'{$current_currency->symbol}' as symbol")
                    ]);
                }
            )
            ->when(
                'products.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                function ($query) use ($store, $current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(products.regular_price / {$store->currency_rate}, 2) as regular_price"),
                        DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / {$store->currency_rate}, 2) ELSE products.tax_type END as promotional_price"),
                        DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / {$store->currency_rate}, 2) ELSE products.tax_type END as tax_rate"),
                        DB::raw("'{$current_currency->symbol}' as symbol")
                    ]);
                }
            )
            ->first();

        if (empty($product)) {
            return redirect()->back();
        }

        $url1 = "inventory";
        return view('admin.product.view', compact('product', 'url1'));
    }

    /**
     *
     * Display product barcode
     *
     * @return Application|Factory|View
     */
    public function printbarcode()
    {
        $urls = "product";

        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $products = Product::where('store_id', $store_id)->where('barcode', '!=', null)->get();

        return view('admin.product.barcode', compact('urls', 'products'));
    }

    /**
     *
     * Display product data from barcode
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function selectedBarcode(Request $request)
    {
        if ($request->barCodeId == '') {
            Session::flash('message', 'Please Select Product');
            return redirect()->back();
        }

        $urls = "product";

        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $ids = explode(',', $request->barCodeId);
        if (isset($ids) && count($ids) > 0) {
            foreach ($ids as $key => $id) {
                $products[$key] = Product::where('store_id', $store_id)->where('id', $id)->where(
                    'barcode',
                    '!=',
                    null
                )->first();
            }
        }

        $pdf = PDF::loadView('admin.product.viewbar', compact('urls', 'products'));

        return $pdf->stream();

        return view('admin.product.viewbar', compact('urls', 'products'));
    }

    /**
     *
     * Update product variant
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->color = $request->color;
        $veriant->size = $request->size;
        $veriant->quantity = $request->quantity;
        $veriant->additional_price = $request->additional_price;
        $veriant->save();
        $data = $veriant->convertCurrency();

        $activity = " Update Attribute";
        $this->saveactivity($activity);

        return response()->json($data);
    }

    /**
     * Product save
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function save(Request $request)
    {
        DB::beginTransaction();

        try {
            // Get user data
            $userData = getUserData();
            $user = $userData['user_id'];
            $store = $userData['store'];
            $store_id = $userData['store_id'];
            $customer_id = $userData['customer_id'];

            $limit = 0;

            // Get store plan limit
            if ($store->plan_id != 'NULL') {
                $plan = Plan::find($store->plan_id);
                if ($store->expiry_date >= Carbon::now()) {
                    if (isset($store->pos_plan_id)) {
                        if ($store->pos_plan_expiry_date >= Carbon::now()) {
                            $posplan = Posplan::find($store->pos_plan_id);
                            if ($plan->product > $posplan->product) {
                                $limit = $plan->product;
                            } else {
                                $limit = $posplan->product;
                            }
                        } else {
                            $limit = $plan->product;
                        }
                    } else {
                        $limit = $plan->product;
                    }
                }
            } else {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date >= Carbon::now()) {
                        $posplan = Posplan::find($store->pos_plan_id);
                        $limit = $posplan->product;
                    }
                }
            }

            // Get store digital plan limit
            if (isset($store->digital_plan_id)) {
                if ($store->plan_id != 'NULL') {
                    $plan = Plan::find($store->plan_id);
                    if ($store->expiry_date >= Carbon::now()) {
                        if (isset($store->pos_plan_id)) {
                            if ($store->pos_plan_expiry_date >= Carbon::now()) {
                                $posplan = Posplan::find($store->pos_plan_id);
                                if ($plan->product > $posplan->product) {
                                    $limit = $plan->product;
                                } else {
                                    $limit = $posplan->product;
                                }
                            } else {
                                $limit = $plan->product;
                            }
                        } else {
                            $limit = $plan->product;
                        }
                    } else {
                        $limit = $limit;
                    }
                } else {
                    if ($store->digital_plan_end_date >= Carbon::now()) {
                        $limit = 50;
                    } else {
                        $limit = $limit;
                    }
                }
            }

            // Get store plan info data and plan limit
            $plan = Plan::find($store->plan_id);
            if ($store->expiry_date >= Carbon::now()) {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date >= Carbon::now()) {
                        $posplan = Posplan::find($store->pos_plan_id);
                        if ($plan->product > $posplan->product) {
                            $limit = $plan->product;
                        } else {
                            $limit = $posplan->product;
                        }
                    } else {
                        $limit = $plan->product;
                    }
                } else {
                    $limit = $plan->product;
                }
            } else {
                $limit = 0;
            }

            // Get store active product
            $proCount = Product::where('store_id', $store_id)->where('status', 'active')->count();

            // Get store product limit
            if ($limit <= $proCount) {
                Session::flash('error', 'Please update your package to add more products.!');
                return back();
            }

            // Get store all product
            $allproduct = Product::where('store_id', $store_id)->where('status', '!=', 'RecycleBin')->count();
            if ($allproduct > $limit) {
                Session::flash('error', 'Product Add Limit Reacted');
                return back()->withInput();
            }

            if (isset($request->page_type) && $request->page_type == "landing") {
                $limit = 0;

                $modulusPayments = ModulusPayment::where("modulus_id", 121)->where("store_id", $store_id)->whereNotNull("status")->latest()->first();

                if (isset($modulusPayments->total_product) && !empty($modulusPayments->total_product)) {
                    $limit = $modulusPayments->total_product ?? 0;
                }

                $productQuery = Product::convertCurrency($store_id)
                    ->select('products.*') // Select only product fields
                    ->where('products.store_id', $store_id)
                    ->where('products.status', '!=', 'RecycleBin')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('product_layouts as layout')
                            ->whereRaw('layout.product_id = products.id'); // Ensures product exists in layout
                    })
                    ->distinct() // Ensure unique products
                    ->orderBy('products.created_at', 'desc'); // Use pagination

                $tProduct = $productQuery->get()->count();

                if ($tProduct > $limit) {
                    Session::flash('error', 'Product Add Limit Reached');
                    return back()->withInput();
                }
            }

            // Input validation rules
            $rules = array(
                'product_name' => 'required|string',
                'description' => 'required|string',
                'regular_price' => 'required|numeric|min:0',
                'discount_type' => 'required',
                //                'quantity' => 'required',
                'image' => 'required_without:gallery_image',
                'gallery_image' => 'required_without:image',
                'category' => 'required',
                // 'SKU' => 'required'
            );

            // Input vaidation message
            $errorMessage = array(
                'product_name.required' => 'Product name is required.',
                'description.required' => 'Description is required.',
                'regular_price.required' => 'Regular Price is required.',
                'regular_price.numeric' => 'Regular Price must be a valid price.',
                'regular_price.min' => 'Regular Price must be greater than 0.',
                'discount_type.required' => 'Discount Type is required.',
                //                'quantity.required' => 'Quantity is required.',
                'image.required_without' => 'Media is required.',
                'gallery_image.required_without' => 'Media is required.',
                'category.required' => 'Category is required.',
            );

            // Check SKU if not get from input then create

            if (empty($request->SKU)) {
                $sku = 'SKU' . mt_rand(100, 999) . time();
            } else {
                $sku = $request->SKU;
            }

            // Validated all input
            $validator = Validator::make($request->all(), $rules, $errorMessage);

            // Check validation fails or pass
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $qtyOrVolume = $request->qtyOrVolume ?? 0;
                if (isset($qtyOrVolume) && $qtyOrVolume == 0) {
                    if (is_null($request->quantity) || empty($request->quantity)) {
                        $validator->getMessageBag()->add('quantity', 'Quantity is required.');

                        return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                    }
                } else {
                    if (is_null($request->volume) || empty($request->volume)) {
                        $validator->getMessageBag()->add('volume', 'Volume is required.');

                        return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                    }

                    if (is_null($request->productUnit) || empty($request->productUnit)) {
                        $validator->getMessageBag()->add('productUnit', 'Unit is required.');

                        return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                    }
                }

                // Image count validation
                $imageError = $this->productImageCountValidation($request->file('image'), $store_id);
                if ($imageError) {
                    $msg = "You cannot upload more than 5 images!";
                    Session::flash('error', $msg);
                    Session::flash('error_message', $msg);
                    return redirect()->back()->withInput();  // Perform the redirect
                }

                // Check product SKU already exists or not
                $productSKU = Product::where('SKU', $sku)->where('store_id', $store_id)->first();

                if (isset($productSKU)) {
                    Session::flash('error', 'SKU Already Taken !');
                    return back()->withInput();
                }

                // Check category is selected or not
                if (count($request->category) <= 0) {
                    Session::flash('error', 'Category Must be Given !');
                    return back()->withInput();
                }

                // Variant image validation check
                $imageError = $this->variantImageValidation($request, $store_id);
                if ($imageError) {
                    Session::flash('error', $imageError);
                    Session::flash('error_message', $imageError);
                    return redirect()->back()->withInput();  // Perform the redirect
                }

                if ($this->variantValidationCheck($request)) {
                    Session::flash('error', 'Product variant value missing!');
                    Session::flash('error_message', 'Product variant value missing!');
                    return back()->withInput();
                } elseif ($this->variantTotalQtyCheckWithProductQty($request)) {
                    Session::flash('error', 'Product variant quantity exited !');
                    Session::flash('error_message', 'Product variant quantity exited !');
                    return back()->withInput();
                } else {
                    $product = new Product;
                    $product->name = $request->product_name;
                    $product->description = $request->description;
                    $product->regular_price = $request->regular_price;
                    $product->discount_type = $request->discount_type;
                    $product->prev_discount = $request->discount_type;
                    if ($request->discount_type != "no_discount") {
                        $product->discount_product = 1;
                    }
                    $product->promotional_price = $request->promotional_price;
                    $product->tax_type = $request->tax_type;
                    $product->tax_rate = $request->tax_rate;
                    $product->quantity = $request->quantity;
                    $product->volume = $request->volume;
                    $product->unit = $request->productUnit;
                    $product->stock_status = $request->stock_status ?? NULL;
                    $product->pre_order_note = $request->pre_order_note ?? NULL;
                    $product->seo_keywords = $request->seo;
                    $product->weight = $request->weight;
                    $product->video_link = $request->video_link;
                    if (ModulusStatus($store_id, 118)) {
                        $product->expiry_date = $request->expiry_date ?? null;
                    }
                    $product->shipping_fee = $request->shipping_fee;
                    $product->brand = $request->brand;
                    $product->supplier = $request->supplier;
                    $product->cost = $request->cost;
                    $product->pse = $request->pse ?? 0;
                    $product->product_link = $request->product_link;
                    $product->currency_id = $store->currency;

                    if ($request->has('pse') && $request->input('pse') == true) {
                        $product->pse_req_date = Carbon::now();
                    }

                    if ($request->barcode == "") {
                        $id = date('y') . rand(1, 10000);
                        $product->barcode = $id;
                    } elseif ($request->barcode == null) {
                        $id = date('y') . rand(1, 10000);
                        $product->barcode = $id;
                    } else {
                        $product->barcode = $request->barcode;
                    }

                    // Image upload functionality
                    if ($request->file('image')) {
                        // Check input image mimeType validation
                        $imageError = $this->inputImageValidation($request->file('image'), $store_id);
                        if ($imageError) {
                            Session::flash('error', $imageError);
                            Session::flash('error_message', $imageError);
                            return redirect()->back()->withInput();  // Perform the redirect
                        }

                        // Save product image
                        $productImageArray = $this->saveProductImage($request->file('image'));

                        $product->images = implode(',', $productImageArray);
                    }

                    if (isset($request->gallery_image) && !empty($request->gallery_image)) {
                        $productImageArray = explode(',', $request->gallery_image);

                        $productImageArray = array_filter($productImageArray, function ($value) {
                            return !empty($value);
                        });

                        $productImageArray = array_map(function ($productImageArray) {
                            return trim(str_replace(env("APP_URL"), "", $productImageArray), '/');
                        }, $productImageArray);
                        $product->gallery_image = implode(',', $productImageArray);
                    }

                    $category = implode(',', $request->category ?? []);

                    $subcategory = "";
                    if (count($request->subcategory ?? []) > 0) {
                        $subcategory = $request->subcategory ?? []; // Assuming $request->subcategory is an array
                        $subcategory = implode(',', $subcategory);
                    }

                    $product->category = $category ?? "";
                    $product->subcategory = $subcategory ?? "";
                    $product->tags = $request->tags;
                    $product->status = "active";

                    $product->SKU = $sku;

                    // Set best sell product status
                    if (isset($request->best_sell)) {
                        $product->best_sell = 1;
                    } else {
                        $product->best_sell = 0;
                    }

                    // Set feature product status
                    if (isset($request->feature)) {
                        $product->feature = 1;
                    } else {
                        $product->feature = 0;
                    }

                    $product->uid = $user;
                    $product->customer_id = $customer_id;
                    $product->store_id = $store_id;
                    $product->creator = $user;
                    $product->editor = $user;
                    $product->save();

                    // Store product variant data
                    $this->storeProductVariant($request, $product);

                    $this->product_layout_update_create_delete($request, $product->id, $store_id);

                    $activity = " Save Product";
                    $this->saveactivity($activity);

                    Session::flash('message', 'Product Save Successfully !');


                    // Commit the transaction
                    DB::commit();

                    if (isset($request->page_type) && $request->page_type == "landing") {
                        return redirect()->route('admin.layout_product')->with('success_message', 'Product Save Successfully!');
                    }

                    return redirect()->route('admin.allproducts')->with('success_message', 'Product Save Successfully!');
                }
            }
        } catch (Exception $e) {
            // Rollback on exception
            DB::rollBack();

            Session::flash('error', "Something went wrong. Try again");
            return redirect()->back();
        }
    }

    /**
     * Variant image validation check
     *
     * @param $request
     * @param $store_id
     * @return false|string
     */
    public function variantImageValidation($request, $store_id)
    {
        if ($request->att == 'color') {
            // Store color variant image
            if ($request->file('cs_color_image') && count($request->file('cs_color_image')) > 0) {
                foreach ($request->file('cs_color_image') as $cs_color_image) {
                    if ($cs_color_image) {
                        // Check input image mimeType validation
                        return $this->inputImageValidation($cs_color_image, $store_id);
                    }
                }
            }

            // Store color and size variant image
            if (isset($request->cs_color_updateImage) && count($request->cs_color_updateImage) > 0) {
                foreach ($request->cs_color_updateImage as $cs_color_Image) {
                    if (isset($cs_color_Image) && count($cs_color_Image) > 0) {
                        foreach ($cs_color_Image as $cs_Image) {
                            if ($cs_Image) {
                                if (is_string($cs_Image)) {
                                    return false;
                                }
                                return $this->inputImageValidation($cs_Image, $store_id);
                            }
                        }
                    }
                }
            }


            // Store color and size variant image
            if ($request->file('cs_Image') && count($request->file('cs_Image')) > 0) {
                foreach ($request->file('cs_Image') as $cs_Image) {
                    if ($cs_Image) {
                        // Check input image mimeType validation
                        return $this->inputImageValidation($cs_Image, $store_id);
                    }
                }
            }
        } elseif ($request->att == 'onlycolor') {
            // Store only color variant image
            if ($request->file('c_Image') && count($request->file('c_Image')) > 0) {
                foreach ($request->file('c_Image') as $c_Image) {
                    if ($c_Image) {
                        // Check input image mimeType validation
                        return $this->inputImageValidation($c_Image, $store_id);
                    }
                }
            }
        } elseif ($request->att == 'unit') {
            // Store unit variant image
            if ($request->file('u_Image') && count($request->file('u_Image')) > 0) {
                foreach ($request->file('u_Image') as $u_Image) {
                    if ($u_Image) {
                        // Check input image mimeType validation
                        return $this->inputImageValidation($u_Image, $store_id);
                    }
                }
            }
        } elseif ($request->att == 'size') {
            // Store size variant image
            if ($request->file('s_Image') && count($request->file('s_Image')) > 0) {
                foreach ($request->file('s_Image') as $s_Image) {
                    if ($s_Image) {
                        // Check input image mimeType validation
                        return $this->inputImageValidation($s_Image, $store_id);
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check input image mimetype validation
     *
     * @param $requestImage
     * @param $store_id
     * @return false|string
     */
    public function inputImageValidation($requestImage, $store_id)
    {
        // Check image covert modules is active or not
        $imageModuleID = '107';
        $storeModulu = BuyModulus::where('modulus_id', $imageModuleID)->where('store_id', $store_id)->first();
        if (isset($storeModulu->status) && $storeModulu->status == 1) {
            $imageConvert = true;
        } else {
            $imageConvert = false;
        }

        foreach ($requestImage as $key => $image) {
            $imgSize = $image->getSize();
            $imgSize = $imgSize / 1024;  // convert image size to kb

            // Check image converter module is active or not if active then check image size
            if ($imageConvert) {
                // Check image size if the size is greater than 600kb than throw an error.
                if ($imgSize > 6144) {
                    $msg = "Media must be lower than or equal to 6MB!";
                    return $msg;
                }
            } else {
                // Check image size if the size is greater than 200kb than throw an error.
                if ($imgSize > 200) {
                    $msg = "Media must be lower than or equal to 200kb.";
                    return $msg;
                }
            }
        }


        // Check mimeType
        $mimeType = getMimeTypes();
        foreach ($requestImage as $key => $image) {
            $imgExt = strtolower($image->getClientOriginalExtension());

            // Check input image mimeType
            if (!in_array($imgExt, $mimeType)) {
                return getMimeTypesValidationMessage();
            }
        }

        return false;
    }

    /**
     * Image count validation
     *
     * @param $requestImage
     * @param $store_id
     * @return RedirectResponse|void
     */
    public function productImageCountValidation($requestImage, $store_id, $product_id = null)
    {
        $imageCount = 0;
        if (is_array($requestImage)) {
            $imageCount = count($requestImage);
        }

        // Get the current images from the product associated with the store
        if (!is_null($product_id)) {
            $productImageCount = Product::where("store_id", $store_id)->where("id", $product_id)->pluck("images")->first();

            // Convert the image string into an array
            $imageArr = array_filter(explode(",", $productImageCount));

            // Check if the existing image count is 5 or more
            if (count($imageArr) > 5) {
                return true;
            }

            // Calculate the new total image count after the new uploads
            $totalImageCount = count($imageArr) + $imageCount;

            // Check if adding the new images exceeds the limit of 5
            if ($totalImageCount > 5) {
                return true;
            }
        } else {
            // Check if adding the new images exceeds the limit of 5
            if ($imageCount > 5) {
                return true;
            }
        }
    }

    /**
     *
     * Variant input validation check
     *
     * @param $request
     * @return bool
     */
    public function variantValidationCheck($request): bool
    {
        $status = false;
        if ($request->att == 'color') {
            // Get color and size total input quantity
            if (isset($request->sid) && count($request->sid) > 0) {
                foreach ($request->cs_qty as $keys => $quantityd) {
                    if (empty($request->cs_color[$keys])) {
                        $status = true;
                    } else {
                        foreach ($quantityd as $ky => $quantity) {
                            if (isset($request->sid[$keys][$ky]) && $request->sid[$keys][$ky] === "yes") {
                                if (!isset($request->cs_qty[$keys][$ky])) {
                                    $status = true;
                                }
                            }
                        }
                    }
                }
            }
        } elseif ($request->att == 'onlycolor') {
            // Get only color total input quantity
            if (isset($request->c_qty) && count($request->c_qty) > 0) {
                foreach ($request->c_qty as $key => $qty) {
                    if (!empty($request->c_qty[$key]) && $request->c_color[$key] == "Select Color") {
                        print_r("color empty $key");
                        $status = true;
                    } elseif ($request->c_qty[$key] == "Select Color" && !empty($request->c_color[$key])) {
                        print_r($request->c_color[$key]);
                        $status = true;
                    }
                }
            }
        } elseif ($request->att == 'unit') {
            // Get unit total input quantity
            if (isset($request->u_unit) && count($request->u_unit) > 0) {
                foreach ($request->u_unit as $key => $units) {
                    if (!empty($request->u_volume[$key]) && ($request->u_unit[$key] == "Select Unit" || empty($request->u_qty[$key]))) {
                        $status = true;
                    } elseif ($request->u_unit[$key] != "Select Unit" && (empty($request->u_volume[$key]) || empty($request->u_qty[$key]))) {
                        $status = true;
                    } elseif (!empty($request->u_qty[$key]) && ($request->u_unit[$key] == "Select Unit" || empty($request->u_volume[$key]))) {
                        $status = true;
                    }
                }
            }
        }

        // No need validation check if value equal zero
//        elseif ($request->att == 'size') {
//            // Get size total input quantity
//            foreach ($request->s_qty as $key => $qty) {
//                if ($request->s_qty[$key] != "") {
//                    if (empty($request->s_qty[$key])) {
//                        $status = true;
//                    }
//                }
//            }
//
//            exit();
//        }

        return $status;
    }

    /**
     *
     * Variant input total quantity check with product quantity. if variant quantity is bigger than product quantity then throw validation error
     *
     * @param $request
     * @return bool
     */
    public function variantTotalQtyCheckWithProductQty($request): bool
    {
        $getVariantTotalQty = 0;
        if ($request->att == 'color') {
            // Get color and size total input quantity
            if (isset($request->cs_qty) && count($request->cs_qty) > 0) {
                foreach ($request->cs_qty as $key => $qty) {
                    foreach ($qty as $kys => $ct) {
                        if (isset($ct)) {
                            $getVariantTotalQty = $getVariantTotalQty + (float) $ct;
                        }
                    }
                }
            }
        } elseif ($request->att == 'onlycolor') {
            // Get only color total input quantity
            if (isset($request->c_qty) && count($request->c_qty) > 0) {
                foreach ($request->c_qty as $key => $qty) {
                    $getVariantTotalQty = $getVariantTotalQty + (float) $request->c_qty[$key];
                }
            }
        } elseif ($request->att == 'unit') {
            // Get unit total input quantity
            if (isset($request->u_unit) && count($request->u_unit) > 0) {
                foreach ($request->u_unit as $key => $units) {
                    $getVariantTotalQty = $getVariantTotalQty + (float) $request->u_qty[$key];
                }
                $getVariantTotalQty = (float) $request->quantity;
            }
        } elseif ($request->att == 'size') {
            // Get size total input quantity
            if (isset($request->s_qty) && count($request->s_qty) > 0) {
                foreach ($request->s_qty as $key => $qty) {
                    if ($request->s_qty[$key] != "") {
                        $getVariantTotalQty = $getVariantTotalQty + (float) $request->s_qty[$key];
                    }
                }
            }
        }

        // For product variant quantity exited
        if ($getVariantTotalQty > (float) $request->quantity) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Save product image
     *
     * @param $requestImage
     * @return array
     * @throws ConversionFailedException
     */
    public function saveProductImage($requestImage)
    {
        // Image name array which is initially empty
        $imgss = array();
        $imageUploadPath = 'assets/images/product/';

        foreach ($requestImage as $key => $image) {
            if ($image) {
                $imgss[] = uploadFile($image, $imageUploadPath);
            }
        }

        return $imgss;
    }


    /**
     *
     * Store product variant data
     *
     * @param $request
     * @param $product
     * @return void
     */
    public function storeProductVariant($request, $product): void
    {
        $imageUploadPath = 'assets/images/product/';

        if ($request->att == 'color') {

            //            $cs_color_updateImage = $request->file('cs_color_updateImage') ?? null;
            $cs_color_updateImage = $request->input('cs_color_updateImage') ?? null;
            // Store product color image
            if (isset($cs_color_updateImage) && count($cs_color_updateImage) > 0) {
                foreach ($cs_color_updateImage as $color => $cs_color_Image) {
                    if (isset($cs_color_Image) && count($cs_color_Image) > 0) {
                        foreach ($cs_color_Image as $image) {
                            if (isset($image)) {
                                //                                $veriantColorImageUpdate = uploadFile($image, $imageUploadPath);
                                $veriantColorImageUpdate = getVariantImagePath($image);
                                if (isset($veriantColorImageUpdate)) {
                                    $this->variantColorImageUpdate($product->id, $color, $veriantColorImageUpdate);
                                }
                            }
                        }
                    }
                }
            }


            // Store color and size variant
            if (isset($request->cs_qty) && count($request->cs_qty) > 0) {
                foreach ($request->cs_qty as $keys => $quantityd) {
                    foreach ($quantityd as $ky => $quantity) {
                        $color = $request->cs_color[$keys] ?? null;
                        $size = $request->cs_size[$keys][$ky] ?? null;

                        if (!empty($request->cs_qty[$keys][$ky]) && !empty($color) && $color !== "Select Color" && trim($color) !== "Select Color" && !empty($size)) {
                            $variantId = $request->cs_attrId[$keys][$ky] ?? null;
                            $veriant = $variantId ? Veriant::where('id', $variantId)->where('pid', $product->id)->first() : null;

                            if (!$veriant) {
                                $veriant = Veriant::where('pid', $product->id)
                                    ->where('color', $color)
                                    ->where('size', $size)
                                    ->first();
                            }

                            if (!$veriant) {
                                // Color image
//                                $colorImage = $request->file('cs_color_image')[$keys] ?? null;
                                $colorImage = $request->input('cs_color_image')[$keys] ?? null;

                                $veriantColorImage = null;
                                if ($colorImage) {
                                    //                                    $veriantColorImage = uploadFile($colorImage, $imageUploadPath);
                                    $veriantColorImage = getVariantImagePath($colorImage);
                                }

                                $veriant = new Veriant();
                                $veriant->color_image = $veriantColorImage ?? null;
                            }

                            //                                $image = $request->file('cs_Image')[$keys][$ky] ?? null;
                            $image = $request->input('cs_Image')[$keys][$ky] ?? null;

                            if ($image) {
                                //                                    $veriant->image = uploadFile($image, $imageUploadPath);
                                $veriant->image = getVariantImagePath($image);
                            }

                            $veriant->pid = $product->id;
                            $veriant->color = $color;
                            $veriant->size = $size;
                            $veriant->quantity = $request->cs_qty[$keys][$ky];
                            $veriant->additional_price = $request->cs_price[$keys][$ky] ?? 0;
                            $veriant->save();
                        }
                    }
                }
            }

        } elseif ($request->att == 'onlycolor') {
            // Store only color variant

            $loop = 0;
            if (isset($request->c_color) && count($request->c_color) > 0) {
                foreach ($request->c_color as $key => $qty) {
                    if ($request->c_color[$key] != "Select Color" && !empty($request->c_qty[$key])) {

                        if (isset($request->c_attrId[$key])) {
                            $variantId = $request->c_attrId[$key];
                            $variant = Veriant::where('id', $variantId)->first();

                            if (!isset($request->c_ImageOld[$variantId])) {
                                //                                $image = $request->file('c_Image')[$loop] ?? null;
                                $image = $request->input('c_Image')[$loop] ?? null;
                                $loop++;

                                if ($image) {
                                    //                                    if (isset($variant->image)) {
//                                        $imagePath = public_path($imageUploadPath . $variant->image);
//                                        deleteFile($imagePath);
//                                    }
//                                    $variant->image = uploadFile($image, $imageUploadPath);
                                    $variant->image = getVariantImagePath($image);
                                }
                            }
                        } else {
                            $variant = new Veriant;

                            //                            $image = $request->file('c_Image')[$loop] ?? null;
                            $image = $request->input('c_Image')[$loop] ?? null;
                            $loop++;

                            if ($image) {
                                //                                $variant->image = uploadFile($image, $imageUploadPath);
                                $variant->image = getVariantImagePath($image);
                            }
                        }

                        if ($variant) {
                            $variant->pid = $product->id;
                            $variant->color = $request->c_color[$key];
                            $variant->quantity = $request->c_qty[$key];
                            $variant->additional_price = $request->c_price[$key];
                            $variant->save();
                        }
                    }
                }
            }
        } elseif ($request->att == 'unit') {
            // Store unit variant

            $loop = 0;
            if (isset($request->u_unit) && count($request->u_unit) > 0) {
                foreach ($request->u_unit as $key => $units) {
                    if ($request->u_unit[$key] != "Select Unit" && !empty($request->u_volume[$key]) && !empty($request->u_qty[$key])) {

                        if (isset($request->u_attrId[$key])) {
                            $variantId = $request->u_attrId[$key];
                            $variant = Veriant::where('id', $variantId)->first();

                            if (!isset($request->u_ImageOld[$variantId])) {
                                //                                $image = $request->file('u_Image')[$loop] ?? null;
                                $image = $request->input('u_Image')[$loop] ?? null;
                                $loop++;
                                if ($image) {
                                    //                                    if (isset($variant->image)) {
//                                        $imagePath = public_path($imageUploadPath . $variant->image);
//                                        deleteFile($imagePath);
//                                    }
//                                    $variant->image = uploadFile($image, $imageUploadPath);
                                    $variant->image = getVariantImagePath($image);
                                }
                            }
                        } else {
                            $variant = new Veriant;

                            //                            $image = $request->file('u_Image')[$loop] ?? null;
                            $image = $request->input('u_Image')[$loop] ?? null;
                            $loop++;

                            if ($image) {
                                //                                $variant->image = uploadFile($image, $imageUploadPath);
                                $variant->image = getVariantImagePath($image);
                            }
                        }

                        if ($variant) {
                            $variant->pid = $product->id;
                            $variant->unit = $request->u_unit[$key];
                            $variant->volume = $request->u_volume[$key];
                            $variant->quantity = $request->u_qty[$key];
                            $variant->additional_price = $request->u_price[$key];

                            $variant->save();
                        }
                    }
                }
            }
        } elseif ($request->att == 'size') {
            // Store size variant
            $loop = 0;
            if (isset($request->s_qty) && count($request->s_qty) > 0) {
                foreach ($request->s_qty as $key => $qty) {
                    if ($request->s_qty[$key] != "" && !empty($request->s_qty[$key])) {
                        if (isset($request->s_attrId[$key])) {
                            $variantId = $request->s_attrId[$key];
                            $variant = Veriant::where('id', $variantId)->first();

                            if (!isset($request->s_ImageOld[$variantId])) {
                                //                                $image = $request->file('s_Image')[$loop] ?? null;
                                $image = $request->input('s_Image')[$loop] ?? null;
                                $loop++;

                                if ($image) {
                                    //                                    if (isset($variant->image)) {
//                                        $imagePath = public_path($imageUploadPath . $variant->image);
//                                        deleteFile($imagePath);
//                                    }
//                                    $variant->image = uploadFile($image, $imageUploadPath);
                                    $variant->image = getVariantImagePath($image);
                                }
                            }
                        } else {
                            $variant = new Veriant;

                            //                            $image = $request->file('s_Image')[$loop] ?? null;
                            $image = $request->input('s_Image')[$loop] ?? null;
                            $loop++;

                            if ($image) {
                                //                                $variant->image = uploadFile($image, $imageUploadPath);
                                $variant->image = getVariantImagePath($image);
                            }
                        }

                        if ($variant) {
                            $variant->pid = $product->id;
                            $variant->size = $request->s_size[$key];
                            $variant->quantity = $request->s_qty[$key];
                            $variant->additional_price = $request->s_price[$key];

                            $variant->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * Update product position
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function updatePositionProduct(Request $request)
    {
        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $value = $request->value;
        $id = $request->id;
        $test = Product::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($test)) {
            return redirect()->back();
        }
        $test->position = $value;
        $test->save();

        $data = $test;

        $activity = "Update Category Position " . $test->name;
        $this->saveactivity($activity);

        return response()->json($data);
    }


    /**
     *
     * Delete product variant
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        if (!$veriant) {
            return response()->json(['message' => 'Variant not found'], 404);
        }

        //        if (isset($veriant->image)) {
//            $imagePath = public_path("assets/images/product/" . $veriant->image);
//            deleteFile($imagePath);
//        }
//        if (isset($veriant->color_image)) {
//            $imagePath = public_path("assets/images/product/" . $veriant->color_image);
//            deleteFile($imagePath);
//        }
        $veriant->delete();
        $data = "Success";

        $activity = " Delete Attribute";
        $this->saveactivity($activity);

        return response()->json($data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function delete($id)
    {
        if (canAccess('product')) {
            $userData = getUserData();
            $store_id = $userData['store_id'];

            $product = Product::where('store_id', $store_id)->where('id', $id)->first();
            if (empty($product)) {
                Session::flash('error', 'Product not found !');
                return redirect()->back();
            }

            $veriants = Veriant::where("pid", $product->id)->get();

            if (count($veriants) > 0) {
                foreach ($veriants as $veriant) {
                    if (isset($veriant->image)) {
                        $imagePath = public_path("assets/images/product/" . $veriant->image);
                        if (!demoImageCheck($veriant->image)) {
                            deleteFile($imagePath);
                        }
                    }
                    $veriant->delete();
                }
            }

            $product->delete();

            $activity = " Delete Product";
            $this->saveactivity($activity);

            Session::flash('success_message', 'Product Delete Successfully !');
            return redirect()->back();
        }
    }

    /**
     *
     * Update size variant
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatesizeattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->size = $request->size;
        $veriant->quantity = $request->quantity;
        $veriant->additional_price = $request->additional_price;
        $veriant->save();
        $data = $veriant->convertCurrency();

        $activity = " Update Size Attribute";
        $this->saveactivity($activity);

        return response()->json($data);
    }


    /**
     *
     * Remove variant
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deletesizeattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        if (!$veriant) {
            return response()->json(['message' => 'Variant not found'], 404);
        }
        $veriant->delete();
        $data = "Success";

        $activity = " Delete Size Attribute";
        $this->saveactivity($activity);

        return response()->json($data);
    }

    /**
     * Update unit variant
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateunitattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->unit = $request->unit;
        $veriant->volume = $request->volume;
        $veriant->quantity = $request->quantity;
        $veriant->additional_price = $request->additional_price;
        $veriant->save();
        $data = $veriant->convertCurrency();

        $activity = " Update Unit Attribute";
        $this->saveactivity($activity);

        return response()->json($data);
    }

    /**
     * Delete unit variant
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteunitattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        if (!$veriant) {
            return response()->json(['message' => 'Variant not found'], 404);
        }
        $veriant->delete();
        $data = "Success";

        $activity = " Delete Unit Attribute";
        $this->saveactivity($activity);

        return response()->json($data);
    }

    /**
     *
     * Update color variant
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateonlycolorattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        $veriant->color = $request->color;
        $veriant->quantity = $request->quantity;
        $veriant->additional_price = $request->additional_price;
        $veriant->save();
        $data = $veriant->convertCurrency();

        return response()->json($data);
    }

    /**
     * Delete color variant
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteonlycolorattribute(Request $request)
    {
        $veriant = Veriant::find($request->id);
        if (!$veriant) {
            return response()->json(['message' => 'Variant not found'], 404);
        }
        $veriant->delete();
        $data = "Success";

        return response()->json($data);
    }

    /**
     * Display product view
     *
     * @return Application|Factory|View
     */
    public function allss()
    {
        return view('admin.product.new');
    }

    /**
     *
     * Display add product view
     *
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function create()
    {
        if (canAccess('product')) {
            $urls = "product";

            // Get user data
            $userData = getUserData();
            $store_id = $userData['store_id'];

            // Store toptools count
            topToolsCount("Product", "box.png", "/products");

            $moduleIsNull = ModulusStatus($store_id, 107);

            $activity = " Access Create Product Page";
            $this->saveactivity($activity);

            $store = Store::with('current_currency')->find($store_id);
            $current_currency = $store->current_currency;

            $currency = Currency::join('stores', 'stores.currency', 'currencies.id')->where(
                'stores.id',
                $store_id
            )->first('code');
            // dd($store_id);
            return view('admin.product.create')
                ->with('urls', $urls)
                ->with('currency', $currency)
                ->with('store', $store)
                ->with('current_currency', $current_currency)
                ->with(['store_id' => $store_id, 'moduleIsNull' => $moduleIsNull]);
        } else {
            return redirect('/');
        }
    }


    /**
     * Get subcategories by category ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getsubcat(Request $request)
    {
        $ids = $request->catid ?? [];
        $data = Category::whereIn('parent', $ids)->where('status', 'active')->get();
        return response()->json($data);
    }

    /**
     * Change product status.
     *
     * @param Request $request
     * @return Response
     */
    public function changeprostatus(Request $request)
    {
        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $id = $request->id;
        $value = $request->value;
        $product = Product::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($product)) {
            return redirect()->back();
        }

        if (isset($product) && $product->status == 'active') {
            $product->status = 'inactive';
        } else {
            $product->status = "active";
        }
        $product->save();
        $data = $product;

        $pseProduct = AcceptedPseProductRequest::where('product_id', $product->id)->first();

        if (!is_null($pseProduct)) {
            if ($data->status == 'active') {
                $pseProduct->status = true;
                $pseProduct->update();
            } else {
                $pseProduct->status = false;
                $pseProduct->update();
            }
        }

        $activity = " Change Product Status";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    /**
     * Update product.
     *
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Get user data
            $userData = getUserData();
            $user = $userData['user_id'];
            $store_id = $userData['store_id'];
            $customer_id = $userData['customer_id'];
            $store = Store::find($store_id);

            // Get product by product id
            $product = Product::where('store_id', $store_id)->where('id', $id)->first();

            // Check product if not found then redirect
            if (empty($product)) {
                Session::flash("error", "Product not found!");
                return redirect()->route("admin.allproducts");
            }

            // Image validation check. Image must not be empty.
            if (empty($request->oldImage) && empty($request->oldGalleryImage) && empty($request->image) && empty($request->gallery_image)) {
                Session::flash('error', 'Image Must be Given !');
                return redirect()->back();
            }

            // Category must not be empty
            if ($request->category == "Select") {
                Session::flash('error', 'Category Must be Given !');
                return redirect()->back();
            }

            // Image count validation
            $imageError = $this->productImageCountValidation($request->file('image'), $store_id, $id);

            if ($imageError) {
                $msg = "You cannot upload more than 5 images!";
                Session::flash('error', $msg);
                Session::flash('error_message', $msg);
                return redirect()->back()->withInput();  // Perform the redirect
            }

            // Input validation
            $rules = array(
                'product_name' => 'required|string',
                'description' => 'required|string',
                'regular_price' => 'required|numeric|min:0',
                'discount_type' => 'required',
                //                'quantity' => 'required',
                'category' => 'required'
            );

            // Input validation message
            $errorMessage = array(
                'product_name.required' => 'Product name is required.',
                'description.required' => 'Description is required.',
                'regular_price.required' => 'Regular Price is required.',
                'regular_price.numeric' => 'Regular Price must be a valid price.',
                'regular_price.min' => 'Regular Price must be greater than 0.',
                'discount_type.required' => 'Discount Type is required.',
                //                'quantity.required' => 'Quantity is required.',
                'category.required' => 'Category is required.',
            );

            $validator = Validator::make($request->all(), $rules, $errorMessage);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            } else {
                // Code will be here....
                $qtyOrVolume = $request->qtyOrVolume ?? 0;
                if (isset($qtyOrVolume) && $qtyOrVolume == 0) {
                    if (is_null($request->quantity) || empty($request->quantity)) {
                        $validator->getMessageBag()->add('quantity', 'Quantity is required.');

                        return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                    }
                } else {
                    if (is_null($request->volume) || empty($request->volume)) {
                        $validator->getMessageBag()->add('volume', 'Volume is required.');

                        return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                    }

                    if (is_null($request->productUnit) || empty($request->productUnit)) {
                        $validator->getMessageBag()->add('productUnit', 'Unit is required.');

                        return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                    }
                }

                // Variant image validation check
                $imageError = $this->variantImageValidation($request, $store_id);
                if ($imageError) {
                    Session::flash('error', $imageError);
                    Session::flash('error_message', $imageError);
                    return redirect()->back()->withInput();  // Perform the redirect
                }

                if ($this->variantValidationCheck($request)) {
                    Session::flash('error', 'Product variant value missing!');
                    Session::flash('error_message', 'Product variant value missing!');
                    return back()->withInput();
                } elseif ($this->variantTotalQtyCheckWithProductQty($request)) {
                    Session::flash('error', 'Product variant quantity exited !');
                    Session::flash('error_message', 'Product variant quantity exited !');
                    return back()->withInput();
                } else {
                    $product->name = $request->product_name;
                    $product->description = $request->description;
                    $product->regular_price = $request->regular_price;
                    $product->discount_type = $request->discount_type;
                    $product->prev_discount = $request->discount_type;
                    if ($request->discount_type == "no_discount") {
                        $product->discount_product = 0;
                    } else {
                        $product->discount_product = 1;
                    }
                    $product->promotional_price = $request->promotional_price;
                    $product->tax_type = $request->tax_type;
                    $product->tax_rate = $request->tax_rate;
                    $product->quantity = $request->quantity;
                    $product->volume = $request->volume;
                    $product->unit = $request->productUnit;
                    $product->stock_status = $request->stock_status ?? NULL;
                    $product->pre_order_note = $request->pre_order_note ?? NULL;
                    $product->seo_keywords = $request->seo;
                    $product->weight = $request->weight;
                    $product->video_link = $request->video_link;
                    if (ModulusStatus($store_id, 118)) {
                        $product->expiry_date = $request->expiry_date ?? null;
                    }
                    $product->shipping_fee = $request->shipping_fee;
                    $product->brand = $request->brand;
                    $product->supplier = $request->supplier;
                    $product->cost = $request->cost;
                    $product->currency_id = $store->currency;
                    $product->product_link = $request->product_link;
                    $product->pse = $request->pse ?? 0;

                    if ($request->has('pse') && $request->input('pse') == true) {
                        $product->pse_req_date = Carbon::now();
                    }

                    $product->barcode = $request->barcode;

                    // Product image upload
                    if ($request->file('image')) {
                        // Check input image mimeType validation
                        $imageError = $this->inputImageValidation($request->file('image'), $store_id);
                        if ($imageError) {
                            Session::flash('error', $imageError);
                            Session::flash('error_message', $imageError);
                            return redirect()->back()->withInput();  // Perform the redirect
                        }

                        // Save product image
                        $productImageArray = $this->saveProductImage($request->file('image'));

                        $product->images = implode(',', $productImageArray);
                    }

                    // Product image set
                    if ($request->oldImage != null && $request->image != null) {
                        $product->images .= ',' . implode(',', $request->oldImage);
                    }

                    if (isset($request->gallery_image) && !empty($request->gallery_image)) {
                        $productImageArray = explode(',', $request->gallery_image);
                        $productImageArray = array_filter($productImageArray, function ($value) {
                            return !empty($value);
                        });

                        $productImageArray = array_map(function ($productImageArray) {
                            return trim(str_replace(env("APP_URL"), "", $productImageArray), '/');
                        }, $productImageArray);

                        // gallery_image hidden input (#imageUrlsInput) already holds the full desired list
                        // (existing + newly picked from file manager). Concatenating with $product->gallery_image
                        // duplicated every image on each update when oldGalleryImage was also submitted.
                        $product->gallery_image = implode(',', $productImageArray);
                    }


                    $category = implode(',', $request->category ?? []);

                    $subcategory = "";
                    if (count($request->subcategory ?? []) > 0) {
                        $subcategory = $request->subcategory ?? []; // Assuming $request->subcategory is an array
                        $subcategory = implode(',', $subcategory);
                    }

                    $product->category = $category ?? "";
                    $product->subcategory = $subcategory ?? "";
                    $product->tags = $request->tags;
                    $product->status = "active";
                    $product->SKU = $request->SKU;

                    if (isset($request->best_sell)) {
                        $product->best_sell = 1;
                    } else {
                        $product->best_sell = 0;
                    }

                    if (isset($request->feature)) {
                        $product->feature = 1;
                    } else {
                        $product->feature = 0;
                    }

                    $product->editor = $user;
                    $product->uid = $user;
                    $product->customer_id = $customer_id;
                    $product->store_id = $store_id;
                    $product->creator = $user;
                    $product->editor = $user;
                    $product->save();

                    // Store product variant data
                    $this->storeProductVariant($request, $product);

                    $this->product_layout_update_create_delete($request, $id, $store_id);


                    $activity = " Update Product";
                    $this->saveactivity($activity);

                    $msg = 'Product Updated Successfully !';
                    if ($request->update == 'update') {
                        $msg = 'Product Updated Successfully !';
                    } else {
                        $msg = 'Product published Successfully !';
                    }

                    Session::flash('message', $msg);


                    // Commit the transaction
                    DB::commit();

                    // Get the value of the clicked button

                    $buttonClicked = $request->input('update');

                    if ($buttonClicked === 'update') {
                        return redirect()->back();
                    } else {
                        if (isset($request->page_type) && $request->page_type == "landing") {
                            return redirect()->route('admin.layout_product')->with('success_message', 'Product Save Successfully!');
                        }
                        return redirect()->route("admin.allproducts");
                    }
                }
            }
        } catch (Exception $e) {
            //Rollback on exception
            DB::rollBack();
            Session::flash('error', "Something went wrong. Try again");
            return redirect()->back();
        }
    }

    private function product_layout_update_create_delete($request, $product_id, $store_id)
    {
        $layoutIds = [];
        $layouts = [];

        if (isset($request->layouts) && count($request->layouts) > 0) {
            $layoutIds = is_array($request->layouts)
                ? array_column($request->layouts, 'id')
                : [];

            // Delete layouts not in the request
            ProductLayout::where('product_id', $product_id)
                ->where('store_id', $store_id)
                ->whereNotIn('id', $layoutIds)
                ->delete();
            foreach ($request->layouts as $index => $layout) {
                // Handle design creation or update
                if (isset($request->design) && isset($request->design[$index])) {
                    $designData = $request->design[$index];

                    if (isset($designData['id'])) {
                        $layout_design = LayoutDesign::updateOrCreate(
                            ['id' => $designData['id']],
                            $designData
                        );
                    } else {
                        $layout_design = LayoutDesign::create($designData);
                    }
                } else {
                    $layout_design = LayoutDesign::create([
                        "color" => "#000000",
                        "bg_color" => "#ffffff",
                        "hover_color" => "#F1593A",
                        "size" => "0",
                    ]);
                }

                // Create or update product layout
                $productLayout = ProductLayout::firstOrNew(
                    ['id' => $layout['id'] ?? null],
                    ['product_id' => $product_id, 'store_id' => $store_id]
                );

                // Image validation and upload (uncomment if needed)
                if (isset($layout['link']) && !is_string($layout['link'])) {
                    $imageError = $this->inputImageValidation($layout['link'], $store_id);
                    if ($imageError) {
                        Session::flash('error', $imageError);
                        Session::flash('error_message', $imageError);
                        return redirect()->back()->withInput();  // Perform the redirect
                    }

                    $imageUploadPath = 'assets/images/product/';
                    $productLayout->link = uploadFile($layout['link'], $imageUploadPath);
                } else if (isset($layout['link']) && is_string($layout['link']) && $layout['type'] === 'button') {
                    $productLayout->link = $layout['link'] ?? null;
                }

                // Manually updating fields
                $productLayout->text = $layout['text'] ?? null;
                $productLayout->type = $layout['type'] ?? null;
                $productLayout->button = $layout['button'] ?? null;
                $productLayout->layout_design_id = $layout_design->id ?? null;
                $productLayout->position = $layout['position'] ?? 0;

                // Save the layout
                $productLayout->save();
            }
        }
    }

    public function duplicateProduct($id)
    {
        $product_id = $id; // Product ID

        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        // Get product by product id
        $product = Product::where('store_id', $store_id)->where('id', $product_id)->first();

        // Check product if not found then redirect
        if (empty($product)) {
            Session::flash("error", "Product not found!");
            return redirect()->route("admin.allproducts");
        }

        // Check product duplicate module active or not
        if (!ModulusStatus($store_id, 1)) {
            Session::flash('error', 'Please purchase duplicate module!');
            return redirect()->back();
        }


        $store = Store::find($store_id);

        $plan = Plan::find($store->plan_id);
        if ($store->expiry_date >= Carbon::now()) {
            if (isset($store->pos_plan_id)) {
                if ($store->pos_plan_expiry_date >= Carbon::now()) {
                    $posplan = Posplan::find($store->pos_plan_id);
                    if ($plan->product > $posplan->product) {
                        $limit = $plan->product;
                    } else {
                        $limit = $posplan->product;
                    }
                } else {
                    $limit = $plan->product;
                }
            } else {
                $limit = $plan->product;
            }
        } else {
            $limit = 0;
        }

        $proCount = Product::where('store_id', $store_id)->where('status', 'active')->count();

        if ($limit <= $proCount) {
            Session::flash('error', 'Please update your package to add more Products!');
            return redirect()->back();
        }


        // Create a new instance of the product
        $newProduct = $product->replicate();
        $newProduct->name = 'Copy of ' . $product->name;
        $newProduct->images = null;
        $newProduct->save();

        // Find product variant
        $productVariants = Veriant::where('pid', $product_id)->get();

        // Copy each variant associated with the product
        foreach ($productVariants as $variant) {
            $newVariant = $variant->replicate();
            $newVariant->pid = $newProduct->id; // Ensure the new variant is associated with the new product
            $newVariant->image = null;

            $newVariant->created_at = Carbon::now();
            $newVariant->updated_at = Carbon::now();

            $newVariant->save();
        }


        // Set current timestamps for created_at and updated_at of the new product
        $newProduct->created_at = Carbon::now();
        $newProduct->updated_at = Carbon::now();
        $newProduct->save();


        // Redirect or return success message
        return redirect()->route('admin.allproducts')->with('message', 'Product duplicated successfully.');

    }


    /**
     * Update product by the table action like ( Active, Inactive, Delete )
     *
     * @param Request $request
     * @return RedirectResponse|void
     */
    public function changeProductStatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Product');
            return redirect()->back();
        }

        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return redirect()->back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Product::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Product');
            return redirect()->back();
        }

        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Product::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Product');
            return redirect()->back();
        }

        if ($request->action == 'delete') {
            $products = explode(',', $request->text2);
            if (isset($products) && count($products) > 0) {
                foreach ($products as $product) {
                    $product = Product::find($product);
                    $product->status = 'RecycleBin';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deleted Product');
            return redirect()->back();
        }
    }

    /**
     *
     * Export product as csv file
     *
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportCsv(Request $request)
    {
        $date = Carbon::now();

        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $fileName = 'products(' . $date . ').csv';
        $products = Product::where('store_id', $store_id)->where('status', '!=', 'RecycleBin')->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array(
            'Name',
            'SKU',
            'Description',
            'Regular Price',
            'Discount Type',
            'Promotional Price',
            'Tax Type',
            'Tax Rate',
            'Quantity',
            'Weight',
            'Shipping Charge',
            'Category',
            'Subcategory',
            'Tags',
            'Create Date'
        );

        $callback = function () use ($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $product) {
                $row['Name'] = $product->name;
                $row['SKU'] = $product->SKU;
                $row['Description'] = $product->description;
                $row['Regular Price'] = $product->regular_price;
                $row['Discount Type'] = $product->discount_type;
                $row['Promotional Price'] = $product->promotional_price;
                $row['Tax Type'] = $product->tax_type;
                $row['Tax Rate'] = $product->tax_rate;
                $row['Quantity'] = $product->quantity;
                $row['Weight'] = $product->weight;
                $row['Shipping Charge'] = $product->shipping_fee;
                $category = Category::find($product->category);
                $row['Category'] = $category->name;
                if (isset($product->subcategory)) {
                    $subcat = Category::find($product->subcategory);
                    $row['Subcategory'] = $subcat->name;
                } else {
                    $row['Subcategory'] = "";
                }
                $row['Tags'] = $product->tags;
                $row['Create Date'] = $product->created_at;

                fputcsv($file, array(
                    $row['Name'],
                    $row['SKU'],
                    $row['Description'],
                    $row['Regular Price'],
                    $row['Discount Type'],
                    $row['Promotional Price'],
                    $row['Tax Type'],
                    $row['Tax Rate'],
                    $row['Quantity'],
                    $row['Weight'],
                    $row['Shipping Charge'],
                    $row['Category'],
                    $row['Subcategory'],
                    $row['Tags'],
                    $row['Create Date']
                ));
            }

            fclose($file);
        };
        $activity = " Product Export CSV";
        $this->saveactivity($activity);

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse|void
     */
    public function edit($id)
    {
        if (canAccess('product')) {
            $urls = "product";

            // Get user data
            $userData = getUserData();
            $store_id = $userData['store_id'];

            $name = "Product";
            $image = "box.png";
            $url = "/products";

            // Save top tools count
            topToolsCount($name, $image, $url);

            $moduleIsNull = ModulusStatus($store_id, 107);
            $customizable = ModulusStatus($store_id, 121);
            $activity = " Access Edit Product Page";
            $this->saveactivity($activity);
            $store = Store::with('current_currency')->find($store_id);
            $current_currency = $store->current_currency;
            $product = Product::with(['layout.design'])
                ->convertCurrency($store_id)
                ->where('products.id', $id)
                ->first();

            // Check product if not found then redirect
            if (empty($product)) {
                Session::flash("error", "Product not found!");
                return redirect()->route("admin.allproducts");
            }
            $final_product = new LayoutDesignResource($product);
            $final_product_json = $final_product->toJson();
            $final_product = json_decode($final_product_json, true);

            return view('admin.product.edit')
                ->with('current_currency', $current_currency)
                ->with('product', $final_product)
                ->with('urls', $urls)
                ->with('store', $store)
                ->with('customizable', $customizable)
                ->with(['store_id' => $store_id, 'moduleIsNull' => $moduleIsNull]);
        }
    }


    /**
     * Remove image from product
     *
     * @param $id
     * @param $image
     * @return JsonResponse|RedirectResponse
     */
    public function removeimage($id, $image)
    {
        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $product = Product::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($product)) {
            return redirect()->back();
        }

        $imagesArray = explode(',', $product->images);

        // Ensure at least one image remains
        if (count($imagesArray) <= 1) {
            return sendError("At least one image is required for the product.", 400);
        }

        // Remove the specific image
        $updatedImages = array_diff($imagesArray, [$image]);
        $product->images = trim(implode(',', $updatedImages), ',');
        $product->save();

        // Delete the image from filesystem if it's not a demo image
        if ($image && !demoImageCheck($image)) {
            $imagePath = public_path("assets/images/product/" . $image);
            deleteFile($imagePath);
        }

        $activity = " Remove Image From Product";
        $this->saveactivity($activity);
        return sendResponse("Success");
    }


    public function removeGalleryImage($id, $image)
    {
        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $product = Product::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($product)) {
            return redirect()->back();
        }

        $imagesArray = explode(',', $product->gallery_image);

        // Ensure at least one image remains
        if (count($imagesArray) <= 1) {
            return sendError("At least one image is required for the product.", 400);
        }

        // Remove the specific image
        $updatedImages = array_diff($imagesArray, [$image]);
        $product->gallery_image = trim(implode(',', $updatedImages), ',');
        $product->save();

        // Delete the image from filesystem if it's not a demo image
        if ($image && !demoImageCheck($image)) {
            $imagePath = public_path("assets/images/product/" . $image);
            deleteFile($imagePath);
        }

        $activity = " Remove Image From Product";
        $this->saveactivity($activity);
        return sendResponse("Success");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function variantDelete($id)
    {
        if (canAccess('attribute')) {

            // Get user data
            $userData = getUserData();
            $product = Product::where('store_id', $userData['store_id'])->where(
                'id',
                $id
            )->first(); // get user store product

            // Check user store product if no product found then redirect back
            if (empty($product)) {
                return redirect()->back();
            }

            // Get product variant
            $veriants = Veriant::where('pid', $id)->get();

            foreach ($veriants as $veriant) {
                // Delete variant image if exists
//                if (isset($veriant->image)) {
//                    $imagePath = public_path("assets/images/product/" . $veriant->image);
//                    deleteFile($imagePath);
//                }
                $veriant->delete();
            }


            return response()->json($veriants);
        }
    }

    /**
     *
     * Variant image delete
     *
     *
     * @param $id
     * @return RedirectResponse
     */
    public function variantImageDelete($id)
    {
        // Get product variant
        $veriant = Veriant::where('id', $id)->first();

        if ($veriant) {
            // Delete variant image if exists
//            if (isset($veriant->image)) {
//                $imagePath = public_path("assets/images/product/" . $veriant->image);
//                deleteFile($imagePath);
//            }
            $veriant->image = null;
            $veriant->save();

            return redirect()->back()->with('message', 'Image Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Variant not found!');
        }
    }


    /**
     * Delete variant color image
     *
     * @param $id
     * @param $color
     * @return RedirectResponse|void
     */
    public function variantColorImageDelete($id)
    {
        // Get product variant
        $veriant = Veriant::where('id', $id)->first();

        $veriants = Veriant::where('pid', $veriant->pid)->where('color', $veriant->color)->get();

        if (isset($veriants) && count($veriants) > 0) {
            foreach ($veriants as $veriant) {
                // Delete variant color image if exists
//                if (isset($veriant->color_image)) {
//                    $imagePath = public_path("assets/images/product/" . $veriant->color_image);
//                    deleteFile($imagePath);
//                }

                $veriant->color_image = null;
                $veriant->save();
            }

            return redirect()->back()->with('message', 'Image Deleted Successfully');

        } else {
            return redirect()->back()->with('error', 'Variant not found!');
        }
    }


    public function variantColorImageUpdate($id, $color, $image)
    {
        $veriants = Veriant::where('pid', $id)->where('color', $color)->get();

        if (isset($veriants) && count($veriants) > 0) {
            foreach ($veriants as $veriant) {
                // Delete variant color image if exists
                if (isset($veriant->color_image)) {
                    $imagePath = public_path("assets/images/product/" . $veriant->color_image);
                    if (file_exists($imagePath)) {
                        deleteFile($imagePath);
                    }
                }

                $veriant->color_image = $image;
                $veriant->save();
            }

            return true;

        } else {
            return false;
        }
    }

    /**
     * Display inventory page
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function inventory(Request $request)
    {
        if (canAccess('inventory')) {
            $urls = "inventory";
            $url1 = "Inventory";

            $perPage = 20;

            // Get user data
            $userData = getUserData();
            $store_id = $userData['store_id'];

            // Store toptools count
            topToolsCount("Inventory", "inventory-2.png", "/inventory");

            $activity = " Access Inventory Page";
            $this->saveactivity($activity);

            $store = Cache::remember("store_with_currency_{$store_id}", 3600, function () use ($store_id) {
                return Store::with('current_currency')->findOrFail($store_id);
            });
            $current_currency = $store->current_currency;
            $products = Product::with('variant')->select("products.*", 'currencies.symbol')
                ->join('currencies', 'products.currency_id', '=', 'currencies.id')
                ->where('products.store_id', $store_id)
                ->where('quantity', '!=', '0')
                ->where('products.status', '!=', 'RecycleBin')
                ->when(
                    'products.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                    function ($query) use ($current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.regular_price / currencies.rate * " . $current_currency->rate . " , 2) as regular_price"),
                            DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / currencies.rate * " . $current_currency->rate . " , 2) ELSE products.tax_type END as promotional_price"),
                            DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / currencies.rate * {$current_currency->rate}, 2) ELSE products.tax_type END as tax_rate"),
                            DB::raw("'{$current_currency->symbol}' as symbol")
                        ]);
                    }
                )
                ->when(
                    'products.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                    function ($query) use ($store, $current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.regular_price / {$store->currency_rate}, 2) as regular_price"),
                            DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / {$store->currency_rate}, 2) ELSE products.tax_type END as promotional_price"),
                            DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / {$store->currency_rate}, 2) ELSE products.tax_type END as tax_rate"),
                            DB::raw("'{$current_currency->symbol}' as symbol")
                        ]);
                    }
                )
                ->orderby('id', 'DESC');

            // Optionally filter by expiry date if ModulusStatus is true and expiry_date is provided
            if (ModulusStatus($store_id, 118)) {
                if (isset($request->expiry_date) && !empty($request->expiry_date)) {
                    $products = $products->where('expiry_date', '<=', $request->expiry_date);
                }
            }

            $products = $products->paginate($perPage);

            $currency = Cache::remember("store_currency_info_{$store_id}", 3600, function () use ($store_id) {
                return Currency::join('stores', 'stores.currency', '=', 'currencies.id')
                    ->where('stores.id', $store_id)
                    ->first(['symbol', 'code']);
            });

            return view('admin.product.inventory')
                ->with('urls', $urls)
                ->with('products', $products)
                ->with('currency', $currency)
                ->with('store_id', $store_id)
                ->with('url1', $url1);
        } else {
            return redirect()->route('admin.index');
        }
    }

    /**
     * Display stock alert page
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function stockalert()
    {
        if (canAccess('inventory')) {
            $urls = "inventory";
            $url1 = "Stock Alert";

            // Get user data
            $userData = getUserData();
            $store_id = $userData['store_id'];

            // Store toptools count
            topToolsCount("Stockalert", "new-product.png", "/stock_alert");

            $currentCurrency = currentCurrency();
            $current_currency = $currentCurrency['current_currency'];
            $current_rate = $currentCurrency['currency_rate'];

            $store_id = getUserData()['store_id'] ?? "";
            $headerSetting = Headersetting::where("store_id", $store_id)->first();
            $stock_out_qty = $headerSetting->stock_out_qty ?? "5";

            $activity = " Access Stock Alert Page";
            $this->saveactivity($activity);
            $products = Product::select("products.*", 'currencies.symbol')
                ->join('currencies', 'products.currency_id', '=', 'currencies.id')
                ->where('products.store_id', $store_id)
                ->where('quantity', '<=', $stock_out_qty)
                ->where('products.status', '!=', 'RecycleBin')
                ->when(
                    'products.currency_id' !== $current_currency->id && $current_currency->customize_rate_status === 0,
                    function ($query) use ($current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.regular_price / currencies.rate * " . $current_currency->rate . " , 2) as regular_price"),
                            DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / currencies.rate * " . $current_currency->rate . " , 2) ELSE products.tax_type END as promotional_price"),
                            DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / currencies.rate * {$current_currency->rate}, 2) ELSE products.tax_type END as tax_rate"),
                            DB::raw("'{$current_currency->symbol}' as symbol")
                        ]);
                    }
                )
                ->when(
                    'products.currency_id' !== $current_currency->id && $current_currency->customize_rate_status,
                    function ($query) use ($current_rate, $current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.regular_price / {$current_rate}, 2) as regular_price"),
                            DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / {$current_rate}, 2) ELSE products.tax_type END as promotional_price"),
                            DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / {$current_rate}, 2) ELSE products.tax_type END as tax_rate"),
                            DB::raw("'{$current_currency->symbol}' as symbol")
                        ]);
                    }
                )
                ->orderby('id', 'DESC')
                ->paginate(20);

            return view('admin.product.inventory')->with('urls', $urls)->with('products', $products)->with(
                'url1',
                $url1
            );
        } else {
            return redirect()->route('admin.index');
        }

    }

    /**
     *
     * Set Stock out quantity
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function stockOutQtyStore(Request $request)
    {
        if (!isset($request->stock_out_qty) || empty($request->stock_out_qty) || $request->stock_out_qty == 0) {
            Session::flash("error", "Stock out alert quantity must be greater than 0!");
            return back();
        }

        $store_id = getUserData()['store_id'] ?? "";
        $headerSetting = Headersetting::where("store_id", $store_id)->first();
        if (isset($headerSetting)) {
            $headerSetting->stock_out_qty = $request->stock_out_qty;
            $headerSetting->update();

            Session::flash("success", "Stock out quantity set successfully!");
            return back();
        }

        Session::flash("error", "Something went wrong. Please try again later.");
        return back();
    }

    /**
     *
     * Display stock out page
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function stockout()
    {
        if (canAccess('inventory')) {
            $urls = "inventory";
            $url1 = "Stock Out";

            // Get user data
            $userData = getUserData();
            $store_id = $userData['store_id'];

            // Store toptools count
            topToolsCount("Stockout", "out-of-stock.png", "/stock_out");

            $currentCurrency = currentCurrency();
            $current_currency = $currentCurrency['current_currency'];
            $current_rate = $currentCurrency['currency_rate'];

            $activity = " Access Stock Out Page";
            $this->saveactivity($activity);
            $products = Product::select("products.*", 'currencies.symbol')
                ->join('currencies', 'products.currency_id', '=', 'currencies.id')
                ->where('products.store_id', $store_id)
                ->where('quantity', '<=', '0')
                ->where('products.status', '!=', 'RecycleBin')
                ->when(
                    'products.currency_id' !== $current_currency->id && $current_currency->customize_rate_status === 0,
                    function ($query) use ($current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.regular_price / currencies.rate * " . $current_currency->rate . " , 2) as regular_price"),
                            DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / currencies.rate * " . $current_currency->rate . " , 2) ELSE products.tax_type END as promotional_price"),
                            DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / currencies.rate * {$current_currency->rate}, 2) ELSE products.tax_type END as tax_rate"),
                            DB::raw("'{$current_currency->symbol}' as symbol")
                        ]);
                    }
                )
                ->when(
                    'products.currency_id' !== $current_currency->id && $current_currency->customize_rate_status,
                    function ($query) use ($current_rate, $current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.regular_price / {$current_rate}, 2) as regular_price"),
                            DB::raw("CASE WHEN products.discount_type = 'fixed' THEN ROUND(products.promotional_price / {$current_rate}, 2) ELSE products.tax_type END as promotional_price"),
                            DB::raw("CASE WHEN products.tax_type = 'fixed' THEN ROUND(products.tax_rate / {$current_rate}, 2) ELSE products.tax_type END as tax_rate"),
                            DB::raw("'{$current_currency->symbol}' as symbol")
                        ]);
                    }
                )
                ->orderby('id', 'DESC')
                ->paginate(20);

            return view('admin.product.inventory')
                ->with('urls', $urls)
                ->with('products', $products)
                ->with('url1', $url1);
        } else {
            return redirect()->route('admin.index');
        }
    }


    /**
     *
     * Product filter by date range
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function productdatefilter(Request $request)
    {
        if ((Auth::user()->type == 'staff') && (!canAccess('product') && !canAccess('category') && !canAccess('subcategory') && !canAccess('brand') && !canAccess('attribute') && !canAccess('supplier'))) {
            return redirect()->route('staff.dashboard');
        }

        $urls = "product";

        // Get user data
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $customer = $userData['customer'];

        $limit = 0;

        $store = Store::with('current_currency')->find($store_id);
        $current_currency = $store->current_currency;

        if ($store->plan_id != 'NULL') {
            $plan = Plan::find($store->plan_id);
            if ($store->expiry_date >= Carbon::now()) {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date >= Carbon::now()) {
                        $posplan = Posplan::find($store->pos_plan_id);
                        if ($plan->product > $posplan->product) {
                            $limit = $plan->product ?? '';
                        } else {
                            $limit = $posplan->product ?? '';
                        }
                    } else {
                        $limit = $plan->product ?? '';
                    }
                } else {
                    $limit = $plan->product ?? '';
                }
            } else {
                $limit = $limit;
            }
        } else {
            if (isset($store->pos_plan_id)) {
                if ($store->pos_plan_expiry_date >= Carbon::now()) {
                    $posplan = Posplan::find($store->pos_plan_id);
                    $limit = $posplan->product ?? '';
                } else {
                    $limit = $limit;
                }
            } else {
                $limit = $limit;
            }
        }

        if (isset($store->digital_plan_id)) {
            if ($store->plan_id != 'NULL') {
                $plan = Plan::find($store->plan_id);
                if ($store->expiry_date >= Carbon::now()) {
                    if (isset($store->pos_plan_id)) {
                        if ($store->pos_plan_expiry_date >= Carbon::now()) {
                            $posplan = Posplan::find($store->pos_plan_id);
                            if ($plan->product > $posplan->product) {
                                $limit = $plan->product ?? '';
                            } else {
                                $limit = $posplan->product ?? '';
                            }
                        } else {
                            $limit = $plan->product ?? '';
                        }
                    } else {
                        $limit = $plan->product ?? '';
                    }
                } else {
                    $limit = $limit;
                }
            } else {
                if ($store->digital_plan_end_date >= Carbon::now()) {
                    $limit = 10000000;
                } else {
                    $limit = $limit;
                }
            }
        }

        $from = $request->formdate;
        $to = $request->enddate;

        $tProduct = Product::where('store_id', $customer->active_store)->where(
            'status',
            '!=',
            'RecycleBin'
        )->count();

        $productQuery = Product::whereBetween('created_at', [$from, $to])->where('store_id', $store_id)
            ->where('status', '!=', 'RecycleBin')
            ->orderBy('position', 'ASC')
            ->latest()
            ->take($limit);

        $product = $productQuery->paginate($limit);

        $allProduct = $productQuery->get();

        // Store toptools count
        topToolsCount("Product", "box.png", "/products");


        $activity = " Fiter Product";
        $this->saveactivity($activity);

        $productCount = Product::where('creator', Auth::user()->id)->where('status', '!=', 'RecycleBin')->get();

        return view('admin.product.index', [
            'currency' => $current_currency,
            'allProduct' => $allProduct,
            'products' => $product,
            'urls' => $urls,
            'limit' => $limit,
            'productcount' => $productCount,
            'store_id' => $store_id,
            'tProduct' => $tProduct,
        ]);
    }


    /**
     * Handle image uploads for CKEditor.
     *
     * This method is responsible for processing image uploads initiated from CKEditor.
     * It receives a file upload request from CKEditor, saves the uploaded image to the server,
     * and returns a JSON response containing the URL of the uploaded image.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \InvalidArgumentException
     */
    public function ckEditor(Request $request)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        if ($request->hasFile('upload')) {
            // Check input image mimeType validation
            $validated = imageValidation($request->file('upload'), $store_id);
            if ($validated) {
                return response()->json(['error' => ['message' => $validated,]], 400);
            }

            // Upload image
            $imageUploadPath = 'assets/images/product/';
            $fileName = uploadFile($request->file('upload'), $imageUploadPath);

            $tmp = new TempImage();
            $tmp->user_id = Auth::user()->id;
            $tmp->store_id = $store_id ?? 0;
            $tmp->image = $fileName;
            $tmp->status = 0;
            $tmp->save();

            $url = asset('assets/images/product/' . $fileName);

            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }

        // If no file upload is found, throw an exception or return an error response
        throw new \InvalidArgumentException('No file upload found.');
    }


    public function generateFacebookCatalogFeedURL($name)
    {
        $store = Store::where("url", $name ?? "")->first();
        $store_id = $store->id ?? null;

        if (isset($store_id) && isset($store)) {
            $productQuery = Product::convertCurrency($store_id)
                ->where('products.status', 'active')
                ->with([
                    'getBrand' => function ($query) {
                        $query->select('id', 'name');
                    }
                ])
                ->withSum('reviews', 'rating')  // Adds total_rating
                ->withCount('reviews')->orderBy('products.id', 'desc');

            // Paginate the query results
            $allProducts = $productQuery->get();
            $products = (new SubdomainController())->getProductResponse($allProducts, $store_id);

            // Create a new XMLWriter instance
            $xml = new \XMLWriter();
            $xml->openMemory();
            $xml->startDocument('1.0', 'UTF-8');

            // Start <rss> element with required attributes
            $xml->startElement('rss');
            $xml->writeAttribute('version', '2.0');
            $xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

            // Start <channel> element
            $xml->startElement('channel');

            // Add Channel Info (using CDATA for title, link, and description)
            $xml->writeElement('title', 'eCommerce Products');
            $xml->writeElement('link', $store->url);
            $xml->writeElement('description', 'Product Feed for Facebook and Google Catalog');

            // Loop through products and write each one to the XML
            foreach ($products as $product) {
                // Start <item> element for each product
                $xml->startElement('item');

                $product_id = $product['id'];
                $product_name = substr(strip_tags($product['name'] ?? ""), 0, 200);  // Truncate and strip tags
                $product_slug = $product['slug'] ?? "";

                $imageFile = null;
                foreach ($product['image'] as $item) {
                    if (!is_null($item) && !empty($item)) {
                        $imageFile = $item;
                        break;
                    }
                }

                $image_link = "";
                if (!is_null($imageFile)) {
                    if (preg_match("~^(?:f|ht)tps?://~i", $imageFile)) {
                        // $imageFile is already a full URL
                        $image_link = $imageFile;
                    } else {
                        // $imageFile is a relative path
                        $image_link = asset("assets/images/product/" . $imageFile);
                    }
                }

                $link = $product['product_link'] ?? $store->url . "/product/" . $product_id . "/" . $product_slug;
                if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
                    $link = "https://" . $link;
                }


                $description = $product['description'] ?? "";
                $descriptionText = substr(strip_tags($description), 0, 9999);

                // Ensure price formatting
                $price = ($product['regular_price'] ?? $product['calculate_regular_price'] ?? 0) . " " . ($product['code'] ?? "");
                $quantity = $product['quantity'] ?? 0;
                $availability = ($quantity > 0) ? "in stock" : "out of stock";

                $brand = $product['brand_name'] ?? $store->name ?? "";
                $condition = "new";
                $sale_price = ($product['calculate_regular_price'] ?? 0) . " " . ($product['code'] ?? "");
                $video_url = $product['video_link'] ?? "";
                $product_tags = $product['tags'] ?? "";

                // Add child elements for each field in the product
                $xml->writeElement('g:id', htmlspecialchars($product_id));  // Escape special chars
                $xml->writeElement('g:title', htmlspecialchars($product_name));
                $xml->writeElement('g:description', htmlspecialchars($descriptionText));
                $xml->writeElement('g:availability', htmlspecialchars($availability));
                $xml->writeElement('g:condition', htmlspecialchars($condition));
                $xml->writeElement('g:price', htmlspecialchars($price));
                $xml->writeElement('g:link', htmlspecialchars($link));
                $xml->writeElement('g:image_link', htmlspecialchars($image_link));
                $xml->writeElement('g:brand', htmlspecialchars($brand));
                $xml->writeElement('g:quantity_to_sell_on_facebook', htmlspecialchars($quantity));
                $xml->writeElement('g:sale_price', htmlspecialchars($sale_price));
                $xml->writeElement('g:video_link', htmlspecialchars($video_url));
                $xml->writeElement('g:product_tags', htmlspecialchars($product_tags));

                // End the <item> element
                $xml->endElement();
            }

            // End <channel> and <rss> elements
            $xml->endElement();
            $xml->endElement();

            return response($xml->outputMemory(), 200)
                ->header('Content-Type', 'application/xml');
        }

        return sendError("Store not found!");
    }


    public function generateFacebookCatalogFeedFile()
    {
        $userData = getUserData();
        $store_id = $userData['store_id'] ?? "";
        $store = $userData['store'] ?? "";

        if (isset($store_id) && isset($store)) {
            $productQuery = Product::convertCurrency($store_id)
                ->where('products.status', 'active')
                ->with([
                    'getBrand' => function ($query) {
                        $query->select('id', 'name');
                    }
                ])
                ->withSum('reviews', 'rating')  // Adds total_rating
                ->withCount('reviews')->orderBy('products.id', 'desc');

            // Paginate the query results
            $allProducts = $productQuery->get();
            $products = (new SubdomainController())->getProductResponse($allProducts, $store_id);

            // Create a new XMLWriter instance
            $xml = new \XMLWriter();
            $xml->openMemory();
            $xml->startDocument('1.0', 'UTF-8');

            // Start <rss> element with required attributes
            $xml->startElement('rss');
            $xml->writeAttribute('version', '2.0');
            $xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

            // Start <channel> element
            $xml->startElement('channel');

            // Add Channel Info (using CDATA for title, link, and description)
            $xml->writeElement('title', 'eCommerce Products');
            $xml->writeElement('link', $store->url);
            $xml->writeElement('description', 'Product Feed for Facebook and Google Catalog');

            // Loop through products and write each one to the XML
            foreach ($products as $product) {
                // Start <item> element for each product
                $xml->startElement('item');

                $product_id = $product['id'];
                $product_name = substr(strip_tags($product['name'] ?? ""), 0, 200);  // Truncate and strip tags
                $product_slug = $product['slug'] ?? "";

                $imageFile = null;
                foreach ($product['image'] as $item) {
                    if (!is_null($item) && !empty($item)) {
                        $imageFile = $item;
                        break;
                    }
                }

                $image_link = "";
                if (!is_null($imageFile)) {
                    if (preg_match("~^(?:f|ht)tps?://~i", $imageFile)) {
                        // $imageFile is already a full URL
                        $image_link = $imageFile;
                    } else {
                        // $imageFile is a relative path
                        $image_link = asset("assets/images/product/" . $imageFile);
                    }
                }

                $link = $product['product_link'] ?? $store->url . "/product/" . $product_id . "/" . $product_slug;
                if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
                    $link = "https://" . $link;
                }


                $description = $product['description'] ?? "";
                $descriptionText = substr(strip_tags($description), 0, 9999);

                // Ensure price formatting
                $price = ($product['regular_price'] ?? $product['calculate_regular_price'] ?? 0) . " " . ($product['code'] ?? "");
                $quantity = $product['quantity'] ?? 0;
                $availability = ($quantity > 0) ? "in stock" : "out of stock";

                $brand = $product['brand_name'] ?? $store->name ?? "";
                $condition = "new";
                $sale_price = ($product['calculate_regular_price'] ?? 0) . " " . ($product['code'] ?? "");
                $video_url = $product['video_link'] ?? "";
                $product_tags = $product['tags'] ?? "";

                // Add child elements for each field in the product
                $xml->writeElement('g:id', htmlspecialchars($product_id));  // Escape special chars
                $xml->writeElement('g:title', htmlspecialchars($product_name));
                $xml->writeElement('g:description', htmlspecialchars($descriptionText));
                $xml->writeElement('g:availability', htmlspecialchars($availability));
                $xml->writeElement('g:condition', htmlspecialchars($condition));
                $xml->writeElement('g:price', htmlspecialchars($price));
                $xml->writeElement('g:link', htmlspecialchars($link));
                $xml->writeElement('g:image_link', htmlspecialchars($image_link));
                $xml->writeElement('g:brand', htmlspecialchars($brand));
                $xml->writeElement('g:quantity_to_sell_on_facebook', htmlspecialchars($quantity));
                $xml->writeElement('g:sale_price', htmlspecialchars($sale_price));
                $xml->writeElement('g:video_link', htmlspecialchars($video_url));
                $xml->writeElement('g:product_tags', htmlspecialchars($product_tags));

                // End the <item> element
                $xml->endElement();
            }

            // End <channel> and <rss> elements
            $xml->endElement();
            $xml->endElement();

            // Prepare the file name
            $datafeedFileName = $store->name ?? "store-name";
            $datafeedFileName = $datafeedFileName . "-datafeed.xml";

            // Stream the XML content as a downloadable file
            return response()->stream(
                function () use ($xml) {
                    echo $xml->outputMemory();  // Output the XML to the stream
                },
                200,
                [
                    'Content-Type' => 'application/xml',
                    'Content-Disposition' => 'attachment; filename="' . $datafeedFileName . '"',
                ]
            );
        }

        return sendError("Store not found!");
    }


}
