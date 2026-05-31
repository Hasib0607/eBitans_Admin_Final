<?php

namespace App\Http\Controllers\PaymentGateway\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Headersetting;
use App\Models\MerchantAccountJournal;
use App\Models\Order;
use App\Models\OrderTransactionHistory;
use App\Models\PaymentProcessingCharge;
use App\Models\Store;
use App\Models\Transaction;
use App\Util\BkashCredential;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Log;
use Karim007\LaravelNagad\Facade\NagadPayment;
use Karim007\LaravelNagad\Facade\NagadRefund;

class NagadController extends Controller
{
    private $successURL = "/payment/success";
    private $failedURL = "/payment/failed";
    private $callbackURL;

    public function __construct()
    {
        $this->callbackURL = route('ebitans-nagad.callback');
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

            $order_id_part = "EBOID" . $order_id;
            $max_length = 20; // Max allowed length
            $remaining_length = $max_length - strlen($order_id_part); // Remaining space for unique ID
            $timestamp = now()->format('His');
            $unique_part = substr($timestamp, -$remaining_length);
            $tran_id = $unique_part . $order_id_part;

            Session::forget('store_id');
            Session::put('store_id', $store_id);

            if (!merchantPaymentModulusStatus($store_id, 129, "nagad")) {
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

            $callBackURL = $this->callbackURL;
            config(['nagad.callback_url' => $callBackURL]);

            $response = NagadPayment::create($amount, $tran_id);

            if (isset($response) && $response->status == "Success") {
                return redirect()->away($response->callBackUrl);
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


    public function callback(Request $request)
    {
        $allRequest = $request->all();
        $status = $allRequest['status'];
        $tran_id = $allRequest['order_id'];

        preg_match('/EBOID(\d+)$/', $tran_id, $matches);
        $order_id = $matches[1] ?? null;

        $payment_ref_id = $allRequest['payment_ref_id'];

        $order = Order::find($order_id);
        $store_id = $order->store_id ?? NULL;

        $returnURL = $this->getStoreURL(null, $store_id);

        if (!$status && !$order_id) {
            $query = "?error_msg=Transaction failed!";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        }

        $verify = NagadPayment::verify($payment_ref_id); // $paymentRefId which you will find callback URL request parameter

        if (isset($verify->status) && $verify->status == "Success") {
            $transactionID = $verify->issuerPaymentRefNo ?? "";
            $amountPaid = $verify->amount ?? 0;
            $payment_type = "nagad";
            $cardnumber = $verify->clientMobileNo ?? "";

            $nagadCashOutCharge = 1.27;
            $nagad_processing_charge = $this->calculateMerchantAmount($amountPaid, $nagadCashOutCharge);
            $storeAmount = $amountPaid - $nagad_processing_charge;

            $bank_trxid = $verify->paymentRefId ?? ""; // This is Nagad payment Ref ID
            $approval_code = "";
            $payment_processor = "nagad";
            $date_processed = $verify->issuerPaymentDateTime ?? "";
            $store_amount = $storeAmount ?? "";
            $processing_ratio = $nagadCashOutCharge;
            $processing_charge = $nagad_processing_charge;
            $ip = "";
            $currency = "BDT";
            $uid = $order->uid ?? "";

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
        } elseif (isset($verify->status) && $verify->status == "Aborted") {
            $order->status = 'Payment Cancel';
            $order->update();

            $query = "?error_msg=Transaction Cancel!";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        } else {
            $order->status = 'Payment Failure';
            $order->update();

            $query = "?error_msg=Transaction failed!";
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


    public function refund($paymentRefId)
    {
        return true;
//        $refundAmount=1000;
//        $verify = NagadRefund::refund($paymentRefId,$refundAmount);
//        //$verify = NagadRefund::refund($paymentRefId,$refundAmount,'','sss',1); last parameter for manage account
//
//        if (isset($verify->status) && $verify->status == "Success") {
//            return $this->success($verify->orderId);
//        } else {
//            return $this->fail($verify->orderId);
//        }
    }


}
