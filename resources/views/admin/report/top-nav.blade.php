<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if(request()->routeIs('admin.report')) active @endif">
                        <a href="{{ route('admin.report') }}">
                            <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Report
                        </a>
                    </li>
                    @if(isActivePos())
                        <li class="breadcrumb-item @if(request()->routeIs('admin.posReport') || request()->routeIs('admin.report.productTransferReport')) active @endif">
                            <a href="{{ route('admin.posReport') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> POS Report
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item @if(request()->routeIs('admin.completeorder')) active @endif"
                        aria-current="page">
                        <a href="{{ route('admin.completeorder') }}">
                            <img src="{{ URL::to('/') }}/img/subcategory.png"> <br>Selling Report
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
