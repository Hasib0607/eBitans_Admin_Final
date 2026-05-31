@extends('admin.layouts.main')
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="{{URL::to('/')}}/report">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Report
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{route('admin.completeorder')}}">
                            <img src="{{URL::to('/')}}/img/subcategory.png" > <br>Selling Report
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">weekend</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') মোট পণ্য @else Total Product @endif</p>
              <?php
              $product=DB::table('products')->where('store_id',$store_id)->get();
              ?>
              <h4 class="mb-0">{{count($product) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than lask week</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') মোট ব্যবহারকারী @else Total Users @endif</p>
              <?php
              $user=DB::table('users')->where('store_id',$store_id)->where('type','customer')->get();
              ?>
              <h4 class="mb-0">{{count($user) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask month</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') পুনর্নবীকরণ @else Revenew @endif</p>
              <?php
              $order=DB::table('orders')->where('customer_id',$customer_id)->where('status','Delivered')->sum('total');
              ?>
              <h4 class="mb-0">{{$order ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>-->
          </div>
        </div>
      </div>
<!----net profit calculation------>
<?php
$orders=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->get();
$cost=0;
if(isset($orders) && count($orders)>0){
    foreach($orders as $order){
        $orderitems=DB::table('orderitems')->where('order_id',$order->id)->get();
        if(isset($orderitems) && count($orderitems)>0){
            foreach($orderitems as $oitm){
                $product=DB::table('products')->where('id',$oitm->product_id)->first();
                if(isset($product->cost)){
                    $cost=$cost+($product->cost*$oitm->quantity);
                }
            }
        }
    }
}
$sell=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->sum('total');
$profit=$sell-$cost;
?>



      <div class="col-xl-3 col-sm-6">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">weekend</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') মোট লাভ @else Net Profit @endif</p>
              <h4 class="mb-0">{{$profit ?? 0}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>-->
          </div>
        </div>
      </div>
      
    </div>
    <div class="row mt-7">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">weekend</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') মোট অর্ডার @else Total Order @endif</p>
              <?php
              $orderss=DB::table('orders')->where('store_id',$store_id)->get();
              ?>
              <h4 class="mb-0">{{count($orderss ?? "0")}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than lask week</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') আদেশ বাতিল @else Cancel Order @endif</p>
              <?php
              $ordersss=DB::table('orders')->where('store_id',$store_id)->where('status','Cancelled')->get();
              ?>
              <h4 class="mb-0">{{count($ordersss ?? "0")}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask month</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') ওয়েবসাইটের আয় @else Website Revenew @endif</p>
              <?php
              $wr=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->where('branch_id',null)->sum('total');
              ?>
              <h4 class="mb-0">{{$wr ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">weekend</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') শাখা রাজস্ব @else Branch Revenew @endif</p>
              <?php
              $br=DB::table('orders')->where('customer_id',$customer_id)->where('store_id',$store_id)->where('status','Delivered')->where('branch_id','!=',null)->sum('total');
              ?>
              <h4 class="mb-0">{{$br ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>-->
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4">
    <div class="col-lg-4 col-md-6 mt-4 mb-4">
       <div class="card z-index-2  ">
         <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
           <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
             <div class="chart">
               <canvas id="chart-lines" class="chart-canvas" height="170"></canvas>
             </div>
           </div>
         </div>
         <div class="card-body">
           <h6 class="mb-0 "> Daily Earn </h6>
           <p class="text-sm "> Last Record Performance </p>
           <hr class="dark horizontal">
           <div class="d-flex ">
             <i class="material-icons text-sm my-auto me-1">schedule</i>
             <p class="mb-0 text-sm"> updated {{rand(1,10)}} sec ago </p>
           </div>
         </div>
       </div>
      </div>
      <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card z-index-2  ">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-info  shadow-success border-radius-lg py-3 pe-1">
                <div class="chart">
                  <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
                  <h6 class="mb-0 "> Daily Orders </h6>
                  <p class="text-sm "> Last Record Performance </p>
                  <hr class="dark horizontal">
                  <div class="d-flex ">
                    <i class="material-icons text-sm my-auto me-1">schedule</i>
                    <p class="mb-0 text-sm"> updated {{rand(1,10)}} sec ago </p>
                  </div>
            </div>
          </div>
      </div>
      <div class="col-lg-4 col-md-6 mt-4 mb-4">
       <div class="card z-index-2 ">
         <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
           <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
             <div class="chart">
               <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
             </div>
           </div>
         </div>
         <div class="card-body">
           <h6 class="mb-0 ">Website User</h6>
           <p class="text-sm ">Last Record Performance</p>
           <hr class="dark horizontal">
           <div class="d-flex ">
             <i class="material-icons text-sm my-auto me-1">schedule</i>
             <p class="mb-0 text-sm"> Per Month </p>
           </div>
         </div>
       </div>
      </div>
    <div class="row mt-7">
        <div class="col-md-12 text-center">
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn') রিপোর্ট @else Reports @endif</h4>
        </div>
    </div>
    <div class="row mt-3">
    <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <p>@if(Session::has('lang') && Session::get('lang')=='bn') দৈনিক প্রতিবেদন @else Daily Report @endif</p>
    </div>
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">weekend</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize"> @if(Session::has('lang') && Session::get('lang')=='bn') গৃহীত আদেশ @else Received Order @endif</p>
              <?php
              $ro=DB::table('orders')->where('store_id',$store_id)->where('status','Pending')->whereDate('created_at',\Carbon\Carbon::now())->get();
              ?>
              <h4 class="mb-0">{{count($ro) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than lask week</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">person</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') সম্পূর্ণ অর্ডার @else Completed Order @endif</p>
              <?php
              $co=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->whereDate('updated_at',\Carbon\Carbon::now())->get();
              ?>
              <h4 class="mb-0">{{count($co) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask month</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">person</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') আদেশ বাতিল @else Cancel Order @endif</p>
              <?php
              $co=DB::table('orders')->where('store_id',$store_id)->where('status','Cancelled')->whereDate('updated_at',\Carbon\Carbon::now())->get();
              ?>
              <h4 class="mb-0">{{count($co) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask month</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">person</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize"> @if(Session::has('lang') && Session::get('lang')=='bn') পুনর্নবীকরণ @else Revenew @endif</p>
              <?php
              $tr=DB::table('orders')->where('customer_id',$customer_id)->where('status','Delivered')->whereDate('updated_at',\Carbon\Carbon::now())->sum('total');
              ?>
              <h4 class="mb-0">{{$tr ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>-->
          </div>
        </div>
      </div>
<!----Today net profit calculation------>
<?php
$torders=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->whereDate('updated_at',\Carbon\Carbon::now())->get();
$tcost=0;
if(isset($torders) && count($torders)>0){
    foreach($orders as $order){
        $orderitems=DB::table('orderitems')->where('order_id',$order->id)->get();
        if(isset($orderitems) && count($orderitems)>0){
            foreach($orderitems as $oitm){
                $product=DB::table('products')->where('id',$oitm->product_id)->first();
                if(isset($product->cost)){
                    $tcost=$tcost+($product->cost*$oitm->quantity);
                }
            }
        }
    }
}
$tsell=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->whereDate('updated_at',\Carbon\Carbon::now())->sum('total');
$tprofit=$tsell-$tcost;
?>
      <div class="col-xl-2 col-sm-6">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">weekend</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') মোট লাভ @else Net Profit @endif</p>
              
              <h4 class="mb-0">{{$tprofit ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>-->
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-3">
        
    </div>
    <div class="row mt-3">
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <p>@if(Session::has('lang') && Session::get('lang')=='bn') সাপ্তাহিক প্রতিবেদন @else Weekly Report @endif</p>
      </div>
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">weekend</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') গৃহীত আদেশ @else Received Order @endif</p>
              <?php
              $ro=DB::table('orders')->where('store_id',$store_id)->where('status','Pending')->whereDate('created_at','>=',\Carbon\Carbon::now()->subDays(7))->get();
              ?>
              <h4 class="mb-0">{{count($ro) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than lask week</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">person</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') সম্পূর্ণ অর্ডার @else Completed Order @endif</p>
              <?php
              $co=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->whereDate('updated_at','>=',\Carbon\Carbon::now()->subDays(7))->get();
              ?>
              <h4 class="mb-0">{{count($co) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask month</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">person</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') আদেশ বাতিল @else Cancel Order @endif</p>
              <?php
              $co=DB::table('orders')->where('store_id',$store_id)->where('status','Cancelled')->whereDate('updated_at','>=',\Carbon\Carbon::now()->subDays(7))->get();
              ?>
              <h4 class="mb-0">{{count($co) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask month</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">person</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize"> @if(Session::has('lang') && Session::get('lang')=='bn') পুনর্নবীকরণ @else Revenew @endif</p>
              <?php
              $tr=DB::table('orders')->where('customer_id',$customer_id)->where('status','Delivered')->whereDate('updated_at','>=',\Carbon\Carbon::now()->subDays(7))->sum('total');
              ?>
              <h4 class="mb-0">{{$tr ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>-->
          </div>
        </div>
      </div>
<!----Weekly net profit calculation------>
<?php
$worders=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->whereDate('updated_at','>=',\Carbon\Carbon::now()->subDays(7))->get();
$wcost=0;
if(isset($worders) && count($worders)>0){
    foreach($orders as $order){
        $orderitems=DB::table('orderitems')->where('order_id',$order->id)->get();
        if(isset($orderitems) && count($orderitems)>0){
            foreach($orderitems as $oitm){
                $product=DB::table('products')->where('id',$oitm->product_id)->first();
                if(isset($product->cost)){
                    $wcost=$wcost+($product->cost*$oitm->quantity);
                }
            }
        }
    }
}
$wsell=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->whereDate('updated_at','>=',\Carbon\Carbon::now()->subDays(7))->sum('total');
$wprofit=$wsell-$wcost;
?>
      <div class="col-xl-2 col-sm-6">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">weekend</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') মোট লাভ @else Net Profit @endif</p>
              
              <h4 class="mb-0">{{$wprofit ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>-->
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-3">
        <!--<div class="col-md-12">-->
        <!--    <p>Monthly Report</p>-->
        <!--</div>-->
    </div>
    <div class="row mt-3">
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <p>@if(Session::has('lang') && Session::get('lang')=='bn') মাসিক প্রতিবেদন @else Monthly Report @endif</p>
      </div>
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">weekend</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') গৃহীত আদেশ @else Received Order @endif</p>
              <?php
              $ro=DB::table('orders')->where('store_id',$store_id)->where('status','Pending')->whereMonth('created_at',\Carbon\Carbon::now()->month)->get();
              ?>
              <h4 class="mb-0">{{count($ro) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than lask week</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">person</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') সম্পূর্ণ অর্ডার @else Completed Order @endif</p>
              <?php
              $co=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->whereMonth('updated_at',\Carbon\Carbon::now()->month)->get();
              ?>
              <h4 class="mb-0">{{count($co) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask month</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">person</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') আদেশ বাতিল @else Cancel Order @endif</p>
              <?php
              $co=DB::table('orders')->where('store_id',$store_id)->where('status','Cancelled')->whereMonth('updated_at',\Carbon\Carbon::now()->month)->get();
              ?>
              <h4 class="mb-0">{{count($co) ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask month</p>-->
          </div>
        </div>
      </div>
      <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">person</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize"> @if(Session::has('lang') && Session::get('lang')=='bn') পুনর্নবীকরণ @else Revenew @endif</p>
              <?php
              $tr=DB::table('orders')->where('customer_id',$customer_id)->where('status','Delivered')->whereDate('updated_at','>=',\Carbon\Carbon::now()->month)->sum('total');
              ?>
              <h4 class="mb-0">{{$tr ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>-->
          </div>
        </div>
      </div>
<!----monthly net profit calculation------>
<?php
$morders=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->whereMonth('updated_at',\Carbon\Carbon::now()->month)->get();
$mcost=0;
if(isset($morders) && count($morders)>0){
    foreach($orders as $order){
        $orderitems=DB::table('orderitems')->where('order_id',$order->id)->get();
        if(isset($orderitems) && count($orderitems)>0){
            foreach($orderitems as $oitm){
                $product=DB::table('products')->where('id',$oitm->product_id)->first();
                if(isset($product->cost)){
                    $mcost=$mcost+($product->cost*$oitm->quantity);
                }
            }
        }
    }
}
$msell=DB::table('orders')->where('store_id',$store_id)->where('status','Delivered')->whereMonth('updated_at',\Carbon\Carbon::now()->month)->sum('total');
$mprofit=$msell-$mcost;
?>
      <div class="col-xl-2 col-sm-6">
        <div class="card">
          <div class="card-header p-3 pt-2">
            <!--<div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">-->
            <!--  <i class="material-icons opacity-10">weekend</i>-->
            <!--</div>-->
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">@if(Session::has('lang') && Session::get('lang')=='bn') মোট লাভ @else Net Profit @endif</p>
              
              <h4 class="mb-0">{{$mprofit ?? "0"}}</h4>
            </div>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-footer p-3">
            <!--<p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>-->
          </div>
        </div>
      </div>
    </div>

@endsection
@push('scripts')
<script src="{{asset('admin/assets/js/plugins/chartjs.min.js')}}"></script>
<script>
  var ctx = document.getElementById("chart-bars").getContext("2d");
  var labels =  {{ Js::from($labels) }};

  var users =  {{ Js::from($data) }};
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [{
        label: "Total User",
        tension: 0.4,
        borderWidth: 0,
        borderRadius: 4,
        borderSkipped: false,
        backgroundColor: "rgba(255, 255, 255, .8)",
        data: users,
        maxBarThickness: 6
      }, ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
            color: 'rgba(255, 255, 255, .2)'
          },
          ticks: {
            suggestedMin: 0,
            suggestedMax: 500,
            beginAtZero: true,
            padding: 10,
            font: {
              size: 14,
              weight: 300,
              family: "Roboto",
              style: 'normal',
              lineHeight: 2
            },
            color: "#fff"
          },
        },
        x: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
            color: 'rgba(255, 255, 255, .2)'
          },
          ticks: {
            display: true,
            color: '#f8f9fa',
            padding: 10,
            font: {
              size: 14,
              weight: 300,
              family: "Roboto",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
      },
    },
  });

  var labelsorder =  {{ Js::from($labelsorder) }};

var dataorders =  {{ Js::from($dataorder) }};
  var ctx2 = document.getElementById("chart-line").getContext("2d");

  new Chart(ctx2, {
    type: "line",
    data: {
      labels: labelsorder,
      datasets: [{
        label: "Total Orders",
        tension: 0,
        borderWidth: 0,
        pointRadius: 5,
        pointBackgroundColor: "rgba(255, 255, 255, .8)",
        pointBorderColor: "transparent",
        borderColor: "rgba(255, 255, 255, .8)",
        borderColor: "rgba(255, 255, 255, .8)",
        borderWidth: 4,
        backgroundColor: "transparent",
        fill: true,
        data: dataorders,
        maxBarThickness: 6

      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
            color: 'rgba(255, 255, 255, .2)'
          },
          ticks: {
            display: true,
            color: '#f8f9fa',
            padding: 10,
            font: {
              size: 14,
              weight: 300,
              family: "Roboto",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            color: '#f8f9fa',
            padding: 10,
            font: {
              size: 14,
              weight: 300,
              family: "Roboto",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
      },
    },
  });
  var labelsc =  {{ Js::from($labelsc) }};

var datac =  {{ Js::from($datac) }};
  var ctx2s = document.getElementById("chart-lines").getContext("2d");

  new Chart(ctx2s, {
    type: "line",
    data: {
      labels: labelsc,
      datasets: [{
        label: "Earn",
        tension: 0,
        borderWidth: 0,
        pointRadius: 5,
        pointBackgroundColor: "rgba(255, 255, 255, .8)",
        pointBorderColor: "transparent",
        borderColor: "rgba(255, 255, 255, .8)",
        borderColor: "rgba(255, 255, 255, .8)",
        borderWidth: 4,
        backgroundColor: "transparent",
        fill: true,
        data: datac,
        maxBarThickness: 6

      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
            color: 'rgba(255, 255, 255, .2)'
          },
          ticks: {
            display: true,
            color: '#f8f9fa',
            padding: 10,
            font: {
              size: 14,
              weight: 300,
              family: "Roboto",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            color: '#f8f9fa',
            padding: 10,
            font: {
              size: 14,
              weight: 300,
              family: "Roboto",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
      },
    },
  });

  var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

  new Chart(ctx3, {
    type: "line",
    data: {
      labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      datasets: [{
        label: "Mobile apps",
        tension: 0,
        borderWidth: 0,
        pointRadius: 5,
        pointBackgroundColor: "rgba(255, 255, 255, .8)",
        pointBorderColor: "transparent",
        borderColor: "rgba(255, 255, 255, .8)",
        borderWidth: 4,
        backgroundColor: "transparent",
        fill: true,
        data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
        maxBarThickness: 6

      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
            color: 'rgba(255, 255, 255, .2)'
          },
          ticks: {
            display: true,
            padding: 10,
            color: '#f8f9fa',
            font: {
              size: 14,
              weight: 300,
              family: "Roboto",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5]
          },
          ticks: {
            display: true,
            color: '#f8f9fa',
            padding: 10,
            font: {
              size: 14,
              weight: 300,
              family: "Roboto",
              style: 'normal',
              lineHeight: 2
            },
          }
        },
      },
    },
  });
</script>
@endpush

