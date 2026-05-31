<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header sticky" style="z-index:9999999999999">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
           aria-hidden="true" id="iconSidenav"></i>

        <a class="navbar-brand m-0" href="{{ route('affiliate.index') }}">
            <img src="{{ asset('logo-white.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        </a>
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
            @if (Auth::user()->type == 'affiliate')
                <li class="nav-item">
                    <a class="nav-link text-white @if (request()->routeIs('affiliate.index')) active bg-gradient-primary @endif"
                       href="{{ route('affiliate.index') }}">
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

                    <?php
                    $affiliateExamInfo = \App\Models\AffiliateExamInfo::where('user_id', '=', Auth::id())->first();
                    ?>

                @if($affiliateExamInfo && $affiliateExamInfo->user_status == 'Approved')
                    <li class="nav-item">
                        <a class="nav-link text-white @if (request()->routeIs('affiliate.affiliateMarketing')) active bg-gradient-primary @endif"
                           href="{{ route('affiliate.affiliateMarketing') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Affiliate Marketing </span>
                        </a>
                    </li>
                @endif

                <li class="nav-item" id="settingtour">
                    <a class="nav-link text-white @if (request()->routeIs('affiliate.profile')) active bg-gradient-primary @endif"
                       href="{{ route('affiliate.profile') }} ">
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
