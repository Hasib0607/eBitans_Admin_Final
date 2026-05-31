<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    @if(canAccess('template'))
                        <li class="breadcrumb-item @isset($theme) active @endisset">
                            <a href="{{route('admin.design.theme')}}">
                                <img src="{{URL::to('/')}}/img/icons/web-design.png"> <br><span
                                    class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        ওয়েবসাইট থিম
                                    @else
                                        Website Themes
                                    @endif</span>
                            </a>
                        </li>
                    @endif

                    @if(canAccess('homepage'))
                        <li class="breadcrumb-item @isset($home_page) active @endisset" aria-current="page">
                            <a href="{{route('admin.design.homepage.common_designs', ['column'=>'header']) }}">
                                <img src="{{URL::to('/')}}/img/icons/landing-page.png"> <br><span
                                    class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        হোম পেজ ডিজাইন
                                    @else
                                        HP Layout Design
                                    @endif</span>
                            </a>
                        </li>
                    @endif

                    @if(canAccess('testimonials'))
                        <li class="breadcrumb-item @isset($testimonial) active @endisset" aria-current="page">
                            <a href="{{route('admin.testimonials')}}">
                                <img src="{{URL::to('/')}}/img/icons/testimonial.png"> <br><span
                                    class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        প্রশংসাপত্র
                                    @else
                                        Testimonials
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(canAccess('pages'))
                        <li class="breadcrumb-item @isset($page) active @endisset" aria-current="page">
                            <a href="{{route('admin.pages')}}">
                                <img src="{{URL::to('/')}}/img/icons/team.png"> <br><span
                                    class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        অন্যান্য পেইজ
                                    @else
                                        Other Pages
                                    @endif</span>
                            </a>
                        </li>
                    @endif
                    @if(canAccess())
                        <li class="breadcrumb-item @isset($invoice) active @endisset" aria-current="page">
                            <a href="{{route('admin.design.homepage.invoice')}}">
                                <img src="{{URL::to('/')}}/img/icons/bill-2.png"> <br>
                                <span
                                    class="nav-link-text ms-1">
                                            @if(Session::has('lang') && Session::get('lang')=='bn')
                                        চালান টেমপ্লেট
                                    @else
                                        Invoice Template
                                    @endif
                                        </span>
                            </a>
                        </li>
                    @endif

                </ol>
            </nav>
        </div>
    </div>
</div>
