@extends('admin.layouts.main')
@push('styles')
<style>
    @media only screen and (max-width:320px){
        .menuname{
                margin-left: -32px;
        }
        .menuposition{
            margin-left: -75px;
        }
        .menunamefield{
            width: 75px;
        }
        .menupositionfield{
            width: 30% !important;
            position: absolute;
            margin-top: -11px;
        }
    }
    @media only screen and (max-width:375px) and (min-width:321px){
        .menuname{
                margin-left: -32px;
        }
        .menuposition{
            margin-left: -75px;
        }
        .menunamefield{
            width: 75px;
        }
        .menupositionfield{
            width: 30% !important;
            position: absolute;
            margin-top: -11px;
        }
    }
    @media only screen and (max-width:425px) and (min-width:376px){
        .menuname{
                margin-left: -32px;
        }
        .menuposition{
            margin-left: -75px;
        }
        .menunamefield{
            width: 75px;
        }
        .menupositionfield{
            width: 30% !important;
            position: absolute;
            margin-top: -11px;
        }
    }
</style>
@endpush
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
<main class="main-content position-relative  h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
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
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="{{route('admin.design.design')}}">
                            <img src="{{URL::to('/')}}/img/icons/title.png" ><br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') হেডার ডিজাইন @else Header Design @endif</span>

                        </a>
                    </li>
                    @endif
                    
                    <li class="breadcrumb-item">
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
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-3 left-menu card card-body mt-4">
            <ul style="padding-left:0rem;">
                <li style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.design')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') ডিজাইন @else Design @endif</a></li>
                <li class="active" style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.header')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') মেনু @else Menu @endif</a></li>
            </ul>
        </div>
        <div class="col-md-9 rightmenu mt-4" style="padding:0px !important">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সব মেনু @else All Menu @endif</h4>
                        </div>
                        <div class="card-body">
                        @if (Session::has('success_message'))
                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                @endif
                            <form action="{{route('admin.saveheadermenu')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="Home" class="menuname" style="text-align:center">@if(Session::has('lang') && Session::get('lang')=='bn') মেনু নাম @else Menu Name @endif</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="" class="menuposition" style="text-align:center">@if(Session::has('lang') && Session::get('lang')=='bn') মেনু অবস্থান @else Menu Position @endif</label>
                                </div>
                                <div class="form-group">
                                    <?php
                                    $home=DB::table("menus")->where('store_id',$store_id)->where('url','')->first();
                                    ?>
                                    <input type="checkbox" id="Home" name="home[]" @if(isset($home)) checked @endif value="Home">&nbsp;&nbsp;&nbsp;
                                    <label for="Home"><input type="text" name="homename[]" class="menunamefield" value="{{$home->name ?? 'Home'}}"></label>  &nbsp;&nbsp;&nbsp;
                                    <input type="hidden" name="url[]" value="">
                                    <input type="hidden" name="menuselect[]" value="Home">
                                    <label for=""><input type="number" name="homesort[]" class="menupositionfield" value="{{$home->sort ?? '1'}}" style="width:50%"></label>
                                </div>
                                <div class="form-group">
                                    <?php
                                    $shop=DB::table("menus")->where('store_id',$store_id)->where('url','shop')->first();
                                    ?>
                                    <input type="checkbox" id="Shop" name="home[]" @if(isset($shop)) checked @endif value="Shop">&nbsp;&nbsp;&nbsp;
                                    <label for="Shop"><input type="text" name="homename[]" class="menunamefield" value="{{$shop->name ?? 'Shop'}}"></label> &nbsp;&nbsp;&nbsp; 
                                    <input type="hidden" name="url[]" value="shop">
                                    <input type="hidden" name="menuselect[]" value="Shop">
                                    <label for=""><input type="number" name="homesort[]" class="menupositionfield" value="{{$shop->sort ?? '1'}}"  style="width:50%"></label>
                                </div>
                                <div class="form-group">
                                    <?php
                                    $about=DB::table("menus")->where('store_id',$store_id)->where('url','about')->first();
                                    ?>
                                    <input type="checkbox" id="About" name="home[]" @if(isset($about)) checked @endif value="About">&nbsp;&nbsp;&nbsp;
                                    <label for="Shop"><input type="text" name="homename[]" class="menunamefield" value="{{$about->name ?? 'About'}}"></label>  &nbsp;&nbsp;&nbsp;
                                    <input type="hidden" name="url[]" value="about">
                                    <input type="hidden" name="menuselect[]" value="About">
                                    <label for=""><input type="number" name="homesort[]" class="menupositionfield" value="{{$about->sort ?? '1'}}"  style="width:50%"></label>                               
                                </div>
                                <div class="form-group">
                                    <?php
                                    $contact=DB::table("menus")->where('store_id',$store_id)->where('url','contact')->first();
                                    ?>
                                    <input type="checkbox" id="Contact" name="home[]" @if(isset($contact)) checked @endif value="Contact">&nbsp;&nbsp;&nbsp;
                                    <label for="Shop"><input type="text" name="homename[]" class="menunamefield" value="{{$contact->name ?? 'Contact'}}"></label>  &nbsp;&nbsp;&nbsp;
                                    <input type="hidden" name="url[]" value="contact">
                                    <input type="hidden" name="menuselect[]" value="Contact">
                                    <label for=""><input type="number" name="homesort[]" class="menupositionfield" value="{{$contact->sort ?? '1'}}"  style="width:50%"></label>                                  
                                </div>
                                <div class="form-group">
                                    <?php
                                    $category=DB::table("menus")->where('store_id',$store_id)->where('url','category')->first();
                                    ?>
                                    <input type="checkbox" id="Category" name="home[]" @if(isset($category)) checked @endif value="Category">&nbsp;&nbsp;&nbsp;
                                    <label for="Shop"><input type="text" name="homename[]" class="menunamefield" value="{{$category->name ?? 'Category'}}"></label>  &nbsp;&nbsp;&nbsp;
                                    <input type="hidden" name="url[]" value="category">
                                    <input type="hidden" name="menuselect[]" value="Category">
                                    <label for=""><input type="number" name="homesort[]" class="menupositionfield" value="{{$category->sort ?? '1'}}"  style="width:50%"></label>                                 
                                </div>
                                <div class="form-group">
                                    <?php
                                    $offer=DB::table("menus")->where('store_id',$store_id)->where('url','offer')->first();
                                    ?>
                                    <input type="checkbox" id="Offer" name="home[]" @if(isset($offer)) checked @endif value="Offer">&nbsp;&nbsp;&nbsp;
                                    <label for="Shop"><input type="text" name="homename[]" class="menunamefield" value="{{$offer->name ?? 'Offer'}}"></label>  &nbsp;&nbsp;&nbsp;
                                    <input type="hidden" name="url[]" value="offer">
                                    <input type="hidden" name="menuselect[]" value="Offer">
                                    <label for=""><input type="number" name="homesort[]" class="menupositionfield" value="{{$offer->sort ?? '1'}}"  style="width:50%"></label>                                 
                                </div>
                                <div class="form-group">
                                    <?php
                                    $blog=DB::table("menus")->where('store_id',$store_id)->where('url','blog')->first();
                                    ?>
                                    <input type="checkbox" id="Blog" name="home[]" @if(isset($blog)) checked @endif value="Blog">&nbsp;&nbsp;&nbsp;
                                    <label for="Shop"><input type="text" name="homename[]" class="menunamefield" value="{{$blog->name ?? 'Blog'}}"></label> &nbsp;&nbsp;&nbsp; 
                                    <input type="hidden" name="url[]" value="blog">
                                    <input type="hidden" name="menuselect[]" value="Blog">
                                    <label for=""><input type="number" name="homesort[]" class="menupositionfield" value="{{$blog->sort ?? '1'}}"  style="width:50%"></label> 
                                </div>
                                <button type="submit" class="btn btn-info mt-3">@if(Session::has('lang') && Session::get('lang')=='bn') সাবমিট @else Submit @endif</button>
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
    </script>
@endpush