<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;
use App\Models\AddonsExpired;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use Closure;
use Auth;

class CheckSms
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->type != 'admin') {
            return redirect()->route('staff.dashboard');
        }

        $store_id = null;

        if ($user->type == 'admin') {
            $store_id = Customer::where('uid', $user->id)->value('active_store');
        }

        if ($user->type == 'staff') {
            $store_id = Staff::where('uid', $user->id)->value('store_id');
        }

        if ($store_id === null) {
            $SmsAlert = 'SMS Store Id Not Found';
            
            return redirect()->route('admin.index');
        }

        $checkSms = AddonsExpired::where('store_id', $store_id)->where('addons_id', '5')->first();

        if (!$checkSms || $checkSms->total - $checkSms->used <= 0) {
            Session::flash('SmsAlert','SMS balance is insufficient.');
        }

        return $next($request);
    }
}
