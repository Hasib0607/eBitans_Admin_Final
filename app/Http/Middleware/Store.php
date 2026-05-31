<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;
use Auth;

class Store
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
        // Check user type
        if (Auth::user()->type == "staff" || Auth::user()->type == "superstaff") {
            return $next($request);
        } elseif (Auth::user()->type == 'superadmin') {
            return redirect()->route('superadmin.index');
        } elseif (Auth::user()->type == 'affiliate') {
            return redirect()->route('affiliate.index');
        } else {
            $store = DB::table('stores')->where('user_id', Auth::user()->id)->get();
            if (count($store) > 0) {
                return $next($request);
            } else {
                return redirect()->route('store.list');
            }
        }
    }
}
