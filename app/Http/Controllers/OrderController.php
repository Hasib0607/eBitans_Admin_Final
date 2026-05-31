<?php

namespace App\Http\Controllers;

use App\Http\Traits\ActivityLogTraits;
use App\Models\AccountJournal;
use App\Models\AddonsExpired;
use App\Models\Booking;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Toptool;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Veriant;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Programmertowheed\BdCourierFraudChecker\Facade\BdCourierFraudChecker;
use Session;
use Validator;

class OrderController extends Controller
{
    use ActivityLogTraits;

    public function index(Request $request)
    {
        if (!canAccess('orders')) {
            return redirect()->back();
        }

        $urls = "order";

        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        /*increment tools count*/
        topToolsCount('Order', "order.png", "/order");

        $activity = "Access Order List Page";
        $this->saveactivity($activity);

        $store = Store::with('current_currency')->find($store_id);
        $current_currency = $store->current_currency;

        // ✅ Start building query
        $orders = Order::select("orders.*", 'currencies.symbol', 'staff.name as staff_name', 'staff.username as staff_username')
            ->join('currencies', 'orders.currency_id', '=', 'currencies.id')
            ->leftJoin('staff', 'orders.staff_id', '=', 'staff.uid')
            ->where('orders.store_id', $store_id)
            ->whereNotIn('orders.status', ['Restock', 'Returned']);

        // 🧩 Staff restriction
        if ($user_type == 'staff') {
            $orders->where('orders.staff_id', $user_id);
        }

        // 🔍 Apply search filter (only when user searches)

        if ($request->filled('search')) {
            $search = $request->search;
            $orders = $orders->where(function ($query) use ($search) {
                $query->where('orders.id', 'LIKE', "%{$search}%") // ✅ use correct column
                    ->orWhere('orders.reference_no', 'LIKE', "%{$search}%")
                    ->orWhere('orders.phone', 'LIKE', "%{$search}%");
            });
        }

        // 📅 Filter by date (optional)
        if ($request->has('date') && !empty($request->date)) {
            $orders->whereBetween('orders.created_at', [
                $request->date . ' 00:00:00',
                $request->date . ' 23:59:59',
            ]);
        }

        // 🔸 Currency conversion logic
        $orders->when('orders.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0, function ($query) use ($current_currency) {
            $query->addSelect([
                DB::raw("ROUND(orders.discount / currencies.rate * {$current_currency->rate}, 2) as discount"),
                DB::raw("ROUND(orders.subtotal / currencies.rate * {$current_currency->rate}, 2) as subtotal"),
                DB::raw("ROUND(orders.shipping / currencies.rate * {$current_currency->rate}, 2) as shipping"),
                DB::raw("ROUND(orders.tax / currencies.rate * {$current_currency->rate}, 2) as tax"),
                DB::raw("ROUND(orders.total / currencies.rate * {$current_currency->rate}, 2) as total"),
                DB::raw("ROUND(orders.extradiscount / currencies.rate * {$current_currency->rate}, 2) as extradiscount"),
                DB::raw("ROUND(orders.paid / currencies.rate * {$current_currency->rate}, 2) as paid"),
                DB::raw("ROUND(orders.due / currencies.rate * {$current_currency->rate}, 2) as due"),
                DB::raw("'{$current_currency->symbol}' as symbol")
            ]);
        });

        // 🚀 Paginate result — this executes the query
        $orders = $orders->orderBy('orders.id', 'DESC')->paginate(20);

        // Keep filters on pagination links
        $orders->appends($request->all());

        $smsuse = AddonsExpired::where('store_id', $store_id)->where('addons_id', '5')->first();
        $courierInfo = Courier::where("store_id", $store_id)->get();
        $activeCourier = Courier::where("store_id", $store_id)->where("status", 1)->count();

