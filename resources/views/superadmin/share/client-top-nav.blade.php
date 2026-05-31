@php
    $current_page = explode('/',Request::path())[0];
@endphp
<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">

                    @if (canSuperStaffAccess('clients'))
                        <li class="breadcrumb-item  @if($current_page == "clients") active @endif">
                            <a href="{{ URL::to('/') }}/clients">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Clients
                            </a>
                        </li>
                    @endif

                    @if (canSuperStaffAccess('paid_clients'))
                        <li class="breadcrumb-item @if($current_page == "paid-clients") active @endif">
                            <a href="{{ route('admin.paidClients') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Paid Clients
                            </a>
                        </li>
                    @endif

                    @if (canSuperStaffAccess('clients_Activities'))

                        <li class="breadcrumb-item @if($current_page == "clients-activities") active @endif">
                            <a href="{{ route('admin.clients.activities') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Clients Activities
                            </a>
                        </li>
                    @endif


                    @if (canSuperStaffAccess('clients_Follow_Up'))
                        <li class="breadcrumb-item @if($current_page == "clients-follow-up") active @endif">
                            <a href="{{ route('admin.clients.followUp') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Clients Follow-up
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('register_client'))
                        <li class="breadcrumb-item @if($current_page == "register-clients") active @endif">
                            <a href="{{ route('admin.registerClients') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Register Clients
                            </a>
                        </li>
                    @endif
                    @if (canSuperStaffAccess('landing_page_clients'))
                        <li class="breadcrumb-item @if(request()->routeIs('landingPageClientsList')) active @endif">
                            <a href="{{ route('landingPageClientsList', ['from_date' => date('Y-m-d')]) }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Landing Page Clients
                            </a>
                        </li>
                    @endif
                    {{--                    <li class="breadcrumb-item active">--}}
                    {{--                        <h1 style="color:#fff;">{{ $last30Days??00 }}</h1>--}}
                    {{--                    </li>--}}

                </ol>
            </nav>
        </div>
    </div>
</div>
