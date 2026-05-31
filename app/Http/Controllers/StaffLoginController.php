<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Store;
use App\Models\SuperstaffSalesCommissionBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Superstaff;
use Illuminate\Support\Facades\Validator;

class StaffLoginController extends Controller
{
    public function stafflogin()
    {
        return view('admin.staff.login');
    }

    public function dashboard(Request $request)
    {
        $perPage = 20;
        $isDateFilter = false;
        $isExpireFilter = false;
        if (isset($request->days) && !empty($request->days)) {
            $isDateFilter = true;
            $days = (int)$request->days + 1 ?? 8;

            // Calculate the start and end dates
            $start_date = Carbon::now()->format('Y-m-d'); // Current date
            $end_date = Carbon::now()->addDays($days)->format('Y-m-d'); // Current date
        } else if (isset($request->expire) && !empty($request->expire) && $request->expire == 1) {
            $isExpireFilter = true;
        }

        $id = Auth::user()->id ?? "";
        $staff = Superstaff::where('uid', $id)->first();
        $staff_id = $staff->id ?? "";

        $storeQuery = Store::leftJoin('superstaff_sales_commissions', 'superstaff_sales_commissions.user_id', '=', 'stores.user_id')->where('superstaff_sales_commissions.staff_id', $staff_id);

        if ($isDateFilter) {
            $storeQuery->whereBetween('stores.expiry_date', [$start_date, $end_date]);
        }
        if ($isExpireFilter) {
            $date = Carbon::now()->format('Y-m-d');
            $storeQuery->where('stores.expiry_date', "<", $date);
        }

        $storeQuery->whereNotIn('stores.plan_id', [6, 9])
            ->where('stores.call_status', '<', "5")
            ->whereNull("stores.upcoming_plan_id");

        if ($isExpireFilter) {
            $storeQuery->orderBy("stores.expiry_date", "DESC");
        } else {
            $storeQuery->orderBy("stores.expiry_date", "ASC");
        }

        $exstore = $storeQuery->select(
            "stores.*",
            "superstaff_sales_commissions.staff_id",
            "superstaff_sales_commissions.new_commission",
            "superstaff_sales_commissions.renew_commission",
            "superstaff_sales_commissions.setup_commission",
            "superstaff_sales_commissions.setup_amount"
        )
            ->paginate($perPage);

        return view('superadmin.staff.dashboard', [
            'exstore' => $exstore,
            'expire' => $request->expire ?? "",
            'filterDays' => $request->days ?? ""
        ]);
    }

    public function staffloginsubmit(Request $request)
    {
        $rules = array(
            'username' => 'required',
            'password' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Session::flash('error', 'Credential not match');
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $pass = Hash::make($request->password);
            $staff = User::where('username', $request->username)->first();
            if (isset($staff)) {
                if (Hash::check($request->password, $staff->password)) {
                    $user = User::find($staff->id);
                    if (isset($user)) {
                        Auth::login($user);
                        return redirect('/');
                    } else {
                        Session::flash('error', 'Username OR Password not match');
                        return back();
                    }
                } else {
                    $superstaff = Superstaff::where('username', $request->username)->where("status", "active")->first();
                    if (isset($superstaff)) {
                        if (Hash::check($request->password, $superstaff->password)) {
                            $users = User::find($superstaff->uid);
                            if (isset($users)) {
                                Auth::login($users);
                                return redirect()->route('staff.dashboard');
                            } else {
                                Session::flash('error', 'Username OR Password not match');
                                return back();
                            }
                        } else {
                            Session::flash('error', 'Username OR Password not match');
                            return back();
                        }
                    } else {
                        Session::flash('error', 'Username OR Password not match');
                        return back();
                    }

                }
            } else {
                $superstaff = Superstaff::where('username', $request->username)->where("status", "active")->first();
                if (isset($superstaff)) {
                    // Get whitelisted IPs from .env (split into an array)
                    $whitelistedIPs = explode(',', env('SUPERSTAFF_WHITELISTED_IPS', ''));

                    // Trim whitespace from each IP (in case of formatting issues)
                    $whitelistedIPs = array_map('trim', $whitelistedIPs);

                    // Get the user's IP (handles proxies automatically)
                    $userIp = $request->ip();

                    // Check if IP is allowed
                    if (!in_array($userIp, $whitelistedIPs)) {
                        Session::flash("error", "Your IP ($userIp) is not allowed.");
                        return back();
                    }

                    if (Hash::check($request->password, $superstaff->password)) {
                        $users = User::find($superstaff->uid);
                        if (isset($users)) {
                            Auth::login($users);
                            return redirect()->route('staff.dashboard');
                        } else {
                            Session::flash('error', 'Username OR Password not match');
                            return back();
                        }
                    } else {
                        Session::flash('error', 'Username OR Password not match');
                        return back();
                    }
                } else {
                    Session::flash('error', 'Credential not match');
                    return back();
                }
            }
        }
    }


