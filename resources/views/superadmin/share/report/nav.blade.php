<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if (canSuperStaffAccess('report_dashboard'))
                        <li class="breadcrumb-item @if(url()->current() == route('superadmin.report')) active @endif">
                            <a href="{{ route('superadmin.report') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Report
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('paid_clients_list'))
                        <li class="breadcrumb-item @if(url()->current() == route('paidClientsList')) active @endif">
                            <a href="{{ route('paidClientsList') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Paid Clients List
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('addon_sell_report') && (Auth::user()->phone != "01677515573"))
                        <li class="breadcrumb-item @if(url()->current() == route('addonSellReport')) active @endif">
                            <a href="{{ route('addonSellReport') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Sell Report
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('sms_log'))
                        <li class="breadcrumb-item @if(url()->current() == route('superadmin.store.sms.list')) active @endif">
                            <a href="{{ route('superadmin.store.sms.list') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> SMS Log
                            </a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
