<?php

namespace App\Http\Controllers\SuperAdmin\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateInfo;
use App\Models\User;
use Session;
use Illuminate\Http\Request;

class AffiliateInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->affiliate_charge <= 0) {
            Session::flash('error', 'Affiliate Charge is invalid!');
            return back();
        }

        $affiliateInfo = AffiliateInfo::first();

        $affiliateInfo ? $affiliateInfo = $affiliateInfo : $affiliateInfo = new AffiliateInfo;

        if ($affiliateInfo) {
            $affiliateInfo->affiliate_charge = $request->affiliate_charge;
            $affiliateInfo->save();
        }
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function commission_rate(Request $request)
    {

        $user = User::where('id', '=', $request->id)->first();
        if ($user) {
            $user->referral_commission = $request->referral_commission;
            $user->update();
            Session::flash('message', 'Commission rate updated successfully!');
            return back();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AffiliateInfo $affiliateInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AffiliateInfo $affiliateInfo)
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
     * @param \App\Models\AffiliateInfo $affiliateInfo
     * @return \Illuminate\Http\Response
     */
    public function show(AffiliateInfo $affiliateInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AffiliateInfo $affiliateInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(AffiliateInfo $affiliateInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AffiliateInfo $affiliateInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(AffiliateInfo $affiliateInfo)
    {
        //
    }
}
