<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\v2\CartController;
use App\Models\Cart;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class AbandonedCartController extends Controller
{

    public function abandonedCartList(Request $request)
    {

        $userData = getUserData();
        $store_id = $userData['store_id'];
        if (!ModulusStatus($store_id, 131)) {
            return redirect()->route("admin.index");
        }

        $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
        $search = $request->search;

        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        $data['search'] = $search;

        $query = Cart::with(["user", "product"])
            ->where("store_id", $store_id)
            ->selectRaw('*, COALESCE(user_id, session_id) as group_key') // Add a grouping key
            ->groupBy('group_key');

        if ($from_date && !$to_date) {
            $query->where('created_at', '>=', $from_date->startOfDay());
        } elseif (!$from_date && $to_date) {
            $query->where('created_at', '<=', $to_date->endOfDay());
        } elseif ($from_date && $to_date) {
            $query->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%$search%")
                    ->orWhereHas('user', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    })->orWhereHas('product', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%$search%");
                    });
            });
        }
        $data['usersList'] = $query->paginate(30);

        return view('admin.abandonedcart.index', $data);
    }


    public function abandonedCartItemList($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];
        if (!ModulusStatus($store_id, 131)) {
            return redirect()->route("admin.index");
        }

        if (!isset($id) || empty($id)) {
            return redirect()->back()->with('error', 'Invalid Request');
        }

        $cartList = Cart::with(['product', 'variant'])->where("id", $id)->get();

        if (isset($cartList) && count($cartList) > 0) {
            $productList = (new CartController())->formatCartResponse($cartList);

            return view('admin.abandonedcart.cartItem', ["productList" => $productList]);
        }
        return redirect()->back()->with('error', 'No item found in this cart');
    }

}
