<?php

namespace App\Http\Controllers\SuperAdmin\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateExamInfo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AffiliateExamInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function start()
    {

        $permission = null;
        $affiliateExamInfoExits = AffiliateExamInfo::where('user_id', '=', Auth::id())->first();

        if ($affiliateExamInfoExits && $affiliateExamInfoExits->answer_submited_at == null) {
            $permission = true;
        }

        if ($permission || !$affiliateExamInfoExits) {
            if (!$affiliateExamInfoExits) {
                $affiliateExamInfo = new AffiliateExamInfo;
                $affiliateExamInfo->user_id = Auth::id();
                $affiliateExamInfo->exam_started_at = Carbon::now();
                $affiliateExamInfo->save();
            }
            return redirect()->route('affiliate.exams');
        }

        if (!$permission) {
            return back()->with('error', 'Ypur are not allowed to give exam!');
        }

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
     * @param \App\Models\AffiliateExamInfo $affiliateExamInfo
     * @return \Illuminate\Http\Response
     */
    public function show(AffiliateExamInfo $affiliateExamInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AffiliateExamInfo $affiliateExamInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(AffiliateExamInfo $affiliateExamInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AffiliateExamInfo $affiliateExamInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AffiliateExamInfo $affiliateExamInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AffiliateExamInfo $affiliateExamInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(AffiliateExamInfo $affiliateExamInfo)
    {
        //
    }
}
