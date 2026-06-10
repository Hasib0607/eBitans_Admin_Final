<?php

namespace App\Providers;

use App\Models\Banner;
use App\Models\Campaign;
use App\Models\Brand;
use App\Models\BuyModulus;
use App\Models\Category;
use App\Models\Design;
use App\Models\DesignPosition;
use App\Models\Headersetting;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Store;
use App\Models\StoreDesign;
use App\Models\Testimonial;
use App\Services\Storefront\StorefrontCache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void

     */

    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
        Blade::withoutDoubleEncoding();
        $this->registerStorefrontCacheInvalidation();

    }

    private function registerStorefrontCacheInvalidation(): void
    {
        $purge = function ($model): void {
            $storeId = $model instanceof Store ? $model->id : ($model->store_id ?? null);

            if (!empty($storeId)) {
                app(StorefrontCache::class)->forgetStore((int) $storeId);
            }
        };

        $purgeProduct = function ($model): void {
            if (!empty($model->store_id) && !empty($model->id)) {
                app(StorefrontCache::class)->forgetProduct((int) $model->store_id, (int) $model->id);
            }
        };

        foreach ([Banner::class, Brand::class, BuyModulus::class, Campaign::class, Category::class, Design::class, DesignPosition::class, Headersetting::class, Menu::class, Page::class, Slider::class, Store::class, StoreDesign::class, Testimonial::class] as $model) {
            $model::saved($purge);
            $model::deleted($purge);
        }

        Product::saved($purgeProduct);
        Product::deleted($purgeProduct);
    }
}
