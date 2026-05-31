@extends('admin.layouts.main')
@push('styles')
<style>
.image-link {
  cursor: -webkit-zoom-in;
  cursor: -moz-zoom-in;
  cursor: zoom-in;
}


/* This block of CSS adds opacity transition to background */
.mfp-with-zoom .mfp-container,
.mfp-with-zoom.mfp-bg {
	opacity: 0;
	-webkit-backface-visibility: hidden;
	-webkit-transition: all 0.3s ease-out; 
	-moz-transition: all 0.3s ease-out; 
	-o-transition: all 0.3s ease-out; 
	transition: all 0.3s ease-out;
}

.mfp-with-zoom.mfp-ready .mfp-container {
		opacity: 1;
}
.mfp-with-zoom.mfp-ready.mfp-bg {
		opacity: 0.8;
}

.mfp-with-zoom.mfp-removing .mfp-container, 
.mfp-with-zoom.mfp-removing.mfp-bg {
	opacity: 0;
}



/* padding-bottom and top for image */
.mfp-no-margins img.mfp-img {
	padding: 0;
}
/* position of shadow behind the image */
.mfp-no-margins .mfp-figure:after {
	top: 0;
	bottom: 0;
}
/* padding for main container */
.mfp-no-margins .mfp-container {
	padding: 0;
}



/* aligns caption to center */
.mfp-title {
  text-align: center;
  padding: 6px 0;
}
.image-source-link {
  color: #DDD;
}
.zoom {
  transition: transform .2s; /* Animation */
  margin: 0 auto;
}

