<?php

namespace App\Http\Controllers\WhatsAppAutomation;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppAutomation\GatewayApiService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GatewaySessionController extends Controller
{
    private function resolveTenantId(Request $request, ?string $tenantId = null): string
    {
        $resolvedTenantId = trim((string) ($tenantId ?: $request->input('tenantId', '')));

        if ($resolvedTenantId === '') {
            $resolvedTenantId = trim((string) config('whatsapp_automation.gateway_tenant_id', ''));
        }

        if ($resolvedTenantId === '') {
            throw new \InvalidArgumentException('tenantId is required.');
        }

        return $resolvedTenantId;
    }

    private function relayGatewayException(\Throwable $exception, string $fallbackMessage): JsonResponse
    {
        if ($exception instanceof RequestException && $exception->response) {
            $payload = $exception->response->json();

            return response()->json([
                'success' => false,
                'message' => $payload['message'] ?? $payload['error'] ?? $fallbackMessage,
                'gateway' => $payload,
            ], $exception->response->status());
        }

        $statusCode = $exception instanceof \InvalidArgumentException ? 422 : 500;

        return response()->json([
            'success' => false,
            'message' => $exception->getMessage() ?: $fallbackMessage,
        ], $statusCode);
    }

    public function create(Request $request, GatewayApiService $gatewayApiService): JsonResponse
    {
        try {
            $tenantId = $this->resolveTenantId($request);
            $payload = $gatewayApiService->createSession($tenantId);

            return response()->json($payload, 202);
        } catch (\Throwable $exception) {
            return $this->relayGatewayException($exception, 'Failed to create gateway session.');
        }
    }

    public function status(Request $request, string $tenantId, GatewayApiService $gatewayApiService): JsonResponse
    {
        try {
            $payload = $gatewayApiService->getSessionStatus($this->resolveTenantId($request, $tenantId));

            return response()->json($payload);
        } catch (\Throwable $exception) {
            return $this->relayGatewayException($exception, 'Failed to fetch gateway session status.');
        }
    }

    public function qr(Request $request, string $tenantId, GatewayApiService $gatewayApiService): JsonResponse
    {
        try {
            $payload = $gatewayApiService->getSessionQr($this->resolveTenantId($request, $tenantId));

            return response()->json($payload);
        } catch (\Throwable $exception) {
            return $this->relayGatewayException($exception, 'Failed to fetch gateway QR.');
        }
    }

    public function logout(Request $request, string $tenantId, GatewayApiService $gatewayApiService): JsonResponse
    {
        try {
            $payload = $gatewayApiService->logoutSession($this->resolveTenantId($request, $tenantId));

            return response()->json($payload);
        } catch (\Throwable $exception) {
            return $this->relayGatewayException($exception, 'Failed to logout gateway session.');
        }
    }
}
