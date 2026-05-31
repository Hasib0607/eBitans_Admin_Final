@extends('admin.layouts.pos')
@push('styles')
<link href="{{asset('admin/assets/css/pos.css')}}" rel="stylesheet" />
<style>
#reader{
    position:fixed !important;
    /*left:40%;*/
    top:30%;
}
.footeron{
    height:10vh;
}
#productsection{
    height:61vh;
}
.productlist{
    height:61vh;
    overflow:auto;
}

    .sidenav{
        display:none !important;
    }
    @media only screen and (max-width:500px){
        .col-sm-6{
            width:50% !important;
        }
        #reader{
            top:20% !important;
            height:200px !important;
        }
        #expandscreen{
            display:none;
        }
        .navbars{
            height:80px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .navbars nav {
          margin-top: 0px !important;
        }
        .topnavbartime{
            text-align:end;
        }
        .postop{
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 9px;
        }
        .postop ul{
            float:unset;
            padding-left:0px;
            margin:0 auto;
        }
        .navbars a{
            font-size:16px;
            font-weight:500;
        }
        #searchpos{
            margin-left:10px;
        }
        .footeron {
          height: 13vh;
          margin-top: 517px;
        }
        .footer {
            margin-top:234px;
        }
        .carttable{
            margin-top:20px;
        }
    }

</style>
@endpush
@section('content')
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
<main class="main-content position-relative  border-radius-lg">


<div class="container-fluid navbars" style="background-color:#ff5733;color:#fff;border-radius:0px;height:6vh;">

      <nav >
          <div class="row">
              <!--<div class="col-md-1">-->
                  <!--<a href="{{URL::to('/')}}">dashboard</a>-->
              <!--</div>-->
              <div class="col-md-3 topnavbartime">
                  <img src="{{asset('admin/assets/img/Flag-Bangladesh.webp')}}" height="15px"> &nbsp;<span id="time"></span>
              </div>
              <div class="col-md-4">

              </div>
              <div class="col-md-5 postop">
                  <ul>
                      <li><a href="{{URL::to('/')}}">Dashboard</a></li>
                      <li><a href="" data-bs-toggle="modal" data-bs-target="#exampleModal3"><i class="fa fa-keyboard"></i></a></li>
                      <li><a href="" data-bs-toggle="modal" data-bs-target="#exampleModal5"><i class='fa fa-hand-paper-o'></i></a></li>
                      <!--<li><a id="btn-scan-qr1"><img src="https://uploads.sitepoint.com/wp-content/uploads/2017/07/1499401426qr_icon.svg" width="20"><a/></li>-->
                      <!--<li><a id="btn-scan-qr1"><img src="https://uploads.sitepoint.com/wp-content/uploads/2017/07/1499401426qr_icon.svg" width="20"><a/></li>-->
                      <li><a href="{{URL::to('/')}}/invoice">Invoice</a></li>
                      <!--<li><div id="qr-reader" style="width: 300px"></div></li>-->

                      <li id="expandscreen"><a  onclick="toggleFullScreenMode(); return false;" style="cursor:pointer"><i class="fa fa-expand" aria-hidden="true"></i></a></li>
                  </ul>
              </div>
          </div>
      </nav>
</div>
<div style="display:flex;justify-content:center;align-items:center">
<div style="position:fixed !important;max-width:330px;height:300px;z-index:999999999999999999;display:none;background-color:#fff" id="reader"></div>
</div>

