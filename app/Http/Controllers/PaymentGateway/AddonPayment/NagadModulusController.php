<?php

namespace App\Http\Controllers\PaymentGateway\AddonPayment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentGateway\AcceptPlanController;
use App\Models\AccountJournal;
use App\Models\AddonsOrder;
use App\Models\AffiliateInfo;
use App\Models\AffiliatePayment;
use App\Models\Modulus;
use App\Models\ModulusPayment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;
use Karim007\LaravelNagad\Facade\NagadPayment;

class NagadModulusController extends Controller
{
    public function createPayment(Request $request)
    {
        try {
            if (empty($request->modulus_id) || is_null($request->modulus_id)) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            if (!isset($request->amount) || $request->amount <= 0) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            $modulus = Modulus::where('id', $request->modulus_id)->first();
            if (!isset($modulus)) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            if ($modulus->price != $request->amount) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            $userData = getUserData();
            $store_id = $userData['store_id'] ?? "";
            $amount = $request->amount;
            $total_product = $request->total_product ?? 0;
            $order_id = $modulus->id;

            $order_id_part = "EBMID" . $store_id . "A" . $total_product . "B" . $order_id;
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

            $callBackURL = route('nagad.modulus.callback');
            config(['nagad.callback_url' => $callBackURL]);

            $response = NagadPayment::create($amount, $tran_id);

            if (isset($response) && $response->status == "Success") {
                return redirect()->away($response->callBackUrl);
            } else {
                $msg = 'Something went wrong';
                return redirect()->route("admin.modulus")->with('error-alert2', $msg);
            }
        } catch (\Exception $e) {
            $msg = 'Something went wrong';
            return redirect()->route("admin.modulus")->with('error-alert2', $msg);
        }
    }

    public function callback(Request $request)
    {
        $allRequest = $request->all();
        $status = $allRequest['status'];
        $tran_id = $allRequest['order_id'];

        preg_match('/EBMID(\d+)A(\d+)B(\d+)$/', $tran_id, $matches);
        $store_id = $matches[1] ?? null;
        $total_product = $matches[2] ?? null;
        $modulus_id = $matches[3] ?? null;

        $payment_ref_id = $allRequest['payment_ref_id'];

        if (!$status && !$modulus_id) {
            Session::flash('payment', "cancel");
            Session::flash('error', "Transaction failed!");
            return redirect()->route("admin.modulus");
        }

        $verify = NagadPayment::verify($payment_ref_id); // $paymentRefId which you will find callback URL request parameter

        if (isset($verify->status) && $verify->status == "Success") {
            $transactionID = $verify->issuerPaymentRefNo ?? "";
            $mobileNumber = $verify->clientMobileNo ?? "";
            $amountPaid = $verify->amount ?? 0;
//            $bank_trxid = $verify->paymentRefId ?? ""; // This is Nagad payment Ref ID

            $payment = new ModulusPayment();
            $payment->modulus_id = $modulus_id;
            $payment->store_id = $store_id;
            $payment->payment_type = "nagad";
            $payment->number = $mobileNumber;
            $payment->price = $amountPaid;
            $payment->transaction_id = $transactionID;
            $payment->total_product = $total_product ?? NULL;
            $payment->status = 1;
            $payment->save();

            Session::flash('payment', "Success");
            Session::flash('success', 'Your payment has been successfully done');
            Session::flash('transaction_id', $transactionID);
            return redirect()->route("admin.modulus");
        } elseif (isset($verify->status) && $verify->status == "Aborted") {
            Session::flash('payment', "cancel");
            Session::flash('error', "Your payment is canceled!");
            return redirect()->route("admin.modulus");
        } else {
            Session::flash('payment', "failed");
            Session::flash('error', "Your transaction is failed.");
            return redirect()->route("admin.modulus");
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
