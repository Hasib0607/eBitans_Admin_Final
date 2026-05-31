<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Headersetting;
use App\Models\Paymenttoken;
use App\Models\ProductAffiliateInfo;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //  public $token = true;

    public function index123(Request $request)
    {
        $credentials = $request->only('phone', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            return response()->json($token);
        } else {
            return response()->json($credentials);
        }

        return response()->json(['error' => 'Unauthorized'], 401);

    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    public function getuser()
    {
        $user = User::with('affiliate_info')->find(Auth::id());
        return response()->json($user);
    }

    protected function authPayload(string $token, bool $verify, ?string $referralCode = null): array
    {
        return [
            'token' => $token,
            'access_token' => $token,
            'token_type' => 'bearer',
            'verify' => $verify,
            'referral' => $referralCode,
        ];
    }

    public function index(Request $request)
    {
        $credentials = $request->validate([
            'phone' => ['required'],
            'password' => ['required'],
            'store_id' => ['required']
        ]);

        $store = Store::find($request->store_id);

        if (!$store) {
            return response()->json(["status" => false, "error" => "Something wrong. Try again"], 422);
        }

        if (isset($store->auth_type) && $store->auth_type == 'EasyOrder') {
            $auth_type = 'EasyOrder';
        } else {
            $auth_type = $store->auth_type;
        }

        $user = User::where(function ($q) use ($request) {
            $q->where('phone', $request->phone)->orWhere("email", $request->phone);
        })->where('store_id', $request->store_id)->first();


        if (isset($user) && $user->type == "customerAffiliate") {
            $productAffiliateUser = ProductAffiliateInfo::where("user_id", $user->id)->first();

            if ($productAffiliateUser->status == 1) {
                if (!ModulusStatus($request->store_id, 120)) {
                    return response()->json(["status" => false, "error" => "Credential Doesn't Match"], 422);
                } else {
                    $auth_type = $user->auth_type;
                }
            } else {
                return response()->json(["status" => false, "error" => "Credential Doesn't Match"], 422);
            }
        }

        $authCheck = Auth::attempt(['phone' => $request->phone, 'password' => $request->password, 'store_id' => $request->store_id, 'auth_type' => $auth_type]) || Auth::attempt(['email' => $request->phone, 'password' => $request->password, 'store_id' => $request->store_id, 'auth_type' => $auth_type]);

        if ($authCheck) {
            // $token = Auth::user()->createToken('AuthToken')->accessToken;
            $token = Auth::user()->createToken('AuthToken')->plainTextToken;

            $user = Auth::user();
            if ($user->otp == 'NULL') {
                $verify = true;
                $payt = new Paymenttoken();
                $payt->token = $token;
                $payt->uid = $user->id;
                $payt->save();
            } else {
                $verify = false;
            }

            $referralCode = null;
            $productAffiliateUser = ProductAffiliateInfo::where("user_id", $user->id)->first();
            if (isset($productAffiliateUser)) {
                $referralCode = $productAffiliateUser->referral_code ?? null;
            }

            // return response()->json(['token' => $token->token, 'details' => $user], 200);
            return response()->json($this->authPayload($token, $verify, $referralCode), 200);
        } else {
            return response()->json(["status" => false, "error" => "Credential Doesn't Match"], 422);
        }
    }

    public function paymentlogin(Request $request)
    {
        $users = User::find($request->user_id);
        Auth::login($users);
        $token = Auth::user()->createToken('AuthToken')->plainTextToken;
        $user = Auth::user();
        if ($user->otp == 'NULL') {
            $verify = true;
        } else {
            $verify = false;
        }
        return response()->json([
            'token' => $token,
            'access_token' => $token,
            'token_type' => 'bearer',
            'verify' => $verify
        ]);
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            'phone' => ['required'],
            'store_id' => ['required'],
        ]);
        $user = User::where('phone', $request->phone)->where('store_id', $request->store_id)->first();
        if (isset($user)) {
            return response()->json(['error' => 'User Already Exist, Please Log In']);
        } else {
            $store = Store::where('id', $request->store_id)->first();
            $user = new User;
            $user->phone = $request->phone;
            $code = sixDigitRandCode();
            $pass = $store->name . "@" . $code;
            $newpass = Hash::make($pass);
            $user->password = $newpass;
            $user->type = "customer";
            $otp = sixDigitRandCode();
            $user->otp = $otp;
            $user->store_id = $store->id;
            $user->customer_id = $store->customer_id;
            $user->save();

            $notificationData = [
                "title" => "New customer register (" . ($user->phone ?? '') . ") - " . formatDateWithTime($user->created_at),
                "type" => "user_create",
                "user_type" => "admin",
                "store_id" => $store->id ?? NULL,
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }

            $text = ($store->name ?? "Your") . " OTP code is " . $user->otp;


            if (addonSmsCount($store->id) && isset($user->phone) && !empty($user->phone)) {
                $smsresult = SendSms($user->phone, $text); //phone , text
                $p = explode("|", $smsresult);
                $sendstatus = $p[0];

                smsLogger($user->phone, $text, "OTP Send", 0, $store->id);
            }


            $text = "Thank You for register to " . $store->name .
                "  Your Login Details is Phone : " . $user->phone .
                " Password : " . $pass;

            if (addonSmsCount($store->id) && isset($user->phone) && !empty($user->phone)) {
                $smsresult = SendSms($user->phone, $text);

                smsLogger($user->phone, $text, "Customer Registration Details", 0, $store->id);
            }

            //number, text
            // $p = explode("|",$smsresult);
            // $sendstatus = $p[0];
            if ($user) {
                $referralCode = null;

                Auth::login($user);
                // $token = Auth::user()->createToken('AuthToken')->accessToken;
                $token = Auth::user()->createToken('AuthToken')->plainTextToken;
                $user = Auth::user();
                if ($user->otp == 'NULL') {
                    $verify = true;
                } else {
                    $verify = false;
                }

                return response()->json($this->authPayload($token, $verify, $referralCode));
            } else {
                return response()->json(['success' => 'Registration Successfully, Please Login']);
            }
        }
    }

    public function verifyotp(Request $request)
    {
        $user_id = Auth::user()->id;
        $otp = $request->otp;
        $user = User::where('id', $user_id)->where('otp', $otp)->first();
        if (isset($user)) {
            $user->otp = 'NULL';
            $user->save();
            $verify = true;
            $users = Auth::user();

            $users->tokens()->where('id', $users->currentAccessToken()->id)->delete();
            $token = Auth::user()->createToken('AuthToken')->plainTextToken;
            return response()->json([
                'token' => $token,
                'access_token' => $token,
                'token_type' => 'bearer',
                'verify' => $verify
            ], 200);
        } else {
            return response()->json(['error' => 'Incorrect Otp']);
        }
    }

    public function forget(Request $request)
    {
        $phone = $request->phone;
        $store_id = $request->store_id;

        $user = User::where('phone', $phone)->where('store_id', $store_id)->first();

        if (empty($user)) {
            $user = User::where('email', $phone)->where('store_id', $store_id)->first();
        }

        if (isset($user)) {
            if ($user->auth_type == 'google' || $user->auth_type == 'facebook') {
                return response()->json(['error' => 'You can not forget password Because your are login for social.']);
            }

            $otp = sixDigitRandCode();
            $user->otp = $otp;
            $user->save();

            $store = Store::find($request->store_id);
            $text = ($store->name ?? "Your") . " OTP code is " . $user->otp;
            $headersetting = Headersetting::where('store_id', $store_id)->first();

            if ($user->auth_type == 'phone' || $user->auth_type == 'EasyOrder') {
                if (addonSmsCount($store->id) && isset($user->phone) && !empty($user->phone)) {
                    $smsresult = SendSms($user->phone, $text);

                    smsLogger($user->phone, $text, "OTP Send", 0, $store->id);
                }
                // phone text
            } else {
                if (isset($user->email)) {
                    $data['email'] = $user->email;
                    $data['orderInfo'] = $text .
                        "\nWe will get in touch with you shortly.\nFor Help:" . $headersetting->phone;

                    $data["title"] = "From " . $store->name;

                    Mail::send('clientOrderNotifyMail', $data, function ($message) use ($data) {
                        $message->from('orderinfo@ebitans.com', $data["title"])->to($data["email"], $data["email"])
                            ->subject('OTP ...');
                    });
                }
            }

            return response()->json(['user_id' => $user->id]);
        } else {
            return response()->json(['error' => 'Incorrect information'], 405);
        }
    }

    public function forgetverify(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user->otp == $request->otp) {
            $user->otp = "NULL";
            $user->save();
            $verify = true;

            return response()->json(['user_id' => $user->id, 'verify' => $verify, 'success' => 'Successfully Verified']);
        } else {
            $verify = false;
            return response()->json(['error' => 'Otp Not Match Please try again', 'verify' => $verify]);
        }
    }

    public function changepass(Request $request)
    {
        // return response()->json(Auth::user());
        $pass = $request->password;
        $confirm_password = $request->confirm_password;
        if ($pass == $confirm_password) {
            $user = User::find($request->user_id);

            if (isset($user)) {
                $user->password = Hash::make($pass);
                $user->save();
                return response()->json(['success' => 'Password Change Successfully']);
            } else {
                return response()->json(['error' => 'User Not Found']);
            }

        } else {
            return response()->json(['error' => 'Password and Confirm Password not match.']);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user(); //or Auth::user()
        // Revoke current user token
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return response()->json(['success' => 'Successfully Logout']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user()
        ]);
    }
}
