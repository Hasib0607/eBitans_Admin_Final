@extends('admin.layouts.main')
@section('content')

<?php
if(Auth::user()->type=='admin'){
    $customer=DB::table('customers')->where('uid',Auth::user()->id)->first();
    $store_id=$customer->active_store;
}elseif(Auth::user()->type=='staff'){
    $staff=DB::table('staff')->where('uid',Auth::user()->id)->first();
    $store_id=$staff->store_id;
    $role=DB::table("roles")->where('id',$staff->role_id)->first();
    if(isset($role)){
        $permission=explode(',',$role->permission);
            foreach($permission as $key=>$pr){
                if($pr=='branch'){
                    $branch=1;
                }elseif($pr=='product'){
                    $product=1;
                }elseif($pr=='category'){
                    $category=1;
                }elseif($pr=='subcategory'){
                    $subcategory=1;
                }elseif($pr=='brand'){
                    $brand=1;
                }elseif($pr=='attribute'){
                    $attribute=1;
                }elseif($pr=='supplier'){
                    $supplier=1;
                }
                elseif($pr=='collection'){
                    $collection=1;
                }elseif($pr=='global_tab'){
                    $global_tab=1;
                }elseif($pr=='coupon'){
                    $coupon=1;
                }elseif($pr=='campaign'){
                    $campaign=1;
                }elseif($pr=='offer'){
                    $offer=1;
                }elseif($pr=='slider'){
                    $slider=1;
                }elseif($pr=='banner'){
                    $banner=1;
                }elseif($pr=='layouts'){
                    $layouts=1;
                }elseif($pr=='template'){
                    $template=1;
                }elseif($pr=='header'){
                    $header=1;
                }elseif($pr=='homepage'){
                    $homepage=1;
                }elseif($pr=='footer'){
                    $footer=1;
                }elseif($pr=='mobilemenu'){
                    $mobilemenu=1;
                }elseif($pr=='product_display'){
                    $product_display=1;
                }elseif($pr=='product_grid'){
                    $product_grid=1;
                }elseif($pr=='shop_page'){
                    $shop_page=1;
                }elseif($pr=='pages'){
                    $pages=1;
                }elseif($pr=='customer'){
                    $customer=1;
                }elseif($pr=='staff'){
                    $staff=1;
                }
                elseif($pr=='invoice'){
                    $invoice=1;
                }elseif($pr=='setting'){
                    $setting=1;
                }elseif($pr=='role_permission'){
                    $role_permission=1;
                }elseif($pr=='pos'){
                    $pos=1;
                }else{

                }
            }
    }
}

?>
<main class="main-content position-relative h-100 border-radius-lg">

