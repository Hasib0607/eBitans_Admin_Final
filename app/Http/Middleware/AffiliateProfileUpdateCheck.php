<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AffiliateProfileUpdateCheck
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
        if (Auth::user()->type == 'affiliate') {
            $user = Auth::user();
            if (empty($user->name) || (empty($user->phone) && empty($user->email))) {
                Session::flash("error", "Update your profile first!!");
                return redirect()->route('affiliate.profile');
            }
        }

        return $next($request);
    }
}
