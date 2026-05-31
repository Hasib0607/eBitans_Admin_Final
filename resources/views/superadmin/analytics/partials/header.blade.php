<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb" class="m-0">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item">
                        <a href="{{ route('super.admin.ebitans.backend.analytics') }}">
                            <img src="https://admin.ebitans.com/img/icons/box.png"> <br> <span
                                class="nav-link-text ms-1"> Back End User </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('super.admin.ebitans.analytics') }}">
                            <img src="https://admin.ebitans.com/img/icons/box.png"> <br> <span
                                class="nav-link-text ms-1"> Analytic Dashboard </span>
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('super.admin.ebitans.analytics.all.traffic') }}">
                            <img src="https://admin.ebitans.com/img/icons/box.png"> <br> <span
                                class="nav-link-text ms-1"> All Traffic </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{ route('super.admin.ebitans.analytics.all.url') }}">
                            <img src="https://admin.ebitans.com/img/icons/categories.png"> <br><span
                                class="nav-link-text ms-1"> All Links </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{ route('super.admin.ebitans.analytics.all.store') }}">
                            <img src="https://admin.ebitans.com/img/icons/categories.png"> <br><span
                                class="nav-link-text ms-1"> All Store </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if(request()->routeIs('super.admin.ebitans.analytics.website.visitor')) active @endif"
                        aria-current="page">
                        <a href="{{ route('super.admin.ebitans.analytics.website.visitor') }}">
                            <img src="https://admin.ebitans.com/img/icons/categories.png"> <br><span
                                class="nav-link-text ms-1"> Website Visitor </span>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
