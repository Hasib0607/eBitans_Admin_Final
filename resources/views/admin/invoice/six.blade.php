<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
    <link rel="stylesheet" href="{{asset('css/index6.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>
<div class="page-content container">
    <div class="page-header text-blue-d2" style="display: flex;align-items: baseline;justify-content: space-between;">
        <h1 class="page-title text-secondary-d1">
            Invoice
            <small class="page-info">
                <i class="fa fa-angle-double-right text-80"></i>
                ID: #{{$invoice->reference_no}}
            </small>
        </h1>

        <div class="page-tools">
            <div class="action-buttons d-flex">
                <a class="btn bg-white btn-light mx-1px text-95" href="#" onclick="printDiv('printable')"
                   data-title="Print">
                    <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                    Print
                </a>
                &nbsp;&nbsp;
                <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                    <a class="a2a_button_whatsapp" data-toggle="tooltip" data-placement="top"
                       title="Share to Whatsapp"></a>
                    <a class="a2a_button_facebook_messenger" data-toggle="tooltip" data-placement="top"
                       title="Share to Messenger"></a>
                    <a class="a2a_button_email"></a>
                </div>
                <script async src="https://static.addtoany.com/menu/page.js"></script>
            </div>
        </div>
    </div>
</div>
<div class="printable mt-4 mb-4" id="printable">
    <section id="header" class="container">
        <div class="row">
            <div class="col-md-4">

                @if ($store->plan_id == 6)
                    <img src="{{asset('logo-white.png')}}" alt="" width="130px">
                    @if(isset($hs->logo))
                        <img src="{{asset('logo-white.png')}}" alt="" width="120" class="imgsd">
                    @else
                        <h2>eBitans</h2>
                    @endif
                @else
                    @if(isset($hs->logo))
                        <img src="{{URL::to('/')}}/assets/images/setting/{{$hs->logo}}" alt="" width="120"
                             class="imgsd">
                    @else
                        <h2>{{$hs->website_name}}</h2>
                    @endif
                @endif

            </div>
            <div class="col-md-8">
                <p><span> <strong>No</strong> #{{$invoice->reference_no}}</span><span> <strong>Date</strong> {{date('d-m-Y', strtotime($invoice->created_at))}}</span>
                </p>
                <h1>INVOICE</h1>
            </div>
        </div>
    </section>
    <section id="headertop" class="container">
        <div class="row">
            <div class="col-4">
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-6">
                        <h3>From,</h3>
                        <strong>{{$hs->website_name}}</strong>
                        <p>{{$hs->address}}</p>
                    </div>
                    <div class="col-6">
                        <h3>To,</h3>
                        <p><strong>{{$order->name}}</strong></p>
                        <p><strong>{{$order->phone}}</strong></p>
                        <p><strong>{{$order->edited_address ?? $order->address ?? "Not Available"}}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="main" class="container mb-5" style="margin-top:40px; background-color:rgb(230, 228, 228);height:100%">
        <div class="row" style="min-height:425px;">
            <div class="col-4" style="padding-left:55px;padding-top:50px;">
                <?php
                $payment = DB::table('transactions')->where('order_id', $order->id)->first();
                ?>
                <h4 style="padding-top:30px;padding-bottom:30px;">PAYMENT METHOD</h4>
                <br>
                @if($payment->mode=='cod')
                    <p>Cash On Delivery</p>
                @elseif($payment->mode=='bkash')
                    <br>
                    <br>
                    <h5>Bkash</h5>
                    <span>Number : {{$payment->number}}</span><br>
                    <span>Transaction ID: {{$payment->transaction_id}}</span>
                    <br>
                @elseif($payment->mode=='bkash')
                    <br>
                    <h5>Nagad</h5>
                    <span>Number : {{$payment->number}}</span><br>
                    <span>Transaction ID: {{$payment->transaction_id}}</span>
                @endif
            </div>
            <div class="col-8" style="padding-left: 0px;">
                <table class="table table1" width="100%" style="height:100%;">
                    <thead style="height:10%">
                    <tr>
                        <th id="firstrow" width="50%">DESCRIPTION</th>
                        <th width="17%">RATE</th>
                        <th width="17%">QTY</th>
                        <th width="16%">AMOUNT</th>
                    </tr>
                    </thead>
                    <tbody style="height:90%">
                    <?php $i = 1; ?>
                    @if(null!==$orderitems && count($orderitems)>0)
                        @foreach($orderitems as $key=>$oitm)
                            <tr>
                                <td id="firstrow">
                                    {{$oitm->name ?? "" }}

                                    <small class="px-4">
                                        @if(isset($oitm->color_name))
                                            Color: <strong>{{$oitm->color_name}}</strong>
                                        @else
                                            @if (isset($oitm->color) && !empty($oitm->color))
                                                Color: <span class="mr-3"
                                                             style="display:inline-block; background: {{ $oitm->color }}; width:20px;border-radius: 50%;"></span>
                                            @endif
                                        @endif

                                        @if (isset($oitm->size) && !empty($oitm->size))
                                            Size: <strong>{{ $oitm->size }}</strong>
                                        @endif

                                        @if (isset($oitm->unit) && !empty($oitm->unit))
                                            Unit: <strong>{{ $oitm->volume }} {{ $oitm->unit }}</strong>
                                        @endif
                                    </small>

                                    @if($key==(count($orderitems)-1))
                                    @else
                                        <hr>
                                    @endif
                                </td>
                                <td>{{$oitm->symbol}}{{$oitm->price  + $oitm->additional_price }}</td>
                                <td>{{$oitm->quantity}}</td>
                                <td>{{$oitm->symbol}}{{$oitm->quantity* ($oitm->price  + $oitm->additional_price)}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-4">

            </div>
            <div class="col-4" style="background-color:#dddd2a;">
                <table class="table mt-3">
                    <tr style="border: 0px;border-color: transparent;color: #fff;">
                        <td style="text-align:center;padding:2px;color:black">Tax</td>
                        <td style="text-align:center;padding:2px;color:black">{{$order->symbol}}{{$order->tax}}</td>
                    </tr>
                    <tr style="border: 0px;border-color: transparent;color: #fff;">
                        <td style="text-align:center;padding:2px;color:black">Shipping</td>
                        <td style="text-align:center;padding:2px;color:black">{{$order->symbol}}{{$order->shipping}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-4" style="background-color:#dddd2a;color:#fff">
                <table class="table mt-3">
                    <tr style="border: 0px;border-color: transparent;color: #fff;">
                        <td style="text-align:center;padding:2px;color:black">Subtotal</td>
                        <td style="text-align:center;padding:2px;color:black">{{$order->symbol}}{{$order->subtotal}}</td>
                    </tr>
                    <tr style="border: 0px;border-color: transparent;color: #fff;">
                        <td style="text-align:center;padding:2px;color:black">Discount</td>
                        <td style="text-align:center;padding:2px;color:black">{{$order->symbol}}{{$order->discount}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
    <section id="total" class="container">
        <div class="row">
            <div class="col-4">

            </div>
            <div class="col-4">
            </div>
            <div class="col-4">
                <table class="table">
                    <tr style="border: 0px;border-color: transparent;">
                        <th style="text-align:center;padding:2px;">Total</th>
                        <td style="text-align:center;padding:2px;">{{$order->symbol}}{{$order->total}}</td>
                    </tr>
                    @if (ModulusStatus($store->id, 106))
                        <tr style="border: 0px;border-color: transparent;">
                            <th style="text-align:center;padding:2px;">Paid</th>
                            <td style="text-align:center;padding:2px;">{{$order->symbol}}{{$order->paid}}</td>
                        </tr>
                        <tr style="border: 0px;border-color: transparent;">
                            <th style="text-align:center;padding:2px;">Due</th>
                            <td style="text-align:center;padding:2px;">{{$order->symbol}}{{$order->due}}</td>
                        </tr>
                    @endif

                </table>
                <div class="authorizesign" style="text-align: center;">
                    <div class="hrss"></div>
                    <h5>Authorised Sign</h5>
                </div>
            </div>
        </div>
    </section>
    <section id="total" class="container">
        <div class="row">
            <div class="col-12">
                <h4>Thank You</h4>
            </div>
            <hr>
            <div class="col-12">
                <span>
                    @if(isset($store->name))
                        <strong>{{  $store->name ?? "" }} | </strong>
                    @endif
                    @if(isset($hs->phone))
                        <strong>Tel. </strong>{{ $hs->phone ?? "" }}
                    @endif
                    @if(isset($hs->email))
                        <strong> | Mail. </strong> {{ $hs->email ?? "" }} <strong>
                    @endif
                            @if(isset($store->url))
                                | Web. </strong>{{ $store->url ?? "" }}<strong>
                    @endif
                            @if(isset($hs->address))
                                | Address. </strong>{{ $hs->address ?? "" }}</span>
                @endif
            </div>
        </div>
    </section>
    <script>
        function printDiv(printable) {
            var printContents = document.getElementById(printable).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
            crossorigin="anonymous"></script>
</body>
</html>
