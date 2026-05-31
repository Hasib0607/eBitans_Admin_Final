<?php

namespace App\Http\Controllers;

use App\Logic\Providers\cPanelApi;
use App\Models\AddonsExpired;
use App\Models\AddonsOrder;
use App\Models\Branchproduct;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Design;
use App\Models\Domain;
use App\Models\Headersetting;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Referral;
use App\Models\RegistrationFee;
use App\Models\Slider;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Toptool;
use App\Models\Websitesetup;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cpanel()
    {
        $api = new cPanelApi("ebitans.com", "ebitans", env("HOST_POINT"));
        echo $api->listDataDomains();
    }

    public function index()
    {
        $urls = "dashboard";

        $userData = getUserData();
        $user = $userData['user'];
        $user_id = $userData['user_id'];
        $user_type = $userData['user_type'];
        $store = $userData['store'];
        $store_id = $userData['store_id'];
        $customer = $userData['customer'];
        $customer_id = $userData['customer_id'];

        if ($user_type != 'admin' && $user_type != 'dropshipper') {
            return redirect()->route('staff.dashboard');
        }

        $useee = Toptool::where('store_id', $store_id)->where('uid', $user_id)->orderBy('count', 'DESC')->take(6)->get();

        $productCounts = Orderitem::select('product_id', \DB::raw('COUNT(*) as count'))
            ->join('orders', 'orders.id', '=', 'orderitems.order_id')
            ->where('orders.store_id', $store_id)
            ->groupBy('product_id')
            ->pluck('count', 'product_id');

        $vals = $productCounts->toArray();

        if (empty($vals)) {
            $vals = [];
        }

        $smsuse = AddonsExpired::where('store_id', $store_id)->where('addons_id', '5')->first();
        $cats = Category::where('store_id', $store_id)->where('parent', 0)->get();
        $productsss = Product::where('store_id', $store_id)->get();
        $setting = Headersetting::where('store_id', $store_id)->first();
        $designs = Design::where('store_id', $store_id)->first();
        $headermenu = Menu::where('store_id', $store_id)->get();
        $slidersd = Slider::where('store_id', $store_id)->get();
        $pagesd = Page::where('store_id', $store_id)->get();
        $domain = Domain::where('store_id', $store_id)->get();

        $done = 0;
        if (isset($cats) && count($cats) > 4) {
            $done = $done + 14.2857142857;
        }
        if (isset($productsss) && count($productsss) > 10) {
            $done = $done + 14.2857142857;
        }
        if (isset($setting->short_description) && isset($setting->phone) && isset($setting->email) && isset($setting->address) && isset($setting->facebook_link) && isset($setting->instagram_link) && isset($setting->youtube_link) && isset($setting->messenger_link) && isset($setting->whatsapp_phone) && isset($setting->tax) && isset($setting->shipping_area_1) && isset($setting->shipping_area_1_cost) && isset($setting->shipping_area_2) && isset($setting->shipping_area_2_cost) && isset($setting->shipping_area_3) && isset($setting->shipping_area_3_cost)) {
            $done = $done + 14.2857142857;
        }
        if (isset($headermenu) && count($headermenu) > 0) {
            $done = $done + 14.2857142857;
        }
        if (isset($slidersd) && count($slidersd) > 0) {
            $done = $done + 14.2857142857;
        }
        if (isset($pagesd) && count($pagesd) > 0) {
            $done = $done + 14.2857142857;
        }
        if (isset($domain) && count($domain) > 1) {
            $done = $done + 14.2857142857;
        }
        if ($designs->template_id != '0') {
            $done = $done + 14.2857142857;
        }

        if (isset($smsuse)) {
            $smsuse->price = conversionsCurrency($smsuse->price ?? null, $smsuse->currency_id, $store_id)['amount'];
        }

        $plan = Plan::where('id', $store->plan_id)->first();
        $conversionsCurrency = conversionsCurrency($plan->price, $plan->currency_id, $store_id);
        $plan->price = $conversionsCurrency['amount'];
        $symbol = $conversionsCurrency['symbol'];
        $code = $conversionsCurrency['code'];
        $upcoming_plan = null;
        if (isset($store->upcoming_plan_id)) {
            $upcoming_plan = Plan::where('id', $store->upcoming_plan_id)->first();
        }

        if ($user->paid_registration) {
            $package = AddonsOrder::where('store_id', $store_id)->whereNotNull('plan_id')->get();

            if (isset($package) && count($package) <= 2 && isset($package[0]['paid_registration']) && $package[0]['paid_registration']) {
                $package = $package[0] ?? [];
                $package_create_at = $package[0]['created_at'] ?? Carbon::now();
                $package_month = $package[0]['plan_month'] ?? 1; // "2025-04-23 12:31:24"
                $package_plan_id = $package[0]['plan_id'] ?? NULL; // "2025-04-23 12:31:24"

                $expectedExpireDate = Carbon::parse($package_create_at)->addMonths($package_month);
                if ((isset($package_plan_id) && $store->plan_id == $package_plan_id) && $store->expiry_date < $expectedExpireDate) {
                    $conversionsCurrency = conversionsCurrency($package->total, $package->currency_id, $store_id);
                    $plan->price = $conversionsCurrency['amount'] ?? $package->total;
                }
            }
        }

        $websitesetup = Websitesetup::where('store_id', $store_id)->latest()->first();

        return view('admin.index',
            compact(
                'cats',
                'productsss',
                'setting',
                'designs',
                'headermenu',
                'slidersd',
                'pagesd',
                'domain',
                'urls',
                'done',
                'store',
                'plan',
                'symbol',
                'code',
                'upcoming_plan',
                'customer'
            ))
            ->with('vals', $vals)
            ->with('smsuse', $smsuse)
            ->with('store_id', $store_id)
            ->with('customer_id', $customer_id)
            ->with('websitesetup', $websitesetup)
            ->with('use', $useee);
    }


    public function affiliateMarketing()
    {
        $user_type = Auth::user()->type;
        if ($user_type == 'admin' || $user_type == 'dropshipper') {
            $data['urls'] = "affiliateMarketing";
            $data['users'] = User::where('refer_by', Auth::user()->referral)->orderBy('id', 'DESC')->get();

            $data['refers'] = Referral::select(
                'referrals.id',
                'referrals.user_id',
                'referrals.store_id',
                'referrals.referral_id',
                'referrals.commission_price',
                'referrals.created_at',
                'referrals.plan_id',
                'plans.name as plan_name',
                'referrals.digital_id',
                'digitalplans.name as digital_plan_name',
                'referrals.pos_id',
                'posplans.name as pos_plan_name',
                'stores.name AS store_name',
                'users.name AS user_name',
                'users.phone AS user_phone'
            )
                ->leftJoin('plans', function ($join) {
                    $join->on('plans.id', '=', 'referrals.plan_id');
                })
                ->leftJoin('digitalplans', function ($join) {
                    $join->on('digitalplans.id', '=', 'referrals.digital_id');
                })
                ->leftJoin('posplans', function ($join) {
                    $join->on('posplans.id', '=', 'referrals.pos_id');
                })
                ->leftJoin('stores', function ($join) {
                    $join->on('stores.id', '=', 'referrals.store_id');
                })
                ->leftJoin('users', function ($join) {
                    $join->on('users.id', '=', 'referrals.user_id');
                })
                ->where('referrals.referral_id', '=', Auth::user()->referral)
                ->get();
            return view('admin.affiliateMarketing.index', $data);
        }
    }


    public function webmail()
    {
        return view('admin.webmail');
    }

    public function changelang(Request $request)
    {
        $val = $request->langtoggle;
        if ($val == 'on') {
            if (Session::has('lang')) {
                Session::forget('lang');
                Session::put('lang', 'bn');
                return back();
            } else {
                Session::put('lang', 'bn');
                return back();
            }
        } else {
            if (Session::has('lang')) {
                Session::forget('lang');
                Session::put('lang', 'en');
                return back();
            } else {
                Session::put('lang', 'bn');
                return back();
            }
        }
    }

    public function mainsearch(Request $request)
    {
        $key = $request->myCountry;
        if ($key == 'Branch') {
            return redirect()->route('admin.branch.index');
        } elseif ($key == 'Product') {
            return redirect()->route('admin.allproducts');
        } elseif ($key == 'Create Product') {
            return redirect()->route('admin.addproducts');
        } elseif ($key == 'Category') {
            return redirect()->route('admin.category.index');
        } elseif ($key == 'Subcategory') {
            return redirect()->route('admin.subcategory.index');
        } elseif ($key == 'Attribute->Color') {
            return redirect()->route('admin.attribute.index');
        } elseif ($key == 'Attribute->Size') {
            return redirect()->route('admin.attribute.size');
        } elseif ($key == 'Attribute->Unit') {
            return redirect()->route('admin.attribute.unit');
        } elseif ($key == 'Brands') {
            return redirect()->route('admin.brand.index');
        } elseif ($key == 'Suppliers') {
            return redirect()->route('admin.supplier.index');
        } elseif ($key == 'Coupon') {
            return redirect()->route('admin.promotion.coupon');
        } elseif ($key == 'Campaign') {
            return redirect()->route('admin.promotion.campaign');
        } elseif ($key == 'Offer') {
            return redirect()->route('admin.promotion.offer');
        } elseif ($key == 'Slider') {
            return redirect()->route('admin.design.slider');
        } elseif ($key == 'Banner') {
            return redirect()->route('admin.design.banner');
        } elseif ($key == 'Layouts') {
            return redirect()->route('admin.design.layout.homepage');
        } elseif ($key == 'Themes') {
            return redirect()->route('admin.design.theme');
        } elseif ($key == 'Header->Design') {
            return redirect()->route('admin.design.design');
        } elseif ($key == 'Header->Menu') {
            return redirect()->route('admin.design.header');
        } elseif ($key == 'Homepage') {
            return back()->with('error', 'Page Not Exist');
        } elseif ($key == 'Footer') {
            return back()->with('error', 'Page Not Exist');
        } elseif ($key == 'Shopage') {
            return back()->with('error', 'Page Not Exist');
        } elseif ($key == 'Testiominals') {
            return redirect()->route('admin.testimonials');
        } elseif ($key == 'Settings') {
            return redirect()->route('admin.design.settings');
        } elseif ($key == 'Pages') {
            return redirect()->route('admin.pages');
        } elseif ($key == 'Customer') {
            return redirect()->route('admin.customer');
        } elseif ($key == 'Staff') {
            return redirect()->route('admin.staff');
        } elseif ($key == 'Invoice') {
            return redirect()->route('admin.invoice');
        } elseif ($key == 'Order') {
            return redirect()->route('admin.order');
        } elseif ($key == 'Role and Permission') {
            return redirect()->route('admin.role.permission');
        } else {
            return back()->with('error', 'You have no permission for access this page');
        }
    }

    public function menu()
    {
        return view('admin.menu');
    }

    public function refresh()
    {
        $campaign = Campaign::all();
        if (isset($campaign) && count($campaign) > 0) {
            foreach ($campaign as $camp) {
                if ($camp->campaign_type == 'product') {
                    $campp = explode(',', $camp->products);
                    if (isset($campp) && count($campp) > 0) {
                        foreach ($camp as $pro) {
                            $product = Product::find($pro);
                            if (isset($product)) {
                            } else {
                                $cpid[] = $pro;
                            }
                        }
                    }
                    if (isset($cpid)) {
                        $campss = Campaign::find($camp->id);
                        $p = explode(',', $campss->products);
                        $b = array_diff($p, $cpid);
                        $c = implode(',', $b);
                        $campss->products = $c;
                        $campss->save();
                    }
                } elseif ($camp->campaign_type == 'category') {
                    $campc = explode(',', $camp->category);
                    if (isset($campc) && count($campc) > 0) {
                        foreach ($campc as $cat) {
                            $category = Category::find($cat);
                            if (isset($category)) {
                            } else {
                                $ccid[] = $cat;
                            }
                        }
                    }
                    if (isset($ccid)) {
                        $campss = Campaign::find($camp->id);
                        $p = explode(',', $campss->category);
                        $b = array_diff($p, $ccid);
                        $c = implode(',', $b);
                        $campss->category = $c;
                        $campss->save();
                    }
                }
            }
        }
        $branchproduct = Branchproduct::all();
        if (isset($branchproduct) && count($branchproduct) > 0) {
            foreach ($branchproduct as $bp) {
                $product = Product::find($bp->product_id);
                if (isset($product)) {
                } else {
                    Branchproduct::find($bp->id)->delete();
                }
            }
        }

        Session::flash('message', 'Successfully Refresh All Website');
        return back();
    }

    public function saveaudio(Request $request)
    {
        $request->file('payload')->storeAs('audio', 'audio.mp3');
        return $request->all();
    }

    public function showRegistrationPaymentMethod()
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $store = $userData['store'];

        $registrationFee = RegistrationFee::where("status", 1)->first();
        $addonsOrder = AddonsOrder::where("store_id", $store_id)->where("paid_registration", 1)->first();
        if (!isset($addonsOrder)) {
            $package = [
                "id" => 2,
                "name" => "Standard",
                "month" => "1",
                "type" => "package",
                "price" => $registrationFee->price,
                "usd_price" => 2,
                "usd_offer_price" => 2,
                "offerprice" => $registrationFee->price,
                "activeTime" => 1
            ];

            $addonsOrder = new AddonsOrder();
            $addonsOrder->user_id = $store->user_id;
            $addonsOrder->store_id = $store_id;
            $addonsOrder->currency_id = $store->currency;
            $addonsOrder->addons = [];
            $addonsOrder->package = json_encode($package) ?? null;
            $addonsOrder->payment_method = NULL;
            $addonsOrder->plan_id = 2;
            $addonsOrder->plan_month = $website_month ?? 1;
            $addonsOrder->plan_type = 'website';
            $addonsOrder->total = $registrationFee->price;
            $addonsOrder->plan_check = 1;
            $addonsOrder->status = 'Failed';
            $addonsOrder->paid_registration = 1;
            $addonsOrder->save();
        }

        return view("payment.adminPayment.payment-method", [
            "addonsOrder" => $addonsOrder
        ]);
    }


    public function registrationFeePayment(Request $request)
    {
        $payment_method = $request->payment_method ?? NULL;
        $order_id = $request->order_id;

        if (is_null($payment_method) || empty($payment_method)) {
            return redirect()->back()->with("error", "Please select payment method");
        }

        $addonsOrder = AddonsOrder::where("id", $order_id)->first();
        if (!isset($addonsOrder)) {
            return redirect()->back()->with("error", "Invalid Payment Request");
        }

        $addonsOrder->payment_method = $payment_method;
        $addonsOrder->update();

        $url = NULL;
        if ($addonsOrder->total > 0) {
            if ($payment_method == "bkash") {
                $url = env('APP_URL') . '/api/v1/admin/bkash/checkout-url/orderPay?order=' . $addonsOrder->id;
            } else if ($payment_method == "nagad") {
                $url = route('nagad.admin.payment') . "?order_id=" . $addonsOrder->id;
            }
        }

        if (isset($url)) {
            return redirect($url);
        }

        return redirect()->back()->with("error", "Invalid Payment Request");
    }

}
