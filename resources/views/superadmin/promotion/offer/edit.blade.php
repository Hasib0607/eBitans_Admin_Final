@extends('admin.layouts.main')
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
                    $category = 1;
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
                    $offerss = 1;
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row" style="width:100%">
                        <div class="col-md-9">
                            <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
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
                <div class="modal-body">
                    <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
                        <table class="table table-stripped" id="taskfilterresult">
                            <thead>
                                <tr>
                                    <th><label></label></th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $products = DB::table('products')
                                    ->where('store_id', $store_id)
                                    ->get();
                                ?>
                                @if (count($products) > 0)
                                    @foreach ($products as $product)
                                        <tr>
                                            <td></td>
                                            <td>{{ Str::of($product->name)->limit(20) }}</td>
                                            <td>{{ $product->SKU }}</td>
                                            <td>{{ $product->regular_price }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.promotion.coupon') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/voucher.png"> <br> <span
                                        class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn') কুপন
                                        @else
                                            Coupon @endif
                                    </span>
                                </a>
                            </li>
                            @if ((isset($campaign) && $campaign == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ route('admin.promotion.campaign') }}">
                                        <img src="{{ URL::to('/') }}/img/icons/bullhorn.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') প্রচারণা
                                            @else
                                                Campaign @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if ((isset($offer) && $offer == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{ route('admin.promotion.offer') }}">
                                        <img src="{{ URL::to('/') }}/img/icons/offer.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') অফার
                                            @else
                                                Offer @endif
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

            <div class="row mt-1 productlist">
                <div class=" ">

                    <div class="">
                        @if (Session::has('success_message'))
                            <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                        @endif
                        <form class="row"
                            @if (isset($offer->id)) action="{{ route('admin.offer.update', $offer->id) }}" @else action="{{ route('admin.offer.store') }}" @endif
                            method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') অফার
                                            @else
                                                Offer Edit @endif
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-1">
                                            <label for="staticEmail" class="col-form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') নাম
                                                @else
                                                    Name @endif
                                            </label>
                                            <div class="">
                                                <input type="text" class="form-control" id="staticEmail"
                                                    value="{{ $offer->name ?? old('name') }}" name="name"
                                                    placeholder="Offer Name">
                                                @error('name')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-1">
                                            <label for="staticEmail" class="col-form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') শুরুর
                                                    তারিখ
                                                @else
                                                    Start Date @endif
                                            </label>
                                            <div class="">
                                                <input type="date" class="form-control" id="staticEmail"
                                                    value="{{ $offer->start_date ?? old('start_date') }}"
                                                    name="start_date">
                                                @error('start_date')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-1">
                                            <label for="staticEmail" class="col-form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') শেষ
                                                    তারিখ
                                                @else
                                                    End Date @endif
                                            </label>
                                            <div class="">
                                                <input type="date" class="form-control" id="staticEmail"
                                                    value="{{ $offer->end_date ?? old('end_date') }}" name="end_date">
                                                @error('end_date')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="staticEmail" class="col-form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    স্ট্যাটাস
                                                @else
                                                    Status @endif
                                            </label>
                                            <div class="">
                                                <div class="form-check form-switch is-filled" style="text-align:center;">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckChecked" name="status" style="margin:0 auto;"
                                                        @if (isset($offer)) @if ($offer->status == 'active')  checked="" @endif
                                                        @endif>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                                </div>
                                                @error('status')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-info">
                                            @if (isset($offer))
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') আপডেট
                                                @else
                                                    Update @endif
                                            @else
                                                @if (Session::has('lang') && Session::get('lang') == 'bn') সংরক্ষণ
                                                @else
                                                    Save @endif
                                            @endif
                                        </button>

                                    </div>
                                </div>
                            </div>


                            <div class="col-md-8">
                                <div class="row selectrowproduct card">
                                    <div class="card-header">
                                        <h4>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn') পণ্য
                                                সম্পাদনা
                                                করুন
                                            @else
                                                Remove Product @endif
                                        </h4>
                                    </div>
                                    <div class="card-body ">
                                        <div class="table-responsive"
                                            style="max-height:360px; overflow-y:auto;border-bottom: 1px solid #afacac1f;">

                                            <table class="table table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th style="padding:10px 0px!important;"><input type="checkbox"
                                                                name="ids" id="checkedAll"></th>
                                                        <th>
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn') নাম
                                                            @else
                                                                Name @endif
                                                        </th>
                                                        <th>
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn') এসকেইউ
                                                            @else
                                                                SKU @endif
                                                        </th>
                                                        <th>
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn') দাম
                                                            @else
                                                                Price @endif
                                                        </th>
                                                        <th>
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn') ডিলিট
                                                            @else
                                                                Delete @endif
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $products = DB::table('products')
                                                        ->where('discount_type', '!=', 'no_discount')
                                                        ->where('store_id', $store_id)
                                                        ->get();
                                                    $pp = 1;
                                                    ?>
                                                    @if (isset($products) && count($products) > 0)
                                                        @foreach ($products as $product)
                                                            <tr>
                                                                <td style="padding: 10px 0px!important;"><input
                                                                        type="checkbox" name="selectedid" id="id"
                                                                        value="{{ $product->id }}" class="checkSingle">
                                                                </td>
                                                                <td>{{ Str::of($product->name)->limit(20) }}
                                                                </td>
                                                                <td>{{ $product->SKU }}</td>
                                                                <td>{{ $product->regular_price }}</td>
                                                                <td><a
                                                                        href="{{ URL::to('/') }}/removefromofr/{{ $product->id }}">
                                                                        <img src="https://admin.ebitans.com/img/delete.png"
                                                                            width="25px" height="25px">
                                                                    </a></td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                        <button type="button" class="btn btn-primary mb-0 mt-2"
                                            id="offersubmit">Delete</button>
                                    </div>
                                </div>
                            </div>


                            <!--<label for="position" class="col-md-2 col-form-label"></label>-->
                            <div class="col-md-12">
                                {{-- <button type="submit" class="btn btn-info">
                                    @if (isset($offer))
                                        @if (Session::has('lang') && Session::get('lang') == 'bn') আপডেট
                                        @else
                                            Update @endif
                                    @else
                                        @if (Session::has('lang') && Session::get('lang') == 'bn') সংরক্ষণ
                                        @else
                                            Save @endif
                                    @endif
                                </button> --}}
                                {{-- <button style="float:right;margin-right: 50px;" type="button" class="btn btn-primary"
                                    id="offersubmit">Delete</button> --}}
                            </div>
                        </form>
                    </div>
                    <form action="{{ route('admin.offerprodelete') }}" method="post" id="offerdeel">
                        @csrf
                        <input type="hidden" name="text2" id="selectids">
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $('#offersubmit').on('click', function() {
            $('#offerdeel').submit();
        })
        $(document).ready(function() {
            $("#checkedAll").change(function() {
                if (this.checked) {
                    $(".checkSingle").each(function() {
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
                var value = $(this).val().toLowerCase();
                $("#taskfilterresult tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endpush
