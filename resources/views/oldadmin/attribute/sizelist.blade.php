@extends('admin.layouts.main')
@push('styles')
<style>
    @media only screen and (max-width:500px){
        .sizelistss{
            margin-top:20px;
        }
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
<style>
    .left-menu{
        position:relative;
        top:50% !important;
    }
    .left-menu ul li{
        float:unset !important;
    }
    .card{
        border:1px solid rgba(222, 226, 230, 0.7);
    }
    .card .card-body {
    font-family: "Roboto", Helvetica, Arial, sans-serif;
    padding: .5rem 1.5rem 1.5rem 1.5rem;
}
.card .card-header{
    padding: .5rem 1.5rem .5rem 1.5rem;
    border-bottom:1px solid rgba(222, 226, 230, 0.7);
}
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
</style>
<main class="main-content position-relative h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a href="{{URL::to('/')}}/products">
                            <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য @else Products @endif</span>
                        </a>
                    </li>
                    @if(isset($category) && $category=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/category">
                            <img src="{{URL::to('/')}}/img/icons/categories.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ক্যাটাগরি @else Categories @endif</span>
                        </a>
                    </li>
                    @endif
                    @if(isset($subcategory) && $subcategory=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.subcategory.index')}}">
                            <img src="{{URL::to('/')}}/img/subcategory.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') সাব ক্যাটাগরি @else Sub Categories @endif</span>
                        </a>
                    </li>
                    @endif
                    @if(isset($attribute) && $attribute=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{URL::to('/')}}/attribute">
                            <img src="{{URL::to('/')}}/img/icons/product.png" ><br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্যের ধরণ @else Variants @endif</span>

                        </a>
                    </li>
                    @endif
                    @if(isset($brand) && $brand=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/brand">
                            <img src="{{URL::to('/')}}/img/icons/brand.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ব্রান্ড @else Brands @endif</span>
                        </a>
                    </li>
                    @endif
                    
                    @if(isset($supplier) && $supplier=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{URL::to('/')}}/supplier">
                            <img src="{{URL::to('/')}}/img/icons/supplier.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') সরবরাহকারী@else Suppliers @endif</span>
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
        <div class="col-md-3 left-menu card card-body mt-4">
            <ul style="padding-left:0rem;">
                <li style="background-color:#FFFF;margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{URL::to('/')}}/attribute" style="display:block;">@if(Session::has('lang') && Session::get('lang')=='bn') রঙ @else Color @endif</a></li>
                <li class="active" style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.attribute.size')}}" style="display:block;">@if(Session::has('lang') && Session::get('lang')=='bn') আকার @else Size @endif</a></li>
                <li style="background-color:#FFFF;margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.attribute.unit')}}" style="display:block;">@if(Session::has('lang') && Session::get('lang')=='bn') ইউনিট @else  Unit @endif</a></li>
            </ul>
        </div>
        <div class="col-md-9 rightmenu mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সাইজ যোগ করুন @else  Add Size @endif</h4>
                        </div>
                        <div class="card-body">
                        <form action="{{route('admin.size.save')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-md-4 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</label>
                                <div class="col-md-12">
                                <input type="text" class="form-control" id="staticEmail" name="name" placeholder="Size Name" required>
                                    @error('name')
                                        <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="position" class="col-md-4 col-form-label"></label>
                                <div class="col-md-12" style="text-align:left">
                                    <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') জমা দিন @else Submit @endif</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 sizelistss">
                    <div class="card">
                        <div class="card-header">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{Session::get('success_message')}}</div>
                            @endif
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="input-group" >
                                        <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                                <!-- <div class="col-md-2">
                                    <input type="date" name="date" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select">
                                        <option>Select</option>
                                    </select>
                                </div> -->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" width="100%" id="taskfilterresult">
                                    <thead>
                                        <tr>
                                            <th width="20%" style="padding:6px"><input type="checkbox"></th>
                                            <th width="40%" style="padding:6px">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</th>                             
                                            <th width="40%" style="padding:6px">@if(Session::has('lang') && Session::get('lang')=='bn') ডিলিট @else Action @endif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sizes as $size)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>
                                                <input type="checkbox" name="id" value="{{$size->id}}">
                                            </td>
                                            <td>{{$size->name}}</td>
                                            <td>             
                                                <a href="{{route('admin.size.delete',$size->id)}}" style="float:center;margin-right:5px;" onclick="return confirm('Are you sure you want to delete this item?');" ><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>    
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
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    </script>
@endpush