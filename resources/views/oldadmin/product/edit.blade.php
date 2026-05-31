@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" src="{{ asset('admin/src/bootstrap-tagsinput.css') }}" />

    s
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
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item active">
                                <a href="{{ URL::to('/') }}/products">
                                    <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn') পণ্য
                                        @else
                                            Products @endif
                                    </span>
                                </a>
                            </li>
                            @if ((isset($category) && $category == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/category">
                                        <img src="{{ URL::to('/') }}/img/icons/categories.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') ক্যাটাগরি
                                            @else
                                                Categories @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if ((isset($subcategory) && $subcategory == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ route('admin.subcategory.index') }}">
                                        <img src="{{ URL::to('/') }}/img/subcategory.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') সাব ক্যাটাগরি
                                            @else
                                                Sub Categories @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if ((isset($attribute) && $attribute == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/attribute">
                                        <img src="{{ URL::to('/') }}/img/icons/product.png"><br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') পণ্যের ধরণ
                                            @else
                                                Variants @endif
                                        </span>

                                    </a>
                                </li>
                            @endif
                            @if ((isset($brand) && $brand == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/brand">
                                        <img src="{{ URL::to('/') }}/img/icons/brand.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') ব্রান্ড
                                            @else
                                                Brands @endif
                                        </span>
                                    </a>
                                </li>
                            @endif

                            @if ((isset($supplier) && $supplier == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/supplier">
                                        <img src="{{ URL::to('/') }}/img/icons/supplier.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') সরবরাহকারী
                                            @else
                                                Suppliers @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="container content-main">
            <div class="row">
                <div class="col-9 mt-4 mb-4">
                    <div class="content-header row">
                        <div class="col-md-6">
                            <h2 class="content-title">
                                @if (Session::has('lang') && Session::get('lang') == 'bn') এডিট পণ্য
                                @else
                                    Edit Product @endif
                            </h2>
                        </div>

                        <div class="col-md-6" style="text-align:right">
                            <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button> -->
                            <!-- <button class="btn btn-info rounded font-sm hover-up">Publich</button> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>
                                @if (Session::has('lang') && Session::get('lang') == 'bn') মৌলিক
                                @else
                                    Basic @endif
                            </h4>
                            <span style="font-size:14px;color:red">
                                @if (Session::has('lang') && Session::get('lang') == 'bn') * চিহ্নিত ক্ষেত্রগুলি
                                    বাধ্যতামূলক
                                @else
                                    Fields marked with * are mandatory @endif
                            </span>
                        </div>
                        <div class="card-body">
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger" style="color:#fff">{{ Session::get('error_message') }}
                                </div>
                            @endif
                            <form action="{{ route('admin.updateproduct', $product->id) }}" method="post"
                                enctype="multipart/form-data">
                                <input type="hidden" name="index" value="1" id="index">
                                @csrf
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn') পণ্য শিরোনাম
                                        @else
                                            Product title @endif
                                        <span class="req">*</span>
                                    </label>
                                    <input type="text" placeholder="Type here" class="form-control" id="product_name"
                                        value="{{ $product->name }}" name="product_name">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn') পূর্ণ বিবরণ
                                        @else
                                            Full description @endif
                                        <span class="req">*</span>
                                    </label>
                                    <textarea placeholder="Type here" class="form-control" rows="4" name="description">{{ $product->description }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') এস কে ইউ
                                                @else
                                                    SKU @endif
                                                <span class="req">*</span>
                                            </label>
                                            <div class="row gx-2">
                                                <input placeholder="" type="text" class="form-control"
                                                    value="{{ $product->SKU }}" name="SKU">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') নিয়মিত
                                                    মূল্য
                                                @else
                                                    Regular price @endif
                                                <span class="req">*</span>
                                            </label>
                                            <div class="row gx-2">
                                                <input placeholder="$" type="number" class="form-control"
                                                    value="{{ $product->regular_price }}" name="regular_price">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') দ্রব্য
                                                    মূল্য
                                                @else
                                                    Product Cost @endif
                                            </label>
                                            <input placeholder="" type="number" class="form-control"
                                                value="{{ $product->cost }}" name="cost">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') পরিমাণ
                                                @else
                                                    Quantity @endif
                                                <span class="req">*</span>
                                            </label>
                                            <input placeholder="" type="number" class="form-control"
                                                value="{{ $product->quantity }}" name="quantity">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') ডিসকাউন্ট
                                                টাইপ
                                            @else
                                                Discount Type @endif
                                            <span class="req">*</span>
                                        </label>
                                        <select class="form-select" name="discount_type">
                                            <option value="fixed" @if ($product->discount_type == 'fixed') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') ফিক্সড
                                                @else
                                                    Fixed @endif
                                            </option>
                                            <option value="percent" @if ($product->discount_type == 'percent') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    পার্সেন্ট
                                                @else
                                                    Percent @endif
                                            </option>
                                            <option value="no_discount" @if ($product->discount_type == 'no_discount') selected @endif>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') নো
                                                    ডিসকাউন্ট
                                                @else
                                                    No Discount @endif
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ডিসকাউন্ট মূল্য
                                                @else
                                                    Discount price @endif
                                            </label>
                                            <input placeholder="$" type="number" class="form-control"
                                                value="{{ $product->promotional_price }}" name="promotional_price">
                                        </div>
                                    </div>



                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') বার কোড
                                                @else
                                                    Bar Code @endif
                                            </label>
                                            <input placeholder="" type="number" class="form-control"
                                                value="{{ $product->barcode }}" name="barcode">
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="mb-4">
                                            <label for="product_name" class="form-label">SEO Keywords</label>
                                            <input type="text" placeholder="Type here" class="form-control" id="product_name" value="{{ $product->seo_keywords }}" name="seo_keywords">
                                        </div> -->
                                <!-- <label class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" value="">
                                            <span class="form-check-label"> Make a template </span>
                                        </label> -->

                        </div>
                    </div> <!-- card end// -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn') পাঠানো
                                        @else
                                            Shipping @endif
                                    </h4>
                                </div>
                                <div class="col-6" style="text-align:right">
                                    <a href="javascript:void(0)" id="shipshow"><i class="fa fa-arrow-down"></i></a>
                                    <a href="javascript:void(0)" id="shiphide"><i class="fa fa-arrow-up"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row" id="shipping-div">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') ওজন
                                            @else
                                                Weight @endif
                                        </label>
                                        <input type="text" placeholder="kg" class="form-control" id="weight"
                                            value="{{ $product->weight }}" name="weight">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') বহন খরচ
                                            @else
                                                Shipping fees @endif
                                        </label>
                                        <input type="number" placeholder="$" class="form-control" id="shipping_fee"
                                            value="{{ $product->shipping_fee }}" name="shipping_fee">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn') ট্যাক্সের ধরন
                                        @else
                                            Tax Type @endif
                                    </label>
                                    <select class="form-select" name="tax_type">
                                        <option value="fixed" @if ($product->tax_type == 'fixed') selected @endif>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') স্থির
                                            @else
                                                Fixed @endif
                                        </option>
                                        <option value="percent" @if ($product->tax_type == 'percent') selected @endif>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') শতাংশ
                                            @else
                                                Percent @endif
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') করের হার
                                            @else
                                                Tax rate @endif
                                        </label>
                                        <input placeholder="$" type="number" class="form-control"
                                            value="{{ $product->tax_rate }}" name="tax_rate">
                                    </div>
                                </div>
                            </div>
                            <!-- </form> -->
                        </div>
                    </div> <!-- card end// -->
                </div>
                <div class="col-lg-3">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>
                                @if (Session::has('lang') && Session::get('lang') == 'bn') মিডিয়া
                                @else
                                    Media @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="input-upload" style="">
                                <!-- <img src="{{ URL::to('/') }}/img/upload.svg" alt="" style="max-width: 100px;margin-bottom: 20px;vertical-align: baseline;"> -->
                                @if ($product->images)
                                    @php
                                        $images = explode(',', $product->images);
                                    @endphp
                                    @foreach ($images as $key => $image)
                                        <img src="{{ URL::to('/') }}/assets/images/product/{{ $image }}"
                                            style="padding:10px;border:1px solid black;margin-bottom:5px;" width="60px"
                                            height="60px">
                                        <a
                                            href="{{ URL::to('/') }}/product/removeimage/{{ $product->id }}/{{ $image }}">X</a>
                                    @endforeach
                                @endif
                                <br>
                                <output id="Filelist"></output>
                                <br>
                                <input type="file" class="form-control" id="image" name="image[]" multiple >
                                @error('image')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                                <!-- <div class="mb-3 row">
                                            <label for="banner" class="col-md-2 col-form-label">Image</label>
                                            <div class="col-md-4">
                                            <input type="file" class="form-control" id="image" name="image">
                                            @error('image')
        <p class="text-danger" role="alert">{{ $message }}</p>
    @enderror
                                            </div>
                                        </div> -->
                            </div>
                        </div>
                    </div> <!-- card end// -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>
                                @if (Session::has('lang') && Session::get('lang') == 'bn') সংগঠন
                                @else
                                    Organization @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row gx-2">
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn') ক্যাটাগরি
                                        @else
                                            Category @endif
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
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') নির্বাচন
                                                করুন
                                            @else
                                                Select @endif
                                        </option>
                                        @foreach ($category as $cat)
                                            @isset($cat)
                                                <option value="{{ $cat->id }}"
                                                    @if ($product->category == $cat->id) selected @endif>{{ $cat->name }}
                                                </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') সাব ক্যাটাগরি
                                            @else
                                                Sub-category @endif
                                        </label>
                                        <select class="form-select" name="subcategory" id="subcategory">
                                            @if (isset($product->subcategory))
                                                <?php
                                                $subcategory = DB::table('categories')
                                                    ->where('id', $product->subcategory)
                                                    ->where('status', 'active')
                                                    ->first();
                                                ?>
                                                <option value="{{ $subcategory->id ?? '0' }}">
                                                    {{ $subcategory->name ?? 'deleted' }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') ব্র্যান্ড
                                            @else
                                                Brand @endif
                                        </label>
                                        <?php
                                        $brands = DB::table('brands')
                                            ->where('store_id', $store_id)
                                            ->get();
                                        ?>
                                        <select class="form-select" name="brand" id="brand">
                                            <option value="null">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') ব্র্যান্ড
                                                    নির্বাচন করুন
                                                @else
                                                    Select Brand @endif
                                            </option>
                                            @foreach ($brands as $brand)
                                                @isset($brand)
                                                    <option value="{{ $brand->id }}"
                                                        @if (isset($product->brand) && $product->brand == $brand->id) selected @endif>{{ $brand->name }}
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
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') সরবরাহকারী
                                                @else
                                                    Supplier @endif
                                            </label>
                                            <?php
                                            $suppliers = DB::table('suppliers')
                                                ->where('store_id', $store_id)
                                                ->get();
                                            ?>
                                            <select class="form-select" name="supplier" id="supplier">
                                                <option value="null">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn') সরবরাহকারী
                                                        নির্বাচন করুন
                                                    @else
                                                        Select Supplier @endif
                                                </option>
                                                @foreach ($suppliers as $supplier)
                                                    @isset($supplier)
                                                        <option value="{{ $supplier->id }}"
                                                            @if (isset($product->supplier) && $product->supplier == $supplier->id) selected @endif>{{ $supplier->name }}
                                                        </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                @error('supplier')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <!--<label for="product_name" class="form-label">Tags</label>-->
                                                <!--<input type="text" class="form-control" value="{{ $product->tags }}" name="tags">-->
                                                <label for="product_name" class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn') ট্যাগ
                                                    @else
                                                        Tags @endif
                                                </label>
                                                <input type="text" value="{{ $product->tags }}" class="form-control"
                                                    data-role="tagsinput" name="tagss" style="width:100%;display: block;">
                                            </div>
                                            <div class="mb-4">
                                                <label for="product_name" class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn') এসইও কীওয়ার্ড
                                                    @else
                                                        SEO Keywords @endif
                                                </label>
                                                <input type="text" placeholder="Type here" class="form-control" id="product_name"
                                                    value="{{ $product->seo_keywords }}" name="seo">
                                                <!--<input type="text" value="{{ $product->seo_keywords }}" class="form-control" data-role="tagsinput" name="seo" style="width:100%;display: block;">-->
                                            </div>
                                            <div class="mb-2">
                                                <label for="best_sell" class="form-label">
                                                    <input type="checkbox" id="best_sell" name="best_sell"
                                                        @if ($product->best_sell == 1) checked @endif>&nbsp;&nbsp;Best Sell</label>

                                                <!--<input type="text" value="" class="form-control" data-role="tagsinput" name="seo" style="width:100%;display: block;">-->
                                                @error('best_sell')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-2">
                                                <label for="feature" class="form-label">
                                                    <input type="checkbox" id="feature" name="feature"
                                                        @if ($product->feature == 1) checked @endif>&nbsp;&nbsp;Feature</label>

                                                <!--<input type="text" value="" class="form-control" data-role="tagsinput" name="seo" style="width:100%;display: block;">-->
                                                @error('feature')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div> <!-- row.// -->
                                    </div>

                                </div> <!-- card end// -->
                                <!--<button class="btn btn-info rounded font-sm hover-up">Update</button>-->
                            </div>
                            <?php
                            $attri = DB::table('veriants')
                                ->where('pid', $product->id)
                                ->get();
                            $attri_color = DB::table('veriants')
                                ->where('pid', $product->id)
                                ->select('color')
                                ->get();
                            $attri_unit = DB::table('veriants')
                                ->where('pid', $product->id)
                                ->where('color', null)
                                ->where('size', null)
                                ->select('volume')
                                ->get();
                            $attri_size = DB::table('veriants')
                                ->where('pid', $product->id)
                                ->where('color', null)
                                ->select('size')
                                ->get();
                            $attri_onlycolor = DB::table('veriants')
                                ->where('pid', $product->id)
                                ->where('size', null)
                                ->select('color')
                                ->get();
                            $select_sizess = DB::table('veriants')
                                ->where('pid', $product->id)
                                ->where('color', null)
                                ->where('size', '!=', null)
                                ->get();
                            $select_unitsss = DB::table('veriants')
                                ->where('pid', $product->id)
                                ->where('color', null)
                                ->where('size', null)
                                ->where('volume', '!=', null)
                                ->get();
                            $select_onlycolor = DB::table('veriants')
                                ->where('pid', $product->id)
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
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn') ভেরিয়েন্ট
                                                    @else
                                                        Attributes @endif
                                                </h4>
                                            </div>
                                            <div class="col-6" style="text-align:right">
                                                <a href="javascript:void(0)" id="attrishow"><i class="fa fa-arrow-down"></i></a>
                                                <a href="javascript:void(0)" id="attrihide"><i class="fa fa-arrow-up"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="attri-div">
                                            <div class="col-md-2">
                                                <label for="">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn') ভেরিয়েন্ট টাইপ
                                                    @else
                                                        Variantion Type @endif
                                                </label>
                                                <select class="form-control" name="att" id="attributes">
                                                    <option value="none">Select</option>
                                                    <option value="color" @if (isset($attri_color) && count($attri_color) > 0) selected @endif>Color &
                                                        Size</option>
                                                    <option value="onlycolor" @if (isset($select_onlycolor) && count($select_onlycolor) > 0) selected @endif>Color
                                                    </option>
                                                    <option value="unit" @if (isset($select_unitsss) && count($select_unitsss) > 0) selected @endif>Unit
                                                    </option>
                                                    <option value="size" @if (isset($select_sizess) && count($select_sizess) > 0) selected @endif>Size
                                                    </option>
                                                </select>
                                            </div>

                                            <div id="colorrss" class="col-lg-12 mt-3">
                                                <?php
                                                $attri_colorss = DB::table('veriants')
                                                    ->where('pid', $product->id)
                                                    ->where('color', '!=', null)
                                                    ->where('size', '!=', null)
                                                    ->get();
                                                ?>
                                                @if (isset($attri_colorss) && count($attri_colorss) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-stripped" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="20%" style="text-align:center">Color</th>
                                                                    <th width="20%" style="text-align:center">Size</th>
                                                                    <th width="20%" style="text-align:center">Quantity</th>
                                                                    <th width="20%" style="text-align:center">Additional Price</th>
                                                                    <th width="20%" style="text-align:center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($attri_colorss as $keyss => $colorsss)
                                                                    <tr id="{{ $colorsss->id }}">
                                                                        <td class="mt-1" style="text-align:center">
                                                                            <select name="clor" id="clor"
                                                                                class="form-control" step="any">
                                                                                <?php
                                                                                $colors = DB::table('colors')
                                                                                    ->where('store_id', $store_id)
                                                                                    ->get();
                                                                                ?>
                                                                                @if (isset($colors))
                                                                                    @foreach ($colors as $cl)
                                                                                        <option value="{{ $cl->name }}"
                                                                                            @if ($colorsss->color == $cl->name) selected @endif>
                                                                                            {{ $cl->name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </td>
                                                                        <td class="mt-1" style="text-align:center">
                                                                            <select name="siz" id="sizs"
                                                                                class="form-control" step="any">
                                                                                <?php
                                                                                $size = DB::table('sizes')
                                                                                    ->where('store_id', $store_id)
                                                                                    ->get();
                                                                                ?>
                                                                                @if (isset($size))
                                                                                    @foreach ($size as $key => $sz)
                                                                                        <option value="{{ $sz->name }}"
                                                                                            @if ($colorsss->size == $sz->name) selected @endif>
                                                                                            {{ $sz->name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </td>
                                                                        <td class="mt-1" style="text-align:center"><input
                                                                                type="number" name="qunty" id="qunty"
                                                                                class="form-control"
                                                                                value="{{ $colorsss->quantity }}"></td>
                                                                        <input type="hidden" name="attriid" id="attriid"
                                                                            value="{{ $colorsss->id }}">
                                                                        <td class="mt-1" style="text-align:center"><input
                                                                                type="number" name="aditionalprice"
                                                                                id="additionalpricess" class="form-control"
                                                                                value="{{ $colorsss->additional_price ?? 0 }}"></td>
                                                                        <td class="mt-1" style="text-align:center"><a
                                                                                href="javascript:void(0)" class="updateattri"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                                title="Update"><img
                                                                                    src="{{ URL::to('/') }}/img/update.png"
                                                                                    alt="" width="30px"></a>&nbsp;&nbsp;<a
                                                                                href="javascript:void(0)" class="deleteattri"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                                title="Delete"><img
                                                                                    src="{{ URL::to('/') }}/img/delete.png"
                                                                                    alt="" width="30px"></a></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                                @if (isset($attri_colorss) && count($attri_colorss) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-stripped" id="officers-table">
                                                            <tbody>
                                                                <?php $i = 0; ?>
                                                                <tr id="new" style="margin-top:5px;">
                                                                    <td class="mt-1" width="15%">
                                                                        <label>Color:</label>
                                                                        <select name="color[0][]" id="color" class="form-control"
                                                                            step="any">
                                                                            <option> Select Color</option>
                                                                            <?php
                                                                            $colors = DB::table('colors')
                                                                                ->where('store_id', $store_id)
                                                                                ->get();
                                                                            ?>
                                                                            @if (isset($colors))
                                                                                @foreach ($colors as $cl)
                                                                                    <option value="{{ $cl->name }}">
                                                                                        {{ $cl->name }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </td>
                                                                    <td class="mt-1" width="0%">

                                                                    </td>
                                                                    <td class="mt-1" width="70%">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                size
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                Quantity
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                Additional Price
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        $size = DB::table('sizes')
                                                                            ->where('store_id', $store_id)
                                                                            ->get();
                                                                        ?>
                                                                        @if (isset($size))
                                                                            @foreach ($size as $key => $sz)
                                                                                <div class="row" style="margin-top:5px;">
                                                                                    <div class="col-md-1 mt-1">
                                                                                        <input type="checkbox"
                                                                                            name="sid[0][{{ $key }}]">
                                                                                    </div>
                                                                                    <div class="col-md-3 mt-1">
                                                                                        <input type="text" class="form-control"
                                                                                            name="size[0][{{ $key }}]"
                                                                                            value="{{ $sz->name }}" readonly>
                                                                                    </div>
                                                                                    <div class="col-md-4 mt-1">
                                                                                        <input type="number" class="form-control"
                                                                                            name="quantitys[0][{{ $key }}]"
                                                                                            placeholder="Enter Quantity"
                                                                                            value="">
                                                                                    </div>
                                                                                    <div class="col-md-4 mt-1">
                                                                                        <input type="number" class="form-control"
                                                                                            name="price[0][{{ $key }}]"
                                                                                            placeholder="Additional Price"
                                                                                            value="0">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td class="mt-1" width="15%">
                                                                        <a class="remove-officer-button mt-3" data-bs-toggle="tooltip"
                                                                            data-bs-placement="top" title="Delete"><img
                                                                                src="{{ URL::to('/') }}/img/delete.png"
                                                                                alt="" width="30px"
                                                                                style="margin-bottom:5px;"></a>
                                                                        <br>
                                                                        <a onclick="addRow()" data-bs-toggle="tooltip"
                                                                            data-bs-placement="top" title="Add"><img
                                                                                src="{{ URL::to('/') }}/img/add.png" alt=""
                                                                                width="30px"></a>
                                                                    </td>
                                                                    <td class="mt-1" width="0%"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                            <div id="sizess" class="col-lg-12 mt-3">
                                                <?php
                                                $attri_sizess = DB::table('veriants')
                                                    ->where('pid', $product->id)
                                                    ->where('color', null)
                                                    ->where('size', '!=', null)
                                                    ->get();
                                                ?>
                                                @if (isset($attri_sizess) && count($attri_sizess) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-stripped" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="25%" style="text-align:center">Size</th>
                                                                    <th width="25%" style="text-align:center">Quantity</th>
                                                                    <th width="25%" style="text-align:center">Additional Price</th>
                                                                    <th width="25%" style="text-align:center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($attri_sizess as $sizesss)
                                                                    <tr>
                                                                        <td class="mt-1" style="text-align:center">
                                                                            <select name="siz" id="sizs"
                                                                                class="form-control" step="any">
                                                                                <?php
                                                                                $size = DB::table('sizes')
                                                                                    ->where('store_id', $store_id)
                                                                                    ->get();
                                                                                ?>
                                                                                @if (isset($size))
                                                                                    @foreach ($size as $key => $sz)
                                                                                        <option value="{{ $sz->name }}"
                                                                                            @if ($sizesss->size == $sz->name) selected @endif>
                                                                                            {{ $sz->name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </td>
                                                                        <td class="mt-1" style="text-align:center"><input
                                                                                type="number" name="qunty" id="qunty"
                                                                                class="form-control"
                                                                                value="{{ $sizesss->quantity }}"></td>
                                                                        <input type="hidden" name="attriid" id="attriid"
                                                                            value="{{ $sizesss->id }}">
                                                                        <td class="mt-1" style="text-align:center"><input
                                                                                type="number" name="aditionalprice"
                                                                                id="additionalpricess" class="form-control"
                                                                                value="{{ $sizesss->additional_price ?? 0 }}"></td>
                                                                        <td class="mt-1" style="text-align:center"><a
                                                                                href="javascript:void(0)" class="updatesizeattri"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                                title="Update"><img
                                                                                    src="{{ URL::to('/') }}/img/update.png"
                                                                                    alt="" width="30px"></a>&nbsp;&nbsp;<a
                                                                                href="javascript:void(0)" class="deletesizeattri"><img
                                                                                    src="{{ URL::to('/') }}/img/delete.png"
                                                                                    alt="" width="30px"></a></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                                @if (isset($attri_sizess) && count($attri_sizess) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-stripped" id="officers-table2">
                                                            <tbody>
                                                                <?php $i = 0; ?>
                                                                <tr id="new2" style="margin-top:5px;">
                                                                    <td class="mt-1">
                                                                        <div class="row">
                                                                            <div class="col-md-4 mt-1">
                                                                                size
                                                                            </div>
                                                                            <div class="col-md-4 mt-1">
                                                                                Quantity
                                                                            </div>
                                                                            <div class="col-md-4 mt-1">
                                                                                Additional Price
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        $size = DB::table('sizes')
                                                                            ->where('store_id', $store_id)
                                                                            ->get();
                                                                        ?>
                                                                        @if (isset($size))
                                                                            @foreach ($size as $key => $sz)
                                                                                <div class="row" style="margin-top:5px;">
                                                                                    <div class="col-md-4 mt-1">
                                                                                        <input type="text" class="form-control"
                                                                                            name="sizess[]"
                                                                                            value="{{ $sz->name }}" readonly>
                                                                                    </div>
                                                                                    <div class="col-md-4 mt-1">
                                                                                        <input type="number" class="form-control"
                                                                                            name="quantityss[]"
                                                                                            placeholder="Enter Quantity"
                                                                                            value="">
                                                                                    </div>
                                                                                    <div class="col-md-4 mt-1">
                                                                                        <input type="number" class="form-control"
                                                                                            name="pricess[]" placeholder="Enter Price"
                                                                                            value="0">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                            <div id="onlycolors" class="col-lg-12 mt-3">
                                                <?php
                                                $attri_onlycolor = DB::table('veriants')
                                                    ->where('pid', $product->id)
                                                    ->where('size', null)
                                                    ->where('color', '!=', null)
                                                    ->get();
                                                ?>
                                                @if (isset($attri_onlycolor) && count($attri_onlycolor) > 0)
                                                    <table class="table table-stripped" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="25%" style="text-align:center">Color</th>
                                                                <th width="25%" style="text-align:center">Quantity</th>
                                                                <th width="25%" style="text-align:center">Additional Price</th>
                                                                <th width="25%" style="text-align:center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($attri_onlycolor as $colorssss)
                                                                <tr>
                                                                    <td style="text-align:center">
                                                                        <select name="colorsd" id="color" class="form-control"
                                                                            step="any">
                                                                            <?php
                                                                            $colors = DB::table('colors')
                                                                                ->where('store_id', $store_id)
                                                                                ->get();
                                                                            ?>
                                                                            @if (isset($colors))
                                                                                @foreach ($colors as $cl)
                                                                                    <option value="{{ $cl->code }}"
                                                                                        @if ($cl->code == $colorssss->color) selected @endif>
                                                                                        {{ $cl->name }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </td>
                                                                    <td style="text-align:center">
                                                                        <input type="number" name="qunty" id="qunty"
                                                                            class="form-control" min="0"
                                                                            value="{{ $colorssss->quantity }}">
                                                                    </td>
                                                                    <input type="hidden" name="attriid" id="attriid"
                                                                        value="{{ $colorssss->id }}">
                                                                    <td style="text-align:center">
                                                                        <input type="number" name="aditionalprice"
                                                                            id="additionalpricess" class="form-control"
                                                                            value="{{ $colorssss->additional_price }}"
                                                                            min="0">
                                                                    </td>
                                                                    <td style="text-align:center"><a href="javascript:void(0)"
                                                                            class="updateonlycolorattri" data-bs-toggle="tooltip"
                                                                            data-bs-placement="top" title="Update"><img
                                                                                src="{{ URL::to('/') }}/img/update.png"
                                                                                alt="" width="30px"></a>&nbsp;&nbsp;<a
                                                                            href="javascript:void(0)"
                                                                            class="deleteonlycolorattri"><img
                                                                                src="{{ URL::to('/') }}/img/delete.png"
                                                                                alt="" width="30px"></a></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                                @if (isset($attri_onlycolor) && count($attri_onlycolor) > 0)
                                                    <table class="table table-stripped" id="officers-table3">
                                                        <tbody>
                                                            <?php $i = 0; ?>
                                                            <tr id="new3" style="margin-top:5px;">
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            Color
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            Quantity
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            Additional Price
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-top:5px;">
                                                                        <div class="col-md-3">
                                                                            <select name="colors[]" id="color"
                                                                                class="form-control" step="any">
                                                                                <option> Select Color</option>
                                                                                <?php
                                                                                $colors = DB::table('colors')
                                                                                    ->where('store_id', $store_id)
                                                                                    ->get();
                                                                                ?>
                                                                                @if (isset($colors))
                                                                                    @foreach ($colors as $cl)
                                                                                        <option value="{{ $cl->code }}">
                                                                                            {{ $cl->name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="number" class="form-control"
                                                                                name="quantitysss[]" placeholder="Enter Quantity"
                                                                                min="0" value="0">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="number" class="form-control"
                                                                                name="pricesss[]" placeholder="Enter Price"
                                                                                min="0" value="0">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <a
                                                                                class="remove-officer-button3 btn btn-danger mt-3">Delete</a>
                                                                            <br>
                                                                            <a class="btn btn-info" onclick="addOnlycolor()">Add
                                                                                new</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                @endif
                                            </div>
                                            <div id="unittss" class="col-lg-12 mt-3">
                                                <?php
                                                $attri_unitsss = DB::table('veriants')
                                                    ->where('pid', $product->id)
                                                    ->where('color', null)
                                                    ->where('size', null)
                                                    ->where('volume', '!=', null)
                                                    ->get();
                                                ?>
                                                @if (isset($attri_unitsss) && count($attri_unitsss) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-stripped" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="25%" style="text-align:center">Volume</th>
                                                                    <th width="25%" style="text-align:center">Unit</th>
                                                                    <th width="25%" style="text-align:center">Quantity</th>
                                                                    <th width="25%" style="text-align:center">Additional Price</th>
                                                                    <th width="25%" style="text-align:center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($attri_unitsss as $unitssss)
                                                                    <tr>
                                                                        <td class="mt-1" style="text-align:center">
                                                                            <input type="number" class="form-control"
                                                                                name="volumess" id="volumess"
                                                                                value="{{ $unitssss->volume }}">
                                                                        </td>
                                                                        <td class="mt-1">
                                                                            <select name="unitss" id="unitss"
                                                                                class="form-control" step="any">
                                                                                <option> Select Unit</option>
                                                                                <?php
                                                                                $color = DB::table('units')
                                                                                    ->where('store_id', $store_id)
                                                                                    ->get();

                                                                                ?>
                                                                                @if (isset($color))
                                                                                    @foreach ($color as $cl)
                                                                                        <option value="{{ $cl->name }}"
                                                                                            @if ($unitssss->unit == $cl->name) selected @endif>
                                                                                            {{ $cl->name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </td>
                                                                        <td class="mt-1" style="text-align:center"><input
                                                                                type="number" name="qunty" id="qunty"
                                                                                class="form-control"
                                                                                value="{{ $unitssss->quantity }}"></td>
                                                                        <input type="hidden" name="attriid" id="attriid"
                                                                            value="{{ $unitssss->id }}">
                                                                        <td class="mt-1" style="text-align:center"><input
                                                                                type="number" name="aditionalprice"
                                                                                id="additionalpricess" class="form-control"
                                                                                value="{{ $unitssss->additional_price ?? 0 }}"></td>
                                                                        <td class="mt-1" style="text-align:center"><a
                                                                                href="javascript:void(0)" class="updateunitattri"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                                title="Update"><img
                                                                                    src="{{ URL::to('/') }}/img/update.png"
                                                                                    alt="" width="30px"></a>&nbsp;&nbsp;<a
                                                                                href="javascript:void(0)" class="deleteunitattri"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                                title="Delete"><img
                                                                                    src="{{ URL::to('/') }}/img/delete.png"
                                                                                    alt="" width="30px"></a></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                                @if (isset($attri_unitsss) && count($attri_unitsss) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-stripped" id="officers-table1">
                                                            <tbody>
                                                                <?php $i = 0; ?>
                                                                <tr id="new1" style="margin-top:5px;">




                                                                    </td>
                                                                    <td>
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                Volume
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                Unit
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                Quantity
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                Additional Price
                                                                            </div>
                                                                        </div>
                                                                        <div class="row" style="margin-top:5px;">
                                                                            <div class="col-md-3 mt-1">
                                                                                <input type="number" class="form-control"
                                                                                    name="volume[]" value="">
                                                                            </div>
                                                                            <div class="col-md-3 mt-1">
                                                                                <select name="unit[]" id="color"
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
                                                                            <div class="col-md-3 mt-1">
                                                                                <input type="number" class="form-control"
                                                                                    name="quantitys[]" placeholder="Enter Quantity"
                                                                                    value="">
                                                                            </div>
                                                                            <div class="col-md-3 mt-1">
                                                                                <input type="number" class="form-control"
                                                                                    name="price[]" placeholder="Enter Price"
                                                                                    value="0">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <a class="remove-officer-button1 mt-3"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            title="Delete"><img
                                                                                src="{{ URL::to('/') }}/img/delete.png"
                                                                                alt="" width="30px"
                                                                                style="margin-bottom:5px;"></a>
                                                                        <br>
                                                                        <a onclick="addUnit()" data-bs-toggle="tooltip"
                                                                            data-bs-placement="top" title="Add"><img
                                                                                src="{{ URL::to('/') }}/img/add.png" alt=""
                                                                                width="30px"></a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- </form> -->
                                    </div>
                                </div> <!-- card end// -->
                                <button class="btn btn-info rounded font-sm hover-up" type="submit">Publish</button>
                            </div>
                            </form>
                        </div>
                    </section>
                    </div>
                </main>
            @endsection

            @push('scripts')
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
                            fileReader.onload = (function(readerEvt) {
                                return function(e) {
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
                    jQuery(function($) {
                        $("div").on("click", ".img-wrap .close", function() {
                            var id = $(this)
                                .closest(".img-wrap")
                                .find("img")
                                .data("id");

                            //to remove the deleted item from array
                            var elementPos = AttachmentArray.map(function(x) {
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
                        //To check file type according to upload conditions
                        if (CheckFileType(readerEvt.type) == false) {
                            alert(
                                "The file (" +
                                readerEvt.name +
                                ") does not match the upload conditions, You can only upload jpg/png/gif files"
                            );
                            e.preventDefault();
                            return;
                        }

                        //To check file Size according to upload conditions
                        if (CheckFileSize(readerEvt.size) == false) {
                            alert(
                                "The file (" +
                                readerEvt.name +
                                ") does not match the upload conditions, The maximum file size for uploads should not exceed 300 KB"
                            );
                            e.preventDefault();
                            return;
                        }

                        //To check files count according to upload conditions
                        if (CheckFilesCount(AttachmentArray) == false) {
                            if (!filesCounterAlertStatus) {
                                filesCounterAlertStatus = true;
                                alert(
                                    "You have added more than 10 files. According to upload conditions you can upload 10 files maximum"
                                );
                            }
                            e.preventDefault();
                            return;
                        }
                    }

                    //To check file type according to upload conditions
                    function CheckFileType(fileType) {
                        if (fileType == "image/jpeg") {
                            return true;
                        } else if (fileType == "image/png") {
                            return true;
                        } else if (fileType == "image/gif") {
                            return true;
                        } else {
                            return false;
                        }
                        return true;
                    }

                    //To check file Size according to upload conditions
                    function CheckFileSize(fileSize) {
                        if (fileSize < 200000) {
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
                    $('.updateattri').on('click', function() {
                        var id = $(this).closest('tr').find('#attriid').val();
                        var quantity = $(this).closest('tr').find('#qunty').val();
                        var size = $(this).closest('tr').find('#sizs').val();
                        var color = $(this).closest('tr').find('#clor').val();
                        var additional_price = $(this).closest('tr').find('#additionalpricess').val();
                        console.log(id);
                        console.log(quantity);
                        console.log(size);
                        console.log(color);
                        $.get('/updateattribute', {
                            id: id,
                            quantity: quantity,
                            size: size,
                            color: color,
                            additional_price: additional_price
                        }, function(data) {
                            console.log(data);

                        });
                        debugger;
                    });
                    $('.deleteattri').on('click', function() {
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
                        }, function(data) {
                            console.log(data);
                            window.location.reload();
                        });
                        debugger;
                    });

                    $('.updatesizeattri').on('click', function() {
                        var id = $(this).closest('tr').find('#attriid').val();
                        var quantity = $(this).closest('tr').find('#qunty').val();
                        var size = $(this).closest('tr').find('#sizs').val();
                        var additional_price = $(this).closest('tr').find('#additionalpricess').val();
                        console.log(id);
                        console.log(quantity);
                        console.log(size);
                        $.get('/updatesizeattribute', {
                            id: id,
                            quantity: quantity,
                            size: size,
                            additional_price: additional_price
                        }, function(data) {
                            console.log(data);

                        });
                        debugger;
                    });
                    $('.deletesizeattri').on('click', function() {
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
                        }, function(data) {
                            console.log(data);
                            window.location.reload();
                        });
                        debugger;
                    });
                    $('.updateonlycolorattri').on('click', function() {
                        var id = $(this).closest('tr').find('#attriid').val();
                        var quantity = $(this).closest('tr').find('#qunty').val();
                        var color = $(this).closest('tr').find('#color').val();
                        var additional_price = $(this).closest('tr').find('#additionalpricess').val();
                        console.log(id);
                        console.log(quantity);
                        console.log(color);
                        $.get('/updateonlycolorattribute', {
                            id: id,
                            quantity: quantity,
                            color: color,
                            additional_price: additional_price
                        }, function(data) {
                            console.log(data);

                        });
                        debugger;
                    });
                    $('.deleteonlycolorattri').on('click', function() {
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
                        }, function(data) {
                            console.log(data);
                            window.location.reload();
                        });
                        debugger;
                    });
                    $('.updateunitattri').on('click', function() {
                        var id = $(this).closest('tr').find('#attriid').val();
                        var quantity = $(this).closest('tr').find('#qunty').val();
                        var volume = $(this).closest('tr').find('#volumess').val();
                        var unit = $(this).closest('tr').find('#unitss').val();
                        var additional_price = $(this).closest('tr').find('#additionalpricess').val();
                        console.log(id);
                        console.log(quantity);
                        $.get('/updateunitattribute', {
                            id: id,
                            quantity: quantity,
                            volume: volume,
                            unit: unit,
                            additional_price: additional_price
                        }, function(data) {
                            console.log(data);

                        });
                        debugger;
                    });
                    $('.deleteunitattri').on('click', function() {
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
                        }, function(data) {
                            console.log(data);
                            window.location.reload();
                        });
                        debugger;
                    });
                    $(document).ready(function() {
                        <?php
         if(isset($attri_colorss) && count($attri_colorss)>0){

         }else{
        ?>
                        $('#colorrss').hide();
                        <?php
         }
        ?>
                        <?php
         if(isset($attri_unitsss) && count($attri_unitsss)>0){

         }else{
        ?>
                        $('#unittss').hide();
                        <?php
         }
        ?>
                        <?php
         if(isset($attri_sizess) && count($attri_sizess)>0){

         }else{
        ?>
                        $('#sizess').hide();
                        <?php
         }
        ?>
                        <?php
         if(isset($attri_onlycolor) && count($attri_onlycolor)>0){

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
                        $('#attributes').on('change', function() {
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
                        $('#shipshow').on('click', function() {
                            $('#shipping-div').show();
                            $('#shiphide').show();
                            $('#shipshow').hide();
                        });
                        $('#shiphide').on('click', function() {
                            $('#shipping-div').hide();
                            $('#shiphide').hide();
                            $('#shipshow').show();
                        });
                        $('#attrishow').on('click', function() {
                            $('#attri-div').show();
                            $('#attrihide').show();
                            $('#attrishow').hide();
                        });
                        $('#attrihide').on('click', function() {
                            $('#attri-div').hide();
                            $('#attrihide').hide();
                            $('#attrishow').show();
                        });
                    })
                </script>
                <script>
                    // var citynames = new Bloodhound({
                    //   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                    //   queryTokenizer: Bloodhound.tokenizers.whitespace,
                    //   prefetch: {
                    //     url: 'assets/citynames.json',
                    //     filter: function(list) {
                    //       return $.map(list, function(cityname) {
                    //         return { name: cityname }; });
                    //     }
                    //   }
                    // });
                    // citynames.initialize();

                    // $('input').tagsinput({
                    //     debugger;
                    //   typeaheadjs: {
                    //     name: 'citynames',
                    //     displayKey: 'name',
                    //     valueKey: 'name',
                    //     source: citynames.ttAdapter()
                    //   }
                    // });
                </script>
                <script>
                    // var citynames = new Bloodhound({
                    //   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                    //   queryTokenizer: Bloodhound.tokenizers.whitespace,
                    //   prefetch: {
                    //     url: 'assets/citynames.json',
                    //     filter: function(list) {
                    //       return $.map(list, function(cityname) {
                    //         return { name: cityname }; });
                    //     }
                    //   }
                    // });
                    // citynames.initialize();

                    // $('input').seoinput({
                    //   typeaheadjs: {
                    //     name: 'citynames',
                    //     displayKey: 'name',
                    //     valueKey: 'name',
                    //     source: citynames.ttAdapter()
                    //   }
                    // });
                </script>
                <script>
                    $(document).ready(function() {

                        $('input[name="input"]').tagsinput({
                            trimValue: true,
                            confirmKeys: [13, 44, 32],
                            focusClass: 'my-focus-class'
                        });

                        $('.bootstrap-tagsinput input').on('focus', function() {
                            $(this).closest('.bootstrap-tagsinput').addClass('has-focus');
                        }).on('blur', function() {
                            $(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
                        });

                    });

                    function addRows() {
                        var col = $('#new').html();
                        $("table tbody").append('<tr>' + col + '</tr>');
                    }

                    function addRow() {
                        var colors = {!! json_encode($colors, JSON_HEX_TAG) !!};
                        color = [];
                        colors.forEach(function(data) {
                            color += ` <option value="` + data.name + `">` + data.name + `</option>`
                        });
                        console.log(color);
                        var sizes = {!! json_encode($size, JSON_HEX_TAG) !!};
                        size = [];
                        index = document.getElementById('index').value;

                        i = document.getElementById('index').value = index + 1;
                        var j = 0;
                        sizes.forEach(function(data) {
                            console.log(data.name);
                            size += ` <div class="row" style="margin-top:5px;">
                                                            <div class="col-md-1">
                                                                <input type="checkbox"  name="sid[` + i + `][` + j +
                                `]" value="` + data.id + `">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control" name="size[` +
                                i + `][` + j + `]" value="` + data.name +
                                `" readonly>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number" class="form-control" name="quantitys[` +
                                i + `][` + j + `]" placeholder="Enter Quantity" value="">
                                                            </div>
                                                            <div class="col-md-4">
                                                            <input type="number" class="form-control" name="price[` +
                                i + `][` + j + `]" placeholder="Enter Price" value="0">
                                                            </div>
                                                        </div>`;
                            j++;

                        });
                        i++;
                        console.log(size);
                        index = document.getElementById('index').value;

                        addindex = document.getElementById('index').value = index + 1;

                        var col = `<tr id="new" style="margin-top:5px;">

                                                <td>
                                                <label>Color:</label>
                                                    <select name="color[` + addindex + `][]" id="color" class="form-control" step="any">
                                                        <option> Select Color</option>
                                                        ` + color + `
                                                    </select>
                                                </td>
                                                <td>

                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            size
                                                        </div>
                                                        <div class="col-md-4">
                                                            Quantity
                                                        </div>
                                                        <div class="col-md-4">
                                                            Price
                                                        </div>
                                                    </div>

                                                  ` + size + `
                                                </td>
                                                <td>
                                                    <a class="remove-officer-button mt-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><img src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px" style="margin-bottom:5px;"></a>
                                                    <br>
                                                    <a  onclick="addRow()" data-bs-toggle="tooltip" data-bs-placement="top" title="Add"><img src="{{ URL::to('/') }}/img/add.png" alt="" width="30px"></a>
                                                </td>
                                            </tr>`
                        $("#officers-table tbody").append(col);

                    }
                    $("#officers-table").on('click', '.remove-officer-button', function(e) {
                        var whichtr = $(this).closest("tr");

                        // alert('worked'); // Alert does not work
                        whichtr.remove();
                    });
                    $("#officers-table1").on('click', '.remove-officer-button1', function(e) {
                        var whichtr = $(this).closest("tr");

                        // alert('worked'); // Alert does not work
                        whichtr.remove();
                    });

                    function addUnit() {
                        var col = $('#new1').html();
                        $("#officers-table1 tbody").append('<tr>' + col + '</tr>');
                    }

                    function addSize() {
                        var col = $('#new2').html();
                        $("#officers-table2 tbody").append('<tr>' + col + '</tr>');
                    }

                    function addOnlycolor() {
                        var col = $('#new3').html();
                        $("#officers-table3 tbody").append('<tr>' + col + '</tr>');
                    }
                    $("#officers-table2").on('click', '.remove-officer-button2', function(e) {
                        var whichtr = $(this).closest("tr");

                        // alert('worked'); // Alert does not work
                        whichtr.remove();
                    });
                    $("#officers-table3").on('click', '.remove-officer-button3', function(e) {
                        var whichtr = $(this).closest("tr");

                        // alert('worked'); // Alert does not work
                        whichtr.remove();
                    });
                </script>
                <script>
                    jQuery('select[name="category"]').on('change', function() {
                        debugger;
                        var val = $(this).val();
                        console.log(val);
                        $('#subcategory').empty();
                        var catid = $('select[name="category"]').val();
                        $.get('/getsubcat', {
                            catid: catid
                        }, function(data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $('#subcategory').append(

                                    '<option value="' + data[i].id + '">' + data[i].name + '</option>'
                                );
                            }
                        });
                    });
                </script>
            @endpush
