@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.customer')}}">
                            <img src="{{URL::to('/')}}/img/icons/rating.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ক্রেতা @else Customers @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.review')}} ">
                            <img src="{{URL::to('/')}}/img/icons/reviews.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') রিভিউ @else Reviews @endif</span>
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
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সব ক্রেতা @else All Customer @endif </h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="{{route('admin.addcustomer')}}" class="btn btn-primary" style="display:block;border-radius:0px !important">@if(Session::has('lang') && Session::get('lang')=='bn') নতুন ক্রেতা যোগ করুন @else Add Customer @endif</a></li>
                <li style="padding:0px;border:0px;"><a data-href="/customerexport" onclick="exportTasks(event.target);" style="display:block;border-radius:0px !important" class="btn btn-secondary">@if(Session::has('lang') && Session::get('lang')=='bn') এক্সপোর্ট @else Excel @endif </a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-2" style="padding-right:1px;">
                        <form id="submitform" method="post" action="{{route('admin.changecustomerssstatus')}}">
                        @csrf
                        <input type="hidden" name="text2" id="selectids">
                        <select class='form-control' name="action" id="action">
                            <option value="select">@if(Session::has('lang') && Session::get('lang')=='bn') সিলেক্ট  অপসন @else Select Option @endif</option>
                            <option value="delete">@if(Session::has('lang') && Session::get('lang')=='bn') ডিলিট @else Delete @endif</option>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding-left:0px;">
                        <p id="submit" class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn') আবেদন  @else Apply @endif</p>
                        </form>
                    </div>
                    <div class="col-md-7"></div>
                    <div class="col-md-2">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive" id="desktoptable">
                    <table class="table table-striped" id="taskfilterresult" width="100%">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') ছবি @else Image @endif </th>
                                <th width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</th>
                                <th width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') ইমেইল @else Email @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') ফোন @else Phone @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') ঠিকানা @else Address @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') অবস্থান @else Position @endif </th>
                                <th width="16%">@if(Session::has('lang') && Session::get('lang')=='bn') এডিট/ডিলিট @else Action @endif </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $customer)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td>
                                    <input type="checkbox" name="selectedid" value="{{$customer->id}}" id="id" class="checkSingle">
                                </td>
                                <td>
                                    <img src="{{URL::to('/')}}/assets/images/img/{{$customer->image}}" alt="" width="60px">
                                </td>
                                <td>{{$customer->name}}</td>
                                <td>
                                {{$customer->email}}                                
                                </td>
                                <td>{{$customer->phone}}</td>
                                <td>{{$customer->address}}</td>
                                <td>@if($customer->otp=='NULL') Verified @else Unverified @endif</td>
                                <td>
                                    <a href="{{URL::to('/')}}/customer/edit/{{$customer->id}}"><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                    &nbsp;&nbsp;
                                    <a href="{{URL::to('/')}}/customer/delete/{{$customer->id}}" onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive" id="mobiletable">
                    <table class="table" width="100%">
                        @foreach($data as $key=>$customer)
                        <tr class="mobilefirstrow">
                            <th width="10%">
                                <input type="checkbox" name="selectedid" value="{{$customer->id}}" id="id" class="checkSingle">
                            </th>
                            <th width="20%" style="color:#f1593a">
                                Name
                            </th>
                            <td width="60%" style="color:black">
                                {{$customer->name}}
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
                                Image
                            </th>
                            <td width="60%">
                                 <img src="{{URL::to('/')}}/assets/images/img/{{$customer->image}}" alt="" width="60px">
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <!--<tr class="cat{{$key}}" style="display:none">-->
                        <!--    <th width="10%"></th>-->
                        <!--    <th width="20%">-->
                        <!--        Email-->
                        <!--    </th>-->
                        <!--    <td width="60%">-->
                        <!--        {{$customer->email}}-->
                        <!--    </td>-->
                        <!--    <td width="10%"></td>-->
                        <!--</tr>-->
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Phone
                            </th>
                            <td width="60%">
                                {{$customer->phone}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Address
                            </th>
                            <td width="60%">
                                {{$customer->address}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Position
                            </th>
                            <td width="60%">
                                @if($customer->otp=='NULL') Verified @else Unverified @endif
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Action
                            </th>
                            <td width="60%">
                               <a href="{{URL::to('/')}}/customer/edit/{{$customer->id}}"><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                    &nbsp;&nbsp;
                                <a href="{{URL::to('/')}}/customer/delete/{{$customer->id}}" onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>
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
 $('#submit').on('click',function(){
     var form = $(this).parents('form');
     var note=$('#action').val();
     if(note != 'select'){
        swal.fire({
          title: 'Are you sure?',
          text: "You want to "+note+" this selected item",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, '+note+' it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
              console.log(form);
            $('#submitform').submit();
            form.submit();
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ){
            swal.fire(
              'Cancelled',
              ''+note+' Cancel :)',
              'error'
            )
          }
        })
     }
 })
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