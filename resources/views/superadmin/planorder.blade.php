@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="{{URL::to('/')}}/planorder">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Plan Order
                        </a>
                    </li>
                
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4>All Orders</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>
                <li style="padding:0px;border:0px;"><a href="javascript:void(0)" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                
                <div class="row">
                    <div class="col-md-2" style="padding-right:1px;">
                        <form method="post" action="{{route('superadmin.deleteallplanorder')}}">
                        @csrf
                        <input type="hidden" name="text2" id="selectids">
                        <select class='form-control' name="action" id="action">
                            <option value="select">Select Option</option>
                            <option value="delete">Delete</option>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding-left:0px;">
                        <button type="submit" class="btn btn-primary">Apply</button>
                        </form>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-md-1 text-end mt-1">
                        <label for="formdate">From Date</label>
                    </div>
                    <div class="col-md-2">
                        <form action="{{route('superadmin.plandatefilter')}}" method="get">
                    @csrf
                        <input type="date" name="formdate"  id="formdate" value="{{$from ?? ""}}" class="form-control">
                    </div>
                    <div class="col-md-1 text-end mt-1">
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
                <div class="table-responsive">
                    <table class="table" id="taskfilterresult" width="100%">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                <th width="5%">Plan Id</th>
                                <th width="20%">Transaction ID</th>
                                <th width="10%">Customer Name</th>
                                <th width="10%">Customer Phone</th>
                                <th width="10%">Purchase Date</th>
                                <th width="10%">Expiry Date</th>
                                <th width="10%">Amount</th>
                                <th width="11%">Month</th>
                                <th Width="5%">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="selectedid" value="{{$order->id}}" id="id" class="checkSingle"></td>
                                <td>{{$order->plan_id}}</td>
                                <td>{{$order->transaction_id}}</td>
                                <?php $customer=DB::table('customers')->where('id',$order->customer_id)->first(); ?>
                                <td>{{$customer->name ?? ""}}</td>
                                <td>{{$customer->phone ?? ""}}</td>
                                <td>{{date('d-m-Y', strtotime($order->active_date))}}</td>
                                <td>{{date('d-m-Y', strtotime($order->expiry_date))}}</td>
                                <td>{{$order->total_amount}}</td>
                                <td>
                                    {{$order->total_month}}
                                </td>
                                <td><a href="{{route('superadmin.planinvoice',$order->id)}}"><img src="{{asset('img/paper.png')}}" width="25px" height="25px"></a></td>
                            </tr>
                            @endforeach
                        </tbody>
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
$(document).ready(function() {
    $("#checkedAll").change(function() {
        debugger;
        if (this.checked) {
            $(".checkSingle").each(function() {
                debugger;
                this.checked=true;
                var valuesArray = $('input[name="selectedid"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
            });
        } else {
            $(".checkSingle").each(function() {
                this.checked=false;
            });
            var valuesArray ='';
            $("#selectids").val(valuesArray);
            $("#selectdelids").val(valuesArray);
        }
    });
    $(".checkSingle").click(function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".checkSingle").each(function() {
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
        }
        else {
            $("#checkedAll").prop("checked", false);
            var valuesArray = $('input[name="selectedid"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
        }
    });
});
    $(document).ready(function(){
      $("#taskfilter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#taskfilterresult tbody tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
</script>
@endpush