<?php

namespace App\Http\Middleware;

use App\Services\WhatsAppAutomation\PermissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWhatsAppAutomationAccess
{
    public function __construct(
        protected PermissionService $permissionService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$this->permissionService->canAccess($user)) {
            abort(403, 'You are not allowed to access WhatsApp Automation.');
        }

        return $next($request);
    }
}