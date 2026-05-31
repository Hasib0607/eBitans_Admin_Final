<?php

namespace App\Http\Controllers;

use App\Models\AffiliateBalance;
use App\Models\AffiliateInfo;
use App\Models\AffiliatePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;
use Karim007\LaravelBkashTokenize\Facade\BkashRefundTokenize;

class BkashTokenizePaymentController extends Controller
{

    /**
     *
     * Create bkash payment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPayment(Request $request)
    {
        $affiliateInfo = AffiliateInfo::latest()->first();
        $userID = Auth::id();
        if (!$userID) {
            return back()->with("error", "Please login first");
        }

        if (isset($request->amount) && $request->amount > 0) {
            $paymentAmount = $request->amount;
        } else {
            $paymentAmount = $affiliateInfo->affiliate_charge;
        }


        $callBackURL = config("bkash.callbackURL") . "?userID=" . $userID;

        $inv = uniqid();
        $request['intent'] = 'sale';
        $request['mode'] = '0011'; //0011 for checkout
        $request['payerReference'] = $inv;
        $request['currency'] = 'BDT';
        $request['amount'] = $paymentAmount;
        $request['merchantInvoiceNumber'] = $inv;
        $request['callbackURL'] = $callBackURL;

        $request_data_json = json_encode($request->all());

        $response = BkashPaymentTokenize::cPayment($request_data_json);
        //$response =  BkashPaymentTokenize::cPayment($request_data_json,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..

        //store paymentID and your account number for matching in callback request
//        dd($response); //if you are using sandbox and not submit info to bkash use it for 1 response

        if (isset($response['bkashURL'])) return redirect()->away($response['bkashURL']);
        else return redirect()->back()->with('error-alert2', $response['statusMessage']);
    }

    /**
     *
     * Bkash success transaction callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callBack(Request $request)
    {
        //callback request params
        // paymentID=your_payment_id&status=success&apiVersion=1.2.0-beta
        //using paymentID find the account number for sending params

        if ($request->status == 'success') {
            $response = BkashPaymentTokenize::executePayment($request->paymentID);
            //$response = BkashPaymentTokenize::executePayment($request->paymentID, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
            if (!$response) { //if executePayment payment not found call queryPayment
                $response = BkashPaymentTokenize::queryPayment($request->paymentID);
                //$response = BkashPaymentTokenize::queryPayment($request->paymentID,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
            }

            if (isset($response['statusCode']) && $response['statusCode'] == "0000" && $response['transactionStatus'] == "Completed") {
                /*
                 * for refund need to store
                 * paymentID and trxID
                 * */
                $affiliatePayment = new AffiliatePayment();
                $affiliatePayment->user_id = $request->userID;
                $affiliatePayment->phone = $response['payerAccount'];
                $affiliatePayment->amount = $response['amount'];
                $affiliatePayment->currency = $response['currency'];
                $affiliatePayment->payment_method = "Bkash";
                $affiliatePayment->transaction_id = $response['trxID'];
                $affiliatePayment->status = "Completed";
                $affiliatePayment->save();


                $affiliateBlance = AffiliateBalance::where('user_id', $request->userID)->first();

                if (isset($affiliateBlance)) {
                    $affiliateBlance->balance = (int)$affiliateBlance->balance + (int)$response['amount'];
                } else {
                    $affiliateBlance = new AffiliateBalance();
                    $affiliateBlance->user_id = $request->userID;
                    $affiliateBlance->balance = $response['amount'];
                }
                $affiliateBlance->save();

                Session::flash('payment', "success");
                Session::flash('trxID', $response['trxID']);

                return redirect()->route("affiliate.index")->with("success", "Your payment has been successfully done");

                //return BkashPaymentTokenize::success('Thank you for your payment', $response['trxID']);
            }
            Session::flash('payment', "failed");
            return redirect()->route("affiliate.payment")->with("error", "Your transaction is failed");

            //return BkashPaymentTokenize::failure($response['statusMessage']);
        } else if ($request->status == 'cancel') {
            Session::flash('payment', "cancel");
            return redirect()->route("affiliate.payment")->with("error", "Your payment is canceled");

            //return BkashPaymentTokenize::cancel('Your payment is canceled');
        } else {
            Session::flash('payment', "failed");
            return redirect()->route("affiliate.payment")->with("error", "Your transaction is failed");

            //return BkashPaymentTokenize::failure('Your transaction is failed');
        }
    }

    /**
     * Search transaction
     *
     * @param $trxID
     * @return mixed
     */
    public function searchTnx($trxID)
    {
        //response
        return BkashPaymentTokenize::searchTransaction($trxID);
        //return BkashPaymentTokenize::searchTransaction($trxID,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }

    /**
     *
     * Refund method
     *
     * @param Request $request
     * @return mixed
     */
    public function refund(Request $request)
    {
        $paymentID = 'Your payment id';
        $trxID = 'your transaction no';
        $amount = 5;
        $reason = 'this is test reason';
        $sku = 'abc';
        //response
        return BkashRefundTokenize::refund($paymentID, $trxID, $amount, $reason, $sku);
        //return BkashRefundTokenize::refund($paymentID,$trxID,$amount,$reason,$sku, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }

    /**
     *
     * Refund stats method
     *
     * @param Request $request
     * @return mixed
     */
    public function refundStatus(Request $request)
    {
        $paymentID = 'Your payment id';
        $trxID = 'your transaction no';
        return BkashRefundTokenize::refundStatus($paymentID, $trxID);
        //return BkashRefundTokenize::refundStatus($paymentID,$trxID, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }
}
