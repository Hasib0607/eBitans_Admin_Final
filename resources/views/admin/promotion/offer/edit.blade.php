@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">

        {{--top navigation--}}
        @include('admin.promotion.share.promotion-nav')
        <div class="container-fluid mt-4" id="toplist">

            <div class="row mt-1 productlist">
                <div class=" ">

                    <div class="">
                        @if (Session::has('success_message'))
                            <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                        @endif
                        <form class="row"
                              @if (isset($offer->id)) action="{{ route('admin.offer.update', $offer->id) }}"
                              @else action="{{ route('admin.offer.store') }}" @endif
                              method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অফার
                                            @else
                                                Offer Edit
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-1">
                                            <label for="staticEmail" class="col-form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    নাম
                                                @else
                                                    Name
                                                @endif
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
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    শুরুর
                                                    তারিখ
                                                @else
                                                    Start Date
                                                @endif
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
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    শেষ
                                                    তারিখ
                                                @else
                                                    End Date
                                                @endif
                                            </label>
                                            <div class="">
                                                <input type="date" class="form-control" id="staticEmail"
                                                       value="{{ $offer->end_date ?? old('end_date') }}"
                                                       name="end_date">
                                                @error('end_date')
                                                <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3" style="display: flex">
                                            <label for="staticEmail" class="col-form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    স্ট্যাটাস
                                                @else
                                                    Status
                                                @endif
                                            </label>
                                            <div class="">
                                                <div class="form-check form-switch is-filled"
                                                     style="text-align:center;padding-left: 25px; margin-top: 14px; display: flex; align-items: center;">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="flexSwitchCheckChecked" name="status"
                                                           style="margin:0 auto;"
                                                           @if (isset($offer) && $offer->status == 'active')  checked="" @endif
                                                    >
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                                @error('status')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-info">
                                            @if (isset($offer))
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    আপডেট
                                                @else
                                                    Update
                                                @endif
                                            @else
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সংরক্ষণ
                                                @else
                                                    Save
                                                @endif
                                            @endif
                                        </button>

                                    </div>
                                </div>
                            </div>


                            <div class="col-md-8">
                                <div class="row selectrowproduct card">
                                    <div class="card-header">
                                        <h4>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পণ্য
                                                সম্পাদনা
                                                করুন
                                            @else
                                                Remove Product
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="card-body ">
                                        <div class="table-responsive"
                                             style="max-height:360px; overflow-y:auto;border-bottom: 1px solid #afacac1f;">

                                            <table class="table table-stripped">
                                                <thead>
                                                <tr>
                                                    <th style="padding:10px 0px!important;"><input type="checkbox"
                                                                                                   name="ids"
                                                                                                   id="checkedAll"></th>
                                                    <th>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            নাম
                                                        @else
                                                            Name
                                                        @endif
                                                    </th>
                                                    <th>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            এসকেইউ
                                                        @else
                                                            SKU
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
                                                            ডিলিট
                                                        @else
                                                            Delete
                                                        @endif
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
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
                                                            <td>{{ $product->symbol }}{{ $product->regular_price }}</td>
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
                                                id="offersubmit">Delete
                                        </button>
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
        $('#offersubmit').on('click', function () {
            $('#offerdeel').submit();
        })
        $(document).ready(function () {
            $("#checkedAll").change(function () {
                if (this.checked) {
                    $(".checkSingle").each(function () {
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
                var value = $(this).val().toLowerCase();
                $("#taskfilterresult tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endpush
