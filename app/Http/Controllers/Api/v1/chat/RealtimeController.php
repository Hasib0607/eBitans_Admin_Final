<?php

namespace App\Http\Controllers\Api\v1\chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessageResource;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RealtimeController extends Controller
{
    protected function isSupportAgent(): bool
    {
        return Auth::check() && in_array((string) Auth::user()->type, ['superadmin', 'superstaff', 'admin', 'staff'], true);
    }

    protected function resolveConversation(Request $request): ?ChatConversation
    {
        $conversationId = (int) $request->query('conversation_id', 0);
        if ($conversationId <= 0) {
            return null;
        }

        $conversation = ChatConversation::with('visitor')->find($conversationId);
        if (!$conversation || !$conversation->visitor) {
            return null;
        }

        if ($this->isSupportAgent()) {
            return !is_null($conversation->agent_id) && (int) $conversation->agent_id === (int) Auth::id()
                ? $conversation
                : null;
        }

        $sessionToken = (string) $request->query('session_token', '');
        if ($sessionToken !== '' && hash_equals((string) $conversation->visitor->session_token, $sessionToken)) {
            return $conversation;
        }

        if (Auth::check() && !$this->isSupportAgent() && (int) $conversation->visitor->user_id === (int) Auth::id()) {
            return $conversation;
        }

        return null;
    }

    protected function buildRealtimePayload(ChatConversation $conversation, int $afterId): array
    {
        $messages = ChatMessage::where('conversation_id', $conversation->id)
            ->where('id', '>=', max((int) floor($afterId / 10), 0))
            ->orderBy('id')
            ->get();

        $events = [];
        $nextCursor = $afterId;

        foreach ($messages as $message) {
            $messageEventId = ((int) $message->id * 10) + 1;
            if ($messageEventId > $afterId) {
                $events[] = [
                    'id' => $messageEventId,
                    'event' => 'message',
                    'conversation_id' => $conversation->id,
                    'data' => [
                        'conversation' => $conversation->fresh(),
                        'message' => (new ChatMessageResource($message))->resolve(),
                        'response' => null,
                        'responseTimeout' => null,
                        'timeOut' => 0,
                        'endSessionTime' => 5 * (60 * 1000),
                        'isEnableChat' => true,
                    ],
                ];
                $nextCursor = max($nextCursor, $messageEventId);
            }

            $seenEventId = ((int) $message->id * 10) + 2;
            if ((int) $message->seen_status === 1 && $seenEventId > $afterId) {
                $events[] = [
                    'id' => $seenEventId,
                    'event' => 'message_seen',
                    'conversation_id' => $conversation->id,
                    'messageID' => $message->id,
                    'conversationID' => $conversation->id,
                ];
                $nextCursor = max($nextCursor, $seenEventId);
            }
        }

        usort($events, static fn ($left, $right) => ($left['id'] ?? 0) <=> ($right['id'] ?? 0));

        return [
            'success' => true,
            'events' => $events,
            'next_cursor' => $nextCursor,
        ];
    }

    public function events(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => ['required', 'integer', 'min:1'],
            'after_id' => ['nullable', 'integer', 'min:0'],
            'session_token' => ['nullable', 'string'],
        ]);

        $conversation = $this->resolveConversation($request);
        if (!$conversation) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 403);
        }

        $afterId = max(
            (int) $request->query('after_id', 0),
            (int) $request->header('Last-Event-ID', 0)
        );

        return response()->json(
            $this->buildRealtimePayload($conversation, $afterId)
        );
    }

    public function stream(Request $request): StreamedResponse|JsonResponse
    {
        $request->validate([
            'conversation_id' => ['required', 'integer', 'min:1'],
            'after_id' => ['nullable', 'integer', 'min:0'],
            'session_token' => ['nullable', 'string'],
        ]);

        $conversation = $this->resolveConversation($request);
        if (!$conversation) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 403);
        }

        $afterId = max(
            (int) $request->query('after_id', 0),
            (int) $request->header('Last-Event-ID', 0),
            0
        );

        return response()->stream(function () use ($conversation, $afterId) {
            $cursor = $afterId;
            $startedAt = time();

            $sendEvent = static function (string $event, array $data): void {
                if (isset($data['id'])) {
                    echo 'id: ' . (int) $data['id'] . "\n";
                }
                echo "event: {$event}\n";
                echo 'data: ' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n\n";

                if (function_exists('ob_flush')) {
                    @ob_flush();
                }
                flush();
            };

            $sendEvent('ready', [
                'success' => true,
                'conversation_id' => $conversation->id,
                'next_cursor' => $cursor,
            ]);

            while (!connection_aborted() && (time() - $startedAt) < 25) {
                $result = $this->buildRealtimePayload($conversation, $cursor);
                $events = $result['events'] ?? [];

                if (!empty($events)) {
                    foreach ($events as $event) {
                        $sendEvent((string) ($event['event'] ?? 'message'), $event);
                        $cursor = max($cursor, (int) ($event['id'] ?? 0));
                    }
                } else {
                    $sendEvent('ping', [
                        'success' => true,
                        'conversation_id' => $conversation->id,
                        'next_cursor' => $cursor,
                        'timestamp' => now()->format('Y-m-d H:i:s'),
                    ]);
                }

                sleep(2);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-transform',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
