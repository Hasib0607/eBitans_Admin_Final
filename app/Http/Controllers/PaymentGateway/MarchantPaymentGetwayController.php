<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\Headersetting;
use App\Models\MarchantPaymentGetway;
use App\Models\MerchantAccountJournal;
use App\Models\MerchantPaymentWithdraw;
use App\Models\OrderTransactionHistory;
use Illuminate\Http\Request;

class MarchantPaymentGetwayController extends Controller
{
    public function merchantPaymentList(Request $request)
    {
        $search = $request->search ?? "";

        $query = OrderTransactionHistory::with("store");

        if (!is_null($search) && !empty($search)) {
            if (is_numeric($search)) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('store', function ($subQuery) use ($search) {
                        $subQuery->where('user_id', $search);
                    });
                });
            } else {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('store', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%$search%")
                            ->orWhere('url', 'like', "%$search%");
                    });
                });
            }
        }

        $orders = $query->groupBy('store_id')->paginate(20);

        return view("superadmin.amarpay.merchant-payment-list", ['orders' => $orders, "search" => $search]);
    }

    public function merchantOrderList(Request $request, $store)
    {
        try {
            if (!isset($store) || empty($store)) {
                return redirect()->back()->with("error", "Store ID missing!");
            }

            $query = OrderTransactionHistory::with("store", "customer")->where('store_id', $store);

            $search = $request->search ?? "";
            if (!is_null($search) && !empty($search)) {
                if (is_numeric($search)) {
                    $query->where(function ($q) use ($search) {
                        $q->where('order_id', $search)->orWhere('customer_id', $search);
                    });
                } else {
                    $query->where(function ($q) use ($search) {
                        $q->where('transactionId', 'like', "%$search%")
                            ->orWhere('bank_trxid', 'like', "%$search%")
                            ->orWhere('cardnumber', 'like', "%$search%")
                            ->orWhere('approval_code', 'like', "%$search%")
                            ->orWhere('payment_processor', 'like', "%$search%")
                            ->orWhere('payment_type', 'like', "%$search%");
                    });
                }
            }

            $orders = $query->orderBy("date_processed", "desc")->paginate(20);

            $totalMerchantAmount = OrderTransactionHistory::where('store_id', $store)->sum('merchant_amount');
            $totalStoreAmount = OrderTransactionHistory::where('store_id', $store)->sum('store_amount');
            $totalProfit = $totalStoreAmount - $totalMerchantAmount;

            $withdrawAmount = MerchantPaymentWithdraw::where('store_id', $store)->where("status", 2)->sum('withdraw_amount');
            $pendingAmount = $totalMerchantAmount - $withdrawAmount;

            return view("superadmin.amarpay.merchant-order-list", [
                'orders' => $orders,
                "store" => $store,
                "search" => $search,
                "totalMerchantAmount" => $totalMerchantAmount,
                "withdrawAmount" => $withdrawAmount,
                "pendingAmount" => $pendingAmount,
                "totalProfit" => $totalProfit,
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with("error", "Something went wrong!");
        }
    }


    public function setWithdrawAmount(Request $request)
    {
        try {
            if (!isset($request->id) || empty($request->id)) {
                return sendError("Store ID missing!", '', 200);
            }

            $headerSetting = Headersetting::where('id', $request->id)->first();
            if ($headerSetting) {
                $headerSetting->balance_min_withdraw = $request->min ?? NULL;
                $headerSetting->balance_max_withdraw = $request->max ?? NULL;
                $headerSetting->save();

                return sendResponse("Success");
            } else {
                return sendError("Record not found!", '', 200);
            }
        } catch (\Exception $exception) {
            return sendError("Something went wrong!", '', 200);
        }
    }

    public function merchantActiveStatus(Request $request)
    {
        try {
            if (!isset($request->id) || empty($request->id)) {
                return sendError("Record ID missing!", '', 200);
            }

            if (isset($request->value) && $request->value == "on") {
                $merchantPaymentGetway = MarchantPaymentGetway::where('id', $request->id)->first();
                if ($merchantPaymentGetway) {
                    $merchantPaymentGetway->status = !$merchantPaymentGetway->status;
                    $merchantPaymentGetway->save();

                    return sendResponse("Success");
                } else {
                    return sendError("Record not found!", '', 200);
                }
            }

        } catch (\Exception $exception) {
            return sendError("Something went wrong!", '', 200);
        }
    }


    public function amarpayPaymentWithdrawList(Request $request, $status = null)
    {
        $search = $request->search ?? "";

        if (isset($status) && !empty($status)) {
            if ($status == 'pending') {
                $status = [0];
            } elseif ($status == 'approved') {
                $status = [1];
            } elseif ($status == 'completed') {
                $status = [2];
            } elseif ($status == 'reject') {
                $status = [3];
            }
        } else {
            $status = [0, 3];
        }

        $query = MerchantPaymentWithdraw::with("store", "user", "kyc");

        if (!is_null($search) && !empty($search)) {
            if (is_numeric($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('user_id', $search);
                });
            } else {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('store', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%$search%")
                            ->orWhere('url', 'like', "%$search%");
                    });
                });
            }
        }

        $items = $query->whereIn("status", $status)->paginate(20);

        return view("superadmin.amarpay.withdraw-request-list", ['items' => $items, "search" => $search]);
    }

    public function amarpayPaymentWithdrawStatusChange($id, $status)
    {
        try {
            if (!isset($id) || empty($id)) {
                return redirect()->back()->with("error", "ID missing!");
            } elseif (!isset($status) || empty($status)) {
                return redirect()->back()->with("error", "Status missing!");
            }

            if (isset($status) && !empty($status)) {
                if ($status == 'pending') {
                    $status = 0;
                } elseif ($status == 'approved') {
                    $status = 1;
                } elseif ($status == 'completed') {
                    $status = 2;
                } elseif ($status == 'reject') {
                    $status = 3;
                }
            }

            $MerchantPaymentWithdraw = MerchantPaymentWithdraw::where('id', $id)->first();

            if (isset($MerchantPaymentWithdraw)) {
                $MerchantPaymentWithdraw->status = $status;
                $MerchantPaymentWithdraw->save();

                if ($status == 2) {
                    MerchantAccountJournal::saveWithdrawRequest($MerchantPaymentWithdraw);
                }

                return redirect()->back()->with("success", "Status successfully updated!");
            }

            return redirect()->back()->with("error", "Record not found!");
        } catch (\Exception $exception) {
            return redirect()->back()->with("error", "Something went wrong!");
        }
    }


}
