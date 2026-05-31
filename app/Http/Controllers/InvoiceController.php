<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Banner;
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
use App\Models\Designlist;
use App\Models\Toptool;
use App\Models\Testimonial;
use App\Models\Invoicepurchase;

class InvoiceController extends Controller
{
    public function invoice1()
    {
        return view('invoice.invoice1');
    }

    public function activeinvoice($id)
    {
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
        }
        $design = Designlist::where('id', $id)->first();
        $md = Design::where('store_id', $store_id)->first();
        $md->invoice = $design->value;
        $md->save();
        Session::flash('message', 'Invoice Change Successfully');
        return back();
    }

    public function buyinvoice(Request $request)
    {
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
        }
        $inv = new Invoicepurchase();
        $inv->invoice_id = $request->invoice_id;
        $inv->store_id = $store_id;
        $inv->status = "pending";
        $inv->amount = "20";
        $inv->payment_method = $request->paymentMethod;
        $inv->number = $request->number;
        $inv->transaction_id = $request->transaction_id;
        $inv->save();

        Session::flash('message', 'Invoice Purchase Successfully. Your Purchase Request Send To our team. Our Team Active Your Order within 12 hours.');
        return back();
    }
}
