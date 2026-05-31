<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice-#{{ $invoice->reference_no ?? $order->id }}</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        /* ===== Header (screen only) ===== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            border-bottom: 1px solid #ddd;
            background: #f8f9fa;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #333;
        }

        .page-info {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            margin-left: 10px;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-action {
            background: #0d6efd;
            color: #fff;
            padding: 8px 14px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-action:hover {
            background: #0b5ed7;
            color: #fff;
            text-decoration: none;
        }

        .share-icons a {
            margin: 2px;
        }

        /* ===== Printable container ===== */
        .page-wrap {
            width: 900px;
            margin: 0 auto;
            padding: 25px;
            overflow: hidden;
        }

        .header-row {
            display: flex;
            gap: 24px;
            align-items: flex-start;
        }

        .customer-box {
            width: 55%;
            border: 2px dashed #222;
            padding: 14px 16px;
            min-height: 140px;
        }

        .customer-box ul {
            margin: 0;
            padding-left: 22px;
        }

        .customer-box li {
            margin: 8px 0;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.4;
        }

        .customer-box li b {
            font-weight: 900;
        }

        .store-box {
            width: 45%;
            padding-top: 2px;
        }

        .brand {
            text-align: left;
            margin-bottom: 12px;
        }

        .store-logo {
            max-height: 60px;
            width: auto;
            display: block;
        }

        .store-meta {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.6;
        }

        .warn {
            margin-top: 12px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 15px;
            font-weight: 900;
        }

        .warn .icons {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding-top: 2px;
        }

        .date-row {
            margin-top: 12px;
            font-size: 16px;
            font-weight: 900;
        }

        .date-pill {
            display: inline-block;
            border: 2px solid #222;
            padding: 4px 10px;
            margin-left: 10px;
            font-weight: 900;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed;
            font-size: 14px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #cfd6df;
            padding: 10px;
            vertical-align: middle;
        }

        .invoice-table thead th {
            background: #d9d9d9;
            font-weight: 900;
            text-align: left;
        }

        .col-no {
            width: 60px;
            text-align: center;
        }

        .col-prod {
            width: auto;
        }

        .col-var {
            width: 170px;
            text-align: left;
            font-size: 13px;
            line-height: 1.25;
        }

        .col-ord {
            width: 100px;
            text-align: center;
        }

        .col-price {
            width: 100px;
            text-align: center;
        }

        .col-qty {
            width: 80px;
            text-align: center;
        }

        .col-total {
            width: 100px;
            text-align: right;
        }

        .prod-cell {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .prod-img {
            width: 55px;
            height: 55px;
            border: 1px solid #cfd6df;
            object-fit: cover;
            background: #fff;
        }

        .prod-name {
            font-size: 14px;
            font-weight: 700;
        }

        .qty-box {
            display: inline-flex;
            min-width: 26px;
            height: 22px;
            align-items: center;
            justify-content: center;
            border: 1px solid #cfd6df;
            font-weight: 900;
            padding: 0 8px;
        }

        /* Summary row */
        .summary-row td {
            padding: 12px 10px;
            font-weight: 900;
            background: #fff;
        }

        .summary-label-inline {
            text-align: center;
            white-space: nowrap;
        }

        .summary-qty {
            text-align: center;
        }

        .summary-ship {
            text-align: right;
        }

        /* Totals block */
        .totals-wrap {
            width: 97%;
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .totals {
            width: 320px;
            max-width: 320px;
            font-size: 16px;
        }

        .totals .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e3e7ee;
        }

        .totals .row:last-child {
            border-bottom: none;
        }

        .totals .label {
            font-weight: 900;
        }

        .totals .val {
            font-weight: 900;
            text-align: right;
            min-width: 120px;
        }

        .note {
            margin-top: 18px;
            font-size: 13px;
            font-style: italic;
            font-weight: 900;
        }

        .thankyou-section {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .thank-text {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            line-height: 1.4;
        }

        .qr-box img {
            width: 80px;
            height: 80px;
        }

        /* Print */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
            }

            #printable {
                padding: 12mm !important;
            }

            .page-wrap {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body>

    @php
        $hs = DB::table('headersettings')->where('store_id', $store->id)->first();

        // Courier (real source + fallbacks)
        $courierRow = \App\Models\CourierDelivery::query()
            ->where('merchant_order_id', $order->reference_no ?? null)
            ->orWhere('merchant_order_id', $order->id ?? null)
            ->orWhere('merchant_order_id', $order->order_no ?? null)
            ->latest('id')
            ->first();
        $courierText = $courierRow->courier_name ?? null;

        // Website (from store->url)
        $storeUrlRaw = $store->url ?? null;
        if (!empty($storeUrlRaw)) {
            $website = preg_match('~^https?://~i', $storeUrlRaw) ? $storeUrlRaw : ('https://' . $storeUrlRaw);
        } else {
            $website = url('/');
        }

        $totalQty = 0;
        foreach ($orderitems as $it) {
            $totalQty += (int) ($it->quantity ?? 0);
        }

        $shipping = (float) ($order->shipping ?? 0);
        $discount = (float) ($order->discount ?? 0);
        $paid = (float) ($order->paid ?? 0);
        $total = (float) ($order->total ?? 0);
        $due = isset($order->due) ? (float) $order->due : max(0, $total - $paid);

        $invoiceDate = $invoice->created_at ?? $order->created_at ?? now();

        // Dynamic Variant column check
        $hasVariantColumn = false;
        foreach ($orderitems as $it) {
            $hasSize = !empty($it->size);
            $hasColor = !empty($it->color);
            $hasUnit = !empty($it->unit) && !empty($it->volume);
            if ($hasSize || $hasColor || $hasUnit) {
                $hasVariantColumn = true;
                break;
            }
        }
    @endphp

    {{-- HEADER with Print + Share --}}
    <div class="page-header no-print">
        <h1 class="page-title">
            Invoice
            <small class="page-info">
                <i class="fa fa-angle-double-right"></i>
                ID: #{{ $invoice->reference_no ?? '' }}
            </small>
        </h1>

        <div class="action-buttons">
            <a class="btn-action" href="#" onclick="window.print(); return false;">
                <i class="fa fa-print"></i> Print
            </a>

            <div class="a2a_kit a2a_kit_size_32 a2a_default_style share-icons" data-a2a-url="{{ url()->current() }}"
                data-a2a-title="Invoice #{{ $invoice->reference_no ?? '' }}">
                <a class="a2a_button_whatsapp" data-toggle="tooltip" data-placement="top" title="Share to Whatsapp"></a>
                <a class="a2a_button_facebook_messenger" data-toggle="tooltip" data-placement="top"
                    title="Share to Messenger"></a>
                <a class="a2a_button_email" title="Share to Email"></a>
            </div>
            <script async src="https://static.addtoany.com/menu/page.js"></script>
        </div>
    </div>

    {{-- PRINTABLE AREA --}}
    <div id="printable" class="page-wrap">

        <div class="header-row">
            {{-- LEFT: Customer --}}
            <div class="customer-box">
                <ul>
                    <li><b>Name:</b> {{ $order->name ?? 'Not Available' }}</li>
                    <li><b>Mobile:</b> {{ $order->phone ?? 'Not Available' }}</li>
                    <li><b>Address:</b> {{ $order->edited_address ?? $order->address ?? 'Not Available' }}</li>
                    <li><b>Invoice No:</b> {{ $invoice->reference_no ?? $order->id }}</li>
                    <li><b>Courier:</b> {{ !empty($courierText) ? strtoupper($courierText) : 'Not Available' }}</li>
                </ul>
            </div>

            {{-- RIGHT: Store --}}
            <div class="store-box">
                <div class="brand">
                    @if(!empty($hs->logo))
                        <img src="{{ asset('assets/images/setting/' . $hs->logo) }}" class="store-logo" alt="Logo">
                    @else
                        <span style="font-weight:900;">{{ $store->name ?? '' }}</span>
                    @endif
                </div>

                <div class="store-meta">
                    <div><b>Office Address:</b> {{ $hs->address ?? 'Not Available' }}</div>
                    <div><b>Phone:</b> {{ $hs->phone ?? 'Not Available' }}</div>
                </div>

                <div class="warn">
                    <div class="icons">⚠️</div>
                    <div>কোনো ক্ষেত্রে পণ্য পছন্দ না হলে ডেলিভারি চার্জ দিয়ে রিটার্ন করবেন ⚠️</div>
                </div>

                <div class="date-row">
                    Date:
                    <span class="date-pill">{{ \Carbon\Carbon::parse($invoiceDate)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- PRODUCTS TABLE --}}
        <table class="invoice-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-prod">Product Details</th>

                    @if($hasVariantColumn)
                        <th class="col-var">Variant</th>
                    @endif

                    <th class="col-ord">Order No</th>
                    <th class="col-price">Price</th>
                    <th class="col-qty">Qty</th>
                    <th class="col-total">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orderitems as $oitm)
                    @php
                        $product = DB::table('products')->where('id', $oitm->product_id)->first();

                        // Image (support local + full URL + getPath + gallery_image)
                        $imgSrc = null;
                        $images = [];
                        $gallery = [];

                        if ($product) {
                            if (!empty($product->images))
                                $images = array_filter(explode(',', $product->images));
                            if (!empty($product->gallery_image))
                                $gallery = array_filter(explode(',', $product->gallery_image));
                        }

                        $mergedImages = array_values(array_unique(array_merge($gallery, $images)));

                        if (!empty($mergedImages)) {
                            $first = trim($mergedImages[0]);
                            if (filter_var($first, FILTER_VALIDATE_URL)) {
                                $imgSrc = $first;
                            } else {
                                if (function_exists('getPath')) {
                                    $imgSrc = getPath($first, 'assets/images/product');
                                } else {
                                    $imgSrc = asset('assets/images/product/' . $first);
                                }
                            }
                        }

                        $lineTotal = (float) ($oitm->quantity ?? 0) * (float) ($oitm->price ?? 0);

                        // Variant fields
                        $parts = [];
                        if (!empty($oitm->size)) {
                            $parts[] = '<b>Size:</b> ' . e($oitm->size);
                        }
                        if (!empty($oitm->color)) {
                            $colorDot = '<span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:' . e($oitm->color) . ';border:1px solid #cfd6df;vertical-align:middle;margin:0 6px 1px 0;"></span>';
                            $parts[] = '<b>Color:</b> ' . $colorDot . e($oitm->color);
                        }
                        if (!empty($oitm->unit) && !empty($oitm->volume)) {
                            $parts[] = '<b>Unit:</b> ' . e($oitm->volume) . ' ' . e($oitm->unit);
                        }
                        $variantHtml = count($parts) ? implode('<br>', $parts) : '';
                    @endphp

                    <tr>
                        <td class="col-no">{{ $loop->iteration }}</td>

                        <td class="col-prod">
                            <div class="prod-cell">
                                @if(!empty($imgSrc))
                                    <img class="prod-img" src="{{ $imgSrc }}" alt="" onerror="this.style.display='none'">
                                @else
                                    <div class="prod-img"
                                        style="display:flex;align-items:center;justify-content:center;color:#777;font-size:12px;">
                                        N/A
                                    </div>
                                @endif
                                <div class="prod-name">{{ $product->name ?? '' }}</div>
                            </div>
                        </td>

                        @if($hasVariantColumn)
                            <td class="col-var">{!! $variantHtml !!}</td>
                        @endif

                        <td class="col-ord">{{ $order->id }}</td>
                        <td class="col-price">{{ (float) ($oitm->price ?? 0) }}</td>
                        <td class="col-qty"><span class="qty-box">{{ (int) ($oitm->quantity ?? 0) }}</span></td>
                        <td class="col-total">{{ number_format($lineTotal, 0) }} Tk</td>
                    </tr>
                @endforeach

                {{-- Summary row --}}
                @php
                    // Keep label centered in the left "space", and values under Qty & Total
                    $labelColspan = $hasVariantColumn ? 5 : 4;
                @endphp
                <tr class="summary-row">
                    <td colspan="{{ $labelColspan }}" class="summary-label-inline">Total Quantity and Shipping Charge
                    </td>
                    <td class="summary-qty">{{ $totalQty }}</td>
                    <td class="summary-ship">{{ number_format($shipping, 0) }} Tk</td>
                </tr>

            </tbody>
        </table>

        {{-- TOTALS --}}
        <div class="totals-wrap">
            <div class="totals">
                <div class="row">
                    <div class="label">Total:</div>
                    <div class="val">{{ number_format($total, 0) }} Tk</div>
                </div>
                <div class="row">
                    <div class="label">Discount:</div>
                    <div class="val">{{ number_format($discount, 0) }} Tk</div>
                </div>
                <div class="row">
                    <div class="label">Paid:</div>
                    <div class="val">{{ number_format($paid, 0) }} Tk</div>
                </div>
                <div class="row">
                    <div class="label">Amount Due:</div>
                    <div class="val">{{ number_format($due, 0) }} Tk</div>
                </div>
            </div>
        </div>

        <div class="note">**No replace will be accepted after 3 days</div>

        {{-- THANK YOU + QR --}}
        <div class="thankyou-section">
            <div class="thank-text">
                Thank you for Shopping 🛍 , For Any Kind of information <br>
                Call Us : {{ $hs->phone ?? '' }}
                @if(!empty($store->url))
                    <br>Visit : {{ $store->url }}
                @else
                    <br>Visit : {{ $website }}
                @endif
            </div>

            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($website) }}"
                    alt="QR Code">
            </div>
        </div>

    </div>

</body>

</html>