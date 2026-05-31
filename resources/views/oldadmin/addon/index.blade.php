@extends('admin.layouts.main')
@section('content')
<style>
    .avatar-upload {
    position: relative;
    /*max-width: 205px;*/
    /*margin: 20px auto;*/
}
    .avatar-edit {
        position: absolute;
        /*right: 12px;*/
        margin-left:135px;
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
.hh-grayBox {
	background-color: #F8F8F8;
	margin-bottom: 20px;
	padding: 35px;
  margin-top: 20px;
}
.pt45{padding-top:45px;}
.order-tracking{
	text-align: center;
	width: 25%;
	position: relative;
	display: block;
}
.order-tracking .is-complete{
	display: block;
	position: relative;
	border-radius: 50%;
	height: 30px;
	width: 30px;
	border: 0px solid #AFAFAF;
	background-color: #f7be16;
	margin: 0 auto;
	transition: background 0.25s linear;
	-webkit-transition: background 0.25s linear;
	z-index: 2;
}
.order-tracking .is-complete:after {
	display: block;
	position: absolute;
	content: '';
	height: 14px;
	width: 7px;
	top: -2px;
	bottom: 0;
	left: 5px;
	margin: auto 0;
	border: 0px solid #AFAFAF;
	border-width: 0px 2px 2px 0;
	transform: rotate(45deg);
	opacity: 0;
}
.order-tracking.completed .is-complete{
	border-color: #27aa80;
	border-width: 0px;
	background-color: #27aa80;
}
.order-tracking.completed .is-complete:after {
	border-color: #fff;
	border-width: 0px 3px 3px 0;
	width: 7px;
	left: 11px;
	opacity: 1;
}
.order-tracking p {
	color: #A4A4A4;
	font-size: 16px;
	margin-top: 8px;
	margin-bottom: 0;
	line-height: 20px;
}
.order-tracking p span{font-size: 14px;}
.order-tracking.completed p{color: #000;}
.order-tracking::before {
	content: '';
	display: block;
	height: 3px;
	width: calc(100% - 40px);
	background-color: #f7be16;
	top: 13px;
	position: absolute;
	left: calc(-50% + 20px);
	z-index: 0;
}
.order-tracking:first-child:before{display: none;}
.order-tracking.completed:before{background-color: #27aa80;}
</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.themecustomize')}}">
                            <img src="{{URL::to('/')}}/img/icons/color-scheme.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') থিম কাস্টমাইজ করুন @else Theme Customization @endif</span>
                        </a>
                    </li>
                    
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.addonss')}}">
                            <img src="{{URL::to('/')}}/img/icons/ecommerce.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ই-কমার্স মোবাইল অ্যাপ @else E-Commerce Mobile App @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item ">
                        <a href="{{route('admin.websitesetup')}}">
                            <img src="{{URL::to('/')}}/img/icons/ecommerce.png"> <br> <span class="nav-link-text ms-1">Website Setup</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item ">
                        <a href="{{route('admin.paymentgateway')}}">
                            <img src="{{URL::to('/')}}/img/icons/ecommerce.png"> <br> <span class="nav-link-text ms-1">Payment Gateway</span>
                        </a>
                    </li>
                    <?php
                    $act=DB::table('activities')->where('store_id',$store_id)->whereDate('expiry_date','>=',Carbon\Carbon::now())->first();
                    ?>
                    @if(isset($act))
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.activitylog')}}">
                            <img src="{{URL::to('/')}}/img/icons/ecommerce.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') কার্য বিবরণ @else Activity Log @endif</span>
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
            <h4>Mobile Apps</h4>
        </div>
        <div class="col-md-6">
            <!--<ul>-->
            <!--    <li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
            <!--    <li style="padding:0px;border:0px;"><a href="javascript:void(0)" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>-->
            <!--</ul>-->
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
        @if($view=="Active")
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
            <form action="{{route('admin.savemobileappsinfo',$mobileapp->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 row">
                    <label for="staticEmail" class="col-md-2 col-form-label">Apps Name</label>
                    <div class="col-md-4">
                    <input type="text" class="form-control" id="staticEmail" name="name" value="{{$mobileapp->name ?? ""}}" required>
                        @error('name')
                            <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row avatar-upload">
                    <label for="banner" class="col-md-2 col-form-label">Apps Icon</label>
                    <div class="col-md-4 avatarmobile">
                        <div class="avatar-edit">
                            <input type='file' id="imageUpload" name="logo" accept=".png, .jpg, .jpeg" onchange="loadFile(event)"/>
                            <label for="imageUpload"></label>
                        </div>
                        <div class="avatar-preview" style="overflow:hidden">
                            @if(isset($mobileapp))
                            @if(isset($mobileapp->image))
                            <img id="output" src="https://admin.ebitans.com/assets/images/category/{{$mobileapp->image}}" style="width:200px;"/>
                            @else
                            <img id="output" src="http://i.pravatar.cc/500?img=7" style="width:200px;"/>
                            <!--<div id="imagePreview" style="background-image: url(http://i.pravatar.cc/500?img=7);">-->
                            @endif
                            @else
                            <img id="output" src="http://i.pravatar.cc/500?img=7" style="width:200px;"/>
                            @endif
                            
                        </div>
                    </div>
                </div>
                <!--<div class="mb-3 row">-->
                <!--    <label for="banner" class="col-md-2 col-form-label">Icon</label>-->
                <!--    <div class="col-md-4">-->
                <!--    <img src="{{URL::to('/')}}/assets/images/category/{{$mobileapp->image}}" width="150px" style="margin-bottom:10px;">-->
                <!--    <br>-->
                <!--    <input type="file" class="form-control" id="banner" name="logo" required>-->
                <!--    @error('logo')-->
                <!--            <p class="text-danger" role="alert">{{$message}}</p>-->
                <!--    @enderror-->
                <!--    </div>-->
                <!--</div>-->
                @if(isset($mobileapp->name) && isset($mobileapp->image))
                    <div class="container">
                        <div class="row">
    						<div class="col-12 col-md-10 hh-grayBox pt45 pb20">
    							<div class="row justify-content-between" style="overflow:hidden">
    								<div class="order-tracking @if($mobileapp->status=='Request Send') completed @elseif($mobileapp->status=='Payment Verified') completed @elseif($mobileapp->status=='Processing') completed @elseif($mobileapp->status=='Download') completed @endif">
    									<span class="is-complete"></span>
    									<p>Request Send<br>
    									<!--<span>Mon, June 24</span>-->
    									</p>
    								</div>
    								<div class="order-tracking @if($mobileapp->status=='Payment Verified') completed @elseif($mobileapp->status=='Processing') completed @elseif($mobileapp->status=='Download') completed @endif">
    									<span class="is-complete"></span>
    									<p>Payment Veryfied<br>
    									<!--<span>Tue, June 25</span>-->
    									</p>
    								</div>
    								<div class="order-tracking @if($mobileapp->status=='Download') completed @elseif($mobileapp->status=='Processing') completed @endif">
    									<span class="is-complete"></span>
    									<p>Processing<br>
    									<!--<span>Fri, June 28</span>-->
    									</p>
    								</div>
    								<div class="order-tracking @if($mobileapp->status=='Download') completed @endif">
    									<span class="is-complete"></span>
    									<p>Download App APK<br>
    									@if($mobileapp->status=='Download')
    									<a href="@if(isset($mobileapp->url)) {{$mobileapp->url}} @else javascript:void(0) @endif" class="btn btn-primary mt-2" download>Download</a>
    									@endif
    									<!--<span>Fri, June 28</span>-->
    									</p>
    								</div>
    							</div>
    						</div>
        				</div>
                    </div>
                @else
                <div class="mb-3 row">
                    <label for="position" class="col-md-2 col-form-label"></label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
                @endif
                </form>
            </div>
        </div>
        @else
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <h4 class="text-center">You Don't Have Any Active Addons </h4>
                </div>
            </div>
        @endif

        </div>
    </div>
</div>
</main>
@endsection
@push('scripts')
<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>
<script>
    $(document).ready(function(){
      $("#taskfilter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#taskfilterresult tbody tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
   
    });
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
</script>
@endpush