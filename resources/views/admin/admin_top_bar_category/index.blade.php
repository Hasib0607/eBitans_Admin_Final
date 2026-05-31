@php
    $current = explode('/',Request::path())[0];
@endphp
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">

                @php
                    $userData = getUserData();
                    $store_id = $userData['store_id'] ?? "";
                @endphp
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if ($current == 'products') active @endif">
                        <a href="{{ URL::to('/') }}/products">
                            <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    পণ্য
                                @else
                                    Products
                                @endif
                            </span>
                        </a>
                    </li>
                    @if (ModulusStatus($store_id, 121))
                        <li class="breadcrumb-item @if ($current == 'layout-products') active @endif"
                            aria-current="page">
                            <a href="{{ route('admin.layout_product') }}">
                                <img src="{{ URL::to('/') }}/img/icons/box.png"> <br><span
                                    class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পণ্য অবতরণ পৃষ্ঠা
                                    @else
                                        Product Landing Page
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endif
                    @if (canAccess('category'))
                        <li class="breadcrumb-item @if ($current == 'category') active @endif"
                            aria-current="page">
                            <a href="{{ URL::to('/') }}/category">
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
                    @if (canAccess('subcategory'))
                        <li class="breadcrumb-item @if ($current == 'subcategory') active @endif"
                            aria-current="page">
                            <a href="{{ route('admin.subcategory.index') }}">
                                <img src="{{ URL::to('/') }}/img/subcategory.png"> <br><span
                                    class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        সাব ক্যাটাগরি
                                    @else
                                        Sub Categories
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endif

                    @if ((canAccess('attribute') && ModulusStatus($store_id, 114)))
                        <li class="breadcrumb-item @if ($current == 'attribute') active @endif"
                            aria-current="page">
                            <a href="{{ URL::to('/') }}/attribute">
                                <img src="{{ URL::to('/') }}/img/icons/product.png"><br><span
                                    class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পণ্যের ধরণ
                                    @else
                                        Variants
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endif
                    @if (canAccess('brand'))
                        <li class="breadcrumb-item @if ($current == 'brand') active @endif"
                            aria-current="page">
                            <a href="{{ URL::to('/') }}/brand">
                                <img src="{{ URL::to('/') }}/img/icons/brand.png"> <br><span
                                    class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ব্রান্ড
                                    @else
                                        Brands
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endif
                    @if (canAccess('supplier'))
                        <li class="breadcrumb-item @if ($current == 'supplier') active @endif"
                            aria-current="page">
                            <a href="{{ URL::to('/') }}/supplier">
                                <img src="{{ URL::to('/') }}/img/icons/supplier.png"> <br><span
                                    class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        সরবরাহকারী
                                    @else
                                        Suppliers
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
