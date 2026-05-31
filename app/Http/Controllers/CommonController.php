<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Currency;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CommonController extends Controller
{
    /**
     * @return Builder|Builder[]|Collection|Model|int|null
     */
    public function flash_exchange_rate(Request $request): Model|Collection|Builder|int|array|null
    {
        $currencyID = $request->id ?? "";
        $rates = Http::asForm()->get('https://latest.currency-api.pages.dev/v1/currencies/usd.json')['usd'];
        $currencies = Currency::all();
        $result = $currencies;
        foreach ($currencies as $currency) {
            $result[$currency->id] = Currency::where('code',
                $currency->code)->update(['rate' => round($rates[strtolower($currency->code)], 2)]);
        }
        $store = 0;
        /*extracting user_id, store_id*/
        extract(getUserData());
        if ($store_id) {
            $this->updateCurrencyRate($currencyID, $store_id, $rates);

            $store = Store::with('current_currency')->find($store_id);
            if (isset($store)) {
                $store->currency_rate = round($rates[strtolower($store->current_currency->code)], 2);
                $store->save();
            }
        }
        return $store;
    }


    /**
     * Update currency
     *
     * @param $id
     * @param $store_id
     * @param $rates
     * @return void
     */
    public function updateCurrencyRate($id, $store_id, $rates)
    {
        if ($id) {
            $store = Store::find($store_id);
            $store->currency = $id;
            $store->currency_rate = $rates;
            $store->save();

            /* update campaign currency*/
            Campaign::where('store_id', $store_id)
                ->update(['currency_id' => $id]);
        }
    }


}
