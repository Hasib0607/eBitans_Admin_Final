<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Branchproduct;
use App\Models\Customer;
use App\Models\Design;
use App\Models\Headersetting;
use App\Models\Holdorder;
use App\Models\Holdorderitem;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Product;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Toptool;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Veriant;
use Auth;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;

class PosController extends Controller
{
    public function index()
    {
        // dd(Cart::instance('cart'));
        $urls = "pos";

        $products = Product::all();
        return view('admin.pos.index')->with('products', $products)->with('urls', $urls);
    }

    public function searchproductss(Request $request)
    {
        $product = Branchproduct::where('branch_id', $request->branch)->get();
        if (isset($product) && count($product) > 0) {
            foreach ($product as $key => $pps) {
                if ($request->search == '') {
                    $pp = Product::where('id', $pps->product_id)->first();
                } else {
                    $pp = Product::where('id', $pps->product_id)->where('barcode', $request->search)->first();
                    if ($pp == null) {
                        $pp = Product::where('id', $pps->product_id)->Where(
                            'name',
                            'LIKE',
                            '%' . $request->search . '%'
                        )->first();
                    }
                }
                if (isset($pp)) {
                    if ($pp->images) {
                        $img = explode(',', $pp->images);
                        $data[$key]['image'] = $img[1];
                    }
                    $data[$key]['id'] = $pp->id;
                    $data[$key]['name'] = $pp->name;
                    $data[$key]['regular_price'] = $pp->regular_price;
                }
                $pp = null;
            }
        } else {
            $data = [];
        }
        return response()->json($data);
    }

    public function addcart(Request $request)
    {
        $id = $request->product_id;
        $product = Product::find($id);
        if ($product->discount_type == "fixed") {
            $price = $product->regular_price - $product->promotional_price;
            $discount = $product->promotional_price;
        } elseif ($product->discount_type == "percent") {
            $price = $product->regular_price - ($product->promotional_price / 100) * $product->regular_price;
            $discount = ($product->promotional_price / 100) * $product->regular_price;
        } else {
            $price = $product->regular_price;
            $discount = "0";
        }
        Cart::instance('cart')->add(
            $product->id,
            $product->name,
            1,
            $price,
            ['discount' => $discount]
        )->associate('App\Models\Product');
        $data = $id;
        return response()->json($data);
    }

    public function addveriantcart(Request $request)
    {
        $id = $request->veriant_id;
        $veriant = Veriant::find($id);
        $product = Product::find($veriant->pid);
        if ($product->discount_type == "fixed") {
            $price = $product->regular_price - $product->promotional_price;
            $discount = $product->promotional_price;
        } elseif ($product->discount_type == "percent") {
            $price = $product->regular_price - ($product->promotional_price / 100) * $product->regular_price;
            $discount = ($product->promotional_price / 100) * $product->regular_price;
        } else {
            $price = $product->regular_price;
            $discount = "0";
        }
        $price = $price + $veriant->additional_price;
        Cart::instance('cart')->add($product->id, $product->name, 1, $price, [
            'discount' => $discount,
            'color' => $veriant->color,
            'size' => $veriant->size,
            'volume' => $veriant->volume,
            'unit' => $veriant->unit,
            'additional_price' => $veriant->additional_price
        ])->associate('App\Models\Product');
        $data = $id;
        return response()->json($data);
    }

    public function incrementcart(Request $request)
    {
        $id = $request->cart_id;
        $product = Cart::instance('cart')->get($id);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($id, $qty);
        // Toastr()->success('Cart Update Successfully');
        $data = $id;
        return response()->json($data);
    }

    public function decrementcart(Request $request)
    {
        $id = $request->cart_id;
        $product = Cart::instance('cart')->get($id);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($id, $qty);
        // Toastr()->success('Cart Update Successfully');
        $data = $id;
        return response()->json($data);
    }

