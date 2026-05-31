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
        .table td,
        .table th {
            padding: 0.2rem;
        }

        .invoice-header {
            padding: 10px 20px 10px 20px;
            border-right: 5px solid #ff5733;
            border-bottom: 1px solid #ff5733;
        }

        .invoice-right-top h1 {
            color: #ff5733;
        }

        .invoice-left-top {
            border-left: 5px solid #ff5733;
            padding-left: 20px;
            padding-top: 20px;
        }

        thead {
            background: #ff5733;
            color: white;
        }

        .authority h5 {
            margin-top: -10px;
            margin-left: 60px;
            color: black;
        }

        .thanks h4 {
            color: #ff5733;
            font-size: 25px;
            font-weight: normal;
            font-family: serif;
            margin-top: 20px;
        }

        .site-address p {
            line-height: 6px;
            font-weight: 300;
        }

        .address {
            line-height: 8px;
            font-weight: 300;
        }
    </style>

</head>

<body>

@php
    $hs = DB::table('headersettings')
        ->where('store_id', $store->id)
        ->first();
@endphp

<div class="invoice-header">
    <div class="float-left site-logo">
        <img src="{{ asset('assets/images/setting/' . $hs->logo) }}" alt=""
             style="width: 130px; height:90px;">
    </div>
    <div class="float-right site-address">
        <h4>{{ $store->name ?? 'Not Available' }}</h4>
        <p>{{ $hs->address ?? 'Not Available' }}</p>
        <p>Phone: {{ $hs->phone ?? 'Not Available' }} </p>
        <p>Email: {{ $hs->email ?? 'Not Available' }} </p>
    </div>
    <div class="clearfix">
    </div>
</div>


<b></b>
<div class="invoice-description">
    <div class="invoice-left-top float-left">
        <strong>Invoice to</strong>
        <p>
        <h4>{{ $order->name ?? 'Not Available' }}</h4>
        </p>
        <div class="address">
            <p>
                Address: {{ $order->edited_address ?? $order->address ?? 'Not Available' }}
            </p>
            <p>Phone: {{ $order->phone ?? 'Not Available' }}</p>
            <p>Email: {{ $order->email ?? 'Not Available' }} </p>
        </div>
    </div>
    <div class="invoice-right-top float-right">
        <h3>Invoice #{{ $invoice->reference_no }}</h3>
        <p>{{ $invoice->created_at }}</p>
        {{-- <p>{{date('d-m-Y', strtotime($invoice->created_at))}}</p> --}}
    </div>
    <div class="clearfix">

    </div>
</div>

<h3>Products Information</h3>
@if ($orderitems->count() > 0)
    <table class="table table-bordered table-stripe">
        <thead>
        <tr>
            <th>No.</th>
            <th>Product Title</th>
            <th>Product Quantity</th>
            <th>Unit Price</th>
            <th>Total Price</th>
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
                <td>{{ $loop->index + 1 }}</td>
                <td>
                    {{ $product->name ?? '' }} <br>
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
                <td>
                    {{ $oitm->quantity }}
                </td>
                <td>
                    BDT {{ $oitm->price }}
                </td>
                <td>
                    BDT {{ $oitm->quantity * $oitm->price }}
                </td>
            </tr>
        @endforeach
        <tr {{ $order->subtotal <= 0 ? 'hidden' : '' }}>
            <td colspan="3"></td>
            <td><strong>Subtotal:</strong></td>
            <td colspan="2">
                <strong>BDT {{ $order->subtotal }}</strong>
            </td>
        </tr>
        <tr {{ $order->discount <= 0 ? 'hidden' : '' }}>
            <td colspan="3"></td>
            <td><strong>Discount:</strong></td>
            <td colspan="2">
                <strong>BDT {{ $order->discount }}</strong>
            </td>
        </tr>
        <tr {{ $order->tax <= 0 ? 'hidden' : '' }}>
            <td colspan="3"></td>
            <td><strong>Tax:</strong></td>
            <td colspan="2">
                <strong>BDT {{ $order->tax }}</strong>
            </td>
        </tr>
        <tr {{ $order->shipping <= 0 ? 'hidden' : '' }}>
            <td colspan="3"></td>
            <td><strong>Shipping Charge:</strong></td>
            <td colspan="2">
                <strong>BDT {{ $order->shipping }}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td><strong>Total Amount:</strong></td>
            <td colspan="2">
                <strong>BDT {{ $order->total }}</strong>
            </td>
        </tr>
        @if (ModulusStatus($store->id, 106))
            <tr>
                <td colspan="3"></td>
                <td><strong>Paid Amount:</strong></td>
                <td colspan="2">
                    <strong>BDT {{ $order->paid }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td><strong>Due Amount:</strong></td>
                <td colspan="2">
                    <strong>BDT {{ $order->due }}</strong>
                </td>
            </tr>
        @endif

        </tbody>
    </table>
@endif

<div class="thanks mt3">
    <h4>Thank you for your Orders...</h4>
</div>
<br>
<div class="authority float-right mt-5 center">
    <b>--------------------------------------------------</b>
    <h5>Authority Signature</h5>
</div>
<!-- main-panel ends -->

</body>

</html>
