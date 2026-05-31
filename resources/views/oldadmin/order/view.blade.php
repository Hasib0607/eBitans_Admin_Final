@extends('admin.layouts.main')
@section('content')
<style>
.order_info tr{
    border:1px solid gray;
}
    .order_info td,.order_info th{
        border:1px solid gray;
        text-align:center;
    }
  .orderdetails  table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

.orderdetails th, td {
  text-align: center;
  padding: 16px;
}

.orderdetails tr:nth-child(even) {
  background-color: #f2f2f2;
}

</style>
<main class="main-content position-relative border-radius-lg">
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item active">
                        <a href="{{route('admin.order')}}">
                            <img src="{{URL::to('/')}}/img/icons/order.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') অর্ডার @else Order @endif</span>
                        </a>
                    </li>
                
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mt-4" style="background-color:#8080801f" id="toplist">
    <div class="row" style="background-color:#fff;display:flex;align-items:center;justify-content:center">
        <div class="col-md-6 pt-2">
            <h4>Orders #{{$order->reference_no}}</h4>
            <p>Placed on {{$order->created_at}}</p>
        </div>
        <div class="col-md-6 text-end pt-2">
            <p>Total:  <span style="font-weight:bold">৳ {{$order->total}}</span></p>
        </div>
    </div>
    <?php
    $store=DB::table('stores')->where('id',$order->store_id)->first();
    ?>
    <div class="row mt-2" style="background-color:#fff;display:flex;align-items:center;justify-content:center">
        <div class="col-md-6 pt-2">
             <h5 style="padding-top:15px;font-size:20px;font-weight:bold;"><img src="https://img.icons8.com/ios/25/000000/gift--v1.png"/> Package 1</h5>
            <p>Sold By {{$store->name}}</p>
        </div>
        <div class="col-md-6 text-end pt-2">
            <!--<p>Total:  <span style="font-weight:bold">৳ {{$order->total}}</span></p>-->
        </div>
    </div>
    <div class="row mt-1" style="background-color:#fff;display:flex;align-items:center;justify-content:center">
        <?php
        $orderitem=DB::table('orderitems')->where('order_id',$order->id)->get();
        ?>
        @if(isset($orderitem) && count($orderitem)>0)
        @foreach($orderitem as $key=>$oitm)
        <?php $product=DB::table('products')->where('id',$oitm->product_id)->first(); ?>
        @if(isset($product))
        <div class="col-1 mt-3 mb-3">
            @if($product->images)
                @php
                    $images=explode(',',$product->images);
                @endphp
                @foreach($images as $key=>$image)
                <?php if($key=="0"){ ?>
                    <img src="{{URL::to('/')}}/assets/images/product/{{$image}}" width="100px" height="100px">
                <?php }
                else{
                ?>    
                
            <?php } ?>
                @endforeach
            @endif
        </div>
        <div class="col-5 mt-3 mb-3">
            {{$product->name}}
            <br>
            @if(isset($oitm->color)) Color: {{$oitm->color}} @endif @if(isset($oitm->size)) Size: {{$oitm->size}} @endif @if(isset($oitm->unit)) Unit: {{$oitm->unit}} @endif
        </div>
        <div class="col-2 mt-3 mb-3">
            Qty. {{$oitm->quantity}}
        </div>
        <div class="col-4 mt-3 mb-3">
            ৳ {{$oitm->price}}
        </div>
        @endif
        @endforeach
        @endif
    </div>
    <div class="row mt-2" style="background-color:#fff;display:flex;justify-content:center">
        <div class="col-6 text-start py-3" style="border-right:8px solid #8080801f">
            <h4>Shipping Address</h4>
            <h6>{{$order->name}}</h6>
            <h6>{{$order->phone}}</h6>
            <h6>{{$order->address}}</h6>
        </div>
        <div class="col-6 text-start py-3">
            <h4>Total Summary</h4>
            <div class="row">
                <div class="col-6">
                    Subtotal
                </div>
                <div class="col-6">
                    ৳ {{$order->subtotal}}
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    Shipping Fee
                </div>
                <div class="col-6">
                    ৳ {{$order->shipping}}
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    Discount
                </div>
                <div class="col-6">
                    ৳ {{$order->discount}}
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    Tax
                </div>
                <div class="col-6">
                    ৳ {{$order->Tax ?? 0}}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6">
                    Total
                </div>
                <div class="col-6">
                    ৳ {{$order->total}}
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
   function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
</script>
@endpush