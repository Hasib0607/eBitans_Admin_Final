@php
    $current = explode('/',Request::path())[2];

    $userData = getUserData();
    $user_type = $userData['user_type'];
    $store = $userData['store'];
    $store_id = $userData['store_id'] ?? "";
    $planOrders = DB::table('addons_orders')
        ->where('store_id', $store_id)
        ->where('status', "Processing")
        ->orderBy('id', 'DESC')
        ->first();
@endphp

@if(isset($user_type) && $user_type == "superstaff")
    <div class="container-fluid"
         style="display:flex;justify-content:center;align-items:center;flex-direction: column; gap: 10px; margin-bottom: 20px;">
        <div class="fixed-popup"
             style="margin: 0 auto;text-align: center;position: relative;background-color: red;border-radius:10px;">
            <p style="color: #fff;font-size: 18px; padding: 7px 17px;line-height: 20px; margin-bottom: 0;">
                You are now at {{ $store->name ?? "" }} Store
            </p>
        </div>
    </div>
@endif

<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if ($current == 'packages') active @endif">
                        <a href="{{ route('payment.packages') }}">
                            <img src="{{ URL::to('/') }}/img/icons/inventory-2.png"> <br> <span
                                class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    প্যাকেজ
                                @else
                                    Packages
                                @endif
                                    </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if ($current == 'addons') active @endif" aria-current="page">
                        <a href="{{ route('payment.addons') }}">
                            <img src="{{ URL::to('/') }}/img/icons/new-product.png"> <br><span
                                class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    অ্যাডঅনস
                                @else
                                    Addons
                                @endif
                                    </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if ($current == 'payments') active @endif" aria-current="page">
                        <a href="{{ route('payment.payments') }}">
                            <img src="{{ URL::to('/') }}/img/icons/out-of-stock.png"> <br><span
                                class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    পেমেন্ট ইতিহাস
                                @else
                                    Payments History
                                @endif
                                    </span>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if(isset($planOrders))
    <div class="container-fluid"
         style="display:flex;justify-content:center;align-items:center; margin-top: 10px">
        <div class="fixed-popup bg-warning"
             style="margin: 0 auto;text-align: center;position: relative;border-radius:10px;">
            <p style="color: #fff;font-size: 17px; padding: 7px 100px;line-height: 20px; margin-bottom: 0;">
                Your payment is under processing.</p>
        </div>
    </div>
@endif
