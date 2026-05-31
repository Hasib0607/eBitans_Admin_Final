<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\AddonsOrder;
use App\Models\Paymentgateway;
use App\Models\Paymenttoken;
use App\Models\User;
use App\Util\BkashCredential;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Log;

class AdminAmarPayController extends Controller
{

    private $base_url;
    private $verify_url;
    private $storeId;
    private $signatureKey;
    private $successUrl;
    private $cancelUrl;
    private $failUrl;

    public function __construct()
    {
        // Live
        if (env('AMARPAY_SANDBOX')) {
            $this->base_url = 'https://sandbox.aamarpay.com/jsonpost.php';
            $this->verify_url = 'https://sandbox.aamarpay.com/api/v1/trxcheck/request.php';
        } else {
            $this->base_url = 'https://secure.aamarpay.com/jsonpost.php';
            $this->verify_url = 'https://secure.aamarpay.com/api/v1/trxcheck/request.php';
        }

        $this->storeId = env('AAMARPAY_STORE_ID', 'aamarpaytest');
        $this->signatureKey = env('AAMARPAY_SIGNATURE_KEY', 'dbb74894e82415a2f7ff0ec3a97e4183');

        $this->successUrl = route('amarpay.admin.successTransaction');
        $this->cancelUrl = route('amarpay.admin.cancelTransaction');
        $this->failUrl = route('amarpay.admin.failedTransaction');

    }

    public function curlWithBody($url, $header, $method, $body_data_json)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body_data_json,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function createPayment(Request $request)
    {
        try {
            if (empty($request->order_id) || is_null($request->order_id)) {
                Session::flash('error', "Order ID is required.");
                return back();
            }

            $order = AddonsOrder::where("id", $request->order_id)->first();
            if (!isset($order)) {
                Session::flash('error', "Invalid order info.");
                return back();
            }
            $user = User::with('store')->where("id", $order->user_id)->first();

            $amount = (float)$order['total'];
            $order_id = $order["id"];

            if (!isset($order_id) || $amount <= 0) {
                Session::flash('error', "Invalid order info.");
                return back();
            }

            $currency_code = "BDT";
            $tran_id = 'EB' . Carbon::now()->timestamp;

            $header = array(
                'Content-Type: application/json'
            );

            if (isset($user->email) && $user->email != "") {
                $userEmail = $user->email;
            } else {
                $userEmail = generateCustomEmail($user->name) ?? "info@ebitans.com";
            }


            $body_data = array(
                "store_id" => $this->storeId,
                "tran_id" => $tran_id,
                "success_url" => $this->successUrl,
                "fail_url" => $this->failUrl,
                "cancel_url" => $this->cancelUrl,
                "amount" => $amount,
                "currency" => $currency_code,
                "signature_key" => $this->signatureKey,
                "desc" => "Customer Package and Addon Payment",
                "cus_name" => $user->name ?? "",
                "cus_email" => $userEmail,
                "cus_add1" => $user->address ?? "",
                "cus_add2" => $user->address ?? "",
                "cus_city" => "Dhaka",
                "cus_state" => "Dhaka",
                "cus_postcode" => "1206",
                "cus_country" => "Bangladesh",
                "cus_phone" => $user->phone ?? "",
                "opt_a" => $order_id,
                "type" => "json"
            );

            $body_data_json = json_encode($body_data);

            $response = $this->curlWithBody($this->base_url, $header, 'POST', $body_data_json);
            $responseObj = json_decode($response);

            if (isset($responseObj->payment_url) && !empty($responseObj->payment_url)) {
                $paymentUrl = $responseObj->payment_url;
                return redirect()->away($paymentUrl);
            } else {
                Session::flash("error", "something went wrong. Plesae try again");
                return redirect()->back();
            }
        } catch (Exception $e) {
            Session::flash("error", "something went wrong. Plesae try again");
            return redirect()->back();
        }
    }

    public function successTransaction(Request $request)
    {
        try {
            $allRequest = $request->all();
            $order_id = $allRequest['opt_a'] ?? "";
            $order = AddonsOrder::find($order_id);

            if (isset($allRequest['pay_status']) && $allRequest['pay_status'] == 'Successful') {
                $request_id = $request->mer_txnid; // merchant invoice

                $url = "$this->verify_url?request_id=$request_id&store_id=$this->storeId&signature_key=$this->signatureKey&type=json";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                $responseObj = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($responseObj);

                $transactionID = $response->pg_txnid ?? "";
                $payment_type = $response->payment_type ?? "";
                $cardnumber = $response->cardnumber ?? "";

                if (isset($response->pay_status) && $response->pay_status == "Successful") {
                    $update = false;

                    if (isset($order)) {
                        $order->payment_method = $payment_type;
                        $order->transaction_id = $transactionID;
                        $order->payment_number = $cardnumber;
                        $order->update();

                        $update = (new AcceptPlanController())->acceptPlanOrder($order_id);
                    }

                    if ($update) {
                        $msg = "Payment Successful";
                    } else {
                        $msg = "Payment Successful. But Order Status Not Updated. Please contact to the support";
                    }

                    return redirect()->route('payment.payments')->with('success', $msg);
                }

            } else {
                $msg = "Transaction failed.";

                return redirect()->route('payment.payments')->with('error', $msg);
            }
        } catch (Exception $e) {
            return redirect()->route('payment.payments')->with('error', "Transaction failed.");
        }
    }

    public function failedTransaction(Request $request)
    {
        return redirect()->route('payment.payments')->with("error", "Transaction failed.");
    }

    public function cancelTransaction()
    {
        return redirect()->route('payment.payments')->with("error", "Transaction cancelled.");
    }


    /**
     *
     * Store Paypal credentials
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function amarpayCredentials(Request $request)
    {
        $rules = array(
            'app_key' => 'nullable|string',
            'app_secret' => 'required|string',
        );

        // Input vaidation message
        $errorMessage = array(
            "client_id.required" => "Client ID is required.",
            "client_id.string" => "Client ID must be a string.",
            "app_key.string" => "App key must be a string.",
            "app_secret.required" => "Client secret is required.",
            "app_secret.string" => "Client secret must be a string.",
        );

        $validator = Validator::make($request->all(), $rules, $errorMessage);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $userData = getUserData();

            $paypal = Paymentgateway::where('payment_company', "paypal")->where('store_id',
                $userData["store_id"])->first();

            if (!isset($paypal)) {
                $paypal = new Paymentgateway();
                $paypal->payment_company = "paypal";
                $paypal->store_id = $userData["store_id"];
            }

            $paypal->client_id = $request->client_id;
            $paypal->app_key = $request->app_key;
            $paypal->app_secret = $request->app_secret;
            $paypal->user_id = $userData["user_id"];
            $paypal->status = isset($request->status) && $request->status == "on" ? "Accepted" : "Pending";
            $paypal->save();

            Session::flash("success", "Credentials save successfully.");
            return back();
        }

    }


}
