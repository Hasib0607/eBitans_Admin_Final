@php
    $current_page = explode('/',Request::path())[1];
@endphp
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if(canAccess('coupon'))
                        <li class="breadcrumb-item @if($current_page == 'coupon') active @endif">
                            <a href="{{route('admin.promotion.coupon')}}">
                                <img src="{{URL::to('/')}}/img/icons/voucher.png"> <br> <span
                                    class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        কুপন
                                    @else
                                        Coupon
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(canAccess('campaign'))
                        <li class="breadcrumb-item @if($current_page == 'campaign') active @endif" aria-current="page">
                            <a href="{{route('admin.promotion.campaign')}}">
                                <img src="{{URL::to('/')}}/img/icons/bullhorn.png"> <br><span
                                    class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        প্রচারণা
                                    @else
                                        Campaign
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(canAccess('offer'))
                        <li class="breadcrumb-item @if($current_page == 'offer') active @endif" aria-current="page">
                            <a href="{{route('admin.promotion.offer')}}">
                                <img src="{{URL::to('/')}}/img/icons/offer.png"> <br><span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        অফার
                                    @else
                                        Offer
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
