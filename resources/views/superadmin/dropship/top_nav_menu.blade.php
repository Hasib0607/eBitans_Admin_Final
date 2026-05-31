<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if (request()->routeIs('superadmin.dropshipper') || request()->routeIs('superadmin.dropship.order.details')) active @endif"
                        aria-current="page">
                        <a href="{{ route('superadmin.dropshipper') }}">
                            <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                            <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Dropship Commission
                                @else
                                    Dropship Commission
                                @endif
                                    </span>
                        </a>
                    </li>

                    <li class="breadcrumb-item @if (request()->routeIs('superadmin.overflow.list')) active @endif"
                        aria-current="page">
                        <a href="{{ route('superadmin.overflow.list') }}">
                            <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                            <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Commission Overflow List
                                @else
                                    Commission Overflow List
                                @endif
                                    </span>
                        </a>
                    </li>

                </ol>
            </nav>
        </div>
    </div>
</div>
