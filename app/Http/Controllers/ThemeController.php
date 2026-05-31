<?php

namespace App\Http\Controllers;

use App\Models\WebsiteSetupDetails;
use App\Models\WebsiteSetupImage;
use App\Models\WebsiteSetupProducts;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use Session;
use Auth;
use App\Models\Store;
use App\Models\Menu;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Headersetting;
use App\Models\Design;
use App\Models\Template;
use App\Models\Toptool;
use App\Models\Themecustomize;
use App\Models\Domain;
use App\Models\Tricket;
use App\Models\Websitesetup;
use App\Models\Paymentgateway;

class ThemeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkrole()
    {
        if (Auth::user()->type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
            $role = Role::where('id', $staff->role_id)->first();
            if (isset($role)) {

                $permission = explode(',', $role->permission);
                foreach ($permission as $key => $pr) {
                    if ($pr == 'activity_log') {
                        $activity_log = 1;
                    } else {
                    }
                }
            }
        }
    }

    public function index()
    {
        $urls = "design";

        /* extract user_id, user_type, store_id, customer_id, customer (NO extract for IDE safety) */
        $userData = getUserData();
        $user_id = $userData['user_id'] ?? null;
        $user_type = $userData['user_type'] ?? null;
        $store_id = $userData['store_id'] ?? null;
        $customer_id = $userData['customer_id'] ?? null;
        $customer = $userData['customer'] ?? null;

        /* increment tools count */
        topToolsCount('Theme', "web-design.png", "/design/theme");

        try {
            $designed_templates = Template::select(
                'templates.feature_image as feature_image',
                'templates.name as name',
                'templates.short_description as short_description',
                'templates.liveurl as liveurl',
                'templates.id as id',
                'designs.id as design_id'
            )
                ->leftJoin('designs', function ($join) use ($store_id) {
                    $join->on('designs.template_id', '=', 'templates.id')
                        ->where('designs.store_id', '=', $store_id);
                })
                ->where('templates.status', 'active')
                ->orderBy('design_id', 'desc')
                ->orderBy('templates.position', 'asc') // Assuming position is a column in templates
                ->get();

            $headerSetting = Headersetting::where("store_id", $store_id)->first();

            return view('admin.design.themes.index')
                ->with('urls', $urls)
                ->with('headerSetting', $headerSetting)
                ->with('designed_templates', $designed_templates);
        } catch (\Exception $exception) {
            return view('errors');
        }
    }

    public function view($id)
    {
        /* find template */
        $template = Template::find($id);

        /* extract user_id, user_type, store_id, customer_id (NO extract for IDE safety) */
        $userData = getUserData();
        $user_id = $userData['user_id'] ?? null;
        $user_type = $userData['user_type'] ?? null;
        $store_id = $userData['store_id'] ?? null;
        $customer_id = $userData['customer_id'] ?? null;

        /* increment tools count */
        topToolsCount('Theme', "web-design.png", "/design/theme");

        return view('admin.design.themes.view')->with('template', $template);
    }

    public function active($id)
    {
        /* extract user_id, user_type, store_id, customer_id (NO extract for IDE safety) */
        $userData = getUserData();
        $user_id = $userData['user_id'] ?? null;
        $user_type = $userData['user_type'] ?? null;
        $store_id = $userData['store_id'] ?? null;
        $customer_id = $userData['customer_id'] ?? null;

        /* increment tools count */
        topToolsCount('Theme', "web-design.png", "/design/theme");

        /* find template and design */
        $template = Template::find($id);
        $design = Design::where('store_id', $store_id)->first();

        if (isset($design)) {
            $design->banner_bottom = $template->banner_bottom;
        } else {
            $design = new Design();
            $design->header_color = "#ffffff";
            $design->text_color = "#000000";
            $design->uid = $user_id;
            $design->customer_id = $customer_id;
            $design->store_id = $store_id;
            $design->creator = $user_id;
            $design->editor = $user_id;
        }

        $design->header = $template->header;
        $design->hero_slider = $template->slider;
        $design->banner = $template->banner;
        $design->feature_category = $template->feature_category;
        $design->product = $template->product;
        $design->feature_product = $template->feature_product;
        $design->best_sell_product = $template->best_sell_product;
        $design->new_arrival = $template->new_arrival;
        $design->testimonial = $template->testimonial;
        $design->footer = $template->footer;
        $design->single_product_page = $template->single_product_page;
        $design->shop_page = $template->shop_page;
        $design->checkout_page = $template->checkout_page;
        $design->login_page = $template->login_page;
        $design->profile_page = $template->profile_page;
        $design->invoice = $template->invoice;
        $design->product_card = $template->product_card;
        $design->product_modal = $template->product_modal;
        $design->preloader = $template->preloader;
        $design->mobile_bottom_menu = $template->mobile_bottom_menu;
        $design->blog = $template->blog;
        $design->contact = $template->contact;
        $design->offer = $template->offer;
        $design->auth = $template->auth;
        $design->template_id = $template->id;
        $design->save();

        Session::flash('message', 'Theme Set Successfully.');
        return back();
    }

    public function themecustomize()
    {
        $urls = "addons";

        /*extract user_id, user_type, store_id, customer_id*/
        extract(getUserData());

        /*increment tools count*/
        topToolsCount('ThemeCustomize', "color-scheme.png", "/themecustomize");

        /*get theme customize requested*/
        $req = Themecustomize::where('store_id', $store_id)->first();

        /*get domain*/
        $domain = Domain::where('store_id', $store_id)->get();

        /*having domain then access */
        if (isset($domain) && count($domain) > 1) {
            $view = "Active";
        } else {
            $view = "InActive";
        }
        return view('admin.addon.theme')->with('urls', $urls)->with('view', $view)->with('req' . $req)->with('store_id', $store_id);
    }

    public function savecustomizinfo(Request $request)
    {
        /*extract user_id, user_type, store_id, customer_id*/
        extract(getUserData());

        $old = Themecustomize::where('store_id', $store_id)->first();
        if (isset($old)) {
            Session::flash('error', 'Already Submitted');
            return back();
        } else {
            $Tt = new Themecustomize();
            $Tt->theme = $request->theme;
            $Tt->details = $request->details;
            $Tt->phone = $request->phone;
            $Tt->store_id = $store_id;
            $Tt->customer_id = $customer_id;
            $Tt->seen = NULL;
            $Tt->save();

            $notificationData = [
                "title" => "Theme customization request by (" . ($store->name ?? $Tt->phone ?? '') . ") - " . formatDateWithTime($Tt->created_at),
                "type" => "theme_customize",
                "user_type" => "superadmin",
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }

            $tts = Themecustomize::find($Tt->id);
            $tts->token = "TT" . $Tt->id . rand(0, 999);
            $tts->save();
            $tricket = new Tricket();
            $tricket->token = $tts->token;
            $tricket->message = $request->details;
            $tricket->sender = "admin";
            $tricket->seen = '1';
            $tricket->save();

            $notificationData = [
                "title" => "New ticket created (" . ($store->name ?? '') . ") - " . formatDateWithTime($tricket->created_at),
                "body" => $tricket->message ?? NULL,
                "type" => "ticket",
                "user_type" => "superadmin",
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }

            Session::flash('message', 'Successfully Submitted');
            return back();
        }
    }

    public function sendmessagetoken(Request $request, $token)
    {
        if ($request->details == "" && $request->image == "") {
            return back();
        } else {
            $tokens = new Tricket();
            $tokens->token = $token;
            if ($request->details == "") {
                $tokens->message = null;
            } else {
                $tokens->message = $request->details;
            }
            if ($request->image == "") {
                $tokens->image = null;
            } else {
                $imgName = Carbon::now()->timestamp . '.' . $request->image->extension();
                $request->image->storeAs('token', $imgName);
                $tokens->image = $imgName;
            }
            $tokens->sender = "admin";
            $tokens->seen = null;
            $tokens->save();

            $notificationData = [
                "title" => "New ticket created - " . formatDateWithTime($tokens->created_at),
                "body" => $tokens->message ?? NULL,
                "type" => "ticket",
                "user_type" => "superadmin",
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }

            return back();
        }
    }

    public function websitesetup()
    {
        $urls = "addons";

        /*extract user_id, user_type, store_id, customer_id*/
        extract(getUserData());

        /*increment tools count*/
        topToolsCount('Websitesetup', "color-scheme.png", "/websitesetup");

        /*find website and check is website was set up*/
        $websitesetup = Websitesetup::where('store_id', $store_id)->latest()->first();
        if (!isset($websitesetup)) {
            $websitesetup = new Websitesetup();
            $websitesetup->store_id = $store_id;
            $websitesetup->data_submit = 0;
            $websitesetup->save();
        }

        $products = WebsiteSetupProducts::with("image")->where("store_id", $store_id)->get();
        $productView = view("admin.addon.websitesetup-product-list", ['products' => $products]);

        return view('admin.addon.websitesetup', [
            "urls" => $urls,
            "websitesetup" => $websitesetup,
            "store_id" => $store_id,
            "productView" => $productView,
        ]);
    }

    public function paymentgateway()
    {
        return redirect()->route("admin.index");

//        $urls = "addons";
//
//        /*extract user_id, user_type, store_id, customer_id*/
//        extract(getUserData());
//
//        /*increment tools count*/
//        topToolsCount('Paymentgateway', "color-scheme.png", "/paymentgateway");
//
//        /*find payment gateway and check is payment gateway was set up*/
//        $req = Paymentgateway::where('store_id', $store_id)->get();
//
//        if (isset($req)) {
//            $view = "Active";
//        } else {
//            $view = "InActive";
//        }
//        return view('admin.addon.paymentgateway')->with('urls', $urls)->with('view', $view)->with('req', $req)->with('store_id', $store_id);
    }

    public function savepaymentinfo(Request $request)
    {

        /*extract user_id, user_type, store_id, customer_id*/
        extract(getUserData());

        /*find payment gateway*/
        $req = Paymentgateway::where('id', $request->id)->where('store_id', $store_id)->first();

        /*if having payment gateway then update data*/
        if (isset($request->payment_company)) {
            $req->payment_company = $request->payment_company;
        }
        $req->app_key = $request->app_key ?? null;
        $req->app_secret = $request->app_secret ?? null;
        $req->api_username = $request->api_username ?? null;
        $req->api_password = $request->api_password ?? null;
        $req->ssl_store_id = $request->store_id ?? null;
        $req->ssl_store_password = $request->store_password ?? null;
        $req->merchant_id = $request->merchant_id ?? null;
        $req->merchant_number = $request->merchant_number ?? null;
        $req->public_key = $request->public_key ?? null;
        $req->private_key = $request->private_key ?? null;
        $req->update();

        Session::flash('message', 'Successfully Saved');
        return back();
    }

    public function websitesetupSaveProduct(Request $request)
    {
        try {
            $store_id = getUserData()['store_id'] ?? "";

            if (empty($request->product_name)) {
                return sendError("Product Name is required!");
            } elseif (empty($request->description)) {
                return sendError("Product Description is required!");
            } elseif (empty($request->category)) {
                return sendError("Product Category is required!");
            } elseif (empty($request->price)) {
                return sendError("Product Price is required!");
            }

            if (isset($store_id) && $store_id != "") {
                DB::beginTransaction();

                $product = new WebsiteSetupProducts();
                $product->store_id = $store_id;
                //$product->model_no = $request->model_no;
                $product->product_name = $request->product_name;
                $product->description = $request->description;
                $product->category = $request->category;
                //$product->sub_category = $request->sub_category;
                $product->price = $request->price;
                //$product->brand = $request->brand;
                //$product->supplier = $request->supplier;
                //$product->cost = $request->cost;
                //$product->discount = $request->discount;
                //$product->color = $request->color;
                //$product->size = $request->size;
                //$product->unit = $request->unit;
                $product->other_info = $request->other_info;
                $product->save();

                if ($request->hasFile('images')) {
                    $this->saveProductImage($request->file('images'), $product->id, $store_id);
                }

                $products = WebsiteSetupProducts::with("image")->where("store_id", $store_id)->get();
                $view = view("admin.addon.websitesetup-product-list", ['products' => $products])->render();

                DB::commit();
                return sendResponse("Record saved successfully", $view);

            }

            DB::rollBack();
            return sendError("Record did not saved!");

        } catch (\Exception) {
            DB::rollBack();
            return sendError("Record did not saved!");
        }
    }

    public function websitesetupUploadProduct(Request $request)
    {
        try {
            $store_id = getUserData()['store_id'] ?? "";

            if (empty($request->product_id)) {
                return sendError("Product Name is required!");
            }
            $product_id = $request->product_id ?? "";

            if (isset($store_id) && $store_id != "") {
                DB::beginTransaction();

                if ($request->hasFile('images')) {
                    $this->saveProductImage($request->file('images'), $product_id, $store_id);
                }

                $images = WebsiteSetupImage::where("store_id", $store_id)->where("product_id", $product_id)->get();
                $view = view("admin.addon.websitesetup-image-list", [
                    'images' => $images,
                    "product_id" => $product_id
                ])->render();

                DB::commit();
                return sendResponse("Record saved successfully", $view);

            }

            DB::rollBack();
            return sendError("Record did not saved!");

        } catch (\Exception) {
            DB::rollBack();
            return sendError("Record did not saved!");
        }
    }

    public function saveProductImage($requestImage, $product_id, $store_id)
    {
        $imageUploadPath = 'assets/images/setup/';
        foreach ($requestImage as $image) {
            if ($image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($imageUploadPath), $filename);

                $websiteSetupImage = new WebsiteSetupImage();
                $websiteSetupImage->store_id = $store_id ?? NULL;
                $websiteSetupImage->product_id = $product_id ?? NULL;
                $websiteSetupImage->image = $filename;
                $websiteSetupImage->save();
            }
        }
    }

    public function websitesetupProductDelete($id)
    {
        $store_id = getUserData()['store_id'] ?? "";
        $product = WebsiteSetupProducts::where("id", $id)->where("store_id", $store_id)->first();

        if (isset($product)) {
            $productImages = WebsiteSetupImage::where("product_id", $product->id)->where("store_id", $store_id)->get();
            foreach ($productImages as $item) {
                if (isset($item->image)) {
                    $imageUploadPath = public_path('assets/images/setup') . "/" . $item->image;

                    if (file_exists($imageUploadPath)) {
                        unlink($imageUploadPath);
                    }
                }
                $item->delete();
            }

            $product->delete();

            return redirect()->back()->with("success", "Record deleted successfully");
        }

        return redirect()->back()->with("error", "Record not found!");
    }


    public function websitesetupSaveSetupDetails(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'mobile_number' => 'required|string',
            'whats_app_number' => 'required|string',
            'email' => 'required|string|email',
            'delivery_cost' => 'required|numeric',
            'logo' => 'required',
            'theme_color' => 'required|string',
        ], [
            'mobile_number.required' => 'Mobile Number is required!',
            'whats_app_number.required' => 'Whats App Number is required!',
            'email.required' => 'Email Address is required!',
            'email.email' => 'Please Enter Valid Email Address!',
            'delivery_cost.required' => 'Delivery Cost is required!',
            'delivery_cost.numeric' => 'Delivery Cost must be numeric!',
            'logo.required' => 'Logo is required!',
            'theme_color.required' => 'Theme color is required!',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $store_id = getUserData()['store_id'] ?? "";

        $details = new WebsiteSetupDetails();
        $details->store_id = $store_id;
        $details->facebook_link = $request->facebook_link ?? "";
        $details->instagram_link = $request->instagram_link ?? "";
        $details->mobile_number = $request->mobile_number ?? "";
        $details->whats_app_number = $request->whats_app_number ?? "";
        $details->youtube_link = $request->youtube_link ?? "";
        $details->email = $request->email ?? "";
        $details->delivery_cost = $request->delivery_cost ?? "";
        $details->tax = $request->tax ?? "";
        $details->address = $request->address ?? "";
        $details->theme_color = $request->theme_color ?? "";
        $details->short_description = $request->short_description ?? "";

        if ($request->file('logo')) {
            $image = $request->file('logo');
            $validation = imageValidation($image, $store_id);
            if ($validation) {
                return back()->with('error', $validation);
            }

            $imageUploadPath = 'assets/images/setting/';
            $imageName = uploadFile($image, $imageUploadPath);

            $details->logo = $imageName;
        }

        $details->save();

        $websiteSetup = Websitesetup::where("store_id", $store_id)->first();
        if (isset($websiteSetup)) {
            $websiteSetup->data_submit = 1;
            $websiteSetup->save();
        }

        return redirect()->back()->with("success", "Record Saved Successfully");
    }


    public function deleteWebsiteProductImage($id)
    {
        if (!isset($id) || empty($id)) {
            return sendError("Record Id Missing!");
        }

        $product = WebsiteSetupImage::where("id", $id)->first();
        if (isset($product)) {
            if (isset($product->image)) {
                $imageUploadPath = public_path('assets/images/setup') . "/" . $product->image;

                if (file_exists($imageUploadPath)) {
                    unlink($imageUploadPath);
                }
            }
            $product->delete();

            return sendResponse("Record deleted successfully");
        }

        return sendError("Record not found!");
    }


}