<div class="container-fluid mt-4" id="toplist">
    @if(isset($coupon) && $coupon=='1' || Auth::user()->type=='superadmin')
    <div class="row">
        <div class="col-md-6">
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সমস্ত কুপন @else All Coupon @endif</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a data-href="/couponexport" onclick="exportCoupon(event.target);" style="display:block;border-radius:0px !important" class="btn btn-secondary">@if(Session::has('lang') && Session::get('lang')=='bn') এক্সপোর্ট @else Excel @endif</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-lg-4 col-md-12 col-sm-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn') নতুন কুপন যোগ করুন @else Add Coupon @endif</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('superadmin.savecoupon')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-11 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') কোড @else Code  @endif<span class="req">*</span></label>
                        <div class="col-md-11">
                        <input type="text" class="form-control" id="staticEmail" name="code" value="{{ old('code') }}" placeholder="Coupon code">
                            @error('code')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-11 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শুরুর তারিখ @else Start Date @endif<span class="req">*</span></label>
                        <div class="col-md-11">
                        <input type="date" class="form-control" id="staticEmail" name="start_date">
                            @error('start_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-11 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শেষ তারিখ @else End Date @endif<span class="req">*</span></label>
                        <div class="col-md-11">
                        <input type="date" class="form-control" id="staticEmail" name="end_date">
                            @error('end_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-11 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ন্যূনতম ক্রয় @else Minimum Purchase @endif<span class="req">*</span></label>
                        <div class="col-md-11">
                        <input type="number" class="form-control" id="staticEmail" name="min_purchase">
                            @error('min_purchase')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-11 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') সর্বোচ্চ ক্রয় @else Maximum Purchase @endif</label>
                        <div class="col-md-11">
                        <input type="number" class="form-control" id="staticEmail" name="max_purchase">
                            @error('max_purchase')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-11 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ছাড়ের ধরন @else Discount Type @endif<span class="req">*</span></label>
                        <div class="col-md-11">
                        <select class="form-control" name="discount_type" id="discount_type">
                            <option value="fixed">@if(Session::has('lang') && Session::get('lang')=='bn') ফিক্সড @else  Fixed @endif</option>
                            <option value="percent">@if(Session::has('lang') && Session::get('lang')=='bn') পার্সেন্ট @else  Percent @endif</option>
                        </select>
                            @error('discount_type')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-11 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ডিসকাউন্ট মূল্য @else Discount Amount @endif <span class="req">*</span></label>
                        <div class="col-md-11">
                        <input type="number" class="form-control" id="staticEmail" name="discount_amount">
                            @error('discount_amount')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-11 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') প্রতি ব্যবহারকারী সর্বোচ্চ ব্যবহার @else Max Use per User  @endif<span class="req">*</span></label>
                        <div class="col-md-11">
                        <input type="number" class="form-control" id="staticEmail" name="max_use">
                            @error('max_use')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-3 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') স্ট্যাটাস @else Status @endif</label>
                        <div class="col-md-8">
                        <div class="form-check form-switch is-filled pt-3" style="text-align:center;">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="status" style="margin:0 auto;" checked="">
                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                        </div>
                        @error('status')
                                <p class="text-danger" role="alert">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="position" class="col-md-3 col-form-label"></label>
                        <div class="col-md-8" style="text-align:right">
                            <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Submit @endif</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 mt-4">
        <div class="card">
            <div class="card-header">
                @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                <div class="row">
                    <div class="col-md-2" style="padding-right:1px;">
                        <form id="submitform" method="post" action="{{route('superadmin.changecouponstatus')}}">
                        @csrf
                    <input type="hidden" name="text2" id="selectids">
                        <select class='form-control' name="action" id="action">
                            <option value="select">@if(Session::has('lang') && Session::get('lang')=='bn') সিলেক্ট  অপসন @else Select Option @endif</option>
                            <option value="active">@if(Session::has('lang') && Session::get('lang')=='bn') সক্রিয় @else Active @endif</option>
                            <option value="deactive">@if(Session::has('lang') && Session::get('lang')=='bn') নিষ্ক্রিয় @else Deactive @endif</option>
                            <option value="delete">@if(Session::has('lang') && Session::get('lang')=='bn') ডিলিট @else Delete @endif</option>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding-left:0px;">
                        <p id="submit" class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn') আবেদন  @else Apply @endif</p>
                        </form>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-3">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" width="100%" id="taskfilterresult">
                        <thead>
                            <tr>
                                <th width="10%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                <th width="23%">@if(Session::has('lang') && Session::get('lang')=='bn') কোড @else Code @endif</th>
                                <th width="23%">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</th>
                                <th width="23%">@if(Session::has('lang') && Session::get('lang')=='bn') স্ট্যাটাস @else Status @endif</th>
                                <th width="24%">@if(Session::has('lang') && Session::get('lang')=='bn') এডিট/ডিলিট @else Action @endif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="selectedid" value="{{$coupon->id}}" id="id" class="checkSingle"></td>
                                <td>{{$coupon->code}}</td>
                                <td>{{$coupon->name}}</td>
                                <td style="text-align: center;display: flex;align-items: center;justify-content: center;padding-left: 0px;">
                                <div class="form-check form-switch" style="text-align:center;">
                                        <input class="form-check-input switchstatus" type="checkbox" id="flexSwitchCheckChecked" data-id="{{$coupon->id}}" name="checkstatus" style="margin:0 auto;" @if($coupon->status=='active') checked @endif>
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                <a href="{{route('superadmin.coupon.edit',$coupon->id)}}"  ><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a> &nbsp;&nbsp;
                                <a href="{{route('superadmin.coupon.delete',$coupon->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    $(document).ready(function(){
        $(".switchstatus").on("change",function(){
            $url="/changecouponstatus";
            var value=$(this).val();
            console.log(value);
            var id = $(this).data('id');
            console.log(id);
            $.get($url,{value:value,id:id}, function(data){
               console.log(data);
            });
        });
    });
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
    function exportCoupon(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
    </script>
@endpush
