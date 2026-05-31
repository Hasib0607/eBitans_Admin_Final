<?php

namespace App\Http\Controllers\WhatsAppAutomation;

use App\Http\Controllers\Controller;
use App\Models\LiveClientShowcase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiveClientShowcaseController extends Controller
{
    protected function rulesForContentType(string $contentType, bool $isUpdate = false): array
    {
        $normalizedType = trim($contentType) !== '' ? trim($contentType) : 'live_client';
        $requiredIfCreate = $isUpdate ? ['sometimes'] : [];

        $urlRules = [...$requiredIfCreate, 'nullable', 'url', 'max:2048'];
        $videoUrlRules = [...$requiredIfCreate, 'nullable', 'url', 'max:2048'];

        if ($normalizedType === 'live_client') {
            $urlRules = [...$requiredIfCreate, 'required', 'url', 'max:2048'];
        } elseif (in_array($normalizedType, ['feature_video', 'admin_demo', 'success_story'], true)) {
            $videoUrlRules = [...$requiredIfCreate, 'required', 'url', 'max:2048'];
        }

        return [
            'title' => [...$requiredIfCreate, 'nullable', 'string', 'max:255'],
            'url' => $urlRules,
            'video_url' => $videoUrlRules,
            'video_title' => [...$requiredIfCreate, 'nullable', 'string', 'max:255'],
            'content_type' => [...$requiredIfCreate, 'nullable', 'string', 'max:50'],
            'feature_tag' => [...$requiredIfCreate, 'nullable', 'string', 'max:100'],
            'objection_type' => [...$requiredIfCreate, 'nullable', 'string', 'max:100'],
            'business_type' => [...$requiredIfCreate, 'nullable', 'string', 'max:100'],
            'description' => [...$requiredIfCreate, 'nullable', 'string', 'max:5000'],
            'sort_order' => [...$requiredIfCreate, 'nullable', 'integer', 'min:0'],
            'is_active' => [...$requiredIfCreate, 'boolean'],
        ];
    }

    public function botIndex(): JsonResponse
    {
        $items = LiveClientShowcase::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get([
                'id',
                'title',
                'url',
                'video_url',
                'video_title',
                'content_type',
                'feature_tag',
                'objection_type',
                'business_type',
                'description',
                'sort_order',
            ]);

        return response()->json([
            'success' => true,
            'urls' => $items->pluck('url')->filter()->values(),
            'data' => $items->map(fn (LiveClientShowcase $item) => $this->serializeItem($item))->values(),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $limit = max(1, min((int) $request->integer('limit', 25), 100));
        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', ''));
        $contentType = trim((string) $request->query('content_type', ''));

        $query = LiveClientShowcase::query()->orderBy('sort_order')->orderBy('id');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', '%' . $search . '%')
                    ->orWhere('url', 'like', '%' . $search . '%')
                    ->orWhere('video_url', 'like', '%' . $search . '%')
                    ->orWhere('video_title', 'like', '%' . $search . '%')
                    ->orWhere('feature_tag', 'like', '%' . $search . '%')
                    ->orWhere('objection_type', 'like', '%' . $search . '%')
                    ->orWhere('business_type', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($contentType !== '') {
            $query->where('content_type', $contentType);
        }

        if ($status !== '') {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $paginator = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'items' => collect($paginator->items())->map(fn (LiveClientShowcase $item) => $this->serializeItem($item))->values(),
            'pagination' => [
                'page' => $paginator->currentPage(),
                'limit' => $paginator->perPage(),
                'total' => $paginator->total(),
                'total_pages' => $paginator->lastPage(),
                'has_next' => $paginator->hasMorePages(),
                'has_prev' => $paginator->currentPage() > 1,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(
            $this->rulesForContentType((string) $request->input('content_type', 'live_client'))
        );

        $item = LiveClientShowcase::create([
            'title' => $validated['title'] ?? null,
            'url' => $validated['url'],
            'video_url' => $validated['video_url'] ?? null,
            'video_title' => $validated['video_title'] ?? null,
            'content_type' => $validated['content_type'] ?? 'live_client',
            'feature_tag' => $validated['feature_tag'] ?? null,
            'objection_type' => $validated['objection_type'] ?? null,
            'business_type' => $validated['business_type'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Live client showcase item created successfully.',
            'item' => $this->serializeItem($item),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $item = LiveClientShowcase::query()->findOrFail($id);

        return response()->json([
            'success' => true,
            'item' => $this->serializeItem($item),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = LiveClientShowcase::query()->findOrFail($id);
        $validated = $request->validate(
            $this->rulesForContentType((string) $request->input('content_type', $item->content_type ?: 'live_client'), true)
        );
        $item->fill($validated);
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Live client showcase item updated successfully.',
            'item' => $this->serializeItem($item),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = LiveClientShowcase::query()->findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Live client showcase item deleted successfully.',
        ]);
    }

    protected function serializeItem(LiveClientShowcase $item): array
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'url' => $item->url,
            'video_url' => $item->video_url,
            'video_title' => $item->video_title,
            'content_type' => $item->content_type ?: 'live_client',
            'feature_tag' => $item->feature_tag,
            'objection_type' => $item->objection_type,
            'business_type' => $item->business_type,
            'description' => $item->description,
            'sort_order' => (int) $item->sort_order,
            'is_active' => (bool) $item->is_active,
            'created_at' => optional($item->created_at)?->toDateTimeString(),
            'updated_at' => optional($item->updated_at)?->toDateTimeString(),
        ];
    }
}
