<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OrderID-#{{ $order->id }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/invoice.css"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <style media="screen">
        .thanks {
            position: unset !important;
            margin-top: 35px;
        }

        .authority {
            position: unset !important;
        }

        #printable {
            height: 100vh;
        }

        @media print {
            tr.vendorListHeading {
                background-color: #1a4567 !important;
                -webkit-print-color-adjust: exact;
            }
        }

        @media print {
            .vendorListHeading th {
                color: white !important;
            }
        }
    </style>

</head>

<body>

    @php
        $hs = DB::table('headersettings')
            ->where('store_id', $store->id)
            ->first();
    @endphp


    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex flex-row-reverse align-self-center py-1 mb-2" style="background: black;">
                <a class="btn btn-info" href="#" onclick="printDiv('printable')" data-title="Print">
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

        <div id="printable" style="position: relative;">

            <div class="ok" style="position: relative;">
                <div class="invoice-header"
                    style="background: #e6e7e8;  -webkit-clip-path: polygon(0 0, 40% 0, 35% 100%, 0% 100%); clip-path: polygon(0 0, 40% 0, 35% 100%, 0% 100%);">
                    <div class="float-left site-logo" style="width: 130px; height:115px;">
                        <h1 style="font-size: 70px;font-weight: 900; color:black; padding-left: 20px;">Invoice</h1>
                    </div>
                    <div class="clearfix">
                    </div>
                </div>
                <div class="nd"
                    style="width: 100%; height:100%; background: rgb(0, 0, 0); position: absolute; top: 0px; -webkit-clip-path: polygon(30% 0, 100% 0%, 100% 59%, 35% 62%);  clip-path: polygon(30% 0, 100% 0%, 100% 59%, 35% 62%);">

                    @if ($store->plan_id == 6)
                    <h2 style="color: #e6e7e8; float: right; padding: 15px 20px;">eBitans
                    </h2>
                                @else
                                <h2 style="color: #e6e7e8; float: right; padding: 15px 20px;">{{ $store->name ?? 'Not Available' }}
                                </h2>
                                @endif
                </div>
                <div class="clearfix">
                </div>
            </div>


            <div style="position: absolute; right: 30px; top: 68px;text-align: end;">
                <h6 style="color: black; margin:0px;">Invoice #{{ $invoice->reference_no }}</h6>
                <p style="margin:0px;">{{ $invoice->created_at }}</p>
            </div>

            <b></b>
            <div class="invoice-description" style="padding: 0 30px;">
                <div class="invoice-left-top float-left">
                    <h6 class='mt-2'>Invoice to</h6>
                    <h4>{{ $order->name ?? 'Not Available' }}</h4>
                    <div class="address" style="line-height: 8px;font-weight: 300;">
                        <p> Address: {{ $order->address ?? 'Not Available' }} </p>
                        <p>Phone: {{ $order->phone ?? 'Not Available' }}</p>
                        <p>Email: {{ $order->email ?? 'Not Available' }} </p>
                    </div>
                </div>
                <div class="invoice-right-top float-right" style="margin-top: 20px;">
                    <h6>Invoice From</h6>
                    <h6>{{ $store->name ?? 'Not Available' }} </h6>
                    <p style="line-height: 6px;font-weight: 300;">Phone: {{ $hs->phone ?? 'Not Available' }}</p>
                    <p style="line-height: 6px;font-weight: 300;">Email: {{ $hs->email ?? 'Not Available' }} </p>
                    <p style="line-height: 6px;font-weight: 300;">{{ $hs->address ?? 'Not Available' }}</p>
                </div>
                <div class="clearfix">

                </div>
            </div>

            <div style="padding: 0 30px;">
                <h5>Products Information</h5>
                @if ($orderitems->count() > 0)
                    <table class="table table-bordered table-stripe">
                        <thead style="background: #ff5733;">
                            <tr>
                                <th style="padding: 0.2rem;">No.</th>
                                <th style="padding: 0.2rem;">Product Title</th>
                                <th style="padding: 0.2rem;">Size</th>
                                <th style="padding: 0.2rem;">Color</th>
                                <th style="padding: 0.2rem;">QNTY</th>
                                <th style="padding: 0.2rem;">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_price = 0;
                            @endphp
                            @foreach ($orderitems as $oitm)
                                @php
                                    $product = DB::table('products')
                                        ->where('id', $oitm->product_id)
                                        ->first();
                                @endphp
                                <tr>
                                    <td style="padding: 0.2rem;">{{ $loop->index + 1 }}</td>
                                    <td style="padding: 0.2rem;">
                                        {{ $product->name ?? '' }}
                                        <small class="px-4">
                                            @if (isset($oitm->unit))
                                                Unit: {{ $oitm->volume }} {{ $oitm->unit }}
                                            @endif
                                        </small>
                                    </td>
                                    <td style="padding: 0.2rem;">
                                        {{ $oitm->size ?? 0 }}
                                    </td>
                                    <td style="padding: 0.2rem;">
                                        <span class="mr-3" style="display:inline-block; background: {{ $oitm->color }}; width:20px;border-radius: 50%;">&nbsp </span>
                                    </td>
                                    <td style="padding: 0.2rem;">
                                        {{ $oitm->quantity }}
                                    </td>

                                    <td style="padding: 0.2rem;">
                                        BDT {{ $oitm->quantity * $oitm->price }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row float-right">
                        <div style="line-height: 10px;padding: 15px;">
                            <p {{ $order->subtotal <= 0 ? 'hidden' : '' }}><strong>Subtotal:</strong> BDT
                                {{ $order->subtotal }}</p>
                            <p {{ $order->discount <= 0 ? 'hidden' : '' }}><strong>Discount:</strong> BDT
                                {{ $order->discount }}</p>
                            <p {{ $order->tax <= 0 ? 'hidden' : '' }}><strong>Tax:</strong> BDT {{ $order->tax }}
                            </p>
                            <p {{ $order->shipping <= 0 ? 'hidden' : '' }}><strong>Shipping Charge:</strong> BDT
                                {{ $order->shipping }}</p>
                            <p {{ $order->total <= 0 ? 'hidden' : '' }}><strong>Total Amount:</strong> BDT
                                {{ $order->total }}</p>

                            @if (ModulusStatus($store->id, 106))
                                <p {{ $order->paid <= 0 ? 'hidden' : '' }}><strong>Paid Amount:</strong> BDT
                                    {{ $order->paid }}</p>
                                <p {{ $order->due <= 0 ? 'hidden' : '' }}><strong>Due Amount:</strong> BDT
                                    {{ $order->due }}</p>
                            @endif

                        </div>
                    </div>
                @endif
            </div>



            <div class="clearfix"> </div>



            <div style="width: 100%;position: absolute; bottom: 0px; left: 0px;" class="">
                <div class="" style="">
                    <div class="invoice-header"
                        style="background: #e6e7e8;width: 100%; position: absolute; bottom: 0px; left: 0px; -webkit-clip-path: polygon(0 0, 60% 0, 70% 100%, 0% 100%); clip-path: polygon(0 0, 60% 0, 70% 100%, 0% 100%)">
                        <div class="float-left site-logo" style="line-height: 6px; padding-left: 40px;padding-top: 15px;">
                            <p {{ $hs->facebook_link??'hidden' }}> {{ $hs->facebook_link }}</p>
                            <p {{ $store->url??'hidden' }}> {{ $store->url }}</p>
                            <p {{ $hs->email??'hidden' }}>{{ $hs->email }}</p>
                            <p {{ $hs->phone??'hidden' }}>{{ $hs->phone }}</p>
                        </div>

                        <div class="clearfix">
                        </div>
                    </div>
                    <div class="authority float-right mt-5 center row"
                        style="position: absolute; bottom: 10px; right: 15px; z-index:999;">
                        <div style="padding-right: 31px;">
                            <b>------------------------------------------</b>
                            <h5 style="margin-top: -10px; margin-left: 80px; color: black;">Authority Signature
                            </h5>
                        </div>

                        <div class="clearfix"> </div>
                    </div>
                </div>
            </div>

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
            integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
        </script>


</body>

</html>