        // 🧾 Return to view
        return view('admin.order.index')
            ->with('orders', $orders)
            ->with('urls', $urls)
            ->with('smsuse', $smsuse)
            ->with('activeCourier', $activeCourier)
            ->with('courierInfo', $courierInfo);
    }


    public function view($id)
    {
        $urls = "order";

        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        /*increment tools count*/
        topToolsCount('Order', "order.png", "/order");

        $store = Store::with('current_currency')->find($store_id);
        $current_currency = $store->current_currency;

        $order = Order::select("orders.*", 'currencies.symbol')
            ->join('currencies', 'orders.currency_id', '=', 'currencies.id')
            ->when(
                $store->currency !== null && $store->currency != 'orders.currency_id' && $current_currency->customize_rate_status === 0,
                function ($query) use ($current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(orders.discount / currencies.rate * " . $current_currency->rate . " , 2) as discount"),
                        DB::raw("ROUND(orders.subtotal / currencies.rate * " . $current_currency->rate . " , 2) as subtotal"),
                        DB::raw("ROUND(orders.shipping / currencies.rate * " . $current_currency->rate . " , 2) as shipping"),
                        DB::raw("ROUND(orders.tax / currencies.rate * " . $current_currency->rate . " , 2) as tax"),
                        DB::raw("ROUND(orders.total / currencies.rate * " . $current_currency->rate . " , 2) as total"),
                        DB::raw("ROUND(orders.extradiscount / currencies.rate * " . $current_currency->rate . " , 2) as extradiscount"),
                        DB::raw("ROUND(orders.paid / currencies.rate * " . $current_currency->rate . " , 2) as paid"),
                        DB::raw("ROUND(orders.due / currencies.rate * " . $current_currency->rate . " , 2) as due"),
                        DB::raw("'{$current_currency->symbol}' as symbol")
                    ]);
                }
            )
            ->when(
                $store->currency !== null && $store->currency != 'orders.currency_id' && $store->current_currency->customize_rate_status,
                function ($query) use ($store, $current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(orders.discount / {$store->currency_rate} , 2) as discount"),
                        DB::raw("ROUND(orders.subtotal / {$store->currency_rate} , 2) as subtotal"),
                        DB::raw("ROUND(orders.shipping / {$store->currency_rate} , 2) as shipping"),
                        DB::raw("ROUND(orders.tax / {$store->currency_rate} , 2) as tax"),
                        DB::raw("ROUND(orders.total / {$store->currency_rate} , 2) as total"),
                        DB::raw("ROUND(orders.extradiscount / {$store->currency_rate} , 2) as extradiscount"),
                        DB::raw("ROUND(orders.paid / {$store->currency_rate} , 2) as paid"),
                        DB::raw("ROUND(orders.due / {$store->currency_rate} , 2) as due"),
                        DB::raw("'{$current_currency->symbol}' as symbol")
                    ]);
                }
            )
            ->where('orders.store_id', $store_id)
            ->where('orders.id', $id)
            ->first();

        $trx = Transaction::where('order_id', $order->id)->first();
        $booking = Booking::where('order_id', $order->id)->where('store_id', $store_id)->first();
        $activity = " View Order " . $order->reference_no;
        $this->saveactivity($activity);

        $order->view = 1;
        $order->update();

        $courierInfo = Courier::where("store_id", $store_id)->get();
        $activeCourier = Courier::where("store_id", $store_id)->where("status", 1)->count();

        return view('admin.order.view')
            ->with('order', $order)
            ->with('urls', $urls)
            ->with('trx', $trx)
            ->with('booking', $booking)
            ->with('activeCourier', $activeCourier)
            ->with('courierInfo', $courierInfo);
    }

    public function getnotiorder(Request $request)
    {
        if (Auth::user()->type != 'admin') {
            $data[0] = false;
            $data[1] = false;
            return response()->json($data);
        }

        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin' || $user_type == 'dropshipper') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }

        $planorder = Order::where('store_id', $store_id)->where('view', null)->first();

        if (isset($planorder)) {
            $data[0] = true;
        } else {
            $data[0] = false;
        }

        return response()->json($data);
    }

    public function viewnotifi()
    {
        $customer = Order::where('view', null)->get();
        if (isset($customer) && count($customer) > 0) {
            foreach ($customer as $customers) {
                $custom = Order::find($customers->id);
                $custom->view = "1";
                $custom->save();
            }
            return redirect()->route('admin.order');
        }
    }

    public function exportorder(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;

        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
        }

        $fileName = 'order(' . $date . ').csv';

        $query = Order::where('store_id', $store_id);

        // Export selected ids if provided
        $ids = [];
        if ($request->filled('ids')) {
            $ids = array_values(array_filter(explode(',', $request->ids), function ($v) {
                return is_numeric($v);
            }));

            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        } else {
            // Apply filters if no selected rows
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('reference_no', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('date')) {
                $query->whereBetween('created_at', [
                    $request->date . ' 00:00:00',
                    $request->date . ' 23:59:59'
                ]);
            }

            if ($request->filled('type') && $request->type !== 'all') {
                if ($request->type === 'walking_customer') {
                    $query->where('type', 'walking_customer');
                } elseif ($request->type === 'website_customer') {
                    $query->where('type', 'customer');
                }
            }

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
        }

        $orders = $query->orderBy('id', 'DESC')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'Name',
            'Phone',
            'Email',
            'Address',
            'Reference No',
            'Subtotal',
            'Discount',
            'Shipping',
            'Tax',
            'Total',
            'Coupon',
            'Status',
            'Transaction Id',
            'Customer Type',
            'Created At'
        ];

        $callback = function () use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->name,
                    $order->phone,
                    $order->email,
                    $order->edited_address ?? $order->address ?? 'Not Available',
                    $order->reference_no,
                    $order->subtotal,
                    $order->discount,
                    $order->shipping,
                    $order->tax,
                    $order->total,
                    $order->coupon,
                    $order->status,
                    $order->transaction_id,
                    $order->type,
                    $order->created_at,
                ]);
            }

            fclose($file);
        };

        $activity = !empty($ids)
            ? "Export Selected Orders (" . count($ids) . ")"
            : "Export Filtered Orders";

        $this->saveactivity($activity);

        return response()->stream($callback, 200, $headers);
    }

    public function typefilter(Request $request)
    {
        $urls = "order";

        $userData = getUserData();
        $store_id = $userData['store_id'];

        $orderQuery = Order::where('store_id', $store_id);

        if ($request->type === 'walking_customer') {
            $orderQuery->where('type', 'walking_customer');
        } elseif ($request->type === 'website_customer') {
            $orderQuery->where('type', 'customer');
        }

        // Optimize date filtering using `whereBetween`
        if ($request->date) {
            $orderQuery->whereBetween('created_at', [
                Carbon::parse($request->date)->startOfDay(),
                Carbon::parse($request->date)->endOfDay()
            ]);
        }

        // Fetch paginated results
        $orders = $orderQuery->orderByDesc('id')->paginate(20);


        $type = $request->type;
        $activity = $request->filled('ids') ? "Export Selected Orders" : "Export All Orders";
        $this->saveactivity($activity);
        return view('admin.order.index')->with('orders', $orders)->with('urls', $urls)->with('type', $type);
    }

    public function changestatus(Request $request)
    {
        if ($request->text2 == "") {
            return back();
        } else {
            if ($request->type == 'all') {
                return back();
            } else {
                $ids = explode(',', $request->text2);
                foreach ($ids as $id) {
                    $order = Order::find($id);
                    $order->status = $request->type;
                    if ($request->type === "Delivered") {
                        $this->placeDropshipOrder($order);
                    }
                    $order->save();
                    $ordr[] = $order->reference_no;
                }
            }
        }
        $ooo = implode(',', $ordr);
        $activity = " Change Order Status" . $request->type . " For Order " . $ooo;
        $this->saveactivity($activity);
        return back();
    }

    public function placeDropshipOrder($order)
    {
        $store = Store::where('id', $order->store_id)->first();
        if (isset($store->order_pull) && $store->order_pull == 1) {
            AccountJournal::saveJournal($order); // Place dropshipper order in account journal
        }
    }

    public function filterstatus(Request $request)
    {
        $status = $request->status;

        $user = Auth::user()->id;
        $user_type = Auth::user()->type;

        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
        }

        $query = Order::where('store_id', $store_id)->orderBy('id', 'DESC');

        if ($status && $status != 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20);

        return view('admin.order.index', compact('orders', 'status'))
            ->with('stts', $status);
    }

    public function returned(Request $request)
    {
        if (!canAccess('returned_product')) {
            return redirect()->back();
        }

        $urls = "order";
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;

        if ($user_type == 'admin' || $user_type == 'dropshipper') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }

        $toptool = Toptool::where('name', 'Order')->where('uid', $user)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Order";
            $toptool->image = "order.png";
            $toptool->url = "/order";
            $toptool->count = "1";
            $toptool->uid = $user;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user;
            $toptool->editor = $user;
            $toptool->save();
        }

        $activity = "Access Returned/Cancelled/Restocked Order List Page";
        $this->saveactivity($activity);

        $query = Order::where('store_id', $store_id)
            ->whereIn('status', ['Returned', 'Cancelled', 'Restock']);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('reference_no', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereBetween('created_at', [
                $request->date . ' 00:00:00',
                $request->date . ' 23:59:59'
            ]);
        }

        // Type filter
        if ($request->filled('type') && $request->type !== 'all') {
            if ($request->type === 'walking_customer') {
                $query->where('type', 'walking_customer');
            } elseif ($request->type === 'website_customer') {
                $query->where('type', 'customer');
            }
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('id', 'DESC')->paginate(20);
        $orders->appends($request->all());

        return view('admin.order.returned', [
            'orders' => $orders,
            'urls' => $urls,
            'searchDate' => $request->date,
            'type' => $request->type,
            'status' => $request->status,
            'search' => $request->search,
        ]);
    }

    public function cancelled()
    {
        if (!canAccess('cancelled_product')) {
            return redirect()->back();
        }

        $user = Auth::user()->id;
        $user_type = Auth::user()->type;

        if ($user_type == 'admin' || $user_type == 'dropshipper') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }

        $toptool = Toptool::where('name', 'Order')->where('uid', $user)->where('store_id', $store_id)->first();
        if (isset($toptool)) {
            $toptool->count = $toptool->count + 1;
            $toptool->save();
        } else {
            $toptool = new Toptool();
            $toptool->name = "Order";
            $toptool->image = "order.png";
            $toptool->url = "/order";
            $toptool->count = "1";
            $toptool->uid = $user;
            $toptool->store_id = $store_id;
            $toptool->customer_id = $customer_id;
            $toptool->creator = $user;
            $toptool->editor = $user;
            $toptool->save();
        }

        $activity = " Access Cancelled Order List Page";
        $this->saveactivity($activity);

        return redirect()->route('admin.returned', [
            'status' => 'Cancelled'
        ]);
    }

    public function retypefilter(Request $request)
    {
        $params = [];

        if ($request->filled('date')) {
            $params['date'] = $request->date;
        }

        if ($request->filled('type')) {
            $params['type'] = $request->type;
        } else {
            $params['type'] = 'all';
        }

        if ($request->filled('status')) {
            $params['status'] = $request->status;
        } else {
            $params['status'] = 'all';
        }

        if ($request->filled('search')) {
            $params['search'] = $request->search;
        }

        $activity = " Filter Returned/Cancelled/Restocked Orders";
        $this->saveactivity($activity);

        return redirect()->route('admin.returned', $params);
    }

    public function restock($id)
    {
        $orderitem = Orderitem::where('order_id', $id)->get();

        if (isset($orderitem) && count($orderitem) > 0) {
            foreach ($orderitem as $oitm) {
                $product = Product::find($oitm->product_id);
                if (isset($product)) {
                    $product->quantity = $product->quantity + $oitm->quantity;
                    $product->save();
                }

                $variant = Veriant::find($oitm->variant_id);
                if (isset($variant)) {
                    $currentQty = is_numeric($variant->quantity) ? $variant->quantity : 0;
                    $variant->quantity = $currentQty + $oitm->quantity;
                    $variant->save();
                }
            }
        }

        $order = Order::find($id);
        if ($order) {
            $order->status = "Restock";
            $order->save();

            $activity = "Order Restock Completed For " . $order->reference_no;
            $this->saveactivity($activity);
        }

        Session()->flash('success_message', 'Product quantity restored successfully');

        return back();
    }

    public function invoice()
    {

        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        /*increment tools count*/
        topToolsCount('Invoice', "bill.png", "/invoice");

        $activity = " View Invoice";
        $this->saveactivity($activity);
        return view('admin.invoice.one');
    }

    public function createorder()
    {
        $urls = "order";
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin' || $user_type == 'dropshipper') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
        }
        return view('admin.order.create')->with('urls', $urls)->with('store_id', $store_id);
    }

    public function orderDetailsUpdate(Request $request)
    {
        try {
            $order_id = $request->order_id ?? "";

            if (isset($order_id) && !empty($order_id)) {
                $order = Order::where('id', $order_id)->first();

                if (isset($order)) {
                    if (isset($request->shipping) && !empty($request->shipping)) {
                        $prevShipping = (float) $order->shipping;
                        $prevTotal = (float) $order->total;
                        $prevPaid = (float) $order->paid;

                        $newShipping = (float) $request->shipping;
                        $newTotal = ($prevTotal - $prevShipping) + $newShipping;
                        $newDue = $newTotal - $prevPaid;

                        $order->shipping = $newShipping;
                        $order->total = $newTotal;
                        $order->due = $newDue;
                    }

                    if (isset($request->due_pay) && !empty($request->due_pay)) {
                        if ($request->due_pay > $order->total) {
                            \Illuminate\Support\Facades\Session::flash("error", "You can not pay extra amount");
                            return redirect()->back();
                        }

                        $prevTotal = (float) $order->total;
                        $prevPaid = (float) $order->paid;

                        $newPaid = $prevPaid + (float) $request->due_pay;
                        $newDue = $prevTotal - $newPaid;

                        $order->paid = $newPaid;
                        $order->due = $newDue;

                    }

                    $order->save();

                    \Illuminate\Support\Facades\Session::flash("success", "Order details updated successfully");
                    return redirect()->back();
                }

                \Illuminate\Support\Facades\Session::flash("error", "Order ID missing");
                return redirect()->back();
            }

            \Illuminate\Support\Facades\Session::flash("error", "Order ID missing");
            return redirect()->back();
        } catch (\Exception $e) {
            return view("error");
        }
    }

    /**
     * Update order address
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function orderAddressUpdate(Request $request)
    {
        try {
            $order_id = $request->order_id ?? "";

            if (isset($order_id) && !empty($order_id)) {
                $order = Order::where('id', $order_id)->first();

                if (isset($order)) {
                    $order->edited_address = $request->edited_address;
                    $order->save();

                    \Illuminate\Support\Facades\Session::flash("success", "Order address updated successfully");
                    return redirect()->back();
                }

                \Illuminate\Support\Facades\Session::flash("error", "Order ID missing");
                return redirect()->back();
            }

            \Illuminate\Support\Facades\Session::flash("error", "Order ID missing");
            return redirect()->back();
        } catch (\Exception $e) {
            return view("error");
        }
    }


    /**
     * Order assign to staff
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignStaffOrder(Request $request)
    {
        $staff_id = $request->staff_id ?? "";

        if (empty($staff_id)) {
            \Illuminate\Support\Facades\Session::flash("error", "Staff ID missing");

            return redirect()->back();
        } else {
            $ids = explode(',', $request->order_ids);

            $ids = array_filter($ids);

            if (count($ids) > 0) {
                foreach ($ids as $id) {
                    $order = Order::find($id);
                    if ($order) {
                        $order->staff_id = $staff_id;
                        $order->save();
                    }
                }

                \Illuminate\Support\Facades\Session::flash("success", "Order assign successfully");

                return redirect()->back();
            } else {
                \Illuminate\Support\Facades\Session::flash("error", "Please select order item");

                return redirect()->back();
            }
        }

    }


    public function checkCourierStatus($phone)
    {
        try {

            $cacheKey = "courier_data_{$phone}";

            // Check cache first
            if (Cache::has($cacheKey)) {
                $response = Cache::get($cacheKey);
            } else {
                $apiResponse = BdCourierFraudChecker::check($phone);

                $allStatusTrue = collect($apiResponse)->every(fn($item) => isset($item['status']) && $item['status'] == true);

                if ($allStatusTrue) {
                    // Store only if all courier statuses are true
                    Cache::put($cacheKey, $apiResponse, now()->addDays(3));
                }

                $response = $apiResponse;
            }

            $defaultData = [
                'success' => 0,
                'cancel' => 0,
                'total' => 0,
                'deliveredPercentage' => 0,
                'returnPercentage' => 0,
            ];

            // Normalize response data for all couriers
            foreach ($response as $courier => $courierData) {
                $status = $courierData['status'];
                $message = $courierData['message'];
                $data = $courierData['data'] ?? [];

                // Always merge with default data, whether status is true or false
                $response[$courier] = [
                    'status' => $status,
                    'message' => $message,
                    'data' => array_merge($defaultData, $data),
                ];
            }

            return sendResponse("Success", $response);
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            return sendError($message);
        }
    }


    public function updateComment(Request $request, Order $order)
    {
        $request->validate([
            'order_comment' => 'nullable|string|max:1000',
        ]);


        $order->order_comment = $request->order_comment;
        $order->save();

        return sendResponse("Comment updated successfully.");
    }


}
