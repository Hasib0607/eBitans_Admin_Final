<?php

namespace App\Http\Middleware;

use App\Models\AddonsOrder;
use App\Models\RegistrationFee;
use Closure;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Customer;
use App\Models\Staff;
use Auth;
use Carbon\Carbon;

class Checkplan
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $store = $userData['store'];

        if (isset($store)) {
            // Check customer store plane
            if ($store->plan_id != 'NULL') {
                if ($store->expiry_date >= Carbon::now()) {
                    return $next($request);
                } elseif (isset($store->upcoming_plan_id)) {
                    $stor = Store::find($store_id);
                    $stor->plan_id = $store->upcoming_plan_id;
                    $stor->renew_date = $store->upcoming_plan_purchase_date;
                    $stor->expiry_date = $store->upcoming_plan_expiry_date;
                    $stor->month = $store->upcoming_plan_month;
                    $stor->upcoming_plan_id = NULL;
                    $stor->upcoming_plan_purchase_date = NULL;
                    $stor->upcoming_plan_expiry_date = NULL;
                    $stor->upcoming_plan_month = NULL;
                    $stor->save();
                    return $next($request);
                }
            }

            // Check customer store pos plane
            if ($store->pos_plan_id != null) {
                if ($store->pos_plan_expiry_date >= Carbon::now()) {
                    return $next($request);
                } elseif (isset($store->upcoming_pos_plan_id)) {
                    $stor = Store::find($store_id);
                    $stor->pos_plan_start_date = $store->upcoming_pos_plan_start_date;
                    $stor->pos_plan_expiry_date = $store->upcoming_pos_plan_expiry_date;
                    $stor->pos_plan_month = $store->upcoming_pos_plan_month;
                    $stor->upcoming_pos_plan_id = NULL;
                    $stor->upcoming_pos_plan_start_date = NULL;
                    $stor->upcoming_pos_plan_expiry_date = NULL;
                    $stor->upcoming_pos_plan_month = NULL;
                    $stor->save();
                    return $next($request);
                }
            }

            // Check customer store digital plane
            if ($store->digital_plan_id != 'NULL') {
                if ($store->digital_plan_end_date >= Carbon::now()) {
                    return $next($request);
                } elseif (isset($store->upcoming_digital_plan_id)) {
                    $stor = Store::find($store_id);
                    $stor->digital_plan_start_date = $store->upcoming_digital_plan_start_date;
                    $stor->digital_plan_end_date = $store->upcoming_digital_plan_expiry_date;
                    // $stor->digital_plan_month=$store->upcoming_digital_plan_month;
                    $stor->upcoming_digital_plan_id = NULL;
                    $stor->upcoming_digital_plan_start_date = NULL;
                    $stor->upcoming_digital_plan_expiry_date = NULL;
                    $stor->upcoming_digital_plan_month = NULL;
                    $stor->save();
                    return $next($request);
                }
            }
            // return redirect('/');

            if (checkPaidRegistration() && $store->paid_registration == 1) {
                $min = env("REGISTRATION_PAYMENT_DELAY", 20);
                $showPaymentTime = Carbon::parse($store->created_at)->addMinutes($min);
                $currentTime = Carbon::now();

                if ($showPaymentTime <= $currentTime) {
                    return redirect()->route('showRegistrationPaymentMethod');
                } else {
                    return $next($request);
                }

            }

            return redirect()->route('payment.packages');
        } else {
            return $next($request);
        }
    }
}