<div class="container-fluid">
    <div class="row" style="background-color:#fdddaa">
        <div class="col-md-7 topleft">
            <div class="row">
                <div class="col-1" style="padding-right:0px;text-align:end">
                <img src="https://img.icons8.com/color/28/000000/search--v1.png" style="padding:5px;"/>
                </div>
                <div class="col-9" style="padding-left:0px;">
                    <input type="text" name="search" id="searchpos" class='form-control' placeholder="Search/Barcode Scan" style="border:0px !important;" autofocus>
                </div>
                <div class="col-2">
                    <!--<a id="btn-scan-qr1"><img src="https://uploads.sitepoint.com/wp-content/uploads/2017/07/1499401426qr_icon.svg" width="20"><a/>-->
                </div>
                <!--<div class="col-4">-->
                    <!--<select name="category" class="form-control" style="border-radius:20px;">-->
                    <!--    <option>View All</option>-->
                    <!--    <option>Category 1</option>-->
                    <!--</select>-->
                <!--</div>-->
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
    <div class="row" id="productsection">

        <!--<canvas hidden="" id="qr-canvas" style="position:fixed;width:200px;height:200px;z-index:999999999999999999;top:0px;left:50%"></canvas>-->

        <!--<div id="qr-result" hidden="">-->
        <!--    <b>Data:</b> <span id="outputData"></span>-->
        <!--</div>-->
        <div class="col-md-7 productlist">
            <div class="row" id="productsrow">
                @if(isset($products) && count($products)>0)
                @foreach($products as $keysss=>$product)
                <div class="col-sm-6 col-md-2 " style="padding-left:5px;padding-right:5px;">
                    <div class="card" style="margin-top:5px;border:1px solid #03ce71;box-shadow:0 0 5px rgba(0,0,0,.9);border-radius:0px;">
                        <div class="card-body" style="padding:5px;text-align:center">
                        @if($product->images)
                            @php
                                $images=explode(',',$product->images);
                            @endphp
                            @foreach($images as $key=>$image)
                            <?php if($key=="0"){ ?>
                            <img src="{{URL::to('/')}}/assets/images/product/{{$image}}" style="width:90%;height:100px !important;" class="img-fluid" >
                            <?php }
                                    else{
                                    ?>

                                <?php } ?>
                            @endforeach
                        @endif
                        <p style="font-size:13px;margin-top:10px;line-height:7px;">{{Str::of($product->name)->limit(20)}}</p>
                        <p style="font-size:12px;line-height:7px;font-weight:500">৳{{$product->regular_price}}</p>
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
                                <input type="text" class="form-control" style="width:55%;height:30px;border-radius:40px;text-align:center;margin-left:5px" name="quantity" value="{{$item->qty}}" maxlength="2" max="10" size="1" id="number">
                                <a class="minus" id="decrementcart" data-id="{{$item->rowId}}" style="text-align:center;padding:0px 25px;border:1px solid #fddeda; background-color:#fddeda;border-radius:5px;font-size:10px;cursor:pointer">-</a>
                            </td>
                            <td>
                                <p style="padding:20px 0px;font-size:14px;color:#000;font-weight:bold;"> {{Str::of($item->model->name)->limit(10)}}
                                <br>
                                ( @if($item->options->color) Color: {{ $item->options->color}} @endif @if($item->options->size)<br> Size: {{ $item->options->size}} @endif @if($item->options->volume) {{ $item->options->volume}} {{ $item->options->unit}}  @endif )</p>
                            </td>
                            <td>
                                <p style="padding:20px 0px;font-size:14px;color:#000;font-weight:bold;">৳{{$item->model->regular_price}}</p>
                            </td>
                            <td>
                                <p style="padding:20px 0px;font-size:14px;color:#000;font-weight:bold;">৳{{$item->qty*$item->model->regular_price}}<a id="removefromcart" data-id="{{$item->rowId}}" style="padding:0px 10px;cursor:pointer;color:red;">X</a></p>
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
    <div class="row footeron">
        <div class="col-md-7" style="background-color:#ff5733;">
            <!--<ul style="width:100%">-->
            <!-- <span id="totalss"> -->
                <!--<li style="width:100%;text-align:center">-->
                   <p style="display: flex;align-items: center;justify-content: center;margin-bottom: 0px;color: #fff;padding-bottom: 0px;height: 100%;font-weight:500"> ৳<span style="font-size:40px;" id="cartlistload2" class="cartload2">@if(Session::has('payable_amount')) {{Session::get('payable_amount')}} @else {{Cart::instance('cart')->total()}} @endif</span></p>
                <!--</li>-->
                <!--<li style="width:25%;padding:25px 0px;"><span><input type="date" class="form-control" style="width:100%;height:40px;border-radius:25px;background-color:#fff;"></span></li>-->
                <!--<li style="width:10%;text-align:center;padding:30px 0px;"><span><img src="https://img.icons8.com/external-flatart-icons-outline-flatarticons/24/000000/external-comment-chat-flatart-icons-outline-flatarticons-2.png"/></span></li>-->
            <!--</ul>           -->
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6"  style="height:10vh;background-color:#04c46c;color:#fff;text-align:center;padding:20px 0px;font-size:25px;">
                <a class="btn paynowbutton" style="font-size:25px;color:#fff;display: flex;align-items: center;justify-content: center;" data-bs-toggle="modal" data-bs-target="#exampleModal1"><img src="https://img.icons8.com/ultraviolet/25/000000/paycheque.png"/> &nbsp;Pay Now</a>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6" style="height:10vh;background-color:#dd4b39;color:#fff;text-align:center;padding:20px 0px;font-size:25px;">
                <a class="btn holdnowbutton" style="font-size:25px;color:#fff;display: flex;align-items: center;justify-content: center;" data-bs-toggle="modal" data-bs-target="#exampleModal4">Hold</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://rawgit.com/sitepoint-editors/jsqrcode/master/src/qr_packed.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
