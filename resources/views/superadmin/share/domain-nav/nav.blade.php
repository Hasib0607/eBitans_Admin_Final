<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if (canSuperStaffAccess('domain_request'))
                        <li class="breadcrumb-item @if(request()->routeIs('superadmin.domainrequest')) active @endif">
                            <a href="{{ route('superadmin.domainrequest') }}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Domain Request
                            </a>
                        </li>
                        <li class="breadcrumb-item @if(request()->routeIs('buy.domain.list')) active @endif">
                            <a href="{{ route('buy.domain.list') }}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Buy Domain
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('domain'))
                        <li class="breadcrumb-item @if(request()->routeIs('superadmin.domainlist')) active @endif">
                            <a href="{{ route('superadmin.domainlist') }}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Domain
                            </a>
                        </li>
                        <li class="breadcrumb-item @if(request()->routeIs('superadmin.deleteDomainList')) active @endif">
                            <a href="{{ route('superadmin.deleteDomainList') }}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Delete Domain
                            </a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
