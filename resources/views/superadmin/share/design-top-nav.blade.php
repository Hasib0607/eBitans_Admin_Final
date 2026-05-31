@php
    $current_page = explode('/',Request::path())[0];
@endphp
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if (canSuperStaffAccess('design'))
                        <li class="breadcrumb-item @if($current_page == "design") active @endif">
                            <a href="{{route('superadmin.designlist')}}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Design
                            </a>
                        </li>
                        <li class="breadcrumb-item @if($current_page == "icon-pack") active @endif">
                            <a href="{{route('superadmin.iconpack')}}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Icon Pack
                            </a>
                        </li>
                        <li class="breadcrumb-item @if($current_page == "business-category") active @endif">
                            <a href="{{route('super_admin.business_category')}}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Business Category
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('template'))
                        <li class="breadcrumb-item @if(url()->current() == route('superadmin.template')) active @endif">
                            <a href="{{route('superadmin.template')}}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br> Template
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item @if(
                                            request()->routeIs('superadmin.store.category.list') ||
                                            request()->routeIs('superadmin.store.product.list') ||
                                            request()->routeIs('superadmin.store.slider.list') ||
                                            request()->routeIs('superadmin.store.banner.list') ||
                                            request()->routeIs('superadmin.store.theme.list') ||
                                            request()->routeIs('superadmin.store.header.list') ||
                                            request()->routeIs('superadmin.store.category.create') ||
                                            request()->routeIs('superadmin.store.edit.category') ||
                                            request()->routeIs('superadmin.store.product.create') ||
                                            request()->routeIs('superadmin.store.edit.product') ||
                                            request()->routeIs('superadmin.store.slider.create') ||
                                            request()->routeIs('superadmin.store.edit.slider') ||
                                            request()->routeIs('superadmin.store.banner.create') ||
                                            request()->routeIs('superadmin.store.edit.banner') ||
                                            request()->routeIs('superadmin.store.theme.create') ||
                                            request()->routeIs('superadmin.store.edit.theme') ||
                                            request()->routeIs('superadmin.store.header.create') ||
                                            request()->routeIs('superadmin.store.edit.header')
                                            ) active @endif">
                        <a href="{{route('superadmin.store.category.list')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> AI Store Data
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
