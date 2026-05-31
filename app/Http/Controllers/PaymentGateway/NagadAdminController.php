<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\AddonsOrder;
use App\Models\Headersetting;
use App\Models\MerchantAccountJournal;
use App\Models\Order;
use App\Models\OrderTransactionHistory;
use App\Models\PaymentProcessingCharge;
use App\Models\Store;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Log;
use Karim007\LaravelNagad\Facade\NagadPayment;
use Karim007\LaravelNagad\Facade\NagadRefund;

class NagadAdminController extends Controller
{
    private $callbackURL;

    public function __construct()
    {
        $this->callbackURL = route('nagad.admin.callback');
    }

    public function createPayment(Request $request)
    {
        try {
            if (empty($request->order_id) || is_null($request->order_id)) {
                $msg = 'Order ID is required!';
                return redirect()->back()->with('error', $msg);
            }

            $order = AddonsOrder::where("id", $request->order_id)->first();
            if (!isset($order)) {
                $msg = 'Invalid order info.';
                return redirect()->back()->with('error', $msg);
            }

            $amount = $order->total ?? 0;
            $store_id = $order->store_id;
            $order_id = $order->id;

            $order_id_part = "EBAOID" . $order_id;
            $max_length = 20; // Max allowed length
            $remaining_length = $max_length - strlen($order_id_part); // Remaining space for unique ID
            $timestamp = now()->format('His');
            $unique_part = substr($timestamp, -$remaining_length);
            $tran_id = $unique_part . $order_id_part;

            Session::forget('store_id');
            Session::put('store_id', $store_id);
            Session::forget('order_id');
            Session::put('order_id', $order_id);

            if ($amount <= 0) {
                $msg = 'Amount cannot be zero.';
                return redirect()->back()->with('error', $msg);
            }

            $callBackURL = $this->callbackURL;
            config(['nagad.callback_url' => $callBackURL]);

            $response = NagadPayment::create($amount, $tran_id);

            if (isset($response) && $response->status == "Success") {
                return redirect()->away($response->callBackUrl);
            } else {
                $msg = 'Something went wrong';
                return redirect()->route('payment.payments')->with('error', $msg);
            }
        } catch (Exception $e) {
            $msg = 'Something went wrong';
            return redirect()->route('payment.payments')->with('error', $msg);
        }

    }


    public function callback(Request $request)
    {
        $allRequest = $request->all();
        $status = $allRequest['status'];
        $tran_id = $allRequest['order_id'];

        preg_match('/EBAOID(\d+)$/', $tran_id, $matches);
        $order_id = $matches[1] ?? null;

        $payment_ref_id = $allRequest['payment_ref_id'];


        if (!$status && !$order_id) {
            $msg = 'Transaction failed!';
            return redirect()->route('payment.payments')->with('error', $msg);
        }

        $verify = NagadPayment::verify($payment_ref_id); // $paymentRefId which you will find callback URL request parameter

        if (isset($verify->status) && $verify->status == "Success") {
            $transactionID = $verify->issuerPaymentRefNo ?? "";
            $mobileNumber = $verify->clientMobileNo ?? "";
//            $amountPaid = $verify->amount ?? 0;
//            $bank_trxid = $verify->paymentRefId ?? ""; // This is Nagad payment Ref ID

            $order = AddonsOrder::find($order_id);
            if (!isset($order)) {
                $msg = "Payment successful. But Order Info Not Updated. Transaction ID " . $transactionID;
                return redirect()->route('payment.payments')->with('success', $msg);
            }

            $order->payment_method = "nagad";
            $order->transaction_id = $transactionID;
            $order->payment_number = $mobileNumber;
            $order->update();

            (new AcceptPlanController())->acceptPlanOrder($order->id);

            $msg = "Payment success. Transaction ID " . $transactionID;
            return redirect()->route('payment.payments')->with('success', $msg);
        } elseif (isset($verify->status) && $verify->status == "Aborted") {
            $msg = 'Transaction Cancel!';
            return redirect()->route('payment.payments')->with('error', $msg);
        } else {
            $msg = 'Transaction failed!';
            return redirect()->route('payment.payments')->with('error', $msg);
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
