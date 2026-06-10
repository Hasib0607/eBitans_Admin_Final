<?php

    namespace App\Models;

    use App\Services\Storefront\StoreCurrencyContext;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class Campaign extends Model
    {
        use HasFactory;

        public function scopeConvertCurrency($query, $store_id)
        {
            $store = StoreCurrencyContext::get((int) $store_id);
            if (!$store) {
                return $query->select("campaigns.*", 'currencies.symbol', 'currencies.id as currency')
                    ->join('currencies', 'campaigns.currency_id', '=', 'currencies.id')
                    ->where('campaigns.store_id', $store_id);
            }

            $current_currency = $store->current_currency ?: StoreCurrencyContext::defaultCurrency();
            return $query->select("campaigns.*", 'currencies.symbol', 'currencies.id as currency')
                ->join('currencies', 'campaigns.currency_id', '=', 'currencies.id')
                ->when('campaigns.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                    function ($query) use ($current_currency) {
                        $query->addSelect([
                            DB::raw("CASE WHEN campaigns.discount_type = 'fixed' THEN ROUND(campaigns.discount_amount / currencies.rate * " . $current_currency->rate . " , 2) ELSE campaigns.discount_amount END as discount_amount"),
                            DB::raw("CASE WHEN campaigns.discount_type = 'fixed' THEN currencies.symbol ELSE '%' END as symbol")
                        ]);
                    })
                ->when('campaigns.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                    function ($query) use ($store, $current_currency) {
                        $query->addSelect([
                            DB::raw("CASE WHEN campaigns.discount_type = 'fixed' THEN ROUND(campaigns.discount_amount / {$store->currency_rate}, 2) ELSE campaigns.discount_amount END as discount_amount"),
                            DB::raw("CASE WHEN campaigns.discount_type = 'fixed' THEN currencies.symbol ELSE '%' END as symbol")
                        ]);
                    })
                ->where('campaigns.store_id', $store_id);
        }
    }
