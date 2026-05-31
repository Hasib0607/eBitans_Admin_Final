<?php

namespace App\Http\Controllers\SuperAdmin\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateBalance;
use App\Models\AffiliateFAQ;
use App\Models\AffiliatePayment;
use App\Models\AffiliateInfo;
use App\Models\AffiliateQuestion;
use App\Models\AffiliateWithdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AffiliateFAQController extends Controller
{

    /**
     *
     * Affiliate question list page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function affiliateFAQList()
    {
        $faqs = AffiliateFAQ::paginate(10);
        return view("superadmin.affiliateFAQ.index", ["faqs" => $faqs]);
    }

    /**
     *
     * Affiliate question create page display
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function affiliateFAQCreate()
    {
        return view("superadmin.affiliateFAQ.create");
    }


    /**
     *
     * Create affiliate question
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function affiliateFAQStore(Request $request)
    {
        try {

            $validator = $this->affiliateValidation($request);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $this->questionStore($request);
                return redirect()->route('affiliate.faq.question.list')->with('success', 'Question added successfully!');
            }

        } catch (\Exception $e) {
            return view('error');
        }
    }

    /**
     *
     * Affiliate question validation
     *
     * @param $request
     * @return \Illuminate\Validation\Validator
     */
    public function affiliateValidation($request)
    {
        // Input validation
        $rules = array(
            'question' => 'required|string',
            'answer' => 'nullable|string',
            'video_link' => 'nullable|string|url',
        );

        // Input validation message
        $errorMessage = array(
            'question.required' => 'Question is required.',
            'question.string' => 'Question must be a string.',
            'answer.string' => 'Answer must be a string.',
            'video_link.string' => 'Video link must be a string.',
            'video_link.url' => 'Please enter a valid URL.',
        );

        return Validator::make($request->all(), $rules, $errorMessage);
    }

    /***
     *
     * Store affiliate question
     *
     * @param $request
     * @return void
     */
    public function questionStore($request)
    {
        if (isset($request->question_id)) {
            $question = AffiliateFAQ::where("id", $request->question_id)->first();
            if (!isset($question)) {
                $question = new AffiliateFAQ();
            }
        } else {
            $question = new AffiliateFAQ();
        }

        $question->question = $request->question;
        $question->answer = $request->answer ?? null;
        $question->video_link = $request->video_link ?? null;
        $question->status = $request->status == 'on' ? 1 : 0;

        $question->save();
    }

    /**
     *
     * Display affiliate question edit page
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function affiliateFAQEdit($id)
    {
        $faq = AffiliateFAQ::where('id', $id)->first();
        if (!isset($faq)) {
            Session::flash('error', 'Question not found.');
            return redirect()->back();
        }
        return view("superadmin.affiliateFAQ.edit", ["faq" => $faq]);
    }

    /**
     *
     * Affiliate question update
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function affiliateFAQUpdate(Request $request)
    {
        try {
            $validator = $this->affiliateValidation($request);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $this->questionStore($request);
                return redirect()->route('affiliate.faq.question.list')->with('success', 'Question updated successfully!');
            }

        } catch (\Exception $e) {
            return view('error');
        }
    }


    /**
     *
     * Affiliate question status change
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        try {
            $value = $request->value;
            $id = $request->id;

            if (empty($id)) {
                return response()->json([
                    'data' => $id,
                    'status' => 'ID not found'
                ]);
            }

            if ($value != 'on') {
                return response()->json([
                    'data' => $value,
                    'status' => "Value Not Found."
                ]);
            }

            $question = AffiliateFAQ::where('id', $id)->first();

            if ($question) {
                if ($question->status == 1) {
                    $question->status = 0;

                    $statusMsg = "Question Inactive Updated Successfully.";
                } elseif ($question->status == 0) {
                    $question->status = 1;

                    $statusMsg = "Question Active Updated Successfully.";
                }

                $question->save();

                return response()->json([
                    'data' => $question,
                    'status' => $statusMsg
                ]);
            } else {
                return response()->json([
                    'data' => $question,
                    'status' => 'Question not found.'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'data' => 404,
                'status' => 'Something wants wrong.'
            ]);
        }

    }

    /**
     *
     * Delete affiliate question
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function affiliateFAQDelete($id)
    {
        $faq = AffiliateFAQ::where('id', $id)->first();
        if (isset($faq)) {
            $faq->delete();
        }

        Session::flash("success", "FAQ deleted successfully");
        return redirect()->back();
    }


    /**
     * Update question by the table action like ( Active, Inactive, Delete )
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function changeQuestionAction(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Question');
            return redirect()->back();
        }

        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return redirect()->back();
        }

        $id = explode(',', $request->text2);
        if (isset($id) && count($id) > 0) {
            foreach ($id as $ids) {
                $question = AffiliateFAQ::findOrFail($ids);
                if ($question) {
                    if ($request->action == 'active') {
                        $question->status = 1;
                        $question->save();
                    } elseif ($request->action == 'deactive') {
                        $question->status = 0;
                        $question->save();
                    } elseif ($request->action == 'delete') {
                        $question->delete();
                    }
                }
            }
        }

        $msg = "Action completed successfully.";

        if ($request->action == 'active') {
            $msg = 'Question Activated Successfully';
        } elseif ($request->action == 'deactive') {
            $msg = 'Question Deactivated Successfully';
        } elseif ($request->action == 'delete') {
            $msg = 'Question Deleted Successfully';
        }

        Session::flash('message', $msg);
        return redirect()->back();
    }


}
