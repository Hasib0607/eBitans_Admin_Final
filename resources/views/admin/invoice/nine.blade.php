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
        .invoice-description p {
            margin-bottom: 10px;
        }

        .site-address h5 {
            font-size: 16px;
        }

        .receipt {
            width: 80mm;
            font-family: Arial, sans-serif;
            font-size: 15px;
            margin: 0 auto;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .receipt-table th, .receipt-table td {
            border-bottom: 1px dotted #000;
            padding: 4px 0;
            text-align: left;
        }

        .receipt-table th {
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }

        .receipt-table td {
            text-align: right;
        }

        .payment-details p {
            text-align: right;
            margin: 0;
        }

        .fontSize {
            font-size: 12px;
        }

        @media print {
            body {
                width: 80mm; /* Set the document width to 80mm */
                margin: 0;
                padding: 0;
            }

            #printDiv {
                width: 100%; /* Make sure the printDiv takes up the full width */
            }

            /* Hide elements you don't want to print, like buttons */
            .printBtn {
                display: none;
            }

            body * {
                display: none !important;
            }

            /* Show only the printDiv */
            #printDiv, #printDiv * {
                display: block !important;
                visibility: visible !important;
            }
        }
    </style>

</head>

<body>
<div class="container">
    <div id="printable" style="position: relative;">
        <div class="row d-flex justify-content-end">
            <div class="col-md-12">
                <div class="receipt printDiv" id="printDiv" style="margin-top: 15px">
                    <div class="logo" style="text-align: center; margin-bottom: 10px;">
                        <img src="{{ asset('assets/images/setting/' . $hs->logo) }}" alt="Store Logo"
                             style="width: 100px;">
                    </div>
                    <div class="title">
                        <h3 style="text-align: center; margin: 5px 0;font-size: 14px;text-transform: uppercase;">SALES
                            RECEIPT</h3>
                        <div class="site-address" style="text-align: center; font-size: 12px;">
                            @if ($store->plan_id == 6)
                                <h5>eBitans</h5>
                            @else
                                <h5>{{ $store->name ?? 'Not Available' }}</h5>
                            @endif
                            <p style="line-height: 0px;font-weight: 300;">{{ $hs->address ?? 'Not Available' }}</p>
                            <p style="line-height: 0px;font-weight: 300;">
                                Phone: {{ $hs->phone ?? 'Not Available' }} </p>
                            <p style="line-height: 0px;font-weight: 300;">
                                Email: {{ $hs->email ?? 'Not Available' }} </p>
                        </div>
                        <div class="invoice-description" style="font-size: 12px; margin: 25px 0;">
                            <div style="border-left: 2px solid #ff5733; padding-left: 10px; padding-top: 0px; }">
                                <strong>Invoice to</strong>
                                <p>Invoice #{{ $invoice->reference_no }}</p>
                                <p>{{ \Carbon\Carbon::parse($invoice->created_at)->format("Y-m-d h:i:s A") }}</p>
                                <h4>{{ $order->name ?? 'Not Available' }}</h4>
                                <div class="address" style="line-height: 8px;font-weight: 300;">
                                    <p style="line-height: 18px;">
                                        Address: {{ $order->edited_address ?? $order->address ?? 'Not Available' }}
                                    </p>
                                    <p>Phone: {{ $order->phone ?? 'Not Available' }}</p>
                                    <p>Email: {{ $order->email ?? 'Not Available' }} </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="receipt-table">
                        <thead>
                        <tr>
                            <th width="70%" class="fontSize" style="padding: 0 10px;">Item Description</th>
                            <th width="10%" class="fontSize">Qty</th>
                            <th width="20%" class="fontSize">Price</th>
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
                                <td style="padding: 0.2rem;text-align: left;">
                                    {{ $product->name ?? '' }}
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
                                <td style="padding: 0.2rem;">
                                    {{ $oitm->quantity }}
                                </td>
                                <td style="padding: 0.2rem;">
                                    {{$oitm->symbol}} {{ $oitm->quantity * $oitm->price }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="col-12 mt-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="fw-bold mb-0">Subtotal</p>
                            <p class="mb-0">{{$order->symbol}} {{ $order->subtotal }}</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="fw-bold mb-0">Discount</p>
                            <p class="mb-0">{{$order->symbol}} {{ $order->discount }}</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="fw-bold mb-0">Tax</p>
                            <p class="mb-0">{{$order->symbol}} {{ $order->tax }}</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="fw-bold mb-0">Shipping Charge</p>
                            <p class="mb-0">{{$order->symbol}} {{ $order->shipping }}</p>
                        </div>
                        <hr>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="fw-bold mb-0">Total</p>
                            <p class="mb-0 fw-bold">{{$order->symbol}} {{ $order->total }}</p>
                        </div>
                        @if (ModulusStatus($store->id, 106))
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Paid</p>
                                <p class="mb-0 fw-bold">{{$order->symbol}} {{ $order->paid }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold mb-0">Due</p>
                                <p class="mb-0 fw-bold">{{$order->symbol}} {{ $order->due }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="footer" style="text-align: center;margin-top: 10px;">
                        <p>THANK YOU</p>
                        <p>Receipt {{ $order->reference_no }} | Date:
                            {{ \Carbon\Carbon::parse($order->updated_at)->format('Y-m-d') }} | Time:
                            {{ \Carbon\Carbon::parse($order->updated_at)->format('h:i:s A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-content-center align-items-center"
         style="text-align: center;">
        <button class="printBtn" onclick="printDiv('printable')" style="cursor: pointer">Print</button>
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
