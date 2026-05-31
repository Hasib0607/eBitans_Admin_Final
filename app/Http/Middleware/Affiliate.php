<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class Affiliate
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
        // Check user type affiliate
        if (Auth::user()->type == 'affiliate') {
            return $next($request);
        } elseif (Auth::user()->type == 'superstaff') {
            return redirect()->route('superadmin.index');
        } elseif (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            return redirect()->route('store.list');
        } elseif (Auth::user()->type == 'staff') {
            return redirect()->route('index');
        } else {
            return abort(404);
        }
    }
}
