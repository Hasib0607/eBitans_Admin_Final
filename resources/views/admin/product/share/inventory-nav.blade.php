@php
    $current = explode('/',Request::path())[0];
@endphp
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if (request()->url() === route('admin.inventory') || request()->url() === route('admin.expiry.product.filter')) active @endif">
                        <a href="{{ URL::to('/') }}/inventory">
                            <img src="{{ URL::to('/') }}/img/icons/inventory-2.png"> <br> <span
                                class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    মালগুদাম
                                @else
                                    Inventory
                                @endif
                                    </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if ($current == 'stock_alert') active @endif" aria-current="page">
                        <a href="{{ route('admin.stockalert') }}">
                            <img src="{{ URL::to('/') }}/img/icons/new-product.png"> <br><span
                                class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    স্টক সতর্কতা
                                @else
                                    Stock Alert
                                @endif
                                    </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if ($current == 'stock_out') active @endif" aria-current="page">
                        <a href="{{ route('admin.stockout') }}">
                            <img src="{{ URL::to('/') }}/img/icons/out-of-stock.png"> <br><span
                                class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    মজুত শেষ
                                @else
                                    Stock Out
                                @endif
                                    </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if ($current == 'top-selling') active @endif" aria-current="page">
                        <a href="{{ route('admin.topselling') }}">
                            <img src="{{ URL::to('/') }}/img/subcategory.png"> <br>Top Selling Products
                        </a>
                    </li>
                    <li class="breadcrumb-item @if ($current == 'lowest-selling') active @endif" aria-current="page">
                        <a href="{{ route('admin.lowestselling') }}">
                            <img src="{{ URL::to('/') }}/img/subcategory.png"> <br>Lowest Selling Products
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
