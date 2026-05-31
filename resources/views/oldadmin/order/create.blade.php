@extends('admin.layouts.main')
@push('styles')


@endpush
@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
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
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.returned')}}">
                            <img src="{{URL::to('/')}}/img/icons/product-return.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') ফেরত পণ্য @else Returned Product @endif</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.invoice')}}">
                            <img src="{{URL::to('/')}}/img/icons/bill.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') চালান @else Invoice @endif</span>
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
            <h4>Create New Order</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;"><a class="btn btn-primary" href="{{route('admin.order')}}" style="display:block;border-radius:0px !important">Back to List</a></li>
                <li style="padding:0px;border:0px;"><a data-href="/orderexport" onclick="exportOrder(event.target);" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 productlist">
        <div class="col-12">
            <input type="hidden" value="{{$branch_id ?? "0"}}" name="branchid" id="branchid">
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
      </div>
      <div class="modal-body">
      <form class="row g-3">
        <div class="col-md-12">
            <label for="validationDefault01" class="form-label">Phone</label>
            <input type="text" class="form-control" id="validationDefault01" name="phone" value="" required>
        </div>
        <div class="col-md-12">
            <label for="validationDefault02" class="form-label">Name</label>
            <input type="text" class="form-control" id="validationDefault02" name="name" id="name123" >
        </div>
        <div class="col-md-6">
            <label for="validationDefault03" class="form-label">Email</label>
            <input type="email" class="form-control" id="validationDefault03" name="email" id="email123" >
        </div>
        <div class="col-md-6">
            <label for="validationDefault03" class="form-label">Address</label>
            <input type="text" class="form-control" id="validationDefault03" name="address" id="address" >
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button"  class="btn btn-primary" id="customersave">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-----Keyboard Shortcut---->
<div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Keyboard Shortcut</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
        <table class="table">
            <tr>
                <td>Customer Add</td>
                <td>ALT + A</td>
            </tr>
            <tr>
                <td>Place Order</td>
                <td>ALT + C</td>
            </tr>
        </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <form action="{{route('admin.saveholdorder')}}" method="post">
          @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hold Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
      </div>
      <div id="cartlistload4" class="modal-body cartload4">
        <div class="row g-3">
        <div class="input-group mb-3">
                <span class="input-group-text" style="right:unset;position:relative;padding:0px 10px;" id="basic-addon3">Order</span>
                <input type="text" class="form-control" name="order_id" placeholder="Order ID" id="basic-url" aria-describedby="basic-addon3">
        </div>
            <h4 style="font-size:20px;font-weight:500;text-align:center">Order Details</h4>
        <div class="table-responsive" style="padding:5px 30px;">
        <table class="table" width="100%" style="border:1px solid #d2d6de">
        <thead>
            <?php $i=1; ?>
        @if(Cart::instance('cart')->count()>0)
        @foreach (Cart::instance('cart')->content() as $item)
            <tr style="background-color:#d2d6de;color:#000;font-size:14px;">
                <td width="10%">{{$i++}}</td>
                <td width="60%">{{$item->model->name}} ({{$item->qty}})</td>
                <td width="30%">{{$item->price}}</td>
            </tr>
        @endforeach
        @else
        No product
        @endif
        </thead>
        <tbody>
            <tr style="color:#000;font-size:14px;">
                <td></td>
                <td style="text-align:end;padding-right:30px;">Subtotal</td>
                <td>{{Cart::instance('cart')->subtotal()}}</td>
            </tr>
            <tr style="color:#000;font-size:14px;">
                <td></td>
                <td style="text-align:end;padding-right:30px;">Discount</td>
                <td id="orderdiscount1">00</td>
                <input type="hidden" name="totaldiscount1" value="0">
            </tr>
            <tr style="color:#000;font-size:14px;">
                <td></td>
                <td style="text-align:end;padding-right:30px;">Tax</td>
                <td id="ordertax1">{{Cart::instance('cart')->tax()}}</td>
                <input type="hidden" name="totaltax1" value="{{Cart::instance('cart')->tax() ?? 0}}">
            </tr>
            <tr style="color:#000;font-size:14px;">
                <td></td>
                <td style="text-align:end;padding-right:30px;">Shipping</td>
                <td id="ordershipping1">00</td>
                <input type="hidden" name="totalshipping1" value="0">
            </tr>
            <tr style="color:#000;font-size:14px;">
                <td></td>
                <td style="text-align:end;padding-right:30px;">Other Charge</td>
                <td id="orderothercharge1">00</td>
                <input type="hidden" name="totalothercharge1" value="0">
            </tr>
            <tr style="color:#000;font-size:14px;">
                <td></td>
                <td style="text-align:end;padding-right:30px;">Payable Amount</td>
                <td id="ordertotal1">{{Cart::instance('cart')->total()}}</td>
                <input type="hidden" name="total123" value="{{Cart::instance('cart')->total() ?? 0}}">
            </tr>
        </tbody>
        </table>
        </div>
        </div>
      </div>
      <div class="modal-footer" style="margin:0 auto;">
        <button type="submit" class="btn btn-secondary">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>

