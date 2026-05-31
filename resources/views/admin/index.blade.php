@extends('admin.layouts.main')

@push('styles')
    <style>
        .copy-text button {
            padding: 10px;
            background: #5784f5;
            color: #fff;
            font-size: 18px;
            border: none;
            outline: none;
            border-radius: 0px 10px 10px 0px;
            cursor: pointer;
        }

        .copy-text button:active {
            background: #809ce2;
        }

        .copy-text button:before {
            content: "Copied";
            position: absolute;
            top: -45px;
            right: 0px;
            background: #5c81dc;
            padding: 8px 10px;
            border-radius: 20px;
            font-size: 15px;
            display: none;
        }

        .copy-text button:after {
            content: "";
            position: absolute;
            top: -20px;
            right: 25px;
            width: 10px;
            height: 10px;
            background: #5c81dc;
            transform: rotate(45deg);
            display: none;
        }

        .copy-text.active button:before,
        .copy-text.active button:after {
            display: block;
        }

        .table td {
            text-align: left;
        }

        .table > :not(caption) > * > * {
            border-bottom-width: 0px !important;
        }

        #unuse1 {
            display: none;
        }

        #unuse {
            display: block;
        }

        @media only screen and (max-width: 500px) {
            #unuse1 {
                display: block;
            }

            #unuse {
                display: none;
            }
        }

        .progress-bar {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(20px, 1fr));
        }

        .progress-step {
            text-align: center;
            position: relative;
            background-color: gray;
            margin-left: 2px;
            margin-right: 2px;
        }

        .progress-step:before,
        .progress-step:after {
            background-color: #c0a359;
            content: "";
            height: 2px;
            position: absolute;
            z-index: -1;
            top: 20px;
        }

        .progress-step:after {
            left: 50%;
            width: 100%;
        }

        .progress-bar .is-active {
            background-color: #f1593a !important;
        }

        .progress-step:last-of-type.is-active:after {
            background-color: #c0a359;
        }

        @media screen and (min-width: 640px) {
            .progress-step:first-of-type:before {
                right: 50%;
                width: 50%;
            }
        }

        @media screen and (max-width: 640px) {

            .progress-step:first-of-type:before,
            .progress-step:last-of-type:after {
                background-color: white !important;
            }
        }

        .progress-step:last-of-type:after {
            left: 50%;
            width: 50%;
        }

        .progress-step .step-count {
            background-color: #c0a359;
            height: 30px;
            width: 30px;
            margin: 0 auto;
            border-radius: 50%;
            color: white;
            line-height: 30px;
            z-index: 100;
            border: 7px solid white;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .progress-step .step-count:before {
            counter-increment: step-count;
            content: counter(step-count);
        }

        .progress-step.is-active .step-description {
            font-weight: 500;
        }

        .progress-step.is-active:after {
            background-color: #dad6ce;
        }

        .progress-step.is-active ~ .progress-step .step-count {
            background-color: #dad6ce;
        }

        .progress-step.is-active ~ .progress-step:after {
            background-color: #dad6ce;
        }

        .step-description {
            font-size: 0.8rem;
        }

        #accessKeyBtn:focus:not(:focus-visible), .btn-primary:active, .btn-primary:focus, .btn-primary:hover, .btn-primary.active:focus {
            border: 1px solid gray !important;
        }

        button#accessKeyBtn {
            font-size: 16px;
            color: #fff;
            padding: 8px 20px;
            border: 1px solid gray;
            border-radius: 10px;
            margin-left: 10px;
            background: #ff5733;
        }

        body.modal-no-scroll .max-height-vh-100 {
            max-height: unset !important;
        }

        /*# sourceMappingURL=style.css.map */
        /*.div {*/
        /*  border: 2px solid #f1593a;*/
        /*  padding: 2px; */
        /*  width: 560px;*/
        /*  resize: both;*/
        /*  overflow: auto;*/
        /*}*/
        .fit-modal-body {
            height: calc(100vh - 225px) !important;
            overflow-y: auto;
        }

        @media screen and (max-width: 434px) {
            .fit-modal-body {
                height: calc(100vh - 175px) !important;
            }
        }

        @media screen and (min-width: 500px) and (max-width: 768px) {
            #accountInfoCard {
                margin-top: 0px !important;
            }
        }

        @media screen and (max-width: 434px) {
            button#accessKeyBtn {
                margin-left: 0px !important;
            }
        }

        .QRBtn {
            background-color: #f75837;
            color: #fff;
        }

        .QRBtn:hover {
            color: #fff;
        }

    </style>
