<?php

namespace App\Http\Controllers;

use App\Models\Headersetting;
use App\Models\MarchantPaymentGetway;
use App\Models\MerchantAccountJournal;
use App\Models\MerchantPaymentWithdraw;
use App\Models\OrderTransactionHistory;
use Illuminate\Http\Request;
use Validator;
use Session;
use Auth;


class AmarpayPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function amarPayOrderList(Request $request)
    {

        try {
            $userData = getUserData();
            $store_id = $userData['store_id'];
            if (
                !merchantPaymentModulusStatus($store_id, 125, "amarpay") &&
                !merchantPaymentModulusStatus($store_id, 128, "bkash") &&
                !merchantPaymentModulusStatus($store_id, 129, "nagad") &&
                !merchantPaymentModulusStatus($store_id, 130, "rocket")
            ) {
                return redirect()->back()->with("error", "You are not authorized to access this page!");
            }

            $query = OrderTransactionHistory::with("order", "customer")->where('store_id', $store_id);

            $search = $request->search ?? "";
            if (!is_null($search) && !empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('transactionId', 'LIKE', "%$search%")
                        ->orWhere('payment_type', 'LIKE', "%$search%")
                        ->orWhereHas('order', function ($subQuery) use ($search) {
                            $subQuery->where('reference_no', 'LIKE', "%$search%");
                        })
                        ->orWhereHas('customer', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'LIKE', "%$search%")
                                ->orWhere('phone', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%");
                        });;
                });
            }

            $orders = $query->orderBy("date_processed", "desc")->paginate(20);

            return view("admin.amarpay.order-list", ['orders' => $orders, "search" => $search]);
        } catch (\Exception $exception) {
            return redirect()->back()->with("error", "Something went wrong!");
        }
    }


    public function amarPayPaymentWithdrawList()
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $merchantPaymentWithdraw = MerchantPaymentWithdraw::where("store_id", $store_id)->orderBy("id", "DESC")->paginate(20);
        $isPending = MerchantPaymentWithdraw::where("store_id", $store_id)->whereIn("status", [0, 1])->first();

        $headerSetting = Headersetting::where("store_id", $store_id)->first();

        $balance = MerchantAccountJournal::getAccountBalance($store_id);

        return view("admin.amarpay.withdraw-list", [
            'items' => $merchantPaymentWithdraw,
            "balance" => $balance,
            "headerSetting" => $headerSetting,
            "isPending" => $isPending,
        ]);
    }


    public function amarPayPaymentWithdrawRequest(Request $request)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $user_id = $userData['user_id'];

        $withdraw_amount = $request->withdraw_amount ?? 0;
        $headerSetting = Headersetting::where("store_id", $store_id)->first();

        if (isset($headerSetting)) {
            if (is_null($headerSetting->balance_min_withdraw) || $withdraw_amount < $headerSetting->balance_min_withdraw) {
                return redirect()->back()->with("error", "Your request amount is lower than minimum withdraw amount!");
            }

            if (!is_null($headerSetting->balance_max_withdraw) && !empty($headerSetting->balance_max_withdraw) && $withdraw_amount > $headerSetting->balance_max_withdraw) {
                return redirect()->back()->with("error", "Your request amount is bigger than maximum withdraw amount!");
            }

            $withdrawRequest = new MerchantPaymentWithdraw();
            $withdrawRequest->user_id = $user_id;
            $withdrawRequest->store_id = $store_id;
            $withdrawRequest->withdraw_amount = $withdraw_amount;
            $withdrawRequest->save();

            return redirect()->back()->with("success", "Successfully submitted withdraw request!");
        } else {
            return redirect()->back()->with("error", "Your are not authorized to withdraw request!");
        }
    }


}