@if(isset($products) && count($products)>0)
@foreach($products as $keysss=>$product)
<?php
    $veriants=DB::table('veriants')->where('pid',$product->id)->get();
?>
<div class="modal fade" id="exampleModals{{$keysss}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:9999999999999999999">
  <div class="modal-dialog modal-sm">
      <form action="{{route('admin.placeorder')}}" method="post">
          @csrf

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Choose Veriant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
      </div>
      <div class="modal-body" style="padding-left:0px;">
      <div class="row">
            <div class="col-md-9">
                 <ul style="list-style-type:none">
                     @foreach($veriants as $veriant)
                     <li>
                         @if(isset($veriant->color) && isset($veriant->size))
                         <label><input type="radio" name="veriant" id="veriant" data-id="{{$veriant->id}}" value="{{$veriant->id}}" data-bs-dismiss="modal" aria-label="Close"> Color: {{$veriant->color}} , Size : {{$veriant->size}}</label>
                         @elseif(!isset($veriant->color) && isset($veriant->size))
                         <label><input type="radio" name="veriant" id="veriant" data-id="{{$veriant->id}}" value="{{$veriant->id}}" data-bs-dismiss="modal" aria-label="Close"> Size : {{$veriant->size}}</label>
                         @else
                         <label><input type="radio" name="veriant" id="veriant" data-id="{{$veriant->id}}" value="{{$veriant->id}}" data-bs-dismiss="modal" aria-label="Close"> Volume: {{$veriant->volume}} {{$veriant->unit}}</label>
                         @endif
                     </li>
                     @endforeach
                 </ul>
            </div>
        </div>
      </div>
      <div class="modal-footer" id="editorderbtn">

      </div>
    </div>
    </form>
  </div>
</div>
@endforeach
@endif
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div id="cartlistload3" class="modal-dialog modal-xl cartload3">
      <form action="{{route('admin.placeorder')}}" method="post">
          @csrf
      <input type="hidden" name="branch_id" value="{{$branch_id ?? "0"}}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
      </div>
      <div id="cartlistload5" class="modal-body cartload5" style="padding-left:0px;">
      <div class="row">
            <div class="col-md-3 paytype" style="padding-left:0px;padding-right:0px;">
                <ul>
                    <li><input id="cod" type="radio" name="payment_type" value="cod" checked>&nbsp;<label for="cod">Cash On Delivery</label></li>
                    <li><input id="online" type="radio" name="payment_type" value="ssl">&nbsp;<label for="online">Online</label></li>
                </ul>
            </div>
            <div class="col-md-4 paymentmethod">
                <h5 style="text-align:center;">Payment Method:</h5>
                <ul>
                    <li><button type="button" class="btn" style="background-color:#00a65a;border-radius:10px;color:#fff">Full Payment</button></li>
                    <li><button type="button" class="btn" style="background-color:#dd4b39;border-radius:10px;color:#fff">Full Due</button></li>
                </ul>
                <div class="input-group mb-3">
                <span class="input-group-text" style="right:unset;position:relative;padding:0px 10px;" id="basic-addon3">Pay Amount</span>
                <input type="text" class="form-control" name="payamount" placeholder="Input Amount" id="basic-url" aria-describedby="basic-addon3">
                </div>
                <div class="input-group mb-3">
                <span class="input-group-text" style="right:unset;position:relative;padding:0px 10px" ><i class="fa fa-pen"></i></span>
                <input type="text" class="form-control" name="note" placeholder="Note Here" aria-label="Amount (to the nearest dollar)">
                </div>
            </div>
            <div class="col-md-5 orderdetails">
                <p style="font-size:20px;text-align:center">Order Details:</p>
                <div class="table-responsive">
                    <table class="table" width="100%">
                        <thead>
                        @if(Cart::instance('cart')->count()>0)
                        @foreach (Cart::instance('cart')->content() as $item)
                            <tr>
                                <th width="80%">{{$item->model->name}}</th>
                                <th width="20%" style="text-align:end">{{$item->price}}</th>
                            </tr>
                        @endforeach
                        @else
                        No Product In cart
                        @endif
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Subtotal</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem">{{Cart::instance('cart')->subtotal()}}</td>
                            </tr>
                            <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Discount</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem" id="orderdiscount">{{Session::get('discount') ?? "00"}}</td>
                                <input type="hidden" name="totaldiscount" value="0">
                            </tr>
                            <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Tax</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem" id="ordertax">{{Cart::instance('cart')->tax()}}</td>
                                <input type="hidden" name="totaltax" value="{{Cart::instance('cart')->tax() ?? 0}}">
                            </tr>
                            <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Shipping</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem" id="ordershipping">00</td>
                                <input type="hidden" name="totalshipping" value="0">
                            </tr>
                            <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Other Charge</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem" id="orderothercharge">00</td>
                                <input type="hidden" name="totalothercharge" value="0">
                            </tr>
                            <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Total</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem" id="ordertotal">{{Cart::instance('cart')->total()}}</td>
                                <input type="hidden" name="totalamount" value="{{Cart::instance('cart')->total() ?? '0'}}">
                            </tr>
                            <!-- <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Paid Amount</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem">100</td>
                            </tr>
                            <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Due Amount</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem">100</td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit"  class="btn btn-primary" >Place Order</button>
      </div>
    </div>
    </form>
  </div>
