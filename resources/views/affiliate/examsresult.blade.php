<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('fav-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('fav-icon.png') }}">
    <title>
        @yield('title') eBitans
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
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.9/sweetalert2.min.css">
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

        #mydiv {
            /* position: absolute; */
            position: fixed;
            resize: both;
            width: 560px;
            z-index: 99999999999999;
            bottom: 6.7%;
            right: 1.6%;
        }

        #mydivheader {
            cursor: move;
            resize: both;
            width: 560px;
            z-index: 10;
            background-color: #f1593a;
            color: #fff;
        }

        #mydiv:hover #hidebutton {
            display: block !important;
        }

        #mydiv #hidebutton {
            position: absolute;
            top: 2px;
            font-size: 15px;
            right: 2px;
            padding: 0px 7px;
            background-color: rgba(0, 0, 0, .8);
            border-radius: 100%;
            cursor: pointer;
            display: none;
        }

        #mydiv #menubutton {
            position: absolute;
            top: 2px;
            font-size: 15px;
            right: 2px;
            padding: 0px 7px;
            background-color: rgba(0, 0, 0, .8);
            border-radius: 100%;
            cursor: pointer;
            display: none;
        }

        #serchWab {
            width: 100%;
            padding-left: 15px;
        }

        @media screen and (max-width: 575px) {
            #mobilesearchdiv {
                display: flex !important;
            }

            #serchWab {
                display: none !important;
            }

        }

        @media screen and (max-width: 640px) {
            #mydiv {
                width: 70%;
                bottom: 9.7%;
            }

            #mydivheader {
                width: 100%;
                height: 185px;
            }

            figure #youTubeUrl {
                min-height: 112px !important;
                max-height: 150px !important;
            }

            #mydiv #hidebutton {
                display: block !important;
            }

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

    <style>
        .imageUploadLoading {
            align-items: center;
            background: #ffffffb0;
            display: flex;
            height: 100vh;
            justify-content: center;
            left: 0;
            position: fixed;
            top: 0;
            transition: opacity 0.2s linear;
            width: 100%;
            z-index: 9999;
            opacity: 1;
            transform: opacity 1s linear;
            display: none;
        }
    </style>

</head>

<body class="g-sidenav-show  bg-gray-200" id="bodyss">

@php
    _getUserUsingInfo(auth()->user());
@endphp

<div class="preloader">
    <div class="frame12">
        <div class="center">
            <div class="dot-1"></div>
            <div class="dot-2"></div>
            <div class="dot-3"></div>
        </div>
    </div>
</div>

<div class="imageUploadLoading" id="imageUploadLoading">
    <div class="cssload-wrap">
        <div class="cssload-container">
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
        </div>
    </div>
</div>
@php
    $reports = 0;
    $analytics = 0;
    $user = Auth::user()->id;
    $user_type = Auth::user()->type;
    if ($user_type == 'admin' || $user_type == 'dropshipper') {
        $customer = DB::table('customers')->where('uid', $user)->first();
        $store_id = $customer->active_store;
        $store = DB::table('stores')->where('id', $store_id)->first();
        $store_name = $store->name;
        $store_url = $store->url;
        $use = DB::table('toptools')
            ->where('uid', $user)
            ->where('store_id', $store_id)
            ->orderBy('count', 'DESC')
            ->get();
    } elseif ($user_type == 'staff') {
        $staff = DB::table('staff')->where('uid', $user)->first();
        $store_id = $staff->store_id;
        $store = DB::table('stores')->where('id', $store_id)->first();
        $store_name = $store->name;
        $store_url = $store->url;
        $use = DB::table('toptools')
            ->where('uid', $user)
            ->where('store_id', $store_id)
            ->orderBy('count', 'DESC')
            ->get();
    } else {
        $use = DB::table('toptools')->where('uid', $user)->orderBy('count', 'DESC')->get();
        $store_name = Auth::user()->name;
        $store_url = Auth::user()->email;
    }
