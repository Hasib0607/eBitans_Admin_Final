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
    .badge{display:inline-block;padding:.25em .4em;font-size:75%;font-weight:700;line-height:1;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25rem;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out}
.badge-primary{color:#fff;background-color:#007bff}a.badge-primary:focus,a.badge-primary:hover{color:#fff;background-color:#0062cc}a.badge-primary.focus,a.badge-primary:focus{outline:0;box-shadow:0 0 0 .2rem rgba(0,123,255,.5)}.badge-secondary{color:#fff;background-color:#6c757d}a.badge-secondary:focus,a.badge-secondary:hover{color:#fff;background-color:#545b62}a.badge-secondary.focus,a.badge-secondary:focus{outline:0;box-shadow:0 0 0 .2rem rgba(108,117,125,.5)}.badge-success{color:#fff;background-color:#28a745}a.badge-success:focus,a.badge-success:hover{color:#fff;background-color:#1e7e34}a.badge-success.focus,a.badge-success:focus{outline:0;box-shadow:0 0 0 .2rem rgba(40,167,69,.5)}.badge-info{color:#fff;background-color:#17a2b8}a.badge-info:focus,a.badge-info:hover{color:#fff;background-color:#117a8b}a.badge-info.focus,a.badge-info:focus{outline:0;box-shadow:0 0 0 .2rem rgba(23,162,184,.5)}.badge-warning{color:#212529;background-color:#ffc107}a.badge-warning:focus,a.badge-warning:hover{color:#212529;background-color:#d39e00}a.badge-warning.focus,a.badge-warning:focus{outline:0;box-shadow:0 0 0 .2rem rgba(255,193,7,.5)}.badge-danger{color:#fff;background-color:#dc3545}a.badge-danger:focus,a.badge-danger:hover{color:#fff;background-color:#bd2130}a.badge-danger.focus,a.badge-danger:focus{outline:0;box-shadow:0 0 0 .2rem rgba(220,53,69,.5)}.badge-light{color:#212529;background-color:#f8f9fa}a.badge-light:focus,a.badge-light:hover{color:#212529;background-color:#dae0e5}a.badge-light.focus,a.badge-light:focus{outline:0;box-shadow:0 0 0 .2rem rgba(248,249,250,.5)}.badge-dark{color:#fff;background-color:#343a40}a.badge-dark:focus,a.badge-dark:hover{color:#fff;background-color:#1d2124}a.badge-dark.focus,a.badge-dark:focus{outline:0;box-shadow:0 0 0 .2rem rgba(52,58,64,.5)}.jumbotron{padding:2rem 1rem;margin-bottom:2rem;background-color:#e9ecef;border-radius:.3rem}@media (min-width:576px){.jumbotron{padding:4rem 2rem}}.jumbotron-fluid{padding-right:0;padding-left:0;border-radius:0}.alert{position:relative;padding:.75rem 1.25rem;margin-bottom:1rem;border:1px solid transparent;border-radius:.25rem}

</style>

<!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
<form action="{{route('admin.savedomain')}}" method="post">
    @csrf
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Connect Domain</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="input-group input-group-outline my-3">
            <label class="form-label">Domain Name</label>
            <input name="domain" type="text" class="form-control">
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">

        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="Submit" class="btn btn-info" >Submit</button>
      </div>
</form>
    </div>
  </div>
</div>







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
                <div class="row">
                <div class="col-lg-9 mt-4 mb-4">
                    <div class="content-header row">
                        <div class="col-md-6">
                            <!--<h2 class="content-title">Settings </h2>-->
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
                            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') একটি ডোমেন সংযুক্ত করুন @else Connect a Domain @endif</h4>
                        </div>
                        @if (Session::has('error_message'))
                            <div class="alert alert-danger" style="color:#fff">{{Session::get('error_message')}}</div>
                        @endif
                        <!--<form action="{{route('admin.updatesetting')}}" method="post" enctype="multipart/form-data">-->
                        <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">

                                <p>@if(Session::has('lang') && Session::get('lang')=='bn') আপনার দোকানের জন্য নিখুঁত ডোমেন সুরক্ষিত করুন যা গ্রাহকরা বিশ্বাস করতে পারেন এবং সহজেই খুঁজে পেতে পারেন। ebitans থেকে একটি নতুন ডোমেন কিনুন, অথবা Google ডোমেইন বা Godaddy-এর মতো তৃতীয় পক্ষ থেকে আপনি ইতিমধ্যেই কিনেছেন এমন একটি ডোমেন সংযুক্ত করুন৷ @else Secure the perfect domain for your store that customers can trust and find easily. Buy a new domain from ebitans, or connect a domain you already purchase from a third -party like google domains or Godaddy. @endif</p>
                                <!--<a href="#" class="btn btn-primary">@if(Session::has('lang') && Session::get('lang')=='bn') নতুন ডোমেইন কিনুন @else Buy new domain @endif</a> -->
                                &nbsp; &nbsp; <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">@if(Session::has('lang') && Session::get('lang')=='bn') বিদ্যমান ডোমেন সংযোগ করুন @else Connect existing domain @endif</a>
                            </div>
                        </div>
                        </div>
                    </div> <!-- card end// -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <p>Domain</p>
                        </div>
                        @if (Session::has('error_message'))
                            <div class="alert alert-danger" style="color:#fff">{{Session::get('error_message')}}</div>
                        @endif
                        <!--<form action="{{route('admin.updatesetting')}}" method="post" enctype="multipart/form-data">-->
                        <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <ul style="list-style:none;padding-left:0rem !important;">
                                    @if(isset($domain) && count($domain)>0)
                                    @foreach($domain as $key=>$dm)
                                    <li style="padding:10px;border-bottom:1px solid gray">@if($dm->status=="Active") <input type="radio" name="domain" class="selectdomain" value="{{$dm->id}}" @if($store->url==$dm->name) checked @endif> @else &nbsp;&nbsp; @endif &nbsp;&nbsp;&nbsp;{{$dm->name}} &nbsp;<span @if($dm->status=="Active")  class="badge badge-primary" @elseif($dm->status=="Requested") class="badge badge-danger" @elseif($dm->status=="Processing") class="badge badge-secondary" @else class="badge badge-danger" @endif>{{$dm->status}}</span></li>

                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        </div>
                    </div> <!-- card end// -->
                    <!--<button type="submit" class="btn btn-info">Update</button>-->

                </div>
                <!--</form>-->
                </div>
                </div>

            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script src="{{asset('admin/src/bootstrap-tagsinput.js')}}"></script>
<script src="{{asset('admin/src/bootstrap-tagsinput-angular.js')}}"></script>
<script>
    $(document).ready(function(){
        $(".selectdomain").on("click",function(){
            $url="/changedomain";
            var value=$(this).val();
            console.log(value);
            $.get($url,{value:value}, function(data){
               console.log(data);
               toastr.success('Domain Set Successfully', 'Success');
            });
        });
    });
</script>
<script>

var citynames = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: {
    url: 'assets/citynames.json',
    filter: function(list) {
      return $.map(list, function(cityname) {
        return { name: cityname }; });
    }
  }
});
citynames.initialize();

$('input').tagsinput({
    debugger;
  typeaheadjs: {
    name: 'citynames',
    displayKey: 'name',
    valueKey: 'name',
    source: citynames.ttAdapter()
  }
});
</script>
<script>

var citynames = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: {
    url: 'assets/citynames.json',
    filter: function(list) {
      return $.map(list, function(cityname) {
        return { name: cityname }; });
    }
  }
});
citynames.initialize();

$('input').seoinput({
  typeaheadjs: {
    name: 'citynames',
    displayKey: 'name',
    valueKey: 'name',
    source: citynames.ttAdapter()
  }
});
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
