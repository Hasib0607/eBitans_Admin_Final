<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('fav-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('fav-icon.png') }}">
    <title>
        eBitans
    </title>

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700"/>
    <!-- Nucleo Icons -->
    <link href="{{ asset('admin/assets/css/nucleo-icons.css') }}" rel="stylesheet"/>
    <link href="{{ asset('admin/assets/css/nucleo-svg.css') }}" rel="stylesheet"/>
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet"/>
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('admin/dist/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('admin/assets/css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.12.0/css/bootstrap-tour-standalone.css" />-->
    <link rel="stylesheet" href="{{ asset('css/tour.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.12.0/js/bootstrap-tour-standalone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.9/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.9/sweetalert2.all.min.js"></script>
    <style>
        .alert-success {
            background-image: linear-gradient(195deg, #0670cb9c 0%, #09b311 100%);
        }

        .alert-success {
            color: #ffffff;
            background-color: #00ff1b9c;
            border-color: #c9e7cb;
        }
    </style>

    @stack('styles')
    @if (Session::has('lang') && Session::get('lang') == 'bn')
        <style>
            input.check-toggle-round-flat + label:after {
                top: 4px;
                left: -7px;
                bottom: 4px;
                width: 33px;
                background-color: #fff;
                -webkit-border-radius: 52px;
                -moz-border-radius: 52px;
                -ms-border-radius: 52px;
                -o-border-radius: 52px;
                border-radius: 5px;
                -webkit-transition: margin 0.2s;
                -moz-transition: margin 0.2s;
                -o-transition: margin 0.2s;
                transition: margin 0.2s;
            }
        </style>
    @else
        <style>
            input.check-toggle-round-flat + label:after {
                top: 4px;
                left: 2px;
                bottom: 4px;
                width: 33px;
                background-color: #fff;
                -webkit-border-radius: 52px;
                -moz-border-radius: 52px;
                -ms-border-radius: 52px;
                -o-border-radius: 52px;
                border-radius: 5px;
                -webkit-transition: margin 0.2s;
                -moz-transition: margin 0.2s;
                -o-transition: margin 0.2s;
                transition: margin 0.2s;
            }
        </style>
    @endif
    <style>

    </style>

    @yield('head')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea',
            plugins: [
                'a11ychecker', 'advlist', 'advcode', 'advtable', 'autolink', 'checklist', 'export',
                'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks',
                'powerpaste', 'fullscreen', 'formatpainter', 'insertdatetime', 'media', 'table', 'help',
                'wordcount'
            ],
            toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
        });
    </script>

</head>

<body class="g-sidenav-show  bg-gray-200" id="bodyss">
<div class="preloader">
    <div class="frame12">
        <div class="center">
            <div class="dot-1"></div>
            <div class="dot-2"></div>
            <div class="dot-3"></div>
        </div>
    </div>
</div>

<?php
$user = Auth::user()->id;
$user_type = Auth::user()->type;
if ($user_type == 'admin' || $user_type == 'dropshipper') {
    $customer = DB::table('customers')
        ->where('uid', $user)
        ->first();
    $store_id = $customer->active_store;
    $store = DB::table('stores')
        ->where('id', $store_id)
        ->first();
    $store_name = $store->name;
    $store_url = $store->url;
    $use = DB::table('toptools')
        ->where('uid', $user)
        ->where('store_id', $store_id)
        ->orderBy('count', 'DESC')
        ->get();
} elseif ($user_type == 'staff') {
    $staff = DB::table('staff')
        ->where('uid', $user)
        ->first();
    $store_id = $staff->store_id;
    $store = DB::table('stores')
        ->where('id', $store_id)
        ->first();
    $store_name = $store->name;
    $store_url = $store->url;
    $use = DB::table('toptools')
        ->where('uid', $user)
        ->where('store_id', $store_id)
        ->orderBy('count', 'DESC')
        ->get();
} else {
    $use = DB::table('toptools')
        ->where('uid', $user)
        ->orderBy('count', 'DESC')
        ->get();
    $store_name = Auth::user()->name;
    $store_url = Auth::user()->email;
}
?>

<div class="modal123" id="exampleModal123" style="display:none">

    <div class="modalshow modal-dialog modal-lg" id="modalshow">
        <?php $tokens = DB::table('trickets')
            ->where('seen', null)
            ->get(); ?>
        @if (isset($tokens) && count($tokens) > 0)
            @foreach ($tokens as $token)
                <div class="modal-content mt-3">
                    <div role="alert"
                         style="color: #084298;background-color: #cfe2ff;border-color: #b6d4fe;padding:15px">
                        <a href="{{ route('superadmin.customizerequest.seentoken', $token->token) }}"
                           style="color: #084298;">New Message from Token {{ $token->token }} </a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
                </div>
                <div class="modal-body">
                        <?php $orders = DB::table('planorders')
                        ->where('view', '0')
                        ->orderBy('id', 'DESC')
                        ->get(); ?>
                    @if (isset($orders) && count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer Name</th>
                                    <th>Total</th>
                                    <th>Create Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($orders) && count($orders) > 0)
                                    @foreach ($orders as $key => $order)
                                        <tr>
                                                <?php $key++; ?>
                                            <td>{{ $key }}</td>
                                                <?php
                                                $customer = DB::table('customers')
                                                    ->where('id', $order->customer_id)
                                                    ->first();
                                                $user = DB::table('users')
                                                    ->where('id', $customer->uid)
                                                    ->first();
                                                ?>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $order->total_amount }}</td>
                                            <td>{{ $order->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                        <?php $customers = DB::table('customers')
                        ->where('seen', null)
                        ->get(); ?>
                    @if (isset($customers) && count($customers) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer Name</th>
                                    <th>Create Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($customers) && count($customers) > 0)
                                    @foreach ($customers as $key => $orders)
                                        <tr>
                                                <?php $key++; ?>
                                            <td>{{ $key }}</td>
                                            <td>{{ $orders->name }}</td>
                                            <td>{{ $orders->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                        <?php $invoices = DB::table('invoicepurchases')
                        ->where('seen', null)
                        ->get(); ?>
                    @if (isset($invoices) && count($invoices) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Invoice Id</th>
                                    <th>Amount</th>
                                    <th>Create Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($invoices) && count($invoices) > 0)
                                    @foreach ($invoices as $key => $invoice)
                                        <tr>
                                                <?php $key++; ?>
                                            <td>{{ $key }}</td>
                                            <td>{{ $invoice->id }}</td>
                                            <td>{{ $invoice->amount }}</td>
                                            <td>{{ $invoice->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                        <?php $themecusrt = DB::table('themecustomizes')
                        ->where('seen', null)
                        ->get(); ?>
                    @if (isset($themecusrt) && count($themecusrt) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Theme Name</th>
                                    <th>Phone</th>
                                    <th>Create Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($themecusrt) && count($themecusrt) > 0)
                                    @foreach ($themecusrt as $key => $themecusrts)
                                        <tr>
                                                <?php $key++; ?>
                                            <td>{{ $key }}</td>
                                                <?php $ths = DB::table('templates')
                                                ->where('id', $themecusrts->theme)
                                                ->first(); ?>
                                            <td>{{ $ths->name }}</td>
                                            <td>{{ $themecusrts->phone }}</td>
                                            <td>{{ $themecusrts->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" class="btn btn-info" onclick="hidenotific()">Close</a>
                    <a href="{{ route('view.notification') }}" class="btn btn-primary">View Notification</a>
                </div>
            </div>
        @endif
    </div>
</div>
<!----admin order ---->
<?php if (Auth::user()->type == 'admin') {
    $customer = DB::table('customers')
        ->where('uid', Auth::user()->id)
        ->first();
    $store_id = $customer->active_store;
} elseif (Auth::user()->type == 'staff') {
    $staff = DB::table('staff')
        ->where('uid', Auth::user()->id)
        ->first();
    $store_id = $staff->store_id;
} ?>
@if (Auth::user()->type == 'admin')
    <div class="modal1234" id="exampleModal1234" style="display:none">
        <div class="modalshow1 modal-dialog modal-lg" id="modalshow1">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
                </div>
                <div class="modal-body">
                        <?php $orders = DB::table('orders')
                        ->where('store_id', $store_id)
                        ->where('view', null)
                        ->orderBy('id', 'DESC')
                        ->get(); ?>
                    @if (isset($orders) && count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer Name</th>
                                    <th>Total</th>
                                    <th>Create Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($orders) && count($orders) > 0)
                                    @foreach ($orders as $key => $order)
                                        <tr>
                                                <?php $key++; ?>
                                            <td>{{ $key }}</td>
                                            <td>{{ $order->name }}</td>
                                            <td>{{ $order->total }}</td>
                                            <td>{{ $order->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" class="btn btn-info" onclick="hidenotific()">Close</a>
                    <a href="{{ route('admin.view.notification') }}" class="btn btn-primary">View Notification</a>
                </div>
            </div>
        </div>
    </div>
@endif


<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header sticky" style="z-index:9999999999999">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
           aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/" target="_blank">
            <img src="{{ asset('logo-white.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            {{-- <span class="ms-1 font-weight-bold text-white">eBitans</span> --}}
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <!--  -->
    <style>
        .navbar-vertical.navbar-expand-xs .navbar-collapse {
            height: calc(100vh - 1px);
        }
    </style>
    <?php
    if (Auth::user()->type == 'admin') {
        $customer = DB::table('customers')
            ->where('uid', Auth::user()->id)
            ->first();
        $store_id = $customer->active_store;
    } elseif (Auth::user()->type == 'staff') {
        $staff = DB::table('staff')
            ->where('uid', Auth::user()->id)
            ->first();
        $store_id = $staff->store_id;
        $role = DB::table('roles')
            ->where('id', $staff->role_id)
            ->first();
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
                } elseif ($pr == 'setting') {
                    $setting = 1;
                } elseif ($pr == 'role_permission') {
                    $role_permission = 1;
                } elseif ($pr == 'pos') {
                    $pos = 1;
                } elseif ($pr == 'testimonials') {
                    $tt = 1;
                } elseif ($pr == 'theme_customize') {
                    $theme_customize = 1;
                } elseif ($pr == 'smm') {
                    $smm = 1;
                } elseif ($pr == 'activity_log') {
                    $activity_log = 1;
                } elseif ($pr == 'inventory') {
                    $inventory = 1;
                } else {
                }
            }
        }
    } else {
        $store_id = 0;
    }
    if ($store_id != 0) {
        $store = DB::table('stores')
            ->where('id', $store_id)
            ->first();
        if ($store->plan_id != 'NULL') {
            if ($store->expiry_date <= Carbon\Carbon::now()) {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date <= Carbon\Carbon::now()) {
                        $exp = 1;
                    } else {
                        $exp = 0;
                    }
                } else {
                    $exp = 1;
                }
            } else {
                $exp = 0;
            }
        } else {
            if (isset($store->pos_plan_id) && $store->pos_plan_expiry_date >= Carbon\Carbon::now()) {
                $posplan = 1;
                $exp = 1;
            } else {
                $posplan = null;
                $exp = 0;
            }
            if (isset($store->digital_plan_id) && Carbon\Carbon::parse($store->digital_plan_end_date) >= Carbon\Carbon::now()) {
                $digitalplan = 1;
            } else {
                $digitalplan = null;
            }
        }
    }
    if (isset($store->pos_plan_id) && $store->pos_plan_expiry_date >= Carbon\Carbon::now()) {
        $posplan = 1;
    } else {
        $posplan = null;
    }
    if (isset($store->digital_plan_id) && Carbon\Carbon::parse($store->digital_plan_end_date) >= Carbon\Carbon::now()) {
        $digitalplan = 1;
        $dexp = 0;
    } else {
        $digitalplan = null;
        $dexp = 1;
    }
    ?>
    @if (Auth::user()->type == 'staff')
            <?php
            $stafff = DB::table('staff')
                ->where('uid', Auth::user()->id)
                ->first();
            if (isset($stafff)) {
                if (isset($stafff->pos)) {
                    $staff_pos = 1;
                } else {
                    $staff_pos = 0;
                }
            } else {
                $staff_pos = 0;
            }
            ?>
    @endif
    <?php if (Auth::user()->type == 'superstaff') {
        $superstaff = DB::table('superstaffs')
            ->where('uid', Auth::user()->id)
            ->first();
        $superrole = DB::table('superroles')
            ->where('id', $superstaff->role_id)
            ->first();
        $permissionss = explode(',', $superrole->permission);
        foreach ($permissionss as $key => $prs) {
            if ($prs == 'branch_delete_request') {
                $branch_delete_request = 1;
            } elseif ($prs == 'customer') {
                $customers = 1;
            } elseif ($prs == 'domain') {
                $domain = 1;
            } elseif ($prs == 'domain_request') {
                $domain_request = 1;
            } elseif ($prs == 'design') {
                $design = 1;
            } elseif ($prs == 'template') {
                $templatess = 1;
            } elseif ($prs == 'order') {
                $order = 1;
            } elseif ($prs == 'reports') {
                $reports = 1;
            } elseif ($prs == 'review') {
                $review = 1;
            } elseif ($prs == 'staff') {
                $staff = 1;
            } elseif ($prs == 'role_and_permission') {
                $role_and_permission = 1;
            } elseif ($prs == 'clients') {
                $clients = 1;
            } elseif ($prs == 'plan_order') {
                $plan_order = 1;
            } elseif ($prs == 'plans') {
                $plans = 1;
            } elseif ($prs == 'smm') {
                $smm = 1;
            } elseif ($prs == 'notification') {
                $notification = 1;
            } elseif ($prs == 'message') {
                $messages = 1;
            } else {
            }
        }
    } ?>

    <div class="collapse  navbar-collapse w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'dashboard') active bg-gradient-primary @endif @endif "
                   href="/">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                            ড্যাশবোর্ড
                        @else
                            Dashboard
                        @endif
                        </span>
                </a>
            </li>


            @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superadminstaff')
                <li class="nav-item">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'pos') active bg-gradient-primary @endif @endif "
                       href="@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/pos @endif @endif"
                       target="_blank">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">POS</span>
                    </a>
                </li>
            @endif

            @if ((isset($staff) && $staff == '1') || Auth::user()->type == 'superadmin')
                <li class="nav-item">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'ebi-analytics') active bg-gradient-primary @endif @endif"
                       href="{{ route('super.admin.ebitans.analytics') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                ইবিট্যান্টস বিশ্লেষণ
                            @else
                                Ebitans Analytics
                            @endif
                            </span>
                    </a>
                </li>
            @endif


            {{-- Products Manage --}}
            @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superadminstaff')
                <li class="nav-item">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'pos') active bg-gradient-primary @endif @endif "
                       href="{{ route('superadmin.store.category') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                দোকান ব্যবস্থাপনা
                            @else
                                Store Management
                            @endif
                            </span>
                    </a>
                </li>
            @endif
            {{-- Products Manage end  --}}



            @if ((isset($branch) && $branch == '1') || (isset($staff_pos) && $staff_pos == '1') || Auth::user()->type == 'admin')
                @if (isset($store))
                        <?php $plan = DB::table('plans')
                        ->where('id', $store->plan_id)
                        ->first(); ?>
                    @if (isset($posplan))
                        <li class="nav-item">
                            <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'branch') active bg-gradient-primary @endif @endif "
                               href="{{ URL::to('/') }}/branch">
                                <div
                                    class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/ios-glyphs/20/ffffff/shop.png"/>
                                </div>
                                <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        স্টোর
                                        ব্রাঞ্চ
                                    @else
                                        Store Branch
                                    @endif
                                    </span>
                            </a>
                        </li>
                    @endif
                @endif
            @endif
            @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')
                @if ((isset($branch_delete_request) && $branch_delete_request == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'branchdel') active bg-gradient-primary @endif @endif "
                           href="{{ URL::to('/') }}/branchdel">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Branch Delete Request</span>
                        </a>
                    </li>
                @endif
                @if ((isset($customers) && $customers == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'supercustomer') active bg-gradient-primary @endif @endif "
                           href="{{ URL::to('/') }}/superadmin/customer">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Customer</span>
                        </a>
                    </li>
                @endif
                @if ((isset($domain) && $domain == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'domainlist') active bg-gradient-primary @endif @endif "
                           href="{{ URL::to('/') }}/domain/list">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Domain</span>
                        </a>
                    </li>
                @endif
                @if ((isset($domain_request) && $domain_request == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'domain') active bg-gradient-primary @endif @endif "
                           href="{{ URL::to('/') }}/domain/request">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Domain Request</span>
                        </a>
                    </li>
                @endif
                @if ((isset($design) && $design == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'designlist') active bg-gradient-primary @endif @endif "
                           href="{{ URL::to('/') }}/design/list">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Design</span>
                        </a>
                    </li>
                @endif
                @if ((isset($templatess) && $templatess == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'templates') active bg-gradient-primary @endif @endif "
                           href="{{ route('superadmin.template') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Template</span>
                        </a>
                    </li>
                @endif
            @endif
            @if (
                (isset($product) && $product == '1') ||
                    (isset($category) && $category == '1') ||
                    (isset($subcategory) && $subcategory == '1') ||
                    (isset($brand) && $brand == '1') ||
                    (isset($attribute) && $attribute == '1') ||
                    (isset($supplier) && $supplier == '1') ||
                    (isset($collection) && $collection == '1') ||
                    (isset($global_tab) && $global_tab == '1') ||
                    Auth::user()->type == 'admin')
                <li class="nav-item" id="producttour">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'product') active bg-gradient-primary @endif @endif "
                       href="@if (isset($exp) && $exp == '1' && isset($dexp) && $dexp == '1') # @else {{ URL::to('/') }}/products @endif">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img src="https://img.icons8.com/material/20/ffffff/shipping-product.png"/>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                প্রোডাক্টস
                            @else
                                Products
                            @endif
                            </span>
                    </a>
                </li>
            @endif
            @if (isset($exp) && $exp != '1')
                @if (
                    (isset($coupon) && $coupon == '1') ||
                        (isset($campaign) && $campaign == '1') ||
                        (isset($offer) && $offer == '1') ||
                        Auth::user()->type == 'admin')

                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'promotion') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.promotion.coupon') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img
                                    src="https://img.icons8.com/external-flatart-icons-solid-flatarticons/22/ffffff/external-offer-shopping-and-commerce-flatart-icons-solid-flatarticons.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    অফার/ প্রোমোশন
                                @else
                                    Offer/ Promotion
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif
            @if ((isset($exp) && $exp != '1') || isset($posplan))
                @if ((isset($inventory) && $inventory == '1') || Auth::user()->type == 'admin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'inventory') active bg-gradient-primary @endif @endif"
                           href=" @if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.inventory') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img src="https://img.icons8.com/glyph-neue/20/ffffff/warehouse-1.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    মালগুদাম
                                @else
                                    Inventory
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif
            @if ((isset($exp) && $exp != '1') || isset($posplan))
                @if (Auth::user()->type == 'admin' || Auth::user()->type == 'staff')
                    <li class="nav-item" id="ordertour">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'order') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.order') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img
                                    src="https://img.icons8.com/ios-glyphs/20/ffffff/shopping-basket-success.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    অর্ডার
                                @else
                                    Orders
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif
            @if (Auth::user()->type == 'superadmin')
                <li class="nav-item">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'superpopupimg') active bg-gradient-primary @endif @endif "
                       href="{{ URL::to('/') }}/superadmin/popupimage">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">Popup Image</span>
                    </a>
                </li>
            @endif

            @if ((isset($invoice) && $invoice == '1') || Auth::user()->type == 'admin')

            @endif


            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                        ওয়েবসাইট ডিজাইন
                    @else
                        Website Design
                    @endif
                </h6>
            </li>
            @if (isset($exp) && $exp != '1')
                @if (
                    (isset($slider) && $slider == '1') ||
                        (isset($banner) && $banner == '1') ||
                        (isset($layouts) && $layouts == '1') ||
                        (isset($template) && $template == '1') ||
                        (isset($header) && $header == '1') ||
                        (isset($homepage) && $homepage == '1') ||
                        (isset($footer) && $footer == '1') ||
                        (isset($mobilemenu) && $mobilemenu == '1') ||
                        (isset($product_display) && $product_display == '1') ||
                        (isset($product_grid) && $product_grid == '1') ||
                        (isset($shop_page) && $shop_page == '1') ||
                        (isset($tt) && $tt == '1') ||
                        Auth::user()->type == 'admin')
                    <li class="nav-item" id="designtour">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'design') active bg-gradient-primary @endif @endif"
                           href=" @if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/design/theme @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img
                                    src="https://img.icons8.com/ios-filled/20/ffffff/windows10-personalization.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ডিজাইন
                                @else
                                    Design
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif

            @if ((isset($pages) && $pages == '1') || Auth::user()->type == 'admin')

            @endif

            @if (
                (isset($theme_customize) && $theme_customize == '1') ||
                    (isset($activity_log) && $activity_log == '1') ||
                    Auth::user()->type == 'admin')

                <li class="nav-item">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'addons') active bg-gradient-primary @endif @endif"
                       href=" {{ route('admin.themecustomize') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img
                                src="https://img.icons8.com/ios-filled/20/ffffff/camera-addon-identification.png"/>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                অ্যাডন
                            @else
                                Addons
                            @endif
                            </span>
                    </a>
                </li>
            @endif
            @if (isset($digitalplan))
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            সোশ্যাল মিডিয়া মার্কেটিং
                        @else
                            Social Media Marketing
                        @endif
                    </h6>
                </li>


                <li class="nav-item" id="designtour">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'digital') active bg-gradient-primary @endif @endif"
                       href=" @if (isset($dexp)) @if ($dexp == '1') # @else {{ URL::to('/') }}/digital_marketing @endif @endif">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img src="https://img.icons8.com/ios-filled/20/ffffff/windows10-personalization.png"/>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                সোশ্যাল মিডিয়া
                                মার্কেটিং
                            @else
                                Social Media Marketing
                            @endif
                            </span>
                    </a>
                </li>
            @endif

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                        অ্যাকাউন্ট
                    @else
                        Account
                    @endif
                </h6>
            </li>

            <!--@if ((isset($role_permission) && $role_permission == '1') || Auth::user()->type == 'admin')-->
            <!--<li class="nav-item">-->
            <!--  <a class="nav-link text-white @if (isset($urls))
                @if ($urls == 'rolepermission')
                    active bg-gradient-primary
                @endif
            @endif" href="@if (isset($exp))
                @if ($exp == '1')
                    #
                @else
                    {{ route('admin.role.permission') }}
                @endif
            @endif">-->
            <!--    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">-->
            <!--      <i class="material-icons opacity-10">person</i>-->
            <!--    </div>-->
            <!--    <span class="nav-link-text ms-1">
@if (Session::has('lang') && Session::get('lang') == 'bn')
                রোল এবং পারমিশন

            @else
                Role & Permission
            @endif
            </span>-->
            <!--  </a>-->
            <!--</li>-->
            <!--@endif-->
            @if (Auth::user()->type == 'admin')
                <li class="nav-item" id="paymenttour">
                    <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'payment') active bg-gradient-primary @endif @endif"
                       href="{{ route('payment.payments') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img src="https://img.icons8.com/ios-filled/20/ffffff/card-security.png"/>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                পেমেন্ট
                            @else
                                Payment
                            @endif
                            </span>
                    </a>
                </li>
            @endif
            @if ((isset($exp) && $exp != '1') || isset($posplan))
                @if (Auth::user()->type == 'admin' || Auth::user()->type == 'staff')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'report') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.report') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img
                                    src="https://img.icons8.com/external-smashingstocks-glyph-smashing-stocks/20/ffffff/external-report-testimonials-and-feedback-smashingstocks-glyph-smashing-stocks.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    রিপোর্ট
                                @else
                                    Reports
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif
            @if ((isset($exp) && $exp != '1') || isset($posplan))
                @if ((isset($customer) && $customer == '1') || Auth::user()->type == 'admin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'customer') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.customer') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img src="https://img.icons8.com/ios-filled/20/ffffff/customer-insight.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    কাস্টমার
                                @else
                                    Customers
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif
            @if ((isset($exp) && $exp != '1') || isset($posplan))
                @if ((isset($staff) && $staff == '1') || Auth::user()->type == 'admin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'staff') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.staff') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img src="https://img.icons8.com/ios-filled/20/ffffff/employee-card.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    স্টাফ
                                @else
                                    Employee
                                @endif
                                </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'email') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.emaillist') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img src="https://img.icons8.com/ios-filled/20/ffffff/employee-card.png"/>
                            </div>
                            <span class="nav-link-text ms-1">Email</span>
                        </a>
                    </li>
                @endif
            @endif
            @if (Auth::user()->type == 'admin' || Auth::user()->type == 'staff')

            @endif
            <!-- <li class="nav-item">
          <a class="nav-link text-white  @if (isset($urls))
                @if ($urls == 'company')
                    active
                @endif
            @endif" href="{{ route('admin.company') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Company</span>
          </a>
        </li> -->
            @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')
                @if (Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'planorderrequest') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.planorderrequest') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Payment Request</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'productrecycle') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.productrecycle') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Recycle Bin</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'addonsss') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.mobilapps') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Addons</span>
                        </a>
                    </li>
                @endif
                @if ((isset($staff) && $staff == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'superstaff') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.staff') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Staff</span>
                        </a>
                    </li>
                @endif
                @if ((isset($smm) && $smm == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'digitalmarketing') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.digitalmarketing') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    সোশ্যাল মিডিয়া
                                    মার্কেটিং
                                @else
                                    Social Media Marketing
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
                @if ((isset($role_and_permission) && $role_and_permission == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'superrolepermission') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.role.permission') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    রোল এবং পারমিশন
                                @else
                                    Role & Permission
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
                @if ((isset($clients) && $clients == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'clients') active bg-gradient-primary @endif @endif"
                           href="{{ route('admin.clients') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Clients</span>
                        </a>
                    </li>
                @endif
                @if ((isset($plan_order) && $plan_order == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'planorder') active bg-gradient-primary @endif @endif"
                           href="{{ route('admin.planorder') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Plan Order</span>
                        </a>
                    </li>
                @endif
                @if ((isset($plans) && $plans == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'plans') active bg-gradient-primary @endif @endif"
                           href="{{ route('plans') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Plans</span>
                        </a>
                    </li>
                @endif
                @if ((isset($notification) && $notification == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'notification') active bg-gradient-primary @endif @endif"
                           href="{{ route('notification') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Notification</span>
                        </a>
                    </li>
                @endif
                @if ((isset($messages) && $messages == '1') || Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'messages') active bg-gradient-primary @endif @endif"
                           href="{{ route('messages') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Messages</span>
                        </a>
                    </li>
                @endif
            @endif
            @if (Auth::user()->type == 'superadmin')
                <li class="nav-item">
                    <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'filecontrol') active bg-gradient-primary @endif @endif"
                       href="{{ route('filecontrol') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <span class="nav-link-text ms-1">File Control</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->type == 'admin')
                <?php $act = DB::table('activities')
                ->where('store_id', $store_id)
                ->whereDate('expiry_date', '>=', Carbon\Carbon::now())
                ->first(); ?>
                @if (isset($act))
                    <!--<li class="nav-item">-->
                    <!--  <a class="nav-link text-white @if (isset($urls))
                        @if ($urls == 'activitylog')
                            active bg-gradient-primary
                        @endif
                    @endif" href="@if (isset($exp))
                        @if ($exp == '1')
                            #
                        @else
                            {{ route('admin.activitylog') }}
                        @endif
                    @endif">-->
                    <!--    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">-->
                    <!--      <i class="material-icons opacity-10">person</i>-->
                    <!--    </div>-->
                    <!--    <span class="nav-link-text ms-1">
@if (Session::has('lang') && Session::get('lang') == 'bn')
                        কার্য বিবরণ

                    @else
                        Activity Log
                    @endif
                    </span>-->
                    <!--  </a>-->
                    <!--</li>-->
                @endif
                {{-- {{ dd($exp) }} --}}
                {{-- @if (isset($exp) && $exp != '1') --}}
                <li class="nav-item" id="settingtour">
                    {{-- <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'settings') active bg-gradient-primary @endif @endif" href="@if (isset($exp)) @if ($exp == '1') # @else {{route('admin.setting')}} @endif @endif"> --}}
                    <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'settings') active bg-gradient-primary @endif @endif"
                       href="{{ route('admin.profile') }} ">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img
                                src="https://img.icons8.com/external-kiranshastry-solid-kiranshastry/20/ffffff/external-settings-coding-kiranshastry-solid-kiranshastry-1.png"/>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                সেটিংস
                            @else
                                Settings
                            @endif
                            </span>
                    </a>
                </li>
                {{-- @endif --}}
            @endif
            @if (Auth::user()->type == 'staff')
                @if (isset($exp) && $exp != '1')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'staff.profile') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.staff.profile') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Profile</span>
                        </a>
                    </li>
                @endif
            @endif


            <li class="nav-item">
                <a class="nav-link text-white " href="#"
                   onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img
                            src="https://img.icons8.com/external-sbts2018-solid-sbts2018/20/ffffff/external-logout-social-media-sbts2018-solid-sbts2018.png"/>
                    </div>
                    <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                            লগ আউট
                        @else
                            Logout
                        @endif
                        </span>
                </a>
            </li>
            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </ul>
    </div>
</aside>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" id="main">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
         navbar-scroll="true">
        <div class="container-fluid py-1 px-3">
                <?php
                if (Auth::user()->type == 'admin') {
                    $customer = DB::table('customers')
                        ->where('uid', Auth::user()->id)
                        ->first();
                    $store_id = $customer->active_store;
                } elseif (Auth::user()->type == 'staff') {
                    $staff = DB::table('staff')
                        ->where('uid', Auth::user()->id)
                        ->first();
                    $store_id = $staff->store_id;
                }
                $store = DB::table('stores')
                    ->where('id', $store_id)
                    ->first();
                ?>
            <h3 class="sitename">
                @if (Auth::user()->type == 'superadmin')
                    Super Admin
                @else
                    <a href="http://{{ $store->url ?? '' }}" target="_blank"> {{ $store->name ?? '' }} </a>
                @endif
            </h3>

            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <li class="nav-item d-xl-none d-flex align-items-center pe-1">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner" style="width: 27px;">
                            <i class="sidenav-toggler-line" style="height: 4px;"></i>
                            <i class="sidenav-toggler-line" style="height: 4px;"></i>
                            <i class="sidenav-toggler-line" style="height: 4px;"></i>
                        </div>
                    </a>
                </li>
                <div class="ms-md-auto pe-md-1 d-flex align-items-center">
                        <?php $toptools = DB::table('toptools')
                        ->get()
                        ->unique('name'); ?>
                    <div class="input-group input-group-outline search1">
                        <input type="text" id="mySearch" placeholder="Search.."
                               style="width:100%;border-radius:5px;border:1px solid #d2d6da;background-color:transparent;height:33px;">
                        <span id="cross" style="cursor:pointer">X</span>
                        <ul id="myMenu" style="border-radius:10px;margin-top:3px;">
                            @if (isset($toptools) && count($toptools) > 0)
                                @foreach ($toptools as $tp)
                                    <li><a href="{{ URL::to('/') }}{{ $tp->url }}">{{ $tp->name }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                <ul class="navbar-nav  justify-content-end">

                    <li class="nav-item d-flex align-items-center px-2" style="margin-right:10px;"
                        id="changelanguagetour">
                        <form action="{{ route('admin.changelang') }}" method="post" id="changelangform">
                            @csrf
                            <!--<select class="form-control" name="lang" style="line-height: 1.0rem !important;" id="lang">-->
                            <!--    <option value="en">English</option>-->
                            <!--    <option value="bn" @if (Session::has('lang') && Session::get('lang') == 'bn')
                                selected
                            @endif>Bangla</option>-->
                            <!--</select>-->
                            <div class="switch">
                                <input id="language-toggle" name="langtoggle"
                                       @if (Session::has('lang') && Session::get('lang') == 'bn') checked @endif
                                       class="check-toggle check-toggle-round-flat" type="checkbox">
                                <label for="language-toggle" style="margin-bottom:0px"></label>
                                <span class="on">EN</span>
                                <span class="off">BN</span>
                            </div>
                        </form>
                    </li>
                    @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superdtaff')
                    @else
                        <li class="nav-item d-flex align-items-center px-2" id="changestoretour">
                            <a @if (Auth::user()->type == 'staff') href="javascript:void(0)"
                               @else href="{{ route('admin.deactivestore') }}" @endif
                               class="nav-link text-body font-weight-bold px-0 tooltip">
                                <!--<i class="fa fa-user me-sm-1"></i>-->
                                <img src="{{ asset('img/store.png') }}" class="zoom" width="16px">
                                <!--<span class="zoom d-sm-inline d-none">Store</span>-->
                                <span class="tooltiptext tooltip-top">Store</span>
                            </a>
                        </li>
                    @endif


                    <li class="nav-item px-1 d-flex align-items-center tooltip px-2">
                        <a href="{{ route('admin.setting') }}" class="nav-link text-body p-0">


                            <!--<i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>-->
                            <img src="{{ asset('img/gear.png') }}" class="cursor-pointer zoom" width="18px">
                        </a>
                        <span class="tooltiptext tooltip-top">Settings</span>
                    </li>
                    <li class="nav-item dropdown pe-2 d-flex align-items-center px-2">
                            <?php
                            $notiorder = DB::table('orders')
                                ->where('store_id', $store_id)
                                ->where('status', 'Pending')
                                ->orderBy('id', 'DESC')
                                ->get();
                            $supernoti = DB::table('notifications')
                                ->orderBy('id', 'DESC')
                                ->get();
                            ?>
                        <a href="javascript:;" class="nav-link text-body p-0 tooltip" id="dropdownMenuButton"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <!--<i class="fa fa-bell cursor-pointer"></i>-->
                            @if (isset($supernoti) || isset($notiorder))
                                <img src="{{ asset('img/notification.png') }}" class="zoom" width="18px">
                            @else
                                <img src="{{ asset('img/bell.png') }}" class="zoom" width="18px">
                            @endif
                            <span class="tooltiptext tooltip-top">Notification</span>
                        </a>

                        <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                            aria-labelledby="dropdownMenuButton">
                            @if (isset($supernoti) && count($supernoti) > 0)
                                @foreach ($supernoti as $key => $sno)
                                    @if ($key <= 2)
                                        @if ($sno->user_type != 'User')
                                            @if (Auth::user()->type == 'admin')
                                                @if ($sno->user_type == 'Admin')
                                                    <li class="mb-2">
                                                        <a class="dropdown-item border-radius-md"
                                                           href="{{ $sno->link }}">
                                                            <div class="d-flex py-1">
                                                                {{ $sno->message }}
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endif
                                            @elseif(Auth::user()->type == 'staff')
                                                @if ($sno->user_type == 'Staff')
                                                    <li class="mb-2">
                                                        <a class="dropdown-item border-radius-md"
                                                           href="{{ $sno->link }}">
                                                            <div class="d-flex py-1">
                                                                {{ $sno->message }}
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endif
                                            @else
                                                <li class="mb-2">
                                                    <a class="dropdown-item border-radius-md"
                                                       href="{{ $sno->link }}">
                                                        <div class="d-flex py-1">
                                                            {{ $sno->message }}
                                                        </div>
                                                    </a>
                                                </li>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                            @if (isset($notiorder) && count($notiorder) > 0)
                                @foreach ($notiorder as $key => $no)
                                    @if ($key <= 4)
                                        <li class="mb-2">
                                            <a class="dropdown-item border-radius-md"
                                               href="{{ URL::to('/') }}/order/view/{{ $no->id }}">
                                                <div class="d-flex py-1">
                                                    New Order placed
                                                    {{ date('d-m-Y', strtotime($no->created_at)) }}
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif

                        </ul>
                    </li>
                    <li class="nav-item px-1 d-flex align-items-center px-2" id="visitwebsite">
                        @if (Auth::user()->type == 'superadmin')
                        @else
                            <a href="http://{{ $store->url ?? '' }}" target="_blank"
                               class="nav-link text-body p-0 tooltip">
                                <img src="{{ asset('img/web.png') }}" class="zoom" width="18px">
                                <span class="tooltiptext tooltip-top">Visit Website</span>
                            </a>
                        @endif
                    </li>
                    <!--<li class="nav-item px-1 align-items-center px-2 search2" style="display:none">-->
                    <!--    @if (Auth::user()->type == 'superadmin')
                    @else
                        -->
                        <!--  <a href="javascript:void(0)" class="nav-link text-body px-0 py-2 tooltip" id="mobilesearch">-->
                        <!--      <img src="https://img.icons8.com/color/48/000000/search--v1.png" class="zoom" width="18px"/>-->
                        <!--<span class="tooltiptext tooltip-top">Search</span>-->
                        <!--  </a>-->
                        <!--   @endif-->
                        <!--</li>-->
                </ul>
            </div>
            <div class="ms-md-auto pe-md-3 align-items-center mt-3" id="mobilesearchdiv"
                 style="width:88%;display:none;position:fixed;top:0px;z-index:9999">
                    <?php $toptools = DB::table('toptools')
                    ->get()
                    ->unique('name'); ?>
                <div class="input-group input-group-outline">
                    <input type="text" id="mySearch1" placeholder="Search.."
                           style="width:90%;border-radius:5px;border:2px solid #000;background-color:#fff;height:33px;">
                    <span id="cross1">X</span>
                    <ul id="myMenu1">
                        @if (isset($toptools) && count($toptools) > 0)
                            @foreach ($toptools as $tp)
                                <li><a href="{{ URL::to('/') }}{{ $tp->url }}">{{ $tp->name }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')
    @else
            <?php $storealert = DB::table('stores')
            ->where('id', $store->id)
            ->where('trail', 1)
            ->where('upcoming_plan_expiry_date', null)
            ->where('expiry_date', '<=', Carbon\Carbon::now()->addDays(7))
            ->first(); ?>
        @if (isset($storealert))
                <?php
                $now = Carbon\Carbon::now();
                $end_date = $storealert->expiry_date;
                $cDate = Carbon\Carbon::parse($end_date);
                $count = $now->diffInDays($cDate);
                ?>
            <div class="container-fluid" style="display:flex;justify-content:center;align-items:center">
                <div class="fixed-popup"
                     style="margin: 0 auto;text-align: center;position: relative;background-color: red;border-radius:10px;">
                    <p style="color: #fff;font-size: 15px;padding: 1px 17px;line-height: 20px;margin-bottom: 0;">
                        Your Subscribtion {{ $count + 1 }} days left, <a href="{{ route('payment.payments') }}"
                                                                         class="badge badge-primary">Pay Now</a></p>
                </div>
            </div>
        @endif
        @if ($store->trail == 0)
                <?php
                $now = Carbon\Carbon::now();
                $end_date = $store->expiry_date;
                $cDate = Carbon\Carbon::parse($end_date);
                $count = $now->diffInDays($cDate);
                ?>
            <div class="container-fluid" style="display:flex;justify-content:center;align-items:center">
                <div class="fixed-popup"
                     style="margin: 0 auto;text-align: center;position: relative;background-color: red;border-radius:10px;">
                    <p style="color: #fff;font-size: 15px;padding: 1px 17px;line-height: 20px;margin-bottom: 0;">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            আপনি সাময়িক সংস্করণ
                            ব্যবহার করছেন। স্টোর শুরু করতে আজই
                        @else
                            You are in a trial mode, your trial end in {{ $count + 1 }} Days. To start your
                            store,
                        @endif
                        <a href="{{ route('payment.payments') }}" class="badge badge-primary">Pay Now</a>
                    </p>
                </div>
            </div>
        @endif
    @endif
    <!-- End Navbar -->
    {!! Toastr::message() !!}
    @yield('content')
    <!----Loader--->


    <!-----End Loader---->
    <footer class="footer py-4  ">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6 mb-lg-0 mb-4">
                    <div class="copyright text-center text-sm text-muted text-lg-start">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        All Rights Received |
                        Developed By
                        <a href="https://www.wavebox.net" class="font-weight-bold" target="_blank">Wavebox</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    </div>
</main>
<div class="fixed-plugin" id="fixedplugin">
    @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')
    @else
        <a class="fixed-plugin-button1 text-dark position-fixed px-3"
           style="padding-top:10px;padding-bottom:10px;right:90px;background-color:#f1593a !important">
            <!--<img src="https://img.icons8.com/ios/20/000000/topic.png" class="py-2"/>-->
            <i class='far fa-comment' style='font-size:20px;color:#fff' class="py-3"></i>
        </a>
        <a class="fixed-plugin-button3 text-dark position-fixed px-3 py-2"
           style="right:90px;background-color:#f1593a !important">
            <!--<img src="https://img.icons8.com/ios/20/000000/topic.png" class="py-2"/>-->
            <!--<img src="https://img.icons8.com/ios/20/000000/multiply.png" class="py-2" />-->
            <i class="fa fa-times" aria-hidden="true" style='font-size:20px;color:#fff' class="py-2"></i>
        </a>
    @endif
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
        <i class="material-icons py-2">construction</i>
    </a>
    <div class="card shadow-lg">
        <div class="card-header pb-0 pt-3">
            <div class="float-start">
                <h5 class="mt-3 mb-0">eBitans</h5>
                <p>See our dashboard options.</p>
            </div>
            <div class="float-end mt-4">
                <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <!-- End Toggle Button -->
        </div>
        <hr class="horizontal dark my-1">
        <div class="card-body pt-sm-3 pt-0">
            <!-- Sidebar Backgrounds -->
            <div>
                <h6 class="mb-0">Sidebar Colors</h6>
            </div>
            <a href="javascript:void(0)" class="switch-trigger background-color" style="display:none">
                <div class="badge-colors my-2 text-start">
                        <span class="badge filter bg-gradient-primary active" data-color="primary"
                              onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-dark" data-color="dark"
                          onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-info" data-color="info"
                          onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-success" data-color="success"
                          onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-warning" data-color="warning"
                          onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-danger" data-color="danger"
                          onclick="sidebarColor(this)"></span>
                </div>
            </a>
            <!-- Sidenav Type -->
            <div class="mt-3">
                <h6 class="mb-0">Sidenav Type</h6>
                <p class="text-sm">Choose between 2 different sidenav types.</p>
            </div>
            <div class="d-flex">
                <button class="btn bg-gradient-dark px-3 mb-2 active" data-class="bg-gradient-dark"
                        onclick="sidebarType(this)">Dark
                </button>
                <!--<button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>-->
                <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white"
                        onclick="sidebarType(this)">White
                </button>
            </div>
            <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
            <!-- Navbar Fixed -->
            <!--<div class="mt-3 d-flex">-->
            <!--<h6 class="mb-0">Navbar Fixed</h6>-->
            <!--<div class="form-check form-switch ps-0 ms-auto my-auto">-->
            <!--    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed"  onclick="navbarFixed(this)">-->
            <!--</div>-->
            <!--</div>-->
            <hr class="horizontal dark my-3">
            <div class="mt-2 d-flex">
                <h6 class="mb-0">Light / Dark</h6>
                <div class="form-check form-switch ps-0 ms-auto my-auto">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version"
                           onclick="darkMode(this)">
                </div>
            </div>
            <hr class="horizontal dark my-sm-4">
            <div class="mt-2 d-flex">
                <h6 class="mb-0">Top Tools</h6>
            </div>
            <div class="d-flex mt-3" style="flex-wrap: wrap;justify-content: space-between;">
                @if (isset($use) && count($use) > 0)
                    @foreach ($use as $key => $uu)
                        @if ($key < 6)
                            <a class="btn bg-gradient-dark px-1 mb-2"
                               href="{{ URL::to('/') }}{{ $uu->url }}" style="width:48%"
                               data-class="bg-gradient-dark">{{ $uu->name }}</a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
</div>
    <?php $messagess = DB::table('messages')
    ->where('uid', Auth::user()->id)
    ->where('store_id', $store_id)
    ->get(); ?>
@if (isset($messagess) && count($messagess) == 0)
    @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')
    @else
        <div class="firstmessage">
            <a class="minus">-</a>
            <p>Welcome to Ebitans.<br> For any quaries we are here to help...</p>

        </div>
    @endif
@endif
<div class="chatbox" @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff') style="display:none"
     @endif style="z-index:99999">
    <div class="card">
        <div class="card-header"
             style="display:flex;justify-content:space-between;align-items:center;padding-top:10px;">
            <p style="align-items:flex-start"></p>
            <p style="align-items:flex-start;cursor:pointer;margin-bottom:0px !important;margin-top:10px !important;"
               class="crosschat"><i class="fa fa-times" aria-hidden="true"></i></p>

        </div>
        <div class="card-body" id="message">
            <ul id="messgeul" class="cartload">

            </ul>
        </div>
        <div class="d-none" id="hidendiv">
            <!--<input  type='file' id="inputFileToLoad" name="img" formenctype="value" onchange="encodeImageFileAsURL(this)" accept="image/*"/>-->
            <img id="output" width="50px" height="50px" style="position:absolute;bottom:68px;"/>
            <input id="inputFileToLoad" type="file" onchange="encodeImageFileAsURL();"
                   style="visibility:hidden;display:none"/>
            <input type="hidden" name="base64img" id="base64img">
            <audio id="recorder" muted hidden></audio>

            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        </div>
        <div class="card-footer " style="padding:17px">
            <div class="send d-felx">
                <label for="inputFileToLoad"
                       style="position: absolute;left:1px;z-index: 99999999;margin-top: 16px;">
                    <img src="https://img.icons8.com/ios/28/000000/add--v1.png"/>
                </label>
                <!--   <div>-->
                <!--	<button id="start">Record</button>-->
                <!--	<button id="stop">Stop</button>-->
                <!--	<a id="download">Download</a>-->
                <!--	<audio id="player" controls></audio>-->
                <!--</div>-->
                <input type="text" name="message" class="messagebox" style="">
                <a href="javascript:void(0)" class="btn btn-primary sendimg"
                   style="margin-left:6px;margin-top:10px;padding: 7px 16px;"><i class='fa fa-send-o'></i></a>
            </div>
        </div>
    </div>
</div>
<!----Mobile Bottom Menu Start----->
<div class='frame'>
    <div class='bar'>
        <a href='{{ route('admin.menu') }}' class='els-wrap el-1'>
            <div class='icon' id="iconNavbarSidenav1" style="margin-left: 2px;">
                <!--<i class="fas fa-align-center"></i>-->
                <img src="https://img.icons8.com/ios-glyphs/25/000000/menu--v1.png"/>

            </div>
            <p style="font-size:12px">Menu</p>

        </a>
        <a href='javascript:;' class='els-wrap el-2' id="mobilesearch1">
            <div class='icon'>
                <!--<i class="far fa-user-circle"></i>-->
                <img src="https://img.icons8.com/ios/25/000000/search--v1.png"/>
            </div>
            <p style="font-size:12px">Search</p>
        </a>
        <a href='@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/products/create @endif @endif'
           class='els-wrap1' style="background-color: #f1593a;margin-bottom: 57px;margin-left:4px;">
            <div class='icon' style="margin-top: 5px;margin-left: 5px;height:2em">
                <!--<i class="far fa-comment-dots"></i>-->
                <!--<img src="https://img.icons8.com/color/30/ffffff/add-product.png"/>-->
                <img src="https://img.icons8.com/android/27/ffffff/plus.png"/>
            </div>
            <!--<p style="font-size:12px">Add Product</p>-->
        </a>
        <a href='@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/order @endif @endif'
           class='els-wrap el-3'>
            <div class='icon' style="margin-left: 3px;">
                <!--<i class="far fa-comment-dots"></i>-->
                <!--<i class="fa fa-solid fa-cart-circle-check"></i>-->
                <img src="https://img.icons8.com/ios-glyphs/25/000000/shopping-basket-success.png"/>
            </div>
            <p style="font-size:12px">Order</p>
        </a>
        <a href='@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/settings @endif @endif'
           class='els-wrap el-4'>
            <div class='icon' style="margin-left: 6px;">
                <!--<i class="fa fa-cog"></i>-->
                <img
                    src="https://img.icons8.com/external-tanah-basah-glyph-tanah-basah/25/000000/external-setting-essentials-pack-tanah-basah-glyph-tanah-basah.png"/>
            </div>
            <p style="font-size:12px;text-align:center">Setting</p>
        </a>

    </div>
</div>
<!-----Mobile Bottom Menu End---->
<!--Start of Tawk.to Script-->

<!--End of Tawk.to Script-->
<!--   Core JS Files   -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"
    integrity="sha512-chZc2Mx8B1GzGSNMfJRH63jW7uYZXzX0a/UlWRrTvl4kxxYqUHNMtyTTA5IDQ7gTl4ATLoXlZthsialW3muS0A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
<script src="{{ asset('admin/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin/dist/js/fontawesome-iconpicker.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!--<script src="{{ asset('js/autocomplete.js') }}"></script>-->
<script src="{{ asset('js/iconpack.js') }}"></script>
<script src="{{ asset('js/notification.js') }}"></script>

<script type="module">
    // Import the functions you need from the SDKs you need
    import {initializeApp} from "https://www.gstatic.com/firebasejs/9.10.0/firebase-app.js";
    import {getAnalytics} from "https://www.gstatic.com/firebasejs/9.10.0/firebase-analytics.js";
</script>

<script>
    $(document).ready(function () {
        //   window.addEventListener('load', fadeOutEffect);
        const myPreloader = document.querySelector('.preloader');
        fadeOutEffect();

        function fadeOutEffect() {
            setTimeout(function () {
                $('.preloader').hide();
            }, 300);
        }
    });

    $("#language-toggle").on('change', function () {
        $('#changelangform').submit();
    });
    $(".minus").on('click', function () {
        $('.firstmessage').hide();
    })
    $("#mobilesearch").on('click', function () {
        $('#mobilesearchdiv').toggle('fadeIn');
    })
    $("#iconNavbarSidenav1").on('click', function () {
        $('#toggleSidenav').toggle('fadeIn');
    })

    $("#mobilesearch1").on('click', function () {
        $('#mobilesearchdiv').toggle('fadeIn');
    })

    function hidenotific() {
        debugger;
        $('.modal123').hide();
        var audio = new Audio('https://admin.ebitans.com/img/message-ringtone-magic.ogg');
        audio.muted = false;
        audio.pause();
    }

    $('.fixed-plugin-button3').hide();
    $('.fixed-plugin-button1').on('click', function () {
        $('.chatbox').toggle();
        $('.fixed-plugin-button1').hide();
        $('.fixed-plugin-button3').show();
        $('.firstmessage').hide();
        var messagess = $('#messagecount').val();
        var message = "Hi! <?php echo $store_name; ?>";
        var message1 = "Welcome to Ebitans. For any quaries we are here to help...";
        var chatid = $('#chatidss').val();
        var storename = $('#storename').val();
        var uid = $('#uidss').val();
        var img = $('#base64img').val();
        if (messagess == 0) {
            $.post("/sendmessageadmin", {
                chatid: chatid,
                uid: uid,
                message: message,
                img: img,
                storename: storename,
                "_token": "{{ csrf_token() }}"
            }, function (data) {
                $('.messagebox').val('');
                $('.messagebox').val('');
                if (typeof (data['message']) === "undefined") {
                    $("#messgeul").append(
                        `<li class="receive"><span style="background-color:transparent;border:0px;"><img src="https://admin.ebitans.com/assets/images/message/` +
                        data['image'] + `" alt="" width="40px"/></span></li>`);
                } else {
                    $("#messgeul").append(`<li class="receive"><span>` + data['message'] +
                        `</span></li>`);
                }
                const element = document.getElementById('message');
                element.scrollTop = element.scrollHeight;
                // setTimeout(update, 1000);
            });
            $.post("/sendmessageadmin", {
                chatid: chatid,
                uid: uid,
                message: message1,
                img: img,
                "_token": "{{ csrf_token() }}"
            }, function (data) {
                $('.messagebox').val('');
                $('.messagebox').val('');
                if (typeof (data['message']) === "undefined") {
                    $("#messgeul").append(
                        `<li class="receive"><span style="background-color:transparent;border:0px;"><img src="https://admin.ebitans.com/assets/images/message/` +
                        data['image'] + `" alt="" width="40px"/></span></li>`);
                } else {
                    $("#messgeul").append(`<li class="receive"><span>` + data['message'] +
                        `</span></li>`);
                }
                const element = document.getElementById('message');
                element.scrollTop = element.scrollHeight;
                // setTimeout(update, 1000);
            });
        }
    });
    $('.fixed-plugin-button3').on('click', function () {
        $('.chatbox').toggle();
        $('.fixed-plugin-button3').hide();
        $('.fixed-plugin-button1').show();
    })
    $('.crosschat').on('click', function () {
        $('.chatbox').hide();
        $('.fixed-plugin-button3').hide();
        $('.fixed-plugin-button1').show();
    });

    function encodeImageFileAsURL() {
        var filesSelected = document.getElementById("inputFileToLoad").files;
        $('#hidendiv').removeClass('d-none');
        $('#hidendiv').addClass('d-block');

        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function () {
            URL.revokeObjectURL(output.src) // free memory
        }

        if (filesSelected.length > 0) {
            var fileToLoad = filesSelected[0];

            var fileReader = new FileReader();

            fileReader.onload = function (fileLoadedEvent) {
                var srcData = fileLoadedEvent.target.result; // <--- data: base64
                $('#base64img').val(srcData);
                var newImage = document.createElement('img');
                newImage.src = srcData;

                // document.getElementById("imgTest").innerHTML = newImage.outerHTML;
                // alert("Converted Base64 version is " + document.getElementById("imgTest").innerHTML);
                // console.log("Converted Base64 version is " + document.getElementById("imgTest").innerHTML);
            }
            fileReader.readAsDataURL(fileToLoad);
        }
    }

    $('#messgeul').scroll(function () {
        if ($('#messgeul').scrollTop() + $('#messgeul').height() == $('#messgeul').height()) {
            alert("bottom!");
        }
    });
    $(document).ready(function () {
        const element = document.getElementById('message');
        element.scrollTop = element.scrollHeight;

        //   update();
        load_home();

        async function load_home() {
            // let url = 'https://admin.ebitans.com/admin/allproducts';
            let url = 'https://admin.ebitans.com/admin/allproducts';
            messgeul.innerHTML = await (await fetch(url)).text();
            var chatid = $('#chatid').val();
            var uid = $('#uid').val();
            var store_id = "<?php echo $store_id; ?>";
            $.get("/getmessage", {
                chatid: chatid,
                uid: uid,
                store_id: store_id,
                "_token": "{{ csrf_token() }}"
            }, function (data) {

            });
            setTimeout(load_home, 1000);
        }

        function update() {
            var chatid = $('#chatid').val();
            var uid = $('#uid').val();
            var store_id = "<?php echo $store_id; ?>";
            //   $.get("/getmessage", {chatid:chatid,uid:uid,store_id:store_id,"_token": "{{ csrf_token() }}"},function(data) {

            // document.getElementById("messgeul").innerHTML='<object type="text/html" data="<?php echo url('admin/allproducts'); ?>" ></object>';
            //
            //


            const element = document.getElementById('message');
            // $("#messgeul").append(`<li class="receive"><span>Hello</span></li>`);
            // if(element.scrollTop==0){
            // element.scrollTop = element.scrollHeight;
            // }else if(element.scrollTop == element.scrollHeight){
            // element.scrollTop = element.scrollHeight;
            // }
            // });
        }

        $(document).on('click', '.sendimg', function (e) {
            var message = $('.messagebox').val();
            var chatid = $('#chatid').val();
            var uid = $('#uid').val();
            var img = $('#base64img').val();
            var storename = $('#storename').val();
            $.post("/sendmessage", {
                chatid: chatid,
                uid: uid,
                message: message,
                img: img,
                storename: storename,
                "_token": "{{ csrf_token() }}"
            }, function (data) {
                $('.messagebox').val('');
                if (typeof (data['message']) === "undefined") {
                    $("#messgeul").append(`<li class="send"><span style="background-color:transparent;border:0px;"><img
                src="https://admin.ebitans.com/assets/images/message/` + data['image'] + `" alt=""
                width="40px" /></span></li>`);
                } else {
                    $("#messgeul").append(`<li class="send"><span>` + data['message'] +
                        `</span></li>`);
                }
                $('#output').addClass('d-none');
                $('#base64img').val(null);
                const element = document.getElementById('message');
                element.scrollTop = element.scrollHeight;
                setTimeout(load_home, 1000);
            });
        });
        $(document).keypress(function (event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                var message = $('.messagebox').val();
                var chatid = $('#chatid').val();
                var uid = $('#uid').val();
                var storename = $('#storename').val();
                $.post("/sendmessage", {
                    chatid: chatid,
                    uid: uid,
                    message: message,
                    storename: storename,
                    "_token": "{{ csrf_token() }}"
                }, function (data) {
                    $('.messagebox').val('');
                    if (typeof (data['message']) === "undefined") {
                        $("#messgeul").append(`<li class="send"><span style="background-color:transparent;border:0px;"><img
                src="https://admin.ebitans.com/assets/images/message/` + data['image'] + `" alt=""
                width="40px" /></span></li>`);
                    } else {
                        $("#messgeul").append(`<li class="send"><span>` + data['message'] +
                            `</span></li>`);
                    }
                    $('#output').addClass('d-none');
                    $('#base64img').val(null);
                    const element = document.getElementById('message');
                    element.scrollTop = element.scrollHeight;
                    setTimeout(load_home, 1000);
                });
            }
        });
        $(document).on('keyup', '#mySearch', function (e) {

            var input, filter, ul, li, a, i;
            input = document.getElementById("mySearch");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myMenu");
            document.getElementById("myMenu").style.display = "block";
            document.getElementById("cross").style.display = "block";
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        });
        $(document).on('click', '#cross', function (e) {
            document.getElementById("cross").style.display = "none";
            document.getElementById("myMenu").style.display = "none";
            document.getElementById("mySearch").value = null;
        });
        $(document).on('keyup', '#mySearch1', function (e) {
            var input, filter, ul, li, a, i;
            input = document.getElementById("mySearch1");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myMenu1");
            document.getElementById("myMenu1").style.display = "block";
            document.getElementById("cross1").style.display = "block";
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        });
        $(document).on('click', '#cross1', function (e) {
            document.getElementById("cross1").style.display = "none";
            document.getElementById("myMenu1").style.display = "none";
            document.getElementById("mySearch1").value = null;
        });
    });

    function myFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("mySearch");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myMenu");
        document.getElementById("myMenu").style.display = "block";
        document.getElementById("cross").style.display = "block";
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    function myFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("mySearch1");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myMenu1");
        document.getElementById("myMenu1").style.display = "block";
        document.getElementById("cross1").style.display = "block";
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
</script>
@yield('js')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    // var select123 = document.getElementById('lang');
    // select123.onchange = function(){
    //     this.form.submit();
    // };
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<script>
    $(function () {
        $('.action-destroy').on('click', function () {
            $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
        });
        // Live binding of buttons
        $(document).on('click', '.action-placement', function (e) {
            $('.action-placement').removeClass('active');
            $(this).addClass('active');
            $('.icp-opts').data('iconpicker').updatePlacement($(this).text());
            e.preventDefault();
            return false;
        });
        $('.action-create').on('click', function () {
            $('.icp-auto').iconpicker();
            $('.icp-dd').each(function () {
                var $this = $(this);
                $this.iconpicker({
                    title: 'Dropdown with picker',
                    container: $(' ~ .dropdown-menu:first', $this)
                });
            });
            $('.icp-glyphs').iconpicker({
                title: 'Using glypghicons',
                icons: ['home', 'repeat', 'search',
                    'arrow-left', 'arrow-right', 'star'
                ],
                iconBaseClass: 'glyphicon',
                iconComponentBaseClass: 'glyphicon',
                iconClassPrefix: 'glyphicon-'
            });
            $('.icp-opts').iconpicker({
                title: 'With custom options',
                icons: ['github', 'heart', 'html5', 'css3'],
                selectedCustomClass: 'label label-success',
                mustAccept: true,
                placement: 'bottomRight',
                showFooter: true,
                // note that this is ignored cause we have an accept button:
                hideOnSelect: true,
                templates: {
                    footer: '<div class="popover-footer">' +
                        '<div style="text-align:left; font-size:12px;">Placements: \n\
                                        <a href="#" class=" action-placement">inline</a>\n\
                                        <a href="#" class=" action-placement">topLeftCorner</a>\n\
                                        <a href="#" class=" action-placement">topLeft</a>\n\
                                        <a href="#" class=" action-placement">top</a>\n\
                                        <a href="#" class=" action-placement">topRight</a>\n\
                                        <a href="#" class=" action-placement">topRightCorner</a>\n\
                                        <a href="#" class=" action-placement">rightTop</a>\n\
                                        <a href="#" class=" action-placement">right</a>\n\
                                        <a href="#" class=" action-placement">rightBottom</a>\n\
                                        <a href="#" class=" action-placement">bottomRightCorner</a>\n\
                                        <a href="#" class=" active action-placement">bottomRight</a>\n\
                                        <a href="#" class=" action-placement">bottom</a>\n\
                                        <a href="#" class=" action-placement">bottomLeft</a>\n\
                                        <a href="#" class=" action-placement">bottomLeftCorner</a>\n\
                                        <a href="#" class=" action-placement">leftBottom</a>\n\
                                        <a href="#" class=" action-placement">left</a>\n\
                                        <a href="#" class=" action-placement">leftTop</a>\n\
                                        </div><hr></div>'
                }
            }).data('iconpicker').show();
        }).trigger('click');


        // Events sample:
        // This event is only triggered when the actual input value is changed
        // by user interaction
        $('.icp').on('iconpickerSelected', function (e) {
            $('.lead .picker-target').get(0).className = 'picker-target fa-3x ' +
                e.iconpickerInstance.options.iconBaseClass + ' ' +
                e.iconpickerInstance.getValue(e.iconpickerValue);
        });
    });
</script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="{{ asset('admin/assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    @if (Session::has('message'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.success("{{ session('message') }}");
    @endif

        @if (Session::has('error'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.error("{{ session::get('error') }}");
    @endif


        @if (Session::has('info'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.info("{{ session('info') }}");
    @endif

        @if (Session::has('warning'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.warning("{{ session('warning') }}");
    @endif

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.error("{{ $error }}");
    @endforeach
        @endif


        @if (Session::has('success'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.success("{{ session('success') }}");
    @endif
</script>
@stack('scripts')

</body>

</html>
