@extends('admin.layouts.main')
@push('styles')


@endpush
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
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.returned')}}">
                            <img src="{{URL::to('/')}}/img/icons/product-return.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ফেরত পণ্য @else Returned Product @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.invoice')}}">
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
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সমস্ত অর্ডার @else All Orders @endif</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <!--<li style="padding:0px;border:0px;"><a href="#" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
                <li style="padding:0px;border:0px;"><a data-href="/orderexport" onclick="exportTasks(event.target);" style="display:block;border-radius:0px !important" class="btn btn-secondary">@if(Session::has('lang') && Session::get('lang')=='bn') এক্সপোর্ট @else Excel @endif</a></li>
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
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <form action="{{route('admin.order.retypefilter')}}" method="get">
                    
                        <select class="form-select" name="type" onchange="this.form.submit()">
                            <option value="all" @if(isset($type) && $type=='all') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn') সব অর্ডার @else All Order @endif </option>
                            <option value="walking_customer" @if(isset($type) && $type=='walking_customer') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn') হাঁটা গ্রাহক @else Walking Customer @endif</option>
                            <option value="website_customer" @if(isset($type) && $type=='website_customer') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn') ওয়েবসাইট গ্রাহক @else Website Customer @endif</option>
                        </select>
                        </form>
                    </div>

                
                </div>
            </div>
            <div class="card-body">
            @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive" id="desktoptable">
                    <table id="taskfilterresult" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th width="3%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                
                                <th width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') অর্ডারের তারিখ @else Order Date @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') রেফারেন্স নং @else Reference No @endif</th>
                                <th width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') গ্রাহক ফোন @else Customer Phone @endif</th>
                                <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn') সাবটোটাল @else Subtotal @endif</th>
                                <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn') ডিসকাউন্ট @else Discount @endif</th>
                                <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn') পাঠানো @else Shipping @endif</th>
                                <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn') ট্যাক্স @else Tax @endif</th>
                                <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn') মোট @else Total @endif</th>
                                <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn') অবস্থা @else Status @endif</th>
                                <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn') আদেশ মত @else Order Type @endif</th>
                                <th width="17%">@if(Session::has('lang') && Session::get('lang')=='bn') দেখুন @else Action @endif</th>
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
                                <td><span class="badge badge-primary" @if($order->status=='Pending') style="background-color:#f0ad4e;color:#fff" @elseif ($order->status=='On Hold') style="background-color:#777;;color:#fff" @elseif ($order->status=='On Hold') style="background-color:#777;;color:#fff"@elseif ($order->status=='Restock') style="background-color:#777;;color:#fff" @elseif($order->status=='Delivered') style="background-color:#d9534f;color:#fff" @elseif($order->status=='Payment Failed') style="background-color:#d9534f;color:#fff" @elseif($order->status=='Processing') style="background-color:#5cb85c;color:#fff" @elseif($order->status=='Shipping') style="background-color:#337ab7;color:#fff" @elseif($order->status=='Payment Failed') style="background-color:#d9534f;color:#fff" @elseif($order->status=='Completed') style="background-color:#a2cca2;color:#fff" @elseif($order->status=='Cancelled') style="background-color:#dba4a2;color:#fff" @elseif($order->status=='Returned') style="background-color:#d9534f;color:#fff" @endif>{{$order->status}}</span></td>
                                <td>{{$order->type}}</td>
                                <td>
                                    <a href="{{route('admin.order.view',$order->id)}}" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') দেখুন @else View @endif</a>
                                    @if($order->status=='Returned')
                                    <a href="{{route('admin.order.restock',$order->id)}}" onclick="return confirm('are you sure, you want to add quantity?')" class="btn btn-primary">@if(Session::has('lang') && Session::get('lang')=='bn') রি স্টক @else Restock @endif </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
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
                                Reference No:
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
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Status
                            </th>
                            <td width="60%" >
                                <span class="badge badge-primary" @if($order->status=='Pending') style="background-color:#f0ad4e;color:#fff" @elseif ($order->status=='On Hold') style="background-color:#777;;color:#fff" @elseif ($order->status=='Restock') style="background-color:#777;;color:#fff" @elseif($order->status=='Delivered') style="background-color:#d9534f;color:#fff" @elseif($order->status=='Payment Failed') style="background-color:#d9534f;color:#fff" @elseif($order->status=='Processing') style="background-color:#5cb85c;color:#fff" @elseif($order->status=='Shipping') style="background-color:#337ab7;color:#fff" @elseif($order->status=='Payment Failed') style="background-color:#d9534f;color:#fff" @elseif($order->status=='Completed') style="background-color:#a2cca2;color:#fff" @elseif($order->status=='Cancelled') style="background-color:#dba4a2;color:#fff" @elseif($order->status=='Returned') style="background-color:#d9534f;color:#fff" @endif>{{$order->status}}</span>
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Order Type
                            </th>
                            <td width="60%">
                                {{$order->type}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Action
                            </th>
                            <td width="60%">
                                <a href="{{route('admin.order.view',$order->id)}}" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') দেখুন @else View @endif</a>
                                    @if($order->status=='Returned')
                                    <a href="{{route('admin.order.restock',$order->id)}}" onclick="return confirm('are you sure, you want to add quantity?')" class="btn btn-primary">@if(Session::has('lang') && Session::get('lang')=='bn') রি স্টক @else Restock @endif </a>
                                    @endif
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
</script>
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
</script>
@endpush