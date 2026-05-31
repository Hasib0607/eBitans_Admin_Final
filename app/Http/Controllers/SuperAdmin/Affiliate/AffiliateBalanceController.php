<?php

namespace App\Http\Controllers\SuperAdmin\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateBalance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateBalanceController extends Controller
{
    public function index()
    {
        $urls = "withdraw";
        // $data = AffiliateBalance::orderBy('id', 'DESC')->paginate(10);
        $data = DB::table('affiliate_balances')
            ->join('users', 'affiliate_balances.user_id', '=', 'users.id')
            ->select('affiliate_balances.*', 'users.name')
            ->where('affiliate_balances.withdraw_request_status', '=', '0')
            ->orderBy('affiliate_balances.id', 'DESC')
            ->paginate(10);

        return view('superadmin.affiliateWithdraw.pending')->with('urls', $urls)->with('data', $data);
    }

    public function approved()
    {
        $urls = "withdraw";
        // $data = AffiliateBalance::orderBy('id', 'DESC')->paginate(10);
        $data = DB::table('affiliate_balances')
            ->join('users', 'affiliate_balances.user_id', '=', 'users.id')
            ->select('affiliate_balances.*', 'users.name')
            ->where('affiliate_balances.withdraw_request_status', '=', '1')
            ->orderBy('affiliate_balances.id', 'DESC')
            ->paginate(10);

        return view('superadmin.affiliateWithdraw.approved')->with('urls', $urls)->with('data', $data);
    }

    public function rejected()
    {
        $urls = "withdraw";
        // $data = AffiliateBalance::orderBy('id', 'DESC')->paginate(10);
        $data = DB::table('affiliate_balances')
            ->join('users', 'affiliate_balances.user_id', '=', 'users.id')
            ->select('affiliate_balances.*', 'users.name')
            ->where('affiliate_balances.withdraw_request_status', '=', '-1')
            ->orderBy('affiliate_balances.id', 'DESC')
            ->paginate(10);

        return view('superadmin.affiliateWithdraw.rejected')->with('urls', $urls)->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function request(Request $request, AffiliateBalance $affiliateBalance)
    {

        $isPendindWithraw = AffiliateBalance::where('user_id', '=', Auth::user()->id)->where('withdraw_request_status', '==', 0)->first();

        if ($isPendindWithraw) {
            Session::flash('error', 'Withdraw is pending!');
            return back();
        }


        // dd($request->withdraw_request_amount);

        $balance = $request->totalEarning - $request->withdraw_request_amount;
        $lastbalance = AffiliateBalance::where('user_id', '=', Auth::user()->id)->where('withdraw_request_status', '=', 1)->latest()->first();

        if ($lastbalance) {
            $balance = $lastbalance->balance - $request->withdraw_request_amount;
            if (($request->withdraw_request_amount + 500) >= $lastbalance->balance || $request->withdraw_request_amount < 500) {
                Session::flash('error', 'Invalid withdraw amount!');
                return back();
            }
        } else {
            if (($request->withdraw_request_amount + 500) >= $request->totalEarning || $request->withdraw_request_amount < 500) {
                Session::flash('error', 'Invalid withdraw amount!');
                return back();
            }
        }

        $affiliateBalance = new AffiliateBalance;
        $affiliateBalance->user_id = Auth::user()->id;
        $affiliateBalance->total_earning = $request->totalEarning;
        $affiliateBalance->balance = $balance;
        $affiliateBalance->withdraw_request_amount = $request->withdraw_request_amount;
        $affiliateBalance->save();
        Session::flash('message', 'Withdraw request successfull!');
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approve_status($id)
    {
        $affiliateBalance = AffiliateBalance::find($id);
        $affiliateBalance->withdraw_request_status = 1;
        $affiliateBalance->save();
        Session::flash('message', 'Withdraw request approved successfully!');
        return back();
    }

    public function reject_status($id)
    {
        $affiliateBalance = AffiliateBalance::find($id);
        $affiliateBalance->withdraw_request_status = -1;
        $affiliateBalance->save();
        Session::flash('message', 'Withdraw request rejected successfully!');
        return back();
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
     * @param \App\Models\AffiliateBalance $affiliateBalance
     * @return \Illuminate\Http\Response
     */
    public function show(AffiliateBalance $affiliateBalance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AffiliateBalance $affiliateBalance
     * @return \Illuminate\Http\Response
     */
    public function edit(AffiliateBalance $affiliateBalance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AffiliateBalance $affiliateBalance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AffiliateBalance $affiliateBalance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AffiliateBalance $affiliateBalance
     * @return \Illuminate\Http\Response
     */
    public function destroy(AffiliateBalance $affiliateBalance)
    {
        //
    }
}