@endpush
@section('content')
    {{--dashboard setup component--}}
    @include('admin.share.dashboard-setup-guide', ['first'=>true])
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="container-fluid py-4">
            <div class="showhidebutton">
                <div class="d-flex flex-row align-items-center">
                    <div>
                        <button id="shh" style="padding: 10px;border: 1px solid gray;border-radius: 10px;"
                                class="btn btn-sm" value="https://www.youtube.com/embed/yRF5qLPfl5E">Play Tutorial <img
                                src="https://img.icons8.com/nolan/24/play.png"/></button>
                        <button id="accessKeyBtn" class="btn btn-sm" onclick="copyAccessKey()"><span
                                style="font-weight:bold">Support PIN</span>:
                            <span
                                id="accessKey">{{ $store->access_key }}</span>
                        </button>
                    </div>

                    @if(isset($websitesetup->data_submit) && $websitesetup->data_submit == 0)
                        <div>
                            <a href="{{ route('admin.websitesetup') }}" class="btn btn-primary"
                               style="margin-left: 7px">Website
                                Setup Info</a>
                        </div>
                    @endif

                </div>
            </div>
            @if ($store->plan_id == null && $store->pos_plan_id == null)
            @else
                <div class="col-lg-4 col-md-6 mt-3" id="unuse1">

                    {{--dashboard setup component--}}
                    @include('admin.share.dashboard-setup-guide', ['second'=>true])
                    <div class="card" style="margin-top:58px;">
                        <div class="card-header pb-0" style="border-radius: 0.75rem 0.75rem 0.75rem 0.75rem;">
                            <h6
                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        অ্যাকাউন্টের তথ্য
                                    @else
                                        Account Info
                                    @endif
                                </span>
                            </h6>
                            <p class="text-sm" style="padding-left:10px;">
                                <span style="font-weight:bold">Current Plan</span> : {{ $plan->name ?? '' }}
                                <br>

                                <span style="font-weight:bold">Price</span> :{{$code}}. {{ $plan->price ?? '' }}
                                <br>
                                <span style="font-weight:bold">Purchase Date</span>: {{ $store->purchase_date ?? '' }}
                                <br>
                                @if(isset($store->renew_date) && !is_null($store->renew_date))
                                    <span style="font-weight:bold">Renew Date</span>: {{ $store->renew_date ?? '' }}
                                    <br>
                                @endif
                                <span style="font-weight:bold">Expiry Date</span>: {{ $store->expiry_date ?? '' }}
                                <br>
                                @if (isset($store->upcoming_plan_id))
                                    <span style="font-weight:bold">Upcoming Plan</span>: {{ $upcoming_plan->name }}
                                    <br>
                                    <span style="font-weight:bold">Upcoming Plan Month</span>:
                                    {{ $store->upcoming_plan_month }}
                                    <br>
                                    <span style="font-weight:bold">Upcoming Plan Expiry Date</span>:
                                    {{ \Carbon\Carbon::parse($store->upcoming_plan_expiry_date)->format("Y-m-d") }}
                                    <br>
                                @endif
                            </p>
                            <div class="visible-print text-center">
                                <p>{!! QrCode::size(100)->generate($store->url ?? env('APP_URL')) !!}</p>
                                <p>Scan me to visit your website.</p>
                                <a href="{{ route('download.qrcode', ["id" => $store->id]) }}"
                                   class="btn btn-sm QRBtn">
                                    Download QR Code
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($smsuse)
                        <div class="card mt-4">
                            <div class="card-body p-3">
                                <h6
                                    style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem; background: {{ $smsuse->total - $smsuse->used <= 100 ? 'red;' : '' }} ">
                                    <span class="nav-link-text ms-1">
                                        {{ $smsuse->addonsName->title }}
                                        <span {{ $smsuse->total - $smsuse->used <= 100 ? '' : 'hidden' }}
                                              style="color: black;">
                                            (Please Renew Your SMS Pack <a style="color: #ffeb00;"
                                                                           href="{{ route('payment.addons') }}"
                                                                           target="_blank"
                                                                           rel="noopener noreferrer">Click to Buy</a>)
                                        </span>
                                    </span>

                                </h6>

                                <p class="text-sm" style="padding-left:10px;">
                                    <span style="font-weight:bold">Plan Name</span> : {{ $smsuse->title }} <br>
                                    <span style="font-weight:bold">Package Price </span>
                                    : {{$symbol}}{{ $smsuse->price }} <br>
                                    @if ($smsuse->type == 'monthly')
                                        <span style="font-weight:bold">Package Type </span>
                                        : {{ ucfirst($smsuse->type) }}
                                        <br>
                                        <span style="font-weight:bold">Month</span> : {{ $smsuse->month }} <br>
                                    @endif
                                    @if ($smsuse->type == 'counter')
                                        <span style="font-weight:bold">Quantity</span> : {{ $smsuse->total ?? 0 }} <br>
                                        <span style="font-weight:bold">Remaining</span> :
                                        {{ $smsuse->total - $smsuse->used ?? 0 }} <br>
                                    @endif
                                    <span style="font-weight:bold">Active Date</span> : {{ $smsuse->created_at ?? '' }}
                                    <br>
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="card mt-4">
                        <div class="card-body p-3">
                            <h6
                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        অ্যাফিলিয়েট মার্কেটিং
                                    @else
                                        Affiliate Marketing
                                    @endif
                                </span>
                            </h6>
                            <strong>Affiliate Link</strong>

                            <div class="copy-text" style="display: flex">
                                <input type="text" class="text form-control" style="border-radius: 10px 0px 0px 10px;"
                                       value="{{ getUserReferralCode() }}"
                                       readonly/>
                                <button><i class="fa fa-clone"></i></button>
                            </div>
                            <br><span style="font-weight:bold">Your Commission Rate</span> :
                            {{ auth()->user()->referral_commission }}%
                            <br><span style="font-weight:bold">Your Balance</span> : {{$code}}
                            {{ conversionsCurrency(auth()->user()->total_commission , auth()->user()->currency_id, $store_id)['amount']}}
                            <br>
                        </div>
                    </div>

                    {{--                    <div class="card mt-4">--}}
                    {{--                        <div class="card-body p-3">--}}
                    {{--                            <h6--}}
                    {{--                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">--}}
                    {{--                                <span class="nav-link-text ms-1">--}}
                    {{--                                    @if (Session::has('lang') && Session::get('lang') == 'bn')--}}
                    {{--                                        রিওয়ার্ড তথ্য--}}
                    {{--                                    @else--}}
                    {{--                                        Reward Info--}}
                    {{--                                    @endif--}}
                    {{--                                </span>--}}
                    {{--                            </h6>--}}
                    {{--                            <p class="text-sm" style="padding-left:10px;">--}}
                    {{--                                <span style="font-weight:bold">Reference Code 1</span> : {{ $customer->ref_code }}--}}
                    {{--                                <br><span style="font-weight:bold">Total Points</span> : {{ $customer->points }}--}}
                    {{--                                <br>--}}
                    {{--                            </p>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                </div>
            @endif
            <div class="row mb-4" id="hre2">
                <div class="col-lg-8 col-md-8 order-2 order-md-1 mt-3">
                    <div id="toptoolstour">
                        <div class="row">
                            <div class="col-md-12 mt-3 mb-3">
                                <h3><span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            সর্বাধিক ব্যবহৃত
                                            টুলস
                                        @else
                                            Top used tools
                                        @endif
                                    </span></h3>
                                <p style="font:10px; color:#424242;"><span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            যে অপশনগুল আপনি সবচেয়ে বেশি ব্যবহার করেছেন
                                        @else
                                            The options that you used most
                                        @endif
                                    </span>
                                <p>
                            </div>
                        </div>
                        <div class="row">
                            @if (isset($use) && count($use) > 0)
                                @foreach ($use as $key => $uu)
                                    @if ($key < 6)
                                        <div class="col-md-6 mt-3">
                                            <a href="{{ URL::to('/') }}{{ $uu->url }}"
                                               style="display: block;width: 100%;padding: 20px 20px;background-color: #fff !important;border-radius:10px;"><img
                                                    src="{{ URL::to('/') }}/img/icons/{{ $uu->image }}"
                                                    width="40" height="40">
                                                {{ $uu->name }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="row">
                                        <div class="col-lg-12 col-12">
                                            <h6
                                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                                <span class="nav-link-text ms-1">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        অর্ডার পরিসংখ্যান
                                                    @else
                                                        Order Statistic
                                                    @endif
                                                </span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body px-4 pb-2">
                                    <div class="table-responsive" style="padding-bottom: 20px;">
                                        <table class="table table-striped" style="margin-bottom:30px;" width="100%">
                                            @php
                                                $ordersInfo = DB::table('orders')
                                                    ->where('store_id', $store_id)
                                                    ->get();
                                            @endphp
                                            <tbody>
                                            <tr>
                                                <th width="25%">Pending</th>
                                                <td width="25%">
                                                    {{ $ordersInfo->where('status', 'Pending')->count() }}</td>
                                                <th width="25%">On Hold</th>
                                                <td width="25%">
                                                    {{ $ordersInfo->where('status', 'On Hold')->count() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Payment Failed</th>
                                                <td>{{ $ordersInfo->where('status', 'Payment Failed')->count() }}</td>
                                                <th>Processing</th>
                                                <td>{{ $ordersInfo->where('status', 'Processing')->count() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping</th>
                                                <td>{{ $ordersInfo->where('status', 'Shipping')->count() }}</td>
                                                <th>Delivered</th>
                                                <td>{{ $ordersInfo->where('status', 'Delivered')->count() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Returned</th>
                                                <td>{{ $ordersInfo->where('status', 'Returned')->count() }}</td>
                                                <th>Cancel</th>
                                                <td>{{ $ordersInfo->where('status', 'Cancelled')->count() }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 order-1 order-md-2 mt-3" id="unuse">

                    {{--dashboard setup component--}}
                    @include('admin.share.dashboard-setup-guide', ['third'=>true])
                    <div class="card" id="accountInfoCard" style="margin-top:58px;">
                        <div id="accinfotr" class="card-header pb-0"
                             style="border-radius: 0.75rem 0.75rem 0.75rem 0.75rem;">
                            <h6
                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        অ্যাকাউন্টের তথ্য
                                    @else
                                        Account Info
                                    @endif
                                </span>
                            </h6>
                            <p class="text-sm" style="padding-left:10px;">
                                <span style="font-weight:bold">Current Plan</span> : {{ $plan->name ?? '' }}
                                <br>
                                <span style="font-weight:bold">Price</span> :{{$code}}. {{ $plan->price ?? '' }}
                                <br>
                                <span style="font-weight:bold">Purchase Date</span>: {{ $store->purchase_date ?? '' }}
                                <br>
                                @if(isset($store->renew_date) && !is_null($store->renew_date))
                                    <span style="font-weight:bold">Renew Date</span>: {{ $store->renew_date ?? '' }}
                                    <br>
                                @endif
                                <span style="font-weight:bold">Expiry Date</span>: {{ $store->expiry_date ?? '' }}
                                <br>
                                @if (isset($store->upcoming_plan_id))
                                    <span style="font-weight:bold">Upcoming Plan</span>: {{ $upcoming_plan->name }}
                                    <br>
                                    <span style="font-weight:bold">Upcoming Plan Month</span>:
                                    {{ $store->upcoming_plan_month }}
                                    <br>
                                    <span style="font-weight:bold">Upcoming Plan Expiry Date</span>:
                                    {{ \Carbon\Carbon::parse($store->upcoming_plan_expiry_date)->format("Y-m-d") }}
                                    <br>
                                @endif
                            </p>
                            <div class="visible-print text-center">
                                <p>{!! QrCode::size(100)->generate($store->url ?? env('APP_URL')) !!}</p>
                                <p>Scan me to visit your website.</p>
                                <a href="{{ route('download.qrcode', ["id" => $store->id]) }}"
                                   class="btn btn-sm QRBtn">
                                    Download QR Code
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($smsuse)
                        <div class="card mt-4">
                            <div class="card-body p-3">
                                <h6
                                    style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem; background: {{ $smsuse->total - $smsuse->used <= 100 ? 'red;' : '' }} ">
                                    <span class="nav-link-text ms-1">
                                        {{ $smsuse->addonsName->title }}
                                        <span {{ $smsuse->total - $smsuse->used <= 100 ? '' : 'hidden' }}
                                              style="color: black;">
                                            (Please Renew Your SMS Pack <a style="color: #ffeb00;"
                                                                           href="{{ route('payment.addons') }}"
                                                                           target="_blank"
                                                                           rel="noopener noreferrer">Click to Buy</a>)
                                        </span>
                                    </span>

                                </h6>

                                <p class="text-sm" style="padding-left:10px;">
                                    <span style="font-weight:bold">Plan Name</span> : {{ $smsuse->title }} <br>
                                    <span style="font-weight:bold">Package Price </span>
                                    : {{$symbol}}{{ $smsuse->price }} <br>
                                    @if ($smsuse->type == 'monthly')
                                        <span style="font-weight:bold">Package Type </span>
                                        : {{ ucfirst($smsuse->type) }}
                                        <br>
                                        <span style="font-weight:bold">Month</span> : {{ $smsuse->month }} <br>
                                    @endif
                                    @if ($smsuse->type == 'counter')
                                        <span style="font-weight:bold">Quantity</span> : {{ $smsuse->total ?? 0 }} <br>
                                        <span style="font-weight:bold">Remaining</span> :
                                        {{ $smsuse->total - $smsuse->used ?? 0 }} <br>
                                    @endif
                                    <span style="font-weight:bold">Active Date</span> : {{ $smsuse->created_at ?? '' }}
                                    <br>
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="card mt-4">
                        <div class="card-body p-3">
                            <h6
                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        অ্যাফিলিয়েট মার্কেটিং
                                    @else
                                        Affiliate Marketing
                                    @endif
                                </span>
                            </h6>
                            <strong>Affiliate Link</strong>

                            <div class="copy-text" style="display: flex">
                                <input type="text" class="text form-control" style="border-radius: 10px 0px 0px 10px;"
                                       value="{{ getUserReferralCode() }}" readonly/>
                                <button><i class="fa fa-clone"></i></button>
                            </div>
                            <br><span style="font-weight:bold">Your Commission Rate</span> :
                            {{ auth()->user()->referral_commission }}%
                            <br><span style="font-weight:bold">Your Balance</span> : {{$code}}
                            {{ conversionsCurrency(auth()->user()->total_commission , auth()->user()->currency_id, $store_id)['amount']}}
                            <br>
                        </div>
                    </div>


                </div>
                <div class="col-lg-8 col-md-6 mb-md-0 mb-4">

                </div>


            </div>
            <div class="row" id="hre1">
                <div class="col-md-12">
                    <h3><span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                সর্বাধিক বিক্রিত পণ্য
                            @else
                                Top selling products
                            @endif
                        </span></h3>
                </div>
            </div>
            <div class="row" style="margin-bottom:30px;">
                @if (isset($vals) && count($vals) > 0)
                        <?php $i = 0;
                        foreach ($vals as $key => $vall) {
                            $price[$i]['v'] = $vall;
                            $price[$i]['p'] = $key;
                            $i++;
                        }
                        rsort($price);
                        $j = 0;
                        ?>
                    @if (isset($price) && count($price) > 0)
                        @foreach ($price as $keys => $prices)
                            @if ($j < 6)
                                    <?php

                                    $product = DB::table('products')
                                        ->where('id', $prices['p'])
                                        ->where('store_id', $store_id)
                                        ->where('customer_id', $customer_id)
                                        ->first();
                                    ?>
                                @if (isset($product))
                                    @php
                                        $conversionsCurrency =conversionsCurrency($product->regular_price, $product->id, $store_id);
                                    @endphp
                                    <div class="col-xl-2 col-md-3 col-sm-6 mb-xl-0 mb-4"
                                         style="padding-left:5px !important;padding-right:5px !important;">
                                        <div class="card">
                                            <div class="card-header p-3 pt-2">
                                                @php
                                                    $images = array_filter(explode(',', $product->images));
                                                    $gallery_image = array_filter(explode(',', $product->gallery_image));
                                                    $mergedImages = array_unique(array_merge($gallery_image, $images));
                                                    $images = array_map(fn($img) => getPath($img, 'assets/images/product'), $mergedImages);
                                                @endphp

                                                @if(isset($images[0]))
                                                    <img src="{{ $images[0] }}"
                                                         style="width:100%;height:250px;" alt="">
                                                @endif
                                            </div>

                                            <hr class="dark horizontal my-0">
                                            <div class="card-footer p-3">
                                                <p class="mb-0">
                                                    {{ Str::limit($product->name, 10) }}
                                                </p>
                                                <p>{{$conversionsCurrency['code']}}
                                                    . {{ $conversionsCurrency['amount'] }}&nbsp;&nbsp;</p>
                                                <p>{{ $prices['v'] }} Sales</p>
                                            </div>
                                        </div>
                                    </div>
                                        <?php $j++; ?>
                                @endif
                            @endif
                        @endforeach
                    @endif
                @endif
            </div>


            @endsection
            @section('js')
                <script src="{{ asset('admin/assets/js/plugins/chartjs.min.js') }}"></script>

                <script>
                    $(document).ready(function () {
                        $('#myModalGuide').modal('show');
                    });

                    // Initialize the tour
                    tour1.init();

                    // Start the tour
                    tour1.start();
                    $(document).ready(function () {
                        $("#hideproducts").hide();
                        $("#mainproducts").on("click", function () {
                            $("#hideproducts").toggle();
                            $("#hidecategory").hide();
                            $("#hidesetting").hide();
                            $("#hidetheme").hide();
                            $("#hidemenu").hide();
                            $("#hidepage").hide();
                            $("#hidedomain").hide();
                        });
                        $("#maincategory").on("click", function () {
                            $("#hidecategory").toggle();
                            $("#hideproducts").hide();
                            $("#hidesetting").hide();
                            $("#hidetheme").hide();
                            $("#hidemenu").hide();
                            $("#hidepage").hide();
                            $("#hidedomain").hide();
                        });
                        $("#hidesetting").hide();
                        $("#mainsetting").on("click", function () {
                            $("#hidesetting").toggle();
                            $("#hidecategory").hide();
                            $("#hideproducts").hide();
                            $("#hidetheme").hide();
                            $("#hidemenu").hide();
                            $("#hidepage").hide();
                            $("#hidedomain").hide();
                        });
                        $("#hidetheme").hide();
                        $("#maintheme").on("click", function () {
                            $("#hidetheme").toggle();
                            $("#hidecategory").hide();
                            $("#hideproducts").hide();
                            $("#hidesetting").hide();
                            $("#hidemenu").hide();
                            $("#hidepage").hide();
                            $("#hidedomain").hide();
                        });
                        $("#hidemenu").hide();
                        $("#mainmenu").on("click", function () {
                            $("#hidemenu").toggle();
                            $("#hidecategory").hide();
                            $("#hideproducts").hide();
                            $("#hidesetting").hide();
                            $("#hidetheme").hide();
                            $("#hidepage").hide();
                            $("#hidedomain").hide();
                        });
                        $("#hidepage").hide();
                        $("#mainpage").on("click", function () {
                            $("#hidepage").toggle();
                            $("#hidecategory").hide();
                            $("#hideproducts").hide();
                            $("#hidesetting").hide();
                            $("#hidetheme").hide();
                            $("#hidemenu").hide();
                            $("#hidedomain").hide();
                        });
                        $("#hidedomain").hide();
                        $("#mainpage1").on("click", function () {
                            $("#hidepage").toggle();
                            $("#hidecategory").hide();
                            $("#hideproducts").hide();
                            $("#hidesetting").hide();
                            $("#hidetheme").hide();
                            $("#hidemenu").hide();
                            $("#hidedomain").hide();
                        });
                        $("#hidedomain").hide();
                        $("#maindomain").on("click", function () {
                            $("#hidedomain").toggle();
                            $("#hidecategory").hide();
                            $("#hideproducts").hide();
                            $("#hidesetting").hide();
                            $("#hidetheme").hide();
                            $("#hidemenu").hide();
                            $("#hidepage").hide();
                        });
                    })


                    // Initialize the tour
                    tour1.init();

                    // Start the tour
                    tour1.start();
                    $(document).ready(function () {
                        $("#modelhideproducts").hide();
                        $("#modelmainproducts").on("click", function () {
                            $("#modelhideproducts").toggle();
                            $("#modelhidecategory").hide();
                            $("#modelhidesetting").hide();
                            $("#modelhidetheme").hide();
                            $("#modelhidemenu").hide();
                            $("#modelhidepage").hide();
                            $("#modelhidedomain").hide();
                        });
                        $("#modelmaincategory").on("click", function () {
                            $("#modelhidecategory").toggle();
                            $("#modelhideproducts").hide();
                            $("#modelhidesetting").hide();
                            $("#modelhidetheme").hide();
                            $("#modelhidemenu").hide();
                            $("#modelhidepage").hide();
                            $("#modelhidedomain").hide();
                        });
                        $("#modelhidesetting").hide();
                        $("#modelmainsetting").on("click", function () {
                            $("#modelhidesetting").toggle();
                            $("#modelhidecategory").hide();
                            $("#modelhideproducts").hide();
                            $("#modelhidetheme").hide();
                            $("#modelhidemenu").hide();
                            $("#modelhidepage").hide();
                            $("#modelhidedomain").hide();
                        });
                        $("#modelhidetheme").hide();
                        $("#modelmaintheme").on("click", function () {
                            $("#modelhidetheme").toggle();
                            $("#modelhidecategory").hide();
                            $("#modelhideproducts").hide();
                            $("#modelhidesetting").hide();
                            $("#modelhidemenu").hide();
                            $("#modelhidepage").hide();
                            $("#modelhidedomain").hide();
                        });
                        $("#modelhidemenu").hide();
                        $("#modelmainmenu").on("click", function () {
                            $("#modelhidemenu").toggle();
                            $("#modelhidecategory").hide();
                            $("#modelhideproducts").hide();
                            $("#modelhidesetting").hide();
                            $("#modelhidetheme").hide();
                            $("#modelhidepage").hide();
                            $("#modelhidedomain").hide();
                        });
                        $("#modelhidepage").hide();
                        $("#modelmainpage").on("click", function () {
                            $("#modelhidepage").toggle();
                            $("#modelhidecategory").hide();
                            $("#modelhideproducts").hide();
                            $("#modelhidesetting").hide();
                            $("#modelhidetheme").hide();
                            $("#modelhidemenu").hide();
                            $("#modelhidedomain").hide();
                        });
                        $("#modelhidedomain").hide();
                        $("#modelmainpage1").on("click", function () {
                            $("#modelhidepage").toggle();
                            $("#modelhidecategory").hide();
                            $("#modelhideproducts").hide();
                            $("#modelhidesetting").hide();
                            $("#modelhidetheme").hide();
                            $("#modelhidemenu").hide();
                            $("#modelhidedomain").hide();
                        });
                        $("#modelhidedomain").hide();
                        $("#modelmaindomain").on("click", function () {
                            $("#modelhidedomain").toggle();
                            $("#modelhidecategory").hide();
                            $("#modelhideproducts").hide();
                            $("#modelhidesetting").hide();
                            $("#modelhidetheme").hide();
                            $("#modelhidemenu").hide();
                            $("#modelhidepage").hide();
                        });
                    })


                    $("#prods").on("click", function () {
                        debugger;
                        $("#hprods").toggle();
                        $("#hcats").hide();
                        $("#hsett").hide();
                        $("#hthem").hide();
                        $("#hmenu").hide();
                        $("#hpage").hide();
                        $("#hdomain").hide();
                    });
                    $("#hsett").hide();
                    $("#sett").on("click", function () {
                        $("#hsett").toggle();
                        $("#hcats").hide();
                        $("#hprods").hide();
                        $("#hthem").hide();
                        $("#hmenu").hide();
                        $("#hpage").hide();
                        $("#hdomain").hide();
                    });
                    $("#hthem").hide();
                    $("#them").on("click", function () {
                        $("#hthem").toggle();
                        $("#hcats").hide();
                        $("#hprods").hide();
                        $("#hsett").hide();
                        $("#hmenu").hide();
                        $("#hpage").hide();
                        $("#hdomain").hide();
                    });
                    $("#hmenu").hide();
                    $("#menu").on("click", function () {
                        $("#hmenu").toggle();
                        $("#hcats").hide();
                        $("#hprods").hide();
                        $("#hsett").hide();
                        $("#hthem").hide();
                        $("#hpage").hide();
                        $("#hdomain").hide();
                    });
                    $("#hpage").hide();
                    $("#page").on("click", function () {
                        $("#hpage").toggle();
                        $("#hcats").hide();
                        $("#hprods").hide();
                        $("#hsett").hide();
                        $("#hthem").hide();
                        $("#hmenu").hide();
                        $("#hdomain").hide();
                    });
                    $("#hdomain").hide();
                    $("#domain").on("click", function () {
                        $("#hdomain").toggle();
                        $("#hcats").hide();
                        $("#hprods").hide();
                        $("#hsett").hide();
                        $("#hthem").hide();
                        $("#hmenu").hide();
                        $("#hpage").hide();
                    });
                </script>
            @endsection
            @push('scripts')
                <script>
                    let copyText = document.querySelector(".copy-text");
                    copyText.querySelector("button").addEventListener("click", function () {
                        let input = copyText.querySelector("input.text");
                        input.select();
                        document.execCommand("copy");
                        copyText.classList.add("active");
                        window.getSelection().removeAllRanges();
                        setTimeout(function () {
                            copyText.classList.remove("active");
                        }, 2500);
                    });
                </script>
                <script>
                    function setCookie(cname, cvalue, exdays) {
                        const d = new Date();
                        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                        let expires = "expires=" + d.toUTCString();
                        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                    }

                    function getCookie(cname) {
                        let name = cname + "=";
                        let decodedCookie = decodeURIComponent(document.cookie);
                        let ca = decodedCookie.split(';');
                        for (let i = 0; i < ca.length; i++) {
                            let c = ca[i];
                            while (c.charAt(0) == ' ') {
                                c = c.substring(1);
                            }
                            if (c.indexOf(name) == 0) {
                                return c.substring(name.length, c.length);
                            }
                        }
                        return "";
                    }

                    const currentDate = new Date().toJSON().slice(0, 10);

                    const date = getCookie("currentDate");
                    if (date !== "" && date === currentDate) {
                        // console.log("upto date currency rate", date);
                    } else {
                        $url = "/common/flash_exchange_rate";
                        $.get($url, {
                            date: date
                        }, function (data) {
                            // console.log("successfully upto date currency exchange")
                        });
                        setCookie("currentDate", currentDate, 7);
                    }

                    // copy access page
                    const copyAccessKey = () => {
                        const copyText = "{{ $store->access_key ?? '' }}"

                        // Use the modern Clipboard API
                        navigator.clipboard.writeText(copyText).then(function () {
                            toastr.success("Support PIN successfully copied to clipboard!");
                            $("#accessKeyBtn").html("Successfully copied").css("background-color", "green");

                            const btnText = '<span style="font-weight:bold">Support PIN</span>: ' +
                                '<span id="accessKey">' + "{{ $store->access_key ?? '' }}" + '</span>';

                            setTimeout(function () {
                                $("#accessKeyBtn").html(btnText).css("background-color", "#ff5733");
                            }, 2500);

                        }).catch(function (error) {
                            toastr.error("Failed to copy: " + error);
                        });
                    }

                    document.querySelectorAll('#myModalGuide').forEach(modal => {
                        const content = modal.querySelector('.modal-content');

                        modal.addEventListener('show.bs.modal', () => {
                            document.body.classList.add('modal-no-scroll');
                        });

                        modal.addEventListener('hide.bs.modal', () => {
                            document.body.classList.remove('modal-no-scroll');
                        });
                    });
                </script>
    @endpush
