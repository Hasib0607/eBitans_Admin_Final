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

        .centered-cell {
            text-align: center;
        }

        .barcode {
            margin: auto;
            display: inline-block;
            /* This ensures that the margin: auto; works for block-level elements within an inline container */
        }

        /* Custom styling for radio buttons in SweetAlert2 */
        .swal2-radio input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 15px;
            height: 15px;
            border: 2px solid #f1593a;
            /* Default border color */
            border-radius: 50%;
            outline: none;
            margin-right: 5px;
        }

        /* Custom styling for checked radio buttons in SweetAlert2 */
        .swal2-radio input[type="radio"]:checked {
            background-color: #f1593a;
            /* Change the background color when checked */
            border-color: #f1593a;
            /* Change the border color when checked */
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">
        @include('superadmin.store_manage.category.top_bar_category')
        <div class="container-fluid mt-4" id="toplist">
            @if ((isset($product) && $product == '1') || Auth::user()->type == 'superadmin')
                <div class="row">
                    <div class="col-md-6">
                        <h4>
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                সব বিজ্ঞাপন
                            @else
                                All ADs
                            @endif
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li style="padding:0px;border:0px;">
                                <a href="{{ route('superadmin.pse.create') }}" class="btn btn-primary"
                                    style="display:block;border-radius:0px !important">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        নতুন বিজ্ঞাপন যোগ করুন
                                    @else
                                        Add New AD
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row mt-5 productlist">
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-2" style="padding-right:1px;">
                                        <form id="submitform" method="post"
                                            action="{{ route('admin.changesliderssstatus') }}">
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
                                    <div class="col-md-7"></div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                aria-label="Dollar amount (with dot and two decimal places)"
                                                id="taskfilter">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                    class="fa fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (Session::has('success_message'))
                                    <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                                @endif
                                <div class="table-responsive" id="desktoptable">
                                    <table class="table table-striped" id="taskfilterresult" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="4%"><input type="checkbox" name="ids" id="checkedAll">
                                                </th>
                                                <th width="10%">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ব্যানার ছবি
                                                    @else
                                                        Banner Image
                                                    @endif
                                                </th>
                                                <th width="30%">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        শিরোনাম
                                                    @else
                                                        Title
                                                    @endif
                                                </th>
                                                <th width="15%">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        অবস্থান
                                                    @else
                                                        Position
                                                    @endif
                                                </th>
                                                <th width="10%">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        স্টেটাস
                                                    @else
                                                        Status
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
                                            @foreach ($getAllAds as $ads)
                                                <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                    <td><input type="checkbox" name="selectedid" value=""
                                                            id="id" class="checkSingle"></td>
                                                    <td>
                                                        <img src="{{ URL::to('/') }}/assets/images/ads_pse_image/{{ $ads->banner }}"
                                                            class="zoom" alt="{{ $ads->banner }}" width="60px">
                                                    </td>
                                                    <td>{{ Str::of(ucfirst($ads->name))->limit(40) }}</td>
                                                    <td>
                                                        <input type="hidden" name="idss" id="id"
                                                            value="{{ $ads->id }}" style="text-align: center;">
                                                        <input type="number" class="form-control" name="position"
                                                            value="{{ $ads->position ?? 0 }}" style="text-align: center;">
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch" style="text-align:center;">
                                                            <input class="form-check-input switchstatus" type="checkbox"
                                                                id="flexSwitchCheckChecked" data-id="{{ $ads->id }}"
                                                                style="margin:0 auto;"
                                                                @if ($ads->status == 1) checked @endif>
                                                            <label class="form-check-label"
                                                                for="flexSwitchCheckChecked"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('superadmin.pse.edit', $ads->id) }}"><img
                                                                src="{{ asset('img/edit.png') }}" width="20px"
                                                                height="20px"></a>
                                                        &nbsp;&nbsp;
                                                        <a
                                                            onclick="showConfirmation('{{ $ads->id }}'); return false;">
                                                            <img src="{{ asset('img/delete.png') }}" width="25px"
                                                                height="25px">
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    {!! $getAllAds->links() !!}
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
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        // change the ad index position
        $(document).ready(function() {
            $('input[name=position]').change(function() {
                var value = $(this).val();
                var id = $(this).parent().parent().find("input[name=idss]").val();
                $url = "{{ route('superadmin.pse.ad.position') }}";
                $.get($url, {
                    value: value,
                    id: id
                }, function(data) {
                    if (data) {
                        Swal.fire(
                            'Congratulations Mr. {{ auth()->user()->name }}!',
                            data.status,
                            'success'
                        )
                    }
                });
            });
        });

        // change the ad active inactive status
        $(document).ready(function() {
            $(".switchstatus").on("change", function() {
                $url = "{{ route('superadmin.pse.ad.status') }}";
                var id = $(this).data('id');
                $.get($url, {
                    id: id
                }, function(data) {
                    if (data) {
                        Swal.fire(
                            'Congratulations Mr. {{ auth()->user()->name }}!',
                            data.status,
                            'success'
                        )
                    }
                });
            });
        });

        //delete ad from list
        function showConfirmation(productId) {
            // Ensure the CSRF token is included in the AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var url = "{{ route('superadmin.pse.ad.delete', ['id' => ':productId']) }}";
            url = url.replace(':productId', productId);

            $.get(url, {
                id: productId
            }, function(data) {
                if (data.status === 200) {
                    Swal.fire(
                        'Congratulations Mr. {{ auth()->user()->name }}!',
                        data.message,
                        'success'
                    ).then(function() {
                        // Reload the window after the user clicks "OK" on the SweetAlert
                        window.location.reload();
                    });
                } else {
                    Swal.fire(
                        'Oops...',
                        data.status,
                        'error'
                    );
                }
            });
        }
    </script>
@endpush
