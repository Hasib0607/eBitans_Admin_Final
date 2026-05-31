<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SuperAdminController;
use App\Models\Activity;
use App\Models\AddonsExpired;
use App\Models\AddonsOrder;
use App\Models\Mobileapp;
use App\Models\Paymentgateway;
use App\Models\Plan;
use App\Models\Referral;
use App\Models\Store;
use App\Models\User;
use App\Models\Websitesetup;
use App\Util\BkashCredential;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Log;

class AcceptPlanController extends Controller
{

    public function acceptPlanOrder($order_id)
    {
        try {
            $order = AddonsOrder::find($order_id);
            $str = Store::where("id", $order->store_id)->first();
            $user = User::find($order->user_id);

            if (isset($user) && isset($str)) {
                $tmp1 = [];
                $tmp1['website'] = 0;
                $tmp1['digital'] = 0;
                $tmp1['pos'] = 0;
                $str->pay_noti = 1;

                if (isset($order->combopackages)) {
                    foreach ($order->combopackages as $key => $item) {
                        $activeDate = null;
                        $expireDate = null;

                        if ($item['type'] == 'website') {
                            if (isset($item['id'])) {
                                $str->plan_status = "active";

                                $isNew = 1;
                                $activeTimeStatus = $item['activeTime'] ?? 0;
                                if (isset($str->plan_id) && $str->plan_id == 6) {
                                    $activeTimeStatus = 1;
                                    $isNew = 1;
                                    setPackageCommission($str->id, $item['id'], 0);
                                } else {
                                    if ($str->paid_registration && is_null($str->expiry_date)) {
                                        $isNew = 1;
                                    } else if ($str->paid_registration && !is_null($str->expiry_date)) {
                                        $package = AddonsOrder::where('store_id', $str->id)->whereNotNull('plan_id')->where('status', "Complete")->get();

                                        if (isset($package)) {
                                            $isNew = 0;
                                        }
                                    } else {
                                        $isNew = 0;
                                    }
                                }

                                if (is_null($order['coupon']) || empty($order['coupon'])) {
                                    $package_month = $item['month'] ?? 1;
                                    (new SuperAdminController())->giveSellerCommission($user->id, $isNew, $item['discountPrice'], $order['store_id'], $package_month);
                                }

                                if (Carbon::parse($str->expiry_date) <= Carbon::now() || $activeTimeStatus == 1) {
                                    $activeDate = Carbon::now();
                                    $expireDate = getNewExpiryDate($item, $str->expiry_date, $str->plan_id, $item['month']);

                                    $str->purchase_date = $activeDate;
                                    $str->plan_id = $item['id'];
                                    $str->expiry_date = $expireDate;
                                    $str->upcoming_plan_id = null;
                                    $str->upcoming_plan_month = null;
                                    $str->upcoming_plan_purchase_date = null;
                                    $str->upcoming_plan_expiry_date = null;
                                } else {
                                    $activeDate = Carbon::parse($str->expiry_date);
                                    $expireDate = getNewExpiryDate($item, $str->expiry_date, $str->plan_id, $item['month']);

                                    $str->upcoming_plan_id = $item['id'];
                                    $str->upcoming_plan_month = $item['month'];
                                    $str->upcoming_plan_purchase_date = $activeDate;
                                    $str->upcoming_plan_expiry_date = $expireDate;
                                }

                                storePlanPurchaseHistory($user->id, $order['store_id'], $item['id'], $item['month'], $item, $activeDate, $expireDate);

                                if (!$isNew) {
                                    $str->renew_date = Carbon::now() ?? NULL;
                                }

                                if ($item['id'] == 6) {
                                    $str->url = $str->slug . '.' . env('STORE_SUB_DOMAIN');
                                }
                            }

                            $tmp1['website'] = $item['discountPrice'];
                            $tmp1['website_id'] = $item['id'];
                        }

                        if ($item['type'] == 'digital') {
                            if (Carbon::parse($str->digital_plan_end_date) <= Carbon::now()) {
                                $activeDate = Carbon::now();
                                $expireDate = Carbon::now()->addMonths($item['month']);

                                $str->digital_plan_status = "active";
                                $str->digital_plan_start_date = $activeDate;
                                $str->digital_plan_id = $item['id'];
                                $str->digital_plan_end_date = $expireDate;
                            } else {
                                $activeDate = Carbon::parse($str->digital_plan_end_date)->addMonths(1);
                                $expireDate = getNewExpiryDate($item, $str->digital_plan_end_date, $str->plan_id, $item['month']);

                                $str->upcoming_digital_plan_id = $item['id'];
                                $str->upcoming_digital_plan_month = $item['month'];
                                $str->upcoming_digital_plan_start_date = $activeDate;
                                $str->upcoming_digital_plan_expiry_date = $expireDate;
                            }

                            storePlanPurchaseHistory($user->id, $order['store_id'], $item['id'], $item['month'], $item, $activeDate, $expireDate);

                            if ($item['id'] == 6) {
                                $str->url = $str->slug . '.' . env('STORE_SUB_DOMAIN');
                            }

                            $tmp1['digital'] = $item['discountPrice'];
                            $tmp1['digital_id'] = $item['id'];
                        }

                        if ($item['type'] == 'pos') {
                            if (Carbon::parse($str->pos_plan_expiry_date) <= Carbon::now()) {
                                $activeDate = Carbon::now();
                                $expireDate = Carbon::now()->addMonths($item['month']);

                                $str->pos_plan_status = "active";
                                $str->pos_plan_start_date = $activeDate;
                                $str->pos_plan_id = $item['id'];
                                $str->pos_plan_expiry_date = $expireDate;
                            } else {
                                $activeDate = Carbon::parse($str->pos_plan_expiry_date)->addMonths(1);
                                $expireDate = getNewExpiryDate($item, $str->pos_plan_expiry_date, $str->plan_id, $item['month']);

                                $str->upcoming_pos_plan_id = $item['id'];
                                $str->upcoming_pos_plan_month = $item['month'];
                                $str->upcoming_pos_plan_start_date = $activeDate;
                                $str->upcoming_pos_plan_expiry_date = $expireDate;
                            }

                            storePlanPurchaseHistory($user->id, $order['store_id'], $item['id'], $item['month'], $item, $activeDate, $expireDate);

                            if ($item['id'] == 6) {
                                $str->url = $str->slug . '.' . env('STORE_SUB_DOMAIN');
                            }
                            $tmp1['pos'] = $item['discountPrice'];
                            $tmp1['pos_id'] = $item['id'];
                        }
                    }
                } else {
                    if (isset($order['plan_id'])) {
                        $activeDate = null;
                        $expireDate = null;

                        if ($order['plan_type'] == 'website') {
                            if (isset($order['plan_id'])) {
                                $str->plan_status = "active";
                                $expired_date = $order['plan_month'] ?? 0;
                                $activeTimeStatus = json_decode($order->package)->activeTime ?? 0;

                                $isNew = 1;
                                if (isset($str->plan_id) && $str->plan_id == 6) {
                                    $activeTimeStatus = 1;
                                    $isNew = 1;
                                    setPackageCommission($str->id, $order['plan_id'], 0);
                                } else {
                                    if ($str->paid_registration && is_null($str->expiry_date)) {
                                        $isNew = 1;
                                    } else if ($str->paid_registration && !is_null($str->expiry_date)) {
                                        $package = AddonsOrder::where('store_id', $str->id)->whereNotNull('plan_id')->where('status', "Complete")->get();

                                        if (isset($package)) {
                                            $isNew = 0;
                                        }
                                    } else {
                                        $isNew = 0;
                                    }
                                }

                                if (is_null($order['coupon']) || empty($order['coupon'])) {
                                    $package = json_decode($order->package, true);

                                    $package_month = $package['month'] ?? 1;
                                    $totalPrice = $order['total'];
                                    if (is_array($package) && isset($package['offerprice'])) {
                                        $totalPrice = (float) $package['offerprice'];
                                    }

                                    (new SuperAdminController())->giveSellerCommission($user->id, $isNew, $totalPrice, $order['store_id'], $package_month);
                                }

                                $newExpiryDate = Carbon::now()->addMonths($expired_date);

                                $isValidDate = true;
                                try {
                                    Carbon::parse($str->expiry_date);
                                } catch (\Exception $e) {
                                    $isValidDate = false;
                                }

                                if (isset($str->expiry_date) && $isValidDate) {
                                    if (Carbon::parse($str->expiry_date)->gt(Carbon::now())) {
                                        $daysLeft = Carbon::now()->diffInDays($str->expiry_date);
                                    } else {
                                        $daysLeft = 0;
                                    }

                                    if ($daysLeft > 0 && $activeTimeStatus == 0) {
                                        $newExpiryDate = Carbon::now()->addMonths($expired_date)->addDays($daysLeft);
                                    }
                                }

                                if (Carbon::parse($str->expiry_date) <= Carbon::now() || $activeTimeStatus == 1) {
                                    $activeDate = Carbon::now();
                                    $expireDate = $newExpiryDate;

                                    $str->purchase_date = $activeDate;
                                    $str->plan_id = $order['plan_id'];
                                    $str->expiry_date = $newExpiryDate;
                                    $str->upcoming_plan_id = null;
                                    $str->upcoming_plan_month = null;
                                    $str->upcoming_plan_purchase_date = null;
                                    $str->upcoming_plan_expiry_date = null;
                                } else {
                                    $activeDate = Carbon::parse($str->expiry_date);
                                    $expireDate = $newExpiryDate;

                                    $str->upcoming_plan_id = $order['plan_id'];
                                    $str->upcoming_plan_month = $order['plan_month'];
                                    $str->upcoming_plan_purchase_date = $activeDate;
                                    $str->upcoming_plan_expiry_date = $newExpiryDate;
                                }

                                storePlanPurchaseHistory($user->id, $order['store_id'], $order['plan_id'], $order['plan_month'], $order, $activeDate, $expireDate, true);

                                if (!$isNew) {
                                    $str->renew_date = Carbon::now() ?? NULL;
                                }
                            }

                            if ($order['plan_id'] == 6) {
                                $str->url = $str->slug . '.' . env('STORE_SUB_DOMAIN');
                            }
                        }

                        if ($order['plan_type'] == 'digital') {
                            if (Carbon::parse($str->digital_plan_end_date) <= Carbon::now()) {
                                $activeDate = Carbon::now();
                                $expireDate = Carbon::now()->addMonths($order['plan_month']);

                                $str->digital_plan_status = "active";
                                $str->digital_plan_start_date = $activeDate;
                                $str->digital_plan_id = $order['plan_id'];
                                $str->digital_plan_end_date = $expireDate;
                            } else {
                                $expired_date = $order['plan_month'] ?? 0;
                                $activeTimeStatus = $item['activeTime'] ?? 1;

                                $newExpiryDate = Carbon::now()->addMonths($expired_date);

                                $isValidDate = true;
                                try {
                                    Carbon::parse($str->digital_plan_end_date);
                                } catch (\Exception $e) {
                                    $isValidDate = false;
                                }

                                if (isset($str->digital_plan_end_date) && $isValidDate) {
                                    if (Carbon::parse($str->digital_plan_end_date)->gt(Carbon::now())) {
                                        $daysLeft = Carbon::now()->diffInDays($str->digital_plan_end_date);
                                    } else {
                                        $daysLeft = 0;
                                    }

                                    if ($daysLeft > 0 && $activeTimeStatus == 0) {
                                        $newExpiryDate = Carbon::now()->addMonths($expired_date)->addDays($daysLeft);
                                    }
                                }

                                $activeDate = Carbon::parse($str->digital_plan_end_date)->addMonths(1);
                                $expireDate = $newExpiryDate;

                                $str->upcoming_digital_plan_id = $order['plan_id'];
                                $str->upcoming_digital_plan_month = $order['plan_month'];
                                $str->upcoming_digital_plan_start_date = $activeDate;
                                $str->upcoming_digital_plan_expiry_date = $newExpiryDate;
                            }

                            storePlanPurchaseHistory($user->id, $order['store_id'], $order['plan_id'], $order['plan_month'], $order, $activeDate, $expireDate, true);

                        }

                        if ($order['plan_type'] == 'pos') {
                            if (Carbon::parse($str->pos_plan_expiry_date) <= Carbon::now()) {
                                $activeDate = Carbon::now();
                                $expireDate = Carbon::now()->addMonths($order['plan_month']);

                                $str->pos_plan_status = "active";
                                $str->pos_plan_start_date = $activeDate;
                                $str->pos_plan_id = $order['plan_id'];
                                $str->pos_plan_expiry_date = $expireDate;
                            } else {
                                $expired_date = $order['plan_month'] ?? 0;
                                $activeTimeStatus = $item['activeTime'] ?? 1;

                                $newExpiryDate = Carbon::now()->addMonths($expired_date);

                                $isValidDate = true;
                                try {
                                    Carbon::parse($str->pos_plan_expiry_date);
                                } catch (\Exception $e) {
                                    $isValidDate = false;
                                }

                                if (isset($str->pos_plan_expiry_date) && $isValidDate) {
                                    if (Carbon::parse($str->pos_plan_expiry_date)->gt(Carbon::now())) {
                                        $daysLeft = Carbon::now()->diffInDays($str->pos_plan_expiry_date);
                                    } else {
                                        $daysLeft = 0;
                                    }

                                    if ($daysLeft > 0 && $activeTimeStatus == 0) {
                                        $newExpiryDate = Carbon::now()->addMonths($expired_date)->addDays($daysLeft);
                                    }
                                }

                                $activeDate = Carbon::parse($str->pos_plan_expiry_date)->addMonths(1);
                                $expireDate = $newExpiryDate;

                                $str->upcoming_pos_plan_id = $order['plan_id'];
                                $str->upcoming_pos_plan_month = $order['plan_month'];
                                $str->upcoming_pos_plan_start_date = $activeDate;
                                $str->upcoming_pos_plan_expiry_date = $newExpiryDate;
                            }

                            storePlanPurchaseHistory($user->id, $order['store_id'], $order['plan_id'], $order['plan_month'], $order, $activeDate, $expireDate, true);
                        }
                    }
                }

                if ($order->addons != null) {
                    foreach ($order->addons as $key => $item) {
                        $addonsExpired = AddonsExpired::where('store_id', $order->store_id)->where('addons_id', $item['id'])->first();
                        if (!$addonsExpired) {
                            $addonsExpired = new AddonsExpired();
                        }

                        $addonsExpired->user_id = $order->user_id;
                        $addonsExpired->store_id = $order->store_id;
                        $addonsExpired->addons_id = $item['id'];
                        $addonsExpired->pos_plan_id = $item['posID'];
                        $addonsExpired->price = $item['price'];
                        $addonsExpired->accept_date = Carbon::now()->addMonths(0);
                        $addonsExpired->status = 1;

                        if (isset($item['title']) && $item['title'] === "Domain") {
                            $domain = $item['domain'] ?? "";
                            $email = $item['email'] ?? "";

                            $newPlan = isset($order['plan_id']) && !in_array($order['plan_id'], [6, 9]);
                            if (!empty($domain)) {
                                addDomainFromAddon($domain, $email, $user, $newPlan);
                            }
                        }

                        if ($item['type'] == 'monthly') {
                            $addonsExpired->expired_date = getNewExpiryDate($item, $addonsExpired->expired_date);
                            $addonsExpired->type = $item['type'];
                            $addonsExpired->title = $item['name'] ?? $item['title'] ?? "";
                        } else {
                            $addonsExpired->expired_date = '0000-00-00 00:00:00';
                            $addonsExpired->type = $item['type'];
                        }

                        if ($item['type'] == 'counter') {
                            $addonsExpired->total = $addonsExpired->total + $item['quantity'];
                            $addonsExpired->type = $item['type'];
                            $addonsExpired->title = $item['name'];
                        }

                        $addonsExpired->save();

                        if ($item['title'] == "Mobile App (Android Web View)") {
                            $mobileapp = Mobileapp::where('store_id', $order->store_id)->first();
                            if (isset($mobileapp)) {
                                $mobileapp->start_date = Carbon::now();
                                $mobileapp->expiry_date = getNewExpiryDate($item, $mobileapp->expiry_date);
                                $mobileapp->save();
                            } else {
                                $mobile = new Mobileapp();
                                $mobile->start_date = Carbon::now();
                                $mobile->expiry_date = Carbon::now()->addMonths($item['months']);
                                $mobile->store_id = $order->store_id;
                                $mobile->save();
                            }
                        }
                        if ($item['title'] == "Activity Log") {
                            $actv = Activity::where('store_id', $order->store_id)->first();
                            if (isset($actv)) {
                                $actv->expiry_date = getNewExpiryDate($item, $actv->expiry_date);
                                $actv->month = $item['months'];
                                $actv->save();
                            } else {
                                $newact = new Activity();
                                $newact->start_date = Carbon::now();
                                $newact->expiry_date = Carbon::now()->addMonths($item['months']);
                                $newact->store_id = $order->store_id;
                                $newact->month = $item['months'];
                                $newact->save();
                            }
                        }
                        if ($item['title'] == "Website Setup") {
                            $newacts = new Websitesetup();
                            $newacts->store_id = $order->store_id;
                            $newacts->status = "Pending";
                            $newacts->save();

                            if (is_null($order->coupon) || empty($order->coupon)) {
                                (new SuperAdminController())->giveSellerCommissionForSetup($user->id, $item['price'], $order->store_id);
                            }
                        }
                        if ($item['title'] == "Payment Gateway") {
                            $newactp = new Paymentgateway();
                            $newactp->payment_company = $item['name'];
                            $newactp->store_id = $order->store_id;
                            $newactp->status = "Accepted";
                            $newactp->save();
                        }
                    }
                }

                $str->trail = 1;
                $str->update();

                $order->order_no = 'EBI-' . Carbon::now()->timestamp;
                $order->status = "Complete";
                $order->update();

                $data['data'] = $order;
                $data['package'] = !empty($order->package) ? json_decode($order->package) : null;

                $this->giveUserReferralCommission($user, $str, $order, $tmp1);


                $planOrderURL = route("superadmin.planorder.view.invoice", ['id' => $order->id]);

                $currentTime = Carbon::now();

                if (!is_null($order->addons) && count($order->addons) > 0) {
                    // Create notification
                    $notificationData = [
                        "title" => "Addon purchased Successfully By (" . ($str->name ?? '') . ") - " . formatDateWithTime($currentTime),
                        "type" => "addon_order",
                        "user_type" => "superadmin",
                        "link" => $planOrderURL,
                    ];

                    if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                        createNotification($notificationData);
                    }
                }

                if (!is_null($order->package) && $order->package != null && !empty($order->package)) {
                    // Create notification
                    $notificationData = [
                        "title" => "Package purchased Successfully By (" . ($str->name ?? '') . ") - " . formatDateWithTime($currentTime),
                        "type" => "plan_order",
                        "user_type" => "superadmin",
                        "link" => $planOrderURL,
                    ];

                    if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                        createNotification($notificationData);
                    }
                }

                if (!empty($user->email)) {

                    $data['email'] = $user->email;
                    $data['data'] = $order;
                    $data['package'] = !empty($order->package) ? json_decode($order->package) : null;
                    $data['mailFrom'] = env('MAIL_FROM_ADDRESS', 'no-replay@ebitans.com.bd');

                    Mail::send('clientPaymentMail', $data, function ($message) use ($data) {
                        $message->from($data["mailFrom"], "Your eBitans Payment Receipt")
                            ->to($data["email"], $data["email"])
                            ->subject('Your Payment has been accepted successfully!');
                    });
                }

                Session::flash('message', 'Order Accept');
                Session::flash('success', 'Order Accept');
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }


