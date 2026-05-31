<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Custom CSS for print and additional styles -->
    <style>
        /* ====== Base ====== */
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
        }

        /* ====== Table ====== */
        table {
            width: 100%;
            font-size: 18px;
            border-spacing: 0;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 6px 10px;
            border: 1px solid #000;
            text-align: right;
            /* all columns right aligned */
            vertical-align: middle;
            white-space: nowrap;
            /* prevents shifting */
        }

        thead tr {
            background-color: #f4765c;
            color: #fff;
        }

        /* ====== Section Header (Package + Print Button) ====== */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }

        .section-header h4 {
            margin: 0;
            font-weight: 700;
        }

        .invoice-section {
            margin-top: 10px;
            margin-bottom: 14px;
        }

        .section-title {
            margin: 0 0 6px 0;
            /* close to table */
            padding: 0;
            font-weight: 700;
            text-align: right;
            /* keep your current alignment */
        }

        .invoice-table {
            margin-top: 0;
            /* remove extra gap */
        }

        /* ====== Footer ====== */
        .invoice-footer {
            text-align: center;
            padding-top: 30px;
            padding-bottom: 20px;
            font-size: 14px;
            color: #555;
        }

        .footer-divider {
            width: 80px;
            height: 3px;
            background: #f4765c;
            border-radius: 5px;
        }

        .thank-you {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 6px 0;
            color: #333;
        }

        .company-name {
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 14px 0;
            color: #111;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            /* or center if you want */
            gap: 10px;
            /* SAME gap always */
            margin: 6px 0;
        }

        .icon-box {
            width: 28px;
            /* fixed box ensures same alignment */
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 28px;
            /* prevents shrinking */
        }

        .icon-svg {
            width: 20px;
            /* fixed icon size */
            height: 20px;
            display: block;
            /* removes baseline weirdness */
            color: #555;
            /* icon color */
        }

        .contact-text {
            line-height: 1.4;
        }

        .powered {
            margin-top: 15px;
            font-size: 12px;
            color: #aaa;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ====== Print ====== */
        @media print {
            .noPrint {
                display: none !important;
            }

            thead tr {
                background-color: #f4765c !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* ===== Action Buttons Wrapper ===== */
        .action-buttons {
            gap: 10px;
        }

        /* ===== Common Button Style ===== */
        .action-btn {
            text-decoration: none;
            border: none;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* ===== Print ===== */
        .print-btn {
            background: #f4765c;
        }

        .print-btn:hover {
            background: #e85b3a;
            transform: translateY(-2px);
        }

        /* ===== Email ===== */
        .email-btn {
            background: #4a6cf7;
        }

        .email-btn:hover {
            background: #3b5be0;
            transform: translateY(-2px);
        }

        /* ===== WhatsApp ===== */
        .whatsapp-btn {
            background: #25D366;
        }

        .whatsapp-btn:hover {
            background: #1ebe5d;
            transform: translateY(-2px);
        }

        @media print {
    .summary-section {
        margin-top: 40px;
    }
}

        /* ===== Hide in Print ===== */
        @media print {
            .noPrint {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="action-buttons noPrint" style="text-align: right; width: 80%; margin: 10px auto;">
        <button onclick="window.print()" class="action-btn print-btn">
            🖨 Print
        </button>

        <a href="mailto:{{ $data->user->email ?? '' }}?subject=Invoice {{ $data->order_no ?? '' }}"
            class="action-btn email-btn">
            📧 Email
        </a>

        @php
            $whatsAppMessage = urlencode("Hello, here is your invoice #" . ($data->order_no ?? '') . " from eBitans.");
            $whatsAppUrl = "https://wa.me/" . ($data->user->phone ?? '') . "?text=" . $whatsAppMessage;
        @endphp

        <a href="{{ $whatsAppUrl }}" target="_blank" class="action-btn whatsapp-btn">
            💬 WhatsApp
        </a>
    </div>
    </div>
    <div id="print" class="container" style="width: 80%; margin: 0 auto; background-color: white;">
        <div class="row summary-section">
            <div class="col-md-12" style="margin-top:10px;">
                <img width="160px;" src="/logo.png" alt="">
                <hr>
            </div>

            <div class="col-md-12 mt-2">
                @if(isset($data->store->name))
                    <h2>{{ $data->store->name }}</h2>
                @endif

                @if(isset($data->user->phone))
                    <p class="m-0"><strong>Phone:</strong> {{ $data->user->phone }}</p>
                @elseif(isset($data->user->email))
                    <p class="m-0"><strong>Email:</strong> {{ $data->user->email }}</p>
                @endif

                @if(isset($data->order_no))
                    <p class="m-0"><strong>Order number:</strong> {{ $data->order_no }}</p>
                @endif

                <p><strong>Order Date:</strong>
                    {{ \Carbon\Carbon::parse($data->created_at)->format("Y-m-d h:i:s A") }}
                </p>
            </div>

            @php
                $totalSub = 0;
                $countryCode = getVisitorInfo()->countryCode ?? "BD";
                $currencyLabel = $countryCode == "BD" ? "BDT" : "USD";
                $invoiceDueAmount = isset($selectedPaymentHistory) && $selectedPaymentHistory
                    ? (float) ($selectedPaymentHistory->current_due_amount ?? 0)
                    : (float) ($data->due_amount ?? 0);
            @endphp

            {{-- ================= PACKAGE SECTION ================= --}}
            <div class="col-md-12 invoice-section">
                <h4 class="section-title">Package</h4>

                <table class="invoice-table">
                    <colgroup>
                        <col style="width: 8%;">
                        <col style="width: 52%;">
                        <col style="width: 20%;">
                        <col style="width: 20%;">
                    </colgroup>

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package</th>
                            <th>Plan</th>
                            <th style="text-align: right;">Price</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(isset($data->package) && $data->package)
                            @php
                                $package = json_decode($data->package);
                                $price = $countryCode == "BD"
                                    ? ($package->offerprice ?? $package->price ?? 0)
                                    : ($package->usd_offer_price ?? $package->usd_price ?? 0);

                                $totalSub += (float) $price;

                                $m = (int) ($package->month ?? 0);

                                if ($m === 1) {
                                    $planText = 'Monthly Package';
                                } elseif ($m >= 2 && $m <= 11) {
                                    $planText = "Package for {$m} Months";
                                } elseif ($m === 12) {
                                    $planText = 'Yearly Package';
                                } else {
                                    $planText = 'Monthly Package'; // fallback
                                }
                            @endphp

                            <tr>
                                <td>1</td>
                                <td>{{ $planText }}</td>
                                <td>{{ $package->name ?? '' }}</td>
                                <td style="text-align:right;">{{ number_format((float) $price, 2) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="4" style="text-align:center;color:red;">No Package Selected</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>


            {{-- ================= ADDONS SECTION ================= --}}
            @if(!empty($data->addons) && is_array($data->addons))
                <div class="col-md-12 invoice-section" style="margin-top: 40px;">
                    <h4 class="section-title">Addons</h4>

                    <table class="invoice-table">
                        <colgroup>
                            <col style="width: 8%;">
                            <col style="width: 52%;">
                            <col style="width: 20%;">
                            <col style="width: 20%;">
                        </colgroup>

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>AddOns Name</th>
                                <th>Details</th>
                                <th style="text-align: right;">Price</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($data->addons as $addon)
                                @php
                                    $title = $addon['title'] ?? $addon['name'] ?? '';
                                    $details = $addon['monthorvalue'] ?? $addon['months'] ?? $addon['month'] ?? '';
                                    $name = $addon['name'] ?? '';

                                    if ($countryCode == "BD") {
                                        $addonPrice = (!empty($addon['offerprice']) && (float) $addon['offerprice'] > 0)
                                            ? (float) $addon['offerprice']
                                            : (float) ($addon['price'] ?? 0);
                                    } else {
                                        $addonPrice = (!empty($addon['usd_offer_price']) && (float) $addon['usd_offer_price'] > 0)
                                            ? (float) $addon['usd_offer_price']
                                            : (float) ($addon['usd_price'] ?? 0);
                                    }

                                    $totalSub += (float) $addonPrice;
                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $title }}
                                        @if(!empty($name))
                                            ({{ $name }})
                                        @endif
                                    </td>
                                    <td>{{ $details }}</td>
                                    <td style="text-align:right;">{{ number_format((float) $addonPrice, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- ================= SUMMARY SECTION ================= --}}
            <div class="col-md-12" style="text-align: right;">

                <h4>Subtotal: {{ number_format($totalSub, 2) }} {{ $currencyLabel }}</h4>

                @if(isset($data->coupon) && isset($data->total))
                    @php
                        $discountAmount = ($totalSub + ($data->late_fee ?? 0)) - $data->total;
                        if ($discountAmount < 0)
                            $discountAmount = 0;
                    @endphp
                    <h4>Discount: {{ number_format($discountAmount, 2) }} {{ $currencyLabel }}</h4>
                @endif

                {{-- LATE FEE --}}
                @if(isset($data->late_fee) && (float) $data->late_fee > 0)
                    <h4 style="color:#dc3545;">
                        Late Fee:
                        {{ number_format((float) $data->late_fee, 2) }} {{ $currencyLabel }}
                    </h4>

                    @if(isset($data->late_fee_overdue_days) && (int) $data->late_fee_overdue_days > 0)
                        <p style="margin:0;color:#dc3545;font-size:14px;">
                            ({{ (int) $data->late_fee_overdue_days }} days overdue)
                        </p>
                    @endif

                    @if(!empty($data->late_fee_reason))
                        @php
                            $lateFeeReasonText = $data->late_fee_reason;

                            // Optional: make reason human-friendly
                            if ($lateFeeReasonText === 'LATE_FEE_3_MONTHS')
                                $lateFeeReasonText = 'Overdue 90+ days (1 month package penalty)';
                            if ($lateFeeReasonText === 'LATE_FEE_7_DAYS')
                                $lateFeeReasonText = 'Overdue 7+ days (fixed late fee)';
                        @endphp

                        <p style="margin:0;color:#dc3545;font-size:14px;">
                            Reason: {{ $lateFeeReasonText }}
                        </p>
                    @endif
                @endif
                @if($invoiceDueAmount > 0)
                    <h4 style="color:#c2410c;">
                        Due:
                        {{ number_format($invoiceDueAmount, 2) }} {{ $currencyLabel }}
                    </h4>
                @endif
                <h2>
                    Grand Total:
                    {{ number_format($data->total, 2) }} {{ $currencyLabel }}
                </h2>
            </div>

            {{-- ================= STATUS STAMP ================= --}}
            @if($data->status == "Complete")
                <div class="col-md-12" style="position: relative;">
                    <img src="https://admin.ebitans.com/public/img/paid.png" width="100px"
                        style="position: absolute; right: 45px;" alt="">
                </div>
            @elseif($data->status == "Failed")
                <div class="col-md-12" style="position: relative;">
                    <img src="https://admin.ebitans.com/public/img/failed-stamp.png" width="100px"
                        style="position: absolute; right: 45px;" alt="">
                </div>
            @endif
            {{-- ================= FOOTER ================= --}}
            <div class="invoice-footer" style="text-align: left;">


                <p class="thank-you" style="margin-bottom:10px;">Thank you for choosing us!</p>
                <div class="footer-divider"></div>
                <h4 class="company-name" style="margin-top:20px;">eBitans Limited</h4>


                <div class="contact-item">
                    <span class="icon-box" aria-hidden="true">
                        <!-- Phone SVG -->
                        <svg class="icon-svg" viewBox="0 0 24 24" fill="none">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2
            A19.86 19.86 0 0 1 3 5.18
            2 2 0 0 1 5 3h3a2 2 0 0 1 2 1.72
            12.44 12.44 0 0 0 .7 2.81
            2 2 0 0 1-.45 2.11L9.91 10.91
            a16 16 0 0 0 6.18 6.18l1.27-1.27
            a2 2 0 0 1 2.11-.45
            12.44 12.44 0 0 0 2.81.7
            A2 2 0 0 1 22 16.92Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="contact-text">+880 1886 515579 | +880 1886 515578</span>
                </div>

                <div class="contact-item">
                    <span class="icon-box" aria-hidden="true">
                        <!-- Mail SVG -->
                        <svg class="icon-svg" viewBox="0 0 24 24" fill="none">
                            <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="m22 6-10 7L2 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="contact-text">info@ebitans.com | support@ebitans.com</span>
                </div>

                <div class="contact-item">
                    <span class="icon-box" aria-hidden="true">
                        <!-- Location SVG -->
                        <svg class="icon-svg" viewBox="0 0 24 24" fill="none">
                            <path d="M12 22s8-4.5 8-12a8 8 0 1 0-16 0c0 7.5 8 12 8 12Z" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="contact-text">4th Floor, House 39, Road 20, Nikunja 2, Dhaka-1229</span>
                </div>

                <p class="powered">
                    Power Up Your Business with eBitans - Your Trusted eCommerce Website Builder Platform!
                </p>
            </div>
        </div>
    </div>

</body>

</html>
