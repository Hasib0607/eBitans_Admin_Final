<?php

namespace App\Http\Controllers;

use App\Http\Middleware\User;
use App\Models\AdminVisitor;
use App\Models\cr;
use App\Models\Customer;
use App\Models\EbitansAnalytics\EbtAnalytics;
use App\Models\Staff;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    public function index()
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $store = $userData['store'];
        $store_url = $store->url ?? NULL;

        if (!ModulusStatus($store_id, 3)) {
            Session::flash('error', 'Please Active Analytics then try again...');
            return redirect()->route('admin.modulus');
        }

        $data['totalUsers'] = AdminVisitor::where('store_id', $store_id)->count();

        $currentWeekVisitors = AdminVisitor::where('store_id', $store_id)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $lastWeekVisitors = AdminVisitor::where('store_id', $store_id)
            ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
            ->count();

        // Calculate percentage change
        if ($lastWeekVisitors > 0) {
            $percentageRatio = (($currentWeekVisitors - $lastWeekVisitors) / $lastWeekVisitors) * 100;
        } else {
            $percentageRatio = $currentWeekVisitors > 0 ? 100 : 0;
        }

//        if ($lastWeekVisitors > 0) {
//            // Calculate what % this week is of last week (capped at 100%)
//            $percentageRatio = min(($currentWeekVisitors / $lastWeekVisitors) * 100, 100);
//        } else {
//            // If last week had 0 visitors, show 100% if current week has visitors
//            $percentageRatio = $currentWeekVisitors > 0 ? 100 : 0;
//        }

//        $percentageRatio = min(($lastWeekVisitors / $currentWeekVisitors) * 100, 100);

