<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdminBlog;
use App\Models\AdminBlogType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminBlogTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if (Auth::check() && Auth::user()->type == "superadmin") {
            $blogTypes = AdminBlogType::whereNull("store_id")->get();
        } elseif (Auth::check() && Auth::user()->type == "superstaff") {
            $blogTypes = AdminBlogType::where("user_id", Auth::id())->where("store_id", NULL)->orderBy('id', 'desc')->get();
        } elseif (Auth::check() && (Auth::user()->type == "admin" || Auth::user()->type == "staff")) {
            $userData = getUserData();
            $store_id = $userData["store_id"] ?? "";
            $blogTypes = AdminBlogType::where("store_id", $store_id)->get();
        }
        $data['blogTypes'] = $blogTypes ?? [];

        if (!is_null($id)) {
            $blogType = AdminBlogType::where('id', $id)->first();
            $data['blogType'] = $blogType;
        }

        return view('superadmin.blogs.type.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required',
        );

        $message = array(
            'name.required' => 'Title is required',
        );

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $msg = 'Blog Type updated successfully!';

            if (Auth::check() && Auth::user()->type == "superadmin") {
                $blogType = AdminBlogType::where('id', $request->id)->where("store_id", NULL)->first();
            } elseif (Auth::check() && Auth::user()->type == "superstaff") {
                $blogType = AdminBlogType::where("user_id", Auth::id())->where('id', $request->id)->orderBy('id', 'desc')->first();

            } elseif (Auth::check() && (Auth::user()->type == "admin" || Auth::user()->type == "staff")) {
                $userData = getUserData();
                $store_id = $userData["store_id"] ?? "";
                $blogType = AdminBlogType::where('id', $request->id)->where('store_id', $store_id)->first();
            }

            if (!isset($blogType)) {

                if (Auth::check() && Auth::user()->type == "superadmin") {
                    $blogType = AdminBlogType::where('type', $request->name)->where("store_id", NULL)->first();
                } elseif (Auth::check() && Auth::user()->type == "superstaff") {
                    $blogType = AdminBlogType::where("user_id", Auth::id())->where('type', $request->name)->orderBy('id', 'desc')->first();
                } elseif (Auth::check() && (Auth::user()->type == "admin" || Auth::user()->type == "staff")) {
                    $userData = getUserData();
                    $store_id = $userData["store_id"] ?? "";
                    $blogType = AdminBlogType::where('type', $request->name)->where('store_id', $store_id)->first();
                }

                if (!isset($blogType)) {
                    $blogType = new AdminBlogType();

                    $msg = 'Blog Type added successfully!';
                }


                $blogType->user_id = Auth::user()->id ?? null;
                $blogType->status = "1";

                if (Auth::user()->type == "admin" || Auth::user()->type == "staff") {
                    $userData = getUserData();
                    $store_id = $userData["store_id"] ?? "";
                    $blogType->store_id = $store_id;
                }

            }

            $blogType->type = $request->name;
            $blogType->save();

            Session::flash('success', $msg);
            return redirect()->route('superadmin.blog.type.index');
        }
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
            Session::flash('message', 'Please Select Blog Type');
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
                    $blogType = AdminBlogType::find($ids);
                    $blogType->status = 1;
                    $blogType->save();
                }
            }

            Session::flash('success', 'Successfully Active Blog Type');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $blogType = AdminBlogType::find($ids);
                    $blogType->status = 0;
                    $blogType->save();
                }
            }

            Session::flash('success', 'Successfully Deactive Blog Type');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $blogType = AdminBlogType::find($ids);
                    $blogType->delete();
                }
            }

            Session::flash('success', 'Successfully Deleted Blog Type');
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
        $blogType = AdminBlogType::find($id);
        if (isset($blogType) && $blogType->status == '1') {
            $blogType->status = '0';
        } else {
            $blogType->status = "1";
        }
        $blogType->save();
        $data = $blogType;

        return response()->json($data);
    }


    /**
     * Blog type delete
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBlogType(Request $request)
    {
        $id = $request->id ?? "";
        if (is_null($id) || empty($id)) {
            Session::flash("error", "Data Not Found");
            return redirect()->route('superadmin.blog.type.index');
        }
        $blogType = AdminBlogType::find($id);
        if (isset($blogType)) {
            $blogType->delete();

            Session::flash("success", "Data deleted successfully");
            return redirect()->route('superadmin.blog.type.index');
        }

        Session::flash("error", "Data Not Deleted!");
        return redirect()->route('superadmin.blog.type.index');
    }

}
