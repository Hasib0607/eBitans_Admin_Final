<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if (canSuperStaffAccess('message'))
                        <li class="breadcrumb-item @if (request()->routeIs('messages') || request()->routeIs('seemessages') || request()->routeIs('chat.index')) active @endif"
                            aria-current="page">
                            <a href="{{ route('messages') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br>
                                <span class="nav-link-text ms-1">Messages</span>
                            </a>
                        </li>
                    @endif

                    @if (canSuperStaffAccess('chatbot'))
                        <li class="breadcrumb-item @if (request()->routeIs('chatBot.*')) active @endif"
                            aria-current="page">
                            <a href="{{ route('chatBot.index') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br>
                                <span class="nav-link-text ms-1">Chat Bot</span>
                            </a>
                        </li>
                    @endif

                    @if (canSuperStaffAccess('whatsapp'))
                        <li class="breadcrumb-item @if (request()->routeIs('superadmin.whatsapp.launch')) active @endif"
                            aria-current="page">
                            <a href="{{ route('superadmin.whatsapp.launch') }}">
                                <img src="{{ URL::to('/') }}/img/cubes.png"> <br>
                                <span class="nav-link-text ms-1">WhatsApp</span>
                            </a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
