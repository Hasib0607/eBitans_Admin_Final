<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OPTSendMail;
use App\Models\User;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use App\Models\Refcodeuse;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Session;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $data = array();

        if (isset($request->referral)) {
            if (!empty($request->referral)) {
                $ref = User::where('referral', $request->referral)->first();

                $data['referral'] = $request->referral;

                if (is_null($ref)) {
                    Session::flash('error', 'Invalid Referral no');
                    return view('admin.sign-up', $data)->with('error', 'Invalid Referral no');
                }
            }
        }

        return view('admin.sign-up', $data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        if (isset($request->referral)) {
            if (!empty($request->referral)) {
                $ref = User::where('referral', $request->referral)->first();

                if (is_null($ref)) {
                    Session::flash('error', 'Invalid Referral no');
                    $data['referral'] = $request->referral;
                    return view('admin.sign-up', $data)->with('error', 'Invalid Referral no');
                }
            }
        }

        if (is_numeric($request->email_or_phone)) {
            $isEmail = false;
        } else {
            $isEmail = true;
        }
        $country_code = $request->country_code ?? "BD";

        $rules = [
            'email_or_phone' => ['required'],
            'user_type' => ['required', 'string'],
            'mathcaptcha' => ['required', 'mathcaptcha'],
            'password' => ['required', 'string'],
        ];

        if ($isEmail) {
            $rules['email_or_phone'][] = 'email';
        } else {
            $rules['email_or_phone'][] = 'phone:' . $country_code; // Basic phone validation

            Validator::extend('email_or_phone', function ($attribute, $value, $parameters, $validator) {
                $country = $parameters[0] ?? 'the country';
                return phone($value, [$country]); // This uses the phone validation logic
            }, 'The phone number must be a valid phone number for :country.');

            Validator::replacer('phone', function ($message, $attribute, $rule, $parameters) use ($country_code) {
                $countryName = getCountryName($country_code);
                return str_replace(':country', $countryName, $message);
            });
        }

        $message = [
            'email_or_phone.required' => 'Email/Phone is required.',
            'user_type.required' => 'User type is required.',
            'mathcaptcha' => 'Fill up this CAPTCHA Number',
            'password.required' => 'Password is required.',
            'email_or_phone.email' => 'Enter valid email address.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($isEmail) {
            if (!filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)) {
                $validator->getMessageBag()->add('email_or_phone', "Invalid email address.");
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            // Parse the phone number to get only the local number (without country code)
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $parsedNumber = $phoneUtil->parse($request->email_or_phone, $country_code);
            $request->email_or_phone = $phoneUtil->getNationalSignificantNumber($parsedNumber);
            if ($country_code == "BD") {
                $request->email_or_phone = '0' . $request->email_or_phone;
            }
        }

        // user find in the database
        $user = User::where(function ($q) use ($request, $isEmail) {
            if ($isEmail) {
                $q->where('email', $request->email_or_phone);
            } else {
                $q->where('phone', $request->email_or_phone);
            }
        })->where(function ($q) {
            $q->where('type', "admin")
                ->orWhere('type', "affiliate")
                ->orWhere('type', "superadmin")
                ->orWhere('type', "dropshipper");
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

            Session::flash('error', $errorMsg);
            return back()->with('error', $errorMsg)->withInput();
        }


        $code = sixDigitRandCode();

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
        if (Session::has('code')) {
            Session::forget('code');
        }
        if (Session::has('referral')) {
            Session::forget('referral');
        }
        if (Session::has('user_type')) {
            Session::forget('user_type');
        }

        Session::put('regid', $code);
        Session::put('name', $request->name ?? "");
        Session::put('password', $request->password);
        Session::put('code', $code);
        Session::put('referral', $request->referral ?? null);
        Session::put('user_type', $request->user_type);
        if ($isEmail) {
            Session::put('email', $request->email_or_phone ?? "");
            Session::put('phone', "");
        } else {
            $email_or_phone = $request->email_or_phone;
            if (strtoupper($country_code) == "BD" && is_numeric($email_or_phone) && strlen($email_or_phone) == 10) {
                $email_or_phone = '0' . $email_or_phone;
            }
            Session::put('phone', $email_or_phone ?? "");
            Session::put('email', "");
        }

        
        

        if ($isEmail) {
            $text = "eBitans OTP code is <span style='font-size: 24px;'>" . $code . "</span>";
            $data['name'] = $request->name;
            $data['subject'] = "Registration";
            $data['text'] = $text;
            $data['formEmail'] = env('MAIL_FROM_ADDRESS');

            Mail::to($request->email_or_phone)->send(new OPTSendMail($data));
            \Illuminate\Support\Facades\Session::flash("send_message", "OTP send to your email");
        } else {
            $text = "eBitans OTP code is " . $code;
            $smsresult = SendSms($request->email_or_phone, $text);  //Number, text
            $p = explode("|", $smsresult);
            $sendstatus = $p[0];

            smsLogger($request->email_or_phone, $text, "OTP Send");

            \Illuminate\Support\Facades\Session::flash("send_message", "OTP send to your phone number");
        }

        return redirect()->route('checkotp');
    }
}
