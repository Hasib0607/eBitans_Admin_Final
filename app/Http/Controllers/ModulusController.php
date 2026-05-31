<?php

namespace App\Http\Controllers;

use App\Models\BookingCustomerFiled;
use App\Models\BookingTag;
use App\Models\BuyModulus;
use App\Models\Headersetting;
use App\Models\Modulus;
use App\Models\ModulusPayment;
use App\Models\Paymentgateway;
use App\Models\Product;
use App\Models\QuickLogin;
use App\Models\Veriant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ModulusController extends Controller
{

    public function index()
    {
        return view('admin.addon.modulus.index', $this->getModulus());
    }

    public function marketingModulusList()
    {
        return view('admin.addon.marketing.modulus', $this->getModulus(1));
    }

    public function modulusConfig($id)
    {
        session()->forget('configError');
        /*extract user_id, user_type, store_id, customer_id*/
        extract(getUserData());

        $data['ok'] = $id;
        $data['store_id'] = $store_id;
        if (!ModulusStatus($store_id, $id)) {
            return redirect()->back()->with('configError', 'Configuration page access denied!. Plesae active your module first.');
        }

        $modulus = Modulus::where("id", $id)->first();
        $data['modulus_type'] = $modulus->modulus_type;

        if ($id == 108) {
            $data['booking_field'] = BookingCustomerFiled::where('modulus_id', 108)->where(
                'store_id',
                $store_id
            )->where('uId', $user_id)->get();
        } elseif ($id != 106) {
            $data['credential'] = QuickLogin::where('modulus_id', $id)->where('store_id', $store_id)->first();
        }

        if ($id == 112) {
            $data['stripe'] = Paymentgateway::where('payment_company', "stripe")->where('store_id', $store_id)->first();
        }
        if ($id == 113) {
            $data['paypal'] = Paymentgateway::where('payment_company', "paypal")->where('store_id', $store_id)->first();
        }
        if ($id == 126) {
            $data['uddoktapay'] = Paymentgateway::where('payment_company', "uddoktapay")->where('store_id', $store_id)->first();
        }

        $data['ap'] = Headersetting::convertCurrency($store_id)->first(['id', 'prepayment', 'payment_type', 'order_sms']);

        $data['booking'] = BookingTag::all();
        $bookingFieldData = BookingCustomerFiled::where('modulus_id', 108)
            ->where('store_id', $store_id)
            ->where('uId', $user_id)
            ->first();
        $data['is_single'] = $bookingFieldData->is_single ?? 1;
        $data['urls'] = 'addons';

        return view('admin.addon.share.config', $data);
    }


    public function getModulus($modulus_type = 0)
    {
        /*extract user_id, user_type, store_id, customer_id*/
        extract(getUserData());

        /*getting modulus*/
        $data['modulus'] = Modulus::select(
            'moduluses.id',
            'moduluses.name',
            'moduluses.title',
            'moduluses.image',
            'moduluses.config_status',
            'moduluses.price',
            'moduluses.price_usd'
        )
            ->selectRaw("(CASE WHEN moduluses.price > 0 THEN 1 ELSE 0 END) AS priceStatus")
            ->selectRaw("moduluses.status AS modulusesStatus")
            ->selectRaw("COALESCE((SELECT MAX(modulus_payments.status) FROM modulus_payments WHERE modulus_payments.modulus_id = moduluses.id
              AND modulus_payments.store_id =  '$store_id'), 0) AS paymentStatus")
            ->where('moduluses.status', 1)
            ->where('moduluses.modulus_type', $modulus_type)
            ->orderBy('moduluses.position', "ASC")
            ->get();

        $data['modulus_type'] = $modulus_type;

        /*having header setting and get bkash column*/
        $data['advanceHeaderSetting'] = Headersetting::convertCurrency($store_id)->first('bkash');

        return $data;
    }

    public function prePaymentConfig(Request $request)
    {
        $rules = array(
            'prepayment' => 'numeric|nullable',
            'payment_type' => 'numeric|nullable',
            'payment_method' => 'required|string',
        );

        // Input vaidation message
        $errorMessage = array(
            "prepayment.numeric" => "Pre-Payment Amount must be numeric.",
            "payment_type.numeric" => "Payment Type  must be numeric.",
            "payment_method.required" => "Payment Method  is required.",
            "payment_method.string" => "Payment Method  must be a string.",
        );

        $validator = Validator::make($request->all(), $rules, $errorMessage);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $data = Headersetting::with('store')->find($request->id);
            $data->currency_id = $data->store->currency;
            $data->prepayment = $request->prepayment;
            $data->payment_type = (int) $request->payment_type;
            $data->payment_method = isset($request->payment_method) ? $request->payment_method : "cod";
            $data->update();
            return back();
        }
    }

    /**
     * Change modulus status
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
public function changeStatus(Request $request)
{
    $storeId   = (int) $request->id;
    $modulusId = (int) $request->modulus_id;

    $store = \App\Models\Store::find($storeId);

    // =========================================================
    // ✅ TRIAL MODE RESTRICTION
    // Trial plan ids = 6, 9
    // Only allow modules: 107, 111, 114
    // =========================================================
    $isTrialPlan = ($store && in_array((int) $store->plan_id, [6, 9]));
    $ALLOW_TRIAL_MODULUS_IDS = [107, 111, 114];

    if ($isTrialPlan && !in_array($modulusId, $ALLOW_TRIAL_MODULUS_IDS)) {
        return response()->json([
            "status"  => false,
            "message" => "Trial period এ এই module ব্যবহার করা যাবে না."
        ]);
    }

    // =========================================================
    // SPECIAL CASE: Variant module (ID = 114)
    // Cannot disable if variants already used
    // =========================================================
    if ($modulusId == 114) {

        $productIds = Product::where("store_id", $storeId)
            ->pluck('id')
            ->toArray();

        $veriants = Veriant::whereIn("pid", $productIds)->get();

        $moduleStatus = BuyModulus::where('modulus_id', $modulusId)
            ->where('store_id', $storeId)
            ->first();

        $statusChange = false;

        if (is_null($moduleStatus)) {
            $statusChange = true;
        } elseif (isset($moduleStatus->status) && $moduleStatus->status == 0) {
            $statusChange = true;
        }

        if (count($veriants) == 0 || $statusChange) {

            $buyModulus = BuyModulus::firstOrNew([
                'modulus_id' => $modulusId,
                'store_id'   => $storeId
            ]);

            $buyModulus->status = !$buyModulus->status;
            $buyModulus->save();

            return response()->json([
                "status"  => true,
                "message" => "Status changed successfully",
                "data"    => $buyModulus
            ]);
        }

        return response()->json([
            "status"  => false,
            "message" => "You can not on/off Variant Modulus. If you set variant any product"
        ]);
    }

    // =========================================================
    // DEFAULT: Toggle other allowed modules
    // =========================================================
    $buyModulus = BuyModulus::firstOrNew([
        'modulus_id' => $modulusId,
        'store_id'   => $storeId
    ]);

    $buyModulus->status = !$buyModulus->status;
    $buyModulus->save();

    return response()->json([
        "status"  => true,
        "message" => "Status changed successfully",
        "data"    => $buyModulus
    ]);
}

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function buy(Request $request)
    {
        $module = Modulus::where("id", $request->modulus_id)->first();
        if (!isset($module)) {
            return redirect()->back()->with("error", "Invalid modulus ID!");
        }
        if (isset($request->paymentMethod) && $request->paymentMethod != "hand_cash" && (empty($request->number) || empty($request->transaction_id))) {
            return redirect()->back()->with("error", "Please input phone number and transaction ID!");
        }

        $totalPrice = $request->price;

        if ($request->modulus_id == 121) {
            $price = (float) $module->price;
            $totalProduct = (int) $request->total_product ?? 1;

            $totalPrice = $price * $totalProduct;
        }

        $payment = new ModulusPayment();
        $payment->modulus_id = $request->modulus_id;
        $payment->store_id = $request->store_id;
        $payment->payment_type = $request->paymentMethod;
        $payment->price = $totalPrice;
        $payment->number = $request->number;
        $payment->transaction_id = $request->transaction_id;
        $payment->total_product = $request->total_product ?? NULL;
        $payment->save();

        return redirect()->back();
    }


    public function modulusPayment(Request $request)
    {
        $modulus_id = $request->modulus_id;
        $amount = $request->amount;
        $payment_method = $request->payment_method;

        if (empty($modulus_id) || is_null($modulus_id)) {
            Session::flash('error', "Invalid request.");
            return back();
        }
        if (empty($payment_method) || is_null($payment_method)) {
            Session::flash('error', "Invalid request.");
            return back();
        }
        if (!isset($amount) || $amount <= 0) {
            Session::flash('error', "Invalid request.");
            return back();
        }

        $modulus = Modulus::where('id', $modulus_id)->first();
        if (!isset($modulus)) {
            Session::flash('error', "Invalid request.");
            return back();
        }

        if ($modulus->price != $amount) {
            Session::flash('error', "Invalid request.");
            return back();
        }

        $amount = $modulus->price ?? 0;
        $amount_usd = $modulus->price_usd ?? 0;
        $modulus_id = $modulus->id;

        if ($modulus_id == 121) {
            $totalProduct = (int) $request->total_product ?? 1;

            $amount = $amount * $totalProduct;
            $amount_usd = $amount_usd * $totalProduct;
        }


        if (!empty($modulus_id)) {
            if (isset($payment_method) && $payment_method == "bkash") {

                $url = route("bkash.modulus.payment", ['amount' => $amount, "modulus_id" => $modulus_id, "total_product" => $request->total_product ?? 0]);
                return redirect()->away($url);
            } elseif (isset($payment_method) && $payment_method == "nagad") {

                $url = route("nagad.modulus.payment", ['amount' => $amount, "modulus_id" => $modulus_id, "total_product" => $request->total_product ?? 0]);
                return redirect()->away($url);
            } elseif (isset($payment_method) && $payment_method == "amarpay") {

                $url = route("amarpay.modulus.payment", ['amount' => $amount, "modulus_id" => $modulus_id, "total_product" => $request->total_product ?? 0]);
                return redirect()->away($url);
            } elseif (isset($payment_method) && $payment_method == "paypal") {
                if ($amount_usd <= 0) {
                    Session::flash('error', "USD price not set for this modulus.");
                    return back();
                }

                $url = route("paypal.modulus.payment", ['amount' => $amount_usd, "modulus_id" => $modulus_id, "total_product" => $request->total_product ?? 0]);
                return redirect()->away($url);
            }
        }

        return back()->with("error", "Something went wrong. Try again later.");

    }


}
