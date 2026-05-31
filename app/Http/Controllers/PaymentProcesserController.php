<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PaymentGateway\AcceptPlanController;
use App\Logic\Providers\cPanelApi;
use App\Models\Activity;
use App\Models\Addon;
use App\Models\AddonsApi;
use App\Models\AddonsExpired;
use App\Models\AddonsOrder;
use App\Models\AdminUserAnalytics;
use App\Models\BusinessCategory;
use App\Models\Category;
use App\Models\ClientActivitieComments;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Designlist;
use App\Models\Digitalcontent;
use App\Models\Digitalplan;
use App\Models\Domain;
use App\Models\Iconpack;
use App\Models\Invoicepurchase;
use App\Models\Message;
use App\Models\Mobileapp;
use App\Models\Modulus;
use App\Models\ModulusPayment;
use App\Models\Notification;
use App\Models\Paymentgateway;
use App\Models\PaymentProcessingCharge;
use App\Models\Plan;
use App\Models\Planorder;
use App\Models\Posplan;
use App\Models\Product;
use App\Models\QuickLogin;
use App\Models\Referral;
use App\Models\RegistrationFee;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Superrole;
use App\Models\Supersetting;
use App\Models\Superstaff;
use App\Models\SuperstaffSalesCommission;
use App\Models\SuperstaffSalesCommissionBalance;
use App\Models\Template;
use App\Models\Temposition;
use App\Models\Themecustomize;
use App\Models\Tricket;
use App\Models\User;
use App\Models\Websitesetup;
use App\Models\ZoneRecord;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentProcesserController extends Controller
{
    public function paymentList($type, $id)
    {
        if (!isset($type) || empty($type)) {
            Session::flash("error", "Invalid Request");
            return redirect()->back();
        }

        if (!isset($id) || empty($id)) {
            Session::flash("error", "Invalid Request");
            return redirect()->back();
        }

        // Define a mapping of plan types to models
        $modelMapping = [
            'plan' => \App\Models\Plan::class,
            'posplan' => \App\Models\PosPlan::class,
            'digitalplan' => \App\Models\DigitalPlan::class,
        ];

        // Ensure the type is valid
        if (!isset($modelMapping[$type])) {
            Session::flash("error", "Invalid plan type");
            return redirect()->back();
        }

        // Get the appropriate model dynamically
        $planModel = $modelMapping[$type];

        // Fetch payment processing charges
        $lists = PaymentProcessingCharge::where("plan_type", $type)
            ->where("plan_id", $id)
            ->get();

        // Fetch plan details manually
        $planDetails = $planModel::find($id);

        // Attach plan details to each result
        $lists->transform(function ($item) use ($planDetails) {
            $item->plan_name = $planDetails->name ?? ""; // Add plan details to each item
            return $item;
        });

        return view("admin.super.payment-processing.index", [
            "lists" => $lists,
            "id" => $id,
            "type" => $type,
        ]);
    }


    public function paymentCreate($type, $id)
    {
        if (!isset($type) || empty($type)) {
            Session::flash("error", "Invalid Request");
            return redirect()->back();
        }

        if (!isset($id) || empty($id)) {
            Session::flash("error", "Invalid Request");
            return redirect()->back();
        }

        $prefix = "";

        if ($type == "plan") {
            $query = Plan::query();
            $prefix = "Plan";
        } elseif ($type == "posplan") {
            $query = PosPlan::query();
            $prefix = "Pos Plan";
        } elseif ($type == "digitalplan") {
            $query = DigitalPlan::query();
            $prefix = "Digital Plan";
        }

        $plan = $query->where("id", $id)->first();
        $title = $prefix . " >> " . $plan->name ?? "" . " Package";

        return view("admin.super.payment-processing.create", [
            "title" => $title,
            "id" => $id,
            "type" => $type,
        ]);
    }


    public function paymentStore(Request $request)
    {
        if (!isset($request->type) || empty($request->type)) {
            Session::flash("error", "Invalid Request");
            return redirect()->back();
        } elseif (!isset($request->id) || empty($request->id)) {
            Session::flash("error", "Invalid Request");
            return redirect()->back();
        } elseif (!isset($request->payment_gateway) || empty($request->payment_gateway)) {
            Session::flash("error", "Please select payment gateway");
            return redirect()->back();
        }

        $paymentProcessCharge = PaymentProcessingCharge::where("plan_id", $request->id)
            ->where("plan_type", $request->type)
            ->where("payment_gateway", $request->payment_gateway)->first();

        if (!isset($paymentProcessCharge)) {
            $paymentProcessCharge = new PaymentProcessingCharge();
            $paymentProcessCharge->plan_id = $request->id;
            $paymentProcessCharge->plan_type = $request->type;
            $paymentProcessCharge->payment_gateway = $request->payment_gateway;
        }

        $paymentProcessCharge->payment_processing_charge = $request->payment_processing_charge;
        $paymentProcessCharge->save();

        Session::flash("success", "Payment processing charge created successfully");
        return redirect()->route("plan.payment.list", ['type' => $request->type, 'id' => $request->id]);
    }

    public function paymentEdit($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash("error", "Invalid Request");
            return redirect()->back();
        }

        $paymentProcessor = PaymentProcessingCharge::where("id", $id)->first();

        $prefix = "";

        if ($paymentProcessor->plan_type == "plan") {
            $query = Plan::query();
            $prefix = "Plan";
        } elseif ($paymentProcessor->plan_type == "posplan") {
            $query = PosPlan::query();
            $prefix = "Pos Plan";
        } elseif ($paymentProcessor->plan_type == "digitalplan") {
            $query = DigitalPlan::query();
            $prefix = "Digital Plan";
        }

        $plan = $query->where("id", $paymentProcessor->plan_id)->first();
        $title = $prefix . " >> " . $plan->name ?? "" . " Package";

        return view("admin.super.payment-processing.edit", [
            "title" => $title,
            "paymentProcessor" => $paymentProcessor,
        ]);

    }

    public function paymentUpdate(Request $request)
    {
        if (!isset($request->id) || empty($request->id)) {
            Session::flash("error", "Invalid Request");
            return redirect()->back();
        } elseif (!isset($request->payment_gateway) || empty($request->payment_gateway)) {
            Session::flash("error", "Please select payment gateway");
            return redirect()->back();
        }

        $paymentProcessCharge = PaymentProcessingCharge::where("id", $request->id)->first();

        if (!isset($paymentProcessCharge)) {
            Session::flash("error", "Record not found");
            return redirect()->back();
        }

        if ($paymentProcessCharge->payment_gateway != $request->payment_gateway) {
            $paymentProcessChargeEx = PaymentProcessingCharge::where("plan_id", $paymentProcessCharge->plan_id)
                ->where("plan_type", $paymentProcessCharge->plan_type)
                ->where("payment_gateway", $request->payment_gateway)->first();

            if (isset($paymentProcessChargeEx)) {
                Session::flash("error", "Record already exists");
                return redirect()->back();
            }
        }

        $paymentProcessCharge->payment_gateway = $request->payment_gateway;
        $paymentProcessCharge->payment_processing_charge = $request->payment_processing_charge;
        $paymentProcessCharge->update();

        Session::flash("success", "Payment processing charge updated successfully");
        return redirect()->route("plan.payment.list", ['type' => $paymentProcessCharge->plan_type, 'id' => $paymentProcessCharge->plan_id]);
    }


    public function paymentDelete($id)
    {
        if (!isset($id) || empty($id)) {
            Session::flash("error", "Invalid Request");
            return redirect()->back();
        }

        $paymentProcessCharge = PaymentProcessingCharge::where("id", $id)->first();

        if (!isset($paymentProcessCharge)) {
            Session::flash("error", "Record not found");
            return redirect()->back();
        }

        $type = $paymentProcessCharge->plan_type;
        $id = $paymentProcessCharge->plan_id;

        $paymentProcessCharge->delete();

        Session::flash("success", "Payment processing charge Deleted successfully");
        return redirect()->route("plan.payment.list", ['type' => $type, 'id' => $id]);
    }


}
