@extends('admin.layouts.main')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
<style>
.rightmenu li{
    float:left !important;
    padding: 1px 16px !important;
}
.select2-container .select2-selection--multiple {
    min-height:40px !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    border-right:0px solid black !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    margin-top:4px !important;
}
.rightmenu ul li {
  float: left;
  padding: 1px 15px;
  border: 1px solid #5e4c4c33;
}
</style>
@endpush
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        
      <div class="modal-header">
          <div class="row" style="width:100%">
              <div class="col-md-9">
                    <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="col-md-3">
                    <div class="input-group" >
                        <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                        <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                    </div>
              </div>
          </div>
        
      </div>
      <div class="modal-body">
          
                
        <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
                                <table class="table table-stripped" id="taskfilterresult">
                                    <thead>
                                        <tr>
                                            <th><label><input type="checkbox" name="ids" id="checkedAll"></label></th>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php
                                        $products=DB::table('products')->where('store_id',$store_id)->get();
                                        ?>
                                        @if(count($products)>0)
                                        @foreach($products as $product)
                                        @if(isset($product))
                                        <tr>
                                            <td><input type="checkbox" name="selectedid"  id="id" value="{{$product->id}}" class="checkSingle"></td>
                                            <td>{{Str::of($product->name)->limit(20)}}</td>
                                            <td>{{$product->SKU}}</td>
                                            <td>{{$product->regular_price}}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
      </div>
   
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                
        <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
                                <table class="table table-stripped">
                                    <thead>
                                        <tr>
                                            <th><label><input type="checkbox" name="idss" id="checkedAlls"></label></th>
                                            <th>Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $category=DB::table('categories')->where('store_id',$store_id)->where('status','!=','RecycleBin')->get();
                                        ?>
                                        @if(count($category)>0)
                                        @foreach($category as $cats)
                                        <tr>
                                            <td><input type="checkbox" name="selectedids" id="ids" value="{{$cats->id}}" class="checkSingles"></td>
                                            <?php
                                            if($cats->parent != "0"){
                                                $ct=DB::table('categories')->where('id',$cats->parent)->first();
                                            }
                                            ?>
                                            <td>{{$cats->name}} @if(isset($ct)) ({{$ct->name}}) @endif</td>
                                            <?php
                                            $ct=null;
                                            ?>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
      </div>
   
    </div>
  </div>
