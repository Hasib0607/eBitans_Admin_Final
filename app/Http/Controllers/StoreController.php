<?php

namespace App\Http\Controllers;

use App\Models\AddonsOrder;
use App\Models\Banner;
use App\Models\BusinessCategory;
use App\Models\Category;
use App\Models\Customer;
use App\Models\DemoStoreData;
use App\Models\Design;
use App\Models\Designlist;
use App\Models\Domain;
use App\Models\Headersetting;
use App\Models\Plan;
use App\Models\Product;
use App\Models\RegistrationFee;
use App\Models\Slider;
use App\Models\Store;
use App\Models\Testimonial;
use App\Models\Toptool;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;



class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pricinglist()
    {
        $plans = Plan::where('status', 'active')->get();
        return view('admin.price.index')->with('plans', $plans);
    }

    public function store()
    {
        $customer = Customer::where('uid', Auth::user()->id)->first();
        $customer_id = $customer->id ?? NULL;

        $store = Store::where('user_id', Auth::user()->id);
        if (!is_null($customer_id)) {
            $store->where('customer_id', $customer_id);
        }
        $store = $store->get();

        return view('plan.store')->with('store', $store);
    }

    public function ChooseProducts()
    {
        return view('plan.chooseStorePlan');
    }

    public function ChooseProductsInfoSubmit(Request $request)
    {
        if ($request->type == 'null') {
            Session::flash('error', 'Store Type Must Be Selected');
            return back();
        }
        if ($request->package_type == 'null' || $request->package_type == null || empty($request->package_type)) {
            $request->package_type = "ecw";
        }

        $rules = array(
            'type' => 'required',
            'storeName' => 'required|unique:stores,name',
            'slug' => 'required|unique:stores,slug',
        );
        $messages = array(
            'type.required' => 'Business Type Must Be Required',
            'storeName.required' => 'Store Name Must Be Required',
            'storeName.unique' => 'Store Name Must Be Unique',
            'slug.required' => 'DOMAIN Must Be Required'
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Session::flash('error', 'Store Type, Store Name, URL  Must Be Selected');
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $url = $request->slug . "." . env("STORE_SUB_DOMAIN");
            $store = Domain::where('name', $url)->where('status', '!=', 'Rejected')->get();

            if (isset($store) && count($store) > 0) {
                Session::flash('error', 'DOMAIN name already exists');
                return redirect()->back()->withErrors($validator);
            }

            // pass this in save method
            return $this->save($request);
        }
    }

    public function save(Request $request)
    {
        if (Auth::user()->type == "dropshipper") {
            $customer = Customer::where('uid', Auth::user()->id)->first();
            $customer_id = $customer->id ?? NULL;

            $store = Store::where('user_id', Auth::user()->id);
            if (!is_null($customer_id)) {
                $store->where('customer_id', $customer_id);
            }
            $storeCount = $store->count();
            if ($storeCount) {
                Session::flash('error', 'You can not create Multiple stores as a Dropshipper');
                return redirect()->back();
            }
        }

        if ($request->type == 'null') {
            Session::flash('error', 'Store Type Must Be Selected');
            return back();
        }
        if ($request->package_type == 'null' || $request->package_type == null || empty($request->package_type)) {
            $request->package_type = "ecw";
        }

        $rules = array(
            'type' => 'required',
            'storeName' => 'required|unique:stores,name',
            'slug' => 'required|unique:stores,slug',
        );
        $messages = array(
            'type.required' => 'Business Type Must Be Required',
            'storeName.required' => 'Store Name Must Be Required',
            'storeName.unique' => 'Store Name Must Be Unique',
            'slug.required' => 'DOMAIN Must Be Required'
        );
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Session::flash('error', 'Store Type, Store Name, URL  Must Be Selected');
            return redirect()->back()
                ->withErrors($validator);
        }

        $storeCategory = BusinessCategory::where("id", $request->type)->first();

        $customer = Customer::where('uid', Auth::user()->id)->first();
        $store = new Store();
        $store->name = $request->storeName;
        $slug = generateSlug($request->slug);
        $store->slug = $slug;
        $store->type = $storeCategory->name ?? NULL;
        $store->category_id = $request->type;
        $store->purpose = $request->purpose ?? NULL;
        $store->user_id = Auth::user()->id;
        $store->customer_id = $customer->id;
        $store->access_key = mt_rand(1000000000, 9999999999);
        $store->status = "active";
        $store->plan_id = null;
        $store->template_id = "1";
        $store->plan_status = "inactive";
        $store->purchase_date = "0000-00-00";
        // $store->expiry_date=Carbon::now()->addDays(0);
        $store->currency = $request->currency ?? 1;
        $store->trail = 0;
        $store->save();

        if (isset($request->phone) && !empty($request->phone)) {
            $user = auth()->user();
            if (!isset($user->phone) || empty($user->phone)) {
                $user->phone = $request->phone ?? NULL;
                $user->save();
            }
        }

        $cus = Customer::find($store->customer_id);
        $cus->active_store = $store->id;
        $cus->template_id = "1";
        $cus->plan_status = "inactive";
        $cus->update();

        $data['store_id'] = $store->id;
        $data['store_name'] = $store->name;
        $data['customer_id'] = $customer->id;

        // Save pre-define store data
        $this->preDefineStoreData($storeCategory, $data);

        $domain = new Domain();
        $domain->name = $store->slug . "." . env("STORE_SUB_DOMAIN");
        $domain->status = "Active";
        $domain->store_id = $store->id;
        $domain->customer_id = $customer->id;
        $domain->uid = Auth::user()->id;
        $domain->creator = Auth::user()->id;
        $domain->editor = Auth::user()->id;
        $domain->save();

        $hs = new Headersetting();
        $hs->website_name = $store->name;
        $hs->uid = Auth::user()->id;
        $hs->currency_id = $store->currency ?? 1;
        $hs->customer_id = $customer->id;
        $hs->store_id = $store->id;
        $hs->creator = Auth::user()->id;
        $hs->editor = Auth::user()->id;
        $hs->save();
        $stor = Store::find($store->id);
        $stor->url = $domain->name;
        $stor->save();

        $testimonial1 = new Testimonial();
        $testimonial1->name = "Testimonial 01";
        $testimonial1->image = "Testimonial_user.jpg";
        $testimonial1->occupation = "Demo";
        $testimonial1->feedback = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled it to make a type specimen book.";
        $testimonial1->position = 0;
        $testimonial1->status = "active";
        $testimonial1->uid = Auth::user()->id;
        $testimonial1->customer_id = $customer->id;
        $testimonial1->store_id = $store->id;
        $testimonial1->creator = Auth::user()->id;
        $testimonial1->editor = Auth::user()->id;
        $testimonial1->save();

        $testimonial2 = new Testimonial();
        $testimonial2->name = "Testimonial 02";
        $testimonial2->image = "Testimonial_user.jpg";
        $testimonial2->occupation = "Demo";
        $testimonial2->feedback = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled it to make a type specimen book.";
        $testimonial2->position = 0;
        $testimonial2->status = "active";
        $testimonial2->uid = Auth::user()->id;
        $testimonial2->customer_id = $customer->id;
        $testimonial2->store_id = $store->id;
        $testimonial2->creator = Auth::user()->id;
        $testimonial2->editor = Auth::user()->id;
        $testimonial2->save();

        $toptool = new Toptool();
        $toptool->name = 'Category';
        $toptool->image = 'categories.png';
        $toptool->url = '/category';
        $toptool->count = 30;
        $toptool->uid = Auth::user()->id;
        $toptool->store_id = $store->id;
        $toptool->customer_id = $customer->id;
        $toptool->creator = Auth::user()->id;
        $toptool->editor = Auth::user()->id;
        $toptool->save();

        $toptool = new Toptool();
        $toptool->name = 'Product';
        $toptool->image = 'box.png';
        $toptool->url = '/products';
        $toptool->count = 30;
        $toptool->uid = Auth::user()->id;
        $toptool->store_id = $store->id;
        $toptool->customer_id = $customer->id;
        $toptool->creator = Auth::user()->id;
        $toptool->editor = Auth::user()->id;
        $toptool->save();

        $toptool = new Toptool();
        $toptool->name = 'Setting';
        $toptool->image = 'settings-2.png';
        $toptool->url = '/settings';
        $toptool->count = 30;
        $toptool->uid = Auth::user()->id;
        $toptool->store_id = $store->id;
        $toptool->customer_id = $customer->id;
        $toptool->creator = Auth::user()->id;
        $toptool->editor = Auth::user()->id;
        $toptool->save();

        $toptool = new Toptool();
        $toptool->name = 'Theme';
        $toptool->image = 'categories.png';
        $toptool->url = '/design/theme';
        $toptool->count = 30;
        $toptool->uid = Auth::user()->id;
        $toptool->store_id = $store->id;
        $toptool->customer_id = $customer->id;
        $toptool->creator = Auth::user()->id;
        $toptool->editor = Auth::user()->id;
        $toptool->save();

        if ("ecw" == $request->package_type) {
            // $tokens=Paymenttoken::where('token',$request->token)->first();
            $paidRegistration = checkPaidRegistration();
            $website_plan_id = $paidRegistration ? 2 : 6;

            if (Auth::user()->type == "dropshipper") {
                $website_plan_id = 9;
            }

            $website_month = 1;
            $pos_plan_id = null;
            $digital_plan_id = null;
            $user = Auth::user()->id;
            $customer = Customer::where('uid', $user)->first();
            $store = Store::where('user_id', $user)->where('id', $customer->active_store)->first();
            $str = Store::find($store->id);

            $free = RegistrationFee::where("status", 1)->first();

            if ($str->purchase_date == "0000-00-00") {
                if (isset($website_plan_id) || $website_plan_id != "") {
                    $str->plan_id = $website_plan_id;
                    $str->month = $website_month;
                    $str->purchase_date = Carbon::now();

                    if (isset($free)) {
                        //                        $str->expiry_date = Carbon::now()->addDays(0); //30
                        $str->expiry_date = NULL; //30
                    } else {
                        if (Auth::user()->type == "dropshipper") {
                            $str->expiry_date = Carbon::now()->addDays(1); //dropshipper 1 Days trial.....
                        } else {
                            $str->expiry_date = Carbon::now()->addDays(3); //normal ecommerce user 3 Days trial.....
                        }
                    }

                }
                if (isset($pos_plan_id) || $pos_plan_id != "") {
                    $str->pos_plan_id = $pos_plan_id;
                    $str->pos_plan_start_date = Carbon::now();
                    $str->pos_plan_expiry_date = null;
                }
                if (isset($digital_plan_id) || $digital_plan_id != "") {
                    $str->digital_plan_id = $digital_plan_id;
                    $str->digital_plan_start_date = Carbon::now();
                    $str->digital_plan_end_date = null;
                }

                $str->trail = 0;
                $str->plan_status = "active";
                $str->status = "active";
                $str->update();

                $custo = Customer::find($customer->id);
                $custo->active_store = $str->id;
                $custo->update();

                $text = "Congratulations, You successfully created a website in eBitans. Your Website address is https://" . $str->url . " . For any inquiries call: 01886515579";

                $phone = Auth::user()->phone ?? null;
                $email = Auth::user()->email ?? null;

                if (isset($phone) && !empty($phone)) {
                    $smsresult = SendSms($phone, $text); // phone, text
                    $p = explode("|", $smsresult);
                    $sendstatus = $p[0];

                    smsLogger($phone, $text, "Store Create Message");
                }

                if (isset($email) && !empty($email)) {
                    $data['name'] = Auth::user()->name ?? "User";
                    $data['subject'] = "Store create";
                    $data['text'] = $text;
                    $data['formEmail'] = env('MAIL_FROM_ADDRESS');
                    $data['email'] = $email;

                    Mail::send('email.store-create', $data, function ($message) use ($data) {
                        $message->from($data['formEmail'], $data["subject"])->to($data["email"], $data["email"])
                            ->subject('Store create');
                    });
                }

            }

            setPackageCommission($store->id);

            if ($paidRegistration) {
                $payment_method = "";
                if (isset($request->payment_method) && !empty($request->payment_method)) {
                    $payment_method = $request->payment_method;
                }

                $str->paid_registration = 1;
                $str->save();

                $user = Auth::user();
                $user->paid_registration = 1;
                $user->save();

                $package = [
                    "id" => 2,
                    "name" => "Standard",
                    "month" => "1",
                    "type" => "package",
                    "price" => $free->price,
                    "usd_price" => 2,
                    "usd_offer_price" => 2,
                    "offerprice" => $free->price,
                    "activeTime" => 1
                ];

                $addonsOrder = new AddonsOrder();
                $addonsOrder->user_id = $user->id;
                $addonsOrder->store_id = $store->id;
                $addonsOrder->currency_id = $store->currency;
                $addonsOrder->addons = [];
                $addonsOrder->package = json_encode($package) ?? null;
                $addonsOrder->payment_method = $payment_method;
                $addonsOrder->plan_id = $website_plan_id;
                $addonsOrder->plan_month = $website_month ?? 1;
                $addonsOrder->plan_type = 'website';
                $addonsOrder->total = $free->price;
                $addonsOrder->plan_check = 1;
                $addonsOrder->status = 'Failed';
                $addonsOrder->paid_registration = 1;
                $addonsOrder->save();


                $url = NULL;
                if ($addonsOrder->total > 0) {
                    if ($payment_method == "bkash") {
                        $url = env('APP_URL') . '/api/v1/admin/bkash/checkout-url/orderPay?order=' . $addonsOrder->id;
                    } elseif ($payment_method == "nagad") {
                        $url = route('nagad.admin.payment') . "?order_id=" . $addonsOrder->id;
                    }
                }

                if (isset($url)) {
                    return redirect($url);
                }

            }
            return redirect('/');

        } elseif ("pos" == $request->package_type) {
            // $tokens=Paymenttoken::where('token',$request->token)->first();
            $website_plan_id = null;
            $website_month = null;
            $pos_plan_id = 1;
            $digital_plan_id = null;
            $user = Auth::user()->id;
            $customer = Customer::where('uid', $user)->first();
            $store = Store::where('user_id', $user)->where('id', $customer->active_store)->first();
            $str = Store::find($store->id);

            if ($str->purchase_date == "0000-00-00") {
                if (isset($website_plan_id) || $website_plan_id != "") {
                    $str->plan_id = $website_plan_id;
                    $str->month = $website_month;
                    $str->purchase_date = Carbon::now();
                    $str->expiry_date = null;
                }
                if (isset($pos_plan_id) || $pos_plan_id != "") {
                    $str->pos_plan_id = $pos_plan_id;
                    $str->pos_plan_start_date = Carbon::now();
                    $str->pos_plan_expiry_date = null;
                }
                if (isset($digital_plan_id) || $digital_plan_id != "") {
                    $str->digital_plan_id = $digital_plan_id;
                    $str->digital_plan_start_date = Carbon::now();
                    $str->digital_plan_end_date = null;
                }

                $str->update();
                $custo = Customer::find($customer->id);
                $custo->active_store = $str->id;
                $custo->update();
            }
            setPackageCommission($store->id);

            return redirect('/');
        } elseif ("smm" == $request->package_type) {
            // $tokens=Paymenttoken::where('token',$request->token)->first();
            $website_plan_id = null;
            $website_month = null;
            $pos_plan_id = null;
            $digital_plan_id = 1;
            $user = Auth::user()->id;
            $customer = Customer::where('uid', $user)->first();
            $store = Store::where('user_id', $user)->where('id', $customer->active_store)->first();
            $str = Store::find($store->id);

            if ($str->purchase_date == "0000-00-00") {
                if (isset($website_plan_id) || $website_plan_id != "") {
                    $str->plan_id = $website_plan_id;
                    $str->month = $website_month;
                    $str->purchase_date = Carbon::now();
                    $str->expiry_date = null;
                }
                if (isset($pos_plan_id) || $pos_plan_id != "") {
                    $str->pos_plan_id = $pos_plan_id;
                    $str->pos_plan_start_date = Carbon::now();
                    $str->pos_plan_expiry_date = null;
                }
                if (isset($digital_plan_id) || $digital_plan_id != "") {
                    $str->digital_plan_id = $digital_plan_id;
                    $str->digital_plan_start_date = Carbon::now();
                    $str->digital_plan_end_date = null;
                }

                // $str->trail=0;
                // $str->plan_status="active";
                // $str->status="active";
                $str->update();
                $custo = Customer::find($customer->id);
                $custo->active_store = $str->id;
                $custo->update();

                $phone = Auth::user()->phone;

                // this part commnent 15.01.2k23
                // $url = "http://66.45.237.70/api.php";
                // $number="88".$phone;
                // $text="Congratulations, You successfully created a website in eBitans. Your Website address is https://".$str->url." . For any inquiries call: 01886515579";
                // $data= array(
                // 'username'=>"01677515579",
                // 'password'=>"EHGTP3ZC",
                // 'number'=>"$number",
                // 'message'=>"$text"
                // );

                // $ch = curl_init(); // Initialize cURL
                // curl_setopt($ch, CURLOPT_URL, $url);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // $smsresult = curl_exec($ch);
                // $p = explode("|", $smsresult);
                // $sendstatus = $p[0];

                // this part commnent 15.01.2k23 end
            }

            setPackageCommission($store->id);

            return redirect('/');
        }

        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function preDefineStoreData($storeCategory, $getData)
    {
        $storeCategoryId = $storeCategory->id;
        $storeCategoryName = $storeCategory->name;

        // Write logic based on the type
        switch ($storeCategoryName) {
            case "Fashion":
            case "Jewellery":
            case "Cosmetics":
            case "Watches":
            case "Footware":
                $data = [
                    'theme_value' => 'seven',
                    'header_color' => '#E74C3C',
                    'checkout_page' => 'eleven',
                    'product_card' => 'three',
                    'preloader' => 'two',
                    'template_id' => '5',
                    'products' => [
                        ['name' => "Kids T-shirt", 'image' => "16768161770.jpg"],
                        ['name' => "Black Sharee", 'image' => "16768161180.jpg"],
                        ['name' => "Top", 'image' => "16768160520.jpg"],
                        ['name' => "Sharee", 'image' => "16768841280.jpg"],
                        ['name' => "Boy's Fashion", 'image' => "16768138120.jpg"],
                        ['name' => "Girl's Fashion", 'image' => "16768145600.jpg"],
                        ['name' => "Women's Fashion", 'image' => "16768838720.jpg"],
                        ['name' => "Men's Fashion", 'image' => "16768139070.jpg"],
                        ['name' => "Green Panjabi", 'image' => "16768139060.jpg"],
                        ['name' => "Pangabi", 'image' => "16768139810.jpg"],
                        ['name' => "T-Shirt", 'image' => "16768140280.jpg"],
                        ['name' => "Girl's-dress", 'image' => "16768140550.jpg"],
                        ['name' => "Boy Panjabi", 'image' => "16768140600.jpg"]
                    ],
                    'categories' => [
                        ['name' => "Men's Fashion", 'image' => "167583851979975.png"],
                        ['name' => "Women's Fashion", 'image' => "167583851952604.png"],
                        ['name' => "Boys Fashion", 'image' => "167583851937969.png"],
                        ['name' => "Girls Fashion", 'image' => "167583851914263.png"],
                        ['name' => "Baby", 'image' => "167583962916244.png"]
                    ],
                    'banners' => ["1661848696.jpg", "1677063697.jpg"],
                    'sliders' => ["1677062447.jpg", "1677062211.jpg", "1677063208.jpg"]
                ];
                break;

            case "Gadget":
            case "Mobile & Accessories":
            case "Computer & Accessories":
                $data = [
                    'theme_value' => 'thirteen',
                    'header_color' => '#E74C3C',
                    'checkout_page' => 'five',
                    'product_card' => 'two',
                    'preloader' => 'six',
                    'template_id' => '14',
                    'products' => [
                        ['name' => "Surface Go", 'image' => "16768977350.jpg"],
                        ['name' => "XL Tower Case", 'image' => "16768976610.jpg"],
                        ['name' => "Tower Case", 'image' => "16768975960.jpg"],
                        ['name' => "Core I7 Desktop PC", 'image' => "16768975560.jpg"],
                        ['name' => "Core I5 Desktop", 'image' => "16768974280.jpg"],
                        ['name' => "Core I3 Desktop", 'image' => "16768973690.jpg"],
                        ['name' => '27" Monitor', 'image' => "16768973230.jpg"],
                        ['name' => '26" Monitor', 'image' => "16768972610.jpg"],
                        ['name' => '24" Monitor', 'image' => "16768972200.jpg"],
                        ['name' => '23" Monitor', 'image' => "16768970820.jpg"],
                        ['name' => "Computer Accessories", 'image' => "16768971040.jpg"],
                        ['name' => "Laptop, Tablet PC with Keyboard (and Stylus Pen), 10.1 Windows Tablet, Convertible Laptop", 'image' => "16768971600.jpg"],
                        ['name' => "MacBook Pro", 'image' => "16768971510.jpg"],
                        ['name' => "Macbook Air", 'image' => "16768971440.jpg"],
                        ['name' => "Dell launches premium XPS consumer laptops", 'image' => "16768971380.jpg"],
                        ['name' => "Dell Inspiron laptop & 2-in-1 Laptop Computers", 'image' => "16768971300.jpg"],
                        ['name' => "Surface", 'image' => "16768971210.jpg"],
                        ['name' => "Gaming Monitor", 'image' => "16768971140.jpg"]
                    ],
                    'categories' => [
                        ['name' => "Laptop", 'image' => "167689265245873.png"],
                        ['name' => "Computer", 'image' => "167689265233069.png"],
                        ['name' => "Hard Disk", 'image' => "167689265215598.png"],
                        ['name' => "Computer Disk", 'image' => "167689265224060.png"],
                        ['name' => "Accessories", 'image' => "167689265215598.png"]
                    ],
                    'banners' => ["1683365411.jpg", "1683365259.jpg", "1658745955.jpg", "1683366223.jpg"],
                    'sliders' => ["1676898656.jpg", "1676898693.jpg", "1677063208.jpg"]
                ];
                break;

            case "Restaurants":
            case "Food":
                $data = [
                    'theme_value' => 'sixteen',
                    'header_color' => '#da3434',
                    'checkout_page' => 'sixteen',
                    'product_card' => 'two',
                    'preloader' => 'eight',
                    'template_id' => '16',
                    'products' => [
                        ['name' => "Brixton patrol all terrain anorak jacket", 'image' => "16695251560.jpg"],
                        ['name' => "Brown bear ice-cream", 'image' => "16695253120.jpg"],
                        ['name' => "Cavier Susi", 'image' => "16695253440.jpg"],
                        ['name' => "Salad", 'image' => "16695262110.jpg"],
                        ['name' => "Red Chili Tomyam Soup", 'image' => "16695263050.jpg"],
                        ['name' => "Burger", 'image' => "16695264990.jpg"],
                        ['name' => "Steak", 'image' => "16695265450.jpg"],
                        ['name' => "Sweet Vegetable Salad", 'image' => "16695266230.jpg"],
                        ['name' => "Vegetable Susi", 'image' => "16695267060.jpg"],
                        ['name' => "Brown bear cushion", 'image' => "16695252390.jpg"]
                    ],
                    'categories' => [
                        ['name' => "Meals", 'image' => "167655647421161.png"],
                        ['name' => "Appetizer", 'image' => "167655647413025.png"],
                        ['name' => "Soup", 'image' => "167655647453357.png"],
                        ['name' => "Snacks", 'image' => "173996857249955.png"],
                        ['name' => "Salad", 'image' => "167655647448015.png"],
                        ['name' => "Dessert", 'image' => "167655647436390.png"]
                    ],
                    'banners' => ["1669530312.jpg", "1669528621.jpg", "1669529883.png"],
                    'sliders' => ["1669531558.jpg", "1669532272.jpg", "1669531558.jpg"]
                ];
                break;

            case "Grocery":
            case "Dairy":
            case "Dry Fruits":
            case "Baby Products":
            case "Fish":
            case "Meat":
                $data = [
                    'theme_value' => 'twentyfive',
                    'header_color' => '#4c9a2a',
                    'checkout_page' => 'twentyfive',
                    'product_card' => 'four',
                    'preloader' => 'seven',
                    'template_id' => '26',
                    'products' => [
                        ['name' => "Chingri Fish", 'image' => "16791370750.jpg"],
                        ['name' => "Savlon 500ml", 'image' => "16791214960.jpg"],
                        ['name' => "Miniket Rice", 'image' => "16791214660.jpg"],
                        ['name' => "Nazirshail Rice", 'image' => "16791214400.jpg"],
                        ['name' => "Ginger", 'image' => "16791213240.jpg"],
                        ['name' => "Detol 1 ltr", 'image' => "16791212960.jpg"],
                        ['name' => "Peyara", 'image' => "16791212610.jpg"],
                        ['name' => "Kacha Morich", 'image' => "16791212280.jpg"],
                        ['name' => "Kamranga", 'image' => "16791211890.jpg"],
                        ['name' => "Potol", 'image' => "16791211550.jpg"],
                        ['name' => "Korolla", 'image' => "16791211290.jpg"],
                        ['name' => "Onion", 'image' => "16791211040.jpg"],
                        ['name' => "Potato Regular", 'image' => "16791210520.jpg"],
                        ['name' => "Pepsi", 'image' => "16701572460.png"],
                        ['name' => "Green Banana", 'image' => "16701572730.jpg"],
                        ['name' => "Beans", 'image' => "16701573010.jpg"],
                        ['name' => "Capcicum", 'image' => "16701573300.png"],
                        ['name' => "Marks Milk Powder", 'image' => "16701573610.webp"],
                        ['name' => "Diploma Milk Powder", 'image' => "16701573960.webp"],
                        ['name' => "Harpic", 'image' => "16701574240.png"],
                        ['name' => "Raisins", 'image' => "16791208420.jpg"],
                        ['name' => "Gura Dudh", 'image' => "16791207910.webp"],
                        ['name' => "Mouthwash", 'image' => "16701575640.webp"]
                    ],
                    'categories' => [
                        ['name' => "Organic", 'image' => "1678878721127571.png"],
                        ['name' => "Meat", 'image' => "167887872162563.png"],
                        ['name' => "Fish", 'image' => "167897478705108.png"],
                        ['name' => "Fruits", 'image' => "1678878721118318.png"],
                        ['name' => "Cleaning", 'image' => "167911921102099.png"]
                    ],
                    'banners' => ["1670158045.jpg", "1670158232.jpg"],
                    'sliders' => ["1679120053.png", "1670157740.jpg", "1679120102.jpg", "1670157806.jpg"]
                ];
                break;

            default:
                $data = [
                    'theme_value' => 'default',
                    'header_color' => '#da3434',
                    'checkout_page' => 'default',
                    'product_card' => 'default',
                    'preloader' => 'default',
                    'template_id' => 'default',
                    'products' => [
                        ['name' => "Demo Product 01", 'image' => "Product_01.jpg"],
                        ['name' => "Demo Product 02", 'image' => "Product_02.jpg"],
                        ['name' => "Demo Product 03", 'image' => "Product_03.jpg"],
                        ['name' => "Demo Product 04", 'image' => "Product_04.jpg"],
                        ['name' => "Demo Product 05", 'image' => "Product_05.jpg"],
                        ['name' => "Demo Product 06", 'image' => "Product_06.jpg"],
                        ['name' => "Demo Product 07", 'image' => "Product_07.jpg"],
                        ['name' => "Demo Product 08", 'image' => "Product_08.jpg"],
                        ['name' => "Demo Product 09", 'image' => "Product_09.jpg"],
                        ['name' => "Demo Product 10", 'image' => "Product_10.jpg"]
                    ],
                    'categories' => [
                        ['name' => "Category 01", 'image' => "167689265245873.png"],
                        ['name' => "Category 02", 'image' => "167689265233069.png"],
                        ['name' => "Category 03", 'image' => "167689265215598.png"],
                        ['name' => "Category 04", 'image' => "167689265224060.png"],
                        ['name' => "Category 05", 'image' => "167689265215598.png"],
                        ['name' => "Category 06", 'image' => "167689265205732.png"]
                    ],
                    'banners' => ["Banner_01.jpg", "Banner_02.jpg", "Banner_03.jpg"],
                    'sliders' => ["Slider_01.jpg", "Slider_02.jpg", "Slider_03.jpg"]
                ];
                break;
        }


        // Fetch theme values matching the category ID
        $themeValues = DemoStoreData::where('type', 'theme')
            ->whereRaw("FIND_IN_SET(?, category_id)", [$storeCategoryId])
            ->inRandomOrder()
            ->value('theme_value');

        // Fetch header colors matching the category ID
        $headerColors = DemoStoreData::where('type', 'header')
            ->whereRaw("FIND_IN_SET(?, category_id)", [$storeCategoryId])
            ->inRandomOrder()
            ->value('header_color');

        // Fetch products
        $products = DemoStoreData::where('type', 'product')
            ->whereRaw("FIND_IN_SET(?, category_id)", [$storeCategoryId])
            ->inRandomOrder()
            ->limit(20)
            ->get(['product_name as name', 'product_image as image'])
            ->toArray();

        // Fetch categories
        $categories = DemoStoreData::where('type', 'category')
            ->whereRaw("FIND_IN_SET(?, category_id)", [$storeCategoryId])
            ->inRandomOrder()
            ->limit(5)
            ->get(['category_name as name', 'category_image as image'])
            ->toArray();

        // Fetch banners for the category
        $banners = DemoStoreData::where('type', 'banner')
            ->whereRaw("FIND_IN_SET(?, category_id)", [$storeCategoryId])
            ->inRandomOrder()
            ->limit(5)
            ->pluck('banner_image')
            ->filter() // Remove any null or empty values
            ->unique() // Ensure uniqueness
            ->values()
            ->toArray();

        // Fetch sliders for the category
        $sliders = DemoStoreData::where('type', 'slider')
            ->whereRaw("FIND_IN_SET(?, category_id)", [$storeCategoryId])
            ->inRandomOrder()
            ->limit(5)
            ->pluck('slider_image')
            ->filter() // Remove nulls
            ->unique() // Remove duplicates
            ->values()
            ->toArray();

        $checkoutPage = Designlist::where('type', 'checkout_page')
            ->where(function ($query) use ($storeCategoryId, $storeCategoryName) {
                $query->whereRaw("FIND_IN_SET(?, category)", [$storeCategoryId])
                    ->orWhere('category', $storeCategoryName); // match string value
            })
            ->inRandomOrder()
            ->value('value');

        $productCard = Designlist::where('type', 'product_card')
            ->where(function ($query) use ($storeCategoryId, $storeCategoryName) {
                $query->whereRaw("FIND_IN_SET(?, category)", [$storeCategoryId])
                    ->orWhere('category', $storeCategoryName); // match string value
            })
            ->inRandomOrder()
            ->value('value');

        $preloader = Designlist::where('type', 'preloader')
            ->where(function ($query) use ($storeCategoryId, $storeCategoryName) {
                $query->whereRaw("FIND_IN_SET(?, category)", [$storeCategoryId])
                    ->orWhere('category', $storeCategoryName); // match string value
            })
            ->inRandomOrder()
            ->value('value');

        // Build the complete data structure
        $data['theme_value'] = $themeValues ?? 'seven';
        $data['header_color'] = $headerColors ?? '#E74C3C';
        $data['checkout_page'] = $checkoutPage ?? 'eleven';
        $data['product_card'] = $productCard ?? 'three';
        $data['preloader'] = $preloader ?? 'two';

        if (isset($products) && count($products) > 0) {
            $data['products'] = $products;
        }
        if (isset($categories) && count($categories) > 0) {
            $data['categories'] = $categories;
        }
        if (isset($banners) && count($banners) > 0) {
            $data['banners'] = $banners;
        }
        if (isset($sliders) && count($sliders) > 0) {
            $data['sliders'] = $sliders;
        }

        // Merge two array
        $data = array_merge($getData, $data);

        $this->_createProduct($data);
    }

    protected function _createProduct($data)
    {
        $store = Store::findOrFail($data['store_id']);

        $user_id = Auth::user()->id;

        $design = new Design();
        $design->name = $data['store_name'];
        $design->header = $data['theme_value'];
        $design->header_color = $data['header_color'];
        $design->hero_slider = $data['theme_value'];
        $design->banner = $data['theme_value'];
        $design->feature_category = $data['theme_value'];
        $design->feature_product = $data['theme_value'];
        $design->best_sell_product = $data['theme_value'];
        $design->new_arrival = $data['theme_value'];
        $design->product = $data['theme_value'];
        $design->testimonial = $data['theme_value'];
        $design->footer = $data['theme_value'];
        $design->auth = $data['theme_value'];
        $design->single_product_page = $data['theme_value'];
        $design->shop_page = $data['theme_value'];
        $design->checkout_page = $data['checkout_page'];
        $design->product_card = $data['product_card'];
        $design->preloader = $data['preloader'];
        $design->template_id = $data['template_id'] ?? NULL;
        $design->uid = $user_id;
        $design->customer_id = $data['customer_id'];
        $design->store_id = $data['store_id'];
        $design->creator = $user_id;
        $design->editor = $user_id;
        $design->save();

        // Categories
        $createdCategoryIds = [];
        foreach ($data['categories'] as $category) {
            $cat1 = new Category();
            $cat1->name = $category['name'];
            $cat1->parent = '0';
            $cat1->banner = "Category_01.jpg";
            $cat1->icon = $category['image'] ?? "";
            $cat1->status = "active";
            $cat1->position = 0;
            $cat1->uid = $user_id;
            $cat1->customer_id = $data['customer_id'];
            $cat1->store_id = $data['store_id'];
            $cat1->creator = $user_id;
            $cat1->editor = $user_id;
            $cat1->save();

            $createdCategoryIds[] = $cat1->id; // collect category ID
        }

        // Slider
        foreach ($data['sliders'] as $slider) {
            $slider1 = new Slider();
            $slider1->image = $slider;
            $slider1->position = 0;
            $slider1->status = "active";
            $slider1->uid = $user_id;
            $slider1->customer_id = $data['customer_id'];
            $slider1->store_id = $data['store_id'];
            $slider1->creator = $user_id;
            $slider1->editor = $user_id;
            $slider1->save();
        }

        // Banner
        foreach ($data['banners'] as $bannerName) {
            $banner = new Banner();
            $banner->image = $bannerName;
            $banner->status = "active";
            $banner->uid = $user_id;
            $banner->customer_id = $data['customer_id'];
            $banner->store_id = $data['store_id'];
            $banner->creator = $user_id;
            $banner->editor = $user_id;
            $banner->save();
        }

        // product
        foreach ($data['products'] as $productData) {
            $product = new Product();
            $product->name = $productData['name'] ?? "";
            $product->description = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
            software like Aldus PageMaker including versions of Lorem Ipsum.";
            $product->regular_price = rand(400, 2100);
            $product->discount_type = "fixed";
            $product->promotional_price = rand(1, 30);
            $product->tax_type = "fixed";
            $product->tax_rate = rand(1, 20);
            $product->quantity = rand(20, 30);
            $product->seo_keywords = "demo";
            $product->images = $productData['image'] ?? "";

            if (!empty($createdCategoryIds)) {
                $randomCategoryIds = collect($createdCategoryIds)
                    ->shuffle()
                    ->take(rand(1, min(3, count($createdCategoryIds))))
                    ->implode(',');

                $product->category = $randomCategoryIds;
            } else {
                // Handle the case when there are no categories
                $product->category = rand(1, 5);  // or you can assign an empty string or null
            }

            $product->tags = "demo";
            $product->status = "active";
            $product->SKU = "DEMO1";
            $product->barcode = rand(1000000000, 2000000000);
            $product->cost = rand(30, 100);
            $product->uid = $user_id;
            $product->customer_id = $data['customer_id'];
            $product->store_id = $data['store_id'];
            $product->creator = $user_id;
            $product->editor = $user_id;
            $product->currency_id = $store->currency;
            $product->save();
        }

        return true;
    }

    public function deactivestore()
    {
        if (Auth::user()->type == "dropshipper") {
            $customer = Customer::where('uid', Auth::user()->id)->first();
            $customer_id = $customer->id ?? NULL;

            $store = Store::where('user_id', Auth::user()->id);
            if (!is_null($customer_id)) {
                $store->where('customer_id', $customer_id);
            }
            $storeCount = $store->count();
            if ($storeCount) {
                Session::flash('error', 'You can not create Multiple stores as a Dropshipper');
                return redirect()->back();
            }
        }

        $user = Auth::user()->id;
        $customer = Customer::where('uid', $user)->first();
        $store = Store::where('id', $customer->active_store)->first();
        if ($store) {
            $store->status = "deactive";
            $store->save();
            $customer->active_store = "0";
            $customer->save();
            return redirect('/');
        } else {
            return redirect()->route('store.list');
        }
    }

    public function activestore($id)
    {
        $customer = Customer::where('uid', Auth::user()->id)->first();
        $customer->active_store = $id;
        $customer->save();
        $store = Store::find($id);
        $store->status = "active";
        $store->save();
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
