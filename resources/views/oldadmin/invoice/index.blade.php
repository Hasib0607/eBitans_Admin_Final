@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.order')}}">
                            <img src="{{URL::to('/')}}/img/icons/order.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') অর্ডার @else Order @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.returned')}}">
                            <img src="{{URL::to('/')}}/img/icons/product-return.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ফেরত পণ্য @else Returned Product @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{URL::to('/')}}/invoice">
                            <img src="{{URL::to('/')}}/img/icons/bill.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') চালান @else Invoice @endif</span>
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
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সমস্ত চালান @else All Invoice @endif</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <!--<li style="padding:0px;border:0px;"><a href="#"  class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
                <li style="padding:0px;border:0px;"><a  data-href="/invoiceexport" onclick="exportTasks(event.target);" style="display:block;border-radius:0px !important" class="btn btn-secondary">@if(Session::has('lang') && Session::get('lang')=='bn') এক্সপোর্ট @else Excel @endif</a></li>
            </ul>
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
                    <div class="col-md-5">

                    </div>
                    <div class="col-md-2">
                        <!--<input type="date" name="date" class="form-control">-->
                    </div>
                    <div class="col-md-2">
                        <!--<select class="form-select">-->
                        <!--    <option>Select</option>-->
                        <!--</select>-->
                    </div>
                </div>
            </div>
            <div class="card-body">
            @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive" id="desktoptable">
                    <table class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th width="5%"><input type="checkbox"></th>
                                <th width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') চালান আইডি @else Invoice ID @endif</th>
                                <th width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') অর্ডার আইডি @else Order Id @endif</th>
                                <th width="25%">@if(Session::has('lang') && Session::get('lang')=='bn') প্রকার @else Type @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') দেখুন @else View @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') মুছে ফেলা @else Delete @endif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>{{$invoice->reference_no}}</th>
                                    <th>{{$invoice->order_id}}</th>
                                    <th>{{$invoice->type}}</th>
                                    <th><a href="{{route('admin.invoiceview',encrypt($invoice->id))}}">View</a></th>
                                    <th><a href="">Delete</a></th>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive" id="mobiletable">
                    <table class="table" width="100%">
                        @foreach($invoices as $key=>$invoice)
                        <tr class="mobilefirstrow">
                            <th width="10%">
                                <input type="checkbox" name="selectedid" value="{{$invoice->id}}" id="id" class="checkSingle">
                            </th>
                            <th width="20%" style="color:#f1593a">
                                Invoice Id:
                            </th>
                            <td width="60%" style="color:black">
                                {{$invoice->reference_no}}
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
                                Order Id:
                            </th>
                            <td width="60%">
                                 {{$invoice->order_id}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Type
                            </th>
                            <td width="60%">
                                 {{$invoice->type}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                View
                            </th>
                            <td width="60%">
                                <a href="{{route('admin.invoiceview',encrypt($invoice->id))}}">View</a>
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Delete
                            </th>
                            <td width="60%">
                                <a href="">Delete</a>
                            </td>
                            <td width="10%"></td>
                        </tr>
                        @endforeach
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
</script>
@endpush