    public function removecart(Request $request)
    {
        $data = $request->cart_id;
        Cart::instance('cart')->remove($data);
        return response()->json($data);
    }

    public function searchcustomer(Request $request)
    {
        $phone = $request->mobile;
        $data = User::where('phone', $phone)->first();
        return response()->json($data);
    }

    public function savecustomer(Request $request)
    {
        $phone = $request->phone;
        $name = $request->name;
        $email = $request->email;
        $address = $request->address;
        $user = User::where('phone', $phone)->first();
        if (isset($user)) {
            if (!isset($user->name)) {
                $user->name = $name;
            }
            if (!isset($user->email)) {
                $user->email = $email;
            }
            $user->save();
            Session::put('customer_id', $user->id);
            Session::put('customer_phone', $user->phone);
            Session::put('customer_name', $user->name);
            Session::put('customer_email', $user->email);
        } else {
            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            $user->password = Hash::make("12345678");
            $user->type = "walking_customer";
            $user->otp = "1234";
            $user->save();

            Session::put('customer_id', $user->id);
            Session::put('customer_phone', $user->phone);
            Session::put('customer_name', $user->name);
            Session::put('customer_email', $user->email);
        }
        $data = 1;
        return response()->json($data);
    }

    public function placeorder(Request $request)
    {
        $order = new Order();
        $order->uid = Session::get('customer_id');
        $order->subtotal = Cart::instance('cart')->subtotal();
        $order->tax = $request->totaltax;
        $order->shipping = $request->totalshipping;
        $order->discount = $request->totaldiscount;
        $order->total = $request->totalamount;
        $digit = substr(str_shuffle("0123456789"), 0, 4);
        $order->reference_no = "BN" . $digit;
        $order->name = Session::get('customer_name');
        $order->phone = Session::get('customer_phone');
        $order->email = Session::get('customer_email');
        $order->address = "NULL";
        $order->note = $request->note;
        $order->status = "Pending";
        $order->creator = Auth::user()->id;
        $order->editor = Auth::user()->id;
        $order->branch_id = $request->branch_id;
        $b = Branch::find($request->branch_id);

        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }

        $store = Store::findOrFail($store_id);
        $order->currency_id = $store->currency;

        // $customer=Customer::where('id',$b->customer_id)->first();
        $order->customer_id = $customer_id;
        $order->store_id = $store_id;
        $order->type = "walking_customer";
        $order->save();
        foreach (Cart::instance('cart')->content() as $key => $item) {
            $orderItem = new Orderitem();
            $orderItem->product_id = $item->model->id;
            $orderItem->order_id = $order->id;
            $orderItem->currency_id = $store->currency;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->color = $item->options->color ?? null;
            $orderItem->size = $item->options->size ?? null;
            $orderItem->volume = $item->options->volume ?? null;
            $orderItem->unit = $item->options->unit ?? null;
            $orderItem->additional_price = $item->options->additional_price ?? null;
            $orderItem->save();
        }

        if ($request->payment_type == 'cod') {
            $transaction = new Transaction();
            $transaction->uid = Session::get('customer_id');
            $transaction->order_id = $order->id;
            $transaction->mode = $request->payment_type;
            $transaction->status = "pending";
            $transaction->save();
        } elseif ($request->payment_type == 'online') {
            $transaction = new Transaction();
            $transaction->uid = Session::get('customer_id');
            $transaction->order_id = $order->id;
            $transaction->mode = $request->payment_type;
            $transaction->status = "pending";
            $transaction->save();
        }
        $invoice = new Invoice;
        $di = substr(str_shuffle("0123456789"), 0, 4);
        $invoice->reference_no = "INV" . $di;
        $invoice->order_id = $order->id;
        $invoice->type = "POS";
        $invoice->uid = $user;
        $invoice->customer_id = $customer_id;
        $invoice->store_id = $store_id;
        $invoice->creator = $user;
        $invoice->editor = $user;
        $invoice->save();

