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

        .tagBro {
            width: inherit !important;
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

        .oldImg-wrap {
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

        .oldImg-wrap .oldClose {
            position: absolute;
            top: 3px;
            right: 2px;
            z-index: 100;
            background-color: #d0e5f5;
            padding: 2px 3px 2px 3px;
            color: #000;
            font-weight: bolder;
            cursor: pointer;
            opacity: 0.5;
            font-size: 12px;
            line-height: 10px;
            border-radius: 50%;
        }

        .img-wrap:hover .close {
            opacity: 1;
            background-color: #ff0000;
        }

        .oldImg-wrap:hover .oldClose {
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
            <form action="{{ route('admin.updateproduct', $product['id']) }}" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="index" value="1" id="index">
                @csrf
                <div class="row">
                    {{--Header title--}}
                    <div class="col-9 mt-4 mb-4">
                        <div class="content-header row">
                            <div class="col-md-6">
                                <h2 class="content-title">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        এডিট পণ্য
                                    @else
                                        Edit Product
                                    @endif
                                </h2>
                            </div>

                            <div class="col-md-6" style="text-align:right">
                                <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button> -->
                                <!-- <button class="btn btn-info rounded font-sm hover-up">Publich</button> -->
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
                                        ক্ষেত্রগুলি
                                        বাধ্যতামূলক
                                    @else
                                        Fields marked with * are mandatory
                                    @endif
                                </span>
                            </div>
                            <div class="card-body">
                                @if (Session::has('error_message'))
                                    <div class="alert alert-danger"
                                         style="color:#fff">{{ Session::get('error_message') }}
                                    </div>
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
                                    <input type="text" placeholder="Type here" class="form-control" id="product_name"
                                           value="{{ $product['name'] }}" name="product_name">
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
                                            </option>
                                            <option value="2">Heading 2
                                            </option>
                                            <option value="3">Heading 3
                                            </option>
                                            <option value="">Normal
                                            </option>
                                        </select>
                                        <span class="ql-formats">
                                            <button class="ql-list" value="ordered"></button>
                                            <button class="ql-list" value="bullet"></button>
                                            <button class="ql-indent" value="-1"></button>
                                            <button class="ql-indent" value="+1"></button>
                                        </span>
                                        <button class="ql-bold" data-toggle="tooltip" data-placement="bottom"
                                                title="Bold"></button>
                                        <button class="ql-italic" data-toggle="tooltip" data-placement="bottom"
                                                title="Add italic text <cmd+i>"></button>
                                        <button class="ql-underline"></button>
                                        <button class="ql-image"></button>

                                    </div>

                                    <textarea hidden placeholder="Type here" class="form-control" id="quill_html"
                                              rows="4" name="description">{!! $product['description'] !!}</textarea>
                                    <div id="editor-container" style="height:200px;">
                                        {!! $product['description'] !!}
                                    </div>
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
                                                       value="{{ $product['video_link'] }}">
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
                                                       value="{{ $product['expiry_date'] ?? "" }}"
                                                       name="expiry_date">
                                                @error('expiry_date')
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
                                                <input placeholder="" type="text" class="form-control"
                                                       value="{{ $product['SKU'] }}" name="SKU">
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
                                                        Regular price ({{$current_currency->symbol}})
                                                    @endif
                                                    <span class="req">*</span>
                                                </div>
                                                @include('admin.product.share.layout-custom-design', ['title'=>'price', 'index' => '3'])
                                            </label>
                                            <div class="row gx-2">
                                                <input placeholder="Regular price" min="0" type="number" step="0.01"
                                                       class="form-control"
                                                       value="{{ $product['regular_price'] }}" name="regular_price">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    দ্রব্য
                                                    মূল্য
                                                @else
                                                    Product Cost ({{$current_currency->symbol}})
                                                @endif
                                            </label>
                                            <input placeholder="" type="number" min="0" step="0.01" class="form-control"
                                                   value="{{ $product['cost'] }}" name="cost">
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
                                            <input placeholder="" id="productQty" type="number" min="0.00"
                                                   class="form-control"
                                                   value="{{ $product['quantity'] }}" name="quantity">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label d-flex justify-content-between">
                                            <div>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ডিসকাউন্ট
                                                    টাইপ
                                                @else
                                                    Discount Type
                                                @endif
                                                <span class="req">*</span>
                                            </div>
                                            @include('admin.product.share.layout-custom-design', ['title'=>'discount_type', 'index' => '5'])
                                        </label>
                                        <select class="form-select" name="discount_type">
                                            <option value="fixed"
                                                    @if ($product['discount_type'] == 'fixed') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ফিক্সড
                                                @else
                                                    Fixed
                                                @endif
                                            </option>
                                            <option value="percent"
                                                    @if ($product['discount_type'] == 'percent') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    পার্সেন্ট
                                                @else
                                                    Percent
                                                @endif
                                            </option>
                                            <option value="no_discount"
                                                    @if ($product['discount_type'] == 'no_discount') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    নো
                                                    ডিসকাউন্ট
                                                @else
                                                    No Discount
                                                @endif
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label d-flex justify-content-between">
                                                <div>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ডিসকাউন্ট মূল্য
                                                    @else
                                                        Discount price ({{$current_currency->symbol}})
                                                    @endif
                                                </div>
                                                @include('admin.product.share.layout-custom-design', ['title'=>'discount_price', 'index' => '6'])
                                            </label>
                                            <input placeholder="$" type="number" min="0" step="0.01"
                                                   class="form-control"
                                                   value="{{ $product['promotional_price'] }}" name="promotional_price">
                                        </div>
                                    </div>
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
                                            <input placeholder="" type="number" class="form-control"
                                                   value="{{ $product['barcode'] }}" name="barcode">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ট্যাক্সের
                                                ধরন
                                            @else
                                                Tax Type
                                            @endif
                                        </label>
                                        <select class="form-select" name="tax_type">
                                            <option value="fixed" @if ($product['tax_type'] == 'fixed') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    স্থির
                                                @else
                                                    Fixed
                                                @endif
                                            </option>
                                            <option value="percent"
                                                    @if ($product['tax_type'] == 'percent') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    শতাংশ
                                                @else
                                                    Percent
                                                @endif
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label class="form-label d-flex justify-content-between">
                                                <div>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        করের হার
                                                    @else
                                                        Tax rate ({{$current_currency->symbol}})
                                                    @endif
                                                </div>
                                                @include('admin.product.share.layout-custom-design', ['title'=>'tax_rate', 'index' => '8'])
                                            </label>
                                            <input placeholder="$" type="number" min="0" step="0.01"
                                                   class="form-control"
                                                   value="{{ $product['tax_rate'] }}" name="tax_rate">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- card end// -->
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
                                        @if(isset($product['layout']) && count($product['layout']) > 0)
                                            <script src="{{ asset('admin/dist/js/ckeditor.js') }}"></script>
                                            @foreach($product['layout'] as $key=>$layout)
                                                @switch($layout['type'])
                                                    @case('title')
                                                        <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                            <input type="hidden" name="layouts[{{$count}}][id]"
                                                                   value="{{$layout['id']}}">
                                                            <div class="d-flex justify-content-between">
                                                                <h6>Title - {{$count-8}}</h6>
                                                                <i class="fa fa-times cursor-pointer design-remove"></i>
                                                            </div>
                                                            <input type="hidden" name="layouts[{{$count}}][type]"
                                                                   value="title">
                                                            <div class="row">
                                                                <div class="mb-2 col">
                                                                    <label for="product_name"
                                                                           class="form-label d-flex justify-content-between">
                                                                        <div>
                                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                                অবস্থান
                                                                            @else
                                                                                Position
                                                                            @endif
                                                                            <span class="req">*</span>
                                                                        </div>
                                                                    </label>
                                                                    <input type="text" placeholder="Type here"
                                                                           class="form-control bg-white"
                                                                           id="product_name"
                                                                           value="{{$layout['position']}}"
                                                                           name="layouts[{{$count}}][position]">
                                                                    <input type="hidden"
                                                                           name="layouts[{{$count}}][type]"
                                                                           value="title">
                                                                </div>
                                                                <div class="mb-4 col-md-12">
                                                                    <label class="form-label">
                                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                            উিরোনাম
                                                                        @else
                                                                            Title
                                                                        @endif
                                                                    </label>
                                                                    <textarea placeholder="Type here"
                                                                              class="form-control" id="editor{{$count}}"
                                                                              rows="8"
                                                                              name="layouts[{{$count}}][text]">
                                                                        {!! Request::old('details', $layout['text'] ?? '') !!}
                                                                    </textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('subtitle')
                                                        <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                            <input type="hidden" name="layouts[{{$count}}][id]"
                                                                   value="{{$layout['id']}}">
                                                            <div class="d-flex justify-content-between">
                                                                <h6>Sub-Title - {{$count - 8}}</h6>
                                                                <i class="fa fa-times cursor-pointer design-remove"></i>
                                                            </div>
                                                            <input type="hidden" name="layouts[{{$count}}][type]"
                                                                   value="title">
                                                            <div class="row">
                                                                <div class="mb-2 col">
                                                                    <label for="product_name"
                                                                           class="form-label d-flex justify-content-between">
                                                                        <div>
                                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                                অবস্থান
                                                                            @else
                                                                                Position
                                                                            @endif
                                                                            <span class="req">*</span>
                                                                        </div>
                                                                    </label>
                                                                    <input type="text" placeholder="Type here"
                                                                           class="form-control bg-white"
                                                                           id="product_name"
                                                                           value="{{$count}}"
                                                                           name="layouts[{{$count}}][position]">
                                                                    <input type="hidden"
                                                                           name="layouts[{{$count}}][type]"
                                                                           value="subtitle">
                                                                </div>
                                                                <div class="mb-4 col-md-12">
                                                                    <label class="form-label">
                                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                            উপ-শিরোনাম
                                                                        @else
                                                                            sub-title
                                                                        @endif
                                                                    </label>
                                                                    <textarea placeholder="Type here"
                                                                              class="form-control" id="editor{{$count}}"
                                                                              rows="8"
                                                                              name="layouts[{{$count}}][text]">
                                                                        {!! Request::old('details', $layout['text'] ?? '') !!}
                                                                    </textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('description')
                                                        <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                            <input type="hidden" name="layouts[{{$count}}][id]"
                                                                   value="{{$layout['id']}}">
                                                            <div class="d-flex justify-content-between">
                                                                <h6>Description - {{$count - 8}}</h6>
                                                                <i class="fa fa-times cursor-pointer design-remove"></i>
                                                            </div>
                                                            <input type="hidden" name="layouts[{{$count}}][type]"
                                                                   value="title">
                                                            <div class="row">
                                                                <div class="mb-2 col">
                                                                    <label for="product_name"
                                                                           class="form-label d-flex justify-content-between">
                                                                        <div>
                                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                                অবস্থান
                                                                            @else
                                                                                Position
                                                                            @endif
                                                                            <span class="req">*</span>
                                                                        </div>
                                                                    </label>
                                                                    <input type="text" placeholder="Type here"
                                                                           class="form-control bg-white"
                                                                           id="product_name"
                                                                           value="{{$layout['position']}}"
                                                                           name="layouts[{{$count}}][position]">
                                                                    <input type="hidden"
                                                                           name="layouts[{{$count}}][type]"
                                                                           value="description">
                                                                </div>
                                                                <div class="mb-4 col-md-12">
                                                                    <label class="form-label">
                                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                            বিস্তারিত
                                                                        @else
                                                                            Description
                                                                        @endif
                                                                    </label>
                                                                    <textarea placeholder="Type here"
                                                                              class="form-control" id="editor{{$count}}"
                                                                              rows="8"
                                                                              name="layouts[{{$count}}][text]">
                                                                        {!! Request::old('details', $layout['text'] ?? '') !!}
                                                                    </textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('button')
                                                        <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                            <input type="hidden" name="layouts[{{$count}}][id]"
                                                                   value="{{$layout['id']}}">
                                                            <div class="d-flex justify-content-between">
                                                                <h6>Button - {{$count - 8}}</h6>
                                                                <i class="fa fa-times cursor-pointer design-remove"></i>
                                                            </div>
                                                            <input type="hidden" name="layouts[{{$count}}][type]"
                                                                   value="title">
                                                            <div class="row">
                                                                <div class="mb-4 col-md-6">
                                                                    <label for="product_name"
                                                                           class="form-label d-flex justify-content-between">
                                                                        <div>
                                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                                বোতাম
                                                                            @else
                                                                                Button
                                                                            @endif
                                                                            <span class="req">*</span>
                                                                        </div>
                                                                        {{--                                                                        @dd(['title' => $layout['type'], 'type' => $layout['type'], 'index' => $count])--}}
                                                                        @include('admin.product.share.layout-custom-design', ['title' => $layout['type'], 'type' => $layout['type'], 'index' => $count])
                                                                    </label>
                                                                    <input type="text" placeholder="Type here"
                                                                           class="form-control bg-white"
                                                                           id="product_name"
                                                                           name="layouts[{{$count}}][button]"
                                                                           value="{{$layout['button']}}">
                                                                </div>
                                                                <input type="hidden" name="layouts[{{$count}}][type]"
                                                                       value="button">
                                                                <div class="mb-2 col">
                                                                    <label for="product_name"
                                                                           class="form-label d-flex justify-content-between">
                                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                            লিঙ্ক
                                                                        @else
                                                                            Link
                                                                        @endif
                                                                    </label>
                                                                    <input type="text" placeholder="Type here"
                                                                           class="form-control bg-white"
                                                                           id="product_name"
                                                                           name="layouts[{{$count}}][link]"
                                                                           value="{{$layout['link']}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @case('image')
                                                        <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                            <input type="hidden" name="layouts[{{$count}}][id]"
                                                                   value="{{$layout['id']}}">
                                                            <div class="d-flex justify-content-between">
                                                                <h6>Image - {{$count - 8}}</h6>
                                                                <i class="fa fa-times cursor-pointer design-remove"></i>
                                                            </div>
                                                            <input type="hidden" name="layouts[{{$count}}][type]"
                                                                   value="image">
                                                            <div class="row">
                                                                <div
                                                                    class="mb-2 col-md-12 d-flex justify-content-center">
                                                                    <img
                                                                        src="/assets/images/product/{{$layout['link']}}"
                                                                        alt="image" height="150px">
                                                                </div>
                                                                <div class="mb-2 col-md-6">
                                                                    <label for="product_name" class="form-label">
                                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                            ছবি
                                                                        @else
                                                                            Image
                                                                        @endif
                                                                        <span class="req">*</span>
                                                                    </label>
                                                                    <input type="file" placeholder="Type here"
                                                                           class="form-control bg-white"
                                                                           id="product_name"
                                                                           name="layouts[{{$count}}][link]"
                                                                           value="{{$layout['link']}}">
                                                                </div>
                                                                <div class="mb-2 col">
                                                                    <label for="product_name"
                                                                           class="form-label d-flex justify-content-between">
                                                                        <div>
                                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                                অবস্থান
                                                                            @else
                                                                                Position
                                                                            @endif
                                                                            <span class="req">*</span>
                                                                        </div>
                                                                    </label>
                                                                    <input type="text" placeholder="Type here"
                                                                           class="form-control bg-white"
                                                                           id="product_name"
                                                                           value="{{$layout['position']}}"
                                                                           name="layouts[{{$count}}][position]">
                                                                    <input type="hidden"
                                                                           name="layouts[{{$count}}][type]"
                                                                           value="image">
                                                                </div>
                                                                <div class="mb-4 col-md-12">
                                                                    <label class="form-label">
                                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                            বিস্তারিত
                                                                        @else
                                                                            Description
                                                                        @endif
                                                                    </label>
                                                                    <textarea placeholder="Type here"
                                                                              class="form-control" id="editor{{$count}}"
                                                                              rows="8" name="layouts[{{$count}}][text]">
                                                                        {!! Request::old('details', $layout['text'] ?? '') !!}
                                                                    </textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break;
                                                    @default
                                                        @break
                                                @endswitch
                                                <script>
                                                    CKEDITOR.ClassicEditor.create(document.querySelector(`#editor{{$count}}`), {
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
                                                @php
                                                    $count+= 1;
                                                @endphp
                                            @endforeach
                                        @endif
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
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="input-upload" style="">
                                    <!-- <img src="{{ URL::to('/') }}/img/upload.svg" alt="" style="max-width: 100px;margin-bottom: 20px;vertical-align: baseline;"> -->
                                    @if ($product['images'])
                                        @php
                                            $images = explode(',', $product['images']);
                                        @endphp

                                        @foreach ($images as $key => $image)
                                            <div class="oldImg-wrap">
                                                <a class="oldClose"
                                                   href="{{ URL::to('/') }}/product/removeimage/{{ $product['id'] }}/{{ $image }}">x</a>

                                                <img src="{{ URL::to('/') }}/assets/images/product/{{ $image }}"
                                                     style="padding:10px;border:1px solid black;margin-bottom:5px;"
                                                     width="60px" height="60px">


                                                <input type="hidden" class="form-control" id=""
                                                       name="oldImage[]" value="{{ $image }}">
                                            </div>
                                        @endforeach
                                    @endif
                                    <br>
                                    <output id="Filelist"></output>
                                    <br>
                                    <input type="file" class="form-control" id="image" name="image[]"
                                           accept="image/*" multiple>
                                    @error('image')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                    <br>
                                    @if ($moduleIsNull == 1)
                                        <label class="form-check" style="opacity:0; position:absolute; left:9999px;">
                                            <input type="checkbox" class="form-check-input" id="is_checked"
                                                   name="is_checked" value="1"{{ $moduleIsNull == 1 ? 'checked' : 0 }}>
                                            <span class="form-check-label">Yes, I converted it to webp file!</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                        </div> <!-- card end// -->
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
                                <div class="row gx-2">
                                    <div class="col-sm-6 mb-3">
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
                                                ->where('store_id', $store_id)
                                                ->where('status', 'active')
                                                ->where('parent', 0)
                                                ->get();
                                            ?>

                                        <select class="form-select" name="category" id="category">
                                            <option>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    নির্বাচন
                                                    করুন
                                                @else
                                                    Select
                                                @endif
                                            </option>
                                            @foreach ($category as $cat)
                                                @isset($cat)
                                                    <option value="{{ $cat->id }}"
                                                            @if ($product['category'] == $cat->id) selected @endif>{{ $cat->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                সাব
                                                ক্যাটাগরি
                                            @else
                                                Sub-category
                                            @endif
                                        </label>
                                        <select class="form-select" name="subcategory" id="subcategory">
                                            @if (isset($product['subcategory']))
                                                    <?php
                                                    $subcategory = DB::table('categories')
                                                        ->where('id', $product['subcategory'])
                                                        ->where('status', 'active')
                                                        ->first();
                                                    ?>
                                                <option value="{{ $subcategory->id ?? '0' }}">
                                                    {{ $subcategory->name ?? 'deleted' }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        @if(count($category) <= 0)
                                            <a href="{{ URL::to('category') }}" class="btn btn-primary">Create
                                                Category</a>
                                        @endif
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
                                                    ব্র্যান্ড
                                                    নির্বাচন করুন
                                                @else
                                                    Select Brand
                                                @endif
                                            </option>
                                            @foreach ($brands as $brand)
                                                @isset($brand)
                                                    <option value="{{ $brand->id }}"
                                                            @if (isset($product['brand']) && $product['brand'] == $brand->id) selected @endif>{{ $brand->name }}
                                                    </option>
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
                                        <select class="form-select" name="supplier" id="supplier">
                                            <option value="null">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সরবরাহকারী
                                                    নির্বাচন করুন
                                                @else
                                                    Select Supplier
                                                @endif
                                            </option>
                                            @foreach ($suppliers as $supplier)
                                                @isset($supplier)
                                                    <option value="{{ $supplier->id }}"
                                                            @if (isset($product['supplier']) && $product['supplier'] == $supplier->id) selected @endif>
                                                        {{ $supplier->name }}
                                                    </option>
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
                                        <input type="text" class="form-control" data-role="tagsinput" name="tags"
                                               value="{{ $product['tags'] }}"
                                               placeholder="Enter a comma after each
                                                        tag">
                                        <div class="error" style="font-size: 11px; color: red;">Enter a comma after each
                                            tag
                                        </div>
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
                                        <input type="text" value="{{ $product['seo_keywords'] }}" class="form-control"
                                               id="product_seo" data-role="tagsinput" name="seo"
                                               style="width:100%;display: block;">
                                        <div class="error" style="font-size: 11px; color: red;">Enter a comma after each
                                            seo keywords
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="best_sell" class="form-label">
                                            <input type="checkbox" id="best_sell" name="best_sell"
                                                   @if ($product['best_sell'] == 1) checked @endif>&nbsp;&nbsp;Best
                                            Sell</label>

                                        <!--<input type="text" value="" class="form-control" data-role="tagsinput" name="seo" style="width:100%;display: block;">-->
                                        @error('best_sell')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="feature" class="form-label">
                                            <input type="checkbox" id="feature" name="feature"
                                                   @if ($product['feature'] == 1) checked @endif>&nbsp;&nbsp;Feature</label>

                                        <!--<input type="text" value="" class="form-control" data-role="tagsinput" name="seo" style="width:100%;display: block;">-->
                                        @error('feature')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="pse" class="form-label">
                                            <input type="checkbox" id="pse" name="pse" value="1"
                                                   @if ($product['pse'] == 1 || $product['pse'] == 2) checked @endif>&nbsp;&nbsp;Show
                                            PSE List
                                            @if ($product['pse'] == 1)
                                                <span style="color: red;">(Requested)</span>
                                            @endif
                                        </label>

                                        <!--<input type="text" value="" class="form-control" data-role="tagsinput" name="seo" style="width:100%;display: block;">-->
                                        @error('feature')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div> <!-- row.// -->
                            </div>

                        </div> <!-- card end// -->
                    </div>


                    {{--Product Variant card--}}
                        <?php
                        $attri = DB::table('veriants')
                            ->where('pid', $product['id'])
                            ->get();
                        $attri_color = DB::table('veriants')
                            ->where('pid', $product['id'])
                            ->select('color')
                            ->get();
                        $attri_unit = DB::table('veriants')
                            ->where('pid', $product['id'])
                            ->where('color', null)
                            ->where('size', null)
                            ->select('volume')
                            ->get();
                        $attri_size = DB::table('veriants')
                            ->where('pid', $product['id'])
                            ->where('color', null)
                            ->select('size')
                            ->get();
                        $attri_onlycolor = DB::table('veriants')
                            ->where('pid', $product['id'])
                            ->where('size', null)
                            ->select('color')
                            ->get();
                        $select_sizess = DB::table('veriants')
                            ->where('pid', $product['id'])
                            ->where('color', null)
                            ->where('size', '!=', null)
                            ->get();
                        $select_unitsss = DB::table('veriants')
                            ->where('pid', $product['id'])
                            ->where('color', null)
                            ->where('size', null)
                            ->where('volume', '!=', null)
                            ->get();
                        $select_onlycolor = DB::table('veriants')
                            ->where('pid', $product['id'])
                            ->where('color', '!=', null)
                            ->where('size', null)
                            ->get();
                        $size = DB::table('sizes')
                            ->where('store_id', $store_id)
                            ->get();
                        $colors = DB::table('colors')
                            ->where('store_id', $store_id)
                            ->get();
                        ?>
                    <div class="col-lg-9">
                        @include('admin.product.share.variant-section')
                    </div>
                    <button class="btn btn-success rounded font-sm hover-up" id="updateBtn" type="submit"
                            name="update"
                            value="update">Update
                    </button>
                    <button class="btn btn-info rounded font-sm hover-up" id="publishBtn" type="submit">Publish
                    </button>

                    {{-- store_id, modulus_id --}}
                    @if (isset($product) && ModulusStatus($store_id, 1))
                        <a href="{{ route('admin.product.duplicate', ["id" => $product['id']]) }}"
                           class="btn btn-info rounded font-sm hover-up" style="background:rebeccapurple;"
                           id="duplicateBtn">
                            Duplicate
                        </a>
                    @endif


                </div>
            </form>
        </section>
        </div>
    </main>
@endsection

@push('scripts')
    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="{{ asset('admin/dist/js/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput-angular.js') }}"></script>

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

                switch (type) {
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

                if (type === 'button') {
                }

                // Removing feature
                $(document).on('click', '.design-remove', function () {
                    $(this).closest('.design-item').remove();
                    console.log('Design removed');
                });

                const id = '#editor' + (count - 1);

                CKEDITOR.ClassicEditor.create(document.querySelector(`#editor${count - 1}`), {
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
        // $('#tagBro').attr("placeholder", "input seo keywords");
        $('.tagBro').hide();
    </script>

    <script>
        function mouseOverShipping() {
            if ($('#shippingCheck').val() == 1) {
                $('#shipping-div').show();
                $('#shiphide').show();
                $('#shipshow').hide();
            }

        }

        function mouseOverShippingmouseOut() {
            if ($('#shippingCheck').val() == 1) {
                $('#shipping-div').hide();
                $('#shipshow').show();
                $('#shiphide').hide();
            }
        }

        function openShipping() {
            if ($('#shippingCheck').val() == 1) {
                $('#shippingCheck').val(0);
                $('#shiphide i').css("color", "#f1593a");
                $('#shipping-div').show();
                $('#shiphide').show();
                $('#shipshow').hide();
            } else {
                $('#shippingCheck').val(1)
            }

        }

        function mouseOverVariant() {
            if ($('#attriCheck').val() == 1) {
                $('#attri-div').show();
                $('#attrihide').show();
                $('#attrishow').hide();
            }

        }

        function mouseOverVariantmouseOut() {
            if ($('#attriCheck').val() == 1) {
                $('#attri-div').hide();
                $('#attrishow').show();
                $('#attrihide').hide();
            }
        }

        function openAttri() {
            if ($('#attriCheck').val() == 1) {
                $('#attriCheck').val(0);
                $('#attrihide i').css("color", "#f1593a");
            } else {
                $('#attriCheck').val(1)
            }

            $('#attri-div').show();
            $('#attrihide').show();
            $('#attrishow').hide();
        }
    </script>


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
            var moduleIsNull = {{ $moduleIsNull }};

            //To check file type according to upload conditions
            if (CheckFileType(readerEvt.type) == false) {
                $('#image').val('');
                swal.fire(
                    'Error!',
                    "The file (" +
                    readerEvt.name +
                    ") does not match the upload conditions, You can only upload jpg/png/gif/webp/jpeg files 🥱",
                    'error'
                );
                e.preventDefault();
                return;
            }

            // //To check file Size according to upload conditions
            if (moduleIsNull == 1) {
                if (CheckFileSize(readerEvt.size, 6000000) == false) {
                    handleSizeError(6);
                    return;
                }
            } else {
                console.log('false');
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
                e.preventDefault();
                return;
            }
        }

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
            e.preventDefault();
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
                '<img class="thumb" src="',
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
        $('.deleteattri').on('click', function () {
            var id = $(this).closest('tr').find('#attriid').val();
            var quantity = $(this).closest('tr').find('#qunty').val();
            var size = $(this).closest('tr').find('#sizs').val();
            var color = $(this).closest('tr').find('#clor').val();
            var additional_price = $(this).closest('tr').find('#additionalpricess').val();
            console.log(id);
            console.log(quantity);
            console.log(size);
            console.log(color);
            $.get('/deleteattribute', {
                id: id,
                quantity: quantity,
                size: size,
                color: color,
                additional_price: additional_price
            }, function (data) {
                console.log(data);
                window.location.reload();
            });
            debugger;
        });
        $('.deletesizeattri').on('click', function () {
            var id = $(this).closest('tr').find('#attriid').val();
            var quantity = $(this).closest('tr').find('#qunty').val();
            var size = $(this).closest('tr').find('#sizs').val();
            var additional_price = $(this).closest('tr').find('#additionalpricess').val();
            console.log(id);
            console.log(quantity);
            console.log(size);
            $.get('/deletesizeattribute', {
                id: id,
                quantity: quantity,
                size: size,
                additional_price: additional_price
            }, function (data) {
                console.log(data);
                window.location.reload();
            });
            debugger;
        });
        $('.deleteonlycolorattri').on('click', function () {
            var id = $(this).closest('tr').find('#attriid').val();
            var quantity = $(this).closest('tr').find('#qunty').val();
            var color = $(this).closest('tr').find('#color').val();
            var additional_price = $(this).closest('tr').find('#additionalpricess').val();
            console.log(id);
            console.log(quantity);
            console.log(color);
            $.get('/deleteonlycolorattribute', {
                id: id,
                quantity: quantity,
                color: color,
                additional_price: additional_price
            }, function (data) {
                console.log(data);
                window.location.reload();
            });
            debugger;
        });
        $('.deleteunitattri').on('click', function () {
            var id = $(this).closest('tr').find('#attriid').val();
            var quantity = $(this).closest('tr').find('#qunty').val();
            var volume = $(this).closest('tr').find('#volumess').val();
            var unit = $(this).closest('tr').find('#unitss').val();
            var additional_price = $(this).closest('tr').find('#additionalpricess').val();
            console.log(id);
            console.log(quantity);
            $.get('/deleteunitattribute', {
                id: id,
                quantity: quantity,
                volume: volume,
                unit: unit,
                additional_price: additional_price
            }, function (data) {
                console.log(data);
                window.location.reload();
            });
            debugger;
        });
        $(document).ready(function () {
                <?php
            if (isset($attri_colorss) && count($attri_colorss) > 0){

            }else{
                ?>
            $('#colorrss').hide();
                <?php
            }
                ?>
                <?php
            if (isset($attri_unitsss) && count($attri_unitsss) > 0){

            }else{
                ?>
            $('#unittss').hide();
                <?php
            }
                ?>
                <?php
            if (isset($attri_sizess) && count($attri_sizess) > 0){

            }else{
                ?>
            $('#sizess').hide();
                <?php
            }
                ?>
                <?php
            if (isset($attri_onlycolor) && count($attri_onlycolor) > 0){

            }else{
                ?>
            $('#onlycolors').hide();
                <?php
            }
                ?>
            $('#shiphide').hide();
            $('#shipping-div').hide();
            $('#attrihide').hide();
            $('#attri-div').hide();
            $('#attributes').on('change', function () {
                var l = this.value;
                if (l == 'none') {
                    changeVarient(l);
                    // $('#colorrss').hide();
                    // $('#unittss').hide();
                    // $('#sizess').hide();
                    // $('#onlycolors').hide();
                } else if (l == 'color') {
                    changeVarient(l);
                    // $('#colorrss').show();
                    // $('#unittss').hide();
                    // $('#sizess').hide();
                    // $('#onlycolors').hide();
                } else if (l == 'unit') {
                    changeVarient(l);
                    // $('#colorrss').hide();
                    // $('#unittss').show();
                    // $('#sizess').hide();
                    // $('#onlycolors').hide();
                } else if (l == 'onlycolor') {
                    changeVarient(l);
                    // $('#colorrss').hide();
                    // $('#unittss').hide();
                    // $('#sizess').hide();
                    // $('#onlycolors').show();
                } else {
                    changeVarient(l);
                    // $('#colorrss').hide();
                    // $('#unittss').hide();
                    // $('#sizess').show();
                    // $('#onlycolors').hide();
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
        });
    </script>

    <script>
        function checkBox(p) {
            if ($('#checkBoxStatus' + p).is(":checked")) {
                $('#checkBoxWrite' + p).attr("readonly", false);
                $('#checkBoxWrite' + p).attr("required", true);
            } else {
                $('#checkBoxWrite' + p).val("");
                $('#checkBoxWrite' + p).attr("readonly", true);
                $('#checkBoxWrite' + p).attr("required", false);
            }

        }

        $(document).ready(function () {
            $('input[name="input"]').tagsinput({
                trimValue: true,
                confirmKeys: [13, 44, 32],
                focusClass: 'my-focus-class'
            }).attr('min', 0);

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
            function addRow(i) {
                i++;

                var colors = {!! json_encode($colors, JSON_HEX_TAG) !!};
                // console.log(colors);
                color = [];
                colors.forEach(function (data) {
                    color += ` <option value="` + data.code + `">` + data.name + `</option>`
                });
                console.log(color);
                var sizes = {!! json_encode($size, JSON_HEX_TAG) !!};
                size = [];
                // index = document.getElementById('index').value;

                // i = document.getElementById('index').value = index + 1;
                var j = 0;
                var p = 8;
                var o = 1;
                sizes.forEach(function (data) {
                    // console.log(data.name);
                    o++
                    p = o + i + j + p + 2;
                    size += ` <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="row-md-6">
                                        <label>size</label>
                                    </div>
                                    <div class="row-md-6">
                                        <div
                                            style="display: flex !important; gap: 10px !important;">
                                            <input type="checkbox" onclick="checkBox(` + p + `)" id="checkBoxStatus` + p + `"  name="sid[` + i + `][]" value="yes">
                                            <input type="text" class="form-control" name="cs_size[` + i + `][]" value="` + data.name + `" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Quantity</label>
                                <input type="number" class="form-control colorSizeQty" onchange="variantQtyCheck(this, 'color')" id="checkBoxWrite` + p + `" readonly name="cs_qty[` + i + `][]" placeholder="Enter Quantity" value="">
                            </div>
                            <div class="col-md-3">
                                <label>Additional Price</label>
                                 <input type="number" class="form-control" name="cs_price[` + i + `][]" placeholder="Enter Price" value="0">
                            </div>
                            <div class="col-md-3">
                                <label>Media</label>
                                <input type="file" class="form-control" onchange="variantImage(event)" accept="image/*" name="cs_Image[` + i + `][]" />
                            </div>
                        </div>`;
                    j++;

                });
                // i++;
                // console.log(size);

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
                                                    <a  onclick="addRow(` + i + `)" data-bs-toggle="tooltip" data-bs-placement="top" title="Add"><img src="{{ URL::to('/') }}/img/add.png" alt="" width="30px"></a>
                                                </td>
                                            </tr>`
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
                        $('#updateBtn').prop('disabled', true);
                        $('#publishBtn').prop('disabled', true);
                        $('#duplicateBtn').prop('disabled', true);
                        swal.fire(
                            'Warning!',
                            "Product variant quantity exited 🥱",
                            'warning'
                        );
                    } else {
                        $('#updateBtn').prop('disabled', false);
                        $('#publishBtn').prop('disabled', false);
                        $('#duplicateBtn').prop('disabled', false);
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
        // Get sub-category on select category
        jQuery('select[name="category"]').on('change', function () {
            debugger;
            var val = $(this).val();
            console.log(val);
            $('#subcategory').empty();
            var catid = $('select[name="category"]').val();
            $.get('/getsubcat', {
                catid: catid
            }, function (data) {
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('#subcategory').append(
                        '<option value="' + data[i].id + '">' + data[i].name + '</option>'
                    );
                }
            });
        });

        // Variant delete on change variant
        function changeVarient(l) {
            swal.fire({
                title: 'আপনি ভেরিয়েন্ট পরিবর্তন করতে চাচ্ছেন?',
                text: "আপনি ভ্যারিয়েন্ট পরিবর্তন করলে, যদি আগে কোনো ভেরিয়েন্ট অ্যাড করে থাকেন সেটি ডিলিট হয়ে যাবে।",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
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

                    $url = "{{ route('admin.variantDelete', $product['id']) }}";
                    // alert($url);
                    $.get($url, {
                        product_id: {{ $product['id'] }}
                    }, function (data) {
                        console.log(data);
                        swal.fire(
                            'আপনি ডিলিট করে ফেলেছেন 🫢 ',
                            'আপনি ডিলিট করতে সফল হয়েছেন।',
                            'success'
                        );

                        $('.colorrss_ok').html('');
                        if (Object.keys(data).length !== 0) {
                            location.reload();
                        }
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        'Cancelled',
                        'Deletion Cancel 🥱',
                        'error'
                    );
                }
            })

        };
    </script>
@endpush
