<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Customer;
use App\Models\Staff;
use Auth;
use Carbon\Carbon;

class Websiteplan
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
        $user_type = Auth::user()->type;
        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', Auth::user()->id)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type == "superadmin" || $user_type == "superstaff") {
            return $next($request);
        } else {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
        }
        $store = Store::where('id', $store_id)->first();
        if (isset($store)) {
            if (isset($store->plan_id)) {
                if ($store->expiry_date <= Carbon::now() && !paidTrial()) {
                    return redirect('/');
                } else {
                    return $next($request);
                }
            } else {
                return redirect('/');
            }
        } else {
            return $next($request);
        }
    }
}
