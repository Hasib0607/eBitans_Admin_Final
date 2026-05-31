<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if (canSuperStaffAccess('staff'))
                        <li class="breadcrumb-item @if(url()->current() == route('superadmin.staff')) active @endif">
                            <a href="{{route('superadmin.staff')}}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Staff
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('role_and_permission'))
                        <li class="breadcrumb-item @if(url()->current() == route('superadmin.role.permission')) active @endif">
                            <a href="{{route('superadmin.role.permission')}}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Role and Permission
                            </a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
