@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative border-radius-lg">
        @include("admin.report.top-nav")

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-6">
                    <h4>Selling Report</h4>
                </div>
                <div class="col-6" style="text-align:end">
                    <!--<ul>-->
                    <!--<li class="active"><a href="{{ route('admin.addproducts') }}">Create New</a></li>-->
                    <!--<li style="border:0px;">-->
                    <!--<button id="export">Export</button>-->
                    <a href="#" class="btn btn-primary" onclick="download_table_as_csv('taskfilterresult');">Excel</a>
                    <!--</li>-->
                    <!--<li><a data-href="/tasks" onclick="exportTasks(event.target);" style="cursor:pointer">Export</a></li>-->
                    <!--</ul>-->
                </div>
            </div>
            <div class="row mt-5 productlist">

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2"></div>

                                <form action="{{ route('admin.completeorder') }}" method="get" class="row">
                                    <div class="col-md-1 text-end mt-1">
                                        <label for="from_date">From Date</label>
                                    </div>

                                    <div class="col-md-2">
                                        <input type="date" name="from_date" id="from_date"
                                               value="{{ $from_date ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-1 text-end mt-1">
                                        <label for="to_date">To Date</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="to_date" id="to_date" value="{{ $to_date ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                                                   class="form-control">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-2" style="padding-left:0px;">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" width="100%" id="taskfilterresult">
                                    <thead>
                                    <tr>
                                        <th width="3%">SL</th>
                                        <th width="15%">Order Date</th>
                                        <th width="10%">Reference No</th>
                                        <th width="20%">Customer Phone</th>
                                        <th width="5%">Subtotal</th>
                                        <th width="5%">Discount</th>
                                        <th width="5%">Shipping</th>
                                        <th width="5%">Tax</th>
                                        <th width="5%">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (isset($orders) && count($orders) > 0)
                                        @foreach ($orders as $key => $order)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                                <td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                                                <td>{{ $order->reference_no }}</td>
                                                <td>{{ $order->phone }}</td>
                                                <td>{{ $order->subtotal }}</td>
                                                <td>{{ $order->discount }}</td>
                                                <td>{{ $order->shipping }}</td>
                                                <td>{{ $order->tax }}</td>
                                                <td>{{ $order->total }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="8" style="text-align: right">Page Total Revenue:</td>
                                        <td>{{ $pageTotalAmount ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" style="text-align: right">Total Revenue:</td>
                                        <td>{{ $revenue ?? 0 }}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div style="text-align: center;">
                                    {!! $orders->appends(['from_date' => request('from_date'), 'to_date' => request('to_date'), 'search' => request('search')])->links() !!}
                                </div>
                            </div>
                            <div class="table-responsive" id="mobiletable">
                                <table class="table" width="100%">
                                    @if (isset($orders) && count($orders) > 0)
                                        @foreach ($orders as $key => $order)
                                            <tr class="mobilefirstrow">
                                                <th width="10%">
                                                    <input type="checkbox" name="selectedid" value="{{ $order->id }}"
                                                           id="id" class="checkSingle">
                                                </th>
                                                <th width="20%" style="color:#f1593a">
                                                    Reference No
                                                </th>
                                                <td width="60%" style="color:black">
                                                    {{ $order->reference_no }}
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
                                                    Order Date
                                                </th>
                                                <td width="60%">
                                                    {{ date('d-m-Y', strtotime($order->created_at)) }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Customer Phone
                                                </th>
                                                <td width="60%">
                                                    {{ $order->phone }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Subtotal
                                                </th>
                                                <td width="60%">
                                                    {{ $order->subtotal }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Discount
                                                </th>
                                                <td width="60%">
                                                    {{ $order->discount }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Shipping
                                                </th>
                                                <td width="60%">
                                                    {{ $order->shipping }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Tax
                                                </th>
                                                <td width="60%">
                                                    {{ $order->tax }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                            <tr class="cat{{ $key }}" style="display:none">
                                                <th width="10%"></th>
                                                <th width="20%">
                                                    Total
                                                </th>
                                                <td width="60%">
                                                    {{ $order->total }}
                                                </td>
                                                <td width="10%"></td>
                                            </tr>
                                        @endforeach
                                    @endif
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
