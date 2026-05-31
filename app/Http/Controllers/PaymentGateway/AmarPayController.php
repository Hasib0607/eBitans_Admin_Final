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
use App\Models\PaymentProcessingCharge;
use App\Models\Paymenttoken;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;


class AmarPayController extends Controller
{
    private $store_id = null;
    private $currency_code = "BDT";

    private $base_url;
    private $verify_url;
    private $storeId;
    private $signatureKey;
    private $successURL = "/payment/success";
    private $failedURL = "/payment/failed";

    private $apaySuccessURL;
    private $apayFailedURL;
    private $apayCancelURL;

    public function __construct()
    {
        // $this->middleware('auth');
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

        $this->apaySuccessURL = route('amarpay.successTransaction');
        $this->apayCancelURL = route('amarpay.cancelTransaction');
        $this->apayFailedURL = route('amarpay.failedTransaction');
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
            $this->store_id = $store_id;
            $order_id = $order["id"];
            $tran_id = $order["reference_no"]; // This is invoice id
            $currency_code = $this->currency_code ?? "BDT";

            if (!merchantPaymentModulusStatus($store_id, 125, "amarpay")) {
                $query = "?error_msg=Something wrong.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

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

            $header = array(
                'Content-Type: application/json'
            );

            if (isset($order->email) && $order->email != "") {
                $userEmail = $order->email;
            } else {
                $userEmail = generateCustomEmail($order->name) ?? "info@ebitans.com";
            }

            $body_data = array(
                "store_id" => $this->storeId,
                "tran_id" => $tran_id,
                "success_url" => $this->apaySuccessURL,
                "fail_url" => $this->apayFailedURL,
                "cancel_url" => $this->apayCancelURL,
                "amount" => $amount,
                "currency" => $currency_code,
                "signature_key" => $this->signatureKey,
                "desc" => "Customer Order Payment",
                "cus_name" => $order->name ?? "",
                "cus_email" => $userEmail,
                "cus_add1" => $order->address ?? "",
                "cus_add2" => "",
                "cus_city" => "",
                "cus_state" => "",
                "cus_postcode" => "",
                "cus_country" => "Bangladesh",
                "cus_phone" => $order->phone ?? "",
                "opt_a" => $order_id,
                "opt_b" => $order->store_id,
                "opt_c" => $order->uid,
                "type" => "json"
            );

            $body_data_json = json_encode($body_data);

            $response = $this->curlWithBody($this->base_url, $header, 'POST', $body_data_json);
            $responseObj = json_decode($response);

            if (isset($responseObj->payment_url) && !empty($responseObj->payment_url)) {
                $paymentUrl = $responseObj->payment_url;
                return redirect()->away($paymentUrl);
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
        $order_id = $allRequest['opt_a'] ?? "";
        $store_id = $allRequest['opt_b'] ?? "";
        $uid = $allRequest['opt_c'] ?? "";

        $returnURL = $this->getStoreURL(null, $store_id);

        try {
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
                $amountPaid = $response->amount ?? "";

                $bank_trxid = $response->bank_trxid ?? "";
                $approval_code = $response->approval_code ?? "";
                $payment_processor = $response->payment_processor ?? "";
                $date_processed = $response->date_processed ?? ""; // Process date
                $store_amount = $response->store_amount ?? ""; // After remove charge amount
                $processing_ratio = $response->processing_ratio ?? ""; // Charge amount
                $processing_charge = $response->processing_charge ?? "";
                $ip = $response->ip ?? "";
                $currency = $response->currency ?? ""; // Transaction currency

                if (isset($response->pay_status) && $response->pay_status == "Successful") {
                    $data = [
                        "transactionId" => $transactionID,
                        "amountPaid" => $amountPaid,
                        "payment_type" => $payment_type,
                        "cardnumber" => $cardnumber,
                        "bank_trxid" => $bank_trxid,
                        "approval_code" => $approval_code,
                        "payment_processor" => $payment_processor,
                        "date_processed" => $date_processed,
                        "store_amount" => $store_amount,
                        "processing_ratio" => $processing_ratio,
                        "processing_charge" => $processing_charge,
                        "ip" => $ip,
                        "currency" => $currency,
                        "order_id" => $order_id,
                        "store_id" => $store_id,
                        "uid" => $uid,
                    ];


                    $this->savePaymentInfo($data);

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

    public function savePaymentInfo($data)
    {
        try {
            $merchant_processing_ratio = $this->getMerchantProcessingRatio($data['store_id']);
            $merchant_processing_charge = $this->calculateMerchantAmount($data['amountPaid'], $merchant_processing_ratio);
            $merchant_amount = (float)$data['amountPaid'] - $merchant_processing_charge;

            $OrderTransactionHistory = new OrderTransactionHistory();
            $OrderTransactionHistory->order_id = $data['order_id'] ?? "";
            $OrderTransactionHistory->store_id = $data['store_id'] ?? "";
            $OrderTransactionHistory->customer_id = $data['uid'] ?? "";
            $OrderTransactionHistory->transactionId = $data['transactionId'] ?? "";
            $OrderTransactionHistory->amountPaid = $data['amountPaid'] ?? "";
            $OrderTransactionHistory->merchant_processing_ratio = $merchant_processing_ratio;
            $OrderTransactionHistory->merchant_processing_charge = $merchant_processing_charge;
            $OrderTransactionHistory->merchant_amount = $merchant_amount;
            $OrderTransactionHistory->currency = $data['currency'] ?? "";;
            $OrderTransactionHistory->payment_type = $data['payment_type'] ?? "";;
            $OrderTransactionHistory->cardnumber = $data['cardnumber'] ?? "";;
            $OrderTransactionHistory->bank_trxid = $data['bank_trxid'] ?? "";;
            $OrderTransactionHistory->approval_code = $data['approval_code'] ?? "";;
            $OrderTransactionHistory->payment_processor = $data['payment_processor'] ?? "";;
            $OrderTransactionHistory->date_processed = $data['date_processed'] ?? "";;
            $OrderTransactionHistory->store_amount = $data['store_amount'] ?? "";;
            $OrderTransactionHistory->processing_ratio = $data['processing_ratio'] ?? "";;
            $OrderTransactionHistory->processing_charge = $data['processing_charge'] ?? "";;
            $OrderTransactionHistory->ip = $data['ip'] ?? "";;
            $OrderTransactionHistory->save();

            MerchantAccountJournal::saveJournal($OrderTransactionHistory);

        } catch (Exception $e) {
            // Do nothing
        }
    }

    public function getMerchantProcessingRatio($storeID = '')
    {
        $store = Store::with('plan')->where("id", $storeID)->first();
        $plan_id = $store->plan_id ?? NULL;

        $payment_processing_charge = 3.5;

        if (isset($plan_id)) {
            $paymentProcessor = PaymentProcessingCharge::where("plan_id", $plan_id)
                ->where("plan_type", "plan")
                ->where("payment_gateway", "amarpay")->first();

            if (isset($paymentProcessor)) {
                $payment_processing_charge = $paymentProcessor->payment_processing_charge ?? 3.5;
            }
        }

        return $payment_processing_charge;
    }


    public function calculateMerchantAmount($amount, $merchant_processing_ratio)
    {
        return ((float)$amount * (float)$merchant_processing_ratio / 100);
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
     * Payment failed
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function failedTransaction(Request $request)
    {
        $returnURL = $this->getStoreURL(null, Session::get('store_id'));

        $query = "?error_msg=Your transaction has been failed.";
        $url = $returnURL . $this->failedURL . $query;
        return redirect()->away($url);
    }

    /**
     *
     * Payment cancel
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancelTransaction(Request $request)
    {
        $returnURL = $this->getStoreURL(null, Session::get('store_id'));

        $query = "?error_msg=You have canceled the transaction.";
        $url = $returnURL . $this->failedURL . $query;
        return redirect()->away($url);
    }


}
