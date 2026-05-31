<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logic\Providers\cPanelApi;
use Session;
use Toastr;
use Auth;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Webmail;

class WebmailController extends Controller
{
    public function webmail()
    {
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }
        $store = Store::find($store_id);

        if ($store->plan_id == 6) {
            return back();
        }

        $domain = $store->url ?? Auth::user()->domain;
        if ($store->webmail_status == 'deactive') {
            Session::flash('message', 'To access Email you need to add domain');
            return back();
        }
        $api = new cPanelApi("ebitans.com", "ebitans", env("HOST_POINT"));

        $data1 = json_decode($api->listEmail("ebitans"))->data ?? '';
        $data = Webmail::where('store_id', $store_id)->get();
        // $weburl="https://webmail.ebitans.com";
        $weburl = "https://webmail." . $store->url;

        $notAllowedDomain = "ebitans.com";
        return view('admin.webmail.emaillist', compact('data', 'weburl', 'domain', 'notAllowedDomain'));
    }

    public function webmaildelete($email)
    {
        $api = new cPanelApi("ebitans.com", "ebitans", env("HOST_POINT"));
        $data = $api->deleteEmail($email);
        $data = json_decode($data);
        if (isset($data->errors)) {
            Session::flash('message', $data->errors[0]);
        } else {
            $ee = Webmail::where('name', $email)->first();
            $ee->delete();
            Session::flash('message', "Successfully Email Deleted");
        }

        return back();
    }

    public function createwebemail(Request $request)
    {
        if (Auth::user()->domain == null || Auth::user()->domain == "") {
            Session::flash('error', "You dont Have any domain, For Use this feature please add domain");
            return back();
        }
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }
        $store = Store::find($store_id);

        $domain = $store->url ?? Auth::user()->domain;
        $notAllowedDomain = "ebitans.com";
        if (isset($notAllowedDomain) && strpos($domain, $notAllowedDomain) !== false) {
            Session::flash('error', "Your are not allowed to create mail under $notAllowedDomain");
            return back();
        }

        $api = new cPanelApi("ebitans.com", "ebitans", env("HOST_POINT"));
        $email = $request->email . '@' . $domain;
        $data = $api->createEmail($email, $request->password, "unlimited");
        $data = json_decode($data);
        // dd($data);

        if (isset($data->warnings)) {
            Session::flash('warning', $data->warnings[0]);
        }

        if (isset($data->errors)) {
            Session::flash('error', $data->errors[0]);
        } else {
            $webmail = new Webmail();
            $webmail->name = $email;
            $webmail->uid = $user;
            $webmail->customer_id = $customer_id;
            $webmail->store_id = $store_id;
            $webmail->save();
            Session::flash('message', "Successfully Email Created");
        }

        return back();
    }

    public function changewebmailpassword(Request $request)
    {
        $api = new cPanelApi("ebitans.com", "ebitans", env("HOST_POINT"));
        $data = $api->setPasswordEmail($request->email, $request->password);
        //  dd($data);
        $data = json_decode($data);
        if (isset($data->warnings)) {
            Session::flash('warning', $data->warnings[0]);
        }

        if (isset($data->errors)) {
            Session::flash('error', $data->errors[0]);
        }
        Session::flash('message', "Successfully Changed Password");
        return back();
    }

    public function acc()
    {
        $api = new cPanelApi("ebitans.com", "ebitans", env("HOST_POINT"));
        echo $api->getUsages();
    }
}
