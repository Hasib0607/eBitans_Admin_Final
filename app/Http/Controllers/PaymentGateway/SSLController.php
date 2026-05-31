<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Customer;
use App\Models\Headersetting;
use App\Models\Order;
use App\Models\Paymentgateway;
use App\Models\Store;
use App\Models\Transaction;
use App\Util\BkashCredential;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class SSLController extends Controller
{

    private $store_password = null;
    private $store_id = null;
    private $successURL = "/payment/success";
    private $failedURL = "/payment/failed";
    private $currency_code = "BDT";

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
            $currency_code = $this->currency_code ?? "BDT";

            // Get order pay amount
            $amount = $this->advancedPayment($order);

            Session::forget('store_id');
            Session::put('store_id', $store_id);
            Session::forget('order_id');
            Session::put('order_id', $order_id);

            $setConfigStatus = $this->setSSLConfig($store_id);
            if (!$setConfigStatus) {
                $query = "?error_msg=Something went wrong!.";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }

            $post_data = array();
            $post_data['total_amount'] = $amount; # You cant not pay less than 10
            $post_data['currency'] = $currency_code;
            $post_data['tran_id'] = uniqid(); // tran_id must be unique

            # CUSTOMER INFORMATION
            $post_data['cus_name'] = $order["name"] ?? "";
            $post_data['cus_email'] = $this->getCustomerEmail($order);
            $post_data['cus_add1'] = $order["address"] ?? "";
            $post_data['cus_add2'] = "";
            $post_data['cus_city'] = "";
            $post_data['cus_state'] = "";
            $post_data['cus_postcode'] = "";
            $post_data['cus_country'] = "Bangladesh";
            $post_data['cus_phone'] = $order["phone"] ?? "";
            $post_data['cus_fax'] = "";

            # SHIPMENT INFORMATION
            $post_data['ship_name'] = $order["name"] ?? "";
            $post_data['ship_add1'] = "Dhaka";
            $post_data['ship_add2'] = "Dhaka";
            $post_data['ship_city'] = "Dhaka";
            $post_data['ship_state'] = "Dhaka";
            $post_data['ship_postcode'] = "1000";
            $post_data['ship_phone'] = $order["phone"] ?? "";
            $post_data['ship_country'] = "Bangladesh";

            $post_data['shipping_method'] = "NO";
            $post_data['product_name'] = "Computer";
            $post_data['product_category'] = "Goods";
            $post_data['product_profile'] = "physical-goods";
            $post_data['value_a'] = $store_id;

            $orders = Order::where('id', $request->order_id)->first();
            if (isset($orders)) {
                $orders->transaction_id = $post_data['tran_id'];
                $orders->save();
            }

            $sslc = new SslCommerzNotification();

            # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
            $payment_options = $sslc->makePayment($post_data, 'hosted');

            if (!is_array($payment_options)) {
                print_r($payment_options);
                $payment_options = array();
            }

        } catch (Exception $e) {
            $query = "?error_msg=Something went wrong!.";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        }
    }

    public function getCustomerEmail($order)
    {
        if (isset($order["email"]) && !empty($order["email"])) {
            return $order["email"];
        }

        $store_id = $order["store_id"];
        $headerSetting = Headersetting::where('store_id', $store_id)->first();
        if (isset($headerSetting) && isset($headerSetting->email)) {
            return $headerSetting->email;
        } else {
            $store = Store::where("id", $store_id)->first();
            if (isset($store)) {
                return 'info@' . $store["url"];
            }
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
        $store_id = $request->value_a ?? $store_id;
        $store = Store::where("id", $store_id)->first();

        if (isset($store)) {
            $store_url = $store->url;
            Session::forget('store_url');
            Session::put('store_url', $store_url);

            return (request()->secure() ? 'https' : 'http') . '://' . $store_url;
        }

        $refer = $request->headers->get('referer') ?? "";
        if (isset($refer)) {
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
     * Set SSL configuration
     *
     * @param $store_id
     * @return bool
     */
    public function setSSLConfig($store_id = null)
    {
        try {
            $ssl = Paymentgateway::where('payment_company', "SSL")->where('store_id', $store_id)->first();

            if (isset($ssl) && !empty($ssl->ssl_store_id) && !empty($ssl->ssl_store_password)) {
                Config::set('sslcommerz.apiCredentials.store_id', $ssl->ssl_store_id);
                Config::set('sslcommerz.apiCredentials.store_password', $ssl->ssl_store_password);

                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            return false;
        }
    }

    public function success(Request $request)
    {
        $returnURL = $this->getStoreURL($request, Session::get('store_id'));

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_details = Order::where('transaction_id', $tran_id)->first();

        if ($order_details->status == 'Pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount);

            if ($validation) {
                if ($this->databaseUpdate($order_details, $tran_id, $amount)) {
                    $msg = "Transaction is successfully Completed.";
                    $query = "?error_msg=$msg&transaction_id=$tran_id&total=$amount";
                    $url = $returnURL . $this->successURL . $query;
                    return redirect()->away($url);
                }

                $msg = "Transaction is successfully Completed But Order status is not updated.";
                $query = "?error_msg=$msg&transaction_id=$tran_id&total=$amount";
                $url = $returnURL . $this->successURL . $query;
                return redirect()->away($url);
            } else {
                $order_details->status = 'Payment Failed';
                $order_details->save();

                $msg = "validation Fail";
                $query = "?error_msg=$msg";
                $url = $returnURL . $this->failedURL . $query;
                return redirect()->away($url);
            }
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            $msg = "Transaction is successfully Completed";
            $query = "?error_msg=$msg";
            $url = $returnURL . $this->successURL . $query;
            return redirect()->away($url);
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            $msg = "Invalid Transaction";
            $query = "?error_msg=$msg";
            $url = $returnURL . $this->failedURL . $query;
            return redirect()->away($url);
        }

    }

    /**
     * @param $data
     * @return bool|RedirectResponse
     */
    public function databaseUpdate($order, $transactionId, $amountPaid)
    {
        try {
            $order_id = $order->id;

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

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = Order::where('transaction_id', $tran_id)->first();

        if ($order_details->status == 'Pending') {
            $order_details->status = "Payment Failed";
            $order_details->save();

            $msg = "Transaction is Falied";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            $msg = "Transaction is already Successful";
        } else {
            $msg = "Transaction is Invalid";
        }

        $returnURL = $this->getStoreURL($request, Session::get('store_id'));
        $query = "?error_msg=$msg";
        $url = $returnURL . $this->failedURL . $query;
        return redirect()->away($url);
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = Order::where('transaction_id', $tran_id)->first();

        if ($order_details->status == 'Pending') {
            $order_details->status = "Payment Failed";
            $order_details->save();

            $msg = "Transaction is Cancel";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            $msg = "Transaction is already Successful";
        } else {
            $msg = "Transaction is Invalid";
        }

        $returnURL = $this->getStoreURL($request, Session::get('store_id'));
        $query = "?error_msg=$msg";
        $url = $returnURL . $this->failedURL . $query;
        return redirect()->away($url);

    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {
            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = Order::where('transaction_id', $tran_id)->first();
            $amount = $this->advancedPayment($order_details);
            $currency = $this->currency_code ?? "BDT";

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

                if ($validation) {
                    $this->databaseUpdate($order_details, $tran_id, $amount);
                    echo "Transaction is successfully Completed";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }

    }


}