    public function superstaffCommission(Request $request)
    {
        if (Auth::user()->type == "superstaff") {
            $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
            $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
            $search = $request->search;

            $id = Auth::user()->id ?? "";
            $staff = Superstaff::where('uid', $id)->first();
            $staff_id = $staff->id ?? "";

            $query = SuperstaffSalesCommissionBalance::with("store", "user")->where("staff_id", $staff_id)->whereNotNull("user_id");

            if ($from_date && !$to_date) {
                $query->where('created_at', '>=', $from_date->startOfDay());
            } elseif (!$from_date && $to_date) {
                $query->where('created_at', '<=', $to_date->endOfDay());
            } elseif ($from_date && $to_date) {
                $query->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
            }

            // Search logic
            if (!empty($search)) {
                $query->where(function ($query) use ($search) {
                    $query->where('user_id', $search)
                        ->orWhereHas('user', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', "%$search%")
                                ->orWhere('phone', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        })->orWhereHas('store', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', "%$search%")
                                ->orWhere('url', 'like', "%$search%");
                        });
                });
            }

            $commissionQuery = $query->orderBy('id', 'DESC');

            $allCommission = $commissionQuery->get();
            $commission = $commissionQuery->paginate(20);

            // Calculate totals
            $totalAmount = $allCommission->sum('total_amount');
            $totalCommission = $allCommission->sum('commission_amount');

            $balance = SuperstaffSalesCommissionBalance::getSellerCommissionBalance($staff_id);

            return view('superadmin.staff.commission', [
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'search' => $search,
                'commission' => $commission,
                'totalAmount' => $totalAmount,
                'totalCommission' => $totalCommission,
                "balance" => $balance
            ]);
        } else {
            return redirect()->route("staff.dashboard")->with("error", "Unauthorized access");
        }
    }

    public function superstaffCommissionPaymentHistory(Request $request)
    {
        if (Auth::user()->type == "superstaff") {
            $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
            $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;

            $id = Auth::user()->id ?? "";
            $staff = Superstaff::where('uid', $id)->first();
            $staff_id = $staff->id ?? "";

            $query = SuperstaffSalesCommissionBalance::with("store", "user")->where("staff_id", $staff_id)->whereNull("user_id");

            if ($from_date && !$to_date) {
                $query->where('created_at', '>=', $from_date->startOfDay());
            } elseif (!$from_date && $to_date) {
                $query->where('created_at', '<=', $to_date->endOfDay());
            } elseif ($from_date && $to_date) {
                $query->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
            }

            $commission = $query->orderBy("id", "DESC")->paginate(20);

            return view('superadmin.staff.payment-history', [
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'commission' => $commission,
            ]);
        } else {
            return redirect()->route("staff.dashboard")->with("error", "Unauthorized access");
        }
    }

    public function accessAdminAccount($id)
    {
        if (Auth::user()->type == "superstaff") {
            if (is_null($id) || empty($id)) {
                return redirect()->back()->with("error", "Invalid link!");
            }

            $store = Store::where("user_id", $id)->get();
            if (isset($store) && $store->count() > 0) {
                return redirect()->back()->with("error", "This account already have store!");
            }

            $storeUser = User::where("id", $id)->first();
            if ($storeUser) {
                Auth::logout();

                // Log in the new user
                Auth::login($storeUser);

                // Redirect to the dashboard or any other page
                return redirect()->route('admin.index')->with('success', 'Successfully switched account.');
            }
        } else {
            return redirect()->back()->with("error", "Unauthorized access");
        }
    }


    public function accessAdminStore($id)
    {
        if (Auth::user()->type == "superstaff") {
            if (is_null($id) || empty($id)) {
                return redirect()->back()->with("error", "Invalid link!");
            }

            $customer = Customer::where('uid', $id)->first();
            $store_id = $customer->active_store ?? "";
            $store = Store::where("id", $store_id)->first();

            if (!isset($store)) {
                return redirect()->back()->with("error", "There is no store in this user!");
            }

            $staff = Auth::user();
            $staff->store_id = $store_id;
            $staff->save();

            // Redirect to the dashboard or any other page
            return redirect()->route('staff.dashboard')->with('success', 'Successfully switched account.');
        } else {
            return redirect()->back()->with("error", "Unauthorized access");
        }
    }

}