<script>
$("#btn-scan-qr1").on('click',function(){
    $("#reader").toggle();
})
    function onScanSuccess(decodedText, decodedResult) {
    // Handle on success condition with the decoded text or result.

$("#searchpos").val(`${decodedText}`);
$url="/searchproductss";
var search=`${decodedText}`;
var branch=$('input[name=branchid]').val();
$.get($url,{search:search,branch:branch}, function(data){
   console.log(data);
   if(!jQuery.isEmptyObject(data)){
       $('#productsrow').empty();
       $.each( data, function( key, value ) {
           var col= `<div class="col-sm-6 col-md-2 " style="padding-left:5px;padding-right:5px;">
                <div class="card" style="margin-top:5px;border:1px solid #03ce71;box-shadow:0 0 5px rgba(0,0,0,.9);border-radius:0px;">
                    <div class="card-body" style="padding:5px;text-align:center">
                        <img src="https://admin.ebitans.com/assets/images/product/`+value.image+`" style="width:90%;height:100px !important;" class="img-fluid" >
                        <p style="font-size:13px;margin-top:10px;line-height:7px;">`+value.name.substring(0, 15)+`</p>
                        <p style="font-size:12px;line-height:7px;font-weight:500">৳`+value.regular_price+`</p>
                    </div>
                    <div class="card-footer" style="padding:0.5rem 3px;background-color:black;color:#fff;border-radius:0px;font-size:13px;">
                        <a <?php if(isset($veriant) && count($veriant)>0){ ?> data-bs-toggle="modal" data-bs-target="#exampleModals`+key+`" <?php }else{ ?> id="addtocart" <?php } ?> data-id="`+value.id+`" style="color:#fff;cursor:pointer">+ Add To Cart</a>
                    </div>
                </div>
            </div>`;
            $('#productsrow').append(col);
        });
   }
});
    console.log(`Scan result: ${decodedText}`);
    // html5QrcodeScanner.clear();
    $("#reader").hide();
}
const formatsToSupport = [
  Html5QrcodeSupportedFormats.QR_CODE,
  Html5QrcodeSupportedFormats.UPC_A,
  Html5QrcodeSupportedFormats.UPC_E,
  Html5QrcodeSupportedFormats.CODE_39,
  Html5QrcodeSupportedFormats.CODE_128,
  Html5QrcodeSupportedFormats.UPC_E,
  Html5QrcodeSupportedFormats.UPC_EAN_EXTENSION,
];

var html5QrcodeScanner = new Html5QrcodeScanner(
	"reader", { formatsToSupport: [ Html5QrcodeSupportedFormats.CODE_128 ] });
	/** Format of detected code. */
const config = { fps: 10, qrbox: { width: 250, height: 250 },facingMode: { exact: "environment"} };
// html5QrcodeScanner.start({ facingMode: { exact: "environment"} }, config, onScanSuccess);
html5QrcodeScanner.render(onScanSuccess);
</script>
<script>
var qrcode = window.qrcode;

const video = document.createElement("video");
const canvasElement = document.getElementById("qr-canvas");
const canvas = canvasElement.getContext("2d");

const qrResult = document.getElementById("qr-result");
const outputData = document.getElementById("outputData");
const btnScanQR = document.getElementById("btn-scan-qr");
const btnScanQR1 = document.getElementById("btn-scan-qr1");

let scanning = false;
qrcode.callback = (res) => {
  if (res) {
    outputData.innerText = res;
    scanning = false;

    video.srcObject.getTracks().forEach(track => {
      track.stop();
    });

    qrResult.hidden = false;
    btnScanQR.hidden = false;
    canvasElement.hidden = true;
  }
};
btnScanQR.onclick = () =>
  navigator.mediaDevices
    .getUserMedia({ video: { facingMode: "environment" } })
    .then(function(stream) {
      scanning = true;
      if(qrResult.hidden===true){
          qrResult.hidden = false;
          btnScanQR.hidden = false;
          canvasElement.hidden = true;
      }else{
          qrResult.hidden = true;
          btnScanQR.hidden = false;
          canvasElement.hidden = false;
      }
      video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
      video.srcObject = stream;
      video.play();
      tick();
      scan();
    });

function tick() {
  canvasElement.height = video.videoHeight;
  canvasElement.width = video.videoWidth;
  canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

  scanning && requestAnimationFrame(tick);
}
function scan() {
  try {

    qrcode.decode();
    console.log(qrcode.decode());
  } catch (e) {
    setTimeout(scan, 300);
  }
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
                       var col= `<div class="col-sm-6 col-md-2 " style="padding-left:5px;padding-right:5px;">
                            <div class="card" style="margin-top:5px;border:1px solid #03ce71;box-shadow:0 0 5px rgba(0,0,0,.9);border-radius:0px;">
                                <div class="card-body" style="padding:5px;text-align:center">
                                    <img src="https://admin.ebitans.com/assets/images/product/`+value.image+`" style="width:90%;height:100px !important;" class="img-fluid" >
                                    <p style="font-size:13px;margin-top:10px;line-height:7px;">`+value.name.substring(0, 15)+`</p>
                                    <p style="font-size:12px;line-height:7px;font-weight:500">৳`+value.regular_price+`</p>
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
