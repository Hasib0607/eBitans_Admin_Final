<?php

namespace App\Http\Controllers\EbitansAnalytics;

use App\Http\Controllers\Controller;
use App\Http\Middleware\User;
use App\Models\cr;
use App\Models\EbitansAnalytics\EbtAnalytics;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['totalUsers'] = EbtAnalytics::count();
        $data['totalEbi'] = EbtAnalytics::where('url', 'like', 'https://ebitans.com%')->count();

        $data['totalMobile'] = EbtAnalytics::where('device', 'Mobile')->count();
        $data['totalPc'] = EbtAnalytics::where('device', 'Desktop')->count();

        // $devices = EbtAnalytics::gur
        $data['os_infos'] = DB::table('ebt_analytics')
            ->select('os', DB::raw('count(*) as totalDevices'))
            ->groupBy('os')
            ->get();
        $data['browser_infos'] = DB::table('ebt_analytics')
            ->select('browser', DB::raw('count(*) as totalBrowser'))
            ->groupBy('browser')
            ->get();
        $data['url_infos'] = DB::table('ebt_analytics')
            ->select('url', 'isTime', 'page_title', DB::raw('count(*) as visitors'), DB::raw('count("isTime") as isTime'))
            ->groupBy('url')->orderBy('visitors', 'DESC')
            ->get();
        $data['country_infos'] = DB::table('ebt_analytics')
            ->select('country_name', 'country_code', DB::raw('count(*) as totalCountry'))
            ->groupBy('country_name')->orderBy('totalCountry', 'DESC')
            ->get();
        $data['state_infos'] = DB::table('ebt_analytics')
            ->select('state', 'country_name', DB::raw('count(*) as totalState'))
            ->groupBy('state')->orderBy('totalState', 'DESC')
            ->get();


        return view('superadmin.analytics.index', $data);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allTraffic()
    {
        $data['allTraffic'] = EbtAnalytics::paginate(50);

        return view('superadmin.analytics.all_traffic', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allStore()
    {
        $data['allStore'] = Store::orderBy("id", "DESC")->paginate(50);

        return view('superadmin.analytics.all_store', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeWise($id)
    {
        $data['allTraffic'] = EbtAnalytics::where('store_id', $id)->orderBy("id", "DESC")->paginate(50);

        return view('superadmin.analytics.all_traffic', $data);
    }


    public function websiteVisitor(Request $request)
    {
        $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
        $search = $request->search;
        $website = $request->website;

        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        $data['search'] = $search;
        $data['website'] = $website;

        if (!isset($website) || empty($website) || is_null($website)) {
            $website = "%ebitans.com%";
        }

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
            ->fromSub(function ($query) use ($website, $from_date, $to_date, $search) {
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
                    ->where(function ($query) use ($website) {
                        $query->whereNull('store_id')
                            ->where('store_url', 'like', $website);
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

        return view('superadmin.analytics.websiteVisitor', $data);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allUrl()
    {
        //
        $data['allTraffic'] = EbtAnalytics::get();
        $data['url_infos'] = DB::table('ebt_analytics')
            ->select('url', 'isTime', 'page_title', DB::raw('count(*) as visitors'), DB::raw('count(*) as isTime'))
            ->groupBy('url')->orderBy('visitors', 'DESC')
            ->get();

        return view('superadmin.analytics.all_url', $data);
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
