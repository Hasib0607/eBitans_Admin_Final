<?php

namespace App\Http\Controllers\SuperAdmin\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateBalance;
use App\Models\AffiliateFAQ;
use App\Models\AffiliatePayment;
use App\Models\AffiliateInfo;
use App\Models\AffiliateQuestion;
use App\Models\AffiliateWithdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AffiliatePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }
        // $data = AffiliatePayment::latest()->get();
        $data = AffiliatePayment::latest()
            ->join('users', 'affiliate_payments.user_id', '=', 'users.id')
            ->select('affiliate_payments.*', 'users.name as name')
            ->latest()
            ->get();

        $affiliateInfo = AffiliateInfo::first();
        $charge = $affiliateInfo ? $affiliateInfo->affiliate_charge : 200;
        $charge_usd = $affiliateInfo ? $affiliateInfo->affiliate_charge_usd : 2;

        $urls = 'affiliatepaymentlists';
        $uid = '';
        return view('superadmin.affiliatePayment.index', compact('data', 'charge', 'charge_usd', 'uid'))->with('urls', $urls);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approved()
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }
        // $data = AffiliatePayment::latest()->get();
        $data = AffiliatePayment::latest()
            ->join('users', 'affiliate_payments.user_id', '=', 'users.id')
            ->select('affiliate_payments.*', 'users.name as name, users.id as user_id', 'users.referral_commission')
            ->where('affiliate_payments.status', '=', 'Completed')
            ->latest()
            ->get();

        $affiliateInfo = AffiliateInfo::first();
        $charge = $affiliateInfo ? $affiliateInfo->affiliate_charge : 200;

        $urls = 'affiliatepaymentlists';
        $uid = '';
        return view('superadmin.affiliatePayment.approved', compact('data', 'charge', 'uid'))->with('urls', $urls);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function rejected(Request $request)
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }
        // $data = AffiliatePayment::latest()->get();
        $data = AffiliatePayment::latest()
            ->join('users', 'affiliate_payments.user_id', '=', 'users.id')
            ->select('affiliate_payments.*', 'users.name as name')
            ->where('affiliate_payments.status', '!=', 'Completed')
            ->latest()
            ->get();

        $affiliateInfo = AffiliateInfo::first();
        $charge = $affiliateInfo ? $affiliateInfo->affiliate_charge : 200;

        $urls = 'affiliatepaymentlists';
        $uid = '';
        return view('superadmin.affiliatePayment.rejected', compact('data', 'charge', 'uid'))->with('urls', $urls);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AffiliatePayment $affiliatePayment
     * @return \Illuminate\Http\Response
     */
    public function show(AffiliatePayment $affiliatePayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AffiliatePayment $affiliatePayment
     * @return \Illuminate\Http\Response
     */
    public function edit(AffiliatePayment $affiliatePayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AffiliatePayment $affiliatePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(AffiliatePayment $affiliatePayment)
    {
        //
    }

    /**
     *
     * Withdraw pending page display
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function withdraw()
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }

        $data = DB::table('affiliate_balances')
            ->leftJoin('users', 'affiliate_balances.user_id', '=', 'users.id')
            ->select('affiliate_balances.*', 'users.name as name')
            ->where('affiliate_balances.withdraw_request_amount', ">", "0")
            ->where('affiliate_balances.withdraw_request_status', "0")
            ->orderBy("affiliate_balances.id", "DESC")
            ->get();

        return view('superadmin.affiliateWithdraw.pending', ["data" => $data]);
    }

    /**
     *
     * Withdraw approved page display
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function withdrawApproved()
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }

        $data = DB::table('affiliate_withdraw_transactions')
            ->leftJoin('users', 'affiliate_withdraw_transactions.user_id', '=', 'users.id')
            ->select('affiliate_withdraw_transactions.*', 'users.name as name')
            ->where('affiliate_withdraw_transactions.status', "1")
            ->orderBy("affiliate_withdraw_transactions.id", "DESC")
            ->get();

        return view('superadmin.affiliateWithdraw.approved', ["data" => $data]);
    }

    /**
     *
     * Withdraw rejected page display
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function withdrawRejected()
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }

        $data = DB::table('affiliate_withdraw_transactions')
            ->leftJoin('users', 'affiliate_withdraw_transactions.user_id', '=', 'users.id')
            ->select('affiliate_withdraw_transactions.*', 'users.name as name')
            ->where('affiliate_withdraw_transactions.status', "2")
            ->orderBy("affiliate_withdraw_transactions.id", "DESC")
            ->get();

        return view('superadmin.affiliateWithdraw.rejected', ["data" => $data]);
    }

    public function affiliatePaymentCharge(Request $request)
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }

        if ($request->affiliate_charge == "" || $request->affiliate_charge == 0) {
            Session::flash("error", "Charge must be greater than 0");
            return redirect()->back()->withInput();
        } elseif ($request->affiliate_charge_usd == "" || $request->affiliate_charge_usd == 0) {
            Session::flash("error", "Charge must be greater than 0");
            return redirect()->back()->withInput();
        } else {
            $affiliateInfo = AffiliateInfo::latest()->first();
            if (!isset($affiliateInfo)) {
                $affiliateInfo = new AffiliateInfo();
            }
            $affiliateInfo->affiliate_charge = $request->affiliate_charge;
            $affiliateInfo->affiliate_charge_usd = $request->affiliate_charge_usd;
            $affiliateInfo->save();
            return redirect()->back()->with("success", "Charge updated successfully");
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AffiliatePayment $affiliatePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AffiliatePayment $affiliatePayment)
    {
        //
    }

    /**
     *
     * Withdraw status change
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function withdrawStatusApproved($id)
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }

        if (isset($id)) {
            //Withdraw code here
            $affiliateBalance = AffiliateBalance::where('id', $id)->first();

            $prev_balance = (int)$affiliateBalance->balance;
            $new_balance = (int)$affiliateBalance->balance - (int)$affiliateBalance->withdraw_request_amount;

            // Affiliate withdraw transaction
            $affiliateWithDraw = new AffiliateWithdraw();
            $affiliateWithDraw->user_id = $affiliateBalance->user_id;
            $affiliateWithDraw->prev_balance = $prev_balance;
            $affiliateWithDraw->withdraw_amount = $affiliateBalance->withdraw_request_amount;
            $affiliateWithDraw->status = "1";
            $affiliateWithDraw->save();

            // Affiliate balance
            $affiliateBalance->balance = $new_balance;
            $affiliateBalance->withdraw_request_amount = "0";
            $affiliateBalance->withdraw_request_status = "0";
            $affiliateBalance->save();

            Session::flash('success', "Status updated successfully");
            return redirect()->back();
        } else {
            Session::flash('error', "Data not found. Try again");
            return redirect()->back();
        }
    }

    /**
     *
     * Withdraw status change
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function withdrawStatusRejected($id)
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }

        if (isset($id)) {
            $affiliateBalance = AffiliateBalance::where('id', $id)->first();

            $prev_balance = (int)$affiliateBalance->balance;

            // Affiliate withdraw transaction
            $affiliateWithDraw = new AffiliateWithdraw();
            $affiliateWithDraw->user_id = $affiliateBalance->user_id;
            $affiliateWithDraw->prev_balance = $prev_balance;
            $affiliateWithDraw->withdraw_amount = $affiliateBalance->withdraw_request_amount;
            $affiliateWithDraw->status = "2";
            $affiliateWithDraw->save();

            // Affiliate balance
            $affiliateBalance->balance = $prev_balance;
            $affiliateBalance->withdraw_request_amount = "0";
            $affiliateBalance->withdraw_request_status = "0";
            $affiliateBalance->save();

            Session::flash('success', "Status updated successfully");
            return redirect()->back();
        } else {
            Session::flash('error', "Data not found. Try again");
            return redirect()->back();
        }
    }

    /**
     *
     * Withdraw status change
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function withdrawStatusPending($id)
    {
        if (Auth::user()->type != 'superadmin') {
            return redirect("/");
        }

        if (isset($id)) {
            $affiliateBalance = AffiliateBalance::where('id', $id)->first();
            $affiliateBalance->withdraw_request_status = "0";
            $affiliateBalance->save();

            Session::flash('success', "Status updated successfully");
            return redirect()->back();
        } else {
            Session::flash('error', "Data not found. Try again");
            return redirect()->back();
        }
    }


}
