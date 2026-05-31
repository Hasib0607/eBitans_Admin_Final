@extends('admin.layouts.main')
@section('title', 'Weekly Visitors')
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
        @include('admin.analytics.partials.header')
        @include('admin.product_khujo.statistics')
        <div class="container-fluid mt-4" id="toplist">
            @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                <div class="row mt-5 productlist">
                    @include('admin.product_khujo.visitor_nav')
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-3" style="padding-right:1px;">
                                    </div>
                                    <div class="col-md-2" style="padding-left:0px;">
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-1" style="float:right;">
                                        <div class="input-group">
                                            <a href="#" class="btn btn-primary form-control"
                                               onclick="download_table_as_csv('taskfilterresult');">Excel</a>
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
                                                                    <img
                                                                        src="{{ URL::to('/') }}/assets/images/product/{{ $image }}"
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
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <p>Product Visitor Not Found</p>
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
        // Quick and simple export target #table_id into a csv
        function download_table_as_csv(table_id, separator = ',') {
            // Select rows from table_id
            var rows = document.querySelectorAll('table#' + table_id + ' tr');
            // Construct csv
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var row = [],
                    cols = rows[i].querySelectorAll('td, th');
                for (var j = 0; j < cols.length; j++) {
                    // Clean innertext to remove multiple spaces and jumpline (break csv)
                    var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                    // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                    data = data.replace(/"/g, '""');
                    // Push escaped string
                    row.push('"' + data + '"');
                }
                csv.push(row.join(separator));
            }
            var csv_string = csv.join('\n');
            // Download it
            var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
            var link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endpush
