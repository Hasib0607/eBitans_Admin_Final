<?php

namespace App\Services\Storefront;

use App\Http\Resources\ProductLayoutResource;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ProductDetailsService
{
    public function get(int $storeId, int $productId): ?array
    {
        $version = app(StorefrontCache::class)->version($storeId);
        $cacheKey = "product_details:v2:{$storeId}:{$productId}:{$version}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($storeId, $productId) {
            return $this->build($storeId, $productId);
        });
    }

    private function build(int $storeId, int $productId): ?array
    {
        $product = Product::convertCurrency($storeId)
            ->where('products.id', $productId)
            ->where('products.status', 'active')
            ->with([
                'getBrand:id,name',
                'getSupplier:id,name',
                'layout' => fn ($query) => $query->orderBy('position', 'asc'),
            ])
            ->withSum('reviews', 'rating')
            ->withCount('reviews')
            ->first();

        if (!$product) {
            return null;
        }

        $presenter = app(StorefrontProductPresenter::class);
        $images = $presenter->productImages($product);
        $variants = $product->getVariantsWithConversion($storeId)->get();
        $categories = $this->getCategoriesByIds($this->csvIds($product->category));
        $subcategories = $this->getCategoriesByIds($this->csvIds($product->subcategory));
        $customizable = $this->modulusEnabled($storeId, 121);
        $layout = $customizable
            ? $product->layout
                ->map(fn ($layout) => (new ProductLayoutResource($layout, $images))->resolve(request()))
                ->values()
                ->all()
            : null;

        $discountPrice = $product->regular_price <= $product->promotional_price ? 0 : $product->promotional_price;
        $calculateRegularPrice = (float) getPrice($product->regular_price, $discountPrice, $product->discount_type);

        $data = $presenter->detail($product, $variants, $categories, $subcategories, $layout);
        $data['calculate_regular_price'] = $calculateRegularPrice;
        $data['product_offer'] = $this->resolveProductOffer($product, $calculateRegularPrice, $storeId);

        return $data;
    }

    private function resolveProductOffer($product, float $regularPrice, int $storeId): array
    {
        $campaigns = $this->getActiveCampaigns($storeId);
        $productId = (string) $product->id;
        $categoryIds = array_merge(
            $this->csvIds($product->category),
            $this->csvIds($product->subcategory)
        );
        $currentTime = Carbon::now()->format('H:i');

        $checks = [
            ['length_type' => 'date_range', 'campaign_type' => 'product'],
            ['length_type' => 'date_range', 'campaign_type' => 'category'],
            ['length_type' => 'specific_date', 'campaign_type' => 'product'],
            ['length_type' => 'specific_date', 'campaign_type' => 'category'],
            ['length_type' => 'repeat_date', 'campaign_type' => 'product'],
            ['length_type' => 'repeat_date', 'campaign_type' => 'category'],
        ];

        foreach ($checks as $check) {
            $matched = $campaigns->filter(function ($campaign) use ($check, $productId, $categoryIds) {
                if ($campaign->length_type !== $check['length_type'] || $campaign->campaign_type !== $check['campaign_type']) {
                    return false;
                }

                if ($check['campaign_type'] === 'product') {
                    return $this->csvContains($campaign->products, $productId);
                }

                return $this->campaignMatchesCategories($campaign->category, $categoryIds);
            });

            foreach ($matched as $campaign) {
                $offer = $this->campaignOfferIfActive($campaign, $regularPrice, $currentTime);

                if ($offer !== null) {
                    return $offer;
                }
            }
        }

        return $this->emptyOffer();
    }

    private function getActiveCampaigns(int $storeId)
    {
        $version = app(StorefrontCache::class)->version($storeId);
        $cacheKey = "store_active_campaigns:{$storeId}:{$version}";
        $currentDate = Carbon::now()->format('Y-m-d');
        $currentDay = Carbon::now()->format('l');

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($storeId, $currentDate, $currentDay) {
            return Campaign::convertCurrency($storeId)
                ->where('campaigns.status', 'active')
                ->where('campaigns.store_id', $storeId)
                ->where(function ($query) use ($currentDate, $currentDay) {
                    $query->where(function ($dateRange) use ($currentDate) {
                        $dateRange->where('campaigns.length_type', 'date_range')
                            ->where('campaigns.start_date', '<=', $currentDate)
                            ->where('campaigns.end_date', '>=', $currentDate);
                    })->orWhere(function ($specificDate) use ($currentDate) {
                        $specificDate->where('campaigns.length_type', 'specific_date')
                            ->where('campaigns.specific_dates', $currentDate);
                    })->orWhere(function ($repeatDate) use ($currentDay) {
                        $repeatDate->where('campaigns.length_type', 'repeat_date')
                            ->whereRaw('FIND_IN_SET(?, campaigns.repeat_dates)', [$currentDay]);
                    });
                })
                ->get();
        });
    }

    private function campaignOfferIfActive($campaign, float $regularPrice, string $currentTime): ?array
    {
        if (isset($campaign->start_time, $campaign->end_time)) {
            if ($campaign->start_time > $currentTime || $campaign->end_time < $currentTime) {
                return null;
            }
        }

        return $this->formatOfferResponse($regularPrice, $campaign);
    }

    private function formatOfferResponse(float $regularPrice, $campaign): array
    {
        $offerPrice = getPrice($regularPrice, $campaign->discount_amount, $campaign->discount_type);
        $discountAmount = getDiscountAmount($regularPrice, $campaign->discount_amount, $campaign->discount_type);

        return [
            'status' => true,
            'message' => 'Success',
            'offer_price' => $offerPrice ?? null,
            'offer_amount' => $discountAmount ?? null,
            'discount_type' => $campaign->discount_type ?? null,
            'discount_amount' => $campaign->discount_amount ?? null,
            'shipping_area' => $campaign->shipping_area ?? null,
        ];
    }

    private function emptyOffer(): array
    {
        return [
            'status' => false,
            'message' => 'No active offers found',
            'offer_price' => null,
            'offer_amount' => null,
            'discount_type' => null,
            'discount_amount' => null,
            'shipping_area' => null,
        ];
    }

    private function getCategoriesByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        return Category::whereIn('id', $ids)
            ->where('status', 'active')
            ->select('id', 'name', 'status')
            ->get()
            ->all();
    }

    private function modulusEnabled(int $storeId, int $modulusId): bool
    {
        return (bool) Cache::remember(
            "modulus_status:{$storeId}:{$modulusId}",
            now()->addMinutes(10),
            fn () => ModulusStatus($storeId, $modulusId)
        );
    }

    private function csvIds($value): array
    {
        if (empty($value)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', (string) $value))));
    }

    private function csvContains(?string $haystack, string $needle): bool
    {
        if (empty($haystack)) {
            return false;
        }

        return in_array($needle, array_map('trim', explode(',', $haystack)), true);
    }

    private function campaignMatchesCategories(?string $campaignCategories, array $productCategoryIds): bool
    {
        if (empty($campaignCategories) || empty($productCategoryIds)) {
            return false;
        }

        $campaignCategoryIds = array_map('trim', explode(',', $campaignCategories));

        return !empty(array_intersect(
            $campaignCategoryIds,
            array_map('strval', $productCategoryIds)
        ));
    }
}
