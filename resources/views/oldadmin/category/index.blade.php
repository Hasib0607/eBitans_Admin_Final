@extends('admin.layouts.main')
@push('styles')
    <style>
        .fade:not(.show) {
            opacity: 1 !important;
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
@endpush
@section('content')
    <?php
    if (Auth::user()->type == 'admin') {
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
                    $categorys = 1;
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
                } else {
                }
            }
        }
    }
    $store = DB::table('stores')
        ->where('id', $store_id)
        ->first();
    if ($store->expiry_date <= Carbon\Carbon::now()) {
        $exp = 1;
    } else {
        $exp = 0;
    }
    ?>
    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/') }}/products">
                                    <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            পণ্য
                                        @else
                                            Products
                                        @endif
                                    </span>
                                </a>
                            </li>
                            @if ((isset($category) && $category == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{ URL::to('/') }}/category">
                                        <img src="{{ URL::to('/') }}/img/icons/categories.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ক্যাটাগরি
                                            @else
                                                Categories
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if ((isset($subcategory) && $subcategory == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ route('admin.subcategory.index') }}">
                                        <img src="{{ URL::to('/') }}/img/subcategory.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                সাব ক্যাটাগরি
                                            @else
                                                Sub Categories
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if ((isset($attribute) && $attribute == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/attribute">
                                        <img src="{{ URL::to('/') }}/img/icons/product.png"><br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পণ্যের ধরণ
                                            @else
                                                Variants
                                            @endif
                                        </span>

                                    </a>
                                </li>
                            @endif
                            @if ((isset($brand) && $brand == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/brand">
                                        <img src="{{ URL::to('/') }}/img/icons/brand.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ব্রান্ড
                                            @else
                                                Brands
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif

                            @if ((isset($supplier) && $supplier == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/supplier">
                                        <img src="{{ URL::to('/') }}/img/icons/supplier.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                সরবরাহকারী
                                            @else
                                                Suppliers
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            সমস্ত ক্যাটাগরি
                        @else
                            All Categories
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a data-href="/categoryexport"
                                onclick="exportCategory(event.target);" style="display:block;border-radius:0px !important"
                                class="btn btn-secondary">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    এক্সপোর্ট
                                @else
                                    Excel
                                @endif
                            </a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    নতুন ক্যাটাগরি যোগ করুন
                                @else
                                    Add Category
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ URL::to('category') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            নাম
                                        @else
                                            Name
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="staticEmail" name="name"
                                            placeholder="Category Name">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="icon" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আইকন
                                        @else
                                            Icon
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <select id='iconpack' name="icon" class="form-control"
                                            style="width:100% !important">
                                            <option value="null">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    আইকন নির্বাচন করুন
                                                @else
                                                    Select Icon
                                                @endif
                                            </option>
                                            <?php
                                            $icons = DB::table('iconpacks')->get();
                                            ?>
                                            @if (isset($icons) && count($icons) > 0)
                                                @foreach ($icons as $icon)
                                                    <option value='{{ $icon->image }}'>{{ $icon->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                        @error('icon')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="banner" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ব্যানার
                                        @else
                                            Banner
                                        @endif
                                        <span class="req">*</span>
                                    </label>

                                    <div class="col-md-8">


                                        {{-- <div id="image-holder" style="text-align: center">
                                        <img id="imagePreview" src="{{ URL::to('/') }}/img/upload.svg"
                                    alt=""
                                    style="max-width: 100px;margin-bottom: 20px;vertical-align: baseline;cursor:pointer">
                                    </div> --}}
                                        <output id="Filelist"></output>


                                        <input type="file" class="form-control" id="banner" name="banner">
                                        @error('banner')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            স্ট্যাটাস
                                        @else
                                            Status
                                        @endif
                                    </label>
                                    <div class="col-md-8">
                                        <div class="form-check form-switch is-filled" style="text-align:center;">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                                name="status" style="margin:0 auto;" checked="">
                                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                        </div>
                                        @error('status')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            অবস্থান
                                        @else
                                            Position
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" id="position" name="position"
                                            placeholder="0" autofocus="">
                                        @error('position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8" style="text-align:right">
                                        <button type="submit" class="btn btn-info">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                জমা দিন
                                            @else
                                                Submit
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form id="submitform" method="post"
                                        action="{{ route('admin.changecategorystatus') }}">
                                        @csrf
                                        <input type="hidden" name="text2" id="selectids">
                                        <select class='form-control' name="action" id="action">
                                            <option value="select">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সিলেক্ট অপসন
                                                @else
                                                    Select Option
                                                @endif
                                            </option>
                                            <option value="active">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সক্রিয়
                                                @else
                                                    Active
                                                @endif
                                            </option>
                                            <option value="deactive">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    নিষ্ক্রিয়
                                                @else
                                                    Deactive
                                                @endif
                                            </option>
                                            <option value="delete">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ডিলিট
                                                @else
                                                    Delete
                                                @endif
                                            </option>
                                        </select>
                                </div>
                                <div class="col-md-1" style="padding-left:0px;">
                                    <p id="submit" class="btn btn-primary filterbuttonss">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আবেদন
                                        @else
                                            Apply
                                        @endif
                                    </p>
                                    </form>
                                </div>

                                <div class="col-md-6"></div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                class="fa fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" width="100%" id="taskfilterresult">
                                    <thead>
                                        <tr>
                                            <th width="4%"><input type="checkbox" name="ids" id="checkedAll">
                                            </th>
                                            <th width="5%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    আইকন
                                                @else
                                                    Icon
                                                @endif
                                            </th>
                                            <th width="20%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ব্যানার
                                                @else
                                                    Banner
                                                @endif
                                            </th>
                                            <th width="20%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    নাম
                                                @else
                                                    Name
                                                @endif
                                            </th>
                                            <th width="10%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    পণ্য
                                                @else
                                                    Product
                                                @endif
                                            </th>
                                            <th width="5%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    অবস্থান
                                                @else
                                                    Position
                                                @endif
                                            </th>
                                            <th width="10%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    স্ট্যাটাস
                                                @else
                                                    Status
                                                @endif
                                            </th>
                                            <th width="21%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    এডিট/ডিলিট
                                                @else
                                                    Action
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($catgories as $cat)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td><input type="checkbox" name="selectedid" value="{{ $cat->id }}"
                                                        id="id" class="checkSingle"></td>
                                                <td>
                                                    <!--<i class="fa {{ $cat->icon }}"></i>-->
                                                    <img src="{{ URL::to('/') }}/assets/images/icon/{{ $cat->icon }}"
                                                        width="40px">
                                                </td>
                                                <td><img src="{{ URL::to('/') }}/assets/images/category/{{ $cat->banner }}"
                                                        width="60px"></td>
                                                <td>{{ $cat->name }}</td>
                                                <?php
                                                $producct = DB::table('products')
                                                    ->where('category', $cat->id)
                                                    ->get();
                                                ?>
                                                <td>{{ count($producct) ?? '0' }}</td>
                                                <td>
                                                    <input type="hidden" name="idss" id="id"
                                                        value="{{ $cat->id }}">
                                                    <input type="number" value="{{ $cat->position ?? '0' }}"
                                                        name="position" id="position" style="width:70%">
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch" style="text-align:center;">
                                                        <input class="form-check-input switchstatus" type="checkbox"
                                                            data-id="{{ $cat->id }}" id="flexSwitchCheckChecked"
                                                            name="checkstatus" style="margin:0 auto;"
                                                            @if ($cat->status == 'active') checked @endif>
                                                        <label class="form-check-label"
                                                            for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <!--<form action="{{ URL::to('/') }}/category/{{ $cat->id }}"  method="POST">-->
                                                    <!--    <input type="hidden" name="_method" value="DELETE">-->
                                                    <!--    <input type="hidden" name="_token" value="{{ csrf_token() }}">-->
                                                    <!--    <a onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{ asset('img/delete.png') }}" width="20px" height="20px"></a>-->
                                                    <!--</form>                                    -->
                                                    <a href="{{ URL::to('/') }}/category/{{ $cat->id }}/edit"><img
                                                            src="{{ asset('img/edit.png') }}" width="20px"
                                                            height="20px"></a>
                                                    &nbsp;&nbsp;
                                                    <a href="{{ URL::to('/') }}/category/{{ $cat->id }}/delete"
                                                        onclick="del()"><img src="{{ asset('img/delete.png') }}"
                                                            width="25px" height="25px"></a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-3" id="mobiletable">
                                <table class="table" width="100%">
                                    @foreach ($catgories as $key => $cat)
                                        <tr class="mobilefirstrow">
                                            <th width="10%">
                                                <input type="checkbox" name="selectedid" value="{{ $cat->id }}"
                                                    id="id" class="checkSingle">
                                            </th>
                                            <th width="20%" style="color:#f1593a">
                                                Name:
                                            </th>
                                            <td width="60%" style="color:black">
                                                {{ $cat->name }}
                                            </td>
                                            <td width="10%">
                                                <a href="#" class="toggler" data-prod-cat="{{ $key }}">
                                                    <i class="fa fa-arrow-down" id="show{{ $key }}"
                                                        style="color:#f1593a"></i>
                                                    <i class="fa fa-arrow-up" id="up{{ $key }}"
                                                        style="display:none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Icon
                                            </th>
                                            <td width="60%">
                                                <img src="{{ URL::to('/') }}/assets/images/icon/{{ $cat->icon }}"
                                                    width="40px">
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Banner
                                            </th>
                                            <td width="60%">
                                                <img src="{{ URL::to('/') }}/assets/images/category/{{ $cat->banner }}"
                                                    width="60px">
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Product
                                            </th>
                                            <td width="60%">
                                                <?php
                                                $producct = DB::table('products')
                                                    ->where('category', $cat->id)
                                                    ->get();
                                                ?>
                                                {{ count($producct) ?? '0' }}
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Position
                                            </th>
                                            <td width="60%">
                                                <input type="hidden" name="idss" id="id"
                                                    value="{{ $cat->id }}">
                                                <input type="number" value="{{ $cat->position ?? '0' }}"
                                                    name="position" id="position" style="width:70%">
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Status
                                            </th>
                                            <td width="60%"
                                                style="display: flex;justify-content: center;align-items: center;">
                                                <div class="form-check form-switch" style="text-align:center;">
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                        id="flexSwitchCheckChecked" data-id="{{ $cat->id }}"
                                                        style="margin:0 auto;"
                                                        @if ($cat->status == 'active') checked @endif>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Action
                                            </th>
                                            <td width="60%">
                                                <a href="{{ URL::to('/') }}/category/{{ $cat->id }}/edit"><img
                                                        src="{{ asset('img/edit.png') }}" width="20px"
                                                        height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{ URL::to('/') }}/category/{{ $cat->id }}/delete"
                                                    onclick="del()"><img src="{{ asset('img/delete.png') }}"
                                                        width="25px" height="25px"></a>
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--<button class="btn btn-danger action-destroy"></button>-->
@endsection

@push('scripts')


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
                .querySelector("#banner")
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
                .getElementById("banner")
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

            var div = document.createElement("div");
            div.className = "FileNameCaptionStyle";
            li.appendChild(div);
            div.innerHTML = [readerEvt.name].join("");
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
        // document.querySelector('input').addEventListener('mouseup', (e) => {
        //     e.preventDefault();
        //     debugger;
        // });
        document.querySelector('input').addEventListener("click", function(event) {
            event.preventDefault()
        });
        $('#submit').on('click', function() {
            var form = $(this).parents('form');
            var note = $('#action').val();
            if (note != 'select') {
                swal.fire({
                    title: 'Are you sure?',
                    text: "you want to " + note + " this category?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + note + ' it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        swal.fire({
                            title: 'Are you sure?',
                            text: "Your all data will be deleted like product,subcategory, are you sure you want to " +
                                note + "?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, ' + note + ' it!',
                            cancelButtonText: 'No, cancel!',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.value) {
                                $('#submitform').submit();
                            } else if (
                                result.dismiss === Swal.DismissReason.cancel
                            ) {
                                swal.fire(
                                    'Cancelled',
                                    'Deletion Cancel :)',
                                    'error'
                                )
                            }
                        })
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swal.fire(
                            'Cancelled',
                            'Deletion Cancel :)',
                            'error'
                        )
                    }
                })
            }
        });
        $(document).ready(function() {
            $('input[name=position]').change(function() {
                var value = $(this).val();
                var id = $(this).parent().parent().find("input[name=idss]").val();
                $url = "/update-position-category";
                $.get($url, {
                    value: value,
                    id: id
                }, function(data) {
                    window.location.reload();
                    // $('#testimonials').load(location.href + ' .testi');
                });
            });
        });
    </script>
    <script>
        function del() {
            let av = confirm("are you sure you want to delete this category?");
            if (av) {
                return confirm("Your all data will be deleted like product,subcategory, are you sure you want to delete?");
            }
        }
        $('.icon').iconpicker();
        $('.action-destroy').on('click', function() {
            $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
        });
        $(document).ready(function() {
            $(".switchstatus").on("change", function() {
                $url = "/changecatstatus";
                var value = $(this).val();
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {
                    value: value,
                    id: id
                }, function(data) {
                    console.log(data);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#checkedAll").change(function() {
                debugger;
                if (this.checked) {
                    $(".checkSingle").each(function() {
                        debugger;
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function() {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function() {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function() {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAll").prop("checked", true);
                    }
                } else {
                    $("#checkedAll").prop("checked", false);
                    var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
        $(document).ready(function() {
            $("#taskfilter").on("keyup", function() {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function() {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportCategory(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
