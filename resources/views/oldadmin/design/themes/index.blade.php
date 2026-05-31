@extends('admin.layouts.main')
@push('styles')
<style>
   .themes .card-title {
    font-weight: 300;
    font-size: 13px;
    /*text-shadow: 0 0 2px #000;*/
    border-top-right-radius:67.5px;
    background:#f1593a;
    color:#fff;
    padding:6px 19px;
    margin-bottom: 14px;
}
.themes .product-card .card {
    margin: 20px;
    overflow: hidden;
}
.themes .product-card .card .card-content {
    padding: 5px;
}
.themes .product-card .card .price {
    width: 70px;
    height: 70px;
    font-weight: 600;
    font-size: 1.45rem;
    line-height: 70px;
    margin: 10px;
    position: absolute;
    top: 0;
    letter-spacing: 0;
}
.themes .product-card ul.card-action-buttons {
   /*margin: -24px 4px 0 0;*/
    text-align: right;
}
.themes .product-card ul.card-action-buttons li {
    display: inline-block;
    padding-left: 7px;
}
.themes .product-card ul.card-action-buttons li>a>i{
    color:#4a4a4a ;
}
.themes .product-card ul.card-action-buttons li a:hover{
    background-color:#f1593a;
    color:#fff;
}
.themes .product-card ul.card-action-buttons li a:hover>a>i{
    color:#fff !important;
}
.themes .product {
    width: 20%;
    padding: 10px;
}
.themes .product .card {
    margin: 0;
}
.themes .product .card .card-content {
    padding: 5px 10px;
}
div.see-more:last-of-type{width:100%;text-align:center; margin-top: 20px; background-color:#03A9DD;}
div.see-more a {color:#fff}
/*#toplist ul li {*/
/*  padding: 0px 0px !important;*/
/*}*/
/*#toplist ul li a{*/
/*  padding: 3px 11px !important;*/
/*}*/



.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -60px;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: black transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
.themeactive{
    background-color:#f1593a;
    color:#fff;
}
.themeactive>a>i{
    color:#fff !important;
}
@media only screen and (max-width:500px){
    .container1 .card{
        width:95% !important;
    }
}
@media only screen and (max-width:320px){
    .themes .card-title {
        font-size:10px;
    }
}
.container1 .card {
  position: relative;
  width: 102%;
  height: 300px;
  margin: 30px 0px;
  overflow: hidden;
  box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
  border-radius: 15px;
  /*display: flex;*/
  /*justify-content: center;*/
  /*align-items: center;*/
}
.container1 .card .content {
  position: absolute;
  bottom: -160px;
  width: 100%;
  height: 120px;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10;
  flex-direction: column;
  /*background-color: #474747;*/
  background-color: rgba(0, 0, 0, 0.7);
   -webkit-backdrop-filter: blur(10px);
  backdrop-filter: blur(10px);
  box-shadow: 0 -10px 10px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  transition: bottom 0.5s;
  transition-delay: 0.65s;
}
.container1 .card:hover .content {
  bottom: 0;
  transition-delay: 0s;
}
.container1 .card .content .contentBx h3 {
  text-transform: uppercase;
  color: #f1593a;
  letter-spacing: 2px;
  font-weight: 500;
  font-size: 16px;
  text-align: center;
  margin: 20px 0 15px;
  line-height: 1.1em;
  transition: 0.5s;
  transition-delay: 0.6s;
  opacity: 0;
  transform: translateY(-20px);
  padding:5px;
}
.container1 .card:hover .content .contentBx h3 {
  opacity: 1;
  transform: translateY(0);
}
.container1 .card .content .contentBx h3 span {
  font-size: 12px;
  font-weight: 300;
  text-transform: initial;
}
.container1 .card .content .sci {
  position: relative;
  bottom: 10px;
  display: flex;
}
.container1 .card .content .sci li {
  list-style: none;
  margin: 0 10px;
  transform: translateY(40px);
  transition: 0.5s;
  opacity: 0;
  transition-delay: calc(0.2s * var(--i));
}

.container1 .card:hover .content .sci li {
  transform: translateY(0);
  opacity: 1;
  color: white;
}
.container1 .card .content .sci li a {
  color: #f1593a;
  font-size: 24px;
}
.container1 .card .content .sci li a:hover i{
    color:#fff !important;
}
.badge-overlay {
    position: absolute;
    left: 0%;
    top: 0px;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
    z-index: 100;
    -webkit-transition: width 1s ease, height 1s ease;
    -moz-transition: width 1s ease, height 1s ease;
    -o-transition: width 1s ease, height 1s ease;
    transition: width 0.4s ease, height 0.4s ease
}

/* ================== Badge CSS ========================*/
.badge {
    margin: 0;
    padding: 0;
    color: white;
    padding: 10px 10px;
    font-size: 15px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
    line-height: normal;
    text-transform: uppercase;
    background: #ed1b24;
}

.badge::before, .badge::after {
    content: '';
    position: absolute;
    top: 0;
    margin: 0 -1px;
    width: 100%;
    height: 100%;
    background: inherit;
    min-width: 55px
}

.badge::before {
    right: 100%
}

.badge::after {
    left: 100%
}
.top-right {
    position: absolute;
    top: 0;
    right: 0;
    -ms-transform: translateX(30%) translateY(0%) rotate(45deg);
    -webkit-transform: translateX(30%) translateY(0%) rotate(45deg);
    transform: translateX(30%) translateY(0%) rotate(45deg);
    -ms-transform-origin: top left;
    -webkit-transform-origin: top left;
    transform-origin: top left;
}
.badge.red {
    background: #ed1b24;
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
<main class="main-content position-relative border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.design.slider')}}">
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
                    
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.design.slider')}}">
                            <img src="{{URL::to('/')}}/img/icons/slider.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') স্লাইডার @else Slider @endif</span>
                        </a>
                    </li>
                    
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
   <?php
   $designss=DB::table('designs')->where('store_id',$store_id)->first();
   ?>
    <div class="row">
        <div class="col-md-6">
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সব থিম @else All Themes @endif</h4>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-1" style="width:3% !important;margin-left:10px;">
                        
                    </div>
                    <div class="col-md-2">
                        
                    </div>
                    <div class="col-md-5">

                    </div>
                    <div class="col-md-1">
                        <!--<input type="date" name="date" class="form-control">-->
                    </div>
                    <div class="col-md-2" style="padding-right:1px;">
                        <form action="{{route('admin.searchtheme')}}" method="get">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter" name="keyword">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-md-1" style="padding-left:0px;">
                        <button type="submit" class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn') সাবমিট @else Submit @endif</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body d-flex flex-wrap flex-row justify-content-between" style="padding-right: 5px">
                <div class="row" style="width:100%">
                @if(isset($templates) && count($templates)>0)
                @foreach($templates as $key=>$template)
                @if(isset($designss) && $designss->template_id == $template->id)
                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12 themes'>
                    <div class="container1">
                    <div class="card">
                    <div class="imgBx">
                      <img
                        src="{{URL::to('/')}}/assets/images/template/{{$template->feature_image}}"
                        alt="" width="100%" height="300"
                      />
                    </div>
                    <div class="badge-overlay">
                        <span class="top-right badge red">Active</span>
                    </div>
                    <div class="content">
                      <div class="contentBx">
                        <h3>{{$template->name}} <br /><span>{{$template->short_description}}</span></h3>
                      </div>
                      <ul class="sci">
                        <li style="--i: 1;padding:0px;border:none;">
                          <a @if(isset($template->liveurl)) href="{{$template->liveurl}}" @else href="{{route('admin.design.theme.view',$template->id)}}"  @endif class="btn-floating waves-effect waves-light red accent-2 " target="_blank" style="padding:5px 10px;border:1px solid #f1593a">
                                <i class="fa fa-eye"></i>
                            </a>
                        </li>
                        <li style="--i: 2;padding:0px;border:none;">
                          <a href="{{route('admin.design.theme.active',$template->id)}}"  class="themeactive btn-floating waves-effect waves-light text-light" style="padding:5px 11px;border:1px solid #f1593a">
                                                        <i class="fa fa-check" style="color:#fff"></i>
                                                    </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  </div>
                </div>
                @endif    
                @endforeach
                @endif
                @if(isset($templates) && count($templates)>0)
                @foreach($templates as $key=>$template)
                @if(isset($designss) && $designss->template_id != $template->id)
                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12 themes'>
                    <div class="container1">
                    <div class="card">
                    <div class="imgBx">
                      <img
                        src="{{URL::to('/')}}/assets/images/template/{{$template->feature_image}}"
                        alt="" width="100%" height="350"
                      />
                    </div>
                    <div class="content">
                      <div class="contentBx">
                        <h3>{{$template->name}} <br /><span> {{$template->short_description}}</span></h3>
                      </div>
                      <ul class="sci">
                        <li style="--i: 1;padding:0px;border:none;">
                          <a @if(isset($template->liveurl)) href="{{$template->liveurl}}" @else href="{{route('admin.design.theme.view',$template->id)}}"  @endif class="btn-floating waves-effect waves-light red accent-2 " target="_blank" style="padding:5px 10px;border:1px solid #f1593a">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                        </li>
                        <li style="--i: 2;;padding:0px;border:none;">
                          <a href="{{route('admin.design.theme.active',$template->id)}}" class="btn-floating waves-effect waves-light " style="padding:5px 11px;border:1px solid #f1593a">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  </div>
                </div>
                @endif    
                @endforeach
                @endif
                <?php /* ?>
                <div class='col-md-4'>
                    <div class="container1">
                    <div class="card">
                    <div class="imgBx">
                      <img
                        src="https://images.pexels.com/photos/3379933/pexels-photo-3379933.jpeg?auto=compress&cs=tinysrgb&h=650&w=940"
                        alt="" width="100%"
                      />
                    </div>
                    <div class="content">
                      <div class="contentBx">
                        <h3>John <br /><span>Web Developer</span></h3>
                      </div>
                      <ul class="sci">
                        <li style="--i: 1">
                          <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        </li>
                        <li style="--i: 2">
                          <a href="#"><i class="fa fa-github" aria-hidden="true"></i></a>
                        </li>
                        <li style="--i: 3">
                          <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        </li>
                      </ul>
                    </div>
                    </div>
                  </div>
                </div>
                <div class='col-md-4'>
                    <div class="container1">
                    <div class="card">
                    <div class="imgBx">
                      <img
                        src="https://images.pexels.com/photos/3379933/pexels-photo-3379933.jpeg?auto=compress&cs=tinysrgb&h=650&w=940"
                        alt="" width="100%"
                      />
                    </div>
                    <div class="content">
                      <div class="contentBx">
                        <h3>John <br /><span>Web Developer</span></h3>
                      </div>
                      <ul class="sci">
                        <li style="--i: 1">
                          <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        </li>
                        <li style="--i: 2">
                          <a href="#"><i class="fa fa-github" aria-hidden="true"></i></a>
                        </li>
                        <li style="--i: 3">
                          <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  </div>
                </div>
                @if(isset($templates) && count($templates)>0)
                @foreach($templates as $key=>$template)
                @if(isset($designss) && $designss->template_id == $template->id)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 themes">
                        <div class="row">
                          <div class="col l4 m8 s12 offset-m2 offset-l4">
                            <div class="product-card">
                                <div class="card  z-depth-4">
                                    <div class="card-image">
                                        <img src="{{URL::to('/')}}/assets/images/template/{{$template->feature_image}}" width="100%" height="250" alt="product-img">
                                    </div>
                                    <!--<div class="d-flex flex-wrap flex-row justify-content-sm-between justify-content-md-between justify-content-lg-between justify-content-xl-between">-->
                                    <div class="row mt-1 themeviewdiv mb-3">
                                        <div class="col-md-6">
                                            <span class="card-title"><span>{{ Str::limit($template->name, 20) }}</span></span>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="card-action-buttons">
                                                <li style="padding:0px;border:none;margin-bottom:5px;">
                                                    <a @if(isset($template->liveurl)) href="{{$template->liveurl}}" @else href="{{route('admin.design.theme.view',$template->id)}}"  @endif class="btn-floating waves-effect waves-light red accent-2 " target="_blank" style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </li>
                                                <li @if(isset($designss) && $designss->template_id==$template->id) class="" @endif style="padding:0px;border:none;margin-bottom:5px;">
                                                    <a href="{{route('admin.design.theme.active',$template->id)}}"  class="themeactive btn-floating waves-effect waves-light text-light" style="padding:5px 11px;border:1px solid #5e4c4c33">
                                                        <i class="fa fa-check" style="color:#fff"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content" style="height:90px;">
                                        <div class="row">
                                            <div class="col s12">
                                                <p style="font-size:15px;padding:0px 10px;line-height:22px;">
                                                    <strong>Description:</strong> <br />
                                                        {{$template->short_description}}
                                                </p>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                @endif    
                @endforeach
                @endif
                @if(isset($templates) && count($templates)>0)
                @foreach($templates as $key=>$template)
                @if(isset($designss) && $designss->template_id != $template->id)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 themes">
                        <div class="row" >
                          <div class="col l4 m8 s12 offset-m2 offset-l4">
                            <div class="product-card">
                                <div class="card  z-depth-4">
                                    <div class="card-image">
                                        
                                        <img src="{{URL::to('/')}}/assets/images/template/{{$template->feature_image}}" width="100%" height="250" alt="product-img">
                                        
                                    </div>
                                    <!--<div class="d-flex  flex-wrap flex-row justify-content-sm-between justify-content-md-between justify-content-lg-between justify-content-xl-between mt-1">-->
                                    <div class="row mt-1 themeviewdiv mb-3">
                                        <div class="col-md-6">
                                            <span class="card-title"><span>{{ Str::limit($template->name, 20) }}</span></span>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="card-action-buttons">
                                                <li style="padding:0px;border:none;margin-bottom:5px;">
                                                    
                                                    <a @if(isset($template->liveurl)) href="{{$template->liveurl}}" @else href="{{route('admin.design.theme.view',$template->id)}}"  @endif class="btn-floating waves-effect waves-light red accent-2 " target="_blank" style="padding:5px 10px;border:1px solid #5e4c4c33">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </li>
                                                <li style="padding:0px;border:none;margin-bottom:5px;">
                                                    <a href="{{route('admin.design.theme.active',$template->id)}}" class="btn-floating waves-effect waves-light " style="padding:5px 11px;border:1px solid #5e4c4c33">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content" style="height:90px;">
                                        <div class="row">
                                            <div class="col s12">
                                                <p style="font-size:15px;padding:0px 10px;line-height:22px;">
                                                    <strong>Description:</strong> <br />
                                                        {{$template->short_description}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                          </div>
                        </div>
                    </div>
                @endif    
                @endforeach
                @endif
                <?php */ ?>
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
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
  
      
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
   
   jQuery(document).ready(function ($) {
  //Buy button effects
  $(".buy").on("click", function () {
    //It is possible to put the 1st argument of setTimeout as callback of the Materialize.toast function but that approach seems significantly slower. I don't know why yet
    setTimeout(function () {
      $("#buy").removeClass("green");
      $(".buy").fadeOut(100, function () {
        $(this).text("add_shopping_cart").fadeIn(150);
      });
    }, 5000);

    $("#buy").addClass("green");
    $(".buy").fadeOut(100, function () {
      $(this).text("check").fadeIn(150);
    });

    var $toastContent = $(
      '<div class="flow-text">ORDERED! &nbsp <a href="#" class=" amber-text">MY CART</a></div>'
    );
    Materialize.toast($toastContent, 5000, "rounded");
  });

  //Like button effects

  $(".like").on("click", function () {
    setTimeout(function () {
      $(".like").fadeOut(100, function () {
        $(this).text("favorite_border").fadeIn(150);
      });
    }, 5000);

    $(".like").fadeOut(100, function () {
      $(this).text("favorite").fadeIn(150);
    });

    var $toastContent2 = $('<div class="flow-text">LIKED!</div>');
    Materialize.toast($toastContent2, 5000, "pink rounded");
  });
});
</script>
@endpush