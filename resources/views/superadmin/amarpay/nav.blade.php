<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if (canSuperStaffAccess('amarpay_kyc'))
                        <li class="breadcrumb-item @if(request()->routeIs('superadmin.amaypay.kyc')) active @endif">
                            <a href="{{ route('superadmin.amaypay.kyc') }}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br>Merchant KYC List
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('amarpay_client_list'))
                        <li class="breadcrumb-item @if(request()->routeIs('superadmin.amaypay.client.list')) active @endif">
                            <a href="{{ route('superadmin.amaypay.client.list') }}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br>Merchant List
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('amarpay_order_list'))
                        <li class="breadcrumb-item @if(request()->routeIs('superadmin.amaypay.payment.list') || request()->routeIs('superadmin.amaypay.payment.list')) active @endif">
                            <a href="{{ route('superadmin.amaypay.payment.list') }}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br>Merchant Payment List
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('merchant_payment_withdraw_list'))
                        <li class="breadcrumb-item @if(request()->routeIs('superadmin.amaypay.withdraw.request.list')) active @endif">
                            <a href="{{ route('superadmin.amaypay.withdraw.request.list') }}">
                                <img src="{{URL::to('/')}}/img/cubes.png"> <br>Merchant Payment Withdraw List
                            </a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
