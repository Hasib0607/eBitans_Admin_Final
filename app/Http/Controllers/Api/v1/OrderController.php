<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\CheckClientSms;
use App\Http\Controllers\Controller;
use App\Models\AccountJournal;
use App\Models\Booking;
use App\Models\Currency;
use App\Models\Design;
use App\Models\Headersetting;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Product;
use App\Models\ProductAffiliateCommission;
use App\Models\ProductAffiliateInfo;
use App\Models\Review;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Veriant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function placeorder(Request $request)
    {
        $store = Store::find($request->store_id);

        if (!isset($store)) {
            return response()->json(['error' => "Invalid request"]);
        }

        if (isset($store->plan_id) && in_array($store->plan_id, [6, 9])) {
            return response()->json(['error' => "Order access denied"]);
        }

        if (!isset($store->currency)) {
            $store->currency = 1;
        }

        foreach ($request->product as $key => $item) {
            $variantQuery = Veriant::convertCurrency($item['id'], $request->store_id)->where('pid', $item['id']);

            if (isset($item['color']) && !empty($item['color']) && $item['color'] != 'null') {
                $variantQuery->where('veriants.color', $item['color']);
            }
            if (isset($item['size']) && !empty($item['size']) && $item['size'] != 'null') {
                $variantQuery->where('veriants.size', $item['size']);
            }
            if (isset($item['volume']) && !empty($item['volume']) && $item['volume'] != 'null') {
                $variantQuery->where('veriants.volume', $item['volume']);
            }
            if (isset($item['unit']) && !empty($item['unit']) && $item['unit'] != 'null') {
                $variantQuery->where('veriants.unit', $item['unit']);
            }

            $variant = $variantQuery->first();

            if (isset($variant)) {
                if ($variant->quantity < $item['quantity']) {
                    $product = Product::where('id', $item['id'])->first();
                    $er = "Quantity not exist for " . $product->name;
                    return response()->json(['error' => $er]);
                }
            } else {
                $product = Product::where('id', $item['id'])->first();
                if (isset($product)) {
                    if ($product->quantity < $item['quantity']) {
                        $er = "Quantity not exist for " . $product->name;
                        return response()->json(['error' => $er]);
                    }
                } else {
                    return response()->json(['error' => "Some product not found. Please check every product!"]);
                }
            }
        }

        $user = User::where('id', Auth::user()->id)->where('store_id', $request->store_id)->first();

        $phone = !empty($request->phone) ? $request->phone : null;
        $email = !empty($request->email) ? strtolower($request->email) : null;

        if (!isset($user) || is_null($user)) {
            $user = User::where('store_id', $request->store_id)
                ->where(function ($q) use ($phone, $email) {
                    if ($phone) {
                        $q->where('phone', '=', $phone);
                    }
                    if ($email) {
                        $q->orWhere('email', '=', strtolower($email)); // case-insensitive
                    }
                })
                ->first();
        }

        if (isset($user)) {
            $uid = $user->id;
        } else {
            $store = Store::find($request->store_id);
            $user = new User;
            $user->phone = $request->phone;
            $user->currency_id = $store->currency;
            $code = sixDigitRandCode();
            $pass = $store->name . "@" . $code;
            $newpass = Hash::make($pass);
            $user->password = $newpass;
            $user->type = "customer";
            $otp = sixDigitRandCode();
            $user->otp = $otp;
            $user->store_id = $store->id;
            $user->customer_id = $store->customer_id;
            $user->save();

            $notificationData = [
                "title" => "New customer register (" . ($user->phone ?? '') . ") - " . formatDateWithTime($user->created_at),
                "type" => "user_create",
                "user_type" => "admin",
                "store_id" => $store->id ?? NULL,
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }

            $text = ($store->name ?? "Your") . " OTP code is " . $user->otp;

            // Create an instance of CheckClientSms
            $smsChecker = new CheckClientSms($store->id, 5);


            if (addonSmsCount($store->id) && isset($user->phone) && !empty($user->phone)) {
                // Check the SMS limit
                $isLimitReached = $smsChecker->checkSmsLimit();

                // Send SMS if the limit not 0
                if ($isLimitReached) {
                    $smsresult = SendSms($user->phone, $text); // phone text
                    $p = explode("|", $smsresult);
                    $sendstatus = $p[0];

                    smsLogger($user->phone, $text, "OTP Send", 0, $store->id);
                }
            }

            $text = "Thank You for register to " . $store->name .
                "  Your Login Details is
                Phone : " . $user->phone .
                " Password : " . $pass;

            if (addonSmsCount($store->id) && isset($user->phone) && !empty($user->phone)) {
                // Check the SMS limit
                $isLimitReached = $smsChecker->checkSmsLimit();

                // Send SMS if the limit not 0
                if ($isLimitReached) {
                    $smsresult = SendSms($user->phone, $text); // phone text
                    $p = explode("|", $smsresult);
                    $sendstatus = $p[0];

                    smsLogger($user->phone, $text, "Customer Registration Details", 0, $store->id);
                }
            }

            $uid = $user->id;
        }

        $lastOrder = Order::where('store_id', $store->id)->latest('order_no')->first();
        $newOrderNo = $lastOrder ? $lastOrder->order_no + 1 : 1;

        $order = new Order();
        $current_currency = Currency::find($store->currency);
        $order->uid = $uid;
        $order->subtotal = $request->subtotal;
        $order->tax = $request->tax;
        $order->currency_id = $store->currency;
        $order->shipping = $request->shipping;
        $order->discount = $request->discount;
        $order->due = $request->total;
        $order->total = $request->total;
        $order->reference_no = generateShortReferenceNo();
        $order->order_no = $newOrderNo;
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->email = $request->email;
        $order->address = $request->address ?? "";
        $order->note = $request->note;
        $order->district = $request->district ?? NULL;
        $order->address_id = $request->address_id ?? NULL;

        $sessionId = $request->header('X-Session-ID') ?? NULL;
        $ip = $request->ip();
        $order->session_id = $sessionId;
        $order->ip = $ip;

        if (ModulusStatus($store->id, 108)) {
            if ($request->from_type == 1 || $request->from_type == 0) {
                $order->status = "Booked";
            }
        } else {
            $order->status = "Pending";
        }

        $order->creator = $uid;
        $order->editor = $uid;
        $order->customer_id = $store->customer_id;
        $order->store_id = $store->id;
        $order->type = "customer";
        $order->coupon = $request->coupon;
        $order->save();

        $booking = new Booking();
        $booking->user_id = $uid;
        $booking->store_id = $store->id;
        $booking->order_id = $order->id;
        $booking->name = $request->name;
        $booking->phone = $request->phone;
        $booking->email = $request->email;
        $booking->date = $request->date;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->pickup_location = $request->pickup_location;
        $booking->drop_location = $request->drop_location;
        $booking->time = $request->time;
        $booking->comment = $request->comment;
        $booking->save();

        foreach ($request->product as $key => $item) {
            $orderItem = new Orderitem();
            if (!empty($item['items'][0])) {
                if (!empty($item['items'][0]['files'])) {
                    foreach ($item['items'][0]['files'] as $lk => $fil) {
                        $file = $fil;
                        $fileName = mt_rand(100, 999) + time() . '.' . $fil->extension();
                        $file->move('orders/', $fileName);
                        $orderImage[$lk] = $fileName;
                    }

                    if (!empty($orderImage)) {
                        $orderItem->orderfiles = implode(",", $orderImage);
                        $orderImage = [];
                    }
                }

                if (!empty($item['items'][0]['description'])) {
                    $orderItem->sampleDescription = $item['items'][0]['description'];
                }
            }

            $orderItem->product_id = $item['id'];
            $orderItem->order_id = $order->id;
            $orderItem->currency_id = $store->currency;
            $orderItem->price = $item['price'] ?? 0;
            $orderItem->quantity = $item['quantity'];
            $orderItem->color = $item['color'] != 'null' ? $item['color'] : '';
            $orderItem->size = $item['size'] != 'null' ? $item['size'] : '';
            $orderItem->additional_price = $item['additional_price'] != 'null' ? $item['additional_price'] : 0;
            $orderItem->unit = $item['unit'] != 'null' ? $item['unit'] : '';
            $orderItem->volume = $item['volume'] != 'null' ? $item['volume'] : '';
            $orderItem->discount = $item['discount'] ?? 0;
            $product = Product::where('id', $item['id'])->first();
            $orderItem->cost = $product->cost ?? 0;
            $orderItem->save();

            if (isset($item['referral_code']) && !is_null($item['referral_code'])) {
                $info = ProductAffiliateInfo::where('referral_code', $item['referral_code'])->first();

                if ($info) {
                    $commissionPercent = (float)$info->commission_percent;
                    $productOrderPrice = $this->calculateProductAmount($item);
                    $commission_amount = ($productOrderPrice * $commissionPercent / 100);
                    $info->total_earning = (float)$info->total_earning + $commission_amount;
                    $info->final_amount = (float)$info->final_amount + $commission_amount;
                    $info->save();

                    $commission = new ProductAffiliateCommission();
                    $commission->affiliate_user_id = $info->user_id;
                    $commission->order_id = $order->id;
                    $commission->product_id = $item['id'];
                    $commission->product_price = $productOrderPrice;
                    $commission->store_id = $info->store_id;
                    $commission->commission_percent = $commissionPercent;
                    $commission->amount = $commission_amount;
                    $commission->currency = $current_currency->code;
                    $commission->save();
                }
            }

            if (isset($product)) {
                $product->quantity = $product->quantity - $item['quantity'];
                $product->save();
            }

            $variantQuery = Veriant::convertCurrency($item['id'], $request->store_id)->where('pid', $item['id']);

            if (isset($item['color']) && !empty($item['color']) && $item['color'] != 'null') {
                $variantQuery->where('veriants.color', $item['color']);
            }
            if (isset($item['size']) && !empty($item['size']) && $item['size'] != 'null') {
                $variantQuery->where('veriants.size', $item['size']);
            }
            if (isset($item['volume']) && !empty($item['volume']) && $item['volume'] != 'null') {
                $variantQuery->where('veriants.volume', $item['volume']);
            }
            if (isset($item['unit']) && !empty($item['unit']) && $item['unit'] != 'null') {
                $variantQuery->where('veriants.unit', $item['unit']);
            }

            $variant = $variantQuery->first();

            if (isset($variant)) {
                $variant->quantity = $variant->quantity - $item['quantity'];
                $variant->save();
            }
        }

        $transaction = new Transaction();
        $transaction->uid = $uid;
        $transaction->order_id = $order->id ?? '';
        $transaction->mode = $request->payment_type ?? '';
        $transaction->status = "pending";
        $transaction->save();

        $invoiceNo = generateInvoiceNo();
        $invoice = new Invoice;
        $invoice->reference_no = $invoiceNo;
        $invoice->order_id = $order->id;
        $invoice->type = "Website";
        $invoice->uid = $uid;
        $invoice->customer_id = $store->customer_id;
        $invoice->store_id = $store->id;
        $invoice->creator = $uid;
        $invoice->editor = $uid;
        $invoice->save();

        $text = sendOrderConfirmationText($store, $order);

        if (isset($store->plan_id) && ($store->plan_id == 8 || $store->plan_id == 9)) {
            if (isset($store->order_pull) && $store->order_pull == 0) {
                AccountJournal::saveJournal($order); // Place dropshipper order in account journal
            }
        }

        // Create an instance of CheckClientSms
        $smsChecker = new CheckClientSms($store->id, 5);
        // Check the SMS limit
        $isLimitReached = $smsChecker->checkSmsLimit();

        // Send SMS if the limit not 0
        if ($isLimitReached) {
            if (addonSmsCount($store->id) && isset($user->phone) && !empty($user->phone)) {
                $smsresult = SendSms($user->phone, $text); // phone, text

                smsLogger($user->phone, $text, "Order Confirmation", 0, $store->id);
            }
        }

        $headersetting = Headersetting::where('store_id', $store->id)->first();

        if (isset($user->email)) {
            if (isset($headersetting) && isset($headersetting->email)) {
                $data['email'] = $user->email;
                $data['FormEmail'] = $headersetting->email;
                $data['orderInfo'] = $text;
                $data["title"] = ucfirst($store->name);

                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::send('clientOrderNotifyMail', $data, function ($message) use ($data) {
                            $message->from($data['FormEmail'], $data["title"])->to($data["email"], $data["email"])
                                ->subject('Order Placed');
                        });
                    } catch (\Exception $e) {
                        // Log or handle the error
                        Log::error('Mail sending failed: ' . $e->getMessage());
                    }
                } else {
                    Log::error('Invalid email address: ' . $user->email);
                }
            }
        }

        if (ModulusStatus($store->id, 4)) {
            if (isset($headersetting) && isset($headersetting->email)) {
                $text = "New Order has been placed to " . $store->name . ". \nOrder Id: " . $order->reference_no . "\nPrice: " . $order->total;

                $data['email'] = $headersetting->email;
                $data['FormEmail'] = env("MAIL_FROM_ADDRESS") ?? $headersetting->email;
                $data['orderInfo'] = $text;
                $data["title"] = ucfirst($store->name);

                if (filter_var($headersetting->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::send('clientOrderNotifyMail', $data, function ($message) use ($data) {
                            $message->from($data['FormEmail'], $data["title"])
                                ->to($data["email"])
                                ->subject('Order placed');
                        });
                    } catch (\Exception $e) {
                        // Log or handle the error
                        Log::error('Mail sending failed: ' . $e->getMessage());
                    }
                } else {
                    Log::error('Invalid email address: ' . $headersetting->email);
                }

            }
        }

        // Create notification
        $orderURL = route("admin.order.view", ['id' => $order->id ?? ""]);
        $notificationData = [
            "title" => "New Order Placed (" . ($order->reference_no ?? '') . ") - " . formatDateWithTime($order->created_at),
            "type" => "store_order",
            "user_type" => "admin",
            "store_id" => $store->id ?? NULL,
            "link" => $orderURL,
        ];

        if (isset($notificationData['title']) && !empty($notificationData['title'])) {
            createNotification($notificationData);
        }

        $id = $store->id;
        $data['invoice'] = Invoice::where('id', $invoice->id)->first();
        $data['store'] = Store::find($store->id);
        $data['design'] = Design::where('store_id', $store->id)->first();
        $data['order'] = Order::find($order->id);
        $data['orderitems'] = Orderitem::where('order_id', $data['order']->id)->get();
        $data['transaction'] = Transaction::where('order_id', $data['order']->id)->first();

        if ($data['design']->invoice == 'one') {
            $data['invoiceNo'] = 2;
        } elseif ($data['design']->invoice == 'two') {
            $data['invoiceNo'] = 3;
        } elseif ($data['design']->invoice == 'three') {
            $data['invoiceNo'] = 4;
        } elseif ($data['design']->invoice == 'four') {
            $data['invoiceNo'] = 5;
        } elseif ($data['design']->invoice == 'six') {
            $data['invoiceNo'] = 6;
        } else {
            $data['invoiceNo'] = 2;
        }

        $token = Auth::user()->token;

        if ($request->payment_type == 'bkash') {
            Session::forget('payment_amount');
            Session::put(' _amount', $request->total);

            Session::forget('invoice');
            Session::put('invoice', $invoiceNo);

            $dat['payment_amount'] = Session::get('payment_amount');
            $dat['invoice'] = Session::get('invoice');

            $url = route('bkash.payment') . '?order=' . $order->id;
        } elseif ($request->payment_type == 'online') {
            Session::forget('payment_amount');
            Session::put(' _amount', $request->total);

            Session::forget('invoice');
            Session::put('invoice', $invoiceNo);

            $dat['payment_amount'] = Session::get('payment_amount');
            $dat['invoice'] = Session::get('invoice');

            $url = route('ssl.create-payment') . "?order_id=$order->id&store_id=$store->id";
        } elseif ($request->payment_type == 'amarpay') {
            Session::forget('payment_amount');
            Session::put('payment_amount', $request->total);
            Session::forget('invoice');
            Session::put('invoice', $invoiceNo);
            Session::forget('payment_type');
            Session::put('payment_type', $request->payment_type);
            $dat['payment_amount'] = Session::get('payment_amount');
            $dat['invoice'] = Session::get('invoice');

            $url = route('amarpay.payment') . '?order_id=' . $order->id;
        } elseif ($request->payment_type == 'merchant_bkash') {
            Session::forget('payment_amount');
            Session::put('payment_amount', $request->total);
            Session::forget('invoice');
            Session::put('invoice', $invoiceNo);
            Session::forget('payment_type');
            Session::put('payment_type', $request->payment_type);
            $dat['payment_amount'] = Session::get('payment_amount');
            $dat['invoice'] = Session::get('invoice');

            $url = route('ebitans-bkash.payment') . '?order_id=' . $order->id;
        } elseif ($request->payment_type == 'merchant_nagad') {
            Session::forget('payment_amount');
            Session::put('payment_amount', $request->total);
            Session::forget('invoice');
            Session::put('invoice', $invoiceNo);
            Session::forget('payment_type');
            Session::put('payment_type', $request->payment_type);
            $dat['payment_amount'] = Session::get('payment_amount');
            $dat['invoice'] = Session::get('invoice');

            $url = route('ebitans-nagad.payment') . '?order_id=' . $order->id;
        } elseif ($request->payment_type == 'uddoktapay') {
            Session::forget('payment_amount');
            Session::put('payment_amount', $request->total);
            Session::forget('invoice');
            Session::put('invoice', $invoiceNo);
            Session::forget('payment_type');
            Session::put('payment_type', $request->payment_type);
            $dat['payment_amount'] = Session::get('payment_amount');
            $dat['invoice'] = Session::get('invoice');

            $url = route('uddoktapay.payment') . '?order_id=' . $order->id;
        } elseif ($request->payment_type == 'paypal') {
            Session::forget('payment_amount');
            Session::put('payment_amount', $request->total);
            Session::forget('invoice');
            Session::put('invoice', $invoiceNo);
            Session::forget('payment_type');
            Session::put('payment_type', $request->payment_type);
            $dat['payment_amount'] = Session::get('payment_amount');
            $dat['invoice'] = Session::get('invoice');

            $url = route('paypal.payment') . '?order_id=' . $order->id;
        } elseif ($request->payment_type == 'stripe') {
            Session::forget('payment_amount');
            Session::put('payment_amount', $request->total);
            Session::forget('invoice');
            Session::put('invoice', $invoiceNo);
            Session::forget('payment_type');
            Session::put('payment_type', $request->payment_type);
            $dat['payment_amount'] = Session::get('payment_amount');
            $dat['invoice'] = Session::get('invoice');

            $url = route('stripe.payment') . '?order_id=' . $order->id;
        } else {
            $url = null;
            if (ModulusStatus($store->id, 106) && $request->payment_type == 'ap') {
                Session::forget('payment_amount');
                Session::put('payment_amount', $request->total);
                Session::forget('invoice');
                Session::put('invoice', $invoiceNo);
                Session::forget('payment_type');
                Session::put('payment_type', $request->payment_type);
                $dat['payment_amount'] = Session::get('payment_amount');
                $dat['invoice'] = Session::get('invoice');

                $url = route('bkash.payment') . '?order=' . $order->id;
            }
        }

        return response()->json(['order' => $order, 'user' => $user, 'url' => $url]);
    }

    /**
     * calculate product price
     *
     * @param $item
     * @return float
     */
    public function calculateProductAmount($item)
    {
        $productPrice = $item['price'] ?? 0;
        $quantity = (float)$item['quantity'];
        $additional_price = $item['additional_price'] != 'null' ? $item['additional_price'] : 0;
        $discount = $item['discount'] ?? 0;

        $price = ((float)$productPrice + (float)$additional_price - (float)$discount);
        $price = $price * $quantity;
        return $price;
    }


    public function getorder(Request $request)
    {
        $order = Order::where('uid', Auth::user()->id)->where('store_id', $request->store_id)->get();
        return response()->json($order);
    }

    public function getorderdetails(Request $request)
    {
        $order = Order::where('uid', $request->user_id)->where('store_id', $request->store_id)->get();
        if (isset($order) && count($order) > 0) {
            return response()->json($order);
        } else {
            $order = null;
            return response()->json($order);
        }
    }

    public function orderdetails(Request $request)
    {
        $order = Order::where('id', $request->id)->where('uid', Auth::user()->id)->first();
        $orderiem = Orderitem::where('order_id', $order->id)->get();
        $transaction = Transaction::where('order_id', $order->id)->first();
        $booking = Booking::where('order_id', $order->id)->where('store_id', $order->store_id)->first();

        if (isset($order)) {
            $order = $order;
        } else {
            $order = null;
        }
        if (isset($orderiem) && count($orderiem) > 0) {
            $orderiem = $orderiem;
        } else {
            $orderiem = null;
        }
        if (isset($transaction)) {
            $transaction = $transaction;
        } else {
            $transaction = null;
        }

        if (isset($booking)) {
            $booking = $booking;
        } else {
            $booking = null;
        }

        return response()->json([
            'order' => $order,
            'orderitem' => $orderiem,
            'transaction' => $transaction,
            'booking' => $booking
        ]);
    }

    public function cancelorder(Request $request)
    {
        $order = Order::where('id', $request->id)->where('uid', Auth::user()->id)->first();
        if (isset($order->status) && ($order->status == 'Delivered' || $order->status == 'Shipping')) {
            return response()->json(['error' => 'You can not cancel this order now. Order already Shipped!']);
        }
        if (isset($order)) {
            $order->status = "Cancelled";
            $order->save();

            $orderItems = Orderitem::where("order_id", $order->id)->get();
            foreach ($orderItems as $orderItem) {
                if (isset($orderItem->variant_id)) {
                    $variant = Veriant::where("id", $orderItem->variant_id)->first();
                    if (isset($variant)) {
                        $variant->quantity = (float)$variant->quantity + (float)$orderItem->quantity;
                        $variant->save();
                    }
                }

                if (isset($orderItem->product_id)) {
                    $product = Product::where("id", $orderItem->product_id)->first();
                    if (isset($product)) {
                        $product->quantity = (float)$product->quantity + (float)$orderItem->quantity;
                        $product->save();
                    }
                }
            }

            return response()->json(['success' => ' Successfully Cancel Order']);
        } else {
            return response()->json(['error' => ' Invalid']);
        }
    }

    public function review(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order->status == 'Delivered') {
            $review = new Review();
            $review->uid = Auth::user()->id;
            $review->order_id = $request->order_id;
            $review->product_id = $request->product_id;
            $user = User::find(Auth::user()->id);
            $review->name = $user->name ?? "";
            $review->comment = $request->comment;
            $review->rating = $request->rating;
            $review->store_id = $request->store_id;
            $review->save();
            $oitm = Orderitem::where('product_id', $request->product_id)->where('order_id',
                $request->order_id)->get();
            foreach ($oitm as $itm) {
                $ot = Orderitem::where('id', $itm->id)->first();
                $ot->review = 1;
                $ot->save();
            }
            return response()->json(['success' => ' Successfully Submitted Review']);
        } else {
            return response()->json(['error' => ' Invalid']);
        }
    }
}
