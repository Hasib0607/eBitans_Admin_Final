@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{URL::to('/')}}/inventory">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Inventory
                                </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{route('admin.stockalert')}}">
                                    <img src="{{URL::to('/')}}/img/categories1.png"> <br>Stock Alert
                                </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{route('admin.stockout')}}">
                                    <img src="{{URL::to('/')}}/img/subcategory.png"> <br>Stock Out
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route('admin.topselling')}}">
                                    <img src="{{URL::to('/')}}/img/subcategory.png"> <br>Top Selling Products
                                </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{route('admin.lowestselling')}}">
                                    <img src="{{URL::to('/')}}/img/subcategory.png"> <br>Lowest Selling Products
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-8">
                    <h4>Top Selling Products</h4>
                </div>
                <div class="col-4" style="text-align:end">
                    <a href="#" class="btn btn-primary" onclick="download_table_as_csv('taskfilterresult');">Excel</a>
                </div>
            </div>
            <div class="row productlist">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
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
                                <div class="alert alert-success">{{Session::get('success_message')}}</div>
                            @endif
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" width="100%" id="taskfilterresult">
                                    <thead>
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th width="5%">Image</th>
                                        <th width="30%">Name</th>
                                        <th width="20%">Price</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Total Sell</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($products->count() > 0)
                                        @foreach($products as $index => $product)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
                                                <td>
                                                    @php
                                                        $images = array_filter(explode(',', $product->images));
                                                        $gallery_image = array_filter(explode(',', $product->gallery_image));
                                                        $mergedImages = array_unique(array_merge($gallery_image, $images));
                                                        $images = array_map(fn($img) => getPath($img, 'assets/images/product'), $mergedImages);
                                                    @endphp
                                                    @if (isset($images[0]))
                                                        <img
                                                            src="{{ $images[0] }}"
                                                            width="30px" alt="">
                                                    @endif
                                                </td>
                                                <td>{{ Str::of($product->name)->limit(20) }}</td>
                                                <td>{{ $product->converted_price }}</td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>{{ $product->popularity }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No products found.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>

                                <!-- Pagination Links -->
                                <div class="pagination-links">
                                    {{ $products->links() }}
                                </div>
                            </div>

                            <div class="table-responsive" id="mobiletable">
                                <table class="table" width="100%">
                                    @if($products->count() > 0)
                                        @foreach($products as $index => $product)
                                            <tr class="mobilefirstrow">
                                                <th width="10%">
                                                    {{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}
                                                </th>
                                                <th width="20%" style="color:#f1593a">
                                                    Name:
                                                </th>
                                                <td width="60%" style="color:black">
                                                    {{Str::of($product->name)->limit(20)}}
                                                </td>
                                                <td width="10%">
                                                    <a href="#" class="toggler" data-prod-cat="{{$index}}">
                                                        <i class="fa fa-arrow-down" id="show{{$index}}"
                                                           style="color:#f1593a"></i>
                                                        <i class="fa fa-arrow-up" id="up{{$index}}"
                                                           style="display:none"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="cat{{$index}}" style="display:none">
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
                                                            class="zoom" width="30px" alt="">
                                                    @endif
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{$index}}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Price
                                                </th>
                                                <td width="60%">
                                                    {{$product->converted_price}}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{$index}}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Quantity
                                                </th>
                                                <td width="60%">
                                                    {{$product->quantity}}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{$index}}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Total Sell
                                                </th>
                                                <td width="60%">
                                                    {{ $product->popularity }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No products found.</td>
                                        </tr>
                                    @endif
                                </table>

                                <!-- Pagination Links -->
                                <div class="pagination-links">
                                    {{ $products->links() }}
                                </div>
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
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
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

        const table = document.getElementById('taskfilterresult');
        const exportBtn = document.getElementById('export');

        exportBtn.addEventListener('click', function () {
            // Export to csv
            const csv = toCsv(table);

            // Download it
            download(csv, 'download.csv');
        });
        const toCsv = function (table) {
            // Query all rows
            const rows = table.querySelectorAll('tr');

            return [].slice
                .call(rows)
                .map(function (row) {
                    // Query all cells
                    const cells = row.querySelectorAll('th,td');
                    return [].slice
                        .call(cells)
                        .map(function (cell) {
                            return cell.textContent;
                        })
                        .join(',');
                })
                .join('\n');
        };
        const download = function (text, fileName) {
            const link = document.createElement('a');
            link.setAttribute('href', `data:text/csv;charset=utf-8,${encodeURIComponent(text)}`);
            link.setAttribute('download', fileName);

            link.style.display = 'none';
            document.body.appendChild(link);

            link.click();

            document.body.removeChild(link);
        };

        // Quick and simple export target #table_id into a csv
        function download_table_as_csv(table_id, separator = ',') {
            // Select rows from table_id
            var rows = document.querySelectorAll('table#' + table_id + ' tr');
            // Construct csv
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll('td, th');
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
