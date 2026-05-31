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
/*.is-focused{*/
/*    background-color:red !important;*/
/*    width:60%;*/
/*    padding:10px 0px;*/
/*}*/
input[type=radio]{
    opacity:1;
}
.headerimg{
    width:80%;
    height:150px;
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
                    <li class="breadcrumb-item active" aria-current="page">
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
                <li  style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.homepage.slider')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') স্লাইডার @else Slider @endif</a></li>
                <li class="active" style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.homepage.banner')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') ব্যানার @else Banner @endif</a></li>
                <li  style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.homepage.featurecategory')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') বৈশিষ্ট্য বিভাগ @else Feature Category @endif</a></li>
                <li  style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.homepage.product')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') পণ্য @else Product @endif</a></li>
                <li  style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.homepage.featureproduct')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') বৈশিষ্ট্য পণ্য @else Feature Product @endif</a></li>
                <li  style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.homepage.bestsellproduct')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') সেরা বিক্রয় পণ্য @else Best Sell Product @endif</a></li>
                <li  style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.homepage.recentaddproduct')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') নতুন আগমন পণ্য @else New Arrival Product @endif</a></li>
                <li  style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.homepage.testimonial')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') প্রশংসাপত্র @else Testimonial @endif</a></li>
                <li  style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a href="{{route('admin.design.homepage.footer')}}" style="display:block">@if(Session::has('lang') && Session::get('lang')=='bn') ফুটার @else Footer @endif</a></li>
            </ul>
        </div>
        <div class="col-md-9 rightmenu mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn') সব স্লাইডার @else  All Banner @endif</h4>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="col-md-3">
                                    <form action="{{route('admin.design.homepage.bannerfilter')}}" method="get">
                                    <select class="form-control" name="categoryfilter" id="category" onchange="this.form.submit()">
                                        <option value="all" @if(isset($stts) && $stts=='all') selected @endif>All Design Category</option>
                                        <option value="Fashion" @if(isset($stts) && $stts=='Fashion') selected @endif>Fashion</option>
                                        <option value="Grocery" @if(isset($stts) && $stts=='Grocery') selected @endif>Grocery</option>
                                        <option value="Medical Equipment" @if(isset($stts) && $stts=='Medical Equipment') selected @endif>Medical Equipment</option>
                                        <option value="Furniture" @if(isset($stts) && $stts=='Furniture') selected @endif>Furniture</option>
                                        <option value="Gadget" @if(isset($stts) && $stts=='Gadget') selected @endif>Gadget</option>
                                        <option value="Gym & Sports" @if(isset($stts) && $stts=='Gym & Sports') selected @endif>Gym & Sports</option>
                                        <option value="Pet Animals" @if(isset($stts) && $stts=='Pet Animals') selected @endif>Pet Animals</option>
                                        <option value="Seasonal" @if(isset($stts) && $stts=='Seasonal') selected @endif>Seasonal</option>
                                        <option value="Electronics" @if(isset($stts) && $stts=='Electronics') selected @endif>Electronics</option>
                                        <option value="Gift" @if(isset($stts) && $stts=='Gift') selected @endif>Gift</option>
                                        <option value="Flowers" @if(isset($stts) && $stts=='Flowers') selected @endif>Flowers</option>
                                        <option value="Books" @if(isset($stts) && $stts=='Books') selected @endif>Books</option>
                                        <option value="Vehicle" @if(isset($stts) && $stts=='Vehicle') selected @endif>Vehicle</option>
                                    </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                        @if (Session::has('success_message'))
                            <div class="alert alert-success">{{Session::get('success_message')}}</div>
                        @endif
                            <!--<form action="{{route('admin.savebanner1')}}" method="post">-->
                                @csrf
                                <?php $header1=DB::table('designs')->where('store_id',$store_id)->where('banner','null')->first();  ?>
                                <div class="form-group">
                                    <input type="radio" id="Homes" class="changebanner" name="banner" @if(isset($header1)) checked @endif value="null">&nbsp;&nbsp;&nbsp;
                                    <label for="Homes"> None </label>  &nbsp;&nbsp;&nbsp;
                                </div>
                                @if(isset($design) && count($design)>0)
                                @foreach($design as $key=>$dsg)
                                <?php $header1=DB::table('designs')->where('store_id',$store_id)->where('banner',$dsg->value)->first();  ?>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-xl">
                                    <div class="modal-content" style="background-color:transparent;border:0px">
                                      
                                      <div class="modal-body" style="border:none">
                                         @if(isset($dsg->image))
                                            <img src="{{URL::to('/')}}/assets/images/design/{{$dsg->image}}"  class="img-fluid" alt="" style="padding:10px;border:0px solid gray;transition-delay: 5s;"> 
                                            @endif
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <input type="radio" id="Home{{$key}}" name="banner" class="changebanner" @if(isset($header1)) checked @endif value="{{$dsg->value}}">&nbsp;&nbsp;&nbsp;
                                    <label for="Home{{$key}}"> {{$dsg->name}}</label>  &nbsp;&nbsp;&nbsp;
                                    <span data-bs-toggle="modal" data-bs-target="#exampleModal{{$key}}"> <i class="fa fa-eye" aria-hidden="true"></i> </span>
                                </div>
                                @endforeach
                                @endif
                                <!--<button type="submit" class="btn btn-info mt-3">Submit</button>-->
                            <!--</form>-->
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
        $(".changebanner").on("click",function(){
            $url="/changebanner";
            var value=$(this).val();
            console.log(value);
            $.get($url,{value:value}, function(data){
               console.log(data); 
               toastr.success('Banner Design Successfully', 'Success');
            });
        });
    });
</script>
<script>
    var myModal = document.getElementById('myModal')
var myInput = document.getElementById('myInput')

myModal.addEventListener('shown.bs.modal', function () {
  myInput.focus()
})
</script>
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