<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Customer;
use App\Models\Staff;
use Auth;
use Carbon\Carbon;

class Websitepos
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
        } else {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
        }
        $store = Store::where('id', $store_id)->first();
        if (isset($store)) {
            if (isset($store->plan_id)) {
                if ($store->expiry_date <= Carbon::now() && !paidTrial()) {
                    if (isset($store->pos_plan_id)) {
                        if ($store->pos_plan_expiry_date <= Carbon::now()) {
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
            } elseif ($store->pos_plan_id) {
                if ($store->pos_plan_expiry_date <= Carbon::now()) {
                    return redirect('/');
                } else {
                    return $next($request);
                }
            } else {
                return redirect('/');
            }
            if (isset($store->plan_id) || isset($store->pos_plan_id)) {
                if ($store->expiry_date <= Carbon::now() || $store->pos_plan_expiry_date <= Carbon::now()) {
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
