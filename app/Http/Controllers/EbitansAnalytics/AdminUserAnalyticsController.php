<?php

namespace App\Http\Controllers\EbitansAnalytics;

use App\Http\Controllers\Controller;
use App\Models\AdminUserAnalytics;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminUserAnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
        $search = $request->search;
        $type = $request->type;
        $currentDate = Carbon::now();

        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        $data['type'] = $type;
        $data['search'] = $search;

        $clientQuery = AdminUserAnalytics::query();

        if (!empty($type)) {
            $clientQuery->where('user_type', $type);
        }

        // Search logic
        if (!is_null($search) && !empty($search)) {
            if (is_numeric($search)) {
                $clientQuery->where(function ($query) use ($search) {
                    $query->where('store_id', $search)
                        ->orWhere('user_id', $search);
                });
            } else {
                $clientQuery->where(function ($query) use ($search) {
                    $query->where('ip', 'like', "%$search%")
                        ->orWhere('mac', 'like', "%$search%")
                        ->orWhere('url', 'like', "%$search%")
                        ->orWhere('countryName', 'like', "%$search%")
                        ->orWhere('regionName', 'like', "%$search%")
                        ->orWhere('cityName', 'like', "%$search%")
                        ->orWhere('zipCode', 'like', "%$search%")
                        ->orWhere('postalCode', 'like', "%$search%")
                        ->orWhere('latitude', 'like', "%$search%")
                        ->orWhere('longitude', 'like', "%$search%")
                        ->orWhere('areaCode', 'like', "%$search%")
                        ->orWhere('timezone', 'like', "%$search%")
                        ->orWhere('device', 'like', "%$search%")
                        ->orWhere('platform', 'like', "%$search%")
                        ->orWhere('browser', 'like', "%$search%");
                });
            }
        }


        if ($from_date && !$to_date) {
            $clientQuery->where('created_at', '>=', $from_date->startOfDay());
        } elseif (!$from_date && $to_date) {
            $clientQuery->where('created_at', '<=', $to_date->endOfDay());
        } elseif ($from_date && $to_date) {
            $clientQuery->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
        } else {
            $clientQuery->where('created_at', '>=', $currentDate->startOfDay());
        }

        $storeData = $clientQuery->get();

        $data['totalStore'] = $storeData->groupBy("store_id")->count() ?? 0;
        $data['totalPage'] = $storeData->count() ?? 0;
        $data['totalPageView'] = $storeData->sum("number_of_visits") ?? 0;

        $data['analyticsInfo'] = AdminUserAnalytics::groupBy('store_id')->orderBy('id', 'DESC')->paginate(100);
        return view('superadmin.analytics.backend.index', $data);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdminUserAnalytics $adminUserAnalytics
     * @return \Illuminate\Http\Response
     */
    public function show(AdminUserAnalytics $adminUserAnalytics)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AdminUserAnalytics $adminUserAnalytics
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminUserAnalytics $adminUserAnalytics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AdminUserAnalytics $adminUserAnalytics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminUserAnalytics $adminUserAnalytics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdminUserAnalytics $adminUserAnalytics
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminUserAnalytics $adminUserAnalytics)
    {
        //
    }
}
