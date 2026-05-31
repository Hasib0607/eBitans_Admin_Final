<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Models\AdminBlogKeyword;
use App\Models\AdminBlogType;
use App\Models\AdminBlog;
use Illuminate\Http\Request;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $store_id = NULL;
            if (isset($request->name) && !empty($request->name)) {
                $store_id = $this->getStoreByURL($request->name);
            }

            if (!is_null($store_id) && empty($store_id)) {
                return response()->json(['status' => 404, 'message' => 'No store found.']);
            }

            // Retrieve paginated blogs, manipulate images, and return JSON response
            $blogs = $this->getBlogsQuery()
                ->select('id', 'type', 'title', 'sub_title', 'key_word', 'thumbnail', 'image', 'slug', 'permalink', 'canonical_url', 'custom_script', 'website', 'created_at', 'updated_at')
                ->where('store_id', $store_id)
                ->Paginate(10)->onEachSide(1)->setPath('');

            if (is_null($blogs)) {
                // Return a 404 response if blog not found
                return response()->json(['status' => 404, 'message' => 'No blogs found.']);
            }


            $this->manipulateBlogImages($blogs);
            return response()->json(['status' => 200, 'results' => $blogs]);
        } catch (\Exception $e) {
            // Return error response in case of exception
            return response()->json($this->generateErrorResponse(500, $e->getMessage()));
        }
    }

    public function getStoreByURL($name = "")
    {
        $store = Store::where('url', $name)->where('expiry_date', '>=', Carbon::now())->first();
        return $store->id ?? "";
    }

    /**
     * Retrieve blogs query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getBlogsQuery()
    {
        // Query to retrieve blogs with status 1 and order by ID descending
        return AdminBlog::query()->where('status', 1)->whereNull('deleted_at')->orderBy('id', 'desc');
    }

    /**
     * Manipulate blog image paths for display.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $blogs
     * @return void
     */
    private function manipulateBlogImages(&$blogs): void
    {
        // Manipulate each blog's image URLs and keywords
        foreach ($blogs as $blog) {
            $this->setBlogImageUrls($blog);
            $this->setBlogKeywords($blog);
        }
    }

    /**
     * Set blog image URLs.
     *
     * @param \App\Models\AdminBlog $blog
     * @return void
     */
    private function setBlogImageUrls(AdminBlog $blog): void
    {
        // Set the thumbnail and image URLs for a blog
        $blog->thumbnail = $this->getImageUrl($blog->thumbnail);
        $blog->image = $this->getImageUrl($blog->image);
    }

    /**
     * Get full image URL.
     *
     * @param string|null $imageName
     * @return string
     */
    private function getImageUrl(?string $imageName): string
    {
        // Generate full image URL based on the image name
        return empty($imageName) ? url('/assets/images/icon/default_category_icon.jpg') : url('/BlogImages/' . $imageName);
    }

    /**
     * Set blog keywords.
     *
     * @param \App\Models\AdminBlog $blog
     * @return void
     */
    private function setBlogKeywords(AdminBlog $blog): void
    {
        // Set keywords for a blog
        $keywordIds = is_array($blog->key_word) ? $blog->key_word : json_decode($blog->key_word, true);
        $keywordNames = [];

        if (!is_null($keywordIds)) {
            $allKeywords = AdminBlogKeyword::all();

            foreach ($keywordIds as $keywordId) {
                $keyword = $allKeywords->where('id', $keywordId)->first();

                if ($keyword) {
                    $keywordNames[] = $keyword->name;
                }
            }
        }

        $blog->key_word = $keywordNames;
    }

    /**
     * Generate an error response.
     *
     * @param int $statusCode
     * @param string $message
     * @return array
     */
    private function generateErrorResponse($statusCode, $message): array
    {
        // Format the error response
        return [
            'status' => $statusCode,
            'error_message' => $message,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug): JsonResponse
    {
        try {
            // Retrieve and format a single blog, then return JSON response
            $blog = AdminBlog::where(function ($q) use ($slug) {
                $q->where('permalink', $slug)->orWhere('slug', $slug);
            })->where('status', 1)->first();

            if (is_null($blog)) {
                // Return a 404 response if blog not found
                return response()->json(['status' => 404, 'message' => 'No blogs found.']);
            }

            $this->setBlogImageUrls($blog);
            $this->setBlogKeywords($blog);

            return response()->json(['status' => 200, 'details' => $blog]);
        } catch (\Exception $e) {
            // Return error response in case of exception
            return response()->json($this->generateErrorResponse(500, $e->getMessage()));
        }
    }

    /**
     * Retrieve all blog types along with their associated posts and return as JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function blogTypes(Request $request, string $id = null)
    {
        try {
            $store_id = NULL;
            if (isset($request->name) && !empty($request->name)) {
                $store_id = $this->getStoreByURL($request->name);
            }

            if (!is_null($store_id) && empty($store_id)) {
                return response()->json(['status' => 404, 'message' => 'No store found.']);
            }
            // Retrieve all blog types and their associated posts, then return JSON response
            $blogTypes = AdminBlogType::where("store_id", $store_id)->get();

            if (is_null($blogTypes)) {
                return response()->json(['status' => 404, 'blogTypes' => 'type not found']);
            }

            if (!is_null($id)) {
                $blogTypes = AdminBlogType::where("store_id", $store_id)->where("id", $id)->first();
                if (is_null($blogTypes)) {
                    return response()->json(['status' => 404, 'blogTypes' => 'type not found']);
                }

                $posts = $this->getBlogsQuery()
                    ->select('id', 'type', 'title', 'sub_title', 'key_word', 'thumbnail', 'image', 'slug', 'permalink', 'canonical_url', 'custom_script', 'website', 'created_at', 'updated_at')
                    ->where('type', $blogTypes->id)
                    ->where('status', 1)
                    ->paginate(6)->onEachSide(1)->setPath('');
                $this->manipulateBlogImages($posts);
                $blogTypes->posts = $posts;
            }

            return response()->json(['status' => 200, 'blogTypes' => $blogTypes]);
        } catch (\Exception $e) {
            // Return error response in case of exception
            return response()->json($this->generateErrorResponse(500, $e->getMessage()));
        }
    }

    public function popularBlog(Request $request)
    {
        try {
            $store_id = NULL;
            if (isset($request->name) && !empty($request->name)) {
                $store_id = $this->getStoreByURL($request->name);
            }

            if (!is_null($store_id) && empty($store_id)) {
                return response()->json(['status' => 404, 'message' => 'No store found.']);
            }

            // Retrieve paginated blogs, manipulate images, and return JSON response
            $blogs = $this->getBlogsQuery()
                ->select('id', 'type', 'title', 'sub_title', 'key_word', 'thumbnail', 'image', 'slug', 'permalink', 'canonical_url', 'custom_script', 'website', 'created_at', 'updated_at')
                ->where("store_id", $store_id)
                ->where('popular', 1)
                ->where('status', 1)
                ->Paginate(20)->onEachSide(1)->setPath('');

            if (is_null($blogs)) {
                // Return a 404 response if blog not found
                return response()->json(['status' => 404, 'message' => 'No popular blogs found.']);
            }


            foreach ($blogs as $blog) {
                $this->setBlogImageUrls($blog);
            }

            return response()->json(['status' => 200, 'results' => $blogs]);
        } catch (\Exception $e) {
            // Return error response in case of exception
            return response()->json($this->generateErrorResponse(500, $e->getMessage()));
        }
    }

    public function siteMap(Request $request)
    {
        try {
            $store_id = NULL;
            if (isset($request->name) && !empty($request->name)) {
                $store_id = $this->getStoreByURL($request->name);
            }

            if (!is_null($store_id) && empty($store_id)) {
                return response()->json(['status' => 404, 'message' => 'No store found.']);
            }

            // Retrieve blogs slug return JSON response
            $blogs = $this->getBlogsQuery()
                ->select('id', 'slug', 'permalink', 'website', 'canonical_url', 'custom_script', 'created_at', 'updated_at')
                ->where("store_id", $store_id)
                ->orderByDesc('id')
                ->get();

            if (is_null($blogs)) {
                // Return a 404 response if blog not found
                return response()->json(['status' => 404, 'message' => 'No blogs found.']);
            }

            return response()->json(['status' => 200, 'results' => $blogs]);
        } catch (\Exception $e) {
            // Return error response in case of exception
            return response()->json($this->generateErrorResponse(500, $e->getMessage()));
        }

    }

    /**
     * Generate a standard response format for API.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $response
     * @return array
     */
    private function generateResponse($response): array
    {
        // Format the response data
        return [
            'status' => 200,
            'total' => $response->total(),
            'per_page' => $response->perPage(),
            'current_page' => $response->currentPage(),
            'results' => $response->items(),
            'next_page_url' => $response->nextPageUrl(),
            'prev_page_url' => $response->previousPageUrl()
        ];
    }
}