</div>
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
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') নতুন প্রচারণা যোগ করুন @else  Add  Campaign @endif</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="{{URL::to('/')}}/promotion/campaign" class="btn btn-primary" style="display:block;border-radius:0px !important">@if(Session::has('lang') && Session::get('lang')=='bn') তালিকায় ফিরে যান  @else Back to List @endif</a></li>
                <li style="padding:0px;border:0px;"><a href="#" style="display:block;border-radius:0px !important" class="btn btn-secondary">@if(Session::has('lang') && Session::get('lang')=='bn') এক্সপোর্ট @else Export @endif</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
            <form action="{{route('admin.campaign.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</label>
                    <div class="col-md-4">
                    <input type="text" class="form-control" id="staticEmail" name="name" placeholder="Campaign Name">
                        @error('name')
                            <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') দৈর্ঘ্য @else Length @endif </label>
                    <div class="col-md-4">
                    <select class="form-control" name="length" id="length">
                        <option>@if(Session::has('lang') && Session::get('lang')=='bn') নির্বাচন করুন  @else Select @endif</option>
                        <option value="date_range">@if(Session::has('lang') && Session::get('lang')=='bn') তারিখের পরিসীমা @else Date Range @endif</option>
                        <option value="specific_date">@if(Session::has('lang') && Session::get('lang')=='bn') নির্দিষ্ট তারিখ @else Specific Date @endif</option>
                        <!--<option value="repeat_date">Repeat Date</option>-->
                    </select>
                        @error('length')
                            <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                    <div class="mb-3 row" id="date_range">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শুরুর তারিখ @else Start Date @endif</label>
                        <div class="col-md-4">
                        <input type="date" class="form-control" id="staticEmail" name="start_date">
                            @error('start_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row" id="date_range1">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শেষ তারিখ @else End Date @endif</label>
                        <div class="col-md-4">
                        <input type="date" class="form-control" id="staticEmail"  name="end_date">
                            @error('end_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row" id="specific_date">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') তারিখ @else Date @endif</label>
                        <div class="col-md-4">
                        <input type="text" class="form-control date" id="staticEmail"  name="specific_date">
                            @error('specific_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row rightmenu" id="repeat_date">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') দিন @else Day @endif</label>
                        <div class="col-md-4">
                         <select class="js-example-basic-single form-control" name="repeat_date[]" multiple="multiple">
                            <option value="saturday">@if(Session::has('lang') && Session::get('lang')=='bn') শনিবার @else Saturday @endif</option>
                            <option value="sunday">@if(Session::has('lang') && Session::get('lang')=='bn') রবিবার @else Sunday @endif</option>
                            <option value="monday">@if(Session::has('lang') && Session::get('lang')=='bn') সোমবার @else Monday @endif</option>
                            <option value="tuesday">@if(Session::has('lang') && Session::get('lang')=='bn') মঙ্গলবার @else Tuesday @endif</option>
                            <option value="saturday">@if(Session::has('lang') && Session::get('lang')=='bn') বুধবার @else Wednasday @endif</option>
                            <option value="thursday">@if(Session::has('lang') && Session::get('lang')=='bn') বৃহস্পতিবার @else Thursday @endif</option>
                            <option value="friday">@if(Session::has('lang') && Session::get('lang')=='bn') শুক্রবার @else Friday @endif</option>
                        </select>

                            @error('specific_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') সময় @else Time @endif</label>
                        <div class="col-md-4">
                        <input type="checkbox" class="times" name="time" id="time" value="1" onchange="valueChanged()">
                            @error('time')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row timerange">
                        <label for="staticEmail" class="col-md-2 col-form-label"></label>
                        <div class="col-md-4">
                        <input type="time" class="form-control" name="start_time" id="start_time">
                            @error('start_time')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        <br>
                        To
                        <br>
                        <input type="time" class="form-control" name="end_time" id="end_time">
                            @error('end_time')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ছাড়ের ধরন @else Discount Type @endif </label>
                        <div class="col-md-4">
                        <select name="discount_type" class="form-control">
                            <option>@if(Session::has('lang') && Session::get('lang')=='bn') নির্বাচন করুন @else Select @endif</option>
                            <option value="fixed">@if(Session::has('lang') && Session::get('lang')=='bn') ফিক্সড @else  Fixed @endif</option>
                            <option value="percent">@if(Session::has('lang') && Session::get('lang')=='bn') পার্সেন্ট @else  Percent @endif</option>
                        </select>
                            @error('discount_type')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ডিসকাউন্ট মূল্য @else Discount Amount @endif </label>
                        <div class="col-md-4">
                        <input type="number" name="discount_amount" class="form-control" >
                            @error('discount_amount')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') স্ট্যাটাস @else Status @endif </label>
                    <div class="col-md-4">
                    <div class="form-check form-switch is-filled" style="text-align:center;">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="status" style="margin:0 auto;"  checked="">
                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                    </div>
                    @error('status')
                            <p class="text-danger" role="alert">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') প্রচারের ধরন  @else Campaign Type @endif</label>
                    <div class="col-md-4">
                    <select class="form-control" name="campaign_type" id="campaign_type">
                        <option >@if(Session::has('lang') && Session::get('lang')=='bn') নির্বাচন করুন @else Select @endif</option>
                        <option value="product">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য @else Product @endif</option>
                        <option value="category">@if(Session::has('lang') && Session::get('lang')=='bn') বিভাগ @else Category @endif</option>
                    </select>
                    @error('campaign_type')
                            <p class="text-danger" role="alert">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <input type="hidden" name="text2" id="selectids">
                <input type="hidden" name="text3" id="selectidss">
                <div class="mb-3 row" id="products">
                    <label for="position" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য যোগ করুন @else Add Product @endif</label>
                    <div class="col-md-4">
                    <p>
                    <!--<a class="btn btn-primary" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Add Product</a>-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          @if(Session::has('lang') && Session::get('lang')=='bn') পণ্য যোগ করুন @else Add Product @endif
                        </button>
                    </p>
                    </div>
                    
                </div>
                <div class="mb-3 row" id="categorys">
                    <label for="position" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') বিভাগ যোগ করুন @else Add Category @endif</label>
                    <div class="col-md-4">
                    <p>
                    <!--<a class="btn btn-primary" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Add Category</a>-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                          @if(Session::has('lang') && Session::get('lang')=='bn') বিভাগ যোগ করুন @else Add Category @endif
                        </button>
                    </p>
                    </div>
                    
                </div>
                <div class="mb-3 row">
                    <label for="position" class="col-md-2 col-form-label"></label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') সংরক্ষণ @else Save @endif</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        </div>
    </div>
</div>
</main>
@endsection
@push('scripts')
 <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script>
$('.date').datepicker({
    multidate: true
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
$(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
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

$(function() {
    $('#products').hide(); 
    $('#categorys').hide();
    $('#campaign_type').change(function(){
        if($('#campaign_type').val() == 'product') {
            $('#products').show();
            $('#categorys').hide();  
        } else if($('#campaign_type').val() == 'category'){
            $('#products').hide(); 
            $('#categorys').show(); 
        }else{
            $('#products').hide(); 
            $('#categorys').hide();
        }
    });
});
$(function() {
    $(".timerange").hide();
    $('#date_range').hide(); 
    $('#date_range1').hide(); 
    $('#specific_date').hide();
    $('#repeat_date').hide();
    $('#length').change(function(){
        if($('#length').val() == 'date_range') {
            $('#date_range').show();
            $('#date_range1').show();
            $('#specific_date').hide();
            $('#repeat_date').hide();
        } else if($('#length').val() == 'specific_date'){
            $('#date_range').hide();
            $('#date_range1').hide();
            $('#specific_date').show();
            $('#repeat_date').hide();
        }else if($('#length').val() == 'repeat_date'){
            $('#date_range').hide();
            $('#date_range1').hide();
            $('#specific_date').hide();
            $('#repeat_date').show();
        }else{
            $('#date_range').hide();
            $('#date_range1').hide();
            $('#specific_date').hide();
            $('#repeat_date').hide();
        }
    });
});
function valueChanged()
    {
        if($('.times').is(":checked"))   
            $(".timerange").show();
        else
            $(".timerange").hide();
    }

$(document).ready(function() {
    $("#checkedAlls").change(function() {
        debugger;
        if (this.checked) {
            $(".checkSingles").each(function() {
                debugger;
                this.checked=true;
                var valuesArray = $('input[name="selectedids"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectidss").val(valuesArray);
                $("#selectdelidss").val(valuesArray);
            });
        } else {
            $(".checkSingles").each(function() {
                this.checked=false;
            });
            var valuesArray ='';
            $("#selectidss").val(valuesArray);
            $("#selectdelidss").val(valuesArray);
        }
    });
    $(".checkSingles").click(function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".checkSingles").each(function() {
                if (!this.checked)
                    isAllChecked = 1;
                var valuesArray = $('input[name="selectedids"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectidss").val(valuesArray);
                $("#selectdelidss").val(valuesArray);
            });
            if (isAllChecked == 0) {
                $("#checkedAlls").prop("checked", true);
            }     
        }
        else {
            $("#checkedAlls").prop("checked", false);
            var valuesArray = $('input[name="selectedids"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectidss").val(valuesArray);
                $("#selectdelidss").val(valuesArray);
        }
    });
});
</script>
@endpush