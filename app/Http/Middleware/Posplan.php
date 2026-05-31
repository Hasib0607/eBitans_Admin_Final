<?php

namespace App\Http\Middleware;

use App\Models\AddonsExpired;
use Closure;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Customer;
use App\Models\Staff;
use Auth;
use Carbon\Carbon;

class Posplan
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
        if ($user_type == "admin") {
            $customer = Customer::where('uid', Auth::user()->id)->first();
            $store_id = $customer->active_store;
        } else {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
        }

        $currentDate = Carbon::now();
        $posAddon = AddonsExpired::where("store_id", $store_id)
            ->where("addons_id", 13)
            ->where('expired_date', ">=", $currentDate)
            ->first();

        if (isset($posAddon)) {
            return $next($request);
        } else {
            return redirect('/');
        }

    }
}
