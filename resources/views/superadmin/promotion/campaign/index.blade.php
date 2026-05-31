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
$store=DB::table('stores')->where('id',$store_id)->first();
if($store->expiry_date <= Carbon\Carbon::now()){
$exp=1;
}else{
  $exp=0;
}
?>
<main class="main-content position-relative border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                <li class="breadcrumb-item">
                        <a href="{{route('admin.promotion.coupon')}}">
                            <img src="{{URL::to('/')}}/img/icons/voucher.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') কুপন @else Coupon @endif</span>
                        </a>
                    </li>
                    @if(isset($campaign) && $campaign=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{route('admin.promotion.campaign')}}">
                            <img src="{{URL::to('/')}}/img/icons/bullhorn.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') প্রচারণা @else Campaign @endif</span>
                        </a>
                    </li>
                    @endif
                    @if(isset($offer) && $offer=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.promotion.offer')}}">
                            <img src="{{URL::to('/')}}/img/icons/offer.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') অফার @else Offer @endif</span>
                        </a>
                    </li>   
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4> @if(Session::has('lang') && Session::get('lang')=='bn') সমস্ত প্রচারণা  @else All Campaign @endif  </h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="{{route('admin.campaign.add')}}" class="btn btn-primary" style="display:block;border-radius:0px !important">@if(Session::has('lang') && Session::get('lang')=='bn') নতুন প্রচারাভিযান তৈরি করুন @else Create New @endif</a></li>
                <li style="padding:0px;border:0px;"><a data-href="/campaignexport" onclick="exportCampaign(event.target);"  style="display:block;border-radius:0px !important" class="btn btn-secondary">@if(Session::has('lang') && Session::get('lang')=='bn') এক্সপোর্ট @else Excel @endif </a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-2" style="padding-right:1px;">
                        <form id="submitform" method="post" action="{{route('admin.changecampaignssstatus')}}">
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
                    <table class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                <th width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</th>
                                <th width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') প্রচারের ধরন  @else Campaign Type @endif</th>
                                <th width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') ছাড়ের ধরন @else Discount Type @endif </th>
                                <th width="15%">@if(Session::has('lang') && Session::get('lang')=='bn') ডিসকাউন্ট মূল্য @else Discount Amount @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') স্ট্যাটাস @else Status @endif </th>
                                <th width="11%">@if(Session::has('lang') && Session::get('lang')=='bn') এডিট/ডিলিট @else Action @endif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaigns as $key=>$campaign)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="selectedid" value="{{$campaign->id}}" id="id" class="checkSingle"></td>
                                <td>{{$campaign->name}}</td>
                                <td>{{$campaign->length_type}}</td>
                                <td>{{$campaign->discount_type}}</td>
                                <td>৳{{$campaign->discount_amount}}</td>
                                <td>
                                    <div class="form-check form-switch" style="text-align:center;">
                                        <input class="form-check-input switchstatus" type="checkbox" data-id="{{$campaign->id}}" id="flexSwitchCheckChecked" style="margin:0 auto;" @if($campaign->status=='active') checked @endif>
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                </td>
<!-- Modal Start-->

<!-- Modal -->
<div class="modal fade" id="exampleModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Campaign</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Campaign Type :  {{$campaign->length_type}}</p>
        
        @if($campaign->length_type=="repeat_date")
        <p>Repeat Dates : {{$campaign->repeat_dates}}</p>
        <p>Start Time : {{$campaign->start_time}}</p>
        <p>End Time : {{$campaign->end_time}}</p>
        
        @elseif($campaign->length_type=="date_range")
        <p>Start Date : {{$campaign->start_date}}</p>
        <p>End Date : {{$campaign->end_date}}</p>
        <p>Start Time : {{$campaign->start_time}}</p>
        <p>End Time : {{$campaign->end_time}}</p>
        
        @elseif($campaign->length_type=="specific_date")
        <p>Specific Dates : {{$campaign->specific_dates}}</p>
        
        <p>Start Time : {{$campaign->start_time}}</p>
        <p>End Time : {{$campaign->end_time}}</p>
        @endif
        <p>Discount Type : {{$campaign->discount_type}}</p>
        <p>Discount Amount : {{$campaign->discount_amount}}</p>
        
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>
<!-----Modal End---->
                                <td>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal{{$key}}">
 @if(Session::has('lang') && Session::get('lang')=='bn') দেখুন @else View @endif 
</button>&nbsp;&nbsp;
                                    <a href="{{route('admin.campaign.edit',$campaign->id)}}" ><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                    &nbsp;&nbsp;
                                    <a href="{{route('admin.campaign.delete',$campaign->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive" id="mobiletable">
                    <table class="table" style="width:100%">
                        @foreach($campaigns as $key=>$campaign)
                        <tr class="mobilefirstrow">
                            <th width="10%">
                                <input type="checkbox" name="selectedid" value="{{$campaign->id}}" id="id" class="checkSingle">
                            </th>
                            <th width="20%" style="color:#f1593a">
                                Name
                            </th>
                            <td width="60%" style="color:black">
                                {{$campaign->name}}
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
                                Campaign Type
                            </th>
                            <td width="60%">
                                {{$campaign->length_type}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Discount Type
                            </th>
                            <td width="60%">
                                 {{$campaign->discount_type}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Discount Amount
                            </th>
                            <td width="60%">
                                ৳{{$campaign->discount_amount}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Status
                            </th>
                            <td width="60%" style="display: flex;justify-content: center;align-items: center;">
                                <div class="form-check form-switch" style="text-align:center;">
                                    <input class="form-check-input switchstatus" type="checkbox" data-id="{{$campaign->id}}" id="flexSwitchCheckChecked" style="margin:0 auto;" @if($campaign->status=='active') checked @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                </div>
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Action
                            </th>
<!-- Modal Start-->

<!-- Modal -->
<div class="modal fade" id="exampleModalss{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Campaign</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Campaign Type :  {{$campaign->length_type}}</p>
        
        @if($campaign->length_type=="repeat_date")
        <p>Repeat Dates : {{$campaign->repeat_dates}}</p>
        <p>Start Time : {{$campaign->start_time}}</p>
        <p>End Time : {{$campaign->end_time}}</p>
        
        @elseif($campaign->length_type=="date_range")
        <p>Start Date : {{$campaign->start_date}}</p>
        <p>End Date : {{$campaign->end_date}}</p>
        <p>Start Time : {{$campaign->start_time}}</p>
        <p>End Time : {{$campaign->end_time}}</p>
        
        @elseif($campaign->length_type=="specific_date")
        <p>Specific Dates : {{$campaign->specific_dates}}</p>
        
        <p>Start Time : {{$campaign->start_time}}</p>
        <p>End Time : {{$campaign->end_time}}</p>
        @endif
        <p>Discount Type : {{$campaign->discount_type}}</p>
        <p>Discount Amount : {{$campaign->discount_amount}}</p>
        
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>
<!-----Modal End---->
                            <td width="60%">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalss{{$key}}">
                                 @if(Session::has('lang') && Session::get('lang')=='bn') দেখুন @else View @endif 
                                </button>&nbsp;&nbsp;
                                <a href="{{route('admin.campaign.edit',$campaign->id)}}" ><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                    &nbsp;&nbsp;
                                <a href="{{route('admin.campaign.delete',$campaign->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>
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
    $(document).ready(function(){
        $(".switchstatus").on("change",function(){
            $url="/changecampaignstatus";
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
   function exportCampaign(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
</script>
@endpush