@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" src="{{ asset('admin/src/bootstrap-tagsinput.css') }}"/>
    <style>
        .zoom:hover {
            transform: scale(1.2);
        }

        /* Bottom right text */
        .text-block {
            width: 95.5%;
            position: absolute;
            bottom: 13px;
            background-color: #00020ccc;
            color: white;
            padding: 8px;
        }


        .card {
            border-radius: 0.3rem;
        }

        a {
            color: #f1593a;
            text-decoration: none;
        }

        .bootstrap-tagsinput {
            width: 100%;
        }

        .bootstrap-tagsinput {
            background-color: #fff;
            /*border: 1px solid #ccc;*/
            /*box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);*/
            display: inline-block;
            padding: 4px 6px;
            color: #555;
            vertical-align: middle;
            border-radius: 4px;
            max-width: 100%;
            line-height: 22px;
            cursor: text;
        }

        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white;
        }

        .label-info {
            background-color: #5bc0de;
        }

        .label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }

        .bootstrap-tagsinput .tag [data-role="remove"] {
            margin-left: 8px;
            cursor: pointer;
        }

        .bootstrap-tagsinput .tag [data-role="remove"]::after {
            content: "x";
            padding: 0px 2px;
        }

        .bootstrap-tagsinput .tag [data-role="remove"] {
            cursor: pointer;
        }

        .card {
            border: 1px solid rgba(222, 226, 230, 0.7);
        }

        .card .card-body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            padding: .5rem 1.5rem 1.5rem 1.5rem;
        }

        .card .card-header {
            padding: .5rem 1.5rem .5rem 1.5rem;
            border-bottom: 1px solid rgba(222, 226, 230, 0.7);
        }

        .size {
            list-style-type: none;

        }

        .size li {
            float: left;
        }

        .bootstrap-tagsinput {
            margin: 0;
            width: 100%;
            padding: 0.5rem 0.75rem 0;
            font-size: 1rem;
            line-height: 1.25;
            transition: border-color 0.15s ease-in-out;

            &.has-focus {
                background-color: #fff;
                border-color: #5cb3fd;
            }

            .label-info {
                display: inline-block;
                background-color: #636c72;
                padding: 0 .4em .15em;
                border-radius: .25rem;
                margin-bottom: 0.4em;
            }

            input {
                margin-bottom: 0.5em;
            }
        }

        .bootstrap-tagsinput .tag [data-role="remove"]:after {
            content: '\00d7';
        }

        .avatar-upload {
            position: relative;
            max-width: 205px;
            margin: 20px auto;
        }

        .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }

        .avatar-edit input {
            display: none;
        }

        .avatar-edit label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all .2s ease-in-out;
        }

        .avatar-edit label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .avatar-edit label:after {
            content: "\f040";
            font-family: 'FontAwesome';
            color: #757575;
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
            text-align: center;
            margin: auto;
        }


        .modal {
            top: 0%;
            left: 0 !important;
        }

        .show {
            background-color: #000000cc !important;
            opacity: 1;
        }


        .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }

        .avatar-preview > div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        @media only screen and (max-width: 768px) {
            .modal {
                position: fixed;
                top: 0;
                left: 0% !important;
                z-index: 1050;
                display: none;
                width: 100%;
                height: 100%;
                overflow-x: hidden;
                overflow-y: auto;
                outline: 0;
            }

        }

        @media only screen and (max-width: 1024px) and (min-width: 769px) {
            .modal {
                position: fixed;
                top: 0;
                left: 10% !important;
                z-index: 1050;
                display: none;
                width: 100%;
                height: 100%;
                overflow-x: hidden;
                overflow-y: auto;
                outline: 0;
            }

        }

        @media only screen and (max-width: 1440px) and (min-width: 1025px) {
            .modal {
                position: fixed;
                top: 0;
                left: 20% !important;
                z-index: 1050;
                display: none;
                width: 100%;
                height: 100%;
                overflow-x: hidden;
                overflow-y: auto;
                outline: 0;
            }

        }

        .modal {
            position: fixed;
            top: 0;
            left: 34%;
            z-index: 1050;
            display: none;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
        }

        .test:hover .modal {
            display: block !important;
        }

        .show {
            background-color: transparent;
            opacity: 1;
        }
    </style>
