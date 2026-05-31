<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="{{asset('css/index4.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/invoice.css')}}">
</head>
<body>
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
                <a class="btn bg-white btn-light mx-1px text-95" href="#" onclick="printDiv('printable')"
                   data-title="Print">
                    <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                    Print
                </a>
                &nbsp;&nbsp;
                <!-- AddToAny BEGIN -->
                <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                    <!--<a class="a2a_dd" href="https://www.addtoany.com/share"></a>-->
                    <a class="a2a_button_whatsapp" data-toggle="tooltip" data-placement="top"
                       title="Share to Whatsapp"></a>
                    <a class="a2a_button_facebook_messenger" data-toggle="tooltip" data-placement="top"
                       title="Share to Messenger"></a>
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
    </section>
    <section id="headermiddle" class="container">
        <div class="row">
            <div class="col-8 leftdiv">
                <h1>INVOICE</h1>
                <h4>{{$order->name}}</h4>
                <p>{{$order->phone}}</p>
                <p>{{$order->edited_address ?? $order->address ?? "Not Available"}}</p>

                <p>{{$order->email}}</p>
                <h4 class="billinfo">BILLED FROM:</h4>
                <p>{{$store->name}}</p>
                <p>{{$hs->phone ?? ''}}</p>
                <p>{{$hs->address ?? ''}}</p>
            </div>
            <div class="col-4 rightdiv">
                <h4>NO. <span class="invoiceno">{{$invoice->reference_no}}</span></h4>
                <h4>{{date('d-m-Y', strtotime($invoice->created_at))}}</h4>
            </div>
        </div>
    </section>
    <section id="table" class="container">
        <table class="table">
            <thead>
            <tr>
                <th>ITEM DESCRIPTION</th>
                <th>QTY</th>
                <th>PRICE</th>
                <th>TOTAL</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; ?>
            @if(null!==$orderitems && count($orderitems)>0)
                @foreach($orderitems as $key=>$oitm)
                    <tr>
                        <td>
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
                        </td>
                        <td>{{$oitm->quantity}}</td>
                        <td>{{$oitm->symbol}}{{$oitm->price  + $oitm->additional_price}}</td>
                        <td>{{$oitm->symbol}}{{$oitm->quantity*($oitm->price  + $oitm->additional_price)}}</td>
                    </tr>
                @endforeach
            @endif
            <!--<tr>-->
            <!--    <td>Lorem ipsum dolor sit amet.</td>-->
            <!--    <td>3</td>-->
            <!--    <td>$100</td>-->
            <!--    <td>$300</td>-->
            <!--</tr>-->
            <!--<tr>-->
            <!--    <td>Lorem ipsum dolor sit amet.</td>-->
            <!--    <td>3</td>-->
            <!--    <td>$100</td>-->
            <!--    <td>$300</td>-->
            <!--</tr>-->
            <!--<tr>-->
            <!--    <td>Lorem ipsum dolor sit amet.</td>-->
            <!--    <td>3</td>-->
            <!--    <td>$100</td>-->
            <!--    <td>$300</td>-->
            <!--</tr>-->
            <!--<tr class="last">-->
            <!--    <td>Lorem ipsum dolor sit amet.</td>-->
            <!--    <td>3</td>-->
            <!--    <td>$100</td>-->
            <!--    <td>$300</td>-->
            <!--</tr>-->
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th>Tax</th>
                <th> {{$order->symbol}}{{$order->tax}} </th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>Discount</th>
                <th> {{$order->symbol}}{{$order->discount}}  </th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>Shipping Charge</th>
                <th> {{$order->symbol}}{{$order->shipping}}  </th>
            </tr>

            <tr>
                <th></th>
                <th></th>
                <th>Subtotal</th>
                <th> {{$order->symbol}}{{$order->total}}  </th>
            </tr>
            @if (ModulusStatus($store->id, 106))
                <tr>
                    <th></th>
                    <th></th>
                    <th>Paid</th>
                    <th> {{$order->symbol}}{{$order->paid}}  </th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Due</th>
                    <th> {{$order->symbol}}{{$order->due}}  </th>
                </tr>
                <tr class="footlast">
                    <!--<th>A/C No. 0177356534<br>-->
                    <!--    One Bank Limited-->
                    <!--</th>-->
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            @endif

            </tfoot>
        </table>
    </section>
    <section id="footertop" class="container">
        <h4 class="text-center py-5">Thank You For Your Business</h4>
    </section>
    <section id="footer" class="container">
        <h4>{{$store->url}}</h4>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
        crossorigin="anonymous"></script>
</body>
</html>
