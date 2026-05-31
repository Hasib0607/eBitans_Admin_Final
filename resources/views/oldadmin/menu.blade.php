@extends('admin.layouts.main')
@section('content')
    <style>
        nav {

        }

        .breadcrumb {
            display: flex;
            flex-wrap: wrap !important;
        }

        ul {
            list-style: none;

        }

        .breadcrumb-item {
            margin: 10px;
            width: 40% !important;
            border: 1px solid #f1593a;
            background-color: #f1593a;
            border-radius: 5px;
        }

        .material-icons1 {
            font-size: 41px;
        }

        .breadcrumb-item img {
            width: 50px;
            padding: 11px;
            background-color: transparent;
            border-radius: 7px;
        }
    </style>
    </style>
    <?php
    if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
        $customer = DB::table('customers')->where('uid', Auth::user()->id)->first();
        $store_id = $customer->active_store;
    } elseif (Auth::user()->type == 'staff') {
        $staff = DB::table('staff')->where('uid', Auth::user()->id)->first();
        $store_id = $staff->store_id;
        $role = DB::table("roles")->where('id', $staff->role_id)->first();
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
        $store = DB::table('stores')->where('id', $store_id)->first();
        if ($store->expiry_date <= Carbon\Carbon::now()) {
            $exp = 1;
        } else {
            $exp = 0;
        }
    }
    ?>
    @if(Auth::user()->type=='staff')
            <?php
            $stafff = DB::table('staff')->where('uid', Auth::user()->id)->first();
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
    <?php
    if (Auth::user()->type == 'superstaff') {
        $superstaff = DB::table('superstaffs')->where('uid', Auth::user()->id)->first();
        $superrole = DB::table('superroles')->where('id', $superstaff->role_id)->first();
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
            } elseif ($prs == 'notification') {
                $notification = 1;
            } elseif ($prs == 'message') {
                $messages = 1;
            } else {

            }
        }
    }
    ?>
    <main class="main-content position-relative border-radius-lg ">
        <div class="container-fluid py-4">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a class="nav-link text-white " href="/">
                            <div class="text-white text-center d-flex align-items-center justify-content-center">
                                <i class="material-icons material-icons1 opacity-10">dashboard</i>
                            </div>
                            <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                    ড্যাশবোর্ড
                                @else
                                    Dashboard
                                @endif</span>
                        </a>
                    </li>
                    @if(Auth::user()->type=="superadmin" || Auth::user()->type=="superadminstaff")
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white "
                               href="@if(isset($exp)) @if($exp=='1') # @else {{URL::to('/')}}/pos @endif @endif"
                               target="_blank">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">table_view</i>
                                </div>
                                <span class="nav-link-text">POS</span>
                            </a>
                        </li>
                    @endif
                    @if(isset($branch) && $branch=='1' || isset($staff_pos) && $staff_pos=='1' || Auth::user()->type=="admin")
                        @if(isset($store))
                                <?php
                                $plan = DB::table('plans')->where('id', $store->plan_id)->first();
                                ?>
                            @if($plan->branch >= 1)
                                <li class="breadcrumb-item">
                                    <a class="nav-link text-white "
                                       href="@if(isset($exp)) @if($exp=='1') # @else {{URL::to('/')}}/branch @endif @endif">
                                        <div
                                            class="text-white text-center d-flex align-items-center justify-content-center">
                                            <img src="https://img.icons8.com/ios-glyphs/20/ffffff/shop.png"/>
                                        </div>
                                        <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                স্টোর ব্রাঞ্চ
                                            @else
                                                Store Branch
                                            @endif</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endif
                    @if(Auth::user()->type=="superadmin" || Auth::user()->type=='superstaff')
                        @if(isset($branch_delete_request) && $branch_delete_request=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white " href="{{URL::to('/')}}/branchdel">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Branch Delete Request</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($customers) && $customers=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white " href="{{URL::to('/')}}/superadmin/customer">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Customer</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($domain) && $domain=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{URL::to('/')}}/domain/list">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Domain</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($domain_request) && $domain_request=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{URL::to('/')}}/domain/request">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Domain Request</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($design) && $design=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{URL::to('/')}}/design/list">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Design</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($templatess) && $templatess=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white " href="{{route('superadmin.template')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Template</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    @if(isset($product) && $product=='1' || isset($category) && $category=='1' || isset($subcategory) && $subcategory=='1' || isset($brand) && $brand=='1' || isset($attribute) && $attribute=='1' || isset($supplier) && $supplier=='1' || isset($collection) && $collection=='1' || isset($global_tab) && $global_tab=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href="@if(isset($exp)) @if($exp=='1') # @else {{URL::to('/')}}/products @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/material/20/ffffff/shipping-product.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        প্রোডাক্টস
                                    @else
                                        Products
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(isset($coupon) && $coupon=='1' || isset($campaign) && $campaign=='1' || isset($offer) && $offer=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href="@if(isset($exp)) @if($exp=='1') # @else {{route('admin.promotion.coupon')}} @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img
                                        src="https://img.icons8.com/external-flatart-icons-solid-flatarticons/22/ffffff/external-offer-shopping-and-commerce-flatart-icons-solid-flatarticons.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        অফার/ প্রোমোশন
                                    @else
                                        Offer/ Promotion
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(isset($inventory) && $inventory=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href=" @if(isset($exp)) @if($exp=='1') # @else {{route('admin.inventory')}} @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/glyph-neue/20/ffffff/warehouse-1.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        মালগুদাম
                                    @else
                                        Inventory
                                    @endif</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper' || Auth::user()->type=='staff')
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href="@if(isset($exp)) @if($exp=='1') # @else {{route('admin.order')}} @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/ios-glyphs/20/ffffff/shopping-basket-success.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        অর্ডার
                                    @else
                                        Orders
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->type=='superadmin')
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white " href="{{URL::to('/')}}/superadmin/popupimage">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">table_view</i>
                                </div>
                                <span class="nav-link-text">Popup Image</span>
                            </a>
                        </li>
                    @endif

                    @if(isset($invoice) && $invoice=='1' || Auth::user()->type=="admin")

                    @endif


                    @if(isset($slider) && $slider=='1' || isset($banner) && $banner=='1' || isset($layouts) && $layouts=='1' || isset($template) && $template=='1' || isset($header) && $header=='1' || isset($homepage) && $homepage=='1' || isset($footer) && $footer=='1' || isset($mobilemenu) && $mobilemenu=='1' || isset($product_display) && $product_display=='1' || isset($product_grid) && $product_grid=='1' || isset($shop_page) && $shop_page=='1' || isset($tt) && $tt=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white "
                               href=" @if(isset($exp)) @if($exp=='1') # @else {{URL::to('/')}}/design/theme @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img
                                        src="https://img.icons8.com/ios-filled/20/ffffff/windows10-personalization.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        ডিজাইন
                                    @else
                                        Design
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(isset($pages) && $pages=='1' || Auth::user()->type=="admin")

                    @endif

                    @if(isset($theme_customize) && $theme_customize=='1' || isset($activity_log) && $activity_log=='1' ||Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')

                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href=" @if(isset($exp)) @if($exp=='1') # @else {{route('admin.themecustomize')}} @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img
                                        src="https://img.icons8.com/ios-filled/20/ffffff/camera-addon-identification.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        অ্যাডন
                                    @else
                                        Addons
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->type=="admin")
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white" href="{{route('payment.payments')}}">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/ios-filled/20/ffffff/card-security.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        পেমেন্ট
                                    @else
                                        Payment
                                    @endif</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper' || Auth::user()->type=='staff')
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href="@if(isset($exp)) @if($exp=='1') # @else {{route('admin.report')}} @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img
                                        src="https://img.icons8.com/external-smashingstocks-glyph-smashing-stocks/20/ffffff/external-report-testimonials-and-feedback-smashingstocks-glyph-smashing-stocks.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        রিপোর্ট
                                    @else
                                        Reports
                                    @endif</span>
                            </a>
                        </li>
                    @endif

                    @if(isset($customer) && $customer=='1' || Auth::user()->type=="admin")
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href="@if(isset($exp)) @if($exp=='1') # @else {{route('admin.customer')}} @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/ios-filled/20/ffffff/customer-insight.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        কাস্টমার
                                    @else
                                        Customers
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(isset($staff) && $staff=='1' || Auth::user()->type=="admin")
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href="@if(isset($exp)) @if($exp=='1') # @else {{route('admin.staff')}} @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/ios-filled/20/ffffff/employee-card.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        স্টাফ
                                    @else
                                        Employee
                                    @endif</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper' || Auth::user()->type=='staff')

                    @endif
                    @if(Auth::user()->type=="superadmin" || Auth::user()->type=='superstaff')
                        @if(Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{route('superadmin.planorderrequest')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Payment Request</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{route('superadmin.productrecycle')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Recycle Bin</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{route('superadmin.mobilapps')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Addons</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($staff) && $staff=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{route('superadmin.staff')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Staff</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($role_and_permission) && $role_and_permission=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{route('superadmin.role.permission')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">person</i>
                                    </div>
                                    <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            রোল এবং পারমিশন
                                        @else
                                            Role & Permission
                                        @endif</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($clients) && $clients=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{route('admin.clients')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Clients</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($plan_order) && $plan_order=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white " href="{{route('admin.planorder')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">table_view</i>
                                    </div>
                                    <span class="nav-link-text">Plan Order</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($plans) && $plans=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{route('plans')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">person</i>
                                    </div>
                                    <span class="nav-link-text ">Plans</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($notification) && $notification=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{route('notification')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">person</i>
                                    </div>
                                    <span class="nav-link-text">Notification</span>
                                </a>
                            </li>
                        @endif
                        @if(isset($messages) && $messages=='1' || Auth::user()->type=='superadmin')
                            <li class="breadcrumb-item">
                                <a class="nav-link text-white" href="{{route('messages')}}">
                                    <div
                                        class="text-white text-center d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10">person</i>
                                    </div>
                                    <span class="nav-link-text">Messages</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if(Auth::user()->type=="admin")
                            <?php
                            $act = DB::table('activities')->where('store_id', $store_id)->whereDate('expiry_date', '>=', Carbon\Carbon::now())->first();
                            ?>
                        @if(isset($act))
                        @endif
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href="@if(isset($exp)) @if($exp=='1') # @else {{route('admin.setting')}} @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <img
                                        src="https://img.icons8.com/external-kiranshastry-solid-kiranshastry/20/ffffff/external-settings-coding-kiranshastry-solid-kiranshastry-1.png"/>
                                </div>
                                <span class="nav-link-text">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        সেটিংস
                                    @else
                                        Settings
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->type=="staff")
                        <li class="breadcrumb-item">
                            <a class="nav-link text-white"
                               href="@if(isset($exp)) @if($exp=='1') # @else {{route('admin.staff.profile')}} @endif @endif">
                                <div class="text-white text-center d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">person</i>
                                </div>
                                <span class="nav-link-text">Profile</span>
                            </a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </main>
@endsection
@section('js')
@endsection

