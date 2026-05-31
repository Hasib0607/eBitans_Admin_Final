<?php

namespace App\Http\Controllers\Dropship;

use App\Http\Controllers\Controller;
use App\Models\AccountJournal;
use App\Models\Activity;
use App\Models\AddonsExpired;
use App\Models\AddonsOrder;
use App\Models\Mobileapp;
use App\Models\Paymentgateway;
use App\Models\Store;
use App\Models\User;
use App\Models\Websitesetup;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalDropshipController extends Controller
{

    private $secret_key;
    private $currency_code = "USD";

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }


    /**
     * Create paypal payment
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|void
     * @throws \Throwable
     */
    public function createPayment(Request $request)
    {
        try {
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
            $currency_code = $this->currency_code ?? "USD";

            Session::forget('store_id');
            Session::put('store_id', $store_id);
            Session::forget('user_id');
            Session::put('user_id', $user_id);

            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.dropshipper.successTransaction', ['user_id' => $user_id, 'store_id' => $store_id]),
                    "cancel_url" => route('paypal.dropshipper.cancelTransaction'),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => $currency_code,
                            "value" => $amount
                        ]
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                // redirect to approve href
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
            }
        } catch (Exception $e) {
            return view('error');
        }
    }


    /**
     *
     * Paypal Transaction Success
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function successTransaction(Request $request)
    {
        try {
            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);

            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                $user_id = $request->query('user_id');
                $store_id = $request->query('store_id');
                $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                $transactionId = $response['purchase_units'][0]['payments']['captures'][0]['id'];

                $data = [
                    "transactionId" => $transactionId,
                    "user_id" => $user_id,
                    "store_id" => $store_id,
                    "amount" => $amount,
                ];

                $updateStatus = $this->databaseUpdate($data);

                if ($updateStatus == true) {
                    Session::flash('success', 'Payment success.');
                    Session::flash('transaction_id', $transactionId);
                    return view('admin.dropship.successPayment');
                }

                Session::flash('success', 'Payment success. But database not updated!');
                Session::flash('transaction_id', $transactionId);
                return view('admin.dropship.successPayment');
            }

            Session::flash('error', "Transaction failed!");
            return view('admin.dropship.failedPayment');
        } catch (Exception $e) {
            return view('error');
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
            $transaction_id = $data['transactionId'] ?? "";
            $payment_method = "paypal";
            $currency_id = 2;
            $note = "Payment of dropship commission";
            $payment_amount = $data['amount'] ?? 0;
            $dr = number_format(AccountJournal::convertCurrency($payment_amount, 2, 1), 2);
            $cr = 0;
            $GetTotalBalance = AccountJournal::getAccountBalance($store_id);
            $balance = abs((float)$GetTotalBalance - (float)$dr);

            $dropship = new AccountJournal();
            $dropship->voucher = $voucher;
            $dropship->user_id = $user_id;
            $dropship->store_id = $store_id;
            $dropship->payment_amount = $payment_amount;
            $dropship->payment_method = $payment_method;
            $dropship->transaction_id = $transaction_id;
            $dropship->currency_id = $currency_id;
            $dropship->note = $note;
            $dropship->dr = $dr;
            $dropship->cr = $cr;
            $dropship->balance = $balance;
            $dropship->save();

            return true;

        } catch (Exception $e) {
            return view('error');
        }
    }


    /**
     *
     * Stripe Transaction Canceled
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function cancelTransaction(Request $request)
    {
        Session::flash('error', $request['message'] ?? 'You have canceled the transaction.');
        return view('admin.dropship.failedPayment');
    }


}
