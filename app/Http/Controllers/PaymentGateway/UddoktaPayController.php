<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\AddonsOrder;
use App\Models\Headersetting;
use App\Models\MarchantPaymentGetway;
use App\Models\MerchantAccountJournal;
use App\Models\Order;
use App\Models\OrderTransactionHistory;
use App\Models\Paymentgateway;
use App\Models\Paymenttoken;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;


class UddoktaPayController extends Controller
{

    private $currency_code = "BDT";

    private $apiKey;
    private $apiBaseURL;
    private $successURL = "/payment/success";
    private $failedURL = "/payment/failed";

    private $uddoktapaySuccessURL;
    private $uddoktapayIpnURL;
    private $uddoktapayCancelURL;

    public function __construct()
    {
        // Live
//        if (env('UDDOKTA_PAY_SANDBOX')) {
//        $apiBaseURL = 'https://sandbox.uddoktapay.com';
//        } else {
//            $apiBaseURL = '';
//        }

//        $this->apiBaseURL = $this->normalizeBaseURL($apiBaseURL);

        $this->uddoktapaySuccessURL = route('uddoktapay.successTransaction');
        $this->uddoktapayCancelURL = route('uddoktapay.cancelTransaction');
        $this->uddoktapayIpnURL = route('uddoktapay.ipnTransaction');
    }


