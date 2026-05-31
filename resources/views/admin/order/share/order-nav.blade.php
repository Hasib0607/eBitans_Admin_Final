@php
    $current = explode('/',Request::path())[0];
@endphp
<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if(canAccess('orders'))
                        <li class="breadcrumb-item @if ($current == 'order') active @endif">
                            <a href="{{ route('admin.order') }}">
                                <img src="{{ URL::to('/') }}/img/icons/order.png"> <br> <span
                                    class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        অর্ডার
                                    @else
                                        Order
                                    @endif
                                    </span>
                            </a>
                        </li>
                    @endif
                    @if(canAccess('returned_product'))
                        <li class="breadcrumb-item @if ($current == 'returned') active @endif">
                            <a href="{{ route('admin.returned') }}">
                                <img src="{{ URL::to('/') }}/img/icons/product-return.png"> <br> <span
                                    class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ফেরত পণ্য
                                    @else
                                        Returned Product
                                    @endif
                                    </span>
                            </a>
                        </li>
                    @endif
                    @if(canAccess('invoice'))
                        <li class="breadcrumb-item @if ($current == 'invoice') active @endif">
                            <a href="{{ route('admin.invoice') }}">
                                <img src="{{ URL::to('/') }}/img/icons/bill.png"> <br> <span
                                    class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        চালান
                                    @else
                                        Invoice
                                    @endif
                                    </span>
                            </a>
                        </li>
                    @endif

                </ol>
            </nav>
        </div>
    </div>
</div>
