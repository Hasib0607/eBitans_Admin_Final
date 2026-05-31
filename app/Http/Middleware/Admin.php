<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class Admin
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
        // Check user is admin or staff if not then abort the request
        if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            return $next($request);
        } elseif (Auth::user()->type == 'staff' || Auth::user()->type == 'superstaff') {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