@endpush
@section('content')
    @php
        $userData = getUserData();
        $store_id = $userData['store_id'] ?? 0;

        $store = DB::table('stores')->where("id",$store_id)->first();
        $plan_id = isset($store) ? $store->plan_id : null;
    @endphp
    <main class="main-content position-relative h-100" style="padding: 0px 15px;">

        <!--addon nav bar component-->
        @include('admin.addon.share.addons-nav')

        <!--Main Section-->
        <section class="content-main">
            <div class="row">
                <!--header-->
                <div class="col-md-12">
                    <h2 class="content-title" style="padding: 15px 0px;">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            মডুলাস
                        @else
                            Modulus
                        @endif
                    </h2>
                </div>

                {{--modulus list--}}
                @foreach ($modulus as $key => $item)
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <strong class="mb-0 h6 text-center"> {{ $item->name }}</strong>

                                {{--checking price status and payment status--}}
                                @if ($item->priceStatus == 1)
                                    @if ($item->paymentStatus == 1)
                                        {{--if modulus is advance payment --}}
                                        @if ($item->id == '106')
                                            @if ($advanceHeaderSetting->bkash != 'active')
                                                <div id="advanceButton"
                                                     class="form-check form-switch is-filled mb-0 p-0 pt-2"
                                                     style="float: right;">
                                                    <input id="advanceCheckbox" class="form-check-input"
                                                           type="checkbox">
                                                </div>
                                            @else

                                                {{--is applicable status toggle for only advance payment--}}
                                                <div class="form-check form-switch is-filled mb-0 p-0 pt-2"
                                                     style="float: right;">
                                                    @if ($plan_id && in_array($plan_id, [6, 9]))
                                                        <input class="form-check-input" type="checkbox"
                                                               onchange="trialAlert(event)"
                                                               style="margin:0 auto;">
                                                    @else
                                                        <input class="form-check-input switchstatus" type="checkbox"
                                                               id="flexSwitchCheckChecked" data-value="{{ $item->id }}"
                                                               data-id="{{ $store_id }}" style="margin:0 auto;"
                                                               @if (ModulusStatus($store_id, $item->id)) checked @endif>
                                                    @endif
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            @endif
                                        @else

                                            {{--is applicable status toggle for only payment modulus--}}
                                            <div class="form-check form-switch is-filled mb-0 p-0 pt-2"
                                                 style="float: right;">
                                                @if ($plan_id && in_array($plan_id, [6, 9]))
                                                    <input class="form-check-input" type="checkbox"
                                                           onchange="trialAlert(event)"
                                                           style="margin:0 auto;">
                                                @else
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                           id="flexSwitchCheckChecked" data-value="{{ $item->id }}"
                                                           data-id="{{ $store_id }}" style="margin:0 auto;"
                                                           @if (ModulusStatus($store_id, $item->id)) checked @endif>
                                                @endif
                                                <label class="form-check-label"
                                                       for="flexSwitchCheckChecked"></label>
                                            </div>
                                        @endif
                                    @else

                                        {{--payment modal toggle button--}}
                                        <div class="form-check form-switch is-filled mb-0 p-0" style="float: right;">
                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                               data-bs-target="#exampleModali{{ $key }}"
                                               class="btn-floating waves-effect waves-light "
                                               style="padding: 10px 0px;border: 1px solid #5e4c4c33;width: 50px;">
                                                <img src="{{ asset("img/king.png") }}" alt="Buy" style="width: inherit">
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    {{--is applicable status toggle except advance payment--}}
                                    <div class="form-check form-switch is-filled mb-0 p-0 pt-2" style="float: right;">
                                        @if ($plan_id && in_array($plan_id, [6, 9]))
                                            <input class="form-check-input" type="checkbox" onchange="trialAlert(event)"
                                                   style="margin:0 auto;">
                                        @else
                                            <input class="form-check-input switchstatus" type="checkbox"
                                                   id="flexSwitchCheckChecked" data-value="{{ $item->id }}"
                                                   data-id="{{ $store_id }}" style="margin:0 auto;"
                                                   @if (ModulusStatus($store_id, $item->id)) checked @endif>
                                        @endif
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body text-center p-0" style="position: relative; overflow: hidden;">
                                <img src="{{ asset('modulus/' . $item->image) }}" class="zoom" width="100%">

                                {{--checking price status and payment status and show title--}}
                                @if ($item->priceStatus == 1)
                                    @if ($item->paymentStatus == 1)
                                        <div class="text-block">
                                            @if ($plan_id && in_array($plan_id, [6, 9]))
                                                <span>{!! $item->title !!} @if($item->config_status)
                                                        <a href="javascript:void(0)" onclick="trialAlert(event)">Configure Now</a>
                                                    @endif</span>
                                            @else
                                                <span>{!! $item->title !!} @if($item->config_status)
                                                        <a href="{{ route('admin.modulus.config', ['id' => $item->id]) }}">Configure Now</a>
                                                    @endif</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-block cursor-pointer" data-bs-toggle="modal"
                                             data-bs-target="#exampleModali{{ $key }}">
                                            <span>Buy Now</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-block">
                                        @if ($plan_id && in_array($plan_id, [6, 9]))
                                            <span>{!! $item->title !!} @if($item->config_status)
                                                    <a href="javascript:void(0)" onclick="trialAlert(event)">Configure Now</a>
                                                @endif</span>
                                        @else
                                            <span>{!! $item->title !!} @if($item->config_status)
                                                    <a href="{{ route('admin.modulus.config', ['id' => $item->id]) }}">Configure Now</a>
                                                @endif </span>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    {{--payment modal--}}
                    <div class="modal fade" id="exampleModali{{ $key }}" tabindex="-1"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg mt-4">
                            <div class="modal-content">
                                @php
                                    $invs = DB::table('modulus_payments')
                                            ->where('modulus_id', $item->id)
                                            ->where('store_id', $store_id)
                                            ->where('status', null)
                                            ->first();
                                @endphp

                                {{--check payment is made or not--}}
                                @if (isset($invs))
                                    <div class="model-body" style="display:flex;align-items:center;height:200px;">
                                        <p style="padding:20px;text-align:center">You Already Submit Payment For this
                                            invoice design. please wait a patient, after verifying your payment it will
                                            automatically activate.
                                        </p>
                                    </div>
                                    {{--not paid for modulus--}}
                                @else
                                    <div class="modal-header">
                                        <table class="table table-striped" width="50%">
                                            <tr>
                                                <th style="text-align:start">Modulus Name: {{ $item->name ?? '' }}</th>
                                                <td>BDT <span
                                                        class="modulePrice{{$key}}">{{ $item->price }}</span>
                                                </td>
                                            </tr>
                                            @if($item->id == 121)
                                                <tr>
                                                    <td style="text-align:start">Number of product</td>
                                                    <td>
                                                        <input id="numberOfProduct" class="form-control" type="number"
                                                               min="1" step="1"
                                                               onchange="changeModulePrice('{{$key}}', '{{$item->price}}', '{{$item->price_usd}}')"
                                                               value="1"/>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">

                                            {{--create modulus payment input field secton--}}
                                            <div class="col-md-12">
                                                <form id="buyModulusForm_{{ $item->id }}" method="post"
                                                      action="{{ route('admin.modulus.payment') }}">
                                                    @csrf
                                                    <input type="hidden" name="modulus_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="amount" class="modulePrice{{$key}}"
                                                           value="{{ $item->price }}">
                                                    <input type="hidden" name="amount_usd"
                                                           class="moduleUsdPrice{{$key}}"
                                                           value="{{ $item->price_usd }}">
                                                    <input type="hidden" name="payment_method" value=""
                                                           id="payment_method_{{$item->id}}">
                                                    <input type="hidden" name="total_product" class="total_product"
                                                           value="1">
                                                </form>

                                                <div class="m-auto" style="width: fit-content;">
                                                    <button name="payment_method" type="submit"
                                                            class="btn btn-primary mt-3"
                                                            value="bkash"
                                                            onclick="handlePaymentMethod('bkash',{{$item->id}})">
                                                        Pay with Bkash
                                                    </button>
                                                    <button name="payment_method" type="submit"
                                                            class="btn btn-primary mt-3"
                                                            value="nagad"
                                                            onclick="handlePaymentMethod('nagad',{{$item->id}})">
                                                        Pay with Nagad
                                                    </button>

                                                    {{--                                                    <button name="payment_method" type="submit"--}}
                                                    {{--                                                            class="btn btn-primary mt-3"--}}
                                                    {{--                                                            value="amarpay"--}}
                                                    {{--                                                            onclick="handlePaymentMethod('amarpay',{{$item->id}})">--}}
                                                    {{--                                                        Card Payment--}}
                                                    {{--                                                    </button>--}}

                                                    <button name="payment_method" type="submit"
                                                            class="btn mt-3"
                                                            style="background-color: #ffc439"
                                                            value="paypal"
                                                            onclick="handlePaymentMethod('paypal',{{$item->id}})">
                                                        <img width="100px" src="{{ asset("img/pngegg.png") }}"
                                                             alt="paypal">
                                                    </button>

                                                    <button name="payment_method" type="submit"
                                                            class="btn btn-primary mt-3"
                                                            value="manual"
                                                            onclick="handlePaymentMethod('manual',{{$item->id}})">
                                                        Manual Payment
                                                    </button>
                                                </div>

                                            </div>

                                            {{-- payment details --}}
                                            <div class="row d-flex justify-content-center">
                                                <div class="col-md-6"
                                                     id="nagadFormDiv_{{$item->id}}" style="display: none">
                                                    <div class="col-md-12">
                                                        <form method="post" action="{{ route('admin.modulus.but') }}">
                                                            @csrf
                                                            <input type="hidden" name="modulus_id"
                                                                   value="{{ $item->id }}">
                                                            <input type="hidden" name="store_id"
                                                                   value="{{ $store_id }}">
                                                            <input type="hidden" name="price"
                                                                   class="modulePrice{{$key}}"
                                                                   value="{{ $item->price }}">
                                                            <input type="hidden" name="total_product"
                                                                   class="total_product"
                                                                   value="1">

                                                            <div class="mt-1">
                                                                <label>Payment Method</label>
                                                                <select class="form-control" name="payment_method"
                                                                        id="paymentMethod<?php echo e($item->id, false); ?>"
                                                                        onchange="handleManualPaymentChange(<?php echo e($item->id, false); ?>)">
                                                                    <option value="bkash_manual">Bkash</option>
                                                                    <option value="nagad_manual">Nagad</option>
                                                                    <option value="rocket_manual">Rocket</option>
                                                                    <option value="hand_cash">Hand Cash</option>
                                                                </select>
                                                            </div>

                                                            <div id="transactionInfo<?php echo e($item->id, false); ?>"
                                                                 class="mt-2">
                                                                <div class="mt-1">
                                                                    <label>Number</label>
                                                                    <input name="number" type="text"
                                                                           class="form-control"
                                                                           pattern="[0-9]{11}">
                                                                </div>
                                                                <div class="mt-1">
                                                                    <label>Transaction Id</label>
                                                                    <input name="transaction_id" type="text"
                                                                           class="form-control">
                                                                </div>
                                                            </div>

                                                            <button type="submit" class="btn btn-primary mt-2">Submit
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <!--<script src="{{ asset('admin/src/bootstrap-tagsinput.js') }}"></script>-->
    <!--<script src="{{ asset('admin/src/bootstrap-tagsinput-angular.js') }}"></script>-->
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    {{--    configuration page access denied--}}
    @if(session('configError'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Retrieve the error message from Blade template
                var errorMessage = @json(session('configError'));

                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage
                    });
                }
            });
        </script>
    @endif
    {{--tab open for advance payment--}}
    <script>
        $("#advanceButton").on("click", function () {
            Swal.fire({
                title: 'Please... Active Bkash Payment Method First!',
                icon: 'error',
                confirmButtonText: 'OK',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("advanceCheckbox").disabled = true;
                    window.location.reload();
                }
            });
        });
    </script>


    {{--enable status toggle handler--}}
    <script>
        $(document).ready(function () {
            handleManualPaymentChange();

            $(".switchstatus").on("change", function (event) {
                {{--const storePlane = "{{ $plan_id ?? "" }}";--}}
                    {{--if (storePlane != "" && (storePlane == 6 || storePlane == 9)) {--}}
                    {{--    swal.fire(--}}
                    {{--        'Warning!',--}}
                    {{--        "You are in trial Mode",--}}
                    {{--        'warning'--}}
                    {{--    );--}}
                    {{--    event.preventDefault(); // Stop the default action of the switch--}}
                    {{--    $(this).prop("checked", !$(this).prop("checked"));--}}

                    {{--    return false;--}}
                    {{--}--}}

                    $url = "/modulus/change-status";
                var value = $(this).val();
                var dataValue = $(this).data('value');
                var id = $(this).data('id');
                // console.log(value, dataValue, id)
                $.get($url, {
                    value: value,
                    modulus_id: dataValue,
                    id: id
                }, function (data) {
                    if (data.status) {
                        swal.fire(
                            'success!',
                            data.message + " 🥱",
                            'success'
                        ).then(function () {
                            location.reload();
                        });
                    } else {
                        var isChecked = $(this).is(':checked');

                        if (isChecked) {
                            $(".switchstatus").prop('checked', false);
                        } else {
                            $(".switchstatus").prop('checked', true);
                        }

                        swal.fire(
                            'Warning!',
                            data.message + " 🥱",
                            'warning'
                        );
                    }
                });
            });
        });


        const handleManualPaymentChange = (id = "") => {
            const paymentMethod = $("#paymentMethod" + id).val();

            if (paymentMethod === "hand_cash") {
                $("#transactionInfo" + id).hide();
            } else {
                $("#transactionInfo" + id).show();
            }
        }

        const handlePaymentMethod = (payment_method, id) => {
            $("#payment_method_" + id).val(payment_method);

            if (payment_method === "manual") {
                $("#nagadFormDiv_" + id).show();
            } else {
                $("#nagadFormDiv_" + id).hide();
                $("#buyModulusForm_" + id).submit();
            }

        };

        const changeModulePrice = (key, price, priceUsd) => {
            let numberOfProduct = $("#numberOfProduct").val() || 1;
            const amount = parseFloat(price) * parseInt(numberOfProduct);
            const amountUsd = parseFloat(priceUsd) * parseInt(numberOfProduct);

            $(".total_product").val(numberOfProduct);
            $(".modulePrice" + key).html(amount);
            $(".moduleUsdPrice" + key).html(amountUsd);
        };

        const trialAlert = (event) => {
            event.preventDefault(); // Stop the default action of the switch
            const switchStatus = $(event.currentTarget); // Reference to the switch

            switchStatus.prop("checked", !switchStatus.prop("checked"));

            swal.fire(
                'Warning!',
                "You are in trial Mode",
                'warning'
            );

            return false;
        }

        /*modal toggle*/
        $(function () {

            $('[data-toggle="modal"]').hover(function () {
                var modalId = $(this).data('target');
                $(modalId).modal('show');
                $(modalId).css({
                    opacity: 1
                });

            });

        });


    </script>
@endpush
