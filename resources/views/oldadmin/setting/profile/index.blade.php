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
    <section class="container content-main">
            <div class="row">
            <form action="{{route('admin.updateprofile')}}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="index" value="1" id="index">
                            @csrf
                <div class="row">
                <div class="col-lg-9 mt-4 mb-4">
                    <div class="content-header row">
                        <div class="col-md-6">
                            <h2 class="content-title">@if(Session::has('lang') && Session::get('lang')=='bn') প্রোফাইল @else Profile @endif</h2>
                        </div>

                        <div class="col-md-6" style="text-align:right">
                            <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button> -->
                            <!-- <button class="btn btn-info rounded font-sm hover-up">Publich</button> -->
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Basic</h4>
                        </div>
                        @if (Session::has('error_message'))
                            <div class="alert alert-danger" style="color:#fff">{{Session::get('error_message')}}</div>
                        @endif
                        <!--<form action="{{route('admin.updatesetting')}}" method="post" enctype="multipart/form-data">-->
                        <div class="card-body">

                                <div class="mb-4 d-none">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="imgInp1" name="companylogo" accept=".png, .jpg, .jpeg" />
                                            <label for="imgInp1"></label>
                                        </div>
                                        <!--<form runat="server">-->
                                        <!--  <input accept="image/*" type='file' id="imgInp" />-->
                                        <!--  <img id="blah" src="#" alt="your image" />-->
                                        <!--</form>-->
                                        <div class="avatar-preview">
                                            @if(isset($data))
                                            @if(isset($data->logo))
                                            <div id="blah" style="background-image: url(https://admin.ebitans.com/assets/images/img/{{$data->logo}});">
                                            @else
                                            <div id="blah" style="background-image: url(https://cdn-icons-png.flaticon.com/512/149/149071.png);">
                                            @endif
                                            @else
                                            <div id="blah" style="background-image: url(https://cdn-icons-png.flaticon.com/512/149/149071.png);">
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divv" style="text-align:center">
                                        <p style="font-weight:bold">@if(Session::has('lang') && Session::get('lang')=='bn') কোম্পানী লোগো @else Company Logo @endif</p>
                                    </div>
                                    @error('logo')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4 d-none">
                                    <label for="company_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') কোমপানির নাম @else Company Name @endif</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="company_name" name="company_name" value="{{$data->company_name ?? old('company_name')}}">
                                    @error('name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">

                                    <div class="avatar-upload">

                                        <div class="avatar-edit">
                                            <input type='file' id="imgInp" name="userimage" accept=".png, .jpg, .jpeg" onchange="loadFile(event)"/>

                                            <label for="imgInp"></label>
                                        </div>
                                        <div class="avatar-preview" style="overflow:hidden">
                                            @if(isset($data))
                                            @if(isset($data->logo))
                                            <img id="output" src="https://admin.ebitans.com/assets/images/img/{{$user->image}}" style="width:200px;"/>
                                            <!--<div id="blah" style="background-image: url(https://admin.ebitans.com/assets/images/img/{{$user->image}});">-->
                                            @else
                                            <img id="output" src="http://i.pravatar.cc/500?img=7" style="width:200px;"/>
                                            <!--<div id="blah" style="background-image: url(http://i.pravatar.cc/500?img=7);">-->
                                            @endif
                                            @else
                                            <img id="output" src="http://i.pravatar.cc/500?img=7" style="width:200px;"/>
                                            <!--<div id="blah" style="background-image: url(http://i.pravatar.cc/500?img=7);">-->
                                            @endif

                                        </div>
                                    </div>
                                    <div class="divv" style="text-align:center">
                                        <p style="font-weight:bold">@if(Session::has('lang') && Session::get('lang')=='bn') ব্যবহারকারীর ছবি @else User Image @endif</p>
                                    </div>
                                    @error('logo')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="name" name="name" value="{{$user->name ?? old('name')}}">
                                    @error('name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ফোন @else Phone @endif</label>
                                    <input type="tel" placeholder="Type here" class="form-control" id="phone" name="phone" value="{{$user->phone ?? old('phone')}}" readonly>
                                    @error('phone')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ইমেইল @else Email @endif</label>
                                    <input type="email" placeholder="Type here" class="form-control" id="email" name="email" value="{{$user->email ?? old('email')}}">
                                    @error('email')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn') ঠিকানা @else Address @endif </label>
                                    <input type="text" placeholder="Type here" class="form-control" id="address" name="address" value="{{$user->address ?? old('address')}}">
                                    @error('address')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <!-- <label class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label"> Make a template </span>
                                </label> -->

                        </div>
                    </div> <!-- card end// -->
                    <button type="submit" class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn') আপডেট @else Update @endif</</button>

                </div>
                <!--</form>-->
                </div>
                </div>

</form>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script src="{{asset('admin/src/bootstrap-tagsinput.js')}}"></script>
<script src="{{asset('admin/src/bootstrap-tagsinput-angular.js')}}"></script>
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

// var citynames = new Bloodhound({
//   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
//   queryTokenizer: Bloodhound.tokenizers.whitespace,
//   prefetch: {
//     url: 'assets/citynames.json',
//     filter: function(list) {
//       return $.map(list, function(cityname) {
//         return { name: cityname }; });
//     }
//   }
// });
// citynames.initialize();

// $('input').tagsinput({
//     debugger;
//   typeaheadjs: {
//     name: 'citynames',
//     displayKey: 'name',
//     valueKey: 'name',
//     source: citynames.ttAdapter()
//   }
// });
</script>

<script>
    $(document).ready(function(){
        $('#colorrss').hide();
        $('#unittss').hide();
        $('#sizess').hide();
        $('#attributes').on('change', function() {
            var l=this.value;
          if(l=='none'){
              $('#colorrss').hide();
              $('#unittss').hide();
              $('#sizess').hide();
          }else if(l=='color'){
              $('#colorrss').show();
              $('#unittss').hide();
              $('#sizess').hide();
          }else if(l=='unit'){
              $('#colorrss').hide();
              $('#unittss').show();
              $('#sizess').hide();
          }else{
              $('#colorrss').hide();
              $('#unittss').hide();
              $('#sizess').show();
          }
        });
    })
</script>
<script>
$(document).ready(function() {

	$('input[name="input"]').tagsinput({
		trimValue: true,
		confirmKeys: [13, 44, 32],
		focusClass: 'my-focus-class'
	});

	$('.bootstrap-tagsinput input').on('focus', function() {
		$(this).closest('.bootstrap-tagsinput').addClass('has-focus');
	}).on('blur', function() {
		$(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
	});

});



$("#officers-table").on('click', '.remove-officer-button', function(e) {
    var whichtr = $(this).closest("tr");

    // alert('worked'); // Alert does not work
    whichtr.remove();
});
$("#officers-table1").on('click', '.remove-officer-button1', function(e) {
    var whichtr = $(this).closest("tr");

    // alert('worked'); // Alert does not work
    whichtr.remove();
});
function addUnit(){
        var col=$('#new1').html();
        $("#officers-table1 tbody").append('<tr>'+col+'</tr>');
}
function addSize(){
        var col=$('#new2').html();
        $("#officers-table2 tbody").append('<tr>'+col+'</tr>');
}
$("#officers-table2").on('click', '.remove-officer-button2', function(e) {
    var whichtr = $(this).closest("tr");

    // alert('worked'); // Alert does not work
    whichtr.remove();
});
</script>
<script>

jQuery('select[name="category"]').on('change',function(){
        debugger;
        var val = $(this).val();
        console.log(val);
        $('#subcategory').empty();
        var catid=$('select[name="category"]').val();
        $.get('/getsubcat',{catid:catid},function(data){
            console.log(data);
            for (var i = 0; i <data.length; i++) {
            $('#subcategory').append(

                '<option value="">select</option><option value="'+data[i].id+'">'+data[i].name+'</option>'
                );
            }
        });
    });
</script>
@endpush
