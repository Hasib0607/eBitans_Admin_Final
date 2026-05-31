@extends('admin.layouts.main')
{{--
ORDER LIST (admin.order.index)
- Desktop + Mobile views
- Multi-select actions: change status, courier, assign staff, export CSV, print invoices
- Search + filters
--}}

@push('styles')
    <style>
        .drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        .drawer {
            position: fixed;
            top: 0;
            right: -100%;
            max-width: 80%;
            height: 100%;
            background: #fff;
            z-index: 1050;
            transition: right 0.3s ease-in-out;
            overflow-y: auto;
        }

        .drawer.open {
            right: 0;
        }

        .circle-chart {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            line-height: 50px;
            text-align: center;
            color: #fff;
            font-weight: bold;
            margin: auto;
        }

        .circle-green {
            background: conic-gradient(#28a745 calc(var(--value) * 1%), #e9ecef 0%);
        }

        .circle-red {
            background: conic-gradient(#dc3545 calc(var(--value) * 1%), #e9ecef 0%);
        }

        .table tbody tr:last-child td {
            border-width: thin;
        }

        .responsive-courier-table {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
        }

        .responsive-courier-table .table {
            min-width: 600px;
            border-collapse: collapse;
        }

        .responsive-courier-table th,
        .responsive-courier-table td {
            vertical-align: middle;
            text-align: center;
        }

        /* Fix bottom border */
        .responsive-courier-table .table-bordered td,
        .responsive-courier-table .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .circle-chart {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: conic-gradient(var(--color, #28a745) calc(var(--value, 0) * 1%), #e9ecef 0%);
            text-align: center;
            line-height: 40px;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            transition: background 0.3s ease;
        }

        .circle-green {
            --color: #28a745;
        }

        .circle-red {
            --color: #dc3545;
        }

        @media (max-width: 576px) {
            .circle-chart {
                width: 32px;
                height: 32px;
                line-height: 32px;
                font-size: 10px;
            }
        }

        @media (min-width: 776px) {
            .drawer {
                min-width: 670px;
            }
        }

        .circle-container {
            position: relative;
            width: 50px;
            height: 50px;
            margin: auto;
        }

        .circle-container svg {
            width: 100%;
            height: 100%;
            transform: rotate(0deg);
        }

        .circle-bg {
            fill: none;
            stroke: #e9ecef;
            stroke-width: 3.8;
        }

        .circle {
            fill: none;
            stroke-width: 3.8;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.5s ease;
        }

        .circle-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: bold;
        }

        .alert-danger {
            color: #ffffff;
        }
    </style>
@endpush

<?php
/**
 * getUserData() likely returns:
 *  - user
 *  - customer
 *  - store_id
 * etc.
 */
$userData = getUserData();
$store_id = $userData['store_id'];
?>

@section('content')
    <main class="main-content position-relative border-radius-lg">
        {{-- Shared top navigation for order module --}}
        @include('admin.order.share.order-nav')

        <!-- Overlay (drawer background dim) -->
        <div class="drawer-overlay" id="drawerOverlay"></div>

        <!-- Drawer (Fraud/Courier check drawer) -->
        <div class="drawer" id="drawer">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Courier Fraud Check - <span id="checkPhone"></span></h5>
                <button id="closeDrawerBtn" class="btn btn-sm btn-danger">&times;</button>
            </div>
            <div class="p-3" id="drawerContent">
                <p class="text-muted">Loading...</p>
            </div>
        </div>

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6" style="display: flex; gap: 20px">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            সমস্ত অর্ডার
                        @else
                            All Orders
                        @endif
                    </h4>

                    {{-- Assign selected orders to staff (uses hidden #staffOrder) --}}
                    <button class="btn bg-orange-600 btn-danger mx-1px text-95" data-bs-toggle="modal"
                        data-bs-target="#staff" data-title="Select Staff">
                        Select Staff
                    </button>
                </div>

                {{-- Staff assign modal --}}
                <div class="modal fade" id="staff" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content" style="background-color:transparent;border:0px">
                            <div class="modal-body" style="border:none">
                                <button class="btn btn-danger sm" data-bs-dismiss="modal"
                                    style="float: right; margin: 0px 8px;">X
                                </button>

                                <div class="row mt-1">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <form method="POST" action="{{ route('admin.assign.staff.order') }}">
                                                    @csrf
                                                    {{-- Filled by JS with selected order IDs --}}
                                                    <input type="hidden" id="staffOrder" name="order_ids" value="">

                                                    @php
                                                        // Staff list for current store
                                                        $staffs = \App\Models\Staff::where('store_id', $store_id)->get();
                                                    @endphp

                                                    <select class="form-select" name="staff_id" id="staff_id">
                                                        <option value="">Select Staff</option>
                                                        @foreach($staffs as $item)
                                                            <option value="{{ $item->uid }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <button class="btn bg-orange-600 btn-danger mx-1px text-95"
                                                        style="margin-top: 25px;margin-bottom: 0;">
                                                        Save
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top-right actions (Excel + Print) --}}
                <div class="col-md-6">
                    <ul class="d-flex justify-content-end gap-2" style="list-style:none; margin:0; padding:0;">
                        <li style="padding:0;border:0;">
                            <a data-href="/orderexport" onclick="exportOrder(this)" class="btn btn-secondary btn-sm px-3"
                                style="border-radius: 4px;">Excel</a>
                        </li>

                        <li style="padding:0;border:0;">
                            <a href="{{ route('admin.invoice.printSelected') }}"
                                onclick="return printSelectedInvoices(event, this);" class="btn btn-primary btn-sm px-3
                                                        class=" btn btn-secondary btn-sm px-3" style="border-radius: 4px;">
                                Print
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- Filter row --}}
                            <div class="d-flex flex-nowrap gap-3 overflow-auto pb-1" style="align-items: stretch;">

                                {{-- Search --}}
                                <div style="min-width: 360px; display:flex; align-items:center;">
                                    <form action="{{ route('admin.order') }}" method="GET"
                                        class="d-flex align-items-center gap-1 mb-0 w-100">
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Order No or Mobile Number..." class="form-control"
                                            style="width: 260px; height: 38px; margin-top:-15px; font-size: 13px;">

                                        <button type="submit" class="btn"
                                            style="height: 38px; min-width: 96px; font-size: 12px; font-weight: 600; background:#f1593a; color:#fff; border-radius:6px; padding:0 18px; display:flex; align-items:center; justify-content:center;">
                                            SEARCH
                                        </button>
                                    </form>
                                </div>

                                <?php
    $user = $userData['user'];
    $customer = $userData['customer'];

    $digitalproductmodules = DB::table('moduluses')
        ->where('id', '=', 110)->where('status', '=', '1')->first();

    $advancepaymentmodules = DB::table('moduluses')
        ->where('id', '=', 106)->where('status', '=', '1')->first();

    $bookingsystemmodules = DB::table('moduluses')
        ->where('id', '=', 108)->where('status', '=', '1')->first();

    if ($digitalproductmodules) {
        $digitalproductstatus = DB::table('buy_moduluses')
            ->where('modulus_id', '=', $digitalproductmodules->id)
            ->where('store_id', '=', $customer->active_store)
            ->where('status', '=', '1')
            ->first();
    } else {
        $digitalproductstatus = null;
    }

    if ($advancepaymentmodules) {
        $advancepaymentstatus = DB::table('buy_moduluses')
            ->where('modulus_id', '=', $advancepaymentmodules->id)
            ->where('store_id', '=', $customer->active_store)
            ->where('status', '=', '1')
            ->first();
    } else {
        $advancepaymentstatus = null;
    }

    if ($bookingsystemmodules) {
        $bookingsystemstatus = DB::table('buy_moduluses')
            ->where('modulus_id', '=', $bookingsystemmodules->id)
            ->where('store_id', '=', $customer->active_store)
            ->where('status', '=', '1')
            ->first();
    } else {
        $bookingsystemstatus = null;
    }
                            ?>

                                @php
                                    $statuses = \App\Models\OrderStatus::getOrderStatus();
                                @endphp

                                {{-- Bulk change status --}}
                                <div style="min-width: 230px;">
                                    <form action="{{ route('admin.order.changestatus') }}" id="changeStatusSubmit"
                                        method="post" class="mb-0">
                                        @csrf
                                        <input type="hidden" name="text2" id="selectids">

                                        <select class="form-select" name="type" onchange="changeStatus(this.value)"
                                            style="height: 38px;">
                                            <option value="all" @if (isset($type) && $type == 'all') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    অর্ডার স্থিতি পরিবর্তন করুন
                                                @else
                                                    Change Order Status
                                                @endif
                                            </option>

                                            @if(count($statuses))
                                                @foreach($statuses as $item)
                                                    @if($item->slug == "Payment Success")
                                                        @if ($digitalproductstatus || $advancepaymentstatus)
                                                            <option value="{{ $item->slug }}" @if (isset($type) && $type == $item->slug)
                                                            selected @endif>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    {{ $item->name_bn }}
                                                                @else
                                                                    {{ $item->name }}
                                                                @endif
                                                            </option>
                                                        @endif
                                                    @elseif($item->slug == "Booked")
                                                        @if($bookingsystemstatus)
                                                            <option value="{{ $item->slug }}" @if (isset($type) && $type == $item->slug)
                                                            selected @endif>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    {{ $item->name_bn }}
                                                                @else
                                                                    {{ $item->name }}
                                                                @endif
                                                            </option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $item->slug }}" @if (isset($type) && $type == $item->slug)
                                                        selected @endif>
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                {{ $item->name_bn }}
                                                            @else
                                                                {{ $item->name }}
                                                            @endif
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </form>
                                </div>

                                {{-- Date filter --}}
                                <div style="min-width: 200px;">
                                    <form action="#" class="mb-0">
                                        @csrf
                                        <input type="date" name="date" class="form-control" onchange="this.form.submit()"
                                            style="height: 38px;">
                                    </form>
                                </div>

                                {{-- Order type filter --}}
                                <div style="min-width: 220px;">
                                    <form action="{{ route('admin.order.typefilter') }}" method="get" class="mb-0">
                                        <select class="form-select" name="type" onchange="this.form.submit()"
                                            style="height: 38px;">
                                            <option value="" @if (isset($type) && $type == 'all') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    অনুসন্ধান নির্বাচন করুন
                                                @else
                                                    Select Search
                                                @endif
                                            </option>
                                            <option value="all" @if (isset($type) && $type == 'all') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সব অর্ডার
                                                @else
                                                    All Order
                                                @endif
                                            </option>
                                            <option value="walking_customer" @if (isset($type) && $type == 'walking_customer')
                                            selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    হাঁটা গ্রাহক
                                                @else
                                                    Walking Customer
                                                @endif
                                            </option>
                                            <option value="website_customer" @if (isset($type) && $type == 'website_customer')
                                            selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ওয়েবসাইট গ্রাহক
                                                @else
                                                    Website Customer
                                                @endif
                                            </option>
                                        </select>
                                    </form>
                                </div>

                                {{-- Status filter --}}
                                <div style="min-width: 220px;">
                                    <form action="{{ route('admin.order.filterstatus') }}" method="get" class="mb-0">
                                        <select class="form-select" name="status" onchange="this.form.submit()"
                                            style="height: 38px;">
                                            <option value="all" @if (request('status') == 'all' || (isset($stts) && $stts == 'all')) selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সব স্ট্যাটাস
                                                @else
                                                    All Status
                                                @endif
                                            </option>

                                            @if(count($statuses))
                                                @foreach($statuses as $item)
                                                    @if($item->slug == "Payment Success")
                                                        @if ($digitalproductstatus || $advancepaymentstatus)
                                                            <option value="{{ $item->slug }}" @if (request('status') == $item->slug || (isset($stts) && $stts == $item->slug)) selected @endif>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    {{ $item->name_bn }}
                                                                @else
                                                                    {{ $item->name }}
                                                                @endif
                                                            </option>
                                                        @endif
                                                    @elseif($item->slug == "Booked")
                                                        @if($bookingsystemstatus)
                                                            <option value="{{ $item->slug }}" @if (request('status') == $item->slug || (isset($stts) && $stts == $item->slug)) selected @endif>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    {{ $item->name_bn }}
                                                                @else
                                                                    {{ $item->name }}
                                                                @endif
                                                            </option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $item->slug }}" @if (request('status') == $item->slug || (isset($stts) && $stts == $item->slug)) selected @endif>
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                {{ $item->name_bn }}
                                                            @else
                                                                {{ $item->name }}
                                                            @endif
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </form>
                                </div>

                                {{-- Courier button --}}
                                @if(canAccess("courier"))
                                    @php
                                        $showCourierBtn = false;
                                        if (isset($courierInfo) && count($courierInfo) > 0 && $activeCourier) {
                                            foreach ($courierInfo as $courier) {
                                                if (($courier->courier_name == "steadfast") && $courier->status == "1") {
                                                    $showCourierBtn = true;
                                                }
                                            }
                                        }
                                    @endphp

                                    @if($showCourierBtn)
                                        <div style="min-width: 95px;">
                                            <button class="btn bg-orange-600 btn-danger mx-1px text-95 w-100" data-bs-toggle="modal"
                                                data-bs-target="#courier" data-title="Print" style="height: 38px;">
                                                Courier
                                            </button>
                                        </div>
                                    @endif
                                @endif

                                {{-- Refresh button --}}
                                <div style="min-width: 50px;">
                                    <a href="{{ route('admin.order') }}" class="btn btn-info filterbtn w-100"
                                        style="background-color: #7b809a; height: 38px; display:flex; align-items:center; justify-content:center;">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Courier popup modal --}}
                        @if(canAccess("courier"))
                            @if(isset($courierInfo) && count($courierInfo) > 0 && $activeCourier)
                                <div class="modal fade" id="courier" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content" style="background-color:transparent;border:0px">
                                            <div class="modal-body" style="border:none">
                                                <button class="btn btn-danger sm" data-bs-dismiss="modal"
                                                    style="float: right; margin: 0px 8px;">X
                                                </button>

                                                <div class="row mt-1">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @foreach($courierInfo as $courier)
                                                                        @if($courier->courier_name == "steadfast" && $courier->status == "1")
                                                                            <div class="col-3 col-lg-2">
                                                                                <form method="POST" id="steadfastForm"
                                                                                    action="{{ route('courier.createSteadfastOrder') }}">
                                                                                    @csrf
                                                                                    <input type="hidden" id="steadfastOrder"
                                                                                        name="order_ids" value="">
                                                                                    <button onclick="submitSteadfast(event)"
                                                                                        class="btn bg-orange-600 btn-danger mx-1px text-95">
                                                                                        <i
                                                                                            class="mr-1 fa fa-truck text-primary-m1 text-120"></i>
                                                                                        Steadfast
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif

                            {{-- Desktop table --}}
                            <div class="table-responsive" id="desktoptable">
                                <table id="taskfilterresult" class="table table-striped" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="3%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                            <th width="15%">@if (Session::has('lang') && Session::get('lang') == 'bn')
                                            অর্ডারের তারিখ @else Order Date @endif</th>
                                            <th width="10%">@if (Session::has('lang') && Session::get('lang') == 'bn')
                                            অর্ডার নং @else Order ID @endif</th>
                                            <th width="10%">@if (Session::has('lang') && Session::get('lang') == 'bn') ষ্টোর
                                            অর্ডার নং @else Store Order No @endif</th>
                                            <th width="20%">@if (Session::has('lang') && Session::get('lang') == 'bn')
                                            গ্রাহক ফোন @else Customer Phone @endif</th>
                                            <th width="5%">@if (Session::has('lang') && Session::get('lang') == 'bn')
                                            সাবটোটাল @else Subtotal @endif</th>
                                            <th width="5%">@if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ডিসকাউন্ট @else Discount @endif</th>
                                            <th width="5%">@if (Session::has('lang') && Session::get('lang') == 'bn') পাঠানো
                                            @else Shipping @endif</th>
                                            <th width="5%">@if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ট্যাক্স @else Tax @endif</th>
                                            <th width="5%">@if (Session::has('lang') && Session::get('lang') == 'bn') মোট
                                            @else Total @endif</th>
                                            <th width="5%">@if (Session::has('lang') && Session::get('lang') == 'bn') অবস্থা
                                            @else Status @endif</th>
                                            <th width="5%">@if (Session::has('lang') && Session::get('lang') == 'bn') আদেশ
                                            মত @else Order Type @endif</th>
                                            <th width="5%">@if (Session::has('lang') && Session::get('lang') == 'bn') স্টাফ
                                            নিয়োগ @else Assign Staff @endif</th>
                                            <th width="17%">@if (Session::has('lang') && Session::get('lang') == 'bn') দেখুন
                                            @else Action @endif</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if (isset($orders) && count($orders) > 0)
                                            @foreach ($orders as $key => $order)
                                                <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                    <td><input type="checkbox" name="selectedid" value="{{ $order->id }}"
                                                            class="checkSingle"></td>
                                                    <td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                                                    <td>{{ $order->reference_no }}</td>
                                                    <td>{{ $order->order_no ?? "" }}</td>
                                                    <td>
                                                        <span class="phone-number text-primary" style="cursor:pointer"
                                                            data-phone="{{ $order->phone ?? "" }}">{{ $order->phone ?? "" }}</span>
                                                    </td>
                                                    <td>{{$order->symbol}}{{ $order->subtotal }}</td>
                                                    <td>{{$order->symbol}}{{ $order->discount }}</td>
                                                    <td>{{$order->symbol}}{{ $order->shipping }}</td>
                                                    <td>{{$order->symbol}}{{ $order->tax }}</td>
                                                    <td>{{$order->symbol}}{{ $order->total }}</td>
                                                    <td>
                                                        <span class="badge badge-primary" @if ($order->status == 'Pending')
                                                        style="background-color:#f0ad4e;color:#fff" @elseif ($order->status == 'On Hold') style="background-color:#777;color:#fff" @elseif ($order->status == 'Restock') style="background-color:#777;color:#fff"
                                                            @elseif($order->status == 'Delivered')
                                                                style="background-color:green;color:#fff"
                                                            @elseif($order->status == 'Payment Failed')
                                                                style="background-color:#d9534f;color:#fff"
                                                            @elseif($order->status == 'Processing')
                                                                style="background-color:#f0ad4e;color:#fff"
                                                            @elseif($order->status == 'Shipping')
                                                                style="background-color:#337ab7;color:#fff"
                                                            @elseif($order->status == 'Completed')
                                                                style="background-color:#a2cca2;color:#fff"
                                                            @elseif($order->status == 'Cancelled')
                                                                style="background-color:#dba4a2;color:#fff"
                                                            @elseif($order->status == 'Returned')
                                                                style="background-color:#d9534f;color:#fff"
                                                            @elseif($order->status == 'Payment Success')
                                                            style="background-color:green;color:#fff" @else
                                                                style="background-color:red;color:#fff"
                                                            @endif>{{ $order->status }}</span>
                                                    </td>
                                                    <td>{{ $order->type }}</td>
                                                    <td>{{ $order->staff_name ?? $order->staff_username ?? "Not Assign" }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.order.view', $order->id) }}" class="btn btn-info">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                দেখুন
                                                            @else
                                                                View
                                                            @endif
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                {!! $orders->appends(request()->query())->links() !!}
                            </div>

                            {{-- Mobile table --}}
                            <div class="table-responsive" id="mobiletable">
                                <table class="table" width="100%">
                                    @if (isset($orders) && count($orders) > 0)
                                        @foreach ($orders as $key => $order)
                                            @if ($order->status == 'Restock' || $order->status == 'Returned')
                                            @else
                                                <tr class="mobilefirstrow">
                                                    <th width="10%">
                                                        <input type="checkbox" name="selectedid" value="{{ $order->id }}"
                                                            class="checkSingle">
                                                    </th>
                                                    <th width="20%" style="color:#f1593a">
                                                        Reference No:
                                                    </th>
                                                    <td width="60%" style="color:black">
                                                        {{ $order->reference_no }}
                                                    </td>
                                                    <td width="10%">
                                                        <a href="#" class="toggler" data-prod-cat="{{ $key }}">
                                                            <i class="fa fa-arrow-down" id="show{{ $key }}" style="color:#f1593a"></i>
                                                            <i class="fa fa-arrow-up" id="up{{ $key }}" style="display:none"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Order Date</th>
                                                    <td width="60%">{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                                                    <td width="10%"></td>
                                                </tr>
                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Customer Phone</th>
                                                    <td width="60%">{{ $order->phone }}</td>
                                                    <td width="10%"></td>
                                                </tr>
                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Subtotal</th>
                                                    <td width="60%">{{ $order->subtotal }}</td>
                                                    <td width="10%"></td>
                                                </tr>
                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Discount</th>
                                                    <td width="60%">{{ $order->discount }}</td>
                                                    <td width="10%"></td>
                                                </tr>
                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Shipping</th>
                                                    <td width="60%">{{ $order->shipping }}</td>
                                                    <td width="10%"></td>
                                                </tr>
                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Tax</th>
                                                    <td width="60%">{{ $order->tax }}</td>
                                                    <td width="10%"></td>
                                                </tr>
                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Total</th>
                                                    <td width="60%">{{ $order->total }}</td>
                                                    <td width="10%"></td>
                                                </tr>
                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Status</th>
                                                    <td width="60%">
                                                        <span class="badge badge-primary" @if ($order->status == 'Pending')
                                                        style="background-color:#f0ad4e;color:#fff" @elseif ($order->status == 'On Hold') style="background-color:#777;color:#fff" @elseif ($order->status == 'Restock') style="background-color:#777;color:#fff"
                                                            @elseif($order->status == 'Delivered')
                                                            style="background-color:green;color:#fff" @elseif($order->status == 'Payment Failed') style="background-color:#d9534f;color:#fff"
                                                            @elseif($order->status == 'Processing')
                                                                style="background-color:#yellow;color:#fff"
                                                            @elseif($order->status == 'Shipping')
                                                                style="background-color:#337ab7;color:#fff"
                                                            @elseif($order->status == 'Completed')
                                                                style="background-color:#a2cca2;color:#fff"
                                                            @elseif($order->status == 'Cancelled')
                                                                style="background-color:#dba4a2;color:#fff"
                                                            @elseif($order->status == 'Returned')
                                                            style="background-color:#d9534f;color:#fff" @else
                                                            style="background-color:red;color:#fff" @endif>{{ $order->status }}</span>
                                                    </td>
                                                    <td width="10%"></td>
                                                </tr>
                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Order Type</th>
                                                    <td width="60%">{{ $order->type }}</td>
                                                    <td width="10%"></td>
                                                </tr>
                                                <tr class="cat{{ $key }}" style="display:none">
                                                    <th width="10%"></th>
                                                    <th width="20%">Action</th>
                                                    <td width="60%">
                                                        <a href="{{ route('admin.order.view', $order->id) }}" class="btn btn-info">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                দেখুন
                                                            @else
                                                                View
                                                            @endif
                                                        </a>
                                                    </td>
                                                    <td width="10%"></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </table>
                            </div>

                        </div> {{-- card-body --}}
                    </div> {{-- card --}}
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // If SmsAlert exists in session, show SweetAlert prompt to buy module
        if ('{{ Session::has('SmsAlert') }}') {
            let smsAlert = '{{ Session::get('SmsAlert') }}';
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
        // Bulk status change confirmation for specific statuses
        function changeStatus(params) {
            if (params == 'Cancelled') {
                swal.fire({
                    title: 'আপনি কি অর্ডারটি বাতিল করতে চাচ্ছেন?',
                    text: "আপনি অর্ডারটি বাতিল করার পর পুনরায় ডেলিভারি করতে পারবেন না",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $('#changeStatusSubmit').submit();
                        swal.fire('আপনি অর্ডারটি বাতিল করে ফেলেছেন 🫢 ', 'আপনি অর্ডারটি বাতিল করতে সফল হয়েছেন।', 'success');
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swal.fire('Cancelled', 'Deletion Cancel 🥱', 'error');
                    }
                });
            } else if (params == 'Delivered') {
                swal.fire({
                    title: 'আপনি কি অর্ডারটি ডেলিভারি করতে চাচ্ছেন?',
                    text: "আপনি অর্ডারটি বাতিল করার পর পুনরায় বাতিল করতে পারবেন না",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $('#changeStatusSubmit').submit();
                        swal.fire('আপনি অর্ডারটি ডেলিভারি করে ফেলেছেন 🫢 ', 'আপনি অর্ডারটি ডেলিভারি করতে সফল হয়েছেন।', 'success');
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swal.fire('Cancelled', 'Deletion Cancel 🥱', 'error');
                    }
                });
            } else {
                $('#changeStatusSubmit').submit();
            }
        }
    </script>

    <script>
        // Old client-side filter (kept as-is). Your main search is server-side.
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#taskfilterresult tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>

    <script>
        // -----------------------------------------------------------------------------
        // Multi-select logic
        // - #checkedAll selects/deselects all rows
        // - .checkSingle updates hidden inputs so bulk actions can use selected IDs
        // -----------------------------------------------------------------------------
        $(document).ready(function () {
            $("#checkedAll").change(function () {
                if (this.checked) {
                    $(".checkSingle").each(function () {
                        this.checked = true;

                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");

                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);

                        // Courier + Staff hidden inputs
                        $("#pathaoOrder").val(valuesArray);
                        $("#steadfastOrder").val(valuesArray);
                        $("#eCourierOrder").val(valuesArray);
                        $("#redxOrder").val(valuesArray);
                        $("#staffOrder").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });

                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);

                    $("#pathaoOrder").val(valuesArray);
                    $("#steadfastOrder").val(valuesArray);
                    $("#eCourierOrder").val(valuesArray);
                    $("#redxOrder").val(valuesArray);
                    $("#staffOrder").val(valuesArray);
                }
            });

            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;

                    $(".checkSingle").each(function () {
                        if (!this.checked) isAllChecked = 1;

                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");

                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);

                        $("#pathaoOrder").val(valuesArray);
                        $("#steadfastOrder").val(valuesArray);
                        $("#eCourierOrder").val(valuesArray);
                        $("#redxOrder").val(valuesArray);
                        $("#staffOrder").val(valuesArray);
                    });

                    if (isAllChecked == 0) {
                        $("#checkedAll").prop("checked", true);
                    }
                } else {
                    $("#checkedAll").prop("checked", false);

                    var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");

                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);

                    $("#pathaoOrder").val(valuesArray);
                    $("#steadfastOrder").val(valuesArray);
                    $("#eCourierOrder").val(valuesArray);
                    $("#redxOrder").val(valuesArray);
                    $("#staffOrder").val(valuesArray);
                }
            });
        });

        // Export button handler
        // If at least one checkbox is selected, export ONLY those rows by calling
        //   /orderexport?ids=1,2,3
        // Otherwise export all (controller decides default behavior)
        function exportOrder(el) {
            let baseUrl = $(el).data('href');
            let selectedIds = $('input[name="selectedid"]:checked').map(function () {
                return this.value;
            }).get().join(",");

            if (selectedIds) {
                window.location.href = baseUrl + '?ids=' + encodeURIComponent(selectedIds);
            } else {
                window.location.href = baseUrl;
            }
        }

        // Print button handler
        // Opens a new tab with printable view for selected invoices
        //   /invoice/print-selected?ids=1,2,3

        function printSelectedInvoices(e, el) {
            e.preventDefault();

            let selectedIds = $('input[name="selectedid"]:checked').map(function () {
                return this.value;
            }).get().join(",");

            if (!selectedIds) {
                alert("Please select at least one order.");
                return false;
            }

            let url = el.getAttribute('href')
                + '?ids=' + encodeURIComponent(selectedIds)
                + '&source=order';

            window.open(url, '_blank');
            return false;
        }

        const submitSteadfast = (e) => {
            e.preventDefault();

            swal.fire({
                title: 'Are you sure?',
                text: "You want Send Parcel to Steadfast?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#steadfastForm').submit();
                }
            })
        }
    </script>

    <script>
        // Cache cleaner for courier data (localStorage) - keep 3 days
        function cleanOldCourierCache() {
            const cache = JSON.parse(localStorage.getItem('courierCache')) || {};
            const today = new Date();

            for (const phone in cache) {
                const cacheDate = new Date(cache[phone].date);
                const diffInDays = (today - cacheDate) / (1000 * 60 * 60 * 24);
                if (diffInDays > 3) {
                    delete cache[phone];
                }
            }

            localStorage.setItem('courierCache', JSON.stringify(cache));
        }

        document.addEventListener('DOMContentLoaded', () => {
            cleanOldCourierCache();
        });

        // Drawer open/close + Courier API fetch
        $(document).ready(function () {
            const $drawer = $('#drawer');
            const $overlay = $('#drawerOverlay');
            const $content = $('#drawerContent');

            function openDrawer() {
                $drawer.addClass('open');
                $overlay.fadeIn();
            }

            function closeDrawer() {
                $drawer.removeClass('open');
                $overlay.fadeOut();
            }

            $('#closeDrawerBtn, #drawerOverlay').on('click', closeDrawer);

            $(document).on('click', '.phone-number', function () {
                const phone = $(this).data('phone');
                $("#checkPhone").html(phone);
                openDrawer();

                $content.html(`
                                                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                                    <div class="spinner-border text-info mb-3" role="status" style="width: 3rem; height: 3rem;">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                    <p class="font-weight-bold mb-0" style="color: #424c59;">Please wait, processing...</p>
                                                </div>
                                            `);

                try {
                    const validPhone = validateBDPhoneNumber(phone);
                    fetchCourierData(validPhone);
                } catch (error) {
                    $content.html(`<div class="alert alert-danger">${error.message}</div>`);
                }
            });

            function renderCourierTable(data) {
                const defaultStats = {
                    success: 0,
                    cancel: 0,
                    total: 0,
                    deliveredPercentage: 0,
                    returnPercentage: 0,
                };

                const couriers = Object.keys(data);

                let html = `
                                                <div class="responsive-courier-table">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead class="thead-dark">
                                                            <tr>
                                                                <th>Courier</th>
                                                                <th>Success</th>
                                                                <th>Cancel</th>
                                                                <th>Total</th>
                                                                <th>Delivered %</th>
                                                                <th>Return %</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                            `;

                couriers.forEach(courier => {
                    const d = data[courier]?.data || defaultStats;
                    const delivered = d?.deliveredPercentage || 0;
                    const returned = d?.returnPercentage || 0;

                    html += `
                                                    <tr>
                                                        <td>${courier.charAt(0).toUpperCase() + courier.slice(1)}</td>
                                                        <td>${d?.success || 0}</td>
                                                        <td>${d?.cancel || 0}</td>
                                                        <td>${d?.total || 0}</td>
                                                        <td>
                                                            <div class="circle-container text-primary" data-percent="${delivered}">
                                                                <svg viewBox="0 0 36 36">
                                                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                                    <path class="circle" stroke="#28a745" stroke-dasharray="100, 100" stroke-dashoffset="100"
                                                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                                </svg>
                                                                <div class="circle-text text-success">${delivered}%</div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="circle-container text-danger" data-percent="${returned}">
                                                                <svg viewBox="0 0 36 36">
                                                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                                    <path class="circle" stroke="#dc3545" stroke-dasharray="100, 100" stroke-dashoffset="100"
                                                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                                </svg>
                                                                <div class="circle-text text-danger">${returned}%</div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                `;
                });

                html += '</tbody></table></div>';
                $content.html(html);
                loadCircle();
            }

            function fetchCourierData(phone) {
                const cached = getCachedCourierData(phone);
                if (cached) {
                    renderCourierTable(cached);
                    return;
                }

                axios.get(`{{ route('admin.checkCourierData', ['phone' => '__PHONE__']) }}`.replace('__PHONE__', phone))
                    .then(response => {
                        const data = response.data.data;
                        if (response.data.status === false) {
                            $content.html(`<div class="alert alert-warning">${response.data.message}</div>`);
                            return;
                        }
                        setCachedCourierData(phone, data);
                        renderCourierTable(data);
                    })
                    .catch(error => {
                        let message = "Failed to load data.";
                        if (error.response && error.response.data && error.response.data.message) {
                            message = error.response.data.message;
                        }
                        $content.html(`<div class="alert alert-danger">${message}</div>`);
                    });
            }

            function setCachedCourierData(phone, data) {
                const cache = JSON.parse(localStorage.getItem('courierCache')) || {};
                const today = new Date().toISOString().slice(0, 10); // YYYY-MM-DD

                cache[phone] = { date: today, data: data };
                localStorage.setItem('courierCache', JSON.stringify(cache));
            }

            function getCachedCourierData(phone) {
                const cache = JSON.parse(localStorage.getItem('courierCache')) || {};
                if (cache[phone]) {
                    const cacheDate = new Date(cache[phone].date);
                    const today = new Date();
                    const diffInDays = (today - cacheDate) / (1000 * 60 * 60 * 24);
                    if (diffInDays <= 3) return cache[phone].data;
                }
                return null;
            }

            function loadCircle() {
                const circles = document.querySelectorAll('.circle-container');
                circles.forEach(el => {
                    const percent = el.getAttribute('data-percent');
                    const path = el.querySelector('.circle');
                    path.style.strokeDashoffset = 100 - parseInt(percent || 0);
                });
            }

            function normalizeBDPhoneNumber(phone) {
                phone = String(phone || '');
                phone = phone.replace(/\D+/g, '');
                if (phone.startsWith('880')) phone = phone.slice(3);
                if (phone.length === 10 && phone.startsWith('1')) phone = '0' + phone;
                return phone;
            }

            function validateBDPhoneNumber(phone) {
                const normalizedPhone = normalizeBDPhoneNumber(phone);
                const bdPhoneRegex = /^01[3-9][0-9]{8}$/;
                if (!bdPhoneRegex.test(normalizedPhone)) {
                    throw { status: false, message: "Invalid Bangladeshi phone number.", code: 422 };
                }
                return normalizedPhone;
            }

            // ESC closes drawer
            $(document).on('keydown', function (e) {
                if (e.key === "Escape") closeDrawer();
            });
        });
    </script>
@endpush