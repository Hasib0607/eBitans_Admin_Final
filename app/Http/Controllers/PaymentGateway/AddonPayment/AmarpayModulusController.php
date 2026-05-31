<?php

namespace App\Http\Controllers\PaymentGateway\AddonPayment;

use App\Http\Controllers\Controller;
use App\Models\AccountJournal;
use App\Models\AffiliateInfo;
use App\Models\AffiliatePayment;
use App\Models\Modulus;
use App\Models\ModulusPayment;
use App\Models\Store;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

class AmarpayModulusController extends Controller
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

        $this->apaySuccessURL = route('amarpay.modulus.successTransaction');
        $this->apayCancelURL = route('amarpay.modulus.cancelTransaction');
        $this->apayFailedURL = route('amarpay.modulus.failedTransaction');
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

            if ($modulus->price != $request->amount) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            $userData = getUserData();
            $store_id = $userData['store_id'] ?? "";
            $user = $userData['user'] ?? "";
            $amount = $request->amount;
            $total_product = $request->total_product ?? 0;
            $modulus_id = $modulus->id;

            $tran_id = uniqid();; // This is invoice id
            $currency_code = $this->currency_code ?? "BDT";

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
                "desc" => "Modulus Payment",
                "cus_name" => $user->name ?? "Unknow user",
                "cus_email" => $user->email ?? generateCustomEmail($user->name),
                "cus_add1" => $user->address ?? "Test Address",
                "cus_add2" => "",
                "cus_city" => "",
                "cus_state" => "",
                "cus_postcode" => "",
                "cus_country" => "Bangladesh",
                "cus_phone" => $user->phone ?? "01700000000",
                "opt_a" => $store_id,
                "opt_b" => $modulus_id,
                "opt_c" => $total_product,
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
                return redirect()->route("admin.modulus");
            }
        } catch (\Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', 'Something went wrong');
            return redirect()->route("admin.modulus");
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


    /**
     * Amar pay success transaction callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function successTransaction(Request $request)
    {
        $allRequest = $request->all();
        $store_id = $allRequest['opt_a'] ?? "";
        $modulus_id = $allRequest['opt_b'] ?? "";
        $total_product = $allRequest['opt_c'] ?? NULL;

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
                    $payment = new ModulusPayment();
                    $payment->modulus_id = $modulus_id;
                    $payment->store_id = $store_id;
                    $payment->payment_type = $payment_type;
                    $payment->number = $cardnumber;
                    $payment->price = $amountPaid;
                    $payment->transaction_id = $transactionID;
                    $payment->total_product = $total_product;
                    $payment->status = 1;
                    $payment->save();

                    Session::flash('payment', "Success");
                    Session::flash('success', 'Your payment has been successfully done');
                    Session::flash('transaction_id', $transactionID);
                    return redirect()->route("admin.modulus");
                }

            }

            Session::flash('payment', "failed");
            Session::flash('error', "Your transaction is failed.");
            return redirect()->route("admin.modulus");

        } catch (\Exception $e) {
            Session::flash('payment', "failed");
            Session::flash('error', 'Something went wrong');
            return redirect()->route("admin.modulus");
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
        return redirect()->route("admin.modulus");
    }

    /***
     * Payment cancel
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelTransaction(Request $request)
    {
        Session::flash('error', 'You have canceled the transaction.');
        return redirect()->route("admin.modulus");
    }


}
