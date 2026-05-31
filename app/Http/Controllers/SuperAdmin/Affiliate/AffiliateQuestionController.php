<?php

namespace App\Http\Controllers\SuperAdmin\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateQuestion;
use Illuminate\Http\Request;

use App\Models\Store;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AffiliateQuestionController extends Controller
{
    /**
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $urls = "questions";
        $questions = AffiliateQuestion::orderBy('id', 'desc')->paginate(20);
        return view('superadmin.affiliateQuestions.index', compact('questions'))->with('urls', $urls);
    }

    public function answers()
    {
        $urls = "questions";
        $questions = AffiliateQuestion::orderBy('id', 'desc')->paginate(20);

        $urls = "digital";
        $id = '';
        $lists = Store::where('digital_plan_id', '!=', null)->get();

        return view('superadmin.affiliateQuestionAnswers.index', compact('urls', 'lists', 'id'))->with('urls', $urls);
    }

    /**
     *
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $urls = "questions";
        $questions = AffiliateQuestion::orderBy('id', 'desc')->paginate(20);
        return view('superadmin.affiliateQuestions.create', compact('questions'))->with('urls', $urls);
    }

    /**
     *
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Input validation
            $rules = array(
                'question' => 'required|string',
                'answer_option_one' => 'nullable|string',
                'answer_option_two' => 'nullable|string',
                'answer_option_three' => 'nullable|string',
                'answer_option_four' => 'nullable|string',
            );

            // Input validation message
            $errorMessage = array(
                'question.required' => 'Question is required.',
                'question.string' => 'Question must be a string.',
                'answer_option_one' => 'Answer option one must be string.',
                'answer_option_two' => 'Answer option two must be string.',
                'answer_option_three' => 'Answer option three must be string.',
                'answer_option_four' => 'Answer option four must be string.',
            );

            $validator = Validator::make($request->all(), $rules, $errorMessage);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {

                if ($request->question_type == 'on' && (empty($request->answer_option_one) && empty($request->answer_option_two) && empty($request->answer_option_three) && empty($request->answer_option_four))) {
                    $validator->getMessageBag()->add('question_type', "Answer option must not be empty!");
                    return redirect()->back()->withInput()->withErrors($validator);
                }

                $question = new AffiliateQuestion;

                $question->question = $request->question;

                if ($request->question_type == 'on') {
                    $question->question_type = 'radio';
                } else {
                    $question->question_type = 'plain';
                }
                $question->answer_option_one = $request->answer_option_one;
                $question->answer_option_two = $request->answer_option_two;
                $question->answer_option_three = $request->answer_option_three;
                $question->answer_option_four = $request->answer_option_four;
                if ($request->status == 'on') {
                    $question->status = 1;
                } else {
                    $question->status = 0;
                }

                $question->save();
                return redirect()->route('affiliate.questions.index')->with('success', 'Question added successfully!');
            }

        } catch (\Exception $e) {
            return view('error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AffiliateQuestion $affiliateQuestion
     * @return \Illuminate\Http\Response
     */
    public function show(AffiliateQuestion $affiliateQuestion)
    {
        //
    }

    /**
     *
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $question = AffiliateQuestion::findOrFail($id);

            if ($question) {
                return view('superadmin.affiliateQuestions.edit', compact('question'));
            } else {
                return redirect()->back()->with('error', 'Question not found!');
            }

        } catch (\Exception $e) {
            return view('error');
        }
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $question = AffiliateQuestion::findOrFail($id);

            // Input validation
            $rules = array(
                'question' => 'required|string',
                'answer_option_one' => 'nullable|string',
                'answer_option_two' => 'nullable|string',
                'answer_option_three' => 'nullable|string',
                'answer_option_four' => 'nullable|string',
            );

            // Input validation message
            $errorMessage = array(
                'question.required' => 'Question is required.',
                'question.string' => 'Question must be a string.',
                'answer_option_one' => 'Answer option one must be string.',
                'answer_option_two' => 'Answer option two must be string.',
                'answer_option_three' => 'Answer option three must be string.',
                'answer_option_four' => 'Answer option four must be string.',
            );

            $validator = Validator::make($request->all(), $rules, $errorMessage);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if ($question) {
                    $question->question = $request->question;

                    if ($request->question_type == 'on') {
                        $question->question_type = 'radio';
                    } else {
                        $question->question_type = 'plain';
                    }

                    $question->answer_option_one = $request->answer_option_one;
                    $question->answer_option_two = $request->answer_option_two;
                    $question->answer_option_three = $request->answer_option_three;
                    $question->answer_option_four = $request->answer_option_four;

                    if ($request->status == 'on') {
                        $question->status = 1;
                    } else {
                        $question->status = 0;
                    }

                    $question->save();
                    return redirect()->back()->with('success', 'Question updated successfully!');
                } else {
                    return redirect()->back()->with('error', 'Question not found!');
                }
            }
        } catch (\Exception $e) {
            return view('error');
        }
    }

    /**
     *
     * Updates the status of Question.
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

            $findBlogOrNot = AffiliateQuestion::where('id', $id)->first();

            if ($findBlogOrNot) {
                if ($findBlogOrNot->status == 1) {
                    $findBlogOrNot->status = 0;

                    $statusMsg = "Question Inactive Updated Successfully.";
                } elseif ($findBlogOrNot->status == 0) {
                    $findBlogOrNot->status = 1;

                    $statusMsg = "Question Active Updated Successfully.";
                }

                $findBlogOrNot->save();

                return response()->json([
                    'data' => $findBlogOrNot,
                    'status' => $statusMsg
                ]);
            } else {
                return response()->json([
                    'data' => $findBlogOrNot,
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
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $question = AffiliateQuestion::findOrFail($id);

            if ($question) {
                $question->delete();

                return redirect()->route('affiliate.questions.index')->with('success_message', 'Question deleted successfully!');
            } else {
                return redirect()->back()->with('error', 'Question not found!');
            }

        } catch (\Exception $e) {
            return view('error');
        }
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
                $question = AffiliateQuestion::findOrFail($ids);
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
