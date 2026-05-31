@extends('admin.layouts.main')
@section('title', 'Visitors -')
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

        .swal2-radio {
            display: block;
        }

        .swal2-radio input {
            margin-right: 5px;
        }
        
        
        /* Style to hide bullet points for list items */
        ul {
            padding-left: 0;

        }

        .swal-ul {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        li {
            list-style: none;
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">
        @include('superadmin.store_manage.category.top_bar_category')
        <div class="container-fluid mt-4" id="toplist">
            @if (Auth::user()->type == 'superadmin')
                <div class="row mt-5 productlist">
                    <div class="col-12">
                        <div class="alert alert-info pt-2 pb-3"
                            style="background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);" role="alert">
                            <span style="color:#fff">
                                All PSE Visitors
                            </span>
                            <ul style="display: unset;">
                                <li style="padding:0px;border:0px;">
                                    <a href="#" style="display:block;border-radius:0px !important"
                                        class="btn btn-info btn-sm">
                                        Monthly
                                    </a>
                                </li>
                                <li style="padding:0px;border:0px;">
                                    <a href="#" style="display:block;border-radius:0px !important"
                                        class="btn btn-secondary btn-sm">
                                        Weekly
                                    </a>
                                </li>
                                <li style="padding:0px;border:0px;">
                                    <a href="#" class="btn btn-primary btn-sm"
                                        style="display:block;border-radius:0px !important">
                                        Daily
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-3" style="padding-right:1px;">
                                    </div>
                                    <div class="col-md-1" style="padding-left:0px;">
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-6" style="float:right;">
                                        <div class="input-group">
                                            {{-- <input type="text" class="form-control"
                                                aria-label="Dollar amount (with dot and two decimal places)"
                                                id="taskfilter">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                                <i class="fa fa-search"></i>
                                            </span> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (Session::has('success_message'))
                                    <div class="alert alert-success" style="color:#fff">
                                        {{ Session::get('success_message') }}
                                    </div>
                                @endif
                                <div class="table-responsive" id="desktoptable">
                                    <table class="table table-striped" width="100%" id="taskfilterresult">
                                        <thead>
                                            <tr>
                                                <th width="4%">SL NO</th>
                                                <th width="5%">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ছবি
                                                    @else
                                                        Image
                                                    @endif
                                                </th>
                                                <th width="55%">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        নাম
                                                    @else
                                                        Product Name
                                                    @endif
                                                </th>
                                                <th width="5%">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        দোকানের নাম
                                                    @else
                                                        Store Name
                                                    @endif
                                                </th>
                                                <th width="5%">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        মোট ভিজিটর
                                                    @else
                                                        Total Visitor
                                                    @endif
                                                </th>
                                                <th width="5%">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        আইপি
                                                    @else
                                                        IP
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!is_null($visitors))
                                                @php
                                                    $currentPage = request()->input('page', 1); // Get the current page number
                                                    $perPage = $visitors->perPage(); // Get the number of items displayed per page
                                                    $startingId = ($currentPage - 1) * $perPage + 1; // Calculate the starting ID for the current page
                                                    $countVisitor = \App\Models\StaticVisitor::first();
                                                    $total = $countVisitor ? $countVisitor->visitors : 0;
                                                @endphp
                                                @foreach ($visitors as $visitor)
                                                    <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                        <td>{{ $startingId++ }}</td>
                                                        <td>
                                                            @if ($visitor->productImage)
                                                                @php
                                                                    $images = is_array($visitor->productImage)
                                                                        ? $visitor->productImage
                                                                        : explode(',', $visitor->productImage);
                                                                @endphp
                                                                @foreach ($images as $key => $image)
                                                                    @if ($key == '0')
                                                                        <img src="{{ URL::to('/') }}/assets/images/product/{{ $image }}"
                                                                            class="zoom" width="30px">
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <p style="color:#000">
                                                                {{ Str::of($visitor->name)->limit(40) }}
                                                            </p>
                                                        </td>
                                                        <td style="text-align: center;">{{ $visitor->store_name }}</td>
                                                        <td style="text-align: center;">
                                                            @if ($total != 0)
                                                                {{ $visitor->totalVisitor * $total }}
                                                            @else
                                                                {{ $visitor->totalVisitor }}
                                                            @endif
                                                        </td>
                                                        {{-- <td>{{ $visitor->ip }}</td> --}}
                                                        <td>
                                                            <a
                                                                onclick="showIpAddress({{ json_encode($allVisitors->where('product_id', $visitor->product_id)->pluck('ip')) }})">
                                                                <img src="{{ asset('img/eye.png') }}" width="20px"
                                                                    height="20px">
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                    <p>Visitor Not Found</p>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    @if (!is_null($visitors))
                                        {{ $visitors->links() }}
                                    @endif
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
        /**
         * Display IP addresses in a formatted list using SweetAlert.
         * @param {Array} ips - An array containing IP addresses to be displayed.
         */
        function showIpAddress(ips) {
            // Extract IP addresses from the input array
            const listItems = ips;
            // Construct HTML for the list
            let listHTML = '<ul class="swal-ul">';
            listItems.forEach(item => {
                listHTML += `<li>${item}</li>`;
            });
            listHTML += '</ul>';

            // Display IP addresses using SweetAlert
            Swal.fire({
                title: "All Ips!",
                html: listHTML,
                icon: "success",
                customClass: {
                    scrollbar: 'scrollbar-class'
                },
                confirmButtonText: 'Back',
            });
        }
    </script>
@endpush
