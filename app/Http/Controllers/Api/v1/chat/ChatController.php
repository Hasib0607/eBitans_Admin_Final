<?php

namespace App\Http\Controllers\Api\v1\chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\UserResourceForChat;
use App\Http\Resources\VisitorResource;
use App\Models\AdminChatSupport;
use App\Models\ChatbotAnswer;
use App\Models\ChatbotQuestion;
use App\Models\ChatbotQuestionAnswer;
use App\Models\ChatbotUnansweredQuestion;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatVisitor;
use App\Models\Store;
use App\Models\User;
use App\Services\WhatsAppAutomation\BotApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function __construct(
        protected BotApiService $botApiService
    ) {
    }

    protected function isSupportAgent(): bool
    {
        return Auth::check() && in_array((string) Auth::user()->type, ['superadmin', 'superstaff', 'admin', 'staff'], true);
    }

    protected function getConversationWithVisitor(?int $conversationId): ?ChatConversation
    {
        if (!$conversationId) {
            return null;
        }

        return ChatConversation::with('visitor')->find($conversationId);
    }

    protected function visitorOwnsConversation(Request $request, ?ChatConversation $conversation): bool
    {
        if (!$conversation || !$conversation->visitor) {
            return false;
        }

        $sessionToken = (string) $request->input('session_token', '');
        if ($sessionToken !== '' && hash_equals((string) $conversation->visitor->session_token, $sessionToken)) {
            return true;
        }

        return Auth::check()
            && !$this->isSupportAgent()
            && (int) $conversation->visitor->user_id === (int) Auth::id();
    }

    protected function agentOwnsConversation(?ChatConversation $conversation): bool
    {
        return $this->isSupportAgent()
            && $conversation
            && !is_null($conversation->agent_id)
            && (int) $conversation->agent_id === (int) Auth::id();
    }

    protected function unauthorizedResponse(string $message = 'Unauthorized')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 403);
    }

    public function welcome(Request $request)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);

        return ['status' => 'Message Sent!'];
    }


    /**
     * Create conversation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createConversation(Request $request)
    {
        if (!$this->isSupportAgent()) {
            return $this->unauthorizedResponse();
        }

        try {
            $visitor = null;
            if (!is_null($request->user_id) && !empty($request->user_id)) {
                $visitor = ChatVisitor::where(function ($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                })->first();
            }

            if (is_null($visitor)) {
                $visitor = new ChatVisitor();
                $visitor->visitor_name = $request->visitor_name ?? NULL;
                $visitor->visitor_email = $request->visitor_email ?? NULL;
                $visitor->visitor_phone = $request->visitor_phone ?? NULL;
                $visitor->image = $request->image ?? NULL;

                if (!is_null($request->user_id) && !empty($request->user_id)) {
                    $visitor->user_id = $request->user_id ?? NULL;
                    $visitor->is_register = 1;
                }

                $visitor->save();
            }

            $agent_id = NULL;
            if (Auth::check()) {
                $agent_id = Auth::id() ?? NULL;
            }

            $conversation = ChatConversation::where(function ($query) use ($visitor) {
                $query->where('visitor_id', $visitor->id);
            })->first();

            if (!is_null($conversation) && is_null($conversation->agent_id)) {
                $conversation->agent_id = $agent_id;
                $conversation->save();
            }

            if (is_null($conversation)) {
                $conversation = new ChatConversation();
                $conversation->visitor_id = $visitor->id;
                $conversation->agent_id = $agent_id ?? NULL;
                $conversation->sender_type = $request->creator_type ?? "agent";
                $conversation->type = $request->type ?? 0;
                $conversation->lang = $request->lang ?? 0;
                $conversation->save();
            }

            return response()->json(["status" => true, "message" => "Conversation created successfully!", "data" => $conversation]);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => "Something went wrong!"]);
        }

    }


    /**
     * Get all conversation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversation(Request $request)
    {
        if (!$this->isSupportAgent()) {
            return $this->unauthorizedResponse();
        }

        $user_id = Auth::id() ?? NULL;

        // Paginate the query
        $perPage = $request->input('per_page', 20); // Default to 20 items per page
        $page = $request->input('page', 1); // Default to page 1
        $search = $request->input('search', '');

        $query = ChatConversation::with(['visitor.user', 'agent'])->where('agent_id', $user_id);

        // Apply search filter
        if (!empty($search)) {
            $query->whereHas('visitor', function ($q) use ($search) {
                $q->where('visitor_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('visitor_email', 'LIKE', '%' . $search . '%')
                    ->orWhere('visitor_phone', 'LIKE', '%' . $search . '%');
            });
        }

        $conversations = $query->orderBy("updated_at", "DESC")->paginate($perPage, ['*'], 'page', $page);

        // Transform paginated data
        $formattedConversations = $conversations->map(function ($conversation) {
            return [
                'conversation' => new ConversationResource($conversation),
                'visitor' => new VisitorResource($conversation->visitor),
                'agent' => new UserResourceForChat($conversation->agent),
            ];
        });

        // Return paginated results with custom transformation
        return response()->json([
            "status" => true,
            "message" => "Successful",
            'data' => $formattedConversations,
            'current_page' => $conversations->currentPage(),
            'per_page' => $conversations->perPage(),
            'total' => $conversations->total(),
            'last_page' => $conversations->lastPage(),
        ]);

    }


    /**
     * Get conversation message by conversation ID
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversationMessage(Request $request, $id)
    {
        if (!is_null($id) && !empty($id)) {
            $conversation = $this->getConversationWithVisitor((int) $id);

            if (is_null($conversation)) {
                return response()->json([
                    "status" => false,
                    "message" => "Conversation not found",
                    "data" => [],
                ], 404);
            }

            if (!$this->agentOwnsConversation($conversation) && !$this->visitorOwnsConversation($request, $conversation)) {
                return $this->unauthorizedResponse();
            }

                $perPage = $request->input('per_page', 10); // Default to 20 items per page
                $page = $request->input('page', 1); // Default to page 1

                $messages = ChatMessage::where('conversation_id', $id)
//                    ->where('isDelete', 0)
                    ->orderBy('id', 'DESC')
                    ->paginate($perPage, ['*'], 'page', $page);

                return response()->json([
                    "status" => true,
                    "message" => "Successful",
                    'data' => ChatMessageResource::collection($messages),
                    'current_page' => $messages->currentPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                    'last_page' => $messages->lastPage(),
                ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Conversation ID missing",
                "data" => [],
            ]);
        }

    }


    /**
     * Get single conversation id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversationByVisitorId($id)
    {
        if (!$this->isSupportAgent()) {
            return $this->unauthorizedResponse();
        }

        $user_id = Auth::id() ?? NULL;

        $conversations = ChatConversation::with(['visitor.user', 'agent'])->where('agent_id', $user_id)->where('visitor_id', $id)->get();

        if (!is_null($conversations)) {
            // Transform paginated data
            $formattedConversations = $conversations->map(function ($conversation) {
                return [
                    'conversation' => new ConversationResource($conversation),
                    'visitor' => new VisitorResource($conversation->visitor),
                    'agent' => new UserResourceForChat($conversation->agent),
                ];
            });

            // Return paginated results with custom transformation
            return response()->json([
                "status" => true,
                "message" => "Successful",
                'data' => $formattedConversations,
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Data not found",
                "data" => [],
            ]);
        }

    }

    /**
     * Message mask as read
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function massageMarkAsRead(Request $request)
    {
        if (!is_null($request->id) && !empty($request->id)) {
            $message = ChatMessage::where('id', $request->id)->first();

            if (!is_null($message)) {
                $conversation = $this->getConversationWithVisitor((int) $message->conversation_id);
                if (!$this->agentOwnsConversation($conversation) && !$this->visitorOwnsConversation($request, $conversation)) {
                    return $this->unauthorizedResponse();
                }

                $message->seen_status = 1;
                $message->save();

                ChatConversation::where("id", $message->conversation_id)->update(["seen_status" => 1]);

                return response()->json([
                    "status" => true,
                    "message" => "Successful",
                    'data' => $message,
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Message not found",
                    "data" => [],
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Message ID missing",
                "data" => [],
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function massageSend(Request $request)
    {
        // Create a validator instance
        $validator = Validator::make($request->all(), [
            'conversation_id' => 'required|integer',
            'agent_id' => 'nullable|integer',
            'sender_type' => 'required|string',
            'message' => 'nullable|string'
        ], [
            "conversation_id.required" => "Conversation id is required",
            "sender_type.required" => "Sender type is required",
            "message.required" => "Message is required!",
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Invalid request",
            ]);
        }

        if (empty($request->message) && !$request->hasFile('images')) {
            return response()->json([
                "status" => false,
                "message" => "Invalid request",
            ]);
        }

        $conversation = $this->getConversationWithVisitor((int) $request->conversation_id);
        if (is_null($conversation)) {
            return response()->json([
                "status" => false,
                "message" => "Conversation not found",
            ], 404);
        }

        $senderType = (string) $request->sender_type;
        $senderId = null;

        if ($senderType === 'agent') {
            if (!$this->isSupportAgent()) {
                return $this->unauthorizedResponse();
            }

            if (!$this->agentOwnsConversation($conversation)) {
                if (is_null($conversation->agent_id)) {
                    $conversation->agent_id = Auth::id();
                } else {
                    return $this->unauthorizedResponse();
                }
            }

            $senderId = Auth::id();
        } elseif ($senderType === 'visitor') {
            if (!$this->visitorOwnsConversation($request, $conversation)) {
                return $this->unauthorizedResponse();
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Invalid sender type",
            ], 422);
        }

        $files = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
//                $originName = $image->getClientOriginalName();
//                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $image->getClientOriginalExtension();
                $fileName = rand(8, 16) . Carbon::now()->timestamp . '.' . $extension;
                $image->move(public_path('chat/chatFile'), $fileName);
                $files[] = $fileName;
            }
        }


        $file = implode(',', $files);

        $messageType = "text";
        if (!empty($request->message) && !empty($file)) {
            $messageType = "mix";
        } elseif (empty($request->message) && !empty($file)) {
            $messageType = "file";
        }

        $message = new ChatMessage();
        $message->conversation_id = $request->conversation_id;
        $message->sender_type = $senderType;
        $message->sender_id = $senderId;
        $message->content = $request->message;
        $message->file_url = $file ?? NULL;
        $message->message_type = $messageType;
        $message->file_type = "image";
        $message->save();

        if ($messageType == "text" || $messageType == "mix") {
            $conversation->last_message = $request->message;
        } elseif ($messageType == "file") {
            $conversation->last_message = $files[0];
        }
        $conversation->sender_type = $senderType;
        $conversation->seen_status = 0;

        $conversation->update();

        if ($conversation->sender_type == "visitor") {
            $linkURL = route("chat.index");
            if (is_null($conversation->store_id)) {
                $notificationData = [
                    "title" => "New Chat Message - " . formatDateWithTime($conversation->updated_at),
                    "body" => $conversation->last_message ?? NULL,
                    "type" => "message",
                    "user_type" => "superadmin",
                    "link" => $linkURL,
                ];
            } else {
                $notificationData = [
                    "title" => "New Chat Message - " . formatDateWithTime($conversation->updated_at),
                    "body" => $conversation->last_message ?? NULL,
                    "type" => "message",
                    "user_type" => "admin",
                    "store_id" => $conversation->store_id,
                    "link" => $linkURL,
                ];
            }

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                updateNotification($notificationData, $conversation->id);
            }
        }


        $fileURL = [];
        if (count($files)) {
            foreach ($files as $file) {
                $fileURL[] = asset('/chat/chatFile/' . $file);
            }
        }

        $response = null;
        $timeout = null;
        $responseTimeout = null;

        if (is_null($conversation->agent_id)) {
            // Chat bot response here...
            $responseArray = $this->botResponse($request, $messageType, $conversation);

            if (isset($responseArray['conversation'])) {
                $conversation = $responseArray['conversation'];
            }

            if (isset($responseArray['message'])) {
                $responseTimeout = $this->calculateTypingTime($responseArray['message']);
                $response = new ChatMessageResource($responseArray['message']);
            }

            $timeout = 5000;
        }

        // Check chat is enable or disable
        $isEnableChat = $this->isAbleToChat($conversation);

        $endSessionTime = 5 * (60 * 1000);

        return response()->json([
            "status" => true,
            "message" => "Successfully send message!",
            "data" => [
                "conversation" => $conversation,
                "message" => new ChatMessageResource($message),
                "response" => $response,
                "responseTimeout" => $responseTimeout,
                "timeOut" => $timeout,
                "endSessionTime" => $endSessionTime,
                "isEnableChat" => $isEnableChat,
            ],
        ]);
    }

    /**
     * This is a bot response function
     *
     * @param $request
     * @param $messageType
     * @param $conversation
     * @return array
     */
    public function botResponse($request, $messageType, $conversation)
    {
        $botMessageType = $messageType;

        $answer = "I'm sorry, I don't have an answer for that right now.";
        if ((int) ($conversation->lang ?? 0) === 1) {
            $answer = "দুঃখিত, আমার কাছে এখন এর কোন উত্তর নেই।";
        }

        if ($messageType === "text" && !empty($request->message)) {
            $botMessageType = "text";
            $sessionId = (string) optional($conversation->visitor)->session_token;
            if ($sessionId === '') {
                $sessionId = 'support-chat-' . $conversation->id;
            }

            try {
                $botResponse = $this->botApiService->sendSupportChat($sessionId, (string) $request->message);
                $botReply = trim((string) ($botResponse['reply'] ?? ''));

                if ($botReply !== '') {
                    $answer = $botReply;
                }
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return $this->saveBotResponse($request, $answer, $botMessageType);
    }

    /**
     *
     * Get visitor conversation create if not exist
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVisitorConversation(Request $request)
    {
        try {
            $user = null;
            $sessionToken = (string) $request->input('session_token', '');

            if (!empty($sessionToken)) {
                $visitor = ChatVisitor::where('session_token', $sessionToken)->first();
                $user = $visitor?->user;
            } elseif (!is_null($request->userID) && !empty($request->userID)) {
                $user = User::where('id', $request->userID)->first();
            }

            $country_code = $request->countryCode ?? "BD";

            if (!is_null($user)) {
                $visitor = ChatVisitor::where(function ($query) use ($user, $request) {
                    $query->where('user_id', $user->id)->orWhere('session_token', $request->session_token);
                })->first();

                if (is_null($visitor)) {
                    $visitor = new ChatVisitor();
                    $visitor->visitor_name = $user->name ?? NULL;
                    $visitor->visitor_email = $user->email ?? NULL;
                    $visitor->visitor_phone = $user->phone ?? NULL;
                    $visitor->image = $user->image ?? NULL;
                    $visitor->user_id = $user->id ?? NULL;
                    $visitor->is_register = 1;
                    $visitor->save();
                }
            } else {
                $visitor = ChatVisitor::where(function ($query) use ($user, $request) {
                    $query->where('session_token', $request->session_token);
                })->first();

                if (is_null($visitor)) {
                    $msg = null;
                    if (is_null($request->visitor_name) || empty($request->visitor_name)) {
                        $msg = "Please enter your name.";
                    } else if (!is_null($request->visitor_phone) && !empty($request->visitor_phone)) {
                        if (!preg_match('/^(?:\+?\d{1,3})?[1-9]\d{5,14}$/', $request->visitor_phone)) {
                            $msg = "Invalid phone number.";
                        }
                    } else {
                        if (!filter_var($request->visitor_email, FILTER_VALIDATE_EMAIL)) {
                            $msg = "Invalid email address.";
                        }
                    }

                    // Parse the phone number to get only the local number (without country code)
                    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                    $parsedNumber = $phoneUtil->parse($request->visitor_phone, $country_code);
                    $request->visitor_phone = $phoneUtil->getNationalSignificantNumber($parsedNumber);

                    if ($country_code == "BD") {
                        $request->visitor_phone = '0' . $request->visitor_phone;
                    }

                    if (!is_null($msg)) {
                        return response()->json(["status" => false, "message" => $msg]);
                    }

                    $visitor = new ChatVisitor();
                    $visitor->visitor_name = $request->visitor_name ?? NULL;
                    $visitor->visitor_email = Str::lower($request->visitor_email) ?? NULL;
                    $visitor->visitor_phone = $request->visitor_phone ?? NULL;
                    $visitor->save();
                } else {
                    $flag = false;
                    if (!empty($request->visitor_name)) {
                        $flag = true;
                        $visitor->visitor_name = $request->visitor_name ?? $visitor->visitor_name;
                    }
                    if (!empty($request->visitor_email)) {
                        $flag = true;
                        $visitor->visitor_email = Str::lower($request->visitor_email) ?? $visitor->visitor_email;
                    }
                    if (!empty($request->visitor_phone)) {
                        $flag = true;
                        $visitor->visitor_phone = $request->visitor_phone ?? $visitor->visitor_phone;
                    }

                    if ($flag) {
                        $visitor->update();
                    }
                }
            }

            // Get conversation
            $conversation = ChatConversation::where(function ($query) use ($visitor) {
                $query->where('visitor_id', $visitor->id);
            })->first();

            if (is_null($conversation)) {
                $conversation = new ChatConversation();
                $conversation->visitor_id = $visitor->id;
                $conversation->agent_id = NULL;
                $conversation->sender_type = "visitor";
                $conversation->type = $request->type ?? 0;
                $conversation->lang = $request->lang ?? 0;
                $conversation->save();

                $message = new ChatMessage();
                $message->conversation_id = $conversation->id;
                $message->sender_type = "bot";
                $message->content = "Hello, how can i help you?";
                $message->save();

                $conversation->sender_type = "bot";
                $conversation->last_message = $message->content;
                $conversation->update();
            } else {
                $conversation->type = $request->type ?? $conversation->type;
                $conversation->lang = $request->lang ?? $conversation->lang;
                $conversation->update();
            }

            $perPage = $request->input('per_page', 10); // Default to 20 items per page
            $page = $request->input('page', 1); // Default to page 1


            // Delete previous message if last chat is one hour ago
//            $oneHourAgo = Carbon::now()->subHour();
            $fifteenMinutesAgo = Carbon::now()->subMinutes(15);
            if ($conversation->updated_at <= $fifteenMinutesAgo) {
                $response = $this->softDeleteMessage($conversation->id);

                if ($response) {
                    $conversation = ChatConversation::where("id", $conversation->id)->first();
                    if (isset($conversation)) {
                        $conversation->agent_id = NULL;
                        $conversation->update();
                    }
                }
            }

            // Check chat is enable or disable
            $isEnableChat = $this->isAbleToChat($conversation);

            $messages = ChatMessage::where('conversation_id', $conversation->id)
                ->where('isDelete', 0)
                ->orderBy('id', 'DESC')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                "status" => true,
                "message" => "Successful",
                'visitor' => new VisitorResource($visitor),
                'conversation' => new ConversationResource($conversation),
                'messages' => ChatMessageResource::collection($messages),
                'current_page' => $messages->currentPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
                'last_page' => $messages->lastPage(),
                'isEnableChat' => $isEnableChat,
            ]);

        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => "Something went wrong!"]);
        }
    }

    /**
     *  Delete visitor conversation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteVisitorConversation(Request $request)
    {
        try {
            $conversation_id = $request->input('conversation_id') ?? "";

            if (is_null($conversation_id) || empty($conversation_id)) {
                return response()->json(["status" => false, "message" => "Conversation ID missing!"]);
            }

            $conversation = $this->getConversationWithVisitor((int) $conversation_id);
            if (!$this->visitorOwnsConversation($request, $conversation)) {
                return $this->unauthorizedResponse();
            }

            $response = $this->softDeleteMessage($conversation_id);

            if ($response) {
                if (isset($conversation)) {
                    $conversation->agent_id = NULL;
                    $conversation->update();
                }

                $msg = "Thanks for chatting with us.";

                if ($conversation->lang == 1) {
                    // Bangla message
                    $msg = "আমাদের সাথে চ্যাট করার জন্য ধন্যবাদ।";
                }

                // Check chat is enable or disable
                $isEnableChat = $this->isAbleToChat($conversation);


                return response()->json([
                    "status" => true,
                    "message" => $msg,
                    "isEnableChat" => $isEnableChat
                ]);
            } else {
                return response()->json(["status" => true, "message" => "Conversation not deleted!"]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => "Something went wrong!"]);
        }

    }


    /**
     * Delete chat message with media
     *
     * @param $conversation_id
     * @return bool
     */
    public function deleteConversationMessageByID($conversation_id)
    {
        try {
            $sevenDaysAgo = Carbon::now()->subDays(7);

            $chatMessages = ChatMessage::where('conversation_id', $conversation_id)->where('updated_at', "<=", $sevenDaysAgo)->select('id', 'file_url')->get();

            if ($chatMessages->count()) {
                foreach ($chatMessages as $message) {
                    $media = $message->file_url;
                    if ($media) {
                        $files = explode(",", $media);
                        foreach ($files as $file) {
                            $filePath = public_path("chat/chatFile/" . trim($file));
                            if (file_exists($filePath)) {
                                deleteFile($filePath);
                            }
                        }
                    }

                    $message->delete();
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * Delete chat message with media
     *
     * @param $conversation_id
     * @return bool
     */
    public function softDeleteMessage($conversation_id)
    {
        try {
            $updatedRows = ChatMessage::where('conversation_id', $conversation_id)->where('isDelete', 0)->update(["isDelete" => 1]);

            // Delete Chat message
            $this->deleteConversationMessageByID($conversation_id);

            if (is_numeric($updatedRows) && $updatedRows > 0) {
                // Update or create admin chat support
                $this->createOrUpdateAdminChatSupport($conversation_id);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * Update or create admin chat support
     *
     * @param $conversation_id
     * @return bool
     */
    public function createOrUpdateAdminChatSupport($conversation_id)
    {
        try {
            // Get store ID By Conversation ID
            $storeID = getStoreByConversationID($conversation_id);

            // Get current year and month
            $currentDate = Carbon::now()->format("Y-m-d");
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;

            $adminChatSupport = AdminChatSupport::where("store_id", $storeID)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->first();

            if (isset($adminChatSupport)) {
                $adminChatSupport->support = (int)$adminChatSupport->support + 1;
                $adminChatSupport->update();
            } else {
                $adminChatSupport = new AdminChatSupport();
                $adminChatSupport->store_id = $storeID;
                $adminChatSupport->date = $currentDate;
                $adminChatSupport->support = 1;
                $adminChatSupport->save();
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * Check user monthly chat support
     *
     * @param $conversation
     * @return bool
     */
    public function isAbleToChat($conversation)
    {
        $conversation_id = $conversation->id;

        if (is_null($conversation->agent_id)) {
            return true;
        }

        $isRegister = ChatVisitor::where("id", $conversation->visitor_id ?? "")->first();
        if (isset($isRegister) && $isRegister->register == 0) {
            return true;
        }

        $numberOfSupport = 0;
        $monthly_chat_support = 0;

        // Get store ID By Conversation ID
        $storeID = getStoreByConversationID($conversation_id);
        $store = Store::with("plan")->where("id", $storeID)->first();

        if (isset($store)) {
            $monthly_chat_support = $store->plan->monthly_chat_support ?? 0;
        }

        // Get current year and month
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $adminChatSupport = AdminChatSupport::where("store_id", $storeID)
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->first();

        if (isset($adminChatSupport)) {
            $numberOfSupport = $adminChatSupport->support ?? 0;
        }

        if ($numberOfSupport >= $monthly_chat_support) {
            return false;
        }

        return true;
    }


    /**
     * Search question and find answer
     *
     * @param $request
     * @param $type
     * @param $lang
     * @return array
     */
    public function searchQuestionAnswer($questionId, $type, $lang)
    {
        // Retrieve the IDs of the answers related to the matched questions
        $answerIDs = ChatbotQuestionAnswer::where('question_id', $questionId)->pluck('answer_id');

        // Check if there are any related answers
        if ($answerIDs->isEmpty()) {
            return []; // Return empty array if no answers are found
        }

        // Retrieve the actual answers
//        $answer = ChatbotAnswer::whereIn('id', $answerIDs)
//            ->where("type", $type)
//            ->where("lang", $lang)
//            ->inRandomOrder()
//            ->first();

        $answerQuery = ChatbotAnswer::whereIn('id', $answerIDs)
            ->inRandomOrder();

        // Apply the 'type' condition based on 'type_both' value
        if ($type !== null) {
            $answerQuery->where(function ($query) use ($type) {
                // If 'type_both' is 1, match both type = 1 or type = 0
                $query->where('type_both', 1)
                    ->where(function ($subQuery) use ($type) {
                        // If type_both is 1, allow either type 0 or type 1
                        $subQuery->where('type', 0)->orWhere('type', 1);
                    });

                // If 'type_both' is 0, apply the 'type' condition normally
                $query->orWhere(function ($subQuery) use ($type) {
                    $subQuery->where('type_both', 0)
                        ->where('type', $type);
                });
            });
        }

        // Apply the 'lang' condition based on 'lang_both' value
        if ($lang !== null) {
            $answerQuery->where(function ($query) use ($lang) {
                // If 'lang_both' is 1, match both lang = 1 or lang = 0
                $query->where('lang_both', 1)
                    ->where(function ($subQuery) use ($lang) {
                        $subQuery->where('lang', 0)->orWhere('lang', 1);
                    });

                // If 'lang_both' is 0, apply the 'lang' condition normally
                $query->orWhere(function ($subQuery) use ($lang) {
                    $subQuery->where('lang_both', 0)
                        ->where('lang', $lang);
                });
            });
        }

        $answer = $answerQuery->first();


        return $answer->answer ?? null;
    }


    /**
     * Get message bot response
     *
     * @param $request
     * @param $type
     * @param $lang
     * @return string
     */
    public function getMessageQuestion($request)
    {
        $userMessage = $request->message;
        $predefinedQuestions = ChatbotQuestion::all();

        return $this->matchUsingCosineSimilarity($userMessage, $predefinedQuestions);
    }


    /**
     * vectorize text
     *
     * @param $text
     * @return array
     */
    public function vectorize($text)
    {
        // Split text into words using space as the delimiter
        $words = preg_split('/\s+/u', strtolower($text));

        $vector = [];

        // Loop through words and build the vector
        foreach ($words as $word) {
            // Ensure the word is not empty after splitting
            if (!empty($word)) {
                // Count occurrences of each word (considering both English and Bangla)
                $vector[$word] = isset($vector[$word]) ? $vector[$word] + 1 : 1;
            }
        }

        return $vector;
    }


    /**
     * cosineSimilarity algorithm
     *
     * @param $vec1
     * @param $vec2
     * @return float|int
     */
    public function cosineSimilarity($vec1, $vec2)
    {
        $dotProduct = 0;
        $vec1Magnitude = 0;
        $vec2Magnitude = 0;

        foreach ($vec1 as $key => $value) {
            if (isset($vec2[$key])) {
                $dotProduct += $value * $vec2[$key];
            }
            $vec1Magnitude += pow($value, 2);
        }

        foreach ($vec2 as $value) {
            $vec2Magnitude += pow($value, 2);
        }

        $vec1Magnitude = sqrt($vec1Magnitude);
        $vec2Magnitude = sqrt($vec2Magnitude);

        if ($vec1Magnitude * $vec2Magnitude == 0) {
            return 0;
        }

        return $dotProduct / ($vec1Magnitude * $vec2Magnitude);
    }

    /**
     * Apply cosineSimilarity match
     *
     * @param $userMessage
     * @param $predefinedQuestions
     * @return string
     */
    public function matchUsingCosineSimilarity($userMessage, $predefinedQuestions)
    {
        // Normalize the user message
        $normalizedUserMessage = $this->normalizeString($userMessage);
        $userVector = $this->vectorize($normalizedUserMessage);

        $bestMatch = '';
        $highestSimilarity = 0;

        foreach ($predefinedQuestions as $question) {
            // Normalize the question before vectorizing
            $normalizedQuestion = $this->normalizeString($question->question);
            $questionVector = $this->vectorize($normalizedQuestion);

            $similarity = $this->cosineSimilarity($userVector, $questionVector);

            if ($similarity > $highestSimilarity) {
                $highestSimilarity = $similarity;
                $bestMatch = $question->id;
            }
        }

        // Return the answer with the highest similarity
        return $bestMatch ?: null;
    }


    private function normalizeString($string)
    {
        // Convert to lowercase (for both English and Bangla)
        $string = mb_strtolower($string, 'UTF-8');

        // Remove non-alphanumeric characters but allow spaces (for both languages)
        $string = preg_replace('/[^\p{L}\s]/u', '', $string);

        // Return trimmed string
        return trim($string);
    }


    /**
     * Save bot message response
     *
     * @param $request
     * @param $answer
     * @param $messageType
     * @return array
     */
    public function saveBotResponse($request, $answer, $messageType)
    {
        DB::beginTransaction();
        try {
            $delyTime = (int)$this->calculateTypingTime($answer);
            if ($delyTime) {
                $delyTime = round($delyTime / 1000);
            } else {
                $delyTime = 25;
            }

            $message = new ChatMessage();
            $message->conversation_id = $request->conversation_id;
            $message->sender_type = "bot";
            $message->content = $answer;
            $message->message_type = $messageType;
            $message->created_at = Carbon::now()->addSeconds($delyTime);
            $message->save();

            $conversation = ChatConversation::where('id', $request->conversation_id)->first();
            $conversation->last_message = $answer;
            $conversation->sender_type = "bot";
            $conversation->update();

            DB::commit();

            return ["message" => $message, "conversation" => $conversation];
        } catch (\Exception $e) {
            DB::rollBack();
            return [];
        }
    }


    /**
     * Calculate answer response time
     *
     *
     * @param $inputString
     * @return float
     */
    public function calculateTypingTime($inputString = "")
    {
        // Average typing speed in words per minute (WPM)
        $averageWPM = 200;

        $lang = $this->detectLanguage($inputString);

        if (isset($lang) && !is_null($lang)) {
            if ($lang == "english") {
                $averageWPM = 300;
                $wordCount = str_word_count($inputString);
            } elseif ($lang == "bengali") {
                $wordCount = $this->countBanglaWords($inputString);
            } else {
                return 35000;
            }
        } else {
            return 35000;
        }


        // Calculate the time in minutes
        $timeInMinutes = $wordCount / $averageWPM;

        // Convert time to seconds
        $timeInSeconds = $timeInMinutes * 60;

        $timeInMiliSeconds = round($timeInSeconds) * 1000; // Return rounded seconds
        if ($timeInMiliSeconds < 1000) {
            $timeInMiliSeconds = 1000;
        }

        return $timeInMiliSeconds ?? 35000;
    }


    /**
     * Count bangla word
     * @param $inputString
     * @return int
     */
    public function countBanglaWords($inputString = "")
    {
        // Ensure input is a string (in case it's an array)
        if (is_array($inputString)) {
            $inputString = implode(" ", $inputString);  // Join array elements into a single string
        }

        // Match sequences of Bangla characters (Unicode range for Bengali script)
        preg_match_all('/[\p{Bengali}]+/u', $inputString, $matches);

        // Return the number of matched "words" in the text
        return count($matches[0]);
    }

    /**
     * Languages Detect function
     *
     * @param $inputString
     * @return mixed
     */
    public function detectLanguage($inputString = "")
    {
        // Create a language detection object
        $languageDetect = new \Text_LanguageDetect();

        // Detect the language of the input string
        $result = $languageDetect->detectSimple($inputString);

        return $result;
    }

    /**
     *
     * Save new question for chatbot
     *
     * @param $request
     * @return void
     */
    public function saveNewQuestion($request)
    {
        $userMessage = $request->message;

        // Check if the question already exists
        $existingQuestion = ChatbotQuestion::where('question', $userMessage)->first();
        $existingUnansweredQuestion = ChatbotUnansweredQuestion::where('question', $userMessage)->first();

        if (!$existingQuestion && !$existingUnansweredQuestion && !empty($userMessage)) {
            // If the question does not exist, save the new question
            $question = new ChatbotUnansweredQuestion();
            $question->question = $userMessage;
            $question->save();
        }
    }


    /**
     * Update conversation type and language
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateConversationUserdata(Request $request)
    {
        try {
            $conversation_id = $request->conversation_id ?? NULL;

            if (is_null($conversation_id) || empty($conversation_id)) {
                return response()->json([
                    "status" => false,
                    "message" => "Conversation ID Required",
                    "data" => [],
                ]);
            }

            $conversation = $this->getConversationWithVisitor((int) $conversation_id);
            if (!$this->visitorOwnsConversation($request, $conversation)) {
                return $this->unauthorizedResponse();
            }

            $type = $request->type ?? "";
            $lang = $request->lang ?? "";

            ChatConversation::where("id", $conversation_id)->update(["type" => $type, "lang" => $lang]);

            return response()->json([
                "status" => true,
                "message" => "Successful",
                'data' => [],
            ]);

        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => "Something went wrong!"]);
        }
    }

}
