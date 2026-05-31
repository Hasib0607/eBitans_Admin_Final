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
    </style>

</head>

<body>
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
        <div class="invoice-header"
             style="border-right: 5px solid #ff5733;border-bottom: 1px solid #ff5733;padding: 10px 20px 10px 20px;">
            <div class="float-left site-logo" style="width: 130px; height:90px;">
                <img src="{{ asset('assets/images/setting/' . $hs->logo) }}" alt="" style="width: 100%;">
            </div>
            <div class="float-right site-address">
                @if ($store->plan_id == 6)
                    <h4>eBitans</h4>
                @else
                    <h4>{{ $store->name ?? 'Not Available' }}</h4>
                @endif
                <p style="line-height: 6px;font-weight: 300;">{{ $hs->address ?? 'Not Available' }}</p>
                <p style="line-height: 6px;font-weight: 300;">Phone: {{ $hs->phone ?? 'Not Available' }} </p>
                <p style="line-height: 6px;font-weight: 300;">Email: {{ $hs->email ?? 'Not Available' }} </p>
            </div>
            <div class="clearfix">
            </div>
        </div>

        <b></b>
        <div class="invoice-description">
            <div class="invoice-left-top float-left"
                 style="border-left: 5px solid #ff5733;padding-left: 30px; padding-top: 20px;">
                <strong>Invoice to</strong>
                <p>
                <h4>{{ $order->name ?? 'Not Available' }}</h4>
                </p>
                <div class="address" style="line-height: 8px;font-weight: 300;">
                    <p>
                        Address: {{ $order->edited_address ?? $order->address ?? 'Not Available' }}
                    </p>
                    <p>Phone: {{ $order->phone ?? 'Not Available' }}</p>
                    <p>Email: {{ $order->email ?? 'Not Available' }} </p>
                </div>
            </div>
            <div class="invoice-right-top float-right" style="margin-top: 70px; padding-right:30px;">
                <h3 style="color: #ff5733;">Invoice #{{ $invoice->reference_no }}</h3>
                <p>{{ $invoice->created_at }}</p>
            </div>
            <div class="clearfix">

            </div>
        </div>

        <div style="padding: 0px 30px">
            <h3>Products Information</h3>
            @if ($orderitems->count() > 0)
                <table class="table table-bordered table-stripe">
                    <thead style="background: #ff5733;">
                    <tr>
                        <th style="padding: 0.2rem;">No.</th>
                        <th style="padding: 0.2rem;">Product Title</th>
                        <th style="padding: 0.2rem;">Product Quantity</th>
                        <th style="padding: 0.2rem;">Unit Price</th>
                        <th style="padding: 0.2rem;">Total Price</th>
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
                                {{$oitm->code}} {{ $oitm->price }}
                            </td>
                            <td style="padding: 0.2rem;">
                                {{$oitm->code}} {{ $oitm->quantity * $oitm->price }}
                            </td>
                        </tr>
                    @endforeach
                    <tr {{ $order->subtotal <= 0 ? 'hidden' : '' }}>
                        <td colspan="3"></td>
                        <td style="padding: 0.2rem;"><strong>Subtotal:</strong></td>
                        <td colspan="2">
                            <strong>{{$order->code}} {{ $order->subtotal }}</strong>
                        </td>
                    </tr>
                    <tr {{ $order->discount <= 0 ? 'hidden' : '' }}>
                        <td colspan="3"></td>
                        <td style="padding: 0.2rem;"><strong>Discount:</strong></td>
                        <td colspan="2">
                            <strong>{{$order->code}} {{ $order->discount }}</strong>
                        </td>
                    </tr>
                    <tr {{ $order->tax <= 0 ? 'hidden' : '' }}>
                        <td colspan="3"></td>
                        <td style="padding: 0.2rem;"><strong>Tax:</strong></td>
                        <td colspan="2">
                            <strong>{{$order->code}} {{ $order->tax }}</strong>
                        </td>
                    </tr>
                    <tr {{ $order->shipping <= 0 ? 'hidden' : '' }}>
                        <td colspan="3"></td>
                        <td style="padding: 0.2rem;"><strong>Shipping Charge:</strong></td>
                        <td colspan="2">
                            <strong>{{$order->code}} {{ $order->shipping }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td style="padding: 0.2rem;"><strong>Total Amount:</strong></td>
                        <td colspan="2">
                            <strong>{{$order->code}} {{ $order->total }}</strong>
                        </td>
                    </tr>
                    @if (ModulusStatus($store->id, 106))
                        <tr>
                            <td colspan="3"></td>
                            <td style="padding: 0.2rem;"><strong>Paid Amount:</strong></td>
                            <td colspan="2">
                                <strong>{{$order->code}} {{ $order->paid }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="padding: 0.2rem;"><strong>Due Amount:</strong></td>
                            <td colspan="2">
                                <strong>{{$order->code}} {{ $order->due }}</strong>
                            </td>
                        </tr>
                    @endif

                    </tbody>
                </table>
            @endif

            <div style="margin-bottom: 30px">
                <div class="thanks mt-3" style="position: absolute; bottom: 40px;">
                    <h4
                        style="color: #ff5733; font-size: 25px; font-weight: normal; font-family: serif; margin-top: 20px;">
                        Thank you for your Orders...</h4>
                </div>
                <br>
                <div class="authority float-right mt-5 center"
                     style="position: absolute; bottom: 30px; right: 30px;">
                    <b>--------------------------------------------------</b>
                    <h5 style="margin-top: -10px; margin-left: 60px; color: black;">Authority Signature</h5>
                </div>
            </div>
        </div>
        <!-- main-panel ends -->

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
