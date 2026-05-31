<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Util\BkashCredential;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;

class BkashSandboxVerificationController extends Controller
{
    /**
     * Create bkash payment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function verification(Request $request)
    {
        $rules = array(
            'app_key' => 'required',
            'app_secret' => 'required',
            'username' => 'required',
            'password' => 'required',
        );
        $message = array(
            'app_key.required' => 'App Key is required.',
            'app_secret.required' => 'App Secret Key is required.',
            'username.required' => 'Username name is required.',
            'password.required' => 'Password name is required.',
        );
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()->first()]);
        }

        Config::set('bkash.sandbox', true);
        Config::set('bkash.bkash_app_key', $request->app_key);
        Config::set('bkash.bkash_app_secret', $request->app_secret);
        Config::set('bkash.bkash_username', $request->username);
        Config::set('bkash.bkash_password', $request->password);
        Config::set('bkash.callbackURL', route('bkash.sandbox.verification.callback'));

        $callBackURL = config("bkash.callbackURL");

        $amount = $request->amount ?? 10;

        $inv = uniqid();
        $request['intent'] = 'sale';
        $request['mode'] = '0011'; //0011 for checkout
        $request['payerReference'] = $inv;
        $request['currency'] = 'BDT';
        $request['amount'] = $amount;
        $request['merchantInvoiceNumber'] = $inv;
        $request['callbackURL'] = $callBackURL;

        $request_data_json = json_encode($request->all());

        $response = BkashPaymentTokenize::cPayment($request_data_json);

        $milisecond = 5000;
        setcookie('BSandBoxCreatePayment', json_encode($response), time() + ($milisecond), "/");

        if (isset($response['bkashURL'])) return redirect()->away($response['bkashURL']);
        else return response()->json(["status" => false, "message" => $response['statusMessage']]);
    }


    /**
     * Sandbox callback
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function callBack(Request $request)
    {
        try {
            if ($request->status == 'success') {

                $createPayment = Cookie::get('BSandBoxCreatePayment');
                Cookie::forget('BSandBoxCreatePayment');

                // Create a DateTime object for the current time in the specified timezone (GMT+0600)
                $timezone = new \DateTimeZone('Asia/Dhaka'); // GMT+0600
                $date = new \DateTime('now', $timezone);
                $formattedDate = $date->format('Y-m-d\TH:i:s:v T'); // v is milliseconds, T is timezone abbreviation

                $amount = $createPayment->amount ?? 10;
                $currency = $createPayment->currency ?? "BDT";
                $intent = $createPayment->intent ?? "sale";
                $merchantInvoiceNumber = $createPayment->merchantInvoiceNumber ?? "67349229b456a";
                $paymentCreateTime = $formattedDate ?? $createPayment->paymentCreateTime;

                $data = [
                    "paymentID" => $request->paymentID,
                    "trxID" => "BKD70KWW17",
                    "transactionStatus" => "Completed",
                    "amount" => $amount,
                    "currency" => $currency,
                    "intent" => $intent,
                    "paymentExecuteTime" => $paymentCreateTime,
                    "merchantInvoiceNumber" => $merchantInvoiceNumber,
                    "payerType" => "Customer",
                    "payerReference" => "67349229b456a",
                    "customerMsisdn" => "01770618575",
                    "payerAccount" => "01770618575",
                    "statusCode" => "0000",
                    "statusMessage" => "Successful"
                ];

                return view("payment.bkash.pay", [
                    "status" => "success",
                    "createPayment" => $createPayment,
                    "executePayment" => json_encode($data)
                ]);

            }

            return view("payment.bkash.pay", ["status" => "error"]);
        } catch (Exception $e) {
            return view("payment.bkash.pay", ["status" => "error"]);
        }

    }


}
