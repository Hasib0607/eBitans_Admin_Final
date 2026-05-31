@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" src="{{ asset('admin/src/bootstrap-tagsinput.css') }}"/>
    <style>
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

        .form-select:focus {
            border-color: #D2D6DA;
        }

        .editDiv {
            position: absolute;
            right: 0;
            z-index: 1;
            top: 10px;
        }

        .editDiv label {
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

        .editDiv label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .editDiv label:after {
            content: "\f040";
            font-family: 'FontAwesome';
            color: #757575;
            position: absolute;
            top: -10px;
            left: 0;
            right: 0;
            text-align: center;
            margin: auto;
        }

        .onchangeShippingMethod {
            cursor: pointer;
            margin-right: 10px;
        }

        .onchangeShippingMethod-label {
            cursor: pointer;
        }

        label.form-label {
            cursor: pointer;
        }

        .switch {
            margin-top: 0;
            margin-bottom: -7px;
        }

        input.check-toggle-round-flat:checked + label:after {
            margin-left: 20px;
        }

        input.check-toggle-round-flat + label:after {
            left: 5px;
            width: 20px;
        }

        input.check-toggle-round-flat + label {
            width: 50px;
        }

        .switch > span.on {
            left: 5px;
        }

        .switch > span.off {
            right: 0px;
            padding-right: 2px;
        }

    </style>
@endpush
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">
        <!--setting nav bar component-->
        @include('admin.setting.share.setting-nav', ['website_settings'=>true])

        <!----Modal 1 Start---->
        <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true" style="background-color:transparent !important">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header " style="text-align:right;padding: 0px 12px;">
                        <span class="button btn btn-primary" id="closemodal" data-dismiss="modal" aria-label="Close"
                              style="margin-left:auto;background-color:transparent !important;color:black;font-size:15px;padding:2px 10px !important;margin-top:15px;">X</span>
                    </div>
                    <div class="modal-body">
                        <p>
                            What is Lorem Ipsum?

                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                            been
                            the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                            galley
                            of type and scrambled it to make a type specimen book. It has survived not only five
                            centuries,
                            but also the leap into electronic typesetting, remaining essentially unchanged. It was
                            popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                            passages,
                            and more recently with desktop publishing software like Aldus PageMaker including versions
                            of
                            Lorem Ipsum.
                            Why do we use it?

                            It is a long established fact that a reader will be distracted by the readable content of a
                            page
                            when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less
                            normal
                            distribution of letters, as opposed to using 'Content here, content here', making it look
                            like
                            readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum
                            as
                            their default model text, and a search for 'lorem ipsum' will uncover many web sites still
                            in
                            their infancy. Various versions have evolved over the years, sometimes by accident,
                            sometimes on
                            purpose (injected humour and the like).
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!----Modal 1 End---->

        <!----Modal 2 Start---->
        <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header " style="text-align:right;padding: 0px 12px;">
                        <span class="button btn btn-primary" id="closemodal1" data-dismiss="modal" aria-label="Close"
                              style="margin-left:auto;background-color:transparent !important;color:black;font-size:15px;padding:2px 10px !important;margin-top:15px;">X</span>
                    </div>
                    <div class="modal-body">
                        <p>
                            Modal 2
                            What is Lorem Ipsum?

                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                            been
                            the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                            galley
                            of type and scrambled it to make a type specimen book. It has survived not only five
                            centuries,
                            but also the leap into electronic typesetting, remaining essentially unchanged. It was
                            popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                            passages,
                            and more recently with desktop publishing software like Aldus PageMaker including versions
                            of
                            Lorem Ipsum.
                            Why do we use it?

                            It is a long established fact that a reader will be distracted by the readable content of a
                            page
                            when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less
                            normal
                            distribution of letters, as opposed to using 'Content here, content here', making it look
                            like
                            readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum
                            as
                            their default model text, and a search for 'lorem ipsum' will uncover many web sites still
                            in
                            their infancy. Various versions have evolved over the years, sometimes by accident,
                            sometimes on
                            purpose (injected humour and the like).
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!----Modal 2 End---->

        {{--website main setting container section--}}
        <section class="container content-main">
            <div class="row">
                <form id="onchangeSubmit" action="{{ route('admin.updatesetting') }}" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf

                    {{--main section--}}
                    <div class="row">
                        {{--header section--}}
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            সেটিংস
                                        @else
                                            Settings
                                        @endif
                                    </h2>
                                </div>

                                <div class="col-md-6" style="text-align:right">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3"></div>

                        {{--basic website setting and social link section--}}
                        <div class="col-lg-6">

                            {{--basic website setting card--}}
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            মৌলিক
                                        @else
                                            Basic
                                        @endif
                                    </h4>
                                </div>
                                @if (Session::has('error_message'))
                                    <div class="alert alert-danger"
                                         style="color:#fff">{{ Session::get('error_message') }}
                                    </div>
                                @endif

                                {{--basic setting input field--}}
                                <div class="card-body">

                                    <div class="row">

                                        {{--website logo--}}
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <div class="avatar-upload">
                                                    <div class="avatar-edit">
                                                        <input type='file' id="imageUpload" name="logo"
                                                               accept=".png, .jpg, .jpeg" onchange="loadFile(event)"/>
                                                        <label for="imageUpload"></label>
                                                    </div>
                                                    <div class="avatar-preview" style="overflow:hidden">
                                                        @if (isset($data))
                                                            @if (isset($data->logo))
                                                                <img id="output" class="center"
                                                                     src=" {{ asset('assets/images/setting/'.$data->logo ) }}"
                                                                     style="width:200px;"/>
                                                            @else
                                                                <img id="output" class="center"
                                                                     src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                                                                     alt=""/>
                                                            @endif
                                                        @else
                                                            <img id="output" class="center"
                                                                 src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                                                                 alt=""/>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="divv" style="text-align:center">
                                                    <p style="font-weight:bold">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            ওয়েবসাইট
                                                            লোগো
                                                        @else
                                                            Website Logo
                                                        @endif
                                                    </p>
                                                </div>
                                                @error('logo')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>

                                        </div>

                                        {{--website favicon--}}
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <div class="avatar-upload">
                                                    <div class="avatar-edit">
                                                        <input type='file' id="favImageUpload" name="favicon"
                                                               accept=".png, .jpg, .jpeg"
                                                               onchange="loadFavicon(event)"/>
                                                        <label for="favImageUpload"></label>
                                                    </div>
                                                    <div class="avatar-preview" style="overflow:hidden">
                                                        @if (isset($data))
                                                            @if (isset($data->favicon))
                                                                <img id="faviconOutput" class="center"
                                                                     src=" {{ asset('assets/images/setting/'.$data->favicon ) }}"
                                                                     style="width:200px;"/>
                                                            @else
                                                                <img id="faviconOutput" class="center"
                                                                     src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                                                                     alt=""/>
                                                            @endif
                                                        @else
                                                            <img id="faviconOutput" class="center"
                                                                 src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                                                                 alt=""/>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="divv" style="text-align:center">
                                                    <p style="font-weight:bold">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            Website Favicon
                                                        @else
                                                            Website Favicon
                                                        @endif
                                                    </p>
                                                </div>
                                                @error('favicon')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Name
                                            @endif
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="name" name="name"
                                               value="{{ $data->website_name ?? old('website_name') }}" readonly>
                                        @error('name')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ছোট বিবরণ
                                            @else
                                                Short Description
                                            @endif
                                        </label>
                                        <textarea class="form-control" name="short_description" id="short_description"
                                                  rows="4">{{ $data->short_description ?? old('short_description') }}</textarea>
                                        @error('short_description')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                টাইপ
                                            @else
                                                Type
                                            @endif
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="type" name="type" value="{{ $store->type ?? old('type') }}"
                                               readonly>
                                        @error('type')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                সক্রিয়
                                                পরিকল্পনা
                                            @else
                                                Active Plan
                                            @endif
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="activeplan" name="activeplan"
                                               value="{{ $store->plan_id ?? old('plan_id') }}" readonly>
                                        @error('activeplan')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4 row">
                                        <div class="col">
                                            <label for="product_name" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    মুদ্রা
                                                @else
                                                    Currency
                                                @endif
                                            </label>
                                            <select class="form-select" aria-label="Default select Currency"
                                                    name="currency" id="selectCurrency" onchange="updateCurrency()">
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency->id }}"
                                                            @if($currency->currency) selected @endif>{{ $currency->code }}</option>
                                                @endforeach
                                            </select>
                                            @error('currency')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        @if($store->current_currency->customize_rate_status)
                                            <div class="col">
                                                <label for="product_name" class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        মুদ্রা বিনিময় হার (USD)
                                                    @else
                                                        Currency Exchange Rate (USD)
                                                    @endif
                                                </label>
                                                <input type="tel" placeholder="Type here" class="form-control"
                                                       id="currency_rate" name="currency_rate"
                                                       value="{{ $store->currency_rate ?? 0.00 }}">
                                                @error('currency_rate')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @else
                                            <div class="col-md-5">

                                                <label for="product_name" class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        মুদ্রা বিনিময় হার (USD)
                                                    @else
                                                        Currency Exchange Rate (USD)
                                                    @endif
                                                </label>
                                                <button type="button" id="currencyBtn" onclick="updateCurrency()"
                                                        class="btn btn-primary">Current
                                                    Rate {{$store->currency_rate}}</button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ফোন
                                            @else
                                                Phone
                                            @endif
                                        </label>
                                        <input type="tel" placeholder="Type here" class="form-control"
                                               id="phone" name="phone" value="{{ $data->phone ?? old('phone') }}">
                                        @error('phone')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ইমেইল
                                            @else
                                                Email
                                            @endif
                                        </label>
                                        <input type="email" placeholder="Type here" class="form-control"
                                               style="border-color: {{ $data->email == null ? 'red!important;' : '' }}"
                                               id="email" name="email" value="{{ $data->email ?? old('email') }}">
                                        @error('email')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ঠিকানা
                                            @else
                                                Address
                                            @endif
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="address" name="address"
                                               value="{{ $data->address ?? old('address') }}">
                                        @error('address')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                মানচিত্র ঠিকানা
                                            @else
                                                Map Address
                                            @endif
                                        </label>
                                        <textarea placeholder="Google map address" class="form-control"
                                                  id="map_address" name="map_address" cols="30"
                                                  rows="3">{{ $data->map_address ?? old('map_address') }}</textarea>
                                        @error('map_address')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="custom_writing" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                কাস্টমাইজেশন লেখা
                                            @else
                                                Custom Writing
                                            @endif
                                        </label>
                                        <textarea placeholder="Custom writing" class="form-control"
                                                  id="custom_writing" name="custom_writing" cols="30"
                                                  rows="3">{{ $data->custom_writing ?? old('custom_writing') ?? "" }}</textarea>
                                        @error('custom_writing')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            {{--social link setting card--}}
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="test">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            সামাজিক লিঙ্ক
                                        @else
                                            Social Link
                                        @endif
                                    </h4>
                                </div>

                                {{--social link form--}}
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ফেসবুক লিঙ্ক
                                            @else
                                                Facebook Link
                                            @endif
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="facebook_link" name="facebook_link"
                                               value="{{ $data->facebook_link ?? old('facebook_link') }}">
                                        @error('facebook_link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ইনস্টাগ্রাম
                                                লিঙ্ক
                                            @else
                                                Instagram Link
                                            @endif
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="instagram_link" name="instagram_link"
                                               value="{{ $data->instagram_link ?? old('instagram_link') }}">
                                        @error('instagram_link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ইউটিউব লিংক
                                            @else
                                                Youtube Link
                                            @endif
                                            <a href="javascript:void(0)" class="d-none" data-toggle="modal"
                                               data-target="#modal2"><img
                                                    id="test"
                                                    src="https://shots.jotform.com/kade/Screenshots/blue_question_mark.png"
                                                    height="13px" style="padding-bottom:3px"/></a>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="youtube_link" name="youtube_link"
                                               value="{{ $data->youtube_link ?? old('youtube_link') }}">
                                        @error('youtube_link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                হোয়াটসঅ্যাপ
                                            @else
                                                Whats App
                                            @endif
                                        </label>
                                        <input type="tel" placeholder="8801886515579" class="form-control"
                                               id="whatsapp_phone" name="whatsapp_phone"
                                               value="{{ $data->whatsapp_phone ?? old('whatsapp_phone') }}">
                                        @error('whatsapp_phone')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="lined_in_link" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                লিঙ্কড ইন
                                            @else
                                                Linked In
                                            @endif
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="lined_in_link" name="lined_in_link"
                                               value="{{ $data->lined_in_link ?? old('lined_in_link') }}">
                                        @error('lined_in_link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div> <!-- card end// -->

                        {{--shipping tax card section--}}
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            শিপিং ও ট্যাক্স
                                        @else
                                            Shipping & Tax
                                        @endif
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ট্যাক্স
                                                @else
                                                    Tax
                                                @endif (%)
                                            </label>
                                            <input type="number" step="0.01" placeholder="Type here"
                                                   class="form-control"
                                                   step="0.01"
                                                   id="tax" name="tax" value="{{ $data->tax ?? old('tax') }}">
                                            @error('tax')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="shipping-methods-wrapper">
                                            @php $shippingMethods = $data->shipping_methods ? is_string($data->shipping_methods) ? $data->shipping_methods : json_encode($data->shipping_methods) : json_encode([['id' => '1','area' => '', 'cost' => '']]) @endphp
                                            @foreach (@json_decode($shippingMethods) as $index => $method)
                                                @include('admin.setting.share.shipping_method_row', ['index' => $index, 'method' => $method])
                                            @endforeach
                                        </div>

                                        <div class="col text-end">
                                            <button type="button" class="btn btn-sm btn-success"
                                                    onclick="addShippingMethod()">
                                                <i class="fa fa-plus"></i> Add Shipping Method
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-header" style="padding: 0">
                                        <h6>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Payment Method
                                            @else
                                                Payment Method
                                            @endif
                                        </h6>
                                    </div>
                                    <div>
                                        <label for="cod" class="form-label" style="margin-bottom: 0;">
                                            <input type="checkbox"
                                                   placeholder="Type here" id="cod"
                                                   name="cod"
                                                   value="{{ $data->cod ?? old('cod') }}"
                                                   @if (isset($data->cod) && $data->cod == 'active') checked @endif>
                                            &nbsp;{{$data->cod_text ?? "Cash On Delivery"}}</label>
                                        <span onclick="paymentTextHandler('cod_text')" class="editDiv"
                                              style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>

                                        @error('cod')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    @if (!empty($ssl) &&  !empty($ssl->ssl_store_id) && !empty($ssl->ssl_store_password))
                                        <div style="margin-top: 3px;">
                                            <label for="online" class="form-label" style="margin-bottom: 0;">
                                                <input type="checkbox" placeholder="Type here" id="online"
                                                       name="online" value="{{ $data->online ?? old('online') }}"
                                                       @if (isset($data->online) && $data->online == 'active') checked @endif>&nbsp;&nbsp;SSL
                                                Payment</label>

                                            @error('online')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    @if (!empty($bkash) &&  !empty($bkash->app_key) && !empty($bkash->app_secret) && !empty($bkash->api_username) && !empty($bkash->api_password))
                                        <div style="margin-top: -10px;">
                                            <label for="bkash" class="form-label" style="margin-bottom: 0;">
                                                <input type="checkbox" placeholder="Type here" id="bkash"
                                                       name="bkash" value="{{ $data->bkash ?? old('bkash') }}"
                                                       @if (isset($data->bkash) && $data->bkash == 'active') checked @endif>&nbsp;&nbsp;{{$data->bkash_text ?? "bKash Payment"}}
                                            </label>
                                            <span onclick="paymentTextHandler('bkash_text')" class="editDiv"
                                                  style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>
                                            @error('bkash')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    @if (!empty($nagad) &&  !empty($nagad->app_key) && !empty($nagad->app_secret) && !empty($nagad->api_username) && !empty($nagad->api_username))
                                        <div style="margin-top: 0px;">
                                            <label for="nagad" class="form-label" style="margin-bottom: 0;">
                                                <input type="checkbox" placeholder="Type here" id="nagad"
                                                       name="nagad" value="{{ $data->nagad ?? old('nagad') }}"
                                                       @if (isset($data->nagad) && $data->nagad == 'active') checked @endif>&nbsp;&nbsp;Nagad
                                                Payment</label>

                                            @error('nagad')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    @if (ModulusStatus($store->id, 112))
                                        @if (!empty($stripe) && $stripe->status == "Accepted" && !empty($stripe->app_key) && !empty($stripe->app_secret))
                                            <div style="margin-top: -10px;">
                                                <label for="stripe" class="form-label" style="margin-bottom: 0;">
                                                    <input type="checkbox" placeholder="Type here" id="stripe"
                                                           name="stripe" value="{{ $data->stripe ?? old('stripe') }}"
                                                           @if (isset($data->stripe) && $data->stripe == 'active') checked @endif>&nbsp;&nbsp;{{$data->stripe_text ?? "Stripe"}}
                                                </label>
                                                <span onclick="paymentTextHandler('stripe_text')" class="editDiv"
                                                      style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>
                                                @error('stripe')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                    @endif

                                    @if (ModulusStatus($store->id, 113))
                                        @if (!empty($paypal) && $paypal->status == "Accepted" && !empty($paypal->app_secret) && !empty($paypal->client_id))
                                            <div style="margin-top: -10px;">
                                                <label for="paypal" class="form-label" style="margin-bottom: 0;">
                                                    <input type="checkbox" placeholder="Type here" id="paypal"
                                                           name="paypal" value="{{ $data->paypal ?? old('paypal') }}"
                                                           @if (isset($data->paypal) && $data->paypal == 'active') checked @endif>&nbsp;&nbsp;{{$data->paypal_text ?? "Paypal"}}
                                                </label>
                                                <span onclick="paymentTextHandler('paypal_text')" class="editDiv"
                                                      style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>
                                                @error('paypal')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                    @endif

                                    @if (isset($uddoktapay) && !empty($uddoktapay->app_key) && $uddoktapay->status == "Accepted")
                                        <div style="margin-top: -10px;">
                                            <label for="uddoktapay" class="form-label" style="margin-bottom: 0;">
                                                <input type="checkbox" placeholder="Type here" id="uddoktapay"
                                                       name="uddoktapay"
                                                       value="{{ $data->uddoktapay ?? old('uddoktapay') }}"
                                                       @if (isset($data->uddoktapay) && $data->uddoktapay == 'active') checked @endif>&nbsp;&nbsp;{{$data->uddoktapay_text ?? "Uddokta Pay"}}
                                            </label>
                                            <span onclick="paymentTextHandler('uddoktapay_text')" class="editDiv"
                                                  style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>
                                            @error('uddoktapay')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    @if (ModulusStatus($store->id, 106))
                                        <div style="margin-top: -10px;">
                                            <label for="ap" class="form-label" style="margin-bottom: 0;">
                                                <input type="checkbox" placeholder="Type here" id="ap"
                                                       name="ap" value=""
                                                       checked
                                                       disabled>&nbsp;&nbsp;{{$data->ap_text ?? "Advance Payment"}}
                                            </label>
                                            <span onclick="paymentTextHandler('ap_text')" class="editDiv"
                                                  style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>
                                        </div>
                                    @endif

                                    @php
                                        $marchenPayment = \App\Models\MarchantPaymentGetway::where("store_id", $store->id)->where("payment_gatway", "amarpay")->first();
                                        $amarPayStatus = isset($marchenPayment->status) && $marchenPayment->status == 1 ? 1 : 0;
                                    @endphp
                                    @if (ModulusStatus($store->id, 125) && $amarPayStatus)
                                        <div style="margin-top: -10px;">
                                            <label for="amarpay" class="form-label" style="margin-bottom: 0;">
                                                <input type="checkbox" placeholder="Type here" id="amarpay"
                                                       name="amarpay" value="{{ $data->amarpay ?? old('amarpay') }}"
                                                       @if (isset($data->amarpay) && $data->amarpay == 'active') checked @endif>&nbsp;&nbsp;{{$data->amarpay_text ?? "Amar Pay"}}
                                            </label>
                                            <span onclick="paymentTextHandler('amarpay_text')" class="editDiv"
                                                  style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>
                                            @error('amarpay')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                    @php
                                        $marchenPayment = \App\Models\MarchantPaymentGetway::where("store_id", $store->id)->where("payment_gatway", "bkash")->first();
                                        $bkashStatus = isset($marchenPayment->status) && $marchenPayment->status == 1 ? 1 : 0;
                                    @endphp
                                    @if (ModulusStatus($store->id, 128) && $bkashStatus)
                                        <div style="margin-top: -10px;">
                                            <label for="merchant_bkash" class="form-label" style="margin-bottom: 0;">
                                                <input type="checkbox" placeholder="Type here" id="merchant_bkash"
                                                       name="merchant_bkash"
                                                       value="{{ $data->merchant_bkash ?? old('merchant_bkash') }}"
                                                       @if (isset($data->merchant_bkash) && $data->merchant_bkash == 'active') checked @endif>&nbsp;&nbsp;{{$data->merchant_bkash_text ?? "Merchant Bkash"}}
                                            </label>
                                            <span onclick="paymentTextHandler('merchant_bkash_text')" class="editDiv"
                                                  style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>
                                            @error('merchant_bkash')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                    @php
                                        $marchenPayment = \App\Models\MarchantPaymentGetway::where("store_id", $store->id)->where("payment_gatway", "nagad")->first();
                                        $nagadStatus = isset($marchenPayment->status) && $marchenPayment->status == 1 ? 1 : 0;
                                    @endphp
                                    @if (ModulusStatus($store->id, 129) && $nagadStatus)
                                        <div style="margin-top: -10px;">
                                            <label for="merchant_nagad" class="form-label" style="margin-bottom: 0;">
                                                <input type="checkbox" placeholder="Type here" id="merchant_nagad"
                                                       name="merchant_nagad"
                                                       value="{{ $data->merchant_nagad ?? old('merchant_nagad') }}"
                                                       @if (isset($data->merchant_nagad) && $data->merchant_nagad == 'active') checked @endif>&nbsp;&nbsp;{{$data->merchant_nagad_text ?? "Merchant Nagad"}}
                                            </label>
                                            <span onclick="paymentTextHandler('merchant_nagad_text')" class="editDiv"
                                                  style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>
                                            @error('merchant_nagad')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                    @php
                                        $marchenPayment = \App\Models\MarchantPaymentGetway::where("store_id", $store->id)->where("payment_gatway", "rocket")->first();
                                        $rocketStatus = isset($marchenPayment->status) && $marchenPayment->status == 1 ? 1 : 0;
                                    @endphp
                                    @if (ModulusStatus($store->id, 130) && $rocketStatus)
                                        <div style="margin-top: -10px;">
                                            <label for="merchant_rocket" class="form-label" style="margin-bottom: 0;">
                                                <input type="checkbox" placeholder="Type here" id="merchant_rocket"
                                                       name="merchant_rocket"
                                                       value="{{ $data->merchant_rocket ?? old('merchant_rocket') }}"
                                                       @if (isset($data->merchant_rocket) && $data->merchant_rocket == 'active') checked @endif>&nbsp;&nbsp;{{$data->merchant_rocket_text ?? "Merchant Rocket"}}
                                            </label>
                                            <span onclick="paymentTextHandler('merchant_rocket_text')" class="editDiv"
                                                  style="position:relative !important;">
                                                <label style="background:none;box-shadow:none"></label>
                                            </span>
                                            @error('merchant_rocket')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    <div class="card-header mt-3" style="padding: 0">
                                        <h6>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Extra Setting
                                            @else
                                                Extra Setting
                                            @endif
                                        </h6>
                                    </div>

                                    <div style="margin-top: 5px;">
                                        <label for="button_status" class="form-label"
                                               style="margin-bottom: 0;cursor: pointer;">
                                            <input type="checkbox" id="button_status"
                                                   name="button_status"
                                                   onclick="onClickHandler('button_status')"
                                                   value="{{ $data->button_status ?? old('button_status') }}"
                                                   @if (isset($data->button_status) && $data->button_status == 1) checked @endif>&nbsp;&nbsp;Button
                                            Status
                                            <div class="switch" style="display: none;">
                                                <input id="rtl_status" name="rtl_status"
                                                       class="check-toggle check-toggle-round-flat"
                                                       type="checkbox"
                                                       onclick="onClickHandler('rtl_status')"
                                                       @if (isset($data->rtl_status) && $data->rtl_status == 1) checked @endif
                                                >
                                                <label for="rtl_status" style="margin-bottom:0px"></label>
                                                <span class="on">L</span>
                                                <span class="off">R</span>
                                            </div>

                                        </label>
                                        @error('button_status')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div style="margin-top: 5px;">
                                        <label for="theme_lock" class="form-label"
                                               style="margin-bottom: 0;cursor: pointer;">
                                            <input type="checkbox" id="theme_lock"
                                                   name="theme_lock"
                                                   onclick="onClickHandler('theme_lock')"
                                                   value="{{ $data->theme_lock ?? old('theme_lock') }}"
                                                   @if (isset($data->theme_lock) && $data->theme_lock == 1) checked @endif>&nbsp;&nbsp;Theme
                                            Lock
                                        </label>
                                        @error('theme_lock')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{--registration system card--}}
                                    <div class="card mb-4 mt-4">
                                        <div class="card-header">
                                            <h4>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Registration System
                                                @else
                                                    Registration System
                                                @endif
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                <label for="auth_type" class="form-label">
                                                    <input class="onchangeSubmit" type="radio"
                                                           placeholder="Type here"
                                                           id="auth_type" name="auth_type" value="email"
                                                        {{ $store->auth_type == 'email' ? 'checked' : '' }}>&nbsp;&nbsp;Email
                                                    Login <span
                                                        style="color:red;">{{ $data->email == null
                                                        ? '(Please set your store email for client OTP verification. )'
                                                        : '' }}</span></label>

                                                @error('auth_type')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="PhoneLogin" class="form-label">
                                                    <input class="onchangeSubmit" type="radio"
                                                           placeholder="Type here"
                                                           id="PhoneLogin" name="auth_type" value="phone"
                                                        {{ $store->auth_type == 'phone' ? 'checked' : '' }}>&nbsp;&nbsp;Phone
                                                    Login</label>

                                                @error('auth_type')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="EasyOrder" class="form-label">
                                                    <input class="onchangeSubmit" type="radio"
                                                           placeholder="Type here"
                                                           id="EasyOrder" name="auth_type" value="EasyOrder"
                                                        {{ $store->auth_type == 'EasyOrder' ? 'checked' : '' }}>&nbsp;&nbsp;Easy
                                                    Order</label>

                                                @error('auth_type')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="EmailEasyOrder" class="form-label">
                                                    <input class="onchangeSubmit" type="radio"
                                                           placeholder="Type here"
                                                           id="EmailEasyOrder" name="auth_type"
                                                           value="EmailEasyOrder"
                                                        {{ $store->auth_type == 'EmailEasyOrder' ? 'checked' : '' }}>&nbsp;&nbsp;Email
                                                    Easy Order</label>
                                                @error('auth_type')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    আপডেট
                                @else
                                    Update
                                @endif
                            </button>
                        </div>
                    </div>
                </form>

                <div class="modal fade" id="paymentTextModal" style="left:0 !important; display:none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.savePaymentMethodText') }}" method="post">
                                @csrf

                                <input type="hidden" name="column" id="columnText" value="">

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <label class="form-label">Message</label>
                                    <div class="input-group input-group-outline mb-3">
                                        <input type="text" name="message" id="message" class="form-control">
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    <button type="Submit" class="btn btn-info">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @php
        $smsStatus = haveSMS($store->id);
    @endphp
@endsection

@push('scripts')

    {{--file load function--}}
    <script>
        var loadFile = function (event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        var loadFavicon = function (event) {
            var faviconOutput = document.getElementById('faviconOutput');
            faviconOutput.src = URL.createObjectURL(event.target.files[0]);
            faviconOutput.onload = function () {
                URL.revokeObjectURL(faviconOutput.src) // free memory
            }
        };
    </script>

    <script>
        var updateCurrency = function () {
            $url = "/common/flash_exchange_rate";
            var id = $("#selectCurrency").val();
            $.get($url, {
                id: id
            }, function (data) {
                $('#currencyBtn').html("Current Rate " + data.currency_rate);
                // swal.fire(
                //     'success!',
                //     "Currency Exchange Rate Updated Successfuly 🥱",
                //     'success'
                // );
            });
        }
    </script>
    {{--form submit function--}}
    <script>
        let defaultAuthType = $('input[name="auth_type"]:checked').val();

        $('.onchangeSubmit').click(function () {
            let AuthType = $(this).val();

            if ($(this).val() == 'phone') {
                if ({{ $smsStatus }} == 0) {
                    swal.fire({
                        title: "You don't have any SMS pack",
                        text: "Please purchase a SMS pack to activate this feature",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Buy Now',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            defaultAuthType = AuthType;
                            // $('#onchangeSubmit').submit();
                            location.replace(`{{ route('payment.payments') }}`);
                            // form.submit();
                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            $('input[name="auth_type"][value="' + defaultAuthType + '"]').prop('checked', true);
                            swal.fire(
                                'Cancelled', 'Cancel :)', 'error'
                            );
                        } else if (result.dismiss === Swal.DismissReason.backdrop) {
                            $('input[name="auth_type"][value="' + defaultAuthType + '"]').prop('checked', true);
                        }
                    });
                } else {
                    $('#onchangeSubmit').submit();
                }
            } else if ($(this).val() == 'EasyOrder') {
                if ({{ $smsStatus }} == 0) {
                    swal.fire({
                        title: "You don't have any SMS pack",
                        text: "Please purchase a SMS pack to get this feature",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Buy Now',
                        cancelButtonText: 'Force Active!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            // $('#onchangeSubmit').submit();
                            location.replace(`{{ route('payment.payments') }}`);
                            // form.submit();
                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            $('#onchangeSubmit').submit();
                        } else if (result.dismiss === Swal.DismissReason.backdrop) {
                            $('input[name="auth_type"][value="' + defaultAuthType + '"]').prop('checked', true);
                        }
                    });
                } else {
                    $('#onchangeSubmit').submit();
                }
            } else if ($(this).val() == 'EmailEasyOrder') {
                if ({{ $checkoutFormEmail }} == 0) {
                    swal.fire({
                        title: "You didn't check mark email input for checkout page",
                        text: "Please check mark email input for Email Easy Order.",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Active',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            $('#onchangeSubmit').submit();
                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            $('input[name="auth_type"][value="' + defaultAuthType + '"]').prop('checked', true);
                            swal.fire(
                                'Cancelled', 'Cancel :)', 'error'
                            );
                        } else if (result.dismiss === Swal.DismissReason.backdrop) {
                            $('input[name="auth_type"][value="' + defaultAuthType + '"]').prop('checked', true);
                        }
                    });
                } else {
                    $('#onchangeSubmit').submit();
                }
            } else {
                $('#onchangeSubmit').submit();
            }

        });

        let defaultChecked = $('input[name="selected_shipping_area"]:checked').val();

        $('.onchangeShippingMethod').click(function () {
            let shippingArea = $(this).val();
            if (shippingArea) {
                swal.fire({
                    title: "You you sure to change default shipping area?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $url = "/update/default-shipping-area";
                        $.get($url, {
                            id: shippingArea
                        }, function (data) {
                            if (data.status) {
                                defaultChecked = shippingArea;
                                swal.fire(
                                    'success!',
                                    "Update default shipping area successfully",
                                    'success'
                                );
                            } else {
                                $('input[name="selected_shipping_area"][value="' + defaultChecked + '"]').prop('checked', true);
                                swal.fire(
                                    'error!',
                                    "Default shipping area not updated!",
                                    'error'
                                );
                            }
                        });
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        $('input[name="selected_shipping_area"][value="' + defaultChecked + '"]').prop('checked', true);
                        swal.fire(
                            'Cancelled', 'Cancel :)', 'error'
                        );
                    }
                });
            }
        });


        $('#closemodal').click(function () {
            $('#modal1').modal('hide');
        });
        $('#closemodal1').click(function () {
            $('#modal2').modal('hide');
        });
        $(function () {
            $('[data-toggle="modal"]').hover(function () {
                var modalId = $(this).data('target');
                $(modalId).modal('show');
                $(modalId).css({
                    opacity: 1
                });

            });
        });

        const paymentTextHandler = (type) => {
            if (type !== "") {
                let message = "";

                if (type === "cod_text") {
                    message = "{{$data->cod_text ?? "Cash On Delivery"}}";
                } else if (type === "bkash_text") {
                    message = "{{$data->bkash_text ?? "Bkash Payment"}}";
                } else if (type === "paypal_text") {
                    message = "{{$data->paypal_text ?? "Paypal"}}";
                } else if (type === "stripe_text") {
                    message = "{{$data->stripe_text ?? "Stripe"}}";
                } else if (type === "uddoktapay_text") {
                    message = "{{$data->uddoktapay_text ?? "Uddokta Pay"}}";
                } else if (type === "ap_text") {
                    message = "{{$data->ap_text ?? "Advance Payment"}}";
                } else if (type === "merchant_bkash_text") {
                    message = "{{$data->merchant_bkash_text ?? "Merchant Bkash"}}";
                } else if (type === "merchant_nagad_text") {
                    message = "{{$data->merchant_nagad_text ?? "Merchant Nagad"}}";
                } else if (type === "merchant_rocket_text") {
                    message = "{{$data->merchant_rocket_text ?? "Merchant Rocket"}}";
                }

                $("#columnText").val(type);
                $("#message").val(message);
                openModal("paymentTextModal")
            }
        }

        // Open the modal
        function openModal(id) {
            const modalElement = document.getElementById(id); // Find the modal by ID
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement); // Initialize a new modal instance
                modal.show();
            }
        }

        // // Close the modal
        // function closeModal(id) {
        //     const modalElement = document.getElementById(id); // Find the modal by ID
        //     if (modalElement) {
        //         const modal = bootstrap.Modal.getInstance(modalElement); // Get the existing modal instance
        //         if (modal) {
        //             modal.hide();
        //         }
        //     }
        // }


        const onClickHandler = (column = "") => {
            if (column === "") {
                return false;
            }

            let checkbox = $('input[name="' + column + '"]');
            let isChecked = checkbox.prop("checked"); // Get the checked state before request

            if (column === "button_status") {
                if (isChecked) {
                    $('.switch').show(); // show switch
                } else {
                    $('.switch').hide();
                }
            }

            let url = "{{ route('admin.updateSettingData') }}";

            $.get(url, {
                column: column,
                status: isChecked ? "active" : "inactive" // Send the checkbox state
            })
                .done(function (data) {
                    if (data.status) {
                        swal.fire(
                            "Success!",
                            "Update setting successfully",
                            "success"
                        );
                    } else {
                        // Rollback to previous state if update fails
                        checkbox.prop("checked", !isChecked);
                        swal.fire(
                            "Error!",
                            "Setting not updated!",
                            "error"
                        );
                    }
                })
                .fail(function () {
                    // Handle AJAX errors (e.g., network failure)
                    checkbox.prop("checked", !isChecked);
                    swal.fire(
                        "Error!",
                        "Something went wrong!",
                        "error"
                    );
                });
        };

        document.addEventListener('DOMContentLoaded', function () {
            let checkbox = $('input[name="button_status"]');
            let isChecked = checkbox.prop("checked");

            if (isChecked) {
                $('.switch').show(); // show switch
            } else {
                $('.switch').hide();
            }
        });


    </script>

@endpush
