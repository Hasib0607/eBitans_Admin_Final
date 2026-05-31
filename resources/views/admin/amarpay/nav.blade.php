@php
    $userData = getUserData();
    $store_id = $userData['store_id'];
@endphp

@if(
merchantPaymentModulusStatus($store_id, 125, "amarpay") ||
merchantPaymentModulusStatus($store_id, 128, "bkash") ||
merchantPaymentModulusStatus($store_id, 129, "nagad") ||
merchantPaymentModulusStatus($store_id, 130, "rocket")
)
    <div class="container-fluid navbars"
         style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
        <div class="row">
            <div class="col-md-12">
                <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                        @if (canAccess('order_list'))
                            <li class="breadcrumb-item @if(request()->routeIs('admin.payment.order.list')) active @endif">
                                <a href="{{ route('admin.payment.order.list') }}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br>Payment List
                                </a>
                            </li>
                        @endif
                        @if (canAccess('merchant_payment_withdraw'))
                            <li class="breadcrumb-item @if(request()->routeIs('admin.payment.withdraw.list')) active @endif">
                                <a href="{{ route('admin.payment.withdraw.list') }}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br>Payment Withdraw
                                </a>
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endif
