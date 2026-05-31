<?php

namespace App\Http\Controllers\SuperAdmin\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateQuestionAnswer;
use App\Models\User;
use App\Models\AffiliateQuestion;
use App\Models\AffiliateExamInfo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateQuestionAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $urls = "answers";
        $users = User::join('affiliate_exam_infos', 'users.id', '=', 'affiliate_exam_infos.user_id')
            ->where('users.type', 'affiliate')
            ->whereNotNull('affiliate_exam_infos.answer_submitted_at')
            ->orderBy('users.id', 'desc')
            ->select('users.*', 'affiliate_exam_infos.answer_submitted_at', 'affiliate_exam_infos.user_status') // Specify required columns
            ->paginate(20);

        $questions = AffiliateQuestion::orderBy('id', 'desc')->paginate(10);

        return view('superadmin.affiliateQuestionAnswers.index', compact('users', 'questions'))->with('urls', $urls);
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

        // dd($request->page);
        if ($request->qus && (count($request->qus) > 0)) {
            foreach ($request->qus as $question_id => $value) {
                $answer = new AffiliateQuestionAnswer;
                $answer->user_id = Auth::id();
                $answer->question_id = $question_id;
                $answer->answers = $value;
                // $answer->save();
            }
        }

        if ($request->page) {

            $affiliateExamInfo = AffiliateExamInfo::where('user_id', '=', Auth::id())->first();
            if ($affiliateExamInfo) {
                $affiliateExamInfo->answer_submited_at = Carbon::now();
                $affiliateExamInfo->save();
            }

            return redirect()->route('affiliate.result')->with('success', 'All Answer uploaded successfully!');
        } else {
            return redirect()->route('affiliate.examspagetwo')->with('success', 'Answer added successfully!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AffiliateQuestionAnswer $affiliateQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(AffiliateQuestionAnswer $affiliateQuestionAnswer, $uid)
    {
        $urls = "answers";

        $users = User::join('affiliate_exam_infos', 'users.id', '=', 'affiliate_exam_infos.user_id')
            ->where('users.type', 'affiliate')
            ->whereNotNull('affiliate_exam_infos.answer_submitted_at')
            ->orderBy('users.id', 'desc')
            ->select('users.*', 'affiliate_exam_infos.answer_submitted_at', 'affiliate_exam_infos.user_status') // Specify required columns
            ->paginate(20);

        $answers = AffiliateQuestionAnswer::orderBy('id', 'desc')
            ->where('affiliate_question_answers.user_id', '=', $uid)
            ->join('affiliate_questions', 'affiliate_question_answers.question_id', '=', 'affiliate_questions.id')
            ->select('affiliate_question_answers.*', 'affiliate_questions.question')
            ->paginate(10);

        return view('superadmin.affiliateQuestionAnswers.view', compact('users', 'answers', 'uid'))->with('urls', $urls);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AffiliateQuestionAnswer $affiliateQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function result(AffiliateQuestionAnswer $affiliateQuestionAnswer, Request $request)
    {

        // dd($request->user_id);
        if ($request->input('Approve')) {
            $user = AffiliateExamInfo::where('user_id', '=', $request->user_id)->first();
            if ($user) {
                $user->user_status = 'approved';
                $user->save();
            }
            return back()->with('success', 'User approved successfully!');
        } elseif ($request->input('Hold')) {
            $user = AffiliateExamInfo::where('user_id', '=', $request->user_id)->first();
            if ($user) {
                $user->user_status = 'hold';
                $user->save();
            }
            return back()->with('success', 'User holded successfully!');
        } else {
            $user = AffiliateExamInfo::where('user_id', '=', $request->user_id)->first();
            if ($user) {
                $user->user_status = 'rejected';
                $user->save();
            }
            return back()->with('success', 'User rejected successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AffiliateQuestionAnswer $affiliateQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(AffiliateQuestionAnswer $affiliateQuestionAnswer)
    {
        //
    }

    public function userStatusChange(Request $request)
    {
        $user_id = $request->user_id ?? "";
        if (empty($user_id)) {
            return back()->with('error', 'User missing!');
        }

        $affiliateExamInfo = AffiliateExamInfo::where('user_id', $user_id)->first();

        $userStatus = null;

        if (isset($request->Approve)) {
            $userStatus = "Approved";
        } elseif ($request->Hold) {
            $userStatus = "Hold";
        } elseif ($request->Reject) {
            $userStatus = "Rejected";
        }

        $affiliateExamInfo->user_status = $userStatus;
        $affiliateExamInfo->update();

        return back()->with('success', 'Status change successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AffiliateQuestionAnswer $affiliateQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AffiliateQuestionAnswer $affiliateQuestionAnswer)
    {
        //
    }
}
