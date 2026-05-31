@php
    $current_page = explode('/',Request::path())[1];
@endphp
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if($current_page == "product-affiliate-users") active @endif">
                        <a href="{{route('admin.product_affiliate.user.get')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Affiliate Users
                        </a>
                    </li>
                    <li class="breadcrumb-item @if($current_page == "withdraw-requests") active @endif">
                        <a href="{{route('admin.product_affiliate.withdraw_requests')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Withdraw Requests
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
