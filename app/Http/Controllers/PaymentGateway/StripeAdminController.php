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
use Stripe;

class StripeAdminController extends Controller
{

    private $secret_key;
    private $currency_code = "USD";

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     *
     * Store Stripe credentials
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function stripeCredentials(Request $request)
    {
        $rules = array(
            'app_key' => 'required|string',
            'app_secret' => 'required|string',
        );

        // Input vaidation message
        $errorMessage = array(
            "app_key.required" => "Publishable key is required.",
            "app_key.string" => "Publishable key must be a string.",
            "app_secret.required" => "Secret key is required.",
            "app_secret.string" => "Secret key must be a string.",
        );

        $validator = Validator::make($request->all(), $rules, $errorMessage);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $userData = getUserData();

            $stripe = Paymentgateway::where('payment_company', "stripe")->where('store_id',
                $userData["store_id"])->first();

            if (!isset($stripe)) {
                $stripe = new Paymentgateway();
                $stripe->payment_company = "stripe";
                $stripe->store_id = $userData["store_id"];
            }

            $stripe->app_key = $request->app_key;
            $stripe->app_secret = $request->app_secret;
            $stripe->user_id = $userData["user_id"];
            $stripe->status = isset($request->status) && $request->status == "on" ? "Accepted" : "Pending";
            $stripe->save();

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
        return view('checkoutPayment.stripeAdmin');
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

            $amount = $order['total'];
            $order_id = $order["id"];

            if (!isset($order_id) || $amount <= 0) {
                Session::flash('error', "Invalid order info.");
                return back();
            }

            $store_id = $order["store_id"];
            $currency_code = $this->currency_code ?? "USD";
            $amount = (float)$amount * 100;

            Session::forget('store_id');
            Session::put('store_id', $store_id);
            Session::forget('order_id');
            Session::put('order_id', $order_id);

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $response = Stripe\Checkout\Session::create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $currency_code,
                            'unit_amount' => $amount, // amount in cents
                            'product_data' => [
                                'name' => 'Payment Amount',
                            ],
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('stripe.admin.successTransaction') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.admin.cancelTransaction'),
                'metadata' => [
                    'order_id' => $order_id, // Add your Order ID here
                    'store_id' => $store_id, // Add your Store ID here
                ],
            ]);

            return redirect()->away($response->url);

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
            $sessionId = $request->query('session_id'); // Get session ID from query parameters


            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $stripeSession = Stripe\Checkout\Session::retrieve($sessionId);

            if (isset($stripeSession)) {
                // Access metadata from the retrieved session object
                $metadata = $stripeSession->metadata;

//                    $order_id = $metadata['order_id'];
//                    $store_id = $metadata['store_id'] ?? "";

                // Access transaction details
                $paymentIntent = $stripeSession->payment_intent;
                $amountPaid = $stripeSession->amount_total / 100; // Convert amount from cents to dollars

                // Retrieve transaction ID from payment intent
                $transactionId = $paymentIntent; // Stripe automatically generates a unique ID for each payment intent

                $data = [
                    "metadata" => $metadata,
                    "transactionId" => $transactionId,
                    "amountPaid" => $amountPaid,
                ];

                if (isset($transactionId) && $this->databaseUpdate($data)) {
                    Session::flash('success', 'Payment success.');
                    Session::flash('transaction_id', $transactionId);
                    return view('checkoutPayment.success');
                }

                Session::flash('success', 'Payment success. But order info not updated!');
                Session::flash('transaction_id', $transactionId);
                return view('checkoutPayment.success');
            }

            Session::flash('success', 'Payment success. But order info not updated!');
            return view('checkoutPayment.success');

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
            $metadata = $data['metadata'];
            $transactionId = $data['transactionId'];
            $amountPaid = $data['amountPaid'];

            $order_id = $metadata['order_id'];

            $order = AddonsOrder::find($order_id);

            if (isset($order)) {
                $order->payment_method = "stripe";
                $order->currency_id = 2; // USD
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
