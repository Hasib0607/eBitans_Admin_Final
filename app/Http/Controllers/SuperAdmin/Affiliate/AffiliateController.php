<?php

namespace App\Http\Controllers\SuperAdmin\Affiliate;


use App\Http\Controllers\Controller;
use App\Models\AffiliateFAQ;
use App\Models\AffiliateQuestionAnswer;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Customer;
use App\Models\Toptool;
use App\Models\Store;
use App\Models\AffiliateQuestion;
use Carbon\Carbon;
use DB;
use App\Models\AffiliateExamInfo;
use App\Models\AffiliateInfo;
use App\Models\AffiliatePayment;
use App\Models\AffiliateBalance;
use Illuminate\Support\Facades\Auth;
use App\Models\Referral;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AffiliateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     *
     * Display affiliate dashboard
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $affiliateExamInfo = AffiliateExamInfo::where('user_id', '=', Auth::id())->first();

        if (is_null($affiliateExamInfo)) {
            $AffiliateExamInfo = new AffiliateExamInfo();
            $AffiliateExamInfo->user_id = Auth::id();
            $AffiliateExamInfo->save();

            return redirect()->route('affiliate.faq');
        } elseif ($affiliateExamInfo && $affiliateExamInfo->answer_submitted_at == null) {
            return redirect()->route('affiliate.faq');
        }

        $examStatus = isset($affiliateExamInfo) ? $affiliateExamInfo : false;

        if ($affiliateExamInfo && $affiliateExamInfo->user_status == null) {
            return redirect()->route('affiliate.result');
        } else {
            $paymentStatus = AffiliatePayment::where('user_id', '=', Auth::id())->first();


            if (!$paymentStatus || $paymentStatus->status != 'Completed') {
                return redirect()->route('affiliate.payment');
            }

            $user = Auth::user();

            if (isset($user) && (is_null($user->referral) || empty($user->referral))) {
                $user->referral = Carbon::now()->timestamp . sixDigitRandCode();
                $user->update();
            }

            $user_type = $user->type;

            if ($user_type == 'affiliate') {
                $data['urls'] = "affiliateMarketing";

                $query = Referral::select(
                    'referrals.id',
                    'referrals.user_id',
                    'referrals.store_id',
                    'referrals.referral_id',
                    'referrals.commission_price',
                    'referrals.created_at',
                    'referrals.plan_id',
                    'plans.name as plan_name',
                    'referrals.digital_id',
                    'digitalplans.name as digital_plan_name',
                    'referrals.pos_id',
                    'posplans.name as pos_plan_name',
                    'stores.name AS store_name',
                    'users.name AS user_name',
                    'users.phone AS user_phone'
                )
                    ->leftJoin('plans', function ($join) {
                        $join->on('plans.id', '=', 'referrals.plan_id');
                    })
                    ->leftJoin('digitalplans', function ($join) {
                        $join->on('digitalplans.id', '=', 'referrals.digital_id');
                    })
                    ->leftJoin('posplans', function ($join) {
                        $join->on('posplans.id', '=', 'referrals.pos_id');
                    })
                    ->leftJoin('stores', function ($join) {
                        $join->on('stores.id', '=', 'referrals.store_id');
                    })
                    ->leftJoin('users', function ($join) {
                        $join->on('users.id', '=', 'referrals.user_id');
                    });

                $data['refers'] = $query->where('referrals.referral_id', '=', Auth::user()->referral)->get();

                // Get the current date
                $currentDate = Carbon::now();

                // Get the date one month ago
                $oneMonthAgo = $currentDate->subMonth();
                $data['refers_last_month'] = $query->where('referrals.referral_id', '=', Auth::user()->referral)
                    ->where('referrals.created_at', '>=', $oneMonthAgo) // Filter for referrals created in the last month
                    ->get();

                $totalEarning = 0;
                $montlyEarning = 0;

                if ($data['refers'] && count($data['refers']) > 0) {
                    foreach ($data['refers'] as $item) {
                        $totalEarning = (float)$totalEarning + (float)$item->commission_price;
                    }
                }


                if ($data['refers_last_month'] && count($data['refers_last_month']) > 0) {
                    foreach ($data['refers_last_month'] as $item) {
                        $montlyEarning = (float)$montlyEarning + (float)$item->commission_price;
                    }
                }

                $data['totalEarning'] = $totalEarning;
                $data['montlyEarning'] = $montlyEarning;

                $WithrawRequest = AffiliateBalance::where('user_id', '=', Auth::user()->id)->latest()->first();
                $data['WithrawRequest'] = $WithrawRequest;

                $data['examStatus'] = $examStatus;

                $paymentStatus = AffiliatePayment::where('user_id', '=', Auth::user()->id)->latest()->first();
                $data['paymentStatus'] = $paymentStatus;

                $affiliateInfo = AffiliateInfo::latest()->first();
                $data['affiliateCharge'] = $affiliateInfo ? $affiliateInfo->affiliate_charge : 500;

                return view('affiliate.index', $data);
            } else {
                return redirect()->back();
            }

        }
    }


    /**
     *
     * Display payment page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|never
     */
    public function payment()
    {

        $paymentStatus = AffiliatePayment::where('user_id', '=', Auth::id())->where('status', '=', 'Completed')->first();

        if ($paymentStatus) {
            return redirect()->route('affiliate.index');
        }

        $affiliateInfo = AffiliateInfo::first();
        $charge = $affiliateInfo ? $affiliateInfo->affiliate_charge : 200;
        $charge_usd = $affiliateInfo ? $affiliateInfo->affiliate_charge_usd : 2;

        return view('affiliate.payment', compact('charge', 'charge_usd'));
    }


    /**
     *
     * Display FAQ page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function faq()
    {
        $totalCount = AffiliateFAQ::count();  // Get the total number of questions
        $halfCount = ceil($totalCount / 2);  // Calculate how many to show per page

        $faqs = AffiliateFAQ::take($halfCount)->get();
        return view('affiliate.faq', ['faqs' => $faqs]);
    }


    /**
     *
     * Display FAQ 2 page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function faq2()
    {
        $totalCount = AffiliateFAQ::count();  // Get the total number of questions
        $halfCount = ceil($totalCount / 2);  // Calculate how many to show per page

        $faqs = AffiliateFAQ::skip($halfCount)->take($halfCount)->get();
        return view('affiliate.faq2', ['faqs' => $faqs, "countStart" => $halfCount]);
    }


    /**
     *
     * Display exam rules page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function exam_rules()
    {
        $affiliateExamInfo = AffiliateExamInfo::where('user_id', '=', Auth::id())->first();
        if ($affiliateExamInfo && $affiliateExamInfo->answer_submitted_at == null) {
            return view('affiliate.examrules');
        } else {
            return back();
        }
    }


    /**
     *
     * Store exam answer
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function answerStore(Request $request)
    {
        if (isset($request->qus) && count($request->qus) > 0) {
            foreach ($request->qus as $question_id => $answer) {
                $affiliateAnswer = AffiliateQuestionAnswer::where('user_id', Auth::id())->where('question_id', $question_id)->first();

                if (is_null($affiliateAnswer)) {
                    $affiliateAnswer = new AffiliateQuestionAnswer();
                    $affiliateAnswer->user_id = Auth::id();
                    $affiliateAnswer->question_id = $question_id;
                }

                $affiliateAnswer->answer = $answer;
                $affiliateAnswer->answer_submitted_at = Carbon::now();
                $affiliateAnswer->save();
            }

            if (isset($request->page) && $request->page == "one") {
                return redirect()->route('affiliate.examspagetwo')->with('success', 'Answer added successfully!');
            } elseif (isset($request->page) && $request->page == "two") {
                $affiliateExamInfo = AffiliateExamInfo::where('user_id', '=', Auth::id())->first();

                if ($affiliateExamInfo && !is_null($affiliateExamInfo->exam_started_at)) {
                    $affiliateExamInfo->answer_submitted_at = Carbon::now();
                    $affiliateExamInfo->update();

                    session()->forget('page_one_questions');
                    session()->forget('page_two_questions');
                }
                return redirect()->route('affiliate.result')->with('success', 'All Answer submitted successfully!');
            }

            return redirect()->route('affiliate.result')->with('success', 'All Answer submitted successfully!');
        } else {
            return redirect()->route('affiliate.result')->with('success', 'All Answer submitted successfully!');
        }

    }

    /**
     *
     * Show exam page one
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function exams()
    {
        $affiliateExamInfo = AffiliateExamInfo::where('user_id', '=', Auth::id())->first();

        if ($affiliateExamInfo && $affiliateExamInfo->answer_submitted_at == null) {
            if (is_null($affiliateExamInfo->exam_started_at)) {
                $affiliateExamInfo->exam_started_at = Carbon::now();
                $affiliateExamInfo->update();
            }
            $exam_started_at = $affiliateExamInfo->exam_started_at;

            $pageOneQuestions = session()->get('page_one_questions');
            if (isset($pageOneQuestions) && count($pageOneQuestions) > 0) {
                $questions = AffiliateQuestion::whereIn('id', $pageOneQuestions)->get();
            } else {
                $questions = AffiliateQuestion::inRandomOrder()->limit(5)->get();;
                session()->put('page_one_questions', $questions->pluck('id')->toArray());
            }

            return view('affiliate.exams', compact('questions', 'exam_started_at'));
        } else {
            return back();
        }
    }

    /**
     *
     * Show exam page two
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function examspagetwo()
    {
        $affiliateExamInfo = AffiliateExamInfo::where('user_id', '=', Auth::id())->first();

        if ($affiliateExamInfo && is_null($affiliateExamInfo->answer_submitted_at)) {
            $exam_started_at = $affiliateExamInfo->exam_started_at;

            $pageTwoQuestions = session()->get('page_two_questions');
            if (isset($pageTwoQuestions) && count($pageTwoQuestions) > 0) {
                $questions = AffiliateQuestion::whereIn('id', $pageTwoQuestions)->get();
            } else {
                $pageOneQuestions = session()->get('page_one_questions');
                $questions = AffiliateQuestion::whereNotIn('id', $pageOneQuestions)
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();
                session()->put('page_two_questions', $questions->pluck('id')->toArray());
            }

            return view('affiliate.exampagetwo', compact('questions', 'exam_started_at'));
        } else {
            return back();
        }
    }


    /**
     *
     * Show exam result page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function result()
    {
        $affiliateExamInfo = AffiliateExamInfo::where('user_id', '=', Auth::id())->where('answer_submitted_at', '!=', null)->first();

        if (is_null($affiliateExamInfo)) {
            return redirect()->route('affiliate.faq');
        } elseif ($affiliateExamInfo) {
            if ($affiliateExamInfo->user_status != null) {
                return redirect()->route('affiliate.index');
            }

            $exam_submitted_at = $affiliateExamInfo->answer_submitted_at;

            $questions = AffiliateQuestion::orderBy('id', 'asc')->limit(3)->get();
            return view('affiliate.examsresult', compact('questions', 'exam_submitted_at'));
        } else {
            return back();
        }

    }


    /**
     *  Affiliate markiting page display
     */
    public function affiliateMarketing()
    {
        $paymentStatus = AffiliatePayment::where('user_id', '=', Auth::id())->first();

        if (!$paymentStatus || $paymentStatus->status != 'Completed') {
            return redirect()->route('affiliate.payment');
        }

        $affiliateExamInfo = AffiliateExamInfo::where('user_id', '=', Auth::id())->first();
        if ($affiliateExamInfo && $affiliateExamInfo->user_status != 'Approved') {
            return back();
        }
        $user_type = Auth::user()->type;

        if ($user_type == 'affiliate') {
            $data['urls'] = "affiliateMarketing";
            $data['users'] = User::where('refer_by', Auth::user()->referral)->orderBy('id', 'DESC')->get();


            $data['refers'] = Referral::select(
                'referrals.id',
                'referrals.user_id',
                'referrals.store_id',
                'referrals.referral_id',
                'referrals.commission_price',
                'referrals.created_at',
                'referrals.plan_id',
                'plans.name as plan_name',
                'referrals.digital_id',
                'digitalplans.name as digital_plan_name',
                'referrals.pos_id',
                'posplans.name as pos_plan_name',
                'stores.name AS store_name',
                'users.name AS user_name',
                'users.phone AS user_phone',
                'users.email AS user_email'
            )
                ->leftJoin('plans', function ($join) {
                    $join->on('plans.id', '=', 'referrals.plan_id');
                })
                ->leftJoin('digitalplans', function ($join) {
                    $join->on('digitalplans.id', '=', 'referrals.digital_id');
                })
                ->leftJoin('posplans', function ($join) {
                    $join->on('posplans.id', '=', 'referrals.pos_id');
                })
                ->leftJoin('stores', function ($join) {
                    $join->on('stores.id', '=', 'referrals.store_id');
                })
                ->leftJoin('users', function ($join) {
                    $join->on('users.id', '=', 'referrals.user_id');
                })
                ->where('referrals.referral_id', '=', Auth::user()->referral)
                ->get();

            if ($user_type == 'affiliate') {
                return view('affiliate.affiliatemarketing', $data);
            } else {
                return back();
            }
        }
    }

    /**
     *
     * Profile page display
     *
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function profile()
    {
        $urls = 'settings';

        $userData = getUserData();

        $user_id = $userData['user_id'];
        $user_type = $userData['user_type'];

        if ($user_type == 'affiliate') {
            $customer = Customer::where('uid', $user_id)->first();
            $store_id = $customer->active_store ?? null;
            $customer_id = $customer->id ?? null;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user_id)->first();
            $store_id = $staff->store_id ?? null;
            $customer_id = $staff->customer_id ?? null;
        }

        $toptool = Toptool::where('name', 'Profile')->where('uid', $user_id)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Profile";
            $toptool->image = "resume.png";
            $toptool->url = "/profile";
            $toptool->count = "1";
            $toptool->uid = $user_id;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user_id;
            $toptool->editor = $user_id;
            $toptool->save();
        }
        $setting = Customer::where('uid', $user_id)->first();
        $store = Store::where('id', $store_id)->first();
        $userss = User::where('id', $user_id)->first();
        $user = User::where('id', $user_id)->first();
        if ($user_type == 'affiliate') {
            return view('affiliate.setting', compact('user', 'setting', 'store', 'userss', 'urls'));
        } else {
            return back();
        }
    }

    /**
     *
     * Affiliate profile update
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function profile_update(Request $request)
    {
        $user = User::where('id', '=', $request->user)->first();
        if ($user) {
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->address = $request->address;

            if ($request->userimage) {
                $imgName = Carbon::now()->timestamp . "U" . '.' . $request->userimage->extension();
                $request->userimage->storeAs('img', $imgName);

                if (isset($user->image)) {
                    $path = public_path("assets/images/img/") . $user->image;
                    deleteFile($path);
                }

                $user->image = $imgName;
            }
            $user->save();
            Session::flash('message', 'Profile updated successfully!');
            return back();
        }
    }


    /**
     *
     * Withdraw request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function withdrawRequest(Request $request)
    {
        if (empty($request->withdraw_request_amount) || $request->withdraw_request_amount < 20) {
            Session::flash('error', 'Withdraw amount cannot be lower than 20!');
            return back();
        }
        $userID = Auth::id();

        $affiliateBalance = AffiliateBalance::where('user_id', $userID)->first();

        if ($affiliateBalance) {
            if ($affiliateBalance->balance >= 500) {
                $newBalance = ((int)$affiliateBalance->balance - (int)$request->withdraw_request_amount);
                if ($newBalance >= 100) {
                    $affiliateBalance->withdraw_request_amount = $request->withdraw_request_amount;
                    $affiliateBalance->save();

                    Session::flash('success', 'You successfully make your withdraw request!');
                    return back();
                }

                Session::flash('error', 'Your minimum balance must be 100!');
                return back();
            } else {
                Session::flash('error', 'Sorry! You cannot withdraw. For withdraw your minimum balance will be 500!');
                return back();
            }
        } else {
            Session::flash('error', 'Something wrong. try Again!');
            return back();
        }
    }


    public function affiliateUserPayment(Request $request)
    {
        if (Auth::user()->type == 'affiliate') {
            if (!isset($request->amount) || empty($request->amount) || $request->amount <= 0) {
                Session::flash("error", "Amount must be greater than 0");
                return back();
            }
            if (!isset($request->amount_usd) || empty($request->amount_usd) || $request->amount_usd <= 0) {
                Session::flash("error", "Amount must be greater than 0");
                return back();
            }
            if (!isset($request->payment_method) || empty($request->payment_method)) {
                Session::flash("error", "Please select payment method");
                return back();
            }

            $affiliate_charge = 0;
            $affiliate_charge_usd = 0;
            $affiliateInfo = AffiliateInfo::latest()->first();
            if (isset($affiliateInfo)) {
                $affiliate_charge = $affiliateInfo->affiliate_charge;
                $affiliate_charge_usd = $affiliateInfo->affiliate_charge_usd;
            }

            if ($affiliate_charge != $request->amount) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            if ($affiliate_charge_usd != $request->amount_usd) {
                Session::flash('error', "Invalid request.");
                return back();
            }

            $userData = getUserData();
            $user_id = $userData['user_id'] ?? "";
            $amount = $affiliate_charge ?? 0;

            if (!empty($user_id)) {
                if (isset($request->payment_method) && $request->payment_method == "bkash") {

                    $url = route("bkash-create-payment", ['amount' => $amount]);
                    return redirect()->away($url);
                } elseif (isset($request->payment_method) && $request->payment_method == "amarpay") {

                    $url = route("amarpay.affiliate.payment", ['amount' => $amount]);
                    return redirect()->away($url);
                } elseif (isset($request->payment_method) && $request->payment_method == "paypal") {
                    $amount = $affiliate_charge_usd ?? 0;

                    if ($amount <= 0) {
                        Session::flash('error', "USD price not set yet.");
                        return back();
                    }

                    $url = route("paypal.affiliate.payment", ['amount' => $amount]);
                    return redirect()->away($url);
                }
            }

            return back()->with("error", "Something went wrong. Try again later.");
        }

        return back()->with("error", "You are not authorized.");

    }

}
