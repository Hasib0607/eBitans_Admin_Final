@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" src="{{ asset('admin/src/bootstrap-tagsinput.css') }}"/>

    {{--module config styles--}}
    <style>
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
            color: #ff5733;
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

        .dangerError {
            color: #F44335 !important;
            font-size: 14px;
            margin-top: 5px;
            margin-left: 8px;
        }

        /*button group*/
        .input-group {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .input-group input {
            flex: 1;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group button {
            margin-bottom: 0px;
            padding: 11px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            background-color: #ff5722; /* Adjust color */
            border-color: #ff5722;
            color: white;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
            box-shadow: none;
            font-size: 12px !important;
        }

        .input-group button:hover {
            background-color: #e64a19;
            font-size: 12px;
        }

        .form-check-inline {
            width: 100%;
        }

    </style>
@endpush
@section('content')

    {{--modulus config main section--}}
    <main class="main-content position-relative h-100" style="padding: 0px 15px;">

        {{--setting nav component section--}}
        @include('admin.addon.share.addons-nav')

        {{--modulus content section--}}
        <section class="content-main">
            <div class="row">

                {{--modulus config header--}}
                <div class="col-md-12">
                    <h2 class="content-title" style="padding: 15px 0px;">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            মডুলাস
                        @else
                            Modulus
                        @endif
                    </h2>
                </div>

                {{--config card section--}}
                <div class="col-md-12 config">
                    @switch($ok)
                        @case(5)
                            {{--Facebook Login Credential Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Facebook Login Credential</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal" action="{{ route('admin.quick.login.info.store') }}"
                                          method="POST">
                                        @csrf
                                        <div class="form-group row">
                                            <input type="hidden" name="modulus_id" value="5">
                                            <input type="hidden" name="type" value="facebook">
                                            <div class="col-lg-3">
                                                <label class="col-from-label">App ID</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control" name="app_id"
                                                       value="{{ $credential->app_id ?? '' }}"
                                                       placeholder="Facebook Client ID">
                                            </div>
                                        </div>

                                        <div class="form-group mb-0 text-right">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @break
                        @case(6)
                            {{--Google Login Credential Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Google Login Credential</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal" action="{{ route('admin.quick.login.info.store') }}"
                                          method="POST">
                                        @csrf
                                        <input type="hidden" name="modulus_id" value="6">
                                        <input type="hidden" name="type" value="google">
                                        <div class="form-group row">
                                            <div class="col-lg-3">
                                                <label class="col-from-label">Client ID</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control" name="client_id"
                                                       value="{{ $credential->client_id ?? '' }}"
                                                       placeholder="Google Client ID">
                                            </div>
                                        </div>

                                        <div class="form-group mb-0 text-right">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @break
                        @case(7)
                            {{--Messenger Credential Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Messenger Credential</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal" action="{{ route('admin.quick.login.info.store') }}"
                                          method="POST">
                                        @csrf
                                        <div class="form-group row">
                                            <input type="hidden" name="modulus_id" value="7">
                                            <input type="hidden" name="type" value="messenger">
                                            <div class="col-lg-3">
                                                <label class="col-from-label">App ID</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control" name="client_id"
                                                       value="{{ $credential->client_id ?? '' }}"
                                                       placeholder="Messenger Client ID">
                                            </div>
                                        </div>

                                        <div class="form-group mt-2 row">
                                            <div class="col-lg-3">
                                                <label class="col-from-label">Page ID</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control" name="client_secret"
                                                       value="{{ $credential->client_secret ?? '' }}"
                                                       placeholder="Facebook Client Secret">
                                            </div>
                                        </div>
                                        <div class="form-group mb-0 text-right">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @break
                        @case(10)
                            {{--Google Analytics & Search Console Config--}}
                            <div class="card">
                                <form class="form-horizontal" action="{{ route('admin.quick.login.info.store') }}"
                                      method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card-header">
                                                <h5 class="mb-0 h6">Google Analytic</h5>
                                            </div>
                                            <div class="card-body">

                                                <input type="hidden" name="modulus_id" value="10">
                                                <input type="hidden" name="type"
                                                       value="Google Analytics & Search Console">
                                                <div class="form-group">
                                                    <input type="text" name="google_analytics" id=""
                                                           class="form-control"
                                                           placeholder="Google Analytics Steam ID"
                                                           value="{{ $credential->google_analytics ?? '' }}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-header">
                                                <h5 class="mb-0 h6">Google Search Console </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input type="text" name="google_search_console" id=""
                                                           class="form-control"
                                                           placeholder="Google Search Console HTML Tag ID"
                                                           value="{{ $credential->google_search_console ?? '' }}"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card-header">
                                                <h5 class="mb-0 h6">Google Tag Manager </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input type="text" name="google_tag_manager" id=""
                                                           class="form-control"
                                                           placeholder="Google Tag Manage ID"
                                                           value="{{ $credential->google_tag_manager ?? '' }}"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            @break
                        @case(11)
                            {{--Facebook Pixel Config--}}
                            <div class="card">
                                <form class="form-horizontal" action="{{ route('admin.quick.login.info.store') }}"
                                      method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card-header">
                                                <h5 class="mb-0 h6">Facebook Pixel</h5>
                                            </div>
                                            <div class="card-body">

                                                <input type="hidden" name="modulus_id" value="11">
                                                <input type="hidden" name="type" value="Facebook Pixel">
                                                <div class="form-group">
                                                    <label for="facebook_pixel" class="form-label">Facebook Pixel
                                                        ID</label>
                                                    <input type="text" name="facebook_pixel" id="facebook_pixel"
                                                           class="form-control"
                                                           placeholder="Facebook Pixel ID"
                                                           value="{{ $credential->facebook_pixel ?? '' }}"/>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="general_access_token" class="form-label">General Access
                                                        Token For Conversation API</label>
                                                    <input type="text" name="general_access_token"
                                                           id="general_access_token"
                                                           class="form-control"
                                                           placeholder="General Access Token For Conversation API"
                                                           value="{{ $credential->general_access_token ?? '' }}"/>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="test_event_code" class="form-label">Test Event
                                                        Code</label>
                                                    <input type="text" name="test_event_code"
                                                           id="test_event_code"
                                                           class="form-control"
                                                           placeholder="Test Event Code"
                                                           value="{{ $credential->test_event_code ?? '' }}"/>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="test_event_code" class="form-label">Domain verification
                                                        Code</label>
                                                    <input type="text" name="domain_verification_code"
                                                           id="domain_verification_code"
                                                           class="form-control"
                                                           placeholder="Domain Verification Code"
                                                           value="{{ $credential->domain_verification_code ?? '' }}"/>
                                                </div>
                                            </div>
                                            <div class="form-group" style="text-align: right;">
                                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                            @break
                        @case(106)
                            {{--Pre payment Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Pre-Payment</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal" action="{{ route('admin.pre.payment.config') }}"
                                          method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $ap->id }}">
                                        <div class="form-group row" id="prepayment_input"
                                             @if($ap->payment_type == 2)style="display: none" @endif>
                                            <div class="col-lg-3">
                                                <label class="col-from-label">Pre-Payment Amount</label>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="number" class="form-control" name="prepayment"
                                                       value="{{ $ap->prepayment ?? '' }}"
                                                       placeholder="Pre-Payment Amount">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-lg-3">
                                                <label class="col-from-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ছাড়ের ধরন
                                                    @else
                                                        Payment Type
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                            </div>
                                            <div class="col-md-7">
                                                <select class="form-control" name="payment_type" id="payment_type"
                                                        onchange="prePaymentSelect()">
                                                    <option value="null">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            প্রকার নির্বাচন করুন
                                                        @else
                                                            Select Type
                                                        @endif
                                                    </option>
                                                    <option value="0" {{ $ap->payment_type == 0 ? 'selected' : '' }}>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            ফিক্সড
                                                        @else
                                                            Fixed
                                                        @endif
                                                    </option>
                                                    <option value="1" {{ $ap->payment_type == 1 ? 'selected' : '' }}>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            পার্সেন্ট
                                                        @else
                                                            Percent
                                                        @endif
                                                    </option>
                                                    <option value="2" {{ $ap->payment_type == 2 ? 'selected' : '' }}>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            ডেলিভারি চার্জ
                                                        @else
                                                            Delivery charge
                                                        @endif
                                                    </option>
                                                </select>
                                                @error('payment_type')
                                                <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mt-3">
                                            <div class="col-lg-3">
                                                <label class="col-from-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        পেমেন্ট মেথড
                                                    @else
                                                        Payment Method
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="payment_method"
                                                               id="payment_method_cod"
                                                               value="cod"
                                                        {{ (isset($ap->payment_method) && $ap->payment_method == "cod" ) || (!isset($ap->payment_method) || is_null($ap->payment_method)) ? 'checked' : '' }}
                                                        ">
                                                        <label class="form-check-label" for="payment_method_cod">
                                                            {{ "Cash On Delivery" }}
                                                        </label>
                                                    </div>

                                                    @php
                                                        $paymentData = \App\Models\Paymentgateway::where('store_id', $store_id)->get();
                                                    @endphp

                                                    @if(isset($paymentData) && count($paymentData))
                                                        @foreach($paymentData as $item)
                                                            @if ((isset($item->payment_company) && $item->payment_company == "SSL") &&  !empty($item->ssl_store_id) && !empty($item->ssl_store_password))
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="payment_method"
                                                                           id="payment_method_d{{$item->payment_company}}"
                                                                           value="{{ $item->payment_company }}"
                                                                        {{ $ap->payment_method == $item->payment_company ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label"
                                                                           for="payment_method_d{{$item->payment_company}}">
                                                                        {{ $item->payment_company }}
                                                                    </label>
                                                                </div>
                                                            @endif

                                                            @if ((isset($item->payment_company) && $item->payment_company == "bKash") &&  !empty($item->app_key) && !empty($item->app_secret) && !empty($item->api_username) && !empty($item->api_password))
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="payment_method"
                                                                           id="payment_method_d{{$item->payment_company}}"
                                                                           value="{{ $item->payment_company }}"
                                                                        {{ $ap->payment_method == $item->payment_company ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label"
                                                                           for="payment_method_d{{$item->payment_company}}">
                                                                        {{ $item->payment_company }}
                                                                    </label>
                                                                </div>
                                                            @endif

                                                            @if ((isset($item->payment_company) && $item->payment_company == "Nagad") &&  !empty($item->merchant_id) && !empty($item->merchant_number) && !empty($item->public_key) && !empty($item->private_key))
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="payment_method"
                                                                           id="payment_method_d{{$item->payment_company}}"
                                                                           value="{{ $item->payment_company }}"
                                                                        {{ $ap->payment_method == $item->payment_company ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label"
                                                                           for="payment_method_d{{$item->payment_company}}">
                                                                        {{ $item->payment_company }}
                                                                    </label>
                                                                </div>
                                                            @endif

                                                            @if (ModulusStatus($store_id, 112))
                                                                @if ((isset($item->payment_company) && $item->payment_company == "stripe") && $item->status == "Accepted" && !empty($item->app_key) && !empty($item->app_secret))
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                               name="payment_method"
                                                                               id="payment_method_d{{$item->payment_company}}"
                                                                               value="{{ $item->payment_company }}"
                                                                            {{ $ap->payment_method == $item->payment_company ? 'checked' : '' }}
                                                                        >
                                                                        <label class="form-check-label"
                                                                               for="payment_method_d{{$item->payment_company}}">
                                                                            {{ $item->payment_company }}
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                            @endif

                                                            @if (ModulusStatus($store_id, 113))
                                                                @if ((isset($item->payment_company) && $item->payment_company == "paypal") && $item->status == "Accepted" && !empty($item->app_secret) && !empty($item->client_id))
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                               name="payment_method"
                                                                               id="payment_method_d{{$item->payment_company}}"
                                                                               value="{{ $item->payment_company }}"
                                                                            {{ $ap->payment_method == $item->payment_company ? 'checked' : '' }}
                                                                        >
                                                                        <label class="form-check-label"
                                                                               for="payment_method_d{{$item->payment_company}}">
                                                                            {{ $item->payment_company }}
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                            @endif

                                                            @if ((isset($item->payment_company) && $item->payment_company == "uddoktapay") && !empty($item->app_key) && $item->status == "Accepted")
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="payment_method"
                                                                           id="payment_method_d{{$item->payment_company}}"
                                                                           value="{{ $item->payment_company }}"
                                                                        {{ $ap->payment_method == $item->payment_company ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label"
                                                                           for="payment_method_d{{$item->payment_company}}">
                                                                        {{ $item->payment_company }}
                                                                    </label>
                                                                </div>
                                                            @endif

                                                        @endforeach
                                                    @endif

                                                    @php
                                                        $marchenPayment = \App\Models\MarchantPaymentGetway::where("store_id", $store_id)->where("status", 1)->get();
                                                    @endphp
                                                    @if(isset($marchenPayment) && count($marchenPayment))
                                                        @foreach($marchenPayment as $item)
                                                            @php
                                                                $payment_gatway = "ebitans_" . $item->payment_gatway;
                                                            @endphp
                                                            @if ((isset($item->payment_gatway) && $item->payment_gatway == "amarpay") && ModulusStatus($store_id, 125))
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="payment_method"
                                                                           value="{{ $payment_gatway }}"
                                                                           id="payment_method_e{{$payment_gatway}}"
                                                                        {{ $ap->payment_method == $payment_gatway ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label"
                                                                           for="payment_method_e{{$payment_gatway}}">
                                                                        {{ "Amar Pay" }}
                                                                    </label>
                                                                </div>
                                                            @endif
                                                            @if ((isset($item->payment_gatway) && $item->payment_gatway == "bkash") && ModulusStatus($store_id, 128))
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="payment_method"
                                                                           id="payment_method_e{{$payment_gatway}}"
                                                                           value="{{ $payment_gatway }}"
                                                                        {{ $ap->payment_method == $payment_gatway ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label"
                                                                           for="payment_method_e{{$payment_gatway}}">
                                                                        {{ "Ebitans Bkash" }}
                                                                    </label>
                                                                </div>
                                                            @endif
                                                            @if ((isset($item->payment_gatway) && $item->payment_gatway == "nagad") && ModulusStatus($store_id, 129))
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="payment_method"
                                                                           id="payment_method_e{{$payment_gatway}}"
                                                                           value="{{ $payment_gatway }}"
                                                                        {{ $ap->payment_method == $payment_gatway ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label"
                                                                           for="payment_method_e{{$payment_gatway}}">
                                                                        {{ "Ebitans Nagad" }}
                                                                    </label>
                                                                </div>
                                                            @endif
                                                            @if ((isset($item->payment_gatway) && $item->payment_gatway == "rocket") && ModulusStatus($store_id, 130))
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="payment_method"
                                                                           id="payment_method_e{{$payment_gatway}}"
                                                                           value="{{ $payment_gatway }}"
                                                                        {{ $ap->payment_method == $payment_gatway ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label"
                                                                           for="payment_method_e{{$payment_gatway}}">
                                                                        {{ "Ebitans Rocket" }}
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>

                                                @error('payment_method')
                                                <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group mb-0 text-right">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @break
                        @case(108)
                            {{--Custom From Credential Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Custom From Credential</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal" action="{{ route('admin.quick.login.info.store') }}"
                                          method="POST">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="radio-inline">
                                                    <input type="radio" name="from_type" value="single"
                                                        {{ $is_single === 1 ? 'checked' : '' }}> Single Page
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="from_type" value="double"
                                                        {{ $is_single === 0 ? 'checked' : '' }}> Double Page
                                                </label>
                                            </div>
                                        </div>
                                        @foreach ($booking as $key => $value)
                                            @php
                                                $data = $booking_field->where('tagId', $value->id)->first();
                                                $isChecked = $data ? $data->is_checked : 0;
                                                $isRequired = $data ? $data->is_required : 0;
                                            @endphp

                                            <div class="form-group row">
                                                <input type="hidden" name="modulus_id" value="108">
                                                <input type="hidden" name="type" value="messenger">
                                                <input type="hidden" name="tagId[]" value="{{ $value->id }}">

                                                <div class="col-lg-1">
                                                    <input type="checkbox" id="is_checked_{{ $value->id }}"
                                                           name="is_checked[{{ $key }}]" value="1"
                                                        {{ $isChecked ? 'checked' : '' }}>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-check">
                                                        <input type="text" name="name[{{ $key }}]" class="name"
                                                               placeholder="{{ ucfirst(trans($value->name)) }}"
                                                               value="{{ isset($data->name) ? ucfirst(trans($data->name)) : $value->name }}">
                                                    </label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                               id="is_required_{{ $value->id }}"
                                                               name="is_required[{{ $key }}]" value="1"
                                                            {{ $isRequired ? 'checked' : '' }}>
                                                        <span class="form-check-label mt-4">Required</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="form-group mb-0 text-right">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            @break

                        @case(112)
                            {{-- Stripe Credential Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Stripe Credential</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal"
                                          action="{{ route('store.stripe.credentials') }}"
                                          method="POST">
                                        @csrf

                                        <div class="form-group mt-3">
                                            <label for="">Publishable key</label>
                                            <input type="text" name="app_key"
                                                   class="form-control"
                                                   value="{{ $stripe->app_key ?? "" }}">
                                            @error('app_key')
                                            <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="">Secret Key</label>
                                            <input type="text" name="app_secret"
                                                   class="form-control"
                                                   value="{{ $stripe->app_secret ?? "" }}">
                                            @error('app_secret')
                                            <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div
                                            class="form-group form-check form-switch d-flex align-items-center mt-3"
                                            style="padding-left: 0;margin-top: 10px;">
                                            <label for="statusCheckbox">Status</label>
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="statusCheckbox"
                                                   name="status"
                                                   style="margin-left: 20px; margin-top: -10px;"
                                                   @if(isset($stripe->status) && $stripe->status == "Accepted") checked @endif
                                            >
                                        </div>
                                        <div class="form-group mb-0 text-right mt-3">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            @break

                        @case(113)
                            {{-- Paypal Credential Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Paypal Credential</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal"
                                          action="{{ route('store.paypal.credentials') }}"
                                          method="POST">
                                        @csrf

                                        <div class="form-group mt-3">
                                            <label for="">Client ID</label>
                                            <input type="text" name="client_id"
                                                   class="form-control"
                                                   value="{{ $paypal->client_id ?? "" }}">
                                            @error('client_id')
                                            <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="">Client secret</label>
                                            <input type="text" name="app_secret"
                                                   class="form-control"
                                                   value="{{ $paypal->app_secret ?? "" }}">
                                            @error('app_secret')
                                            <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div
                                            class="form-group form-check form-switch d-flex align-items-center mt-3"
                                            style="padding-left: 0;margin-top: 10px;">
                                            <label for="statusCheckbox">Status</label>
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="statusCheckbox"
                                                   name="status"
                                                   style="margin-left: 20px; margin-top: -10px;"
                                                   @if(isset($paypal->status) && $paypal->status == "Accepted") checked @endif
                                            >
                                        </div>
                                        <div class="form-group mb-0 text-right mt-3">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            @break

                        @case(119)
                            {{-- Order SMS Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Order SMS Configuration</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal"
                                          action="{{ route('admin.store.order.sms.template') }}"
                                          method="POST">
                                        @csrf

                                        <ul class="form-group mt-3">
                                            <li class=""><strong>@{{store_name}}</strong> : Your store name</li>
                                            <li class=""><strong>@{{store_phone}}</strong> : Your store phone number
                                            </li>
                                            <li class=""><strong>@{{store_email}}</strong> : Your store email address
                                            </li>
                                            <li class=""><strong>@{{order_invoice}}</strong> : Order invoice number
                                            </li>
                                            <li class=""><strong>@{{order_total}}</strong> : Order total amount</li>
                                            <li class=""><strong>@{{customer_name}}</strong> : Customer name</li>
                                            <li class=""><strong>@{{customer_phone}}</strong> : Customer phone number
                                            </li>
                                            <li class=""><strong>@{{customer_email}}</strong> : Customer email address
                                            </li>
                                            <li class=""><strong>@{{customer_address}}</strong> : Customer address</li>
                                        </ul>
                                        <div class="form-group mt-3">
                                            <label for="">SMS Text</label>
                                            <textarea name="order_sms" class="form-control" id="order_sms" cols="30"
                                                      rows="5">{{ $ap->order_sms ?? "" }}</textarea>
                                            @error('order_sms')
                                            <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-0 text-right mt-3">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @break

                        @case(125)
                            {{-- Amar Pay Credential Config--}}
                            @php
                                $usreData = getUserData();
                                $marchenPayment  = \App\Models\MarchantPaymentGetway::where("store_id", $usreData['store_id'])->where("payment_gatway", "amarpay")->first();
                                $submitKYC  = \App\Models\MarchantPaymentGetwayKYC::where("store_id", $usreData['store_id'])->where("payment_gatway", "amarpay")->where("status", 0)->first();
                                $isKYCApproved  = \App\Models\MarchantPaymentGetwayKYC::where("store_id", $usreData['store_id'])->where("payment_gatway", "amarpay")->where("status", 1)->first();
                            @endphp
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Amar Pay KYC</h5>
                                </div>
                                <div class="card-body relative">
                                    @if(isset($submitKYC))
                                        <div class="absolute"
                                             style="height: 100%; width: 100%; position: absolute;margin-left: -24px; margin-top: -50px; background: #00000075; display: flex ; justify-content: center; align-items: center;">
                                            <p class="text-center" style="color: #efff00">Your request is pending...</p>
                                        </div>
                                    @endif

                                    @if((isset($marchenPayment) && $marchenPayment->status == 1) || (isset($isKYCApproved)))
                                        <div class="mt-5 mb-5">
                                            <h6 class="text-center text-success">Your KYC Already Approved</h6>
                                        </div>
                                    @else
                                        <form class="form-horizontal"
                                              action="{{ route('admin.store.amarpayKYC') }}"
                                              method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-group mb-3">
                                                <label for="">NID <span class="text-danger">*</span></label>
                                                <input type="text" name="nid"
                                                       class="form-control"
                                                       value="{{ $submitKYC->nid ?? old('nid') ?? "" }}">
                                                <input type="hidden" name="payment_gatway" value="amarpay">
                                                @error('nid')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-3">
                                                <label for="">NID Front Side Photo <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->nid_front) && !empty($submitKYC->nid_front))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->nid_front" }}"
                                                            alt="NID Front" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="nid_front"
                                                       class="form-control">
                                                @error('nid_front')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">NID Back Side Photo <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->nid_back) && !empty($submitKYC->nid_back))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->nid_back" }}"
                                                            alt="NID Back" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="nid_back"
                                                       class="form-control">
                                                @error('nid_back')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Current Bill Copy <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->current_bill_copy) && !empty($submitKYC->current_bill_copy))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->current_bill_copy" }}"
                                                            alt="Current Bill Copy" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="current_bill_copy"
                                                       class="form-control"
                                                       value="{{ $submitKYC->current_bill_copy ?? old('current_bill_copy') ?? "" }}">
                                                @error('current_bill_copy')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID</label>
                                                <input type="text" name="dbid"
                                                       class="form-control"
                                                       value="{{ $submitKYC->dbid ?? old('dbid') ?? "" }}">
                                                @error('dbid')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID Front Side Photo</label>
                                                @if(isset($submitKYC->dbid_front) && !empty($submitKYC->dbid_front))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->dbid_front" }}"
                                                            alt="DBID Front" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="dbid_front"
                                                       class="form-control">
                                                @error('dbid_front')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID Back Side Photo</label>
                                                @if(isset($submitKYC->dbid_back) && !empty($submitKYC->dbid_back))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->dbid_back" }}"
                                                            alt="DBID Back" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="dbid_back"
                                                       class="form-control">
                                                @error('dbid_back')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Trade Licence</label>
                                                <input type="text" name="trade_licence"
                                                       class="form-control"
                                                       value="{{ $submitKYC->trade_licence ?? old('trade_licence') ?? "" }}">
                                                @error('trade_licence')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Trade Licence Photo</label>
                                                @if(isset($submitKYC->trade_licence_image) && !empty($submitKYC->trade_licence_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->trade_licence_image" }}"
                                                            alt="Trade Licence" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="trade_licence_image"
                                                       class="form-control">
                                                @error('trade_licence_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">TIN <span class="text-danger">*</span></label>
                                                <input type="text" name="tin"
                                                       class="form-control"
                                                       value="{{ $submitKYC->tin ?? old('tin') ?? "" }}">
                                                @error('tin')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">TIN Photo <span class="text-danger">*</span></label>
                                                @if(isset($submitKYC->tin_image) && !empty($submitKYC->tin_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->tin_image" }}"
                                                            alt="TIN" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="tin_image"
                                                       class="form-control">
                                                @error('tin_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">BIN</label>
                                                <input type="text" name="bin"
                                                       class="form-control"
                                                       value="{{ $submitKYC->bin ?? old('bin') ?? "" }}">
                                                @error('bin')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">BIN Photo</label>
                                                @if(isset($submitKYC->bin_image) && !empty($submitKYC->bin_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->bin_image" }}"
                                                            alt="BIN" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="bin_image"
                                                       class="form-control">
                                                @error('bin_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Bank Account</label>
                                                <textarea name="bank_account_number" class="form-control" id=""
                                                          cols="30"
                                                          rows="5">{{ $submitKYC->bank_account_number ?? old('bank_account_number') ?? "" }}</textarea>
                                                @error('bank_account_number')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Online Banking</label>
                                                <textarea name="online_bank" class="form-control" id="" cols="30"
                                                          rows="5">{{ $submitKYC->online_bank ?? old('online_bank') ?? "" }}</textarea>
                                                @error('online_bank')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            @if(!isset($submitKYC))
                                                <div class="form-group mb-0 text-right mt-3">
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                </div>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @break
                        @case(126)
                            {{-- Stripe Credential Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Uddoktapay Credential</h5>
                                </div>
                                <div class="card-body">
                                    <form class="form-horizontal"
                                          action="{{ route('store.uddoktapay.credentials') }}"
                                          method="POST">
                                        @csrf

                                        <div class="form-group mt-3">
                                            <label for="">API Key</label>
                                            <input type="text" name="app_key"
                                                   class="form-control"
                                                   value="{{ $uddoktapay->app_key ?? "" }}">
                                            @error('app_key')
                                            <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="">Base URL</label>
                                            <input type="text" name="client_id"
                                                   class="form-control"
                                                   value="{{ $uddoktapay->client_id ?? "" }}">
                                            @error('client_id')
                                            <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div
                                            class="form-group form-check form-switch d-flex align-items-center mt-3"
                                            style="padding-left: 0;margin-top: 10px;">
                                            <label for="statusCheckbox">Status</label>
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="statusCheckbox"
                                                   name="status"
                                                   style="margin-left: 20px; margin-top: -10px;"
                                                   @if(isset($uddoktapay->status) && $uddoktapay->status == "Accepted") checked @endif
                                            >
                                        </div>
                                        <div class="form-group mb-0 text-right mt-3">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            @break
                        @case(127)
                            {{-- Stripe Credential Config--}}
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6 text-center">Generate Facebook Catalog Feed</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-0 text-center mt-3">
                                        <a href="{{ route('admin.facebook.dataFeed.file') }}"
                                           class="btn btn-sm btn-primary">Generate
                                            XML</a>
                                    </div>
                                    @php
                                        $store_url = "";
                                        $usreData = getUserData();
                                        $store = $usreData['store'] ?? "";
                                        if(isset($store->url)){
                                            $store_url = $store->url ?? "";
                                        }
                                    @endphp
                                    {{--                                    <div class="form-group mb-0 text-center mt-3">--}}
                                    {{--                                        <p><strong>XML URL</strong></p>--}}
                                    {{--                                        <input type="text" class="form-control"--}}
                                    {{--                                               value="{{ route('facebook.dataFeed.url', ['name' => $store_url ?? ""]) }}">--}}
                                    {{--                                    </div>--}}

                                    <div class="form-group mb-0 text-center mt-3">
                                        <p><strong>XML URL</strong></p>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="xmlUrl"
                                                   value="{{ route('facebook.dataFeed.url', ['name' => $store_url ?? ""]) }}"
                                                   readonly>
                                            <button class="btn btn-primary" id="copyBtn" type="button">
                                                Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @break
                        @case(128)
                            {{-- Bkash Credential Config--}}
                            @php
                                $usreData = getUserData();
                                $marchenPayment  = \App\Models\MarchantPaymentGetway::where("store_id", $usreData['store_id'])->where("payment_gatway", "bkash")->first();
                                $submitKYC  = \App\Models\MarchantPaymentGetwayKYC::where("store_id", $usreData['store_id'])->where("payment_gatway", "bkash")->where("status", 0)->first();
                                $isKYCApproved  = \App\Models\MarchantPaymentGetwayKYC::where("store_id", $usreData['store_id'])->where("payment_gatway", "bkash")->where("status", 1)->first();

                                $bkash = \App\Models\Paymentgateway::where('store_id', $usreData['store_id'])->where('payment_company', "bKash")->first();
                                if (!$bkash) {
                                    $bkash = new \App\Models\Paymentgateway();
                                    $bkash->store_id = $usreData['store_id'];
                                    $bkash->user_id = $usreData['user_id'];
                                    $bkash->payment_company = "bKash";
                                    $bkash->save();
                                }
                            @endphp

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Bkash Payment Setup</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4 mx-auto">
                                            <div class="form-group">
                                                <label for="">Select Bkash Option</label>
                                                <select class="form-control" name="payment_company"
                                                        id="payment_company">
                                                    <option value="">Select</option>
                                                    <option value="ebitans_bkash">Ebitans Bkash</option>
                                                    @if(isset($bkash))
                                                        <option value="bkash_form">Own Bkash</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($bkash))
                                <div class="card mt-3" id="bkash_form" style="max-width: 50%; display: none;">
                                    <div class="card-body">
                                        <form class="form-horizontal" action="{{route('admin.savepaymentinfo')}}"
                                              method="post">
                                            @csrf
                                            <div>
                                                <input type="hidden" name="id" value="{{ $bkash->id }}">
                                                <div class="form-group">
                                                    <label for="">App Key</label>
                                                    <input type="text" name="app_key" class="form-control"
                                                           value="{{$bkash->app_key ?? ""}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">App Secret</label>
                                                    <input type="text" name="app_secret"
                                                           class="form-control"
                                                           value="{{$bkash->app_secret ?? ""}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">API Username</label>
                                                    <input type="text" name="api_username"
                                                           class="form-control"
                                                           value="{{$bkash->api_username ?? ""}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">API Password</label>
                                                    <input type="text" name="api_password"
                                                           class="form-control"
                                                           value="{{$bkash->api_password ?? ""}}">
                                                </div>
                                                <div class="form-group my-4">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            <div class="card mt-3" id="ebitans_bkash" style="max-width: 50%; display: none;">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Merchat Payment Bkash KYC</h5>
                                </div>
                                <div class="card-body relative">
                                    @if(isset($submitKYC))
                                        <div class="absolute"
                                             style="height: 100%; width: 100%; position: absolute;margin-left: -24px; margin-top: -50px; background: #00000075; display: flex ; justify-content: center; align-items: center;">
                                            <p class="text-center" style="color: #efff00">Your request is pending...</p>
                                        </div>
                                    @endif

                                    @if((isset($marchenPayment) && $marchenPayment->status == 1) || (isset($isKYCApproved)))
                                        <div class="mt-5 mb-5">
                                            <h6 class="text-center text-success">Your KYC Already Approved</h6>
                                        </div>
                                    @else
                                        <form class="form-horizontal"
                                              action="{{ route('admin.store.amarpayKYC') }}"
                                              method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-group mb-3">
                                                <label for="">NID <span class="text-danger">*</span></label>
                                                <input type="text" name="nid"
                                                       class="form-control"
                                                       value="{{ $submitKYC->nid ?? old('nid') ?? "" }}">
                                                <input type="hidden" name="payment_gatway" value="bkash">
                                                @error('nid')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-3">
                                                <label for="">NID Front Side Photo <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->nid_front) && !empty($submitKYC->nid_front))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->nid_front" }}"
                                                            alt="NID Front" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="nid_front"
                                                       class="form-control">
                                                @error('nid_front')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">NID Back Side Photo <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->nid_back) && !empty($submitKYC->nid_back))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->nid_back" }}"
                                                            alt="NID Back" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="nid_back"
                                                       class="form-control">
                                                @error('nid_back')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Current Bill Copy <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->current_bill_copy) && !empty($submitKYC->current_bill_copy))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->current_bill_copy" }}"
                                                            alt="Current Bill Copy" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="current_bill_copy"
                                                       class="form-control"
                                                       value="{{ $submitKYC->current_bill_copy ?? old('current_bill_copy') ?? "" }}">
                                                @error('current_bill_copy')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID</label>
                                                <input type="text" name="dbid"
                                                       class="form-control"
                                                       value="{{ $submitKYC->dbid ?? old('dbid') ?? "" }}">
                                                @error('dbid')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID Front Side Photo</label>
                                                @if(isset($submitKYC->dbid_front) && !empty($submitKYC->dbid_front))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->dbid_front" }}"
                                                            alt="DBID Front" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="dbid_front"
                                                       class="form-control">
                                                @error('dbid_front')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID Back Side Photo</label>
                                                @if(isset($submitKYC->dbid_back) && !empty($submitKYC->dbid_back))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->dbid_back" }}"
                                                            alt="DBID Back" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="dbid_back"
                                                       class="form-control">
                                                @error('dbid_back')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Trade Licence</label>
                                                <input type="text" name="trade_licence"
                                                       class="form-control"
                                                       value="{{ $submitKYC->trade_licence ?? old('trade_licence') ?? "" }}">
                                                @error('trade_licence')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Trade Licence Photo</label>
                                                @if(isset($submitKYC->trade_licence_image) && !empty($submitKYC->trade_licence_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->trade_licence_image" }}"
                                                            alt="Trade Licence" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="trade_licence_image"
                                                       class="form-control">
                                                @error('trade_licence_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">TIN</label>
                                                <input type="text" name="tin"
                                                       class="form-control"
                                                       value="{{ $submitKYC->tin ?? old('tin') ?? "" }}">
                                                @error('tin')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">TIN Photo</label>
                                                @if(isset($submitKYC->tin_image) && !empty($submitKYC->tin_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->tin_image" }}"
                                                            alt="TIN" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="tin_image"
                                                       class="form-control">
                                                @error('tin_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">BIN</label>
                                                <input type="text" name="bin"
                                                       class="form-control"
                                                       value="{{ $submitKYC->bin ?? old('bin') ?? "" }}">
                                                @error('bin')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">BIN Photo</label>
                                                @if(isset($submitKYC->bin_image) && !empty($submitKYC->bin_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->bin_image" }}"
                                                            alt="BIN" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="bin_image"
                                                       class="form-control">
                                                @error('bin_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Bank Account</label>
                                                <textarea name="bank_account_number" class="form-control" id=""
                                                          cols="30"
                                                          rows="5">{{ $submitKYC->bank_account_number ?? old('bank_account_number') ?? "" }}</textarea>
                                                @error('bank_account_number')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Online Banking</label>
                                                <textarea name="online_bank" class="form-control" id="" cols="30"
                                                          rows="5">{{ $submitKYC->online_bank ?? old('online_bank') ?? "" }}</textarea>
                                                @error('online_bank')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            @if(!isset($submitKYC))
                                                <div class="form-group mb-0 text-right mt-3">
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                </div>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @break
                        @case(129)
                            {{-- Nagad Credential Config--}}
                            @php
                                $usreData = getUserData();
                                $marchenPayment  = \App\Models\MarchantPaymentGetway::where("store_id", $usreData['store_id'])->where("payment_gatway", "nagad")->first();
                                $submitKYC  = \App\Models\MarchantPaymentGetwayKYC::where("store_id", $usreData['store_id'])->where("payment_gatway", "nagad")->where("status", 0)->first();
                                $isKYCApproved  = \App\Models\MarchantPaymentGetwayKYC::where("store_id", $usreData['store_id'])->where("payment_gatway", "nagad")->where("status", 1)->first();

                              $nagad = \App\Models\Paymentgateway::where('store_id', $usreData['store_id'])->where('payment_company', "Nagad")->first();

                                if (!$nagad) {
                                    $nagad = new \App\Models\Paymentgateway();
                                    $nagad->store_id = $usreData['store_id'];
                                    $nagad->user_id = $usreData['user_id'];
                                    $nagad->payment_company = "Nagad";
                                    $nagad->save();
                                }
                            @endphp

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Nagad Payment Setup</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4 mx-auto">
                                            <div class="form-group">
                                                <label for="">Select Nagad Option</label>
                                                <select class="form-control" name="payment_company"
                                                        id="payment_company">
                                                    <option value="">Select</option>
                                                    <option value="ebitans_nagad">Ebitans Nagad</option>
                                                    @if(isset($nagad))
                                                        <option value="nagad_form">Own Nagad</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($nagad))
                                <div class="card mt-3" id="nagad_form" style="max-width: 50%; display: none;">
                                    <div class="card-body">
                                        <form action="{{route('admin.savepaymentinfo')}}" method="post">
                                            @csrf
                                            <div class="nagad">
                                                <input type="hidden" name="id" value="{{ $nagad->id }}">
                                                <div class="form-group mb-2">
                                                    <label for="">Merchant ID</label>
                                                    <input type="text" name="merchant_id"
                                                           class="form-control"
                                                           value="{{$nagad->merchant_id ?? ""}}">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label for="">Merchant Number</label>
                                                    <input type="text" name="merchant_number"
                                                           class="form-control"
                                                           value="{{$nagad->merchant_number ?? ""}}">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label for="">Public Key</label>
                                                    <textarea name="public_key"
                                                              class="form-control" cols="10"
                                                              rows="5">{{$nagad->public_key ?? ""}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Private Key</label>
                                                    <textarea name="private_key"
                                                              class="form-control" cols="10"
                                                              rows="5">{{$nagad->private_key ?? ""}}</textarea>
                                                </div>
                                                <div class="form-group my-4">
                                                    <button type="submit" class="btn btn-primary">Save
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            <div class="card mt-3" id="ebitans_nagad" style="max-width: 50%; display: none;">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Merchat Payment Nagad KYC</h5>
                                </div>
                                <div class="card-body relative">
                                    @if(isset($submitKYC))
                                        <div class="absolute"
                                             style="height: 100%; width: 100%; position: absolute;margin-left: -24px; margin-top: -50px; background: #00000075; display: flex ; justify-content: center; align-items: center;">
                                            <p class="text-center" style="color: #efff00">Your request is pending...</p>
                                        </div>
                                    @endif

                                    @if((isset($marchenPayment) && $marchenPayment->status == 1) || (isset($isKYCApproved)))
                                        <div class="mt-5 mb-5">
                                            <h6 class="text-center text-success">Your KYC Already Approved</h6>
                                        </div>
                                    @else
                                        <form class="form-horizontal"
                                              action="{{ route('admin.store.amarpayKYC') }}"
                                              method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-group mb-3">
                                                <label for="">NID <span class="text-danger">*</span></label>
                                                <input type="text" name="nid"
                                                       class="form-control"
                                                       value="{{ $submitKYC->nid ?? old('nid') ?? "" }}">
                                                <input type="hidden" name="payment_gatway" value="nagad">
                                                @error('nid')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-3">
                                                <label for="">NID Front Side Photo <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->nid_front) && !empty($submitKYC->nid_front))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->nid_front" }}"
                                                            alt="NID Front" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="nid_front"
                                                       class="form-control">
                                                @error('nid_front')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">NID Back Side Photo <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->nid_back) && !empty($submitKYC->nid_back))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->nid_back" }}"
                                                            alt="NID Back" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="nid_back"
                                                       class="form-control">
                                                @error('nid_back')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Current Bill Copy <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->current_bill_copy) && !empty($submitKYC->current_bill_copy))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->current_bill_copy" }}"
                                                            alt="Current Bill Copy" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="current_bill_copy"
                                                       class="form-control"
                                                       value="{{ $submitKYC->current_bill_copy ?? old('current_bill_copy') ?? "" }}">
                                                @error('current_bill_copy')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID</label>
                                                <input type="text" name="dbid"
                                                       class="form-control"
                                                       value="{{ $submitKYC->dbid ?? old('dbid') ?? "" }}">
                                                @error('dbid')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID Front Side Photo</label>
                                                @if(isset($submitKYC->dbid_front) && !empty($submitKYC->dbid_front))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->dbid_front" }}"
                                                            alt="DBID Front" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="dbid_front"
                                                       class="form-control">
                                                @error('dbid_front')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID Back Side Photo</label>
                                                @if(isset($submitKYC->dbid_back) && !empty($submitKYC->dbid_back))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->dbid_back" }}"
                                                            alt="DBID Back" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="dbid_back"
                                                       class="form-control">
                                                @error('dbid_back')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Trade Licence</label>
                                                <input type="text" name="trade_licence"
                                                       class="form-control"
                                                       value="{{ $submitKYC->trade_licence ?? old('trade_licence') ?? "" }}">
                                                @error('trade_licence')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Trade Licence Photo</label>
                                                @if(isset($submitKYC->trade_licence_image) && !empty($submitKYC->trade_licence_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->trade_licence_image" }}"
                                                            alt="Trade Licence" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="trade_licence_image"
                                                       class="form-control">
                                                @error('trade_licence_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">TIN</label>
                                                <input type="text" name="tin"
                                                       class="form-control"
                                                       value="{{ $submitKYC->tin ?? old('tin') ?? "" }}">
                                                @error('tin')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">TIN Photo</label>
                                                @if(isset($submitKYC->tin_image) && !empty($submitKYC->tin_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->tin_image" }}"
                                                            alt="TIN" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="tin_image"
                                                       class="form-control">
                                                @error('tin_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">BIN</label>
                                                <input type="text" name="bin"
                                                       class="form-control"
                                                       value="{{ $submitKYC->bin ?? old('bin') ?? "" }}">
                                                @error('bin')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">BIN Photo</label>
                                                @if(isset($submitKYC->bin_image) && !empty($submitKYC->bin_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->bin_image" }}"
                                                            alt="BIN" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="bin_image"
                                                       class="form-control">
                                                @error('bin_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Bank Account</label>
                                                <textarea name="bank_account_number" class="form-control" id=""
                                                          cols="30"
                                                          rows="5">{{ $submitKYC->bank_account_number ?? old('bank_account_number') ?? "" }}</textarea>
                                                @error('bank_account_number')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Online Banking</label>
                                                <textarea name="online_bank" class="form-control" id="" cols="30"
                                                          rows="5">{{ $submitKYC->online_bank ?? old('online_bank') ?? "" }}</textarea>
                                                @error('online_bank')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            @if(!isset($submitKYC))
                                                <div class="form-group mb-0 text-right mt-3">
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                </div>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @break
                        @case(130)
                            {{-- Amar Pay Credential Config--}}
                            @php
                                $usreData = getUserData();
                                $marchenPayment  = \App\Models\MarchantPaymentGetway::where("store_id", $usreData['store_id'])->where("payment_gatway", "rocket")->first();
                                $submitKYC  = \App\Models\MarchantPaymentGetwayKYC::where("store_id", $usreData['store_id'])->where("payment_gatway", "rocket")->where("status", 0)->first();
                                $isKYCApproved  = \App\Models\MarchantPaymentGetwayKYC::where("store_id", $usreData['store_id'])->where("payment_gatway", "rocket")->where("status", 1)->first();
                            @endphp
                            <div class="card" style="max-width: 50%">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">Merchat Payment Rocket KYC</h5>
                                </div>
                                <div class="card-body relative">
                                    @if(isset($submitKYC))
                                        <div class="absolute"
                                             style="height: 100%; width: 100%; position: absolute;margin-left: -24px; margin-top: -50px; background: #00000075; display: flex ; justify-content: center; align-items: center;">
                                            <p class="text-center" style="color: #efff00">Your request is pending...</p>
                                        </div>
                                    @endif

                                    @if((isset($marchenPayment) && $marchenPayment->status == 1) || (isset($isKYCApproved)))
                                        <div class="mt-5 mb-5">
                                            <h6 class="text-center text-success">Your KYC Already Approved</h6>
                                        </div>
                                    @else
                                        <form class="form-horizontal"
                                              action="{{ route('admin.store.amarpayKYC') }}"
                                              method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-group mb-3">
                                                <label for="">NID <span class="text-danger">*</span></label>
                                                <input type="text" name="nid"
                                                       class="form-control"
                                                       value="{{ $submitKYC->nid ?? old('nid') ?? "" }}">
                                                <input type="hidden" name="payment_gatway" value="rocket">
                                                @error('nid')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-3">
                                                <label for="">NID Front Side Photo <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->nid_front) && !empty($submitKYC->nid_front))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->nid_front" }}"
                                                            alt="NID Front" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="nid_front"
                                                       class="form-control">
                                                @error('nid_front')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">NID Back Side Photo <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->nid_back) && !empty($submitKYC->nid_back))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->nid_back" }}"
                                                            alt="NID Back" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="nid_back"
                                                       class="form-control">
                                                @error('nid_back')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Current Bill Copy <span
                                                        class="text-danger">*</span></label>
                                                @if(isset($submitKYC->current_bill_copy) && !empty($submitKYC->current_bill_copy))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->current_bill_copy" }}"
                                                            alt="Current Bill Copy" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="current_bill_copy"
                                                       class="form-control"
                                                       value="{{ $submitKYC->current_bill_copy ?? old('current_bill_copy') ?? "" }}">
                                                @error('current_bill_copy')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID</label>
                                                <input type="text" name="dbid"
                                                       class="form-control"
                                                       value="{{ $submitKYC->dbid ?? old('dbid') ?? "" }}">
                                                @error('dbid')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID Front Side Photo</label>
                                                @if(isset($submitKYC->dbid_front) && !empty($submitKYC->dbid_front))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->dbid_front" }}"
                                                            alt="DBID Front" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="dbid_front"
                                                       class="form-control">
                                                @error('dbid_front')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">DBID Back Side Photo</label>
                                                @if(isset($submitKYC->dbid_back) && !empty($submitKYC->dbid_back))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->dbid_back" }}"
                                                            alt="DBID Back" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="dbid_back"
                                                       class="form-control">
                                                @error('dbid_back')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Trade Licence</label>
                                                <input type="text" name="trade_licence"
                                                       class="form-control"
                                                       value="{{ $submitKYC->trade_licence ?? old('trade_licence') ?? "" }}">
                                                @error('trade_licence')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Trade Licence Photo</label>
                                                @if(isset($submitKYC->trade_licence_image) && !empty($submitKYC->trade_licence_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->trade_licence_image" }}"
                                                            alt="Trade Licence" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="trade_licence_image"
                                                       class="form-control">
                                                @error('trade_licence_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">TIN <span class="text-danger">*</span></label>
                                                <input type="text" name="tin"
                                                       class="form-control"
                                                       value="{{ $submitKYC->tin ?? old('tin') ?? "" }}">
                                                @error('tin')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">TIN Photo <span class="text-danger">*</span></label>
                                                @if(isset($submitKYC->tin_image) && !empty($submitKYC->tin_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->tin_image" }}"
                                                            alt="TIN" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="tin_image"
                                                       class="form-control">
                                                @error('tin_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">BIN</label>
                                                <input type="text" name="bin"
                                                       class="form-control"
                                                       value="{{ $submitKYC->bin ?? old('bin') ?? "" }}">
                                                @error('bin')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">BIN Photo</label>
                                                @if(isset($submitKYC->bin_image) && !empty($submitKYC->bin_image))
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('/assets/images/kyc')."/$submitKYC->bin_image" }}"
                                                            alt="BIN" style="width: 175px;height: 85px">
                                                    </div>
                                                @endif
                                                <input type="file" name="bin_image"
                                                       class="form-control">
                                                @error('bin_image')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Bank Account</label>
                                                <textarea name="bank_account_number" class="form-control" id=""
                                                          cols="30"
                                                          rows="5">{{ $submitKYC->bank_account_number ?? old('bank_account_number') ?? "" }}</textarea>
                                                @error('bank_account_number')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="">Online Banking</label>
                                                <textarea name="online_bank" class="form-control" id="" cols="30"
                                                          rows="5">{{ $submitKYC->online_bank ?? old('online_bank') ?? "" }}</textarea>
                                                @error('online_bank')
                                                <p class="text-danger dangerError" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            @if(!isset($submitKYC))
                                                <div class="form-group mb-0 text-right mt-3">
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                </div>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @break
                        @case(132)
                            {{-- SSL Credential Config--}}
                            @php
                                $usreData = getUserData();
                                $ssl = \App\Models\Paymentgateway::where('store_id', $usreData['store_id'])->where('payment_company', "SSL")->first();

                                 if (!$ssl) {
                                    $ssl = new \App\Models\Paymentgateway();
                                    $ssl->store_id = $usreData['store_id'];
                                    $ssl->user_id = $usreData['user_id'];
                                    $ssl->payment_company = "SSL";
                                    $ssl->save();
                                }
                            @endphp

                            <div class="card mt-3" style="max-width: 50%;">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">SSLCOMMERZ Payment Setup</h5>
                                </div>

                                <div class="card-body">
                                    <form action="{{route('admin.savepaymentinfo')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $ssl->id }}">
                                        <div class="ssl">
                                            <div class="form-group">
                                                <label for="">Store Id</label>
                                                <input type="text" name="store_id"
                                                       class="form-control"
                                                       value="{{$ssl->ssl_store_id ?? ""}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Store Password</label>
                                                <input type="text" name="store_password"
                                                       class="form-control"
                                                       value="{{$ssl->ssl_store_password ?? ""}}">
                                            </div>
                                            <div class="form-group my-4">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @break
                        @default
                            <div class="card" style="min-height: 300px;">
                                <div class="card-body"
                                     style="display: flex; justify-content: center; align-items: center;">
                                    <h4 style="color: sandybrown ">
                                        <i class="fa fa-exclamation-triangle"
                                           aria-hidden="true"></i>
                                        Modulus Config Not Found
                                    </h4>
                                </div>
                            </div>
                    @endswitch
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            // Disable all is_required checkboxes on page load
            $('input[id^="is_required_"]').prop('disabled', true);

            // Attach a change event listener to all checkboxes with id starting with 'is_checked_'
            $('input[id^="is_checked_"]').change(function () {
                var checkboxId = $(this).attr('id'); // Get the id of the clicked checkbox
                var key = checkboxId.split('_').pop(); // Extract the key from the checkbox id

                // If the checkbox is checked, enable the corresponding is_required checkbox
                if ($(this).prop('checked')) {
                    $('#is_required_' + key).prop('disabled', false);
                } else {
                    // If the checkbox is unchecked, disable and uncheck the corresponding is_required checkbox
                    $('#is_required_' + key).prop('disabled', true).prop('checked', false);
                }
            });

            // Trigger the change event on page load to initialize the state based on the initial values
            $('input[id^="is_checked_"]').change();


            function disablePaymentMethod() {
                $("#bkash_form").hide();
                $("#ebitans_bkash").hide();

                $("#nagad_form").hide();
                $("#ebitans_nagad").hide();

                $("#rocket_form").hide();
                $("#ebitans_rocket").hide();

                $("#ssl_form").hide();
                $("#ebitans_ssl").hide();

                $("#paypal_form").hide();
                $("#ebitans_paypal").hide();
            }

            disablePaymentMethod();

            $("#payment_company").on('change', function () {
                const value = $(this).find(":selected").val();

                if (value !== "") {
                    disablePaymentMethod();
                    $("#" + value).show();
                } else {
                    disablePaymentMethod();
                }
            })

        });


        const prePaymentSelect = () => {
            let payment_type = $("#payment_type").val();
            let prepayment_input = $("#prepayment_input");

            if (payment_type == 2) {
                prepayment_input.hide();
            } else {
                prepayment_input.show();
            }

        }


        // Get the button and input element
        var copyBtn = document.getElementById('copyBtn');
        // var inputUrl = document.getElementById('xmlUrl');

        // Attach click event to the button
        copyBtn.addEventListener('click', function () {
            // // Select the input text
            let copyText = $("#xmlUrl").val();

            navigator.clipboard.writeText(copyText).then(function () {
                toastr.success("URL successfully copied to clipboard!");
                $("#copyBtn").html("Copied").css("background-color", "green");

                const btnText = 'Copy';

                setTimeout(function () {
                    $("#copyBtn").html(btnText).css("background-color", "#ff5733");
                }, 2500);

            }).catch(function (error) {
                toastr.error("Failed to copy: " + error);
            });
        });

    </script>
@endpush
