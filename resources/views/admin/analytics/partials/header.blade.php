<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if (url()->current() == route('admin.ebitans.analytics')) active @endif">
                        <a href="{{ route('admin.ebitans.analytics') }}">
                            <img src="https://admin.ebitans.com/img/icons/box.png"> <br> <span
                                class="nav-link-text ms-1"> Analytic Dashboard </span>
                        </a>
                    </li>

                    <li class="breadcrumb-item @if (url()->current() == route('admin.ebitans.analytics.all.url')) active @endif" aria-current="page">
                        <a href="{{ route('admin.ebitans.analytics.all.url') }}">
                            <img src="https://admin.ebitans.com/img/icons/categories.png"> <br><span
                                class="nav-link-text ms-1"> All Links </span>
                        </a>
                    </li>

                    <li class="breadcrumb-item @if (url()->current() == route('product.khujo') ||
                            url()->current() == route('weekly.report') ||
                            url()->current() == route('monthly.report') ||
                            url()->current() == route('all.visitor')) active @endif" aria-current="page">
                        <a href="{{ route('product.khujo') }}">
                            <img src="{{ URL::to('/') }}/img/icons/product-khujo.png"> <br><span
                                class="nav-link-text ms-1"> Product খুঁজো </span>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
