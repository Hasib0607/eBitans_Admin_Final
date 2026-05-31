<?php

namespace App\Http\Controllers\SuperAdmin\Dropship;


use App\Http\Controllers\Controller;
use App\Http\Controllers\SuperAdmin\Affiliate\Staff;
use App\Models\AccountJournal;
use App\Models\AffiliateFAQ;
use App\Models\AffiliateQuestionAnswer;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Customer;
use App\Models\Toptool;
use App\Models\Store;
use App\Models\AffiliateQuestion;
use Carbon\Carbon;
use DB;
use App\Models\AffiliateExamInfo;
use App\Models\AffiliateInfo;
use App\Models\AffiliatePayment;
use App\Models\AffiliateBalance;
use Illuminate\Support\Facades\Auth;
use App\Models\Referral;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DropshipperController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     *
     * Display all dropshipper
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if (Auth::user()->type == 'superadmin') {
            $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
            $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
            $search = $request->search;

            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
            $data['search'] = $search;

            $clientQuery = Store::with('accountJournals')
                ->whereHas('user', function ($query) {
                    $query->where('type', 'dropshipper');
                });

            if ($from_date && !$to_date) {
                $clientQuery->where('created_at', '>=', $from_date->startOfDay());
            } elseif (!$from_date && $to_date) {
                $clientQuery->where('created_at', '<=', $to_date->endOfDay());
            } elseif ($from_date && $to_date) {
                $clientQuery->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
            }

            if (!empty($search)) {
                $clientQuery->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('url', 'like', "%$search%")
                        ->orWhere('type', 'like', "%$search%");
                });
            }

            $data['stores'] = $clientQuery->paginate(30);

            return view('superadmin.dropship.index', $data);
        } else {
            return redirect()->back();
        }

    }


    /**
     *
     * Display all overflow dropshipper
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function overFlowList(Request $request)
    {
        if (Auth::user()->type == 'superadmin') {
            $search = $request->search;
            $data['search'] = $search;

            $overflowAmount = 4000;

            $storeBalances = \DB::table('account_journals')
                ->select('store_id', \DB::raw('ABS(SUM(dr) - SUM(cr)) as balance'))
                ->groupBy('store_id')
                ->havingRaw('ABS(SUM(dr) - SUM(cr)) >= ?', [$overflowAmount])
                ->pluck('balance', 'store_id')
                ->toArray();  // Convert the collection to an array

//            $stores = Store::with(['accountJournals', 'user'])
//                ->whereIn('id', array_keys($storeBalances))  // Use array_keys on the array
//                ->whereHas('user', function ($query) {
//                    $query->where('type', 'dropshipper');
//                })
//                ->get();

            // Step 2: Fetch stores matching store IDs with search and user filter
            $storeQuery = Store::with(['user'])
                ->whereIn('id', array_keys($storeBalances))
                ->whereHas('user', function ($query) {
                    $query->where('type', 'dropshipper');
                });

            // Apply search filter
            if (!empty($search)) {
                $storeQuery->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('url', 'like', "%$search%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            }

            $stores = $storeQuery->paginate(30)->appends([
                'search' => $request->search,
            ]);

            // Add balance to each store
            $stores->getCollection()->each(function ($store) use ($storeBalances) {
                $store->balance = $storeBalances[$store->id] ?? 0;
            });

            $data['stores'] = $stores;

            return view('superadmin.dropship.overFlowList', $data);
        } else {
            return redirect()->back();
        }

    }


    /**
     * Change dropship commission rate
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function commissionUpdate(Request $request)
    {
        if (Auth::user()->type == 'superadmin') {
            if (isset($request->id) && !empty($request->id) && isset($request->commission) && $request->commission != "") {
                $store = Store::where('id', '=', $request->id)->first();

                if ($store) {
                    $store->dropship_commission = $request->commission;
                    $store->save();

                    return response()->json(['status' => true, "message" => "Successfully updated dropship commission percentage.!"]);
                }

                return response()->json(['status' => false, "message" => "User not found!"]);
            }

            return response()->json(['status' => false, "message" => "Data missing!"]);
        }

        return response()->json(['status' => false, "message" => "Unauthorized!"]);
    }


    /**
     * Change dropship commission rate
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderPullUpdate(Request $request)
    {
        if (Auth::user()->type == 'superadmin') {
            if (isset($request->id) && !empty($request->id) && isset($request->order_pull) && $request->order_pull != "") {
                $store = Store::where('id', '=', $request->id)->first();

                if ($store) {
                    $store->order_pull = $request->order_pull;
                    $store->save();

                    return response()->json(['status' => true, "message" => "Successfully updated order pull stage.!"]);
                }

                return response()->json(['status' => false, "message" => "User not found!"]);
            }

            return response()->json(['status' => false, "message" => "Data missing!"]);
        }

        return response()->json(['status' => false, "message" => "Unauthorized!"]);
    }


    /**
     * Change dropship overflow commission
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function overflowCommissionUpdate(Request $request)
    {
        if (Auth::user()->type == 'superadmin') {
            if (isset($request->id) && !empty($request->id) && isset($request->overflow_commission) && $request->overflow_commission != "") {
                $store = Store::where('id', '=', $request->id)->first();

                if ($store) {
                    $store->overflow_commission = $request->overflow_commission;
                    $store->save();

                    return response()->json(['status' => true, "message" => "Successfully updated overflow commission!"]);
                }

                return response()->json(['status' => false, "message" => "User not found!"]);
            }

            return response()->json(['status' => false, "message" => "Data missing!"]);
        }

        return response()->json(['status' => false, "message" => "Unauthorized!"]);
    }

    /**
     *
     * Show dropshipper order details in superadmin
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function storeOrderDetails(Request $request, $id)
    {
        $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
        $search = $request->search;

        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        $data['search'] = $search;
        $data['store_id'] = $id;

        $store = Store::where('id', $id)->first();
        $data['store'] = $store;
        $clientQuery = AccountJournal::with("order", "currency")->where('store_id', $store->id);

        if ($from_date && !$to_date) {
            $clientQuery->where('created_at', '>=', $from_date->startOfDay());
        } elseif (!$from_date && $to_date) {
            $clientQuery->where('created_at', '<=', $to_date->endOfDay());
        } elseif ($from_date && $to_date) {
            $clientQuery->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
        }

        if (!empty($search)) {
            $clientQuery->where(function ($query) use ($search) {
                $query->where('product_order_amount', 'like', "%$search%")
                    ->orWhere('commission_percent', 'like', "%$search%")
                    ->orWhere('payment_amount', 'like', "%$search%")
                    ->orWhere('payment_method', 'like', "%$search%")
                    ->orWhere('payment_number', 'like', "%$search%")
                    ->orWhere('transaction_id', 'like', "%$search%")
                    ->orWhereHas('order', function ($subQuery) use ($search) {
                        $subQuery->where('reference_no', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            });
        }

        $data['storeOrders'] = $clientQuery->orderBy("id", "DESC")->paginate(30);

        return view('superadmin.dropship.storeOrder', $data);
    }


    /**
     * Display dropship order table
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function dropshipCommission(Request $request)
    {
        if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $userData = getUserData();
            $store_id = $userData['store_id'] ?? "";

            $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
            $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
            $search = $request->search;

            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
            $data['search'] = $search;

            // Must be BDT currency id is 1
            $currencyBDTRate = Currency::where('id', 1)->value('rate') ?? "";

            // Get store currency ID
            $storeCurrency_id = Store::where('id', $store_id)->value('currency') ?? "";

            // Get store currency details
            $data['storeCurrency'] = Currency::where('id', $storeCurrency_id)->first();

            if (isset($store_id) && !empty($store_id) && $currencyBDTRate != "" && $storeCurrency_id != "") {
                $clientQuery = AccountJournal::with('order', 'currency')
                    ->where('store_id', $store_id)
                    ->join('currencies', 'account_journals.currency_id', '=', 'currencies.id')
                    ->selectRaw(
                        "account_journals.*,
                        ROUND(account_journals.dr / ? * currencies.rate, 2) as converted_dr,
                        ROUND(account_journals.cr / ? * currencies.rate, 2) as converted_cr,
                        ROUND(account_journals.balance / ? * currencies.rate, 2) as converted_balance",
                        [$currencyBDTRate, $currencyBDTRate, $currencyBDTRate]
                    );

                if ($from_date && !$to_date) {
                    $clientQuery->where('account_journals.created_at', '>=', $from_date->startOfDay());
                } elseif (!$from_date && $to_date) {
                    $clientQuery->where('account_journals.created_at', '<=', $to_date->endOfDay());
                } elseif ($from_date && $to_date) {
                    $clientQuery->whereBetween('account_journals.created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
                }

                $clientQuery->where(function ($query) use ($search) {
                    $query->where('account_journals.product_order_amount', 'like', "%$search%")
                        ->orWhere('account_journals.commission_percent', 'like', "%$search%")
                        ->orWhere('account_journals.payment_amount', 'like', "%$search%")
                        ->orWhere('account_journals.payment_method', 'like', "%$search%")
                        ->orWhere('account_journals.payment_number', 'like', "%$search%")
                        ->orWhere('account_journals.transaction_id', 'like', "%$search%")
                        ->orWhereHas('order', function ($subQuery) use ($search) {
                            $subQuery->where('orders.reference_no', 'like', "%$search%")
                                ->orWhere('orders.phone', 'like', "%$search%")
                                ->orWhere('orders.email', 'like', "%$search%");
                        });
                });


                $data['storeOrders'] = $clientQuery->orderBy("account_journals.id", "DESC")->paginate(30);

                $totalBalance = AccountJournal::getAccountBalance($store_id);
                $totalBalance = AccountJournal::convertCurrency($totalBalance, 1, $storeCurrency_id);
                $totalBalance = $totalBalance >= 0 ? number_format($totalBalance, 2) : 0;
                $data['totalBalance'] = $totalBalance;

                return view('admin.dropship.index', $data);
            }

            return back()->with("error", "Something went wrong.");
        }

        return back()->with("error", "You are not authorized.");

    }


    public function dropshipCommissionPay(Request $request)
    {
        if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            if (!isset($request->amount) || empty($request->amount) || $request->amount <= 0.01) {
                Session::flash("error", "Amount must be greater than 0.01");
            }
            if (!isset($request->payment_method) || empty($request->payment_method)) {
                Session::flash("error", "Please select payment method");
            }

            $userData = getUserData();
            $user_id = $userData['user_id'] ?? "";
            $store_id = $userData['store_id'] ?? "";
            $amount = $request->amount ?? 0;

            if (!empty($user_id) && !empty($store_id)) {
                // Get store currency ID
                $storeCurrency_id = Store::where('id', $store_id)->value('currency') ?? "";

                if ($storeCurrency_id == 1) {
                    if (isset($request->payment_method) && $request->payment_method == "bkash") {
                        $GetTotalBalance = (float)abs(AccountJournal::getAccountBalance($store_id));

                        if ($amount > $GetTotalBalance) {
                            Session::flash('error', "You can not pay extra amount.");
                            return back();
                        }

                        $url = route("bkash.dropshipper.payment", ['user_id' => $user_id, 'store_id' => $store_id, 'amount' => $amount]);
                        return redirect()->away($url);
                    }
                    if (isset($request->payment_method) && $request->payment_method == "amarpay") {
                        $GetTotalBalance = (float)abs(AccountJournal::getAccountBalance($store_id));

                        if ($amount > $GetTotalBalance) {
                            Session::flash('error', "You can not pay extra amount.");
                            return back();
                        }

                        $url = route("amarpay.dropshipper.payment", ['user_id' => $user_id, 'store_id' => $store_id, 'amount' => $amount]);
                        return redirect()->away($url);
                    }
                } else {
                    if (isset($request->payment_method) && $request->payment_method == "paypal") {
                        $GetTotalBalance = (float)abs(AccountJournal::getAccountBalance($store_id));
                        $GetTotalBalance = number_format(AccountJournal::convertCurrency($GetTotalBalance, 1, 2), 2);

                        if ($amount > $GetTotalBalance) {
                            Session::flash('error', "You can not pay extra amount.");
                            return back();
                        }

                        $url = route("paypal.dropshipper.payment", ['user_id' => $user_id, 'store_id' => $store_id, 'amount' => $amount]);
                        return redirect()->away($url);
                    }

                    return back()->with("error", "Wrong payment method");
                }
            }

            return back()->with("error", "Something went wrong. Try again later.");
        }

        return back()->with("error", "You are not authorized.");
    }


}