@endphp
<div class="modal123" id="exampleModal123" style="display:none">
    <div class="modalshow modal-dialog modal-lg" id="modalshow">
        <?php $tokens = DB::table('trickets')->where('seen', null)->get(); ?>
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
                        <?php $orders = DB::table('planorders')->where('view', '0')->orderBy('id', 'DESC')->get(); ?>
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
                                            @php
                                                $key++;
                                            @endphp
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

                    @php
                        $newOrders = DB::table('addons_orders')->where('view', '0')->orderBy('id', 'DESC')->get();
                    @endphp

                    @if (isset($newOrders) && count($newOrders) > 0)
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
                                @if (isset($newOrders) && count($newOrders) > 0)
                                    @foreach ($newOrders as $key => $order)
                                        <tr>
                                            <td> {{ $key++ }} </td>
                                                <?php

                                                $user = DB::table('users')
                                                    ->where('id', $order->user_id)
                                                    ->first();
                                                ?>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $order->total }}</td>
                                            <td>{{ $order->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @php
                        $domains = DB::table('domains')->where('view', 0)->orderBy('id', 'DESC')->get();
                    @endphp

                    @if (isset($domains) && count($domains) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer Name</th>
                                    <th>Domain</th>
                                    <th>Create Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($domains) && count($domains) > 0)
                                    @foreach ($domains as $key => $domain)
                                        <tr>
                                            <td> {{ $key++ }} </td>
                                            @php
                                                $user = DB::table('users')
                                                    ->where('id', $domain->uid)
                                                    ->first();
                                            @endphp
                                            <td>{{ $user->name ?? 'Empty' }}</td>
                                            <td>{{ $domain->name ?? 'Empty' }}</td>
                                            <td>{{ $domain->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @php
                        $messages = DB::table('messages')->where('view', 0)->orderBy('id', 'DESC')->get();
                    @endphp

                    @if (isset($messages) && count($messages) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer Name</th>
                                    <th>NameS</th>
                                    <th>Create Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (isset($messages) && count($messages) > 0)
                                    @foreach ($messages as $key => $message)
                                        <tr>
                                            <td> {{ $key++ }} </td>
                                            @php
                                                $user = DB::table('users')
                                                    ->where('id', $message->uid)
                                                    ->first();
                                            @endphp
                                            <td>{{ $user->name ?? 'Empty' }}</td>
                                            <td>{{ $message->name ?? 'Empty' }}</td>
                                            <td>{{ $message->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @php
                        $customers = DB::table('customers')->where('seen', null)->get();
                    @endphp

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
                                            <td>{{ $key++ }}</td>
                                            <td>{{ $orders->name ?? 'Empty' }}</td>
                                            <td>{{ $orders->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @php
                        $invoices = DB::table('invoicepurchases')->where('seen', null)->get();
                    @endphp

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
                                            @php
                                                $key++;
                                            @endphp
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
                    @php
                        $themecusrt = DB::table('themecustomizes')->where('seen', null)->get();
                    @endphp
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
                                            @php
                                                $key++;
                                            @endphp
                                            <td>{{ $key }}</td>
                                            @php
                                                $ths = DB::table('templates')
                                                    ->where('id', $themecusrts->theme)
                                                    ->first();
                                            @endphp
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
@php
    if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
@endphp
@if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
    <div class="modal1234" id="exampleModal1234" style="display:none">
        <div class="modalshow1 modal-dialog modal-lg" id="modalshow1">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
                </div>
                <div class="modal-body">
                    @php
                        $orders = DB::table('orders')
                            ->where('store_id', $store_id)
                            ->where('view', null)
                            ->orderBy('id', 'DESC')
                            ->get();
                    @endphp
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
                                            @php
                                                $key++;
                                            @endphp
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
                    <a href="{{ route('admin.view.notification') }}" class="btn btn-primary">View
                        Notification</a>
                </div>
            </div>
        </div>
    </div>
@endif

<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header sticky" style="z-index:9999999999999">

        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
           aria-hidden="true" id="iconSidenav"></i>
        @if (Auth::user()->type == 'staff' || Auth::user()->type == 'superstaff')
            <a class="navbar-brand m-0" href="{{ route('affiliate.result') }}">
                <img src="{{ asset('logo-white.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            </a>
        @else
            <a class="navbar-brand m-0" href="{{ route('affiliate.result') }}">
                <img src="{{ asset('logo-white.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            </a>
        @endif

    </div>
    <hr class="horizontal light mt-0 mb-2">
    <!--  -->
    <style>
        .navbar-vertical.navbar-expand-xs .navbar-collapse {
            height: calc(100vh - 1px);
        }
    </style>
    @php
        if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
                    } elseif ($pr == 'pse') {
                        $pse = 1;
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
                    } elseif ($pr == 'orders') {
                        $orders = 1;
                    } elseif ($pr == 'reports') {
                        $reports = 1;
                    } elseif ($pr == 'analytics') {
                        $analytics = 1;
                    } else {
                    }
                }
            }
        } else {
            $store_id = 0;
        }
        if ($store_id != 0) {
            $store = DB::table('stores')->where('id', $store_id)->first();
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
                if (
                    isset($store->digital_plan_id) &&
                    Carbon\Carbon::parse($store->digital_plan_end_date) >= Carbon\Carbon::now()
                ) {
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
        if (
            isset($store->digital_plan_id) &&
            Carbon\Carbon::parse($store->digital_plan_end_date) >= Carbon\Carbon::now()
        ) {
            $digitalplan = 1;
            $dexp = 0;
        } else {
            $digitalplan = null;
            $dexp = 1;
        }
    @endphp

    @if (Auth::user()->type == 'staff')
        @php
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
        @endphp
    @endif
    @php
        if (Auth::user()->type == 'superstaff') {
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
                    $clientsPer = 1;
                } elseif ($prs == 'clients_Activities') {
                    $clients_ActivitiesPer = 1;
                } elseif ($prs == 'clients_Follow_Up') {
                    $clients_Follow_UpPer = 1;
                } elseif ($prs == 'plan_order') {
                    $plan_order = 1;
                } elseif ($prs == 'plans') {
                    $plans = 1;
                } elseif ($prs == 'blog') {
                    $blog = 1;
                } elseif ($prs == 'webSetup') {
                    $webSetup = 1;
                } elseif ($prs == 'notification') {
                    $notification = 1;
                } elseif ($prs == 'message') {
                    $messages = 1;
                } elseif ($prs == 'smm') {
                    $smm = 1;
                } elseif ($prs == 'wSetUp') {
                    $wSetUp = 1;
                } elseif ($prs == 'returned_product') {
                    $returned_product = 1;
                } elseif ($prs == 'invoice') {
                    $invoice = 1;
                }
            }
        }
    @endphp

    <div class="collapse  navbar-collapse w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if (Auth::user()->type == 'affiliate')
                <li class="nav-item">
                    <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'dashboard') active bg-gradient-primary @endif @endif "
                       href="{{ route('affiliate.result') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1" id="ffd">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                ড্যাশবোর্ড
                            @else
                                Dashboard
                            @endif
                            </span>
                    </a>
                </li>
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


<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" id="main"
      style="min-height:100vh">

    <!-- Navbar -->
    <!-- <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl mb-5" id="navbarBlur" navbar-scroll="true">

      <div class="w-100 rounded d-flex align-items-center" style="background-color: #FBD0C8; height: 70px" >
          <div class="row w-100">
            <div class="col-lg-3 text-left ps-5">
              <h4>dashboard</h4>
            </div>
            <div class="col-lg-5 text-center">
              <div class="row w-100">

                <div class="col-lg-6 text-left ps-5">
                  <span>Calender</span>
                </div>
                <div class="col-lg-6 text-left ps-5">
                  <span>Search</span>
                </div>

              </div>
            </div>
            <div class="col-lg-4 text-center">
              <h4>dashboard</h4>
            </div>
          </div>
      </div>

    </nav> -->

    <div class="row justify-content-center mt-5" style="margin-top: 200px !important">

        <div class="rounded border d-flex justify-content-center align-items-center col-lg-2"
             style="background-color: #F6EBE8; border-color: #FBD0C7 !important; width: 264px; height: 262px; display: inline-block">
                <span class="d-flex justify-content-center align-items-center" style=" width: 189px; height: 218px">
                    <span class="">
                        <h1 class="text-center m-0 p-0"
                            style="color: #442721 !important; font-size: 152px !important"><span id="hour"></span></h1>
                        <p class="text-center m-0 p-0 m-auto d-flex justify-content-center align-items-center"
                           style="font-size: 28px !important; color: #F1593A !important; width: 144px; height: 34px">HOURS</p>
                    </span>
                </span>
        </div>

        <div class="rounded border d-flex justify-content-center align-items-center col-lg-2 mx-3"
             style="background-color: #F6EBE8; border-color: #FBD0C7 !important; width: 264px; height: 262px; display: inline-block">
                <span class="d-flex justify-content-center align-items-center" style=" width: 189px; height: 218px">
                    <span class="">
                        <h1 class="text-center m-0 p-0"
                            style="color: #442721 !important; font-size: 152px !important"><span
                                id="minute"></span></h1>
                        <p class="text-center m-0 p-0 m-auto d-flex justify-content-center align-items-center"
                           style="font-size: 28px !important; color: #F1593A !important; width: 144px; height: 34px">MINUTES</p>
                    </span>
                </span>
        </div>

        <div class="rounded border d-flex justify-content-center align-items-center col-lg-2"
             style="background-color: #F6EBE8; border-color: #FBD0C7 !important; width: 264px; height: 262px; display: inline-block">
                <span class="d-flex justify-content-center align-items-center" style=" width: 189px; height: 218px">
                    <span class="">
                        <h1 class="text-center m-0 p-0"
                            style="color: #442721 !important; font-size: 152px !important"><span
                                id="second"></span></h1>
                        <p class="text-center m-0 p-0 m-auto d-flex justify-content-center align-items-center"
                           style="font-size: 28px !important; color: #F1593A !important; width: 144px; height: 34px">SECONDS</p>
                    </span>
                </span>
        </div>

    </div>

    <div class="text-center my-3 py-3">
        <p class="text-center py-2">
            Best wishes on your ebitans affiliation exam results. We appreciate your participation.Will publish the
            result within 24 hours.
        </p>
    </div>


    <!-- End Navbar -->
    {!! Toastr::message() !!}
    @yield('content')
    <!----Loader--->

    <!-----End Loader---->
    <footer class="footer py-4" style="margin-top: 300px !important">
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
                        <a href="https://www.ebitans.com" class="font-weight-bold" target="_blank">eBitans</a>
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
            <i class='far fa-comment' style='font-size:20px;color:#fff' class="py-3"></i>
        </a>
        <a class="fixed-plugin-button3 text-dark position-fixed px-3 py-2"
           style="right:90px;background-color:#f1593a !important">
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
                <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white"
                        onclick="sidebarType(this)">White
                </button>
            </div>
            <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
            <!-- Navbar Fixed -->
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
@php
    $messagess = DB::table('messages')
        ->where('uid', Auth::user()->id)
        ->where('store_id', $store_id)
        ->get();
@endphp
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
                <img src="https://img.icons8.com/ios-glyphs/25/000000/menu--v1.png"/>
            </div>
            <p style="font-size:12px">Menu</p>
        </a>
        <a href='javascript:;' class='els-wrap el-2' id="mobilesearch1">
            <div class='icon'>
                <img src="https://img.icons8.com/ios/25/000000/search--v1.png"/>
            </div>
            <p style="font-size:12px">Search</p>
        </a>
        <a href='@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/products/create @endif @endif'
           class='els-wrap1' style="background-color: #f1593a;margin-bottom: 57px;margin-left:4px;">
            <div class='icon' style="margin-top: 5px;margin-left: 5px;height:2em">
                <img src="https://img.icons8.com/android/27/ffffff/plus.png"/>
            </div>
        </a>
        <a href='@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/order @endif @endif'
           class='els-wrap el-3'>
            <div class='icon' style="margin-left: 3px;">
                <img src="https://img.icons8.com/ios-glyphs/25/000000/shopping-basket-success.png"/>
            </div>
            <p style="font-size:12px">Order</p>
        </a>
        <a href='@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/settings @endif @endif'
           class='els-wrap el-4'>
            <div class='icon' style="margin-left: 6px;">
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

<div id="mydiv" class="div" style="display:none">
    <div id="mydivheader">
        <div id="menubutton" style="left: 1px; display: block;background-color:unset;"><strong>Play
                Tutorial</strong></div>
        <div id="hidebutton"> X</div>
        <figure style="padding:30px 10px 0px 10px;">
            <iframe id="youTubeUrl" style="width:100%;min-height:300px" src=""
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </figure>
    </div>
</div>

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
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/9.10.0/firebase-app.js";
    import {
        getAnalytics
    } from "https://www.gstatic.com/firebasejs/9.10.0/firebase-analytics.js";
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

        load_home();

        async function load_home() {
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

            const element = document.getElementById('message');
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
<script src="https://www.youtube.com/iframe_api"></script>


<script>
    const dasht = localStorage.getItem("dashtutorial");

    if (dasht === 'done') {
        var url = $('#shh').val();
        $('#youTubeUrl').attr('src', url);
        $("#mydiv").hide();
    } else {
        var url = $('#shh').val();
        $('#youTubeUrl').attr('src', url);
        $("#mydiv").show();
    }

    $("#shh").on('click', function () {
        var url = $('#shh').val();
        $('#youTubeUrl').attr('src', url);
        $("#mydiv").toggle();
    })
    $('#hidebutton').on('click', function () {
        $('#youTubeUrl').attr('src', url);
        $("#mydiv").toggle();
        localStorage.setItem("dashtutorial", "done");
    })
    dragElement(document.getElementById("mydiv"));

    function dragElement(elmnt) {
        var pos1 = 0,
            pos2 = 0,
            pos3 = 0,
            pos4 = 0;
        if (document.getElementById(elmnt.id + "header")) {
            /* if present, the header is where you move the DIV from:*/
            document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
        } else {
            /* otherwise, move the DIV from anywhere inside the DIV:*/
            elmnt.onmousedown = dragMouseDown;
        }

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }
</script>

<script>
    // Check if SmsAlert is present in the URL
    let smsAlert = '{{ request()->query('SmsAlert') }}';
    if (smsAlert !== '') {
        Swal.fire({
            title: smsAlert,
            icon: 'question',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Buy Now',
            cancelButtonText: 'Cancel',
            focusConfirm: false,
            preConfirm: () => {
                var paymentRoute = "{{ route('payment.payments') }}";
                window.open(paymentRoute, "_blank");
            }
        });
    }
</script>

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

<script>
    // Get the PHP variable value
    var examSubmittedAt = new Date("{{ $exam_submitted_at }}");

    // Function to update the timer
    function updateTimer() {
        var now = new Date();
        var timeDiff = (examSubmittedAt.getTime() + (24 * 60 * 60 * 1000) - now.getTime()) / 1000; // Time difference in seconds

        // If time is up, display a message
        if (timeDiff <= 0) {
            document.getElementById("hour").innerHTML = '00';
            document.getElementById("minute").innerHTML = '00';
            document.getElementById("second").innerHTML = '00';
            return;
        }

        // Calculate hours, minutes, and seconds
        var hours = Math.floor(timeDiff / 3600);
        var minutes = Math.floor((timeDiff % 3600) / 60);
        var seconds = Math.floor(timeDiff % 60);

        // Add leading zeros if necessary
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        // Display the timer
        document.getElementById("hour").innerHTML = hours;
        document.getElementById("minute").innerHTML = minutes;
        document.getElementById("second").innerHTML = seconds;
    }

    // Update the timer every second
    setInterval(updateTimer, 1000);

    // Initial update
    updateTimer();
</script>


@stack('scripts')
</body>

</html>