.zoom:hover {
  transform: scale(7.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
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

<main class="main-content position-relative border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if($url1=='Inventory') active @endif">
                        <a href="{{URL::to('/')}}/inventory">
                            <img src="{{URL::to('/')}}/img/icons/inventory-2.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') মালগুদাম @else Inventory @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if($url1=='Stock Alert') active @endif" aria-current="page">
                        <a href="{{route('admin.stockalert')}}">
                            <img src="{{URL::to('/')}}/img/icons/new-product.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') স্টক সতর্কতা @else Stock Alert @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if($url1=='Stock Out') active @endif" aria-current="page">
                        <a href="{{route('admin.stockout')}}">
                            <img src="{{URL::to('/')}}/img/icons/out-of-stock.png" > <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') মজুত শেষ
                                @else Stock Out @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.topselling')}}">
                            <img src="{{URL::to('/')}}/img/subcategory.png" > <br>Top Selling Products
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.lowestselling')}}">
                            <img src="{{URL::to('/')}}/img/subcategory.png" > <br>Lowest Selling Products
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4><?php echo $url1; ?></h4>
        </div>
    </div>
    <div class="row mt-5 productlist">

        <div class="col-12">
        <div class="card">
            <div class="card-header">
                
                <div class="row">
                    <div class="col-md-2" style="padding-right:1px;">
                        <form id="submitform" method="post" action="{{route('admin.changeproductstatus')}}">
                        @csrf
                    <input type="hidden" name="text2" id="selectids">
                        <select class='form-control' name="action" id="action">
                            <option value="select">@if(Session::has('lang') && Session::get('lang')=='bn') সিলেক্ট  অপসন @else Select Option @endif</option>
                            <option value="active">@if(Session::has('lang') && Session::get('lang')=='bn') সক্রিয় @else Active @endif</option>
                            <option value="deactive">@if(Session::has('lang') && Session::get('lang')=='bn') নিষ্ক্রিয় @else Deactive @endif</option>
                            <option value="delete">@if(Session::has('lang') && Session::get('lang')=='bn') ডিলিট @else Delete @endif</option>
                        </select>
                    </div>
                    <div class="col-md-1" style="padding-left:0px;">
                        <p id="submit" class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn') আবেদন  @else Apply @endif</p>
                        </form>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group" >
                            <input type="text" class="form-control" aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                
                    <div class="col-md-1 text-end mt-1">
                        <label for="formdate">@if(Session::has('lang') && Session::get('lang')=='bn') তারিখ হইতে @else From Date @endif</label>
                    </div>
                    <div class="col-md-2">
                        <form action="{{route('admin.productdatefilter')}}" method="get">
                    @csrf
                        <input type="date" name="formdate"  id="formdate" value="{{$from ?? ""}}" class="form-control">
                    </div>
                    <div class="col-md-1 text-end mt-1" style="padding-left:0px;padding-right:0px;width:4%">
                        <label for="todate">@if(Session::has('lang') && Session::get('lang')=='bn') এখন পর্যন্ত @else To Date @endif</label>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="enddate" id="todate" value="{{$to ?? ""}}" class="form-control">
                    </div>
                    <div class="col-md-1 filterbtns">
                        <button type="submit" class="btn btn-info filterbtn" style="background-color: #7b809a ">@if(Session::has('lang') && Session::get('lang')=='bn') ফিল্টার @else Filter @endif</button>
                        </form>
                    </div>
               
                </div>
                 </form>
            </div>
            <div class="card-body">
            @if (Session::has('success_message'))
                    <div class="alert alert-success" style="color:#fff">{{Session::get('success_message')}}</div>
                @endif
                <div class="table-responsive" id="desktoptable">
                    <table class="table table-striped" width="100%" id="taskfilterresult">
                        <thead>
                            <tr>
                                <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn') ছবি @else Image @endif</th>
                                <th width="30%">@if(Session::has('lang') && Session::get('lang')=='bn') নাম @else Name @endif</th>
                                <th width="20%">@if(Session::has('lang') && Session::get('lang')=='bn') দাম @else Price @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') পরিমাণ @else Quantity @endif</th>
                                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn') স্ট্যাটাস @else Status @endif</th>
                                <th width="15%"> @if(Session::has('lang') && Session::get('lang')=='bn') তারিখ @else Date @endif</th>
                                <th width="5%">View</th>
                                <th width="11%"> @if(Session::has('lang') && Session::get('lang')=='bn') এডিট @else Action @endif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                <td><input type="checkbox" name="selectedid" value="{{$product->id}}" id="id" class="checkSingle"></td>
                                <td>
                                @if($product->images)
                                    @php
                                        $images=explode(',',$product->images);
                                    @endphp
                                    @foreach($images as $key=>$image)
                                    <?php if($key=="0"){ ?>
                                        <!--<a href="{{URL::to('/')}}/assets/images/product/{{$image}}" class="without-caption image-link">-->
                                            <img src="{{URL::to('/')}}/assets/images/product/{{$image}}" class="zoom" width="30px">
                                        <!--</a>-->
                                    <?php }
                                    else{
                                    ?>    
                                    
                                <?php } ?>
                                    @endforeach
                                @endif
                            </td>
                                <td>{{Str::of($product->name)->limit(20)}}</td>
                                <td>৳{{$product->regular_price}}</td>
                                <td>{{$product->quantity}}</td>
                                <td>
                                    <div class="form-check form-switch" style="text-align:center;">
                                        <input class="form-check-input switchstatus" type="checkbox" id="flexSwitchCheckChecked" data-id="{{$product->id}}" style="margin:0 auto;" @if($product->status=='active') checked @endif>
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                </td>
                                <td>{{date('d-m-Y', strtotime($product->created_at))}}</td>
                                <td>
                                    <a href="{{route('admin.product.view',$product->id)}}" class="btn btn-secondary">View</a>
                                </td>
                                <td>
                                    <a href="{{URL::to('/')}}/products/edit/{{$product->id}}"><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                    &nbsp;&nbsp;
                                    <!--<a href="{{URL::to('/')}}/deleteproduct/{{$product->id}}"  onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>-->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive" id="mobiletable">
                    <table class="table" style="width:100%">
                        @foreach($products as $key=>$product)
                        <tr class="mobilefirstrow">
                            <th width="10%">
                                <input type="checkbox" name="selectedid" value="{{$product->id}}" id="id" class="checkSingle">
                            </th>
                            <th width="20%" style="color:#f1593a">
                                Name
                            </th>
                            <td width="60%" style="color:black">
                                {{Str::of($product->name)->limit(20)}}
                            </td>
                            <td width="10%">
                                <a href="#" class="toggler" data-prod-cat="{{$key}}">
                                    <i class="fa fa-arrow-down" id="show{{$key}}" style="color:#f1593a"></i>
                                    <i class="fa fa-arrow-up" id="up{{$key}}" style="display:none"></i>
                                </a>
                            </td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Image
                            </th>
                            <td width="60%">
                                @if($product->images)
                                    @php
                                        $images=explode(',',$product->images);
                                    @endphp
                                    @foreach($images as $keyss=>$image)
                                    <?php if($keyss=="0"){ ?>
                                        <!--<a href="{{URL::to('/')}}/assets/images/product/{{$image}}" class="without-caption image-link">-->
                                            <img src="{{URL::to('/')}}/assets/images/product/{{$image}}" class="zoom" width="30px">
                                        <!--</a>-->
                                    <?php }
                                    else{
                                    ?>    
                                    
                                <?php } ?>
                                    @endforeach
                                @endif
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Price
                            </th>
                            <td width="60%">
                                 ৳{{$product->regular_price}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Quantity
                            </th>
                            <td width="60%">
                                {{$product->quantity}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Status
                            </th>
                            <td width="60%" style="display: flex;justify-content: center;align-items: center;">
                                <div class="form-check form-switch" style="text-align:center;">
                                    <input class="form-check-input switchstatus" type="checkbox" id="flexSwitchCheckChecked" data-id="{{$product->id}}" style="margin:0 auto;" @if($product->status=='active') checked @endif>
                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                </div>
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Date
                            </th>
                            <td width="60%">
                                {{date('d-m-Y', strtotime($product->created_at))}}
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <tr class="cat{{$key}}" style="display:none">
                            <th width="10%"></th>
                            <th width="20%">
                                Action
                            </th>
                            <td width="60%">
                                 <a href="{{URL::to('/')}}/products/edit/{{$product->id}}"><img src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                    &nbsp;&nbsp;
                            </td>
                            <td width="10%"></td>
                        </tr>
                        @endforeach
                    </table>
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

</script>
<script>
 $('#submit').on('click',function(){
     var form = $(this).parents('form');
     var note=$('#action').val();
     if(note != 'select'){
        swal.fire({
          title: 'Are you sure?',
          text: "You want to "+note+" this selected item",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, '+note+' it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
              console.log(form);
            $('#submitform').submit();
            form.submit();
          } else if (
            result.dismiss === Swal.DismissReason.cancel
          ){
            swal.fire(
              'Cancelled',
              ''+note+' Cancel :)',
              'error'
            )
          }
        })
     }
 })
    $(document).ready(function(){
        $(".switchstatus").on("change",function(){
            $url="/changeprostatus";
            var value=$(this).val();
            console.log(value);
            var id = $(this).data('id');
            console.log(id);
            $.get($url,{value:value,id:id}, function(data){
               console.log(data); 
            });
        });
    });
</script>
<script>
$(document).ready(function() {
    $("#checkedAll").change(function() {
        debugger;
        if (this.checked) {
            $(".checkSingle").each(function() {
                debugger;
                this.checked=true;
                var valuesArray = $('input[name="selectedid"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
            });
        } else {
            $(".checkSingle").each(function() {
                this.checked=false;
            });
            var valuesArray ='';
            $("#selectids").val(valuesArray);
            $("#selectdelids").val(valuesArray);
        }
    });
    $(".checkSingle").click(function () {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".checkSingle").each(function() {
                if (!this.checked)
                    isAllChecked = 1;
                var valuesArray = $('input[name="selectedid"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
            });
            if (isAllChecked == 0) {
                $("#checkedAll").prop("checked", true);
            }     
        }
        else {
            $("#checkedAll").prop("checked", false);
            var valuesArray = $('input[name="selectedid"]:checked').map(function () {  
                return this.value;
                }).get().join(",");
                $("#selectids").val(valuesArray);
                $("#selectdelids").val(valuesArray);
        }
    });
});
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
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
</script>
@endpush