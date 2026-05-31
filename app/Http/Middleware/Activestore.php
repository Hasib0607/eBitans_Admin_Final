<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class Activestore
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
        if (!Auth::check()) {
            return $next($request);
        }

        $customer = Customer::with('getStore')
            ->where('uid', Auth::id())
            ->first();

        if (!$customer) {
            return $next($request);
        }

        if (!$customer->active_store) {
            return redirect()->route('store.list');
        }

        $store = $customer->getStore;

        if (!$store) {
            return redirect()->route('store.list');
        }

        if ((int) $store->store_status !== 1) {
            return redirect()->route('store.list');
        }

        return $next($request);
    }
}