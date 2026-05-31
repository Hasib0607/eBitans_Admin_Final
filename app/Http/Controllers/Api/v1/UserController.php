<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BuyModuleResource;
use App\Mail\OPTSendMail;
use App\Models\ProductAffiliateInfo;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Store;
use App\Models\Address;
use App\Models\Headersetting;
use App\Models\Brand;
use App\Models\BuyModulus;
use Illuminate\Support\Str;
use App\Models\Modulus;
use App\Rules\PhoneNumber;
use App\Models\Prereguser;
use App\Models\QuickLogin;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Propaganistas\LaravelPhone\Rules\Phone;

class UserController extends Controller
{
    public function updateuser(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->where('store_id', $request->store_id)->first();
        if (isset($user)) {
            $user->name = $request->name;
            $user->phone = $request->phone ?? $user->phone;
            $user->email = $request->email ?? $user->email;
            $user->address = $request->address;
            if ($request->image) {
                $img = substr($request->image, strpos($request->image, ",") + 1);
                $file = base64_decode($img);
                $safeName = Str::random(10) . '.' . 'png';

                $success = file_put_contents(public_path() . '/assets/images/img/' . $safeName, $file);
                $user->image = $safeName;
            }
            $user->save();
            return response()->json($user);
        } else {
            return response()->json(['error' => 'User Not found']);
        }
    }

    public function modules(Request $request)
    {
        $data['modules'] = BuyModulus::where('store_id', $request->store_id)->rightJoin('moduluses', 'moduluses.id', '=', 'buy_moduluses.modulus_id')
            ->select('buy_moduluses.id', 'buy_moduluses.store_id', 'moduluses.id as modulus_id', 'moduluses.name', 'buy_moduluses.price', 'buy_moduluses.type', 'buy_moduluses.start_date', 'buy_moduluses.end_date', 'buy_moduluses.sms_count', 'buy_moduluses.status')
            ->get();
        $data['QuickLogin'] = QuickLogin::where('store_id', $request->store_id)->get();

        return response()->json(['data' => $data], 200);

    }


