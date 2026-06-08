<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductLayoutResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Headersetting;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Product;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Temposition;
use App\Models\Testimonial;
use App\Services\Storefront\StorefrontCache;
use App\Services\Storefront\StorefrontProductPresenter;
use App\Services\Storefront\StorefrontStoreResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StorefrontController extends Controller
{
    public function bootstrap(Request $request, string $domain): JsonResponse
    {
        $metrics = $this->startMetrics($request);

        try {
            $store = app(StorefrontStoreResolver::class)->resolve($domain);

            if (!$store) {
                return response()->json(['status' => false, 'message' => 'Store not found!'], 404);
            }

            $categories = $this->getCategoriesForStore($store->id, $request->boolean('include_counts'));
            $modules = $this->getModules($store->id);

            $payload = [
                'status' => true,
                'message' => 'Success',
                'data' => [
                    'store' => $this->storePayload($store),
                    'design' => $this->getDesign($store->id),
                    'headersetting' => $this->getHeaderSetting($store->id),
                    'layout' => $this->getLayout($store),
                    'menu' => $this->getMenu($store->id),
                    'page' => $this->getPages($store->id),
                    'category' => CategoryResource::collection($categories['categories'])->resolve($request),
                    'subcategory' => $categories['subcategories'],
                    'modules' => $modules,
                    'marketing_modules' => $this->getMarketingModules($modules),
                ],
            ];

            return $this->storefrontResponse($request, $payload, $metrics);
        } catch (\Exception $exception) {
            return serverError();
        }
    }

    public function home(Request $request, string $domain): JsonResponse
    {
        $metrics = $this->startMetrics($request);

        try {
            $store = app(StorefrontStoreResolver::class)->resolve($domain);

            if (!$store) {
                return response()->json(['status' => false, 'message' => 'Store not found!'], 404);
            }

            if (!$request->boolean('debug')) {
                $cacheKey = $this->homeCacheKey($store->id, $request);
                $payload = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request, $store) {
                    return $this->buildHomePayload($request, $store);
                });

                return $this->storefrontResponse($request, $payload, $metrics);
            }

            $payload = $this->buildHomePayload($request, $store);

            return $this->storefrontResponse($request, $payload, $metrics);
        } catch (\Exception $exception) {
            return serverError();
        }
    }

    private function buildHomePayload(Request $request, object $store): array
    {
        $design = $this->getDesign($store->id);
        $layout = $this->filterRequestedSections($this->getActiveLayout($store, $design), $request);

        $data = [
            'layout' => $layout,
        ];

        foreach ($layout as $section) {
            switch ($section) {
                case 'hero_slider':
                case 'slider':
                    $data['slider'] = SliderResource::collection($this->getSliders($store->id))->resolve($request);
                    break;

                case 'banner':
                    $data['banner'] = BannerResource::collection($this->getBanners($store->id))->resolve($request);
                    break;

                case 'feature_category':
                case 'category':
                    $categories = $this->getCategoriesForStore($store->id, $request->boolean('include_counts'));
                    $data['category'] = CategoryResource::collection($categories['categories'])->resolve($request);
                    break;

                case 'product':
                    $data['products'] = $this->getCompactProducts($store->id, null, $request);
                    break;

                case 'feature_product':
                    $data['feature_products'] = $this->getCompactProducts($store->id, 'feature', $request);
                    break;

                case 'best_sell_product':
                    $data['best_sell_products'] = $this->getCompactProducts($store->id, 'best_sell', $request);
                    break;

                case 'new_arrival':
                case 'new_arrival_product':
                case 'new_arrival_products':
                    $data['new_arrival_products'] = $this->getCompactProducts($store->id, 'new_arrival', $request);
                    break;

                case 'testimonial':
                    $data['testimonial'] = TestimonialResource::collection($this->getTestimonials($store->id))->resolve($request);
                    break;

                case 'brand':
                    $data['brand'] = BrandResource::collection($this->getBrands($store->id))->resolve($request);
                    break;
            }
        }

        return [
            'status' => true,
            'message' => 'Success',
            'store_id' => $store->id,
            'data' => $data,
        ];
    }

    public function productPage(Request $request, string $domain, string $product): JsonResponse
    {
        $metrics = $this->startMetrics($request);

        try {
            $store = app(StorefrontStoreResolver::class)->resolve($domain);

            if (!$store) {
                return response()->json(['status' => false, 'message' => 'Store not found!'], 404);
            }

            $productData = $this->findProduct($store->id, $product);

            if (!$productData) {
                return response()->json(['status' => false, 'message' => 'Product not found!', 'data' => []], 404);
            }

            $presenter = app(StorefrontProductPresenter::class);
            $images = $presenter->productImages($productData);
            $variants = $productData->getVariantsWithConversion($store->id)->get();
            $categoryIds = $this->csvIds($productData->category);
            $subcategoryIds = $this->csvIds($productData->subcategory);
            $categories = $this->getCategoriesByIds($categoryIds);
            $subcategories = $this->getCategoriesByIds($subcategoryIds);
            $customizable = ModulusStatus($store->id, 121);
            $layout = $customizable
                ? $productData->layout->map(fn ($layout) => new ProductLayoutResource($layout, $images))->values()
                : null;

            $payload = [
                'status' => true,
                'message' => 'Success',
                'store_id' => $store->id,
                'data' => [
                    'product' => $presenter->detail($productData, $variants, $categories, $subcategories, $layout),
                    'related_products' => $this->getRelatedProducts($store->id, $productData, $request),
                    'reviews' => $this->getReviewBundle($productData->id),
                    'store_settings' => [
                        'store' => $this->storePayload($store),
                        'design' => $this->getDesign($store->id),
                        'headersetting' => $this->getHeaderSetting($store->id),
                        'modules' => [
                            'custom_product_layout' => (bool) $customizable,
                        ],
                    ],
                ],
            ];

            return $this->storefrontResponse($request, $payload, $metrics);
        } catch (\Exception $exception) {
            return serverError();
        }
    }

    public function shell(Request $request, string $domain): JsonResponse
    {
        $metrics = $this->startMetrics($request);

        try {
            $store = app(StorefrontStoreResolver::class)->resolve($domain);

            if (!$store) {
                return response()->json(['status' => false, 'message' => 'Store not found!'], 404);
            }

            $design = $this->getDesign($store->id);
            $modules = $this->getModules($store->id);

            $payload = [
                'status' => true,
                'message' => 'Success',
                'store_id' => $store->id,
                'data' => [
                    'store' => $this->storePayload($store),
                    'headersetting' => $this->getHeaderSetting($store->id),
                    'menu' => $this->getMenu($store->id),
                    'footer' => [
                        'design' => $design['footer'] ?? null,
                        'pages' => $this->getPages($store->id),
                    ],
                    'modules' => $modules,
                    'marketing_modules' => $this->getMarketingModules($modules),
                ],
            ];

            return $this->storefrontResponse($request, $payload, $metrics);
        } catch (\Exception $exception) {
            return serverError();
        }
    }

    private function getDesign(int $storeId): ?array
    {
        $cacheKey = "design_layout_store_v2_{$storeId}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($storeId) {
            $design = DB::table('designs')->where('store_id', $storeId)->first();

            if (!$design) {
                return null;
            }

            $design = (array) $design;

            foreach ($this->designColumns() as $column) {
                if (array_key_exists($column, $design) && $design[$column] === 'none') {
                    $design[$column] = null;
                }
            }

            return $design;
        });
    }

    private function getHeaderSetting(int $storeId): ?object
    {
        return Headersetting::convertCurrency($storeId)->first();
    }

    private function getLayout(object $store): array
    {
        $cacheKey = "layout_positions_{$store->id}_{$store->template_id}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($store) {
            $tempPositions = Temposition::where('template_id', $store->template_id)
                ->select('name', 'position')
                ->pluck('position', 'name')
                ->toArray();

            $designPositions = DB::table('design_positions')
                ->where('store_id', $store->id)
                ->select('name', 'position')
                ->orderBy('position', 'asc')
                ->pluck('position', 'name')
                ->toArray();

            $merged = array_merge($tempPositions, $designPositions);
            asort($merged);

            return array_values(array_map(fn ($section) => $this->normalizeSectionName($section), array_keys($merged)));
        });
    }

    private function getActiveLayout(object $store, ?array $design): array
    {
        return array_values(array_filter(
            $this->getLayout($store),
            fn ($section) => $this->isSectionEnabled($section, $design)
        ));
    }

    private function filterRequestedSections(array $layout, Request $request): array
    {
        $requested = $this->requestedSections($request);

        if (empty($requested)) {
            return $layout;
        }

        return array_values(array_filter($layout, fn ($section) => in_array($section, $requested, true)));
    }

    private function requestedSections(Request $request): array
    {
        if (!$request->filled('sections')) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map(
            fn ($section) => $this->normalizeSectionName(trim($section)),
            explode(',', $request->query('sections'))
        ))));
    }

    private function homeCacheKey(int $storeId, Request $request): string
    {
        $version = app(StorefrontCache::class)->version($storeId);
        $sections = $this->requestedSections($request);
        $fields = $this->requestedProductFields($request) ?? [];
        $includeCounts = $request->boolean('include_counts') ? 'counts' : 'no-counts';

        return 'storefront:home:' . $storeId
            . ':v' . $version
            . ':sections:' . md5(implode(',', $sections))
            . ':fields:' . md5(implode(',', $fields))
            . ':' . $includeCounts;
    }

    private function getMenu(int $storeId)
    {
        return Menu::where('store_id', $storeId)->orderBy('sort', 'ASC')->get();
    }

    private function getPages(int $storeId)
    {
        return Page::where('store_id', $storeId)
            ->where('status', 'active')
            ->get(['id', 'name', 'slug', 'status', 'store_id']);
    }

    private function getSliders(int $storeId)
    {
        return Slider::where('store_id', $storeId)
            ->where('status', 'active')
            ->orderBy('position', 'ASC')
            ->get();
    }

    private function getBanners(int $storeId)
    {
        return Banner::where('store_id', $storeId)
            ->where('status', 'active')
            ->get();
    }

    private function getTestimonials(int $storeId)
    {
        return Testimonial::where('store_id', $storeId)
            ->where('status', 'active')
            ->get();
    }

    private function getBrands(int $storeId)
    {
        return Brand::where('store_id', $storeId)->get(['id', 'name', 'image']);
    }

    private function getCategoriesForStore(int $storeId, bool $includeCounts = false): array
    {
        $counts = $includeCounts ? $this->getProductCategoryCounts($storeId) : [
            'category' => [],
            'subcategory' => [],
        ];

        $categories = Category::where('store_id', $storeId)
            ->where('parent', 0)
            ->where('status', 'active')
            ->orderBy('position', 'ASC')
            ->with([
                'subcategories' => function ($query) use ($storeId) {
                    $query->where('store_id', $storeId)
                        ->where('status', 'active')
                        ->orderBy('position', 'ASC');
                },
            ])
            ->get()
            ->map(function ($category) use ($counts) {
                $category->total_products = $counts['category'][$category->id] ?? 0;

                $category->subcategories->each(function ($subcategory) use ($counts) {
                    $subcategory->total_products = $counts['subcategory'][$subcategory->id] ?? 0;
                });

                return $category;
            });

        $subcategories = $categories
            ->flatMap(fn ($category) => $category->subcategories)
            ->values()
            ->map(function ($subcategory) {
                return [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'slug' => $subcategory->slug,
                    'parent' => $subcategory->parent,
                    'status' => $subcategory->status,
                    'position' => $subcategory->position,
                    'store_id' => $subcategory->store_id,
                    'total_products' => $subcategory->total_products ?? 0,
                ];
            });

        return [
            'categories' => $categories,
            'subcategories' => $subcategories,
        ];
    }

    private function getProductCategoryCounts(int $storeId): array
    {
        $counts = [
            'category' => [],
            'subcategory' => [],
        ];

        Product::where('store_id', $storeId)
            ->where('status', 'active')
            ->get(['category', 'subcategory'])
            ->each(function ($product) use (&$counts) {
                foreach ($this->csvIds($product->category) as $categoryId) {
                    $counts['category'][$categoryId] = ($counts['category'][$categoryId] ?? 0) + 1;
                }

                foreach ($this->csvIds($product->subcategory) as $subcategoryId) {
                    $counts['subcategory'][$subcategoryId] = ($counts['subcategory'][$subcategoryId] ?? 0) + 1;
                }
            });

        return $counts;
    }

    private function getModules(int $storeId): array
    {
        return DB::table('moduluses')
            ->leftJoin('buy_moduluses', function ($join) use ($storeId) {
                $join->on('moduluses.id', '=', 'buy_moduluses.modulus_id')
                    ->where('buy_moduluses.store_id', $storeId);
            })
            ->select(
                'moduluses.id as modulus_id',
                'moduluses.name',
                'moduluses.status as module_status',
                'buy_moduluses.id',
                'buy_moduluses.store_id',
                'buy_moduluses.price',
                'buy_moduluses.type',
                'buy_moduluses.start_date',
                'buy_moduluses.end_date',
                'buy_moduluses.sms_count',
                'buy_moduluses.status'
            )
            ->get()
            ->mapWithKeys(function ($module) {
                return [
                    $module->modulus_id => [
                        'id' => $module->id,
                        'store_id' => $module->store_id,
                        'modulus_id' => $module->modulus_id,
                        'name' => $module->name,
                        'price' => $module->price,
                        'type' => $module->type,
                        'start_date' => $module->start_date,
                        'end_date' => $module->end_date,
                        'sms_count' => $module->sms_count,
                        'status' => $module->status,
                        'enabled' => (bool) ($module->module_status == 1 && $module->status == 1),
                    ],
                ];
            })
            ->all();
    }

    private function getMarketingModules(array $modules): array
    {
        return [
            'facebook_pixel' => (bool) ($modules[11]['enabled'] ?? false),
            'google_analytics' => (bool) ($modules[10]['enabled'] ?? false),
        ];
    }

    private function getCompactProducts(int $storeId, ?string $type = null, ?Request $request = null): array
    {
        $products = Product::convertCurrency($storeId)
            ->where('products.status', 'active')
            ->when($type === 'feature', fn ($query) => $query->where('products.feature', 1))
            ->when($type === 'best_sell', fn ($query) => $query->where('products.best_sell', 1))
            ->with(['getBrand:id,name'])
            ->withSum('reviews', 'rating')
            ->withCount('reviews')
            ->when($type === 'new_arrival', function ($query) {
                $query->orderBy('products.created_at', 'desc')
                    ->orderBy('products.id', 'desc');
            }, function ($query) {
                $query->orderBy('products.position', 'ASC')
                    ->orderBy('products.id', 'DESC');
            })
            ->limit(10)
            ->get();

        $fields = $this->requestedProductFields($request);
        $presenter = app(StorefrontProductPresenter::class);

        return $products->map(fn ($product) => $presenter->compact($product, $fields))->all();
    }

    private function findProduct(int $storeId, string $product)
    {
        $query = Product::convertCurrency($storeId)
            ->where('products.status', 'active')
            ->with([
                'getBrand:id,name',
                'getSupplier:id,name',
                'layout' => fn ($query) => $query->orderBy('position', 'asc'),
            ])
            ->withSum('reviews', 'rating')
            ->withCount('reviews');

        if (is_numeric($product)) {
            return $query->where('products.id', $product)->first();
        }

        return $query
            ->whereRaw("LOWER(REPLACE(products.name, ' ', '-')) = ?", [strtolower($product)])
            ->first();
    }

    private function getRelatedProducts(int $storeId, $product, Request $request): array
    {
        $categoryId = $this->csvIds($product->category)[0] ?? null;

        $products = Product::convertCurrency($storeId)
            ->where('products.status', 'active')
            ->where('products.id', '!=', $product->id)
            ->when($categoryId, fn ($query) => $query->whereRaw('FIND_IN_SET(?, products.category)', [$categoryId]))
            ->with(['getBrand:id,name'])
            ->withSum('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('products.position', 'ASC')
            ->orderBy('products.id', 'DESC')
            ->limit(8)
            ->get();

        $fields = $this->requestedProductFields($request);
        $presenter = app(StorefrontProductPresenter::class);

        return $products->map(fn ($relatedProduct) => $presenter->compact($relatedProduct, $fields))->all();
    }

    private function getReviewBundle(int $productId): array
    {
        $reviews = Review::query()
            ->leftJoin('users', 'users.id', '=', 'reviews.uid')
            ->where('reviews.product_id', $productId)
            ->orderBy('reviews.id', 'DESC')
            ->get([
                'reviews.id',
                'reviews.name',
                'reviews.comment',
                'reviews.rating',
                'reviews.created_at',
                'users.image',
                'users.created_at as user_created_at',
            ]);

        return [
            'summary' => [
                'count' => $reviews->count(),
                'average_rating' => $reviews->count() > 0 ? round($reviews->avg('rating'), 2) : 0,
            ],
            'items' => $reviews->map(fn ($review) => [
                'id' => $review->id,
                'name' => $review->name ?? '',
                'image' => getPath(($review->image ?? ''), 'assets/images/img'),
                'ucd' => $review->user_created_at ?? '',
                'comment' => $review->comment ?? '',
                'rating' => $review->rating ?? '',
                'cd' => $review->created_at ?? '',
            ])->all(),
        ];
    }

    private function getCategoriesByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        return Category::whereIn('id', $ids)
            ->where('status', 'active')
            ->get(['id', 'name', 'slug', 'status'])
            ->all();
    }

    private function requestedProductFields(?Request $request): ?array
    {
        if (!$request || !$request->filled('fields')) {
            return null;
        }

        return array_filter(array_map('trim', explode(',', $request->query('fields'))));
    }

    private function storePayload(object $store): array
    {
        $data = (array) $store;

        unset($data['bkash_token']);

        return $data;
    }

    private function designColumns(): array
    {
        return [
            'header',
            'hero_slider',
            'banner',
            'banner_bottom',
            'feature_category',
            'product',
            'feature_product',
            'best_sell_product',
            'new_arrival',
            'testimonial',
            'youtube',
            'announcement',
            'about',
            'newsletter',
            'brand',
            'footer',
            'auth',
            'single_product_page',
            'shop_page',
            'checkout_page',
            'login_page',
            'profile_page',
            'invoice',
            'product_card',
            'product_modal',
            'preloader',
            'mobile_bottom_menu',
            'offer',
            'blog',
            'contact',
        ];
    }

    private function normalizeSectionName(string $section): string
    {
        return match ($section) {
            'slider', 'hero' => 'hero_slider',
            'new_arrival_product', 'new_arrival_products' => 'new_arrival',
            default => $section,
        };
    }

    private function isSectionEnabled(string $section, ?array $design): bool
    {
        if (!$design) {
            return true;
        }

        $designKey = $this->normalizeSectionName($section);

        if (!array_key_exists($designKey, $design)) {
            return true;
        }

        return !empty($design[$designKey]) && $design[$designKey] !== 'none';
    }

    private function csvIds($value, bool $numeric = true): array
    {
        if (empty($value)) {
            return [];
        }

        $items = array_filter(array_map('trim', explode(',', (string) $value)));

        if (!$numeric) {
            return $items;
        }

        return array_values(array_filter($items, fn ($item) => is_numeric($item)));
    }

    private function startMetrics(Request $request): array
    {
        $metrics = [
            'enabled' => $request->boolean('debug'),
            'started_at' => microtime(true),
            'queries' => [],
        ];

        if ($metrics['enabled']) {
            DB::listen(function ($query) use (&$metrics) {
                $metrics['queries'][] = [
                    'sql' => $query->sql,
                    'time_ms' => $query->time,
                ];
            });
        }

        return $metrics;
    }

    private function withMetrics(Request $request, array $payload, array $metrics): array
    {
        if (!$metrics['enabled']) {
            return $payload;
        }

        $queries = collect($metrics['queries']);

        $payload['meta'] = [
            'elapsed_ms' => round((microtime(true) - $metrics['started_at']) * 1000, 2),
            'query_count' => $queries->count(),
            'query_time_ms' => round($queries->sum('time_ms'), 2),
            'payload_bytes' => strlen(json_encode($payload)),
            'slow_queries' => $queries
                ->sortByDesc('time_ms')
                ->take(10)
                ->values()
                ->all(),
        ];

        return $payload;
    }

    private function storefrontResponse(Request $request, array $payload, array $metrics): JsonResponse
    {
        $response = response()->json($this->withMetrics($request, $payload, $metrics));

        if ($request->boolean('debug')) {
            $response->header('Cache-Control', 'no-store');

            return $this->gzipResponseIfSupported($request, $response);
        }

        $response
            ->header('Cache-Control', 'public, s-maxage=60, stale-while-revalidate=300')
            ->header('Vary', 'Accept-Encoding');

        return $this->gzipResponseIfSupported($request, $response);
    }

    private function gzipResponseIfSupported(Request $request, JsonResponse $response): JsonResponse
    {
        if (!function_exists('gzencode') || !str_contains($request->header('Accept-Encoding', ''), 'gzip')) {
            return $response;
        }

        if ($response->headers->has('Content-Encoding')) {
            return $response;
        }

        $compressed = gzencode($response->getContent(), 6);

        if ($compressed === false) {
            return $response;
        }

        $response->setContent($compressed);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Content-Length', (string) strlen($compressed));
        $response->headers->set('Vary', 'Accept-Encoding');

        return $response;
    }
}
