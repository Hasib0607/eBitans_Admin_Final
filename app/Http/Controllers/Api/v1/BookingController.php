<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\BookingCustomerFiled;
use Illuminate\Http\Request;
use App\Models\BuyModulus;
use App\Models\Modulus;
use App\Models\Store;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $is_module_active = BuyModulus::where('store_id', $request->store_id)->where('modulus_id', $request->modulus_id)->first();
        
        if (is_null($is_module_active)) {
            return response()->json([
                'status' => 404,
                'module' => 'The store id or module id not fund.'
            ]);
        }

        if ($is_module_active->status == 0) {
            return response()->json([
                'status' => 404,
                'module' => 'Module not active '
            ]);
        }
        
        $find_store_id = Store::where('id', $request->store_id)->first();
        if (empty($find_store_id)) {
            return response()->json([
                'status' => 404,
                'module' => 'Store id not found.'
            ]);
        }

        $module_is_find = Modulus::where('id', $request->modulus_id)->first();
        if (empty($module_is_find)) {
            return response()->json([
                'status' => 404,
                'module' => 'Module id not found.'
            ]);
        }
        
        $field = BookingCustomerFiled::
            selectRaw('booking_tags.name as field_name, booking_tags.type as type, CASE WHEN booking_customer_fields.is_required = 1 THEN "required" ELSE "optional" END as requirement_status')
            ->selectRaw('COALESCE(NULLIF(booking_customer_fields.name, ""), booking_tags.name) as c_name')
            ->leftJoin('booking_tags', function ($join) {
                $join->on('booking_tags.id', '=', 'booking_customer_fields.tagId');
            })
            ->where('booking_customer_fields.store_id', '=', $is_module_active->store_id)
            ->where('booking_customer_fields.modulus_id', '=', $is_module_active->modulus_id)
            ->where('booking_customer_fields.is_checked', '=', 1)
            ->get();

        $fieldType = BookingCustomerFiled::selectRaw("CASE WHEN is_single = 1 THEN 'single' ELSE 'double' END as from_type")
            ->where("modulus_id", "=", $is_module_active->modulus_id)
            ->where("store_id", "=", $is_module_active->store_id)
            ->where("is_checked", "=", 1)
            ->first();

        if ($field->isEmpty()) {
            return response()->json([
                'status' => 400,
                'error' => 'Data not found'
            ]);
        }

        return response()->json([
            'status' => 200,
            'from_type' => $fieldType->from_type,
            'data' => $field
        ]);
    }
}
