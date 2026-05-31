<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header sticky" style="z-index:9999999999999">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
           aria-hidden="true" id="iconSidenav"></i>
        @if (Auth::user()->type == 'staff' || Auth::user()->type == 'superstaff')
            <a class="navbar-brand m-0" href="{{ route('staff.dashboard') }}">
                <img src="{{ asset('logo-white.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            </a>
        @else
            <a class="navbar-brand m-0" href="/">
                <img src="{{ asset('logo-white.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            </a>
        @endif
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <!--  -->
    <style>
        .navbar-vertical.navbar-expand-xs .navbar-collapse {
            height: calc(100vh - 1px);
        }
    </style>

    <div class="collapse  navbar-collapse w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if (Auth::user()->type == 'staff' || Auth::user()->type == 'superstaff')
                <li class="nav-item">
                    <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'dashboard') active bg-gradient-primary @endif @endif "
                       href="{{ route('staff.dashboard') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1" id="ffd">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                ড্যাশবোর্ড
                            @else
                                Dashboard
                            @endif
                            </span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'dashboard') active bg-gradient-primary @endif @endif "
                       href="/">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1" id="ffd">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                ড্যাশবোর্ড
                            @else
                                Dashboard
                            @endif
                            </span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->type == 'superadmin' || canSuperStaffAccess('report_dashboard') || canSuperStaffAccess('paid_clients_list') || canSuperStaffAccess('addon_sell_report') || canSuperStaffAccess('sms_log'))
                <li class="nav-item">
                    <a class="nav-link text-white  @if (url()->current() == route('superadmin.report') ||  url()->current() == route('paidClientsList') ||  url()->current() == route('addonSellReport') ||  url()->current() == route('superadmin.store.sms.list')) active bg-gradient-primary @endif"
                       href="@if(canSuperStaffAccess('report_dashboard')){{ route('superadmin.report') }}@elseif(canSuperStaffAccess('paid_clients_list')){{ route('paidClientsList') }}@elseif(canSuperStaffAccess('addon_sell_report')){{ route('addonSellReport') }}@elseif(canSuperStaffAccess('sms_log')){{ route('superadmin.store.sms.list') }}@endif">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                Report
                            @else
                                Report
                            @endif
                        </span>
                    </a>
                </li>
            @endif

            @php
                $canSeeSsBotMenu = Auth::check()
                    && in_array(Auth::user()->type, ['superadmin', 'superstaff', 'superadminstaff']);
                $ssBotMenuActive = request()->routeIs('messages')
                    || request()->routeIs('seemessages')
                    || request()->routeIs('chat.index')
                    || request()->routeIs('chatBot.*')
                    || request()->routeIs('superadmin.whatsapp.launch');
                $ssBotMenuUrl = canSuperStaffAccess('message')
                    ? route('messages')
                    : (canSuperStaffAccess('chatbot')
                        ? route('chatBot.index')
                        : (canSuperStaffAccess('whatsapp')
                            ? route('superadmin.whatsapp.launch')
                            : 'javascript:void(0)'));
            @endphp

            @if ($canSeeSsBotMenu && $ssBotMenuUrl !== 'javascript:void(0)')
                <li class="nav-item">
                    <a class="nav-link text-white @if ($ssBotMenuActive) active bg-gradient-primary @endif"
                       href="{{ $ssBotMenuUrl }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">smart_toy</i>
                        </div>
                        <span class="nav-link-text ms-1">SS Bot</span>
                    </a>
                </li>
            @endif

            @if (canSuperStaffAccess('pse'))
                <li class="nav-item">
                    <a class="nav-link text-white
                                @if (url()->current() == route('superadmin.product.pse') ||
                                        url()->current() == route('superadmin.store.category') ||
                                        url()->current() == route('superadmin.pse.ad') ||
                                        url()->current() == route('superadmin.pse.create') ||
                                        url()->current() == route('superadmin.pse.accepted') ||
                                        url()->current() == route('superadmin.pse.rejected') ||
                                        url()->current() == route('pse.category') ||
                                        url()->current() == route('superadmin.pse.visitor')) active bg-gradient-primary @endif
                            "
                       href="{{ route('superadmin.product.pse') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                প্রোডাক্ট সার্চ ইঞ্জিন
                            @else
                                PSE
                            @endif
                            </span>
                    </a>
                </li>
            @endif

            @php
                $store_id = "";
                    if( Auth::user()->type == "admin" ||  Auth::user()->type == "staff" || Auth::user()->type == 'dropshipper'){
                        $userData = getUserData();
                        $store_id = $userData['store_id'] ?? "";
                    }
            @endphp
            @if ((Auth::user()->type == "admin" || Auth::user()->type == "staff") && (canAccess('announcement') && ModulusStatus($store_id, 117)))
                <li class="nav-item">
                    <a class="nav-link text-white @if (url()->current() == route('admin.announcement.index')) active bg-gradient-primary @endif"
                       href="{{ route('admin.announcement.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">Announcement </span>
                    </a>
                </li>
            @endif

            @if (ModulusStatus($store_id, 3) && (canAccess('analytics')))
                <li class="nav-item">
                    <a class="nav-link text-white  @if (url()->current() == route('admin.ebitans.analytics') ||
                                url()->current() == route('admin.ebitans.analytics.all.url') ||
                                url()->current() == route('product.khujo') ||
                                url()->current() == route('weekly.report') ||
                                url()->current() == route('monthly.report') ||
                                url()->current() == route('all.visitor')) active bg-gradient-primary @endif"
                       href="{{ route('admin.ebitans.analytics') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                বিশ্লেষণ
                            @else
                                Analytics
                            @endif
                            </span>
                    </a>
                </li>
            @endif

            @if (canSuperStaffAccess('staff'))
                <li class="nav-item">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'ebi-analytics') active bg-gradient-primary @endif @endif"
                       href="{{ route('super.admin.ebitans.analytics') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                ইবিট্যান্টস বিশ্লেষণ
                            @else
                                Ebitans Analytics
                            @endif
                            </span>
                    </a>
                </li>
            @endif

            {{-- Products Manage --}}
            @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superadminstaff')
                <li class="nav-item">
                    <a class="nav-link text-white  @if (url()->current() == route('superadmin.store.manage')) active bg-gradient-primary @endif"
                       href="{{ route('superadmin.store.manage') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                দোকান ব্যবস্থাপনা
                            @else
                                Store Management
                            @endif
                            </span>
                    </a>
                </li>
            @endif

            {{-- Products Manage end  --}}
            @if ( canAccess('branch') || (isset($staff_pos) && $staff_pos == '1') )
                @if (isset($store) && !empty($store))
                    @if (isset($posplan) || isAddonActive(13))
                        <li class="nav-item">
                            <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'branch') active bg-gradient-primary @endif @endif "
                               href="{{ URL::to('/') }}/branch">
                                <div
                                    class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/ios-glyphs/20/ffffff/shop.png"/>
                                </div>
                                <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        স্টোর
                                        ব্রাঞ্চ
                                    @else
                                        POS
                                    @endif
                                    </span>
                            </a>
                        </li>
                    @endif
                @endif
            @endif

            @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')
                @if (canSuperStaffAccess('branch_delete_request'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'branchdel') active bg-gradient-primary @endif @endif "
                           href="{{ URL::to('/') }}/branchdel">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Branch Delete Request</span>
                        </a>
                    </li>
                @endif
                @if (canSuperStaffAccess('customer'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'supercustomer') active bg-gradient-primary @endif @endif "
                           href="{{ URL::to('/') }}/superadmin/customer">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Customer</span>
                        </a>
                    </li>
                @endif
                @if (canSuperStaffAccess('domain_request') || canSuperStaffAccess('domain'))
                    <li class="nav-item">
                        <a class="nav-link text-white @if(request()->routeIs('superadmin.domainrequest') || request()->routeIs('buy.domain.list') || request()->routeIs('superadmin.domainlist')) active bg-gradient-primary @endif"
                           href="{{ URL::to('/') }}/domain/request">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Domain Request</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->type == 'superadmin' && (Auth::user()->phone != "01677515573"))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'planorderrequest') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.orderPlanrequest') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    পেমেন্ট অনুরোধ
                                @else
                                    Payment Request
                                @endif
                        </span>
                        </a>
                    </li>
                @endif
                @if (canSuperStaffAccess('clients') || canSuperStaffAccess('paid_clients') || canSuperStaffAccess('clients_Activities') || canSuperStaffAccess('clients_Follow_Up'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls) && $urls == 'clients') active bg-gradient-primary @endif"
                           href="@if(canSuperStaffAccess('clients')){{ route('admin.clients') }} @elseif(canSuperStaffAccess('paid_clients')) {{ route('admin.paidClients') }} @elseif(canSuperStaffAccess('clients_Activities')) {{ route('admin.clients.activities') }} @elseif(canSuperStaffAccess('clients_Follow_Up')) {{ route('admin.clients.followUp') }} @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Clients</span>
                        </a>
                    </li>
                @endif
                @if (canSuperStaffAccess('design') || canSuperStaffAccess('template'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'designlist' || $urls == 'templates') active bg-gradient-primary @endif @endif "
                           href="{{ URL::to('/') }}/design/list">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Design</span>
                        </a>
                    </li>
                @endif
                @if (canSuperStaffAccess('staff') || canSuperStaffAccess('role_and_permission'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'superstaff' || $urls == 'superrolepermission') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.staff') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Teammate</span>
                        </a>
                    </li>
                @endif
                @if (canSuperStaffAccess('amarpay_kyc'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (request()->routeIs('superadmin.amaypay.kyc') || request()->routeIs('superadmin.amaypay.payment.list') || request()->routeIs('superadmin.amaypay.client.list'))  active bg-gradient-primary @endif"
                           href="{{ route('superadmin.amaypay.kyc') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Ebitans Payment</span>
                        </a>
                    </li>
                @endif
            @endif

            @if (canAccess('product') || canAccess('category') || canAccess('subcategory') || canAccess('brand') || canAccess('attribute') || canAccess('supplier'))
                <li class="nav-item" id="producttour">
                    <a class="nav-link text-white  @if (url()->current() == url('/products') ||
                                url()->current() == url('/category') ||
                                url()->current() == route('admin.subcategory.index') ||
                                url()->current() == url('/attribute') ||
                                url()->current() == url('/brand') ||
                                url()->current() == url('/supplier')) active bg-gradient-primary @endif "
                       href="@if (isset($exp) && $exp == '1' && isset($dexp) && $dexp == '1' && !paidTrial()) # @else {{ URL::to('/') }}/products @endif">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img src="https://img.icons8.com/material/20/ffffff/shipping-product.png"/>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                প্রোডাক্টস
                            @else
                                Products
                            @endif
                            </span>
                    </a>
                </li>
            @endif

            {{--            @if ((isset($exp) && $exp != '1') || paidTrial())--}}
            {{--                @if (canAccess('coupon') || canAccess('campaign') || canAccess('offer'))--}}
            {{--                    <li class="nav-item">--}}
            {{--                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'promotion') active bg-gradient-primary @endif @endif"--}}
            {{--                           href="@if (isset($exp) && $exp == '1' && !paidTrial()) # @else {{ route('admin.promotion.coupon') }} @endif">--}}
            {{--                            <div--}}
            {{--                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">--}}
            {{--                                <img--}}
            {{--                                    src="https://img.icons8.com/external-flatart-icons-solid-flatarticons/22/ffffff/external-offer-shopping-and-commerce-flatart-icons-solid-flatarticons.png"/>--}}
            {{--                            </div>--}}
            {{--                            <span class="nav-link-text ms-1">--}}
            {{--                                    @if (Session::has('lang') && Session::get('lang') == 'bn')--}}
            {{--                                    অফার/ প্রোমোশন--}}
            {{--                                @else--}}
            {{--                                    Offer/ Promotion--}}
            {{--                                @endif--}}
            {{--                                </span>--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            {{--                @endif--}}
            {{--            @endif--}}
            @if ((isset($exp) && $exp != '1') || isset($posplan))
                @if (canAccess('inventory'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'inventory') active bg-gradient-primary @endif @endif"
                           href=" @if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.inventory') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img src="https://img.icons8.com/glyph-neue/20/ffffff/warehouse-1.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    মালগুদাম
                                @else
                                    Inventory
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif
            @if ((isset($exp) && $exp != '1') || isset($posplan) || paidTrial())
                @if (canAccess('orders') || canAccess('returned_product') || canAccess('invoice'))
                    <li class="nav-item" id="ordertour">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'order') active bg-gradient-primary @endif @endif"
                           href="@if (canAccess('orders')) {{ route('admin.order') }} @elseif (canAccess('returned_product')) {{ route('admin.returned') }}  @elseif (canAccess('invoice')) {{ route('admin.invoice') }} @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img
                                    src="https://img.icons8.com/ios-glyphs/20/ffffff/shopping-basket-success.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    অর্ডার
                                @else
                                    Orders
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif
            @if (Auth::user()->type == 'superadmin')
                <li class="nav-item">
                    <a class="nav-link text-white  @if ( request()->routeIs('superadmin.order.status.index') ) active bg-gradient-primary @endif"
                       href="{{ route('superadmin.order.status.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                Order Status
                            @else
                                Order Status
                            @endif
                    </span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->type == 'superadmin')
                <li class="nav-item">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'superpopupimg') active bg-gradient-primary @endif @endif "
                       href="{{ URL::to('/') }}/superadmin/popupimage">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">Popup Image</span>
                    </a>
                </li>
            @endif
            @if ((Auth::user()->type == "superadmin" || Auth::user()->type == "superstaff"))
            @else
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            ওয়েবসাইট ডিজাইন
                        @else
                            Website Design
                        @endif
                    </h6>
                </li>
            @endif
            @if ((isset($exp) && $exp != '1') || paidTrial())
                @if (canAccess('slider') || canAccess('banner') || canAccess('layouts') || canAccess('template') || canAccess('header') || canAccess('homepage') || canAccess('footer') || canAccess('mobilemenu') || canAccess('product_display') || canAccess('product_grid') || canAccess('shop_page') || canAccess('testimonials'))
                    <li class="nav-item" id="designtour">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'design') active bg-gradient-primary @endif @endif"
                           href=" @if (isset($exp) && $exp == '1' && !paidTrial()) # @else {{ URL::to('/') }}/design/theme @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img
                                    src="https://img.icons8.com/ios-filled/20/ffffff/windows10-personalization.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ডিজাইন
                                @else
                                    Design
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif

            @if ((Auth::user()->type == "superadmin" || Auth::user()->type == "superstaff"))

            @else
                @if ((canAccess('modulus') || canAccess('activity_log')) && !paidTrial())
                    <li class="nav-item">
                        <a class="nav-link text-white @if(request()->routeIs('admin.modulus')) active bg-gradient-primary @endif"
                           href=" {{ route('admin.modulus') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img
                                    src="https://img.icons8.com/ios-filled/20/ffffff/camera-addon-identification.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    অ্যাডন
                                @else
                                    Addons
                                @endif
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white @if(request()->routeIs('admin.marketing.modulus')) active bg-gradient-primary @endif"
                           href="{{ route('admin.marketing.modulus') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img
                                    src="https://img.icons8.com/ios-filled/20/ffffff/camera-addon-identification.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    মার্কেটিং
                                @else
                                    Marketing
                                @endif
                        </span>
                        </a>
                    </li>
                @endif

            @endif

            @if (isset($digitalplan))
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            সোশ্যাল মিডিয়া মার্কেটিং
                        @else
                            Social Media Marketing
                        @endif
                    </h6>
                </li>

                <li class="nav-item" id="designtour">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'digital') active bg-gradient-primary @endif @endif"
                       href=" @if (isset($dexp)) @if ($dexp == '1') # @else {{ URL::to('/') }}/digital_marketing @endif @endif">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img
                                src="https://img.icons8.com/ios-filled/20/ffffff/windows10-personalization.png"/>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                সোশ্যাল মিডিয়া মার্কেটিং
                            @else
                                Social Media Marketing
                            @endif
                            </span>
                    </a>
                </li>
            @endif
            {{--            @if ((canAccess('blog') && ModulusStatus($store_id, 116)) || canSuperStaffAccess("blog"))--}}
            {{--                <li class="nav-item">--}}
            {{--                    <a class="nav-link text-white @if (url()->current() == route('superadmin.blog.index') ||--}}
            {{--                                    url()->current() == route('superadmin.blog.create') ||--}}
            {{--                                    url()->current() == route('superadmin.blog.type.index')) active bg-gradient-primary @endif"--}}
            {{--                       href="{{ route('superadmin.blog.index') }}">--}}
            {{--                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">--}}
            {{--                            <i class="material-icons opacity-10">table_view</i>--}}
            {{--                        </div>--}}
            {{--                        <span class="nav-link-text ms-1">Blogs</span>--}}
            {{--                    </a>--}}
            {{--                </li>--}}
            {{--            @endif--}}

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                        অ্যাকাউন্ট
                    @else
                        Account
                    @endif
                </h6>
            </li>

            @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper' || (Auth::user()->type == 'superstaff' && !is_null(Auth::user()->store_id)))
                <li class="nav-item" id="paymenttour">
                    <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'payment') active bg-gradient-primary @endif @endif"
                       href="{{ route('payment.packages') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img src="https://img.icons8.com/ios-filled/20/ffffff/card-security.png"/>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                পেমেন্ট
                            @else
                                Payment
                            @endif
                            </span>
                    </a>
                </li>
            @endif

            @if(
             merchantPaymentModulusStatus($store_id, 125, "amarpay") ||
             merchantPaymentModulusStatus($store_id, 128, "bkash") ||
             merchantPaymentModulusStatus($store_id, 129, "nagad") ||
             merchantPaymentModulusStatus($store_id, 130, "rocket")
             )
                @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (request()->routeIs('admin.payment.order.list')) active bg-gradient-primary @endif"
                           href="{{ route('admin.payment.order.list') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img src="https://img.icons8.com/ios-filled/20/ffffff/card-security.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    পেমেন্ট বিবরণ
                                @else
                                    Payment History
                                @endif
                            </span>
                        </a>
                    </li>
                @endif
            @endif

            @if (Auth::user()->type == 'superadmin')
                <li class="nav-item">
                    <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'promotion') active bg-gradient-primary @endif @endif"
                       href="{{ route('superadmin.promotion.coupon') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                কুপন
                            @else
                                Coupon
                            @endif
                        </span>
                    </a>
                </li>
            @endif

            @if (isset($exp) && $exp != '1')
                @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'affiliateMarketing') active bg-gradient-primary @endif @endif"
                           href="{{ route('admin.affiliateMarketing') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    অ্যাফিলিয়েট মার্কেটিং
                                @else
                                    Affiliate Marketing
                                @endif
                        </span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->type == 'admin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (request()->routeIs('dropshipper.dropship.commission')) active bg-gradient-primary @endif"
                           href="{{ route('dropshipper.dropship.commission') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Sell Commission</span>
                        </a>
                    </li>
                @endif
            @endif

            @if (isset($exp) && $exp != '1' && ModulusStatus($store_id, 120))
                @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'product_affiliate_users') active bg-gradient-primary @endif @endif"
                           href="{{ route('admin.product_affiliate.user.get') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    পণ্য অ্যাফিলিয়েট মার্কেটিং
                                @else
                                    Product Affiliate Marketing
                                @endif
                        </span>
                        </a>
                    </li>
                @endif
            @endif

            @if (isset($exp) && $exp != '1')
                @if(ModulusStatus($store_id, 123))
                    @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper' || Auth::user()->type == 'staff')
                        <li class="nav-item">
                            <a class="nav-link text-white  @if (request()->routeIs('courier.index') || request()->routeIs('courier.courierPage')) active bg-gradient-primary @endif"
                               href="{{ route('courier.index') }}">
                                <div
                                    class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">table_view</i>
                                </div>
                                <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        কুরিয়ার
                                    @else
                                        Courier
                                    @endif
                        </span>
                            </a>
                        </li>
                    @endif
                @endif
            @endif

            @if ((isset($exp) && $exp != '1') || isset($posplan) || paidTrial())
                @if (canAccess('reports'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'report') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp) && $exp == '1' && !paidTrial()) # @else {{ route('admin.report') }} @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img
                                    src="https://img.icons8.com/external-smashingstocks-glyph-smashing-stocks/20/ffffff/external-report-testimonials-and-feedback-smashingstocks-glyph-smashing-stocks.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    রিপোর্ট
                                @else
                                    Reports
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif

            @if ((isset($exp) && $exp != '1') || isset($posplan))
                @if ((isset($customer) && $customer == '1') || Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'customer') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.customer') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img src="https://img.icons8.com/ios-filled/20/ffffff/customer-insight.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    কাস্টমার
                                @else
                                    Customers
                                @endif
                                </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'news_latter') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.customer.news_latter') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img src="https://img.icons8.com/ios-filled/20/ffffff/customer-insight.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    কাস্টমার
                                @else
                                    News Latter
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
            @endif

            @if ((isset($exp) && $exp != '1') || isset($posplan))
                @if (canAccess('staff'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'staff') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.staff') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <img src="https://img.icons8.com/ios-filled/20/ffffff/employee-card.png"/>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    স্টাফ
                                @else
                                    Employee
                                @endif
                                </span>
                        </a>
                    </li>

                    @if ($store->plan_id != 6 || $store->trail != 0)
                        <li class="nav-item">
                            <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'email') active bg-gradient-primary @endif @endif"
                               href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.emaillist') }} @endif @endif">
                                <div
                                    class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <img src="https://img.icons8.com/ios-filled/20/ffffff/employee-card.png"/>
                                </div>
                                <span class="nav-link-text ms-1">Email</span>
                            </a>
                        </li>
                    @endif
                @endif
            @endif

            @if ((isset($exp) && $exp != '1') && Auth::user()->type == 'dropshipper')
                <li class="nav-item">
                    <a class="nav-link text-white  @if (request()->routeIs('dropshipper.dropship.commission')) active bg-gradient-primary @endif"
                       href="{{ route('dropshipper.dropship.commission') }}">
                        <div
                            class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">Dropship Commission</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')
                @if (Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (request()->routeIs('superadmin.dropshipper') || request()->routeIs('superadmin.overflow.list') || request()->routeIs('superadmin.dropship.order.details')) active bg-gradient-primary @endif"
                           href="{{ route('superadmin.dropshipper') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Drop Shipper</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (request()->routeIs('superadmin.sell.commission') || request()->routeIs('superadmin.sell.commission.overflow.list') || request()->routeIs('superadmin.sell.order.details')) active bg-gradient-primary @endif"
                           href="{{ route('superadmin.sell.commission') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Sell Commission</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'productrecycle') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.productrecycle') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    রিসাইকেল বিন
                                @else
                                    Recycle Bin
                                @endif
                        </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'addonsss') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.mobilapps') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    অ্যাডঅনস
                                @else
                                    Addons
                                @endif
                            </span>
                        </a>
                    </li>
                @endif

                @if(canSuperStaffAccess("affiliate"))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'affiliateMarketing') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.affiliateMarketing') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    অ্যাফিলিয়েট মার্কেটিং
                                @else
                                    Affiliate Marketing
                                @endif
                    </span>
                        </a>
                    </li>
                @endif

                @if (canSuperStaffAccess('smm'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'digitalmarketing') active bg-gradient-primary @endif @endif"
                           href="{{ route('superadmin.digitalmarketing') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    সোশ্যাল মিডিয়া মার্কেটিং
                                @else
                                    Social Media Marketing
                                @endif
                                </span>
                        </a>
                    </li>
                @endif

                @if (canSuperStaffAccess('webSetup'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'wsetup') active bg-gradient-primary @endif @endif"
                           href="{{ route('staff.webSetUp') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Web Site Setup
                                @else
                                    Web Site Setup
                                @endif
                                </span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->type == 'superstaff')
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (request()->routeIs('superstaff.commission')) active bg-gradient-primary @endif"
                           href="{{ route('superstaff.commission') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Commission
                                @else
                                    Commission
                                @endif
                                </span>
                        </a>
                    </li>
                @endif

                @if (canSuperStaffAccess('plan_order'))
                    <li class="nav-item">
                        <a class="nav-link text-white  @if (isset($urls)) @if ($urls == 'planorder') active bg-gradient-primary @endif @endif"
                           href="{{ route('admin.planorder') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Plan Order</span>
                        </a>
                    </li>
                @endif

                @if (canSuperStaffAccess('plans'))
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'plans') active bg-gradient-primary @endif @endif"
                           href="{{ route('plans') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Plans</span>
                        </a>
                    </li>
                @endif

                @if (canSuperStaffAccess('notification'))
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'notification') active bg-gradient-primary @endif @endif"
                           href="{{ route('notification') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Notification</span>
                        </a>
                    </li>
                @endif
            @endif

            @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')

                @if (Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (request()->routeIs('cpanel.zone.record')) active bg-gradient-primary @endif"
                           href="{{ route('cpanel.zone.record') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Cpanel Zone Record</span>
                        </a>
                    </li>
                @endif


                @if (Auth::user()->type == 'superadmin')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'gsc-fb-pixel') active bg-gradient-primary @endif @endif"
                           href="{{ route('gscFbPixel') }}">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">GSC & FB Pixel</span>
                        </a>
                    </li>
                @endif
            @endif

            @if (Auth::user()->type == 'superadmin')
                <li class="nav-item">
                    <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'filecontrol') active bg-gradient-primary @endif @endif"
                       href="{{ route('filecontrol') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <span class="nav-link-text ms-1">File Control</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                @php
                    $app = DB::table('mobileapps')
                        ->where('store_id', $store_id)
                        ->where('expiry_date', '>=', Carbon\Carbon::now())
                        ->first();
                @endphp
                @isset($app)
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'notification') active bg-gradient-primary @endif @endif"
                           href="{{ route('admin.notification') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Apps Notification</span>
                        </a>
                    </li>
                @endisset

                <li class="nav-item" id="settingtour">
                    <a class="nav-link text-white @if(request()->routeIs('admin.setting')) active bg-gradient-primary @endif"
                       href="{{ route('admin.setting') }} ">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <img
                                src="https://img.icons8.com/external-kiranshastry-solid-kiranshastry/20/ffffff/external-settings-coding-kiranshastry-solid-kiranshastry-1.png"/>
                        </div>
                        <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                সেটিংস
                            @else
                                Settings
                            @endif
                            </span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->type == 'staff')
                @if (isset($exp) && $exp != '1')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (isset($urls)) @if ($urls == 'staff.profile') active bg-gradient-primary @endif @endif"
                           href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.staff.profile') }} @endif @endif">
                            <div
                                class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Profile</span>
                        </a>
                    </li>
                @endif
            @endif


            @if (canSuperStaffAccess('setting'))
                <li class="nav-item">
                    <a class="nav-link text-white @if (request()->routeIs('super_admin.settings.index') || request()->routeIs('super_admin.settings.currency_list') || request()->routeIs('super_admin.settings.store.static.data')) active bg-gradient-primary @endif"
                       href="{{ route('super_admin.settings.index') }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">settings</i>
                        </div>
                        <span class="nav-link-text ms-1">Setting</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link text-white " href="#"
                   onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img
                            src="https://img.icons8.com/external-sbts2018-solid-sbts2018/20/ffffff/external-logout-social-media-sbts2018-solid-sbts2018.png"/>
                    </div>
                    <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                            লগ আউট
                        @else
                            Logout
                        @endif
                        </span>
                </a>
            </li>
            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </ul>
    </div>
</aside>
