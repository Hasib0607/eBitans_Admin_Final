<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\Headersetting;
use App\Models\Order;
use App\Models\Paymentgateway;
use App\Models\Store;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;


class PaypalController extends Controller
{
    private $store_id = null;
    private $successURL = "/payment/success";
    private $failedURL = "/payment/failed";
    private $currency_code = "USD";

    public function __construct()
    {
//            $this->middleware('auth');
    }


    public function paymentView()
    {
        return view('checkoutPayment.paypal');
    }

    /**
     *
     * Create paypal payment
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
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

            if ($amount <= 0) {
                $query = "?error_msg=Amount cannot be zero.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            Session::forget('store_id');
            Session::put('store_id', $store_id);
            Session::forget('order_id');
            Session::put('order_id', $order_id);

            // Set paypal configuration
            $setConfigStatus = $this->setConfig($store_id);
            if (!$setConfigStatus) {
                $query = "?error_msg=Something went wrong!.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.successTransaction',
                        ['order_id' => $order_id, "store_id" => $store_id]),
                    "cancel_url" => route('paypal.cancelTransaction'),
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
            $query = "?error_msg=Something wrong.";
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
     * Set paypal configuration
     *
     * @param $store_id
     * @return bool
     */
    public function setConfig($store_id)
    {
        $returnURL = $this->getStoreURL(null, $store_id);

        try {
            $paypal = Paymentgateway::where('payment_company', "paypal")->where('store_id', $store_id)->first();

            if (isset($paypal) && !empty($paypal->client_id) && !empty($paypal->app_secret)) {
                Config::set('paypal.sandbox.client_id', $paypal->client_id);
                Config::set('paypal.sandbox.client_secret', $paypal->app_secret);

                Config::set('paypal.live.client_id', $paypal->client_id);
                Config::set('paypal.live.client_secret', $paypal->app_secret);

                return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * Paypal success transaction callback
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function successTransaction(Request $request)
    {
        $order_id = $request->query('order_id');
        $store_id = $request->query('store_id');

        $returnURL = $this->getStoreURL(null, $store_id);

        try {
            // Set paypal configuration
            $setConfigStatus = $this->setConfig($store_id);
            if (!$setConfigStatus) {
                $query = "?error_msg=Something went wrong!.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);

            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                // Get the transaction ID from the response
                $transactionId = $response['purchase_units'][0]['payments']['captures'][0]['id'];

                // Get the amount from the response
                $amountPaid = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

                $data = [
                    "transactionId" => $transactionId,
                    "amountPaid" => $amountPaid,
                    "order_id" => $order_id,
                    "store_id" => $store_id,
                ];

                if ($this->databaseUpdate($data)) {
                    $query = "?msg=Payment success.&transaction_id=" . $transactionId . "&total=" . $amountPaid;
                    $url = $returnURL . $this->successURL . $query;
                    return redirect()->away($url);
                }

                $query = "?msg=Payment success. But order info not updated!&transaction_id=" . $transactionId . "&total=" . $amountPaid;
                $url = $returnURL . $this->successURL . $query;
                return redirect()->away($url);
            }
            $query = "?error_msg=Transaction failed!";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);

        } catch (Exception $e) {
            $query = "?error_msg=Something wrong.";
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
            $transactionId = $data['transactionId'];
            $amountPaid = $data['amountPaid'];

            $order_id = $data['order_id'];
            $store_id = $data['store_id'] ?? "";

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
     * Payment cancel
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
