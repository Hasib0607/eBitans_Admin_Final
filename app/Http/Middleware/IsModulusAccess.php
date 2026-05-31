<?php

namespace App\Http\Middleware;

use App\Models\BuyModulus;
use App\Models\Modulus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsModulusAccess
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $modulus_id = null)
    {
        if (Auth::check()) {
            // Check user type superadmin or super admin staff
            if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff') {
                return $next($request);
            } else {
                if (is_null($modulus_id)) {
                    return redirect('/');
                }
                $user_id = Auth::user()->id;
                $user_type = Auth::user()->type;
                if ($user_type == 'admin' || $user_type == 'dropshipper') {
                    $customer = \App\Models\Customer::where('uid', $user_id)->first();
                    $store_id = $customer->active_store;
                } elseif ($user_type == 'staff') {
                    $staff = \App\Models\Staff::where('uid', $user_id)->first();
                    $store_id = $staff->store_id;
                }

                if (isset($store_id)) {
                    $buyModulus = BuyModulus::where('store_id', $store_id)->where('modulus_id', $modulus_id)->first();
                    $modulus = Modulus::find($modulus_id);

                    if (isset($modulus->status) && isset($buyModulus->status) && $modulus->status == 1 && $buyModulus->status == 1) {
                        return $next($request);
                    }
                }

                return redirect('/');
            }
        }

        return redirect()->route('login');
    }
}
