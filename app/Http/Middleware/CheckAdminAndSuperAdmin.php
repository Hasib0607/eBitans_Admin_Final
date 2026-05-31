<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Staff;
use App\Models\Store;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckAdminAndSuperAdmin
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
        if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            // Get customer data
            $customer = Customer::where('uid', Auth::user()->id)->first();

            // check customer active store list if customer have not active store then redirect to store list page
            if (isset($customer)) {
                if ($customer->active_store == "0") {
                    return redirect()->route('store.list');
                } else {
                    $store = Store::where('id', $customer->active_store)->first();
                    if ($store->status != 'active') {
                        return redirect()->route('store.list');
                    }
                }
            }

            return $next($request);
        } elseif (Auth::user()->type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first(); // Get staff data
            $store_id = $staff->store_id ?? "";
            $store = DB::table('stores')->where('id', $store_id)->get();
            if (isset($store->status) && $store->status != 'active') {
                return redirect()->route('store.list');
            }

            return $next($request);
        } elseif (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff') {
            return $next($request);
        } elseif (Auth::user()->type == 'affiliate') {
            return redirect()->route('affiliate.index');
        } else {
            return abort(403);
        }

    }
}
