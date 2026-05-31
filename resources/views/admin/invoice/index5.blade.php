<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="{{asset('css/index5.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/invoice.css')}}">
</head>
  <body>
      <?php
                        $hs=DB::table('headersettings')->where('store_id',$store->id)->first();
                        ?>
    <div class="page-content container">
    <div class="page-header text-blue-d2">
        <h1 class="page-title text-secondary-d1">
            Invoice
            <small class="page-info">
                <i class="fa fa-angle-double-right text-80"></i>
                ID: #{{$invoice->reference_no}}
            </small>
        </h1>

        <div class="page-tools">
            <div class="action-buttons d-flex">
                <a class="btn bg-white btn-light mx-1px text-95" href="#" onclick="printDiv('printable')" data-title="Print">
                    <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                    Print
                </a>
                &nbsp;&nbsp;
                <!-- AddToAny BEGIN -->
                <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                <!--<a class="a2a_dd" href="https://www.addtoany.com/share"></a>-->
                <a class="a2a_button_whatsapp" data-toggle="tooltip" data-placement="top" title="Share to Whatsapp"></a>
                <a class="a2a_button_facebook_messenger" data-toggle="tooltip" data-placement="top" title="Share to Messenger"></a>
                <a class="a2a_button_email"></a>
                </div>
                <script async src="https://static.addtoany.com/menu/page.js"></script>
                <!-- AddToAny END -->
                <!--<a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="PDF">-->
                <!--    <i class="mr-1 fa fa-file-pdf-o text-danger-m1 text-120 w-2"></i>-->
                <!--    Export-->
                <!--</a>-->
            </div>
        </div>
    </div>
    </div>
<div class="printable" id="printable">
    <section id="headertop" class="container">
        <div class="row">
            <div class="col-6 leftdiv">
                <div class="topblank"></div>
                <div class="invoicetext">
                    <h1>INVOICE</h1>
                </div>
            </div>
            <div class="col-6 rightdiv">
                @if ($store->plan_id == 6)
                                <img src="{{asset('logo-white.png')}}" alt="" width="130px">
                                @else
                                <img src="{{asset('assets/images/setting/'.$hs->logo)}}" alt="" width="120" class="imgsd">
                                @endif

            </div>
        </div>
    </section>
    <section id="headermiddle" class="container"></section>
    <section id="invoiceto" class="container">
        <div class="row">
            <div class="col-6 leftdiv">
                <h4>Invoice To:</h4>
                <h4 class="name">{{$order->name}}</h4>
                <p>{{$order->address}}</p>
                <p>{{$order->email}}</p>
                <p>{{$order->phone}}</p>
            </div>
            <div class="col-6 rightdiv">
                <h4>Invoice#&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$invoice->reference_no}}</h4>
                <h4>Date:&nbsp;&nbsp;&nbsp;&nbsp;{{date('d-m-Y', strtotime($invoice->created_at))}}</h4>
            </div>
        </div>
    </section>
    <section id="table" class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>SL.</th>
                    <th>Item Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; ?>
                    @if(null!==$orderitems && count($orderitems)>0)
                    @foreach($orderitems as $key=>$oitm)
                <tr>
                    <td>{{$i++}}</td>
                    <?php
                            $product=DB::table('products')->where('id',$oitm->product_id)->first();
                            ?>
                    <td>
                        {{$product->name ?? "" }}
                        <small class="px-4">
                            @if (isset($oitm->color))
                            Color: <span class="mr-3" style="display:inline-block; background: {{ $oitm->color }}; width:20px;border-radius: 50%;">&nbsp
                            </span>
                        @endif

                        @if (isset($oitm->size))
                            Size: {{ $oitm->size }}
                        @endif

                        @if (isset($oitm->unit))
                                Unit: {{ $oitm->volume }} {{ $oitm->unit }}
                        @endif
                           </small>
                    </td>
                    <td>৳{{$oitm->price  + $oitm->additional_price}}</td>
                    <td>{{$oitm->quantity}}</td>
                    <td>৳{{$oitm->quantity*($oitm->price  + $oitm->additional_price)}}</td>
                </tr>
                @endforeach
                    @endif
                <!--<tr>-->
                <!--    <td>2</td>-->
                <!--    <td>Lorem Ipsum Dolar</td>-->
                <!--    <td>$100</td>-->
                <!--    <td>5</td>-->
                <!--    <td>$500</td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--    <td>3</td>-->
                <!--    <td>Lorem Ipsum Dolar</td>-->
                <!--    <td>$100</td>-->
                <!--    <td>5</td>-->
                <!--    <td>$500</td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--    <td>4</td>-->
                <!--    <td>Lorem Ipsum Dolar</td>-->
                <!--    <td>$100</td>-->
                <!--    <td>5</td>-->
                <!--    <td>$500</td>-->
                <!--</tr>-->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </section>
    <section id="bottomtop" class="container">
        <div class="row">
            <div class="col-6 leftdiv">
                <h5>Terms & Conditions</h5>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Similique soluta quibusdam autem omnis repellendus rerum dignissimos eum nam maiores! Praesentium aliquid officia natus doloribus eum.</p>
                <!--<h5>Payment Info:</h5>-->
                <!--<table class="table">-->
                <!--    <tr>-->
                <!--        <td>Account No:</td>-->
                <!--        <td>012453675765</td>-->
                <!--    </tr>-->
                <!--    <tr>-->
                <!--        <td>A/C Name:</td>-->
                <!--        <td>Rakibul Hasan</td>-->
                <!--    </tr>-->
                <!--    <tr>-->
                <!--        <td>Bank Details</td>-->
                <!--        <td>Add Your Details</td>-->
                <!--    </tr>-->
                <!--</table>-->
                <div class="hrs"></div>

            </div>
            <div class="col-2"></div>
            <div class="col-4 rightdiv">
                <table class="table" style="float:right;">
                    <tr>
                        <td>Subtotal</td>
                        <td>৳{{$order->subtotal}}</td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <td>৳{{$order->tax}}</td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td>৳{{$order->discount}}</td>
                    </tr>
                    <tr>
                        <td>Shipping Charge</td>
                        <td>৳{{$order->shipping}}</td>
                    </tr>
                    <tr class="hr">
                        <td>Total</td>
                        <td>৳{{$order->total}}</td>
                    </tr>
                    @if (ModulusStatus($store->id, 106))
                        <tr class="hr">
                            <td>Paid</td>
                            <td>৳{{$order->paid}}</td>
                        </tr>
                        <tr class="hr">
                            <td>Due</td>
                            <td>৳{{$order->due}}</td>
                        </tr>
                    @endif

                </table>
                <div class="authorizesign" style="float:right">
                    <div class="hrss"></div>
                    <h5>Authorised Sign</h5>
                </div>
            </div>
        </div>
    </section>
    <section id="footer" class="container">
        <h5>Thank You For Your Business</h5>
    </section>
</div>
    <script>
        function printDiv(printable) {
             var printContents = document.getElementById(printable).innerHTML;
             var originalContents = document.body.innerHTML;
             document.body.innerHTML = printContents;
             window.print();
             document.body.innerHTML = originalContents;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>
