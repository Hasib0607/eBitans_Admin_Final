<?php

namespace App\Services\WhatsAppAutomation;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GatewayApiService
{
    protected function client(): PendingRequest
    {
        $baseUrl = rtrim((string) config('whatsapp_automation.gateway_api_url'), '/');
        $apiSecret = trim((string) config('whatsapp_automation.gateway_api_secret', ''));

        if ($baseUrl === '') {
            throw new \RuntimeException('WHATSAPP_GATEWAY_API_URL is not configured.');
        }

        $client = Http::baseUrl($baseUrl)
            ->withOptions([
                'connect_timeout' => 15,
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
            ])
            ->timeout((int) config('whatsapp_automation.gateway_timeout_seconds', 30))
            ->retry(2, 1000)
            ->acceptJson();

        if ($apiSecret !== '') {
            $client = $client->withHeaders([
                'x-api-secret' => $apiSecret,
            ]);
        }

        return $client;
    }

    public function createSession(string $tenantId): array
    {
        $response = $this->client()->post('/api/sessions/create', [
            'tenantId' => $tenantId,
        ]);

        return $response->throw()->json();
    }

    public function getSessionStatus(string $tenantId): array
    {
        $response = $this->client()->get('/api/sessions/' . urlencode($tenantId) . '/status');

        return $response->throw()->json();
    }

    public function getSessionQr(string $tenantId): array
    {
        $response = $this->client()->get('/api/sessions/' . urlencode($tenantId) . '/qr');

        return $response->throw()->json();
    }

    public function logoutSession(string $tenantId): array
    {
        $response = $this->client()->post('/api/sessions/' . urlencode($tenantId) . '/logout');

        return $response->throw()->json();
    }

    public function sendTextMessage(string $tenantId, string $replyToJid, string $message): array
    {
        $response = $this->client()->post('/api/messages/send', [
            'tenantId' => $tenantId,
            'replyToJid' => $replyToJid,
            'message' => $message,
        ]);

        return $response->throw()->json();
    }
}
