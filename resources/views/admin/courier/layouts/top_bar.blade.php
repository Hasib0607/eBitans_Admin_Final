<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li
                        class="breadcrumb-item @if (request()->url() === route('courier.courierPage', ['name' => 'pathao'])) active @endif">
                        <a href="{{ route('courier.courierPage', ["name" => "pathao"]) }}">
                            <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    পাঠাও
                                @else
                                    Pathao
                                @endif
                            </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if (request()->url() === route('courier.courierPage', ['name' => 'steadfast'])) active @endif"
                        aria-current="page">
                        <a href="{{ route('courier.courierPage', ["name" => "steadfast"]) }}">
                            <img src="{{ URL::to('/') }}/img/icons/categories.png"> <br><span
                                class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Steadfast
                                @else
                                    Steadfast
                                @endif
                            </span>
                        </a>
                    </li>
                    <!-- <li class="breadcrumb-item @if (request()->url() === route('courier.courierPage', ['name' => 'ecourier'])) active @endif"
                        aria-current="page">
                        <a href="{{ route('courier.courierPage', ['name' => 'ecourier']) }}">
                            <img src="{{ URL::to('/') }}/img/subcategory.png"> <br>
                            <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    eCourier
                                @else
                                    eCourier
                                @endif
                            </span>
                        </a>
                    </li> -->

                    <li class="breadcrumb-item @if (request()->url() === route('courier.courierPage', ['name' => 'redx'])) active @endif"
                        aria-current="page">
                        <a href="{{ route('courier.courierPage', ['name' => 'redx']) }}">
                            <img src="{{ URL::to('/') }}/img/icons/product.png"><br>
                            <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    RedX
                                @else
                                    RedX
                                @endif
                            </span>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>