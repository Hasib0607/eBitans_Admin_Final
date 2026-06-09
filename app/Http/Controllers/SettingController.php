<?php

namespace App\Http\Controllers;

use App\Http\Traits\ActivityLogTraits;
use App\Models\Campaign;
use App\Models\CheckoutForm;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Domain;
use App\Models\Headersetting;
use App\Models\Paymentgateway;
use App\Models\Staff;
use App\Models\Store;
use App\Models\User;
use App\Models\Visitorcount;
use App\Services\Domains\AccountDomainConnector;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class SettingController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function setting()
    {
        $urls = 'settings';
        /*extract user_id, user_type, store_id, customer_id*/
        extract(getUserData());

        /*increment tools count*/
        topToolsCount('Setting', "settings-2.png", "/settings");

        /*get header setting*/
        $setting = Headersetting::convertCurrency($store_id)->first();

        $bkash = Paymentgateway::where('store_id', $store_id)->where('payment_company', 'Bkash')->first();
        $nagad = Paymentgateway::where('store_id', $store_id)->where('payment_company', 'Nagad')->first();
        $ssl = Paymentgateway::where('store_id', $store_id)->where('payment_company', 'SSL')->first();
        $uddoktapay = Paymentgateway::where('store_id', $store_id)->where('payment_company', 'uddoktapay')->first();
        $paypal = Paymentgateway::where('store_id', $store_id)->where('payment_company', 'paypal')->first();
        $stripe = Paymentgateway::where('store_id', $store_id)->where('payment_company', 'stripe')->first();
        $checkoutFormEmail = CheckoutForm::where("store_id", $store_id)->where('name', 'email')->value("status");
        $checkoutFormEmail = isset($checkoutFormEmail) ?: 0;

        $currencies = Currency::select(
            'currencies.id',
            'currencies.code',
            'currencies.customize_rate_status',
            'stores.currency'
        )
            ->leftJoin('stores', function ($join) use ($store_id) {
                $join->on('currencies.id', 'stores.currency')
                    ->where('stores.id', $store_id);
            })
            ->get();


        $store = Store::with('current_currency')->where('id', $store_id)->first();
        $activity = " Access Site Settings Page ";
        $this->saveactivity($activity);
        return view('admin.setting.index')
            ->with('data', $setting)
            ->with('store', $store)
            ->with('urls', $urls)
            ->with('checkoutFormEmail', $checkoutFormEmail)
            ->with('currencies', $currencies)
            ->with('bkash', $bkash)
            ->with('nagad', $nagad)
            ->with('uddoktapay', $uddoktapay)
            ->with('paypal', $paypal)
            ->with('stripe', $stripe)
            ->with('ssl', $ssl);
    }

    public function updatesetting(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "index" => "numeric",
            "name" => 'string',
            "short_description" => "string|max:255",
            "type" => "string",
            "phone" => "string",
            "email" => "email",
            "address" => "string|max:255",
            "map_address" => "string",
            "facebook_link" => "string|max:255",
            "instagram_link" => "string|max:255",
            "youtube_link" => "string|max:255",
            "whatsapp_phone" => "string|max:15",
            "lined_in_link" => "string",
            "currency" => "required|numeric",
            "currency_rate" => "numeric|max:99999.9999",
            "tax" => "numeric",
            'shipping_methods' => 'array',
            'shipping_methods.*.id' => 'numeric',
            'shipping_methods.*.area' => 'string|max:255',
            'shipping_methods.*.cost' => 'numeric',
            'selected_shipping_area' => 'nullable|integer',
        ], [
            "name.string" => 'Name must be a string',
            "short_description.string" => "Description must be a string",
            "short_description.max" => "The description must be less than 255 characters",
            "type.string" => "Type must be a string",
            "email.email" => "Invalid email address",
            "address.string" => "Address must be a string",
            "address.max" => "Address must be less than 255 characters",
            "map_address.string" => "Map address must be a string ",
            "facebook_link.string" => "Facebook link must be a string",
            "facebook_link.max" => "Facebook link must be less than 255 characters",
            "instagram_link.string" => "Instagram link must be a string",
            "instagram_link.max" => "Instagram link must be less than 255 characters",
            "instagram_link" => "string|max:255",
            "youtube_link.string" => "Youtube link must be a string",
            "youtube_link.max" => "Youtube link must be less than 255 characters",
            "youtube_link" => "string|max:255",
            "whatsapp_phone.string" => "Whatsapp phone number must be a string",
            "whatsapp_phone.max" => "Whatsapp phone number must be less than 15 characters",
            "lined_in_link" => "string",
            "currency" => "required|numeric",
            "currency_rate" => "numeric|max:99999.9999",
            "tax" => "numeric",
            'shipping_methods.*.area.string' => 'The shipping area must be a string.',
            'shipping_methods.*.area.max' => 'The shipping area may not be greater than 255 characters.',
            'shipping_methods.*.cost.numeric' => 'The cost must be a valid number.',
            'selected_shipping_area.integer' => 'Invalid selected shipping option.',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $shippingMethods = $request->shipping_methods ?? [];

        $reformattedShippingMethods = [];
        $idCounter = 1;

        foreach ($shippingMethods as $method) {
            $reformattedShippingMethods[] = [
                'id' => $idCounter++,
                'area' => $method['area'] ?? '',
                'cost' => isset($method['cost']) ? floatval($method['cost']) : 0,
            ];
        }

        $validIds = array_column($reformattedShippingMethods, 'id');
        $selected = $request->selected_shipping_area;
        if (!in_array($selected, $validIds)) {
            $selected = $reformattedShippingMethods[0]['id'] ?? null;
        }

        /*get user and customer*/
        $userData = getUserData();
        $user = $userData["user_id"];
        $store_id = $userData["store_id"];
        $customer_id = $userData["customer_id"];

        $changeAuthType = null;
        /*get store and save updated data*/
        $store = Store::find($store_id);
        if ($store->auth_type != $request->auth_type) {
            $changeAuthType = $request->auth_type;
        }
        $store->name = $request->name;
        $store->auth_type = $request->auth_type;
        if (isset($request->sms_plan)) {
            $store->sms_plan = 1;
        } else {
            $store->sms_plan = 0;
        }
        if ($request->currency != $store->currency) {
            $store->currency = $request->currency;
        }
        if (isset($request->currency_rate)) {
            $store->currency_rate = $request->currency_rate;
        }
        $store->save();

        if (!is_null($changeAuthType)) {
            $this->changeUserAuthType($changeAuthType, $store_id);
        }

        /* update campaign currency*/
        Campaign::where('store_id', $store_id)
            ->update(['currency_id' => $request->currency]);

        /*get header setting*/
        $hs = Headersetting::convertCurrency($store_id)->first();

        $prepayment = $hs->prepayment;
        if (isset($hs)) {
            $hs->website_name = $request->name;
            $hs->short_description = $request->short_description;
            if ($request->logo) {
                $imgName = Carbon::now()->timestamp . '.' . $request->logo->extension();
                $request->logo->storeAs('setting', $imgName);
                $hs->logo = $imgName;
            }

            if ($request->favicon) {
                $imgName = Carbon::now()->timestamp . '.' . $request->favicon->extension();
                $request->favicon->storeAs('setting/favicon', $imgName);
                $hs->favicon = 'favicon/' . $imgName;
            }

            $hs->phone = $request->phone;
            $hs->currency_id = $store->currency;
            $hs->address = $request->address;
            $hs->map_address = $request->map_address;
            $hs->custom_writing = $request->custom_writing ?? NULL;
            $hs->email = $request->email;
            $hs->facebook_link = $request->facebook_link;
            $hs->facebook_app_id = $request->facebook_app_id;
            $hs->instagram_link = $request->instagram_link;
            $hs->youtube_link = $request->youtube_link;
            $hs->messenger_link = $request->messenger_link;
            $hs->whatsapp_phone = $request->whatsapp_phone;
            $hs->lined_in_link = $request->lined_in_link;
            $hs->tax = $request->tax;

            $hs->shipping_methods = json_encode($reformattedShippingMethods) ?? NULL;
            $hs->selected_shipping_area = $selected;

            $hs->prepayment = $prepayment;
            if (isset($request->cod)) {
                $hs->cod = "active";

            } else {
                $hs->cod = "deactive";
            }
            if (isset($request->online)) {
                $hs->online = "active";
            } else {
                $hs->online = "deactive";
            }
            if (isset($request->bkash)) {
                $hs->bkash = "active";
            } else {
                $hs->bkash = "deactive";
            }
            if (isset($request->nagad)) {
                $hs->nagad = "active";
            } else {
                $hs->nagad = "deactive";
            }
            if (isset($request->paypal)) {
                $hs->paypal = "active";
            } else {
                $hs->paypal = "deactive";
            }
            if (isset($request->stripe)) {
                $hs->stripe = "active";
            } else {
                $hs->stripe = "deactive";
            }
            if (isset($request->amarpay)) {
                $hs->amarpay = "active";
            } else {
                $hs->amarpay = "deactive";
            }
            if (isset($request->uddoktapay)) {
                $hs->uddoktapay = "active";
            } else {
                $hs->uddoktapay = "deactive";
            }
            if (isset($request->merchant_bkash)) {
                $hs->merchant_bkash = "active";
            } else {
                $hs->merchant_bkash = "deactive";
            }
            if (isset($request->merchant_nagad)) {
                $hs->merchant_nagad = "active";
            } else {
                $hs->merchant_nagad = "deactive";
            }
            if (isset($request->merchant_rocket)) {
                $hs->merchant_rocket = "active";
            } else {
                $hs->merchant_rocket = "deactive";
            }

            $hs->save();
            $activity = " Update Setting";
            $this->saveactivity($activity);
            Session::flash('message', 'Setting Updated Successfully');
            return back();
        } else {
            $hs = new Headersetting();
            $hs->website_name = $request->name;
            if ($request->logo) {
                $imgName = Carbon::now()->timestamp . '.' . $request->logo->extension();
                $request->logo->storeAs('setting', $imgName);
                $hs->logo = $imgName;
            }
            if ($request->favicon) {
                $imgName = Carbon::now()->timestamp . '.' . $request->favicon->extension();
                $request->favicon->storeAs('setting/favicon', $imgName);
                $hs->favicon = 'favicon/' . $imgName;
            }
            $hs->phone = $request->phone;
            $hs->currency_id = $store->currency;
            $hs->address = $request->address;
            $hs->map_address = $request->map_address;
            $hs->custom_writing = $request->custom_writing ?? NULL;
            $hs->email = $request->email;
            $hs->tax = $request->tax;

            $hs->shipping_methods = json_encode($reformattedShippingMethods) ?? NULL;
            $hs->selected_shipping_area = $selected;

            if (isset($request->cod)) {
                $hs->cod = "active";
            } else {
                $hs->cod = "deactive";
            }

            if (isset($request->online)) {
                $hs->online = "active";
            } else {
                $hs->online = "deactive";
            }
            $hs->uid = $user;
            $hs->store_id = $store_id;
            $hs->customer_id = $customer_id;
            $hs->creator = $user;
            $hs->editor = $user;
            $hs->save();
            $activity = " Update Setting";
            $this->saveactivity($activity);
            Session::flash('message', 'Setting Updated Successfully');
            return back();
        }
    }

    // Change user auth type
    public function changeUserAuthType($auth_type, $store_id = null)
    {
        if (!is_null($store_id)) {
            User::where("store_id", $store_id)->update(["auth_type" => $auth_type]);

            if ($auth_type == "EmailEasyOrder") {
                $checkoutFormEmail = CheckoutForm::where("store_id", $store_id)->where('name', 'email')->first();
                if (isset($checkoutFormEmail) && $checkoutFormEmail->status == 0) {
                    $checkoutFormEmail->status = 1;
                    $checkoutFormEmail->update();
                }
            }
        }
    }

    public function profile()
    {
        $urls = 'settings';

        /*extract user_id, user_type, store_id, customer_id*/
        extract(getUserData());

        /*increment tools count*/
        topToolsCount('Profile', "resume.png", "/profile");

        /*getting data */
//            $setting = Customer::where('uid', $user_id)->first();
        $store = Store::where('id', $store_id)->first();
        $user = User::where('id', $user_id)->first();
        $this->saveactivity(" Access Profile Page");
        return view('admin.setting.profile.index')->with('store', $store)->with('user', $user)->with('urls', $urls);
    }

    public function updateprofile(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'address' => 'string|max:150',
            'email' => 'required|string|email|max:100',
            'userimage' => 'image|mimes:jpeg,png,jpg',
        ], [
            'name' => 'name is required and less than 100 characters',
            'address' => 'address is less than 150 characters',
            'email' => 'email is required and less than 100 characters',
            'userimage' => 'image must be jpeg, png, jpg',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        /*finding user*/
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        if ($request->userimage) {
            $imgName = Carbon::now()->timestamp . "U" . '.' . $request->userimage->extension();
            $request->userimage->storeAs('img', $imgName);
            $user->image = $imgName;
        }
        $user->save();
        $this->saveactivity(" Update Profile Information");
        Session::flash('message', 'Profile Setting Updated Successfully');
        return back();
    }

    public function staffprofile()
    {
        $urls = "staff.profile";
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
        }
        $ur = User::find(Auth::user()->id);
        $activity = " Access Staff Profile";
        $this->saveactivity($activity);
        return view('admin.setting.profile.staffprofile')->with('urls', $urls)->with('staff', $staff)->with('user',
            $ur);
    }

    public function updatestaffprofile(Request $request)
    {
        $staff = Staff::where('uid', Auth::user()->id)->first();
        if ($request->image) {
            $user = User::find(Auth::user()->id);
            $imgName = Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('img', $imgName);
            $user->image = $imgName;
            $user->save();
        }
        $staff->name = $request->name;
        $staff->phone = $request->phone;
        $staff->email = $request->email;
        $staff->address = $request->address;
        $staff->save();
        $activity = " Update Staff Profile Information";
        $this->saveactivity($activity);
        Session::flash('message', 'Profile  Updated Successfully');
        return back();
    }

    public function domain()
    {
        $urls = "settings";

        /*extract user_id, user_type, store_id, customer_id*/
        extract(getUserData());

        /*increment tools count*/
        topToolsCount('Domain', "domain-2.png", "/domain");

        $domain = Domain::where('store_id', $store_id)->where('customer_id', $customer_id)->orderBy('id', 'DESC')->get();
        $store = Store::where('id', $store_id)->first();
        $activity = " Access Domain List Page";
        $this->saveactivity($activity);
        return view('admin.setting.domain')->with('urls', $urls)->with('domain', $domain)->with('store', $store);
    }

    public function savedomain(Request $request)
    {
        if (is_null($request->domain) || empty($request->domain)) {
            Session::flash('error', 'Please enter domain name.');
            return back();
        }

        $user = Auth::user()->id;
        $userData = getUserData();
        $store = $userData['store'];
        $store_id = $userData['store_id'];
        $customer_id = $userData['customer_id'];

        if ($store->plan_id == 6) {
            Session::flash('error', 'Free plan can not add domain. Please change your plan..');
            return back();
        }

        $inputDomain = cleanDomain($request->domain);
        $exDomain = Domain::where("name", $inputDomain)->where("status", "!=", "Rejected")->first();
        if ($exDomain) {
            Session::flash('error', 'Domain already exist. Please choose another domain.');
            return back();
        }

        $domainData = new Domain();
        $domainData->name = $inputDomain;
        $domainData->status = "Processing";
        $domainData->uid = $user;
        $domainData->store_id = $store_id;
        $domainData->customer_id = $customer_id;
        $domainData->creator = $user;
        $domainData->editor = $user;
        $domainData->save();

        $domain = $domainData->name ?? "";

        if (empty($domain)) {
            Session::flash('error', 'Please provide domain name');
            return redirect()->back();
        }

        $result = app(AccountDomainConnector::class)->connect($domainData);

        if (!$result['status']) {
            Session::flash('error', $result['message']);
            return redirect()->back();
        }

        $domainData = $domainData->fresh();
        $linkURL = route("superadmin.domainrequest");
        $notificationData = [
            "title" => "Domain Activated (" . ($domainData->name ?? '') . ") - " . formatDateWithTime($domainData->created_at),
            "type" => "domain_request",
            "user_type" => "superadmin",
            "link" => $linkURL,
        ];

        if (isset($notificationData['title']) && !empty($notificationData['title'])) {
            createNotification($notificationData);
        }

        $activity = " Save Domain";
        $this->saveactivity($activity);
        Session::flash('success', 'Domain connect successfully');
        return redirect()->back();
    }

    public function changedomain(Request $request)
    {
        $id = $request->value;
        $domain = Domain::find($id);
        $store = Store::find($domain->store_id);

        if ($store->plan_id == 6) {
            return response()->json([
                'message' => 'Free plan can not add domain. Please change your plan..',
                'data' => 0
            ]);
        } else {
            $store->url = $domain->name;
            $store->save();

            $user = Auth::user();
            $user->domain = $domain->name;
            $user->save();

            $activity = " Change Domain";
            $this->saveactivity($activity);
            //  Session::flash('message','Domain Request Send Successfully.');
            return response()->json(['message' => 'Change domain Success', 'data' => $store]);
        }
    }

    public function storeOrderSMSTemplate(Request $request)
    {
        $order_sms = $request->order_sms;

        if (empty($order_sms)) {
            \Illuminate\Support\Facades\Session::flash("error", "Please enter SMS Text!");

            return back();
        }

        try {
            $userData = getUserData();
            $store_id = $userData['store_id'] ?? "";

            $headersetting = Headersetting::where('store_id', $store_id)->first();
            if ($headersetting) {
                $headersetting->order_sms = $order_sms;
                $headersetting->update();

                \Illuminate\Support\Facades\Session::flash("success", "SMS Text set successfully!");

                return back();
            }

            \Illuminate\Support\Facades\Session::flash("error", "Store Info missing!");

            return back();
        } catch (\Exception $e) {
            return view('error');
        }

    }

    private function storeImage($file, $path)
    {
        $imgName = Carbon::now()->timestamp . '.' . $file->extension();
        $file->storeAs($path, $imgName);
        return $imgName;
    }

    public function updatePaymentMethodText(Request $request)
    {
        $column = $request->column ?? "";
        $message = $request->message ?? "";

        if (empty($column)) {
            \Illuminate\Support\Facades\Session::flash("error", "Data missing");
            return redirect()->back();
        }
        if (empty($message)) {
            \Illuminate\Support\Facades\Session::flash("error", "Payment method text is required!");
            return redirect()->back();
        }

        $userData = getUserData();
        $store_id = $userData['store_id'] ?? "";

        $headerSetting = Headersetting::where("store_id", $store_id)->first();

        if (!is_null($headerSetting)) {
            $headerSetting->$column = $message;
            $headerSetting->save();

            \Illuminate\Support\Facades\Session::flash("success", "Payment method text set successfully!!");
            return redirect()->back();
        } else {
            \Illuminate\Support\Facades\Session::flash("error", "Setting Data Not Found!!");
            return redirect()->back();
        }

    }


    public function updateDefaultShippingArea(Request $request)
    {
        $shipping_area = $request->id ?? "";

        if (empty($shipping_area)) {
            return sendError("Shipping area not found!!");
        }

        $userData = getUserData();
        $store_id = $userData['store_id'] ?? "";

        if (isset($store_id)) {
            $headerSetting = Headersetting::where("store_id", $store_id)->first();
            $headerSetting->selected_shipping_area = $shipping_area;
            $headerSetting->save();

            return sendResponse("Shipping area set successfully!!");
        } else {
            return sendError("Store not found!!");
        }

    }


    public function updateSettingData(Request $request)
    {
        $column = $request->column ?? "";

        if (empty($column)) {
            return sendError("Request data not found!!");
        }

        $userData = getUserData();
        $store_id = $userData['store_id'] ?? "";

        if (isset($store_id)) {
            $headerSetting = Headersetting::where("store_id", $store_id)->first();
            $headerSetting->$column = $headerSetting->$column == 1 ? 0 : 1;
            $headerSetting->save();

            return sendResponse("Setting update successfully!!");
        } else {
            return sendError("Store not found!!");
        }

    }


}
