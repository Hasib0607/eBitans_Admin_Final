<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class Superadmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check user type superadmin or super admin staff
        if (Auth::user()->type == 'superadmin') {
            return $next($request);
        } elseif (Auth::user()->type == 'superstaff') {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
