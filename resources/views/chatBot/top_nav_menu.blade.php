@include('superadmin.share.ss-bot-top-nav')

<div class="container-fluid navbars mt-3"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                    <li class="breadcrumb-item @if (request()->routeIs('chatBot.index') || request()->routeIs('chatBot.questions.create') || request()->routeIs('chatBot.questions.edit')) active @endif"
                        aria-current="page">
                        <a href="{{ route('chatBot.index') }}">
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
                    <li class="breadcrumb-item @if (request()->routeIs('chatBot.unansweredQuestions.list') || request()->routeIs('chatBot.unansweredQuestions.create')) active @endif"
                        aria-current="page">
                        <a href="{{ route('chatBot.unansweredQuestions.list') }}">
                            <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                            <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    New Questions
                                @else
                                    New Questions
                                @endif
                                    </span>
                        </a>
                    </li>
                    <li class="breadcrumb-item @if (request()->routeIs('chatBot.support.analytics')) active @endif"
                        aria-current="page">
                        <a href="{{ route('chatBot.support.analytics') }}">
                            <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                            <span class="nav-link-text ms-1">
                                Support Analytics
                            </span>
                        </a>
                    </li>

                    @if(canSuperStaffAccess('chat_assign'))
                        <li class="breadcrumb-item @if (request()->routeIs('chatBot.botConversation.list') || request()->routeIs('chatBot.botConversation.assign')) active @endif"
                            aria-current="page">
                            <a href="{{ route('chatBot.botConversation.list') }}">
                                <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                                <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Bot Conversation
                                    @else
                                        Bot Conversation
                                    @endif
                                    </span>
                            </a>
                        </li>
                        <li class="breadcrumb-item @if (request()->routeIs('chatBot.agentConversation.list')) active @endif"
                            aria-current="page">
                            <a href="{{ route('chatBot.agentConversation.list') }}">
                                <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br>
                                <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Agent Conversation
                                    @else
                                        Agent Conversation
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
