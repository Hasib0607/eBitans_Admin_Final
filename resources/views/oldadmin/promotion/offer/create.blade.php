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
                    $offerss=1;
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
        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                    <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
                        <table class="table table-stripped">
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
                                <tr>
                                    <td><input type="checkbox" name="selectedid" id="id" value="{{$product->id}}" class="checkSingle"></td>
                                    <td>{{Str::of($product->name)->limit(20)}}</td>
                                    <td>{{$product->SKU}}</td>
                                    <td>{{$product->regular_price}}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
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
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.promotion.campaign')}}">
                            <img src="{{URL::to('/')}}/img/icons/bullhorn.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') প্রচারণা @else Campaign @endif</span>
                        </a>
                    </li>
                    @endif
                    @if(isset($offer) && $offer=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item active" aria-current="page">
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
            <h4>Add Campaign</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a href="{{URL::to('/')}}/promotions/campaign" class="btn btn-primary" style="display:block;border-radius:0px !important">Back to List</a></li>
                <li style="padding:0px;border:0px;"><a href="#" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>
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
                    <label for="staticEmail" class="col-md-2 col-form-label">Name</label>
                    <div class="col-md-4">
                    <input type="text" class="form-control" id="staticEmail" name="name" placeholder="Campaign Name">
                        @error('name')
                            <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">Start Date</label>
                        <div class="col-md-4">
                        <input type="date" class="form-control" id="staticEmail" name="start_date">
                            @error('start_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">End Date</label>
                        <div class="col-md-4">
                        <input type="date" class="form-control" id="staticEmail"  name="end_date">
                            @error('end_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">Discount Type</label>
                        <div class="col-md-4">
                        <select name="discount_type" class="form-control">
                            <option>Select</option>
                            <option value="fixed">Fixed</option>
                            <option value="percent">Percent</option>
                        </select>
                            @error('discount_type')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">Discount Amount</label>
                        <div class="col-md-4">
                        <input type="number" name="discount_amount" class="form-control" >
                            @error('discount_amount')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-md-2 col-form-label">Status</label>
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
                <input type="hidden" name="text2" id="selectids">
                <div class="mb-3 row">
                    <label for="position" class="col-md-2 col-form-label">Add Product</label>
                    <div class="col-md-4">
                    <p>
                    <!--<a class="btn btn-primary" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Add Product</a>-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          Add Product
                        </button>
                    </p>
                    </div>
                    
                </div>
                <div class="mb-3 row">
                    <label for="position" class="col-md-2 col-form-label"></label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info">Update</button>
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