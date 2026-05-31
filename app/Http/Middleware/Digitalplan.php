<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Customer;
use App\Models\Staff;
use Auth;
use Carbon\Carbon;

class Digitalplan
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
        $user_type=Auth::user()->type;
        if($user_type=="admin"){
            $customer=Customer::where('uid',Auth::user()->id)->first();
            $store_id=$customer->active_store;
        }else{
            $staff=Staff::where('uid',Auth::user()->id)->first();
            $store_id=$staff->store_id;
        }
        $store=Store::where('id',$store_id)->first();
        if(isset($store)){
            if(isset($store->digital_plan_id)){
                if($store->digital_plan_end_date <= Carbon::now()){
                    return redirect('/');
                }else{
                    return $next($request);
                }
            }else{
                return redirect('/');
            }  
        }else{
            return $next($request);
        }    
    }
}
