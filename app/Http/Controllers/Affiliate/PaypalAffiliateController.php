<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AccountJournal;
use App\Models\Activity;
use App\Models\AddonsExpired;
use App\Models\AddonsOrder;
use App\Models\AffiliateBalance;
use App\Models\AffiliateInfo;
use App\Models\AffiliatePayment;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalAffiliateController extends Controller
{

    private $secret_key;
    private $currency_code = "USD";

    public function __construct()
    {
        $this->middleware(['auth', 'affiliate']);
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
            if (!isset($request->amount) || $request->amount <= 0) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            $user = Auth::user();
            $user_id = Auth::id();
            if (!isset($user)) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            $affiliateInfo = AffiliateInfo::latest()->first();
            if (isset($request->amount) && $request->amount > 0) {
                $amount = $request->amount;
            } else {
                $amount = $affiliateInfo->affiliate_charge;
            }

            $currency_code = $this->currency_code ?? "USD";

            Session::forget('user_id');
            Session::put('user_id', $user_id);

            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.affiliate.successTransaction', ['user_id' => $user_id]),
                    "cancel_url" => route('paypal.affiliate.cancelTransaction'),
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
            Session::flash('payment', "failed");
            Session::flash('error', 'Something went wrong');
            return back();
        }
    }


    /**
     * Paypal Transaction Success
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
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
                $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                $transactionId = $response['purchase_units'][0]['payments']['captures'][0]['id'];
                $payerEmail = isset($response['payer']['email_address']) ? $response['payer']['email_address'] : null; // Get the payer's email

                $data = [
                    "transactionId" => $transactionId,
                    "payment_method" => "Paypal",
                    "user_id" => $user_id,
                    "payment_amount" => $amount,
                    "payment_number" => $payerEmail,
                ];

                $updateStatus = $this->databaseUpdate($data);

                if ($updateStatus == true) {
                    Session::flash('success', 'Payment success.');
                    Session::flash('transaction_id', $transactionId);
                    return redirect()->route('affiliate.index');
                }

                Session::flash('success', 'Payment success. But database not updated!');
                Session::flash('transaction_id', $transactionId);
                return redirect()->route('affiliate.index');
            }

            Session::flash('payment', "failed");
            Session::flash('error', "Your transaction is failed.");
            return redirect()->route('affiliate.payment');
        } catch (Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', 'Something went wrong');
            return redirect()->route('affiliate.payment');
        }
    }

    /**
     * Update Affiliate account journal
     *
     * @param $data
     * @return RedirectResponse|true
     */
    public function databaseUpdate($data)
    {
        try {
            $user_id = $data['user_id'] ?? "";
            $payment_number = $data['payment_number'] ?? "";
            $transaction_id = $data['transactionId'] ?? "";
            $payment_method = $data['payment_method'] ?? "";
            $currency_id = $this->currency_code ?? "USD";
            $payment_amount = $data['payment_amount'] ?? 0;


            $rates = Http::asForm()->get('https://latest.currency-api.pages.dev/v1/currencies/usd.json')['usd'];
            $usdToTk = $rates['bdt'] ?? 100;
            $usdToTk = round($usdToTk);

            $payment_amount = ($usdToTk * (float)$payment_amount);

            $affiliatePayment = new AffiliatePayment();
            $affiliatePayment->user_id = $user_id;
            $affiliatePayment->phone = $payment_number;
            $affiliatePayment->amount = $payment_amount;
            $affiliatePayment->currency = $currency_id;
            $affiliatePayment->payment_method = $payment_method;
            $affiliatePayment->transaction_id = $transaction_id;
            $affiliatePayment->status = "Completed";
            $affiliatePayment->save();

            $affiliateBlance = AffiliateBalance::where('user_id', $user_id)->first();

            if (isset($affiliateBlance)) {
                $affiliateBlance->balance = (int)$affiliateBlance->balance + (int)$payment_amount;
            } else {
                $affiliateBlance = new AffiliateBalance();
                $affiliateBlance->user_id = $user_id;
                $affiliateBlance->balance = $payment_amount;
            }
            $affiliateBlance->save();

            return true;

        } catch (\Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', 'Something went wrong');
            return redirect()->route('affiliate.payment');
        }
    }


    /**
     * Paypal Transaction Canceled
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancelTransaction(Request $request)
    {
        Session::flash('error', $request['message'] ?? 'You have canceled the transaction.');
        return redirect()->route('affiliate.payment');
    }


}
