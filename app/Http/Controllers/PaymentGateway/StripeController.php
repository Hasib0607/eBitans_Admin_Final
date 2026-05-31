<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\Headersetting;
use App\Models\Order;
use App\Models\Paymentgateway;
use App\Models\Store;
use App\Models\Transaction;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Stripe;

class StripeController extends Controller
{

    private $secret_key = null;
    private $store_id = null;
    private $successURL = "/payment/success";
    private $failedURL = "/payment/failed";
    private $currency_code = "USD";

    public function __construct()
    {
//        $this->middleware('auth');
    }


    /**
     *
     * Stripe payment page show
     *
     * @return Application|Factory|View
     */
    public function payment()
    {
        $userData = getUserData();
        $stripe = Paymentgateway::where('payment_company', "stripe")->where('store_id',
            $userData["store_id"])->first();

        return view('checkoutPayment.stripe', ["stripe" => $stripe]);
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
        $returnURL = $this->getStoreURL($request);

        try {
            if (empty($request->order_id) || is_null($request->order_id)) {
                $query = "?error_msg=Order ID is required!";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            $order = Order::where("id", $request->order_id)->first();
            if (!isset($order)) {
                $query = "?error_msg=Invalid order info.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            $store_id = $order["store_id"];
            $this->store_id = $store_id;
            $order_id = $order["id"];
            $currency_code = $this->currency_code ?? "USD";

            // Get order pay amount
            $amount = $this->advancedPayment($order);
            $amount = $amount * 100;

            Session::forget('store_id');
            Session::put('store_id', $store_id);
            Session::forget('order_id');
            Session::put('order_id', $order_id);

            // Set paypal configuration
            $setConfigStatus = $this->setStripeConfig($store_id);
            if (!$setConfigStatus) {
                $query = "?error_msg=Something went wrong!.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            $stripe_app_secret = $this->secret_key ?? Session::get('stripe_app_secret');

            if (isset($stripe_app_secret)) {
                Stripe\Stripe::setApiKey($stripe_app_secret);

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
                    'success_url' => route('stripe.successTransaction') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('stripe.cancelTransaction'),
                    'metadata' => [
                        'order_id' => $order_id, // Add your Order ID here
                        'store_id' => $store_id, // Add your Store ID here
                    ],
                ]);

                return redirect()->away($response->url);
            } else {
                $query = "?error_msg=Something went wrong!.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

        } catch (Exception $e) {
            $query = "?error_msg=Something went wrong!.";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        }
    }

    /**
     *
     * Get store url
     *
     * @param $store_id
     * @return mixed|string|null
     */
    public function getStoreURL($request = null, $store_id = null)
    {
        $store_id = $request->store_id ?? $store_id ?? Session::get('store_id');
        $store = Store::where("id", $store_id)->first();

        if (isset($store)) {
            $store_url = $store->url;
            Session::forget('store_url');
            Session::put('store_url', $store_url);

            return (request()->secure() ? 'https' : 'http') . '://' . $store_url;
        }

        if ($request->headers->get('referer')) {
            $referrerUrl = $request->headers->get('referer');
            $referrer = parse_url($referrerUrl, PHP_URL_HOST);
        } else {
            $referrer = Session::get('referer_url') ?? "";
        }

        if (!empty($referrer)) {
            Session::forget('referer_url');
            Session::put('referer_url', $referrer);

            return (request()->secure() ? 'https' : 'http') . '://' . $referrer;
        }

        return null;

    }

    /**
     *
     * Calculate order pay amount
     *
     * @param $order
     * @return float
     */
    public function advancedPayment($order)
    {
        $amount = $order->due;
        if (ModulusStatus($order->store_id, 106)) {
            $paymentTy = Transaction::where('order_id', $order->id)->first();

            if (isset($paymentTy->mode) && $paymentTy->mode == 'ap') {
                $advancePayment = Headersetting::convertCurrency($order->store_id)->first();
                if ($advancePayment->payment_type == 0) {
                    $amount = $advancePayment->prepayment;
                } elseif ($advancePayment->payment_type == 1 && !empty($advancePayment->prepayment) && $advancePayment->prepayment != 0) {
                    $amount = ceil($amount * $advancePayment->prepayment / 100);
                } elseif ($advancePayment->payment_type == 2) {
                    $amount = $order->shipping;
                }
            }
        }
        return (float)$amount;
    }

    /**
     * Set stripe configuration
     *
     * @param $store_id
     * @return bool|RedirectResponse
     */
    public function setStripeConfig($store_id = null)
    {
        $store_id = $store_id ?? Session::get('store_id');

        $returnURL = $this->getStoreURL(null, $store_id);

        try {
            $store_id = $store_id ?? $this->store_id ?? Session::get('store_id');

            $stripe = Paymentgateway::where('payment_company', "stripe")->where('store_id', $store_id)->first();

            if (!isset($stripe) || !isset($stripe->app_secret)) {
                $query = "?error_msg=Something went wrong.!";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            Session::forget('stripe_app_secret');
            Session::put('stripe_app_secret', $stripe->app_secret);

            $this->secret_key = $stripe->app_secret;
            return true;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * Stripe Transaction Success
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function successTransaction(Request $request)
    {
        $store_id = Session::get('store_id');
        $returnURL = $this->getStoreURL(null, $store_id);

        try {
            $sessionId = $request->query('session_id'); // Get session ID from query parameters

            $setConfigStatus = $this->setStripeConfig($store_id);
            if (!$setConfigStatus) {
                $query = "?error_msg=Something went wrong!.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            $stripe_app_secret = $this->secret_key ?? Session::get('stripe_app_secret');

            if (isset($stripe_app_secret)) {
                Stripe\Stripe::setApiKey($this->secret_key);
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
                        $query = "?msg=Payment success.&transaction_id=" . $transactionId . "&total=" . $amountPaid;
                        $url = $returnURL . $this->failedURL . $query;
                        return redirect()->away($url);
                    }

                    $query = "?msg=Payment success. But order info not updated!&transaction_id=" . $transactionId . "&total=" . $amountPaid;
                    $url = $returnURL . $this->failedURL . $query;
                    return redirect()->away($url);
                }
            }

            $query = "?msg=Payment success. But order info not updated!";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);

        } catch (Exception $e) {
            $query = "?error_msg=Payment success. Something went wrong.!";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        }

    }

    /**
     * @param $data
     * @return bool|RedirectResponse
     */
    public function databaseUpdate($data)
    {
        try {
            $metadata = $data['metadata'];
            $transactionId = $data['transactionId'];
            $amountPaid = $data['amountPaid'];

            $order_id = $metadata['order_id'];
            $store_id = $metadata['store_id'] ?? "";

            $order = Order::where("id", $order_id);

            if (isset($order) && isset($store_id)) {
                $order = $order->where("store_id", $store_id);
            }

            $order = $order->first();

            if (isset($order)) {
                $due = (float)$order["due"] - (float)$amountPaid;
                $due = $due < 0 ? 0 : $due;

                $order->transaction_id = $transactionId;
                $order->paid = $amountPaid;
                $order->due = $due;
                $order->status = $order->total == $amountPaid ? 'Payment Success' : 'Partial Paid';
                $order->update();

                $transaction = Transaction::where('order_id', $order_id)->first();
                if (isset($transaction)) {
                    $transaction->transaction_id = $transactionId;
                    $transaction->status = 'Paid';
                    $transaction->update();
                }

                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * Stripe Transaction Canceled
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancelTransaction(Request $request)
    {
        $returnURL = $this->getStoreURL(null, Session::get('store_id'));

        $query = "?error_msg=You have canceled the transaction.";
        $url = $returnURL . $this->failedURL . $query;
        return redirect()->away($url);
    }


}
