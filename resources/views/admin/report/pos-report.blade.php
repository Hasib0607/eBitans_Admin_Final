@extends('admin.layouts.main')
@push('styles')
    <style>
        .spinner-border {
            display: inline-block;
            width: 15px;
            height: 15px;
            vertical-align: -0.125em;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: 0.75s linear infinite spinner-border;
            animation: 0.75s linear infinite spinner-border;
        }

        .loadingDiv {
            margin-top: 10px;
        }

        .icon-lg {
            width: 64px !important;
            height: 64px !important;
        }

        button#viewExpensesBtn i, button#addExpenseBtn i {
            font-size: 14px;
        }

        .delete-expense i {
            font-size: 14px !important;
        }

        .modal-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1060; /* Above modal content */
            border-radius: 0.5rem;
        }

        /* Make modal content position relative */
        .modal-content {
            position: relative;
        }

        /* Smaller spinner */
        .spinner-border-sm {
            width: 1.5rem;
            height: 1.5rem;
            border-width: 0.2em;
        }

        .notes-cell {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Show full notes on hover */
        /*.notes-cell:hover {*/
        /*    white-space: normal;*/
        /*    overflow: visible;*/
        /*    position: absolute;*/
        /*    background: white;*/
        /*    z-index: 100;*/
        /*    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
        /*    padding: 8px;*/
        /*    border: 1px solid #ddd;*/
        /*    max-width: 300px;*/
        /*}*/

        #expenseListSection .table thead th {
            padding: 0;
        }


        .loadingDiv {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 100;
            border-radius: 0.5rem;
        }

        .loadingDiv .spinner-border {
            width: 2rem;
            height: 2rem;
            margin-bottom: 0.5rem;
        }

        .loadingDiv span {
            font-size: 0.875rem;
            color: #555;
        }

        /* Button Loading State */
        #generateReport .loading-content {
            display: none;
        }

        #generateReport.loading {
            pointer-events: none;
        }

        #generateReport.loading .button-content {
            visibility: hidden;
        }

        #generateReport.loading .loading-content {
            display: flex;
            align-items: center;
        }

        /* Spinner styling */
        .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        .cardPeople {
            z-index: 101;
        }
    </style>