        Cart::instance('cart')->destroy();
        Session::forget('customer_id');
        Session::forget('customer_name');
        Session::forget('customer_phone');
        Session::forget('customer_email');
        return back();
    }

    public function savehold(Request $request)
    {
        if (Cart::instance('cart')->count() < 1) {
            return back();
        }
        $order = new Holdorder;
        $order->order_id = $request->order_id;
        $digits = substr(str_shuffle("0123456789"), 0, 4);
        $order->oids = "HR" . $digits;
        $order->uid = Session::get('customer_id');
        $order->subtotal = Cart::instance('cart')->subtotal();
        $order->discount = $request->totaldiscount1;
        $order->tax = $request->totaltax1;
        $order->shipping = $request->totalshipping1;
        $order->other_charge = $request->totalothercharge1;
        $order->payable_amount = $request->total123;
        $order->save();
        foreach (Cart::instance('cart')->content() as $key => $item) {
            $orderItem = new Holdorderitem();
            $orderItem->oid = $order->id;
            $orderItem->pid = $item->model->id;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }
        Cart::instance('cart')->destroy();
        Session::forget('customer_id');
        Session::forget('customer_name');
        Session::forget('customer_phone');
        Session::forget('customer_email');
        return back();
    }

    public function holdorderdetails(Request $request)
    {
        $data = $request->order_id;
        $order = Holdorder::find($data);
        if (isset($order->uid)) {
            $user = User::find($order->uid);
            $name = $user->name;
            $phone = $user->phone;
        } else {
            $name = "Walking Customer";
            $phone = "0859437654";
        }
        $data2 = "<thead>
                    <tr>
                        <th>" . $name . "</th>
                        <th>" . $phone . "</th>
                    </tr>
                </thead>";
        $data = array();
        $orderitem = Holdorderitem::where('oid', $order->id)->get();
        foreach ($orderitem as $oitm) {
            $product = Product::find($oitm->pid);
            if ($product->discount_type == "fixed") {
                $price = $product->regular_price - $product->promotional_price;
            } elseif ($product->discount_type == "percent") {
                $price = $product->regular_price - ($product->promotional_price / 100) * $product->regular_price;
            } else {
                $price = $product->regular_price;
            }
            $data[] = "<tr>
                        <th width='80%'>" . $product->name . "</th>
                        <th width='20%' style='text-align:end'>" . $price . "</th>
                    </tr>";
        }
        $data1 = "<tr>
                    <td style='text-align:end;padding:0.15rem 1.5rem'>Subtotal</td>
                    <td style='text-align:end;padding:0.15rem 1.5rem'>" . $order->subtotal . "</td>
                </tr>
                <tr>
                    <td style='text-align:end;padding:0.15rem 1.5rem'>Discount</td>
                    <td style='text-align:end;padding:0.15rem 1.5rem' id='orderdiscount'>" . $order->discount . "</td>
                </tr>
                <tr>
                    <td style='text-align:end;padding:0.15rem 1.5rem'>Tax</td>
                    <td style='text-align:end;padding:0.15rem 1.5rem' id='ordertax'>" . $order->tax . "</td>
                </tr>
                <tr>
                    <td style='text-align:end;padding:0.15rem 1.5rem'>Shipping</td>
                    <td style='text-align:end;padding:0.15rem 1.5rem' id='ordershipping'>" . $order->shipping . "</td>
                </tr>
                <tr>
                    <td style='text-align:end;padding:0.15rem 1.5rem'>Other Charge</td>
                    <td style='text-align:end;padding:0.15rem 1.5rem' id='orderothercharge'>" . $order->other_charge . "</td>
                </tr>
                <tr>
                    <td style='text-align:end;padding:0.15rem 1.5rem'>Total</td>
                    <td style='text-align:end;padding:0.15rem 1.5rem' id='ordertotal'>" . $order->payable_amount . "</td>
                </tr>";
        $data3 = "<a href='/editholdorder/" . $order->id . "' class='btn btn-primary' >Edt Order</a>";
        $data = [$data2, $data, $data1, $data3];
        return response()->json($data);
    }

    public function deleteholdorder($id)
    {
        $order = Holdorder::find($id);
        $orderitem = Holdorderitem::where('oid', $order->id)->get();
        foreach ($orderitem as $oitm) {
            $o = Holdorderitem::find($oitm->id);
            $o->delete();
        }
        $order->delete();
        return back();
    }

    public function editholdorder($id)
    {
        Session::forget('customer_id');
        Session::forget('customer_name');
        Session::forget('customer_phone');
        Session::forget('customer_email');
        Session::forget('tax');
        Session::forget('shipping');
        Session::forget('other_charge');
        Session::forget('payableamount');
        Cart::instance('cart')->destroy();
        $order = Holdorder::find($id);
        $orderdetails = Holdorderitem::where('oid', $order->id)->get();
        foreach ($orderdetails as $odts) {
            $product = Product::find($odts->pid);
            if ($product->discount_type == "fixed") {
                $price = $product->regular_price - $product->promotional_price;
            } elseif ($product->discount_type == "percent") {
                $price = $product->regular_price - ($product->promotional_price / 100) * $product->regular_price;
            } else {
                $price = $product->regular_price;
            }
            $discount = $order->discount;
            Cart::instance('cart')->add(
                $product->id,
                $product->name,
                $odts->quantity,
                $price,
                ['discount' => $discount]
            )->associate('App\Models\Product');
        }
        Session::put('tax', $order->tax);
        Session::put('shipping', $order->shipping);
        Session::put('other_charge', $order->other_charge);
        Session::put('payableamount', $order->payable_amount);
        if (isset($order->uid)) {
            $user = User::find($order->uid);
            Session::put('customer_id', $user->id);
            Session::put('customer_phone', $user->phone);
            Session::put('customer_name', $user->name);
            Session::put('customer_email', $user->email);
        }
        foreach ($orderdetails as $odts) {
            $orde = Holdorderitem::where('id', $odts->id)->first();
            $orde->delete();
        }
        $order->delete();
        return back();
    }

    // public function invoice()
    // {
    //     if (!canAccess('invoice')) {
    //         return redirect()->back();
    //     }
    //     $invs = $this->checkrole();
    //     if (isset($invs) && $invs == "1" || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
    //         $urls = "order";
    //         $user = Auth::user()->id;
    //         $user_type = Auth::user()->type;
    //         if ($user_type == 'admin' || $user_type == 'dropshipper') {
    //             $customer = Customer::where('uid', $user)->first();
    //             $store_id = $customer->active_store;
    //             $customer_id = $customer->id;
    //         } elseif ($user_type == 'staff') {
    //             $staff = Staff::where('uid', $user)->first();
    //             $store_id = $staff->store_id;
    //             $customer_id = $staff->customer_id;
    //         }
    //         $toptool = Toptool::where('name', 'Invoice')->where('uid', $user)->where('store_id',
    //             $store_id)->first();
    //         if (isset($toptool)) {
    //             $toptool->count = $toptool->count + 1;
    //             $toptool->save();
    //         } else {
    //             $toptool = new Toptool();
    //             $toptool->name = "Invoice";
    //             $toptool->image = "bill-2.png";
    //             $toptool->url = "/invoice";
    //             $toptool->count = "1";
    //             $toptool->uid = $user;
    //             $toptool->store_id = $store_id;
    //             $toptool->customer_id = $customer_id;
    //             $toptool->creator = $user;
    //             $toptool->editor = $user;
    //             $toptool->save();
    //         }
    //         $invoice = Invoice::where('store_id', $store_id)->orderBy('id', 'DESC')->get();
    //         return view('admin.invoice.index')->with('urls', $urls)->with('invoices', $invoice);
    //     }
    // }


    public function invoice(Request $request)
    {
        if (!canAccess('invoice')) {
            return redirect()->back();
        }

        $invs = $this->checkrole();

        if ((isset($invs) && $invs == "1") || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {

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

            // Toptool (unchanged)
            $toptool = Toptool::where('name', 'Invoice')
                ->where('uid', $user)
                ->where('store_id', $store_id)
                ->first();

            if (isset($toptool)) {
                $toptool->count = $toptool->count + 1;
                $toptool->save();
            } else {
                $toptool = new Toptool();
                $toptool->name = "Invoice";
                $toptool->image = "bill-2.png";
                $toptool->url = "/invoice";
                $toptool->count = "1";
                $toptool->uid = $user;
                $toptool->store_id = $store_id;
                $toptool->customer_id = $customer_id;
                $toptool->creator = $user;
                $toptool->editor = $user;
                $toptool->save();
            }

            // ✅ Server-side search + DB pagination (20 per page)
            $q = trim((string) $request->get('q', ''));

            $invoiceQuery = Invoice::with(['orders'])
                ->where('store_id', $store_id)
                ->orderBy('id', 'DESC');

            if ($q !== '') {
                $invoiceQuery->where(function ($query) use ($q) {
                    $query->where('reference_no', 'like', "%{$q}%")
                        ->orWhere('type', 'like', "%{$q}%")
                        // If your invoice table has order_id column and you want search by it too:
                        ->orWhere('order_id', 'like', "%{$q}%")
                        // ✅ Search by Order reference no (the Order Id you show)
                        ->orWhereHas('orders', function ($oq) use ($q) {
                            $oq->where('reference_no', 'like', "%{$q}%");
                        });
                });
            }

            $invoices = $invoiceQuery->paginate(20)->appends(['q' => $q]);

            return view('admin.invoice.index', compact('urls', 'invoices', 'q'));
        }

        return redirect()->back();
    }

    //multi invoice print
    public function printSelected(Request $request)
    {
        $ids = array_filter(explode(',', (string) $request->ids));
        $source = $request->get('source', 'invoice');

        $query = \App\Models\Invoice::with('orders');

        if ($source === 'order') {
            $query->whereHas('orders', function ($q) use ($ids) {
                $q->whereIn('id', $ids);
            });
        } else {
            $query->whereIn('id', $ids);
        }

        $invoices = $query->get();

        if ($invoices->isEmpty()) {
            return redirect()->back()->with('error_message', 'No invoices found.');
        }

        return view('admin.invoice.print_selected', compact('invoices'));
    }


    public function checkrole()
    {

        if (Auth::user()->type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
            $role = Role::where('id', $staff->role_id)->first();
            if (isset($role)) {

                $permission = explode(',', $role->permission);
                foreach ($permission as $key => $pr) {
                    if ($pr == 'branch') {
                        $branch = 1;
                    } elseif ($pr == 'product') {
                        $product = 1;
                    } elseif ($pr == 'category') {
                        $category = 1;
                    } elseif ($pr == 'subcategory') {
                        $subcategory = 1;
                    } elseif ($pr == 'brand') {
                        $brand = 1;
                    } elseif ($pr == 'attribute') {
                        $attribute = 1;
                    } elseif ($pr == 'supplier') {
                        $supplier = 1;
                    } elseif ($pr == 'collection') {
                        $collection = 1;
                    } elseif ($pr == 'global_tab') {
                        $global_tab = 1;
                    } elseif ($pr == 'coupon') {
                        $coupon = 1;
                    } elseif ($pr == 'campaign') {
                        $campaign = 1;
                    } elseif ($pr == 'offer') {
                        $offer = 1;
                    } elseif ($pr == 'slider') {
                        $slider = 1;
                    } elseif ($pr == 'banner') {
                        $banner = 1;
                    } elseif ($pr == 'layouts') {
                        $layouts = 1;
                    } elseif ($pr == 'template') {
                        $template = 1;
                    } elseif ($pr == 'header') {
                        $header = 1;
                    } elseif ($pr == 'homepage') {
                        $homepage = 1;
                    } elseif ($pr == 'footer') {
                        $footer = 1;
                    } elseif ($pr == 'mobilemenu') {
                        $mobilemenu = 1;
                    } elseif ($pr == 'product_display') {
                        $product_display = 1;
                    } elseif ($pr == 'product_grid') {
                        $product_grid = 1;
                    } elseif ($pr == 'shop_page') {
                        $shop_page = 1;
                    } elseif ($pr == 'pages') {
                        $pages = 1;
                    } elseif ($pr == 'customer') {
                        $customer = 1;
                    } elseif ($pr == 'staff') {
                        $staff = 1;
                    } elseif ($pr == 'invoice') {
                        $invoice = 1;
                        return $invoice;
                    } elseif ($pr == 'setting') {
                        $setting = 1;
                    } elseif ($pr == 'role_permission') {
                        $role_permission = 1;
                    } elseif ($pr == 'pos') {
                        $pos = 1;
                    } else {
                    }
                }
            }
        }
    }

    public function invoiceexport(Request $request)
    {
        $date = Carbon::now();
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == "admin" || $user_type == "dropshipper") {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', Auth::user()->id)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }
        $fileName = 'invoice(' . $date . ').csv';
        $coupon = Invoice::where('store_id', $store_id)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Reference No', 'Order No', 'Type', 'Created_at');

        $callback = function () use ($coupon, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($coupon as $cat) {
                $row['Reference No'] = $cat->reference_no;
                $row['Order No'] = $cat->order_id;
                $row['Type'] = $cat->type;
                $row['Create Date'] = $cat->created_at;

                fputcsv($file, array($row['Reference No'], $row['Order No'], $row['Type'], $row['Create Date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function invoiceview($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $store = Store::with('current_currency')->find($store_id);
        $current_currency = $store->current_currency;

        $id = decrypt($id);

        $invoice = Invoice::where('id', $id)->first();
        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice not found!');
        }

        $stor = Store::with('current_currency')->find($invoice->store_id);

        // ✅ design can be null
        $design = Design::where('store_id', $stor->id)->first();

        // ✅ invoice design name
        $selectedInvoice = $design->invoice ?? null;

        // ✅ if no invoice selected (or invalid selections)
        if (empty($selectedInvoice) || in_array($selectedInvoice, ['default', 'five'])) {
            return redirect()->back()->with('invoice_not_selected', 1);
        }

        $order = Order::select('orders.*', 'currencies.symbol', 'currencies.code')
            ->join('currencies', 'orders.currency_id', '=', 'currencies.id')
            ->when(
                'orders.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                function ($query) use ($current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(orders.subtotal / currencies.rate * " . $current_currency->rate . " , 2) as subtotal"),
                        DB::raw("ROUND(orders.discount / currencies.rate * " . $current_currency->rate . " , 2) as discount"),
                        DB::raw("ROUND(orders.shipping / currencies.rate * " . $current_currency->rate . " , 2) as shipping"),
                        DB::raw("ROUND(orders.tax / currencies.rate * " . $current_currency->rate . " , 2) as tax"),
                        DB::raw("ROUND(orders.total / currencies.rate * " . $current_currency->rate . " , 2) as total"),
                        DB::raw("ROUND(orders.extradiscount / currencies.rate * " . $current_currency->rate . " , 2) as extradiscount"),
                        DB::raw("ROUND(orders.paid / currencies.rate * " . $current_currency->rate . " , 2) as paid"),
                        DB::raw("ROUND(orders.due / currencies.rate * " . $current_currency->rate . " , 2) as due"),
                        DB::raw("'{$current_currency->symbol}' as symbol"),
                        DB::raw("'{$current_currency->code}' as code")
                    ]);
                }
            )
            ->when(
                'orders.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                function ($query) use ($store, $current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(orders.subtotal / {$store->currency_rate}, 2) as subtotal"),
                        DB::raw("ROUND(orders.discount / {$store->currency_rate}, 2) as discount"),
                        DB::raw("ROUND(orders.shipping / {$store->currency_rate}, 2) as shipping"),
                        DB::raw("ROUND(orders.tax / {$store->currency_rate}, 2) as tax"),
                        DB::raw("ROUND(orders.total / {$store->currency_rate}, 2) as total"),
                        DB::raw("ROUND(orders.extradiscount / {$store->currency_rate}, 2) as extradiscount"),
                        DB::raw("ROUND(orders.paid / {$store->currency_rate}, 2) as paid"),
                        DB::raw("ROUND(orders.due / {$store->currency_rate}, 2) as due"),
                        DB::raw("'{$current_currency->symbol}' as symbol"),
                        DB::raw("'{$current_currency->code}' as code")
                    ]);
                }
            )
            ->find($invoice->order_id);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found for this invoice!');
        }

        $payment = Transaction::where('order_id', $order->id)->first();
        $transaction = Transaction::where('order_id', $order->id)->first();
        $hs = Headersetting::convertCurrency($invoice->store_id)->first();
        $store = Store::find($order->store_id);

        $orderitems = Orderitem::select('orderitems.*', 'products.name', 'currencies.symbol', 'currencies.code', 'colors.name as color_name')
            ->leftJoin('products', function ($join) {
                $join->on('products.id', 'orderitems.product_id');
            })
            ->join('currencies', 'orderitems.currency_id', '=', 'currencies.id')
            ->leftJoin('colors', function ($join) use ($store_id) {
                $join->on('orderitems.color', '=', 'colors.code')
                    ->where('colors.store_id', '=', $store_id);
            })
            ->where('order_id', $order->id)
            ->when(
                'orderitems.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                function ($query) use ($current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(orderitems.price / currencies.rate * " . $current_currency->rate . " , 2) as price"),
                        DB::raw("ROUND(orderitems.additional_price / currencies.rate * " . $current_currency->rate . " , 2) as additional_price"),
                        DB::raw("ROUND(orderitems.discount / currencies.rate * " . $current_currency->rate . " , 2) as discount"),
                        DB::raw("ROUND(orderitems.cost / currencies.rate * " . $current_currency->rate . " , 2) as cost"),
                        DB::raw("'{$current_currency->symbol}' as symbol"),
                        DB::raw("'{$current_currency->code}' as code")
                    ]);
                }
            )
            ->when(
                'orderitems.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                function ($query) use ($store, $current_currency) {
                    $query->addSelect([
                        DB::raw("ROUND(orderitems.price / {$store->currency_rate}, 2) as price"),
                        DB::raw("ROUND(orderitems.additional_price / {$store->currency_rate}, 2) as additional_price"),
                        DB::raw("ROUND(orderitems.discount / {$store->currency_rate}, 2) as discount"),
                        DB::raw("ROUND(orderitems.cost / {$store->currency_rate}, 2) as cost"),
                        DB::raw("'{$current_currency->symbol}' as symbol"),
                        DB::raw("'{$current_currency->code}' as code")
                    ]);
                }
            )
            ->get();

        // ✅ protect missing blade file too
        $viewName = "admin.invoice." . $selectedInvoice;
        if (!view()->exists($viewName)) {
            return redirect()->back()->with('invoice_not_selected', 1);
        }

        return view($viewName)
            ->with('invoice', $invoice)
            ->with('order', $order)
            ->with('hs', $hs)
            ->with('payment', $payment)
            ->with('orderitems', $orderitems)
            ->with('transaction', $transaction)
            ->with('store', $store);
    }
}
