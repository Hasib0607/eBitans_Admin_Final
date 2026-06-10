<?php

namespace App\Services\Storefront;

use App\Models\Store;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StorefrontCache
{
    public function version(int $storeId): string
    {
        return (string) Cache::get($this->versionKey($storeId), '1');
    }

    public function touchStore(int $storeId): void
    {
        Cache::forever($this->versionKey($storeId), (string) now()->getTimestampMs());
    }

    public function forgetStore(int $storeId): void
    {
        $store = Store::find($storeId);

        $this->touchStore($storeId);

        Cache::forget("storefront:bootstrap:{$storeId}");
        Cache::forget("storefront:home:{$storeId}");
        Cache::forget("storefront:shell:{$storeId}");
        Cache::forget("design_layout_store_v2_{$storeId}");
        Cache::forget("store_{$storeId}_products");
        Cache::forget("store_{$storeId}_feature_product");
        Cache::forget("store_{$storeId}_best_sell_product");
        Cache::forget("store_{$storeId}_new_arrival_products");

        if ($store) {
            Cache::forget("layout_positions_{$storeId}_{$store->template_id}");
            Cache::forget("store_lookup_v3_" . md5((string) $store->url));
            Cache::forget("store_lookup_v3_" . md5((string) $store->slug));
        }

        foreach ($this->domainsForStore($storeId) as $domain) {
            Cache::forget("store_lookup_v3_" . md5($domain));
        }
    }

    public function forgetProduct(int $storeId, int $productId): void
    {
        Cache::forget("storefront:product:{$storeId}:{$productId}");
        Cache::forget("product_images_{$productId}");
        Cache::forget("store_active_campaigns:{$storeId}:" . $this->version($storeId));
        $this->forgetStore($storeId);
    }

    private function domainsForStore(int $storeId): array
    {
        if (!Schema::hasTable('domains')) {
            return [];
        }

        return DB::table('domains')
            ->where('store_id', $storeId)
            ->pluck('name')
            ->filter()
            ->map(fn ($domain) => strtolower(trim($domain)))
            ->values()
            ->all();
    }

    private function versionKey(int $storeId): string
    {
        return "storefront:version:{$storeId}";
    }
}
