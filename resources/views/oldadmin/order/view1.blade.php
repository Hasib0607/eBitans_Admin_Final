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
<div class="container-fluid mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4>View Orders ({{$order->reference_no}})</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li class="active"><a href="{{route('admin.order')}}">Back</a></li>
                <li><a href="#">Import</a></li>
                <li><a href="#">Export</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-md-12">
            <h5>Order ID: {{$order->id}}</h5>
            <h6>Reference NO: {{$order->reference_no}}</h6>
        </div>
        <div class="col-md-3">
            <div class="customer_info">
                <h3>Customer Info</h3>
                <p>Name: {{$order->name}}</p>
                <p>Phone: {{$order->phone}}</p>
                <p>Email: {{$order->email}}</p>
                <p>Address: {{$order->address}}</p>
            </div>
        </div>
        <div class="col-md-9">
            <div class="order_info">
                <h3>Order Info</h3>
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{$order->id}}</td>
                        <th>Subtotal</th>
                        <td>{{$order->subtotal}}</td>
                        <th>Discount</th>
                        <td>{{$order->discount}}</td>
                        <th>Shipping</th>
                        <td>{{$order->shipping}}</td>
                        <th>Tax</th>
                        <td>{{$order->tax}}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>{{$order->total}}</td>
                        <th>Status</th>
                        <td>{{$order->status}}</td>
                        <th>Type</th>
                        <td>{{$order->type}}</td>
                        <th>Store</th>
                        <td>{{$order->store_id}}</td>
                        <th>Branch</th>
                        <td>{{$order->branch_id}}</td>
                    </tr>
                    <?php $transaction=DB::table('transactions')->where('order_id',$order->id)->first(); ?>
                        @if(isset($transaction))
                    <tr>
                        <th>Transaction Id</th>
                        <td>{{$transaction->transaction_id}}</td>
                        <th>Transaction Status</th>
                        <td>{{$transaction->status}}</td>
                        <th>Transaction Type</th>
                        <td>{{$transaction->mode}}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="orderdetails">
                <h3>Product Details</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $orderitem=DB::table('orderitems')->where('order_id',$order->id)->get();
                        ?>
                        @if(isset($orderitem) && count($orderitem)>0)
                        @foreach($orderitem as $key=>$oitm)
                        <?php $product=DB::table('products')->where('id',$oitm->product_id)->first(); ?>
                        @if(isset($product))
                        <tr>
                            <td>{{$oitm->id}}</td>
                            <td>
                                @if($product->images)
                                    @php
                                        $images=explode(',',$product->images);
                                    @endphp
                                    @foreach($images as $key=>$image)
                                    <?php if($key=="1"){ ?>
                                        <img src="{{URL::to('/')}}/assets/images/product/{{$image}}" width="150px">
                                    <?php }
                                    else{
                                    ?>    
                                    
                                <?php } ?>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{$product->name}}</td>
                            <td>{{$oitm->quantity}}</td>
                            <td>{{$oitm->price}}</td>
                        </tr>
                        @endif
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <table class="table">
                <tr>
                    <td>Subtotal</td>
                    <td>{{$order->subtotal}}</td>
                </tr>
                <tr>
                
                    <td>Discount</td>
                    <td>{{$order->discount}}</td>
                </tr>
                <tr>
                    <td>Shipping</td>
                    <td>{{$order->shipping}}</td>
                </tr>
                <tr>
                    <td>Tax</td>
                    <td>{{$order->tax}}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{{$order->total}}</td>
                </tr>
            </table>
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