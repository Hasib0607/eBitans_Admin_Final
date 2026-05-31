<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if (request()->routeIs('affiliate.questions.index') || request()->routeIs('affiliate.questions.create') || request()->routeIs('affiliate.questions.edit')) active @endif"
                        aria-current="page">
                        <a href="{{ route('affiliate.questions.index') }}">
                            <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                            <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    All Questions
                                @else
                                    All Questions
                                @endif
                                    </span>
                        </a>
                    </li>

                    <li class="breadcrumb-item @if (request()->routeIs('affiliate.questions.answers.index') || request()->routeIs('affiliate.questions.answers.show')) active @endif"
                        aria-current="page">
                        <a href="{{ route('affiliate.questions.answers.index') }}">
                            <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                            <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Answers
                                @else
                                    Answers
                                @endif
                                    </span>
                        </a>
                    </li>

                    @if (Auth::user()->type == 'superadmin')
                        <li class="breadcrumb-item @if (request()->routeIs('affiliate.payment.lists') || request()->routeIs('affiliate.payment.approved') || request()->routeIs('affiliate.payment.rejected')) active @endif"
                            aria-current="page">
                            <a href="{{ route('affiliate.payment.lists') }}">
                                <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                                <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Payment Lists
                                    @else
                                        Payment Lists
                                    @endif
                                    </span>
                            </a>
                        </li>

                        <li class="breadcrumb-item @if (request()->routeIs('affiliate.withdraw.pending') || request()->routeIs('affiliate.withdraw.status.approved')|| request()->routeIs('affiliate.withdraw.status.rejected')) active @endif"
                            aria-current="page">
                            <a href="{{ route('affiliate.withdraw.pending') }}">
                                <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                                <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Withdrawal
                                    @else
                                        Withdrawal
                                    @endif
                                    </span>
                            </a>
                        </li>

                    @endif

                    <li class="breadcrumb-item @if (request()->routeIs('affiliate.faq.question.list') || request()->routeIs('affiliate.faq.question.create')|| request()->routeIs('affiliate.faq.question.edit')) active @endif"
                        aria-current="page">
                        <a href="{{ route('affiliate.faq.question.list') }}">
                            <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                            <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    FAQ
                                @else
                                    FAQ
                                @endif
                                    </span>
                        </a>
                    </li>


                </ol>
            </nav>
        </div>
    </div>
</div>