//        dd($currentWeekVisitors, $lastWeekVisitors, $percentageRatio);

        // Format the percentage with + or - sign
        $visitorChange = ($percentageRatio >= 0 ? '+' : '') . round($percentageRatio, 2) . '%';

        // Add data to response
        $data['currentWeekVisitors'] = $currentWeekVisitors;
        $data['visitorChange'] = $visitorChange;


        $normalizedUrl = rtrim('https://www.' . $store->url, '/');
        $data['totalEbi'] = AdminVisitor::where('store_id', $store_id)->whereRaw("TRIM(TRAILING '/' FROM page_url) LIKE ?", [$normalizedUrl])
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $data['totalMobile'] = AdminVisitor::where('store_id', $store_id)->where('device', 'Mobile')->count();
        $data['totalPc'] = AdminVisitor::where('store_id', $store_id)->where('device', 'Desktop')->count();

        $data['os_infos'] = DB::table('admin_visitors')
            ->select('os', DB::raw('count(*) as totalDevices'))
            ->groupBy('os')
            ->where('store_id', $store_id)->get();
        $data['browser_infos'] = DB::table('admin_visitors')
            ->select('browser', DB::raw('count(*) as totalBrowser'))
            ->groupBy('browser')
            ->where('store_id', $store_id)->get();
        $data['url_infos'] = DB::table('admin_visitors')
            ->select([
                'page_title',
                'page_url',
                'refer_page_url',
                DB::raw('COUNT(*) as total_page_views'),
                DB::raw('COUNT(DISTINCT CONCAT(COALESCE(NULLIF(ip, ""), "unknown"), DATE(created_at))) as visits_per_day'),
                DB::raw('AVG(
                    CASE
                        WHEN exit_time IS NOT NULL THEN TIMESTAMPDIFF(SECOND, visit_time, exit_time)
                        WHEN next_visit_time IS NOT NULL AND TIMESTAMPDIFF(MINUTE, visit_time, next_visit_time) <= 15
                            THEN TIMESTAMPDIFF(SECOND, visit_time, next_visit_time)
                        ELSE NULL
                    END
                ) AS avg_visit_time')
            ])
            ->fromSub(function ($query) use ($store_id, $store_url) {
                $query->from('admin_visitors')
                    ->select([
                        'page_title',
                        'page_url',
                        'refer_page_url',
                        'visit_time',
                        'exit_time',
                        'created_at',
                        'store_id',
                        'store_url',
                        DB::raw("COALESCE(NULLIF(ip, ''), 'unknown') AS ip"),
                        DB::raw('LEAD(visit_time) OVER (PARTITION BY COALESCE(NULLIF(ip, ""), "unknown") ORDER BY visit_time) AS next_visit_time')
                    ])
                    ->where(function ($query) use ($store_id, $store_url) {
                        $query->where('store_id', $store_id)
                            ->orWhere('store_url', $store_url);
                    });
            }, 'subquery')
            ->groupBy('page_url')
            ->orderByDesc('total_page_views')
            ->paginate(10);
        $data['country_infos'] = DB::table('admin_visitors')
            ->select('country_name', 'country_code', DB::raw('count(*) as totalCountry'))
            ->groupBy('country_name')->orderBy('totalCountry', 'DESC')
            ->where('store_id', $store_id)->get();
        $data['state_infos'] = DB::table('admin_visitors')
            ->select('state', 'country_name', DB::raw('count(*) as totalState'))
            ->groupBy('state')->orderBy('totalState', 'DESC')
            ->where('store_id', $store_id)->get();

        return view('admin.analytics.index', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function checkModulusAuth()
    {
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } else {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }

        if (ModulusStatus($store_id, 3)) {
            return $store_id;
        } else {
            Session::flash('error', 'Please Active Analytics then try again...');
            return 0;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allTraffic()
    {
        Session::flash('error', 'Access Denied This page');
        return back();


        $store_id = $this->checkModulusAuth();
        if (!$store_id) {
            return redirect()->route('admin.modulus');
        }

        $data['allTraffic'] = EbtAnalytics::where('store_id', $store_id)->get();

        return view('admin.analytics.all_traffic', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allStore()
    {
        $store_id = $store_id = $this->checkModulusAuth();
        if (!$store_id) {
            return redirect()->route('admin.modulus');
        }

        $data['allStore'] = Store::where('store_id', $store_id)->get();

        return view('admin.analytics.all_store', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeWise($id)
    {
        $store_id = $store_id = $this->checkModulusAuth();
        if (!$store_id) {
            return redirect()->route('admin.modulus');
        }

        $data['allTraffic'] = EbtAnalytics::where('store_id', $store_id)->get();

        return view('admin.analytics.all_traffic', $data);
    }

    /**
     * Display visitor data
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function allUrl(Request $request)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $store = $userData['store'];
        $store_url = $store->url ?? NULL;

        if (!ModulusStatus($store_id, 3)) {
            Session::flash('error', 'Please Active Analytics then try again...');
            return redirect()->route('admin.modulus');
        }

        $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
        $search = $request->search;

        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        $data['search'] = $search;


//        $reports = DB::table('admin_visitors')
//            ->select([
//                'page_title',
//                'page_url',
//                'refer_page_url',
//                DB::raw('COUNT(*) as total_page_views'),
//                DB::raw('AVG(visit_time) as avg_visit_time'),
//                DB::raw('COUNT(DISTINCT CONCAT(ip, DATE(created_at))) as visits_per_day')
//            ])
//            ->where(function ($query) use ($store_id, $store_url) {
//                $query->where('store_id', $store_id)
//                    ->orWhere('store_url', $store_url);
//            })
//            ->groupBy('page_url')
//            ->orderByDesc('total_page_views')
//            ->paginate(30);


        $reports = DB::table('admin_visitors')
            ->select([
                'page_title',
                'page_url',
                'refer_page_url',
                DB::raw('COUNT(*) as total_page_views'),
                DB::raw('COUNT(DISTINCT CONCAT(COALESCE(NULLIF(ip, ""), "unknown"), DATE(created_at))) as visits_per_day'),
                DB::raw('AVG(
                    CASE
                        WHEN exit_time IS NOT NULL THEN TIMESTAMPDIFF(SECOND, visit_time, exit_time)
                        WHEN next_visit_time IS NOT NULL AND TIMESTAMPDIFF(MINUTE, visit_time, next_visit_time) <= 15
                            THEN TIMESTAMPDIFF(SECOND, visit_time, next_visit_time)
                        ELSE NULL
                    END
                ) AS avg_visit_time')
            ])
            ->fromSub(function ($query) use ($store_id, $store_url, $from_date, $to_date, $search) {
                $query->from('admin_visitors')
                    ->select([
                        'page_title',
                        'page_url',
                        'refer_page_url',
                        'visit_time',
                        'exit_time',
                        'created_at',
                        'store_id',
                        'store_url',
                        DB::raw("COALESCE(NULLIF(ip, ''), 'unknown') AS ip"),
                        DB::raw('LEAD(visit_time) OVER (PARTITION BY COALESCE(NULLIF(ip, ""), "unknown") ORDER BY visit_time) AS next_visit_time')
                    ])
                    ->where(function ($query) use ($store_id, $store_url) {
                        $query->where('store_id', $store_id)
                            ->orWhere('store_url', $store_url);
                    });

                if ($from_date && !$to_date) {
                    $query->where('created_at', '>=', $from_date->startOfDay());
                } elseif (!$from_date && $to_date) {
                    $query->where('created_at', '<=', $to_date->endOfDay());
                } elseif ($from_date && $to_date) {
                    $query->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
                }

                if (!empty($search)) {
                    $query->where(function ($query) use ($search) {
                        $query->where('page_title', 'like', "%$search%")
                            ->orWhere('page_url', 'like', "%$search%")
                            ->orWhere('refer_page_url', 'like', "%$search%");
                    });
                }

            }, 'subquery')
            ->groupBy('page_url')
            ->orderByDesc('total_page_views')
            ->paginate(30);

        $data['reports'] = $reports;

        return view('admin.analytics.all_url', $data);
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
        $store_id = $store_id = $this->checkModulusAuth();
        if (!$store_id) {
            return redirect()->route('admin.modulus');
        }


        $info = new EbtAnalytics();

        $info->store_id = $request->store_id;
        $info->user_id = $request->user_id;
        $info->device = $request->device;
        $info->ip = $request->ip;
        $info->mac = $request->mac;
        $info->url = $request->url;
        $info->city = $request->city;
        $info->country_code = $request->country_code;
        $info->country_name = $request->country_name;
        $info->latitude = $request->latitude;
        $info->longitude = $request->longitude;
        $info->postal = $request->postal;
        $info->state = $request->state;
        $info->location = $request->location;
        $info->save();

        return response()->json($info);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\cr $cr
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\cr $cr
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\cr $cr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\cr $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
