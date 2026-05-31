<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LayoutDesignResource;
use App\Models\Headersetting;
use App\Models\MarchantPaymentGetway;
use App\Models\PlanDetail;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreDesign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
{
    public function headerSettings(Request $request)
    {
        $name = $request->name;
        $store = Store::with('current_currency')
            ->where('url', $name)
            ->where('expiry_date', '>=', Carbon::now())
            ->first();

        if (isset($store)) {
            try {
                $header_setting = Headersetting::convertCurrency($store->id)->first();
                $header_setting['currency'] = $store->current_currency;
                $designs = StoreDesign::select('id', 'title', 'title_color', 'subtitle', 'subtitle_color', 'button', 'button_color', 'button_bg_color', 'button1', 'button1_color', 'button1_bg_color', "link", 'bg_image', 'image_description', 'type')
                    ->where('store_id', $store->id)
                    ->get()->groupBy('type');

                $designData = [];

                if (count($designs) > 0) {
                    foreach ($designs as $type => $design) {
                        $designData[$type] = $design->toArray();
                    }
                }

                $header_setting['custom_design'] = $designData;

                $header_setting['total_sms'] = getSmsCount($store->id);;

                $header_setting->amarpay = merchantPaymentStatus($store->id, 125, "amarpay", $header_setting->amarpay);
                $header_setting->merchant_bkash = merchantPaymentStatus($store->id, 128, "bkash", $header_setting->merchant_bkash);
                $header_setting->merchant_nagad = merchantPaymentStatus($store->id, 129, "nagad", $header_setting->merchant_nagad);
                $header_setting->merchant_rocket = merchantPaymentStatus($store->id, 130, "rocket", $header_setting->merchant_rocket);

                return response()->json($header_setting);
            } catch (\Exception $exception) {
                return response()->json(['error' => 'Not Found']);
            }

        }
        return response()->json(['error' => 'Not Found']);
    }

    public function layoutProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id' => 'required',
        ], [
            'name.required' => "Name is required",
            'id.required' => "ID is required",
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $name = $validator->validate()['name'];
        $store = Store::where('url', $name)
            ->where('expiry_date', '>=', Carbon::now())
            ->first();
        if (!isset($store)) {
            return response()->json(['error' => 'your account not found or expired'], 404);
        }

        $customizable = ModulusStatus($store->id, 121);
        if (!$customizable) {
            return response()->json(['error' => 'Access Denied'], 400);
        }
        $product = Product::with(['layout.design'])->convertCurrency($store->id)->where('products.id', $request->id)->first();
        if (!isset($product)) {
            return response()->json(['error' => 'product not found'], 404);
        }

        $final_product = new LayoutDesignResource($product);
        $final_product_json = $final_product->toJson();
        $final_product = json_decode($final_product_json, true);

        return response()->json(['success' => 'Get data with layout successfully', 'product' => $final_product]);
    }

    public function getProductForLayout($name)
    {
        $store = Store::where('url', $name)
            ->where('expiry_date', '>=', Carbon::now())
            ->first();
        if (!isset($store)) {
            return response()->json(['error' => 'your account not found or expired'], 404);
        }
        $customizable = ModulusStatus($store->id, 121);
        if (!$customizable) {
            return response()->json(['error' => 'Access Denied'], 400);
        }

        $products = Product::convertCurrency($store->id)
            ->distinct()
            ->join('product_layouts as layout', 'layout.product_id', '=', 'products.id')
            ->get();
        if (!isset($products)) {
            return response()->json(['error' => 'products not found'], 404);
        }
        return response()->json(['success' => 'Get data with layout successfully', 'products' => $products]);
    }
}