@endpush
@section('content')
    <!-- Expense Modal -->
    @include("admin.report.expanse-tracker")

    <main class="main-content position-relative border-radius-lg">
        @include("admin.report.top-nav")
        <div class="container-fluid py-4">
            <div class="row mt-4" style="height: calc(93%);">
                <div class="col-xl-3 col-sm-3 mb-xl-0 mb-4">
                    <div class="card" style="height: 100%">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>
                            <div class="text-end pt-xl-0 pt-5">
                                <a href="{{ route('admin.allproducts') }}" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মোট পণ্য
                                    @else
                                        Total Product
                                    @endif
                                </a>

                                <div id="productCountLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="productCount"></h4>
                                <p class="mb-0 mt-1 text-sm">Active: <span class="text-bold" id="activeProduct">0</span>
                                </p>
                                <p class="mb-0 text-sm">Inactive: <span class="text-bold"
                                                                        id="inactiveProduct">0</span>
                                </p>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-3 mb-xl-0 mb-4">
                    <div class="card" style="height: 100%">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-xl-0 pt-5">
                                <a href="{{ route("admin.customer") }}" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মোট ব্যবহারকারী
                                    @else
                                        Total Users
                                    @endif
                                </a>
                                <div id="userCountLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="userCount"></h4>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-3 mb-xl-0 mb-4">
                    <div class="card" style="height: 100%">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>
                            <div class="text-end pt-xl-0 pt-5">
                                <a href="{{ route('admin.order') }}" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মোট অর্ডার
                                    @else
                                        Total Order
                                    @endif
                                </a>
                                <div id="totalOrderCountLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="totalOrderCount"></h4>
                                <p class="mb-0 mt-1 text-sm">Delivered : <span class="text-bold"
                                                                               id="deliveredOrder">0</span>
                                </p>
                                <p class="mb-0 text-sm">Pending: <span class="text-bold"
                                                                       id="pendingOrder">0</span>
                                </p>
                                <p class="mb-0 text-sm">Cancel: <span class="text-bold"
                                                                      id="cancelOrder">0</span>
                                </p>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-3 mb-xl-0 mb-4">
                    <div class="card" style="height: 100%">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-xl-0 pt-5">
                                <a href="javascript:void(0)" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        এক্সপ্যান্স
                                    @else
                                        Expanse
                                    @endif
                                </a>
                                <div id="expanseLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="expanseSummery"></h4>
                                <button class="btn btn-sm btn-outline-primary mt-3" id="openExpenseModalBtn">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        খরচ ব্যবস্থাপনা
                                    @else
                                        Manage Expenses
                                    @endif
                                </button>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4" style="display: flex;justify-content: center;">
                <div class="col-8 card">
                    <div class="card-header">
                        <h4>Revenue Report</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <select name="branch_id" id="branch_id" class="form-control">
                                    @if(isset($branches) && count($branches) > 0)
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="" selected>Select Branch</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" id="startDate" class="form-control">
                            </div>
                            <div class="col-md-1 mt-1 text-center">
                                <label for="to_date">To</label>
                            </div>
                            <div class="col-md-3">
                                <input type="date" id="endDate" class="form-control">
                            </div>
                            <div class="col">
                                <button id="generateReport" class="btn btn-primary position-relative">
        <span class="button-content">
            @if (Session::has('lang') && Session::get('lang') == 'bn')
                রিপোর্ট তৈরি করুন
            @else
                Generate Report
            @endif
        </span>
                                    <span class="loading-content position-absolute start-50 top-50 translate-middle">
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            লোড হচ্ছে...
                                        @else
                                            Loading...
                                        @endif
        </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4" style="height: calc(75%);">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-5">
                    <div class="card" style="height: 100%; position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-5">
                                <p class="text-md text-bold mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পস আয়
                                    @else
                                        POS Revenue
                                    @endif
                                </p>
                                <div id="posLoading" class="loadingDiv"
                                     style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h5 class="mx-1 text-sm mt-1 text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Total sales before any deductions">Gross Sale: <span
                                        class="text-bold"
                                        id="posGrossSale">0</span>
                                </h5>
                                <h5 class="mx-1 text-sm text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Sum of all discounts applied to orders">Total Discount: <span
                                        class="text-bold"
                                        id="posTotalDiscount">0</span>
                                </h5>
                                <h5 class="mx-1 text-sm text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Gross sales minus discounts and returns">Total Net Sale: <span
                                        class="text-bold"
                                        id="posTotalNetSale">0</span>
                                </h5>
                                <h5 class="mx-1 text-sm text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Total tax amount collected on orders">Total Taxes: <span
                                        class="text-bold"
                                        id="posTotalTaxes">0</span>
                                </h5>
                                <hr class="my-2">
                                <h5 class="mx-1 text-sm text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Final amount after all adjustments (net sale + shipping + taxes)">
                                    Total Sale: <span class="text-bold"
                                                      id="posTotalSale">0</span>
                                </h5>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-5">
                    <div class="card" style="height: 100%; position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-5">
                                <p class="text-md text-bold mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        সমস্ত অর্ডার রাজস্ব
                                    @else
                                        All Order Revenue
                                    @endif
                                </p>
                                <div id="allOrderLoading" class="loadingDiv"
                                     style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h5 class="mx-1 text-sm mt-1 text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Total sales before any deductions">Gross Sale: <span
                                        class="text-bold"
                                        id="allOrderGrossSale">0</span>
                                </h5>
                                <h5 class="mx-1 text-sm text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Sum of all discounts applied to orders">Total Discount: <span
                                        class="text-bold"
                                        id="allOrderTotalDiscount">0</span>
                                </h5>
                                <h5 class="mx-1 text-sm text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Gross sales minus discounts and returns">Total Net Sale: <span
                                        class="text-bold"
                                        id="allOrderTotalNetSale">0</span>
                                </h5>
                                <h5 class="mx-1 text-sm text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Total tax amount collected on orders">Total Taxes: <span
                                        class="text-bold"
                                        id="allOrderTotalTaxes">0</span>
                                </h5>
                                <hr class="my-2">
                                <h5 class="mx-1 text-sm text-normal cursor-pointer" data-toggle="tooltip"
                                    title="Final amount after all adjustments (net sale + shipping + taxes)">
                                    Total Sale: <span class="text-bold"
                                                      id="allOrderTotalSale">0</span>
                                </h5>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-5">
                    <div class="card" style="height: 100%; position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-5">
                                <p class="text-md text-bold mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        শাখার তথ্য
                                    @else
                                        Branch Info
                                    @endif
                                </p>
                                <div id="branchInfoLoading" class="loadingDiv"
                                     style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h5 class="mx-1 text-sm mt-1 text-normal">Total Product: <span
                                        class="text-bold"
                                        id="branchTotalProduct">0</span>
                                </h5>
                                <h5 class="mx-1 text-sm text-normal">Total User: <span
                                        class="text-bold"
                                        id="branchTotalUser">0</span>
                                </h5>
                                <h5 class="mx-1 text-sm text-normal">Total Order: <span
                                        class="text-bold"
                                        id="branchTotalOrder">0</span>
                                </h5>
                                <a href="{{ route('admin.report.productTransferReport') }}"
                                   class="btn btn-sm btn-dark mt-3">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পণ্য স্থানান্তর রিপোর্ট
                                    @else
                                        Product Transfer Report
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-6 col-sm-3 mb-4">
                    <div class="card" style="position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>
                            <div class="text-end pt-xxl-0 pt-5">
                                <a href="javascript:void(0)" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মোট বিক্রয়
                                    @else
                                        Gross Sale
                                    @endif
                                </a>
                                <div id="singleGrossSaleLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="posSingleGrossSaleResult">0</h4>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3 mb-4">
                    <div class="card" style="position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-xxl-0 pt-5">
                                <a href="javascript:void(0)" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মোট ছাড়
                                    @else
                                        Total Discount
                                    @endif
                                </a>
                                <div id="singleDiscountLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="posSingleDiscountResult">0</h4>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3 mb-4">
                    <div class="card" style="position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>
                            <div class="text-end pt-xxl-0 pt-5">
                                <a href="javascript:void(0)" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মোট নেট বিক্রয়
                                    @else
                                        Total Net Sale
                                    @endif
                                </a>
                                <div id="singleNetSaleLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="posSingleNetSaleResult">0</h4>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3 mb-4">
                    <div class="card" style="position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-xxl-0 pt-5">
                                <a href="javascript:void(0)" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মোট কর
                                    @else
                                        Total Taxes
                                    @endif
                                </a>
                                <div id="singleTaxesLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="posSingleTaxesResult">0</h4>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3 mb-4">
                    <div class="card" style="height: 100%;position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-xxl-0 pt-5">
                                <a href="javascript:void(0)" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মোট বিক্রয়
                                    @else
                                        Total Sale
                                    @endif
                                </a>
                                <div id="singleTotalSaleLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="posSingleTotalSaleResult">0</h4>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3 mb-4">
                    <div class="card" style="height: 100%;position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-xxl-0 pt-5">
                                <a href="javascript:void(0)" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        EBITA
                                    @else
                                        EBITA
                                    @endif
                                </a>
                                <div id="singleEBITALoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <input type="hidden" id="netSaleResultValue" value="">
                                <input type="hidden" id="expanseResultValue" value="">

                                <h4 class="mb-0" id="totalSingleEBITAResult">0</h4>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3 mb-4">
                    <div class="card" style="height: 100%;position: relative;">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape cardPeople bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <div class="text-end pt-xxl-0 pt-5">
                                <a href="{{ route('admin.order') }}" class="text-sm mb-0 text-capitalize">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মোট অর্ডার
                                    @else
                                        Total Order
                                    @endif
                                </a>
                                <div id="filterTotalOrderLoading" class="loadingDiv" style="display: none">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                                <h4 class="mb-0" id="filterTotalOrderCount">0</h4>
                                <div class="mb-0" id="filterTotalOrderResult">

                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Updated dashboard data
        const loadDashboardData = () => {
            // Show all loading indicators
            $('#productCountLoading').show();
            $('#userCountLoading').show();
            $('#totalOrderCountLoading').show();

            axios.get("{{ route('admin.report.section', ['section' => 'posDashboardData']) }}")
                .then(response => {
                    const data = response?.data?.data || {};

                    // Update general counts
                    const general = data.general || {};
                    $('#productCount').html(general.totalProduct || "0");
                    $('#activeProduct').html(general.activeProduct || "0");
                    $('#inactiveProduct').html(general.inactiveProduct || "0");
                    $('#userCount').html(general.totalUser || "0");

                    // Update order data
                    const orders = data.orders || {};
                    $('#totalOrderCount').html(orders.totalOrder || "0");
                    $('#deliveredOrder').html(orders.deliveredOrder || "0");
                    $('#pendingOrder').html(orders.pendingOrder || "0");
                    $('#cancelOrder').html(orders.cancelOrder || "0");

                    // Hide all loading indicators
                    $('#productCountLoading').hide();
                    $('#userCountLoading').hide();
                    $('#totalOrderCountLoading').hide();
                })
                .catch(error => {
                    // Reset all values on error
                    $('[id$="Count"]').html("0");
                    $('#productCountLoading').hide();
                    $('#userCountLoading').hide();
                    $('#totalOrderCountLoading').hide();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load dashboard data',
                        confirmButtonColor: '#3085d6',
                    });
                });
        };

        // Initialize on page load
        $(document).ready(() => {
            loadDashboardData();
        });


        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Report
        $('#generateReport').click(function () {
            loadRevenueReport($('#startDate').val(), $('#endDate').val(), $("#branch_id").val());
        });

        function loadRevenueReport(startDate, endDate, branchId) {
            $('#generateReport').addClass('loading');

            $('#posLoading').show();
            $('#allOrderLoading').show();
            $('#branchInfoLoading').show();

            $('#singleGrossSaleLoading').show();
            $('#singleDiscountLoading').show();
            $('#singleNetSaleLoading').show();
            // $('#singleShippingChargeLoading').show();
            $('#singleTaxesLoading').show();
            $('#singleTotalSaleLoading').show();
            // $('#singleTotalSaleWithShippingLoading').show();
            $('#singleEBITALoading').show();
            $('#filterTotalOrderLoading').show();

            $.ajax({
                url: '{{ route("admin.report.posRevenueReport") }}',
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    branchId: branchId,
                },
                success: function (response) {
                    updateReportUI(response);
                    $('#generateReport').removeClass('loading');

                    $('#posLoading').hide();
                    $('#allOrderLoading').hide();
                    $('#branchInfoLoading').hide();

                    $('#singleGrossSaleLoading').hide();
                    $('#singleDiscountLoading').hide();
                    $('#singleNetSaleLoading').hide();
                    $('#singleTaxesLoading').hide();
                    $('#singleTotalSaleLoading').hide();
                    $('#singleEBITALoading').hide();
                    $('#filterTotalOrderLoading').hide();
                },
                error: function () {
                    Swal.fire(
                        'Error!',
                        'Error loading report',
                        'error'
                    );
                    $('#generateReport').removeClass('loading');

                    $('#posLoading').hide();
                    $('#allOrderLoading').hide();
                    $('#branchInfoLoading').hide();

                    $('#singleGrossSaleLoading').hide();
                    $('#singleDiscountLoading').hide();
                    $('#singleNetSaleLoading').hide();
                    $('#singleTaxesLoading').hide();
                    $('#singleTotalSaleLoading').hide();
                    $('#singleEBITALoading').hide();
                    $('#filterTotalOrderLoading').hide();
                }
            });
        }

        function updateReportUI(data) {
            // Update POS stats
            $('#posGrossSale').text(data?.branch?.delivered_orders?.gross_sale?.toFixed(2));
            $('#posSingleGrossSaleResult').text(data?.branch?.delivered_orders?.gross_sale?.toFixed(2));

            $('#posTotalDiscount').text(data?.branch?.delivered_orders?.total_discount?.toFixed(2));
            $('#posSingleDiscountResult').text(data?.branch?.delivered_orders?.total_discount?.toFixed(2));

            $('#posTotalNetSale').text(data?.branch?.delivered_orders?.net_sale?.toFixed(2));
            $('#posSingleNetSaleResult').text(data?.branch?.delivered_orders?.net_sale?.toFixed(2));

            $('#posTotalTaxes').text(data?.branch?.delivered_orders?.total_taxes?.toFixed(2));
            $('#posSingleTaxesResult').text(data?.branch?.delivered_orders?.total_taxes?.toFixed(2));

            $('#posTotalSale').text(data?.branch?.delivered_orders?.total_sale?.toFixed(2));
            $('#posSingleTotalSaleResult').text(data?.branch?.delivered_orders?.total_sale?.toFixed(2));

            $('#netSaleResultValue').val(data?.branch?.delivered_orders?.total_sale?.toFixed(2));
            calculateEBITA();

            // Update all totals
            $('#allOrderGrossSale').text(data?.branch?.branch?.all_orders?.gross_sale?.toFixed(2));
            $('#allOrderTotalDiscount').text(data?.branch?.all_orders?.total_discount?.toFixed(2));
            $('#allOrderTotalNetSale').text(data?.branch?.all_orders?.net_sale?.toFixed(2));
            $('#allOrderTotalTaxes').text(data?.branch?.all_orders?.total_taxes?.toFixed(2));
            $('#allOrderTotalSale').text(data?.branch?.all_orders?.total_sale?.toFixed(2));

            // Branch info
            $('#branchTotalProduct').text(data?.branchInfo?.general?.total_product);
            $('#branchTotalUser').text(data?.branchInfo?.general?.total_user);
            $('#branchTotalOrder').text(data?.branchInfo?.total_order);


            $('#filterTotalOrderCount').text(data?.order_counts?.total || 0);
            // Update status breakdown
            const breakdownContainer = $('#filterTotalOrderResult');
            breakdownContainer.empty();

            if (data?.order_counts?.by_status) {
                Object.entries(data?.order_counts?.by_status).forEach(([status, count]) => {
                    const statusItem = `
                                <p class="mb-0 mt-1 text-sm">${status}: <span class="text-bold"
                                 id="deliveredOrder">${count}</span>
                                </p>
                            `;
                    breakdownContainer.append(statusItem);
                });
            }
        }

        function calculateEBITA() {
            // Get input values and convert to numbers
            const netSale = parseFloat($('#netSaleResultValue').val()) || 0;
            const expenses = parseFloat($('#expanseResultValue').val()) || 0;

            // Calculate EBITA (Earnings Before Interest, Taxes, and Amortization)
            const ebita = netSale - expenses;

            // Display result with 2 decimal places and proper formatting
            $('#totalSingleEBITAResult').text(ebita.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        // Initialize with current month
        $(document).ready(function () {
            const startDate = new Date();
            startDate.setDate(1);
            const endDate = new Date();

            $('#startDate').val(startDate.toISOString().split('T')[0]);
            $('#endDate').val(endDate.toISOString().split('T')[0]);

            const branch_id = $("#branch_id").val();

            loadRevenueReport(startDate.toISOString().split('T')[0], endDate.toISOString().split('T')[0], branch_id);
        });

    </script>
@endpush
