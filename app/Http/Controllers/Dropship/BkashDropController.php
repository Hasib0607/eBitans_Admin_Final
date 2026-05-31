<?php

namespace App\Http\Controllers\Dropship;

use App\Http\Controllers\Controller;
use App\Models\AccountJournal;
use App\Models\AffiliateInfo;
use App\Models\AffiliatePayment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

class BkashDropController extends Controller
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
        if (empty($request->user_id) || is_null($request->user_id)) {
            Session::flash('error', "Invalid request.");
            return back();
        }
        if (empty($request->store_id) || is_null($request->store_id)) {
            Session::flash('error', "Invalid request.");
            return back();
        }

        if (!isset($request->amount) || $request->amount <= 0) {
            Session::flash('error', "Invalid request.");
            return back();
        }

        $store_id = $request->store_id;
        $user_id = $request->user_id;
        $amount = $request->amount;

        $callBackURL = route('bkash.dropshipper.successTransaction', ['user_id' => $user_id, 'store_id' => $store_id]);

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

        //store paymentID and your account number for matching in callback request
//        dd($response); //if you are using sandbox and not submit info to bkash use it for 1 response

        if (isset($response['bkashURL'])) return redirect()->away($response['bkashURL']);
        else return redirect()->back()->with('error-alert2', $response['statusMessage']);
    }

    /**
     * Bkash success transaction callback
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function successTransaction(Request $request)
    {
        try {
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

                    $user_id = $request->user_id;
                    $store_id = $request->store_id;
                    $phone = $response['payerAccount'];
                    $amount = $response['amount'];
                    $transaction_id = $response['trxID'];

                    $data = [
                        "transactionId" => $transaction_id,
                        "user_id" => $user_id,
                        "store_id" => $store_id,
                        "amount" => $amount,
                        "phone" => $phone,
                    ];

                    $updateStatus = $this->databaseUpdate($data);

                    if ($updateStatus == true) {
                        Session::flash('payment', "Success");
                        Session::flash('success', 'Your payment has been successfully done');
                        Session::flash('transaction_id', $transaction_id);
                        return view('admin.dropship.successPayment');
                    }

                    Session::flash('payment', "Success");
                    Session::flash('success', 'Payment success. But database not updated!');
                    Session::flash('transaction_id', $transaction_id);
                    return view('admin.dropship.successPayment');
                }
                Session::flash('payment', "failed");
                Session::flash('error', 'Your transaction is failed');
                return view('admin.dropship.failedPayment');

                //return BkashPaymentTokenize::failure($response['statusMessage']);
            } else if ($request->status == 'cancel') {
                Session::flash('payment', "cancel");
                Session::flash('error', "Your payment is canceled!");
                return view('admin.dropship.failedPayment');

                //return BkashPaymentTokenize::cancel('Your payment is canceled');
            } else {
                Session::flash('payment', "failed");
                Session::flash('error', "Your transaction is failed.");
                return view('admin.dropship.failedPayment');

                //return BkashPaymentTokenize::failure('Your transaction is failed');
            }
        } catch (\Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', "Something went wrong.");
            return view('admin.dropship.failedPayment');
        }
    }


    /**
     * Update dropship account journal
     *
     * @param $data
     * @return bool|Application|Factory|View
     */
    public function databaseUpdate($data)
    {
        try {
            $voucher = AccountJournal::createVoucher();
            $user_id = $data['user_id'] ?? "";
            $store_id = $data['store_id'] ?? "";
            $payment_number = $data['phone'] ?? "";
            $transaction_id = $data['transactionId'] ?? "";
            $payment_method = "Bkash";
            $currency_id = 1;
            $note = "Payment of dropship commission";
            $payment_amount = (float)($data['amount'] ?? 0);
            $dr = $payment_amount;
            $cr = 0;
            $GetTotalBalance = AccountJournal::getAccountBalance($store_id);
            $balance = abs((float)$GetTotalBalance - $payment_amount);

            $dropship = new AccountJournal();
            $dropship->voucher = $voucher;
            $dropship->user_id = $user_id;
            $dropship->store_id = $store_id;
            $dropship->payment_amount = $payment_amount;
            $dropship->payment_method = $payment_method;
            $dropship->payment_number = $payment_number;
            $dropship->transaction_id = $transaction_id;
            $dropship->currency_id = $currency_id;
            $dropship->note = $note;
            $dropship->dr = $dr;
            $dropship->cr = $cr;
            $dropship->balance = $balance;
            $dropship->save();

            return true;

        } catch (\Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', 'Something went wrong');
            return view('admin.dropship.failedPayment');
        }
    }


}
