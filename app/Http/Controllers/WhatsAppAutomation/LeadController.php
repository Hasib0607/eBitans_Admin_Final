<?php

namespace App\Http\Controllers\WhatsAppAutomation;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppAutomation\BotApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        protected BotApiService $botApiService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $auth = $request->attributes->get('whatsapp_auth', []);
        $query = $request->only([
            'page',
            'limit',
            'status',
            'bot_type',
            'tag',
            'search',
            'follow_up_required',
        ]);

        return response()->json([
            'success' => true,
            'auth' => [
                'uid' => $auth['uid'] ?? null,
                'name' => $auth['name'] ?? null,
                'email' => $auth['email'] ?? null,
                'role' => $auth['role'] ?? null,
                'permissions' => $auth['permissions'] ?? [],
                'exp' => $auth['exp'] ?? null,
            ],
            'bot_leads' => $this->botApiService->getLeads($query),
        ]);
    }

    public function show(Request $request, string $sessionId): JsonResponse
    {
        $auth = $request->attributes->get('whatsapp_auth', []);
        $payload = $this->botApiService->getLead($sessionId, compact: true);

        return response()->json([
            'success' => true,
            'auth' => [
                'uid' => $auth['uid'] ?? null,
                'name' => $auth['name'] ?? null,
                'email' => $auth['email'] ?? null,
                'role' => $auth['role'] ?? null,
                'permissions' => $auth['permissions'] ?? [],
                'exp' => $auth['exp'] ?? null,
            ],
            'lead' => $payload['lead'] ?? null,
            'handoff' => [
                'success' => true,
                'session_id' => $payload['session_id'] ?? $sessionId,
                'handoff' => $payload['handoff'] ?? null,
            ],
        ]);
    }

    public function updateStatus(Request $request, string $sessionId): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
        ]);

        return response()->json(
            $this->botApiService->updateLeadStatus($sessionId, $validated['status'])
        );
    }

    public function tags(string $sessionId): JsonResponse
    {
        return response()->json(
            $this->botApiService->getLeadTags($sessionId)
        );
    }

    public function assignTag(Request $request, string $sessionId): JsonResponse
    {
        $validated = $request->validate([
            'tag_name' => ['required', 'string'],
        ]);

        return response()->json(
            $this->botApiService->assignLeadTag($sessionId, $validated['tag_name'])
        );
    }

    public function removeTag(string $sessionId, string $tagName): JsonResponse
    {
        return response()->json(
            $this->botApiService->removeLeadTag($sessionId, $tagName)
        );
    }

    public function allTags(): JsonResponse
    {
        return response()->json(
            $this->botApiService->getAllTags()
        );
    }

    public function createTag(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        return response()->json(
            $this->botApiService->createTag(
                $validated['name'],
                $validated['description'] ?? ''
            )
        );
    }
    public function followupPlans(string $sessionId): JsonResponse
    {
        return response()->json(
            $this->botApiService->getFollowupPlans([
                'session_id' => $sessionId,
            ])
        );
    }

    public function createFollowupPlan(Request $request, string $sessionId): JsonResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string'],
            'note' => ['nullable', 'string'],
            'scheduled_for' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
        ]);

        return response()->json(
            $this->botApiService->createFollowupPlan([
                'session_id' => $sessionId,
                'reason' => $validated['reason'],
                'note' => $validated['note'] ?? '',
                'scheduled_for' => $validated['scheduled_for'] ?? null,
                'priority' => $validated['priority'] ?? 'normal',
            ])
        );
    }

    public function followupPlanReasons(): JsonResponse
    {
        return response()->json(
            $this->botApiService->getFollowupPlanReasons()
        );
    }

    public function allFollowupPlans(Request $request): JsonResponse
    {
        $query = $request->only([
            'page',
            'limit',
            'status',
            'session_id',
        ]);

        return response()->json(
            $this->botApiService->getFollowupPlans($query)
        );
    }

    public function history(Request $request, string $sessionId): JsonResponse
    {
        return response()->json(
            $this->botApiService->getUnifiedLeadHistory(
                $sessionId,
                $request->only(['include_meta', 'limit', 'before_id'])
            )
        );
    }

    public function comments(Request $request, string $sessionId): JsonResponse
    {
        return response()->json(
            $this->botApiService->getLeadComments(
                $sessionId,
                $request->only(['limit'])
            )
        );
    }

    public function addComment(Request $request, string $sessionId): JsonResponse
    {
        $validated = $request->validate([
            'comment' => ['required', 'string'],
        ]);

        $auth = $request->attributes->get('whatsapp_auth', []);

        return response()->json(
            $this->botApiService->addLeadComment($sessionId, [
                'comment' => $validated['comment'],
                'created_by_uid' => $auth['uid'] ?? '',
                'created_by_name' => $auth['name'] ?? '',
                'created_by_role' => $auth['role'] ?? '',
            ])
        );
    }

    public function saveMessageFeedback(Request $request, int $messageId): JsonResponse
    {
        $validated = $request->validate([
            'verdict' => ['required', 'string', 'in:like,dislike'],
            'note' => ['nullable', 'string'],
            'preferred_reply' => ['nullable', 'string'],
            'wrong_intent_key' => ['nullable', 'string'],
            'corrected_intent_key' => ['nullable', 'string'],
            'wrong_topic_key' => ['nullable', 'string'],
            'corrected_topic_key' => ['nullable', 'string'],
            'feedback_use_mode' => ['nullable', 'string', 'in:exact,guidance'],
        ]);

        return response()->json(
            $this->botApiService->saveMessageFeedback(
                $messageId,
                $validated['verdict'],
                $validated['note'] ?? '',
                $validated['preferred_reply'] ?? '',
                $validated['wrong_intent_key'] ?? '',
                $validated['corrected_intent_key'] ?? '',
                $validated['wrong_topic_key'] ?? '',
                $validated['corrected_topic_key'] ?? '',
                $validated['feedback_use_mode'] ?? ''
            )
        );
    }

    public function salesPromptVariant(): JsonResponse
    {
        return response()->json(
            $this->botApiService->getSalesPromptVariant()
        );
    }

    public function updateSalesPromptVariant(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'variant' => ['required', 'string'],
        ]);

        return response()->json(
            $this->botApiService->updateSalesPromptVariant($validated['variant'])
        );
    }

    public function updateAutoReply(Request $request, string $sessionId): JsonResponse
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);

        return response()->json(
            $this->botApiService->updateLeadAutoReply($sessionId, (bool) $validated['enabled'])
        );
    }

    public function updatePromisedPayment(Request $request, string $sessionId): JsonResponse
    {
        $validated = $request->validate([
            'promised_payment_at' => ['nullable', 'string'],
        ]);

        return response()->json(
            $this->botApiService->updateLeadPromisedPayment(
                $sessionId,
                $validated['promised_payment_at'] ?? null
            )
        );
    }

    public function refreshTags(string $sessionId): JsonResponse
    {
        return response()->json(
            $this->botApiService->refreshLeadTags($sessionId)
        );
    }

    public function updateFollowupPlanStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
        ]);

        return response()->json(
            $this->botApiService->updateFollowupPlanStatus($id, $validated['status'])
        );
    }

    public function markFollowupPlanSent(int $id): JsonResponse
    {
        return response()->json(
            $this->botApiService->markFollowupPlanSent($id)
        );
    }

    public function deleteFollowupPlan(int $id): JsonResponse
    {
        return response()->json(
            $this->botApiService->deleteFollowupPlan($id)
        );
    }

    public function runFollowupScheduler(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dispatch_immediately' => ['nullable', 'boolean'],
            'limit' => ['nullable', 'integer', 'min:1'],
        ]);

        return response()->json(
            $this->botApiService->runFollowupScheduler(
                (bool) ($validated['dispatch_immediately'] ?? false),
                (int) ($validated['limit'] ?? 50)
            )
        );
    }
}
