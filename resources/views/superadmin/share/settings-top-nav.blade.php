<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if(request()->routeIs('super_admin.settings.index')) active @endif">
                        <a href="{{route('super_admin.settings.index')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Setting
                        </a>
                    </li>

                    <li class="breadcrumb-item @if(request()->routeIs('super_admin.settings.currency_list')) active @endif">
                        <a href="{{route('super_admin.settings.currency_list')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Currency
                        </a>
                    </li>

                    <li class="breadcrumb-item @if(request()->routeIs('super_admin.settings.store.static.data')) active @endif">
                        <a href="{{route('super_admin.settings.store.static.data')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Store Data
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
