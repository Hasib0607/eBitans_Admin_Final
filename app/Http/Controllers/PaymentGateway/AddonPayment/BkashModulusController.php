<?php

namespace App\Http\Controllers\PaymentGateway\AddonPayment;

use App\Http\Controllers\Controller;
use App\Models\AccountJournal;
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

class BkashModulusController extends Controller
{

    /**
     * Create bkash payment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPayment(Request $request)
    {
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
        $modulus_id = $modulus->id;

        $callBackURL = route('bkash.modulus.successTransaction', ['modulus_id' => $modulus_id, 'store_id' => $store_id, 'total_product' => $total_product]);

        $inv = uniqid();
        $request['intent'] = 'sale';
        $request['mode'] = '0011'; //0011 for checkout
        $request['payerReference'] = $inv;
        $request['currency'] = 'BDT';
        $request['amount'] = $amount;
        $request['merchantInvoiceNumber'] = $inv;
        $request['callbackURL'] = $callBackURL;

        $request_data_json = json_encode($request->all());

        $response = BkashPaymentTokenize::cPayment($request_data_json);
        //$response =  BkashPaymentTokenize::cPayment($request_data_json,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..

        if (isset($response['bkashURL'])) {
            return redirect()->away($response['bkashURL']);
        } else {
            return redirect()->route("admin.modulus")->with('error-alert2', $response['statusMessage']);
        }

    }

    /**
     * Bkash success transaction callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function successTransaction(Request $request)
    {
        try {
            if ($request->status == 'success') {
                $response = BkashPaymentTokenize::executePayment($request->paymentID);
                //$response = BkashPaymentTokenize::executePayment($request->paymentID, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
                if (!$response) { //if executePayment payment not found call queryPayment
                    $response = BkashPaymentTokenize::queryPayment($request->paymentID);
                    //$response = BkashPaymentTokenize::queryPayment($request->paymentID,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
                }

                if (isset($response['statusCode']) && $response['statusCode'] == "0000" && $response['transactionStatus'] == "Completed") {
                    $modulus_id = $request->modulus_id;
                    $store_id = $request->store_id;
                    $phone = $response['payerAccount'];
                    $amount = $response['amount'];
                    $transaction_id = $response['trxID'];

                    $payment = new ModulusPayment();
                    $payment->modulus_id = $modulus_id;
                    $payment->store_id = $store_id;
                    $payment->payment_type = "bkash";
                    $payment->number = $phone;
                    $payment->price = $amount;
                    $payment->transaction_id = $transaction_id;
                    $payment->total_product = $request->total_product ?? NULL;
                    $payment->status = 1;
                    $payment->save();

                    Session::flash('payment', "Success");
                    Session::flash('success', 'Your payment has been successfully done');
                    Session::flash('transaction_id', $transaction_id);
                    return redirect()->route("admin.modulus");
                }

                Session::flash('payment', "failed");
                Session::flash('error', 'Your transaction is failed');
                return redirect()->route("admin.modulus");

            } else if ($request->status == 'cancel') {
                Session::flash('payment', "cancel");
                Session::flash('error', "Your payment is canceled!");
                return redirect()->route("admin.modulus");

            } else {
                Session::flash('payment', "failed");
                Session::flash('error', "Your transaction is failed.");
                return redirect()->route("admin.modulus");

            }
        } catch (\Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', "Something went wrong.");
            return redirect()->route("admin.modulus");
        }
    }


}
