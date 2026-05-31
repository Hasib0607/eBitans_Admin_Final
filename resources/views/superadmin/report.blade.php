@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @include('superadmin.share.report.nav')

        <?php
        $currentDate = \Carbon\Carbon::now();
        ?>

        <div class="container-fluid py-4">
            <div class="row mt-3">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Admin
                                    @else
                                        Total Admin
                                    @endif
                                </p>
                                <?php
                                $userCount = DB::table('users')
                                    ->where('type', "admin")
                                    ->count();
                                ?>
                                <h4 class="mb-0">{{ $userCount ?? '0' }}</h4>
                            </div>
                            <div class="text-end pt-2">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Paid Store
                                    @else
                                        Total Paid Store
                                    @endif
                                </p>
                                <?php

                                $paidStore = DB::table('stores')
                                    ->where('expiry_date', '>=', $currentDate->startOfDay())
                                    ->whereNotIn('plan_id', [6, 8, 9])
                                    ->groupBy('user_id')
                                    ->get()
                                    ->count();
                                ?>
                                <h4 class="mb-0">{{ $paidStore ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Dropshipper
                                    @else
                                        Total Dropshipper
                                    @endif
                                </p>
                                <?php
                                $adminCount = DB::table('users')
                                    ->where('type', "dropshipper")
                                    ->count();
                                ?>
                                <h4 class="mb-0">{{ $adminCount ?? '0' }}</h4>
                            </div>
                            <div class="text-end pt-2">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Paid Dropshipper
                                    @else
                                        Total Paid Dropshipper
                                    @endif
                                </p>
                                <?php

                                $paidDropshipper = DB::table('stores')
                                    ->where('expiry_date', '>=', $currentDate->startOfDay())
                                    ->where('plan_id', 8)
                                    ->groupBy('user_id')
                                    ->get()
                                    ->count();
                                ?>
                                <h4 class="mb-0">{{ $paidDropshipper ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Customer
                                    @else
                                        Total Customer
                                    @endif
                                </p>
                                <?php
                                $customer = DB::table('users')
                                    ->where('type', 'customer')
                                    ->count();
                                ?>
                                <h4 class="mb-0">{{ $customer ?? '0' }}</h4>
                            </div>
                            <div class="row pt-1">
                                <div class="text-start col-6">
                                    <p class="text-sm mb-0 text-capitalize">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            Total Affiliate
                                        @else
                                            Total Affiliate
                                        @endif
                                    </p>
                                    <?php
                                    $affiliate = DB::table('users')
                                        ->where('type', 'affiliate')
                                        ->count();
                                    ?>
                                    <h4 class="mb-0">{{ $affiliate ?? '0' }}</h4>
                                </div>
                                <div class="text-end col-6">
                                    <p class="text-sm mb-0 text-capitalize">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            Total Customer Affiliate
                                        @else
                                            Total Customer Affiliate
                                        @endif
                                    </p>
                                    <?php
                                    $customerAffiliate = DB::table('users')
                                        ->where('type', 'customerAffiliate')
                                        ->count();
                                    ?>
                                    <h4 class="mb-0">{{ $customerAffiliate ?? '0' }}</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        LifeTime Total Paid Store
                                    @else
                                        LifeTime Total Paid Store
                                    @endif
                                </p>
                                <?php
                                $lifeTimePaidStore = DB::table('stores')
                                    ->whereNotIn('plan_id', [6, 8, 9])
                                    ->groupBy('user_id')
                                    ->get()
                                    ->count();
                                ?>
                                <h4 class="mb-0">{{ $lifeTimePaidStore ?? '0' }}</h4>
                            </div>
                            <div class="text-end pt-2">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        LifeTime Total Paid Dropshipper
                                    @else
                                        LifeTime Total Paid Dropshipper
                                    @endif
                                </p>
                                <?php
                                $lifeTimePaidDropshiper = DB::table('stores')
                                    ->where('plan_id', 8)
                                    ->groupBy('user_id')
                                    ->get()
                                    ->count();
                                ?>
                                <h4 class="mb-0">{{ $lifeTimePaidDropshiper ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total New Sell Amount
                                    @else
                                        Total New Sell Amount
                                    @endif
                                </p>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Monthly: </span>{{ $totalNewCustomerPackageMonthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $totalNewCustomerPackageYearly ?? '0' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Renew Sell Amount
                                    @else
                                        Total Renew Sell Amount
                                    @endif
                                </p>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Monthly: </span>{{ $totalRenewCustomerPackageMonthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $totalRenewCustomerPackageYearly ?? '0' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Addon Amount
                                    @else
                                        Total Addon Amount
                                    @endif
                                </p>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Monthly: </span>{{ $addonMonthlyTotal ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $addonYearlyTotal ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Addon Amount Without Domain
                                    @else
                                        Total Addon Amount Without Domain
                                    @endif
                                </p>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Monthly: </span>{{ $addonMonthlyExcludingDomain ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $addonYearlyExcludingDomain ?? '0' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Package Amount
                                    @else
                                        Total Package Amount
                                    @endif
                                </p>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Monthly: </span>{{ $packageMonthlyTotal ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $packageYearlyTotal ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Revenue
                                    @else
                                        Total Revenue
                                    @endif
                                </p>
                                {{--                                <h4 class="mb-0"><span--}}
                                {{--                                        class="text-normal text-sm">Monthly: </span>{{ $totalMonthlyRevenew ?? '0' }}--}}
                                {{--                                </h4>--}}
                                {{--                                <h4 class="mb-0"><span--}}
                                {{--                                        class="text-normal text-sm">Yearly: </span>{{ $totalYearlyRevenew ?? '0' }}</h4>--}}
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Monthly: </span>{{ $totalAmountMonthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $totalAmountYearly ?? '0' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">payments</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Revenue After Discount
                                    @else
                                        Total Revenue After Discount
                                    @endif
                                </p>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Monthly: </span>{{ $totalRevenueAfterDiscountMonthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $totalRevenueAfterDiscountYearly ?? '0' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">account_balance_wallet</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Due
                                    @else
                                        Total Due
                                    @endif
                                </p>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Monthly: </span>{{ $totalDueMonthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $totalDueYearly ?? '0' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header p-3 pt-2 pb-0 d-flex justify-content-center align-items-center">
                            <h5 class="pt-1 pb-0 mb-0">Staff Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" width="100%">
                                    <tbody>
                                    <tr>
                                        <th>Staff Name</th>
                                        <th>Time Period</th>
                                        <th>New Sell</th>
                                        <th>Renew Sell</th>
                                        <th>Setup</th>
                                        <th>Total</th>
                                    </tr>
                                    @foreach ($Staffs as $item)
                                        <tr>
                                            <td rowspan="2" style="padding-top: 34px;">
                                                {{ $item->name ?? "" }}
                                            </td>
                                            <td>Monthly</td>
                                            <td>{{ $item->monthly_new_sell_count ?? 0 }}</td>
                                            <td>{{ $item->monthly_renew_sell_count ?? 0 }}</td>
                                            <td>{{ $item->monthly_setup_count ?? 0 }}</td>
                                            @php
                                                $totalMonthly = 0;
                                                if(isset($item->monthly_new_sell_total)){
                                                    $totalMonthly += $item->monthly_new_sell_total ?? 0;
                                                }
                                                if(isset($item->monthly_renew_sell_total)){
                                                    $totalMonthly += $item->monthly_renew_sell_total ?? 0;
                                                }
                                                if(isset($item->monthly_setup_total)){
                                                    $totalMonthly += $item->monthly_setup_total ?? 0;
                                                }
                                            @endphp
                                            <td>{{ $totalMonthly ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Yearly</td>
                                            <td>{{ $item->yearly_new_sell_count ?? 0 }}</td>
                                            <td>{{ $item->yearly_renew_sell_count ?? 0 }}</td>
                                            <td>{{ $item->yearly_setup_count ?? 0 }}</td>

                                            @php
                                                $totalYearly = 0;
                                                if(isset($item->yearly_new_sell_total)){
                                                    $totalYearly += $item->yearly_new_sell_total ?? 0;
                                                }
                                                if(isset($item->yearly_renew_sell_total)){
                                                    $totalYearly += $item->yearly_renew_sell_total ?? 0;
                                                }
                                                if(isset($item->yearly_setup_total)){
                                                    $totalYearly += $item->yearly_setup_total ?? 0;
                                                }
                                            @endphp
                                            <td>{{ $totalYearly ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