    /**
     * Get all module info by store url
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModuleInfo(Request $request)
    {
        $name = $request->name ?? "";
        $store = Store::where('url', $name)->where('expiry_date', '>=', Carbon::now())->first();

        if (!is_null($store)) {
            $store_id = $store->id;
            $buyModulus = BuyModulus::with("module")->where('store_id', $store_id)->get();

            if (count($buyModulus) > 0) {
                return response()->json(["status" => true, 'message' => 'Success', 'data' => BuyModuleResource::collection($buyModulus)]);
            }
            return response()->json(["status" => false, 'message' => 'Module not found']);
        }

        return response()->json(["status" => false, 'message' => 'Store not found']);
    }


    /**
     * Get single module is active or inactive
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModuleById(Request $request, $id)
    {
        if (isset($id)) {
            $name = $request->name ?? "";
            $modulus_id = $id ?? "";
            $store = Store::where('url', $name)->where('expiry_date', '>=', Carbon::now())->first();

            if (!is_null($store)) {
                $store_id = $store->id;
                $buyModulus = BuyModulus::where('store_id', $store_id)->where('modulus_id', $modulus_id)->first();
                $modulus = Modulus::find($modulus_id);

                if (isset($modulus->status) && isset($buyModulus->status) && $modulus->status == 1 && $buyModulus->status == 1) {
                    return response()->json(["status" => true, 'message' => 'Module Active']);
                }
                return response()->json(["status" => false, 'message' => 'Module Inactive']);
            }

            return response()->json(["status" => false, 'message' => 'Store not found']);
        }

        return response()->json(["status" => false, 'message' => 'Module ID missing']);
    }

    public function userdetails(Request $request)
    {
        $user = User::where('id', $request->user_id)->where('store_id', $request->store_id)->first();
        if (isset($user)) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'User Not found']);
        }
    }

    public function changepass(Request $request)
    {
        if ($request->password == $request->confirm_password) {
            $user = User::where('id', Auth::user()->id)->where('store_id', $request->store_id)->first();
            if (isset($user)) {
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(['success' => 'Password Change Successfully']);
            } else {
                return response()->json(['error' => 'User Not found']);
            }
        } else {
            return response()->json(['error' => 'Password and Confirm Password Not Match']);
        }
    }

    public function address(Request $request)
    {
        $user = Auth::user();

        $order = Address::with("district")->where('uid', $user->id)->get();
        if (isset($order) && count($order) > 0) {
            foreach ($order as $key => $ord) {
                $ords[$key]['id'] = $ord->id ?? "";
                $ords[$key]['name'] = $ord->name ?? "";
                $ords[$key]['phone'] = $ord->phone ?? "";
                $ords[$key]['email'] = $ord->email ?? "";
                $ords[$key]['address'] = $ord->address ?? "";
                $ords[$key]['note'] = $ord->note ?? "";
                $ords[$key]['district'] = $ord->district ?? "";
            }
        } else {
            $ords = [];
        }
        return response()->json(['user' => $user, 'address' => $ords]);
    }

    public function saveaddress(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user_id = $user->id;
        } else {
            // User is not authenticated, create a new user
            $store = Store::find($request->store_id);
            if ($store->auth_type == 'EasyOrder') {
                $existing_user = User::where('phone', $request->phone)
                    ->where('type', 'customer')
                    ->where('store_id', $store->id)
                    ->first();

                if (empty($existing_user)) {
                    // Create new user
                    $user = new User;
                    $user->phone = $request->phone;
                    $code = sixDigitRandCode();
                    $pass = $store->name . "@" . $code;
                    $newpass = Hash::make($pass);
                    $user->password = $newpass;
                    $user->type = "customer";
                    $otp = sixDigitRandCode();
                    $user->otp = 'NULL';
                    $user->store_id = $store->id;
                    $user->auth_type = 'EasyOrder';
                    $user->customer_id = $store->customer_id;
                    $user->save();

                    $notificationData = [
                        "title" => "New customer register (" . ($user->name ?? '') . ") - " . formatDateWithTime($user->created_at),
                        "type" => "user_create",
                        "user_type" => "admin",
                        "store_id" => $store->id ?? NULL,
                    ];

                    if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                        createNotification($notificationData);
                    }

                    $text = "Thank You for register to " . $store->name . "  Your Login Details is Phone : " . $request->phone . " Password : " . $pass;

                    if (addonSmsCount($store->id) && isset($user->phone) && !empty($user->phone)) {
                        $smsresult = SendSms($user->phone, $text); // phone text
                        $p = explode("|", $smsresult);
                        $sendstatus = $p[0];

                        smsLogger($user->phone, $text, "Customer Registration Details", 0, $store->id);
                    }
                } else {
                    // Use existing user
                    $user = $existing_user;
                }
            } else {
                // Handle other authentication types if necessary
                return response()->json(['error' => 'User authentication failed'], 401);
            }

            // Retrieve user ID
            $user_id = $user->id;
        }

        // Now that we have the user ID, we can proceed with saving the address
        $ad = new Address();
        // Populate address fields
        $ad->name = $request->name ?? "";
        $ad->phone = $request->phone ?? "";
        $ad->email = $request->email ?? null;
        $ad->address = $request->address ?? "";
        $ad->district_id = $request->district ?? null;
        $ad->note = $request->note ?? null;
        $ad->uid = $user_id;
        $ad->save();

        $token['token'] = $user->createToken('AuthToken')->plainTextToken;
        $token['verify'] = 'true';

        return response()->json(['success' => 'Address Save', 'address' => $ad, 'token' => $token]);
    }


    public function updateaddress(Request $request)
    {
        $user = Auth::user();
        $user_id = Auth::user()->id;
        $ad = Address::where('id', $request->id)->where('uid', $user_id)->first();
        if (isset($ad)) {
            $ad->name = $request->name;
            $ad->phone = $request->phone;
            $ad->address = $request->address;
            $ad->district_id = $request->district ?? null;
            $ad->save();
            return response()->json(['success' => 'Address Update', 'address' => $ad]);
        } else {
            return response()->json(['error' => 'Error']);
        }
    }

    public function getBrand(Request $request)
    {
        $brand = Brand::where('store_id', $request->store_id)->get(['name', 'image']);
        if (isset($brand) && !empty($request->store_id)) {

            return response()->json(['status' => 200, 'brand' => $brand]);
        } else {
            return response()->json(['error' => 'Error! please provide valid store id']);
        }
    }

    public function deleteaddress(Request $request)
    {
        $user = Auth::user();
        $user_id = Auth::user()->id;
        $ad = Address::where('id', $request->id)->where('uid', $user_id)->first();
        if (isset($ad)) {
            $ad->delete();
            return response()->json(['success' => 'Address Delete Successfully']);
        } else {
            return response()->json(['error' => 'Error']);
        }
    }

    public function userinfo(Request $request)
    {
        $rules = array(
            'phone' => ['required', new PhoneNumber],
            'store_id' => ['required'],
        );
        $message = array(
            'phone.required' => "Phone number is required",
            'store_id.required' => "Store id is required",
        );

        $validation = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $message);

        if ($validation->fails()) {
            $errors = $validation->getMessageBag()->toArray();
            return response()->json(['message' => 'Validation error', 'errors' => $errors], 409);
        }

        $store = Store::find($request->store_id);
        $user = User::where('phone', $request->phone)->where('store_id', $request->store_id)->where(function ($q) use ($request) {
            $q->where('type', "customer")->orWhere('type', "customerAffiliate");
        })->first();
        if (isset($user)) {
            return response()->json(['message' => 'Already Registered'], 409);
        }
        $existuser = Prereguser::where('phone', $request->phone)->where('store_id', $request->store_id)->first();
        if (isset($existuser)) {
            $existuser->delete();
        }
        $otp = sixDigitRandCode();
        $pre = new Prereguser();
        $pre->phone = $request->phone;
        $code = sixDigitRandCode();
        $pass = $store->name . "@" . $code;
        $pre->password = $pass;
        $pre->store_id = $request->store_id;
        $pre->type = $request->type ?? "customer";
        $pre->otp = $otp;
        $token = encrypt($pass);
        $pre->token = $token;
        $pre->save();

        $text = $store->name . " OTP code is " . $pre->otp;

        if (addonSmsCount($store->id) && isset($pre->phone) && !empty($pre->phone)) {
            $smsresult = SendSms($pre->phone, $text); // phone, text

            smsLogger($pre->phone, $text, "OTP Send", 0, $store->id);
        }

        return response()->json(['message' => 'Success', 'token' => $token], 200);
    }

    public function userRegistrationEmail(Request $request)
    {
        $rules = array(
            'email' => ['required'],
            'password' => ['required'],
            'store_id' => ['required'],
        );

        $message = array(
            'email.required' => "Email is required",
            'password.required' => "Password is required",
            'store_id.required' => "Store id is required",
        );

        $validation = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $message);

        if ($validation->fails()) {
            $errors = $validation->getMessageBag()->toArray();
            return response()->json(['message' => 'Validation error', 'errors' => $errors], 409);
        }

        $store = Store::find($request->store_id);

        $user = User::where('email', $request->email)->where('store_id', $request->store_id)->where(function ($q) use ($request) {
            $q->where('type', "customer")->orWhere('type', "customerAffiliate");
        })->first();
        if (isset($user)) {
            return response()->json(['message' => 'Already Registered'], 409);
        }

        $existuser = Prereguser::where('email', $request->email)->where('store_id', $request->store_id)->first();
        if (isset($existuser)) {
            $existuser->delete();
        }

        $otp = sixDigitRandCode();
        $pre = new Prereguser();
        $pre->email = $request->email;
        $code = sixDigitRandCode();
        $pass = $request->password;
        $pre->password = $pass;
        $pre->store_id = $request->store_id;
        $pre->type = $request->type ?? "customer";
        $pre->otp = $otp;
        $token = encrypt($pass);
        $pre->token = $token;
        $pre->save();

        $text = $store->name . " OTP code is " . $pre->otp;

        $headersetting = Headersetting::where('store_id', $store->id)->first();

        if (isset($request->email)) {
            if (is_null($headersetting->email) || empty($headersetting->email)) {
                return response()->json(['status' => false, 'message' => 'Admin email not set yet'], 409);
            }

            $data['email'] = $request->email;
            $data['FormEmail'] = $headersetting->email;
//            $data['orderInfo'] = "Registration OTP code is - " . $pre->otp . " From " . $store->name .
//                "\nWe will get in touch with you shortly.\nFor Help:" . $headersetting->phone;

            $data["title"] = $store->name;

            $data["store_name"] = $store->name;
            $data["app_url"] = $store->url;
            $data["otp"] = $pre->otp;
            $data["help_number"] = $headersetting->phone ?? "";

            Mail::send('emailNotify.registrationOTP', $data, function ($message) use ($data) {
                $message->from($data['FormEmail'], $data["title"])->to($data["email"], $data["email"])
                    ->replyTo($data['FormEmail'], 'Support Team')
                    ->subject('Registration OTP');
            });
        }

        return response()->json(['message' => 'Success', 'token' => $token], 200);
    }

    public function checkotps(Request $request)
    {
        $Prereguser = Prereguser::where('token', $request->token)->first();
        if (isset($Prereguser) && $Prereguser->otp == $request->otp) {
            $store = Store::where('id', $Prereguser->store_id)->first();

            $registerType = "customer";
            if ($Prereguser->type == 'customer') {
                $registerType = "customer";
            } elseif ($Prereguser->type == 'customerAffiliate') {
                $registerType = "customerAffiliate";
            }


            $user = new User;
            $user->phone = $Prereguser->phone;
            $user->email = $Prereguser->email;
            $code = sixDigitRandCode();
            $pass = $Prereguser->password;
            $newpass = Hash::make($pass);
            $user->password = $newpass;
            $user->type = $registerType;
            $user->otp = 'NULL';
            $user->store_id = $store->id;
            $user->save();

            if ($user->type == 'customer') {
                $userType = "Customer";
            } elseif ($user->type == 'customerAffiliate') {
                $userType = "Affiliate Customer";
            }

            $notificationData = [
                "title" => "New user register as " . ucfirst($userType) . " (" . getUserNameOrPhone($user) . ") - " . formatDateWithTime($user->created_at),
                "type" => "user_create",
                "user_type" => "admin",
                "store_id" => $store->id ?? NULL,
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }

            $text = "Thank You for register to " . $store->name .
                "  Your Login Details is
            Phone : " . $user->phone .
                " Password : " . $pass;

            if (isset($Prereguser->email)) {
                $user->auth_type = 'email';

                $headersetting = Headersetting::where('store_id', $store->id)->first();

                if (isset($Prereguser->email)) {
                    if (is_null($headersetting->email) || empty($headersetting->email)) {
                        return response()->json(['status' => false, 'message' => 'Admin email not set yet'], 409);
                    }
                    $data['email'] = $Prereguser->email;
                    $data['FormEmail'] = $headersetting->email;
                    $data['orderInfo'] = $text .
                        "\nWe will get in touch with you shortly.\nFor Help:" . $headersetting->phone;

                    $data["title"] = "From " . $store->name;

                    Mail::send('clientOrderNotifyMail', $data, function ($message) use ($data) {
                        $message->from($data['FormEmail'], $data["title"])->to($data["email"], $data["email"])
                            ->subject('OTP ...');
                    });
                }
            } else {
                $user->auth_type = 'phone';
                if (addonSmsCount($store->id) && isset($user->phone) && !empty($user->phone)) {
                    $smsresult = SendSms($user->phone, $text); // phone, text
                    smsLogger($user->phone, $text, "Customer Registration Details", 0, $store->id);
                }
            }


            $Prereguser->delete();
            if ($user) {
                if ($registerType == "customerAffiliate") {
                    // Get visitor info by IP address
                    $visitorInfo = getVisitorInfo();

                    $info = new ProductAffiliateInfo();
                    $info->user_id = $user->id;
                    $info->store_id = $store->id;
                    $info->referral_code = Str::upper(Str::random(10));
                    if (isset($visitorInfo->countryCode) && $visitorInfo->countryCode !== "BD") {
                        $info->currency = 'USD';
                    }
                    $info->save();
                }

                $referralCode = null;
                $token = null;
                $verify = false;

                if ($user->otp == 'NULL') {
                    $verify = true;
                    $productAffiliateUser = ProductAffiliateInfo::where("user_id", $user->id)->first();

                    if ($user->type == "customerAffiliate") {
                        if (isset($productAffiliateUser) && $productAffiliateUser->status == 0) {
                            return response()->json(['token' => $token, 'verify' => $verify, 'referral' => $referralCode], 200);
                        }

                        if (isset($productAffiliateUser)) {
                            $referralCode = $productAffiliateUser->referral_code ?? null;
                        }
                    }

                    Auth::login($user);
                    $token = Auth::user()->createToken('AuthToken')->plainTextToken;

                    return response()->json(['token' => $token, 'verify' => $verify, 'referral' => $referralCode], 200);
                }

                return response()->json(['token' => $token, 'verify' => $verify, 'referral' => $referralCode], 200);
            } else {
                return response()->json(['success' => 'Registration Successfully, Please Login']);
            }
        }

        return response()->json(['error' => 'OTP Doesn"t Match'], 405);
    }


    public function rsendotps(Request $request)
    {
        $otp = sixDigitRandCode();
        $Prereguser = Prereguser::where('token', $request->token)->first();
        if (isset($Prereguser)) {
            $Prereguser->otp = $otp;
            $Prereguser->save();
            $store = Store::find($Prereguser->store_id);
            $text = $store->name . " OTP code is " . $Prereguser->otp;

            if (isset($Prereguser->email)) {
                $headersetting = Headersetting::where('store_id', $store->id)->first();

                if (is_null($headersetting->email) || empty($headersetting->email)) {
                    return response()->json(['status' => false, 'message' => 'Admin email not set yet'], 409);
                }

                $emailForm = $headersetting->email;
                $data['email'] = $Prereguser->email;
                $data['FormEmail'] = $headersetting->email;
                $data['orderInfo'] = $text .
                    "\nWe will get in touch with you shortly.\nFor Help:" . $headersetting->phone;

                $data["title"] = "From " . $store->name;

                Mail::send('clientOrderNotifyMail', $data, function ($message) use ($data) {
                    $message->from($data['FormEmail'], $data["title"])->to($data["email"], $data["email"])
                        ->subject('OTP ...');
                });
            } else {
                if (addonSmsCount($store->id) && isset($Prereguser->phone) && !empty($Prereguser->phone)) {
                    $smsresult = SendSms($Prereguser->phone, $text); // phone, text
                    smsLogger($Prereguser->phone, $text, "OTP Send", 0, $store->id);
                }
            }

            return response()->json(['message' => 'Success', 'token' => $request->token], 200);
        } else {
            return response()->json(['message' => 'User Not Found'], 200);
        }
    }

    public function registers(Request $request)
    {
        if (Str::is('*@*', $request->phone)) {
            if (!filter_var($request->phone, FILTER_VALIDATE_EMAIL)) {
                return [
                    "user" => false,
                    "message" => "Invalid email address."
                ];
            }

            $user = User::where('email', $request->phone)->where(function ($q) use ($request) {
                $q->where('type', "admin")->orWhere('type', "dropshipper");
            })->first();

            if (isset($user)) {
                return [
                    'user' => true,
                    "message" => "Email address already exists. Please login your account.",
                ];
            }
        } else {
            if (!preg_match('/^(?:\+?\d{1,3})?[1-9]\d{5,14}$/', $request->phone)) {
                return [
                    'user' => true,
                    "message" => "Invalid phone number.",
                ];
            }

            $user = User::where('phone', $request->phone)->where(function ($q) use ($request) {
                $q->where('type', "admin")->orWhere('type', "dropshipper");
            })->first();

            if (isset($user)) {
                return [
                    'user' => true,
                    "message" => "Phone number already exists. Please login your account.",
                ];
            }
        }

        $user = new User;
        $user->name = $request->name != null ? $request->name : '';
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = 'admin';
        $user->phone = $request->phone;
        $user->otp = "NULL";
        $user->offertime = $request->time != null ? Carbon::parse($request->time) : null;
        $user->save();

        $notificationData = [
            "title" => "New user register as " . ucfirst($user->type) . " (" . getUserNameOrPhone($user) . ") - " . formatDateWithTime($user->created_at),
            "type" => "user_create",
            "user_type" => "superadmin",
        ];

        if (isset($notificationData['title']) && !empty($notificationData['title'])) {
            createNotification($notificationData);
        }

        $customer = new Customer;
        $customer->uid = $user->id;
        $customer->phone = $user->phone;
        $customer->plan_id = "NULL";
        $customer->purchase_date = "NULL";
        $customer->active_store = "0";
        $customer->ref_code = Str::random(8);
        $customer->points = "200";
        $customer->save();

        return $user;
    }

    public function registerscheck(Request $request)
    {
        $isEmail = false;
        if (Str::is('*@*', $request->phone)) {
            if (!filter_var($request->phone, FILTER_VALIDATE_EMAIL)) {
                return [
                    "user" => false,
                    "message" => "Invalid email address."
                ];
            }
            $isEmail = true;

            $user = User::where('email', $request->phone)->where(function ($q) use ($request) {
                $q->where('type', "admin")->orWhere('type', "dropshipper");
            })->first();

            if (isset($user)) {
                return [
                    'user' => true,
                    "message" => "Email address already exists.",
                ];
            }
        } else {
            if (!preg_match('/^(?:\+?\d{1,3})?[1-9]\d{5,14}$/', $request->phone)) {
                return [
                    'user' => true,
                    "message" => "Invalid phone number.",
                ];
            }

            $user = User::where('phone', $request->phone)->where(function ($q) use ($request) {
                $q->where('type', "admin")->orWhere('type', "dropshipper");
            })->first();

            if (isset($user)) {
                return [
                    'user' => true,
                    "message" => "Phone number already exists. Please login your account.",
                ];
            }
        }

        if (isset($user)) {
            return [
                'user' => true
            ];
        } else {
            $text = "Ebitans OTP code is " . $request->code;
            if ($isEmail) {

                $data['name'] = $request->phone ?? "";
                $data['subject'] = "Registration";
                $data['text'] = $text;
                $data['formEmail'] = env('MAIL_FROM_ADDRESS');

                Mail::to($request->phone)->send(new OPTSendMail($data));
            } else {
                if (isset($request->phone) && !empty($request->phone)) {
                    SendSms($request->phone, $text); // phone, text

                    smsLogger($request->phone, $text, "OTP Send");
                }
            }

            return [
                'user' => false
            ];
        }
    }

}
