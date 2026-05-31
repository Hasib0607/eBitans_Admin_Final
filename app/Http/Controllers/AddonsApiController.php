<?php

namespace App\Http\Controllers;

use App\Models\AddonsApi;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AddonsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addonPack()
    {
        $data['urls'] = "addons";
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin' || $user_type == 'dropshipper') {
            $customer = Customer::where('uid', $user)->first();
            $data['store_id'] = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $data['store_id'] = $staff->store_id;
            $customer_id = $staff->customer_id;
        }

        $data['addons'] = AddonsApi::get();

        return view('admin.addon.addons_pack', $data);
    }

    public function addonPackPaginate(Request $request)
    {
        $length = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Starting record
        $search = $request->input('search')['value'] ?? '';

        // Query with search filter
        $query = AddonsApi::query()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'LIKE', "%$search%")
                    ->orWhere('heading', 'LIKE', "%$search%")
                    ->orWhere('type', 'LIKE', "%$search%");
            });

        $paginatedUsers = $query->paginate($length, ['*'], 'page', ($start / $length) + 1);

        // Format data for DataTables
        $data = $paginatedUsers->items();
        $data = array_map(function ($user) {
            return [
                'id' => $user->id,
                'title' => $user->title,
                'heading' => $user->heading,
                'price' => $user->price,
                'type' => $user->type,
                'rating' => $user->rating,
                'total_rating' => $user->total_rating,
            ];
        }, $data);
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $paginatedUsers->total(),
            'recordsFiltered' => $paginatedUsers->total(),
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addonPackStore(Request $request)
    {
        $addons = new AddonsApi();

        if ($request->thumbnail != null) {
            $imageName = Auth::user()->id . time() . '.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('addon_image/'), $imageName);
        }

        $addons->title = $request->title;
        $addons->heading = $request->heading;
        $addons->image = $imageName;
        $addons->price = $request->price;
        $addons->type = $request->type;
        $addons->rating = $request->rating;
        $addons->total_rating = $request->total_rating;
        $addons->review = $request->review;
        $addons->status = $request->status;

        $addons->save();
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addonPackUpdate(Request $request)
    {
        $addons = AddonsApi::find($request->id);

        if ($request->thumbnail != null) {
            if (File::exists(public_path('addon_image/' . $request->oldImage))) {
                File::delete(public_path('addon_image/' . $request->oldImage));
            }
            $imageName = Auth::user()->id . time() . '.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('addon_image/'), $imageName);
            $addons->image = $imageName;
        }

        $addons->title = $request->title;
        $addons->heading = $request->heading;
        $addons->price = $request->price;
        $addons->type = $request->type;
        $addons->rating = $request->rating;
        $addons->total_rating = $request->total_rating;
        $addons->review = $request->review;

        $addons->update();
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AddonsApi $addonsApi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AddonsApi $addonsApi)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addonPackDelete(Request $request)
    {
        $addons = AddonsApi::find($request->id);

        if ($request->id != null) {
            if (File::exists(public_path('addon_image/' . $addons->image))) {
                File::delete(public_path('addon_image/' . $addons->image));
            }
        }

        $addons->delete();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AddonsApi $addonsApi
     * @return \Illuminate\Http\Response
     */
    public function show(AddonsApi $addonsApi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AddonsApi $addonsApi
     * @return \Illuminate\Http\Response
     */
    public function edit(AddonsApi $addonsApi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AddonsApi $addonsApi
     * @return \Illuminate\Http\Response
     */
    public function destroy(AddonsApi $addonsApi)
    {
        //
    }
}
