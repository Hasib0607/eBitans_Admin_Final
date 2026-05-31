@extends('admin.layouts.main')
@section('content')
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
.avatar-upload {
    position: relative;
    max-width: 205px;
    margin: 20px auto;
}
    .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
    }
     .avatar-edit input {
            display: none;
     }
    .avatar-edit label {
                display: inline-block;
                width: 34px;
                height: 34px;
                margin-bottom: 0;
                border-radius: 100%;
                background: #FFFFFF;
                border: 1px solid transparent;
                box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
                cursor: pointer;
                font-weight: normal;
                transition: all .2s ease-in-out;
    }
    .avatar-edit label:hover {
                    background: #f1f1f1;
                    border-color: #d6d6d6;
                }
    .avatar-edit label:after {
                    content: "\f040";
                    font-family: 'FontAwesome';
                    color: #757575;
                    position: absolute;
                    top: 10px;
                    left: 0;
                    right: 0;
                    text-align: center;
                    margin: auto;
    }
    .avatar-preview {
        width: 192px;
        height: 192px;
        position: relative;
        border-radius: 100%;
        border: 6px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }
    .avatar-preview>div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
    }
</style>

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
                    $settingss=1;
                }elseif($pr=='role_permission'){
                    $role_permission=1;
                }elseif($pr=='pos'){
                    $pos=1;
                }elseif($pr=='testimonials'){
                    $tt=1;
                }elseif($pr=='designsettings'){
                    $ds=1;
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
<main class="main-content position-relative  h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                <li class="breadcrumb-item ">
                        <a href="{{route('admin.design.slider')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Slider
                        </a>
                    </li>
                    @if(isset($banner) && $banner=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.banner')}}">
                            <img src="{{URL::to('/')}}/img/categories1.png" > <br>Banner
                        </a>
                    </li>
                    @endif
                    @if(isset($layout) && $layout=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.layout.homepage')}}">
                            <img src="{{URL::to('/')}}/img/subcategory.png" > <br>Invoice
                        </a>
                    </li>
                    @endif
                    @if(isset($template) && $template=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.theme')}}">
                            <img src="{{URL::to('/')}}/img/brand.png" > <br>Themes
                        </a>
                    </li>
                    @endif
                    @if(isset($header) && $header=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.design')}}">
                            <img src="{{URL::to('/')}}/img/sort-descending.png" ><br>Header
                        </a>
                    </li>
                    @endif
                    @if(isset($homepage) && $homepage=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.homepage.slider')}}">
                            <img src="{{URL::to('/')}}/img/ribbon.png" > <br>HomePage
                        </a>
                    </li>
                    @endif
                    <!--@if(isset($footer) && $footer=='1' || Auth::user()->type=='admin')-->
                    <!--<li class="breadcrumb-item" aria-current="page">-->
                    <!--    <a href="#">-->
                    <!--        <img src="{{URL::to('/')}}/img/collection.png" > <br>Footer-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--@endif-->
                    <!--@if(isset($mobilemenu) && $mobilemenu=='1' || Auth::user()->type=='admin')-->
                    <!--<li class="breadcrumb-item" aria-current="page">-->
                    <!--    <a href="#">-->
                    <!--        <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Mobile Menu-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--@endif-->
                    <!--@if(isset($product_display) && $product_display=='1' || Auth::user()->type=='admin')-->
                    <!--<li class="breadcrumb-item" aria-current="page">-->
                    <!--    <a href="#">-->
                    <!--        <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Product Display-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--@endif-->
                    <!--@if(isset($product_grid) && $product_grid=='1' || Auth::user()->type=='admin')-->
                    <!--<li class="breadcrumb-item" aria-current="page">-->
                    <!--    <a href="#">-->
                    <!--        <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Product Grid System-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--@endif-->
                    <!--@if(isset($shop_page) && $shop_page=='1' || Auth::user()->type=='admin')-->
                    <!--<li class="breadcrumb-item" aria-current="page">-->
                    <!--    <a href="#">-->
                    <!--        <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Shop Page-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--@endif-->
                    @if(isset($tt) && $tt=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.testimonials')}}">
                            <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Testimonials
                        </a>
                    </li>
                    @endif
                    @if(isset($pages) && $pages=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.pages')}}">
                            <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Page
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.homepage.invoice')}}">
                            <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Invoice
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
        <div class="col-md-9 rightmenu">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Frontend Settings</h4>
                        </div>
                        <div class="card-body">
                        @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                            <form action="{{route('admin.saveheadersettings')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="imageUpload" name="logo" accept=".png, .jpg, .jpeg" />
                                            <label for="imageUpload"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            @if(isset($setting))
                                            @if(isset($setting->logo))
                                            <div id="imagePreview" style="background-image: url(https://admin.ebitans.com/assets/images/setting/{{$setting->logo}});">
                                            @else
                                            <div id="imagePreview" style="background-image: url(http://i.pravatar.cc/500?img=7);">
                                            @endif
                                            @else
                                            <div id="imagePreview" style="background-image: url(http://i.pravatar.cc/500?img=7);">
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divv" style="text-align:center">
                                        <p style="font-weight:bold">Logo</p>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <label for="phone" class="col-md-2">Phone</label> &nbsp;&nbsp;&nbsp; 
                                    <input type="number" name="phone" class="col-md-8" value="{{$setting->phone ?? ''}}">
                                </div>
                                <br>
                                <div class="form-group row" >
                                    <label for="address" class="col-md-2">Address</label>  &nbsp;&nbsp;&nbsp;
                                    <input type="text" name="address" class="col-md-8" value="{{$setting->address ?? ''}}">                              
                                </div>
                                <br>
                                <button type="submit" class="btn btn-info mt-3">Submit</button>
                            </form>
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
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});
    </script>
@endpush