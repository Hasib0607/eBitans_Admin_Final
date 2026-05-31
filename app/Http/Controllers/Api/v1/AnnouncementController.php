<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            if (isset($request->name) && !empty($request->name)) {
                $store_id = $this->getStoreByURL($request->name) ?? "";

                $isModulus = ModulusStatus($store_id, 117);

                if ($isModulus) {
                    // Retrieve paginated blogs, manipulate images, and return JSON response
                    $announcement = Announcement::where('store_id', $store_id)->where('status', 1)->get();

                    if (is_null($announcement)) {
                        // Return a 404 response if blog not found
                        return response()->json(['status' => false, 'message' => 'No announcement found.', 'data' => ""]);
                    }

                    return response()->json(['status' => true, "message" => "Success", 'data' => $announcement]);
                }

                return response()->json(['status' => false, 'data' => "", "message" => "You have to active this modulus"]);
            }
            return response()->json(['status' => false, 'message' => 'No store found.'], 400);

        } catch (\Exception $e) {
            // Return error response in case of exception
            return response()->json([
                'status' => false,
                'error_message' => "Something went wrong.",
            ], 500);
        }
    }

    public function getStoreByURL($name = "")
    {
        $store = Store::where('url', $name)->where('expiry_date', '>=', Carbon::now())->first();
        return $store->id ?? "";
    }

}
