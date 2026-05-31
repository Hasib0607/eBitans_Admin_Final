<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="margin:0;padding:0;background:#ffffff;font-family:Arial,Helvetica,sans-serif;color:#222;">
    <div style="width:80%;margin:0 auto;background:#ffffff;">
        <div>
            <div style="margin-top:16px;">
                <img width="160" src="/logo.png" alt="" style="display:block;">
                <hr style="border:none;border-top:1px solid #ddd;margin:12px 0;">
            </div>

            <div style="margin-top:8px;">
                <h1 style="margin:0 0 6px 0;font-size:26px;">Thank You</h1>
                <p style="margin:0;color:#555;">You've made a purchase from eBitans</p>
            </div>

            <div style="margin-top:12px;">
                <p style="margin:0 0 6px 0;"><strong>Order number:</strong> {{ $data->order_no }}</p>
                <p style="margin:0;"><strong>Order Date:</strong> {{ $data->created_at }}</p>
            </div>

            @php
                $totalSub = 0;
                $countryCode = getVisitorInfo()->countryCode ?? "";

                $lateFee = (float) ($data->late_fee ?? 0);
                $lateFeeDays = (int) ($data->late_fee_overdue_days ?? 0);
                $lateFeeReason = $data->late_fee_reason ?? null;

                $currencyLabel = ($countryCode == "BD") ? "tk" : "usd";

                $lateFeeReasonText = null;
                if ($lateFeeReason === 'LATE_FEE_3_MONTHS') {
                    $lateFeeReasonText = 'Overdue 90+ days (1 month package penalty)';
                } elseif ($lateFeeReason === 'LATE_FEE_7_DAYS') {
                    $lateFeeReasonText = 'Overdue 7+ days (fixed late fee)';
                }
            @endphp

            {{-- ================= PACKAGE SECTION ================= --}}
            <div style="margin-top:14px;">
                {{-- heading close to table --}}
                <h4 style="text-align:right;margin:0 0 6px 0;padding:0;font-size:18px;font-weight:700;">Package</h4>

                <table role="presentation" cellpadding="0" cellspacing="0"
                       style="width:100%;font-size:18px;border-collapse:collapse;border-spacing:0;table-layout:fixed;">
                    <colgroup>
                        <col style="width:8%;">
                        <col style="width:52%;">
                        <col style="width:20%;">
                        <col style="width:20%;">
                    </colgroup>

                    <thead>
                        <tr style="background:#f4765c;color:#ffffff;">
                            <th style="border:1px solid #000;padding:6px 10px;text-align:right;">#</th>
                            <th style="border:1px solid #000;padding:6px 10px;text-align:right;">Plan</th>
                            <th style="border:1px solid #000;padding:6px 10px;text-align:right;">Package</th>
                            <th style="border:1px solid #000;padding:6px 10px;text-align:right;">Price</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            if (!empty($data->plan_id)) {
                                $plan = DB::table('plans')->where('id', $data->plan_id)->first();
                            } else {
                                $combopackages = $data->combopackages;
                                if (isset($combopackages)) {
                                    $plan = DB::table('plans')->where('id', $combopackages[0]['id'])->first();
                                }
                            }
                        @endphp

                        <strong>
                            @if (!empty($data->plan_id))
                                @if (isset($package))
                                    <tr>
                                        <th scope="row" style="border:1px solid #000;padding:6px 10px;text-align:right;">1</th>
                                        <td style="border:1px solid #000;padding:6px 10px;text-align:right;">
                                            {{ $package->type }}
                                        </td>
                                        <td style="border:1px solid #000;padding:6px 10px;text-align:right;">
                                            {{ $package->name ?? '' }}
                                        </td>
                                        <td style="border:1px solid #000;padding:6px 10px;text-align:right;">
                                            {{ $countryCode == "BD" ? $package->price ?? 0 : $package->usd_price ?? 0 }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <th scope="row" style="border:1px solid #000;padding:6px 10px;text-align:right;">1</th>
                                        <td colspan="3" style="border:1px solid #000;padding:10px;text-align:center;color:red;">
                                            No packages selected
                                        </td>
                                    </tr>
                                @endif

                                @php
                                    $packagePrice = $countryCode == "BD" ? $package->price ?? 0 : $package->usd_price ?? 0;
                                    $totalSub = $totalSub + ($packagePrice ?? 0);
                                @endphp
                            @else
                                @if (isset($data->combopackages))
                                    @foreach ($data->combopackages as $key => $ite)
                                        @php
                                            $planName = DB::table('plans')->where('id', $ite['id'])->first();
                                        @endphp
                                        <tr>
                                            <th scope="row" style="border:1px solid #000;padding:6px 10px;text-align:right;">1</th>
                                            <td style="border:1px solid #000;padding:6px 10px;text-align:right;">{{ $ite['type'] }}</td>
                                            <td style="border:1px solid #000;padding:6px 10px;text-align:right;">{{ $planName->name ?? '' }}</td>
                                            <td style="border:1px solid #000;padding:6px 10px;text-align:right;">{{ $ite['price'] }}</td>
                                        </tr>
                                        @php $totalSub = $totalSub + $ite['price']; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <th scope="row" style="border:1px solid #000;padding:6px 10px;text-align:right;">1</th>
                                        <td colspan="3" style="border:1px solid #000;padding:10px;text-align:center;color:red;">
                                            No packages selected
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        </strong>
                    </tbody>
                </table>
            </div>

            {{-- ================= ADDONS SECTION ================= --}}
            <div style="{{ $data->addons == null ? 'display:none;' : '' }} margin-top:14px;">
                {{-- heading close to table --}}
                <h4 style="text-align:right;margin:0 0 6px 0;padding:0;font-size:18px;font-weight:700;">Addons</h4>

                <table role="presentation" cellpadding="0" cellspacing="0"
                       style="width:100%;font-size:18px;border-collapse:collapse;border-spacing:0;table-layout:fixed;">
                    {{-- same colgroup so columns align with package table --}}
                    <colgroup>
                        <col style="width:8%;">
                        <col style="width:52%;">
                        <col style="width:20%;">
                        <col style="width:20%;">
                    </colgroup>

                    <thead>
                        <tr style="background:#f4765c;color:#ffffff;">
                            <th style="border:1px solid #000;padding:6px 10px;text-align:right;">SL</th>
                            <th style="border:1px solid #000;padding:6px 10px;text-align:right;">Name</th>
                            <th style="border:1px solid #000;padding:6px 10px;text-align:right;">Details</th>
                            <th style="border:1px solid #000;padding:6px 10px;text-align:right;">Price</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (isset($data->addons) && count($data->addons) > 0)
                            @foreach ($data->addons as $key => $adonsItem)
                                <tr>
                                    <td style="border:1px solid #000;padding:6px 10px;text-align:right;">
                                        {{ $key + 1 }}
                                    </td>

                                    <td style="border:1px solid #000;padding:6px 10px;text-align:right;">
                                        {{ $adonsItem['title'] ?? '' }}
                                    </td>

                                    <td style="border:1px solid #000;padding:6px 10px;text-align:right;">
                                        {{ $adonsItem['monthorvalue'] ?? $adonsItem['months'] ?? $adonsItem['month'] ?? '' }}
                                    </td>

                                    <td style="border:1px solid #000;padding:6px 10px;text-align:right;">
                                        @if($countryCode == "BD")
                                            {{ isset($adonsItem['offerprice']) && $adonsItem['offerprice'] != 0 ? $adonsItem['offerprice'] : $adonsItem['price'] ?? 0 }}
                                        @else
                                            {{ isset($adonsItem['usd_offer_price']) && $adonsItem['usd_offer_price'] != 0 ? $adonsItem['usd_offer_price'] : $adonsItem['usd_price'] ?? 0 }}
                                        @endif
                                    </td>
                                </tr>

                                @php
                                    $addonPrice = isset($adonsItem['usd_offer_price']) && $adonsItem['usd_offer_price'] != 0 ? $adonsItem['usd_offer_price'] : $adonsItem['usd_price'] ?? 0;
                                    if ($countryCode == "BD") {
                                        $addonPrice = isset($adonsItem['offerprice']) && $adonsItem['offerprice'] != 0 ? $adonsItem['offerprice'] : $adonsItem['price'] ?? 0;
                                    }
                                    $totalSub = $totalSub + $addonPrice;
                                @endphp
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" style="border:1px solid #000;padding:10px;text-align:center;color:red;">
                                    No Addons selected
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- ================= SUMMARY ================= --}}
            <div style="text-align:right;position:relative;margin-top:14px;">
                <h3 style="padding-right:10px;margin:0;">Subtotal: {{ $totalSub }} {{ $currencyLabel }}</h3>

                @if($lateFee > 0)
                    <h4 style="padding-right:10px;margin:8px 0 0;color:#dc3545;">
                        Late Fee: {{ number_format($lateFee, 2) }} {{ $currencyLabel }}
                    </h4>

                    @if($lateFeeDays > 0)
                        <p style="padding-right:10px;margin:2px 0 0;color:#dc3545;">
                            {{ $lateFeeDays }} days overdue
                        </p>
                    @endif

                    @if(!empty($lateFeeReasonText))
                        <p style="padding-right:10px;margin:2px 0 0;color:#dc3545;">
                            Reason: {{ $lateFeeReasonText }}
                        </p>
                    @endif
                @endif

                <h2 style="padding-right:10px;margin:12px 0 0;">
                    Grand Total: {{ number_format((float) ($data->total ?? $totalSub), 2) }} {{ $currencyLabel }}
                </h2>
            </div>

            <div style="text-align:right;position:relative;">
                <img src="https://admin.ebitans.com/public/img/paid.png" width="100"
                     style="position:absolute;bottom:-52px;right:45px;" alt="">
            </div>

            {{-- ================= FOOTER (modern, left-aligned) ================= --}}
            <div style="margin-top:60px;text-align:left;font-size:14px;color:#555;padding-bottom:20px;">
                <p style="margin:0 0 8px 0;font-size:16px;font-weight:600;color:#333;">Thank you for choosing us!</p>

                <div style="width:80px;height:3px;background:#f4765c;border-radius:5px;margin:0 0 14px 0;"></div>

                <h4 style="margin:0 0 14px 0;font-size:20px;font-weight:800;color:#111;">eBitans Limited</h4>

                <div style="display:flex;align-items:center;gap:10px;margin:8px 0;">
                    <span style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;flex:0 0 28px;">
                        <svg viewBox="0 0 24 24" fill="none" width="20" height="20" style="display:block;color:#555;">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 A19.86 19.86 0 0 1 3 5.18 2 2 0 0 1 5 3h3a2 2 0 0 1 2 1.72 12.44 12.44 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L9.91 10.91a16 16 0 0 0 6.18 6.18l1.27-1.27a2 2 0 0 1 2.11-.45 12.44 12.44 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span style="line-height:1.4;">+880 1886 515579 | +880 1886 515578</span>
                </div>

                <div style="display:flex;align-items:center;gap:10px;margin:8px 0;">
                    <span style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;flex:0 0 28px;">
                        <svg viewBox="0 0 24 24" fill="none" width="20" height="20" style="display:block;color:#555;">
                            <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="m22 6-10 7L2 6"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span style="line-height:1.4;">info@ebitans.com | support@ebitans.com</span>
                </div>

                <div style="display:flex;align-items:center;gap:10px;margin:8px 0;">
                    <span style="width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;flex:0 0 28px;">
                        <svg viewBox="0 0 24 24" fill="none" width="20" height="20" style="display:block;color:#555;">
                            <path d="M12 22s8-4.5 8-12a8 8 0 1 0-16 0c0 7.5 8 12 8 12Z"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span style="line-height:1.4;">Office Address: 4th Floor, House 39, Road 20, Nikunja 2, Dhaka-1229</span>
                </div>

                <p style="margin:14px 0 0;font-size:12px;color:#aaa;letter-spacing:1px;text-transform:uppercase;">
                    Power Up Your Business with eBitans
                </p>
            </div>

        </div>
    </div>
</body>
</html>