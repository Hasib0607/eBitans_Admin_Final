<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AccountJournal;
use App\Models\AffiliateBalance;
use App\Models\AffiliateInfo;
use App\Models\AffiliatePayment;
use App\Models\Store;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

class AmarpayAffiliateController extends Controller
{
    private $currency_code = "BDT";

    private $base_url;
    private $verify_url;
    private $storeId;
    private $signatureKey;
    private $apaySuccessURL;
    private $apayFailedURL;
    private $apayCancelURL;

    public function __construct()
    {
        // Live
        if (env('AMARPAY_SANDBOX')) {
            $this->base_url = 'https://sandbox.aamarpay.com/jsonpost.php';
            $this->verify_url = 'https://sandbox.aamarpay.com/api/v1/trxcheck/request.php';
        } else {
            $this->base_url = 'https://secure.aamarpay.com/jsonpost.php';
            $this->verify_url = 'https://secure.aamarpay.com/api/v1/trxcheck/request.php';
        }

        $this->storeId = env('AAMARPAY_STORE_ID', 'aamarpaytest');
        $this->signatureKey = env('AAMARPAY_SIGNATURE_KEY', 'dbb74894e82415a2f7ff0ec3a97e4183');

        $this->apaySuccessURL = route('amarpay.affiliate.successTransaction');
        $this->apayCancelURL = route('amarpay.affiliate.cancelTransaction');
        $this->apayFailedURL = route('amarpay.affiliate.failedTransaction');
    }


    /**
     * Create amarpay payment
     *
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Http\RedirectResponse
     *
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

            $tran_id = uniqid();; // This is invoice id
            $currency_code = $this->currency_code ?? "BDT";

            Session::forget('user_id');
            Session::put('user_id', $user_id);

            $header = array(
                'Content-Type: application/json'
            );

            $body_data = array(
                "store_id" => $this->storeId,
                "tran_id" => $tran_id,
                "success_url" => $this->apaySuccessURL,
                "fail_url" => $this->apayFailedURL,
                "cancel_url" => $this->apayCancelURL,
                "amount" => $amount,
                "currency" => $currency_code,
                "signature_key" => $this->signatureKey,
                "desc" => "Affiliate Payment",
                "cus_name" => $user->name ?? "Unknow user",
                "cus_email" => $user->email ?? generateCustomEmail($user->name),
                "cus_add1" => $user->address ?? "Test Address",
                "cus_add2" => "",
                "cus_city" => "",
                "cus_state" => "",
                "cus_postcode" => "",
                "cus_country" => "Bangladesh",
                "cus_phone" => $user->phone ?? "01700000000",
                "opt_a" => $user_id,
                "opt_b" => "",
                "opt_c" => "",
                "type" => "json"
            );


            $body_data_json = json_encode($body_data);

            $response = $this->curlWithBody($this->base_url, $header, 'POST', $body_data_json);
            $responseObj = json_decode($response);

            if (isset($responseObj->payment_url) && !empty($responseObj->payment_url)) {
                $paymentUrl = $responseObj->payment_url;
                return redirect()->away($paymentUrl);
            } else {
                Session::flash('payment', "failed");
                Session::flash('error', 'Something went wrong');
                return back();
            }
        } catch (\Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', 'Something went wrong');
            return back();
        }

    }


    public function curlWithBody($url, $header, $method, $body_data_json)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body_data_json,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }


    /***
     * Amar pay success transaction callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function successTransaction(Request $request)
    {
        $allRequest = $request->all();
        $user_id = $allRequest['opt_a'] ?? "";

        try {
            if (isset($allRequest['pay_status']) && $allRequest['pay_status'] == 'Successful') {
                $request_id = $request->mer_txnid; // merchant invoice

                $url = "$this->verify_url?request_id=$request_id&store_id=$this->storeId&signature_key=$this->signatureKey&type=json";

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                $responseObj = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($responseObj);

                $transactionID = $response->pg_txnid ?? "";
                $payment_type = $response->payment_type ?? "";
                $cardnumber = $response->cardnumber ?? "";
                $amountPaid = $response->amount ?? "";

                if (isset($response->pay_status) && $response->pay_status == "Successful") {
                    $data = [
                        "transactionId" => $transactionID,
                        "payment_method" => $payment_type,
                        "user_id" => $user_id,
                        "payment_amount" => $amountPaid,
                        "payment_number" => $cardnumber,
                    ];

                    $updateStatus = $this->databaseUpdate($data);

                    if ($updateStatus == true) {
                        Session::flash('payment', "Success");
                        Session::flash('success', 'Your payment has been successfully done');
                        Session::flash('transaction_id', $transactionID);
                        return redirect()->route('affiliate.index');
                    }

                    Session::flash('payment', "Success");
                    Session::flash('success', 'Payment success. But database not updated!');
                    Session::flash('transaction_id', $transactionID);
                    return redirect()->route('affiliate.index');
                }

            }

            Session::flash('payment', "failed");
            Session::flash('error', "Your transaction is failed.");
            return redirect()->route('affiliate.payment');

        } catch (\Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', 'Something went wrong');
            return redirect()->route('affiliate.payment');
        }
    }


    /**
     * Update user data
     *
     * @param $data
     * @return \Illuminate\Http\RedirectResponse|true
     */
    public function databaseUpdate($data)
    {
        try {
            $user_id = $data['user_id'] ?? "";
            $payment_number = $data['payment_number'] ?? "";
            $transaction_id = $data['transactionId'] ?? "";
            $payment_method = $data['payment_method'] ?? "";
            $currency_id = "BDT";
            $payment_amount = $data['payment_amount'] ?? 0;
            $payment_amount = number_format($payment_amount, 2);

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
     * Payment failed
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function failedTransaction(Request $request)
    {
        Session::flash('error', 'Your transaction is failed');
        return redirect()->route('affiliate.payment');
    }

    /**
     * Payment cancel
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelTransaction(Request $request)
    {
        Session::flash('error', 'You have canceled the transaction.');
        return redirect()->route('affiliate.payment');
    }


}
