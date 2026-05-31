<div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if (url()->current() == route('admin.announcement.index', ['id' => request()->route('id')])) active @endif"
                        aria-current="page">
                        <a href="{{ route('admin.announcement.index') }}">
                            <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                            <span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    All Announcement
                                @else
                                    All Announcement
                                @endif
                            </span>
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
