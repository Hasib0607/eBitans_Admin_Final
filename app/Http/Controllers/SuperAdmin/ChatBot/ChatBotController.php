<?php

namespace App\Http\Controllers\SuperAdmin\ChatBot;

use App\Models\ChatbotAnswer;
use App\Models\ChatbotQuestion;
use App\Models\ChatbotQuestionAnswer;
use App\Models\ChatbotUnansweredQuestion;
use App\Models\ChatMessage;
use App\Models\ChatConversation;
use App\Models\ChatVisitor;
use App\Models\AdminChatSupport;
use App\Models\User;
use App\Services\WhatsAppAutomation\BotApiService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChatBotController extends Controller
{
    public function __construct(
        protected BotApiService $botApiService
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Fetch question answers, eager load questions and answers
        $questionAnswers = ChatbotQuestionAnswer::with([
            'question' => function ($query) {
                $query->select('id', 'question', 'type', 'lang'); // Select specific columns for question
            },
            'answer' => function ($query) {
                $query->select('id', 'answer'); // Select specific columns for answer
            }
        ])
            ->orderBy('group_id') // Order by group_id if necessary
            ->get();

        // Process and structure the data as needed
        $groupedData = $this->structureGroupedData($questionAnswers);

        // Paginate the grouped data by group_id
        $perPage = 10; // Number of items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Slice the grouped data to paginate
        $currentPageItems = $groupedData->slice(($currentPage - 1) * $perPage, $perPage)->all();

        // Create a new LengthAwarePaginator instance
        $paginatedData = new LengthAwarePaginator(
            $currentPageItems,
            $groupedData->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        // Pass $groupedData to the Blade view
        return view('chatBot.index', compact('paginatedData'));
    }

    // Helper function to structure grouped data
    private function structureGroupedData($questionAnswers)
    {
        // Initialize an empty collection to store grouped data
        $groupedData = collect();

        // Group the data by group_id
        $questionAnswers->groupBy('group_id')->each(function ($group) use ($groupedData) {
            // Fetch the type and lang from the first question in the group
            $type = $group->first()->question->type;
            $type_both = $group->first()->question->type_both;
            $lang = $group->first()->question->lang;
            $lang_both = $group->first()->question->lang_both;

            // Initialize arrays to store questions and answers for this group
            $questions = [];
            $answers = [];

            // Loop through each item in the group to collect unique questions and answers
            foreach ($group as $item) {
                // Add question to the array if not already added
                if (!in_array($item->question->id, array_column($questions, 'id'))) {
                    $questions[] = [
                        'id' => $item->question->id,
                        'question' => $item->question->question,
                    ];
                }

                // Add answer to the array if not already added
                if (!in_array($item->answer->id, array_column($answers, 'id'))) {
                    $answers[] = [
                        'id' => $item->answer->id,
                        'answer' => $item->answer->answer,
                    ];
                }
            }

            // Create a structure for this group and add to groupedData collection
            $groupedData->push([
                'group_id' => $group->first()->group_id, // assuming group_id is the same for all items in the group
                'type' => $type,
                'type_both' => $type_both,
                'lang' => $lang,
                'lang_both' => $lang_both,
                'questions' => $questions,
                'answers' => $answers,
            ]);
        });

        return $groupedData;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate your input
        $validator = Validator::make($request->all(), [
            'question' => 'required|array|min:1',
            'question.*' => 'required|string',
            'answer' => 'required|array|min:1',
            'answer.*' => 'required|string',
            'type' => 'nullable|integer',
            'lang' => 'nullable|integer'
        ], [
            "question.required" => "Question is required",
            "question.*.required" => "At least one question must be required!",
            "question.min" => "At least one question must be required!",
            "answer.required" => "Answer is required",
            "answer.*.required" => "At least one answer must be required!",
            "answer.min" => "At least one answer must be required!",
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            // Redirect back with errors and input
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Begin a database transaction
        \DB::beginTransaction();

        try {
            // Check if the 'lang_both' and 'type_both' checkboxes are checked
            $typeBothChecked = $request->has('type_both') ? 1 : 0;
            $langBothChecked = $request->has('lang_both') ? 1 : 0;

            // Increment the group_id
            $latestGroup = ChatbotQuestionAnswer::max('group_id') ?? 0;
            $groupId = $latestGroup + 1;

            //insert answer
            $answerIds = [];
            foreach ($request->input('answer') as $answerData) {
                $ifExist = ChatbotAnswer::where("answer", $answerData)->first();
                if (!$ifExist) {
                    $answer = ChatbotAnswer::create([
                        'answer' => $answerData,
                        'type' => $request->type ? 1 : 0,
                        'type_both' => $typeBothChecked,
                        'lang' => $request->lang ? 1 : 0,
                        'lang_both' => $langBothChecked,
                    ]);
                    $answerIds[] = $answer->id;
                }
            }


            foreach ($request->input('question') as $questionData) {
                $ifExist = ChatbotQuestion::where("question", $questionData)->first();
                if (!$ifExist) {
                    $question = ChatbotQuestion::create([
                        'question' => $questionData,
                        'type' => $request->type ? 1 : 0,
                        'type_both' => $typeBothChecked,
                        'lang' => $request->lang ? 1 : 0,
                        'lang_both' => $langBothChecked,
                    ]);

                    // Attach answers to the question with group_id
                    $question->answers()->attach($answerIds, ['group_id' => $groupId]);
                }
            }

            // Commit the transaction
            \DB::commit();

            return redirect()->back()->with('success', 'Questions and answers saved successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            \DB::rollback();
            return redirect()->back()->with('error', 'Failed to save questions and answers.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return view('chatBot.create');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        // Fetch all question answers, eager load questions and answers
        $questionAnswers = ChatbotQuestionAnswer::with([
            'question' => function ($query) {
                $query->select('id', 'question', 'type', 'type_both', 'lang', 'lang_both'); // Select specific columns for question
            },
            'answer' => function ($query) {
                $query->select('id', 'answer'); // Select specific columns for answer
            }
        ])
            ->orderBy('group_id') // Order by group_id if necessary
            ->get();

        // Process and structure the data as needed
        $groupedData = $this->structureGroupedData($questionAnswers);

        // Filter data for group_id 1
        $groupData = $groupedData->where('group_id', $id)->first();

        if (!is_null($groupData)) {
            return view('chatBot.edit', ['groupData' => $groupData]);
        }

        Session::flash("error", "Data not found!");
        return redirect()->back();
    }

    /**
     *
     * Update a question.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|integer',
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string',
            'answers' => 'required|array|min:1',
            'answers.*' => 'required|string',
            'type' => 'nullable|integer',
            'lang' => 'nullable|integer'
        ], [
            'group_id.required' => 'Group ID is required.',
            'group_id.integer' => 'Group ID must be an integer.',
            'questions.required' => 'At least one question is required.',
            'questions.array' => 'Questions must be an array.',
            'questions.min' => 'At least one question must be provided.',
            'questions.*.required' => 'Each question must be a non-empty string.',
            'questions.*.string' => 'Each question must be a string.',
            'answers.required' => 'At least one answer is required.',
            'answers.array' => 'Answers must be an array.',
            'answers.min' => 'At least one answer must be provided.',
            'answers.*.required' => 'Each answer must be a non-empty string.',
            'answers.*.string' => 'Each answer must be a string.',
            'type.integer' => 'Type must be an integer.',
            'lang.integer' => 'Language must be an integer.',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            // Redirect back with errors and input
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Begin a database transaction
        \DB::beginTransaction();

        try {
            $groupId = $request->input('group_id');
            $type = $request->input('type');
            $lang = $request->input('lang');

            // Check if the 'lang_both' and 'type_both' checkboxes are checked
            $typeBothChecked = $request->has('type_both') ? 1 : 0;
            $langBothChecked = $request->has('lang_both') ? 1 : 0;

            // Handle Questions
            $questionsInput = $request->input('questions');
            $newQuestionsInput = $request->input('new_questions');
            $questionIds = [];

            if (isset($questionsInput) && count($questionsInput)) {
                foreach ($questionsInput as $questionId => $questionText) {
                    $question = ChatbotQuestion::find($questionId);

                    if ($question) {
                        $question->update([
                            'question' => $questionText,
                            'type' => $type,
                            'type_both' => $typeBothChecked,
                            'lang' => $lang,
                            'lang_both' => $langBothChecked
                        ]);
                        $questionIds[] = $question->id;
                    }
                }
            }


            if (isset($newQuestionsInput) && count($questionsInput)) {
                foreach ($newQuestionsInput as $questionText) {
                    $question = ChatbotQuestion::create([
                        'question' => $questionText,
                        'type' => $type,
                        'type_both' => $typeBothChecked,
                        'lang' => $lang,
                        'lang_both' => $langBothChecked
                    ]);
                    $questionIds[] = $question->id;
                }
            }


            // Handle Answers
            $answersInput = $request->input('answers');
            $newAnswersInput = $request->input('new_answers');
            $answerIds = [];

            if (isset($answersInput) && count($answersInput)) {
                foreach ($answersInput as $answerId => $answerText) {

                    $answer = ChatbotAnswer::find($answerId);

                    if ($answer) {
                        $answer->update([
                            'answer' => $answerText,
                            'type' => $type,
                            'type_both' => $typeBothChecked,
                            'lang' => $lang,
                            'lang_both' => $langBothChecked
                        ]);
                        $answerIds[] = $answer->id;
                    }
                }
            }

            if (isset($newAnswersInput) && count($newAnswersInput)) {
                foreach ($newAnswersInput as $answerText) {
                    $answer = ChatbotAnswer::create([
                        'answer' => $answerText,
                        'type' => $type,
                        'type_both' => $typeBothChecked,
                        'lang' => $lang,
                        'lang_both' => $langBothChecked
                    ]);
                    $answerIds[] = $answer->id;
                }
            }


            foreach ($questionIds as $questionId) {
                foreach ($answerIds as $answerId) {
                    // Use updateOrCreate to avoid duplicate entries
                    ChatbotQuestionAnswer::updateOrCreate(
                        [
                            'question_id' => $questionId,
                            'answer_id' => $answerId,
                            'group_id' => $groupId,
                        ]
                    );
                }
            }

            \DB::commit();

            return redirect()->back()->with('success', 'Questions and answers updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            \DB::rollback();

            return redirect()->back()->with('error', 'Failed to update questions and answers.');
        }
    }

    /**
     * Delete the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        if (!isset($id)) {
            return redirect()->back()->with('error', 'ID is required!.');
        }

        // Begin a database transaction
        \DB::beginTransaction();

        try {
            $questionId = ChatbotQuestionAnswer::where('group_id', $id)->pluck('question_id')->toArray();
            $answerId = ChatbotQuestionAnswer::where('group_id', $id)->pluck('answer_id')->toArray();

            ChatbotQuestion::whereIn("id", $questionId)->delete();
            ChatbotAnswer::whereIn("id", $answerId)->delete();


            \DB::commit();

            Session::flash('success', 'Question Answer Delete Successfully !');
            return redirect()->back();
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            \DB::rollback();

            return redirect()->back()->with('error', 'Failed to delete questions and answers.');
        }

    }

    /**
     * Delete answer.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAnswer($id)
    {
        // Find the Answer by ID
        $answer = ChatbotAnswer::find($id);

        if (empty($answer)) {
            Session::flash('error', 'Data Not Found!');
            return redirect()->back();
        }

        $answer->delete();

        Session::flash('success', 'Answer Delete Successfully !');
        return redirect()->back();

    }

    /**
     * Delete question.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteQuestion($id)
    {
        // Find the Answer by ID
        $question = ChatbotQuestion::find($id);

        if (empty($question)) {
            Session::flash('error', 'Data Not Found!');
            return redirect()->back();
        }

        $question->delete();

        Session::flash('success', 'Question Delete Successfully !');
        return redirect()->back();

    }


    /**
     * Change status and delete on bulk
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function actionChange(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Blog');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return back();
        }

        // Begin a database transaction
        \DB::beginTransaction();

        try {
            if ($request->action == 'delete') {
                $id = explode(',', $request->text2);
                if (isset($id) && count($id) > 0) {
                    foreach ($id as $ids) {
                        $questionId = ChatbotQuestionAnswer::where('group_id', $ids)->pluck('question_id')->toArray();
                        $answerId = ChatbotQuestionAnswer::where('group_id', $ids)->pluck('answer_id')->toArray();

                        ChatbotQuestion::whereIn("id", $questionId)->delete();
                        ChatbotAnswer::whereIn("id", $answerId)->delete();
                    }
                }
            }
            \DB::commit();

            Session::flash('success', 'Question Answer Delete Successfully !');
            return redirect()->back();
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            \DB::rollback();

            return redirect()->back()->with('error', 'Failed to delete questions and answers.');
        }
    }


    /**
     * Get all answer from database
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAnswer(Request $request)
    {
        // Validate the request data (optional but recommended)
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        // Retrieve the search term
        $query = $request->input('query');

        // Perform a search in the 'ChatbotQuestion' table (adjust the table/model as needed)
        $results = ChatbotAnswer::where('answer', 'like', '%' . $query . '%')->get();

        $html = "";
        if (count($results) > 0) {
            foreach ($results as $result) {
                $escapedAnswer = htmlspecialchars($result->answer, ENT_QUOTES);
                $html .= "<li onclick=\"selectAnswer($result->id, this)\" data-answer='$escapedAnswer'>$escapedAnswer</li>";
            }
        }

        // Return the results as a JSON response
        return response()->json(['status' => 'success', 'html' => $html]);
    }


    /**
     * Display unanswered question list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function unansweredQuestionsList()
    {
        $response = $this->botApiService->getLearningQuestions([
            'status' => 'open',
            'bot_type' => 'support',
        ]);

        $items = collect($response['items'] ?? []);
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $questions = new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('chatBot.unansweredQuestion.index', ['questions' => $questions]);
    }

    public function supportAnalytics()
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $weekStart = $now->copy()->startOfWeek();
        $dayStart = $now->copy()->startOfDay();

        $baseConversations = ChatConversation::query()->whereNull('store_id');
        $baseMessages = ChatMessage::query()->whereHas('conversation', function ($query) {
            $query->whereNull('store_id');
        });

        $totalConversations = (clone $baseConversations)->count();
        $openBotConversations = (clone $baseConversations)->whereNull('agent_id')->count();
        $assignedAgentConversations = (clone $baseConversations)->whereNotNull('agent_id')->count();
        $todayConversations = (clone $baseConversations)->where('created_at', '>=', $dayStart)->count();
        $monthConversations = (clone $baseConversations)->where('created_at', '>=', $monthStart)->count();

        $totalMessages = (clone $baseMessages)->count();
        $visitorMessages = (clone $baseMessages)->where('sender_type', 'visitor')->count();
        $agentMessages = (clone $baseMessages)->where('sender_type', 'agent')->count();
        $botMessages = (clone $baseMessages)->where('sender_type', 'bot')->count();
        $todayMessages = (clone $baseMessages)->where('created_at', '>=', $dayStart)->count();

        $registeredVisitors = ChatVisitor::query()->where('is_register', 1)->count();
        $guestVisitors = ChatVisitor::query()->where(function ($query) {
            $query->whereNull('is_register')->orWhere('is_register', 0);
        })->count();

        $monthlyUsage = AdminChatSupport::query()
            ->select('store_id', DB::raw('SUM(support) as total_support'))
            ->where('date', '>=', $monthStart->toDateString())
            ->groupBy('store_id')
            ->orderByDesc('total_support')
            ->limit(5)
            ->with('store:id,storename')
            ->get();

        $dailyMessageSeries = ChatMessage::query()
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw("SUM(CASE WHEN sender_type = 'visitor' THEN 1 ELSE 0 END) as visitor_count")
            ->selectRaw("SUM(CASE WHEN sender_type = 'agent' THEN 1 ELSE 0 END) as agent_count")
            ->selectRaw("SUM(CASE WHEN sender_type = 'bot' THEN 1 ELSE 0 END) as bot_count")
            ->whereHas('conversation', function ($query) {
                $query->whereNull('store_id');
            })
            ->where('created_at', '>=', $weekStart)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topAgents = ChatConversation::query()
            ->select('agent_id', DB::raw('COUNT(*) as total_conversations'))
            ->whereNull('store_id')
            ->whereNotNull('agent_id')
            ->groupBy('agent_id')
            ->orderByDesc('total_conversations')
            ->limit(5)
            ->with('agent:id,name')
            ->get();

        $openLearningCount = 0;
        try {
            $learningResponse = $this->botApiService->getLearningQuestions([
                'status' => 'open',
                'bot_type' => 'support',
            ]);
            $openLearningCount = count($learningResponse['items'] ?? []);
        } catch (\Throwable $exception) {
            report($exception);
        }

        return view('chatBot.analytics', [
            'summary' => [
                'total_conversations' => $totalConversations,
                'open_bot_conversations' => $openBotConversations,
                'assigned_agent_conversations' => $assignedAgentConversations,
                'today_conversations' => $todayConversations,
                'month_conversations' => $monthConversations,
                'total_messages' => $totalMessages,
                'visitor_messages' => $visitorMessages,
                'agent_messages' => $agentMessages,
                'bot_messages' => $botMessages,
                'today_messages' => $todayMessages,
                'registered_visitors' => $registeredVisitors,
                'guest_visitors' => $guestVisitors,
                'open_learning_count' => $openLearningCount,
            ],
            'monthlyUsage' => $monthlyUsage,
            'dailyMessageSeries' => $dailyMessageSeries,
            'topAgents' => $topAgents,
        ]);
    }


    /***
     * Show store new question answer page
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function unansweredQuestionsCreate($id)
    {
        try {
            $response = $this->botApiService->getLearningQuestion((int) $id);
            $question = $response['item'] ?? null;

            if (!empty($question)) {
                return view('chatBot.unansweredQuestion.create', ['question' => $question]);
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        Session::flash('error', 'Question not found!');
        return redirect()->route('chatBot.unansweredQuestions.list');
    }


    /**
     * Store unanswered question and answer
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unansweredQuestionsStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|integer',
            'manual_answer' => 'required|string',
            'training_content' => 'nullable|string',
            'add_to_training' => 'nullable|boolean',
        ], [
            "question_id.required" => "Question is required",
            "manual_answer.required" => "Answer is required",
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $trainingContent = trim((string) $request->input('training_content', ''));
            if ($trainingContent === '') {
                $originalQuestion = trim((string) $request->input('question_text', ''));
                $manualAnswer = trim((string) $request->input('manual_answer', ''));
                $trainingContent = "Customer asked: {$originalQuestion}\nSupport answer: {$manualAnswer}";
            }

            $this->botApiService->resolveLearningQuestion((int) $request->question_id, [
                'manual_answer' => (string) $request->manual_answer,
                'training_content' => $trainingContent,
                'add_to_training' => (bool) $request->boolean('add_to_training', true),
            ]);

            return redirect()->route('chatBot.unansweredQuestions.list')->with('success', 'Questions and answers saved successfully.');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Failed to save questions and answers.');
        }
    }


    /**
     *
     * Delete multiple unanswered question
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unansweredQuestionsActionChange(Request $request)
    {
        Session::flash('error', 'Bulk actions are not supported for support learning queue items.');
        return redirect()->back();
    }

    /**
     *  Unanswered question delete
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unansweredQuestionsDelete($id)
    {
        Session::flash('error', 'Delete is not supported for support learning queue items.');
        return redirect()->back();
    }


    /**
     * Show all bot conversation
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function botConversationList()
    {
        if (!canSuperStaffAccess('chat_assign')) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }
        $agents = User::where('type', 'superadmin')->orWhere('type', 'superstaff')->select(["id", "name", "type"])->get();

        $conversations = ChatConversation::with('visitor', 'visitor.user', 'visitor.user.store')
            ->whereNull('agent_id')
            ->whereNull("store_id")
            ->orderBy("id", "DESC")
            ->paginate(20);

        return view('chatBot.conversation.botConversation', ['conversations' => $conversations, 'agents' => $agents]);
    }


    /**
     * Show all agent conversation
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function agentConversationList()
    {
        if (!canSuperStaffAccess('chat_assign')) {
            return redirect()->back()->with('error', 'You do not have permission to access this page.');
        }

        $agents = User::where('type', 'superadmin')->orWhere('type', 'superstaff')->select(["id", "name", "type"])->get();

        $conversations = ChatConversation::with('visitor', 'agent', 'visitor.user', 'visitor.user.store')
            ->whereNotNull('agent_id')
            ->whereNull("store_id")
            ->orderBy("id", "DESC")
            ->paginate(20);

        return view('chatBot.conversation.agentConversation', ['conversations' => $conversations, 'agents' => $agents]);
    }


    public function conversationAssignAgent(Request $request)
    {
        $conversation_id = $request->conversation_id ?? "";
        $agent_id = $request->agent_id ?? "";

        if (is_null($conversation_id) || empty($conversation_id)) {
            Session::flash('error', 'Conversation ID missing!');
            return redirect()->back();
        }

        if (is_null($agent_id) || empty($agent_id)) {
            Session::flash('error', 'Agent ID missing!');
            return redirect()->back();
        }

        $conversation = ChatConversation::where("id", $conversation_id)->first();

        if (isset($conversation)) {
            $agent_id = $agent_id == "bot" ? NULL : $agent_id;
            $conversation->agent_id = $agent_id;
            $conversation->update();

            Session::flash('success', 'Successfully assign agent to conversation!');
            return redirect()->back();
        } else {
            Session::flash('error', 'Conversation ID missing!');
            return redirect()->back();
        }
    }


}
