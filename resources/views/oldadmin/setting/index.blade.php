@extends('admin.layouts.main')
@push('styles')
<link rel="stylesheet" src="{{asset('admin/src/bootstrap-tagsinput.css')}}" />
@endpush
@section('content')
<style>
.bootstrap-tagsinput {
  width: 100%;
}
.bootstrap-tagsinput {
  background-color: #fff;
  /*border: 1px solid #ccc;*/
  /*box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);*/
  display: inline-block;
  padding: 4px 6px;
  color: #555;
  vertical-align: middle;
  border-radius: 4px;
  max-width: 100%;
  line-height: 22px;
  cursor: text;
}
.bootstrap-tagsinput .tag {
  margin-right: 2px;
  color: white;
}
.label-info {
  background-color: #5bc0de;
}
.label {
  display: inline;
  padding: .2em .6em .3em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  color: #fff;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: .25em;
}
.bootstrap-tagsinput .tag [data-role="remove"] {
  margin-left: 8px;
  cursor: pointer;
}
.bootstrap-tagsinput .tag [data-role="remove"]::after {
  content: "x";
  padding: 0px 2px;
}
.bootstrap-tagsinput .tag [data-role="remove"] {
  cursor: pointer;
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
.size{
    list-style-type:none;

}
.size li{
    float:left;
}
.bootstrap-tagsinput {
	margin: 0;
	width: 100%;
	padding: 0.5rem 0.75rem 0;
	font-size: 1rem;
  line-height: 1.25;
	transition: border-color 0.15s ease-in-out;

	&.has-focus {
    background-color: #fff;
    border-color: #5cb3fd;
	}

	.label-info {
		display: inline-block;
		background-color: #636c72;
		padding: 0 .4em .15em;
		border-radius: .25rem;
		margin-bottom: 0.4em;
	}

	input {
		margin-bottom: 0.5em;
	}
}
.bootstrap-tagsinput .tag [data-role="remove"]:after {
	content: '\00d7';
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
@media only screen and (max-width:768px){
    .modal {
      position: fixed;
      top: 0;
      left: 0% !important;
      z-index: 1050;
      display: none;
      width: 100%;
      height: 100%;
      overflow-x: hidden;
      overflow-y: auto;
      outline: 0;
    }

}
@media only screen and (max-width:1024px) and (min-width:769px){
    .modal {
      position: fixed;
      top: 0;
      left: 10% !important;
      z-index: 1050;
      display: none;
      width: 100%;
      height: 100%;
      overflow-x: hidden;
      overflow-y: auto;
      outline: 0;
    }

}
@media only screen and (max-width:1440px) and (min-width:1025px){
    .modal {
      position: fixed;
      top: 0;
      left: 20% !important;
      z-index: 1050;
      display: none;
      width: 100%;
      height: 100%;
      overflow-x: hidden;
      overflow-y: auto;
      outline: 0;
    }

}
.modal {
  position: fixed;
  top: 0;
  left: 34%;
  z-index: 1050;
  display: none;
  width: 100%;
  height: 100%;
  overflow-x: hidden;
  overflow-y: auto;
  outline: 0;
}
.test:hover .modal{
    display:block !important;
}
.show{
    background-color:transparent;
    opacity:1;
}
</style>


<?php
if (Auth::user()->type == "admin") {
    $customer = DB::table("customers")
        ->where("uid", Auth::user()->id)
        ->first();
    $store_id = $customer->active_store;
} elseif (Auth::user()->type == "staff") {
    $staff = DB::table("staff")
        ->where("uid", Auth::user()->id)
        ->first();
    $store_id = $staff->store_id;
    $role = DB::table("roles")
        ->where("id", $staff->role_id)
        ->first();
    if (isset($role)) {
        $permission = explode(",", $role->permission);
        foreach ($permission as $key => $pr) {
            if ($pr == "branch") {
                $branch = 1;
            } elseif ($pr == "product") {
                $product = 1;
            } elseif ($pr == "category") {
                $category = 1;
            } elseif ($pr == "subcategory") {
                $subcategory = 1;
            } elseif ($pr == "brand") {
                $brand = 1;
            } elseif ($pr == "attribute") {
                $attribute = 1;
            } elseif ($pr == "supplier") {
                $supplier = 1;
            } elseif ($pr == "collection") {
                $collection = 1;
            } elseif ($pr == "global_tab") {
                $global_tab = 1;
            } elseif ($pr == "coupon") {
                $coupon = 1;
            } elseif ($pr == "campaign") {
                $campaign = 1;
            } elseif ($pr == "offer") {
                $offer = 1;
            } elseif ($pr == "slider") {
                $slider = 1;
            } elseif ($pr == "banner") {
                $banner = 1;
            } elseif ($pr == "layouts") {
                $layouts = 1;
            } elseif ($pr == "template") {
                $template = 1;
            } elseif ($pr == "header") {
                $header = 1;
            } elseif ($pr == "homepage") {
                $homepage = 1;
            } elseif ($pr == "footer") {
                $footer = 1;
            } elseif ($pr == "mobilemenu") {
                $mobilemenu = 1;
            } elseif ($pr == "product_display") {
                $product_display = 1;
            } elseif ($pr == "product_grid") {
                $product_grid = 1;
            } elseif ($pr == "shop_page") {
                $shop_page = 1;
            } elseif ($pr == "pages") {
                $pages = 1;
            } elseif ($pr == "customer") {
                $customer = 1;
            } elseif ($pr == "staff") {
                $staff = 1;
            } elseif ($pr == "invoice") {
                $invoice = 1;
            } elseif ($pr == "setting") {
                $setting = 1;
            } elseif ($pr == "role_permission") {
                $role_permission = 1;
            } elseif ($pr == "pos") {
                $pos = 1;
            } elseif ($pr == "testimonials") {
                $tt = 1;
            } elseif ($pr == "theme_customize") {
                $theme_customize = 1;
            } elseif ($pr == "activity_log") {
                $activity_log = 1;
            } elseif ($pr == "inventory") {
                $inventory = 1;
            } else {
            }
        }
    }
} else {
    $store_id = 0;
}
if ($store_id != 0) {
    $store = DB::table("stores")
        ->where("id", $store_id)
        ->first();
    if ($store->plan_id != "NULL") {
        if ($store->expiry_date <= Carbon\Carbon::now()) {
            if (isset($store->pos_plan_id)) {
                if ($store->pos_plan_expiry_date <= Carbon\Carbon::now()) {
                    $exp = 1;
                } else {
                    $exp = 0;
                }
            } else {
                $exp = 1;
            }
        } else {
            $exp = 0;
        }
    } else {
        if (
            isset($store->pos_plan_id) &&
            $store->pos_plan_expiry_date >= Carbon\Carbon::now()
        ) {
            $posplan = 1;
            $exp = 1;
        } else {
            $posplan = null;
            $exp = 0;
        }
        if (
            isset($store->digital_plan_id) &&
            Carbon\Carbon::parse($store->digital_plan_end_date) >=
                Carbon\Carbon::now()
        ) {
            $digitalplan = 1;
        } else {
            $digitalplan = null;
        }
    }
}
if (
    isset($store->pos_plan_id) &&
    $store->pos_plan_expiry_date >= Carbon\Carbon::now()
) {
    $posplan = 1;
} else {
    $posplan = null;
}
if (
    isset($store->digital_plan_id) &&
    Carbon\Carbon::parse($store->digital_plan_end_date) >= Carbon\Carbon::now()
) {
    $digitalplan = 1;
    $dexp = 0;
} else {
    $digitalplan = null;
    $dexp = 1;
}
?>
@if(Auth::user()->type=='staff')
<?php
$stafff = DB::table("staff")
    ->where("uid", Auth::user()->id)
    ->first();
if (isset($stafff)) {
    if (isset($stafff->pos)) {
        $staff_pos = 1;
    } else {
        $staff_pos = 0;
    }
} else {
    $staff_pos = 0;
}
?>
@endif
<?php if (Auth::user()->type == "superstaff") {
    $superstaff = DB::table("superstaffs")
        ->where("uid", Auth::user()->id)
        ->first();
    $superrole = DB::table("superroles")
        ->where("id", $superstaff->role_id)
        ->first();
    $permissionss = explode(",", $superrole->permission);
    foreach ($permissionss as $key => $prs) {
        if ($prs == "branch_delete_request") {
            $branch_delete_request = 1;
        } elseif ($prs == "customer") {
            $customers = 1;
        } elseif ($prs == "domain") {
            $domain = 1;
        } elseif ($prs == "domain_request") {
            $domain_request = 1;
        } elseif ($prs == "design") {
            $design = 1;
        } elseif ($prs == "template") {
            $templatess = 1;
        } elseif ($prs == "order") {
            $order = 1;
        } elseif ($prs == "reports") {
            $reports = 1;
        } elseif ($prs == "review") {
            $review = 1;
        } elseif ($prs == "staff") {
            $staff = 1;
        } elseif ($prs == "role_and_permission") {
            $role_and_permission = 1;
        } elseif ($prs == "clients") {
            $clients = 1;
        } elseif ($prs == "plan_order") {
            $plan_order = 1;
        } elseif ($prs == "plans") {
            $plans = 1;
        } elseif ($prs == "notification") {
            $notification = 1;
        } elseif ($prs == "message") {
            $messages = 1;
        } else {
        }
    }
} ?>



<main class="main-content position-relative h-100 border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                     <li class="breadcrumb-item">
                        <a href="{{route('admin.profile')}}">
                            <img src="{{URL::to('/')}}/img/icons/resume.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') প্রোফাইল @else User Profile @endif</span>
                        </a>
                    </li>

                     @if(isset($exp) && $exp != '1')
                        <li class="breadcrumb-item active">
                            <a href="@if(isset($exp)) @if($exp=='1') # @else {{route('admin.setting')}} @endif @endif">
                                <img src="{{URL::to('/')}}/img/icons/settings-2.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ওয়েবসাইট সেটিংস @else Website Settings @endif</span>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="@if(isset($exp)) @if($exp=='1') # @else {{route('admin.domain')}} @endif @endif">
                                <img src="{{URL::to('/')}}/img/icons/domain-2.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ডোমেইন সংযোগ করুন @else Connect Domain @endif</span>
                            </a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
<!----Modal 1 Start---->
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color:transparent !important">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header " style="text-align:right;padding: 0px 12px;">
            <span class="button btn btn-primary"  id="closemodal" data-dismiss="modal" aria-label="Close" style="margin-left:auto;background-color:transparent !important;color:black;font-size:15px;padding:2px 10px !important;margin-top:15px;">X</span>
        </div>
        <div class="modal-body">
<p>
What is Lorem Ipsum?

Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
Why do we use it?

It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!----Modal 1 End---->
<!----Modal 2 Start---->
<div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header " style="text-align:right;padding: 0px 12px;">
            <span class="button btn btn-primary" id="closemodal1" data-dismiss="modal" aria-label="Close" style="margin-left:auto;background-color:transparent !important;color:black;font-size:15px;padding:2px 10px !important;margin-top:15px;">X</span>
        </div>
        <div class="modal-body">
            <p>
          Modal 2
What is Lorem Ipsum?

Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
Why do we use it?

It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!----Modal 2 End---->
    <section class="container content-main">
            <div class="row">
            <form action="{{route('admin.updatesetting')}}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="index" value="1" id="index">
                            @csrf
                <div class="row">
                <div class="col-lg-9 mt-4 mb-4">
                    <div class="content-header row">
                        <div class="col-md-6">
                            <h2 class="content-title">@if(Session::has('lang') && Session::get('lang')=='bn') সেটিংস @else Settings @endif </h2>
                        </div>

                        <div class="col-md-6" style="text-align:right">
                            <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button> -->
                            <!-- <button class="btn btn-info rounded font-sm hover-up">Publich</button> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-3"></div>

                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') মৌলিক @else Basic @endif</h4>
                        </div>
                        @if (Session::has('error_message'))
                            <div class="alert alert-danger" style="color:#fff">{{Session::get('error_message')}}</div>
                        @endif
                        <!--<form action="{{route('admin.updatesetting')}}" method="post" enctype="multipart/form-data">-->
                        <div class="card-body">

                                <div class="mb-4">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="imageUpload" name="logo" accept=".png, .jpg, .jpeg" onchange="loadFile(event)"/>
                                            <label for="imageUpload"></label>
                                        </div>
                                        <div class="avatar-preview" style="overflow:hidden">
                                            @if(isset($data))
                                            @if(isset($data->logo))
                                            <img id="output" src="https://admin.ebitans.com/assets/images/setting/{{$data->logo}}" style="width:200px;"/>
                                            <!--<div id="imagePreview" style="background-image: url(https://admin.ebitans.com/assets/images/setting/{{$data->logo}});">-->
                                            @else
                                            <img id="output" src="https://cdn-icons-png.flaticon.com/512/149/149071.png" style="width:200px;"/>
                                            <!--<div id="imagePreview" style="background-image: url(http://i.pravatar.cc/500?img=7);">-->
                                            @endif
                                            @else
                                            <img id="output" src="https://cdn-icons-png.flaticon.com/512/149/149071.png" style="width:200px;"/>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="divv" style="text-align:center">
                                        <p style="font-weight:bold"> @if(Session::has('lang') && Session::get('lang')=='bn') লোগো @else Logo @endif</p>
                                    </div>
                                    @error('logo')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="name" name="name" value="{{$data->website_name ?? old('website_name')}}">
                                    @error('name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ছোট বিবরণ @else Short Description @endif</label>
                                    <textarea class="form-control" name="short_description" id="short_description" rows="4">{{$data->short_description ?? old('short_description')}}</textarea>
                                    @error('short_description')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') টাইপ @else Type @endif</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="type" name="type" value="{{$store->type ?? old('type')}}" readonly>
                                    @error('type')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') সক্রিয় পরিকল্পনা @else Active Plan @endif</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="activeplan" name="activeplan" value="{{$store->plan_id ?? old('plan_id')}}" readonly>
                                    @error('activeplan')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ফোন @else Phone @endif</label>
                                    <input type="tel" placeholder="Type here" class="form-control" id="phone" name="phone" value="{{$data->phone ?? old('phone')}}">
                                    @error('phone')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ইমেইল @else Email @endif</label>
                                    <input type="email" placeholder="Type here" class="form-control" id="email" name="email" value="{{$data->email ?? old('email')}}">
                                    @error('email')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ঠিকানা @else Address @endif</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="address" name="address" value="{{$data->address ?? old('address')}}">
                                    @error('address')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="test">@if(Session::has('lang') && Session::get('lang')=='bn') সামাজিক লিঙ্ক @else Social Link @endif</h4>
                                </div>
                                <div class="card-body">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ফেসবুক লিঙ্ক @else Facebook Link @endif<a target="_blank" href="#" title="Hi, I'm a tooltip thingy. Please add your name in the textbox to the right of me, thanks! "><img src="https://shots.jotform.com/kade/Screenshots/blue_question_mark.png" height="13px" style="padding-bottom:3px"/></a></label>
                                    <input type="text" placeholder="Type here" class="form-control" id="facebook_link" name="facebook_link" value="{{$data->facebook_link ?? old('facebook_link')}}">
                                    @error('facebook_link')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ইনস্টাগ্রাম লিঙ্ক @else Instagram Link @endif<a href="javascript:void"  data-toggle="modal" data-target="#modal1"><img id="test" src="https://shots.jotform.com/kade/Screenshots/blue_question_mark.png" height="13px" style="padding-bottom:3px"/></a></label>
                                    <input type="text" placeholder="Type here" class="form-control" id="instagram_link" name="instagram_link" value="{{$data->instagram_link ?? old('instagram_link')}}">
                                    @error('instagram_link')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ইউটিউব লিংক @else Youtube Link @endif <a href="javascript:void"  data-toggle="modal" data-target="#modal2"><img id="test" src="https://shots.jotform.com/kade/Screenshots/blue_question_mark.png" height="13px" style="padding-bottom:3px"/></a></label>
                                    <input type="text" placeholder="Type here" class="form-control" id="youtube_link" name="youtube_link" value="{{$data->youtube_link ?? old('youtube_link')}}">
                                    @error('youtube_link')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') মেসেঞ্জার লিঙ্ক @else Messenger Link @endif</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="messenger_link" name="messenger_link" value="{{$data->messenger_link ?? old('messenger_link')}}">
                                    @error('messenger_link')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') হোয়াটসঅ্যাপ ফোন @else Whats app phone @endif</label>
                                    <input type="tel" placeholder="Type here" class="form-control" id="whatsapp_phone" name="whatsapp_phone" value="{{$data->whatsapp_phone ?? old('whatsapp_phone')}}">
                                    @error('whatsapp_phone')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                </div>
                                <!-- <label class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label"> Make a template </span>
                                </label> -->

                        </div>
                    </div> <!-- card end// -->
                    <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') শিপিং ও ট্যাক্স @else Shipping & Tax @endif</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ট্যাক্স @else Tax @endif (%)</label>
                                    <input type="number" placeholder="Type here" class="form-control" id="tax" name="tax" value="{{$data->tax ?? old('tax')}}">
                                    @error('tax')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4 row">
                                <div class="col-6 mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শিপিং এরিয়া  @else Shipping Area @endif 1</label>
                                    <input type="text" placeholder="Ex. Inside Dhaka / Outside Dhaka" class="form-control" id="shipping_area_1" name="shipping_area_1" value="{{$data->shipping_area_1 ?? old('shipping_area_1')}}">
                                    @error('shipping_area_1')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="col-6 mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') মূল্য  @else Cost @endif (৳)</label>
                                    <input type="number" placeholder="Type here" class="form-control" id="shipping_area_1_cost" name="shipping_area_1_cost" value="{{$data->shipping_area_1_cost ?? old('shipping_area_1_cost')}}">
                                    @error('shipping_area_1_cost')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4 row">
                                <div class="col-6 mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শিপিং এরিয়া @else Shipping Area @endif 2</label>
                                    <input type="text" placeholder="Ex. Inside Dhaka / Outside Dhaka" class="form-control" id="shipping_area_2" name="shipping_area_2" value="{{$data->shipping_area_2 ?? old('shipping_area_2')}}">
                                    @error('shipping_area_2')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="col-6 mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') মূল্য @else Cost @endif (৳)</label>
                                    <input type="number" placeholder="Type here" class="form-control" id="shipping_area_2_cost" name="shipping_area_2_cost" value="{{$data->shipping_area_2_cost ?? old('shipping_area_2_cost')}}">
                                    @error('shipping_area_2_cost')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4 row">
                                <div class="col-6 mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') শিপিং এরিয়া  @else Shipping Area @endif 3</label>
                                    <input type="text" placeholder="Ex. Inside Dhaka / Outside Dhaka" class="form-control" id="shipping_area_3" name="shipping_area_3" value="{{$data->shipping_area_3 ?? old('shipping_area_3')}}">
                                    @error('shipping_area_3')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="col-6 mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') মূল্য @else Cost @endif (৳)</label>
                                    <input type="number" placeholder="Type here" class="form-control" id="shipping_area_3_cost" name="shipping_area_3_cost" value="{{$data->shipping_area_3_cost ?? old('shipping_area_3_cost')}}">
                                    @error('shipping_area_3_cost')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label"><input type="checkbox" placeholder="Type here"  id="cod" name="cod" value="{{$data->cod ?? old('cod')}}"@if(isset($data->cod) && $data->cod=='active') checked @endif> &nbsp;&nbsp;Cash On Delivery </label>

                                    @error('cod')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label"> <input type="checkbox" placeholder="Type here" id="online" name="online" value="{{$data->online ?? old('online')}}" @if(isset($data->online) && $data->online=='active') checked @endif>&nbsp;&nbsp;Online Payment</label>

                                    @error('online')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div> <!-- card end// -->
                    </div>
                    </div>
                    <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif</button>

                </div>

                </div>
                <!--</form>-->

</form>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<!--<script src="{{asset('admin/src/bootstrap-tagsinput.js')}}"></script>-->
<!--<script src="{{asset('admin/src/bootstrap-tagsinput-angular.js')}}"></script>-->
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
$('#closemodal').click(function() {
    $('#modal1').modal('hide');
});
$('#closemodal1').click(function() {
    $('#modal2').modal('hide');
});
$(function() {

  $('[data-toggle="modal"]').hover(function() {
    var modalId = $(this).data('target');
    $(modalId).modal('show');
    $(modalId).css({ opacity: 1 });

  });

});
// $(document).ready(function(){
//     $( "#test" ).hover(function() {
//           $('#exampleModalScrollable').modal({
//         show: true
//     });
//   });
// });

// $("#b1").hover(function () {
//     $('#modal1').modal({
//         show: true,
//         backdrop: false
//     })
// });
</script>

@endpush
