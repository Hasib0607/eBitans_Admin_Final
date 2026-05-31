<?php

namespace App\Http\Controllers\WhatsAppAutomation;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppAutomation\BotApiService;
use App\Services\WhatsAppAutomation\GatewayApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GatewayWebhookController extends Controller
{
    public function health(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'WhatsApp gateway webhook endpoint is active',
        ]);
    }

    public function receive(
        Request $request,
        BotApiService $botApiService,
        GatewayApiService $gatewayApiService
    ): JsonResponse
    {
        Log::info('WhatsApp gateway webhook received', [
            'headers_secret_present' => trim((string) $request->header('x-api-secret', '')) !== '',
            'payload_keys' => array_keys((array) $request->json()->all()),
        ]);

        $expectedSecret = trim((string) config('whatsapp_automation.gateway_api_secret', ''));
        if ($expectedSecret !== '') {
            $receivedSecret = trim((string) $request->header('x-api-secret', ''));
            if ($receivedSecret !== $expectedSecret) {
                Log::warning('WhatsApp gateway webhook secret mismatch');
                return response()->json([
                    'success' => false,
                    'error' => 'Unauthorized webhook secret',
                ], 401);
            }
        }

        $payload = $request->json()->all();
        if (empty($payload)) {
            Log::warning('WhatsApp gateway webhook empty payload');
            return response()->json([
                'success' => false,
                'error' => 'Empty or invalid JSON payload',
            ], 400);
        }

        if (($payload['event'] ?? '') !== 'whatsapp.incoming_message') {
            Log::info('WhatsApp gateway webhook ignored non-message event', [
                'event' => $payload['event'] ?? null,
            ]);
            return response()->json([
                'success' => true,
                'ignored' => true,
                'event' => $payload['event'] ?? null,
                'message' => 'Event acknowledged but not processed',
            ]);
        }

        $expectedTenantId = trim((string) config('whatsapp_automation.gateway_tenant_id', ''));
        $tenantId = trim((string) ($payload['tenantId'] ?? ''));
        if ($expectedTenantId !== '' && $tenantId !== '' && $tenantId !== $expectedTenantId) {
            Log::info('WhatsApp gateway webhook ignored due to tenant mismatch', [
                'received_tenant_id' => $tenantId,
                'expected_tenant_id' => $expectedTenantId,
            ]);
            return response()->json([
                'success' => true,
                'ignored' => true,
                'reason' => 'Tenant mismatch',
                'tenantId' => $tenantId,
            ]);
        }

        $remoteJid = trim((string) ($payload['remoteJid'] ?? ''));
        $remoteJidAlt = trim((string) ($payload['remoteJidAlt'] ?? ''));
        $senderJid = trim((string) ($payload['senderJid'] ?? ''));
        $participantJid = trim((string) ($payload['participantJid'] ?? ''));
        $participantJidAlt = trim((string) ($payload['participantJidAlt'] ?? ''));
        $replyToJid = trim((string) ($payload['replyToJid'] ?? ''));
        $messageId = trim((string) ($payload['messageId'] ?? ''));
        $text = trim((string) ($payload['text'] ?? ''));
        $pushName = trim((string) ($payload['pushName'] ?? ''));
        $media = is_array($payload['media'] ?? null) ? $payload['media'] : null;

        if ($text === '' && $media) {
            $text = trim((string) (($media['caption'] ?? '') ?: ($media['fileName'] ?? '')));
        }

        $sessionId = $replyToJid !== '' ? $replyToJid : ($remoteJidAlt ?: $remoteJid ?: $senderJid);
        $receivedSecret = trim((string) $request->header('x-api-secret', ''));

        try {
            Log::info('Forwarding WhatsApp gateway webhook to Python bot', [
                'tenantId' => $tenantId,
                'sessionId' => $sessionId,
                'messageId' => $messageId,
                'replyToJid' => $replyToJid,
                'text_present' => $text !== '',
                'media_type' => is_array($media) ? ($media['type'] ?? null) : null,
                'media_public_url' => is_array($media) ? (($media['publicUrl'] ?? '') ?: ($media['mediaUrl'] ?? '')) : null,
            ]);

            $forwarded = $botApiService->forwardGatewayWebhook($payload, $receivedSecret ?: $expectedSecret);

            Log::info('Python bot webhook forward completed', [
                'tenantId' => $tenantId,
                'sessionId' => $sessionId,
                'messageId' => $messageId,
                'forward_success' => (bool) ($forwarded['success'] ?? false),
                'forward_keys' => array_keys((array) $forwarded),
            ]);

            return response()->json($forwarded);
        } catch (\Throwable $exception) {
            report($exception);

            Log::error('Forwarding WhatsApp gateway webhook to Python failed', [
                'tenantId' => $tenantId,
                'sessionId' => $sessionId,
                'messageId' => $messageId,
                'replyToJid' => $replyToJid,
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $exception->getMessage(),
                'incoming' => [
                    'event' => 'whatsapp.incoming_message',
                    'tenantId' => $tenantId,
                    'remoteJid' => $remoteJid,
                    'remoteJidAlt' => $remoteJidAlt,
                    'senderJid' => $senderJid,
                    'participantJid' => $participantJid,
                    'participantJidAlt' => $participantJidAlt,
                    'replyToJid' => $replyToJid,
                    'sessionId' => $sessionId,
                    'messageId' => $messageId,
                    'text' => $text,
                    'pushName' => $pushName,
                    'timestamp' => $payload['timestamp'] ?? null,
                    'media' => $media,
                ],
            ], 502);
        }
    }

}
