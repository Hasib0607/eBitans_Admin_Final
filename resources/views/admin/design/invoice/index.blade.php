@extends('admin.layouts.main')
@push('styles')
    <style>
        .themes .card-title {
            font-weight: 300;
            font-size: 13px;
            /*text-shadow: 0 0 2px #000;*/
            border-top-right-radius: 67.5px;
            background: #f1593a;
            color: #fff;
            padding: 6px 19px;
            margin-bottom: 14px;
        }

        .themes .product-card .card {
            margin: 20px;
            /*overflow: hidden;*/
        }

        .themes .product-card .card .card-content {
            padding: 5px;
        }

        .themes .product-card .card .price {
            width: 70px;
            height: 70px;
            font-weight: 600;
            font-size: 1.45rem;
            line-height: 70px;
            margin: 10px;
            position: absolute;
            top: 0;
            letter-spacing: 0;
        }

        .themes .product-card ul.card-action-buttons {
            /*margin: -24px 4px 0 0;*/
            text-align: right;
        }

        .themes .product-card ul.card-action-buttons li {
            display: inline-block;
            padding-left: 7px;
        }

        .themes .product-card ul.card-action-buttons li > a > i {
            color: #4a4a4a;
        }

        .themes .product-card ul.card-action-buttons li a:hover {
            background-color: #f1593a;
            color: #fff;
        }

        .themes .product-card ul.card-action-buttons li a:hover > a > i {
            color: #fff !important;
        }

        .themes .product {
            width: 20%;
            padding: 10px;
        }

        .themes .product .card {
            margin: 0;
        }

        .themes .product .card .card-content {
            padding: 5px 10px;
        }

        div.see-more:last-of-type {
            width: 100%;
            text-align: center;
            margin-top: 20px;
            background-color: #03A9DD;
        }

        div.see-more a {
            color: #fff
        }

        /*#toplist ul li {*/
        /*  padding: 0px 0px !important;*/
        /*}*/
        /*#toplist ul li a{*/
        /*  padding: 3px 11px !important;*/
        /*}*/


        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: 150%;
            left: 50%;
            margin-left: -60px;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: black transparent transparent transparent;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
        }

        .themeactive {
            background-color: #f1593a;
            color: #fff;
        }

        .themeactive > a > i {
            color: #fff !important;
        }

        .fade:not(.show) {
            opacity: 0;
        }

        .themeviewdiv {
            padding: 15px;
            display: flex;
            align-items: center;
        }

        .themeviewdiv span {
            color: #000;
        }

        .a1 {
            display: flex;
            text-align: center;
            justify-content: center;
        }

        .a2 {
            background-color: #808080;
            border-radius: 0px 6px 6px 0px;
            margin: 14.8px 0px 0px -1px;
            color: white;
            letter-spacing: 1px;
            padding: 2.5px 5px 0px 5px;
            height: 21.5px;
            transform: skew(170deg);
        }

        .fa-crown {
            font-size: 20px;
            color: #FFD114;
        }

        .card1 {
            width: 100%;
            border-radius: 1rem;
            background: white;
            box-shadow: 4px 4px 15px rgba(#000, 0.15);
            position: relative;
            color: #434343;
        }

        .card1 .card__container {
            /*padding : 2rem;*/
            width: 100%;
            height: 100%;
            background: white;
            /*border-radius: 1rem;*/
            position: relative;
        }

        .card1 .card__header {
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .card1 .card__body {
            font-family: 'Roboto', sans-serif;
        }

        .card1::before {
            position: absolute;
            top: 2rem;
            right: -0.5rem;
            content: '';
            background: #283593;
            transform: rotate(45deg);
        }

        .card1::after {
            position: absolute;
            content: attr(data-label);
            top: 11px;
            right: -14px;
            padding: 0.5rem;
            background: #3949ab;
            color: white;
            text-align: center;
            font-family: 'Roboto', sans-serif;
            box-shadow: 4px 4px 15px rgba(26, 35, 126, 0.2);
        }

        @media only screen and (max-width: 320px) {
            .themes .card-title {
                font-size: 10px;
            }
        }

        .badge-overlay {
            position: absolute;
            left: 0%;
            top: 0px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 100;
            -webkit-transition: width 1s ease, height 1s ease;
            -moz-transition: width 1s ease, height 1s ease;
            -o-transition: width 1s ease, height 1s ease;
            transition: width 0.4s ease, height 0.4s ease
        }

        /* ================== Badge CSS ========================*/
        .badge {
            margin: 0;
            padding: 0;
            color: white;
            padding: 10px 10px;
            font-size: 15px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            line-height: normal;
            text-transform: uppercase;
            background: #ed1b24;
        }

        .badge::before, .badge::after {
            content: '';
            position: absolute;
            top: 0;
            margin: 0 -1px;
            width: 100%;
            height: 100%;
            background: inherit;
            min-width: 55px
        }

        .badge::before {
            right: 100%
        }

        .badge::after {
            left: 100%
        }

        .top-right {
            position: absolute;
            top: 0;
            right: 0;
            -ms-transform: translateX(30%) translateY(0%) rotate(45deg);
            -webkit-transform: translateX(30%) translateY(0%) rotate(45deg);
            transform: translateX(30%) translateY(0%) rotate(45deg);
            -ms-transform-origin: top left;
            -webkit-transform-origin: top left;
            transform-origin: top left;
        }

        .badge.red {
            background: #ed1b24;
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        {{--design main top nav--}}
        @include('admin.design.share.designs-nav', ['invoice' => true])

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn')
                            সমস্ত চালান
                        @else
                            All Invoice
                        @endif</h4>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{route('admin.design.invoice_search')}}" method="get">
                                <div class="row">
                                    <div class="col-md-1" style="width:3% !important;margin-left:10px;">

                                    </div>
                                    <div class="col-md-2">

                                    </div>
                                    <div class="col-md-5">

                                    </div>
                                    <div class="col-md-1">
                                        <!--<input type="date" name="date" class="form-control">-->
                                    </div>
                                    <div class="col-md-2" style="padding-right:1px;">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   aria-label="Dollar amount (with dot and two decimal places)"
                                                   id="taskfilter" name="keyword" value="{{ $keyword ?? "" }}">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                    class="fa fa-search"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-1" style="padding-left:0px;">
                                        <button type="submit"
                                                class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                সাবমিট
                                            @else
                                                Submit
                                            @endif</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body d-flex flex-wrap flex-row justify-content-between">
                            <div class="row" style="width:100%">
                                @foreach($designs as $key=>$design)
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 themes">
                                        <div class="row">
                                            <div class="col l4 m8 s12 offset-m2 offset-l4">
                                                <div class="product-card">
                                                    <div class="card z-depth-4">
                                                        <div class="card-image">
                                                            <img
                                                                src="{{URL::to('/')}}/assets/images/design/{{$design->image}}"
                                                                width="100%" height="250" alt="product-img">
                                                        </div>
                                                        @if($key > 9 && $design->status == 'approved')
                                                            <div class="badge-overlay">
                                                                <span class="top-right badge red">Paid</span>
                                                            </div>
                                                        @endif
                                                        @if($key > 9)
                                                            <span style="position:absolute;padding:10px">
                                                                <div class="a1">
                                                                  <i class="fa fa-solid fa-crown"></i>
                                                                  <div class="a2"><span
                                                                          style="font-size:12px">Premium</span></div>
                                                                </div>
                                                                <img src="{{asset('img/permium.png')}}" width="60">
                                                            </span>
                                                        @endif
                                                        <div class="row mt-0 themeviewdiv mb-3">
                                                            <div class="col-md-6">
                                                                <span>{{ Str::limit($design->name, 20) }}</span>
                                                            </div>
                                                            {{--preview modal--}}
                                                            <div class="modal fade" id="previewModel{{$key}}"
                                                                 tabindex="-1"
                                                                 aria-labelledby="previewModelLabel"
                                                                 aria-hidden="true">
                                                                <div class="modal-dialog modal-xl">
                                                                    <div class="modal-content"
                                                                         style="background-color:transparent;border:0px">

                                                                        <div class="modal-body"
                                                                             style="border:none">
                                                                            <button class="btn btn-danger sm"
                                                                                    data-bs-dismiss="modal"
                                                                                    style="float: right; margin: 0px 8px;">
                                                                                X
                                                                            </button>
                                                                            @if(isset($design->image))
                                                                                <img
                                                                                    src="{{URL::to('/')}}/assets/images/design/{{$design->image}}"
                                                                                    class="img-fluid" alt=""
                                                                                    style="padding:0px 10px;border:0px solid gray;transition-delay: 5s;">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{--purchase modal--}}
                                                            <div class="modal fade" id="purchaseModal{{$key}}"
                                                                 tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                 aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        @if($design->status == 'pending')
                                                                            <div class="model-body"
                                                                                 style="display:flex;align-items:center;height:200px;">
                                                                                <p style="padding:20px;text-align:center">
                                                                                    You Already Submit Payment For this
                                                                                    invoice design. please wait a
                                                                                    patient, after verifying your
                                                                                    payment it will automatically
                                                                                    activate.</p>
                                                                            </div>
                                                                        @else
                                                                            <div class="modal-header">
                                                                                <table class="table table-striped"
                                                                                       width="50%">
                                                                                    <tr>
                                                                                        <th style="text-align:start">
                                                                                            Invoice name
                                                                                            : {{$dsg->name ?? ""}}</th>
                                                                                        <td>BDT 20</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <form method="post"
                                                                                              action="{{route('admin.buyinvoice')}}">
                                                                                            @csrf
                                                                                            <input type="hidden"
                                                                                                   name="invoice_id"
                                                                                                   value="{{$design->id}}">
                                                                                            <div
                                                                                                class="custom-control custom-radio">
                                                                                                <input type="radio"
                                                                                                       id="html"
                                                                                                       name="paymentMethod"
                                                                                                       value="bkash"
                                                                                                       checked>
                                                                                                <label
                                                                                                    for="html">Bkash</label><br>
                                                                                                <input type="radio"
                                                                                                       id="css"
                                                                                                       name="paymentMethod"
                                                                                                       value="nagad">
                                                                                                <label
                                                                                                    for="css">Nagad</label><br>
                                                                                            </div>
                                                                                            <div class="">
                                                                                                <label>Number</label>
                                                                                                <br>
                                                                                                <input id="credit"
                                                                                                       name="number"
                                                                                                       type="tel"
                                                                                                       class="form-control custom-control-input"
                                                                                                       required>
                                                                                            </div>
                                                                                            <div class="">
                                                                                                <label>Transaction
                                                                                                    Id</label>
                                                                                                <input id="credit"
                                                                                                       name="transaction_id"
                                                                                                       type="text"
                                                                                                       class="form-control custom-control-input"
                                                                                                       required>
                                                                                            </div>
                                                                                            <button type="submit"
                                                                                                    class="btn btn-primary mt-2">
                                                                                                Submit
                                                                                            </button>
                                                                                        </form>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <ul style="list-style:none"
                                                                                            id="bkashsetps">
                                                                                            <li style="float:unset">You
                                                                                                need to payment this
                                                                                                Bkash number 01677515579
                                                                                            </li>
                                                                                            <li style="float:unset"></li>
                                                                                            <li style="float:unset">
                                                                                                Step:
                                                                                            </li>
                                                                                            <li style="float:unset">1.
                                                                                                Dial *247# to go mobile
                                                                                                menu.
                                                                                            </li>
                                                                                            <li style="float:unset">2.
                                                                                                Enter "1" to select send
                                                                                                money option
                                                                                            </li>
                                                                                            <li style="float:unset">3.
                                                                                                Enter "01677515579" and
                                                                                                press send
                                                                                            </li>
                                                                                            <li style="float:unset">4.
                                                                                                Enter Amount "<span
                                                                                                    id="finaltotal1"><strong
                                                                                                        id="finaltotal1">BDT 20</strong></span>"
                                                                                            </li>
                                                                                            <li style="float:unset">5.
                                                                                                Enter Reference "123"
                                                                                            </li>
                                                                                            <li style="float:unset">6.
                                                                                                Enter Your Bkash PIN to
                                                                                                confirm
                                                                                            </li>
                                                                                        </ul>
                                                                                        <ul style="list-style:none;display:none"
                                                                                            id="nagadsetps">
                                                                                            <li style="float:unset">You
                                                                                                need to payment this
                                                                                                Nagad number 01677515579
                                                                                            </li>
                                                                                            <li style="float:unset"></li>
                                                                                            <li style="float:unset">
                                                                                                Step:
                                                                                            </li>
                                                                                            <li style="float:unset">1.
                                                                                                Dial *167# to go mobile
                                                                                                menu.
                                                                                            </li>
                                                                                            <li style="float:unset">2.
                                                                                                Enter "2" to select send
                                                                                                money option
                                                                                            </li>
                                                                                            <li style="float:unset">3.
                                                                                                Enter "01677515579" and
                                                                                                press send
                                                                                            </li>
                                                                                            <li style="float:unset">4.
                                                                                                Enter Amount "<span
                                                                                                    id="finaltotal12"><strong
                                                                                                        id="finaltotal12">BDT 20</strong></span>"
                                                                                            </li>
                                                                                            <li style="float:unset">5.
                                                                                                Enter Reference "12"
                                                                                            </li>
                                                                                            <li style="float:unset">6.
                                                                                                Enter Your Bkash PIN to
                                                                                                confirm
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <ul class="card-action-buttons mt-1">
                                                                    <li style="padding:0px;border:none;margin-right:5px">
                                                                        <a href="javascript:void(0)"
                                                                           data-bs-toggle="modal"
                                                                           data-bs-target="#previewModel{{$key}}"
                                                                           class="btn-floating waves-effect waves-light "
                                                                           style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li
                                                                        style="padding:0px;border:none;margin-right:5px;">
                                                                        @if(!$design->invoice)
                                                                            @if($key < 9 || $design->status == 'approved')
                                                                                <a href="{{route('admin.invoice.active',$design->id)}}"
                                                                                   class="btn-floating waves-effect waves-light "
                                                                                   style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                                                    <i class="fa fa-plus"
                                                                                       style="color:#000"></i>
                                                                                </a>
                                                                            @else
                                                                                <a href="javascript:void(0)"
                                                                                   data-bs-toggle="modal"
                                                                                   data-bs-target="#purchaseModal{{$key}}"
                                                                                   class="btn-floating waves-effect waves-light "
                                                                                   style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                                                    <i class="fa fa-solid fa-crown"></i>
                                                                                </a>
                                                                            @endif
                                                                        @else
                                                                            <a href="{{route('admin.invoice.active',$design->id)}}"
                                                                               class="themeactive btn-floating waves-effect waves-light text-light"
                                                                               style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                                                <i class="fa fa-check"
                                                                                   style="color:#fff"></i>
                                                                            </a>
                                                                        @endif
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <!--</div>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{--<?php
                                    $designss=DB::table('designs')->where('store_id',$store_id)->first();
                                ?>
                                @if(isset($designs) && count($designs)>0)
                                    @foreach($designs as $key=>$dsg)
                                            <?php $header1 = DB::table('designs')->where('store_id',
                                            $store_id)->where('invoice', $dsg->value)->first(); ?>
                                        @if(isset($designss) && $designss->invoice == $dsg->value)
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 themes">
                                                <div class="row">
                                                    <div class="col l4 m8 s12 offset-m2 offset-l4">
                                                        <div class="product-card">
                                                            <div class="card z-depth-4">
                                                                <div class="card-image">
                                                                    <img
                                                                        src="{{URL::to('/')}}/assets/images/design/{{$dsg->image}}"
                                                                        width="100%" height="250" alt="product-img">
                                                                </div>
                                                                @if($key > 9)
                                                                    <span style="position:absolute;padding:10px">
                                                                        <div class="a1">
                                                                          <i class="fa fa-solid fa-crown"></i>
                                                                          <div class="a2"><span style="font-size:12px">Premium</span></div>
                                                                        </div>
                                                                        <img src="{{asset('img/permium.png')}}" width="60">
                                                                    </span>
                                                                @endif
                                                                <div class="row mt-0 themeviewdiv mb-3">
                                                                    <div class="col-md-6">
                                                                        <span>{{ Str::limit($dsg->name, 20) }}</span>
                                                                    </div>
                                                                    <!--Modal -->
                                                                    <div class="modal fade" id="exampleModal{{$key}}"
                                                                         tabindex="-1"
                                                                         aria-labelledby="exampleModalLabel"
                                                                         aria-hidden="true">
                                                                        <div class="modal-dialog modal-xl">
                                                                            <div class="modal-content"
                                                                                 style="background-color:transparent;border:0px">

                                                                                <div class="modal-body"
                                                                                     style="border:none">
                                                                                    <button class="btn btn-danger sm"
                                                                                            onclick="modalRE({{ $key }})"
                                                                                            style="float: right; margin: 0px 8px;">
                                                                                        X
                                                                                    </button>
                                                                                    @if(isset($dsg->image))
                                                                                        <img
                                                                                            src="{{URL::to('/')}}/assets/images/design/{{$dsg->image}}"
                                                                                            class="img-fluid" alt=""
                                                                                            style="padding:0px 10px;border:0px solid gray;transition-delay: 5s;">
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <ul class="card-action-buttons mt-1">
                                                                            <li style="padding:0px;border:none;margin-right:5px">
                                                                                <a href="javascript:void(0)"
                                                                                   data-bs-toggle="modal"
                                                                                   data-bs-target="#exampleModal{{$key}}"
                                                                                   class="btn-floating waves-effect waves-light "
                                                                                   style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </a>
                                                                            </li>
                                                                            <li @if(isset($designss) && $designss->invoice==$dsg->value) class=""
                                                                                @endif style="padding:0px;border:none;margin-right:5px;">
                                                                                <a href="{{route('admin.invoice.active',$dsg->id)}}"
                                                                                   class="themeactive btn-floating waves-effect waves-light text-light"
                                                                                   style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                                                    <i class="fa fa-check"
                                                                                       style="color:#fff"></i>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <!--</div>-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                    @foreach($designs as $key=>$dsg)
                                            <?php
                                            $header1 = DB::table('designs')->where('store_id',
                                                $store_id)->where('invoice', $dsg->value)->first();
                                            $inv = DB::table('invoicepurchases')->where('store_id',
                                                $store_id)->where('invoice_id', $dsg->id)->where('status',
                                                'approved')->first();
                                            ?>
                                        @if(isset($designss) && $designss->invoice != $dsg->value)
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 themes">
                                                <div class="row">
                                                    <div class="col l4 m8 s12 offset-m2 offset-l4">
                                                        <div class="product-card">
                                                            <div class="card z-depth-4">
                                                                <div class="card-image">

                                                                    <img
                                                                        src="{{URL::to('/')}}/assets/images/design/{{$dsg->image}}"
                                                                        width="100%" height="250" alt="product-img">

                                                                </div>
                                                                @if($key < 9 || isset($inv))
                                                                @else
                                                                    <div class="badge-overlay">
                                                                        <span class="top-right badge red">Paid</span>
                                                                    </div>
                                                                @endif
                                                                @if($key > 8)
                                                                    <span style="position:absolute;padding:10px">
                                        <div class="a1">
                                          <i class="fa fa-solid fa-crown"></i>
                                            <!--<div class="a2"><span style="font-size:12px;">Premium</span></div>-->
                                        </div>
                                        <!--<img src="{{asset('img/permium.png')}}" width="60" >-->
                                        </span>
                                                                @endif
                                                                <!--<div class="d-flex  flex-wrap flex-row  justify-content-sm-between justify-content-md-between justify-content-lg-between justify-content-xl-between mt-1">-->
                                                                <div class="row mt-0 themeviewdiv mb-3">
                                                                    <div class="col-md-6">
                                                                        <!--<span class="card-title">-->
                                                                        <span>{{ Str::limit($dsg->name, 20) }}</span>
                                                                        <!--</span>-->
                                                                    </div>
                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="exampleModal{{$key}}"
                                                                         tabindex="-1"
                                                                         aria-labelledby="exampleModalLabel"
                                                                         aria-hidden="true">
                                                                        <div class="modal-dialog modal-xl">
                                                                            <div class="modal-content"
                                                                                 style="background-color:transparent;border:0px">

                                                                                <div class="modal-body"
                                                                                     style="border:none">
                                                                                    @if(isset($dsg->image))
                                                                                        <img
                                                                                            src="{{URL::to('/')}}/assets/images/design/{{$dsg->image}}"
                                                                                            class="img-fluid" alt=""
                                                                                            style="padding:10px;border:0px solid gray;transition-delay: 5s;">
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Modal Invoice -->
                                                                    <div class="modal fade" id="exampleModali{{$key}}"
                                                                         tabindex="-1"
                                                                         aria-labelledby="exampleModalLabel"
                                                                         aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                    <?php
                                                                                    $invs = DB::table('invoicepurchases')->where('invoice_id',
                                                                                        $dsg->id)->where('store_id',
                                                                                        $store_id)->where('status',
                                                                                        'pending')->first();
                                                                                    ?>
                                                                                @if(isset($invs))
                                                                                    <div class="model-body"
                                                                                         style="display:flex;align-items:center;height:200px;">
                                                                                        <p style="padding:20px;text-align:center">
                                                                                            You Already Submit Payment
                                                                                            For this invoice design.
                                                                                            please wait a patient, after
                                                                                            verifying your payment it
                                                                                            will automatically
                                                                                            activate.</p>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="modal-header">
                                                                                        <table
                                                                                            class="table table-striped"
                                                                                            width="50%">
                                                                                            <tr>
                                                                                                <th style="text-align:start">
                                                                                                    Invoice name
                                                                                                    : {{$dsg->name ?? ""}}</th>
                                                                                                <td>BDT 20</td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <form method="post"
                                                                                                      action="{{route('admin.buyinvoice')}}">
                                                                                                    @csrf
                                                                                                    <input type="hidden"
                                                                                                           name="invoice_id"
                                                                                                           value="{{$dsg->id}}">
                                                                                                    <div
                                                                                                        class="custom-control custom-radio">
                                                                                                        <input
                                                                                                            type="radio"
                                                                                                            id="html"
                                                                                                            name="paymentMethod"
                                                                                                            value="bkash"
                                                                                                            checked>
                                                                                                        <label
                                                                                                            for="html">Bkash</label><br>
                                                                                                        <input
                                                                                                            type="radio"
                                                                                                            id="css"
                                                                                                            name="paymentMethod"
                                                                                                            value="nagad">
                                                                                                        <label
                                                                                                            for="css">Nagad</label><br>
                                                                                                    </div>
                                                                                                    <div class="">
                                                                                                        <label>Number</label>
                                                                                                        <br>
                                                                                                        <input
                                                                                                            id="credit"
                                                                                                            name="number"
                                                                                                            type="tel"
                                                                                                            class="form-control custom-control-input"
                                                                                                            required>
                                                                                                    </div>
                                                                                                    <div class="">
                                                                                                        <label>Transaction
                                                                                                            Id</label>
                                                                                                        <input
                                                                                                            id="credit"
                                                                                                            name="transaction_id"
                                                                                                            type="text"
                                                                                                            class="form-control custom-control-input"
                                                                                                            required>
                                                                                                    </div>
                                                                                                    <button
                                                                                                        type="submit"
                                                                                                        class="btn btn-primary mt-2">
                                                                                                        Submit
                                                                                                    </button>
                                                                                                </form>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <ul style="list-style:none"
                                                                                                    id="bkashsetps">
                                                                                                    <li style="float:unset">
                                                                                                        You need to
                                                                                                        payment this
                                                                                                        Bkash number
                                                                                                        01677515579
                                                                                                    </li>
                                                                                                    <li style="float:unset"></li>
                                                                                                    <li style="float:unset">
                                                                                                        Step:
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        1. Dial *247# to
                                                                                                        go mobile menu.
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        2. Enter "1" to
                                                                                                        select send
                                                                                                        money option
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        3. Enter
                                                                                                        "01677515579"
                                                                                                        and press send
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        4. Enter Amount
                                                                                                        "<span
                                                                                                            id="finaltotal1"><strong
                                                                                                                id="finaltotal1">BDT 20</strong></span>"
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        5. Enter
                                                                                                        Reference "123"
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        6. Enter Your
                                                                                                        Bkash PIN to
                                                                                                        confirm
                                                                                                    </li>
                                                                                                </ul>
                                                                                                <ul style="list-style:none;display:none"
                                                                                                    id="nagadsetps">
                                                                                                    <li style="float:unset">
                                                                                                        You need to
                                                                                                        payment this
                                                                                                        Nagad number
                                                                                                        01677515579
                                                                                                    </li>
                                                                                                    <li style="float:unset"></li>
                                                                                                    <li style="float:unset">
                                                                                                        Step:
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        1. Dial *167# to
                                                                                                        go mobile menu.
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        2. Enter "2" to
                                                                                                        select send
                                                                                                        money option
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        3. Enter
                                                                                                        "01677515579"
                                                                                                        and press send
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        4. Enter Amount
                                                                                                        "<span
                                                                                                            id="finaltotal12"><strong
                                                                                                                id="finaltotal12">BDT 20</strong></span>"
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        5. Enter
                                                                                                        Reference "12"
                                                                                                    </li>
                                                                                                    <li style="float:unset">
                                                                                                        6. Enter Your
                                                                                                        Bkash PIN to
                                                                                                        confirm
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <ul class="card-action-buttons mt-1">
                                                                            <li style="padding:0px;border:none;margin-right:5px">
                                                                                <a href="javascript:void(0)"
                                                                                   data-bs-toggle="modal"
                                                                                   data-bs-target="#exampleModal{{$key}}"
                                                                                   class="btn-floating waves-effect waves-light "
                                                                                   style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                                                    <i class="fa fa-eye"
                                                                                       style="color:#000"></i>
                                                                                </a>
                                                                            </li>
                                                                            @if($key < 9 || isset($inv))
                                                                                <li style="padding:0px;border:none;margin-right:5px">
                                                                                    <a href="{{route('admin.invoice.active',$dsg->id)}}"
                                                                                       class="btn-floating waves-effect waves-light "
                                                                                       style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                                                        <i class="fa fa-plus"
                                                                                           style="color:#000"></i>
                                                                                    </a>
                                                                                </li>
                                                                            @else
                                                                                <li style="padding:0px;border:none;margin-right:5px">
                                                                                    <a href="javascript:void(0)"
                                                                                       data-bs-toggle="modal"
                                                                                       data-bs-target="#exampleModali{{$key}}"
                                                                                       class="btn-floating waves-effect waves-light "
                                                                                       style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                                                        <i class="fa fa-solid fa-crown"></i>
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
@push('scripts')
    <script src="{{URL::to('/')}}/js/planpurchase.js"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })


        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }

        jQuery(document).ready(function ($) {
            //Buy button effects
            $(".buy").on("click", function () {
                //It is possible to put the 1st argument of setTimeout as callback of the Materialize.toast function but that approach seems significantly slower. I don't know why yet
                setTimeout(function () {
                    $("#buy").removeClass("green");
                    $(".buy").fadeOut(100, function () {
                        $(this).text("add_shopping_cart").fadeIn(150);
                    });
                }, 5000);

                $("#buy").addClass("green");
                $(".buy").fadeOut(100, function () {
                    $(this).text("check").fadeIn(150);
                });

                var $toastContent = $(
                    '<div class="flow-text">ORDERED! &nbsp <a href="#" class=" amber-text">MY CART</a></div>'
                );
                Materialize.toast($toastContent, 5000, "rounded");
            });

            //Like button effects

            $(".like").on("click", function () {
                setTimeout(function () {
                    $(".like").fadeOut(100, function () {
                        $(this).text("favorite_border").fadeIn(150);
                    });
                }, 5000);

                $(".like").fadeOut(100, function () {
                    $(this).text("favorite").fadeIn(150);
                });

                var $toastContent2 = $('<div class="flow-text">LIKED!</div>');
                Materialize.toast($toastContent2, 5000, "pink rounded");
            });
        });
    </script>

@endpush
