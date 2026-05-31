<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyEbitansLaravelApiToken
{
    public function handle(Request $request, Closure $next): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $expectedToken = trim((string) config('whatsapp_automation.laravel_api_token', ''));

        if ($expectedToken === '') {
            return response()->json([
                'success' => false,
                'error' => 'EBITANS_LARAVEL_API_TOKEN is not configured.',
            ], 500);
        }

        $providedToken = trim((string) $request->bearerToken());

        if ($providedToken === '' || !hash_equals($expectedToken, $providedToken)) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}
