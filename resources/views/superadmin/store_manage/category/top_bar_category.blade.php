<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if (canSuperStaffAccess('pse'))
                        <li class="breadcrumb-item @if (url()->current() == route('superadmin.product.pse') ||
                            url()->current() == route('superadmin.pse.accepted') ||
                            url()->current() == route('superadmin.pse.rejected')) active @endif">
                            <a href="{{ route('superadmin.product.pse') }}">
                                <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পণ্য
                                    @else
                                        Products
                                    @endif
                            </span>
                            </a>
                        </li>

                        <li class="breadcrumb-item @if (url()->current() == route('pse.category')) active @endif"
                            aria-current="page">
                            <a href="{{ route('pse.category') }}">
                                <img src="{{ URL::to('/') }}/img/icons/categories.png"> <br><span
                                    class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ক্যাটাগরি
                                    @else
                                        Categories
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->type == 'superadmin')
                        <li class="breadcrumb-item @if (url()->current() == route('superadmin.pse.ad') || url()->current() == route('superadmin.pse.create')) active @endif"
                            aria-current="page">
                            <a href="{{ route('superadmin.pse.ad') }}">
                                <img src="{{ URL::to('/') }}/img/icons/slider.png"> <br><span
                                    class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        বিজ্ঞাপন
                                    @else
                                        Ad
                                    @endif
                                </span>
                            </a>
                        </li>
                        <li class="breadcrumb-item @if (url()->current() == route('superadmin.pse.visitor')) active @endif"
                            aria-current="page">
                            <a href="{{ route('superadmin.pse.visitor') }}">
                                <img src="{{ URL::to('/') }}/img/icons/slider.png"> <br><span
                                    class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        দর্শক
                                    @else
                                        Visitors
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
