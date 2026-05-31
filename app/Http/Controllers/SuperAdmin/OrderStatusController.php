<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderStatusController extends Controller
{

    /**
     *
     * Display all order status
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index($id = null)
    {
        if (Auth::check() && Auth::user()->type == "superadmin") {
            $data['statuses'] = OrderStatus::all();

            if (!is_null($id)) {
                $status = OrderStatus::where('id', $id)->first();
                $data['status'] = $status;
            }

            return view('superadmin.orderStatus.index', $data);
        }

        return redirect()->back()->with("error", "You are not authorized to access this page.");
    }

    /**
     *
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->type == "superadmin") {
            if (!isset($request->name) || empty($request->name) || !isset($request->name_bn) || empty($request->name_bn) || !isset($request->slug) || empty($request->slug)) {
                return redirect()->back()->with("error", "Name/Name Bangla/Slug is required.");
            }

            $msg = 'Status updated successfully!';

            $orderStatus = OrderStatus::where('id', $request->id)->first();
            if (!isset($orderStatus)) {
                $orderStatus = new OrderStatus();
                $orderStatus->status = "1";

                $msg = 'Status added successfully!';
            }

            $orderStatus->name = $request->name;
            $orderStatus->name_bn = $request->name_bn;
            $orderStatus->slug = $request->slug ?? $request->name;
            $orderStatus->save();

            Session::flash('success', $msg);
            return redirect()->back();
        }

        return redirect()->back()->with("error", "You are not authorized to access this page.");
    }


    /**
     *
     * Change blog type status
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function changeBlogTypeStatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Item');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $orderStatus = OrderStatus::find($ids);
                    $orderStatus->status = 1;
                    $orderStatus->save();
                }
            }

            Session::flash('success', 'Successfully Active order status');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $orderStatus = OrderStatus::find($ids);
                    $orderStatus->status = 0;
                    $orderStatus->save();
                }
            }

            Session::flash('success', 'Successfully Deactive order status');
            return back();
        }
    }

    /**
     *
     * Single blog type status change
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function singleBlogTypestatusChange(Request $request)
    {
        $id = $request->id;
        $orderStatus = OrderStatus::find($id);
        if (isset($orderStatus) && $orderStatus->status == '1') {
            $orderStatus->status = '0';
        } else {
            $orderStatus->status = "1";
        }
        $orderStatus->save();

        return response()->json(['status' => true, "message" => "Status updated successfully."]);
    }


}