</div>

<div class="modal fade" id="exampleModal5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div id="cartlistload3" class="modal-dialog modal-xl cartload3">
      <form action="{{route('admin.placeorder')}}" method="post">
          @csrf

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Hold Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
      </div>
      <div id="cartlistload5" class="modal-body cartload5" style="padding-left:0px;">
      <div class="row">
            <div class="col-md-3 paytype" style="padding-left:0px;padding-right:0px;">
                <ul>
                    <?php
                    $holdorder=DB::table('holdorders')->get();
                    ?>
                    @if(count($holdorder)>0)
                    @foreach($holdorder as $order)
                    <li><a href="javascript:void(0)" id="holdorderdetails" data-id="{{$order->id}}">>> {{$order->order_id}} {{$order->oids}}</a><span><a href="{{route('admin.deleteholdorder',$order->id)}}" style="margin-left:40px;text-align:end;"><i class="fa fa-trash"></i></a></span></li>
                    @endforeach
                    @endif
                </ul>
            </div>
            <div class="col-md-9 orderdetails">
                <p style="font-size:20px;text-align:center">Order Details:</p>
                <table class="table" id="customerdetails" width="100%">

                </table>
                <div class="table-responsive">
                    <table class="table" width="100%">
                        <thead id="productlisthead">

                        </thead>
                        <tbody id="subtotals">

                            <!-- <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Paid Amount</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem">100</td>
                            </tr>
                            <tr>
                                <td style="text-align:end;padding:0.15rem 1.5rem">Due Amount</td>
                                <td style="text-align:end;padding:0.15rem 1.5rem">100</td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer" id="editorderbtn">

      </div>
    </div>
    </form>
  </div>
</div>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">


<div class="container-fluid navbars" style="background-color:#ff5733;color:#fff;border-radius:0px;height:6vh;">

      <nav >
          <div class="row">
              <!--<div class="col-md-1">-->
                  <!--<a href="{{URL::to('/')}}">dashboard</a>-->
              <!--</div>-->
              <div class="col-md-3">
                  <img src="{{asset('admin/assets/img/Flag-Bangladesh.webp')}}" height="15px"> &nbsp;<span id="time"></span>
              </div>
              <div class="col-md-4">

              </div>
              <div class="col-md-5 postop">
                  <ul>
                      <li><a href="{{URL::to('/')}}">Dashboard</a></li>
                      <li><a href="" data-bs-toggle="modal" data-bs-target="#exampleModal3"><i class="fa fa-keyboard"></i></a></li>
                      <li><a href="" data-bs-toggle="modal" data-bs-target="#exampleModal5"><i class='fa fa-hand-paper-o'></i></a></li>
                      <li><a href="{{URL::to('/')}}/invoice">Invoice</a></li>
                      <li><a  onclick="toggleFullScreenMode(); return false;" style="cursor:pointer"><i class="fa fa-expand" aria-hidden="true"></i></a></li>
                  </ul>
              </div>
          </div>
      </nav>
