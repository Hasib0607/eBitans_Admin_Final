<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('css/invoice.css')}}">
    <title>Invoice</title>
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
    <div class="printable" id="printable">
        <div class="container px-0">
            <div class="row mt-4">
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center text-150">
                                <!--<i class="fa fa-book fa-2x text-success-m2 mr-1"></i>-->

                                @if ($store->plan_id == 6)
                                    <img src="{{asset('logo-white.png')}}" alt="" width="130px">
                                @else
                                    <img src="{{asset('assets/images/setting/'.$hs->logo)}}" alt="" width="130px">
                                @endif

                                <!--<span class="text-default-d3">{{$store->url}}</span>-->
                            </div>
                        </div>
                    </div>
                    <!-- .row -->

                    <hr class="row brc-default-l1 mx-n1 mb-4"/>

                    <div class="row">
                        <div class="col-sm-6">
                            <div>
                                <span class="text-sm text-grey-m2 align-middle">To:</span>
                                <span class="text-600 text-110 text-blue align-middle">{{$order->name}}</span>
                            </div>
                            <div class="text-grey-m2">
                                <div class="my-1">
                                    {{$order->edited_address ?? $order->address ?? "Not Available"}}
                                </div>
                                <div class="my-1">
                                    {{$order->email}}
                                </div>
                                <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"
                                                     style="transform: rotate(20deg);"></i> <b
                                        class="text-600">{{$order->phone}}</b></div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                            <hr class="d-sm-none"/>
                            <div class="text-grey-m2">
                                <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                    Invoice
                                </div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">ID:</span> #{{$invoice->reference_no}}</div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">Issue Date:</span>{{date('d-m-Y', strtotime($invoice->created_at))}}
                                </div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span
                                        class="text-600 text-90">Total:</span> {{$order->symbol}}{{$order->total}}</div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="mt-4">
                        <!--<div class="row text-600 text-white bgc-default-tp1 py-25">-->
                        <!--    <div class="d-none d-sm-block col-1">#</div>-->
                        <!--    <div class="col-9 col-sm-5">Description</div>-->
                        <!--    <div class="d-none d-sm-block col-4 col-sm-2">Qty</div>-->
                        <!--    <div class="d-none d-sm-block col-sm-2">Unit Price</div>-->
                        <!--    <div class="col-2">Amount</div>-->
                        <!--</div>-->
                        <?php /* ?>
                        <div class="text-95 text-secondary-d3">
                        <?php $i=1; ?>
                        @if(null!==$orderitems && count($orderitems)>0)
                        @foreach($orderitems as $key=>$oitm)
                            <div class="row mb-2 mb-sm-0 py-25 @if($key/2==0) @else bgc-default-l4 @endif">
                                <div class="d-none d-sm-block col-1">{{$i++}}</div>
                                <?php
                                $product=DB::table('products')->where('id',$oitm->product_id)->first();
                                ?>
                                @if(isset($product))
                                <div class="col-9 col-sm-5">{{$product->name}}</div>
                                @else
                                <div class="col-9 col-sm-5"></div>
                                @endif
                                <div class="d-none d-sm-block col-2">{{$oitm->quantity}}</div>
                                <div class="d-none d-sm-block col-2 text-95">৳{{$oitm->price}}</div>
                                <div class="col-2 text-secondary-d2">৳{{$oitm->quantity*$oitm->price}}</div>
                            </div>
                        @endforeach
                        @endif
                        </div>
                            <!--<div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">-->
                            <!--    <div class="d-none d-sm-block col-1">2</div>-->
                            <!--    <div class="col-9 col-sm-5">Web hosting</div>-->
                            <!--    <div class="d-none d-sm-block col-2">1</div>-->
                            <!--    <div class="d-none d-sm-block col-2 text-95">$15</div>-->
                            <!--    <div class="col-2 text-secondary-d2">$15</div>-->
                            <!--</div>-->

                            <!--<div class="row mb-2 mb-sm-0 py-25">-->
                            <!--    <div class="d-none d-sm-block col-1">3</div>-->
                            <!--    <div class="col-9 col-sm-5">Software development</div>-->
                            <!--    <div class="d-none d-sm-block col-2">--</div>-->
                            <!--    <div class="d-none d-sm-block col-2 text-95">$1,000</div>-->
                            <!--    <div class="col-2 text-secondary-d2">$1,000</div>-->
                            <!--</div>-->

                            <!--<div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">-->
                            <!--    <div class="d-none d-sm-block col-1">4</div>-->
                            <!--    <div class="col-9 col-sm-5">Consulting</div>-->
                            <!--    <div class="d-none d-sm-block col-2">1 Year</div>-->
                            <!--    <div class="d-none d-sm-block col-2 text-95">$500</div>-->
                            <!--    <div class="col-2 text-secondary-d2">$500</div>-->
                            <!--</div>-->
                        <div class="row border-b-2 brc-default-l2"></div>

                         <!--or use a table instead -->
    <?php */ ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                                <thead class="bg-none bgc-default-tp1">
                                <tr class="text-white">
                                    <th class="opacity-2">#</th>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th width="140">Amount</th>
                                </tr>
                                </thead>

                                <tbody class="text-95 text-secondary-d3">
                                <tr></tr>
                                <?php $i = 1; ?>
                                @if(null!==$orderitems && count($orderitems)>0)
                                    @foreach($orderitems as $key=>$oitm)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>
                                                {{$oitm->name ?? "" }} <br>
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
                                            <td class="text-95">{{$oitm->symbol}}{{$oitm->price + $oitm->additional_price}}</td>
                                            <td class="text-secondary-d2">
                                                {{$oitm->symbol}}{{$oitm->quantity*($oitm->price  + $oitm->additional_price)}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>


                        <div class="row mt-3">
                            <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                <!--Extra note such as company or payment information...-->
                            </div>

                            <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                <div class="row my-2">
                                    <div class="col-7 text-right">
                                        SubTotal
                                    </div>
                                    <div class="col-5">
                                        <span
                                            class="text-120 text-secondary-d1">{{$order->symbol}}{{$order->subtotal}}</span>
                                    </div>
                                </div>
                                <div class="row my-2">
                                    <div class="col-7 text-right">
                                        Discount
                                    </div>
                                    <div class="col-5">
                                        <span
                                            class="text-110 text-secondary-d1">{{$order->symbol}}{{$order->discount}}</span>
                                    </div>
                                </div>

                                <div class="row my-2">
                                    <div class="col-7 text-right">
                                        Tax
                                    </div>
                                    <div class="col-5">
                                        <span
                                            class="text-110 text-secondary-d1">{{$order->symbol}}{{$order->tax}}</span>
                                    </div>
                                </div>
                                <div class="row my-2">
                                    <div class="col-7 text-right">
                                        Shipping
                                    </div>
                                    <div class="col-5">
                                        <span
                                            class="text-110 text-secondary-d1">{{$order->symbol}}{{$order->shipping}}</span>
                                    </div>
                                </div>

                                <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                    <div class="col-7 text-right">
                                        Total Amount
                                    </div>
                                    @if (ModulusStatus($store->id, 106))
                                        <div class="col-5">
                                            <span
                                                class="text-110 text-success-d3 opacity-2">{{$order->symbol}}{{$order->total}}</span>
                                        </div>
                                        <div class="col-7 text-right">
                                            Paid Amount
                                        </div>
                                        <div class="col-5">
                                            <span
                                                class="text-110 text-success-d3 opacity-2">{{$order->symbol}}{{$order->paid}}</span>
                                        </div>
                                        <div class="col-7 text-right">
                                            Due Amount
                                        </div>
                                        <div class="col-5">
                                            <span
                                                class="text-110 text-success-d3 opacity-2">{{$order->symbol}}{{$order->due}}</span>
                                        </div>
                                    @else
                                        <div class="col-5">
                                            <span
                                                class="text-110 text-success-d3 opacity-2">{{$order->symbol}}{{$order->total}}</span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <hr/>

                        <div class="my-3">

                            <span class="text-secondary-d1 text-105">Thank you for your business, For Any Kind of information <br> Call Us : @if(isset($hs))
                                    {{$hs->phone}}
                                @endif or Visit : <a href="{{$store->url}}" target="_blank">{{$store->url}}</a></span>
                            <!--<a href="#" class="btn btn-info btn-bold px-4 float-right mt-3 mt-lg-0">Pay Now</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script>
    function printDiv(printable) {
        var printContents = document.getElementById(printable).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
</body>
</html>
