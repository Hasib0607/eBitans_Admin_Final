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
<main class="main-content position-relative border-radius-lg">


<div class="container-fluid mt-4" id="toplist">
    @if(isset($coupon) && $coupon=='1' || Auth::user()->type=='superadmin')
    <div class="row">
        <div class="col-md-6">
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট   কুপন @else Update Coupon @endif</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li class="active"><a href="{{URL::to('/')}}/promotions/coupon">@if(Session::has('lang') && Session::get('lang')=='bn') তালিকায় ফিরে যান @else Back to List @endif </a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
            <form action="{{route('superadmin.coupon.update',$coupon->id)}}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') কোড @else Code  @endif<span class="req">*</span></label>
                        <div class="col-md-4">
                        <input type="text" class="form-control" id="staticEmail" name="code" value="{{$coupon->code}}">
                            @error('code')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শুরুর তারিখ @else Start Date @endif <span class="req">*</span></label>
                        <div class="col-md-4">
                        <input type="date" class="form-control" id="staticEmail" value="{{$coupon->start_date}}" name="start_date">
                            @error('start_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শেষ তারিখ @else End Date @endif <span class="req">*</span></label>
                        <div class="col-md-4">
                        <input type="date" class="form-control" id="staticEmail" value="{{$coupon->end_date}}" name="end_date">
                            @error('end_date')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ন্যূনতম ক্রয় @else Minimum Purchase @endif <span class="req">*</span></label>
                        <div class="col-md-4">
                        <input type="number" class="form-control" id="staticEmail" value="{{$coupon->min_purchase}}" name="min_purchase">
                            @error('min_purchase')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') সর্বোচ্চ ক্রয় @else Maximum Purchase @endif </label>
                        <div class="col-md-4">
                        <input type="number" class="form-control" id="staticEmail" value="{{$coupon->max_purchase}}" name="max_purchase">
                            @error('max_purchase')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ছাড়ের ধরন @else Discount Type @endif <span class="req">*</span></label>
                        <div class="col-md-4">
                        <select class="form-control" name="discount_type" id="discount_type">
                            <option value="percent" @if($coupon->discount_type=='percent') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn') পার্সেন্ট @else  Percent @endif </option>
                            <option value="fixed" @if($coupon->discount_type=='fixed') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn') ফিক্সড @else  Fixed @endif </option>
                        </select>
                            @error('discount_type')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ডিসকাউন্ট মূল্য @else Discount Amount @endif  <span class="req">*</span></label>
                        <div class="col-md-4">
                        <input type="number" class="form-control" id="staticEmail" name="discount_amount" value="{{$coupon->discount_amount}}">
                            @error('discount_amount')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') প্রতি ব্যবহারকারী সর্বোচ্চ ব্যবহার @else Max Use per User @endif <span class="req">*</span></label>
                        <div class="col-md-4">
                        <input type="number" class="form-control" id="staticEmail" value="{{$coupon->max_use}}" name="max_use">
                            @error('max_use')
                                <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') স্ট্যাটাস @else Status @endif </label>
                    <div class="col-md-4">
                    <div class="form-check form-switch is-filled" style="text-align:center;">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="status" style="margin:0 auto;" @if($coupon->status='active') checked="" @endif>
                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                    </div>
                    @error('status')
                            <p class="text-danger" role="alert">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="position" class="col-md-2 col-form-label"></label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif </button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        </div>
    </div>
     @endif
</div>
</main>
@endsection