</div>
<div class="container-fluid">
    <div class="row" style="background-color:#fdddaa">
        <div class="col-md-7 topleft">
            <div class="row">
                <div class="col-md-1" style="padding-right:0px;">
                <img src="https://img.icons8.com/color/28/000000/search--v1.png" style="padding:5px;"/>
                </div>
                <div class="col-md-4" style="padding-left:0px;">
                    <input type="text" name="search" id="searchpos" placeholder="Search/Barcode Scan" style="border:0px !important;">
                </div>
                <div class="col-md-3">

                </div>
                <div class="col-md-4">
                    <!--<select name="category" class="form-control" style="border-radius:20px;">-->
                    <!--    <option>View All</option>-->
                    <!--    <option>Category 1</option>-->
                    <!--</select>-->
                </div>
            </div>
        </div>
        <div class="col-md-3 walkingcustomer">
            <img src="https://img.icons8.com/ios-glyphs/25/000000/user-male-circle.png"/>
            @if(Session::has('customer_id'))
            <span style="font-size:14px;padding:0px 10px;color:black;" id="customer" class="cusload">{{Session::get('customer_name')}}({{Session::get('customer_phone')}})</span>
            @else
            <span style="font-size:14px;padding:0px 10px;color:black;" id="customer" class="cusload">Customer(01303204773)</span>
            @endif
            <button type="button" style="padding:0px;margin-bottom: 0px;" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <img src="https://img.icons8.com/ios-glyphs/25/000000/add-user-group-woman-man.png"/>
            </button>
        </div>
        <!--<div class="col-md-1 topdue">-->
        <!--    Due-->
        <!--    <div style="padding:3 10px;border:1px solid #ff9090;border-radius:20px;background-color:#dd4b39">0.00</div>-->
        <!--</div>-->
        <!--<div class="col-md-1 topplus">-->
            <!--<img src="https://img.icons8.com/ios-glyphs/60/000000/plus-math.png"/>-->
        <!--</div>-->
    </div>
    <div class="row" style="height:61vh;">
        <div class="col-md-7 productlist">
            <div class="row" id="productsrow">
                @if(isset($products) && count($products)>0)
                @foreach($products as $keysss=>$product)
                <div class="col-md-2" style="padding-left:5px;padding-right:5px;">
                    <div class="card" style="margin-top:5px;border:1px solid #03ce71;box-shadow:0 0 5px rgba(0,0,0,.9);border-radius:0px;">
                        <div class="card-body">
                        @if($product->images)
                            @php
                                $images=explode(',',$product->images);
                            @endphp
                            @foreach($images as $key=>$image)
                            <?php if($key=="1"){ ?>
                            <img src="{{URL::to('/')}}/assets/images/product/{{$image}}" style="height:60px !important;" class="img-fluid" >
                            <?php }
                                    else{
                                    ?>

                                <?php } ?>
                                    @endforeach
                                @endif
                        </div>
                        <?php
                        $veriant=DB::table('veriants')->where('pid',$product->id)->get();
                        ?>

                        <div class="card-footer" style="padding:0.5rem 3px;background-color:black;color:#fff;border-radius:0px;font-size:13px;">

                            <a @if(isset($veriant) && count($veriant)>0) data-bs-toggle="modal" data-bs-target="#exampleModals{{$keysss}}" @else id="addtocart" @endif  data-id="{{$product->id}}" style="color:#fff;cursor:pointer">+ Add To Cart</a>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

            </div>
        </div>
        <div class="col-md-5 carttable" style="padding:0px">
            <div class="table-responsive table1">
                <table class="table cartload" width="100%" id="cartlistload">
                    <thead>
                        <tr>
                            <th width="15%">Quantity</th>
                            <th width="45%">Product</th>
                            <th width="15%">Price</th>
                            <th width="25%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody >
                        <?php $discount1=0;
                        $reg_price=0;
                        ?>
                    @if(Cart::instance('cart')->count()>0)
                    @foreach (Cart::instance('cart')->content() as $item)
                        <tr style="background-color:#ccc">
                            <td>
                            <!-- onclick="incrementValue()"onclick="decrementValue()" -->
                                <a class="plus" id="incrementcart" data-id="{{$item->rowId}}" style="text-align:center;padding:0px 25px;border:1px solid #bef1d9; background-color:#bef1d9;border-radius:5px;font-size:10px;cursor:pointer">+</a>
                                <input type="text" class="form-control" style="width:55%;height:30px;border-radius:40px;text-align:center;" name="quantity" value="{{$item->qty}}" maxlength="2" max="10" size="1" id="number">
                                <a class="minus" id="decrementcart" data-id="{{$item->rowId}}" style="text-align:center;padding:0px 25px;border:1px solid #fddeda; background-color:#fddeda;border-radius:5px;font-size:10px;cursor:pointer">-</a>
                            </td>
                            <td>
                                <p style="padding:20px 0px;font-size:14px;color:#000;font-weight:bold;">{{$item->model->name ?? ""}} ( @if($item->options->color) Color: {{ $item->options->color}} @endif @if($item->options->size) Size: {{ $item->options->size}} @endif @if($item->options->volume) {{ $item->options->volume}} {{ $item->options->unit}}  @endif )</p>
                            </td>
                            <td>
                                <p style="padding:20px 0px;font-size:14px;color:#000;font-weight:bold;">${{$item->model->regular_price}}</p>
                            </td>
                            <td>
                                <p style="padding:20px 0px;font-size:14px;color:#000;font-weight:bold;">${{$item->qty*$item->model->regular_price}}<a id="removefromcart" data-id="{{$item->rowId}}" style="padding:0px 10px;cursor:pointer;color:red;">X</a></p>
                            </td>
                        </tr>
                        <?php
                        $price=$item->qty*$item->model->regular_price;
                        $reg_price=$price+$reg_price;
                        ?>
                        <?php
                        if($item->options->has('discount')){
                        $discount=$item->qty*$item->options->discount;
                        $discount1=$discount+$discount1;
                        }
                        ?>
                        @endforeach
                        @endif
                    </tbody>
                    <input type="hidden" name="totalamountss" value="{{$reg_price ?? '0'}}">
                </table>
            </div>

            <div class="table-responsive table2" width="100%">
                <table class="table cartload1" id="cartlistload1">
                    <thead>
                        <tr>
                            <th width="30%">TOTAL ITEM</th>
                            <th width="20%">{{Cart::instance('cart')->count()}}</th>
                            <th width="30%">TOTAL</th>
                            <th width="20%">{{Cart::instance('cart')->subtotal()}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>DISCOUNT</td>
                            <td>
                                <input type="text" value="{{$discount1 ?? '0'}}" class="form-control" style="border-radius:25px;background-color:#ccc;height:30px;text-align:center" name="discount">
                            </td>
                            <td>TAX AMOUNT(%)</td>
                            <td>
                                <input type="text" value="@if(Session::has('tax')) {{Session::get('tax')}} @else {{Cart::instance('cart')->tax()}} @endif" class="form-control" style="border-radius:25px;background-color:#ccc;height:30px;text-align:center" name="tax">
                            </td>
                        </tr>
                        <tr>
                            <td>SHIPPING CHARGE</td>
                            <td>
                                <input type="text" value="@if(Session::has('shipping')) {{Session::get('shipping')}} @else 0 @endif" class="form-control" style="border-radius:25px;background-color:#ccc;height:30px;text-align:center" name="shipping">
                            </td>
                            <td>OTHER CHARGE</td>
                            <td>
                                <input type="text" value="@if(Session::has('other_charge')) {{Session::get('other_charge')}} @else 0 @endif" class="form-control" style="border-radius:25px;background-color:#ccc;height:30px;text-align:center" name="othercharge">
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>TOTAL PAYABLE</th>
                            <th><span id="totalpayable">@if(Session::has('payable_amount')) {{Session::get('payable_amount')}} @else {{Cart::instance('cart')->total()}} @endif</span></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="row footeron" style="height:13vh">
        <div class="col-md-7" style="background-color:#ff5733;">
            <ul style="width:100%">
            <!-- <span id="totalss"> -->
                <li style="width:65%;text-align:center">$<span style="font-size:40px;" id="cartlistload2" class="cartload2">@if(Session::has('payable_amount')) {{Session::get('payable_amount')}} @else {{Cart::instance('cart')->total()}} @endif</span></li>
                <!--<li style="width:25%;padding:25px 0px;"><span><input type="date" class="form-control" style="width:100%;height:40px;border-radius:25px;background-color:#fff;"></span></li>-->
                <!--<li style="width:10%;text-align:center;padding:30px 0px;"><span><img src="https://img.icons8.com/external-flatart-icons-outline-flatarticons/24/000000/external-comment-chat-flatart-icons-outline-flatarticons-2.png"/></span></li>-->
            </ul>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-6"  style="height:15vh;background-color:#04c46c;color:#fff;text-align:center;padding:20px 0px;font-size:25px;">
                <a class="btn" style="font-size:25px;color:#fff;" data-bs-toggle="modal" data-bs-target="#exampleModal1"><img src="https://img.icons8.com/ultraviolet/25/000000/paycheque.png"/> &nbsp;Pay Now</a>
                </div>
                <div class="col-md-6" style="height:15vh;background-color:#dd4b39;color:#fff;text-align:center;padding:25px 0px;font-size:25px;">
                <a class="btn" style="font-size:25px;color:#fff;" data-bs-toggle="modal" data-bs-target="#exampleModal4">Hold</a>
                </div>
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
function exportOrder(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   }
</script>
<script>
document.addEventListener("keydown", function(e) {
    if (e.altKey && (e.key === 'g' || e.key === 'G')){
        $('#exampleModal4').modal('toggle');
        e.preventDefault();
    }
    if (e.altKey && (e.key === 'h' || e.key === 'H')){
        $('#exampleModal5').modal('toggle');
        e.preventDefault();
    }
    if (e.altKey && (e.key === 's' || e.key === 'S')){
        $('#searchpos').focus();
        e.preventDefault();
    }
    if (e.altKey && (e.key === 'p' || e.key === 'P')){
        $('#exampleModal1').modal('toggle');
        e.preventDefault();
    }
    if (e.altKey && (e.key === 'c' || e.key === 'c')){
        $('#exampleModal').modal('toggle');
        e.preventDefault();
    }
});


$(document).on('click', '#veriant', function() {
    $url="/addveriantcart";
    debugger;
    var veriant_id = $(this).data('id');
    $(this).modal('hide');
    debugger;
    $.get($url,{veriant_id:veriant_id}, function(data){
        console.log(data);
        // toastr.success('Cart Add Successfully !');
        // window.location.reload();

        $('#cartlistload').load(location.href + ' .cartload');
        $('#cartlistload1').load(location.href + ' .cartload1');
        $('#cartlistload2').load(location.href + ' .cartload2');
        $('#cartlistload3').load(location.href + ' .cartload3');
        $('#cartlistload4').load(location.href + ' .cartload4');
        $('#cartlistload5').load(location.href + ' .cartload5');
    });
});
    $("input[name='search']").on("keyup",function () {
        debugger;
         $url="/searchproductss";
    var search=$('input[name=search]').val();
    var branch=$('input[name=branchid]').val();
    // if(search != ''){
        //   $('#productsrow').empty();
           $.get($url,{search:search,branch:branch}, function(data){
               console.log(data);
               if(!jQuery.isEmptyObject(data)){
                   $('#productsrow').empty();
                   $.each( data, function( key, value ) {
                       var col= `<div class="col-md-2" style="padding-left:5px;padding-right:5px;">
                            <div class="card" style="margin-top:5px;border:1px solid #03ce71;box-shadow:0 0 5px rgba(0,0,0,.9);border-radius:0px;">
                                <div class="card-body">
                                    <img src="https://admin.ebitans.com/assets/images/product/`+value.image+`" style="height:60px !important;" class="img-fluid" >
                                </div>
                                <div class="card-footer" style="padding:0.5rem 3px;background-color:black;color:#fff;border-radius:0px;font-size:13px;">
                                    <a <?php if(isset($veriant) && count($veriant)>0){ ?> data-bs-toggle="modal" data-bs-target="#exampleModals`+key+`" <?php }else{ ?> id="addtocart" <?php } ?> data-id="`+value.id+`" style="color:#fff;cursor:pointer">+ Add To Cart</a>
                                </div>
                            </div>
                        </div>`;
                        $('#productsrow').append(col);
                    });
                    //
               }

           });
        //   }

});
</script>
<script src="{{asset('admin/assets/js/pos.js')}}"></script>
@endpush
