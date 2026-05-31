<?php

namespace App\Http\Controllers;

use App\Mail\OPTSendMail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Refcodeuse;
use App\Models\Resetotp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OtpverifyController extends Controller
{

    /**
     *
     * Verify OTP page show. If OTP not set in session then redirect to login page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verify()
    {
        if (Session::has('regid')) {
            return view('auth.checkotp');
        } else {
            return redirect('/login');
        }
    }


    /**
     *
     * Verify OTP Code and create user
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkverify(Request $request)
    {
        if (Session::has('regid') && (Session::has('phone') || Session::has('email')) && Session::has('code')) {
            if (Session::get('code') == $request->code) {

                $name = Session::get('name');
                $email = Session::get('email');
                $phone = Session::get('phone');
                $password = Session::get('password');
                $code = Session::get('code');
                $referral = Session::get('referral') ?? null;
                $user_type = Session::get('user_type') ?? 'admin';

                if (!empty($email)) {
                    $isEmail = true;
                } elseif (!empty($phone)) {
                    $isEmail = false;
                } else {
                    Session::flash('message', 'Try again');
                    return redirect('/login');
                }

                // user find in the database
                $user = User::where(function ($q) use ($phone, $email, $isEmail) {
                    if ($isEmail) {
                        $q->where('email', $email);
                    } else {
                        $q->where('phone', $phone);
                    }
                })->first();


                if (isset($user)) {
                    if ($isEmail) {
                        $errorMsg = 'This Email Already Registered';

                        if ($user->type == 'admin') {
                            $errorMsg = 'This Email Already Registered as Admin';
                        } elseif ($user->type == 'affiliate') {
                            $errorMsg = 'This Email Already Registered as Affiliate';
                        } elseif ($user->type == 'superadmin') {
                            $errorMsg = 'This Email Already Registered';
                        } elseif ($user->type == 'dropshipper') {
                            $errorMsg = 'This Email Already Registered as Drop Shipper';
                        }
                    } else {
                        $errorMsg = 'This Phone Number Already Registered';

                        if ($user->type == 'admin') {
                            $errorMsg = 'This Phone Number Already Registered as Admin';
                        } elseif ($user->type == 'affiliate') {
                            $errorMsg = 'This Phone Number Already Registered as Affiliate';
                        } elseif ($user->type == 'superadmin') {
                            $errorMsg = 'This Phone Number Already Registered';
                        }
                    }

                    Session::flash('message', $errorMsg);
                    return redirect('/login');
                } else {
                    // create user
                    $host = request()->getHost();
                    $cleanHost = $host ? preg_replace('/^www\./', '', $host) : NULL;

                    $user = new User();
                    $user->name = $name;
                    $user->email = $email ?? NULL;
                    $user->password = Hash::make($password);
                    $user->type = $user_type;
                    $user->phone = $phone ?? NULL;
                    $user->referral = Carbon::now()->timestamp . sixDigitRandCode();
                    $user->refer_by = $referral;
                    $user->otp = "NULL";
                    $user->register_from = $cleanHost;
                    $user->save();

                    $notificationData = [
                        "title" => "New user register as " . ucfirst($user->type) . " (" . getUserNameOrPhone($user) . ") - " . formatDateWithTime($user->created_at),
                        "type" => "user_create",
                        "user_type" => "superadmin",
                    ];

                    if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                        createNotification($notificationData);
                    }

                    if ($user_type == "admin" || $user_type == "dropshipper" || $user_type == "affiliate" || $user_type == "superstaff") {
                        $linkURL = NULL;
                        $notificationData = [
                            "title" => "New User register as " . ucfirst($user->type) . " (" . getUserNameOrPhone($user) . ") - " . formatDateWithTime($user->created_at),
                            "type" => "user_create",
                            "user_type" => "superadmin",
                            "link" => $linkURL,
                        ];

                        if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                            createNotification($notificationData);
                        }
                    }

                    if ($user_type == "admin" || $user_type == "dropshipper") {
                        $customer = new Customer();
                        $customer->uid = $user->id;
                        $customer->phone = $user->phone;
                        $customer->plan_id = "NULL";
                        $customer->purchase_date = "NULL";
                        $customer->active_store = "0";
                        $customer->ref_code = Str::random(8);
                        $customer->points = "200";
                        $customer->save();

                        $cuss = Customer::where('ref_code', $referral)->first();
                        if (isset($cuss)) {
                            $cuss->points = $cuss->points + 200;
                            $cuss->save();
                            $ref = new Refcodeuse();
                            $ref->user = $user->id;
                            $ref->code = $ref;
                            $ref->point = "200";
                            $ref->type = "register";
                            $ref->save();
                        }
                    }

                    if (Session::has('regid')) {
                        Session::forget('regid');
                    }
                    if (Session::has('name')) {
                        Session::forget('name');
                    }
                    if (Session::has('email')) {
                        Session::forget('email');
                    }
                    if (Session::has('password')) {
                        Session::forget('password');
                    }
                    if (Session::has('phone')) {
                        Session::forget('phone');
                    }
                    if (Session::has('ref')) {
                        Session::forget('ref');
                    }
                    if (Session::has('user_type')) {
                        Session::forget('user_type');
                    }
                    if (Session::has('country_code')) {
                        Session::forget('country_code');
                    }
                    event(new Registered($user));

                    Auth::login($user);

                    return redirect('/');
                }
            } else {
                return back();
            }
        } else {
            return redirect('/login');
        }
    }

    public function getcode()
    {
        $name = Session::get('name') ?? "";
        $email = Session::get('email') ?? "";
        $phone = Session::get('phone') ?? "";
        if (Session::has('code')) {
            Session::forget('code');
        }

        $code = sixDigitRandCode();
        Session::put('code', $code);

        $text = "Ebitans OTP code is <span style='font-size: 24px;'>" . $code . "</span>";

        if (!empty($email)) {
            $isEmail = true;
        } elseif (!empty($phone)) {
            $isEmail = false;
        } else {
            Session::flash('message', 'Try again');
            return redirect()->back();
        }

        if ($isEmail) {
            $data['name'] = $name;
            $data['subject'] = "Registration";
            $data['text'] = $text;
            $data['formEmail'] = env('MAIL_FROM_ADDRESS');

            Mail::to($email)->send(new OPTSendMail($data));
            Session::flash("send_message", "OTP send to your email");
        } else {
            $smsresult = SendSms($phone, $text);  //Number, text

            $p = explode("|", $smsresult);
            $sendstatus = $p[0];
            smsLogger($phone, $text, "OTP Send");

            Session::flash("send_message", "OTP send to your phone number");
        }

        return back();
    }

    public function fpotp()
    {
        if (Session::has('st')) {
            if (Session::get('st') == "0") {
                // if(Session::get('exp-min')>Carbon::now()){
                return view('admin.fpotp');
                // }else{
                //     return redirect('/login');
                // }
            }
        } else {
            return redirect('/login');
        }
//        Session::flash('message', 'Your OTP is incorrect.');
        return view('admin.fpotp');
    }


    /***
     *
     * Send OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Random\RandomException
     */
    public function sendotpfp(Request $request)
    {
        if (empty($request->resend)) {
            # code...
            $request->validate([
                'mathcaptcha' => 'required|mathcaptcha'
            ], [
                'mathcaptcha' => 'Fill up this CAPTCHA Number'
            ]);
        }


        if (Session::has('otpSendCount')) {
            $otpSendCount = Session::get('otpSendCount') + 1;

            $to = Carbon::createFromFormat('Y-m-d H:s:i', Session::get('timeLestOtp'));
            $from = Carbon::createFromFormat('Y-m-d H:s:i', now()->format('Y-m-d H:s:i'));
            $diff_in_hours = $to->diffInHours($from);


            if (Session::get('otpSendCount') > 3 && $diff_in_hours == 0) {
                # code...
                Session::flash('message', 'OTP send max time please wait 60 min');
                return back();
            } elseif (Session::get('otpSendCount') >= 2 && $diff_in_hours > 0) {
                Session::put('otpSendCount', 0);
                Session::put('timeLestOtp', now()->format('Y-m-d H:s:i'));
            }

        }

        $country_code = $request->country_code ?? "BD";

        $now = Carbon::now();
        $code = sixDigitRandCode();
        $expmin = $now->addMinutes(2);

        if (isset($request->resend) && $request->resend == true) {
            if (!empty(Session::get('email'))) {
                $isEmail = true;
            } elseif (!empty(Session::get('number'))) {
                $isEmail = false;
            } else {
                Session::flash('message', 'Try again');
                return redirect()->route('fpotp');
            }
        } else {
            $emailOrPhone = $request->email_or_phone ?? "";

            if (is_numeric($emailOrPhone)) {
                $isEmail = false;
            } else {
                $isEmail = true;
            }

            $rules = [
                'email_or_phone' => ['required'],
            ];

            if ($isEmail) {
                $rules['email_or_phone'][] = 'email';
            } else {
                $rules['email_or_phone'][] = 'phone:' . $country_code; // Basic phone validation

                \Illuminate\Support\Facades\Validator::extend('email_or_phone', function ($attribute, $value, $parameters, $validator) {
                    $country = $parameters[0] ?? 'the country';
                    return phone($value, [$country]); // This uses the phone validation logic
                }, 'The phone number must be a valid phone number for :country.');

                \Illuminate\Support\Facades\Validator::replacer('phone', function ($message, $attribute, $rule, $parameters) use ($country_code) {
                    $countryName = getCountryName($country_code);
                    return str_replace(':country', $countryName, $message);
                });
            }

            $message = [
                'email_or_phone.required' => 'Email/Phone is required.',
                'email_or_phone.email' => 'Enter a valid email address.',
            ];

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $message);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return redirect()->back()->withErrors($errors)->withInput();
            }

            if (!$isEmail) {
                // Parse the phone number to get only the local number (without country code)
                $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                $parsedNumber = $phoneUtil->parse($emailOrPhone, $country_code);
                $emailOrPhone = $phoneUtil->getNationalSignificantNumber($parsedNumber);
                if ($country_code == "BD") {
                    $emailOrPhone = '0' . $emailOrPhone;
                }
            }
        }

        $reqEmail = "";
        $reqPhone = "";

        if ($isEmail) {
            $reqEmail = $emailOrPhone ?? Session::get('email');
            $errorMsg = "Email Address Not Found";
        } else {
            $reqPhone = $emailOrPhone ?? Session::get('number');
            $errorMsg = "Phone Number Not Found";
        }

        $user = User::where(function ($q) use ($reqEmail, $reqPhone, $isEmail) {
            if ($isEmail) {
                $q->where('email', $reqEmail);
            } else {
                $q->where('phone', $reqPhone);
            }
        })->where(function ($q) {
            $q->where('type', 'admin')->orWhere('type', 'affiliate')->orWhere('type', 'superadmin')->orWhere('type', 'dropshipper');
        })->first();


        if (isset($user)) {
            if ($isEmail) {
                $data = Resetotp::where('email', $reqEmail)->first();
            } else {
                $data = Resetotp::where('phone', $reqPhone)->first();
            }

            if (isset($data)) {
                $dt = Resetotp::find($data->id);
                $dt->code = $code;
                $dt->status = "0";
                $dt->token = $request->token;
                $dt->start_min = $now;
                $dt->exp_min = $expmin;
                $dt->save();
                Session::put('number', $reqPhone);
                Session::put('email', $reqEmail);
                Session::put('exp-min', $expmin);
                Session::put('st', $dt->status);
            } else {
                $dt = new Resetotp();
                $dt->phone = $reqPhone;
                $dt->email = $reqEmail;
                $dt->code = $code;
                $dt->status = "0";
                $dt->token = $request->token;
                $dt->start_min = $now;
                $dt->exp_min = $expmin;

                $dt->save();
                Session::put('number', $reqPhone);
                Session::put('email', $reqEmail);
                Session::put('exp-min', $expmin);
                Session::put('st', $dt->status);
            }

            $text = "Ebitans Password Reset OTP code is " . $code;

            if ($isEmail) {
                $data['name'] = $user->name ?? "";
                $data['subject'] = "Registration";
                $data['text'] = $text;
                $data['formEmail'] = env('MAIL_FROM_ADDRESS');

                Mail::to($reqEmail)->send(new OPTSendMail($data));
                Session::flash("send_message", "OTP send to your email");
            } else {
                $smsresult = SendSms($reqPhone, $text); //number, text
                $p = explode("|", $smsresult);
                $sendstatus = $p[0];

                smsLogger($reqPhone, $text, "OTP Send");

                Session::flash("send_message", "OTP send to your phone number");
            }

            if (Session::has('otpSendCount')) {
                $otpSendCount = Session::get('otpSendCount') + 1;
                Session::put('otpSendCount', $otpSendCount);
                Session::put('timeLestOtp', now()->format('Y-m-d H:i:s'));
            } else {
                Session::put('otpSendCount', 1);
                Session::put('timeLestOtp', now()->format('Y-m-d H:i:s'));
            }

            return redirect()->route('fpotp');
        } else {
            Session::flash('message', $errorMsg ?? 'credentials Not Found');
            return back()->withInput();
        }
    }


    /**
     *
     * Display OTP Verify page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function GEtverifyfpotp()
    {
        if (Session::has('number') || Session::has('email')) {
            if (!empty(Session::get('email'))) {
                $isEmail = true;
            } elseif (!empty(Session::get('number'))) {
                $isEmail = false;
            } else {
                Session::flash('message', 'Try again');
                return redirect()->back();
            }

            if ($isEmail) {
                $data = Resetotp::where('email', Session::get('email'))->first();
            } else {
                $data = Resetotp::where('phone', Session::get('number'))->first();
            }

            if (isset($data->status) && $data->status == '1') {
                return view('admin.passwordchange');
            }
        }
    }


    /**
     *
     * Verify OTP
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function verifyfpotp(Request $request)
    {
        if (Session::has('number') || Session::has('email')) {

            if (!empty(Session::get('email'))) {
                $isEmail = true;
            } elseif (!empty(Session::get('number'))) {
                $isEmail = false;
            } else {
                Session::flash('message', 'Try again');
                return redirect()->route('fpotp');
            }

            $user = User::where(function ($q) use ($isEmail) {
                if ($isEmail) {
                    $q->where('email', Session::get('email'));
                } else {
                    $q->where('phone', Session::get('number'));
                }
            })->where(function ($q) {
                $q->where('type', 'admin')->orWhere('type', 'affiliate')->orWhere('type', 'superadmin')->orWhere('type', 'dropshipper');
            })->first();

            if (isset($user)) {
                if ($isEmail) {
                    $data = Resetotp::where('email', Session::get('email'))->first();
                } else {
                    $data = Resetotp::where('phone', Session::get('number'))->first();
                }
                if (isset($data)) {
                    if ($data->exp_min >= Carbon::now()) {
                        if ($data->code == $request->otp) {
                            $data->status = "1";
                            $data->save();
                            return view('admin.passwordchange');
                        } else {
                            return back();
                        }
                        return back();
                    }

                    return back();

                }
                return back();
            }

            return redirect()->route('login');
        }
        return redirect()->route('login');
    }


    /**
     *
     * Change password function
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changepass(Request $request)
    {
        if ($request->password == $request->password_confirmation) {
            if (Session::has('number') || Session::has('email')) {

                if (!empty(Session::get('email'))) {
                    $isEmail = true;
                } elseif (!empty(Session::get('number'))) {
                    $isEmail = false;
                } else {
                    Session::flash('message', 'Try again');
                    return redirect()->route('login');
                }


                $user = User::where(function ($q) use ($isEmail) {
                    if ($isEmail) {
                        $q->where('email', Session::get('email'));
                    } else {
                        $q->where('phone', Session::get('number'));
                    }
                })->where(function ($q) {
                    $q->where('type', 'admin')->orWhere('type', 'affiliate')->orWhere('type', 'superadmin')->orWhere('type', 'dropshipper');
                })->first();

                if (isset($user)) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    if (Session::has('number')) {
                        Session::forget('number');
                    }
                    if (Session::has('email')) {
                        Session::forget('email');
                    }
                    if (Session::has('st')) {
                        Session::forget('st');
                    }

                    Session::flash('message', 'Successfully Password Update');
                    return redirect()->route('login');
                }

                Session::flash('error', 'Something Error');
                return redirect()->route('login');

            } else {
                Session::flash('error', 'Something Error');
                return redirect()->route('login');
            }
        }

        Session::flash('error', 'Password Does Not Match');
        return redirect()->route('submitfpotp.agin')->with('error', 'Password Does Not Match');
    }
}
