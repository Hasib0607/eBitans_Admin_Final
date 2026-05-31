@extends('admin.layouts.main')
@section('content')
<style>
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
.size{
    list-style-type:none;

}
.size li{
    float:left;
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
                    $setting=1;
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
<main class="main-content position-relative h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if(isset($template) && $template=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.theme')}}">
                            <img src="{{URL::to('/')}}/img/icons/web-design.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ওয়েবসাইট থিম @else Website Themes @endif</span>
                        </a>
                    </li>

                    @if(isset($homepage) && $homepage=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.homepage.slider')}}">
                            <img src="{{URL::to('/')}}/img/icons/landing-page.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') হোম পেজ ডিজাইন @else HP Layout Design @endif</span>
                        </a>
                    </li>
                    @endif

                    @if(isset($header) && $header=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.design')}}">
                            <img src="{{URL::to('/')}}/img/icons/title.png" ><br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') হেডার ডিজাইন @else Header Design @endif</span>

                        </a>
                    </li>
                    @endif

                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.design.slider')}}">
                            <img src="{{URL::to('/')}}/img/icons/slider.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') স্লাইডার @else Slider @endif</span>
                        </a>
                    </li>
                    @endif

                    @if(isset($banner) && $banner=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.banner')}}">
                            <img src="{{URL::to('/')}}/img/icons/ads.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') বিজ্ঞাপন ব্যানার @else Ads Banner @endif</span>
                        </a>
                    </li>
                    @endif
                    <!--@if(isset($layout) && $layout=='1' || Auth::user()->type=='admin')-->
                    <!--<li class="breadcrumb-item" aria-current="page">-->
                    <!--    <a href="{{route('admin.design.layout.homepage')}}">-->
                    <!--        <img src="{{URL::to('/')}}/img/icons/subcategory.png" > <br>Invoice-->
                    <!--    </a>-->
                    <!--</li>-->
                    <!--@endif-->


                    @if(isset($tt) && $tt=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.testimonials')}}">
                            <img src="{{URL::to('/')}}/img/icons/testimonial.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') প্রশংসাপত্র @else Testimonials @endif</span>
                        </a>
                    </li>
                    @endif

                    @if(isset($pages) && $pages=='1' || Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.pages')}}">
                            <img src="{{URL::to('/')}}/img/icons/team.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') অন্যান্য পেইজ @else Other Pages @endif</span>
                        </a>
                    </li>
                    @endif
                     @if(Auth::user()->type=='admin')
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.design.homepage.invoice')}}">
                            <img src="{{URL::to('/')}}/img/icons/bill-2.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') চালান টেমপ্লেট @else Invoice Template @endif</span>
                        </a>
                    </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
    <section class="container content-main">
            <div class="row">
            <form action="{{route('admin.slider.update',$slider->id)}}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="index" value="1" id="index">
                            @csrf
                <div class="row">
                <div class="col-lg-9 mt-4 mb-4">
                    <div class="content-header row">
                        <div class="col-md-6">
                            <h2 class="content-title"></h2>
                        </div>

                        <div class="col-md-6" style="text-align:right">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" style="margin:0 auto;">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') এডিট স্লাইডার @else Edit Slider @endif</h4>
                        </div>
                        <div class="card-body">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ছবি @else Image @endif </label>

                                    <div id="image-holder" style="text-align: center">
                                        <img src="{{URL::to('/')}}/assets/images/slider/{{$slider->image}}" alt="" width="250px" style="padding:10px;border:1px solid gray;margin-top:5px;margin-bottom:5px;">

                                    </div>

                                    <br>
                                    <input type="file" placeholder="Type here" class="form-control" id="image" name="image">
                                    @error('image')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <!--<div class="mb-4">-->
                                <!--    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') উপ ছবি @else Sub Image @endif</label>-->
                                <!--    <br>-->
                                <!--    <div id="image-holder2" style="text-align: center">-->
                                <!--        <img src="{{URL::to('/')}}/assets/images/slider/{{$slider->subimage}}" alt="" width="250px" style="padding:10px;border:1px solid gray;margin-top:5px;margin-bottom:5px;">-->

                                <!--    </div>-->

                                <!--    <br>-->
                                <!--    <input type="file" placeholder="Type here" class="form-control" id="subimage" name="subimage">-->
                                <!--    @error('subimage')-->
                                <!--            <p class="text-danger" role="alert">{{$message}}</p>-->
                                <!--    @enderror-->
                                <!--</div>-->
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শিরোনাম @else Title @endif</label>
                                    <input type="text" placeholder="Type here" value="{{$slider->title}}" class="form-control" id="title" name="title">
                                    @error('title')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') উপ শিরোনাম @else Sub Title @endif</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="subtitle" value="{{$slider->subtitle}}" name="subtitle">
                                    @error('subtitle')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Text Color</label>
                                    <input type="color" placeholder="Type here" class="form-control" id="link" name="color" value="{{$slider->color}}" style="width:50%">
                                    @error('color')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label"> @if(Session::has('lang') && Session::get('lang')=='bn') লিঙ্ক @else Link @endif </label>
                                    <input type="text" placeholder="Type here" class="form-control" id="link" value="{{$slider->link}}" name="link">
                                    @error('link')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') অবস্থান @else Position @endif <span class="req">*</span></label>
                                    <input type="number" placeholder="Type here" class="form-control" id="position" value="{{$slider->position}}" name="position">
                                    @error('position')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn') স্টেটাস @else Status @endif </label>
                                    <div class="col-md-4">
                                    <div class="form-check form-switch is-filled" style="text-align:center;padding-top:14px;">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="status" @if($slider->status=="active") checked="" @endif style="margin:0 auto;">
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                    @error('status')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label"></label>
                                    <button class="btn btn-info rounded font-sm hover-up" type="submit">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif </button>
                                </div>

                        </div>
                    </div> <!-- card end// -->

                </div>

                </div>
                </div>

</form>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
    $("#image").on('change', function() {

        //Get count of selected files
        var countFiles = $(this)[0].files.length;

        var imgPath = $(this)[0].value;
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var image_holder = $("#image-holder");
        image_holder.empty();

        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
            if (typeof(FileReader) != "undefined") {

                //loop for each file selected for uploaded.
                for (var i = 0; i < countFiles; i++) {

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "style": "width: 40%;margin-bottom: 20px;vertical-align: baseline;cursor:pointer",
                            "src": e.target.result,
                            "class": "thumb-image"
                        }).appendTo(image_holder);
                    }

                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[i]);
                }

            } else {
                alert("This browser does not support FileReader.");
            }
        } else {
            alert("Pls select only images");
        }
    });
</script>

<script>
    $("#subimage").on('change', function() {

        //Get count of selected files
        var countFiles = $(this)[0].files.length;

        var imgPath = $(this)[0].value;
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var image_holder = $("#image-holder2");
        image_holder.empty();

        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
            if (typeof(FileReader) != "undefined") {

                //loop for each file selected for uploaded.
                for (var i = 0; i < countFiles; i++) {

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "style": "width: 40%;margin-bottom: 20px;vertical-align: baseline;cursor:pointer",
                            "src": e.target.result,
                            "class": "thumb-image"
                        }).appendTo(image_holder);
                    }

                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[i]);
                }

            } else {
                alert("This browser does not support FileReader.");
            }
        } else {
            alert("Pls select only images");
        }
    });
</script>
@endpush
