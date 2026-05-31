@extends('admin.layouts.main')
@push('styles')
    <style>
        .image-link {
            cursor: -webkit-zoom-in;
            cursor: -moz-zoom-in;
            cursor: zoom-in;
        }


        /* This block of CSS adds opacity transition to background */
        .mfp-with-zoom .mfp-container,
        .mfp-with-zoom.mfp-bg {
            opacity: 0;
            -webkit-backface-visibility: hidden;
            -webkit-transition: all 0.3s ease-out;
            -moz-transition: all 0.3s ease-out;
            -o-transition: all 0.3s ease-out;
            transition: all 0.3s ease-out;
        }

        .mfp-with-zoom.mfp-ready .mfp-container {
            opacity: 1;
        }

        .mfp-with-zoom.mfp-ready.mfp-bg {
            opacity: 0.8;
        }

        .mfp-with-zoom.mfp-removing .mfp-container,
        .mfp-with-zoom.mfp-removing.mfp-bg {
            opacity: 0;
        }


        /* padding-bottom and top for image */
        .mfp-no-margins img.mfp-img {
            padding: 0;
        }

        /* position of shadow behind the image */
        .mfp-no-margins .mfp-figure:after {
            top: 0;
            bottom: 0;
        }

        /* padding for main container */
        .mfp-no-margins .mfp-container {
            padding: 0;
        }


        /* aligns caption to center */
        .mfp-title {
            text-align: center;
            padding: 6px 0;
        }

        .image-source-link {
            color: #DDD;
        }

        .zoom {
            transition: transform .2s;
            /* Animation */
            margin: 0 auto;
        }

        .zoom:hover {
            transform: scale(2);
            /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }


        .filelabel {
            width: 120px;
            border: 2px dashed grey;
            border-radius: 5px;
            display: block;
            padding: 5px;
            transition: border 300ms ease;
            cursor: pointer;
            text-align: center;
            margin: 0;
        }

        .filelabel i {
            font-size: 23px;
            padding-bottom: 5px;
        }

        .filelabel i,
        .filelabel .title {
            color: grey;
            transition: 200ms color;
        }

        .filelabel:hover {
            border: 2px solid #1665c4;
        }

        .filelabel:hover i,
        .filelabel:hover .title {
            color: #1665c4;
        }

        #FileInput {
            display: none;
        }

        .productlist .card-body .table td {
            height: 58px;
            border-bottom: 0;
        }

    </style>
    <style>
        div#progressBar {
            color: green;
            font-size: 15px;
            height: 25px;
        }

        .progress {
            height: 55px !important;
        }

        .btn-close {
            background: #b1b1b1;
            color: red;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            padding: 5px;
            font-weight: 600;
        }

        .btn-close:hover {
            color: red !important;
        }

        .swal-like-alert {
            padding: 20px;
            border-radius: 8px;
            background-color: #e6ffed;
            border: 1px solid #b3f3c0;
            color: #2f855a;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .swal-like-alert i {
            font-size: 24px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">

        {{-- Page top bar menu --}}
        @include('admin.admin_top_bar_category.index')

        <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="importModalLabel"
             aria-hidden="true"
             data-bs-backdrop="static" data-bs-keyboard="false">

            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewModalLabel">📊 Preview Excel Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Import Progress -->
                        <div class="d-none" id="importProgress" style="margin-bottom: 10px;">
                            <div class="progress" style="background: #e6f4ff;padding: 15px; color: green;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     style="width: 0%" id="progressBar">0%
                                </div>
                            </div>
                        </div>
                        <div id="showMessage"></div>
                        <div class="table-responsive" id="importTableContainer">
                            <table class="table table-bordered" id="previewTable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Type</th>
                                    <th>Product Name</th>
                                    <th>Product Description</th>
                                    <th>Product Images</th>
                                    <th>Category</th>
                                    <th>Sub-Category</th>
                                    <th>SKU</th>
                                    <th>Product Price</th>
                                    <th>Quantity</th>
                                    <th>Variant Type</th>
                                    <th>Variant Value</th>
                                    <th>Variant Quantity</th>
                                    <th>Additional Price</th>
                                    <th>Variant Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Rows injected by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <div id="paginationControls" class="d-flex"></div>
                        <div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="startImport">Start Import</button>
                            <button type="button" class="btn btn-danger d-none" id="retryAll">Retry All Failed
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4" id="toplist">
            @if (canAccess('product'))

                {{-- Table top --}}
                <div class="row">
                    <div class="col-md-6">
                        <h4>
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                সব পণ্য
                            @else
                                All Products
                            @endif
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li style="padding:0px;border:0px;"><a href="{{ route('admin.addproducts') }}"
                                                                   class="btn btn-primary"
                                                                   style="display:block;border-radius:0px !important">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        নতুন পণ্য যোগ করুন
                                    @else
                                        Add Product
                                    @endif
                                </a></li>
                                <li style="padding:0px;border:0px;"><a href="javascript:void(0)"
                                    onclick="downloadSelectedOrFilteredExcel()"
                                    class="btn btn-secondary">
                                    Download Excel
                                </a></li>
                            <li style="padding:0px;border:0px;">
                                <a href="#" onclick="BarCodeFormSubmit()"
                                   style="display:block;border-radius:0px !important" class="btn btn-info">Print
                                    Barcode</a>
                            </li>
                            <li style="padding:0px 5px;border:0px;">
                                <form id="formSubmitFile"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <label class="filelabel">
                                        <i class="fa fa-paperclip">
                                        </i>
                                        <span class="title">
                                                Add File
                                            </span>
                                        <input class="FileUpload1" name="file" id="FileInput" type="file"/>
                                    </label>
                                    <li style="padding:0px;border:0px;"><a href="/demo_product_import_template.xlsx"
                                                                   >
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        এক্সপোর্ট
                                    @else
                                        Download Sample
                                    @endif
                                </a></li>
                                </form>
                            </li>
                            <li class="showhidebutton" style="padding:0px 5px;border:0px;">
                                <button id="shh"
                                        style="font-weight: bold; background-color:#f2251b; color: white; padding: 7px 18px; border: 1px solid gray; border-radius: 5px;"
                                        value="https://www.youtube.com/embed/pEwwVSjWBrs">Play Tutorial <img
                                        style="margin-top: -5px;"
                                        src="https://img.icons8.com/external-kiranshastry-solid-kiranshastry/25/ffffff/external-video-tutorial-online-learning-kiranshastry-solid-kiranshastry.png"/>
                                </button>
                            </li>
                        </ul>
                        <form id="barCodeForm" action="{{ route('admin.selectedBarcode') }}" method="post">
                            @csrf
                            <input type="hidden" name="barCodeId" id="BarCodeSelectIds">
                        </form>
                    </div>
                </div>

                {{-- Table data --}}
                <div class="row mt-5 productlist">
                    <div class="col-12">
                        <div class="alert alert-info"
                             style="background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);" role="alert">
                            <span style="color:#fff">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    মোট পণ্য
                                @else
                                    Total Product add
                                @endif{{ $tProduct ?? '' }}/{{ $limit }}
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-1" style="padding-right:1px;">
                                        <form id="submitform" method="post"
                                              action="{{ route('admin.changeproductstatus') }}">
                                            @csrf
                                            <input type="hidden" name="text2" id="selectids">
                                            <input type="hidden" name="type" id="type" value="Product">
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

                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   aria-label="Dollar amount (with dot and two decimal places)"
                                                   id="taskfilter">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                    class="fa fa-search"></i></span>
                                        </div>
                                    </div>

                                    <div class="col-md-1 mt-1">
                                        <label for="formdate">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                তারিখ হইতে
                                            @else
                                                From Date
                                            @endif
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <form action="{{ route('admin.productdatefilter') }}" method="get">
                                            @csrf
                                            <input type="date" name="formdate" id="formdate"
                                                   value="{{ $_GET['formdate'] ?? '' }}" class="form-control"/>
                                    </div>
                                    <div class="col-md-1 mt-1">
                                        <label for="todate">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এখন পর্যন্ত
                                            @else
                                                To Date
                                            @endif
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="enddate" id="todate"
                                               value="{{ $_GET['enddate'] ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-2 filterbtns">
                                        <button type="submit" class="btn btn-info filterbtn"
                                                style="background-color: #7B809A ">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ফিল্টার
                                            @else
                                                Filter
                                            @endif
                                        </button>
                                        </form>
                                        <a href="{{ route('admin.allproducts') }}" class="btn btn-info filterbtn"
                                           style="background-color: #7B809A ">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                        </a>
                                    </div>

                                    <div class="card-body" id="searchingProdutsShow">
                                        @if (Session::has('success_message'))
                                            <div class="alert alert-success" style="color:#fff">
                                                {{ Session::get('success_message') }}</div>
                                        @endif

                                        {{-- For dasktop user --}}
                                        <div class="table-responsive" id="desktoptable">
                                            <table class="table table-striped" width="100%" id="taskfilterresult">
                                                <thead>
                                                <tr>
                                                    <th width="4%"><input type="checkbox" name="ids"
                                                                          id="checkedAll"></th>
                                                    <th width="5%">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            ছবি
                                                        @else
                                                            Image
                                                        @endif
                                                    </th>
                                                    <th width="30%">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            নাম
                                                        @else
                                                            Name
                                                        @endif
                                                    </th>
                                                    <th width="20%">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            দাম
                                                        @else
                                                            Price
                                                        @endif
                                                    </th>
                                                    @if (ModulusStatus($store_id, 9))
                                                        <th width="10%">Position</th>
                                                    @endif

                                                    <th width="10%">SKU</th>
                                                    @if (isAddonActive(13))
                                                        <th width="10%">Barcode</th>
                                                    @endif
                                                    <th width="10%">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            স্ট্যাটাস
                                                        @else
                                                            Status
                                                        @endif
                                                    </th>
                                                    <th width="15%">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            তারিখ
                                                        @else
                                                            Date
                                                        @endif
                                                    </th>
                                                    <th width="11%">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            এডিট/ডিলিট
                                                        @else
                                                            Action
                                                        @endif
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($products as $product)
                                                    <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                        <td><input type="checkbox" name="selectedid"
                                                                   value="{{ $product->id }}" id="id"
                                                                   class="checkSingle"></td>
                                                        <td>
                                                            @php
                                                                $images = array_filter(explode(',', $product->images));
                                                                $gallery_image = array_filter(explode(',', $product->gallery_image));
                                                                $mergedImages = array_unique(array_merge($gallery_image, $images));
                                                            @endphp
                                                            @if(count($mergedImages) && isset($mergedImages[0]) && !empty($mergedImages[0]))
                                                                <img
                                                                    src="{{ getPath($mergedImages[0], "assets/images/product") }}"
                                                                    class="zoom" width="30px">
                                                            @endif
                                                        </td>
                                                        <td>{{ Str::of($product->name)->limit(50) }}</td>
                                                        <td>{{$currency->symbol ?? ""}} {{ $product->regular_price }}</td>

                                                        @if (ModulusStatus($store_id, 9))
                                                            <td>
                                                                <input type="hidden" name="idss" id="id"
                                                                       value="{{ $product->id }}">
                                                                <input type="number" class="form-control"
                                                                       name="position"
                                                                       value="{{ $product->position??0 }}">
                                                            </td>
                                                        @endif

                                                        <td>
                                                            @if (isset($product->SKU) && $product->SKU != '')
                                                                {{ $product->SKU?? '' }}
                                                            @endif
                                                        </td>
                                                        @if (isAddonActive(13))
                                                            <td>
                                                                @if (isset($product->barcode) && $product->barcode != '')
                                                                    <div
                                                                        class="barcode">{!! DNS1D::getBarcodeHTML(ucwords($product->barcode), 'C128', 1.4, 22) !!}</div>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <div class="form-check form-switch"
                                                                 style="text-align:center;">
                                                                <input class="form-check-input switchstatus"
                                                                       type="checkbox"
                                                                       id="flexSwitchCheckChecked"
                                                                       data-id="{{ $product->id }}"
                                                                       style="margin:0 auto;"
                                                                       @if ($product->status == 'active') checked @endif>
                                                                <label class="form-check-label"
                                                                       for="flexSwitchCheckChecked"></label>
                                                            </div>
                                                        </td>
                                                        <td>{{ date('d-m-Y', strtotime($product->created_at)) }}</td>
                                                        <td class="d-flex justify-content-around align-items-center">
                                                            @if(ModulusStatus($store_id, 121))
                                                                <a href="{{ URL::to('/') }}/layout-products/edit/{{ $product->id }}"
                                                                   style="font-size: 25px; margin-right: 5px"><i
                                                                        class="fa fa-adjust" aria-hidden="true"></i></a>
                                                            @endif
                                                            <a href="{{ URL::to('/') }}/products/edit/{{ $product->id }}"><img
                                                                    src="{{ asset('img/edit.png') }}" width="20px"
                                                                    height="20px"></a>
                                                            <a href="{{ URL::to('/') }}/deleteproduct/{{ $product->id }}"
                                                               onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                                    src="{{ asset('img/delete.png') }}" width="25px"
                                                                    height="25px"></a>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                </tbody>
                                            </table>
                                            {!! $products->links() !!}
                                        </div>

                                        {{-- For mobile user --}}
                                        <div class="table-responsive mt-3" id="mobiletable">
                                            <table class="table" width="100%">
                                                @foreach ($products as $key => $product)
                                                    <tr class="mobilefirstrow">
                                                        <th width="10%">
                                                            <input type="checkbox" name="selectedid"
                                                                   value="{{ $product->id }}"
                                                                   id="id" class="checkSingle">
                                                        </th>
                                                        <th width="20%" style="color:#f1593a">
                                                            Name:
                                                        </th>
                                                        <td width="60%" style="color:black">
                                                            {{ Str::of($product->name)->limit(20) }}
                                                        </td>
                                                        <td width="10%">
                                                            <a href="#" class="toggler"
                                                               data-prod-cat="{{ $key }}">
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
                                                            Image
                                                        </th>
                                                        <td width="60%">

                                                            @php
                                                                $images = array_filter(explode(',', $product->images));
                                                                $gallery_image = array_filter(explode(',', $product->gallery_image));
                                                                $mergedImages = array_unique(array_merge($gallery_image, $images));
                                                                $images = array_map(fn($img) => getPath($img, 'assets/images/product'), $mergedImages);
                                                            @endphp
                                                            @if (isset($images[0]))
                                                                <img
                                                                    src="{{ $images[0] }}"
                                                                    class="zoom" width="30px">
                                                            @endif
                                                        </td>
                                                        <td width="10%"></td>
                                                    </tr>
                                                    <tr class="cat{{ $key }}" style="display:none">
                                                        <th width="10%"></th>
                                                        <th width="20%">
                                                            Price
                                                        </th>
                                                        <td width="60%">
                                                            {{$currency->symbol}} {{ $product->regular_price }}
                                                        </td>
                                                        <td width="10%"></td>
                                                    </tr>
                                                    <tr class="cat{{ $key }}" style="display:none">
                                                        <th width="10%"></th>
                                                        <th width="20%">
                                                            SKU
                                                        </th>
                                                        <td width="60%">
                                                            @if (isset($product->SKU) && $product->SKU != '')
                                                                {{ $product->SKU?? '' }}
                                                            @endif
                                                        </td>
                                                        <td width="10%"></td>
                                                    </tr>
                                                    <tr class="cat{{ $key }}" style="display:none">
                                                        <th width="10%"></th>
                                                        <th width="20%">
                                                            Barcode
                                                        </th>
                                                        <td width="60%">
                                                            @if (isset($product->barcode) && $product->barcode != '')
                                                                {!! DNS1D::getBarcodeHTML(ucwords($product->barcode), 'C128', 1.4, 22) !!}
                                                            @endif
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
                                                            <div class="form-check form-switch"
                                                                 style="text-align:center;">
                                                                <input class="form-check-input switchstatus"
                                                                       type="checkbox"
                                                                       id="flexSwitchCheckChecked"
                                                                       data-id="{{ $product->id }}"
                                                                       style="margin:0 auto;"
                                                                       @if ($product->status == 'active') checked @endif>
                                                                <label class="form-check-label"
                                                                       for="flexSwitchCheckChecked"></label>
                                                            </div>
                                                        </td>
                                                        <td width="10%"></td>
                                                    </tr>
                                                    <tr class="cat{{ $key }}" style="display:none">
                                                        <th width="10%"></th>
                                                        <th width="20%">
                                                            Date
                                                        </th>
                                                        <td width="60%">
                                                            {{ date('d-m-Y', strtotime($product->created_at)) }}
                                                        </td>
                                                        <td width="10%"></td>
                                                    </tr>
                                                    <tr class="cat{{ $key }}" style="display:none">
                                                        <th width="10%"></th>
                                                        <th width="20%">
                                                            Action
                                                        </th>
                                                        <td class="d-flex justify-content-around align-items-center">
                                                            @if(ModulusStatus($store_id, 121))
                                                                <a href="{{ URL::to('/') }}/layout-products/edit/{{ $product->id }}"
                                                                   style="font-size: 25px; margin-right: 5px"><i
                                                                        class="fa fa-adjust" aria-hidden="true"></i></a>
                                                            @endif
                                                            <a href="{{ URL::to('/') }}/products/edit/{{ $product->id }}"><img
                                                                    src="{{ asset('img/edit.png') }}" width="20px"
                                                                    height="20px"></a>
                                                            <a href="{{ URL::to('/') }}/deleteproduct/{{ $product->id }}"
                                                               onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                                    src="{{ asset('img/delete.png') }}" width="25px"
                                                                    height="25px"></a>
                                                        </td>
                                                        <td width="10%"></td>
                                                    </tr>
                                                @endforeach

                                            </table>
                                            {!! $products->links() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        let excelData = [];
        const rowsPerPage = 10;
        let currentPage = 1;

        function renderTablePage(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const pageData = excelData.slice(start + 1, end + 1); // +1 because row[0] is header

            const tbody = document.querySelector('#previewTable tbody');
            tbody.innerHTML = '';

            pageData.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${start + index + 1}</td>
                    <td contenteditable="true">${row[0] ?? ''}</td>  <!-- Type -->
                    <td contenteditable="true">${row[1] ?? ''}</td>  <!-- Name -->
                    <td contenteditable="true">${row[2] ?? ''}</td>  <!-- Description -->
                    <td contenteditable="true">${row[3] ?? ''}</td>  <!-- Product Images -->
                    <td contenteditable="true">${row[4] ?? ''}</td>  <!-- Category -->
                    <td contenteditable="true">${row[5] ?? ''}</td>  <!-- Sub Category -->
                    <td contenteditable="true">${row[6] ?? ''}</td>  <!-- SKU -->
                    <td contenteditable="true">${row[7] ?? ''}</td>  <!-- Price -->
                    <td contenteditable="true">${row[8] ?? ''}</td>  <!-- Quantity -->
                    <td contenteditable="true">${row[9] ?? ''}</td>  <!-- Variant Type -->
                    <td contenteditable="true">${row[10] ?? ''}</td> <!-- Variant Value -->
                    <td contenteditable="true">${row[11] ?? ''}</td> <!-- Variant Quantity -->
                    <td contenteditable="true">${row[12] ?? ''}</td> <!-- Additional Price -->
                    <td contenteditable="true">${row[13] ?? ''}</td> <!-- Variant Image -->
                    <td class="status">⏳ Pending</td>
                    <td><button class="btn btn-sm btn-warning retry d-none">Retry</button></td>
            `;
                tbody.appendChild(tr);
            });

            renderPaginationControls();
        }

        function renderPaginationControls() {
            const totalPages = Math.ceil((excelData.length - 1) / rowsPerPage); // skip header
            const container = document.getElementById('paginationControls');
            container.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = `btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-secondary'} me-1`;
                btn.innerText = i;
                btn.onclick = () => {
                    currentPage = i;
                    renderTablePage(currentPage);
                };
                container.appendChild(btn);
            }
        }

        let lastFailedRows = [];

        document.getElementById('startImport').addEventListener('click', async function () {

    const rows = document.querySelectorAll('#previewTable tbody tr');

    if (!rows.length) {
        Swal.fire('⚠️ No data found to import.', '', 'warning');
        return;
    }

    const allRows = [];

    rows.forEach(row => {
        allRows.push({
            type: row.cells[1].innerText.trim(),
            name: row.cells[2].innerText.trim(),
            description: row.cells[3].innerText.trim(),
            product_images: row.cells[4].innerText.trim(),
            category: row.cells[5].innerText.trim(),
            subcategory: row.cells[6].innerText.trim(),
            sku: row.cells[7].innerText.trim(),
            price: row.cells[8].innerText.trim(),
            quantity: row.cells[9].innerText.trim(),
            variant_type: row.cells[10].innerText.trim(),
            variant_value: row.cells[11].innerText.trim(),
            variant_quantity: row.cells[12].innerText.trim(),
            additional_price: row.cells[13].innerText.trim(),
            variant_image: row.cells[14].innerText.trim(),
        });
    });

    const totalRows = allRows.length;

    document.getElementById('importProgress').classList.remove('d-none');
    const bar = document.getElementById('progressBar');

    try {
        const response = await axios.post(
            "{{ route('admin.products.import.process') }}",
            { rows: allRows },
            {
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json'
                }
            }
        );

        const result = response.data;
        lastFailedRows = [];

        rows.forEach((row, index) => {
            const statusCell = row.querySelector('.status');
            const retryBtn = row.querySelector('.retry');

            const failedRow = result.failed.find(r => r.index === index);

            if (failedRow) {
                statusCell.innerHTML = `❌ Failed: ${failedRow.error}`;
                retryBtn.classList.remove('d-none');
                lastFailedRows.push(allRows[index]);
            } else {
                row.remove();
            }

            const percent = Math.round(((index + 1) / totalRows) * 100);
            bar.style.width = percent + '%';
            bar.innerText = percent + '%';
        });

        if (lastFailedRows.length === 0) {
            document.getElementById('importTableContainer').classList.add('d-none');
            document.getElementById('startImport').classList.add('d-none');
            document.getElementById('retryAll').classList.add('d-none');
            document.getElementById('paginationControls').innerHTML = "";

            document.getElementById('showMessage').innerHTML = `
                <div class="swal-like-alert">
                    <i class="fa fa-check-circle"></i>
                    All products imported successfully!
                </div>
            `;
        } else {
            document.getElementById('startImport').classList.add('d-none');
            document.getElementById('retryAll').classList.remove('d-none');
            Swal.fire('⚠️ Some rows failed. Fix them or click Retry.', '', 'warning');
        }

    } catch (error) {
        console.error(error);
        Swal.fire('❌ Import request failed.', '', 'error');
    }
});


        document.addEventListener('click', async function (e) {
            if (e.target.classList.contains('retry')) {
                const row = e.target.closest('tr');
                const data = {
                    type: row.cells[1].innerText.trim(),
                    name: row.cells[2].innerText.trim(),
                    description: row.cells[3].innerText.trim(),
                    product_images: row.cells[4].innerText.trim(),
                    category: row.cells[5].innerText.trim(),
                    subcategory: row.cells[6].innerText.trim(),
                    sku: row.cells[7].innerText.trim(),
                    price: row.cells[8].innerText.trim(),
                    quantity: row.cells[9].innerText.trim(),
                    variant_type: row.cells[10].innerText.trim(),
                    variant_value: row.cells[11].innerText.trim(),
                    variant_quantity: row.cells[12].innerText.trim(),
                    additional_price: row.cells[13].innerText.trim(),
                    variant_image: row.cells[14].innerText.trim(),
                };

                const statusCell = row.querySelector('.status');
                const retryBtn = row.querySelector('.retry');

                statusCell.innerHTML = '⏳ Retrying...';
                retryBtn.disabled = true;

                try {
                    const response = await axios.post(
                        "{{ route('admin.products.import.process') }}",
                        {rows: [data]},
                        {
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Content-Type': 'application/json'
                            }
                        }
                    );

                    const failed = response.data.failed;

                    if (failed.length === 0) {
                        statusCell.innerHTML = '✅ Success';
                        retryBtn.classList.add('d-none');
                        row.remove();
                        Swal.fire('✅ Row imported successfully!', '', 'success');
                    } else {
                        statusCell.innerHTML = `❌ Failed: ${failed[0].error}`;
                        retryBtn.disabled = false;
                        Swal.fire('⚠️ Still failed.', failed[0].error, 'error');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('❌ Retry failed due to error.', '', 'error');
                }
            }
        });


        document.getElementById('retryAll').addEventListener('click', async function () {
            if (lastFailedRows.length === 0) return Swal.fire('✅ No failed rows to retry.');

            try {
                const response = await axios.post(
                    "{{ route('admin.products.import.process') }}",
                    {rows: lastFailedRows},
                    {
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json'
                        }
                    }
                );

                const result = response.data;
                let retriedSuccess = 0;

                document.querySelectorAll('#previewTable tbody tr').forEach((row, index) => {
                    const statusCell = row.querySelector('.status');
                    const retryBtn = row.querySelector('.retry');

                    const failedRow = result.failed.find(r => r.index === index);
                    if (!failedRow) {
                        statusCell.innerHTML = '✅ Success';
                        retryBtn.classList.add('d-none');
                        row.remove();
                        retriedSuccess++;
                    } else {
                        statusCell.innerHTML = `❌ Failed: ${failedRow.error}`;
                    }
                });

                Swal.fire(`🔁 Retried: ${retriedSuccess} succeeded.`, '', 'info');
            } catch (err) {
                console.error(err);
                Swal.fire('❌ Retry all failed.', '', 'error');
            }
        });


        $("#FileInput").on('change', function (e) {
            var labelVal = $(".title").text();
            var oldfileName = $(this).val();
            fileName = e.target.value.split('\\').pop();

            if (oldfileName == fileName) {
                return false;
            }
            var extension = fileName.split('.').pop();

            console.log("extension", extension);
            if (fileName) {
                if (fileName.length > 10) {
                    $(".filelabel .title").text(fileName.slice(0, 4) + '...' + extension);
                } else {
                    $(".filelabel .title").text(fileName);
                }
            } else {
                $(".filelabel .title").text(labelVal);
            }

            extension = extension.toLowerCase();

if ($.inArray(extension, ['jpg', 'jpeg', 'png']) >= 0) {
    $(".filelabel i").removeClass().addClass('fa fa-file-image-o');
    $(".filelabel i, .filelabel .title").css({'color': '#208440'});
    $(".filelabel").css({'border': '2px solid #208440'});
} else if (extension === 'pdf') {
    $(".filelabel i").removeClass().addClass('fa fa-file-pdf-o');
    $(".filelabel i, .filelabel .title").css({'color': 'red'});
    $(".filelabel").css({'border': '2px solid red'});
} else if (extension === 'doc' || extension === 'docx') {
    $(".filelabel i").removeClass().addClass('fa fa-file-word-o');
    $(".filelabel i, .filelabel .title").css({'color': '#2388df'});
    $(".filelabel").css({'border': '2px solid #2388df'});
} else if (extension === 'xls' || extension === 'xlsx' || extension === 'csv') {
    $(".filelabel i").removeClass().addClass('fa fa-file-excel-o');
    $(".filelabel i, .filelabel .title").css({'color': '#1D6F42'});
    $(".filelabel").css({'border': '2px solid #1D6F42'});
    handlePreview();
} else {
    $(".filelabel i").removeClass().addClass('fa fa-file-o');
    $(".filelabel i, .filelabel .title").css({'color': 'black'});
    $(".filelabel").css({'border': '2px solid black'});
    Swal.fire('⚠️ Please select a valid Excel file (.xls, .xlsx, .csv).', '', 'warning');
}
        });

        // document.getElementById('FileInput').addEventListener('change', handlePreview);
        function downloadSelectedOrFilteredExcel()
{
    let selectedIds = [];

    $(".checkSingle:checked").each(function () {
        selectedIds.push($(this).val());
    });

    const search = $("#taskfilter").val() || '';
    const formdate = $("#formdate").val() || '';
    const enddate = $("#todate").val() || '';

    let url = "{{ url('/admin/products/export-selected-filtered-excel') }}?";

    if (selectedIds.length > 0) {
        url += "ids=" + selectedIds.join(',');
    } else {
        url += "search=" + encodeURIComponent(search)
            + "&formdate=" + encodeURIComponent(formdate)
            + "&enddate=" + encodeURIComponent(enddate);
    }

    window.location.href = url;
}

        function handlePreview() {
            const fileInput = document.getElementById('FileInput');
            if (!fileInput.files[0]) return Swal.fire('⚠️ Please select an Excel file.', '', 'warning');

            var importTableContainer = document.getElementById('importTableContainer');
            if (importTableContainer && importTableContainer.classList.contains('d-none')) {
                importTableContainer.classList.remove('d-none');
            }

            var startImport = document.getElementById('startImport');
            if (startImport && startImport.classList.contains('d-none')) {
                startImport.classList.remove('d-none');
            }

            var retryAll = document.getElementById('retryAll');
            if (retryAll && !retryAll.classList.contains('d-none')) {
                retryAll.classList.add('d-none');
            }

            document.getElementById('showMessage').innerHTML = ``;

            const formData = new FormData();
            formData.append('excel_file', fileInput.files[0]);

            axios.post("{{ route('admin.products.import.preview') }}", formData, {
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    excelData = response.data;

                    currentPage = 1;
                    renderTablePage(currentPage);
                    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
                    modal.show();

                })
                .catch(error => {
                    console.error("Preview failed:", error);
                    Swal.fire('⚠️ Failed to load preview.', '', 'warning')
                });
        }
    </script>

    <script>
        function BarCodeFormSubmit() {
            $('#barCodeForm').submit();
        }
    </script>
    <script>
        $(document).ready(function () {
            $('input[name=position]').change(function () {
                var value = $(this).val();
                var id = $(this).parent().parent().find("input[name=idss]").val();
                $url = "/update-position-product";
                $.get($url, {
                    value: value,
                    id: id
                }, function (data) {
                    window.location.reload();
                    // $('#testimonials').load(location.href + ' .testi');
                });
            });
        });
    </script>
    <script>
        $('#submit').on('click', function () {
            var form = $(this).parents('form');
            var note = $('#action').val();
            if (note != 'select') {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You want to " + note + " this selected item",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + note + ' it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        console.log(form);
                        $('#submitform').submit();
                        form.submit();
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swal.fire(
                            'Cancelled',
                            '' + note + ' Cancel :)',
                            'error'
                        )
                    }
                })
            }
        })

        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                $url = "/changeprostatus";
                var value = $(this).val();
                var id = $(this).data('id');
                $.get($url, {
                    value: value,
                    id: id
                }, function (data) {
                    // console.log(data);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            let valuesArray = [];

            // Check all checkbox action
            $("#checkedAll").change(function () {
                if (this.checked) {
                    // If "checkedAll" is checked, check all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = true;
                        let value = $(this).val();
                        if (!valuesArray.includes(value)) {
                            valuesArray.push(value);
                        }
                    });
                } else {
                    // If "checkedAll" is unchecked, uncheck all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    valuesArray = [];
                }

                let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                $("#selectids").val(newAaluesArray);
            });

            // Single check action
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    let value = $(this).val();

                    let isAllChecked = $(".checkSingle").length === $(".checkSingle:checked").length;
                    $("#checkedAll").prop("checked", isAllChecked);

                    if (!valuesArray.includes(value)) {
                        valuesArray.push(value);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
                } else {
                    $("#checkedAll").prop("checked", false);

                    let value = $(this).val();

                    let index = valuesArray.indexOf(value);

                    if (index === -1) {
                        valuesArray.push(value);
                    } else {
                        valuesArray.splice(index, 1);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
                }
            });
        });

        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                // debugger;
                var value = $(this).val();
                var store_id = {{ $store_id }};
                // debugger;
                $url = "/products/searching/";
                $.get($url, {
                    search: value,
                    store_id: store_id
                }, function (data) {
                    $('#searchingProdutsShow').html('');
                    $('#searchingProdutsShow').html(data);
                    // window.location.reload();
                    // $('#testimonials').load(location.href + ' .testi');

                    $(".switchstatus").on("change", function () {
                        $url = "/changeprostatus";
                        var value = $(this).val();
                        var id = $(this).data('id');
                        $.get($url, {
                            value: value,
                            id: id
                        }, function (data) {
                            // xiao
                        });
                    });
                });
            });
        });

    </script>


@endpush
