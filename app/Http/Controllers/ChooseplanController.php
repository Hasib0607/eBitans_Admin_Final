<?php

namespace App\Http\Controllers;

use App\Models\AddonsApi;
use App\Models\AddonsOrder;
use App\Models\AddonsOrderPaymentHistory;
use App\Models\AdminCoupon;
use App\Models\ModulusPayment;
use App\Models\Posplan;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\Planorder;
use App\Models\Store;
use App\Models\Plan;
use App\Models\Domain;
use App\Models\Addon;
use App\Models\Mobileapp;
use App\Models\Toptool;
use App\Models\Staff;
use DB;
use Carbon\Carbon;
use App\Library\SslCommerz\SslCommerzNotification;
use Session;
use App\Models\Activitylog;
use App\Models\Paymenttoken;
use App\Http\Traits\ActivityLogTraits;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ChooseplanController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chooseplan()
    {
        $urls = "planorder";
        $plans = Plan::all();
        // return view('plan.chooseplan')->with('urls',$urls);
        return view('admin.price.index')->with('urls', $urls)->with('plans', $plans);

    }

    public function chooseplans()
    {
        $urls = "planorder";
        $plans = Plan::all();
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        }
        $store = Store::find($store_id);
        $plan = Plan::find($store->plan_id);
        $response = Http::post('https://admin.ebitans.com/api/v1/paymentlogin', [
            'user_id' => Auth::user()->id,
        ]);
        $payt = new Paymenttoken();
        $payt->token = $response['token'];
        $payt->uid = $user;
        $payt->save();
        // $response->successful();
        $url = "https://admin.ebitans.com/pricings?" . $response['token'];
        // $url="http://localhost:3000?".$response['token'];
        return redirect($url);
        // return view('plan.chooseplan')->with('urls',$urls);
        // return view('admin.price.index')->with('urls',$urls)->with('plans',$plans);

    }

    public function activeplans($id, $month)
    {
        $user = Auth::user()->id;
        $customer = Customer::where('uid', $user)->first();
        $store = Store::where('user_id', Auth::user()->id)->where('id', $customer->active_store)->first();
        $str = Store::find($store->id);
        if ($str->purchase_date == "0000-00-00") {
            $str->plan_id = $id;
            $str->month = $month;
            $str->trail = 0;
            $str->purchase_date = Carbon::now();
            $str->expiry_date = Carbon::now()->addDays(7);
            $str->plan_status = "active";
            $str->status = "active";
            $str->save();
            $custo = Customer::find($customer->id);
            $custo->active_store = $str->id;
            $custo->save();
            // dd("ok");
            return redirect('/');
        } else {
            Session::put('plan_month', $month);
            Session::put('plan_id', $id);
            Session::put('purchase_data', Carbon::now());
            Session::put('expiry_date', Carbon::now()->addDays($month * 30));
            return redirect()->route('payment.payments');
        }
    }

    public function payment_payments()
    {
        if (Auth::user()->type == 'superstaff' && is_null(Auth::user()->store_id)) {
            return redirect()->route('staff.dashboard');
        }

        $store_id = getUserData()['store_id'];
        $urls = 'payment';
        $addonsOrders = AddonsOrder::select('addons_orders.*', 'plans.name', 'currencies.code')
            ->leftJoin('plans', function ($query) {
                $query->on('addons_orders.plan_id', '=', 'plans.id');
            })
            ->join('currencies', 'currencies.id', 'addons_orders.currency_id')
            ->where('addons_orders.store_id', $store_id)
            ->orderBy('addons_orders.id', 'DESC')
            ->get();

        $modulusPayments = ModulusPayment::with('getModulus')
            ->where('store_id', $store_id)
            ->get()
            ->map(function ($payment) {
                $payment->modulus_name = optional($payment->getModulus)->name;
                $payment->status = isset($payment->status) ? $payment->status == 1 ? "Complete" : "Failed" : "Processing";
                return $payment;
            });

        // Merge the results
        $mergedData = $addonsOrders->merge($modulusPayments);

        // Sort by `created_at` in descending order
        $orders = $mergedData->sortByDesc('created_at')->values();

        return view('admin.payment.payments.index', compact('urls', 'orders'));
    }

    public function paymentInvoiceById(Request $request, $id)
    {
        if (Auth::user()->type == 'superstaff' && is_null(Auth::user()->store_id)) {
            return redirect()->route('staff.dashboard');
        }

        $store_id = getUserData()['store_id'];
        $order = AddonsOrder::with(["store", "user", "paymentHistories.creator"])
            ->where('id', $id)
            ->where('store_id', $store_id)
            ->first();

        if (isset($order)) {
            $data['data'] = $order;
            $data['package'] = !empty($order->package) ? json_decode($order->package) : null;
            $data['selectedPaymentHistory'] = null;

            if ($request->filled('payment_history_id')) {
                $data['selectedPaymentHistory'] = $order->paymentHistories
                    ->firstWhere('id', (int) $request->payment_history_id);
            }

            return view('admin.payment.payments.invoice', $data);
        }

        return redirect()->route('payment.payments');
    }

    public function updateManualDuePayment(Request $request, $id)
    {
        if (Auth::user()->type == 'superstaff' && is_null(Auth::user()->store_id)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
            ], 403);
        }

        $store_id = getUserData()['store_id'];
        $order = AddonsOrder::where('id', $id)
            ->where('store_id', $store_id)
            ->first();

        if (!isset($order)) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        $currentDue = round((float) ($order->due_amount ?? 0), 2);
        if ($currentDue <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'This payment has no due amount left.',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'additional_paid_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            'payment_number' => 'nullable|string|max:100',
            'transaction_id' => 'nullable|string|max:191',
            'bank_name' => 'nullable|string|max:191',
            'account_number' => 'nullable|string|max:191',
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $paymentAmount = round((float) $request->additional_paid_amount, 2);
        if ($paymentAmount > $currentDue) {
            return response()->json([
                'status' => false,
                'message' => 'Additional paid amount cannot exceed current due amount.',
            ], 422);
        }

        $pendingHistory = $order->paymentHistories()
            ->where('due_amount_status', 'pending_acceptance')
            ->latest('id')
            ->first();

        if ($pendingHistory) {
            return response()->json([
                'status' => false,
                'message' => 'A due payment request is already pending for approval.',
            ], 422);
        }

        $paymentMethod = (string) $request->payment_method;
        if ($paymentMethod === 'bank_transfer') {
            if (empty($request->bank_name) || empty($request->account_number) || empty($request->transaction_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bank transfer requires bank name, account number, and transaction ID.',
                ], 422);
            }
        } elseif (!in_array($paymentMethod, ['hand_cash', 'due'], true)) {
            if (empty($request->payment_number) || empty($request->transaction_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please input payment number and transaction ID.',
                ], 422);
            }
        }

        try {
            $previousPaidAmount = round((float) ($order->paid_amount ?? 0), 2);
            $previousDueAmount = $currentDue;

            $currentPaidAmount = round($previousPaidAmount + $paymentAmount, 2);
            $currentDueAmount = round(max(0, ((float) $order->total) - $currentPaidAmount), 2);
            $history = AddonsOrderPaymentHistory::create([
                'addons_order_id' => $order->id,
                'payment_amount' => $paymentAmount,
                'previous_paid_amount' => $previousPaidAmount,
                'previous_due_amount' => $previousDueAmount,
                'current_paid_amount' => $currentPaidAmount,
                'current_due_amount' => $currentDueAmount,
                'due_amount_status' => 'pending_acceptance',
                'payment_method' => $paymentMethod,
                'payment_number' => $request->payment_number,
                'transaction_id' => $request->transaction_id,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'note' => $request->note,
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Partial due payment request sent to superadmin for approval.',
                'invoice_url' => route('payment.payments.invoice', [
                    'id' => $order->id,
                    'payment_history_id' => $history->id,
                ]),
                'order' => [
                    'id' => $order->id,
                    'payment_method' => $order->payment_method,
                    'paid_amount' => (float) ($order->paid_amount ?? 0),
                    'due_amount' => (float) ($order->due_amount ?? 0),
                    'due_amount_status' => $order->due_amount_status,
                    'transaction_id' => $order->transaction_id,
                    'payment_number' => $order->payment_number,
                    'bank_name' => $order->bank_name,
                    'account_number' => $order->account_number,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Could not submit due payment request. Please try again.',
            ], 500);
        }
    }

    public function payment_addons(Request $request)
    {
        if (Auth::user()->type == 'superstaff' && is_null(Auth::user()->store_id)) {
            return redirect()->route('staff.dashboard');
        }

        $userData = getUserData();
        $store = $userData['store'];
        $urls = 'payment';
        $addons = AddonsApi::where("status", 1)->orderBy("position", "ASC")->get();
        $plan = Plan::where('id', $request->plan)->first() ?? null;
        // ✅ Late fee calculation for UI display
        $currency_type = $this->getCurrencyType(); // BDT or USD
        $lastPlan = Plan::find($store->plan_id); // ✅ last active plan
        $lateFeeInfo = $this->calculateLateFee($store, $lastPlan, $currency_type);

        $late_fee = (float) ($lateFeeInfo['late_fee'] ?? 0);
        $late_fee_days = (int) ($lateFeeInfo['overdue_days'] ?? 0);
        $late_fee_reason = $lateFeeInfo['reason'] ?? null;
        $posPlan = Posplan::where('status', "active")->orderBy("position", "ASC")->get();
        $dueOrders = AddonsOrder::with(['paymentHistories.creator'])
            ->where('store_id', $store->id)
            ->whereIn('status', ['Complete', 'Processing'])
            ->where('due_amount', '>', 0)
            ->orderBy('updated_at', 'DESC')
            ->get();

        if (isset($plan)) {
            $plan->month = $request->month ?? 1;
            if (isset($store->upcoming_plan_id)) {
                return redirect()->back()->with("error", "You have already purchased next plan");
            }
        }

        return view('admin.payment.addons.index', compact(
            'urls',
            'addons',
            'plan',
            'posPlan',
            'dueOrders',
            'late_fee',
            'late_fee_days',
            'late_fee_reason'
        ));
    }

    public function payment_packages()
    {
        $urls = 'payment';

        if (Auth::user()->type == 'superstaff' && is_null(Auth::user()->store_id)) {
            return redirect()->route('staff.dashboard');
        }

        //user_id, store_id, customer, customer_id
        extract(getUserData());

        $store = Store::find($store_id);

        if (isset(Auth::user()->type)) {
            if (Auth::user()->type == 'dropshipper') {
                $plans = Plan::with('details')->whereIn('id', [8])->where("status", "active")->get();
            } else {
                $plans = Plan::with('details')->whereNotIn('id', [8, 9])->where("status", "active")->get();
            }
        } else {
            return view('error');
        }

        return view('admin.payment.packages.index', compact('urls', 'plans'));
    }

    public function payment_packages_view($id)
    {
        $urls = 'payment';

        if (Auth::user()->type == 'superstaff' && is_null(Auth::user()->store_id)) {
            return redirect()->route('staff.dashboard');
        }

        $plan = Plan::with('details')->find($id);
        $plans = Plan::with('details')->get();
        if (!isset($plan)) {
            \Illuminate\Support\Facades\Session::flash("error", "Package Not Found");
            return redirect()->route('payment.packages');
        }
        return view('admin.payment.packages.view', compact('urls', 'plan', 'plans'));
    }


    public function getCoupon(Request $request)
    {
        $visitor = getVisitorInfo();
        $currency_type = "BDT";
        $code = $request->code;
        $subtotal = $request->subtotal;

        if (isset($visitor) && isset($visitor->countryCode)) {
            if ($visitor->countryCode !== 'BD') {
                $currency_type = "USD";
            }
        }
        $today = now()->toDateString();

        $coupon = AdminCoupon::where('code', $code)
            ->where('currency_type', $currency_type)
            ->where('min_purchase', '<=', $subtotal)
            ->where('max_purchase', '>=', $subtotal)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->where('status', 'active')
            ->first();

        if (isset($coupon)) {
            return response()->json($coupon);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Coupon not found or expired']);
        }
    }

    public function buyAddons(Request $request)
    {
        if ($request->addons == "" && $request->combo_packages == "" && isset($request->plan_id)) {
            return response()->json(['warning' => 'Please Select any Package or Addons.']);
        }
        if ($request->subtotal <= 0) {
            return response()->json(["status" => false, 'message' => "Payment amount can't be zero!."]);
        }

        if (!isset($request->payment_method) || empty($request->payment_method)) {
            return response()->json(['warning' => 'Please Select a payment method.']);
        }

        //user_id, store_id, customer, customer_id
        extract(getUserData());

        $today = now()->toDateString();
        $user = User::findOrFail($user_id);
        if (!isset($user)) {
            return response()->json(['error', 'message' => 'unauthorized user']);
        }
        $phone = $user->phone ?? "";

        $visitor = getVisitorInfo();
        $currency_type = "BDT";
        if (isset($visitor) && isset($visitor->countryCode)) {
            if ($visitor->countryCode !== 'BD') {
                $currency_type = "USD";
            }
        }
        $store = Store::find($store_id);

        if (!$store) {
            return response()->json(['status' => false, 'message' => 'Store not found']);
        }

        // ✅ last active plan (store current plan)
        $lastPlan = Plan::find($store->plan_id);

        $lateFeeInfo = $this->calculateLateFee($store, $lastPlan, $currency_type);
        $lateFee = (float) ($lateFeeInfo['late_fee'] ?? 0);
        $discount = 0;
        $coupon = AdminCoupon::where('code', $request->code)
            ->where('currency_type', $currency_type)
            ->where('min_purchase', '<=', $request->subtotal)
            ->where('max_purchase', '>=', $request->subtotal)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->where('status', 'active')
            ->first();
        if (isset($coupon)) {
            $discount = (float) $coupon->discount_amount;
            if ($coupon->discount_type == 'percent') {
                $discount = floor((float) $request->subtotal * ((float) $coupon->discount_amount / 100));
            }
            if ($request->discount < $discount) {
                $discount = $request->discount;
            }
        }
        $plan = Plan::find($request->plan_id);

        // Separate addons (excluding 'package' type)
        $addons = array_values(array_filter($request->addons, function ($item) {
            return $item['type'] !== 'package';
        }));

        $package = current(array_filter($request->addons, function ($item) {
            return $item['type'] === 'package';
        })) ?: NULL;


        // Sum addon prices
        $addonTotal = array_reduce($addons, function ($carry, $addon) {
            return $carry + ($addon['offerprice'] ?? 0);
        }, 0);

        // Add package price if it exists
        $packagePrice = $package['offerprice'] ?? 0;

        // Final total
        $totalPrice = $addonTotal + $packagePrice;

        if ($request->subtotal < $totalPrice) {
            return response()->json(['warning' => 'Total amount does not match!']);
        }

        $addonsOrder = new AddonsOrder();

        if ($plan) {
            $addonsOrder->plan_id = $plan->id;
            $addonsOrder->plan_type = 'website';
            $addonsOrder->plan_month = $request->month ?? 0;
        }

        $addonsOrder->user_id = $user_id;
        $addonsOrder->store_id = $store_id;
        $addonsOrder->currency_id = 1;
        $addonsOrder->addons = $addons;
        $addonsOrder->package = json_encode($package);
        $addonsOrder->payment_method = $request->payment_method;
        $addonsOrder->payment_number = $phone;
        $addonsOrder->total = ((float) $request->subtotal - $discount) + $lateFee;

        // ✅ store late fee breakdown for invoice/mail
        $addonsOrder->late_fee = (float) ($lateFeeInfo['late_fee'] ?? 0);
        $addonsOrder->late_fee_overdue_days = (int) ($lateFeeInfo['overdue_days'] ?? 0);
        $addonsOrder->late_fee_reason = $lateFeeInfo['reason'] ?? null;
        $addonsOrder->currency_type = $currency_type;

        $addonsOrder->plan_check = 1;
        $addonsOrder->status = 'Failed';
        $addonsOrder->save();

        $store = Store::where("id", $store_id)->first();

        $planOrderURL = route("superadmin.orderPlanrequest");

        if (!is_null($addons) && count($addons) > 0) {
            // Create notification
            $notificationData = [
                "title" => "Addon purchased request By (" . ($store->name ?? '') . ") - " . formatDateWithTime($addonsOrder->created_at),
                "type" => "addon_order",
                "user_type" => "superadmin",
                "link" => $planOrderURL,
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }
        }

        if (!is_null($package) && $package != null) {
            // Create notification
            $notificationData = [
                "title" => "Package purchased request By (" . ($store->name ?? '') . ") - " . formatDateWithTime($addonsOrder->created_at),
                "type" => "plan_order",
                "user_type" => "superadmin",
                "link" => $planOrderURL,
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }
        }


        if ($request->payment_method == "bkash") {
            $url = env('APP_URL') . '/api/v1/admin/bkash/checkout-url/orderPay?order=' . $addonsOrder->id;
        } elseif ($request->payment_method == "nagad") {
            $url = route('nagad.admin.payment') . "?order_id=" . $addonsOrder->id;
        } elseif ($request->payment_method == "paypal") {
            $url = route('paypal.admin.payment') . "?order_id=" . $addonsOrder->id;
        } elseif ($request->payment_method == "amarpay") {
            $url = route('amarpay.admin.payment') . "?order_id=" . $addonsOrder->id;
        } else {
            $url = null;
        }

        return response()->json(['addonsOrder' => $addonsOrder, 'url' => $url]);
    }

    public function buyAddonsWithManual(Request $request)
    {
        if (!in_array(Auth::user()->type, ['superadmin', 'superstaff'])) {
            return response()->json(['status' => false, 'message' => 'Unauthorized']);
        }

        if ($request->addons == "" && $request->combo_packages == "" && isset($request->plan_id)) {
            return response()->json(["status" => false, 'message' => 'Please Select any Package or Addons.']);
        }

        if ($request->subtotal <= 0) {
            return response()->json(["status" => false, 'message' => "Payment amount can't be zero!."]);
        }

        if (!isset($request->payment_method) || empty($request->payment_method)) {
            return response()->json(["status" => false, 'message' => "Please Select a payment method."]);
        }

        extract(getUserData());

        $today = now()->toDateString();
        $user = User::findOrFail($user_id);

        if (!isset($user)) {
            return response()->json(["status" => false, 'message' => 'unauthorized user']);
        }

        $visitor = getVisitorInfo();
        $currency_type = "BDT";
        if (isset($visitor) && isset($visitor->countryCode)) {
            if ($visitor->countryCode !== 'BD') {
                $currency_type = "USD";
            }
        }

        $store = Store::find($store_id);
        if (!$store) {
            return response()->json(['status' => false, 'message' => 'Store not found']);
        }

        // Last active plan for late fee calculation
        $lastPlan = Plan::find($store->plan_id);
        $lateFeeInfo = $this->calculateLateFee($store, $lastPlan, $currency_type);
        $lateFee = (float) ($lateFeeInfo['late_fee'] ?? 0);

        // New manual fields
        $manualDiscount = (float) ($request->manual_discount ?? 0);
        $manualDiscountComment = trim($request->manual_discount_comment ?? '');
        $paymentType = $request->payment_type ?? 'full';
        $paidAmount = (float) ($request->paid_amount ?? 0);
        $dueAmount = (float) ($request->due_amount ?? 0);
        $bankName = trim($request->bank_name ?? '');
        $accountNumber = trim($request->account_number ?? '');

        // Coupon discount
        $discount = 0;
        $coupon = AdminCoupon::where('code', $request->code)
            ->where('currency_type', $currency_type)
            ->where('min_purchase', '<=', $request->subtotal)
            ->where('max_purchase', '>=', $request->subtotal)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->where('status', 'active')
            ->first();

        if (isset($coupon)) {
            $discount = (float) $coupon->discount_amount;

            if ($coupon->discount_type == 'percent') {
                $discount = floor((float) $request->subtotal * ((float) $coupon->discount_amount / 100));
            }

            if ($request->discount < $discount) {
                $discount = $request->discount;
            }
        }

        // Validation
        if ($manualDiscount < 0) {
            return response()->json(["status" => false, 'message' => 'Manual discount cannot be negative.']);
        }

        $baseAmount = (float) $request->subtotal - (float) $discount;
        if ($manualDiscount > $baseAmount) {
            return response()->json(["status" => false, 'message' => 'Manual discount cannot exceed payable amount.']);
        }

        if ($paymentType === 'partial') {
            if ($paidAmount <= 0) {
                return response()->json(["status" => false, 'message' => 'Please enter paid amount for partial payment.']);
            }

            $partialPayable = ($baseAmount - $manualDiscount) + $lateFee;

            if ($paidAmount > $partialPayable) {
                return response()->json(["status" => false, 'message' => 'Paid amount cannot be greater than payable amount.']);
            }

            $dueAmount = $partialPayable - $paidAmount;
            if ($dueAmount < 0) {
                $dueAmount = 0;
            }
        } else {
            $paidAmount = 0;
            $dueAmount = 0;
        }

        if ($request->payment_method === 'bank_transfer') {
            if (empty($bankName)) {
                return response()->json(["status" => false, 'message' => 'Please enter bank name.']);
            }

            if (empty($accountNumber)) {
                return response()->json(["status" => false, 'message' => 'Please enter account number.']);
            }

            if (empty($request->transaction)) {
                return response()->json(["status" => false, 'message' => 'Please enter transaction ID.']);
            }
        }

        if (
            $request->payment_method !== 'hand_cash' &&
            $request->payment_method !== 'due' &&
            $request->payment_method !== 'bank_transfer'
        ) {
            if (empty($request->phone) || empty($request->transaction)) {
                return response()->json(["status" => false, 'message' => 'Please input payment number and transaction ID.']);
            }
        }

        if ($request->payment_method === 'due' && $paymentType !== 'partial') {
            return response()->json(["status" => false, 'message' => 'Due payment must be partial payment.']);
        }

        if (($manualDiscount > 0 || $paymentType === 'partial' || $request->payment_method === 'due') && empty($manualDiscountComment)) {
            return response()->json(["status" => false, 'message' => 'Please write a comment / reason.']);
        }

        $plan = Plan::find($request->plan_id);
        $addonsOrder = new AddonsOrder();
        $package = null;

        if ($plan) {
            $addonsOrder->plan_id = $plan->id;
            $addonsOrder->plan_type = 'website';
            $addonsOrder->plan_month = $request->month ?? 0;

            $package = array_filter($request->addons, function ($item) {
                return $item['type'] === 'package';
            });
            $package = $package[0] ?? null;
        }

        $dueAmountStatus = 'paid';

        if ($request->payment_method === 'due') {
            $dueAmountStatus = 'due';
        } elseif ($paymentType === 'partial' && $dueAmount > 0) {
            $dueAmountStatus = 'partial_due';
        } elseif ($dueAmount <= 0) {
            $dueAmountStatus = 'paid';
        }


        $request->addons = array_filter($request->addons, function ($item) {
            return $item['type'] !== 'package';
        });

        $addonsOrder->user_id = $user_id;
        $addonsOrder->store_id = $store_id;
        $addonsOrder->currency_id = 1;
        $addonsOrder->addons = $request->addons ?? null;
        $addonsOrder->package = !empty($package) ? json_encode($package) : null;
        $addonsOrder->payment_method = $request->payment_method;
        $addonsOrder->transaction_id = $request->transaction;
        $addonsOrder->payment_number = $request->phone;

        // New manual payment fields
        $addonsOrder->manual_discount = $manualDiscount;
        $addonsOrder->manual_discount_comment = $manualDiscountComment;
        $addonsOrder->payment_type = $paymentType;
        $addonsOrder->paid_amount = $paymentType === 'partial' ? $paidAmount : null;
        $addonsOrder->due_amount = $paymentType === 'partial' ? $dueAmount : 0;
        $addonsOrder->due_amount_status = $dueAmountStatus;
        $addonsOrder->bank_name = $bankName ?: null;
        $addonsOrder->account_number = $accountNumber ?: null;

        // Final total
        $addonsOrder->total = (((float) $request->subtotal - $discount) - $manualDiscount) + $lateFee;
        if ($addonsOrder->total < 0) {
            $addonsOrder->total = 0;
        }

        // Late fee breakdown
        $addonsOrder->late_fee = (float) ($lateFeeInfo['late_fee'] ?? 0);
        $addonsOrder->late_fee_overdue_days = (int) ($lateFeeInfo['overdue_days'] ?? 0);
        $addonsOrder->late_fee_reason = $lateFeeInfo['reason'] ?? null;
        $addonsOrder->currency_type = $currency_type;

        $addonsOrder->coupon = $request->code;
        $addonsOrder->plan_check = 1;
        $addonsOrder->status = 'Processing';
        $addonsOrder->save();

        $planOrderURL = route("superadmin.orderPlanrequest");

        if (!is_null($request->addons) && count($request->addons) > 0) {
            $notificationData = [
                "title" => "Addon purchased request By (" . ($store->name ?? '') . ") - " . formatDateWithTime($addonsOrder->created_at),
                "type" => "addon_order",
                "user_type" => "superadmin",
                "link" => $planOrderURL,
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }
        }

        if (!is_null($package) && $package != null) {
            $notificationData = [
                "title" => "Package purchased request By (" . ($store->name ?? '') . ") - " . formatDateWithTime($addonsOrder->created_at),
                "type" => "plan_order",
                "user_type" => "superadmin",
                "link" => $planOrderURL,
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }
        }

        $payment_accept_code = env('NAGAD_PAYMENT_ACCEPT_CODE', '');
        if ((!empty($payment_accept_code) && $payment_accept_code === $request->transaction)) {
            $result = (new SuperAdminController())->newacceptplanorder($addonsOrder->id, true);

            if (isset($result['status']) && $result['status'] == true) {
                return response()->json([
                    "status" => true,
                    "message" => $result['message'],
                    'addonsOrder' => $addonsOrder
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    'message' => $result['message']
                ]);
            }
        }

        if ($addonsOrder) {
            return response()->json([
                "status" => true,
                "message" => "Your payment is under Processing",
                'addonsOrder' => $addonsOrder
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Process not completed. Plesae try again!",
            'addonsOrder' => $addonsOrder
        ]);
    }


    public function payments1234()
    {
        // $user=Auth::user()->id;
        // $customer=Customer::where('uid',$user)->first();
        // if($customer->expiry_date <= Carbon::now()){
        // Session::forget('plan_id');
        // Session::forget('addons');
        // Session::forget('addons_month');
        // Session::forget('addons_total');
        // Session::forget('activityaddons');
        // Session::forget('activityaddons_month');
        // Session::forget('activityaddons_total');
        $urls = "payment";
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        }
        $store = Store::find($store_id);
        $plan = Plan::find($store->plan_id);
        $baseUrl = rtrim(config('app.url'), '/');

        $response = Http::post($baseUrl . '/api/v1/paymentlogin', [
            'user_id' => Auth::user()->id,
        ]);

        $token = data_get($response->json(), 'token'); // safer than $response['token']

        $payt = new Paymenttoken();
        $payt->token = $token;
        $payt->uid = $user;
        $payt->save();

        $url = $baseUrl . '/payment?' . http_build_query(['token' => $token]);
        // $url="http://localhost:3000?".$response['token'];
        return redirect($url);

        // return view('plan.payment')->with('urls',$urls)->with('store',$store)->with('plan',$plan);

        // }else{
        //     return redirect('/');
        // }

    }

    public function payments()
    {
        $user = Auth::user()->id;
        $customer = Customer::where('uid', $user)->first();
        // if($customer->expiry_date <= Carbon::now()){
        //     Session::forget('plan_id');
        //     Session::forget('addons');
        //     Session::forget('addons_month');
        //     Session::forget('addons_total');
        //     Session::forget('activityaddons');
        //     Session::forget('activityaddons_month');
        //     Session::forget('activityaddons_total');
        $urls = "payment";
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        }
        $store = Store::find($store_id);
        $plan = Plan::find($store->plan_id);
        return view('plan.payment')->with('urls', $urls)->with('store', $store)->with('plan', $plan);
        // }else{
        //     return redirect('/');
        // }

    }

    public function plancheck(Request $request)
    {
        Session::put('plan_id', $request->plan_id);
        Session::put('plan_month', $request->month);
        $data['plan_id'] = Session::get("plan_id");
        $data['plan_month'] = Session::get("plan_month");
        $plan = Plan::where('id', $request->plan_id)->first();
        return response()->json($data);
    }

    public function plancheckout(Request $request)
    {
        Session::forget('plan_id');
        Session::forget('plan_month');
        $data = 0;
        return response()->json($data);
    }

    public function addonsadd(Request $request)
    {

        Session::put('addons', $request->name);
        Session::put('addons_month', $request->month);
        Session::put('addons_total', $request->month * 100);
        if (Session::has('activityaddons_total')) {
            $totaal = Session::get('addons_total') + Session::get('activityaddons_total');
        } else {
            $totaal = Session::get('addons_total');
        }
        Session::forget('addons_total');
        Session::Put('addons_total', $totaal);
        $data = 1;
        return response()->json($data);
    }

    public function activityaddonsadd(Request $request)
    {
        Session::put('activityaddons', $request->name);
        Session::put('activityaddons_month', $request->month);
        if (Session::has('addons_total') && Session::has('activityaddons_total')) {
            $totals = $totaal = Session::get('addons_total') - Session::get('activityaddons_total');
            Session::put('addons_total', $totals);
        }
        Session::put('activityaddons_total', $request->month * 50);
        if (Session::has('addons_total')) {
            $totaal = Session::get('addons_total') + Session::get('activityaddons_total');
        } else {
            $totaal = Session::get('activityaddons_total');
        }
        Session::Put('addons_total', $totaal);
        $data = 1;
        return response()->json($data);
    }

    public function addonsremove(Request $request)
    {
        if (Session::has('activityaddons_total')) {
            $total = Session::get('addons_total') - (Session::get('addons_month') * 100);
            Session::Put('addons_total', $total);
        } else {
            Session::forget('addons_total');
        }
        Session::forget('addons');
        Session::forget('addons_month');

        $data = 1;
        return response()->json($data);
    }

    public function activityaddonsremove(Request $request)
    {
        if (Session::has('activityaddons_total')) {
            $total = Session::get('addons_total') - Session::get('activityaddons_total');
            Session::Put('addons_total', $total);
        } else {
            Session::forget('addons_total');
        }
        Session::forget('activityaddons');
        Session::forget('activityaddons_month');
        Session::forget('activityaddons_total');

        $data = 1;
        return response()->json($data);
    }

    public function changeplan(Request $request)
    {
        if (Session::has('plan_id')) {
            Session::forget('plan_id');
        }
        Session::put('plan_id', $request->value);
        $data = $request->value;
        return response()->json($data);
    }

    public function checkStoreName(Request $request)
    {
        $exists = Store::where('name', $request->name)->exists();
        if ($exists) {
            $data['storeName'] = [
                'status' => 422,
                'message' => '"' . ucwords($request->name) . '" The Store Name Already Exist'
            ];
        } else {
            $data['storeName'] = [
                'status' => 200,
                'message' => '"' . ucwords($request->name) . '" Your Store Name is Available '
            ];
        }
        return response()->json($data);
    }

    public function checkurlname(Request $request)
    {
        if (empty($request->name)) {
            return response()->json([
                'status' => 0,
                'url' => null
            ]);
        }

        $url = $request->name . "." . env("STORE_SUB_DOMAIN");

        $store = Domain::where('name', $url)
            ->where('status', '!=', 'Rejected')
            ->get();

        if ($store->count() > 0) {
            return response()->json([
                'status' => 1,
                'url' => $url
            ]);
        }

        return response()->json([
            'status' => 0,
            'url' => $url
        ]);
    }

    public function placeplan(Request $request)
    {
        //   dd($request->all());
        $user_id = Auth::user()->id;
        $customer = Customer::where('uid', $user_id)->first();
        $store = Store::where('id', $customer->active_store)->first();
        $exp = $request->month * 30;
        $post_data = array();
        $post_data['total_amount'] = $request->total; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";
        if ($request->selectpackage == "0") {
            $order = new Planorder;
            $order->customer_id = $customer->id;
            $order->store_id = $store->id;
            $order->total_amount = $request->total;
            $order->status = "Processing";
            $order->view = "0";
            if ($request->paymentMethod == 'bkash') {
                $order->method = "bkash";
                $order->number = $request->bkash;
                $order->transaction_id = $request->bkash_transaction_id;
            } elseif ($request->paymentMethod == 'nagad') {
                $order->method = "nagad";
                $order->number = $request->nagad;
                $order->transaction_id = $request->nagad_transaction_id;
            }
            $order->discount = $request->discount;
            $order->addons_price = $request->addons;
            $order->save();
            if ($request->mobileapps) {
                $olds = Addon::where('name', 'mobileapps')->where('store_id', $store->id)->first();
                $addonss = new Addon();
                $addonss->plan_order_id = $order->id;
                $addonss->name = $request->mobileapps;
                $addonss->price = $request->mobileappsmonth * 100;
                $addonss->store_id = $store->id;
                $addonss->status = "Pending";
                $addonss->month = $request->mobileappsmonth;
                $addonss->start_date = Carbon::now();
                $addonss->expiry_date = Carbon::now()->addDays($request->mobileappsmonth * 30);
                $addonss->save();
            }
            if ($request->activitylog) {
                $addonss = new Addon();
                $addonss->plan_order_id = $order->id;
                $addonss->name = $request->activitylog;
                $addonss->price = $request->activitymonth * 50;
                $addonss->store_id = $store->id;
                $addonss->status = "Pending";
                $addonss->month = $request->activitymonth;
                $addonss->start_date = Carbon::now();
                $addonss->expiry_date = Carbon::now()->addDays($request->mobileappsmonth * 30);
                $addonss->save();
            }
            if ($request->adminpanelapps) {
                $addonss = new Addon();
                $addonss->plan_order_id = $order->id;
                $addonss->name = $request->adminpanelapps;
                $addonss->price = $request->adminmobileappsmonth * 100;
                $addonss->store_id = $store->id;
                $addonss->status = "Pending";
                $addonss->month = $request->adminmobileappsmonth;
                $addonss->start_date = Carbon::now();
                $addonss->expiry_date = Carbon::now()->addDays($request->adminmobileappsmonth * 30);
                $addonss->save();
            }
        } else {
            $order = new Planorder;
            $order->plan_id = $request->selectpackage;
            $order->customer_id = $customer->id;
            $order->store_id = $store->id;
            $order->active_date = Carbon::now();
            $order->expiry_date = Carbon::now()->addDays($exp);
            $order->total_amount = $request->total;
            $order->total_month = $request->month;
            $order->status = "Processing";
            $order->view = "0";
            if ($request->paymentMethod == 'bkash') {
                $order->method = "bkash";
                $order->number = $request->bkash;
                $order->transaction_id = $request->bkash_transaction_id;
            } elseif ($request->paymentMethod == 'nagad') {
                $order->method = "nagad";
                $order->number = $request->nagad;
                $order->transaction_id = $request->nagad_transaction_id;
            }
            $order->discount = $request->discount;
            $order->addons_price = $request->addons;
            $order->save();
            if ($request->mobileapps) {
                $addonss = new Addon();
                $addonss->plan_order_id = $order->id;
                $addonss->name = $request->mobileapps;
                $addonss->price = $request->mobileappsmonth * 100;
                $addonss->store_id = $store->id;
                $addonss->status = "Pending";
                $addonss->month = $request->mobileappsmonth;
                $addonss->start_date = Carbon::now();
                $addonss->expiry_date = Carbon::now()->addDays($request->mobileappsmonth * 30);
                $addonss->save();
            }
            if ($request->activitylog) {
                $addonss = new Addon();
                $addonss->plan_order_id = $order->id;
                $addonss->name = $request->activitylog;
                $addonss->price = $request->activitymonth * 50;
                $addonss->store_id = $store->id;
                $addonss->status = "Pending";
                $addonss->month = $request->activitymonth;
                $addonss->start_date = Carbon::now();
                $addonss->expiry_date = Carbon::now()->addDays($request->mobileappsmonth * 30);
                $addonss->save();
            }
            if ($request->adminpanelapps) {
                $addonss = new Addon();
                $addonss->plan_order_id = $order->id;
                $addonss->name = $request->adminpanelapps;
                $addonss->price = $request->adminmobileappsmonth * 100;
                $addonss->store_id = $store->id;
                $addonss->status = "Pending";
                $addonss->month = $request->adminmobileappsmonth;
                $addonss->start_date = Carbon::now();
                $addonss->expiry_date = Carbon::now()->addDays($request->adminmobileappsmonth * 30);
                $addonss->save();
            }
        }

        // $sslc = new SslCommerzNotification();
        // # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        // $payment_options = $sslc->makePayment($post_data, 'hosted');

        // if (!is_array($payment_options)) {
        //     print_r($payment_options);
        //     $payment_options = array();
        // }
        return redirect('/');
    }

    public function addons()
    {
        $urls = "addons";
        $user_id = Auth::user()->id;
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user_id)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }
        $toptool = Toptool::where('name', 'Mobile App')->where('uid', $user)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Mobile App";
            $toptool->image = "ecommerce.png";
            $toptool->url = "/addonss";
            $toptool->count = "1";
            $toptool->uid = $user;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user;
            $toptool->editor = $user;
            $toptool->save();
        }

        $mobileapp = Mobileapp::where('store_id', $customer->active_store)->first();
        if (isset($mobileapp)) {
            $view = "Active";
        } else {
            $view = "Inactive";
        }
        $activity = " Access Addons Mobile App Page";
        $this->saveactivity($activity);
        return view('admin.addon.index')->with('urls', $urls)->with('mobileapp', $mobileapp)->with(
            'store_id',
            $store_id
        )->with('view', $view);
    }

    public function savemobileappsinfo(Request $request, $id)
    {
        $mobileapp = Mobileapp::find($id);
        $mobileapp->name = $request->name;
        if (isset($request->logo)) {
            if ($request->logo) {
                $imageName = Carbon::now()->timestamp . '.' . $request->logo->extension();
                $request->logo->storeAs('category', $imageName);
                $mobileapp->image = $imageName;
            }
        }
        if (isset($mobileapp->name) && isset($mobileapp->image)) {
            $mobileapp->status = "Request Send";
        }
        $mobileapp->save();

        $activity = " Save Mobile Apps Information " . $mobileapp->name;
        $this->saveactivity($activity);
        Session::flash('message', 'Mobile Apps Setting Successfully Updated');
        return back();
    }

    public function changestatusmobileapps($id, $status)
    {
        // dd($status);
        $mobileapp = Mobileapp::find($id);
        $mobileapp->status = $status;
        $mobileapp->save();
        Session::flash('message', 'Mobile Apps Status Updated');
        return back();
    }

    /**
     * Determine currency type based on visitor country.
     * BD => BDT, otherwise USD (same logic you already use in buyAddons()).
     */
    private function getCurrencyType(): string
    {
        $visitor = getVisitorInfo();
        if (isset($visitor) && isset($visitor->countryCode) && $visitor->countryCode !== 'BD') {
            return "USD";
        }
        return "BDT";
    }

    /**
     * Get monthly plan price based on currency.
     * Plans table: price (BDT), usd_price (USD)
     */
    private function getPlanMonthlyPriceByCurrency(?Plan $plan, string $currencyType): float
    {
        if (!$plan)
            return 0.0;

        if ($currencyType === 'USD') {
            return (float) ($plan->usd_price ?? 0);
        }

        return (float) ($plan->price ?? 0);
    }

    /**
     * Late fee rules:
     * - overdue >= 90 days => 1 month of last plan price (currency-wise)
     * - overdue >= 7 days  => fixed fee (BDT or USD by ENV)
     * - otherwise          => 0
     */
    private function calculateLateFee(Store $store, ?Plan $lastPlan, string $currencyType): array
    {
        // No late fee for trial plan
        if ((int) ($store->plan_id ?? 0) === 6 || (int) ($lastPlan->id ?? 0) === 6) {
            return ['late_fee' => 0.0, 'reason' => null, 'overdue_days' => 0];
        }

        if (empty($store->expiry_date) || $store->expiry_date === "0000-00-00") {
            return ['late_fee' => 0.0, 'reason' => null, 'overdue_days' => 0];
        }

        $expiry = Carbon::parse($store->expiry_date)->startOfDay();
        $today = Carbon::now()->startOfDay();

        if ($today->lte($expiry)) {
            return ['late_fee' => 0.0, 'reason' => null, 'overdue_days' => 0];
        }

        $overdueDays = $expiry->diffInDays($today);

        // 3 months missed (approx 90 days)
        if ($overdueDays >= 90) {
            $monthlyPrice = $this->getPlanMonthlyPriceByCurrency($lastPlan, $currencyType);

            return [
                'late_fee' => (float) $monthlyPrice,
                'reason' => 'LATE_FEE_3_MONTHS',
                'overdue_days' => $overdueDays,
            ];
        }

        // 7 days missed
        if ($overdueDays >= 7) {
            $fee = $currencyType === 'USD'
                ? (float) env('LATE_FEE_USD', 5)
                : (float) env('LATE_FEE_BDT', 500);

            return [
                'late_fee' => $fee,
                'reason' => 'LATE_FEE_7_DAYS',
                'overdue_days' => $overdueDays,
            ];
        }

        return ['late_fee' => 0.0, 'reason' => null, 'overdue_days' => $overdueDays];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
