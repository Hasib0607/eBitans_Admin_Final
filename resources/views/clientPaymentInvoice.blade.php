<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Custom CSS for print and additional styles -->
    <style>
        .print-button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin-top: 20px; /* Adjust margin as needed */
        }

        .print-button-container button {
            width: fit-content;
            padding: 6px 15px;
            text-transform: uppercase;
        }

        .noPrint {
            cursor: pointer;
        }

        table {
            width: 100%;
            font-size: 18px;
            border-spacing: 0;
            border-collapse: collapse; /* Ensures borders are collapsed */
        }

        table th,
        table td {
            padding: 5px 2px; /* Adjust padding as needed */
            border: 1px solid black; /* Border for th and td */
        }

        thead tr {
            background-color: #f4765c;
            color: white;
        }


        @media print {
            thead {
                background-color: #f4765c !important;
                color: white !important;
            }

            .noPrint {
                display: none; /* Hide the print button in print mode */
            }
        }
    </style>
</head>

<body>

<div id="print" class="container" style="width: 80%; margin: 0 auto; background-color: white;">
    <div class="row">
        <div class="col-md-12 mt-4">
            <img width="160px;" src="https://ebitans.com/static/media/logo-dark.602bcd5a22dae84824fe.png"
                 alt="">
            <hr>
        </div>
        <div class="col-md-12 mt-2">
            @if(isset($data->store->name) && !empty($data->store->name))
                <h2>{{ $data->store->name ?? "" }}</h2>
            @endif
            @if(isset($data->user->phone) && !empty($data->user->phone))
                <p class="m-0"><strong>Phone:</strong> {{ $data->user->phone ?? "" }}</p>
            @elseif(isset($data->user->email) && !empty($data->user->email))
                <p class="m-0"><strong>Email:</strong> {{ $data->user->email ?? "" }}</p>
            @endif
            @if(isset($data->order_no))
                <p class="m-0"><strong>Order number:</strong> {{ $data->order_no ?? "" }}</p>
            @endif
            <p><strong>Order Date:</strong> {{ $data->created_at }}</p>
        </div>

        @php
            $totalSub = 0;
            $countryCode = getVisitorInfo()->countryCode ?? "";
            $currencyLabel = $countryCode == "BD" ? "tk" : "usd";
            $invoiceDueAmount = isset($selectedPaymentHistory) && $selectedPaymentHistory
                ? (float) ($selectedPaymentHistory->current_due_amount ?? 0)
                : (float) ($data->due_amount ?? 0);
        @endphp

        <div class="col-md-12" {{ !isset($package) && !isset($data->combopackages) ? 'hidden' : '' }}>
            <h4 style="text-align: right;">Package</h4>
            <table class="table" style="width: 100%;font-size: 18px; border-spacing: 0px;">
                <thead style="position: relative;background: transparent !important; color: #fff !important;">
                <tr style="color: black !important;background: transparent !important;">
                    <th style="padding: 5px 2px!important;">#</th>
                    <th style="padding: 5px 2px!important;">Plan</th>
                    <th style="padding: 5px 2px!important;">Package</th>
                    <th style="text-align: right;padding-right: 10px;">Price</th>
                </tr>
                <img src="{{ asset('/brandbg.png') }}" alt="" style="position: absolute; width: 78.5%; height: 33px;"/>
                </thead>
                <tbody style="text-align: center;">

                @php
                    if (!empty($data->plan_id)) {
                        $plan = DB::table('plans')
                            ->where('id', $data->plan_id)
                            ->first();
                    } else {
                        $combopackages = $data->combopackages;
                        if (isset($combopackages)) {
                            $plan = DB::table('plans')
                                ->where('id', $combopackages[0]['id'])
                                ->first();
                        }
                    }
                    $visitorInfo = getVisitorInfo();

                @endphp

                @if (!empty($data->plan_id))
                    @if (isset($package))
                        <tr>
                            <th scope="row">1</th>
                            <td>{{ $package->type }}</td>
                            <td>{{ $package->name ?? '' }}</td>
                            <td style="text-align: right;padding-right: 10px;">{{ $countryCode == "BD" ? $package->price ?? 0 :  $package->usd_price ?? 0 }}</td>
                        </tr>
                    @else
                        <tr>
                            <th scope="row">1</th>
                            <td colspan="3" style="text-align: center;">
                                <span style="color: red;">No packages selected</span>
                            </td>
                        </tr>
                    @endif

                    @php
                        $packagePrice = $countryCode == "BD" ? $package->price ?? 0 :  $package->usd_price ?? 0;
                        $totalSub = $totalSub +  ($packagePrice ?? 0);
                    @endphp
                @else
                    @if (isset($data->combopackages))
                        @foreach ($data->combopackages as $key => $ite)
                            @php
                                $planName = DB::table('plans')
                                    ->where('id', $ite['id'])
                                    ->first();
                            @endphp
                            <tr>
                                <th scope="row">1</th>
                                <td>{{ $ite['type'] }}</td>
                                <td>{{ $planName->name ?? '' }}</td>
                                <td style="text-align: right;padding-right: 10px;">{{ $ite['price'] }}</td>
                            </tr>
                            @php
                                $totalSub = $totalSub + $ite['price'];
                            @endphp
                        @endforeach
                    @else
                        <tr>
                            <th scope="row">1</th>
                            <td colspan="3" style="text-align: center;">
                                <span style="color: red;">No packages selected</span>
                            </td>
                        </tr>
                    @endif
                @endif

                </tbody>
            </table>
        </div>

        <div class="col-md-12" {{ $data->addons == null ? 'hidden' : '' }}>
            <h4 style="text-align: right;">Addons</h4>
            <table class="table" style="width: 100%;font-size: 18px; border-spacing: 0px;">
                <thead style="position: relative;background: transparent !important; color: #fff !important;">
                <tr style="color: black !important;background: transparent !important;">
                    <th style="padding: 5px 2px!important;">SL</th>
                    <th style="padding: 5px 2px!important;">Name</th>
                    <th style="padding: 5px 2px!important;">Month</th>
                    <th style="padding: 5px 2px!important;">Type</th>
                    <th style="text-align: right; padding-right: 10px;">Price</th>
                </tr>
                <img src="{{ asset('/brandbg.png') }}" alt="" style="position: absolute; width: 78.5%; height: 33px;"/>
                </thead>
                <tbody style="text-align: center;">
                @if (isset($data->addons) && count($data->addons) > 0)
                    @foreach ($data->addons as $key => $adonsItem)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $adonsItem['title'] }}</td>
                            <td>{{ $adonsItem['months'] }}</td>
                            <td>{{ $adonsItem['type'] }}</td>
                            <td style="text-align: right;padding-right: 10px;">
                                @if($countryCode == "BD")
                                    {{ isset($adonsItem['offerprice']) && $adonsItem['offerprice'] != 0 ? $adonsItem['offerprice'] : $adonsItem['price'] ?? 0 }}
                                @else
                                    {{ isset($adonsItem['usd_offer_price']) && $adonsItem['usd_offer_price'] != 0 ? $adonsItem['usd_offer_price'] : $adonsItem['usd_price'] ?? 0 }}
                                @endif
                            </td>
                        </tr>
                        @php
                            $addonPrice = isset($adonsItem['usd_offer_price']) && $adonsItem['usd_offer_price'] != 0 ? $adonsItem['usd_offer_price'] : $adonsItem['usd_price'] ?? 0;
                            if($countryCode == "BD"){
                              $addonPrice = isset($adonsItem['offerprice']) && $adonsItem['offerprice'] != 0 ? $adonsItem['offerprice'] : $adonsItem['price'] ?? 0;
                            }

                            $totalSub = $totalSub + $addonPrice;
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <th scope="row">1</th>
                        <td colspan="5" style="text-align: center;">
                            <span style="color: red;">No Addons selected</span>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <div class="col-md-12" style="text-align: right;text-align: right; position: relative;">
            <h2 style="padding-right: 10px;">Total: {{ $totalSub }} {{ $currencyLabel }}</h2>
            @if($invoiceDueAmount > 0)
                <h3 style="padding-right: 10px; color: #c2410c;">Due: {{ number_format($invoiceDueAmount, 2) }} {{ $currencyLabel }}</h3>
            @endif
        </div>

        @if(isset($data->status) && $data->status == "Complete")
            <div class="col-md-12" style="text-align: right;text-align: right; position: relative;">
                <img src="https://admin.ebitans.com/public/img/paid.png" width="100px;"
                     style="position: absolute;bottom: -52px;right: 45px;" alt="">
            </div>
        @elseif(isset($data->status) && $data->status == "Failed")
            <div class="col-md-12" style="text-align: right;text-align: right; position: relative;">
                <img src="https://admin.ebitans.com/public/img/failed-stamp.png" width="100px;"
                     style="position: absolute;bottom: -52px;right: 45px;" alt="">
            </div>
        @endif

        <div class="col-md-12 mt-5">
            <p>Thank You</p>
            <h4>Ebitans</h4>
            <p>Phone: +8801886 515579 | +8801886 515578</p>
            <p>Email: info@ebitans.com | support@ebitans.com</p>
            <address>Office Address: 4th Floor, House: 39, Road: 20, Nikunja 2, Dhaka-1229</address>
        </div>
    </div>

    <div class="row print-button-container">
        <button onclick="print();" class="noPrint">
            Print
        </button>
    </div>

</div>


<script>

</script>
</body>

</html>