    /**
     *
     * Create amarpay payment
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function createPayment(Request $request)
    {
        $returnURL = $this->getStoreURL($request);

        try {
            if (empty($request->order_id) || is_null($request->order_id)) {
                $query = "?error_msg=Order ID is required!";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            $order = Order::where("id", $request->order_id)->first();
            if (!isset($order)) {
                $query = "?error_msg=Invalid order info.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            $store_id = $order["store_id"];
            $order_id = $order["id"];
            $currency_code = $this->currency_code ?? "USD";

            // Get order pay amount
            $amount = $this->advancedPayment($order);

            if ($amount <= 0) {
                $query = "?error_msg=Amount cannot be zero.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            Session::forget('store_id');
            Session::put('store_id', $store_id);
            Session::forget('order_id');
            Session::put('order_id', $order_id);

            // Set paypal configuration
            $setConfigStatus = $this->setConfig($store_id);
            if (!$setConfigStatus) {
                $query = "?error_msg=Something went wrong!.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            if (isset($order->email) && $order->email != "") {
                $userEmail = $order->email;
            } else {
                $userEmail = generateCustomEmail($order->name) ?? "info@ebitans.com";
            }

            $requestData = [
                'full_name' => $order->name ?? "Unknown",
                'email' => $userEmail,
                'amount' => $amount,
                'metadata' => [
                    "store_id" => $store_id,
                    "order_id" => $order_id,
                    "currency" => $currency_code
                ],
                'redirect_url' => $this->uddoktapaySuccessURL . "?store_id=" . $store_id,
                'return_type' => 'GET',
                'cancel_url' => $this->uddoktapayCancelURL,
                'webhook_url' => $this->uddoktapayIpnURL,
            ];

//            $apiType = 'checkout'; // Basic checkout API (IPN notification only).
//            $apiType = 'checkout-v2/global'; // Global advanced checkout API (Success Page notification only).
//            $apiType = 'checkout/global'; // Global basic checkout API (IPN notification only).

            $apiType = 'checkout-v2'; // Advanced checkout API (default, Success Page notification only).
            $apiUrl = $this->buildURL($apiType);

            $response = $this->sendRequest('POST', $apiUrl, $requestData);

            if (isset($response['payment_url']) && !empty($response['payment_url'])) {
                return redirect()->away($response['payment_url']);
            } else {
                $query = "?error_msg=Something wrong.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }
        } catch (Exception $e) {
            $query = "?error_msg=Something wrong.";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        }

    }

    /**
     * Set paypal configuration
     *
     * @param $store_id
     * @return bool
     */
    public function setConfig($store_id)
    {
        try {
            $uddoktapay = Paymentgateway::where('payment_company', "uddoktapay")->where('store_id', $store_id)->first();

            if (isset($uddoktapay) && !empty($uddoktapay->app_key) && !empty($uddoktapay->client_id)) {
                Session::forget('uddoktapay.app_key');
                Session::put('uddoktapay.app_key', $uddoktapay->app_key);
                Session::forget('uddoktapay.base_url');
                Session::put('uddoktapay.base_url', $uddoktapay->client_id);
                $this->apiKey = $uddoktapay->app_key;
                $this->apiBaseURL = $this->normalizeBaseURL($uddoktapay->client_id);

                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * Store Stripe credentials
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function uddoktapayCredentials(Request $request)
    {
        $rules = array(
            'app_key' => 'required|string',
            'client_id' => 'required|string',
        );

        // Input vaidation message
        $errorMessage = array(
            "app_key.required" => "API key is required.",
            "app_key.string" => "API key must be a string.",
            "client_id.required" => "Base URL is required.",
            "client_id.string" => "Base URL must be a string.",
        );

        $validator = Validator::make($request->all(), $rules, $errorMessage);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $userData = getUserData();
            $store_id = $userData["store_id"];
            $user_id = $userData["user_id"];

            $uddoktapay = Paymentgateway::where('payment_company', "uddoktapay")->where('store_id', $store_id)->first();

            if (!isset($uddoktapay)) {
                $uddoktapay = new Paymentgateway();
                $uddoktapay->payment_company = "uddoktapay";
                $uddoktapay->store_id = $store_id;
            }

            $uddoktapay->app_key = $request->app_key;
            $uddoktapay->client_id = $request->client_id;
            $uddoktapay->user_id = $user_id;
            $uddoktapay->status = isset($request->status) && $request->status == "on" ? "Accepted" : "Pending";
            $uddoktapay->save();

            Session::flash("success", "Credentials save successfully.");
            return back();
        }

    }

    /**
     *
     * Get store url
     *
     * @param $store_id
     * @return mixed|string|null
     */
    public function getStoreURL($request = null, $store_id = null)
    {
        $store_id = $request->store_id ?? $store_id ?? Session::get('store_id');

        $store = Store::where("id", $store_id)->first();

        if (isset($store)) {
            $store_url = $store->url;
            Session::forget('store_url');
            Session::put('store_url', $store_url);

            return (request()->secure() ? 'https' : 'http') . '://' . $store_url;
        }

        if ($request->headers->get('referer')) {
            $referrerUrl = $request->headers->get('referer');
            $referrer = parse_url($referrerUrl, PHP_URL_HOST);
        } else {
            $referrer = Session::get('referer_url') ?? "";
        }

        if (!empty($referrer)) {
            Session::forget('referer_url');
            Session::put('referer_url', $referrer);

            return (request()->secure() ? 'https' : 'http') . '://' . $referrer;
        }

        return null;

    }

    /**
     *
     * Calculate order pay amount
     *
     * @param $order
     * @return float
     */
    public function advancedPayment($order)
    {
        $amount = $order->due;
        if (ModulusStatus($order->store_id, 106)) {
            $paymentTy = Transaction::where('order_id', $order->id)->first();

            if (isset($paymentTy->mode) && $paymentTy->mode == 'ap') {
                $advancePayment = Headersetting::convertCurrency($order->store_id)->first();
                if ($advancePayment->payment_type == 0) {
                    $amount = $advancePayment->prepayment;
                } elseif ($advancePayment->payment_type == 1 && !empty($advancePayment->prepayment) && $advancePayment->prepayment != 0) {
                    $amount = ceil($amount * $advancePayment->prepayment / 100);
                } elseif ($advancePayment->payment_type == 2) {
                    $amount = $order->shipping;
                }
            }
        }
        return (float)$amount;
    }

    /**
     *
     * Amar pay success transaction callback
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function successTransaction(Request $request)
    {
        $allRequest = $request->all();
        $invoice_id = $allRequest['invoice_id'] ?? "";
        $store_id = $allRequest['store_id'] ?? "";

        $returnURL = $this->getStoreURL(null, $store_id);
        try {
            if (isset($invoice_id) && !empty($invoice_id)) {
                // Set paypal configuration
                $setConfigStatus = $this->setConfig($store_id);
                if (!$setConfigStatus) {
                    $query = "?error_msg=Something went wrong!.";
                    $url = $returnURL . $this->failedURL . $query;
                    return redirect()->away($url);
                }

                $response = $this->verifyPayment($invoice_id);

                $transactionID = $response['transaction_id'] ?? "";
                $amountPaid = $response['amount'] ?? "";
                $order_id = $response['metadata']['order_id'] ?? "";
                $store_id = $response['metadata']['store_id'] ?? "";

                if (isset($response['status']) && $response['status'] == "COMPLETED") {
                    $data = [
                        "transactionId" => $transactionID,
                        "amountPaid" => $amountPaid,
                        "order_id" => $order_id,
                        "store_id" => $store_id,
                    ];

                    if ($this->databaseUpdate($data)) {
                        $query = "?msg=Payment success.&transaction_id=" . $transactionID . "&total=" . $amountPaid;
                        $url = $returnURL . $this->successURL . $query;
                        return redirect()->away($url);
                    }

                    $query = "?msg=Payment success. But order info not updated!&transaction_id=" . $transactionID . "&total=" . $amountPaid;
                    $url = $returnURL . $this->successURL . $query;
                    return redirect()->away($url);
                }

            }

            $query = "?error_msg=Transaction failed!";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        } catch (Exception $e) {
            $query = "?error_msg=Something wrong.";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        }
    }


    /**
     * @param $data
     * @return bool|RedirectResponse
     */
    public function databaseUpdate($data)
    {
        try {
            $transactionId = $data['transactionId'];
            $amountPaid = $data['amountPaid'];

            $order_id = $data['order_id'];
            $store_id = $data['store_id'] ?? "";

            $order = Order::where("id", $order_id);

            if (isset($order) && isset($store_id)) {
                $order = $order->where("store_id", $store_id);
            }

            $order = $order->first();

            if (isset($order)) {
                $due = (float)$order["due"] - (float)$amountPaid;
                $due = $due < 0 ? 0 : $due;

                $order->transaction_id = $transactionId;
                $order->paid = $amountPaid;
                $order->due = $due;
                $order->status = $order->total == $amountPaid ? 'Payment Success' : 'Partial Paid';
                $order->update();

                $transaction = Transaction::where('order_id', $order_id)->first();
                if (isset($transaction)) {
                    $transaction->transaction_id = $transactionId;
                    $transaction->status = 'Paid';
                    $transaction->update();
                }

                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * Payment cancel
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancelTransaction()
    {
        $returnURL = $this->getStoreURL(null, Session::get('store_id'));

        $query = "?error_msg=You have canceled the transaction.";
        $url = $returnURL . $this->failedURL . $query;
        return redirect()->away($url);
    }


    public function ipnTransaction(Request $request)
    {
        $response = $this->executePayment();
        // Process the IPN response
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $transactionID = $response['transaction_id'] ?? "";
            $amountPaid = $response['amount'] ?? "";
            $order_id = $response['metadata']['order_id'] ?? "";
            $store_id = $response['metadata']['store_id'] ?? "";

            $data = [
                "transactionId" => $transactionID,
                "amountPaid" => $amountPaid,
                "order_id" => $order_id,
                "store_id" => $store_id,
            ];

            $this->databaseUpdate($data);

            Log::error('Uddoktapay IPN request successfully processed.');
        } else {
            $log = "IPN request error: " . $response['message'];
            Log::error($log);
        }

    }


    private function normalizeBaseURL($apiBaseURL)
    {
        $baseURL = rtrim($apiBaseURL, '/');
        $apiSegmentPosition = strpos($baseURL, '/api');

        if ($apiSegmentPosition !== false) {
            $baseURL = substr($baseURL, 0, $apiSegmentPosition + 4); // Include '/api'
        }

        return $baseURL;
    }

    private function buildURL($endpoint)
    {
        $apiBaseURL = $this->normalizeBaseURL($this->apiBaseURL);

        $endpoint = ltrim($endpoint, '/');
        return $apiBaseURL . '/' . $endpoint;
    }


    public function verifyPayment($invoiceId)
    {
        $verifyUrl = $this->buildURL('verify-payment');
        $requestData = ['invoice_id' => $invoiceId];
        return $this->sendRequest('POST', $verifyUrl, $requestData);
    }

    public function executePayment()
    {
        $headerApi = $_SERVER['HTTP_RT_UDDOKTAPAY_API_KEY'] ?? null;

        if ($headerApi === null) {
            Log::error('Uddoktapay Invalid API Key');
            return false;
        }

        $apiKey = $this->apiKey ?? Session::get('uddoktapay.app_key');

        if ($headerApi !== $apiKey) {
            Log::error('Uddoktapay Unauthorized Action');
            return false;
        }

        $rawInput = trim(file_get_contents('php://input'));

        if (empty($rawInput)) {
            Log::error('Invalid response from UddoktaPay API.');
            return false;
        }

        $data = json_decode($rawInput, true);
        $invoiceId = $data['invoice_id'];

        return $this->verifyPayment($invoiceId);
    }

    private function sendRequest($method, $url, $data)
    {
        $apiKey = $this->apiKey ?? Session::get('uddoktapay.app_key');

        $headers = [
            'RT-UDDOKTAPAY-API-KEY: ' . $apiKey,
            'accept: application/json',
            'content-type: application/json'
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return false;
        }

        return json_decode($response, true);
    }


}
