<?php

namespace App\Http\Controllers;

use App\Exports\PaidClientsExport;
use App\Http\Controllers\PaymentGateway\AcceptPlanController;
use App\Logic\Providers\cPanelApi;
use App\Models\Activity;
use App\Models\Addon;
use App\Models\AddonsApi;
use App\Models\AddonsExpired;
use App\Models\AddonsOrder;
use App\Models\AddonsOrderPaymentHistory;
use App\Models\AdminUserAnalytics;
use App\Models\BusinessCategory;
use App\Models\Category;
use App\Models\ClientActivitieComments;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Designlist;
use App\Models\Digitalcontent;
use App\Models\Domain;
use App\Models\Iconpack;
use App\Models\Invoicepurchase;
use App\Models\Message;
use App\Models\Mobileapp;
use App\Models\Modulus;
use App\Models\ModulusPayment;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Paymentgateway;
use App\Models\Plan;
use App\Models\Planorder;
use App\Models\Product;
use App\Models\QuickLogin;
use App\Models\Referral;
use App\Models\RegistrationFee;
use App\Models\Staff;
use App\Models\Store;
use App\Models\StorePurchaseHistory;
use App\Models\SuperAdminSetting;
use App\Models\Superrole;
use App\Models\Supersetting;
use App\Models\Superstaff;
use App\Models\SuperstaffSalesCommission;
use App\Models\SuperstaffSalesCommissionBalance;
use App\Models\Template;
use App\Models\Temposition;
use App\Models\Themecustomize;
use App\Models\Tricket;
use App\Models\User;
use App\Models\Websitesetup;
use App\Models\WhatsAppMessage;
use App\Models\ZoneRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SuperAdminController extends Controller
{
    public function index(Request $request)
    {
        // This code will remove later
        $sellerStore = User::with('getStore', 'seller')->whereNotNull('seller_id')
            ->whereHas('getStore', function ($query) {
                $query->whereBetween('created_at', ['2024-12-22', '2025-01-07']);
            })
            ->where(function ($query) {
                $query->where("type", "admin")
                    ->orWhere("type", "affiliate")
                    ->orWhere("type", "dropshipper");
            })
            ->get();
        // This code will remove later

        $urls = "dashboard";
        $perPage = 20;
        $isDateFilter = false;
        $isExpireFilter = false;
        if (isset($request->days) && !empty($request->days)) {
            $isDateFilter = true;
            $days = (int) $request->days + 1 ?? 8;

            // Calculate the start and end dates
            $start_date = Carbon::now()->format('Y-m-d'); // Current date
            $end_date = Carbon::now()->addDays($days)->format('Y-m-d'); // Current date
        } else if (isset($request->expire) && !empty($request->expire) && $request->expire == 1) {
            $isExpireFilter = true;
        }

        $storeQuery = Store::with('user');
        if ($isDateFilter) {
            $storeQuery->whereBetween('expiry_date', [$start_date, $end_date]);
        }
        if ($isExpireFilter) {
            $date = Carbon::now()->format('Y-m-d');
            $storeQuery->where('expiry_date', "<", $date);
        }

        $storeQuery->whereNotIn('plan_id', [6, 9])
            ->where('call_status', '<', '5')
            ->whereNull("upcoming_plan_id");

        if ($isExpireFilter) {
            $storeQuery->orderBy("expiry_date", "DESC");
        } else {
            $storeQuery->orderBy("expiry_date", "ASC");
        }

        $exstore = $storeQuery->paginate($perPage);
        $nightlyBackupStatus = app(\App\Services\BackupManager::class)->getNightlyStatus();

        $nowDhaka = Carbon::now('Asia/Dhaka');
        $nextNightlyBackupRun = $nowDhaka->copy()->setTime(23, 0, 0);

        if ($nowDhaka->greaterThanOrEqualTo($nextNightlyBackupRun)) {
            $nextNightlyBackupRun->addDay();
        }

        return view('superadmin.index')
            ->with('urls', $urls)
            ->with('exstore', $exstore)
            ->with('sellerStore', $sellerStore)
            ->with('expire', $request->expire ?? "")
            ->with('filterDays', $request->days ?? "")
            ->with('nightlyBackupStatus', $nightlyBackupStatus)
            ->with('nextNightlyBackupRun', $nextNightlyBackupRun);
    }

    public function clientlist()
    {
        if (canSuperStaffAccess('clients')) {
            $data['urls'] = "clients";

            $clientQuery = User::with([
                'customerInfo',
                'addonOrder' => function ($query) {
                    $query->select('id', 'user_id', 'status');
                }
            ])
                ->whereIn('type', ['admin', 'affiliate', 'dropshipper'])
                ->withCount([
                    'addonOrder as complete_orders_count' => function ($query) {
                        $query->where('status', 'complete');
                    }
                ]);

            //            $data['clientsExport'] = $clientQuery->orderBy('id', 'DESC')
//                ->get()->map(function ($client, $key) {
//                    return [
//                        'SL' => $key + 1,
//                        'Name' => $client->name ?? 'Empty',
//                        'StoreName' => $client->getStore->name ?? '',
//                        'Phone' => isset($client->phone) && !empty($client->phone) ? $client->phone : $client->email ?? '',
//                        'UserType' => $client->type ?? '',
//                        'UserID' => $client->id ?? '',
//                        'Seller' => $client->customerInfo->staff->name ?? 'empty',
//                        'CreatedDate' => date('j M, Y', strtotime($client->created_at ?? '2000-01-01')),
//                    ];
//                });

            $data['clientsExport'] = [];

            $data['clients'] = $clientQuery->orderBy('id', 'DESC')->paginate(10);

            $data['staff'] = Superstaff::where("status", "active")->select("id", "uid", "name", "new_commission", "renew_commission", "setup_commission")->get();
            $data['categories'] = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();

            return view('superadmin.clientlist', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function registerClients(Request $request)
    {
        if (canSuperStaffAccess('register_clients')) {
            $data['urls'] = "register-clients";
            $data['formdate'] = $request->formdate;
            $data['enddate'] = $request->enddate;

            $clientQuery = Store::query();

            if (isset($request->formdate) && isset($request->enddate)) {
                $clientQuery = $clientQuery->whereBetween('created_at', [$request->formdate, $request->enddate]);
            } elseif (isset($request->status)) {
                $clientQuery = $clientQuery->where('status', $request->status);
            }

            //            if (Auth::user()->type == 'superstaff') {
//                // Add condition to check expiry date is after 2 months from today
//                $twoMonthsFromNow = Carbon::now()->addMonths(2);
//                $clientQuery = $clientQuery->where('expiry_date', '<', $twoMonthsFromNow);
//            }

            $data['paidClientsExport'] = $clientQuery->where('plan_id', 6)
                ->groupBy('user_id')
                ->get()->map(function ($client, $key) {
                    return [
                        'SL' => $key + 1,
                        'Name' => $client->getUser->name ?? 'Empty',
                        'StoreName' => $client->name ?? '',
                        'Phone' => $client->getUser->phone ?? '',
                        'UserID' => $client->getUser->id ?? '',
                        'Plan' => $client->getPlan->name ?? 'empty',
                        'ActiveDate' => date('j M, Y', strtotime($client->purchase_date ?? '2000-01-01')),
                        'ExpireDate' => date('j M, Y', strtotime($client->expiry_date ?? '2000-01-01')),
                    ];
                });

            $data['paidClients'] = $clientQuery->where('plan_id', 6)
                ->groupBy('user_id')
                ->paginate(20);

            return view('superadmin.registerClients', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function padiClients(Request $request)
    {
        if (canSuperStaffAccess('paid_clients')) {
            $data['urls'] = "paid-clients";
            $data['formdate'] = $request->formdate;
            $data['enddate'] = $request->enddate;

            $clientQuery = Store::query();

            if (isset($request->formdate) && isset($request->enddate)) {
                $clientQuery = $clientQuery->whereBetween('expiry_date', [$request->formdate, $request->enddate]);
            } elseif (isset($request->status)) {
                $clientQuery = $clientQuery->where('status', $request->status);
            }

            if (Auth::user()->type == 'superstaff') {
                // Add condition to check expiry date is after 2 months from today
                $twoMonthsFromNow = Carbon::now()->addMonths(2);
                $clientQuery = $clientQuery->where('expiry_date', '<', $twoMonthsFromNow);
            }

            $data['paidClientsExport'] = $clientQuery->whereNotIn('plan_id', [6, 9])
                ->groupBy('user_id')
                ->get()->map(function ($client, $key) {
                    return [
                        'SL' => $key + 1,
                        'Name' => $client->getUser->name ?? 'Empty',
                        'StoreName' => $client->name ?? '',
                        'Phone' => $client->getUser->phone ?? '',
                        'UserID' => $client->getUser->id ?? '',
                        'Plan' => $client->getPlan->name ?? 'empty',
                        'ActiveDate' => date('j M, Y', strtotime($client->purchase_date ?? '2000-01-01')),
                        'ExpireDate' => date('j M, Y', strtotime($client->expiry_date ?? '2000-01-01')),
                    ];
                });

            $data['paidClients'] = $clientQuery->whereNotIn('plan_id', [6, 9])
                ->groupBy('user_id')
                ->paginate(20);

            return view('superadmin.paidClients', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function padiClientsList(Request $request)
    {
        if (canSuperStaffAccess('paid_clients')) {
            $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
            $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
            $search = $request->search;
            $type = $request->type;
            $currentDate = Carbon::now();

            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
            $data['type'] = $type;
            $data['search'] = $search;

            $clientQuery = Store::query();

            $clientQuery->with([
                'user',
                'addonsOrders' => function ($query) use ($from_date, $to_date) {
                    if ($from_date && !$to_date) {
                        $query->where('created_at', '>=', $from_date->startOfDay());
                    } elseif (!$from_date && $to_date) {
                        $query->where('created_at', '<=', $to_date->endOfDay());
                    } elseif ($from_date && $to_date) {
                        $query->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
                    }
                    $query->where('status', 'Complete');
                }
            ]);


            // Search logic
            if (!empty($search)) {
                $clientQuery->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('user_id', $search)
                        ->orWhereHas('user', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', "%$search%")
                                ->orWhere('phone', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        });
                });
            }

            if (!empty($type)) {
                switch ($type) {
                    case 'paid':
                        if ($from_date && !$to_date) {
                            $clientQuery->where('expiry_date', '>=', $from_date->startOfDay());
                        } elseif (!$from_date && $to_date) {
                            $clientQuery->where('expiry_date', '<=', $to_date->endOfDay());
                        } elseif ($from_date && $to_date) {
                            $clientQuery->whereBetween('expiry_date', [$from_date->startOfDay(), $to_date->endOfDay()]);
                        } else {
                            $clientQuery->where('expiry_date', '>=', $currentDate->startOfDay());
                        }

                        $clientQuery->orderBy('expiry_date');
                        break;

                    case 'expired':
                        if ($from_date && !$to_date) {
                            $clientQuery->where('expiry_date', '>=', $from_date->startOfDay());
                        } elseif (!$from_date && $to_date) {
                            $clientQuery->where('expiry_date', '<', $to_date->endOfDay());
                        } elseif ($from_date && $to_date) {
                            $clientQuery->whereBetween('expiry_date', [$from_date->startOfDay(), $to_date->endOfDay()]);
                        } else {
                            $clientQuery->where('expiry_date', '<', $currentDate->startOfDay());
                        }
                        $clientQuery->orderBy('expiry_date', "desc");
                        break;

                    case 'renew':
                        if ($from_date && !$to_date) {
                            $clientQuery->where('renew_date', '>=', $from_date);
                        } elseif (!$from_date && $to_date) {
                            $clientQuery->where('renew_date', '<=', $to_date);
                        } elseif ($from_date && $to_date) {
                            $clientQuery->whereBetween('renew_date', [$from_date, $to_date]);
                        }

                        $clientQuery->whereNotNull('renew_date');
                        break;

                    case 'new_customer':
                        if ($from_date && !$to_date) {
                            $clientQuery->where('purchase_date', '>=', $from_date);
                        } elseif (!$from_date && $to_date) {
                            $clientQuery->where('purchase_date', '<=', $to_date);
                        } elseif ($from_date && $to_date) {
                            $clientQuery->whereBetween('purchase_date', [$from_date, $to_date]);
                        }

                        $clientQuery->whereNull('renew_date');
                        break;

                    case 'active':
                        $sevenDaysAgo = Carbon::now()->subDays(7)->startOfDay();

                        $analyticsSubquery = \DB::table('admin_user_analytics')
                            ->select('store_id')
                            ->where('created_at', '>=', $sevenDaysAgo)
                            ->groupBy('store_id')
                            ->havingRaw('SUM(number_of_visits) >= 50')
                            ->havingRaw('COUNT(DISTINCT url) >= 15')
                            ->havingRaw('COUNT(DISTINCT DATE(created_at)) >= 3');

                        $clientQuery->whereIn('id', $analyticsSubquery);
                        break;

                    case 'setup_buy':
                        if ($from_date && !$to_date) {
                            $clientQuery->where('purchase_date', '>=', $from_date);
                        } elseif (!$from_date && $to_date) {
                            $clientQuery->where('purchase_date', '<=', $to_date);
                        } elseif ($from_date && $to_date) {
                            $clientQuery->whereBetween('purchase_date', [$from_date, $to_date]);
                        }

                        $clientQuery->where('setup_status', 1);
                        break;

                    case 'setup_not_buy':
                        if ($from_date && !$to_date) {
                            $clientQuery->where('purchase_date', '>=', $from_date);
                        } elseif (!$from_date && $to_date) {
                            $clientQuery->where('purchase_date', '<=', $to_date);
                        } elseif ($from_date && $to_date) {
                            $clientQuery->whereBetween('purchase_date', [$from_date, $to_date]);
                        }

                        $clientQuery->where('setup_status', 0);
                        break;

                    default:
                        // Default case logic
                        break;
                }
            }

            // Exclude specific plan IDs
            $clientQuery->whereNotIn('plan_id', [6, 9])->groupBy('user_id');

            // Calculate the sum of 'total' for all results (optional, for reporting purposes)
            $data['totalAmount'] = $clientQuery->get()->pluck('addonsOrders')->flatten()->sum('total') ?? 0;

            $totalPackage = 0;
            $totalAddons = 0;
            $totalData = $clientQuery->get()->pluck('addonsOrders')->flatten();

            foreach ($totalData as $key => $order) {
                // Check if 'addons' is a string and decode it if necessary
                $addons = $order->addons;
                if (is_string($addons)) {
                    $addons = json_decode($addons, true); // Decode the JSON string to array
                }

                // If 'addons' is an array, calculate the total price of addons
                if (is_array($addons)) {
                    foreach ($addons as $addon) {
                        $addonPrice = isset($addon['price']) ? (float) $addon['price'] : 0;
                        $totalAddons += $addonPrice;
                    }
                }

                // Check if 'package' is a string and decode it if necessary
                $package = $order->package;
                if (is_string($package)) {
                    $package = json_decode($package, true); // Decode the JSON string to array
                }

                // If 'package' is an array and has a price, calculate the total package price
                if (is_array($package) && isset($package['offerprice'])) {
                    $totalPackage += (float) $package['offerprice'];
                }
            }

            $data['totalPackage'] = $totalPackage;
            $data['totalAddons'] = $totalAddons;


            if ($request->report === 'excel') {
                $paidClients = $clientQuery->get(); // Collection
                $addonsOrders = $paidClients->pluck('addonsOrders')->flatten();
            } else {
                $paidClients = $clientQuery->paginate(20); // LengthAwarePaginator
                $addonsOrders = $paidClients->getCollection()->pluck('addonsOrders')->flatten();
            }

            $data['paidClients'] = $paidClients;

            // Calculate the sum of 'total' for the current page's addonsOrders
            $data['pageTotalAmount'] = $addonsOrders->sum('total') ?? 0;

            $pageTotalPackage = 0;
            $pageTotalAddons = 0;

            foreach ($addonsOrders as $order) {
                // Check if 'addons' is a string and decode it if necessary
                $addons = $order->addons;
                if (is_string($addons)) {
                    $addons = json_decode($addons, true); // Decode the JSON string to array
                }

                // If 'addons' is an array, calculate the total price of addons
                if (is_array($addons)) {
                    foreach ($addons as $addon) {
                        $addonPrice = isset($addon['price']) ? (float) $addon['price'] : 0;
                        $pageTotalAddons += $addonPrice;
                    }
                }

                // Check if 'package' is a string and decode it if necessary
                $package = $order->package;
                if (is_string($package)) {
                    $package = json_decode($package, true); // Decode the JSON string to array
                }

                // If 'package' is an array and has a price, calculate the total package price
                if (is_array($package) && isset($package['offerprice'])) {
                    $pageTotalPackage += (float) $package['offerprice'];
                }
            }

            $data['pageTotalPackage'] = $pageTotalPackage;
            $data['pageTotalAddons'] = $pageTotalAddons;

            if ($request->report === 'excel') {
                $allClients = $clientQuery->get();

                $exportData = [
                    'paidClients' => $allClients,
                    'totalAmount' => $data['totalAmount'],
                    'totalPackage' => $totalPackage,
                    'totalAddons' => $totalAddons
                ];

                return Excel::download(new PaidClientsExport($exportData), 'paid_clients_report.xlsx');
            }

            return view('superadmin.paidClientsList', $data);
        } else {
            return redirect()->route('superadmin.index');
        }

    }

    public function landingPageClientsList(Request $request)
    {
        if (canSuperStaffAccess('landing_page_clients')) {
            $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
            $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
            $search = $request->search;
            $website = $request->website;
            $type = $request->type;

            $data['urls'] = "landing-page-clients";
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
            $data['website'] = $website;
            $data['type'] = $type;
            $data['search'] = $search;

            $clientQuery = User::query();
            $clientQuery->whereIn('type', ['admin', 'affiliate', 'dropshipper']);

            $clientQuery->with([
                'getStore' => function ($query) {
                    $query->where('status', "active");
                }
            ])->withCount('getStore as total_store_count');

            if ($from_date && !$to_date) {
                $clientQuery->where('created_at', '>=', $from_date->startOfDay());
            } elseif (!$from_date && $to_date) {
                $clientQuery->where('created_at', '<=', $to_date->endOfDay());
            } elseif ($from_date && $to_date) {
                $clientQuery->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
            }

            // Search logic
            if (!empty($search)) {
                $clientQuery->where(function ($query) use ($search) {
                    if (is_numeric($search)) {
                        $query->where('id', $search);
                    } else {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhereHas('store', function ($subQuery) use ($search) {
                                $subQuery->where('name', 'like', "%$search%");
                            });
                    }

                });
            }

            if (!empty($website)) {
                $clientQuery->where('register_from', $website);
            }

            if (!empty($type)) {
                $clientQuery->where('paid_registration', $type);
            }

            // Exclude specific plan IDs
            $clientQuery->orderBy("created_at", "DESC");

            $allClients = (clone $clientQuery)->get();
            // Count total
            $data['totalClients'] = $allClients->count();
            // Count Active / Inactive
            $data['activeCount'] = $allClients->filter(function ($client) {
                return $client->total_store_count > 0;
            })->count();
            $data['inactiveCount'] = $allClients->filter(function ($client) {
                return $client->total_store_count <= 0;
            })->count();

            $data['paidClientsExport'] = $clientQuery->get()->map(function ($client, $key) {
                return [
                    'SL' => $key + 1,
                    'Name' => $client->name ?? 'Empty',
                    'StoreName' => $client->getStore->name ?? '',
                    'Phone' => $client->phone ?? '',
                    'UserID' => $client->id ?? '',
                    'Plan' => $client->getStore->getPlan->name ?? 'empty',
                    'ActiveDate' => isset($client->getStore->purchase_date) ? date('j M, Y', strtotime($client->getStore->purchase_date)) : "",
                    'ExpireDate' => isset($client->getStore->expiry_date) ? date('j M, Y', strtotime($client->getStore->expiry_date)) : "",
                ];
            });

            // Exclude specific plan IDs and group by user
            $paidClients = $clientQuery->paginate(20);
            //            dd($paidClients);
            $data['paidClients'] = $paidClients;

            $data['fromWebsite'] = User::whereNotNull('register_from')->distinct()->pluck('register_from');

            $data['message'] = WhatsAppMessage::first();

            return view('superadmin.landingPageClientsList', $data);
        } else {
            return redirect()->route('superadmin.index');
        }

    }

    public function addonSellReport(Request $request)
    {
        if (canSuperStaffAccess('paid_clients')) {
            $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
            $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
            $search = $request->search;
            $type = $request->type ?? "all";
            $due_status = $request->due_status ?? null;

            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
            $data['type'] = $type;
            $data['search'] = $search;
            $data['due_status'] = $due_status;

            // default summary values
            $data['pageTotalAmount'] = 0;
            $data['pagePaidAmount'] = 0;
            $data['pageDueAmount'] = 0;
            $data['totalAmount'] = 0;
            $data['totalPaidAmount'] = 0;
            $data['totalDueAmount'] = 0;

            if ($type == "module") {
                $clientQuery = ModulusPayment::query();

                if ($from_date && !$to_date) {
                    $clientQuery->where('created_at', '>=', $from_date->startOfDay());
                } elseif (!$from_date && $to_date) {
                    $clientQuery->where('created_at', '<=', $to_date->endOfDay());
                } elseif ($from_date && $to_date) {
                    $clientQuery->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
                }

                $clientQuery->where('status', 1)->with(['store', 'module']);

                if (!empty($search)) {
                    $clientQuery->where(function ($query) use ($search) {
                        $query->where('store_id', $search)
                            ->orWhere('modulus_id', $search)
                            ->orWhereHas('store', function ($subQuery) use ($search) {
                                $subQuery->where('name', 'like', "%$search%")
                                    ->orWhere('url', 'like', "%$search%");
                            })
                            ->orWhereHas('module', function ($subQuery) use ($search) {
                                $subQuery->where('name', 'like', "%$search%")
                                    ->orWhere('title', 'like', "%$search%");
                            });
                    });
                }

                $clientQuery->where("price", ">", 0)->orderBy("id", "DESC");

                $data['totalAmount'] = $clientQuery->sum('price') ?? 0;

                $moduleList = $clientQuery->paginate(20);
                $data['moduleList'] = $moduleList;
                $data['pageTotalAmount'] = $moduleList->getCollection()->sum('price') ?? 0;
            } else {
                $clientQuery = AddonsOrder::query();

                if ($from_date && !$to_date) {
                    $clientQuery->where('created_at', '>=', $from_date->startOfDay());
                } elseif (!$from_date && $to_date) {
                    $clientQuery->where('created_at', '<=', $to_date->endOfDay());
                } elseif ($from_date && $to_date) {
                    $clientQuery->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
                }

                $clientQuery->where('status', 'Complete');

                if (!empty($due_status) && in_array($due_status, ['paid', 'partial_due', 'due', 'cleared'])) {
                    $clientQuery->where('due_amount_status', $due_status);
                }

                if (!empty($type)) {
                    switch ($type) {
                        case 'addon':
                            $extraData = clone $clientQuery;

                            $extraData = $extraData->whereRaw("JSON_LENGTH(addons) > 0");

                            if (!empty($search)) {
                                $extraData->whereRaw("
                                LOWER(JSON_UNQUOTE(JSON_EXTRACT(addons, '$[0].title'))) LIKE LOWER(?)",
                                    ["%$search%"]
                                );
                            }

                            $extraData = $extraData->get();

                            $groupedData = [];

                            foreach ($extraData as $item) {
                                $addons = $item->addons;

                                if (is_string($addons)) {
                                    $addons = json_decode($addons, true);
                                }

                                if (!is_array($addons)) {
                                    continue;
                                }

                                foreach ($addons as $addon) {
                                    if (is_array($addon) && isset($addon['title'], $addon['price'])) {
                                        $title = $addon['title'];
                                        $price = (float) ($addon['price'] ?? 0);

                                        if (!is_null($title)) {
                                            if (isset($groupedData[$title])) {
                                                $groupedData[$title]['total_sales']++;
                                                $groupedData[$title]['total_price'] += $price;
                                            } else {
                                                $groupedData[$title] = [
                                                    'name' => $title,
                                                    'total_sales' => 1,
                                                    'total_price' => $price,
                                                ];
                                            }
                                        }
                                    }
                                }
                            }

                            $data['extraData'] = array_values($groupedData);
                            break;

                        case 'package':
                            $extraData = clone $clientQuery;

                            $extraData = $extraData->whereNotNull('package')
                                ->whereRaw("JSON_LENGTH(package) > 0");

                            if (!empty($search)) {
                                $extraData->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(package, '$.name')) LIKE ?", ["%$search%"]);
                            }

                            $data['extraData'] = $extraData->selectRaw("
                            JSON_UNQUOTE(JSON_EXTRACT(package, '$.name')) as name,
                            COUNT(*) as total_sales,
                            SUM(JSON_UNQUOTE(JSON_EXTRACT(package, '$.price'))) as total_price
                        ")
                                ->groupBy('name')
                                ->get();
                            break;

                        default:
                            $clientQuery->with(['user', 'store']);

                            if (!empty($search)) {
                                $clientQuery->where(function ($query) use ($search) {
                                    $query->where('store_id', $search)
                                        ->orWhere('user_id', $search)
                                        ->orWhereHas('store', function ($subQuery) use ($search) {
                                            $subQuery->where('name', 'like', "%$search%")
                                                ->orWhere('url', 'like', "%$search%");
                                        })
                                        ->orWhereHas('user', function ($subQuery) use ($search) {
                                            $subQuery->where('name', 'like', "%$search%")
                                                ->orWhere('phone', 'like', "%$search%")
                                                ->orWhere('email', 'like', "%$search%");
                                        });
                                });
                            }

                            $clientQuery->where("total", ">", 0)->orderBy("id", "DESC");

                            $summaryQuery = clone $clientQuery;

                            $data['totalAmount'] = $summaryQuery->sum('total') ?? 0;
                            $data['totalPaidAmount'] = $summaryQuery->sum('paid_amount') ?? 0;
                            $data['totalDueAmount'] = $summaryQuery->sum('due_amount') ?? 0;

                            $addonsList = $clientQuery->paginate(20);
                            $data['addonsList'] = $addonsList;

                            $data['pageTotalAmount'] = $addonsList->getCollection()->sum('total') ?? 0;
                            $data['pagePaidAmount'] = $addonsList->getCollection()->sum('paid_amount') ?? 0;
                            $data['pageDueAmount'] = $addonsList->getCollection()->sum('due_amount') ?? 0;
                            break;
                    }
                }
            }

            return view('superadmin.addonSellReport', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function clientActivities(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $data['urls'] = "clients";
            $data['analyticsInfo'] = AdminUserAnalytics::orderBy('updated_at', 'DESC')->groupBy('store_id')->take(100)->get();

            $startTime = Carbon::now()->subDays(30);
            $end_date = Carbon::now();


            $last30Days = AdminUserAnalytics::where(
                'updated_at',
                '>=',
                date('Y-m-d H:m:s', strtotime($startTime)),
                'and',
                'updated_at',
                '<=',
                date('Y-m-d H:m:s', strtotime($end_date))
            )->groupBy('store_id')->get();

            $data['last30Days'] = $last30Days->count();

            return view('superadmin.clientActivities', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function getClientActivitiesData(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $activitiesQuery = AdminUserAnalytics::with(['getStore', 'getUser', 'getStore.activityComments', 'getAdminAnalytics']);

            // Search filter
            if ($request->has('search') && $request->search['value'] != '') {
                $searchValue = $request->search['value'];
                $activitiesQuery->where(function ($query) use ($searchValue) {
                    $query->where('id', 'LIKE', "%{$searchValue}%")
                        ->orWhereHas('getUser', function ($subQuery) use ($searchValue) {
                            $subQuery->where('name', 'LIKE', "%{$searchValue}%")
                                ->orWhere('phone', 'LIKE', "%{$searchValue}%");
                        })
                        ->orWhereHas('getStore', function ($subQuery) use ($searchValue) {
                            $subQuery->where('name', 'LIKE', "%{$searchValue}%")
                                ->orWhere('url', 'LIKE', "%{$searchValue}%");
                        });
                });
            }

            // Total records
            $totalRecords = AdminUserAnalytics::count();

            // Apply sorting
//            if ($request->has('order')) {
//                $columnIndex = $request->order[0]['column'];
//                $columnName = $request->columns[$columnIndex]['data'];
//                $columnDir = $request->order[0]['dir'];
//                $activitiesQuery->orderBy($columnName, $columnDir);
//            }
            // Apply sorting - FIXED VERSION
            if ($request->has('order') && !empty($request->order[0]['column'])) {
                $columnIndex = $request->order[0]['column'];
                $columnName = $request->columns[$columnIndex]['data'];
                $columnDir = $request->order[0]['dir'];

                // Map DataTables columns to actual database columns
                $sortableColumns = [
                    'id' => 'admin_user_analytics.id',
                    'user_name' => 'users.name',
                    'store_name' => 'stores.name',
                    'created_at' => 'admin_user_analytics.created_at'
                    // Add other sortable columns
                ];

                if (array_key_exists($columnName, $sortableColumns)) {
                    if (str_contains($sortableColumns[$columnName], '.')) {
                        // Handle relationship sorting
                        $parts = explode('.', $sortableColumns[$columnName]);
                        $relation = $parts[0];
                        $relationColumn = $parts[1];

                        $activitiesQuery->leftJoin($relation, "admin_user_analytics.{$relation}_id", '=', "{$relation}.id")
                            ->orderBy("{$relation}.{$relationColumn}", $columnDir);
                    } else {
                        // Regular column sorting
                        $activitiesQuery->orderBy($sortableColumns[$columnName], $columnDir);
                    }
                }
            }

            // Pagination
            $perPage = $request->get('length', 10);
            $activities = $activitiesQuery->skip($request->start)
                ->take($request->length)
                ->get();

            // Prepare data
            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $activities // Return items for the current page
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    public function clientActivitiesFollowUp(Request $request)
    {
        if (Auth::user()->type == 'superstaff') {
            $superstaff = Superstaff::where('uid', Auth::user()->id)->first();
            $superrole = Superrole::where('id', $superstaff->role_id)->first();
            $permissionss = explode(',', $superrole->permission);
            foreach ($permissionss as $key => $prs) {
                if ($prs == 'clients') {
                    $clientsPer = 1;
                    $data['clientsPer'] = 1;
                }

                if ($prs == 'clients_Activities') {
                    $clients_ActivitiesPer = 1;
                    $data['clients_ActivitiesPer'] = 1;
                }

                if ($prs == 'clients_Follow_Up') {
                    $clients_Follow_UpPer = 1;
                    $data['clients_Follow_UpPer'] = 1;
                }

                if (false) {
                    $clients_Follow_UpPer = 0;
                    $data['clients_Follow_UpPer'] = 0;
                }
            }
        }

        if ((isset($clients_Follow_UpPer) && $clients_Follow_UpPer == "1") || Auth::user()->type == 'superadmin') {
            $data['urls'] = "clients";
            $data['analyticsInfo'] = AdminUserAnalytics::with('getUser', 'getStore')->orderBy(
                'updated_at',
                'DESC'
            )->groupBy('store_id')->paginate(10);
            $analytics = AdminUserAnalytics::paginate(10);
            //            dd($analytics, $data['analyticsInfo']);

            $startTime = Carbon::now()->subDays(30);
            $end_date = Carbon::now();

            $last30Days = AdminUserAnalytics::with('getUser', 'getStore')->where(
                'updated_at',
                '>=',
                date('Y-m-d H:m:s', strtotime($startTime)),
                'and',
                'updated_at',
                '<=',
                date('Y-m-d H:m:s', strtotime($end_date))
            )->groupBy('store_id')->get();
            $data['last30Days'] = $last30Days;


            return view('superadmin.clientFollowUp', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function clientActivitiesFollowUpSearch(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $data['urls'] = "clients";
            $data['analyticsInfo'] = AdminUserAnalytics::orderBy(
                'updated_at',
                'DESC'
            )->groupBy('store_id')->join('users', 'users.store_id', '=', 'admin_user_analytics.store_id')
                ->where('users.id', 'LIKE', '%' . $request->search . '%')
                ->orWhere('users.phone', 'LIKE', '%' . $request->search . '%')
                ->orWhere('users.name', 'LIKE', '%' . $request->search . '%')
                ->select(
                    'admin_user_analytics.id',
                    'admin_user_analytics.store_id',
                    'admin_user_analytics.user_id',
                    'admin_user_analytics.updated_at'
                )
                ->get();

            return view('superadmin.clientFollowUpSearch', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function clientlistSearch(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $data['urls'] = "clients";

            $search = $request->search;
            $idSearch = $request->idSearch;

            $cacheKey = 'clients_search_' . md5($search . '_' . $idSearch . '_' . $request->get('page', 1));

            $data['clients'] = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($search, $idSearch) {
                $clientQuery = User::with(['customerInfo', 'getStore'])
                    ->whereIn('type', ['admin', 'affiliate', 'dropshipper']);

                if (!empty($search)) {
                    if ($idSearch == "true") {
                        $clientQuery->where('id', $search);
                    } else {
                        $clientQuery->where(function ($q) use ($search) {
                            $q->where('id', 'like', $search . '%')
                                ->orWhere('phone', 'like', "$search%")
                                ->orWhere('name', 'like', "$search%")
                                ->orWhere('email', 'like', "$search%")
                                ->orWhere('comment', 'like', "$search%")
                                ->orWhereHas('getStore', function ($q2) use ($search) {
                                    $q2->where('name', 'like', "$search%")
                                        ->orWhere('url', 'like', "$search%");
                                });
                        });
                    }
                }

                return $clientQuery->orderBy('id', 'DESC')->paginate(10);
            });

            return view('superadmin.clientSearch', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function clientlistSearchByfollowUpDate(Request $request)
    {

        if (canSuperStaffAccess('clients')) {

            $data['urls'] = "clients";
            $data['clientComments'] = ClientActivitieComments::where('follow_up_date', $request->followUp)->get();
            $startTime = Carbon::now()->subDays(30);
            $end_date = Carbon::now();
            $last30Days = AdminUserAnalytics::where(
                'updated_at',
                '>=',
                date('Y-m-d H:m:s', strtotime($startTime)),
                'and',
                'updated_at',
                '<=',
                date('Y-m-d H:m:s', strtotime($end_date))
            )->groupBy('store_id')->get();
            $data['last30Days'] = $last30Days->count();

            return view('superadmin.clientSearchByfollowUpDate', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function clientActivitiesByDate(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $data['urls'] = "clients";

            // Convert formdate and enddate to proper datetime format
            $startDate = date('Y-m-d H:i:s', strtotime($request->formdate));
            $endDate = date('Y-m-d H:i:s', strtotime($request->enddate));

            // Fetch analytics info with date range using whereBetween
            $data['analyticsInfo'] = AdminUserAnalytics::whereBetween('updated_at', [$startDate, $endDate])
                ->groupBy('store_id')
                ->paginate(10);

            // Set the start and end times in the data array
            $data['startTime'] = $startDate;
            $data['end_date'] = $endDate;

            // Count stores' activities in the last 30 days
            $data['last30Days'] = AdminUserAnalytics::whereBetween('updated_at', [$startDate, $endDate])
                ->groupBy('store_id')
                ->count();

            // Return the view with the data
//            return view('superadmin.clientActivitiesByDate', $data);
            return view('superadmin.clientFollowUp', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function clientActivitiesComments(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $clientComment = new ClientActivitieComments();
            $clientComment->user_id = $request->user_id;
            $clientComment->store_id = $request->store_id == 0 ? null : $request->store_id;
            $clientComment->short_comment = $request->clientStatus;
            $clientComment->follow_up_date = $request->followUpData;
            $clientComment->follow_up_time = $request->followUpTime;
            $clientComment->comment = $request->comment;
            $clientComment->comment_by = Auth::user()->name;
            $clientComment->save();
            $comments = ClientActivitieComments::where('store_id', $clientComment->store_id)->orderBy(
                'updated_at',
                'DESC'
            )->get();

            return view('superadmin.clientComments', compact('comments'));
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function affiliateMarketing()
    {
        if (canSuperStaffAccess('clients')) {
            $data['urls'] = "affiliateMarketing";
            $data['users'] = \DB::table('users')
                ->leftJoin('affiliate_exam_infos', 'users.id', '=', 'affiliate_exam_infos.user_id')
                ->select('users.*')
                ->leftJoin('referrals', 'users.referral', '=', 'referrals.referral_id')
                ->select('users.*', \DB::raw('COUNT(referrals.id) as referral_count'))
                ->whereIn('users.type', ['admin', 'affiliate'])
                ->groupBy('users.id')
                ->orderBy('users.id', 'DESC')
                ->get();

            return view('superadmin.affiliateMarketing.index', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function storeComment(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $urls = "clients";
            $clients = User::find($request->id);
            $clients->comment = $request->comment;
            $clients->comment_date = Carbon::now();
            $clients->update();

            return response()->json(['data' => $clients]);
        } else {
            return response()->json('error');
        }
    }

    public function referralCommissionUpdate(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $urls = "affiliateMarketing";
            $clients = User::find($request->id);
            $clients->referral_commission = $request->comment;
            $clients->update();

            return response()->json(['data' => $clients]);
        } else {
            return response()->json('error');
        }
    }

    public function clientdatefilter(Request $request)
    {
        $urls = "clients";
        $from = $request->formdate ? Carbon::parse($request->formdate) : null;
        $to = $request->enddate ? Carbon::parse($request->enddate) : null;
        $category = $request->category;

        $clientQuery = User::with([
            'stores',
            'customerInfo',
            'addonOrder' => function ($query) {
                $query->select('id', 'user_id', 'status');
            }
        ])->whereIn('type', ['admin', 'affiliate', 'dropshipper']);

        if ($from && !$to) {
            $clientQuery->where('created_at', '>=', $from->startOfDay());
        } elseif (!$from && $to) {
            $clientQuery->where('created_at', '<=', $to->endOfDay());
        } elseif ($from && $to) {
            $clientQuery->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()]);
        }

        if (!empty($category)) {
            $categoryName = DB::table('business_categories')->where('id', $category)->value('name');

            $clientQuery->whereHas('stores', function ($query) use ($category, $categoryName) {
                $query->where(function ($q) use ($category, $categoryName) {
                    $q->where('stores.category_id', $category)
                        ->orWhere(function ($q2) use ($categoryName) {
                            $q2->whereNull('stores.category_id')
                                ->where('stores.type', $categoryName);
                        });
                });
            });
        }

        $clientQuery = $clientQuery->orderBy('id', 'DESC');


        $activeCount = 0;
        $inactiveCount = 0;
        $clientsExport = (clone $clientQuery)->get()->map(function ($client, $key) use (&$activeCount, &$inactiveCount) {
            // Count active clients (those with store name)
            if (!empty($client->getStore->name)) {
                $activeCount++;
            }

            if (empty($client->getStore->name)) {
                $inactiveCount++;
            }

            // Return the mapped data for export
            return [
                'SL' => $key + 1,
                'Name' => $client->name ?? 'Empty',
                'StoreName' => $client->getStore->name ?? '',
                'Phone' => isset($client->phone) && !empty($client->phone) ? $client->phone : $client->email ?? '',
                'UserType' => $client->type ?? '',
                'UserID' => $client->id ?? '',
                'Seller' => $client->customerInfo->staff->name ?? 'empty',
                'CreatedDate' => date('j M, Y', strtotime($client->created_at ?? '2000-01-01')),
            ];
        });

        $clients = (clone $clientQuery)->paginate(20);
        $totalClients = $clients->total();

        //        $inactiveCount = $totalClients - $activeCount;
//        $activeCount = (clone $clientQuery)->get()->filter(function ($client) {
//            return !empty($client->getStore->name);
//        })->count();
//        $inactiveCount = $allClients->filter(function ($client) {
//            return empty($client->getStore->name);
//        })->count();


        $staff = Superstaff::where("status", "active")->select("id", "uid", "name", "new_commission", "renew_commission", "setup_commission")->get();
        $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();

        return view('superadmin.clientlist', [
            "urls" => $urls,
            "clients" => $clients,
            "totalClients" => $totalClients,
            "activeCount" => $activeCount,
            "inactiveCount" => $inactiveCount,
            "clientsExport" => $clientsExport,
            "formdate" => $request->formdate,
            "enddate" => $request->enddate,
            "category" => $request->category,
            "categories" => $categories,
            "staff" => $staff,
        ]);
    }

    public function planorderlist()
    {
        if (canSuperStaffAccess('plan_order')) {
            $urls = "planorder";
            $orders = Planorder::all();

            return view('superadmin.planorder')->with('orders', $orders)->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function customer()
    {
        if (canSuperStaffAccess('customer')) {
            $urls = "supercustomer";
            $customer = User::where('type', 'customer')->orWhere('type', 'walking_customer')->orderBy(
                'id',
                'DESC'
            )->get();
            return view('superadmin.customer.index')->with('urls', $urls)->with('data', $customer);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function deleteclient(Request $request)
    {
        if (canSuperStaffAccess('customer')) {
            $user = User::find($request->id);
            $cus = Customer::where('uid', $request->id)->delete();
            $user->delete();
            Session::flash('message', 'Successfully Delete Clients');
            return back();
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function productdatefilter(Request $request)
    {
        $urls = "planorder";
        $from = $request->formdate;
        $to = $request->enddate;
        $orders = Planorder::whereBetween('created_at', [$from, $to])->get();

        return view('superadmin.planorder')->with('orders', $orders)->with('urls', $urls)->with(
            'from',
            $from
        )->with('to', $to);
    }

    public function planinvoice($id)
    {
        if (canSuperStaffAccess('plan_order')) {
            $plan = Planorder::find($id);
            return view('superadmin.planorderinvoice')->with('plan', $plan);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function domainlist()
    {
        if (canSuperStaffAccess('domain')) {
            $urls = "domainlist";
            $domain = Domain::all();
            return view('superadmin.domainlist')->with('urls', $urls)->with('domain', $domain);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function deleteDomainList()
    {
        if (canSuperStaffAccess('domain')) {
            $domain = Domain::where("deleteRequest", 1)->paginate(10);

            return view('superadmin.deletedomainlist', ['domain' => $domain]);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function domainrequest()
    {
        if (canSuperStaffAccess('domain_request')) {
            $domain = Domain::with('store')->where('status', 'Requested')->orWhere('status', 'Processing')->orWhere('status', 'Buying Request')->orderBy('id', 'DESC')->get();

            return view('superadmin.domainrequest', ['domain' => $domain]);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function domainBuyingList()
    {
        if (canSuperStaffAccess('domain_request')) {
            $perPage = 10;

            $domain = Domain::where('buy_domain', 1)->orderBy('id', 'DESC')->paginate($perPage);

            return view('superadmin.domainBuyinglist', ['domain' => $domain]);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function domainrequestaccept($id)
    {
        if (canSuperStaffAccess('domain_request')) {
            $domain = Domain::find($id);
            $domain->status = "Active";
            $domain->save();
            $dm = explode('.', $domain->name);
            if (isset($dm) && count($dm) == 2) {
                $user = User::find($domain->uid);
                $user->domain = $domain->name;
                $user->active_cpanel = "active";
                $user->save();
                $store = Store::find($domain->store_id);
                $store->webmail_status = "active";
                $store->save();
            }
            Session::flash('message', 'Domain Accepted Successfully.');
            return back();
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function domainConnectRequest($id)
    {
        try {
            $domainData = Domain::where('id', $id)->first();
            $domain = $domainData->name ?? "";

            if (!empty($domain)) {
                $api = new cPanelApi("ebitans.com", "ebitans", env("HOST_POINT"));

                $responseData = null;

                if (checkDomainConnectWithCpanel()) {
                    // Add Domain in cpanel
                    if (is_null($domainData->connect_status)) {
                        $data = $api->addDomain($domain);
                        $responseData = json_decode($data);

                        if (!isset($responseData->cpanelresult->error)) {
                            $domainData->connect_status = 1;
                            $domainData->remark = "Domain add in cpanel";
                            $domainData->update();
                        } else {
                            $error1 = "already exists in the userdata";
                            if (strpos($responseData->cpanelresult->error, $error1) !== false) {
                                $domainData->connect_status = 1;
                                $domainData->remark = "Domain add in cpanel";
                                $domainData->update();
                            } else {
                                $domainData->remark = "Failed adding domain in cpanel";
                                $domainData->update();

                                Session::flash('error', 'Your Name Server not update yet. Please update your name server.');
                                return redirect()->back();
                            }
                        }
                    }

                    // Add Sub daomain in cpanel
                    if (!is_null($domainData->connect_status) && $domainData->connect_status == 1) {
                        $data = $api->addSubdomain($domain);
                        $responseData = json_decode($data);
                        if (!isset($responseData->cpanelresult->error)) {
                            $domainData->connect_status = 2;
                            $domainData->remark = "Sub domain add in cpanel";
                            $domainData->update();
                        } else {
                            $error1 = "already exists in the userdata";
                            if (strpos($responseData->cpanelresult->error, $error1) !== false) {
                                $domainData->connect_status = 2;
                                $domainData->remark = "Sub domain add in cpanel";
                                $domainData->update();
                            } else {
                                $domainData->remark = "Failed adding sub domain in cpanel";
                                $domainData->update();

                                Session::flash('error', 'Your Name Server not update yet. Please update your name server.');
                                return redirect()->back();
                            }
                        }

                        // For update MX Record
                        $api->updateMxToWebmail($domain);
                    }


                    if (!is_null($domainData->connect_status) && $domainData->connect_status == 2) {
                        $ARecordZone = ZoneRecord::where('type', 'A')->pluck('value');
                        $CNAMERecord = ZoneRecord::where('type', 'CNAME')->pluck('value');

                        if (count($ARecordZone) <= 0 || count($CNAMERecord) <= 0) {
                            $domainData->remark = "Zone type like A,CNAME Not found in the database";
                            $domainData->update();

                            Session::flash('error', 'Domain connect failed. Please contact to the support');
                            return redirect()->back();
                        }

                        $zoneData = [
                            [
                                "record_type" => "A",
                                "record_value" => $ARecordZone ?? [],
                            ],
                            [
                                "record_type" => "CNAME",
                                "record_value" => $CNAMERecord ?? [],
                            ],
                        ];

                        $zoneErrorStatus = false;

                        // Delete zone record (A, CNAME, AAAA)
                        $deleteTypes = array_merge(
                            array_column($zoneData, 'record_type'),
                            ['AAAA'] // add AAAA manually
                        );

                        foreach ($deleteTypes as $type) {
                            $result = $api->deleteDomainZoneEditorRecord($domain, $type);
                            if (!$result) {
                                $zoneErrorStatus = true;
                                break;
                            }
                        }

                        // Check error status is false then proceed
                        if (!$zoneErrorStatus) {
                            // Add zone record
                            foreach ($zoneData as $zone) {
                                $successStatus = false;
                                foreach ($zone['record_value'] as $value) {
                                    $data = $api->addZoneEditor($domain, $zone['record_type'], $value);
                                    $responseData = json_decode($data);

                                    if (isset($responseData->cpanelresult->data[0]->result->status) && $responseData->cpanelresult->data[0]->result->status == 0) {
                                        $domainData->remark = "Domain not add in Cpanel Zone";
                                        $domainData->update();

                                        Session::flash('error', 'Domain connect failed. Please contact to the support');
                                        return redirect()->back();
                                    }

                                    if (!isset($responseData->cpanelresult->error)) {
                                        $successStatus = true;
                                        break;
                                    }
                                }
                                if (!$successStatus) {
                                    $zoneErrorStatus = true;
                                    break;
                                }
                            }
                        }

                        if ($zoneErrorStatus) {
                            $domainData->remark = "Domain not add in Cpanel Zone";
                            $domainData->update();

                            Session::flash('error', 'Domain connect failed. Please contact to the support');
                            return redirect()->back();
                        } else {
                            $domainData->connect_status = 3;
                            $domainData->remark = "Successfully domain add in Cpanel Zone";
                            $domainData->update();
                        }
                    }
                }

                if ($domainData->connect_status == 3 || !checkDomainConnectWithCpanel()) {
                    $vercelStatus = false;
                    $domain_name = cleanDomain($domain);
                    $data = $this->addDomainInVercel($domain_name);
                    $vercelError = null;

                    if (!is_null($data)) {
                        $responseData = json_decode($data);

                        if (!isset($responseData->error)) {
                            $domain_name = cleanDomain($domain);
                            $domain = "www.$domain_name"; // Second Time Add With www
                            $data = $this->addDomainInVercel($domain);
                            if (!is_null($data)) {
                                $responseData = json_decode($data);
                                if (!isset($responseData->error)) {
                                    $vercelStatus = true;
                                }
                            }
                        } else {
                            if ($responseData->error->code == "domain_already_in_use") {
                                $vercelError = $responseData->error->message ?? "Domain already use in vercel";
                            }
                        }
                    }

                    if ($vercelStatus) {
                        // Active domain
                        $domainData->status = "Active";
                        $domainData->connect_status = 4;
                        $domainData->remark = "Successfully add domain in Cpanel and Vercel Both";
                        $domainData->update();

                        $this->activeStoreDomain($domainData);

                        $dm = explode('.', $domainData->name);
                        if (isset($dm) && count($dm) == 2) {
                            $user = User::find($domainData->uid);
                            $user->domain = $domainData->name;
                            $user->active_cpanel = "active";
                            $user->save();

                            $store = Store::find($domainData->store_id);
                            $store->webmail_status = "active";
                            $store->save();
                        }

                        Session::flash('success', 'Domain connect successfully');
                        return redirect()->back();
                    } else {
                        $domainData->remark = $vercelError ?? "Domain not add in Vercel";
                        $domainData->update();

                        Session::flash('error', 'Domain connect failed. Please contact to the support');
                        return redirect()->back();
                    }
                }

                if ($domainData->connect_status >= 4) {
                    Session::flash('success', 'You already successfully connect this domain.');
                    return redirect()->back();
                }

                $domainData->remark = "Unknown remark";
                $domainData->update();
                Session::flash('error', 'Domain connect failed. Please contact to the support');
                return redirect()->back();
            }

            Session::flash('error', 'Please provide domain name');
            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('error', 'Something Went Wrong. Please try again');
            return redirect()->back();
        }
    }

    /**
     * Add domain in vercel
     *
     * @param $domain
     * @return bool|string|null
     */
    public function addDomainInVercel($domain_name)
    {
        // Vercel API token
        $vercel_token = env("VERCEL_API_TOKEN", "fRIBqCNQ7YOPBBRVuUhSQfBt");
        $project_id = env("VERCEL_PROJECT_ID", "prj_I1ptfsAENdijfsmMd0ggJayvBxVn");

        if (empty($vercel_token) && empty($project_id) && empty($domain_name)) {
            return null;
        }

        // Vercel API URL for adding a domain
        $url = "https://api.vercel.com/v9/projects/$project_id/domains";

        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = json_encode(['name' => $domain_name]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Set headers
        $headers = [
            "Authorization: Bearer $vercel_token",
            "Content-Type: application/json"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute API request
        $result = curl_exec($ch);

        if ($result == false) {
            error_log("curl_exec threw error \"" . curl_error($ch) . "\" for $url");
        }
        curl_close($ch);
        return $result;
    }


    /**
     * Active domain for store
     *
     * @param $domain
     * @return void
     */
    public function activeStoreDomain($domain)
    {
        $store = Store::find($domain->store_id);
        if (!is_null($store) && isset($store->plan_id) && ($store->plan_id != 6 && $store->plan_id != 9)) {
            $store->url = $domain->name;
            $store->save();

            $user = User::where('id', $store->user_id)->first();
            if ($user) {
                $user->domain = $domain->name;
                $user->save();
            }
        }
    }

    public function domainrequestreject($id)
    {
        if (canSuperStaffAccess('domain_request')) {
            $domain = Domain::find($id);
            $domain->status = "Deactive";
            $domain->save();
            Session::flash('message', 'Domain Rejected Successfully.');
            return back();
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function domainrequestprocessing($id)
    {
        if (canSuperStaffAccess('domain_request')) {
            $domain = Domain::find($id);
            $domain->status = "Processing";
            $domain->save();
            Session::flash('message', 'Domain Request Placed Processing.');

            return redirect()->back();
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function domainBuyRequest($id)
    {
        if (canSuperStaffAccess('domain_request')) {
            $domain = Domain::find($id);
            $domain->status = "Processing";
            $domain->buy_domain = 1;
            $domain->save();
            Session::flash('message', 'Domain Request Placed Processing.');

            return redirect()->back();
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function deleteDomainFromCpanel($id)
    {
        $domain = Domain::find($id);
        if (isset($domain)) {
            $domain->deleteRequest = NULL;
            $domain->save();

            $store = Store::where("id", $domain->store_id ?? "")->first();
            if (isset($store)) {
                $store->isDomainDelete = 1;
                $store->save();
            }
        }

        Session::flash('message', 'Domain Removed From Cpanel Successfully.');
        return back();
    }

    public function deletedomain($id)
    {
        $domain = Domain::find($id);
        $domain->delete();

        Session::flash('message', 'Domain Deleted Successfully.');
        return back();
    }

    public function designlist()
    {
        if (canSuperStaffAccess('design')) {
            $urls = "designlist";
            $design = Designlist::orderBy('id', 'DESC')->paginate(20);
            /*get design list type*/
            $types = Designlist::select('type')->distinct()->orderBy('type', 'ASC')->get();

            return view('superadmin.design.index')
                ->with('urls', $urls)
                ->with('types', $types)
                ->with('design', $design);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function business_category()
    {
        if (canSuperStaffAccess('design')) {
            $urls = "designlist";
            $categories = BusinessCategory::orderBy('name', 'ASC')->get();
            $parentCategories = BusinessCategory::whereNull("parent_id")->orderBy('name', 'ASC')->get();

            return view('superadmin.businessCategory.index', [
                'urls' => $urls,
                'categories' => $categories,
                'parentCategories' => $parentCategories,
            ]);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function business_category_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:business_categories,name',
            'slug' => 'string|max:100'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $business_category = new BusinessCategory();
        $business_category->name = $request->name;
        $business_category->slug = $request->slug;
        $business_category->parent_id = !empty($request->parent_id) ? $request->parent_id : NULL;
        $business_category->save();

        Session::flash('message', 'Business Category Save Successfully');
        return redirect()->route('super_admin.business_category');
    }

    public function business_category_update(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'string|unique:business_categories,name,' . $id,
            'slug' => 'required|string|max:100'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the category by ID or fail
        $business_category = BusinessCategory::findOrFail($id);

        // Update the category with validated data
//        $business_category->update($validator->validated());
        $business_category->name = $request->name;
        $business_category->slug = $request->slug;
        $business_category->parent_id = $request->parent_id;
        $business_category->save();

        // Flash a success message to the session
        Session::flash('message', 'Business Category updated successfully');

        // Redirect to the business category index route
        return redirect()->route('super_admin.business_category');
    }

    public function designcreate()
    {
        if (canSuperStaffAccess('design')) {
            $urls = "designlist";

            /*get design list type*/
            $types = Designlist::select('type')->distinct()->orderBy('type', 'ASC')->get();

            /*get Business Categories*/
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();

            return view('superadmin.design.create')
                ->with('urls', $urls)
                ->with('types', $types)
                ->with('categories', $categories);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function designsave(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'category' => 'required',
            'value' => 'required|string',
            'title' => 'string',
            'title_color' => 'string',
            'subtitle' => 'string',
            'subtitle_color' => 'string',
            'button' => 'string',
            'button_color' => 'string',
            'button_bg_color' => 'string',
            'button1' => 'string',
            'button1_color' => 'string',
            'button1_bg_color' => 'string',
            'link' => 'string',
            'image_description' => 'string',
            'status' => 'required|in:on,off',
            'image' => 'file',
            'bg_image' => 'file',
        ], [
            "bg_image.required" => "Background image is required!"
        ]);

        // Custom validation logic
        $validator->after(function ($validator) use ($request) {
            $exists = Designlist::where('value', $request->value)
                ->where('type', $request->type)
                ->exists();

            if ($exists) {
                Session::flash('error', 'Value Already Taken');
                $validator->errors()->add('value', 'The combination of value and type exist.');
            }
        });

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $design = new Designlist;
        $design->name = $request->name;
        $design->type = $request->type;
        if (isset($request->category)) {
            $cat = implode(',', $request->category);
            $design->category = $cat;
        }

        $design->value = $request->value;
        if ($request->hasFile('image')) {
            $imgName = Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('design', $imgName);
            $design->image = $imgName;
        }
        if ($request->hasFile('bg_image')) {
            $bgImgName = Carbon::now()->timestamp . '.' . $request->bg_image->extension();
            $request->bg_image->storeAs('design', $bgImgName);
            $design->bg_image = $bgImgName;
        }
        if ($request->status == 'on') {
            $design->status = 'active';
        } else {
            $design->status = 'inactive';
        }
        if ($request->title != "") {
            $design->title = $request->title;
            $design->title_color = $request->title_color;
        }
        if ($request->subtitle != "") {
            $design->subtitle = $request->subtitle;
            $design->subtitle_color = $request->subtitle_color;
        }

        if ($request->button != "") {
            $design->button = $request->button;
            $design->button_color = $request->button_color;
            $design->button_bg_color = $request->button_bg_color;
        }

        if ($request->button1 != "") {
            $design->button1 = $request->button1;
            $design->button1_color = $request->button1_color;
            $design->button1_bg_color = $request->button1_bg_color;
        }

        $design->link = $request->link ?? NULL;

        $design->image_description = $request->image_description;
        $design->save();
        Session::flash('message', 'Design Save Successfully');
        return redirect()->route('superadmin.designlist');
    }

    public function changedesignstatus(Request $request)
    {
        $id = $request->id;
        $value = $request->value;
        $slider = Designlist::find($id);
        if (isset($slider) && $slider->status == 'active') {
            $slider->status = 'inactive';
        } else {
            $slider->status = "active";
        }
        $slider->save();
        $data = $slider;
        return response()->json($data);
    }

    public function changetemplatestatus(Request $request)
    {
        $id = $request->id;
        $value = $request->value;
        $slider = Template::find($id);
        if (isset($slider) && $slider->status == 'active') {
            $slider->status = 'inactive';
        } else {
            $slider->status = "active";
        }
        $slider->save();
        $data = $slider;

        return response()->json($data);
    }

    public function editdesign($id)
    {
        if (canSuperStaffAccess('design')) {
            $design = Designlist::find($id);
            $urls = "designlist";

            /*get design list type*/
            $types = Designlist::select('type')->distinct()->orderBy('type', 'asc')->get();

            /*get Business Categories*/
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();

            return view('superadmin.design.edit')
                ->with('urls', $urls)
                ->with('design', $design)
                ->with('categories', $categories)
                ->with('types', $types);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function updatedesign(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'category' => 'required',
            'value' => 'required|string',
            'title' => 'string',
            'title_color' => 'string',
            'subtitle' => 'string',
            'subtitle_color' => 'string',
            'button' => 'string',
            'button_color' => 'string',
            'button_bg_color' => 'string',
            'button1' => 'string',
            'button1_color' => 'string',
            'button1_bg_color' => 'string',
            'link' => 'string',
            'image_description' => 'string',
            'status' => 'required|in:on,off',
            'image' => 'file',
            'bg_image' => 'file',
        ], [
            "bg_image.required" => "Background image is required!"
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $design = Designlist::find($id);
        $design->name = $request->name;
        $design->type = $request->type;
        if (isset($request->category)) {
            $cat = implode(',', $request->category);
            $design->category = $cat;
        }
        $design->value = $request->value;
        if ($request->hasFile('image')) {
            if (isset($design->image) && !empty($design->image)) {
                $oldImagePath = public_path('assets/images/design') . '/' . $design->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $imgName = Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('design', $imgName);
            $design->image = $imgName;
        }
        if ($request->hasFile('bg_image')) {
            if (isset($design->bg_image) && !empty($design->bg_image)) {
                $oldImagePath = public_path('assets/images/design') . '/' . $design->bg_image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $bgImgName = Carbon::now()->timestamp . '.' . $request->bg_image->extension();
            $request->bg_image->storeAs('design', $bgImgName);
            $design->bg_image = $bgImgName;
        }
        if ($request->status == 'on') {
            $design->status = 'active';
        } else {
            $design->status = 'inactive';
        }
        if ($request->title != "") {
            $design->title = $request->title;
            $design->title_color = $request->title_color;
        }
        if ($request->subtitle != "") {
            $design->subtitle = $request->subtitle;
            $design->subtitle_color = $request->subtitle_color;
        }

        if ($request->button != "") {
            $design->button = $request->button;
            $design->button_color = $request->button_color;
            $design->button_bg_color = $request->button_bg_color;
        }

        if ($request->button1 != "") {
            $design->button1 = $request->button1;
            $design->button1_color = $request->button1_color;
            $design->button1_bg_color = $request->button1_bg_color;
        }

        $design->link = $request->link ?? NULL;

        $design->image_description = $request->image_description;
        $design->save();

        Session::flash('message', 'Design Update Successfully');
        return redirect()->route('superadmin.designlist');
    }

    public function deletedesign($id)
    {
        if (canSuperStaffAccess('design')) {
            $design = Designlist::find($id);
            $design->delete();

            Session::flash('message', 'Design Deleted Successfully');
            return redirect()->route('superadmin.designlist');
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function designtypefilter(Request $request)
    {
        $urls = "designlist";
        if ($request->type == 'all') {
            $design = Designlist::orderBy('id', 'DESC')->paginate(20);
        } else {
            $design = Designlist::where('type', $request->type)->orderBy('id', 'DESC')->paginate(20);
        }
        $type = $request->type ?? "";
        $types = Designlist::select('type')->distinct()->orderBy('type', 'ASC')->get();

        return view('superadmin.design.index')->with('design', $design)->with('urls', $urls)->with('types', $types)->with('stts', $type);
    }

    public function templates()
    {
        if (canSuperStaffAccess('template')) {
            $urls = "templates";
            $template = Template::orderBy('position', 'asc')->get();

            return view('superadmin.template.index')->with('templates', $template)->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function createtemplate()
    {
        if (canSuperStaffAccess('template')) {
            $urls = "templates";
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
            $designs = Designlist::where('status', 'active')
                ->orderBy('type', 'asc')
                ->orderBy('name', 'asc')
                ->get()
                ->groupBy('type');

            return view(
                'superadmin.template.create',
                compact('categories', 'designs')
            )
                ->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function savetemplate(Request $request)
    {
        ini_set('memory_limit', '2024M');
        ini_set('post_max_size', '2024M');
        ini_set('upload_max_filesize', '2024M');
        ini_set('max_input_time', 36000); // 10 houres
        set_time_limit(36000); // 10 houres
        $rules = array(
            'name' => 'required',
            'feature_image' => 'required',
            'main_image' => 'required',
            'value' => 'required|unique:templates',
            'short_description' => 'required',
            'category' => 'required',
            'header' => 'required',
            'slider' => 'required',
            'banner' => 'required',
            'feature_category' => 'required',
            'product' => 'required',
            'feature_product' => 'required',
            'best_sell_product' => 'required',
            'new_arrival' => 'required',
            'testimonial' => 'required',
            'youtube' => 'required',
            'footer' => 'required',
            'auth' => 'required',
            'mainposition' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()
                ->withErrors($validator);
        } else {
            $template = new Template();
            $template->name = $request->name;
            if ($request->link != "") {
                $template->liveurl = $request->link;
            }
            $template->value = $request->value;
            if ($request->category) {
                $cats = implode(',', $request->category);
                $template->category = $cats;
            }

            $template->short_description = $request->short_description;
            if ($request->feature_image) {
                $imgName = "fi" . Carbon::now()->timestamp . '.' . $request->feature_image->extension();
                $request->feature_image->storeAs('template', $imgName);
                $template->feature_image = $imgName;
            }
            if ($request->main_image) {
                $imgName = "mi" . Carbon::now()->timestamp . '.' . $request->main_image->extension();
                $request->main_image->storeAs('template', $imgName);
                $template->main_image = $imgName;
            }
            $template->header = $request->header;
            $template->slider = $request->slider;
            $template->banner = $request->banner;
            $template->banner_bottom = $request->banner_bottom;
            $template->feature_category = $request->feature_category;
            $template->product = $request->product;
            $template->feature_product = $request->feature_product;
            $template->best_sell_product = $request->best_sell_product;
            $template->new_arrival = $request->new_arrival;
            $template->testimonial = $request->testimonial;
            $template->youtube = $request->youtube;
            $template->footer = $request->footer;
            $template->auth = $request->auth;
            $template->single_product_page = $request->single_product_page;
            $template->shop_page = $request->shop_page;
            $template->checkout_page = $request->checkout_page;
            $template->login_page = $request->login_page;
            $template->profile_page = $request->profile_page;
            $template->invoice = $request->invoice;
            $template->product_card = $request->product_card;
            $template->product_modal = $request->product_modal;
            $template->preloader = $request->preloader;
            $template->mobile_bottom_menu = $request->mobile_bottom_menu;
            $template->offer = $request->offer;
            $template->blog = $request->blog;
            $template->contact = $request->contact;
            $template->announcement = $request->announcement;
            $template->is_premium = $request->is_premium;
            $template->price = $request->price;
            $template->review = $request->review;
            $template->reviewer = $request->reviewer;
            $template->downlad = $request->downlad;
            $template->position = $request->mainposition;
            if ($request->status == 'on') {
                $template->status = 'active';
            } else {
                $template->status = 'inactive';
            }
            $template->save();

            $temposition = new Temposition();
            $temposition->name = "header";
            $temposition->position = $request->header_position;
            $temposition->template_id = $template->id;
            $temposition->save();
            $stemposition = new Temposition();
            $stemposition->name = "hero_slider";
            $stemposition->position = $request->slider_position;
            $stemposition->template_id = $template->id;
            $stemposition->save();
            $btemposition = new Temposition();
            $btemposition->name = "banner";
            $btemposition->position = $request->banner_position;
            $btemposition->template_id = $template->id;
            $btemposition->save();
            $btemposition = new Temposition();
            $btemposition->name = "banner_bottom";
            $btemposition->position = $request->banner_bottom_position;
            $btemposition->template_id = $template->id;
            $btemposition->save();

            $fctemposition = new Temposition();
            $fctemposition->name = "feature_category";
            $fctemposition->position = $request->feature_category_position;
            $fctemposition->template_id = $template->id;
            $fctemposition->save();
            $ptemposition = new Temposition();
            $ptemposition->name = "product";
            $ptemposition->position = $request->product_position;
            $ptemposition->template_id = $template->id;
            $ptemposition->save();
            $fptemposition = new Temposition();
            $fptemposition->name = "feature_product";
            $fptemposition->position = $request->feature_product_position;
            $fptemposition->template_id = $template->id;
            $fptemposition->save();
            $bstemposition = new Temposition();
            $bstemposition->name = "best_sell_product";
            $bstemposition->position = $request->best_sell_product_position;
            $bstemposition->template_id = $template->id;
            $bstemposition->save();
            $natemposition = new Temposition();
            $natemposition->name = "new_arrival";
            $natemposition->position = $request->new_arrival_position;
            $natemposition->template_id = $template->id;
            $natemposition->save();
            $tttemposition = new Temposition();
            $tttemposition->name = "testimonial";
            $tttemposition->position = $request->testimonial_position;
            $tttemposition->template_id = $template->id;
            $tttemposition->save();
            $tttemposition = new Temposition();
            $tttemposition->name = "youtube";
            $tttemposition->position = $request->youtube_position ?? 10;
            $tttemposition->template_id = $template->id;
            $tttemposition->save();
            $ftemposition = new Temposition();
            $ftemposition->name = "footer";
            $ftemposition->position = $request->footer_position;
            $ftemposition->template_id = $template->id;
            $ftemposition->save();

            Session::flash('message', 'Template Added Successfully');
            return redirect()->route('superadmin.template');
        }
    }

    public function edittemplate($id)
    {
        if (canSuperStaffAccess('template')) {
            $template = Template::find($id);
            $urls = "templates";
            $categories = BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
            $template_position = Temposition::where("template_id", $template->id)->get();
            $designs = Designlist::where('status', 'active')
                ->orderBy('type', 'asc')
                ->orderBy('name', 'asc')
                ->get()
                ->groupBy('type');
            return view('superadmin.template.edit', compact('categories', 'designs'))->with('urls', $urls)->with('template', $template)->with('template_position', $template_position);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function updatetemplate(Request $request, $id)
    {
        ini_set('memory_limit', '2024M');
        ini_set('post_max_size', '2024M');
        ini_set('upload_max_filesize', '2024M');
        ini_set('max_input_time', 36000); // 10 houres
        set_time_limit(36000); // 10 houres
        $rules = array(
            'name' => 'required',
            'value' => 'required',
            'short_description' => 'required',
            'category' => 'required',
            'header' => 'required',
            'slider' => 'required',
            'banner' => 'required',
            'feature_category' => 'required',
            'product' => 'required',
            'feature_product' => 'required',
            'best_sell_product' => 'required',
            'new_arrival' => 'required',
            'testimonial' => 'required',
            'youtube' => 'required',
            'footer' => 'required',
            'auth' => 'required',
            'mainposition' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()
                ->withErrors($validator);
        } else {
            $template = Template::find($id);
            $template->name = $request->name;
            if ($request->link != "") {
                $template->liveurl = $request->link;
            } else {
                $template->liveurl = null;
            }
            $template->value = $request->value;
            if ($request->category) {
                $cats = implode(',', $request->category);
                $template->category = $cats;
            }
            $template->short_description = $request->short_description;
            if ($request->feature_image) {
                $imgName = "fi" . Carbon::now()->timestamp . '.' . $request->feature_image->extension();
                $request->feature_image->storeAs('template', $imgName);
                $template->feature_image = $imgName;
            }
            if ($request->main_image) {
                $imgName = "mi" . Carbon::now()->timestamp . '.' . $request->main_image->extension();
                $request->main_image->storeAs('template', $imgName);
                $template->main_image = $imgName;
            }
            $template->header = $request->header;
            $template->slider = $request->slider;
            $template->banner = $request->banner;
            $template->banner_bottom = $request->banner_bottom;
            $template->feature_category = $request->feature_category;
            $template->product = $request->product;
            $template->feature_product = $request->feature_product;
            $template->best_sell_product = $request->best_sell_product;
            $template->new_arrival = $request->new_arrival;
            $template->testimonial = $request->testimonial;
            $template->youtube = $request->youtube;
            $template->footer = $request->footer;
            $template->auth = $request->auth;
            $template->blog = $request->blog;
            $template->contact = $request->contact;
            $template->announcement = $request->announcement;
            $template->single_product_page = $request->single_product_page;
            $template->shop_page = $request->shop_page;
            $template->checkout_page = $request->checkout_page;
            $template->login_page = $request->login_page;
            $template->profile_page = $request->profile_page;
            $template->invoice = $request->invoice;
            $template->product_card = $request->product_card;
            $template->product_modal = $request->product_modal;
            $template->preloader = $request->preloader;
            $template->mobile_bottom_menu = $request->mobile_bottom_menu;
            $template->offer = $request->offer;
            $template->is_premium = $request->is_premium;
            $template->price = $request->price;
            $template->review = $request->review;
            $template->reviewer = $request->reviewer;
            $template->downlad = $request->downlad;
            $template->position = $request->mainposition;
            if ($request->status == 'on') {
                $template->status = 'active';
            } else {
                $template->status = 'inactive';
            }
            $template->update();

            $oldtem = Temposition::where('template_id', $id)->get();
            if (isset($oldtem) && count($oldtem) > 0) {
                foreach ($oldtem as $otm) {
                    $tt = Temposition::find($otm->id);
                    $tt->delete();
                }
            }

            $temposition = new Temposition();
            $temposition->name = "header";
            $temposition->position = $request->header_position;
            $temposition->template_id = $template->id;
            $temposition->save();
            $stemposition = new Temposition();
            $stemposition->name = "hero_slider";
            $stemposition->position = $request->slider_position;
            $stemposition->template_id = $template->id;
            $stemposition->save();
            $btemposition = new Temposition();
            $btemposition->name = "banner";
            $btemposition->position = $request->banner_position;
            $btemposition->template_id = $template->id;
            $btemposition->save();

            $btemposition = new Temposition();
            $btemposition->name = "banner_bottom";
            $btemposition->position = $request->banner_bottom_position;
            $btemposition->template_id = $template->id;
            $btemposition->save();

            $fctemposition = new Temposition();
            $fctemposition->name = "feature_category";
            $fctemposition->position = $request->feature_category_position;
            $fctemposition->template_id = $template->id;
            $fctemposition->save();
            $ptemposition = new Temposition();
            $ptemposition->name = "product";
            $ptemposition->position = $request->product_position;
            $ptemposition->template_id = $template->id;
            $ptemposition->save();
            $fptemposition = new Temposition();
            $fptemposition->name = "feature_product";
            $fptemposition->position = $request->feature_product_position;
            $fptemposition->template_id = $template->id;
            $fptemposition->save();
            $bstemposition = new Temposition();
            $bstemposition->name = "best_sell_product";
            $bstemposition->position = $request->best_sell_product_position;
            $bstemposition->template_id = $template->id;
            $bstemposition->save();
            $natemposition = new Temposition();
            $natemposition->name = "new_arrival";
            $natemposition->position = $request->new_arrival_position;
            $natemposition->template_id = $template->id;
            $natemposition->save();
            $tttemposition = new Temposition();
            $tttemposition->name = "testimonial";
            $tttemposition->position = $request->testimonial_position;
            $tttemposition->template_id = $template->id;
            $tttemposition->save();
            $tttemposition = new Temposition();
            $tttemposition->name = "youtube";
            $tttemposition->position = $request->youtube_position ?? 10;
            $tttemposition->template_id = $template->id;
            $tttemposition->save();
            $ftemposition = new Temposition();
            $ftemposition->name = "footer";
            $ftemposition->position = $request->footer_position;
            $ftemposition->template_id = $template->id;
            $ftemposition->save();

            Session::flash('message', 'Template Update Successfully');
            return redirect()->route('superadmin.template');
        }
    }

    public function updatepositiontemplate(Request $request)
    {
        $template = Template::find($request->id);
        $template->position = $request->value;
        $template->save();
        $data = $template;

        return response()->json($data);
    }

    public function deletetemplate($id)
    {
        if (canSuperStaffAccess('template')) {
            $template = Template::find($id);
            $template->delete();

            Session::flash('message', 'Template Deleted Successfully');
            return redirect()->route('superadmin.template');
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function notification()
    {
        if (canSuperStaffAccess('notification')) {
            $urls = "notification";
            $notification = Notification::latest()->get();

            return view('superadmin.notification.index')->with('urls', $urls)->with('notification', $notification);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function createnotification()
    {

        if (canSuperStaffAccess('notification')) {
            $urls = "notification";
            return view('superadmin.notification.create')->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function savenotification(Request $request)
    {
        $notification = new Notification();
        $notification->title = $request->title;
        $notification->body = $request->body;
        $notification->user_type = $request->user_type ?? NULL;
        $notification->link = $request->link;
        $notification->save();

        appNotification($notification);
        pushNotification($notification);

        Session::flash('message', 'Notification Save Successfully');
        return redirect()->route('notification');
    }

    public function editnotification($id)
    {
        if (canSuperStaffAccess('notification')) {
            $urls = "notification";
            $notification = Notification::find($id);

            return view('superadmin.notification.edit')->with('urls', $urls)->with('notification', $notification);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function updatenotification(Request $request, $id)
    {
        $notification = Notification::find($id);
        if (isset($notification)) {
            $notification->title = $request->title;
            $notification->body = $request->body;
            $notification->user_type = $request->user_type ?? NULL;
            $notification->view = 0;
            $notification->link = $request->link;
            $notification->save();

            appNotification($notification);
            pushNotification($notification);

            Session::flash('message', 'Notification Update Successfully');
            return redirect()->route('notification');
        } else {
            Session::flash('error', 'Notification Not Found');
            return redirect()->back();
        }

    }

    public function deletenotification($id)
    {
        if (canSuperStaffAccess('notification')) {
            $urls = "notification";
            $notification = Notification::find($id);
            $notification->delete();

            Session::flash('message', 'Notification Deleted Successfully');
            return back();
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function iconpack()
    {
        if (canSuperStaffAccess('design')) {
            $urls = "designlist";
            $icons = Iconpack::orderBy('id', 'DESC')->paginate(20);

            return view('superadmin.iconpack.index')->with('urls', $urls)->with('icons', $icons);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function createiconpack()
    {
        if (canSuperStaffAccess('design')) {
            $urls = "designlist";
            return view('superadmin.iconpack.create')->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function saveiconpack(Request $request)
    {
        $rules = array(
            'image' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()
                ->withErrors($validator);
        } else {
            if ($request->image) {
                foreach ($request->image as $key => $image) {
                    $imageName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $image->getClientOriginalName());
                    $icon = new Iconpack();
                    $icon->name = $imageName;
                    $icon->value = generateSlug($imageName, '-') . '_' . rand(100, 999);
                    $imgName = Carbon::now()->timestamp . $key . rand(1000, 9999) . '.' . $image->extension();
                    $image->storeAs('icon', $imgName);
                    $icon->image = $imgName;
                    $icon->save();
                }
            }

            Session::flash('message', 'Icon Save Successfully');
            return redirect()->route('superadmin.iconpack');
        }
    }

    public function deleteiconpack($id)
    {
        if (canSuperStaffAccess('design')) {
            $icon = Iconpack::find($id);
            if (isset($icon->image)) {
                unlink('assets/images/icon' . '/' . $icon->image);
            }
            $icon->delete();

            Session::flash('message', 'Icon Deleted Successfully');
            return redirect()->route('superadmin.iconpack');
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function message()
    {
        $uid = Auth::user()->id;
        $messages = Message::all();

        return back()->with('messages', $messages);
    }

    public function payNoti()
    {
        if (canSuperStaffAccess('clients')) {
            $end_date = Carbon::now()->addDays(7);
            $end_date = date('Y-m-d', strtotime($end_date));

            $store = Store::where('expiry_date', '<=', $end_date)->where('plan_id', '!=', 6)->where('pay_noti', 1)->get();

            foreach ($store as $key => $sms) {
                $user = User::find($sms->user_id);
                $text = "Dear " . ($user->name ?? "eBitans User, ") . "\nPlease pay your monthly website bill for " . $sms->name . " by " . date(
                    'd-m-Y',
                    strtotime(Carbon::now()->addDays(6))
                ) . " to avoid disconnection.\nPayment Link: https://admin.ebitans.com/payment/";

                $sms->pay_noti = 7;
                $store->pay_mail_status = 1;
                $sms->update();

                $phone = $user->phone ?? "";
                $email = $user->email ?? "";

                if (isset($phone) && !empty($phone)) {
                    $smsresult = SendSms($phone, $text); // phone, text
                    smsLogger($phone, $text, "Payment Notification");
                }

                if (isset($email) && !empty($email)) {
                    $data['name'] = $user->name ?? "User";
                    $data['subject'] = "Payment Notification";
                    $data['text'] = $text;
                    $data['formEmail'] = env('MAIL_FROM_ADDRESS');
                    $data['email'] = $email;

                    Mail::send('email.payment-notification', $data, function ($message) use ($data) {
                        $message->from($data['formEmail'], $data["subject"])->to($data["email"], $data["email"])
                            ->subject('Payment Notification');
                    });
                }
            }

            Session::flash('message', 'Successfully');
            return redirect()->route('superadmin.index');
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function payNotiByCustomer($id)
    {
        if (canSuperStaffAccess('clients')) {
            $store = Store::where('id', $id)->whereNotIn('plan_id', [6, 9])->first();
            if ($store) {
                $user = User::find($store->user_id);
                $text = "Dear " . ($user->name ?? "eBitans User,") . " your store $store->name Please pay your website bill for uninterrupted service.\n\nFor any enquiry please call: " . env('SUPPORT_NUMBER');

                $store->pay_noti = 0;
                $store->pay_mail_status = 1;
                $store->update();

                $phone = $user->phone ?? "";
                $email = $user->email ?? "";

                if (isset($phone) && !empty($phone)) {
                    $smsresult = SendSms($phone, $text); // phone, text
                    smsLogger($phone, $text, "Payment Notification");
                }

                if (isset($email) && !empty($email)) {
                    $data['name'] = $user->name ?? "User";
                    $data['subject'] = "Payment Notification";
                    $data['text'] = $text;
                    $data['formEmail'] = env('MAIL_FROM_ADDRESS');
                    $data['email'] = $email;

                    Mail::send('email.payment-notification', $data, function ($message) use ($data) {
                        $message->from($data['formEmail'], $data["subject"])->to($data["email"], $data["email"])
                            ->subject('Payment Notification');
                    });
                }

                Session::flash('message', 'Successfully');
                return redirect()->back();
            }

            Session::flash('error', 'Store Missing');
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function sendCustomPaySms(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $store_id = $request->store_id ?? "";
            $message = $request->message ?? "";
            if (is_null($store_id) || empty($store_id)) {
                Session::flash('error', 'Store ID Missing');
                return redirect()->back();
            }
            if (is_null($message) || empty($message)) {
                Session::flash('error', 'Message is required');
                return redirect()->back();
            }

            $store = Store::where('id', $store_id)->whereNotIn('plan_id', [6, 9])->first();
            if ($store) {
                $user = User::find($store->user_id);
                $text = "Dear " . ($user->name ?? "eBitans User,") . " your store $store->name. Please pay your website bill for uninterrupted service.\n\nFor any enquiry please call: " . env('SUPPORT_NUMBER');
                $message = $message ?? $text;

                $store->pay_noti = 0;
                $store->pay_mail_status = 1;
                $store->sms_status = $store->sms_status + 1;
                $store->update();


                $phone = $user->phone ?? "";
                $email = $user->email ?? "";

                if (isset($phone) && !empty($phone)) {
                    $smsresult = SendSms($phone, $message);
                    smsLogger($phone, $message, "Custom Payment Notification");
                }

                if (isset($email) && !empty($email)) {
                    $data['name'] = $user->name ?? "User";
                    $data['subject'] = "Payment Notification";
                    $data['text'] = $message;
                    $data['formEmail'] = env('MAIL_FROM_ADDRESS');
                    $data['email'] = $email;

                    Mail::send('email.payment-notification', $data, function ($message) use ($data) {
                        $message->from($data['formEmail'], $data["subject"])->to($data["email"], $data["email"])
                            ->subject('Payment Notification');
                    });
                }

                Session::flash('message', 'Message send Successfully');
                return redirect()->back();
            }

            Session::flash('error', 'Store Missing');
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function sendMultiplePaySms(Request $request)
    {
        try {
            if (canSuperStaffAccess('clients')) {
                $storeIds = $request->storeIds ?? "";

                if (is_null($storeIds) || empty($storeIds)) {
                    Session::flash('error', 'Please select store first!');
                    return redirect()->back();
                }

                $ids = explode(',', $storeIds);
                if (isset($ids) && count($ids) > 0) {
                    foreach ($ids as $id) {
                        $store = Store::with('user')->where('id', $id)->whereNotIn('plan_id', [6, 9])->first();
                        if ($store) {
                            $message = "Dear " . ($store->user->name ?? "eBitans User,") . " your store $store->name. Please pay your website bill for uninterrupted service.\n\nFor any enquiry please call: " . env('SUPPORT_NUMBER');

                            $store->pay_noti = 0;
                            $store->pay_mail_status = 1;
                            $store->sms_status = $store->sms_status + 1;
                            $store->update();

                            $phone = $store->user->phone ?? "";
                            $email = $store->user->email ?? "";

                            if (isset($phone) && !empty($phone)) {
                                $smsresult = SendSms($phone, $message);
                                smsLogger($phone, $message, "Payment Notification");
                            }

                            if (isset($email) && !empty($email)) {
                                $data['name'] = $store->user->name ?? "User";
                                $data['subject'] = "Payment Notification";
                                $data['text'] = $message;
                                $data['formEmail'] = env('MAIL_FROM_ADDRESS');
                                $data['email'] = $email;

                                Mail::send('email.payment-notification', $data, function ($message) use ($data) {
                                    $message->from($data['formEmail'], $data["subject"])->to($data["email"], $data["email"])
                                        ->subject('Payment Notification');
                                });
                            }
                        }
                    }

                    Session::flash('message', 'Message send Successfully');
                    return redirect()->back();
                }

                Session::flash('error', 'Please select store first!');
                return redirect()->back();
            } else {
                return redirect()->back();
            }
        } catch (Exception $e) {
            Session::flash('message', "Something went wrong. Try again");
            return redirect()->back();
        }

    }

    /**
     * Make change call status
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeCustomerCallStatus($id)
    {
        if (canSuperStaffAccess('clients')) {
            $store = Store::where('id', $id)->whereNotIn('plan_id', [6, 9])->first();
            if ($store) {
                $store->call_status = (int) $store->call_status + 1;
                $store->update();
            }

            Session::flash('message', 'Successfully');
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function getmessage(Request $request)
    {
        $data = $request->chatid;
        $store_id = $request->store_id;
        $oldmessage = Message::where('store_id', $store_id)->where('session', 'active')->orderBy(
            'id',
            'DESC'
        )->first();
        if (isset($oldmessage) && $oldmessage->session_end <= Carbon::now()) {
            $outsession = Message::where('session_id', $oldmessage->session_id)->get();
            if (isset($outsession) && count($outsession) > 0) {
                foreach ($outsession as $outs) {
                    $msage = Message::find($outs->id);
                    $msage->session = 'deactive';
                    $msage->save();
                }
            }
        }
        return response()->json($data);
    }

    public function sendmessage(Request $request)
    {
        if ($request->message == null && $request->image == null) {
            return 1;
        }
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin' || $user_type == 'dropshipper') {
            $customer = Customer::where('uid', $user_id)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type = 'staff') {
            $staff = Staff::where('uid', $user_id)->first();
            $store_id = $staff->store_id;
        } else {
            $store_id = '0';
        }
        $messagess = $request->message;
        $uid = $request->uid;
        $chatid = $request->chatid;

        $oldmessage = Message::where('store_id', $store_id)->where('session', 'active')->orderBy(
            'id',
            'DESC'
        )->first();
        if (isset($oldmessage) && $oldmessage->session_end <= Carbon::now()) {
            $outsession = Message::where('session_id', $oldmessage->session_id)->get();
            if (isset($outsession) && count($outsession) > 0) {
                foreach ($outsession as $outs) {
                    $msage = Message::find($outs->id);
                    $msage->session = 'deactive';
                    $msage->save();
                }
            }
        }

        $message = new Message;
        $message->tokenid = '0';
        $message->seen = 0;
        $message->name = $request->storename;
        if (isset($oldmessage)) {
            $message->session_id = $oldmessage->session_id;
            $message->session_end = Carbon::now()->addMinutes(30);
            $message->session = "active";
        } else {
            $message->session_id = Carbon::now()->timestamp;
            $message->session_end = Carbon::now()->addMinutes(30);
            $message->session = "active";
        }
        if ($messagess == "" || $messagess == null) {
        } else {
            $message->message = $messagess;
        }
        $message->store_id = $store_id;
        if ($store_id == '0') {
            $message->type = "receive";
        } else {
            $message->type = "send";
        }
        if ($request->img) {
            $img = substr($request->img, strpos($request->img, ",") + 1);
            $file = base64_decode($img);
            $safeName = Str::random(10) . '.' . 'png';
            $success = file_put_contents(public_path() . '/assets/images/message/' . $safeName, $file);
            $message->image = $safeName;
        }
        $message->uid = Auth::user()->id;
        $message->send_id = Auth::user()->id;
        $message->save();
        $data = $message;

        return response()->json($data);
    }

    public function messages()
    {
        if (canSuperStaffAccess('message')) {
            $urls = "messages";
            $member = Message::groupBy('tokenid')->groupBy('store_id')->orderBy('id', 'DESC')->get();

            return view('superadmin.messages.index')->with('member', $member)->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function gscFbPixel()
    {
        if (canSuperStaffAccess('message')) {
            $urls = "gsc-fb-pixel";
            $storesList = QuickLogin::where('modulus_id', 10)->orWhere(
                'modulus_id',
                11
            )->groupBy('store_id')->orderBy('id', 'DESC')->get();

            return view('superadmin.gscFbPixel.index')->with('storesList', $storesList)->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function gscFbPixelDetails(Request $request)
    {
        if (canSuperStaffAccess('message')) {
            $urls = "gsc-fb-pixel";
            $storesList = QuickLogin::where('store_id', $request->id)->where('modulus_id', 10)->orderBy(
                'id',
                'DESC'
            )->first();
            $fb = QuickLogin::where('store_id', $request->id)->where('modulus_id', 11)->orderBy(
                'id',
                'DESC'
            )->first();

            $data['facebook_pixel'] = $fb->facebook_pixel ?? 'No Entry';
            $data['google_analytics'] = $storesList->google_analytics ?? 'No Entry';
            $data['google_search_console'] = $storesList->google_search_console ?? 'No Entry';

            return response()->json($data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function seemessages($uid, $store_id)
    {
        if (canSuperStaffAccess('message')) {
            $urls = "messages";
            $messag = Message::where('uid', $uid)->where('store_id', $store_id)->first();
            $name = $messag->name;
            $message = Message::where('uid', $uid)->where('store_id', $store_id)->get();
            if (isset($message) && count($message) > 0) {
                foreach ($message as $msg) {
                    $mgs = Message::find($msg->id);
                    $mgs->seen = 1;
                    $mgs->save();
                }
            }
            $outsession = Message::where('store_id', $store_id)->where(
                'session_end',
                '<=',
                Carbon::now()
            )->where('session', 'active')->get();
            if (isset($outsession) && count($outsession) > 0) {
                foreach ($outsession as $outs) {
                    $msage = Message::find($outs->id);
                    $msage->session = 'deactive';
                    $msage->save();
                }
            }
            $member = Message::groupBy('tokenid')->groupBy('store_id')->orderBy('id', 'DESC')->get();

            return view('superadmin.messages.view')->with('message', $message)->with(
                'member',
                $member
            )->with('urls', $urls)->with('name', $name);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function sendmessageadmin(Request $request)
    {
        $user = User::find($request->uid);
        if ($user->type == 'admin') {
            $customer = Customer::where('uid', $user->id)->first();
            $store_id = $customer->active_store;
        } elseif ($user->type == 'staff') {
            $staff = Staff::where('uid', $user->id)->first();
            $store_id = $staff->store_id;
        }
        $oldmessage = Message::where('store_id', $store_id)->where('session', 'active')->orderBy(
            'id',
            'DESC'
        )->first();
        if (isset($oldmessage) && $oldmessage->session_end <= Carbon::now()) {
            $outsession = Message::where('session_id', $oldmessage->session_id)->get();
            if (isset($outsession) && count($outsession) > 0) {
                foreach ($outsession as $outs) {
                    $msage = Message::find($outs->id);
                    $msage->session = 'deactive';
                    $msage->save();
                }
            }
        }
        $message = new Message;
        $message->tokenid = $request->chatid;
        $message->name = $request->storename;
        if ($request->message == "") {
        } else {
            $message->message = $request->message;
        }
        $message->seen = 1;
        $message->store_id = $store_id;
        $message->type = "receive";
        if (isset($oldmessage)) {
            $message->session_id = $oldmessage->session_id;
            $message->session_end = Carbon::now()->addMinutes(30);
            $message->session = "active";
        } else {
            $message->session_id = Carbon::now()->timestamp;
            $message->session_end = Carbon::now()->addMinutes(30);
            $message->session = "active";
        }
        if ($request->img) {
            $img = substr($request->img, strpos($request->img, ",") + 1);
            $file = base64_decode($img);
            $safeName = Str::random(10) . '.' . 'png';
            $success = file_put_contents(public_path() . '/assets/images/message/' . $safeName, $file);
            $message->image = $safeName;
        }
        $message->uid = $user->id;
        $message->send_id = Auth::user()->id;
        $message->save();
        $data = $message;

        return response()->json($data);
    }

    public function viewcustomer($id)
    {
        $urls = "customer";
        $user = User::find($id);

        return view('superadmin.customer.view')->with('urls', $urls)->with('user', $user);
    }

    public function toggleStatus(Store $store)
    {
        try {
            $store->store_status = request('is_active') ? 1 : 0;
            $store->update();

            $status = request('is_active') ? 'activated' : 'deactivated';

            return sendResponse("Store {$store->name} has been {$status} successfully.", $store->store_status);

        } catch (\Exception $e) {
            return serverError();
        }
    }

    public function staff()
    {
        if (canSuperStaffAccess('staff')) {
            $urls = "superstaff";
            $staff = Superstaff::with('role')->get();

            return view('superadmin.staff.index')->with('urls', $urls)->with('staff', $staff);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function staffcreate()
    {
        if (canSuperStaffAccess('staff')) {
            $urls = "superstaff";
            $role = Superrole::all();
            if (count($role) == 0) {
                Session::flash('error', "Your planel have no role. Please add atleast one role");
                return back();
            }
            return view('superadmin.staff.create')->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function staffsave(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'username' => 'required|unique:superstaffs',
            'phone' => 'required',
            'password' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()
                ->withErrors($validator);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = time() . "gmail.com";
            $user->type = "superstaff";
            $user->phone = time();
            $user->password = Hash::make($request->password);
            $user->save();

            $notificationData = [
                "title" => "New staff register (" . getUserNameOrPhone($user) . ") - " . formatDateWithTime($user->created_at),
                "type" => "user_create",
                "user_type" => "superadmin",
            ];

            if (isset($notificationData['title']) && !empty($notificationData['title'])) {
                createNotification($notificationData);
            }


            $superstaff = new Superstaff();
            $superstaff->name = $request->name;
            $superstaff->username = $request->username;
            $superstaff->phone = $request->phone;
            $superstaff->email = $request->email;
            $superstaff->address = $request->address;
            $superstaff->new_commission = $request->new_commission ?? NULL;
            $superstaff->renew_commission = $request->renew_commission ?? NULL;
            $superstaff->setup_commission = $request->setup_commission ?? NULL;
            $superstaff->password = Hash::make($request->password);
            $superstaff->role_id = $request->role;
            $superstaff->uid = $user->id;
            if ($request->status == "on") {
                $superstaff->status = "active";
            } else {
                $superstaff->status = "inactive";
            }
            $superstaff->save();

            Session::flash('message', 'Staff Save Successfully');
            return redirect()->route('superadmin.staff');
        }
    }

    public function staffedit($id)
    {
        if (canSuperStaffAccess('staff')) {
            $role = Superrole::all();
            if (count($role) == 0) {
                Session::flash('error', "Your planel have no role. Please add atleast one role");
                return back();
            }
            $urls = "superstaff";
            $staff = Superstaff::find($id);

            return view('superadmin.staff.edit')->with('urls', $urls)->with('staff', $staff);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function staffupdate(Request $request, $id)
    {
        $rules = array(
            'name' => 'required',
            'username' => 'required',
            'phone' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()
                ->withErrors($validator);
        } else {

            $superstaff = Superstaff::find($id);
            $user = User::find($superstaff->uid);
            $user->name = $request->name;
            if (isset($request->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            $superstaff->name = $request->name;
            $superstaff->username = $request->username;
            $superstaff->phone = $request->phone;
            $superstaff->email = $request->email;
            $superstaff->address = $request->address;
            $superstaff->new_commission = $request->new_commission ?? NULL;
            $superstaff->renew_commission = $request->renew_commission ?? NULL;
            $superstaff->setup_commission = $request->setup_commission ?? NULL;
            if (isset($request->password)) {
                $superstaff->password = Hash::make($request->password);
            }
            $superstaff->role_id = $request->role;
            if ($request->status == "on") {
                $superstaff->status = "active";
            } else {
                $superstaff->status = "inactive";
            }
            $superstaff->save();

            Session::flash('message', 'Staff Update Successfully');
            return redirect()->route('superadmin.staff');
        }
    }

    public function staffdelete($id)
    {
        if (canSuperStaffAccess('staff')) {
            $urls = "superstaff";
            $staff = Superstaff::find($id);
            $user = User::find($staff->uid);
            if (isset($user)) {
                $user->delete();
            }
            $staff->delete();

            Session::flash('message', 'Staff Deleted Successfully');
            return redirect()->route('superadmin.staff');
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function changestaffstatus(Request $request)
    {
        $id = $request->id;
        $value = $request->value;
        $slider = Superstaff::find($id);
        if (isset($slider) && $slider->status == 'active') {
            $slider->status = 'inactive';
        } else {
            $slider->status = "active";
        }
        $slider->save();
        $data = $slider;

        return response()->json($data);
    }

    public function rolepermission()
    {
        if (canSuperStaffAccess('role_and_permission')) {
            $urls = "superrolepermission";
            $roles = Superrole::all();
            return view('superadmin.role.index')->with('urls', $urls)->with('roles', $roles);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function supersaverole(Request $request)
    {
        $rules = array(
            'name' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $role = new Superrole();
            $role->name = $request->name;
            $role->save();

            Session::flash('message', 'Role Created Successfully');
            return back();
        }
    }

    public function supereditrole(Request $request, $id)
    {
        if (canSuperStaffAccess('role_and_permission')) {
            $urls = "superrolepermission";
            $id = decrypt($id);
            $role = Superrole::find($id);
            $roles = Superrole::all();

            return view('superadmin.role.edit')->with('urls', $urls)->with('role', $role)->with('roles', $roles);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function superupdaterole(Request $request, $id)
    {
        $rules = array(
            'name' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $role = Superrole::find($id);
            $role->name = $request->name;
            $role->save();

            Session::flash('message', 'Role Updated Successfully');
            return redirect()->route('superadmin.role.permission');
        }
    }

    public function superdeleterole($id)
    {
        if (canSuperStaffAccess('role_and_permission')) {
            $role = Superrole::find($id);
            $role->delete();

            Session::flash('message', 'Role Deleted Successfully');
            return redirect()->route('superadmin.role.permission');
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function superpermission($id)
    {
        if (canSuperStaffAccess('role_and_permission')) {
            $id = decrypt($id);
            $urls = "superrolepermission";
            $role = Superrole::find($id);

            return view('superadmin.role.permission')->with('role', $role)->with('urls', $urls);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function supersavepermission(Request $request, $id)
    {
        if (canSuperStaffAccess('role_and_permission')) {
            $role = Superrole::find($id);
            $permission = implode(',', $request->permission);
            $role->permission = $permission;
            $role->save();

            Session::flash('message', 'Permission Update Successfully');
            return redirect()->route('superadmin.role.permission');
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function registrationFee()
    {
        $urls = "registrationFee";
        $data = RegistrationFee::first();

        return view('superadmin.order.registrationFee')->with('urls', $urls)->with('data', $data);
    }

    public function registrationFeeUpdate(Request $request)
    {
        $urls = "registrationFee";
        $data = RegistrationFee::find(1);
        $data->price = $request->price;
        $data->status = $request->status;
        $data->update();

        return back();
    }

    public function modulusRequest()
    {
        $urls = "modulusRequest ";
        $data = ModulusPayment::where('status', null)->orderBy('id', 'DESC')->paginate(10);

        return view('superadmin.order.modulus')->with('urls', $urls)->with('data', $data);
    }

    public function planorderrequestrejected()
    {
        $urls = "planorderrequest";
        $data = Planorder::where('status', 'Failed')->orderBy('id', 'DESC')->get();

        return view('superadmin.order.rejectorder')->with('urls', $urls)->with('data', $data);
    }

    public function rejectplanorder($id)
    {
        $data = Planorder::find($id);
        $data->status = "Failed";
        $data->save();
        $addons = Addon::where('plan_order_id', $id)->get();
        if (isset($addons) && count($addons) > 0) {
            foreach ($addons as $add) {
                $a = Addon::find($add->id);
                $a->status = "Rejected";
                $a->start_date = Carbon::now();
                $a->expiry_date = Carbon::now()->addDays((int) $add->month * 30);
                $a->save();
            }
        }

        Session::flash('message', 'Order Rejected');
        return back();
    }

    public function newRejectOrderPlan($id)
    {
        $data = AddonsOrder::find($id);
        $data->status = "Failed";
        $data->update();

        Session::flash('message', 'Order Rejected');
        return back();
    }

    public function acceptplanorder($id)
    {
        $data = Planorder::find($id);
        $data->status = "Complete";
        $data->save();
        if ($data->plan_id == null && $data->pos_plan_id == null && $data->digital_plan_id == null) {
            $addons = Addon::where('plan_order_id', $id)->get();
            if (isset($addons) && count($addons) > 0) {
                foreach ($addons as $add) {
                    $a = Addon::find($add->id);
                    $a->status = "Active";
                    $a->start_date = Carbon::now();
                    $a->expiry_date = Carbon::now()->addMonths($add->month);
                    $a->save();

                    if ($add->name == "mobile") {
                        $mobileapp = Mobileapp::where('store_id', $data->store_id)->first();
                        if (isset($mobileapp)) {
                            $mobileapp->start_date = Carbon::now();
                            $mobileapp->expiry_date = Carbon::now()->addMonths($add->month);
                            $mobileapp->month = $a->month;
                            $mobileapp->save();
                        } else {
                            $mobile = new Mobileapp();
                            $mobile->start_date = Carbon::now();
                            $mobile->expiry_date = Carbon::now()->addMonths($add->month);
                            $mobile->store_id = $data->store_id;
                            $mobileapp->month = $a->month;
                            $mobile->save();
                        }
                    }
                    if ($add->name == "activitylog") {
                        $actv = Activity::where('store_id', $data->store_id)->first();
                        if (isset($actv)) {
                            $actv->expiry_date = Carbon::now()->addMonths($add->month);
                            $actv->month = $add->month;
                            $mobileapp->save();
                        } else {
                            $newact = new Activity();
                            $newact->start_date = Carbon::now();
                            $newact->expiry_date = Carbon::now()->addMonths($add->month);
                            $newact->store_id = $data->store_id;
                            $newact->month = $add->month;
                            $newact->save();
                        }
                    }
                    if ($add->name == "websitesetup") {
                        $newacts = new Websitesetup();
                        $newacts->store_id = $data->store_id;
                        $newacts->status = "Pending";
                        $newacts->save();
                    }
                    if ($add->name == "paymentgateway") {
                        $newactp = new Paymentgateway();
                        $newactp->payment_company = $data->name;
                        $newactp->store_id = $data->store_id;
                        $newactp->status = "Pending";
                        $newactp->save();
                    }
                }
            }
        } else {
            $customers = Customer::where('id', $data->customer_id)->first();
            $customers->plan_status = "Active";
            $customers->save();
            $str = Store::find($data->store_id);
            $str->pay_noti = 1;
            if (isset($data->plan_id)) {
                $str->plan_status = "active";
                $str->purchase_date = Carbon::now();
                if (Carbon::parse($str->expiry_date) <= Carbon::now()) {
                    $str->plan_id = $data->plan_id;
                    $str->expiry_date = Carbon::now()->addMonths($data->total_month);
                } else {
                    $str->upcoming_plan_id = $data->plan_id;
                    $str->upcoming_plan_month = $data->total_month;
                    $str->upcoming_plan_purchase_date = Carbon::parse($str->expiry_date)->addMonths(1);
                    $str->upcoming_plan_expiry_date = Carbon::parse($str->expiry_date)->addMonths($data->total_month);
                }
            }
            if (isset($data->pos_plan_id)) {
                $str->pos_plan_status = "active";
                $str->pos_plan_start_date = Carbon::now();
                if (Carbon::parse($str->pos_plan_expiry_date) <= Carbon::now()) {
                    $str->pos_plan_id = $data->pos_plan_id;
                    $str->pos_plan_expiry_date = Carbon::now()->addMonths($data->pos_plan_month);
                } else {
                    $str->upcoming_pos_plan_id = $data->pos_plan_id;
                    $str->upcoming_pos_plan_month = $data->pos_plan_month;
                    $str->upcoming_pos_plan_start_date = Carbon::parse($str->pos_plan_expiry_date)->addMonths(1);
                    $str->upcoming_pos_plan_expiry_date = Carbon::parse($str->pos_plan_expiry_date)->addMonths($data->pos_plan_month);
                }
            }
            if (isset($data->digital_plan_id)) {
                $str->digital_plan_status = "active";
                $str->digital_plan_start_date = Carbon::now();
                if (Carbon::parse($str->digital_plan_end_date) <= Carbon::now()) {
                    $str->digital_plan_id = $data->digital_plan_id;
                    $str->digital_plan_end_date = Carbon::now()->addMonths($data->digital_plan_month);
                } else {
                    $str->upcoming_digital_plan_id = $data->digital_plan_id;
                    $str->upcoming_digital_plan_month = $data->digital_plan_month;
                    $str->upcoming_digital_plan_start_date = Carbon::parse($str->digital_plan_end_date)->addMonths(1);
                    $str->upcoming_digital_plan_expiry_date = Carbon::parse($str->digital_plan_end_date)->addMonths($data->digital_plan_month);
                }
            }
            $str->trail = 1;
            $str->save();
            $addons = Addon::where('plan_order_id', $id)->get();
            if (isset($addons) && count($addons) > 0) {
                foreach ($addons as $add) {
                    $a = Addon::find($add->id);
                    $a->status = "Active";
                    $a->start_date = Carbon::now();
                    $a->expiry_date = Carbon::now()->addMonths($add->month);
                    $a->save();
                    if ($add->name == "mobile") {
                        $mobileapp = Mobileapp::where('store_id', $data->store_id)->first();
                        if (isset($mobileapp)) {
                            $mobileapp->start_date = Carbon::now();
                            $mobileapp->expiry_date = Carbon::now()->addMonths($add->month);
                            $mobileapp->save();
                        } else {
                            $mobile = new Mobileapp();
                            $mobile->start_date = Carbon::now();
                            $mobile->expiry_date = Carbon::now()->addMonths($add->month);
                            $mobile->store_id = $data->store_id;
                            $mobile->save();
                        }
                    }
                    if ($add->name == "activitylog") {
                        $actv = Activity::where('store_id', $data->store_id)->first();
                        if (isset($actv)) {
                            $actv->expiry_date = Carbon::now()->addMonths($add->month);
                            $actv->month = $add->month;
                            $mobileapp->save();
                        } else {
                            $newact = new Activity();
                            $newact->start_date = Carbon::now();
                            $newact->expiry_date = Carbon::now()->addMonths($add->month);
                            $newact->store_id = $data->store_id;
                            $newact->month = $add->month;
                            $newact->save();
                        }
                    }
                    if ($add->name == "websitesetup") {
                        $newacts = new Websitesetup();
                        $newacts->store_id = $data->store_id;
                        $newacts->status = "Pending";
                        $newacts->save();
                    }
                    if ($add->name == "paymentgateway") {
                        $newactp = new Paymentgateway();
                        $newactp->payment_company = $data->name;
                        $newactp->store_id = $data->store_id;
                        $newactp->status = "Pending";
                        $newactp->save();
                    }
                }
            }
        }

        Session::flash('message', 'Order Accept');
        return back();
    }

    public function newacceptplanorder($id, $noresponse = false)
    {
        \DB::beginTransaction();
        try {
            $order = AddonsOrder::where("id", $id)->where("status", "!=", "Complete")->first();

            if (!isset($order)) {
                if ($noresponse) {
                    return ["status" => false, "message" => 'Order not found!'];
                }

                Session::flash('error', 'Order not found!');
                return back();
            }


            $str = Store::where("id", $order->store_id)->first();

            $user = User::find($order->user_id);

            if (!isset($user)) {
                if ($noresponse) {
                    return ["status" => false, "message" => 'User not found!'];
                }

                Session::flash('error', 'User not found!');
                return back();
            }

            if (!is_null($str)) {
                $tmp1 = [];
                $tmp1['website'] = 0;
                $tmp1['digital'] = 0;
                $tmp1['pos'] = 0;
                $str->pay_noti = 1;

                if (isset($order->combopackages)) {
                    foreach ($order->combopackages as $key => $item) {
                        $activeDate = null;
                        $expireDate = null;

                        $str = Store::find($order['store_id']);
                        $str->pay_noti = 1;
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
                                    $this->giveSellerCommission($user->id, $isNew, $item['discountPrice'], $order['store_id'], $package_month);
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

                                    $this->giveSellerCommission($user->id, $isNew, $totalPrice, $order['store_id'], $package_month);
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
                                    $daysLeft = Carbon::now()->diffInDays($str->digital_plan_end_date);
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
                                    $daysLeft = Carbon::now()->diffInDays($str->pos_plan_expiry_date);
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
                            $updatedds = 1;
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
                                $this->giveSellerCommissionForSetup($user->id, $item['price'], $order->store_id);
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

                $data['data'] = AddonsOrder::find($id);
                $data['package'] = !empty($order->package) ? json_decode($order->package) : null;


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
                    $data['orderInfo'] = "Hi! " . $user->name .
                        ". \n\nYour Payment has been accepted successfully and your plan active. \n\nSee attached invoice for more details.";
                    $data["title"] = "Your eBitans Payment Receipt";

                    Mail::send('clientPaymentMail', $data, function ($message) use ($data) {
                        $message->from('info@ebitans.com', $data["title"])->to($data["email"], $data["email"])
                            ->subject('Your Payment has been accepted successfully!');
                    });
                }

                (new AcceptPlanController())->giveUserReferralCommission($user, $str, $order, $tmp1);

                \DB::commit();

                if ($noresponse) {
                    return ["status" => true, "message" => 'Your payment is Completed And Order Accepted Successfully!'];
                }

                Session::flash('message', 'Order Accept');
                return back();

            } else {
                \DB::rollBack();

                if ($noresponse) {
                    return ["status" => false, "message" => 'Store not found!'];
                }

                Session::flash('error', 'Store not found!');
                return back();
            }

        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json(['status', false, 'error' => 'Something wrong. Please try again'], 500);
        }


    }


    /**
     *
     * payment request invoice generate
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewPlaneOrderInvoice(Request $request, $id)
    {
        $order = AddonsOrder::with(["store", "user", "paymentHistories.creator"])->where('id', $id)->first();
        $user = User::find($order->user_id);

        $data['data'] = $order;
        $data['package'] = !empty($order->package) ? json_decode($order->package) : null;
        $data['selectedPaymentHistory'] = null;

        if ($request->filled('payment_history_id')) {
            $data['selectedPaymentHistory'] = $order->paymentHistories
                ->firstWhere('id', (int) $request->payment_history_id);
        }

        $data['email'] = $user->email ?? $user->phone ?? "";
        $data["title"] = "Your eBitans Payment Receipt";
        $data['orderInfo'] = "Hi! $user->name. \n\nYour Payment has been accepted successfully and your plan active. \n\nSee attached invoice for more details.";

        return view('clientPaymentInvoice', $data);
    }

    public function updateOrderPayment(Request $request, $id)
    {
        $order = AddonsOrder::find($id);

        if (!isset($order)) {
            return back()->with('error_message', 'Order not found.');
        }

        if ($order->status !== 'Complete') {
            return back()->with('error_message', 'Only completed orders can be updated.');
        }

        $currentDue = (float) ($order->due_amount ?? 0);
        if ($currentDue <= 0) {
            return back()->with('error_message', 'This order has no due amount left.');
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
            return back()->withErrors($validator)->withInput();
        }

        $paymentAmount = round((float) $request->additional_paid_amount, 2);
        if ($paymentAmount > $currentDue) {
            return back()->with('error_message', 'Additional paid amount cannot exceed current due amount.');
        }

        $paymentMethod = (string) $request->payment_method;
        if ($paymentMethod === 'bank_transfer') {
            if (empty($request->bank_name) || empty($request->account_number) || empty($request->transaction_id)) {
                return back()->with('error_message', 'Bank transfer requires bank name, account number, and transaction ID.');
            }
        } elseif (!in_array($paymentMethod, ['hand_cash', 'due'], true)) {
            if (empty($request->payment_number) || empty($request->transaction_id)) {
                return back()->with('error_message', 'Please provide payment number and transaction ID.');
            }
        }

        DB::beginTransaction();

        try {
            $previousPaidAmount = round((float) ($order->paid_amount ?? 0), 2);
            $previousDueAmount = round($currentDue, 2);

            $currentPaidAmount = round($previousPaidAmount + $paymentAmount, 2);
            $currentDueAmount = round(max(0, ((float) $order->total) - $currentPaidAmount), 2);
            $dueStatus = $currentDueAmount > 0 ? 'partial_due' : 'cleared';

            $history = AddonsOrderPaymentHistory::create([
                'addons_order_id' => $order->id,
                'payment_amount' => $paymentAmount,
                'previous_paid_amount' => $previousPaidAmount,
                'previous_due_amount' => $previousDueAmount,
                'current_paid_amount' => $currentPaidAmount,
                'current_due_amount' => $currentDueAmount,
                'due_amount_status' => $dueStatus,
                'payment_method' => $paymentMethod,
                'payment_number' => $request->payment_number,
                'transaction_id' => $request->transaction_id,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'note' => $request->note,
                'created_by' => Auth::id(),
            ]);

            $order->payment_method = $paymentMethod;
            $order->payment_number = $request->payment_number ?: $order->payment_number;
            $order->transaction_id = $request->transaction_id ?: $order->transaction_id;
            $order->bank_name = $request->bank_name ?: $order->bank_name;
            $order->account_number = $request->account_number ?: $order->account_number;
            $order->paid_amount = $currentPaidAmount;
            $order->due_amount = $currentDueAmount;
            $order->due_amount_status = $dueStatus;
            $order->save();

            DB::commit();

            return redirect()
                ->route('superadmin.planorder.view.invoice', [
                    'id' => $order->id,
                    'payment_history_id' => $history->id,
                ])
                ->with('success_message', 'Due payment updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error_message', 'Could not update due payment. Please try again.');
        }
    }

    public function acceptDuePaymentRequest($historyId)
    {
        $history = AddonsOrderPaymentHistory::with('order')->find($historyId);

        if (!isset($history) || !isset($history->order)) {
            return back()->with('error_message', 'Due payment request not found.');
        }

        if (($history->due_amount_status ?? '') !== 'pending_acceptance') {
            return back()->with('error_message', 'This due payment request is already processed.');
        }

        $order = $history->order;

        DB::beginTransaction();

        try {
            $finalDueStatus = ((float) ($history->current_due_amount ?? 0) > 0) ? 'partial_due' : 'cleared';

            $order->payment_method = $history->payment_method ?: $order->payment_method;
            $order->payment_number = $history->payment_number ?: $order->payment_number;
            $order->transaction_id = $history->transaction_id ?: $order->transaction_id;
            $order->bank_name = $history->bank_name ?: $order->bank_name;
            $order->account_number = $history->account_number ?: $order->account_number;
            $order->paid_amount = (float) ($history->current_paid_amount ?? $order->paid_amount);
            $order->due_amount = (float) ($history->current_due_amount ?? $order->due_amount);
            $order->due_amount_status = $finalDueStatus;
            $order->status = 'Complete';
            $order->save();

            $history->due_amount_status = $finalDueStatus;
            $history->save();

            DB::commit();

            return redirect()->route('superadmin.planorder.view.invoice', [
                'id' => $order->id,
                'payment_history_id' => $history->id,
            ])->with('success_message', 'Due payment request accepted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error_message', 'Could not accept due payment request. Please try again.');
        }
    }

    public function rejectmodulus($id)
    {
        $data = ModulusPayment::find($id);
        $data->status = 0;
        $data->update();

        Session::flash('message', 'Order Rejected');
        return back();
    }

    public function acceptmodulus($id)
    {
        $data = ModulusPayment::find($id);
        $data->status = 1;
        $data->update();

        Session::flash('message', 'Order Accept');
        return back();
    }

    public function productrecycle()
    {
        $urls = "productrecycle";
        $products = Product::where('status', 'RecycleBin')->get();

        return view('superadmin.recyclebin.product')->with('products', $products)->with('urls', $urls);
    }

    public function restoreproduct($id)
    {
        $product = Product::find($id);
        $product->status = "active";
        $product->update();

        Session::flash('message', 'Successfully Restore Product');
        return back();
    }

    public function categoryrecycle()
    {
        $urls = "productrecycle";
        $categories = Category::where('status', 'RecycleBin')->get();

        return view('superadmin.recyclebin.category')->with('categories', $categories)->with('urls', $urls);
    }

    public function categoryrestore($id)
    {
        $product = Category::find($id);
        $product->status = "active";
        $product->save();

        Session::flash('message', 'Successfully Restore Category');
        return back();
    }

    public function deleteallproduct(Request $request)
    {
        $text3 = $request->text3;
        $text = explode(',', $text3);
        if (isset($text) && count($text) > 0) {
            foreach ($text as $txt) {
                $product = Product::find($txt);
                $product->delete();
            }
            Session::flash('message', 'Successfully Delete Product');
            return redirect()->back();
        } else {
            Session::flash('error', 'No Product Selected');
            return redirect()->back();
        }
    }

    public function restoreallproduct(Request $request)
    {
        $text3 = $request->text2;
        $text = explode(',', $text3);
        if (isset($text) && count($text) > 0) {
            foreach ($text as $txt) {
                $product = Product::find($txt);
                $product->status = "active";
                $product->save();
            }
            Session::flash('message', 'Successfully Restore Product');
            return back();
        } else {
            Session::flash('error', 'No Product Selected');
            return back();
        }
    }

    public function deleteallcategory(Request $request)
    {
        $text3 = $request->text3;
        $text = explode(',', $text3);
        if (isset($text) && count($text) > 0) {
            foreach ($text as $txt) {
                $product = Category::find($txt);
                $product->delete();
            }
            Session::flash('message', 'Successfully Delete category');
            return back();
        } else {
            Session::flash('error', 'No category Selected');
            return back();
        }
    }

    public function restoreallcategory(Request $request)
    {
        $text3 = $request->text2;
        $text = explode(',', $text3);
        if (isset($text) && count($text) > 0) {
            foreach ($text as $txt) {
                $product = Category::find($txt);
                $product->status = "active";
                $product->save();
            }
            Session::flash('message', 'Successfully Restore category');
            return back();
        } else {
            Session::flash('error', 'No category Selected');
            return back();
        }
    }

    public function todayplanorderrequest()
    {
        $urls = "planorderrequest";
        $data = Planorder::where('status', 'Processing')->whereDate('created_at', Carbon::today())->orderBy(
            'id',
            'DESC'
        )->get();

        return view('superadmin.order.todayplanorder')->with('urls', $urls)->with('data', $data);
    }

    public function addonssmobileapps()
    {
        $urls = "addonsss";
        $mobileapps = Mobileapp::all();

        return view('superadmin.addons.mobileapps')->with('urls', $urls)->with('data', $mobileapps);
    }

    public function addonsAdd()
    {
        $urls = "addonsss";
        $addons = AddonsApi::orderBy("position", "ASC")->get();

        return view('superadmin.addons.addons')->with('urls', $urls)->with('data', $addons);
    }

    public function addonsAddStore(Request $request)
    {
        if (!empty($request->id)) {
            $addon = AddonsApi::find($request->id);
        } else {
            $addon = new AddonsApi();
        }

        if ($request->hasFile('image')) {
            if (File::exists(public_path('addons/' . $addon->image))) {
                File::delete(public_path('addons/' . $addon->image));
            }
            $imageName = 'addons' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('addons/'), $imageName);
            $addon->image = $imageName;
        }
        //        dd($request->offerPrice);
        $addon->title = $request->title;
        $addon->name = $request->name;
        $addon->type = $request->type;
        $addon->price = $request->price;
        $addon->offerprice = $request->offerPrice;
        $addon->usd_offer_price = json_encode($request->usd_offer_price);
        $addon->usd_price = json_encode($request->usd_price);
        $addon->monthorvalue = 'null';
        $addon->status = 1;
        $addon->position = $request->position ?? 0;

        if ($request->type == 'monthly') {
            $addon->price = $request->mprice;
            $addon->offerprice = $request->mofferPrice;
            $addon->monthorvalue = $request->month;
            $addon->usd_price = json_encode($request->mouth_usd_price);
            $addon->usd_offer_price = json_encode($request->mouth_usd_offer_price);
        }
        if ($request->type == 'counter') {
            $addon->price = $request->cprice;
            $addon->offerprice = $request->cofferPrice;
            $addon->monthorvalue = $request->numberOfValue;
            $addon->usd_price = json_encode($request->count_usd_price);
            $addon->usd_offer_price = json_encode($request->count_usd_offer_price);
        }

        $addon->save();

        return back()->with('success_message', 'OK Done Bro!');
    }

    public function changeAddonstatus(Request $request)
    {
        $id = $request->id;
        $value = $request->value;
        $slider = AddonsApi::find($id);
        if (isset($slider) && $slider->status == 1) {
            $slider->status = 0;
        } else {
            $slider->status = 1;
        }
        $slider->save();
        $data = $slider;
        return response()->json($data);
    }

    public function modulusAdd()
    {
        $urls = "addonsss";
        $modulus = Modulus::orderBy("position", "ASC")->get();

        return view('superadmin.addons.modulus')->with('urls', $urls)->with('data', $modulus);
    }

    public function modulusAddStore(Request $request)
    {
        if (!empty($request->id)) {
            $modulus = Modulus::find($request->id);
        } else {
            $modulus = new Modulus();
        }

        $modulus->name = $request->name;
        $modulus->title = $request->title;
        $modulus->price = $request->price;
        $modulus->price_usd = $request->price_usd ?? 0.00;
        $modulus->rating = $request->rating;
        $modulus->no_of_rating = $request->no_of_rating;
        $modulus->no_of_user = $request->no_of_user;
        $modulus->type = $request->type ?? 0;
        $modulus->review = $request->review;
        $modulus->status = $request->status;
        $modulus->position = $request->position ?? 0;
        $modulus->config_status = isset($request->config_status) ? $request->config_status == "on" ? 1 : 0 : 0;
        $modulus->modulus_type = $request->modulus_type ?? 0;

        if ($request->image) {
            if (File::exists(public_path('modulus/' . $modulus->image))) {
                File::delete(public_path('modulus/' . $modulus->image));
            }
            $imageName = 'modulus' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('modulus/'), $imageName);
            $modulus->image = $imageName;
        }


        $modulus->save();

        return back()->with('success_message', 'Module Update successfully! ');
    }

    public function changeModulustatus(Request $request)
    {
        $id = $request->id ?? "";
        $module = Modulus::find($id);

        if (isset($module) && $module->status == 1) {
            $module->status = 0;
        } else {
            $module->status = 1;
        }
        $module->save();

        return response()->json($module);
    }

    public function websitesetup()
    {
        $urls = "addonsss";
        $data = Websitesetup::orderBy('id', 'DESC')->get();

        return view('superadmin.addons.websitesetup')->with('urls', $urls)->with('data', $data);
    }

    public function websitesetupstatus($id, $status)
    {
        $data = Websitesetup::find($id);
        if ($status == 'Complete') {
            $store = Store::find($data->store_id);
            if (isset($store)) {
                $store->access_key = mt_rand(1000000000, 9999999999);
                $store->update();

                (new StaffController())->deleteWebsiteSetupData($data->store_id);
            }

            $data->status = $status;
        } else {
            $data->status = $status;
        }

        $data->save();
        return back();
    }

    public function paymentgateway()
    {
        $urls = "addonsss";
        $data = Paymentgateway::orderBy('id', 'DESC')->get();

        return view('superadmin.addons.paymentgateway')->with('urls', $urls)->with('data', $data);
    }

    public function getnotification()
    {
        if (Auth::user()->type != 'superadmin') {
            $data[0] = false;
            $data[1] = false;
            $data[2] = false;
            $data[3] = false;
            $data[4] = false;
            return response()->json($data);
        }
        $planorder = Planorder::where('view', '0')->first();
        if (isset($planorder)) {
            $data[0] = true;
        } else {
            $customer = Customer::where('seen', null)->first();
            if (isset($customer)) {
                $data[0] = true;
            } else {
                $invoiceorder = Invoicepurchase::where('seen', null)->first();
                if (isset($invoiceorder)) {
                    $data[0] = true;
                } else {
                    $themereq = Themecustomize::where('seen', null)->first();
                    if (isset($themereq)) {
                        $data[0] = true;
                    } else {
                        $token = Tricket::where('seen', null)->first();
                        if (isset($token)) {
                            $data[0] = true;
                        } else {
                            $data[0] = false;
                        }
                    }
                }
            }
        }

        $newOrders = AddonsOrder::where('view', '0')->first();
        if (isset($newOrders)) {
            $data[2] = true;
        } else {
            $data[2] = false;
        }

        $domains = Domain::where('view', '0')->first();

        if (isset($domains)) {
            $data[3] = true;
        } else {
            $data[3] = false;
        }

        $message = Message::where('view', '0')->first();

        if (isset($message)) {
            $data[4] = true;
        } else {
            $data[4] = false;
        }

        return response()->json($data);
    }

    public function viewnotification()
    {
        $planorder = Planorder::where('view', '0')->get();
        if (isset($planorder) && count($planorder) > 0) {
            foreach ($planorder as $order) {
                $ord = Planorder::find($order->id);
                $ord->view = '1';
                $ord->save();
            }
            return redirect()->route('superadmin.planorderrequest');
        }

        $newOrders = AddonsOrder::where('view', '0')->get();
        if (isset($newOrders) && count($newOrders) > 0) {
            foreach ($newOrders as $order) {
                $ord = AddonsOrder::find($order->id);
                $ord->view = '1';
                $ord->save();
            }
            return redirect()->route('superadmin.orderPlanrequest');
        }

        $domains = Domain::where('view', '0')->get();
        if (isset($domains) && count($domains) > 0) {
            foreach ($domains as $order) {
                $ord = Domain::find($order->id);
                $ord->view = '1';
                $ord->save();
            }
            return redirect()->route('superadmin.domainrequest');
        }

        $messages = Message::where('view', '0')->get();
        if (isset($messages) && count($messages) > 0) {
            foreach ($messages as $order) {
                $ord = Message::find($order->id);
                $ord->view = '1';
                $ord->save();
            }
            return redirect()->route('messages');
        }

        $customer = Customer::where('seen', null)->get();
        if (isset($customer) && count($customer) > 0) {
            foreach ($customer as $customers) {
                $custom = Customer::find($customers->id);
                $custom->seen = "1";
                $custom->save();
            }
            return redirect()->route('admin.clients');
        }
        $orders = Invoicepurchase::where('seen', null)->get();
        if (isset($orders) && count($orders) > 0) {
            foreach ($orders as $order) {
                $customs = Invoicepurchase::find($order->id);
                $customs->seen = "1";
                $customs->save();
            }
            return redirect()->route('superadmin.invoiceorder');
        }
        $ordersss = Themecustomize::where('seen', null)->get();
        if (isset($ordersss) && count($ordersss) > 0) {
            foreach ($ordersss as $orderssss) {
                $customsss = Themecustomize::find($orderssss->id);
                $customsss->seen = "1";
                $customsss->save();
            }
            return redirect()->route('superadmin.customizerequest');
        }
    }

    public function saveappslink(Request $request)
    {
        if ($request->link == "") {
            return back();
        } else {
            $apps = Mobileapp::find($request->appid);
            $apps->url = $request->link;
            $apps->save();
            Session::flash('message', 'Successfully Save');
            return back();
        }
    }

    public function deleteallcustomer(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select At Least One Item');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = User::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Customer');
            return redirect()->back();
        }
    }

    public function deletealldomain(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select At Least One Item');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Designlist::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Domain');
            return redirect()->back();
        }
    }

    public function changedesignssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select At least one items');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return redirect()->back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Designlist::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Design list');
            return redirect()->back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Designlist::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Design List');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Designlist::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Design list');
            return redirect()->back();
        }
    }

    public function changeiconpackssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select at least one item');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Iconpack::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Icon Pack');
            return redirect()->back();
        }
    }

    public function changetemplatessstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Template');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return redirect()->back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Template::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Template');
            return redirect()->back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Template::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Template');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Template::find($ids);
                    $product->status = 'RecycleBin';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deleted Template');
            return redirect()->back();
        }
    }

    public function changestaffsssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Staff');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return redirect()->back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Superstaff::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Staff');
            return redirect()->back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Superstaff::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Staff');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Superstaff::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Staff');
            return redirect()->back();
        }
    }

    public function changerolessstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Role');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return redirect()->back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Superrole::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Active Role');
            return redirect()->back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Superrole::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            Session::flash('message', 'Successfully Deactive Role');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Superrole::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Role');
            return redirect()->back();
        }
    }

    public function changeclientssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Client');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = User::find($ids);
                    //                    $product->delete();
                    $cus = Customer::where('uid', $id)->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Customer');
            return redirect()->back();
        }
    }

    public function changeClientSetupStatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Client');
            return redirect()->back();
        }
        if ($request->action == '') {
            Session::flash('error', 'Please Select an Option');
            return redirect()->back();
        }

        $ids = array_filter(explode(',', $request->text2)); // Removes empty strings

        if (!empty($ids)) {
            Store::whereIn('id', $ids)->update([
                'setup_status' => (int) ($request->action == 1 ? 1 : 0)
            ]);
        }

        Session::flash('message', 'Status Changed Successfully!');
        return redirect()->back();
    }


    public function deleteallplanorder(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select Order');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Planorder::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted Plan Order');
            return redirect()->back();
        }
    }

    public function changenotificationstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select at least one item');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return redirect()->back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Notification::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted');
            return redirect()->back();
        }
    }

    public function invoiceorder()
    {
        $urls = "planorderrequest";
        $orders = Invoicepurchase::where('status', 'pending')->orderBy('id', 'DESC')->get();

        return view('superadmin.order.invoiceorder')->with('urls', $urls)->with('data', $orders);
    }

    public function acceptinvoiceorder($id)
    {
        $order = Invoicepurchase::find($id);
        $order->status = "approved";
        $order->save();

        Session::flash('message', 'Order Approved');
        return redirect()->back();
    }

    public function rejectinvoiceorder($id)
    {
        $order = Invoicepurchase::find($id);
        $order->status = "rejected";
        $order->save();

        Session::flash('message', 'Order Rejected');
        return redirect()->back();
    }

    public function allinvoiceorder()
    {
        $urls = "planorderrequest";
        $orders = Invoicepurchase::orderBy('id', 'DESC')->get();

        return view('superadmin.order.allinvoiceorder')->with('urls', $urls)->with('data', $orders);
    }

    public function customizerequest()
    {
        $urls = "planorderrequest";
        $orders = Themecustomize::orderBy('id', 'DESC')->get();

        return view('superadmin.order.customizereq')->with('urls', $urls)->with('data', $orders);
    }

    public function seentoken($token)
    {
        $messages = Tricket::where('token', $token)->get();
        if (isset($messages) && count($messages) > 0) {
            foreach ($messages as $msg) {
                $mm = Tricket::where('id', $msg->id)->first();
                $mm->seen = "1";
                $mm->save();
            }
        }

        return redirect()->route('superadmin.customizerequest.startchat', $token);
    }

    public function startchats($token)
    {
        $urls = "planorderrequest";
        $data = Themecustomize::where('token', $token)->first();
        if (isset($data)) {
            $messages = Tricket::where('token', $token)->get();
            if (isset($messages) && count($messages) > 0) {
                foreach ($messages as $msg) {
                    $mm = Tricket::where('token', $token)->first();
                    $mm->seen = "1";
                    $mm->save();
                }
            }
        } else {
            $data = Digitalcontent::where('token', $token)->first();
            $messages = Tricket::where('token', $token)->get();
            if (isset($messages) && count($messages) > 0) {
                foreach ($messages as $msg) {
                    $mm = Tricket::where('token', $token)->first();
                    $mm->seen = "1";
                    $mm->save();
                }
            }
        }
        return view('superadmin.chat.token')->with('data', $data)->with('urls', $urls)->with('messages', $messages);
    }

    public function sendmessagetoken(Request $request, $token)
    {
        if ($request->details == "" && $request->image == "") {
            return redirect()->back();
        } else {
            $tokens = new Tricket();
            $tokens->token = $token;
            if ($request->details == "") {
                $tokens->message = null;
            } else {
                $tokens->message = $request->details;
            }
            if ($request->image == "") {
                $tokens->image = null;
            } else {
                $imgName = Carbon::now()->timestamp . '.' . $request->image->extension();
                $request->image->storeAs('token', $imgName);
                $tokens->image = $imgName;
            }
            $tokens->sender = "superadmin";
            $tokens->seen = 1;
            $tokens->save();

            return redirect()->back();
        }
    }

    public function popupimage()
    {
        $urls = "superpopupimg";
        $st = Supersetting::find(1);

        return view('superadmin.popupimage')->with('urls', $urls)->with('st', $st);
    }

    public function savepopupimg(Request $request)
    {
        $st = Supersetting::find(1);
        if ($request->image) {
            $image_path = public_path() . '/assets/images/setting/' . $request->oldImage;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $imgName = "popup" . Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('setting', $imgName);
            $st->image = $imgName;
        }

        $st->status = $request->status ?? 0;

        $st->update();

        Session::flash('message', 'Successfully Update');
        return redirect()->back();
    }

    public function discounttimmer()
    {
        $urls = "superpopupimg";
        $st = Supersetting::find(1);

        return view('superadmin.discounttimmer')->with('urls', $urls)->with('st', $st);
    }

    public function savediscounttimmer(Request $request)
    {
        $st = Supersetting::find(1);
        $st->title = $request->title;
        $st->title2 = $request->title2;
        $st->subtitle = $request->subtitle;
        $st->discount = $request->discount;
        if ($request->image) {
            $imgName = "popup" . Carbon::now()->timestamp . '.' . $request->image->extension();
            $request->image->storeAs('setting', $imgName);
            $st->img = $imgName;
        }
        $st->save();

        Session::flash('message', 'Successfully Update');
        return redirect()->back();
    }

    public function pse()
    {
        try {
            $products = Product::where('pse', 1)->paginate(20);
            $categories = Category::select('categories.*')
                ->whereNull('categories.store_id')
                ->whereNull('categories.customer_id')
                ->get();
            return view('superadmin.pse.index', compact('products', 'categories'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function pseAccepted(Request $request)
    {
        try {
            $productId = $request->input('productId');
            $category = $request->input('categoryId');
            $urls = "pse";
            if ($productId) {
                $product = Product::find($productId);
                $product->pse = 2;
                $product->pse_status = "Accepted";
                $product->pse_cat_id = $category;
                $product->update();
                return redirect()->back();
            } else {
                $products = Product::where('pse', 2)->paginate(15);
                $categories = Category::select('categories.*')
                    ->whereNull('categories.store_id')
                    ->whereNull('categories.customer_id')
                    ->get();

                return view('superadmin.pse.index', compact('products', 'urls', 'categories'));
            }
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function pseRejected(Request $request)
    {
        try {
            $urls = "pse";
            if ($request->id) {
                $product = Product::find($request->id);
                $product->pse = 3;
                $product->update();
                return redirect()->back();
            } else {
                $products = Product::where('pse', 3)->paginate(15);
                $categories = Category::select('categories.*')
                    ->whereNull('categories.store_id')
                    ->whereNull('categories.customer_id')
                    ->get();

                return view('superadmin.pse.index', compact('products', 'urls', 'categories'));
            }
        } catch (Exception $e) {
            return view('error');
        }
    }


    public function pseSelectAction(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Product');
            return redirect()->back();
        }
        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return redirect()->back();
        }
        if ($request->action == 'accept') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Product::find($ids);
                    $product->pse = 2;
                    $product->update();
                }
            }

            Session::flash('message', 'Successfully Accepted Product');
            return redirect()->back();
        }
        if ($request->action == 'rejecte') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Product::find($ids);
                    $product->pse = 3;
                    $product->update();
                }
            }

            Session::flash('message', 'Successfully Rejected Product');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function pseView($id)
    {
        if (Auth::user()->type == 'superadmin') {
            $urls = "pse";
            $product = Product::find($id);
            return view('superadmin.pse.view')->with('product', $product)->with('urls', $urls)->with('store_id', $id);
        }
    }

    public function superAdminListSearch(Request $request)
    {
        if (canSuperStaffAccess('clients')) {
            $query = $request->search;

            $results = Product::select(
                'id',
                'name',
                'images',
                'regular_price',
                'barcode',
                'status',
                'created_at',
                'pse'
            )
                ->where('name', 'LIKE', '%' . $query . '%')
                ->where('pse', 1)
                ->paginate(20);

            $data['products'] = $results;

            $data['categories'] = Category::select('categories.*')
                ->whereNull('categories.store_id')
                ->whereNull('categories.customer_id')
                ->get();
            return view('superadmin.pse.search', $data);
        } else {
            return redirect()->route('superadmin.index');
        }
    }

    public function staffClientAccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_key' => 'required|string'
        ]);
        if ($validator->fails()) {
            Session::flash('error', 'Please enter access key');
            return redirect()->back()->withInput();
        }

        $access_key = $request->access_key;
        $store = Store::where("access_key", $access_key)->first();

        if (Auth::check()) {
            if (!is_null($store)) {
                $staff = Auth::user();
                $staffType = $staff->type ?? "";

                if ($staffType == "superstaff") {
                    //                    $staff->store_id = $store->id;
//                    $staff->save();

                    if ($store) {
                        do {
                            $access_key = mt_rand(1000000000, 9999999999);
                        } while (Store::where('access_key', $access_key)->exists());

                        $store->access_key = $access_key;
                        $store->update();
                    }

                    $storeUser = User::where("id", $store->user_id)->first();
                    if ($storeUser) {
                        Auth::logout();

                        // Log in the new user
                        Auth::login($storeUser);

                        // Redirect to the dashboard or any other page
                        return redirect()->route('admin.index')->with('success', 'Successfully switched account.');
                    }

                    //                    Session::flash('success', 'Access granted');
//                    return redirect()->back();
                }

                Session::flash('error', 'You do not have permission to access this!');
                return redirect()->back()->withInput();
            }

            Session::flash('error', 'Invalid access key');
            return redirect()->back()->withInput();
        } else {
            return redirect()->route('staff.login');
        }
    }

    public function staffClientRemoveAccess()
    {
        if (Auth::check()) {
            $staff = Auth::user();
            $staffType = $staff->type ?? "";

            if ($staffType == "superstaff") {
                $staff->store_id = NULL;
                $staff->save();

                Session::flash('success', 'Access removed');
                return redirect()->back();
            }

            Session::flash('error', 'You do not have permission to access this!');
            return redirect()->back()->withInput();
        } else {
            return redirect()->route('staff.login');
        }
    }

    /**
     * Filter order plane payment list
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function filterOrderPlanRequest(Request $request)
    {
        // Get dates from the request
        $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;

        $urls = "planorderrequest";

        // Initialize the query
        $query = AddonsOrder::with(['paymentHistories']);

        // Check if request includes a status
        if (isset($request->status) && !empty($request->status)) {
            if ($request->status === 'Processing') {
                $query->where(function ($subQuery) {
                    $subQuery->where('status', 'Processing')
                        ->orWhereHas('paymentHistories', function ($historyQuery) {
                            $historyQuery->where('due_amount_status', 'pending_acceptance');
                        });
                });
            } else {
                $query->where('status', $request->status);
            }
        } else {
            $query->where(function ($subQuery) {
                $subQuery->where('status', 'Processing')
                    ->orWhereHas('paymentHistories', function ($historyQuery) {
                        $historyQuery->where('due_amount_status', 'pending_acceptance');
                    });
            });
        }

        // Check for date ranges
        if ($from_date && !$to_date) {
            // Only from date provided
            $query->where('created_at', '>=', $from_date);
        } elseif (!$from_date && $to_date) {
            // Only to date provided
            $query->where('created_at', '<=', $to_date);
        } elseif ($from_date && $to_date) {
            // Both from and to dates provided
            $query->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
        }

        // Get AddonsOrders
        $addonsOrders = $query->get();

        // Query ModulusPayment
        $modulusQuery = ModulusPayment::query();

        // Check if request includes a status
        if (isset($request->status) && !empty($request->status)) {
            if ($request->status == "Complete") {
                $modulusQuery->where('status', 1);
            } else if ($request->status == "Failed") {
                $modulusQuery->where('status', 0);
            }
        } else {
            $modulusQuery->whereNull('status');
        }

        // Check for date ranges
        if ($from_date && !$to_date) {
            // Only from date provided
            $modulusQuery->where('created_at', '>=', $from_date);
        } elseif (!$from_date && $to_date) {
            // Only to date provided
            $modulusQuery->where('created_at', '<=', $to_date);
        } elseif ($from_date && $to_date) {
            // Both from and to dates provided
            $modulusQuery->whereBetween('created_at', [$from_date->startOfDay(), $to_date->endOfDay()]);
        }

        $modulusPayments = $modulusQuery->get();

        // Merge the results
        $mergedData = $addonsOrders->merge($modulusPayments);

        // Sort by `created_at` in descending order
        $sortedData = $mergedData->sortByDesc('created_at')->values();

        // Paginate manually
        $page = request()->get('page', 1); // Get current page
        $perPage = 10; // Items per page
        $paginatedData = new LengthAwarePaginator(
            $sortedData->slice(($page - 1) * $perPage, $perPage)->values(),
            $sortedData->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Calculate total amount (only from AddonsOrder)
        $sumTotal = $addonsOrders->sum('total');
        $sumTotal += $modulusPayments->sum('price');

        return view('superadmin.order.orderplan')->with('urls', $urls)
            ->with('data', $paginatedData)
            ->with('status', $request->status)
            ->with('from_date', $request->from_date)
            ->with('to_date', $request->to_date)
            ->with('totalAmount', $sumTotal);

    }


    /**
     * Save seller commission for package
     *
     * @param $userID
     * @param $isNew
     * @param $total
     * @return void
     */
    public function giveSellerCommission($userID, $isNew, $total, $store_id, $package_month = 1)
    {
        $store = Store::where("id", $store_id)->first();
        $callStatus = $store->call_status;

        $proceed = $isNew || $callStatus > 0;

        $commission = SuperstaffSalesCommission::where("user_id", $userID ?? "")->first();
        $commissionCount = StorePurchaseHistory::where("user_id", $userID ?? "")->where("seller_id", $commission->staff_id ?? "")->count();

        if (isset($commission) && $proceed && $commissionCount < 2) {
            $staff_id = $commission->staff_id ?? "";
            $getBalance = $this->getSellerCommissionBalance($staff_id);

            $commission_balance = new SuperstaffSalesCommissionBalance();
            $commission_balance->user_id = $userID;
            $commission_balance->staff_id = $staff_id;
            $commission_balance->store_id = $store_id;
            if ($isNew) {
                $commission_balance->isNew = 1;
                $commission_balance->new_commission = $commission->new_commission ?? 0.0;
                $commission_amount = $this->calculateSellerCommission($commission, "new", $total, $package_month);
            } else {
                $commission_balance->isRenew = 1;
                $commission_balance->renew_commission = $commission->renew_commission ?? 0.0;
                $commission_amount = $this->calculateSellerCommission($commission, "renew", $total, $package_month);
            }

            $commission_balance->commission_amount = $commission_amount;
            $commission_balance->cr = $commission_amount;
            $totalBalance = $getBalance + $commission_amount;
            $commission_balance->total_amount = $total;
            $commission_balance->balance = $totalBalance;
            $commission_balance->save();

            $store->call_status = 0;
            $store->save();
        }

    }


    /**
     * Save seller commission for setup
     *
     * @param $userID
     * @param $total
     * @return void
     */
    public function giveSellerCommissionForSetup($userID, $total, $store_id)
    {
        $commission = SuperstaffSalesCommission::where("user_id", $userID ?? "")->first();
        if (isset($commission)) {
            $staff_id = $commission->staff_id ?? "";
            if (!is_null($commission->setup_amount) && $commission->setup_amount > 0) {
                $total = $commission->setup_amount;
            }
            $getBalance = $this->getSellerCommissionBalance($staff_id);

            $commission_balance = new SuperstaffSalesCommissionBalance();
            $commission_balance->user_id = $userID;
            $commission_balance->staff_id = $staff_id;
            $commission_balance->store_id = $store_id;
            $commission_balance->isSetup = 1;
            $commission_balance->setup_commission = $commission->setup_commission ?? 0.0;
            $commission_amount = $this->calculateSellerCommission($commission, "setup", $total);
            $commission_balance->commission_amount = $commission_amount;
            $commission_balance->cr = $commission_amount;
            $totalBalance = $getBalance + $commission_amount;
            $commission_balance->total_amount = $total;
            $commission_balance->balance = $totalBalance;
            $commission_balance->save();
        }

    }


    /**
     * Calculate seller commission
     *
     * @param $commission
     * @param $total
     * @return float
     */
    public function calculateSellerCommission($commission, $for, $total, $package_month = 1)
    {
        if ($for == "new" || $for == "renew") {
            if ($for == "renew") {
                $new_commission = $commission->renew_commission ?? 0;
                $renew_commission = $commission->renew_commission ?? 0;
            } else {
                $new_commission = $commission->new_commission ?? 0;
                $renew_commission = $commission->renew_commission ?? 0;
            }

            if ($package_month > 1) {
                $commissionAmountPerMonth = (float) $total / (float) $package_month;

                // First month commission
                $firstMonth = $this->commissionCalculate($new_commission, $commissionAmountPerMonth);

                $restOfMonth = $package_month - 1; // Rest of month
                $restMonthCommissionAmount = ($commissionAmountPerMonth * $restOfMonth); // Rest of month commission amount
                $restMonth = $this->commissionCalculate($renew_commission, $restMonthCommissionAmount); // Rest of month commission

                return $firstMonth + $restMonth;
            } else {
                return $this->commissionCalculate($new_commission, $total);
            }

        } elseif ($for == "setup") {
            $setup_commission = $commission->setup_commission ?? 0;

            return $this->commissionCalculate($setup_commission, $total);
        } else {
            return 0;
        }

    }


    /***
     * Commission calculate
     *
     * @param $commission
     * @param $total
     * @return float
     */
    public function commissionCalculate($commission, $total)
    {
        return (((float) $commission * (float) $total) / 100);
    }


    /**
     * Get seller commission balance
     *
     * @param $staff_id
     * @return float|int
     */
    public function getSellerCommissionBalance($staff_id)
    {
        // Use the query builder to calculate the balance amount
        $balance = \Illuminate\Support\Facades\DB::table('superstaff_sales_commission_balances')
            ->select(DB::raw('SUM(dr) - SUM(cr) as balance_amount'))
            ->where('staff_id', $staff_id)
            ->value('balance_amount');

        // If the balance is null, set it to 0
        return abs($balance) ?? 0;
    }


    /**
     * Assign customer to seller
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function assignSeller(Request $request)
    {
        if (Auth::check() && Auth::user()->type == "superadmin") {
            $user_id = $request->user_id ?? "";
            $staff_id = $request->staff_id ?? "";
            $sales_id = $request->sales_id ?? "";

            if (empty($user_id) || is_null($user_id)) {
                return redirect()->back()->with("error", "Customer ID Missing!");
            }

            if (empty($staff_id) || is_null($staff_id)) {
                return redirect()->back()->with("error", "Please select a staff!");
            }

            $staff = Superstaff::where('id', $staff_id)->where("status", "active")->first();
            if ($staff) {
                $assignClient = SuperstaffSalesCommission::where("id", $sales_id)->first();

                if (!isset($assignClient)) {
                    $assignClient = new SuperstaffSalesCommission();
                }
                $assignClient->user_id = $user_id;
                $assignClient->staff_id = $staff_id;
                $assignClient->new_commission = $request->new_commission ?? $staff->new_commission ?? 0.00;
                $assignClient->renew_commission = $request->renew_commission ?? $staff->renew_commission ?? 0.00;
                $assignClient->setup_commission = $request->setup_commission ?? $staff->setup_commission ?? 0.00;
                $assignClient->setup_amount = $request->setup_amount ?? NULL;
                $assignClient->save();

                return redirect()->back()->with("success", "Staff assign successfully!");
            }
            return redirect()->back()->with("error", "Staff not found!");

        } else {
            return redirect()->back()->with("error", "You are not authorized!");
        }

    }

    /**
     *
     * Staff make the customer own
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeMySeller(Request $request)
    {
        $user_id = $request->id ?? "";
        if (empty($user_id) || is_null($user_id)) {
            return response()->json(['status' => false, 'message' => 'Client ID missing.']);
        }

        if (Auth::check() && Auth::user()->type == "superstaff") {
            $user = User::where('id', $user_id)->first();

            if ($user) {
                $staff = Superstaff::where('uid', Auth::user()->id ?? "")->where("status", "active")->first();

                if (isset($staff)) {
                    $staff_id = $staff->id ?? "";

                    $assignClient = SuperstaffSalesCommission::where("staff_id", $staff_id)->where("user_id", $user_id)->first();

                    if (!isset($assignClient)) {
                        $assignClient = new SuperstaffSalesCommission();
                        $assignClient->user_id = $user_id;
                        $assignClient->staff_id = $staff_id;
                        $assignClient->new_commission = $request->new_commission ?? $staff->new_commission ?? 0.00;
                        $assignClient->renew_commission = $request->renew_commission ?? $staff->renew_commission ?? 0.00;
                        $assignClient->setup_commission = $request->setup_commission ?? $staff->setup_commission ?? 0.00;
                        $assignClient->save();

                        return response()->json(['status' => true, 'message' => 'Customer save successfully!']);
                    } else {
                        $assignClient->delete();

                        return response()->json(['status' => true, 'message' => 'Customer removed successfully!']);
                    }
                }

                return response()->json(['status' => false, 'message' => 'Super staff May be Inactive!']);
            }
            return response()->json(['status' => false, 'message' => 'User not found!']);
        } else {
            return response()->json(['status' => false, 'message' => 'Please login first!']);
        }

    }

    /**
     * Display cpanel zone record
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function cpanelZoneRecord(Request $request)
    {
        if (!Auth::check() || Auth::user()->type != "superadmin") {
            return redirect()->back();
        }

        $perPage = 20;
        $zoneList = ZoneRecord::orderBy('id', 'DESC')->paginate($perPage);

        $id = $request->id ?? "";
        $zone = null;
        if (isset($id) && !empty($id)) {
            $zone = ZoneRecord::where("id", $id)->first();
        }

        return view('superadmin.cpanelZone.index', ["zoneList" => $zoneList, "zone" => $zone]);

    }


    /**
     *
     * Store or update zone record
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeOrUpdateZoneRecord(Request $request)
    {
        if (!Auth::check() || Auth::user()->type != "superadmin") {
            return redirect()->back();
        }

        // Define validation rules
        $rules = [
            'id' => 'nullable|exists:zone_records,id',
            'type' => 'required|string|in:A,CNAME,MX,TXT,SRV', // Adjust types as needed
            'value' => 'required|string|max:255',
        ];

        // Define custom error messages (optional)
        $messages = [
            'id.exists' => 'The specified record does not exist.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The type must be one of the following: A, CNAME, MX, TXT, SRV.',
            'value.required' => 'The value field is required.',
        ];

        // Validate request
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $zoneExists = ZoneRecord::where('type', $request->type)->where('value', $request->value);

        if (!is_null($request->id) && !empty($request->id)) {
            $zoneExists = $zoneExists->where('id', '!=', $request->id);
        }

        $zoneExists = $zoneExists->first();

        if ($zoneExists) {
            Session::flash('error', 'Record already exists');
            return redirect()->back();
        }

        // Retrieve or create zone record
        $zone = ZoneRecord::find($request->id) ?? new ZoneRecord();
        $msg = $zone->exists ? 'Record has been updated successfully!' : 'Record has been saved successfully!';

        // Set record values
        $zone->type = $request->type;
        $zone->value = $request->value;
        $zone->save();

        // Flash success message
        Session::flash('success', $msg);
        return redirect()->route('cpanel.zone.record');
    }


    /**
     * Delete zone record data
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCpanelZoneRecord($id)
    {
        if (!Auth::check() || Auth::user()->type != "superadmin") {
            return redirect()->back();
        }

        if (isset($id) && !empty($id)) {
            $zone = ZoneRecord::where("id", $id)->first();
            $zone->delete();

            Session::flash('success', 'Record has been delete successfully!');
            return redirect()->back();
        }

        Session::flash('error', 'Record ID missing');
        return redirect()->back();
    }

    /**
     * Delete zone record
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function deleteSelectedRecord(Request $request)
    {
        if (!Auth::check() || Auth::user()->type != "superadmin") {
            return redirect()->back();
        }

        if ($request->text2 == '') {
            Session::flash('message', 'Please Select at least one item');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return back();
        }

        // If delete is delete then status change to delete
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    ZoneRecord::where('id', $ids)->delete();
                }
            }

            Session::flash('message', 'Successfully Deleted');
            return back();
        }
    }


    public function getStoreStaticData()
    {
        $fileName = 'static-data.js';
        $filePath = public_path($fileName);

        if (File::exists($filePath)) {
            $content = File::get($filePath);
        } else {
            $content = "Hello";
            File::put($filePath, $content);
        }

        return view('superadmin.storeStaticData.index', ['content' => $content]);
    }

    public function saveStoreStaticData(Request $request)
    {
        $fileName = 'static-data.js';
        $filePath = public_path($fileName);

        $content = $request->data ?? "";
        File::put($filePath, $content);

        return view('superadmin.storeStaticData.index', ['content' => $content]);
    }

    public function superAdminSetting()
    {
        return view('superadmin.setting.index', ['data' => SuperAdminSetting::pluck('value', 'name')]);
    }

    public function saveSuperAdminSetting(Request $request)
    {
        $settings = $request->except(['_token']);

        foreach ($settings as $key => $value) {
            SuperAdminSetting::setValue($key, $value, auth()->id() ?? null);
        }

        return back();
    }


    public function superstaffCommission(Request $request, $id = null)
    {
        $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
        $search = $request->search;

        if (!Auth::check() || Auth::user()->type != "superadmin") {
            return redirect()->back();
        }

        if (is_null($id) || empty($id)) {
            Session::flash('error', 'Staff ID Missing');
            return redirect()->back();
        }

        $staff = Superstaff::where("id", $id)->first();
        if (!isset($staff)) {
            Session::flash('error', 'Staff not found!');
            return redirect()->route("superadmin.staff");
        }

        $query = SuperstaffSalesCommissionBalance::with("store", "user")->where("staff_id", $id)->whereNotNull("user_id");

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

        $balance = SuperstaffSalesCommissionBalance::getSellerCommissionBalance($id);

        return view('superadmin.staff.commission', [
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'search' => $search,
            'staff_id' => $id,
            'commission' => $commission,
            'totalAmount' => $totalAmount,
            'totalCommission' => $totalCommission,
            "balance" => $balance
        ]);
    }


    public function superstaffPaymentHistory(Request $request, $id = null)
    {
        if (!Auth::check() || Auth::user()->type != "superadmin") {
            return redirect()->back();
        }

        if (is_null($id) || empty($id)) {
            Session::flash('error', 'Staff ID Missing');
            return redirect()->back();
        }

        $staff = Superstaff::where("id", $id)->first();
        if (!isset($staff)) {
            Session::flash('error', 'Staff not found!');
            return redirect()->route("superadmin.staff");
        }

        $from_date = $request->from_date ? Carbon::parse($request->from_date) : null;
        $to_date = $request->to_date ? Carbon::parse($request->to_date) : null;
        $staff_id = $staff->id ?? "";

        $query = SuperstaffSalesCommissionBalance::with("parent.store", "parent.user")->where("staff_id", $staff_id)->whereNull("user_id");

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
    }


    public function superstaffCommissionPay(Request $request)
    {
        $amount = $request->amount ?? 0;
        $staff_id = $request->staff_id ?? "";

        if (!Auth::check() || Auth::user()->type != "superadmin") {
            return redirect()->back();
        }

        if (is_null($staff_id) || empty($staff_id)) {
            Session::flash('error', 'Staff ID Missing');
            return redirect()->back();
        }

        if (is_null($amount) || empty($amount) || $amount == 0) {
            Session::flash('error', 'Amount must be greater than 0!');
            return redirect()->back();
        }

        $balance = (float) SuperstaffSalesCommissionBalance::getSellerCommissionBalance($staff_id);

        if ($balance < $amount) {
            Session::flash('error', 'Amount must not be greater than balance amount!');
            return redirect()->back();
        }

        $dr = (float) $amount;
        $totalBalance = $balance - $dr;

        $commission_balance = new SuperstaffSalesCommissionBalance();
        $commission_balance->staff_id = $staff_id;
        $commission_balance->dr = $dr;
        $commission_balance->balance = $totalBalance;
        $commission_balance->pay_status = 1;
        $commission_balance->save();

        Session::flash('success', 'Successfully pay staff commission!');
        return redirect()->back();
    }

    public function superstaffCommissionChangePayStatus(Request $request)
    {
        if (!Auth::check() || Auth::user()->type != "superadmin") {
            return redirect()->back();
        }

        $action = $request->action ?? null;
        $ids = $request->text2 ?? null;
        if (is_null($action) || empty($action)) {
            Session::flash('error', 'Please select an action!!');
            return redirect()->back();
        }
        if (is_null($ids) || empty($ids)) {
            Session::flash('error', 'Please select at least a row!!');
            return redirect()->back();
        }

        $ids = explode(",", $ids);

        if (isset($ids) && count($ids) > 0) {
            foreach ($ids as $id) {
                $item = SuperstaffSalesCommissionBalance::where("id", $id)->first();

                if (isset($item)) {
                    $amount = $item->commission_amount ?? 0;
                    $staff_id = $item->staff_id ?? "";

                    if ($amount > 0 && $staff_id) {
                        $balance = (float) SuperstaffSalesCommissionBalance::getSellerCommissionBalance($staff_id);

                        if ($action == "paid" && $item->pay_status == 0) {
                            if ($balance > $amount) {
                                $dr = (float) $amount;
                                $totalBalance = $balance - $dr;

                                $commission_balance = new SuperstaffSalesCommissionBalance();
                                $commission_balance->staff_id = $staff_id;
                                $commission_balance->dr = $dr;
                                $commission_balance->balance = $totalBalance;
                                $commission_balance->commission_id = $id;
                                $commission_balance->pay_status = 1;
                                $commission_balance->save();

                                $item->pay_status = 1;
                                $item->save();
                            }
                        } elseif ($action == "unpaid" && $item->pay_status == 1) {
                            $cr = (float) $amount;
                            $totalBalance = $balance + $cr;

                            $commission_balance = new SuperstaffSalesCommissionBalance();
                            $commission_balance->commission_amount = $amount;
                            $commission_balance->staff_id = $staff_id;
                            $commission_balance->cr = $cr;
                            $commission_balance->balance = $totalBalance;
                            $commission_balance->commission_id = $id;
                            $commission_balance->pay_status = 0;
                            $commission_balance->save();

                            $item->pay_status = 0;
                            $item->save();
                        }
                    }
                }
            }
        }

        Session::flash('success', 'Successfully pay staff commission!');
        return redirect()->back();
    }


    public function saveWhatsAppMessage(Request $request)
    {
        if (empty($request->message)) {
            return redirect()->back()->with("error", "Message must not be empty!");
        }

        $message = WhatsAppMessage::first();
        if (!$message) {
            $message = new WhatsAppMessage();
        }

        $message->message = $request->message;
        $message->save();

        return redirect()->back()->with("success", "Message saved!");
    }


}
