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

        /*new */
        .container {
            width: 80%;
            margin: 0 auto;
        }

        .product-table th, .product-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .toggle-variants {
            background-color: #4CAF50; /* Green */
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .variant-info {
            margin-top: 10px;
        }

        .variant-table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-bottom: 50px;
            margin-top: 15px;
        }

        .variant-table th, .variant-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }

        .variant-table table, .variant-table th, .variant-table td {
            border: 1px solid #ddd; /* Adds a 1px solid black border */
            padding: 8px; /* Adds padding inside cells for readability */
        }

        .variant-table th {
            background-color: #f5f5f5; /* Optional: Adds a background color to header cells */
        }

        table.variant-table tr td {
            border: 1px solid #ddd !important;
        }


    </style>
@endpush
@section('content')
    @php
        $store_id = getUserData()['store_id'] ?? "";
    @endphp
    <main class="main-content position-relative border-radius-lg">
        @include('admin.product.share.inventory-nav')
        <div class="container-fluid mt-4" id="toplist">

            @if(request()->routeIs('admin.stockalert'))
                <div class="row">
                    <div class="col-md-6" style="display: flex;align-items: center;margin-bottom: 10px;">
                        <h4><?php echo $url1; ?></h4>
                        <button class="btn bg-orange-600 btn-danger mx-1px text-95"
                                style="margin-left: 10px"
                                data-bs-toggle="modal"
                                data-bs-target="#courier"
                                data-title="Print">
                            Set Alert Quantity
                        </button>
                    </div>
                </div>

                {{--Set stock alert popup modal--}}
                <div class="modal fade" id="courier" tabindex="-1"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content"
                             style="background-color:transparent;border:0px">

                            <div class="modal-body" style="border:none">
                                <button class="btn btn-danger sm" data-bs-dismiss="modal"
                                        style="float: right; margin: 0px 8px;">X
                                </button>
                                @php
                                    $headerSetting = \App\Models\Headersetting::where("store_id", $store_id)->first();
                                    $stock_out_qty = $headerSetting->stock_out_qty;
                                @endphp
                                <div class="row mt-1">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <form method="post" action="{{ route("admin.stockOutQty") }}">
                                                        @csrf

                                                        <div class="">
                                                            <label>Stock Alert Quantity</label>
                                                            <br>
                                                            <input name="stock_out_qty" type="number"
                                                                   class="form-control"
                                                                   required pattern="[0-9]{11}" min="1"
                                                                   value="{{ $stock_out_qty ?? "" }}">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary mt-3"
                                                                style="margin-top: 5px">Submit
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-6">
                        <h4><?php echo $url1; ?></h4>
                    </div>
                </div>
            @endif

            @php
                $Module_118_status = ModulusStatus($store_id, 118);
            @endphp

            <div class="row mt-5 productlist">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="row">
                                <div class="col-md-1" style="padding-right:1px;">
                                    <form id="submitform" method="post"
                                          action="{{ route('admin.changeproductstatus') }}">
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
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               aria-label="Dollar amount (with dot and two decimal places)"
                                               id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                class="fa fa-search"></i></span>
                                    </div>
                                </div>

                                <div class="col text-end mt-1">
                                    <label for="formdate">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            তারিখ হইতে
                                        @else
                                            From Date
                                        @endif
                                    </label>
                                </div>
                                <div class="col">
                                    <form action="{{ route('admin.productdatefilter') }}" method="get">
                                        @csrf
                                        <input type="hidden" name="searchFor" value="inventory">
                                        <input type="date" name="formdate" id="formdate" value="{{ $from ?? '' }}"
                                               class="form-control">
                                </div>
                                <div class="col text-end mt-1" style="padding-left:0px;padding-right:0px;width:4%">
                                    <label for="todate">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            এখন পর্যন্ত
                                        @else
                                            To Date
                                        @endif
                                    </label>
                                </div>
                                <div class="col">
                                    <input type="date" name="enddate" id="todate" value="{{ $to ?? '' }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-2 filterbtns" style="width: fit-content;">
                                    <button type="submit" class="btn btn-info filterbtn"
                                            style="background-color: #7b809a ">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ফিল্টার
                                        @else
                                            Filter
                                        @endif
                                    </button>
                                    </form>
                                    <a href="#" onclick="reloadPage()" class="btn btn-info filterbtn"
                                       style="background-color: #7b809a ">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>

                                    </a>
                                </div>

                                @if ($Module_118_status)
                                    <div class="col text-end mt-1" style="padding-left:0px;padding-right:0px;width:4%">
                                        <label for="todate">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Expiry Date
                                            @else
                                                Expiry Date
                                            @endif
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <form id="expiryDateForm" action="{{ route('admin.expiry.product.filter') }}"
                                              method="POST">
                                            @csrf
                                            <input type="date" name="expiry_date" id="expiry_date"
                                                   value="{{ $expiry_date ?? '' }}"
                                                   class="form-control" onchange="this.form.submit()">
                                        </form>
                                    </div>
                                @endif

                            </div>
                            {{--                            </form>--}}
                        </div>


                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success"
                                     style="color:#fff">{{ Session::get('success_message') }}
                                </div>
                            @endif
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" width="100%" id="taskfilterresult">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll">
                                        </th>
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
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পরিমাণ
                                            @else
                                                Quantity
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্ট্যাটাস
                                            @else
                                                Status
                                            @endif
                                        </th>
                                        @if ($Module_118_status)
                                            <th width="15%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Expiry Date
                                                @else
                                                    Expiry Date
                                                @endif
                                            </th>
                                        @else
                                            <th width="15%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    তারিখ
                                                @else
                                                    Date
                                                @endif
                                            </th>
                                        @endif
                                        <th width="5%">View</th>
                                        <th width="11%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এডিট
                                            @else
                                                Action
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($products as $product)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td><input type="checkbox" name="selectedid" value="{{ $product->id }}"
                                                       id="id" class="checkSingle"></td>
                                            <td>
                                                @php
                                                    $images = array_filter(explode(',', $product->images));
                                                    $gallery_image = array_filter(explode(',', $product->gallery_image));
                                                    $mergedImages = array_unique(array_merge($gallery_image, $images));
                                                    $images = array_map(fn($img) => getPath($img, 'assets/images/product'), $mergedImages);
                                                @endphp
                                                @if (isset($images[0]))
                                                    <img src="{{ $images[0] }}"
                                                         class="zoom" width="30px" alt="">
                                                @endif
                                            </td>
                                            <td>{{ Str::of($product->name)->limit(20) }}</td>
                                            <td>{{$product->symbol}} {{ $product->regular_price }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>
                                                <div class="form-check form-switch" style="text-align:center;">
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                           id="flexSwitchCheckChecked" data-id="{{ $product->id }}"
                                                           style="margin:0 auto;"
                                                           @if ($product->status == 'active') checked @endif>
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($Module_118_status)
                                                    {{ $product->expiry_date ? date('d-m-Y', strtotime($product->expiry_date)) : "" }}
                                                @else
                                                    {{ date('d-m-Y', strtotime($product->created_at)) }}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.product.view', $product->id) }}"
                                                   class="btn btn-secondary">View</a>
                                            </td>
                                            <td>
                                                <a href="{{ URL::to('/') }}/products/edit/{{ $product->id }}"><img
                                                        src="{{ asset('img/edit.png') }}" width="20px"
                                                        height="20px"></a>
                                                &nbsp;&nbsp;
                                                <!--<a href="{{ URL::to('/') }}/deleteproduct/{{ $product->id }}"  onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{ asset('img/delete.png') }}" width="25px" height="25px"></a>-->

                                                <button class="toggle-variants">+</button>
                                            </td>
                                        </tr>
                                        <tr class="variant-info" style="display: none;">
                                            <td colspan="9">
                                                <table class="variant-table">
                                                    <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Color</th>
                                                        <th>Size</th>
                                                        <th>Unit</th>
                                                        <th>Volume</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($product->variant as $variant)
                                                        <tr>
                                                            <td>
                                                                @if($variant->image)
                                                                    <img
                                                                        src="{{ URL::to('/') }}/assets/images/product/{{ $variant->image }}"
                                                                        class="zoom" width="30px">
                                                                @endif
                                                            </td>
                                                            <td>{{ $variant->color ?? "" }}</td>
                                                            <td>{{ $variant->size ?? "" }}</td>
                                                            <td>{{ $variant->unit ?? "" }}</td>
                                                            <td>{{ $variant->volume ?? "" }}</td>
                                                            <td>{{$product->symbol}} {{ $variant->additional_price }}</td>
                                                            <td>{{ $variant->quantity > 0 ? $variant->quantity : "Out of Stock" }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div>{{ $products->links() }}</div>

                            <div class="table-responsive" id="mobiletable">
                                <table class="table" style="width:100%">
                                    @foreach ($products as $key => $product)
                                        <tr class="mobilefirstrow">
                                            <th width="10%">
                                                <input type="checkbox" name="selectedid" value="{{ $product->id }}"
                                                       id="id" class="checkSingle">
                                            </th>
                                            <th width="20%" style="color:#f1593a">
                                                Name
                                            </th>
                                            <td width="60%" style="color:black">
                                                {{ Str::of($product->name)->limit(20) }}
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
                                                    <img src="{{ $images[0] }}"
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
                                                {{$product->symbol}} {{ $product->regular_price }}
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Quantity
                                            </th>
                                            <td width="60%">
                                                {{ $product->quantity }}
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
                                                           id="flexSwitchCheckChecked" data-id="{{ $product->id }}"
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
                                                <a href="{{ URL::to('/') }}/products/edit/{{ $product->id }}"><img
                                                        src="{{ asset('img/edit.png') }}" width="20px"
                                                        height="20px"></a>
                                                &nbsp;&nbsp;
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
@endsection
@push('scripts')
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
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
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
                    var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                // debugger;
                var value = $(this).val().toLowerCase();
                // debugger;
                $("#taskfilterresult tbody tr").filter(function () {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }

        $(document).ready(function () {
            $(".toggle-variants").click(function () {
                $(this).closest("tr").next(".variant-info").toggle(); // Toggle the next variant info row
                $(this).text($(this).text() === '+' ? '-' : '+'); // Change button text
            });
        });
    </script>

    <script>
        function reloadPage() {
            location.reload();
        }
    </script>
@endpush