    public function giveUserReferralCommission($user, $str, $order, $tmp1)
    {
        if (isset($user->refer_by)) {
            $referral = new Referral();

            $referral->user_id = $user->id;
            $referral->store_id = $str->id;
            $referral->referral_id = $user->refer_by;
            $referral->save();

            $refUser = User::where('referral', $user->refer_by)
                ->where('created_at', '>=', Carbon::now()->subYear())
                ->first();

            if ($refUser) {
                $referralCommission = $refUser->referral_commission ?? 0;
                $totalCommission = $refUser->total_commission ?? 0;

                if ($order->plan_id) {
                    $plan = Plan::find($order->plan_id);
                    $referral->plan_id = $str->plan_id;
                    $commission = ($plan->price * $referralCommission) / 100;
                    $referral->commission_price = $commission;
                    $referral->update();

                    $refUser->total_commission = $totalCommission + $commission;
                    $refUser->update();
                } else {
                    $totalPrice = $tmp1['website'] + $tmp1['digital'] + $tmp1['pos'];
                    $commission = ($totalPrice * $referralCommission) / 100;
                    $referral->commission_price = $commission;
                    $referral->plan_id = $tmp1['website_id'];
                    $referral->digital_id = $tmp1['digital_id'];
                    $referral->pos_id = $tmp1['pos_id'];
                    $referral->update();

                    $refUser->total_commission = $totalCommission + $commission;
                    $refUser->update();
                }
            }
        }
    }


}
