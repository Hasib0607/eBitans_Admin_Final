@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" src="{{ asset('admin/src/bootstrap-tagsinput.css') }}"/>
    <!-- Include stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush
@section('content')
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


        /* This is copied from https://github.com/blueimp/jQuery-File-Upload/blob/master/css/jquery.fileupload.css */
        .fileinput-button {
            position: relative;
            overflow: hidden;
        }

        #imgList {
            display: contents;
        }

        .fileinput-button input {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            opacity: 0;
            -ms-filter: "alpha(opacity=0)";
            font-size: 200px;
            direction: ltr;
            cursor: pointer;
        }

        .thumb {
            height: 80px;
            width: 100px;
            border: 1px solid #000;
        }

        ul.thumb-Images li {
            width: 120px;
            float: left;
            display: inline-block;
            vertical-align: top;
            height: 120px;
        }

        .img-wrap {
            position: relative;
            display: inline-block;
            font-size: 0;
        }

        .img-wrap .close {
            position: absolute;
            top: 2px;
            right: 2px;
            z-index: 100;
            background-color: #d0e5f5;
            padding: 5px 2px 2px;
            color: #000;
            font-weight: bolder;
            cursor: pointer;
            opacity: 0.5;
            font-size: 23px;
            line-height: 10px;
            border-radius: 50%;
        }

        .img-wrap:hover .close {
            opacity: 1;
            background-color: #ff0000;
        }

        .FileNameCaptionStyle {
            font-size: 12px;
        }
    </style>

    <main class="main-content position-relative h-100 border-radius-lg">

        {{-- Page top bar menu --}}
        @include('admin.admin_top_bar_category.index')


        {{--Form input section--}}
        <section class="container content-main">
            <div class="row">
                <form action="{{ route('admin.productSave') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf

                    <div class="row">

                        {{--Header title--}}
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            নতুন পণ্য যোগ করুন
                                        @else
                                            Add New Product
                                        @endif
                                    </h2>
                                </div>
                            </div>
                        </div>

                        {{--Left input card--}}
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            মৌলিক
                                        @else
                                            Basic
                                        @endif
                                    </h4>
                                    <span style="font-size:14px;color:red">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            * চিহ্নিত
                                            ক্ষেত্রগুলি বাধ্যতামূলক
                                        @else
                                            Fields marked with * are mandatory
                                        @endif
                                    </span>
                                </div>
                                <div class="card-body">
                                    @if (Session::has('error_message'))
                                        <div class="alert alert-danger" style="color:#fff">
                                            {{ Session::get('error_message') }}</div>
                                    @endif
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label d-flex justify-content-between">
                                            <div>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    পণ্য শিরোনাম
                                                @else
                                                    Product title
                                                @endif
                                                <span class="req">*</span>
                                            </div>
                                            @include('admin.product.share.layout-custom-design', ['title'=>'title', 'index' => '0'])
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="product_name"
                                               name="product_name" value="{{ old('product_name') }}">
                                        @error('product_name')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label  d-flex justify-content-between">
                                            <div>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    পূর্ণ বিবরণ
                                                @else
                                                    Full description
                                                @endif
                                                <span class="req">*</span>
                                            </div>
                                            @include('admin.product.share.layout-custom-design', ['title'=>'description', 'index' => '1'])
                                        </label>
                                        <div id="toolbar-container">

                                            <select class="ql-header">
                                                <option value="1">Heading 1
                                                <option/>
                                                <option value="2">Heading 2
                                                <option/>
                                                <option value="3">Heading 3
                                                <option/>
                                                <option value="">Normal
                                                <option/>
                                            </select>
                                            <span class="ql-formats">
                                                <button class="ql-list" value="ordered"/>
                                                <button class="ql-list" value="bullet"/>
                                                <button class="ql-indent" value="-1"/>
                                                <button class="ql-indent" value="+1"/>
                                            </span>
                                            <button class="ql-bold" data-toggle="tooltip" data-placement="bottom"
                                                    title="Bold"/>
                                            <button class="ql-italic" data-toggle="tooltip" data-placement="bottom"
                                                    title="Add italic text <cmd+i>"/>
                                            <button class="ql-underline"/>
                                            <button class="ql-image"/>

                                        </div>

                                        <textarea hidden placeholder="Type here" class="form-control"
                                                  id="quill_html" rows="40"
                                                  name="description"> {{ old('description') }} </textarea>
                                        <div id="editor-container" style="height:200px;">
                                            {!! old('description') !!}
                                        </div>
                                        @error('description')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror

                                    </div>

                                    @php
                                        $userData = getUserData();
                                        $store_id = $userData['store_id'];
                                    @endphp

                                    @if (ModulusStatus($store_id, 115))
                                        <div class="col-md-12">
                                            <div class="mb-4">
                                                <label class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        Video Link
                                                    @else
                                                        Video Link
                                                    @endif
                                                    {{-- <span class="req">*</span> --}}
                                                </label>
                                                <div class="row gx-2">
                                                    <input placeholder="YouTube Embed Video Link" type="url"
                                                           class="form-control" name="video_link"
                                                           value="{{ old('video_link') }}">
                                                    @error('video_link')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (ModulusStatus($store_id, 118))
                                        <div class="col-md-12">
                                            <div class="mb-4">
                                                <label class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        Expiry Date
                                                    @else
                                                        Expiry Date
                                                    @endif
                                                </label>
                                                <div class="row gx-2">
                                                    <input type="date" class="form-control" id="expiry_date"
                                                           value="{{ old('expiry_date') }}"
                                                           name="expiry_date">
                                                    @error('expiry_date')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                        <?php

                                        if (Illuminate\Support\Facades\Auth::check()) {
                                            $user = Illuminate\Support\Facades\Auth::user();
                                        }

                                        //   ** if user is staff then there have to
                                        //  findout store owner id which is into customers table **
                                        if ($user->type == 'staff') {
                                            $staff_assigned_store = DB::table('stores')
                                                ->where('id', '=', $user->store_id)
                                                ->first();
                                            // owner/admin id from stores table
                                            $admin_id = $staff_assigned_store->user_id;

                                        }
                                        // if user is not staff then set admin id as their user id
                                        if ($user->type !== 'staff') {
                                            $admin_id = $user->id;
                                        }

                                        $customer = DB::table('customers')
                                            ->where('uid', '=', $admin_id)
                                            ->first();


                                        $digitalproductmodules = DB::table('moduluses')
                                            ->where('id', '=', 110)
                                            ->where('status', '=', '1')
                                            ->first();

                                        if ($digitalproductmodules) {
                                            $digitalproductstatus = DB::table('buy_moduluses')
                                                ->where('modulus_id', '=', $digitalproductmodules->id)
                                                ->where('store_id', '=', $customer->active_store)
                                                ->where('status', '=', '1')
                                                ->first();
                                        } else {
                                            $digitalproductstatus = null;
                                        }

                                        ?>

                                    {{-- Digital product input --}}
                                    @if ($digitalproductstatus)
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <div class="mb-4">
                                                    <label for="product_link" class="form-label">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            পণ্য লিংক
                                                        @else
                                                            Product link
                                                        @endif
                                                        <span class="req">*</span>
                                                    </label>
                                                    <input type="text" placeholder="Type here" class="form-control"
                                                           id="product_link"
                                                           name="product_link" value="{{ old('product_link') }}">
                                                    @error('product_link')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-4">
                                                <label class="form-label d-flex justify-content-between">
                                                    <div>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            এস কে ইউ
                                                        @else
                                                            SKU
                                                        @endif
                                                        <span class="req">*</span>
                                                    </div>
                                                    @include('admin.product.share.layout-custom-design', ['title'=>'SKU', 'index' => '2'])
                                                </label>
                                                <div class="row gx-2">
                                                    <input placeholder="SKU" type="text" class="form-control"
                                                           name="SKU" value="{{ old('SKU') }}">
                                                    @error('SKU')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-4">
                                                <label class="form-label d-flex justify-content-between">
                                                    <div>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            নিয়মিত
                                                            মূল্য
                                                        @else
                                                            Regular price ({{$currency->symbol}})
                                                        @endif
                                                        <span class="req">*</span>
                                                    </div>
                                                    @include('admin.product.share.layout-custom-design', ['title'=>'price', 'index' => '3'])
                                                </label>
                                                <div class="row gx-2">
                                                    <input placeholder="Regular price ({{$currency->code}})"
                                                           type="number" step="0.01"
                                                           min="0" class="form-control"
                                                           name="regular_price" value="{{ old('regular_price') }}">
                                                    @error('regular_price')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-4">
                                                <label class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        দ্রব্য মূল্য
                                                    @else
                                                        Product Cost ({{$currency->code}})
                                                    @endif
                                                </label>
                                                <input placeholder="" type="number"
                                                       min="0" step="0.01" class="form-control" name="cost"
                                                       value="{{ old('cost') }}">
                                                @error('cost')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-4">
                                                <label class="form-label d-flex justify-content-between">
                                                    <div>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            পরিমাণ
                                                        @else
                                                            Quantity
                                                        @endif
                                                        <span class="req">*</span>
                                                    </div>
                                                    @include('admin.product.share.layout-custom-design', ['title'=>'quantity', 'index' => '4'])
                                                </label>
                                                <input placeholder="" type="number"
                                                       min="0" class="form-control"
                                                       name="quantity" value="{{ old('quantity') }}" id="productQty">
                                                @error('quantity')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ডিসকাউন্ট
                                                    টাইপ
                                                @else
                                                    Discount Type
                                                @endif
                                                <span class="req">*</span>
                                            </div>
                                            <select class="form-select" name="discount_type">
                                                <option value="fixed"
                                                    {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ফিক্সড
                                                    @else
                                                        Fixed
                                                    @endif
                                                </option>
                                                <option value="percent"
                                                    {{ old('discount_type') == 'percent' ? 'selected' : '' }}>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        পার্সেন্ট
                                                    @else
                                                        Percent
                                                    @endif
                                                </option>
                                                <option value="no_discount"
                                                    {{ old('discount_type') == 'no_discount' ? 'selected' : '' }}>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        নো
                                                        ডিসকাউন্ট
                                                    @else
                                                        No Discount
                                                    @endif
                                                </option>
                                            </select>
                                            @error('discount_type')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-4">
                                                <label class="form-label d-flex justify-content-between">
                                                    <div>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            ডিসকাউন্ট মূল্য
                                                        @else
                                                            Discount price ({{$currency->symbol}})
                                                        @endif
                                                    </div>
                                                    @include('admin.product.share.layout-custom-design', ['title'=>'discount_price', 'index' => '6'])
                                                </label>
                                                <input placeholder="Discount price ({{$currency->code}})" type="number"
                                                       min="0" step="0.01" class="form-control"
                                                       name="promotional_price"
                                                       value="{{ old('promotional_price') }}">
                                                @error('promotional_price')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-4">
                                                <label class="form-label d-flex justify-content-between">
                                                    <div>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            বার কোড
                                                        @else
                                                            Bar Code
                                                        @endif
                                                    </div>
                                                    @include('admin.product.share.layout-custom-design', ['title'=>'bar_code', 'index' => '7'])
                                                </label>
                                                <input placeholder="" type="number"
                                                       min="0" class="form-control"
                                                       name="barcode"
                                                       value="{{ old('barcode') }}">
                                                @error('barcode')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-4">
                                                <label for="product_name" class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ওজন
                                                    @else
                                                        Weight
                                                    @endif
                                                </label>
                                                <input type="text" placeholder="kg" class="form-control"
                                                       id="weight" name="weight" value="{{ old('weight') }}">
                                                @error('weight')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-4">
                                                <label for="product_name" class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        বহন
                                                        খরচ
                                                    @else
                                                        Shipping fees ({{$currency->code}})
                                                    @endif
                                                </label>
                                                <input type="number"
                                                       min="0" step="0.01"
                                                       placeholder="Shipping fees ({{$currency->code}})"
                                                       class="form-control"
                                                       id="shipping_fee" name="shipping_fee"
                                                       value="{{ old('shipping_fee') }}">
                                                @error('shipping_fee')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($customizable) && $customizable)
                                <div class="card mb-4">
                                    <div class="card-header  d-flex justify-content-between">
                                        <h4>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                মৌলিক
                                            @else
                                                Additional Details
                                            @endif
                                        </h4>
                                        <div class="w-50">
                                            <div class="input-group">
                                                <select class="form-select h-100" id="type-define">
                                                    <option selected>Choose..</option>
                                                    <option value="title">Title</option>
                                                    <option value="subtitle">Sub-Title</option>
                                                    <option value="description">Description</option>
                                                    <option value="button">Button</option>
                                                    <option value="image">Image</option>
                                                </select>
                                                <button class="btn btn-primary" id="design-add" type="button">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="design-list">
                                            @php
                                                $count = 9;
                                            @endphp
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{--Right input card--}}
                        <div class="col-lg-3">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            মিডিয়া
                                        @else
                                            Media
                                        @endif
                                        <span class="req">*</span>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="input-upload" style="padding: 0;">
                                        <input type="hidden" class="form-control" id="store_id" name="store_id"
                                               value="{{ $store_id }}">
                                        <label for="image">
                                        </label>
                                        <output id="Filelist"></output>
                                        <input type="file" class="form-control" id="image" name="image[]"
                                               multiple accept="image/*">
                                        @error('image')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                        <br>
                                        @if ($moduleIsNull == 1)
                                            <label class="form-check"
                                                   style="opacity:0; position:absolute; left:9999px;">
                                                <input type="checkbox" class="form-check-input" id="is_checked"
                                                       style="opacity:0; position:absolute; left:9999px;"
                                                       name="is_checked"
                                                       value="1"{{ $moduleIsNull == 1 ? 'checked' : 0 }}>
                                                <span
                                                    class="form-check-label">Yes, I converted it to webp file!</span>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            সংগঠন
                                        @else
                                            Organization
                                        @endif
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 mb-3">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ক্যাটাগরি
                                                @else
                                                    Category
                                                @endif
                                                <span class="req">*</span>
                                            </label>


                                                <?php
                                                $category = DB::table('categories')
                                                    ->where('parent', 0)
                                                    ->where('store_id', $store_id)
                                                    ->where('status', 'active')
                                                    ->get();
                                                ?>

                                            <select class="form-select" name="category" id="category">
                                                <option>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        নির্বাচন করুন
                                                    @else
                                                        Select
                                                    @endif
                                                </option>
                                                @foreach ($category as $cat)
                                                    @isset($cat)
                                                        <option value="{{ $cat->id }}"
                                                            {{ old('category') == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if(count($category) <= 0)
                                                <a href="{{ URL::to('category') }}" class="btn btn-primary mt-3">Create
                                                    Category</a>
                                            @endif
                                            @error('category')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-3">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সাব ক্যাটাগরি
                                                @else
                                                    Sub-category
                                                @endif
                                            </label>
                                            <select class="form-select" name="subcategory" id="subcategory">
                                            </select>
                                            @error('subcategory')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ব্র্যান্ড
                                                @else
                                                    Brand
                                                @endif
                                            </label>
                                                <?php
                                                $brands = DB::table('brands')
                                                    ->where('store_id', $store_id)
                                                    ->get();
                                                ?>
                                            <select class="form-select" name="brand" id="brand">
                                                <option value="null">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ব্র্যান্ড নির্বাচন করুন
                                                    @else
                                                        Select Brand
                                                    @endif
                                                </option>
                                                @foreach ($brands as $brand)
                                                    @isset($brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('brand')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সরবরাহকারী
                                                @else
                                                    Supplier
                                                @endif
                                            </label>
                                                <?php
                                                $suppliers = DB::table('suppliers')
                                                    ->where('store_id', $store_id)
                                                    ->get();
                                                ?>
                                            <select class="form-select" name="supplier" id="brand">
                                                <option value="null">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        সরবরাহকারী নির্বাচন করুন
                                                    @else
                                                        Select Supplier
                                                    @endif
                                                </option>
                                                @foreach ($suppliers as $supplier)
                                                    @isset($supplier)
                                                        <option
                                                            value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('supplier')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ট্যাগ
                                                @else
                                                    Tags
                                                @endif
                                            </label>
                                            <input type="text" value="{{ old('tags') }}" class="form-control"
                                                   data-role="tagsinput" name="tags"
                                                   style="width:100%;display: block;">
                                            <div class="error" style="font-size: 11px; color: red;">
                                                Enter a comma after each tag
                                            </div>
                                            @error('tags')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    এসইও
                                                    কীওয়ার্ড
                                                @else
                                                    SEO Keywords
                                                @endif
                                            </label>
                                            <input type="text" value="{{ old('seo') }}" class="form-control"
                                                   id="product_name" data-role="tagsinput" name="seo"
                                                   style="width:100%;display: block;">
                                            <div class="error" style="font-size: 11px; color: red;">
                                                Enter a comma after each tag
                                            </div>
                                            @error('seo')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label for="best_sell" class="form-label">
                                                <input type="checkbox" id="best_sell" name="best_sell">&nbsp;&nbsp;Best
                                                Sell</label>
                                            @error('best_sell')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label for="feature" class="form-label">
                                                <input type="checkbox" id="feature"
                                                       name="feature">&nbsp;&nbsp;Feature</label>
                                            @error('feature')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-2">
                                            <label for="pse" class="form-label">
                                                <input type="checkbox" id="pse" name="pse"
                                                       value="1">&nbsp;&nbsp;Request For Product খুঁজো
                                                List</label>

                                            @error('pse')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-9">
                            @if (ModulusStatus($store_id, 114))
                                {{--Product Variant card--}}
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ভেরিয়েন্ট
                                                    @else
                                                        Product Variants
                                                    @endif
                                                </h4>
                                            </div>
                                            <div class="col-6" style="text-align:right">
                                                <a href="javascript:void(0)" id="attrishow"><i
                                                        class="fa fa-arrow-down"></i></a>
                                                <a href="javascript:void(0)" id="attrihide"><i
                                                        class="fa fa-arrow-up"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="attri-div">

                                            {{--Variant select option--}}
                                            <div class="col-md-3">
                                                <label for="">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ভেরিয়েন্ট টাইপ
                                                    @else
                                                        Variantion Type
                                                    @endif
                                                </label>
                                                <select class="form-control" name="att" id="attributes">
                                                    <option value="none">Select</option>
                                                    <option value="color">Color & size</option>
                                                    <option value="onlycolor">Color</option>
                                                    <option value="unit">Unit</option>
                                                    <option value="size">Size</option>
                                                </select>
                                            </div>


                                            {{--Color and size variant card--}}
                                            <div id="colorrss" class="col-lg-12 mt-3">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped" id="officers-table">
                                                        <tbody>
                                                            <?php $i = 0; ?>
                                                        <tr id="new" style="margin-top:5px;">
                                                            <td>
                                                                <label>Color:</label>
                                                                <select name="cs_color[]" id="color"
                                                                        class="form-control" step="any">
                                                                    <option value=""> Select Color</option>
                                                                        <?php
                                                                        $colors = DB::table('colors')
                                                                            ->where('store_id', $store_id)
                                                                            ->orderBy('position', 'asc')
                                                                            ->get();

                                                                        ?>
                                                                    @if (isset($colors))
                                                                        @foreach ($colors as $cl)
                                                                            <option value="{{ $cl->code }}">
                                                                                {{ $cl->name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td>
                                                                    <?php
                                                                    $size = DB::table('sizes')
                                                                        ->where('store_id', $store_id)
                                                                        ->orderBy('position', 'asc')
                                                                        ->get();
                                                                    ?>
                                                                @if (isset($size))
                                                                    @foreach ($size as $key => $sz)
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <div class="row">
                                                                                    <div class="row-md-6">
                                                                                        <label>size</label>
                                                                                    </div>
                                                                                    <div class="row-md-6">
                                                                                        <div
                                                                                            style="display: flex !important; gap: 10px !important;">
                                                                                            <input type="checkbox"
                                                                                                   onclick="checkBox({{ $key }})"
                                                                                                   id="checkBoxStatus{{ $key }}"
                                                                                                   name="sid[0][]"
                                                                                                   value="yes">
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   name="cs_size[0][]"
                                                                                                   value="{{ $sz->name }}"
                                                                                                   readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label>Quantity</label>
                                                                                <input type="number"
                                                                                       min="0"
                                                                                       class="form-control colorSizeQty"
                                                                                       name="cs_qty[0][]"
                                                                                       id="checkBoxWrite{{ $key }}"
                                                                                       onchange="variantQtyCheck(this, 'color')"
                                                                                       readonly
                                                                                       placeholder="Enter Quantity"
                                                                                       value="">
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label>Additional Price</label>
                                                                                <input type="number"
                                                                                       min="0"
                                                                                       class="form-control"
                                                                                       name="cs_price[0][]"
                                                                                       placeholder="Additional Price"
                                                                                       value="0">
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label>Media</label>
                                                                                <input type="file"
                                                                                       class="form-control"
                                                                                       onchange="variantImage(event)"
                                                                                       accept="image/*"
                                                                                       name="cs_Image[0][]"
                                                                                />
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a class="remove-officer-button mt-3 "
                                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                                   title="Delete"><img
                                                                        src="{{ URL::to('/') }}/img/delete.png"
                                                                        alt="" width="30px"
                                                                        style="margin-bottom:5px;"></a>
                                                                <br>
                                                                <a onclick="addRow(0)" data-bs-toggle="tooltip"
                                                                   data-bs-placement="top" title="Add"><img
                                                                        src="{{ URL::to('/') }}/img/add.png"
                                                                        alt="" width="30px"></a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            {{--Only color variant card--}}
                                            <div id="onlycolors" class="col-lg-12 mt-3">
                                                <table class="table table-stripped" id="officers-table3">
                                                    <tbody>
                                                    <tr id="new3" style="margin-top:5px;">
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    Color
                                                                </div>
                                                                <div class="col-md-3">
                                                                    Quantity
                                                                </div>
                                                                <div class="col-md-2">
                                                                    Additional Price
                                                                </div>
                                                                <div class="col-md-3">
                                                                    Media
                                                                </div>
                                                            </div>
                                                            <div class="row" style="margin-top:5px;">
                                                                <div class="col-md-2">
                                                                    <select name="c_color[]" id="color"
                                                                            class="form-control" step="any">
                                                                        <option> Select Color</option>
                                                                            <?php
                                                                            $colorsss = DB::table('colors')
                                                                                ->where('store_id', $store_id)
                                                                                ->orderBy('position', 'asc')
                                                                                ->get();
                                                                            ?>
                                                                        @if (isset($colorsss))
                                                                            @foreach ($colorsss as $cl)
                                                                                <option value="{{ $cl->code }}">
                                                                                    {{ $cl->name }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="number"
                                                                           class="form-control onlyColorQty"
                                                                           name="c_qty[]"
                                                                           onchange="variantQtyCheck(this, 'onlycolor')"
                                                                           placeholder="Enter Quantity"
                                                                           min="0" value="">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="number" class="form-control"
                                                                           name="c_price[]" placeholder="Enter Price"
                                                                           min="0" value="0">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file"
                                                                           class="form-control"
                                                                           name="c_Image[]"
                                                                           onchange="variantImage(event)"
                                                                           accept="image/*"
                                                                    />
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <a class="remove-officer-button3 mt-3"
                                                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                                                       title="Delete"><img
                                                                            src="{{ URL::to('/') }}/img/delete.png"
                                                                            alt="" width="30px"
                                                                            style="margin-bottom:5px;"></a>
                                                                    <br>
                                                                    <a class="" onclick="addOnlycolor()"
                                                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                                                       title="Add"><img
                                                                            src="{{ URL::to('/') }}/img/add.png"
                                                                            alt="" width="30px"></a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            {{--Unit variant card--}}
                                            <div id="unittss" class="col-lg-12 mt-3">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped" id="officers-table1">
                                                        <tbody>
                                                            <?php $i = 0; ?>
                                                        <tr id="new1" style="margin-top:5px;">
                                                            <td class="mt-1">
                                                                <div class="row">
                                                                    <div class="col-md-2 mt-1">
                                                                        Volume
                                                                    </div>
                                                                    <div class="col-md-2 mt-1">
                                                                        Unit
                                                                    </div>
                                                                    <div class="col-md-2 mt-1">
                                                                        Quantity
                                                                    </div>
                                                                    <div class="col-md-3 mt-1">
                                                                        Additional Price
                                                                    </div>
                                                                    <div class="col-md-3 mt-1">
                                                                        Media
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top:5px;">
                                                                    <div class="col-md-2 mt-1">
                                                                        <input type="number" step="0.01"
                                                                               class="form-control" name="u_volume[]"
                                                                               value="">
                                                                    </div>
                                                                    <div class="col-md-2 mt-1">
                                                                        <select name="u_unit[]" id="color"
                                                                                class="form-control" step="any">
                                                                            <option> Select Unit</option>
                                                                                <?php
                                                                                $color = DB::table('units')
                                                                                    ->where('store_id', $store_id)
                                                                                    ->get();

                                                                                ?>
                                                                            @if (isset($color))
                                                                                @foreach ($color as $cl)
                                                                                    <option value="{{ $cl->name }}">
                                                                                        {{ $cl->name }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2 mt-1">
                                                                        <input type="number"
                                                                               class="form-control unitQty"
                                                                               min="0"
                                                                               name="u_qty[]"
                                                                               onchange="variantQtyCheck(this, 'unit')"
                                                                               placeholder="Enter Quantity" value="">
                                                                    </div>
                                                                    <div class="col-md-3 mt-1">
                                                                        <input type="number" class="form-control"
                                                                               min="0"
                                                                               name="u_price[]"
                                                                               placeholder="Enter Price"
                                                                               value="0">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="file"
                                                                               class="form-control"
                                                                               name="u_Image[]"
                                                                               onchange="variantImage(event)"
                                                                               accept="image/*"
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="mt-1">
                                                                <a class="remove-officer-button1  mt-3"
                                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                                   title="Delete"><img
                                                                        src="{{ URL::to('/') }}/img/delete.png"
                                                                        alt="" width="30px"
                                                                        style="margin-bottom:5px;"></a>
                                                                <br>
                                                                <a onclick="addUnit()" data-bs-toggle="tooltip"
                                                                   data-bs-placement="top" title="Add"><img
                                                                        src="{{ URL::to('/') }}/img/add.png"
                                                                        alt="" width="30px"></a>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            {{--Size variant card--}}
                                            <div id="sizess" class="col-lg-12 mt-3">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped" id="officers-table2"
                                                           style="width: 99%">
                                                        <tbody>
                                                            <?php $i = 0; ?>
                                                        <tr id="new2" style="margin-top:5px;">
                                                            <td class="mt-1">
                                                                <div class="row">
                                                                    <div class="col-md-3 mt-1">
                                                                        size
                                                                    </div>
                                                                    <div class="col-md-3 mt-1">
                                                                        Quantity
                                                                    </div>
                                                                    <div class="col-md-3 mt-1">
                                                                        Additional Price
                                                                    </div>
                                                                    <div class="col-md-3 mt-1">
                                                                        Media
                                                                    </div>
                                                                </div>
                                                                    <?php
                                                                    $size = DB::table('sizes')
                                                                        ->where('store_id', $store_id)
                                                                        ->orderBy('position', 'asc')
                                                                        ->get();
                                                                    ?>
                                                                @if (isset($size))
                                                                    @foreach ($size as $key => $sz)
                                                                        <div class="row" style="margin-top:5px;">
                                                                            <div class="col-md-3 mt-1">
                                                                                <input type="text" class="form-control"
                                                                                       name="s_size[]"
                                                                                       value="{{ $sz->name }}" readonly>
                                                                            </div>
                                                                            <div class="col-md-3 mt-1">
                                                                                <input type="number"
                                                                                       min="0"
                                                                                       class="form-control sizeQty"
                                                                                       name="s_qty[]"
                                                                                       onchange="variantQtyCheck(this, 'size')"
                                                                                       placeholder="Enter Quantity"
                                                                                       value="">
                                                                            </div>
                                                                            <div class="col-md-3 mt-1">
                                                                                <input type="number"
                                                                                       min="0"
                                                                                       class="form-control"
                                                                                       name="s_price[]"
                                                                                       placeholder="Enter Price"
                                                                                       value="0">
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="file"
                                                                                       class="form-control"
                                                                                       name="s_Image[]"
                                                                                       onchange="variantImage(event)"
                                                                                       accept="image/*"
                                                                                />
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <button class="btn btn-info rounded font-sm hover-up" id="publishBtn" type="submit">
                                Publish
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </section>

    </main>
@endsection


@push('scripts')
    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        $(document).ready(function () {
            var count = {{$count ?? 0 }};
            $('#design-add').on('click', function () {
                console.log(count);
                const type = $('#type-define').val();
                const designList = $('#design-list');
                let buttonDesign = `@include('admin.product.share.layout-custom-design', ['title' => '${type}', 'type' => '${type}', 'index' => '${count}'])`;
                // let buttonDesign = ``;

                let positionLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                অবস্থান
@else
                Position
@endif`;
                let imageLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                ছবি
@else
                Image
@endif`;
                let linkLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                লিঙ্ক
@else
                Link
@endif`;
                let buttonLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                বোতাম
@else
                Button
@endif`;

                let descriptionLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                বিস্তারিত
@else
                Description
@endif`;

                let subtitleLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                উপ-শিরোনাম
@else
                sub-title
@endif`;
                let titleLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                উিরোনাম
@else
                title
@endif`;

                let oldDetails = `{!! Request::old('details', '') !!}`;
                let errorDetails = `@error('details')
                <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror`;

                let position = `<div class="mb-2 col">
                                        <label for="product_name" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${positionLabel} <!-- Blade-generated position label -->
                                                <span class="req">*</span>
                                            </div>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control bg-white" id="product_name"
                                               value="${count}" name="layouts[${count}][position]">
                                        <input type="hidden" name="layouts[${count}][type]" value="${type}">
                                    </div>`
                let image = `<div class="mb-2 col-md-6">
                                        <label for="product_name" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${imageLabel} <!-- Blade-generated position label -->
                                                <span class="req">*</span>
                                            </div>
                                        </label>
                                        <input type="file" placeholder="Type here" class="form-control bg-white" id="product_name"
                                                name="layouts[${count}][link]">
                                    </div>`
                let link = `<div class="mb-2 col">
                                        <label for="product_name" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${linkLabel} <!-- Blade-generated position label -->
                                                <span class="req">*</span>
                                            </div>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control bg-white" id="product_name"
                                                name="layouts[${count}][link]">
                                    </div>`
                let button_link = `<div class="mb-2 col">
                                        <label for="product_name" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${linkLabel} <!-- Blade-generated position label -->
                                                <span class="req">*</span>
                                            </div>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control bg-white" id="product_name"
                                                name="layouts[${count}][link]" value="/checkout">
                                    </div>`
                let button = `<div class="mb-4 col-md-6">
                                        <label for="product_name" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${buttonLabel}
                                                <span class="req">*</span>
                                            </div>
                                            ${buttonDesign} <!-- Blade content injected here -->
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control bg-white" id="product_name"
                                               name="layouts[${count}][button]" value='Checkout'>
                                    </div>`
                let description = `<div class="mb-4 col-md-12">
                                        <label class="form-label">
                                            ${descriptionLabel} <!-- Blade-generated description label -->
                                        </label>
                                        <textarea placeholder="Type here" class="form-control" id="editor${count}" rows="8" name="layouts[${count}][text]">
                                            ${oldDetails}
                                        </textarea>
                                        ${errorDetails}
                                    </div>`;
                let title = `<div class="mb-4 col-md-12">
                                        <label class="form-label">
                                            ${titleLabel} <!-- Blade-generated description label -->
                                        </label>
                                        <textarea placeholder="Type here" class="form-control" id="editor${count}" rows="8" name="layouts[${count}][text]">
                                            ${oldDetails}
                                        </textarea>
                                        ${errorDetails}
                                    </div>`;
                let subtitle = `<div class="mb-4 col-md-12">
                                        <label class="form-label">
                                            ${subtitleLabel} <!-- Blade-generated description label -->
                                        </label>
                                        <textarea placeholder="Type here" class="form-control" id="editor${count}" rows="8" name="layouts[${count}][text]">
                                            ${oldDetails}
                                        </textarea>
                                        ${errorDetails}
                                    </div>`;

                switch(type) {
                    case 'title':
                        let titleItem = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Title - ${count - 8}</h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="title">
                                <div class="row">
                                    ${position}
                                    ${title}
                                </div>
                            </div>`;
                        count++;
                        designList.append(titleItem);
                        break;
                    case 'subtitle':
                        let subtitleItem = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Subtitle - ${count - 8}</h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="subtitle">
                                <div class="row">
                                    ${position}
                                    ${subtitle}
                                </div>
                            </div>`;
                        count++;
                        designList.append(subtitleItem);
                        break;
                    case 'description':
                        let item = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Description - ${count - 8}</h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="description">
                                <div class="row">
                                    ${position}
                                    ${description}
                                </div>
                            </div>`;
                        count++;
                        designList.append(item);
                        break;
                    case 'button':
                        var buttonItem = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Button - ${count - 8} </h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="button" >
                                <div class="row">
                                    ${button}
                                    ${button_link}
                                </div>
                            </div>`;
                        count++;
                        designList.append(buttonItem);
                        break;
                    case 'image':
                        let imageItem = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Image - ${count - 8}</h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="image">
                                <div class="row">
                                    ${image}
                                    ${position}
                                    ${description}
                                </div>
                            </div>`;
                        count++;
                        designList.append(imageItem);
                        break;
                    default:
                    // code block
                }

                if(type === 'button') {
                }

                // Removing feature
                $(document).on('click', '.design-remove', function () {
                    $(this).closest('.design-item').remove();
                    console.log('Design removed');
                });

                const id = '#editor'+(count-1);

                CKEDITOR.ClassicEditor.create(document.querySelector(`#editor${count-1}`), {
                    // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format

                    ckfinder: {
                        uploadUrl: '{{ route('superadmin.blog.ck') . '?_token=' . csrf_token() }}',
                    },
                    toolbar: {
                        items: [
                            'exportPDF', 'exportWord', '|',
                            'findAndReplace', 'selectAll', '|',
                            'heading', '|',
                            'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript',
                            'removeFormat', '|',
                            'bulletedList', 'numberedList', 'todoList', '|',
                            'outdent', 'indent', '|',
                            'undo', 'redo',
                            '-',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                            'alignment', '|',
                            'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed',
                            '|',
                            'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                            'textPartLanguage', '|',
                            'sourceEditing'
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    // Changing the language of the interface requires loading the language file using the <script> tag.
                    // language: 'es',
                    list: {
                        properties: {
                            styles: true,
                            startIndex: true,
                            reversed: true
                        }
                    },
                    // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
                    heading: {
                        options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            },
                            {
                                model: 'heading4',
                                view: 'h4',
                                title: 'Heading 4',
                                class: 'ck-heading_heading4'
                            },
                            {
                                model: 'heading5',
                                view: 'h5',
                                title: 'Heading 5',
                                class: 'ck-heading_heading5'
                            },
                            {
                                model: 'heading6',
                                view: 'h6',
                                title: 'Heading 6',
                                class: 'ck-heading_heading6'
                            }
                        ]
                    },
                    // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
                    placeholder: 'Enter your page details',
                    // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
                    fontFamily: {
                        options: [
                            'default',
                            'Arial, Helvetica, sans-serif',
                            'Courier New, Courier, monospace',
                            'Georgia, serif',
                            'Lucida Sans Unicode, Lucida Grande, sans-serif',
                            'Tahoma, Geneva, sans-serif',
                            'Times New Roman, Times, serif',
                            'Trebuchet MS, Helvetica, sans-serif',
                            'Verdana, Geneva, sans-serif'
                        ],
                        supportAllValues: true
                    },
                    // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
                    fontSize: {
                        options: [10, 12, 14, 'default', 18, 20, 22],
                        supportAllValues: true
                    },
                    // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
                    // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
                    htmlSupport: {
                        allow: [{
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }]
                    },
                    // Be careful with enabling previews
                    // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                    htmlEmbed: {
                        showPreviews: true
                    },
                    // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
                    link: {
                        decorators: {
                            addTargetToExternalLinks: true,
                            defaultProtocol: 'https://',
                            toggleDownloadable: {
                                mode: 'manual',
                                label: 'Downloadable',
                                attributes: {
                                    download: 'file'
                                }
                            }
                        }
                    },
                    // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
                    mention: {
                        feeds: [{
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes',
                                '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread',
                                '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding',
                                '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }]
                    },
                    // The "super-build" contains more premium features that require additional configuration, disable them below.
                    // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
                    removePlugins: [
                        // These two are commercial, but you can try them out without registering to a trial.
                        // 'ExportPdf',
                        // 'ExportWord',
                        'CKBox',
                        'CKFinder',
                        'EasyImage',
                        // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                        // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                        // Storing images as Base64 is usually a very bad idea.
                        // Replace it on production website with other solutions:
                        // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                        // 'Base64UploadAdapter',
                        'RealTimeCollaborativeComments',
                        'RealTimeCollaborativeTrackChanges',
                        'RealTimeCollaborativeRevisionHistory',
                        'PresenceList',
                        'Comments',
                        'TrackChanges',
                        'TrackChangesData',
                        'RevisionHistory',
                        'Pagination',
                        'WProofreader',
                        // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                        // from a local file system (file://) - load this site via HTTP server if you enable MathType
                        'MathType'
                    ]
                });

            })

        });
    </script>

    <script>
        // This sample still does not showcase all CKEditor 5 features (!)
        // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
        CKEDITOR.ClassicEditor.create(document.querySelector(".editor"), {
            // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format

            ckfinder: {
                uploadUrl: '{{ route('superadmin.blog.ck') . '?_token=' . csrf_token() }}',
            },
            toolbar: {
                items: [
                    'exportPDF', 'exportWord', '|',
                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript',
                    'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed',
                    '|',
                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                    'textPartLanguage', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            // Changing the language of the interface requires loading the language file using the <script> tag.
            // language: 'es',
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
            heading: {
                options: [{
                    model: 'paragraph',
                    title: 'Paragraph',
                    class: 'ck-heading_paragraph'
                },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
            placeholder: 'Enter your page details',
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
            fontSize: {
                options: [10, 12, 14, 'default', 18, 20, 22],
                supportAllValues: true
            },
            // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
            // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
            htmlSupport: {
                allow: [{
                    name: /.*/,
                    attributes: true,
                    classes: true,
                    styles: true
                }]
            },
            // Be careful with enabling previews
            // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
            htmlEmbed: {
                showPreviews: true
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
            link: {
                decorators: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://',
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
            mention: {
                feeds: [{
                    marker: '@',
                    feed: [
                        '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes',
                        '@chocolate', '@cookie', '@cotton', '@cream',
                        '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread',
                        '@gummi', '@ice', '@jelly-o',
                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding',
                        '@sesame', '@snaps', '@soufflé',
                        '@sugar', '@sweet', '@topping', '@wafer'
                    ],
                    minimumCharacters: 1
                }]
            },
            // The "super-build" contains more premium features that require additional configuration, disable them below.
            // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
            removePlugins: [
                // These two are commercial, but you can try them out without registering to a trial.
                // 'ExportPdf',
                // 'ExportWord',
                'CKBox',
                'CKFinder',
                'EasyImage',
                // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                // Storing images as Base64 is usually a very bad idea.
                // Replace it on production website with other solutions:
                // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                // 'Base64UploadAdapter',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                // from a local file system (file://) - load this site via HTTP server if you enable MathType
                'MathType'
            ]
        });
    </script>
    <script>
        $('.ck-placeholder').attr('data-placeholder', 'Enter your page details');
    </script>

    <!-- Initialize Quill editor -->
    <script>
        $(document).ready(function () {
            var quill = new Quill('#editor-container', {
                modules: {
                    toolbar: '#toolbar-container'
                },
                placeholder: 'Enter Yours Product Description...',
                theme: 'snow'
            });

            quill.on('text-change', function (delta, oldDelta, source) {
                document.getElementById("quill_html").value = quill.root.innerHTML;
            });

            // Enable all tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Can control programmatically too
            $('.ql-italic').mouseover();
            setTimeout(function () {
                $('.ql-italic').mouseout();
            }, 2500);
        });
    </script>


    <script src="{{ asset('admin/src/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput-angular.js') }}"></script>

    <script>
        //I added event handler for the file upload control to access the files properties.
        document.addEventListener("DOMContentLoaded", init, false);

        //To save an array of attachments
        var AttachmentArray = [];

        //counter for attachment array
        var arrCounter = 0;

        //to make sure the error message for number of files will be shown only one time.
        var filesCounterAlertStatus = false;

        //un ordered list to keep attachments thumbnails
        var ul = document.createElement("ul");
        ul.className = "thumb-Images";
        ul.id = "imgList";

        function init() {
            //add javascript handlers for the file upload event
            document
                .querySelector("#image")
                .addEventListener("change", handleFileSelect, false);
        }

        //the handler for file upload event
        function handleFileSelect(e) {
            //to make sure the user select file/files
            if (!e.target.files) return;

            //To obtaine a File reference
            var files = e.target.files;

            // Loop through the FileList and then to render image files as thumbnails.
            for (var i = 0, f;
                 (f = files[i]); i++) {
                //instantiate a FileReader object to read its contents into memory
                var fileReader = new FileReader();

                // Closure to capture the file information and apply validation.
                fileReader.onload = (function (readerEvt) {
                    return function (e) {
                        //Apply the validation rules for attachments upload
                        ApplyFileValidationRules(readerEvt);

                        //Render attachments thumbnails.
                        RenderThumbnail(e, readerEvt);

                        //Fill the array of attachment
                        FillAttachmentArray(e, readerEvt);
                    };
                })(f);

                // Read in the image file as a data URL.
                // readAsDataURL: The result property will contain the file/blob's data encoded as a data URL.
                // More info about Data URI scheme https://en.wikipedia.org/wiki/Data_URI_scheme
                fileReader.readAsDataURL(f);
            }
            document
                .getElementById("image")
                .addEventListener("change", handleFileSelect, false);
        }

        //To remove attachment once user click on x button
        jQuery(function ($) {
            $("div").on("click", ".img-wrap .close", function () {
                var id = $(this)
                    .closest(".img-wrap")
                    .find("img")
                    .data("id");

                //to remove the deleted item from array
                var elementPos = AttachmentArray.map(function (x) {
                    return x.FileName;
                }).indexOf(id);
                if (elementPos !== -1) {
                    AttachmentArray.splice(elementPos, 1);
                }

                //to remove image tag
                $(this)
                    .parent()
                    .find("img")
                    .not()
                    .remove();

                //to remove div tag that contain the image
                $(this)
                    .parent()
                    .find("div")
                    .not()
                    .remove();

                //to remove div tag that contain caption name
                $(this)
                    .parent()
                    .parent()
                    .find("div")
                    .not()
                    .remove();

                //to remove li tag
                var lis = document.querySelectorAll("#imgList li");
                for (var i = 0;
                     (li = lis[i]); i++) {
                    if (li.innerHTML == "") {
                        li.parentNode.removeChild(li);
                    }
                }
            });
        });

        //Apply the validation rules for attachments upload
        function ApplyFileValidationRules(readerEvt) {
            var storeId = document.getElementById("store_id").value;
            var moduleIsNull = {{ $moduleIsNull }};

            //To check file type according to upload conditions
            if (CheckFileType(readerEvt.type) == false) {
                $('#image').val('');
                swal.fire(
                    'Error!',
                    "The file (" + readerEvt.name + ") does not match the upload conditions, You can only upload jpg/png/gif/webp/jpeg files 🥱",
                    'error'
                );
                // e.preventDefault();
                return;
            }

            // //To check file Size according to upload conditions
            if (moduleIsNull == 1) {
                if (CheckFileSize(readerEvt.size, 6000000) == false) {
                    handleSizeError(6);
                    return;
                }
            } else {
                if (CheckFileSize(readerEvt.size, 200000) == false) {
                    handleSizeError(200);
                    return;
                }
            }

            //To check files count according to upload conditions
            if (CheckFilesCount(AttachmentArray) == false) {
                if (!filesCounterAlertStatus) {
                    filesCounterAlertStatus = true;
                    $('#image').val('');
                    swal.fire(
                        'Error!',
                        "You have added more than 10 files. According to upload conditions you can upload 10 files maximum 🥱",
                        'error'
                    );
                }
                // e.preventDefault();
                return;
            }
        }

        // Helper function to compare file size
        function CheckFileSize(fileSize, maxSize) {
            return fileSize < maxSize;
        }

        // Helper function to handle size error
        function handleSizeError(maxSizeInMB) {
            var moduleIsNull = {{ $moduleIsNull }};
            var message = "The file does not match the upload conditions. ";

            if (moduleIsNull == 1) {
                message += "The maximum file size for uploads should not exceed " + maxSizeInMB + " MB 🥱";
            } else {
                message += "The maximum file size for uploads should not exceed " + maxSizeInMB + " KB 🥱";
            }

            $('#image').val('');
            swal.fire('Error!', message, 'error');
            // e.preventDefault();
        }

        //To check file type according to upload conditions
        function CheckFileType(fileType) {
            if (fileType == "image/jpeg") {
                return true;
            } else if (fileType == "image/png") {
                return true;
            } else if (fileType == "image/gif") {
                return true;
            } else if (fileType == "image/webp") {
                return true;
            } else if (fileType == "image/jpg") {
                return true;
            } else {
                return false;
            }
            return true;
        }

        //To check files count according to upload conditions
        function CheckFilesCount(AttachmentArray) {
            //Since AttachmentArray.length return the next available index in the array,
            //I have used the loop to get the real length
            var len = 0;
            for (var i = 0; i < AttachmentArray.length; i++) {
                if (AttachmentArray[i] !== undefined) {
                    len++;
                }
            }
            //To check the length does not exceed 10 files maximum
            if (len > 9) {
                return false;
            } else {
                return true;
            }
        }

        //Render attachments thumbnails.
        function RenderThumbnail(e, readerEvt) {
            var li = document.createElement("li");
            ul.appendChild(li);
            li.innerHTML = [
                '<div class="img-wrap"> <span class="close">&times;</span>' +
                '<img class="thumb" class="w-100" src="',
                e.target.result,
                '" title="',
                escape(readerEvt.name),
                '" data-id="',
                readerEvt.name,
                '"/>' + "</div>"
            ].join("");

            // var div = document.createElement("div");
            // div.className = "FileNameCaptionStyle";
            // li.appendChild(div);
            // div.innerHTML = [readerEvt.name].join("");
            document.getElementById("Filelist").insertBefore(ul, null);
        }

        //Fill the array of attachment
        function FillAttachmentArray(e, readerEvt) {
            AttachmentArray[arrCounter] = {
                AttachmentType: 1,
                ObjectType: 1,
                FileName: readerEvt.name,
                FileDescription: "Attachment",
                NoteText: "",
                MimeType: readerEvt.type,
                Content: e.target.result.split("base64,")[1],
                FileSizeInBytes: readerEvt.size
            };
            arrCounter = arrCounter + 1;
        }
    </script>

    <script>
        $(document).ready(function () {
            $('#colorrss').hide();
            $('#unittss').hide();
            $('#sizess').hide();
            $('#shiphide').hide();
            $('#shipping-div').hide();
            $('#attrihide').hide();
            $('#attri-div').hide();
            $('#onlycolors').hide();
            $('#attributes').on('change', function () {
                var l = this.value;
                if (l == 'none') {
                    $('#colorrss').hide();
                    $('#unittss').hide();
                    $('#sizess').hide();
                    $('#onlycolors').hide();
                } else if (l == 'color') {
                    $('#colorrss').show();
                    $('#unittss').hide();
                    $('#sizess').hide();
                    $('#onlycolors').hide();
                } else if (l == 'unit') {
                    $('#colorrss').hide();
                    $('#unittss').show();
                    $('#sizess').hide();
                    $('#onlycolors').hide();
                } else if (l == 'onlycolor') {
                    $('#colorrss').hide();
                    $('#unittss').hide();
                    $('#sizess').hide();
                    $('#onlycolors').show();
                } else {
                    $('#colorrss').hide();
                    $('#unittss').hide();
                    $('#sizess').show();
                    $('#onlycolors').hide();
                }
            });
            $('#shipshow').on('click', function () {
                $('#shipping-div').show();
                $('#shiphide').show();
                $('#shipshow').hide();
            });
            $('#shiphide').on('click', function () {
                $('#shipping-div').hide();
                $('#shiphide').hide();
                $('#shipshow').show();
            });
            $('#attrishow').on('click', function () {
                $('#attri-div').show();
                $('#attrihide').show();
                $('#attrishow').hide();
            });
            $('#attrihide').on('click', function () {
                $('#attri-div').hide();
                $('#attrihide').hide();
                $('#attrishow').show();
            });
        })
    </script>

    <script>
        function checkBox(p) {
            // alert(p);
            // alert($('#checkBoxStatus'+p).is(":checked"));

            if ($('#checkBoxStatus' + p).is(":checked")) {
                $('#checkBoxWrite' + p).attr("readonly", false);
            } else {
                $('#checkBoxWrite' + p).val("");
                $('#checkBoxWrite' + p).attr("readonly", true);
            }

        }

        $(document).ready(function () {
            $('input[name="input"]').tagsinput({
                trimValue: true,
                confirmKeys: [13, 44, 32],
                focusClass: 'my-focus-class'
            });
            $('.bootstrap-tagsinput input').on('focus', function () {
                $(this).closest('.bootstrap-tagsinput').addClass('has-focus');
            }).on('blur', function () {
                $(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
            });
        });
    </script>

    @if (ModulusStatus($store_id, 114))
        <script>
            // Add Color and size variant row
            // function addRows() {
            //     var col = $('#new').html();
            //     $("table tbody").append('<tr>' + col + '</tr>');
            // }

            // Color and size variant row add
            function addRow(y) {
                var colors = {!! json_encode($colors, JSON_HEX_TAG) !!};
                color = [];
                colors.forEach(function (data) {
                    color += ` <option value="` + data.code + `">` + data.name + `</option>`
                });
                // console.log(color);
                var sizes = {!! json_encode($size, JSON_HEX_TAG) !!};
                size = [];
                index = document.getElementById('index').value;

                i = document.getElementById('index').value = index + 1;
                var j = 0;
                var o = 0;
                y = y + 1;
                sizes.forEach(function (data) {
                    o++;
                    p = o + i + 1;
                    // console.log(data.name);
                    size += ` <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="row-md-6">
                                        <label>size</label>
                                    </div>
                                    <div class="row-md-6">
                                        <div
                                            style="display: flex !important; gap: 10px !important;">
                                            <input type="checkbox" onclick="checkBox(` + p + `)" id="checkBoxStatus` + p + `"  name="sid[` + y + `][]" value="yes">
                                            <input type="text" class="form-control" name="cs_size[` + y + `][]" value="` + data.name + `" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Quantity</label>
                                <input type="number" class="form-control colorSizeQty" onchange="variantQtyCheck(this, 'color')" id="checkBoxWrite` + p + `" readonly name="cs_qty[` + y + `][]" placeholder="Enter Quantity" value="">
                            </div>
                            <div class="col-md-3">
                                <label>Additional Price</label>
                                 <input type="number" class="form-control" name="cs_price[` + y + `][]" placeholder="Enter Price" value="0">
                            </div>
                            <div class="col-md-3">
                                <label>Media</label>
                                <input type="file" class="form-control" onchange="variantImage(event)" accept="image/*" name="cs_Image[` + y + `][]" />
                            </div>
                        </div>`;
                    j++;

                });
                i++;
                // console.log(size);
                index = document.getElementById('index').value;

                addindex = document.getElementById('index').value = index + 1;

                var col = `<tr id="new" style="margin-top:5px;">
                            <td>
                            <label>Color:</label>
                                <select name="cs_color[]" id="color" class="form-control" step="any">
                                    <option> Select Color</option>
                                    ` + color + `
                                </select>
                            </td>
                            <td>
                              ` + size + `
                            </td>
                            <td>
                                <a class="remove-officer-button mt-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><img src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px" style="margin-bottom:5px;"></a>
                                <br>
                                <a onclick="addRow(` + y + `)" data-bs-toggle="tooltip" data-bs-placement="top" title="Add"><img src="{{ URL::to('/') }}/img/add.png" alt="" width="30px"></a>
                            </td>
                        </tr>`;

                // console.log(col);

                $("#officers-table tbody").append(col);
            }

            // Add unit variant row
            function addUnit() {
                var col = $('#new1').html();
                $("#officers-table1 tbody").append('<tr>' + col + '</tr>');
            }

            // Add size variant row
            function addSize() {
                var col = $('#new2').html();
                $("#officers-table2 tbody").append('<tr>' + col + '</tr>');
            }

            // Add only color variant row
            function addOnlycolor() {
                var col = $('#new3').html();
                $("#officers-table3 tbody").append('<tr>' + col + '</tr>');
            }

            // Remove color and size row
            $("#officers-table").on('click', '.remove-officer-button', function (e) {
                var whichtr = $(this).closest("tr");

                // alert('worked'); // Alert does not work
                whichtr.remove();
            });

            // Remove only color row
            $("#officers-table3").on('click', '.remove-officer-button3', function (e) {
                var whichtr = $(this).closest("tr");

                // alert('worked'); // Alert does not work
                whichtr.remove();
            });

            // Remove unit row
            $("#officers-table1").on('click', '.remove-officer-button1', function (e) {
                var whichtr = $(this).closest("tr");

                // alert('worked'); // Alert does not work
                whichtr.remove();
            });

            // Remove size row
            // $("#officers-table2").on('click', '.remove-officer-button2', function (e) {
            //     var whichtr = $(this).closest("tr");
            //
            //     // alert('worked'); // Alert does not work
            //     whichtr.remove();
            // });


            // Variant quantity check with product total quantity
            const variantQtyCheck = (e, variantType) => {
                const productQty = document.getElementById('productQty').value;
                const variant_qty = e.value;

                let totalVariantQty = 0;

                switch (variantType) {
                    case 'color':
                        totalVariantQty = getVariantTotalQty("colorSizeQty");
                        break;
                    case 'onlycolor':
                        totalVariantQty = getVariantTotalQty("onlyColorQty");
                        break;
                    case 'unit':
                        totalVariantQty = getVariantTotalQty("unitQty");
                        break;
                    case 'size':
                        totalVariantQty = getVariantTotalQty("sizeQty");
                        break;
                    default:
                        totalVariantQty = 0;
                }

                // Check product quantity
                if (productQty.trim() === '' || productQty.trim() === '0') {
                    swal.fire(
                        'Warning!',
                        "Input product Quantity first 🥱",
                        'warning'
                    );
                    return;
                }

                // Check product quantity and variant quantity
                if (variant_qty.trim() !== "" && productQty.trim() !== '0') {
                    if (totalVariantQty > productQty) {
                        $('#publishBtn').prop('disabled', true);
                        swal.fire(
                            'Warning!',
                            "Product variant quantity exited 🥱",
                            'warning'
                        );
                    } else {
                        $('#publishBtn').prop('disabled', false);
                    }
                }
            }

            // Get total input quantity of color and size variant
            function getVariantTotalQty(className) {
                // Select elements by class name
                var inputs = document.getElementsByClassName(className);

                let totalQty = 0;

                // Iterate through the elements
                for (var i = 0; i < inputs.length; i++) {
                    // Access input values
                    var value = inputs[i].value;

                    if (value.trim() !== '') {
                        totalQty = (parseFloat(totalQty) + parseFloat(value));
                    }
                }

                return totalQty;
            }

            // Variant image file handler
            function variantImage(e) {
                if (!e.target.files[0]) return;

                //To obtaine a File reference
                var file = e.target.files[0];

                const reader = new FileReader();

                // Closure to capture the file information and apply validation.
                reader.onload = (function (readerEvt) {
                    return function (e) {
                        //Apply the validation rules for attachments upload
                        ApplyFileValidationRules(readerEvt);
                    };
                })(file);

                reader.readAsDataURL(file);
            }

        </script>
    @endif

    <script>
        jQuery('select[name="category"]').on('change', function () {
            // debugger;
            var val = $(this).val();
            // console.log(val);
            $('#subcategory').empty();
            var catid = $('select[name="category"]').val();
            $.get('/getsubcat', {
                catid: catid
            }, function (data) {
                // console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('#subcategory').append(
                        '<option value="' + data[i].id + '">' + data[i].name + '</option>'
                    );
                }
            });
        });
    </script>

@endpush
