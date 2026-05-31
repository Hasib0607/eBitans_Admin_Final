<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AddonsOrder;
use App\Models\AdminCoupon;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\Design;
use App\Models\Digitalplan;
use App\Models\ExpoDeviceToken;
use App\Models\Headersetting;
use App\Models\HomePae;
use App\Models\Menu;
use App\Models\Mobileapp;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Page;
use App\Models\Paymenttoken;
use App\Models\Plan;
use App\Models\Posplan;
use App\Models\Product;
use App\Models\QuickLogin;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Store;
use App\Models\Supersetting;
use App\Models\Template;
use App\Models\Temposition;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Veriant;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubdomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $store = Store::where('expiry_date', '>=', Carbon::now())->get();
        if (isset($store)) {
            if (count($store) > 0) {
                foreach ($store as $str) {
                    $slug[] = $str->url;
                }
            }
        } else {
            $slug[] = null;
        }
        return response()->json($slug);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function appStatus(Request $request)
    {
        $app = Mobileapp::where('store_id', $request->store_id)->where('expiry_date', '>=', Carbon::now())->first();

        if (empty($app)) {
            return response()->json(['status' => 'false']);
        } else {
            $expoDeviceInfo = ExpoDeviceToken::firstOrCreate(
                ['expo_token' => request('expo_token')],
                ['store_id' => request('store_id')]
            );

            $appurl = null;
            if ($app->status == "Download") {
                $appurl = $app->url;
            }

            return response()->json(['status' => 'true', 'expoDeviceInfo' => $expoDeviceInfo, 'appurl' => $appurl]);
        }
    }

    public function getsearch()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://seo-keyword-research.p.rapidapi.com/keyword?keyword=email%20marketing&country=us",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: seo-keyword-research.p.rapidapi.com",
                "X-RapidAPI-Key: f0a3fa7693msh277c0ad98d4ff5bp1b0b6djsn3d943d2a6385"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function sendheader()
    {
        $store = Store::where('expiry_date', '>=', Carbon::now())->get();
        if (isset($store) && count($store) > 0) {
            foreach ($store as $key => $stor) {
                $data[$key]['domain'] = $stor->url;
                $data[$key]['store_id'] = $stor->id;
                $ders = Design::where('store_id', $stor->id)->first();
                $data[$key]['header'] = $ders->header ?? "default";
                $data[$key]['hero'] = $ders->hero_slider ?? "default";
                $data[$key]['product'] = $ders->product ?? "default";
                $data[$key]['testimonial'] = $ders->testimonial ?? "default";
                $data[$key]['footer'] = $ders->footer ?? "default";
            }
        }
        return response()->json($data);
    }

    public function getnotification()
    {
        $notification = Notification::all();
        return response()->json($notification);
    }

    /**
     *
     *  Get subdomain info by info tag for SEO
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getsubdomainname(Request $request)
    {
        try {
            $name = $request->name;
            $store = Store::where('url', $name)->where('expiry_date', '>=', Carbon::now())->first();

            $headInput = isset($request->head) ? $request->head : [];
            if (!empty($headInput)) {
                $headInput = explode(",", $headInput);
            } else {
                $headInput = [];
            }

            if (isset($store)) {
                $responseData = [];

                $responseData['store_id'] = $store->id;

                $heads = array();
                foreach ($headInput as $key => $value) {
                    $heads[$value] = $key;
                }

                // Check and set menu in response
                if (isset($heads['store']) || count($heads) <= 0) {
                    $responseData['store'] = $store;
                }

                // Check and set menu in response
                if (isset($heads['menu']) || count($heads) <= 0) {
                    $menu = Menu::where('store_id', $store->id)->orderBy('sort', 'ASC')->get();
                    $responseData['menu'] = $menu;
                }

                // Check and set headersetting in response
                if (isset($heads['headersetting']) || count($heads) <= 0) {
                    $headersetting = Headersetting::convertCurrency($store->id)->first();
                    $headersetting['gtm'] = QuickLogin::where('modulus_id', 10)->where('store_id',
                        $store->id)->first(); // google tag manager api
                    $headersetting['facebook_pixel'] = QuickLogin::where('modulus_id', 11)->where('store_id',
                        $store->id)->first()->facebook_pixel ?? null; //for facebook pixel api

                    $responseData['headersetting'] = $headersetting;
                }

                // Check and set category in response
                if (isset($heads['category']) || count($heads) <= 0) {
                    $category = Category::where('store_id', $store->id)->where('parent', 0)->where('status',
                        'active')->orderBy('position', 'ASC')->get();
                    if (isset($category) && count($category) > 0) {
                        foreach ($category as $key => $cat) {
                            $cat['cat'] = $cat;
                            $subcat = Category::where('store_id', $store->id)->where('parent', $cat->id)->get();
                            if (isset($subcat) && count($subcat) > 0) {
                                $cat['cat'] = $subcat;
                            } else {
                                $cat['cat'] = null;
                            }
                        }
                    } else {
                        $cat[] = null;
                    }

                    $responseData['category'] = $category;
                    $responseData['cat'] = $cat;
                }

                // Check and set subcategory in response
                if (isset($heads['subcategory']) || count($heads) <= 0) {
                    $subcategory = Category::where('store_id', $store->id)->where('parent', '!=', '0')->where('status',
                        'active')->orderBy('position', 'ASC')->get();

                    $responseData['subcategory'] = $subcategory;
                }

                // Check and set slider in response
                if (isset($heads['slider']) || count($heads) <= 0) {
                    $slider = Slider::where('store_id', $store->id)->where('status', 'active')->orderBy('position',
                        'ASC')->get();
                    $responseData['slider'] = $slider;
                }


                // Check and set product in response
                if (isset($heads['product']) || count($heads) <= 0) {
                    $data = Product::convertCurrency($store->id)->where('products.status',
                        'active')->orderBy('products.position',
                        'ASC')->inRandomOrder()->limit(10)->get();

                    $best_sell = Product::convertCurrency($store->id)->where('products.status',
                        'active')->where('products.best_sell',
                        1)->orderBy('products.position', 'ASC')->inRandomOrder()->limit(10)->get();
                    if (isset($best_sell) && count($best_sell) > 0) {
                        foreach ($best_sell as $key => $products) {
                            $best_sell_product[$key]['id'] = $products->id;
                            $best_sell_product[$key]['name'] = $products->name;
                            $image = explode(',', $products->images);
                            $im = array();
                            foreach ($image as $keys => $img) {
                                $im[] = $img;
                            }

                            $rating = Review::where('product_id', $products->id)->where('rating', '!=',
                                null)->sum('rating');
                            $number_rating = Review::where('product_id', $products->id)->where('rating', '!=',
                                null)->count();

                            if ($rating != null && $number_rating != null) {
                                $rating = ($rating / $number_rating);
                            }

                            $best_sell_product[$key]['rating'] = $rating ?? 0;
                            $best_sell_product[$key]['number_rating'] = $number_rating ?? 0;
                            $best_sell_product[$key]['slug'] = generateSlug($products->name);

                            $best_sell_product[$key]['image'] = $im;
                            $best_sell_product[$key]['description'] = mb_substr($products->description, 0, 216);
                            $best_sell_product[$key]['regular_price'] = $products->regular_price;
                            $best_sell_product[$key]['discount_type'] = $products->discount_type;
                            $cat = Category::where('id', $products->category)->where('status', 'active')->first();
                            $best_sell_product[$key]['category_id'] = $products->category ?? "";
                            $best_sell_product[$key]['subcategory_id'] = $products->subcategory ?? "";
                            $best_sell_product[$key]['category'] = $cat->name ?? "";
                            if ($products->regular_price <= $products->promotional_price) {
                                $best_sell_product[$key]['discount_price'] = "0";
                            } else {
                                $best_sell_product[$key]['discount_price'] = $products->promotional_price;
                            }
                            $best_sell_product[$key]['tax_type'] = $products->tax_type;
                            $best_sell_product[$key]['tax_rate'] = $products->tax_rate;
                            $best_sell_product[$key]['quantity'] = $products->quantity;
                            $best_sell_product[$key]['seo_keywords'] = $products->seo_keywords;
                            $best_sell_product[$key]['weight'] = $products->weight;
                            $best_sell_product[$key]['shipping_fee'] = $products->shipping_fee;
                            $variant = Veriant::convertCurrency($products->id, $store->id)->get();


                            $best_sell_product[$key]['variant'] = $variant;

                            $brand = Brand::where('id', $products->brand)->first();

                            $best_sell_product[$key]['brand_id'] = $brand->id ?? null;
                            $best_sell_product[$key]['brand_name'] = $brand->name ?? null;
                        }
                    } else {
                        $best_sell_product = [];
                    }

                    $feature_products = Product::convertCurrency($store->id)
                        ->where('products.status', 'active')
                        ->where('products.feature', 1)
                        ->orderBy('products.position', 'ASC')
                        ->inRandomOrder()
                        ->limit(10)
                        ->get();
                    if (isset($feature_products) && count($feature_products) > 0) {
                        foreach ($feature_products as $key => $products) {
                            $feature_product[$key]['id'] = $products->id;
                            $feature_product[$key]['name'] = $products->name;
                            $image = explode(',', $products->images);
                            $im = array();
                            foreach ($image as $keys => $img) {
                                $im[] = $img;
                            }

                            $rating = Review::where('product_id', $products->id)->where('rating', '!=',
                                null)->sum('rating');
                            $number_rating = Review::where('product_id', $products->id)->where('rating', '!=',
                                null)->count();

                            if ($rating != null && $number_rating != null) {
                                $rating = ($rating / $number_rating);
                            }

                            $feature_product[$key]['rating'] = $rating ?? 0;
                            $feature_product[$key]['number_rating'] = $number_rating ?? 0;
                            $feature_product[$key]['slug'] = generateSlug($products->name);

                            $feature_product[$key]['image'] = $im;
                            $feature_product[$key]['description'] = mb_substr($products->description, 0, 216);
                            $feature_product[$key]['regular_price'] = $products->regular_price;
                            $feature_product[$key]['discount_type'] = $products->discount_type;
                            $cat = Category::where('id', $products->category)->where('status', 'active')->first();
                            $feature_product[$key]['category_id'] = $products->category ?? "";
                            $feature_product[$key]['subcategory_id'] = $products->subcategory ?? "";
                            $feature_product[$key]['category'] = $cat->name ?? "";
                            if ($products->regular_price <= $products->promotional_price) {
                                $feature_product[$key]['discount_price'] = "0";
                            } else {
                                $feature_product[$key]['discount_price'] = $products->promotional_price;
                            }
                            $feature_product[$key]['tax_type'] = $products->tax_type;
                            $feature_product[$key]['tax_rate'] = $products->tax_rate;
                            $feature_product[$key]['quantity'] = $products->quantity;
                            $feature_product[$key]['seo_keywords'] = $products->seo_keywords;
                            $feature_product[$key]['weight'] = $products->weight;
                            $feature_product[$key]['shipping_fee'] = $products->shipping_fee;
                            $variant = Veriant::convertCurrency($products->id, $store->id)->get();
                            $feature_product[$key]['variant'] = $variant;

                            $brand = Brand::where('id', $products->brand)->first();

                            $feature_product[$key]['brand_id'] = $brand->id ?? "";
                            $feature_product[$key]['brand_name'] = $brand->name ?? "";
                        }
                    } else {
                        $feature_product = [];
                    }

                    $product = array();
                    foreach ($data as $key => $products) {
                        $product[$key]['id'] = $products->id;
                        $product[$key]['name'] = $products->name;
                        $image = explode(',', $products->images);
                        $im = array();
                        foreach ($image as $keys => $img) {
                            $im[] = $img;
                        }

                        $rating = Review::where('product_id', $products->id)->where('rating', '!=',
                            null)->sum('rating');
                        $number_rating = Review::where('product_id', $products->id)->where('rating', '!=',
                            null)->count();

                        if ($rating != null && $number_rating != null) {
                            $rating = ($rating / $number_rating);
                        }

                        $product[$key]['rating'] = $rating ?? 0;
                        $product[$key]['number_rating'] = $number_rating ?? 0;
                        $product[$key]['slug'] = generateSlug($products->name);

                        $product[$key]['image'] = $im;
                        $product[$key]['description'] = mb_substr($products->description, 0, 216);
                        $product[$key]['regular_price'] = $products->regular_price;
                        $product[$key]['discount_type'] = $products->discount_type;
                        $cat = Category::where('id', $products->category)->where('status', 'active')->first();
                        $product[$key]['category_id'] = $products->category ?? "";
                        $product[$key]['subcategory_id'] = $products->subcategory ?? "";
                        $product[$key]['category'] = $cat->name ?? "";
                        if ($products->regular_price <= $products->promotional_price) {
                            $product[$key]['discount_price'] = "0";
                        } else {
                            $product[$key]['discount_price'] = $products->promotional_price;
                        }
                        $product[$key]['tax_type'] = $products->tax_type;
                        $product[$key]['tax_rate'] = $products->tax_rate;
                        $product[$key]['quantity'] = $products->quantity;
                        $product[$key]['seo_keywords'] = $products->seo_keywords;
                        $product[$key]['weight'] = $products->weight;
                        $product[$key]['shipping_fee'] = $products->shipping_fee;
                        $product[$key]['SKU'] = $products->SKU;
                        $product[$key]['tags'] = $products->tags;
                        $variant = Veriant::convertCurrency($products->id, $store->id)->get();

                        $product[$key]['variant'] = $variant;

                        $brand = Brand::where('id', $products->brand)->first();

                        $product[$key]['brand_id'] = $brand->id ?? "";
                        $product[$key]['brand_name'] = $brand->name ?? "";
                    }

                    $responseData['product'] = $product;
                    $responseData['best_sell_product'] = $best_sell_product;
                    $responseData['feature_product'] = $feature_product;
                }

                // Check and set banner in response
                if (isset($heads['banner']) || count($heads) <= 0) {
                    $banner = Banner::where('store_id', $store->id)->where('status', 'active')->get();
                    $responseData['banner'] = $banner;
                }

                $designs = Design::where('store_id', $store->id)->first();
                // Check and set design in response
                if (isset($heads['design']) || count($heads) <= 0) {
                    $responseData['design'] = $designs;
                }

                // Check and set layout in response
                if (isset($heads['layout']) || count($heads) <= 0) {
                    $tps = Temposition::where('template_id', $designs->template_id)->orderBy('position', 'ASC')->get();
                    if (isset($tps) && count($tps) > 0) {
                        foreach ($tps as $key => $tp) {
                            $layout[] = $tp->name;
                        }
                    } else {
                        $layout[] = "header";
                        $layout[] = "hero_slider";
                        $layout[] = "banner";
                        $layout[] = "banner_bottom";
                        $layout[] = "feature_category";
                        $layout[] = "product";
                        $layout[] = "feature_product";
                        $layout[] = "best_sell_product";
                        $layout[] = "new_arrival";
                        $layout[] = "testimonial";
                        $layout[] = "footer";
                        $layout[] = "auth";
                    }

                    $responseData['layout'] = $layout;
                }

                // Check and set page in response
                if (isset($heads['page']) || count($heads) <= 0) {
                    $page = Page::where('store_id', $store->id)->where('status', 'active')->get();
                    $responseData['page'] = $page;
                }

                // Check and set testimonials in response
                if (isset($heads['testimonials']) || count($heads) <= 0) {
                    $testimonials = Testimonial::where('store_id', $store->id)->where('status', 'active')->get();
                    $responseData['testimonials'] = $testimonials;
                }

                // Check and set offer in response
                if (isset($heads['offer']) || count($heads) <= 0) {
                    $offer = Offer::where('store_id', $store->id)->where('status', 'active')->first();

                    $data = Product::convertCurrency($store->id)
                        ->where('products.status', 'active')
                        ->where('products.discount_type', '!=', 'no_discount')
                        ->with([
                            'getCategory' => function ($query) {
                                $query->select('id', 'name', 'status')->where('status', 'active');
                            },
                            'getSubcategory' => function ($query) {
                                $query->select('id', 'name', 'status')->where('status', 'active');
                            },
                            'getBrand' => function ($query) {
                                $query->select('id', 'name');
                            }
                        ])
                        ->withSum('reviews', 'rating')  // Adds total_rating
                        ->withCount('reviews')
                        ->orderBy('products.position', 'ASC')
                        ->get();

                    $product = $data->map(function ($product) use ($store) {
                        // Prepare each product's data
                        $images = explode(',', $product->images);
                        $averageRating = $product->reviews_count > 0 ? $product->reviews_sum_rating / $product->reviews_count : 0;

                        // Convert currency for variants
                        $variants = $product->getVariantsWithConversion($store->id)->get()->map(function ($variant) {
                            return [
                                'id' => $variant->id,
                                'pid' => $variant->pid,
                                'color' => $variant->color,
                                'size' => $variant->size,
                                'volume' => $variant->volume,
                                'unit' => $variant->unit,
                                'quantity' => $variant->quantity,
                                'additional_price' => $variant->additional_price,
                                'image' => $variant->image,
                                'symbol' => $variant->symbol,
                                'code' => $variant->code,
                            ];
                        });

                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'image' => $images,
                            'rating' => $averageRating,
                            'number_rating' => $product->reviews_count,
                            'slug' => generateSlug($product->name),
                            'description' => mb_substr($product->description, 0, 216),
                            'regular_price' => $product->regular_price,
                            'discount_type' => $product->discount_type,
                            'discount_price' => $product->regular_price <= $product->promotional_price ? "0" : $product->promotional_price,
                            'category_id' => $product->category,
                            'subcategory_id' => $product->subcategory,
                            'category' => $product->getCategory->name ?? "",
                            'subcategory' => $product->getSubcategory->name ?? "",
                            'tax_type' => $product->tax_type,
                            'tax_rate' => $product->tax_rate,
                            'quantity' => $product->quantity,
                            'seo_keywords' => $product->seo_keywords,
                            'weight' => $product->weight,
                            'shipping_fee' => $product->shipping_fee,
                            'SKU' => $product->SKU,
                            'tags' => $product->tags,
                            'position' => $product->position,
                            'variant' => $variants,
                            'brand_id' => $product->brand,
                            'brand_name' => $product->getBrand->name ?? "",
                            'supplier_id' => $product->supplier,
                            'supplier_name' => $product->getSupplier->name ?? "",
                            'created_at' => $product->created_at ?? ""
                        ];
                    });

                    if (isset($offer)) {
                        $orf['name'] = $offer->name;
                        $orf['start_date'] = $offer->start_date;
                        $orf['end_date'] = $offer->end_date;
                        $orf['status'] = $offer->status;
                        $orf['products'] = $product;
                    } else {
                        $orf = [];
                    }
                    $responseData['offer'] = $orf;
                }

                // Check and set campaign in response
                if (isset($heads['campaign']) || count($heads) <= 0) {
                    $campaign = Campaign::convertCurrency($store->id)->where('campaigns.store_id',
                        $store->id)->where('campaigns.status',
                        'active')->get();
                    $responseData['campaign'] = $campaign;
                }

                // Check and set layoutposition position in response
                if (isset($heads['layoutposition']) || count($heads) <= 0) {
                    $tempPosition = Temposition::where('template_id', $store->template_id)->get();
                    if (count($tempPosition)) {
                        foreach ($tempPosition as $position) {
                            $layoutposition[$position->name] = $position->position;
                        }
                    }

                    $designPosition = DB::table('design_positions')
                        ->where('store_id', $store->id)
                        ->orderBy('position', 'asc')
                        ->get();
                    if (count($designPosition)) {
                        foreach ($designPosition as $position) {
                            $layoutposition[$position->name] = $position->position;
                        }
                    }

                    $responseData['layoutposition'] = $layoutposition;
                }

                // Check and set brand in response
                if (isset($heads['brand']) || count($heads) <= 0) {
                    $brand = Brand::where('store_id', $store->id)->get(['id', 'name', 'image']);
                    $responseData['brand'] = $brand;
                }

                return response()->json($responseData);
            } else {
                return response()->json(['error' => 'Not Found']);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Not Found']);
        }
    }

    public function getDomainSection($name, $section)
    {
        try {
            if (empty($name) || is_null($name)) {
                return response()->json(['status' => false, 'message' => 'Domain name is required']);
            }

            if (empty($section) || is_null($section)) {
                return response()->json(['status' => false, 'message' => 'Section name is required']);
            }

            $store = Store::where('url', $name)->where('expiry_date', '>=', Carbon::now())->first();

            if (isset($store)) {
                switch ($section) {
                    case 'layout':
                        $tempPositions = Temposition::where('template_id', $store->template_id)
                            ->pluck('position', 'name')
                            ->toArray();

                        $designPositions = DB::table('design_positions')
                            ->where('store_id', $store->id)
                            ->orderBy('position', 'asc')
                            ->pluck('position', 'name')
                            ->toArray();

                        $data = array_merge($tempPositions, $designPositions);
                        asort($data);

                        return response()->json(['status' => true, 'message' => 'Success', 'data' => array_keys($data)]);

                    case 'design':
                        $data = Design::where('store_id', $store->id)->first();
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $data]);

                    case 'menu':
                        $menu = Menu::where('store_id', $store->id)->orderBy('sort', 'ASC')->get();
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $menu]);

                    case 'slider':
                        $slider = Slider::where('store_id', $store->id)->where('status', 'active')->orderBy('position', 'ASC')->get();
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $slider]);

                    case 'banner':
                        $banner = Banner::where('store_id', $store->id)->where('status', 'active')->get();
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $banner]);

                    case 'page':
                        $page = Page::where('store_id', $store->id)->where('status', 'active')->get();
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $page]);

                    case 'best_sell_product':
                        $data = Product::convertCurrency($store->id)
                            ->where('products.status', 'active')
                            ->where('products.best_sell', 1)
                            ->with([
                                'getCategory' => function ($query) {
                                    $query->select('id', 'name', 'status')->where('status', 'active');
                                },
                                'getSubcategory' => function ($query) {
                                    $query->select('id', 'name', 'status')->where('status', 'active');
                                },
                                'getBrand' => function ($query) {
                                    $query->select('id', 'name');
                                }
                            ])
                            ->withSum('reviews', 'rating')  // Adds total_rating
                            ->withCount('reviews')
                            ->orderBy('products.position', 'ASC')
                            ->inRandomOrder()
                            ->limit(10)
                            ->get();

                        $best_sell_product = $this->getProductResponse($data, $store->id);

                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $best_sell_product]);

                    case 'feature_product':
                        $data = Product::convertCurrency($store->id)
                            ->where('products.status', 'active')
                            ->where('products.feature', 1)
                            ->with([
                                'getCategory' => function ($query) {
                                    $query->select('id', 'name', 'status')->where('status', 'active');
                                },
                                'getSubcategory' => function ($query) {
                                    $query->select('id', 'name', 'status')->where('status', 'active');
                                },
                                'getBrand' => function ($query) {
                                    $query->select('id', 'name');
                                }
                            ])
                            ->withSum('reviews', 'rating')  // Adds total_rating
                            ->withCount('reviews')
                            ->orderBy('products.position', 'ASC')
                            ->inRandomOrder()
                            ->limit(10)
                            ->get();

                        $feature_products = $this->getProductResponse($data, $store->id);

                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $feature_products]);

                    case 'testimonial':
                        $testimonials = Testimonial::where('store_id', $store->id)->where('status', 'active')->get();
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $testimonials]);

                    case 'product':
                        $data = Product::convertCurrency($store->id)
                            ->where('products.status', 'active')
                            ->with([
                                'getCategory' => function ($query) {
                                    $query->select('id', 'name', 'status')->where('status', 'active');
                                },
                                'getSubcategory' => function ($query) {
                                    $query->select('id', 'name', 'status')->where('status', 'active');
                                },
                                'getBrand' => function ($query) {
                                    $query->select('id', 'name');
                                }
                            ])
                            ->withSum('reviews', 'rating')  // Adds total_rating
                            ->withCount('reviews')
                            ->orderBy('products.position', 'ASC')
                            ->inRandomOrder()
                            ->limit(10)
                            ->get();

                        $product = $this->getProductResponse($data, $store->id);

                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $product]);

                    case 'brand':
                        $brand = Brand::where('store_id', $store->id)->get(['id', 'name', 'image']);
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $brand]);

                    case 'campaign':
                        $campaign = Campaign::convertCurrency($store->id)->where('campaigns.store_id', $store->id)->where('campaigns.status', 'active')->get();
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $campaign]);

                    case 'category':
                        $categories = Category::where('store_id', $store->id)
                            ->where('parent', 0)
                            ->where('status', 'active')
                            ->orderBy('position', 'ASC')
                            ->with([
                                'subcategories' => function ($query) use ($store) {
                                    $query->where('store_id', $store->id)
                                        ->where('status', 'active'); // Optional: Add sorting if needed
                                }
                            ])
                            ->get();

                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $categories]);

                    case 'subcategory':
                        $subcategory = Category::where('store_id', $store->id)->where('parent', '!=', '0')->where('status', 'active')->orderBy('position', 'ASC')->get();
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $subcategory]);

                    case 'offer':
                        $offer = Offer::where('store_id', $store->id)->where('status', 'active')->first();

                        $data = Product::convertCurrency($store->id)
                            ->where('products.status', 'active')
                            ->where('products.discount_type', '!=', 'no_discount')
                            ->with([
                                'getCategory' => function ($query) {
                                    $query->select('id', 'name', 'status')->where('status', 'active');
                                },
                                'getSubcategory' => function ($query) {
                                    $query->select('id', 'name', 'status')->where('status', 'active');
                                },
                                'getBrand' => function ($query) {
                                    $query->select('id', 'name');
                                }
                            ])
                            ->withSum('reviews', 'rating')  // Adds total_rating
                            ->withCount('reviews')
                            ->orderBy('products.position', 'ASC')
                            ->get();

                        $product = $this->getProductResponse($data, $store->id);

                        $response = [];
                        if (isset($offer)) {
                            $response = [
                                'name' => $offer->name,
                                'start_date' => $offer->start_date,
                                'end_date' => $offer->end_date,
                                'status' => $offer->status,
                                'products' => $product,
                            ];

                        }
                        return response()->json(['status' => true, 'message' => 'Success', 'data' => $response]);

                    default:
                        return response()->json(['status' => true, 'message' => 'Data not found!', 'data' => $store]);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Store not found!']);
            }
        } catch (\Exception $exception) {
            return serverError();
        }
    }

    public function getProductResponse($data, $store_id)
    {
        return $data->map(function ($product) use ($store_id) {
            // Prepare each product's data
            $images = explode(',', $product->images);
            $averageRating = $product->reviews_count > 0 ? $product->reviews_sum_rating / $product->reviews_count : 0;

            // Convert currency for variants
            $variants = $product->getVariantsWithConversion($store_id)->get()->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'pid' => $variant->pid,
                    'color' => $variant->color,
                    'size' => $variant->size,
                    'volume' => $variant->volume,
                    'unit' => $variant->unit,
                    'quantity' => $variant->quantity,
                    'additional_price' => $variant->additional_price,
                    'image' => $variant->image,
                    'color_image' => $variant->color_image,
                    'symbol' => $variant->symbol,
                    'code' => $variant->code,
                ];
            });

            $discount_price = $product->regular_price <= $product->promotional_price ? "0" : $product->promotional_price;

            $calculate_regular_price = getPrice($product->regular_price, $discount_price, $product->discount_type);
            $campaign_offer = $this->checkProductOffer($product, $calculate_regular_price, $store_id);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $images,
                'rating' => $averageRating,
                'number_rating' => $product->reviews_count,
                'slug' => generateSlug($product->name),
                'description' => mb_substr($product->description, 0, 216),
                'regular_price' => (float)$product->regular_price,
                'calculate_regular_price' => (float)$calculate_regular_price ?? (float)$product->regular_price ?? "",
                'product_offer' => $campaign_offer ?? "",
                'discount_type' => $product->discount_type,
                'discount_price' => (float)$discount_price,
                'category_id' => $product->category ?? "",
                'subcategory_id' => $product->subcategory ?? "",
                'category' => $product->getCategory->name ?? "",
                'subcategory' => $product->getSubcategory->name ?? "",
                'tax_type' => $product->tax_type,
                'tax_rate' => (float)$product->tax_rate,
                'quantity' => (float)$product->quantity,
                'seo_keywords' => $product->seo_keywords,
                'weight' => $product->weight,
                'shipping_fee' => (float)$product->shipping_fee,
                'SKU' => $product->SKU,
                'tags' => $product->tags,
                'position' => $product->position,
                'variant' => $variants,
                'brand_id' => $product->brand,
                'brand_name' => $product->getBrand->name ?? "",
                'supplier_id' => $product->supplier,
                'supplier_name' => $product->getSupplier->name ?? "",
                'created_at' => $product->created_at ?? ""
            ];
        });
    }

    public function getsubdomainnameNew(Request $request)
    {
        try {
            $name = $request->name;
            $store = Store::where('url', $name)->where('expiry_date', '>=', Carbon::now())->first();

            $headInput = isset($request->head) ? $request->head : [];
            if (!empty($headInput)) {
                $headInput = explode(",", $headInput);
            } else {
                $headInput = [];
            }

            if (isset($store)) {
                $responseData = [];

                $responseData['store_id'] = $store->id;

                $heads = array();
                foreach ($headInput as $key => $value) {
                    $heads[$value] = $key;
                }

                // Check and set menu in response
                if (isset($heads['store']) || count($heads) <= 0) {
                    $responseData['store'] = $store;
                }

                // Check and set menu in response
                if (isset($heads['menu']) || count($heads) <= 0) {
                    $lists = DB::table("menus")->where('store_id', $store->id)->orderBy("sort")->get();

                    $menulist = [
                        'Home',
                        'Shop',
                        'About',
                        'Contact',
                        'Category',
                        'Helps',
                        'Blog',
                        'Offer',
                    ];

                    if (count($lists) == 0) {
                        foreach ($menulist as $menu) {
                            $status = $menu == "Home" || $menu == "Shop" || $menu == "Contact" ? 1 : 0;
                            $slug = $menu == "Home" || $menu == "home" ? '' : generateSlug($menu);

                            \App\Models\Menu::create([
                                'store_id' => $store->id,
                                'uid' => $store->user_id,
                                'url' => $slug,
                                'name' => $menu,
                                'status' => $status,
                                'sort' => 1,
                                'creator' => $store->user_id,
                                'editor' => $store->user_id,
                                'customer_id' => $store->customer_id,
                            ]);
                        }
                    }

                    $menu = Menu::where('store_id', $store->id)->orderBy('sort', 'ASC')->get();
                    $responseData['menu'] = $menu;
                }

                // Check and set headersetting in response
                if (isset($heads['headersetting']) || count($heads) <= 0) {
                    $headersetting = Headersetting::convertCurrency($store->id)->first();
                    $headersetting['gtm'] = QuickLogin::where('modulus_id', 10)->where('store_id',
                        $store->id)->first(); // google tag manager api
                    $headersetting['facebook_pixel'] = QuickLogin::where('modulus_id', 11)->where('store_id',
                        $store->id)->first()->facebook_pixel ?? null; //for facebook pixel api

                    $responseData['headersetting'] = $headersetting;
                }

                // Check and set category in response
                if (isset($heads['category']) || count($heads) <= 0) {
                    $category = Category::where('store_id', $store->id)->where('parent', 0)->where('status',
                        'active')->orderBy('position', 'ASC')->get();
                    if (isset($category) && count($category) > 0) {
                        foreach ($category as $key => $cat) {
                            $cat['cat'] = $cat;
                            $subcat = Category::where('store_id', $store->id)->where('parent', $cat->id)->get();
                            if (isset($subcat) && count($subcat) > 0) {
                                $cat['cat'] = $subcat;
                            } else {
                                $cat['cat'] = null;
                            }
                        }
                    } else {
                        $cat[] = null;
                    }

                    $responseData['category'] = $category;
                    $responseData['cat'] = $cat;
                }

                // Check and set subcategory in response
                if (isset($heads['subcategory']) || count($heads) <= 0) {
                    $subcategory = Category::where('store_id', $store->id)->where('parent', '!=', '0')->where('status',
                        'active')->orderBy('position', 'ASC')->get();

                    $responseData['subcategory'] = $subcategory;
                }

                // Check and set slider in response
                if (isset($heads['slider']) || count($heads) <= 0) {
                    $slider = Slider::where('store_id', $store->id)->where('status', 'active')->orderBy('position',
                        'ASC')->get();
                    $responseData['slider'] = $slider;
                }

                // Check and set banner in response
                if (isset($heads['banner']) || count($heads) <= 0) {
                    $banner = Banner::where('store_id', $store->id)->where('status', 'active')->get();
                    $responseData['banner'] = $banner;
                }

                $designs = Design::where('store_id', $store->id)->first();
                // Check and set design in response
                if (isset($heads['design']) || count($heads) <= 0) {
                    $responseData['design'] = $designs;
                }

                // Check and set layout in response
                if (isset($heads['layout']) || count($heads) <= 0) {
                    $tps = Temposition::where('template_id', $designs->template_id)->orderBy('position', 'ASC')->get();
                    if (isset($tps) && count($tps) > 0) {
                        foreach ($tps as $key => $tp) {
                            $layout[] = $tp->name;
                        }
                    } else {
                        $layout[] = "header";
                        $layout[] = "hero_slider";
                        $layout[] = "banner";
                        $layout[] = "banner_bottom";
                        $layout[] = "feature_category";
                        $layout[] = "product";
                        $layout[] = "feature_product";
                        $layout[] = "best_sell_product";
                        $layout[] = "new_arrival";
                        $layout[] = "testimonial";
                        $layout[] = "footer";
                        $layout[] = "auth";
                    }

                    $responseData['layout'] = $layout;
                }

                // Check and set page in response
                if (isset($heads['page']) || count($heads) <= 0) {
                    $page = Page::where('store_id', $store->id)->where('status', 'active')->get();
                    $responseData['page'] = $page;
                }

                // Check and set testimonials in response
                if (isset($heads['testimonials']) || count($heads) <= 0) {
                    $testimonials = Testimonial::where('store_id', $store->id)->where('status', 'active')->get();
                    $responseData['testimonials'] = $testimonials;
                }

                // Check and set campaign in response
                if (isset($heads['campaign']) || count($heads) <= 0) {
                    $campaign = Campaign::convertCurrency($store->id)->where('campaigns.store_id',
                        $store->id)->where('campaigns.status',
                        'active')->get();
                    $responseData['campaign'] = $campaign;
                }

                // Check and set layoutposition position in response
                if (isset($heads['layoutposition']) || count($heads) <= 0) {
                    $tempPosition = Temposition::where('template_id', $store->template_id)->get();
                    if (count($tempPosition)) {
                        foreach ($tempPosition as $position) {
                            $layoutposition[$position->name] = $position->position;
                        }
                    }

                    $designPosition = DB::table('design_positions')
                        ->where('store_id', $store->id)
                        ->orderBy('position', 'asc')
                        ->get();
                    if (count($designPosition)) {
                        foreach ($designPosition as $position) {
                            $layoutposition[$position->name] = $position->position;
                        }
                    }

                    $responseData['layoutposition'] = $layoutposition;
                }

                // Check and set brand in response
                if (isset($heads['brand']) || count($heads) <= 0) {
                    $brand = Brand::where('store_id', $store->id)->get(['id', 'name', 'image']);
                    $responseData['brand'] = $brand;
                }

                return response()->json($responseData);
            } else {
                return response()->json(['error' => 'Not Found']);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Not Found']);
        }
    }

    public function getAllBrandProducts(Request $request)
    {
        try {
            $brand = Brand::where('id', $request->id)->first();

            if (empty($brand)) {
                return response()->json([
                    'status' => '404',
                    'message' => 'Brand id not found.'
                ]);
            }

            $perPage = 10;

            $products = Product::select(
                'products.id',
                'products.name',
                'products.regular_price',
                'products.discount_type',
                'products.promotional_price',
                'products.tax_type',
                'products.tax_rate',
                'products.quantity',
                'products.seo_keywords',
                'products.weight',
                'products.video_link',
                'products.shipping_fee',
                'products.images as image',
                'products.category',
                'products.subcategory',
                'products.tags',
                'products.position',
                'products.status',
                'products.best_sell',
                'products.feature',
                'products.uid',
                'products.customer_id',
                'products.store_id',
                'products.creator',
                'products.editor',
                'products.brand',
                'products.supplier',
                'products.cost',
                'products.pse',
                'products.pse_status',
                'products.pse_cat_id',
                'products.barcode',
                'products.ask_price',
                'products.created_at',
                'products.commission',
                'products.updated_at',
                'products.SKU',
                'brands.id as brand_id',
                'brands.name as brand_name'
            )
                ->leftJoin('brands', 'brands.id', '=', 'products.brand')
                ->where('products.status', '!=', 'RecycleBin')
                ->where('brands.id', '=', $brand->id)
                ->Paginate($perPage)->onEachSide(1)->setPath('');

            if (empty($brand)) {
                return response()->json([
                    'status' => '200',
                    'message' => 'The have no product in this brand.'
                ]);
            }

            // Convert images string to array
            $products->getCollection()->transform(function ($product) {
                $product->image = [trim($product->image, '"')];

                // Check if variants exist for the product
                $variants = Veriant::convertCurrency($product->id)->get();

                // If variants exist, add them to the product, otherwise keep the "variant" array empty
                $product->variant = $variants->isEmpty() ? [] : $variants;
                return $product;
            });

            return response()->json(['data' => $products]);
        } catch (QueryException $e) {
            // Handle database query exception
            $response = [
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ];
        } catch (Exception $e) {
            // Handle other exceptions
            $response = [
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($response);
    }

    public function campaign(Request $request)
    {
        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $store_id = $store_id ?? $request->store_id ?? "";

        if (!isset($store_id) || empty($store_id)) {
            return response()->json(['status' => false, 'message' => "Store id not found"]);
        }

        $data = []; // Define $data variable here
        $campaign = Campaign::convertCurrency($store_id)
            ->where('campaigns.store_id', $request->store_id)
            ->where('campaigns.status', 'active')
            ->get();
        $keys = 0;
        $k = 0;
        foreach ($campaign as $value) {
            if (!empty($value->products)) {
                $pros = explode(',', $value->products);
                $value['campaignProducts'] = $this->__product($pros, $request->store_id, 0);
                $data['campaign'][$keys] = $value;
                $keys++;
            } else {
                $pros = explode(',', $value->category);
                $value['campaignProducts'] = $this->__productCat($pros, $request->store_id, 0);
                $data['campaign'][$keys] = $value;
                $keys++;
            }
        }

        return response()->json(['status' => '200', 'yourData' => $data]);
    }

    protected function __product($opss, $store_id, $cat)
    {
        $op = [];
        if (!empty($opss)) {
            $keyf = 0;
            $f = 0;
            foreach ($opss as $pro) {
                $products = Product::where('store_id', $store_id)->where('id', $pro)->where('status',
                    'active')->first();

                if (isset($products)) {
                    $op[$keyf]['id'] = $products->id;
                    $op[$keyf]['name'] = $products->name;
                    $image = explode(',', $products->images);
                    $im = array();
                    foreach ($image as $img) {
                        $im[] = $img;
                    }

                    $rating = Review::where('product_id', $products->id)->where('rating', '!=',
                        null)->sum('rating');
                    $number_rating = Review::where('product_id', $products->id)->where('rating', '!=',
                        null)->count();

                    if ($rating != null && $number_rating != null) {
                        $rating = ($rating / $number_rating);
                    }

                    $op[$keyf]['rating'] = $rating ?? 0;
                    $op[$keyf]['number_rating'] = $number_rating ?? 0;
                    $op[$keyf]['slug'] = generateSlug($products->name);

                    $op[$keyf]['image'] = $im;
                    $op[$keyf]['description'] = mb_substr($products->description, 0, 216);
                    $op[$keyf]['regular_price'] = $products->regular_price;
                    $op[$keyf]['discount_type'] = $products->discount_type;
                    $cat = Category::where('id', $products->category)->where('status', 'active')->first();
                    $op[$keyf]['category_id'] = $products->category ?? "";
                    $op[$keyf]['subcategory_id'] = $products->subcategory ?? "";
                    $op[$keyf]['category'] = $cat->name ?? "";
                    if ($products->regular_price <= $products->promotional_price) {
                        $op[$keyf]['discount_price'] = "0";
                    } else {
                        $op[$keyf]['discount_price'] = $products->promotional_price;
                    }
                    $op[$keyf]['tax_type'] = $products->tax_type;
                    $op[$keyf]['tax_rate'] = $products->tax_rate;
                    $op[$keyf]['quantity'] = $products->quantity;
                    $op[$keyf]['seo_keyfwords'] = $products->seo_keywords;
                    $op[$keyf]['weight'] = $products->weight;
                    $op[$keyf]['shipping_fee'] = $products->shipping_fee;
                    $op[$keyf]['SKU'] = $products->SKU;
                    $op[$keyf]['tags'] = $products->tags;
                    $variant = Veriant::convertCurrency($products->id)->get();

                    $op[$keyf]['variant'] = $variant;
                    $keyf++;
                }
            }
        }
        return $op;
    }

    protected function __productCat($opss, $store_id, $cat)
    {
        $op = [];
        if (!empty($opss)) {
            $keyf = 0;
            foreach ($opss as $pro) {
                $prodsd = Product::where('store_id', $store_id)->where('category', $pro)->where('status',
                    'active')->get();

                foreach ($prodsd as $products) {
                    {
                        $op[$keyf]['id'] = $products->id;
                        $op[$keyf]['name'] = $products->name;
                        $image = explode(',', $products->images);
                        $im = array();
                        foreach ($image as $img) {
                            $im[] = $img;
                        }

                        $rating = Review::where('product_id', $products->id)->where('rating', '!=',
                            null)->sum('rating');
                        $number_rating = Review::where('product_id', $products->id)->where('rating', '!=',
                            null)->count();

                        if ($rating != null && $number_rating != null) {
                            $rating = ($rating / $number_rating);
                        }

                        $op[$keyf]['rating'] = $rating ?? 0;
                        $op[$keyf]['number_rating'] = $number_rating ?? 0;
                        $op[$keyf]['slug'] = generateSlug($products->name);

                        $op[$keyf]['image'] = $im;
                        $op[$keyf]['description'] = mb_substr($products->description, 0, 216);
                        $op[$keyf]['regular_price'] = $products->regular_price;
                        $op[$keyf]['discount_type'] = $products->discount_type;
                        $cat = Category::where('id', $products->category)->where('status', 'active')->first();
                        $op[$keyf]['category_id'] = $products->category ?? "";
                        $op[$keyf]['subcategory_id'] = $products->subcategory ?? "";
                        $op[$keyf]['category'] = $cat->name ?? "";
                        if ($products->regular_price <= $products->promotional_price) {
                            $op[$keyf]['discount_price'] = "0";
                        } else {
                            $op[$keyf]['discount_price'] = $products->promotional_price;
                        }
                        $op[$keyf]['tax_type'] = $products->tax_type;
                        $op[$keyf]['tax_rate'] = $products->tax_rate;
                        $op[$keyf]['quantity'] = $products->quantity;
                        $op[$keyf]['seo_keyfwords'] = $products->seo_keywords;
                        $op[$keyf]['weight'] = $products->weight;
                        $op[$keyf]['shipping_fee'] = $products->shipping_fee;
                        $op[$keyf]['SKU'] = $products->SKU;
                        $op[$keyf]['tags'] = $products->tags;
                        $variant = Veriant::convertCurrency($products->id)->get();

                        $op[$keyf]['variant'] = $variant;
                        $keyf++;
                    }
                }
            }
            return $op;
        }
    }

    function productSearch(Request $request)
    {
        $searchResult = [];
        if ($request->store_id && $request->search) {
            $data = Product::where('store_id', $request->store_id)->where('status', 'active')->where('name', 'LIKE',
                "%" . $request->search . "%")->orderBy('name', 'ASC')->limit(50)->get();

            if (isset($data) && count($data) > 0) {
                foreach ($data as $key => $products) {
                    $searchResult[$key]['id'] = $products->id;
                    $searchResult[$key]['store_id'] = $products->store_id;
                    $searchResult[$key]['name'] = $products->name;
                    $image = explode(',', $products->images);

                    $searchResult[$key]['slug'] = generateSlug($products->name);
                    $searchResult[$key]['image'] = $image[0];
                    $searchResult[$key]['regular_price'] = $products->regular_price;
                    $searchResult[$key]['discount_type'] = $products->discount_type;
                    if ($products->regular_price <= $products->promotional_price) {
                        $searchResult[$key]['discount_price'] = "0";
                    } else {
                        $searchResult[$key]['discount_price'] = $products->promotional_price;
                    }
                }
            }
            return response()->json($searchResult);
        }
        return response()->json([]);
    }

    public function getcatproduct(Request $request)
    {
        $id = $request->id;
        $cat = Category::find($id);

        if (empty($cat)) {
            return response()->json(['status' => 200, 'colors' => 'category not found']);
        }

        // Retrieve colors
        $colors = Color::where('store_id', $cat->store_id)->get(['name', 'code']);

        // Build query for products
        $productQuery = Product::convertCurrency($cat->store_id)
            ->where(function ($query) use ($id) {
                $query->where('products.category', "LIKE", "%$id%")
                    ->orWhere('products.subcategory', "LIKE", "%$id%");
            })
            ->where('products.status', 'active');

        // Apply sorting filter
        if ($request->filter) {
            $type = $request->filter;
            $sortOptions = [
                'az' => ['products.name', 'asc'],
                'za' => ['products.name', 'desc'],
                'lh' => ['products.regular_price', 'asc'],
                'hl' => ['products.regular_price', 'desc']
            ];
            if (ModulusStatus($cat->store_id, 9)) {
                $defaultSort = ['products.position', 'asc'];
            } else {
                $defaultSort = ['products.id', 'desc'];
            }
            $productQuery->orderBy(...($sortOptions[$type] ?? $defaultSort));
        } else {
            if (ModulusStatus($cat->store_id, 9)) {
                $productQuery->orderBy('products.position');
            } else {
                $productQuery->orderBy('products.id', 'desc');
            }
        }

        // Apply price filter
        if (!empty($request->priceFilter)) {
            $priceFilter = $request->priceFilter;
            $productQuery->where('products.regular_price', '<=', $priceFilter);
        }

        // Apply color filter
        if (!empty($request->colorFilter)) {
            $colorFilter = $request->colorFilter;
            if ($colorFilter) {
                $productQuery->join('veriants', 'products.id', '=', 'veriants.pid')
                    ->where('veriants.color', $colorFilter)
                    ->select('products.*', 'veriants.color', 'veriants.size')
                    ->groupBy('products.id');
            }
        }

        // Paginate products
        $data = $productQuery->paginate(8)->onEachSide(1)->setPath('');
        $store_id = $cat->store_id ?? "";

        // Process and format the product data
        $formattedData = $data->map(function ($product) use ($store_id) {
            $images = explode(',', $product->images);

            $rating = Review::where('product_id', $product->id)->whereNotNull('rating')->avg('rating');
            $numberRating = Review::where('product_id', $product->id)->whereNotNull('rating')->count();

            // Get category and subcategory names
            $category = Category::whereIn('id', explode(',', $product->category))
                ->where('status', 'active')
                ->first()->name ?? "";

            $subcategory = Category::whereIn('id', explode(',', $product->subcategory))
                ->where('status', 'active')
                ->first()->name ?? "";

            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $images,
                'description' => mb_substr($product->description, 0, 216),
                'regular_price' => $product->regular_price,
                'discount_type' => $product->discount_type,
                'category_id' => $product->category ?? "",
                'subcategory_id' => $product->subcategory ?? "",
                'category' => $category ?? "",
                'subcategory' => $subcategory ?? "",
                'discount_price' => $product->regular_price <= $product->promotional_price ? "0" : $product->promotional_price,
                'tax_type' => $product->tax_type,
                'tax_rate' => $product->tax_rate,
                'quantity' => $product->quantity,
                'seo_keywords' => $product->seo_keywords,
                'weight' => $product->weight,
                'shipping_fee' => $product->shipping_fee,
                'variant' => Veriant::convertCurrency($product->id, $store_id)->get(),
                'brand_id' => optional(Brand::find($product->brand))->id,
                'brand_name' => optional(Brand::find($product->brand))->name,
                'rating' => $rating ?? 0,
                'number_rating' => $numberRating ?? 0,
                'slug' => generateSlug($product->name),
            ];
        });

        $data->setCollection($formattedData);

        return response()->json(['data' => $data, 'colors' => $colors]);
    }


    public function getsubcatproduct(Request $request)
    {
        $id = $request->id;
        $cat = Category::find($id);

        if (!$cat) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $storeId = $cat->store_id;

        // Base query for products
        $productQuery = Product::convertCurrency($storeId)
            ->where(function ($query) use ($id) {
                $query->where('products.category', "LIKE", "%$id%")
                    ->orWhere('products.subcategory', "LIKE", "%$id%");
            })
            ->where('products.status', 'active');

        // Apply sorting
        if ($request->filter) {
            $type = $request->filter;
            switch ($type) {
                case 'az':
                    $productQuery->orderBy('products.name', 'asc');
                    break;
                case 'za':
                    $productQuery->orderBy('products.name', 'desc');
                    break;
                case 'lh':
                    $productQuery->orderBy('products.regular_price', 'asc');
                    break;
                case 'hl':
                    $productQuery->orderBy('products.regular_price', 'desc');
                    break;
                default:
                    if (ModulusStatus($storeId, 9)) {
                        $productQuery->orderBy('products.position');
                    } else {
                        $productQuery->orderBy('products.id', 'desc');
                    }
                    break;
            }
        } else {
            if (ModulusStatus($storeId, 9)) {
                $productQuery->orderBy('products.position');
            } else {
                $productQuery->orderBy('products.id', 'desc');
            }
        }

        // Apply price filter if present
        if (!empty($request->priceFilter)) {
            $productQuery->where('products.regular_price', '<=', $request->priceFilter);
        }

        // Apply color filter if present
        if (!empty($request->colorFilter)) {
            $productQuery->join('veriants', 'products.id', '=', 'veriants.pid')
                ->where('veriants.color', $request->colorFilter)
                ->select('products.*', 'veriants.color', 'veriants.size')
                ->groupBy('products.id');
        }

        // Paginate and fetch data
        $data = $productQuery->orderBy('products.id', 'desc')->paginate(8)->onEachSide(1);

        $colors = Color::where('store_id', $storeId)->get(['name', 'code']);

        // Process products
        $data->getCollection()->transform(function ($product) {
            $product->image = explode(',', $product->images);
            $product->rating = Review::where('product_id', $product->id)->whereNotNull('rating')->avg('rating') ?? 0;
            $product->number_rating = Review::where('product_id', $product->id)->whereNotNull('rating')->count() ?? 0;
            $product->slug = generateSlug($product->name);
            $product->description = mb_substr($product->description, 0, 216);

            $category = Category::whereIn('id', explode(',', $product->category))
                ->where('status', 'active')
                ->first()->name ?? "";
            $subcategory = Category::whereIn('id', explode(',', $product->subcategory))
                ->where('status', 'active')
                ->first()->name ?? "";

            $product->category_name = $category->name ?? "";
            $product->subcategory_name = $subCategory->name ?? "";
            $product->category_id = $product->category ?? "";
            $product->subcategory_id = $product->subcategory ?? "";
            $product->category = $category ?? "";
            $product->subcategory = $subcategory ?? "";

            $product->discount_price = $product->regular_price <= $product->promotional_price ? "0" : $product->promotional_price;
            $product->brand_name = Brand::find($product->brand)->name ?? null;

            $product->variant = Veriant::convertCurrency($product->id)->get();

            return $product;
        });

        // Return response
        return response()->json(['data' => $data, 'colors' => $colors]);
    }


    public function verifycoupon(Request $request)
    {
        $store_id = $request->store_id;
        $code = $request->code ?? null;
        $orderCoupon = Order::where('uid', $request->user_id)->where('coupon', $request->code)->where('store_id',
            $store_id)->count();
        $coupon = Coupon::where('store_id', $store_id)->where('status', 'active')->where('code',
            $code)->whereDate('end_date', '>=', Carbon::today()->toDateString())->first();

        if (isset($coupon)) {
            if ($coupon->max_use > $orderCoupon) {
                return response()->json($coupon);
            }
            return response()->json(['error' => 'Sorry!  You exit the MAXIMUM limit.']);
        } else {
            return response()->json(['error' => 'Sorry! Currently we can"t accept this coupon.']);
        }
    }

    /***
     * Check store coupon available or not
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function availableCoupon(Request $request)
    {
        $store_id = $request->store_id;
        $coupon = Coupon::where('store_id', $store_id)->where('status', 'active')->get();

        if (count($coupon) > 0) {
            return response()->json(['status' => true, "message" => "Coupon is available"]);
        } else {
            return response()->json(['status' => false, "message" => "Coupon is not available"]);
        }
    }

    public function adminVerifyCoupon(Request $request)
    {
        $store_id = $request->store_id;
        $code = $request->code;
        $tokens = Paymenttoken::where('token', $request->token)->first();
        $user_id = $tokens->uid;
        $ordersCoupon = AddonsOrder::where('user_id', $user_id)->where('coupon', $request->code)->count();
        $coupon = AdminCoupon::where('status', 'active')->where('code', $code)->whereDate('end_date', '>=',
            Carbon::today()->toDateString())->first();

        if (isset($coupon)) {

            if ($coupon->max_use > $ordersCoupon) {
                return response()->json($coupon);
            }
            return response()->json(['error' => 'Sorry!  You exit the MAXIMUM limit.']);
        } else {
            return response()->json(['error' => 'Sorry! Currently we can"t accept this coupon.']);
        }
    }


    public function getdetails(Request $request)
    {
        $store_id = $request->store_id;
        $product_id = $request->product_id;
        $products = Product::convertCurrency($store_id)->where('products.id', $product_id)->where('products.status', 'active')->first();

        if (isset($products)) {
            $product['id'] = $products->id;
            $product['name'] = $products->name;
            $image = explode(',', $products->images);
            $im = array();
            foreach ($image as $keys => $img) {
                $im[] = $img;
            }
            $rating = Review::where('product_id', $products->id)->where('rating', '!=', null)->sum('rating');
            $number_rating = Review::where('product_id', $products->id)->where('rating', '!=', null)->count();

            if ($rating != null && $number_rating != null) {
                $rating = ($rating / $number_rating);
            }

            $product['rating'] = $rating ?? 0;
            $product['number_rating'] = $number_rating ?? 0;
            $product['slug'] = generateSlug($products->name);

            $product['image'] = $im;
            $product['description'] = $products->description;
            $product['regular_price'] = $products->regular_price;
            $product['discount_type'] = $products->discount_type;
            $product['ask_price'] = $products->ask_price;
            if ($products->regular_price <= $products->promotional_price) {
                $product['discount_price'] = "0";
            } else {
                $product['discount_price'] = $products->promotional_price;
            }

            $category = Category::whereIn('id', explode(',', $products->category))
                ->where('status', 'active')
                ->first()->name ?? "";
            $subcategory = Category::whereIn('id', explode(',', $products->subcategory))
                ->where('status', 'active')
                ->first()->name ?? "";

            $product['category_id'] = $products->category ?? "";
            $product['subcategory_id'] = $products->subcategory ?? "";
            $product['category'] = $category ?? "";
            $product['subcategory'] = $subcategory ?? null;

            $product['tax_type'] = $products->tax_type;
            $product['tax_rate'] = $products->tax_rate;
            $product['quantity'] = $products->quantity;
            $product['seo_keywords'] = $products->seo_keywords;
            $product['tags'] = $products->tags;
            $product['weight'] = $products->weight;
            $product['video_link'] = $products->video_link;
            $product['SKU'] = $products->SKU;
            $product['shipping_fee'] = $products->shipping_fee;
            $product['brand_id'] = $products->brand;
            $product['brand_name'] = $products->getBrand->name ?? "";
            $product['supplier_id'] = $products->supplier;
            $product['supplier_name'] = $products->getSupplier->name ?? "";
            $variant = Veriant::with('getColor')->convertCurrency($products->id, $store_id)->get();

            if (isset($variant) && count($variant) > 0) {
                $vrcolor = array();
                $vrcolor1 = array();
                foreach ($variant as $key => $vr) {
                    if (isset($vr->color)) {
                        $vrcolor[] = $vr->color;
                        $vrcolor1[] = [
                            'color' => $vr->color,
                            'color_name' => $vr->getColor->name ?? "",
                            'color_image' => $vr->color_image,
                        ];
                    }
                }
                $vsr = array_unique($vrcolor);

                $uniqueColors = collect($vrcolor1)
                    ->unique('color')
                    ->values()
                    ->toArray();

                foreach ($vsr as $vrr) {
                    $vrr1[] = $vrr;
                }
                if (isset($vrr1) && count($vrr1) > 0) {
                    return response()->json(['product' => $product, 'variant' => $variant, 'vrcolor' => $vrr1, 'vrcolorimage' => $uniqueColors]);
                } else {
                    return response()->json(['product' => $product, 'variant' => $variant]);
                }
            } else {
                return response()->json(['product' => $product, 'variant' => $variant]);
            }
        } else {
            return response()->json(['error' => 'Coupon Invalid']);
        }
    }

    public function plandetails(Request $request)
    {
        $visitor = getVisitorInfo();
        $timeZone = $request->timeZone ?? "";

        $plan = Plan::with('details')
            ->whereNotIn('id', [8, 9])
            ->where('status', 'active');
        $columns = [
            'id',
            'name',
            'subtitle',
            'branch',
            'staff',
            'product',
            'category',
            'sub_category',
            'inventory',
            'google_ad',
            'order',
            'website_setup',
            'advance_report',
            'position',
            'status',
        ];
        if ((isset($visitor->countryCode) && $visitor->countryCode == 'BD') || $timeZone == "Asia/Dhaka") {
            $columns = array_merge($columns, [
                'price',
                'discount_type',
                'onedis as one_month_discount',
                'sixdis as six_month_discount',
                'twelvedis as twelve_month_discount',
                'twentyfourdis as twenty_four_month_discount',
                DB::raw("'৳' as symbol"),
            ]);
        } else {
            $columns = array_merge($columns, [
                'usd_price as price',
                'usd_discount_type as discount_type',
                'usd_1_dis as one_month_discount',
                'usd_6_dis as six_month_discount',
                'usd_12_dis as twelve_month_discount',
                'usd_24_dis as twenty_four_month_discount',
                DB::raw("'$' as symbol"),
            ]);
        }
        $plans = $plan->select($columns)
            ->orderBy('position', 'ASC')
            ->get();
//        $posplan = Posplan::where('status', 'active')->orderBy('position', 'ASC')->get();
//        $digitalplan = Digitalplan::where('status', 'active')->orderBy('position', 'ASC')->get();

        return response()->json([
            'website_Plan' => $plans,
//            'Pos_Plan' => $posplan,
//            'Digital_Plan' => $digitalplan
        ]);
    }

    public function pages(Request $request)
    {
        $page = Page::where('slug', $request->slug)->where('store_id', $request->store_id)->first();
        if (isset($page)) {
            return response()->json($page);
        } else {
            $page = [];
            return response()->json($page);
        }
    }

    public function relatedproduct(Request $request)
    {
        $productss = Product::find($request->id);
        if (isset($productss)) {
            $rpros = Product::convertCurrency($productss->store_id)
                ->where('products.category', $productss->category)
                ->where('products.status', 'active')
                ->get();
            if (isset($rpros)) {
                foreach ($rpros as $key => $rpro) {
                    $product[$key]['id'] = $rpro->id;
                    $product[$key]['name'] = $rpro->name;
                    $image = explode(',', $rpro->images);
                    $im = array();
                    foreach ($image as $keys => $img) {
                        $im[] = $img;
                    }

                    $rating = Review::where('product_id', $rpro->id)->where('rating', '!=', null)->sum('rating');
                    $number_rating = Review::where('product_id', $rpro->id)->where('rating', '!=', null)->count();

                    if ($rating != null && $number_rating != null) {
                        $rating = ($rating / $number_rating);
                    }
                    $product[$key]['rating'] = $rating ?? 0;
                    $product[$key]['number_rating'] = $number_rating ?? 0;

                    $product[$key]['slug'] = generateSlug($rpro->name);
                    $product[$key]['image'] = $im;
                    $product[$key]['description'] = mb_substr($rpro->description, 0, 216);

                    $product[$key]['regular_price'] = $rpro->regular_price;
                    $product[$key]['discount_type'] = $rpro->discount_type;
                    if ($rpro->regular_price <= $rpro->promotional_price) {
                        $product[$key]['discount_price'] = "0";
                    } else {
                        $product[$key]['discount_price'] = $rpro->promotional_price;
                    }

                    $product[$key]['category_id'] = $rpro->category ?? "";
                    $product[$key]['subcategory_id'] = $rpro->subcategory ?? "";

                    $category = Category::whereIn('id', explode(',', $rpro->category))
                        ->where('status', 'active')
                        ->first()->name ?? "";
                    $subcategory = Category::whereIn('id', explode(',', $rpro->subcategory))
                        ->where('status', 'active')
                        ->first()->name ?? "";

                    $product[$key]['category'] = $category ?? "";
                    $product[$key]['subcategory'] = $subcategory ?? null;

                    $product[$key]['tax_type'] = $rpro->tax_type;
                    $product[$key]['tax_rate'] = $rpro->tax_rate;
                    $product[$key]['quantity'] = $rpro->quantity;
                    $product[$key]['seo_keywords'] = $rpro->seo_keywords;
                    $product[$key]['weight'] = $rpro->weight;
                    $product[$key]['shipping_fee'] = $rpro->shipping_fee;
                    $product[$key]['ask_price'] = $rpro->ask_price;
                    $variant = Veriant::convertCurrency($rpro->id)->get();
                    $product[$key]['variant'] = $variant;
                }
                return response()->json($product);
            } else {
                $product = [];
                return response()->json($product);
            }
        } else {
            $product = [];
            return response()->json($product);
        }
    }

    public function getreview(Request $request)
    {
        $reviewss = Review::where('product_id', $request->product_id)->get();
        if (isset($reviewss) && count($reviewss) > 0) {
            foreach ($reviewss as $key => $rv) {
                $user = User::find($rv->uid);
                $review[$key]['id'] = $rv->id ?? '';
                $review[$key]['name'] = $rv->name ?? '';
                $review[$key]['image'] = $user->image ?? '';
                $review[$key]['ucd'] = $user->created_at ?? '';
                $review[$key]['comment'] = $rv->comment ?? '';
                $review[$key]['rating'] = $rv->rating ?? '';
                $review[$key]['cd'] = $rv->created_at ?? '';
            }
            return response()->json($review);
        } else {
            return response()->json(['error' => 'No Review Found']);
        }
    }

    public function checkoffer(Request $request)
    {
        $id = $request->id;
        $store_id = $request->store_id;
        $productsss = Product::find($id);
        $campaign1s = Campaign::convertCurrency($store_id)
            ->where('campaigns.campaign_type', 'product')
            ->where('campaigns.length_type', 'date_range')
            ->where('campaigns.status', 'active')
            ->where('campaigns.store_id', $store_id)
            ->where('campaigns.start_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('campaigns.end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->whereRaw('FIND_IN_SET("' . $id . '",campaigns.products)')
            ->get();
        if (isset($campaign1s) && count($campaign1s) > 0) {
            foreach ($campaign1s as $campaign1) {
                if (isset($campaign1)) {
                    if (isset($campaign1->start_time) && isset($campaign1->end_time)) {
                        if ($campaign1->start_time <= Carbon::now()->format('H:i') && $campaign1->end_time >= Carbon::now()->format('H:i')) {
                            return response()->json($campaign1);
                        }
                    } else {
                        return response()->json($campaign1);
                    }
                }
            }
        }
        if (isset($productsss->subcategory)) {
            $campaign2s = Campaign::convertCurrency($store_id)
                ->where('campaigns.campaign_type', 'category')
                ->where('campaigns.length_type', 'date_range')
                ->where('campaigns.status', 'active')
                ->where('campaigns.store_id', $store_id)
                ->where('campaigns.start_date', '<=', Carbon::now()->format('Y-m-d'))
                ->where('campaigns.end_date', '>=', Carbon::now()->format('Y-m-d'))
                ->whereRaw('FIND_IN_SET("' . (int)$productsss->category . '",campaigns.category)')
                ->orWhereRaw('FIND_IN_SET("' . (int)$productsss->subcategory . '",campaigns.category)')
                ->get();
            // return response()->json($campaign2);
            if (isset($campaign2s) && count($campaign2s) > 0) {
                foreach ($campaign2s as $campaign2) {
                    if (isset($campaign2)) {
                        if (isset($campaign2->start_time) && isset($campaign2->end_time)) {
                            if ($campaign2->start_time <= Carbon::now()->format('H:i') && $campaign2->end_time >= Carbon::now()->format('H:i')) {
                                return response()->json($campaign2);
                            }
                        } else {
                            return response()->json($campaign2);
                        }
                    }
                }
            }
        } else {
            $campaign2s = Campaign::convertCurrency($store_id)
                ->where('campaigns.campaign_type', 'category')
                ->where('campaigns.length_type', 'date_range')
                ->where('campaigns.status', 'active')
                ->where('campaigns.store_id', $store_id)
                ->where('campaigns.start_date', '<=', Carbon::now()->format('Y-m-d'))
                ->where('campaigns.end_date', '>=', Carbon::now()->format('Y-m-d'))
                ->whereRaw('FIND_IN_SET("' . (int)$productsss->category . '",campaigns.category)')
                ->get();
            if (isset($campaign2s) && count($campaign2s) > 0) {
                foreach ($campaign2s as $campaign2) {
                    if (isset($campaign2)) {
                        if (isset($campaign2->start_time) && isset($campaign2->end_time)) {
                            if ($campaign2->start_time <= Carbon::now()->format('H:i') && $campaign2->end_time >= Carbon::now()->format('H:i')) {
                                return response()->json($campaign2);
                            }
                        } else {
                            return response()->json($campaign2);
                        }
                    }
                }
            }
        }

        $campaign3s = Campaign::convertCurrency($store_id)
            ->where('campaigns.campaign_type', 'product')
            ->where('campaigns.length_type', 'specific_date')
            ->where('campaigns.status', 'active')
            ->where('campaigns.store_id', $store_id)
            ->where('campaigns.specific_dates', Carbon::now()->format('Y-m-d'))
            ->whereRaw('FIND_IN_SET("' . $id . '",campaigns.products)')
            ->get();
        if (isset($campaign3s) && count($campaign3s) > 0) {
            foreach ($campaign3s as $campaign3) {
                if (isset($campaign3)) {
                    if (isset($campaign3->start_time) && isset($campaign3->end_time)) {
                        if ($campaign3->start_time <= Carbon::now()->format('H:i') && $campaign3->end_time >= Carbon::now()->format('H:i')) {
                            return response()->json($campaign3);
                        }
                    } else {
                        return response()->json($campaign3);
                    }
                }
            }
        }
        if (isset($productsss->subcategory)) {
            $campaign4s = Campaign::convertCurrency($store_id)
                ->where('campaigns.campaign_type', 'category')
                ->where('campaigns.length_type', 'specific_date')
                ->where('campaigns.status', 'active')
                ->where('campaigns.store_id', $store_id)
                ->where('campaigns.specific_dates', Carbon::now()->format('Y-m-d'))
                ->whereRaw('FIND_IN_SET("' . (int)$productsss->category . '",campaigns.category)')
                ->orWhereRaw('FIND_IN_SET("' . (int)$productsss->subcategory . '",campaigns.category)')
                ->get();
            if (isset($campaign4s) && count($campaign4s) > 0) {
                foreach ($campaign4s as $campaign4) {
                    if (isset($campaign4)) {
                        if (isset($campaign4->start_time) && isset($campaign4->end_time)) {
                            if ($campaign4->start_time <= Carbon::now()->format('H:i') && $campaign4->end_time >= Carbon::now()->format('H:i')) {
                                return response()->json($campaign4);
                            }
                        } else {
                            return response()->json($campaign4);
                        }
                    }
                }
            }
        } else {
            $campaign4s = Campaign::convertCurrency($store_id)
                ->where('campaigns.campaign_type', 'category')
                ->where('campaigns.length_type', 'specific_date')
                ->where('campaigns.status', 'active')
                ->where('campaigns.store_id', $store_id)
                ->where('campaigns.specific_dates', Carbon::now()->format('Y-m-d'))
                ->whereRaw('FIND_IN_SET("' . (int)$productsss->category . '",campaigns.category)')
                ->get();
            if (isset($campaign4s) && count($campaign4s) > 0) {
                foreach ($campaign4s as $campaign4) {
                    if (isset($campaign4)) {
                        if (isset($campaign4->start_time) && isset($campaign4->end_time)) {
                            if ($campaign4->start_time <= Carbon::now()->format('H:i') && $campaign4->end_time >= Carbon::now()->format('H:i')) {
                                return response()->json($campaign4);
                            }
                        } else {
                            return response()->json($campaign4);
                        }
                    }
                }
            }
        }

        $campaign5 = Campaign::convertCurrency($store_id)
            ->where('campaigns.campaign_type', 'product')
            ->where('campaigns.length_type', 'repeat_date')
            ->where('campaigns.status', 'active')
            ->where('campaigns.store_id', $store_id)
            ->whereRaw('FIND_IN_SET("' . Carbon::now()->format('l') . '",campaigns.repeat_dates)')
            ->whereRaw('FIND_IN_SET("' . $id . '",campaigns.products)')
            ->get();
        if (isset($campaign5s) && count($campaign5s) > 0) {
            foreach ($campaign5s as $campaign5) {
                if (isset($campaign5)) {
                    if (isset($campaign5->start_time) && isset($campaign5->end_time)) {
                        if ($campaign5->start_time <= Carbon::now()->format('H:i') && $campaign5->end_time >= Carbon::now()->format('H:i')) {
                            return response()->json($campaign5);
                        }
                    } else {
                        return response()->json($campaign5);
                    }
                }
            }
        }
        if (isset($productsss->subcategory)) {
            $campaign6 = Campaign::convertCurrency($store_id)
                ->where('campaigns.campaign_type', 'category')
                ->where('campaigns.length_type', 'repeat_date')
                ->where('campaigns.status', 'active')
                ->where('campaigns.store_id', $store_id)
                ->whereRaw('FIND_IN_SET("' . Carbon::now()->format('l') . '",campaigns.repeat_dates)')
                ->whereRaw('FIND_IN_SET("' . (int)$productsss->category . '",campaigns.category)')
                ->orWhereRaw('FIND_IN_SET("' . (int)$productsss->subcategory . '",campaigns.category)')
                ->get();
            if (isset($campaign6s) && count($campaign6s) > 0) {
                foreach ($campaign6s as $campaign6) {
                    if (isset($campaign6)) {
                        if (isset($campaign6->start_time) && isset($campaign6->end_time)) {
                            if ($campaign6->start_time <= Carbon::now()->format('H:i') && $campaign6->end_time >= Carbon::now()->format('H:i')) {
                                return response()->json($campaign6);
                            }
                        } else {
                            return response()->json($campaign6);
                        }
                    }
                }
            }
        } else {
            $campaign6s = Campaign::convertCurrency($store_id)
                ->where('campaigns.campaign_type', 'category')
                ->where('campaigns.length_type', 'repeat_date')
                ->where('campaigns.status', 'active')
                ->where('campaigns.store_id', $store_id)
                ->whereRaw('FIND_IN_SET("' . Carbon::now()->format('l') . '",campaigns.repeat_dates)')
                ->whereRaw('FIND_IN_SET("' . (int)$productsss->category . '",campaigns.category)')
                ->get();
            if (isset($campaign6s) && count($campaign6s) > 0) {
                foreach ($campaign6s as $campaign6) {
                    if (isset($campaign6)) {
                        if (isset($campaign6->start_time) && isset($campaign6->end_time)) {
                            if ($campaign6->start_time <= Carbon::now()->format('H:i') && $campaign6->end_time >= Carbon::now()->format('H:i')) {
                                return response()->json($campaign6);
                            }
                        } else {
                            return response()->json($campaign6);
                        }
                    }
                }
            }
        }
        return response()->json(['error' => 'Offer Not Exists r']);
    }

    public function checkProductOffer($product, $regular_price, $store_id)
    {
        $id = $product->id;
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentDay = Carbon::now()->format('l');

        // Common query base for campaigns
        $baseQuery = Campaign::convertCurrency($store_id)
            ->where('campaigns.status', 'active')
            ->where('campaigns.store_id', $store_id);

        // Date Range Campaigns (Campaign 1 & 2)
        $dateRangeQuery = clone $baseQuery;
        $dateRangeQuery->where('campaigns.length_type', 'date_range')
            ->where('campaigns.start_date', '<=', $currentDate)
            ->where('campaigns.end_date', '>=', $currentDate);

        // Campaign 1: Product-specific
        $campaign1 = (clone $dateRangeQuery)->where('campaigns.campaign_type', 'product')
            ->whereRaw('FIND_IN_SET("' . $id . '", campaigns.products)')
            ->get();
        if ($response = $this->isCampaignActiveNow($campaign1, $regular_price)) {
            return $response;
        }

        // Campaign 2: Category-specific
        $categoryQuery = (clone $dateRangeQuery)->where('campaigns.campaign_type', 'category')
            ->whereRaw('FIND_IN_SET("' . (int)$product->category . '", campaigns.category)');

        if (isset($product->subcategory)) {
            $categoryQuery->orWhereRaw('FIND_IN_SET("' . (int)$product->subcategory . '", campaigns.category)');
        }

        $campaign2 = $categoryQuery->get();
        if ($response = $this->isCampaignActiveNow($campaign2, $regular_price)) {
            return $response;
        }

        // Specific Date Campaigns (Campaign 3 & 4)
        $specificDateQuery = clone $baseQuery;
        $specificDateQuery->where('campaigns.length_type', 'specific_date')
            ->where('campaigns.specific_dates', $currentDate);

        // Campaign 3: Product-specific
        $campaign3 = (clone $specificDateQuery)->where('campaigns.campaign_type', 'product')
            ->whereRaw('FIND_IN_SET("' . $id . '", campaigns.products)')
            ->get();
        if ($response = $this->isCampaignActiveNow($campaign3, $regular_price)) {
            return $response;
        }

        // Campaign 4: Category-specific
        $categoryQuery = (clone $specificDateQuery)->where('campaigns.campaign_type', 'category')
            ->whereRaw('FIND_IN_SET("' . (int)$product->category . '", campaigns.category)');

        if (isset($product->subcategory)) {
            $categoryQuery->orWhereRaw('FIND_IN_SET("' . (int)$product->subcategory . '", campaigns.category)');
        }

        $campaign4 = $categoryQuery->get();
        if ($response = $this->isCampaignActiveNow($campaign4, $regular_price)) {
            return $response;
        }

        // Repeat Date Campaigns (Campaign 5 & 6)
        $repeatDateQuery = clone $baseQuery;
        $repeatDateQuery->where('campaigns.length_type', 'repeat_date')
            ->whereRaw('FIND_IN_SET("' . $currentDay . '", campaigns.repeat_dates)');

        // Campaign 5: Product-specific
        $campaign5 = (clone $repeatDateQuery)->where('campaigns.campaign_type', 'product')
            ->whereRaw('FIND_IN_SET("' . $id . '", campaigns.products)')
            ->get();
        if ($response = $this->isCampaignActiveNow($campaign5, $regular_price)) {
            return $response;
        }

        // Campaign 6: Category-specific
        $categoryQuery = (clone $repeatDateQuery)->where('campaigns.campaign_type', 'category')
            ->whereRaw('FIND_IN_SET("' . (int)$product->category . '", campaigns.category)');

        if (isset($product->subcategory)) {
            $categoryQuery->orWhereRaw('FIND_IN_SET("' . (int)$product->subcategory . '", campaigns.category)');
        }

        $campaign6 = $categoryQuery->get();
        if ($response = $this->isCampaignActiveNow($campaign6, $regular_price)) {
            return $response;
        }

        return [
            "status" => false,
            "message" => "No active offers found",
            "offer_price" => null
        ];
    }

    /**
     * Check if a campaign is currently active based on start and end times.
     */
    private function isCampaignActiveNow($campaigns, $regular_price)
    {
        $currentTime = Carbon::now()->format('H:i');

        foreach ($campaigns as $campaign) {
            if (isset($campaign->start_time, $campaign->end_time)) {
                if ($campaign->start_time <= $currentTime && $campaign->end_time >= $currentTime) {
                    return $this->generateOfferResponse($regular_price, $campaign);
                }
            } else {
                return $this->generateOfferResponse($regular_price, $campaign);
            }
        }

        return null;
    }

    /**
     * Generate the offer response.
     */
    private function generateOfferResponse($regular_price, $campaign)
    {
        $offer_price = getPrice($regular_price, $campaign->discount_amount, $campaign->discount_type);

        return [
            "status" => true,
            "message" => "Success",
            "offer_price" => $offer_price ?? null
        ];
    }


    public function getshoppageproduct(Request $request)
    {
        $name = $request->name;
        $store = Store::where('url', $name)
            ->where('expiry_date', '>=', Carbon::now())
            ->first();

        if (!$store) {
            return response()->json(['data' => [], 'colors' => []]);
        }

        $colors = Color::where('store_id', $store->id)->get(['name', 'code']);

        $productQuery = Product::convertCurrency($store->id)
            ->where('products.status', 'active');

        if ($request->filter) {
            $type = $request->filter;
            switch ($type) {
                case 'az':
                    $productQuery->orderBy('products.name', 'asc');
                    break;
                case 'za':
                    $productQuery->orderBy('products.name', 'desc');
                    break;
                case 'lh':
                    $productQuery->orderBy('products.regular_price', 'asc');
                    break;
                case 'hl':
                    $productQuery->orderBy('products.regular_price', 'desc');
                    break;
                default:
                    if (ModulusStatus($store->id, 9)) {
                        $productQuery->orderBy('products.position');
                    } else {
                        $productQuery->orderBy('products.id', 'desc');
                    }
                    break;
            }
        } else {
            if (ModulusStatus($store->id, 9)) {
                $productQuery->orderBy('products.position');
            } else {
                $productQuery->orderBy('products.id', 'desc');
            }
        }

        if (!empty($request->priceFilter)) {
            $priceFilter = $request->priceFilter;
            $productQuery->where('products.regular_price', '<=', $priceFilter);
        }

        if (!empty($request->colorFilter)) {
            $colorFilter = $request->colorFilter;
            if ($colorFilter) {
                $productQuery->join('veriants', 'products.id', '=', 'veriants.pid')
                    ->where('veriants.color', $colorFilter)
                    ->select('products.*', 'veriants.color', 'veriants.size')
                    ->groupBy('products.id');
            }
        }

        // Paginate the query results
        $products = $productQuery->paginate(8)->onEachSide(1)->setPath('');

        // Process paginated results
        foreach ($products as $product) {
            $product->image = explode(',', $product->images);
            $product->rating = Review::where('product_id', $product->id)
                ->whereNotNull('rating')
                ->avg('rating') ?? 0;
            $product->number_rating = Review::where('product_id', $product->id)
                ->whereNotNull('rating')
                ->count() ?? 0;
            $product->slug = generateSlug($product->name);
            $product->description = mb_substr($product->description, 0, 216);
            $product->category_id = $product->category ?? "";
            $product->subcategory_id = $product->subcategory ?? "";
            $category = Category::whereIn('id', explode(',', $product->category))
                ->where('status', 'active')
                ->first()->name ?? "";
            $subcategory = Category::whereIn('id', explode(',', $product->subcategory))
                ->where('status', 'active')
                ->first()->name ?? "";
            $product->category = $category ?? "";
            $product->subcategory = $subcategory ?? "";
            $product->discount_price = $product->regular_price <= $product->promotional_price ? "0" : $product->promotional_price;
            $product->brand_id = Brand::where('id', $product->brand)->value('id') ?? null;
            $product->brand_name = Brand::where('id', $product->brand)->value('name') ?? null;
            $product->variant = Veriant::convertCurrency($product->id)->get();
        }

        return response()->json(['data' => $products, 'colors' => $colors]);
    }


    public function appsurl(Request $request)
    {
        $store = Store::where('id', $request->store_id)->whereDate('expiry_date', '>=', Carbon::now())->first();
        if (isset($store)) {
            $url = $store->url;
            $design = Design::where('store_id', $store->id)->first();
            if (isset($design)) {
                $header_color = $design->header_color;
                $text_color = $design->text_color;
            } else {
                $header_color = "#f1593a";
                $text_color = "#fff";
            }
        } else {
            $url = env('APP_URL');
            $header_color = "#f1593a";
            $text_color = "#fff";
        }
        return response()->json(['url' => $url, 'header_color' => $header_color, 'text_color' => $text_color]);
    }

    public function popupimage()
    {
        $data = Supersetting::find(1);
        return ['data' => $data];
    }

    public function digitaltimmer()
    {
        $data = Supersetting::find(1);
        return ['data' => $data];
    }

    public function templates()
    {
        $data = Template::where('status', 'active')->orderBy('position', 'asc')->get();
        return [
            'templates' => $data
        ];
    }


    /**
     *
     * Get product by product tags
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTagProduct(Request $request)
    {
        try {
            $store_id = $request->store_id ?? "";
            if (empty($store_id) || is_null($store_id)) {
                return response()->json(['status' => false, "message" => "Store id missing", "data" => []]);
            }

            $tag = $request->tag ?? "";

            if (!empty($tag)) {
                $data = Product::where('tags', 'like', "%$tag%")
                    ->where('status', 'active')
                    ->inRandomOrder() // Randomize the order
                    ->limit(4) // Limit the results to 4
                    ->get();

                return response()->json(['data' => $data]);
            }

            return response()->json(['status' => false, "message" => "Tags missing", "data" => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => "Something went wrong", "data" => []]);
        }
    }


}
