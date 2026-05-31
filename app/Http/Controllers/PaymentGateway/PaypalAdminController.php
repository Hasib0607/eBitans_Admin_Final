<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
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

class PaypalAdminController extends Controller
{

    private $secret_key;
    private $currency_code = "USD";

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     *
     * Store Paypal credentials
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function paypalCredentials(Request $request)
    {
        $rules = array(
            'client_id' => 'required|string',
            'app_key' => 'nullable|string',
            'app_secret' => 'required|string',
        );

        // Input vaidation message
        $errorMessage = array(
            "client_id.required" => "Client ID is required.",
            "client_id.string" => "Client ID must be a string.",
            "app_key.string" => "App key must be a string.",
            "app_secret.required" => "Client secret is required.",
            "app_secret.string" => "Client secret must be a string.",
        );

        $validator = Validator::make($request->all(), $rules, $errorMessage);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $userData = getUserData();

            $paypal = Paymentgateway::where('payment_company', "paypal")->where('store_id',
                $userData["store_id"])->first();

            if (!isset($paypal)) {
                $paypal = new Paymentgateway();
                $paypal->payment_company = "paypal";
                $paypal->store_id = $userData["store_id"];
            }

            $paypal->client_id = $request->client_id;
            $paypal->app_key = $request->app_key;
            $paypal->app_secret = $request->app_secret;
            $paypal->user_id = $userData["user_id"];
            $paypal->status = isset($request->status) && $request->status == "on" ? "Accepted" : "Pending";
            $paypal->save();

            Session::flash("success", "Credentials save successfully.");
            return back();
        }

    }

    /**
     *
     * Stripe payment page show
     *
     * @return Application|Factory|View
     */
    public function paymentView()
    {
        return view('checkoutPayment.paypalAdmin');
    }


    /**
     *
     * Create stripe payment
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function createPayment(Request $request)
    {
        try {
            if (empty($request->order_id) || is_null($request->order_id)) {
                Session::flash('error', "Order ID is required.");
                return back();
            }

            $order = AddonsOrder::where("id", $request->order_id)->first();
            if (!isset($order)) {
                Session::flash('error', "Invalid order info.");
                return back();
            }

            $amount = (float)$order['total'];
            $order_id = $order["id"];

            if (!isset($order_id) || $amount <= 0) {
                Session::flash('error', "Invalid order info.");
                return back();
            }

            $store_id = $order["store_id"];
            $currency_code = $this->currency_code ?? "USD";

            Session::forget('store_id');
            Session::put('store_id', $store_id);
            Session::forget('order_id');
            Session::put('order_id', $order_id);

            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.admin.successTransaction', ['order_id' => $order_id]),
                    "cancel_url" => route('paypal.admin.cancelTransaction'),
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
     * Stripe Transaction Success
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
                $order_id = $request->query('order_id');
                $transactionId = $response['purchase_units'][0]['payments']['captures'][0]['id'];

                $data = [
                    "transactionId" => $transactionId,
                    "order_id" => $order_id,
                ];

                if ($this->databaseUpdate($data)) {
                    Session::flash('success', 'Payment success.');
                    Session::flash('transaction_id', $transactionId);
                    return view('checkoutPayment.success');
                }

                Session::flash('success', 'Payment success. But order info not updated!');
                Session::flash('transaction_id', $transactionId);
                return view('checkoutPayment.success');
            }

            Session::flash('error', "Transaction failed!");
            return view('checkoutPayment.fail');
        } catch (Exception $e) {
            return view('error');
        }
    }

    /**
     * Update order details
     *
     * @param $data
     * @return bool|Application|Factory|View
     */
    public function databaseUpdate($data)
    {
        try {
            $transactionId = $data['transactionId'];
            $order_id = $data['order_id'];

            $order = AddonsOrder::find($order_id);

            if (isset($order)) {
                $order->payment_method = "paypal";
                $order->currency_id = 2; // Set USD
                $order->transaction_id = $transactionId;
                $order->update();

                return (new AcceptPlanController())->acceptPlanOrder($order_id);
            }

            return false;
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
        return view('checkoutPayment.fail');
    }


}
