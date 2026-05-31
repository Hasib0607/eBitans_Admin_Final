@php
    $current_page = explode('/',Request::path())[0];
@endphp
<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if($current_page == "plans") active @endif">
                        <a href="{{route('plans')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Website Plans
                        </a>
                    </li>
                    <li class="breadcrumb-item @if($current_page == "posplans") active @endif">
                        <a href="{{route('posplans')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Pos Plans
                        </a>
                    </li>
                    <li class="breadcrumb-item @if($current_page == "digitalplans") active @endif">
                        <a href="{{route('digitalplans')}}">
                            <img src="{{URL::to('/')}}/img/cubes.png"> <br> Digital Plans
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
