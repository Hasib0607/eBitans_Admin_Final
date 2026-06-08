<?php

namespace App\Services\Storefront;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StorefrontStoreResolver
{
    public function resolve(string $name): ?object
    {
        $cacheKey = "store_lookup_v3_" . md5($name);

        return Cache::remember($cacheKey, 600, fn () => $this->resolveFresh($name));
    }

    public function resolveFresh(string $name): ?object
    {
        $normalizedDomain = $this->normalizeDomainName($name);
        $slug = $this->domainSlug($normalizedDomain);

        $storeIdFromDomain = DB::table('domains')
            ->where('name', $normalizedDomain)
            ->whereIn('status', ['Active', 'active'])
            ->value('store_id');

        if (!empty($storeIdFromDomain)) {
            $store = $this->activeStoreQuery()->where('id', $storeIdFromDomain)->first();

            if ($store) {
                return $store;
            }
        }

        $store = $this->activeStoreQuery()->where('url', $normalizedDomain)->first();

        if ($store) {
            return $store;
        }

        $store = $this->activeStoreQuery()->where('slug', $slug)->first();

        if ($store) {
            return $store;
        }

        return $this->resolveFallback($normalizedDomain, $slug);
    }

    private function activeStoreQuery()
    {
        return DB::table('stores')
            ->where('expiry_date', '>=', Carbon::now()->toDateString())
            ->where(function ($query) {
                $query->whereNull('store_status')->orWhere('store_status', 1);
            });
    }

    private function resolveFallback(string $normalizedDomain, string $slug): ?object
    {
        $storeIdFromDomain = DB::table('domains')
            ->whereRaw('LOWER(TRIM(name)) = ?', [strtolower($normalizedDomain)])
            ->whereIn('status', ['Active', 'active'])
            ->value('store_id');

        return $this->activeStoreQuery()
            ->where(function ($query) use ($normalizedDomain, $slug, $storeIdFromDomain) {
                $query->whereRaw('LOWER(TRIM(url)) = ?', [strtolower($normalizedDomain)])
                    ->orWhereRaw('LOWER(TRIM(slug)) = ?', [strtolower($slug)]);

                if (!empty($storeIdFromDomain)) {
                    $query->orWhere('id', $storeIdFromDomain);
                }
            })
            ->first();
    }

    private function normalizeDomainName(string $name): string
    {
        $name = trim($name);
        $host = parse_url($name, PHP_URL_HOST);

        if ($host) {
            $name = $host;
        }

        return strtolower(trim($name, "/ \t\n\r\0\x0B"));
    }

    private function domainSlug(string $domain): string
    {
        $storeSubDomain = strtolower((string) env('STORE_SUB_DOMAIN', ''));

        if ($storeSubDomain !== '' && Str::endsWith($domain, '.' . $storeSubDomain)) {
            return Str::before($domain, '.' . $storeSubDomain);
        }

        return Str::before($domain, '.');
    }
}
