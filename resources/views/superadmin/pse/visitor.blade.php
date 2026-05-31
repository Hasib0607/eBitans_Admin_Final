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
                                    <div class="col-md-2" style="padding-right:1px;">
                                        <div class="input-group">
                                            <input type="number" min="0" step="0.01" class="form-control"
                                                   name="static_visitor"
                                                   value="{{ $staticVisitor->visitors ?? 0 }}"
                                                   style="text-align: center;">
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="padding-left:0px;">
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-6" style="float:right;">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   aria-label="Dollar amount (with dot and two decimal places)"
                                                   id="taskfilter">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                                <i class="fa fa-search"></i>
                                            </span>
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
                                                        <img
                                                            src="{{ URL::to('/') }}/assets/images/setting/{{ $visitor->logo }}"
                                                            class="zoom" width="30px">
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <p style="color:#000">{{ $visitor->name }}</p>
                                                    </td>
                                                    <td style="text-align: center;">{{ $visitor->url }}</td>
                                                    <td style="text-align: center;">
                                                        @if ($total != 0)
                                                            {{ $visitor->totalVisitor * $total }}
                                                        @else
                                                            {{ $visitor->totalVisitor }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="{{ route('superadmin.pse.visitor.details', $visitor->visitor_id) }}">
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
    <script>
        $(document).ready(function () {
            // Triggered when the value of an input with name 'static_visitor' changes
            $('input[name=static_visitor]').change(function () {
                handleVisitorCounter($(this));
            });

            function handleVisitorCounter(element) {
                var value = element.val();

                sendAjaxRequest('{{ route('superadmin.pse.static.visitor') }}', {
                    value: value
                });
            }
        });

        /**
         * Sends an AJAX request and handles the response
         * @param {string} url - The URL for the AJAX request
         * @param {Object} requestData - The data to be sent in the request
         */
        function sendAjaxRequest(url, requestData) {
            $.get(url, requestData, function (data) {
                if (data) {
                    Swal.fire({
                        title: 'Congratulations Mr. ' + '{{ auth()->user()->name }}!',
                        text: data.status,
                        icon: 'success',
                        showConfirmButton: true
                    }).then((result) => {
                        if (result.value) {
                            // Reload the browser window
                            window.location.reload();
                        }
                    });
                }
            });
        }

        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                var value = $(this).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'get',
                    url: "{{ route('superadmin.pse.visitor.search') }}",
                    data: {
                        search: value
                    },
                    success: function (data) {
                        $('#taskfilterresult').html(data);
                    }
                });
            });
        });
    </script>
@endpush
