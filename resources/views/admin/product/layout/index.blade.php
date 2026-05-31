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
            transform: scale(7.5);
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
    </style>
@endpush
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">

        {{-- Page top bar menu --}}
        @include('admin.admin_top_bar_category.index')

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
                            <li style="padding:0px;border:0px;"><a href="{{ route('admin.layout_product_create') }}"
                                                                   class="btn btn-primary"
                                                                   style="display:block;border-radius:0px !important">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        নতুন পণ্য যোগ করুন
                                    @else
                                        Add Product
                                    @endif
                                </a></li>
                            <li style="padding:0px;border:0px;"><a data-href="/tasks" onclick="htmlTableToExcel('xlsx')"
                                                                   style="display:block;border-radius:0px !important"
                                                                   class="btn btn-secondary">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        এক্সপোর্ট
                                    @else
                                        Download Excel
                                    @endif
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
                                        <input class="FileUpload1" name="file" id="FileInput" name="booking_attachment"
                                               type="file"/>
                                    </label>
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
                                    <div class="col-md-2">
                                        <form class="row" id="submitform" method="post"
                                              action="{{ route('admin.changeproductstatus') }}">
                                            @csrf
                                            <div class="col-md-8" style="padding-right:1px;">
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
                                            <div class="col-md-4" style="padding-left:0px;">
                                                <p id="submit" class="btn btn-primary filterbuttonss">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        আবেদন
                                                    @else
                                                        Apply
                                                    @endif
                                                </p>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-10">
                                        <form class="row" action="{{ route('admin.layout.product.filter') }}"
                                              method="get">
                                            @csrf
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <input type="text" name="search" id="search"
                                                           value="{{ $_GET['search'] ?? '' }}" class="form-control">
                                                    <span class="input-group-text"
                                                          style="padding: 0.75rem 11px !important;"><i
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
                                                <a href="{{ route('admin.layout_product') }}"
                                                   class="btn btn-info filterbtn"
                                                   style="background-color: #7B809A ">
                                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </form>
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
                                                @if(count($products) > 0)
                                                    @foreach ($products as $product)
                                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                            <td><input type="checkbox" name="selectedid"
                                                                       value="{{ $product->id }}" id="id"
                                                                       class="checkSingle"></td>
                                                            <td>
                                                                @if ($product->images)
                                                                    @php
                                                                        $images = explode(',', $product->images);
                                                                    @endphp
                                                                    @foreach ($images as $key => $image)
                                                                            <?php if ($key == "0"){ ?>
                                                                            <!--<a href="{{ URL::to('/') }}/assets/images/product/{{ $image }}" class="without-caption image-link">-->
                                                                        <img
                                                                            src="{{ URL::to('/') }}/assets/images/product/{{ $image }}"
                                                                            class="zoom" width="30px">
                                                                        <!--</a>-->
                                                                        <?php }
                                                                        else {
                                                                            ?>

                                                                        <?php } ?>
                                                                    @endforeach
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
                                                            <td>
                                                                <a href="{{ route('admin.layout_edit', ['id' => $product->id]) }}">
                                                                    <img src="{{ asset('img/edit.png') }}" width="20px"
                                                                         height="20px" alt="">
                                                                </a>
                                                                &nbsp;&nbsp;
                                                                {{--<a href="{{ URL::to('/') }}/deleteproduct/{{ $product->id }}"
                                                                   onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                                        src="{{ asset('img/delete.png') }}" width="25px"
                                                                        height="25px"></a>--}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="10" class="text-center">Product not found!</td>
                                                    </tr>
                                                @endif
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
                                                            @if ($product->images)
                                                                @php
                                                                    $images = explode(',', $product->images);
                                                                @endphp
                                                                @foreach ($images as $keys => $image)
                                                                        <?php if ($keys == "0"){ ?>
                                                                        <!--<a href="{{ URL::to('/') }}/assets/images/product/{{ $image }}" class="without-caption image-link">-->
                                                                    <img
                                                                        src="{{ URL::to('/') }}/assets/images/product/{{ $image }}"
                                                                        class="zoom" width="30px">
                                                                    <!--</a>-->
                                                                    <?php }
                                                                    else {
                                                                        ?>

                                                                    <?php } ?>
                                                                @endforeach
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
                                                        <td width="60%">
                                                            <a href="{{ URL::to('/') }}/layout-products/edit/{{ $product->id }}"><img
                                                                    src="{{ asset('img/edit.png') }}" width="20px"
                                                                    height="20px"></a>
                                                            &nbsp;&nbsp;
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

    {{-- this part hidden --}}
    <div style="display: none;" class="table-responsive" id="desktoptable">
        <table class="table table-striped" width="100%" id="excelDownload">
            <thead>
            <tr style="background: black; color: white;">
                <th>SL.</th>

                <th>
                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                        নাম
                    @else
                        Name
                    @endif
                </th>
                <th>
                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                        দাম
                    @else
                        Price
                    @endif
                </th>
                <th>
                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                        পরিমাণ
                    @else
                        Quantity
                    @endif
                </th>

                <th>
                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                        তারিখ
                    @else
                        Date
                    @endif
                </th>

            </tr>
            </thead>

            {{-- product info download execl data--}}
            <tbody>
            @foreach ($allProduct as $key => $product)
                <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                    <td>{{ ++$key }}</td>
                    <td>{{ Str::of($product->name)->limit(90) }}</td>
                    <td>{{$currency->symbol}} {{ $product->regular_price }}</td>
                    <td>{{ $product->quantity }}</td>

                    <td>{{ date('d-m-Y', strtotime($product->created_at)) }}</td>

                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
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
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {
                    value: value,
                    id: id
                }, function (data) {
                    console.log(data);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#checkedAll").change(function () {
                debugger;
                if (this.checked) {
                    $(".checkSingle").each(function () {
                        debugger;
                        this.checked = true;
                        var valuesArray = '';
                        valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val('');
                        $("#selectids").val(valuesArray);
                        $("#BarCodeSelectIds").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    debugger;
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val('');
                    $("#selectids").val(valuesArray);
                    $("#BarCodeSelectIds").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });

            $(".checkSingle").click(function () {
                debugger;
                var valuesArray = '';
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function () {
                        debugger;
                        if (!this.checked)
                            isAllChecked = 1;
                        valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val('');
                        $("#selectids").val(valuesArray);
                        $("#BarCodeSelectIds").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });

                    if (isAllChecked == 0) {
                        $("#checkedAll").prop("checked", true);
                    }
                } else {
                    debugger;
                    $(this).prop("checked", false);
                    $(this).prop("checked", false);
                    $("#checkedAll").prop("checked", false);
                    this.checked = false;
                    this.value = '';
                    $(this).prop("checked", false);
                    $(this).prop("name", '');
                    var valuesArray = '';
                    valuesArray = $('input[name="selectedid"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#BarCodeSelectIds").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                // debugger;
                var value = $(this).val();
                var store_id = {{ $store_id }};
                debugger;
                $url = "/products/searching/";
                $.get($url, {
                    search: value,
                    store_id: store_id
                }, function (data) {
                    $('#searchingProdutsShow').html('');
                    $('#searchingProdutsShow').html(data);
                    // window.location.reload();
                    // $('#testimonials').load(location.href + ' .testi');
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function htmlTableToExcel(type) {
            var data = document.getElementById('excelDownload');
            var excelFile = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(excelFile, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(excelFile, 'ExportedFile:{{ auth()->user()->name }}.' + type);
        }
    </script>
    <script>
        $("#FileInput").on('change', function (e) {
            var labelVal = $(".title").text();
            var oldfileName = $(this).val();
            fileName = e.target.value.split('\\').pop();

            if (oldfileName == fileName) {
                return false;
            }
            var extension = fileName.split('.').pop();


            if (fileName) {
                if (fileName.length > 10) {
                    $(".filelabel .title").text(fileName.slice(0, 4) + '...' + extension);
                } else {
                    $(".filelabel .title").text(fileName);
                }
            } else {
                $(".filelabel .title").text(labelVal);
            }

            if ($.inArray(extension, ['jpg', 'jpeg', 'png']) >= 0) {
                $(".filelabel i").removeClass().addClass('fa fa-file-image-o');
                $(".filelabel i, .filelabel .title").css({'color': '#208440'});
                $(".filelabel").css({'border': ' 2px solid #208440'});
            } else if (extension == 'pdf') {
                $(".filelabel i").removeClass().addClass('fa fa-file-pdf-o');
                $(".filelabel i, .filelabel .title").css({'color': 'red'});
                $(".filelabel").css({'border': ' 2px solid red'});

            } else if (extension == 'doc' || extension == 'docx') {
                $(".filelabel i").removeClass().addClass('fa fa-file-word-o');
                $(".filelabel i, .filelabel .title").css({'color': '#2388df'});
                $(".filelabel").css({'border': ' 2px solid #2388df'});
            } else {
                $(".filelabel i").removeClass().addClass('fa fa-file-o');
                $(".filelabel i, .filelabel .title").css({'color': 'black'});
                $(".filelabel").css({'border': ' 2px solid black'});
               // $("#formSubmitFile").submit(); // Submit the form
            }
        });
    </script>
@endpush
