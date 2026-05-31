@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @include('superadmin.share.report.nav')
        
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
                                <h4 class="mb-0">{{ $total_admin ?? '0' }}</h4>
                            </div>
                            <div class="text-end pt-2">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Paid Store
                                    @else
                                        Total Paid Store
                                    @endif
                                </p>
                                <h4 class="mb-0">{{ $total_paid_store ?? '0' }}</h4>
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
                                <h4 class="mb-0">{{ $total_dropshipper ?? '0' }}</h4>
                            </div>
                            <div class="text-end pt-2">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Total Paid Dropshipper
                                    @else
                                        Total Paid Dropshipper
                                    @endif
                                </p>
                                <h4 class="mb-0">{{ $total_paid_dropshipper ?? '0' }}</h4>
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
                                <h4 class="mb-0">{{ $total_customer ?? '0' }}</h4>
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
                                    <h4 class="mb-0">{{ $total_affiliate ?? '0' }}</h4>
                                </div>
                                <div class="text-end col-6">
                                    <p class="text-sm mb-0 text-capitalize">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            Total Customer Affiliate
                                        @else
                                            Total Customer Affiliate
                                        @endif
                                    </p>
                                    <h4 class="mb-0">{{ $total_customer_affiliate ?? '0' }}</h4>
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
                                <h4 class="mb-0">{{ $lifetime_total_paid_store ?? '0' }}</h4>
                            </div>
                            <div class="text-end pt-2">
                                <p class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        LifeTime Total Paid Dropshipper
                                    @else
                                        LifeTime Total Paid Dropshipper
                                    @endif
                                </p>
                                <h4 class="mb-0">{{ $lifetime_total_paid_dropshipper ?? '0' }}</h4>
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
                                        class="text-normal text-sm">Monthly: </span>{{ $total_new_sell_amount_monthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $total_new_sell_amount_yearly ?? '0' }}
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
                                        class="text-normal text-sm">Monthly: </span>{{ $total_renew_sell_amount_monthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $total_renew_sell_amount_yearly ?? '0' }}
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
                                        class="text-normal text-sm">Monthly: </span>{{ $total_addon_amount_monthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $total_addon_amount_yearly ?? '0' }}
                                </h4>
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
                                        class="text-normal text-sm">Monthly: </span>{{ $total_addon_amount_without_domain_monthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $total_addon_amount_without_domain_yearly ?? '0' }}
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
                                        class="text-normal text-sm">Monthly: </span>{{ $total_package_amount_monthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $total_package_amount_yearly ?? '0' }}
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
                                        Total Revenue
                                    @else
                                        Total Revenue
                                    @endif
                                </p>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Monthly: </span>{{ $total_revenue_monthly ?? '0' }}
                                </h4>
                                <h4 class="mb-0"><span
                                        class="text-normal text-sm">Yearly: </span>{{ $total_revenue_yearly ?? '0' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
