<?php

namespace App\Http\Controllers\PaymentGateway\AddonPayment;

use App\Http\Controllers\Controller;
use App\Models\AccountJournal;
use App\Models\Activity;
use App\Models\AddonsExpired;
use App\Models\AddonsOrder;
use App\Models\Mobileapp;
use App\Models\Modulus;
use App\Models\ModulusPayment;
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

class PaypalModulusController extends Controller
{

    private $currency_code = "USD";

    public function __construct()
    {
        $this->middleware(['auth']);
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

            if ($modulus->price_usd != $request->amount) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            $userData = getUserData();
            $store_id = $userData['store_id'] ?? "";
            $amount = $request->amount;
            $modulus_id = $modulus->id;
            $total_product = $request->total_product ?? 0;
            $currency_code = $this->currency_code ?? "USD";

            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.modulus.successTransaction', ['modulus_id' => $modulus_id, 'store_id' => $store_id, 'total_product' => $total_product]),
                    "cancel_url" => route('paypal.modulus.cancelTransaction'),
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
            return redirect()->route("admin.modulus");
        }
    }


    /***
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
                $modulus_id = $request->query('modulus_id');
                $store_id = $request->query('store_id');
                $total_product = $request->query('total_product') ?? NULL;
                $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                $transactionId = $response['purchase_units'][0]['payments']['captures'][0]['id'];
                $payerEmail = isset($response['payer']['email_address']) ? $response['payer']['email_address'] : null; // Get the payer's email

                $payment = new ModulusPayment();
                $payment->modulus_id = $modulus_id;
                $payment->store_id = $store_id;
                $payment->payment_type = "paypal";
                $payment->number = $payerEmail;
                $payment->price = $amount;
                $payment->transaction_id = $transactionId;
                $payment->total_product = $total_product;
                $payment->status = 1;
                $payment->save();

                Session::flash('success', 'Payment success.');
                Session::flash('transaction_id', $transactionId);
                return redirect()->route("admin.modulus");
            }

            Session::flash('error', "Transaction failed!");
            return redirect()->route("admin.modulus");
        } catch (Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', 'Something went wrong');
            return redirect()->route("admin.modulus");
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
        return redirect()->route("admin.modulus");
    }


}
