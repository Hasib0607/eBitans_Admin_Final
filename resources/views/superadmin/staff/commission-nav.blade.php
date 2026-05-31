<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">

                    <li class="breadcrumb-item @if (request()->routeIs('superstaff.commission')) active @endif">
                        <a href="{{ route('superstaff.commission') }}">
                            <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Commission
                        </a>
                    </li>
                    <li class="breadcrumb-item @if (request()->routeIs('superstaff.commission.payment.history')) active @endif">
                        <a href="{{ route('superstaff.commission.payment.history') }}">
                            <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Payment History
                        </a>
                    </li>

                    {{--                    @if (Auth::user()->type == 'superstaff')--}}
                    {{--                        <li class="breadcrumb-item @if (request()->routeIs('superstaff.commission')) active @endif">--}}
                    {{--                            <a href="{{ route('superstaff.commission') }}">--}}
                    {{--                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Commission--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                    @endif--}}

                </ol>
            </nav>
        </div>
    </div>
</div>
