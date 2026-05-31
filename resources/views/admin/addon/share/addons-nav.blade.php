<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if(isset($modulus_type) && $modulus_type == 1)
                        <li class="breadcrumb-item @if(request()->routeIs('admin.marketing.modulus')) active @endif">
                            <a href="{{ route('admin.marketing.modulus') }}">
                                <img src="{{ URL::to('/') }}/img/icons/resume.png" alt="" style="margin-bottom: 10px;">
                                <br>
                                <span
                                    class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মডুলাস
                                    @else
                                        Modulus
                                    @endif
                                    </span>
                            </a>
                        </li>
                    @else
                        <li class="breadcrumb-item @if(request()->routeIs('admin.modulus')) active @endif">
                            <a href="{{ route('admin.modulus') }}">
                                <img src="{{ URL::to('/') }}/img/icons/resume.png"> <br> <span
                                    class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মডুলাস
                                    @else
                                        Modulus
                                    @endif
                                    </span>
                            </a>
                        </li>
                    @endif

                    @include("admin.addon.share.share-addons-nav", ['modulus_type'=> $modulus_type ?? 0])

                    @if(isset($modulus_type) && $modulus_type == 1)
                        @if (ModulusStatus($store_id, 131))
                            <li class="breadcrumb-item @if(request()->routeIs('admin.abandoned.cart.list')) active @endif">
                                <a href="{{ route('admin.abandoned.cart.list') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/color-scheme.png">
                                    <br>
                                    <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            পরিত্যক্ত কার্ট
                                        @else
                                            Abandoned Cart
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endif

                        @php
                            extract(getStoreExpiryStatus());
                        @endphp

                        @if ((isset($exp) && $exp != '1') || paidTrial())
                            @if (canAccess('coupon') || canAccess('campaign') || canAccess('offer'))
                                <li class="breadcrumb-item @if (isset($urls) && $urls == 'promotion') active @endif">
                                    <a href="@if (isset($exp) && $exp == '1' && !paidTrial()) # @else {{ route('admin.promotion.coupon') }} @endif">
                                        <img src="{{ URL::to('/') }}/img/icons/color-scheme.png">
                                        <br>
                                        <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অফার/ প্রোমোশন
                                            @else
                                                Offer/ Promotion
                                            @endif
                                </span>
                                    </a>
                                </li>
                            @endif
                        @endif

                        @if ((canAccess('blog') && ModulusStatus($store_id, 116)) || canSuperStaffAccess("blog"))
                            <li class="breadcrumb-item @if (request()->routeIs('superadmin.blog.index') ||
                                    request()->routeIs('superadmin.blog.create') ||
                                    request()->routeIs('superadmin.blog.type.index')) active @endif">
                                <a href="{{ route('superadmin.blog.index') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/color-scheme.png">
                                    <br>
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            Blogsন
                                        @else
                                            Blogs
                                        @endif
                                        </span>
                                </a>
                            </li>
                        @endif

                    @else
                        <li class="breadcrumb-item @if(isset($theme)) active @endif">
                            <a href="{{ route('admin.themecustomize') }}">
                                <img src="{{ URL::to('/') }}/img/icons/color-scheme.png">
                                <br>
                                <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        থিম কাস্টমাইজ করুন
                                    @else
                                        Theme Customization
                                    @endif
                            </span>
                            </a>
                        </li>
                        <li class="breadcrumb-item @if(isset($mobile_app)) active @endif">
                            <a href="{{ route('admin.addonss') }}">
                                <img src="{{ URL::to('/') }}/img/icons/ecommerce.png"> <br> <span
                                    class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ই-কমার্স মোবাইল অ্যাপ
                                    @else
                                        E-Commerce Mobile App
                                    @endif
                                    </span>
                            </a>
                        </li>
                        <li class="breadcrumb-item @if(isset($website_setup)) active @endif">
                            <a href="{{ route('admin.websitesetup') }}">
                                <img src="{{ URL::to('/') }}/img/icons/ecommerce.png"> <br> <span
                                    class="nav-link-text ms-1">Website Setup</span>
                            </a>
                        </li>
                        {{--                        <li class="breadcrumb-item @if(isset($payment_gateway)) active @endif">--}}
                        {{--                            <a href="{{ route('admin.paymentgateway') }}">--}}
                        {{--                                <img src="{{ URL::to('/') }}/img/icons/ecommerce.png"> <br> <span--}}
                        {{--                                    class="nav-link-text ms-1">Payment Gateway</span>--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}
                        @php
                            $act = DB::table('activities')->where('store_id', $store_id)->whereDate('expiry_date', '>=', Carbon\Carbon::now())->first();
                        @endphp
                        @if (isset($act))
                            <li class="breadcrumb-item @if(isset($active_log)) active @endif">
                                <a href="{{ route('admin.activitylog') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/ecommerce.png"> <br> <span
                                        class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            কার্য বিবরণ
                                        @else
                                            Activity Log
                                        @endif
                                        </span>
                                </a>
                            </li>
                        @endif
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
