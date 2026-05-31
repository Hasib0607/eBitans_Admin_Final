<?php

namespace App\Http\Controllers\PaymentGateway\Merchant;

use App\Http\Controllers\Controller;
use App\Models\BkashIDToken;
use App\Models\Headersetting;
use App\Models\MerchantAccountJournal;
use App\Models\Order;
use App\Models\OrderTransactionHistory;
use App\Models\Paymentgateway;
use App\Models\PaymentProcessingCharge;
use App\Models\Store;
use App\Models\Transaction;
use App\Util\BkashCredential;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Log;

class BkashController extends Controller
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;

    private $currency_code = "BDT";
    private $successURL = "/payment/success";
    private $failedURL = "/payment/failed";
    private $callbackURL;

    public function __construct()
    {
        // Live
        if (env('BKASH_SANDBOX')) {
            $this->base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
        } else {
            $this->base_url = 'https://tokenized.pay.bka.sh/v1.2.0-beta';
        }

        $this->app_key = env('BKASH_APP_KEY');
        $this->app_secret = env('BKASH_APP_SECRET');
        $this->username = env('BKASH_USERNAME');
        $this->password = env('BKASH_PASSWORD');

        $this->callbackURL = route('ebitans-bkash.callback');
    }

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
            $tran_id = $order["reference_no"]; // This is invoice id
            $currency_code = $this->currency_code ?? "BDT";

            Session::forget('store_id');
            Session::put('store_id', $store_id);

            if (!merchantPaymentModulusStatus($store_id, 128, "bkash")) {
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

            $header = $this->authHeaders();

            $body_data = array(
                'mode' => '0011',
                'payerReference' => ' ',
                'callbackURL' => $this->callbackURL . "?order_id=$order_id&store_id=$store_id",
                'amount' => $amount,
                'currency' => $currency_code,
                'intent' => 'sale',
                'merchantInvoiceNumber' => "Inv" . $tran_id // you can pass here OrderID
            );
            $body_data_json = json_encode($body_data);

            $response = $this->curlWithBody('/tokenized/checkout/create', $header, 'POST', $body_data_json);

            $responseData = json_decode($response);

            // Check if the response contains the expected property
            if (isset($responseData->bkashURL) && !empty($responseData->bkashURL)) {
                $paymentUrl = $responseData->bkashURL;
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

    public function authHeaders()
    {
        return array(
            'Content-Type:application/json',
            'Authorization:' . $this->grant(),
            'X-APP-Key:' . $this->app_key
        );
    }


    public function getBkashToken()
    {
        $lastTwoHours = Carbon::now()->subHours(2);

        $isTokenValid = BkashIDToken::whereNull("store_id")
            ->where("isAdmin", 1)
            ->where("updated_at", ">", $lastTwoHours)
            ->first();

        return $isTokenValid ? $isTokenValid->id_token : null;
    }

    public function grant()
    {
        $returnURL = $this->getStoreURL();
        try {
            $isTokenValid = $this->getBkashToken();
            if ($isTokenValid) {
                return $isTokenValid;
            }

            $header = array(
                "Content-Type:application/json",
                "username:$this->username",
                "password:$this->password"
            );

            $body_data = array(
                'app_key' => $this->app_key,
                'app_secret' => $this->app_secret
            );
            $body_data_json = json_encode($body_data);

            $response = $this->curlWithBody('/tokenized/checkout/token/grant', $header, 'POST', $body_data_json);

            $token = json_decode($response)->id_token;

            $store = BkashIDToken::whereNull("store_id")
                ->where("isAdmin", 1)
                ->first();

            if (isset($store)) {
                $store->id_token = $token;
                $store->update();
            } else {
                $store = new BkashIDToken();
                $store->store_id = NULL;
                $store->isAdmin = 1;
                $store->id_token = $token;
                $store->save();
            }

            return $token;
        } catch (Exception $e) {
            $query = "?error_msg=Something wrong.";
            $url = $returnURL . $this->failedURL . $query;
            return Redirect::to($url);
        }
    }

    public function curlWithBody($url, $header, $method, $body_data_json)
    {
        $curl = curl_init($this->base_url . $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body_data_json);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $response = curl_exec($curl);
        curl_close($curl);

        $res = json_decode($response);

        if (isset($res->message)) {
            $token = BkashIDToken::whereNull("store_id")
                ->where("isAdmin", 1)
                ->first();

            if (isset($token)) {
                $token->delete();
            }

            $header = $this->authHeaders();
            return $this->curlWithBody($url, $header, $method, $body_data_json);
        }

        return $response;
    }

    public function callback(Request $request)
    {
        $allRequest = $request->all();
        $order_id = $allRequest['order_id'];
        $store_id = $allRequest['store_id'];
        $order = Order::find($order_id);

        $returnURL = $this->getStoreURL(null, $store_id);

        if (isset($allRequest['status']) && $allRequest['status'] == 'failure') {
            $order->status = 'Payment Failure';
            $order->update();

            $query = "?error_msg=Transaction failed!";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        } else if (isset($allRequest['status']) && $allRequest['status'] == 'cancel') {
            $order->status = 'Payment Cancel';
            $order->update();

            $query = "?error_msg=Transaction Cancel!";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        } else {
            $response = $this->executePayment($allRequest['paymentID']);

            if (!isset($response)) {
                $response = $this->queryPayment($allRequest['paymentID']);
            }

            if (isset($response['statusCode']) && $response['statusCode'] == "0000" && $response['transactionStatus'] == "Completed") {
                $transactionID = $response['trxID'];
                $amountPaid = $response['amount'] ?? "";
                $payment_type = "bkash";
                $cardnumber = $response['customerMsisdn'] ?? "";

                $bkashCashOutCharge = 1.29;
                $bkash_processing_charge = $this->calculateMerchantAmount($amountPaid, $bkashCashOutCharge);
                $storeAmount = $amountPaid - $bkash_processing_charge;

                $bank_trxid = $response['paymentID'] ?? ""; // This is bkash payment ID
                $approval_code = "";
                $payment_processor = "bkash";
                $date_processed = $response['paymentExecuteTime'] ?? "";
                $store_amount = $storeAmount ?? "";
                $processing_ratio = $bkashCashOutCharge;
                $processing_charge = $bkash_processing_charge;
                $ip = "";
                $currency = $response['currency'] ?? "";
                $uid = $order->uid ?? "";

                if (isset($response['paymentExecuteTime'])) {
                    $date_processed = $this->formatDate($response['paymentExecuteTime']);
                }

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


            $query = "?error_msg=Transaction failed!";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        }
    }

    public function formatDate($timestamp)
    {
        $cleanTimestamp = preg_replace('/:\d{3}/', '', $timestamp);
        preg_match('/GMT([+-]\d{4})/', $cleanTimestamp, $matches);
        $timezoneOffset = $matches[1] ?? '+0000';
        $cleanTimestamp = str_replace(" GMT$timezoneOffset", '', $cleanTimestamp);
        $date = Carbon::createFromFormat('Y-m-d\TH:i:s', $cleanTimestamp, new \DateTimeZone($timezoneOffset));
        $localDate = $date->setTimezone(config('app.timezone')); // Set your local timezone

        return $localDate->format('Y-m-d H:i:s');
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
                ->where("payment_gateway", "bkash")->first();

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

    public function executePayment($paymentID)
    {
        $header = $this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID
        );
        $body_data_json = json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/execute', $header, 'POST', $body_data_json);

        return json_decode($response, true);
    }

    public function queryPayment($paymentID)
    {
        $header = $this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID,
        );

        $body_data_json = json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/payment/status', $header, 'POST', $body_data_json);

        return json_decode($response, true);
    }

    public function getRefund(Request $request)
    {
        return view('CheckoutURL.refund');
    }

    public function refundPayment(Request $request)
    {
        $header = $this->authHeaders();

        $body_data = array(
            'paymentID' => $request->paymentID,
            'amount' => $request->amount,
            'trxID' => $request->trxID,
            'sku' => 'sku',
            'reason' => 'Quality issue'
        );

        $body_data_json = json_encode($body_data);

        $response = $this->curlWithBody('/tokenized/checkout/payment/refund', $header, 'POST', $body_data_json);

        return view('CheckoutURL.refund')->with([
            'response' => $response,
        ]);

    }


}
