<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\AddonsOrder;
use App\Models\BuyModulus;
use App\Models\Headersetting;
use App\Models\MarchantPaymentGetway;
use App\Models\MarchantPaymentGetwayKYC;
use App\Models\Order;
use App\Models\OrderTransactionHistory;
use App\Models\Paymentgateway;
use App\Models\Paymenttoken;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;


class MarchantPaymentGetwayKYCController extends Controller
{

    public function amarpayCredentials(Request $request)
    {
        try {
            // Input validation rules
            $rules = array(
                'nid' => 'required|numeric',
                'nid_front' => 'required',
                'nid_back' => 'required',
                'current_bill_copy' => 'required',
                'tin' => 'string',
                'payment_gatway' => 'required',
            );

            // Input vaidation message
            $errorMessage = array(
                'nid.required' => 'NID is required.',
                'nid.numeric' => 'NID must be a number.',
                'nid_front.required' => 'NID Front Photo is required.',
                'nid_back.required' => 'NID Back Photo is required.',
                'current_bill_copy.required' => 'Current Bill Photo is required',
                'payment_gatway.required' => 'Invalid Request Send.',
            );

            // Validated all input
            $validator = Validator::make($request->all(), $rules, $errorMessage);

            // Check validation fails or pass
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $userData = getUserData();
                $store_id = $userData["store_id"];

                if (isset($request->payment_gatway) && !empty($request->payment_gatway) && $request->payment_gatway == "amarpay") {
                    if (!isset($request->tin) || empty($request->tin)) {
                        $validator->errors()->add('tin', "TIN is required.");
                        return redirect()->back()->withErrors($validator)->withInput();
                    }

                    if (!$request->hasFile('tin_image')) {
                        $validator->errors()->add('tin_image', "TIN image is required.");
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                }

                // nid_front Image validation check
                if ($request->file('nid_front')) {
                    $imageError = $this->inputImageValidation($request->file('nid_front'), $store_id);
                    if ($imageError) {
                        $validator->errors()->add('nid_front', $imageError);
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                }

                // nid_back Image validation check
                if ($request->file('nid_back')) {
                    $imageError = $this->inputImageValidation($request->file('nid_back'), $store_id);
                    if ($imageError) {
                        $validator->errors()->add('nid_back', $imageError);
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                }

                // current_bill_copy Image validation check
                if ($request->file('current_bill_copy')) {
                    $imageError = $this->inputImageValidation($request->file('current_bill_copy'), $store_id);
                    if ($imageError) {
                        $validator->errors()->add('current_bill_copy', $imageError);
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                }

                // tin_image Image validation check
                if ($request->file('tin_image')) {
                    $imageError = $this->inputImageValidation($request->file('tin_image'), $store_id);
                    if ($imageError) {
                        $validator->errors()->add('tin_image', $imageError);
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                }

                $marchantPaymentKYC = new MarchantPaymentGetwayKYC();
                $marchantPaymentKYC->store_id = $store_id;
                $marchantPaymentKYC->nid = $request->nid ?? "";
                $marchantPaymentKYC->dbid = $request->dbid ?? "";
                $marchantPaymentKYC->trade_licence = $request->trade_licence ?? "";
                $marchantPaymentKYC->tin = $request->tin ?? "";
                $marchantPaymentKYC->bin = $request->bin ?? "";
                $marchantPaymentKYC->bank_account_number = $request->bank_account_number ?? "";
                $marchantPaymentKYC->online_bank = $request->online_bank ?? "";
                $marchantPaymentKYC->payment_gatway = $request->payment_gatway ?? NULL;

                // nid_front Image upload
                if ($request->file('nid_front')) {
                    $marchantPaymentKYC->nid_front = $this->saveImage($request->file('nid_front'));
                }

                // nid_back Image upload
                if ($request->file('nid_back')) {
                    $marchantPaymentKYC->nid_back = $this->saveImage($request->file('nid_back'));
                }

                // current_bill_copy Image upload
                if ($request->file('current_bill_copy')) {
                    $marchantPaymentKYC->current_bill_copy = $this->saveImage($request->file('current_bill_copy'));
                }

                // dbid_front Image upload
                if ($request->file('dbid_front')) {
                    $marchantPaymentKYC->dbid_front = $this->saveImage($request->file('dbid_front'));
                }

                // dbid_back Image upload
                if ($request->file('dbid_back')) {
                    $marchantPaymentKYC->dbid_back = $this->saveImage($request->file('dbid_back'));
                }

                // trade_licence_image Image upload
                if ($request->file('trade_licence_image')) {
                    $marchantPaymentKYC->trade_licence_image = $this->saveImage($request->file('trade_licence_image'));
                }

                // tin_image Image upload
                if ($request->file('tin_image')) {
                    $marchantPaymentKYC->tin_image = $this->saveImage($request->file('tin_image'));
                }

                // bin_image Image upload
                if ($request->file('bin_image')) {
                    $marchantPaymentKYC->bin_image = $this->saveImage($request->file('bin_image'));
                }

                $marchantPaymentKYC->save();

                Session::flash("success", "Request is successfully submitted.");
                return redirect()->back();
            }
        } catch (Exception $e) {
            Session::flash("error", "Something went wrong. Try again");
            return redirect()->back();
        }

    }


    public function inputImageValidation($image, $store_id)
    {
        // Check image covert modules is active or not
        $imageModuleID = '107';
        $storeModulu = BuyModulus::where('modulus_id', $imageModuleID)->where('store_id', $store_id)->first();
        if (isset($storeModulu->status) && $storeModulu->status == 1) {
            $imageConvert = true;
        } else {
            $imageConvert = false;
        }


        $imgSize = $image->getSize();
        $imgSize = $imgSize / 1024;  // convert image size to kb

        // Check image converter module is active or not if active then check image size
        if ($imageConvert) {
            // Check image size if the size is greater than 600kb than throw an error.
            if ($imgSize > 6144) {
                $msg = "Media must be lower than or equal to 6MB!";
                return $msg;
            }
        } else {
            // Check image size if the size is greater than 200kb than throw an error.
            if ($imgSize > 200) {
                $msg = "Media must be lower than or equal to 200kb.";
                return $msg;
            }
        }


        // Check mimeType
        $mimeType = getMimeTypes();

        $imgExt = strtolower($image->getClientOriginalExtension());

        // Check input image mimeType
        if (!in_array($imgExt, $mimeType)) {
            return getMimeTypesValidationMessage();
        }

        return false;
    }


    public function saveImage($image)
    {
        $imageUploadPath = 'assets/images/kyc/';

        if ($image) {
            return uploadFile($image, $imageUploadPath);
        }

        return false;
    }


    public function amarpayKYCList($status = 0)
    {
        if (isset($status) && !empty($status)) {
            if ($status == 'pending') {
                $status = 0;
            } elseif ($status == 'reject') {
                $status = 2;
            }
        }

        $marchantPaymentKYC = MarchantPaymentGetwayKYC::with("store")->where('status', $status)->paginate(20);;

        return view("superadmin.amarpay.kyc-list", ['kycList' => $marchantPaymentKYC]);
    }


    public function amarpayKYCView($id)
    {
        try {
            if (!isset($id) || empty($id)) {
                return redirect()->back()->with("error", "ID missing!");
            }

            $marchantPaymentKYC = MarchantPaymentGetwayKYC::with("store")
                ->where("id", $id)
                ->first();

            if (isset($marchantPaymentKYC)) {
                return view("superadmin.amarpay.kyc-view", ['item' => $marchantPaymentKYC]);
            }

            return redirect()->back()->with("error", "Record not found!");
        } catch (\Exception $exception) {
            return redirect()->back()->with("error", "Something went wrong!");
        }
    }

    public function amarpayAcceptKYCView($id)
    {
        try {
            if (!isset($id) || empty($id)) {
                return redirect()->back()->with("error", "ID missing!");
            }
            $marchenPayment = MarchantPaymentGetway::where("id", $id)->first();

            if (isset($marchenPayment)) {
                $store_id = $marchenPayment->store_id;
                $payment_gatway = $marchenPayment->payment_gatway;
                $marchantPaymentKYC = MarchantPaymentGetwayKYC::with("store")
                    ->where('store_id', $store_id)
                    ->where('payment_gatway', $payment_gatway)
                    ->first();

                if (isset($marchantPaymentKYC)) {
                    return view("superadmin.amarpay.kyc-view", ['item' => $marchantPaymentKYC]);
                }
            }

            return redirect()->back()->with("error", "Record not found!");
        } catch (\Exception $exception) {
            return redirect()->back()->with("error", "Something went wrong!");
        }
    }


    public function amarpayKYCStatusChnage($id, $status)
    {
        try {
            if (!isset($id) || empty($id)) {
                return redirect()->back()->with("error", "ID missing!");
            } elseif (!isset($status) || empty($status)) {
                return redirect()->back()->with("error", "Status missing!");
            }

            if (isset($status) && !empty($status)) {
                if ($status == 'pending') {
                    $status = 0;
                } elseif ($status == 'active') {
                    $status = 1;
                } elseif ($status == 'reject') {
                    $status = 2;
                }
            }

            $marchantPaymentKYC = MarchantPaymentGetwayKYC::where('id', $id)->first();

            if (isset($marchantPaymentKYC)) {
                $marchantPaymentKYC->status = $status;
                $marchantPaymentKYC->save();

                $payment = $marchantPaymentKYC->payment_gatway;

                $marchenPayment = MarchantPaymentGetway::where("store_id", $marchantPaymentKYC->store_id)->where("payment_gatway", $payment)->first();
                if (!isset($marchenPayment)) {
                    $marchenPayment = new MarchantPaymentGetway();
                    $marchenPayment->store_id = $marchantPaymentKYC->store_id;
                    $marchenPayment->payment_gatway = $payment;
                }

                $marchenPayment->status = $status == 1 ? 1 : 0;
                $marchenPayment->save();

                return redirect()->back()->with("success", "Status successfully updated!");
            }

            return redirect()->back()->with("error", "Record not found!");
        } catch (\Exception $exception) {
            return redirect()->back()->with("error", "Something went wrong!");
        }
    }


    public function amarPayClientList(Request $request)
    {
        $search = $request->search ?? "";

        $query = MarchantPaymentGetway::with("store", "header");

        if (!is_null($search) && !empty($search)) {
            if (is_numeric($search)) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('store', function ($subQuery) use ($search) {
                        $subQuery->where('user_id', $search);
                    });
                });
            } else {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('store', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%$search%")
                            ->orWhere('url', 'like', "%$search%");
                    });

                    $q->orWhere('payment_gatway', 'like', "%$search%");
                });
            }
        }

        $marchantPaymentKYC = $query->orderBy('created_at', "DESC")->paginate(20);

        return view("superadmin.amarpay.client-list", ['kycList' => $marchantPaymentKYC, 'search' => $search]);
    }


}
