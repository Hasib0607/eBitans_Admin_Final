<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PSEWhitelistedDomain
{
    protected $whitelistedDomains = [
        'https://ebitans.com',
        'https://ebitans.com.bd',
        'https://admin.ebitans.com',
        'http://localhost:3000',
        'http://localhost:8000',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
//        $origin = $request->headers->get('origin');
//        $referer = $request->headers->get('referer');
//
//        $valid = false;
//
//        foreach ($this->whitelistedDomains as $domain) {
//            if (
//                ($origin && str_starts_with($origin, $domain)) ||
//                ($referer && str_starts_with($referer, $domain))
//            ) {
//                $valid = true;
//                break;
//            }
//        }
//
//        if (!$valid) {
//            return response()->json(['message' => 'Unauthorized'], 401);
//        }

        return $next($request);
    }
}
