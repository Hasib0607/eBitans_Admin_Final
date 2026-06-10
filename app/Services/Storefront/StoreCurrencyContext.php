<?php

namespace App\Services\Storefront;

use App\Models\Currency;
use App\Models\Store;

class StoreCurrencyContext
{
    private static array $stores = [];

    private static ?Currency $defaultCurrency = null;

    public static function get(int $storeId): ?Store
    {
        if (!array_key_exists($storeId, self::$stores)) {
            self::$stores[$storeId] = Store::with('current_currency')->find($storeId);
        }

        return self::$stores[$storeId];
    }

    public static function defaultCurrency(): Currency
    {
        return self::$defaultCurrency ??= Currency::query()->find(1) ?? new Currency(['id' => 1, 'rate' => 1, 'symbol' => '৳', 'customize_rate_status' => 0]);
    }

    public static function reset(): void
    {
        self::$stores = [];
        self::$defaultCurrency = null;
    }
}
