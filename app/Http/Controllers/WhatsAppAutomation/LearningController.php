<?php

namespace App\Http\Controllers\WhatsAppAutomation;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppAutomation\BotApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function __construct(
        protected BotApiService $botApiService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $query = $request->only([
            'status',
            'bot_type',
        ]);

        return response()->json(
            $this->botApiService->getLearningQuestions($query)
        );
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->botApiService->getLearningQuestion($id)
        );
    }

    public function resolve(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'manual_answer' => ['required', 'string'],
            'training_content' => ['nullable', 'string'],
            'add_to_training' => ['nullable', 'boolean'],
        ]);

        return response()->json(
            $this->botApiService->resolveLearningQuestion($id, [
                'manual_answer' => $validated['manual_answer'],
                'training_content' => $validated['training_content'] ?? '',
                'add_to_training' => (bool) ($validated['add_to_training'] ?? true),
            ])
        );
    }

    public function replyFeedbackIndex(Request $request): JsonResponse
    {
        $query = $request->only([
            'bot_type',
            'verdict',
            'intent_key',
            'has_preferred_reply',
            'approval_status',
            'limit',
        ]);

        return response()->json(
            $this->botApiService->getReplyFeedbackList($query)
        );
    }

    public function replyFeedbackAnalytics(Request $request): JsonResponse
    {
        $botType = (string) ($request->query('bot_type', 'sales') ?: 'sales');

        return response()->json(
            $this->botApiService->getReplyFeedbackLearningAnalytics($botType)
        );
    }

    public function updateReplyFeedbackApproval(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'approval_status' => ['required', 'string', 'in:pending,approved_guidance,approved_template,approved_exact,denied'],
            'approved_by' => ['nullable', 'string'],
            'approval_note' => ['nullable', 'string'],
        ]);

        return response()->json(
            $this->botApiService->updateReplyFeedbackApproval($id, [
                'approval_status' => $validated['approval_status'],
                'approved_by' => $validated['approved_by'] ?? '',
                'approval_note' => $validated['approval_note'] ?? '',
            ])
        );
    }
}
