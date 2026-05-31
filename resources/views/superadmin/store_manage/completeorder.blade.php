@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item ">
                        <a href="{{URL::to('/')}}/report">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Report
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{route('admin.completeorder')}}">
                            <img src="{{URL::to('/')}}/img/subcategory.png" > <br>Selling Report
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-6">
            <h4>Selling Report</h4>
        </div>
        <div class="col-6" style="text-align:end">
            <!--<ul>-->
                <!--<li class="active"><a href="{{route('admin.addproducts')}}">Create New</a></li>-->
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
                    <div class="col-md-1" style="width:3% !important;margin-left:10px;">
                        
                    </div>
                    <div class="col-md-2">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-1 mt-1">
                        <label for="formdate">From Date</label>
                    </div>
                    <div class="col-md-2">
                        <form action="{{route('admin.completeorderdatefilter')}}" method="get">
                    @csrf
                        <input type="date" name="formdate"  id="formdate" value="{{$from ?? ""}}" class="form-control">
                    </div>
                    <div class="col-md-1 mt-1">
                        <label for="todate">To Date</label>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="enddate" id="todate" value="{{$to ?? ""}}" class="form-control">
                    </div>
                    <div class="col-md-1 filterbtns">
                        <button type="submit" class="btn btn-info filterbtn" style="background-color: #7b809a ">Filter</button>
                        </form>
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
                                <th width="3%"><input type="checkbox" name="ids" id="checkedAll"></th>
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
                            @if(isset($orders) && count($orders)>0)
                            @foreach($orders as $key=>$order)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="selectedid" value="{{$order->id}}" class="checkSingle"></td>
                                
                                <td>{{date('d-m-Y', strtotime($order->created_at))}}</td>
                                <td>{{$order->reference_no}}</td>
                                <td>
                                    {{$order->phone}}
                                </td>
                                <td>{{$order->subtotal}}</td>
                                <td>{{$order->discount}}</td>
                                <td>{{$order->shipping}}</td>
                                <td>{{$order->tax}}</td>
                                <td>{{$order->total}}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Revenue: </th>
                                <th>{{$revenue}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="table-responsive" id="mobiletable">
                    <table class="table" width="100%">
                        @if(isset($orders) && count($orders)>0)
                        @foreach($orders as $key=>$order)
                        <tr class="mobilefirstrow">
                            <th width="10%">
                                <input type="checkbox" name="selectedid" value="{{$order->id}}" id="id" class="checkSingle">
                            </th>
                            <th width="20%" style="color:#f1593a">
                                Reference No
                            </th>
                            <td width="60%" style="color:black">
                                {{$order->reference_no}}
                            </td>
                            <td width="10%">
                                <a href="#" class="toggler" data-prod-cat="{{$key}}">
                                <i class="fa fa-arrow-down" id="show{{$key}}" style="color:#f1593a"></i>
                                <i class="fa fa-arrow-up" id="up{{$key}}" style="display:none"></i>
                                </a>
                            </td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Order Date
                            </th>
                            <td width="60%">
                                 {{date('d-m-Y', strtotime($order->created_at))}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Customer Phone
                            </th>
                            <td width="60%">
                                {{$order->phone}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Subtotal
                            </th>
                            <td width="60%">
                                {{$order->subtotal}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Discount
                            </th>
                            <td width="60%">
                                {{$order->discount}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Shipping
                            </th>
                            <td width="60%">
                                {{$order->shipping}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Tax
                            </th>
                            <td width="60%">
                               {{$order->tax}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Total
                            </th>
                            <td width="60%">
                                {{$order->total}}
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
    $(document).ready(function(){
      $("#taskfilter").on("keyup", function() {
          debugger;
        var value = $(this).val().toLowerCase();
        debugger;
        $("#taskfilterresult tbody tr").filter(function() {
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