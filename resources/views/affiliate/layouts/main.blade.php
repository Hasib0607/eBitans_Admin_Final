@include('affiliate.layouts.header')

@php
    _getUserUsingInfo(auth()->user());

    $authUser = Auth::user();

@endphp

<div class="preloader">
    <div class="frame12">
        <div class="center">
            <div class="dot-1"></div>
            <div class="dot-2"></div>
            <div class="dot-3"></div>
        </div>
    </div>
</div>

<div class="imageUploadLoading" id="imageUploadLoading">
    <div class="cssload-wrap">
        <div class="cssload-container">
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
            <span class="cssload-dots"></span>
        </div>
    </div>
</div>

@include('affiliate.layouts.left_menu')


<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" id="main"
      style="min-height: 100vh !important">

    @if(!route('affiliate.index'))
        @include('affiliate.layouts.nav_bar')
    @endif

    {!! Toastr::message() !!}
    @yield('content')

    <!-----End Loader---->
    @include('affiliate.layouts.footer')
    </div>
</main>

<div class="fixed-plugin" id="fixedplugin">
    @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper' || Auth::user()->type == 'staff' || Auth::user()->type == 'affiliate')
        <style>
            a.fixed-plugin-button1.text-dark.position-fixed.px-3 {
                width: 48px;
                height: 48px;
            }
        </style>

        <a class="fixed-plugin-button1 text-dark position-fixed px-3"
           style="padding-top:10px;padding-bottom:10px;right:90px;background-color:#f1593a !important;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white"
                 style="width: 30px !important; height: 30px !important; margin-left: -6px;" fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
        </a>
        <a class="fixed-plugin-button3 text-dark position-fixed px-3 py-2"
           style="right:90px;background-color:#f1593a !important;">
            <i class="fa fa-times" aria-hidden="true" style='font-size:20px;color:#fff' class="py-2"></i>
        </a>
    @endif
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
        <i class="material-icons py-2">construction</i>
    </a>
    <div class="card shadow-lg">
        <div class="card-header pb-0 pt-3">
            <div class="float-start">
                <h5 class="mt-3 mb-0">eBitans</h5>
                <p>See our dashboard options.</p>
            </div>
            <div class="float-end mt-4">
                <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <!-- End Toggle Button -->
        </div>
        <hr class="horizontal dark my-1">
        <div class="card-body pt-sm-3 pt-0">
            <!-- Sidebar Backgrounds -->
            <div>
                <h6 class="mb-0">Sidebar Colors</h6>
            </div>
            <a href="javascript:void(0)" class="switch-trigger background-color" style="display:none">
                <div class="badge-colors my-2 text-start">
                        <span class="badge filter bg-gradient-primary active" data-color="primary"
                              onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-dark" data-color="dark"
                          onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-info" data-color="info"
                          onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-success" data-color="success"
                          onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-warning" data-color="warning"
                          onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-danger" data-color="danger"
                          onclick="sidebarColor(this)"></span>
                </div>
            </a>
            <!-- Sidenav Type -->
            <div class="mt-3">
                <h6 class="mb-0">Sidenav Type</h6>
                <p class="text-sm">Choose between 2 different sidenav types.</p>
            </div>
            <div class="d-flex">
                <button class="btn bg-gradient-dark px-3 mb-2 active" data-class="bg-gradient-dark"
                        onclick="sidebarType(this)">Dark
                </button>
                <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white"
                        onclick="sidebarType(this)">White
                </button>
            </div>
            <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
            <!-- Navbar Fixed -->
            <hr class="horizontal dark my-3">
            <div class="mt-2 d-flex">
                <h6 class="mb-0">Light / Dark</h6>
                <div class="form-check form-switch ps-0 ms-auto my-auto">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version"
                           onclick="darkMode(this)">
                </div>
            </div>
            <hr class="horizontal dark my-sm-4">
            <div class="mt-2 d-flex">
                <h6 class="mb-0">Top Tools</h6>
            </div>
            <div class="d-flex mt-3" style="flex-wrap: wrap;justify-content: space-between;">
                @if (isset($use) && count($use) > 0)
                    @foreach ($use as $key => $uu)
                        @if ($key < 6)
                            <a class="btn bg-gradient-dark px-1 mb-2"
                               href="{{ URL::to('/') }}{{ $uu->url }}" style="width:48%"
                               data-class="bg-gradient-dark">{{ $uu->name }}</a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
</div>

@if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper' || Auth::user()->type == 'staff' || Auth::user()->type == 'affiliate')
    <!-- Chat system -->
    <div class="chatbox" style="z-index:99999">
        <style>
            #chat-popup {
                position: absolute;
                bottom: 0.5rem; /* 10px */
                right: 2.5rem; /* 10px */
                width: 24rem; /* 96px */
                background-color: white;
                border-radius: 0.375rem; /* 6px */
                box-shadow: 0 0.375rem 0.75rem rgba(0, 0, 0, 0.1); /* shadow-md */
                display: flex;
                flex-direction: column;
                transition: all 0.3s ease;
                font-size: 0.875rem; /* 14px */
            }

            #chat-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem; /* 4px */
                background-color: #f1593A; /* bg-gray-800 */
                color: white;
                border-top-left-radius: 0.375rem; /* 6px */
                border-top-right-radius: 0.375rem; /* 6px */
            }

            .chat-title {
                margin: 0;
                font-size: 1.125rem; /* text-lg */
                color: #fff;
            }

            .close-popup {
                background: transparent;
                border: none;
                color: white;
                cursor: pointer;
            }

            .icon {
                height: 1.5rem; /* 6px */
                width: 1.5rem; /* 6px */
            }

            .chat-messages {
                flex: 1;
                padding: 1rem; /* 4px */
                overflow-y: auto;
            }

            .chat-input-container {
                padding: 1rem; /* 4px */
                border-top: 1px solid #e2e8f0; /* border-gray-200 */
            }

            .input-wrapper {
                display: flex;
                gap: 1rem; /* space-x-4 */
                align-items: center;
            }

            .chat-input {
                flex: 1;
                border: 1px solid #d1d5db; /* border-gray-300 */
                border-radius: 0.375rem; /* 6px */
                padding: 0.5rem 1rem; /* px-4 py-2 */
                outline: none;
                width: 75%; /* w-3/4 */
            }

            .chat-submit {
                background-color: #f1593A; /* bg-gray-800 */
                color: white;
                border-radius: 0.375rem; /* 6px */
                padding: 0.5rem 1rem; /* px-4 py-2 */
                cursor: pointer;
                border: 1px solid #ff603f;
            }


            #chat-widget-container {
                position: fixed;
                bottom: 20px;
                right: 20px;
                flex-direction: column;
            }

            #chat-popup {
                height: 70vh;
                max-height: 70vh;
                transition: all 0.3s;
                overflow: hidden;
            }

            @media (max-width: 768px) {
                #chat-popup {
                    position: fixed;
                    right: 30px;
                    bottom: 100px;
                    width: 55%;
                    height: 60vh;
                    max-height: 100%;
                    border-radius: 0;
                }

                .chat-title {
                    font-size: 14px;
                }
            }

            @media (max-width: 600px) {
                #chat-popup {
                    width: 65%;
                }

            }

            @media (max-width: 500px) {
                #chat-popup {
                    width: 80%;
                }

            }


            @media (max-width: 450px) {
                #chat-popup {
                    height: 50vh;
                }
            }

            @media (max-width: 350px) {
                #chat-popup {
                    width: 85%;
                    height: 40vh;
                }
            }


            /*--------------------
                Messages
                --------------------*/
            .messages {
                flex: 1 1 auto;
                color: rgba(255, 255, 255, 0.5);
                overflow: hidden;
                position: relative;
                width: 100%;
            }

            .messages .messages-content {
                position: absolute;
                top: 0;
                left: 0;
                height: 101%;
                width: 100%;
                padding: 0 5px;
            }

            .messages .message {
                clear: both;
                float: left;
                padding: 6px 10px 7px;
                border-radius: 10px 10px 10px 0;
                background: rgb(255 91 0);
                margin: 8px 0;
                font-size: 15px;
                color: #fff;
                line-height: 1.4;
                margin-left: 35px;
                position: relative;
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            }

            .messages .message .timestamp {
                position: absolute;
                bottom: -20px;
                font-size: 13px;
                color: #000;
            }

            .messages .message::before {
                content: "";
                position: absolute;
                bottom: -6px;
                border-top: 6px solid #ff5b00;
                left: 0;
                border-right: 7px solid transparent;
            }

            .messages .message .avatar {
                position: absolute;
                z-index: 1;
                bottom: -15px;
                left: -35px;
                border-radius: 30px;
                width: 30px;
                height: 30px;
                overflow: hidden;
                margin: 0;
                padding: 0;
                border: 2px solid rgba(255, 255, 255, 0.24);
                background: #000;
            }

            .messages .message .avatar img {
                width: 50%;
                height: auto;
            }

            .messages .message.message-personal {
                float: right;
                color: #fff;
                text-align: right;
                background: rgb(255 91 0);
                border-radius: 10px 10px 0 10px;
            }

            .messages .message.message-personal::before {
                left: auto;
                right: 0;
                border-right: none;
                border-left: 5px solid transparent;
                border-top: 4px solid #ff5b00;
                bottom: -4px;
            }

            .messages .message:last-child {
                margin-bottom: 30px;
            }

            .messages .message.new {
                transform: scale(0);
                transform-origin: 0 0;
                animation: bounce 500ms linear both;
            }

            .messages .message.loading::before {
                border: none;
                animation-delay: 0.15s;
            }

            .typing-dots {
                display: inline-block;
                position: relative;
                width: 60px;
                height: 5px;
                margin-top: 8px;
            }

            .typing-dots span {
                display: inline-block;
                position: absolute;
                width: 6px;
                height: 6px;
                background-color: #000; /* Dot color */
                border-radius: 50%;
                /*animation: ball 0.6s infinite alternate;*/
                animation: ball 0.6s ease-in-out infinite alternate;
                animation-delay: 0.3s;
            }

            .typing-dots span:nth-child(1) {
                left: 0;
                animation-delay: 0s;
            }

            .typing-dots span:nth-child(2) {
                left: 14px;
                animation-delay: 0.2s;
            }

            .typing-dots span:nth-child(3) {
                left: 28px;
                animation-delay: 0.4s;
            }

            @keyframes bounce {
                from {
                    transform: translateY(0);
                }
                to {
                    transform: translateY(-10px);
                }
            }


            /*--------------------
            Custom Scrollbar
            --------------------*/
            .mCSB_scrollTools {
                margin: 1px -3px 1px 0;
                opacity: 0;
            }

            .mCSB_inside > .mCSB_container {
                margin-right: 0px;
                padding: 0 10px;
            }

            .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
                background-color: rgba(0, 0, 0, 0.5) !important;
            }

            /*--------------------
            Bounce Animation
            --------------------*/
            @keyframes bounce {
                0% {
                    transform: matrix3d(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                4.7% {
                    transform: matrix3d(0.45, 0, 0, 0, 0, 0.45, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                9.41% {
                    transform: matrix3d(0.883, 0, 0, 0, 0, 0.883, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                14.11% {
                    transform: matrix3d(1.141, 0, 0, 0, 0, 1.141, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                18.72% {
                    transform: matrix3d(1.212, 0, 0, 0, 0, 1.212, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                24.32% {
                    transform: matrix3d(1.151, 0, 0, 0, 0, 1.151, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                29.93% {
                    transform: matrix3d(1.048, 0, 0, 0, 0, 1.048, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                35.54% {
                    transform: matrix3d(0.979, 0, 0, 0, 0, 0.979, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                41.04% {
                    transform: matrix3d(0.961, 0, 0, 0, 0, 0.961, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                52.15% {
                    transform: matrix3d(0.991, 0, 0, 0, 0, 0.991, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                63.26% {
                    transform: matrix3d(1.007, 0, 0, 0, 0, 1.007, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                85.49% {
                    transform: matrix3d(0.999, 0, 0, 0, 0, 0.999, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
                100% {
                    transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1);
                }
            }

            /*--------------------
            Ball Animation
            --------------------*/
            @keyframes ball {
                from {
                    transform: translateY(0) scaleY(0.8);
                }
                to {
                    transform: translateY(-10px);
                }
            }

            div#chat-popup {
                padding: 0;
            }

            button:focus:not(:focus-visible), .btn-primary:active, .btn-primary:focus, .btn-primary:hover, .btn-primary.active:focus {
                box-shadow: none !important;
                border: none !important;
                outline: 0 !important;
            }

            .btn-primary:not(:disabled):not(.disabled):active {
                color: #fff !important;
            }

            .btn-primary:active, .btn-primary:focus, .btn-primary:hover {
                background-color: #ff5733;
                font-size: 14px;
            }

        </style>

        @php
            $countryCode = getVisitorInfo()->countryCode ?? "";
        @endphp

        <div id="chat-popup" class="chat-popup container">
            <chat-popup :socketurl='@json(env("SOCKET_URL"))' :userid='@json(optional(Auth::user())->id ?? null)'
                        :countrycode='@json($countryCode)'/>
        </div>
    </div>
@endif

<!----Mobile Bottom Menu Start----->
<div class='frame'>
    <div class='bar'>
        <a href='{{ route('admin.menu') }}' class='els-wrap el-1'>
            <div class='icon' id="iconNavbarSidenav1" style="margin-left: 2px;">
                <img src="https://img.icons8.com/ios-glyphs/25/000000/menu--v1.png"/>
            </div>
            <p style="font-size:12px">Menu</p>
        </a>
        <a href='javascript:;' class='els-wrap el-2' id="mobilesearch1">
            <div class='icon'>
                <img src="https://img.icons8.com/ios/25/000000/search--v1.png"/>
            </div>
            <p style="font-size:12px">Search</p>
        </a>
        <a href='@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/products/create @endif @endif'
           class='els-wrap1' style="background-color: #f1593a;margin-bottom: 57px;margin-left:4px;">
            <div class='icon' style="margin-top: 5px;margin-left: 5px;height:2em">
                <img src="https://img.icons8.com/android/27/ffffff/plus.png"/>
            </div>
        </a>
        <a href='@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/order @endif @endif'
           class='els-wrap el-3'>
            <div class='icon' style="margin-left: 3px;">
                <img src="https://img.icons8.com/ios-glyphs/25/000000/shopping-basket-success.png"/>
            </div>
            <p style="font-size:12px">Order</p>
        </a>
        <a href='@if (isset($exp)) @if ($exp == '1') # @else {{ URL::to('/') }}/settings @endif @endif'
           class='els-wrap el-4'>
            <div class='icon' style="margin-left: 6px;">
                <img
                    src="https://img.icons8.com/external-tanah-basah-glyph-tanah-basah/25/000000/external-setting-essentials-pack-tanah-basah-glyph-tanah-basah.png"/>
            </div>
            <p style="font-size:12px;text-align:center">Setting</p>
        </a>

    </div>
</div>
<!-----Mobile Bottom Menu End---->
<!--Start of Tawk.to Script-->

<!--End of Tawk.to Script-->
<!--   Core JS Files   -->

<div id="mydiv" class="div" style="display:none">
    <div id="mydivheader">
        <div id="menubutton" style="left: 1px; display: block;background-color:unset;"><strong>Play
                Tutorial</strong></div>
        <div id="hidebutton"> X</div>
        <figure style="padding:30px 10px 0px 10px;">
            <iframe id="youTubeUrl" style="width:100%;min-height:300px" src=""
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </figure>
    </div>
</div>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"
    integrity="sha512-chZc2Mx8B1GzGSNMfJRH63jW7uYZXzX0a/UlWRrTvl4kxxYqUHNMtyTTA5IDQ7gTl4ATLoXlZthsialW3muS0A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
<script src="{{ asset('admin/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin/dist/js/fontawesome-iconpicker.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!--<script src="{{ asset('js/autocomplete.js') }}"></script>-->
<script src="{{ asset('js/iconpack.js') }}"></script>
<script src="{{ asset('js/notification.js') }}"></script>

@if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper' || Auth::user()->type == 'staff' || Auth::user()->type == 'affiliate')
    <script src="{{asset('js/frontendApp.js')}}"></script>
@endif

<script>
    $(document).ready(function () {
        //   window.addEventListener('load', fadeOutEffect);
        const myPreloader = document.querySelector('.preloader');
        fadeOutEffect();

        function fadeOutEffect() {
            setTimeout(function () {
                $('.preloader').hide();
            }, 300);
        }
    });

    $("#language-toggle").on('change', function () {
        $('#changelangform').submit();
    });
    $(".minus").on('click', function () {
        $('.firstmessage').hide();
    })
    $("#mobilesearch").on('click', function () {
        $('#mobilesearchdiv').toggle('fadeIn');
    })
    $("#iconNavbarSidenav1").on('click', function () {
        $('#toggleSidenav').toggle('fadeIn');
    })

    $("#mobilesearch1").on('click', function () {
        $('#mobilesearchdiv').toggle('fadeIn');
    })


    $('.fixed-plugin-button3').hide();
    $('.fixed-plugin-button1').on('click', function () {
        $('.chatbox').toggle();
        $('.fixed-plugin-button1').hide();
        $('.fixed-plugin-button3').show();
    });
    $('.fixed-plugin-button3').on('click', function () {
        $('.chatbox').toggle();
        $('.fixed-plugin-button3').hide();
        $('.fixed-plugin-button1').show();
    })

    $('.crosschat').on('click', function () {
        $('.chatbox').hide();
        $('.fixed-plugin-button3').hide();
        $('.fixed-plugin-button1').show();
    });


    $('#messgeul').scroll(function () {
        if ($('#messgeul').scrollTop() + $('#messgeul').height() == $('#messgeul').height()) {
            alert("bottom!");
        }
    });


    function myFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("mySearch");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myMenu");
        document.getElementById("myMenu").style.display = "block";
        document.getElementById("cross").style.display = "block";
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

    function myFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("mySearch1");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myMenu1");
        document.getElementById("myMenu1").style.display = "block";
        document.getElementById("cross1").style.display = "block";
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
</script>
@yield('js')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    var win = navigator.platform.indexOf('Win') > -1;

    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<script>
    $(function () {
        $('.action-destroy').on('click', function () {
            $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
        });

        // Live binding of buttons
        $(document).on('click', '.action-placement', function (e) {
            $('.action-placement').removeClass('active');
            $(this).addClass('active');
            $('.icp-opts').data('iconpicker').updatePlacement($(this).text());
            e.preventDefault();
            return false;
        });

        $('.action-create').on('click', function () {
            $('.icp-auto').iconpicker();
            $('.icp-dd').each(function () {
                var $this = $(this);
                $this.iconpicker({
                    title: 'Dropdown with picker',
                    container: $(' ~ .dropdown-menu:first', $this)
                });
            });

            $('.icp-glyphs').iconpicker({
                title: 'Using glypghicons',
                icons: ['home', 'repeat', 'search',
                    'arrow-left', 'arrow-right', 'star'
                ],
                iconBaseClass: 'glyphicon',
                iconComponentBaseClass: 'glyphicon',
                iconClassPrefix: 'glyphicon-'
            });

            $('.icp-opts').iconpicker({
                title: 'With custom options',
                icons: ['github', 'heart', 'html5', 'css3'],
                selectedCustomClass: 'label label-success',
                mustAccept: true,
                placement: 'bottomRight',
                showFooter: true,
                // note that this is ignored cause we have an accept button:
                hideOnSelect: true,
                templates: {
                    footer: '<div class="popover-footer">' +
                        '<div style="text-align:left; font-size:12px;">Placements: \n\
                                                                                                                            <a href="#" class=" action-placement">inline</a>\n\
                                                                                                                            <a href="#" class=" action-placement">topLeftCorner</a>\n\
                                                                                                                            <a href="#" class=" action-placement">topLeft</a>\n\
                                                                                                                            <a href="#" class=" action-placement">top</a>\n\
                                                                                                                            <a href="#" class=" action-placement">topRight</a>\n\
                                                                                                                            <a href="#" class=" action-placement">topRightCorner</a>\n\
                                                                                                                            <a href="#" class=" action-placement">rightTop</a>\n\
                                                                                                                            <a href="#" class=" action-placement">right</a>\n\
                                                                                                                            <a href="#" class=" action-placement">rightBottom</a>\n\
                                                                                                                            <a href="#" class=" action-placement">bottomRightCorner</a>\n\
                                                                                                                            <a href="#" class=" active action-placement">bottomRight</a>\n\
                                                                                                                            <a href="#" class=" action-placement">bottom</a>\n\
                                                                                                                            <a href="#" class=" action-placement">bottomLeft</a>\n\
                                                                                                                            <a href="#" class=" action-placement">bottomLeftCorner</a>\n\
                                                                                                                            <a href="#" class=" action-placement">leftBottom</a>\n\
                                                                                                                            <a href="#" class=" action-placement">left</a>\n\
                                                                                                                            <a href="#" class=" action-placement">leftTop</a>\n\
                                                                                                                            </div><hr></div>'
                }
            }).data('iconpicker').show();
        }).trigger('click');

        // Events sample:
        // This event is only triggered when the actual input value is changed
        // by user interaction
        $('.icp').on('iconpickerSelected', function (e) {
            $('.lead .picker-target').get(0).className = 'picker-target fa-3x ' +
                e.iconpickerInstance.options.iconBaseClass + ' ' +
                e.iconpickerInstance.getValue(e.iconpickerValue);
        });
    });
</script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="{{ asset('admin/assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://www.youtube.com/iframe_api"></script>


<script>
    const dasht = localStorage.getItem("dashtutorial");

    if (dasht === 'done') {
        var url = $('#shh').val();
        $('#youTubeUrl').attr('src', url);
        $("#mydiv").hide();
    } else {
        var url = $('#shh').val();
        $('#youTubeUrl').attr('src', url);
        $("#mydiv").show();
    }

    $("#shh").on('click', function () {
        var url = $('#shh').val();
        $('#youTubeUrl').attr('src', url);
        $("#mydiv").toggle();
    })
    $('#hidebutton').on('click', function () {
        $('#youTubeUrl').attr('src', url);
        $("#mydiv").toggle();
        localStorage.setItem("dashtutorial", "done");
    })
    dragElement(document.getElementById("mydiv"));

    function dragElement(elmnt) {
        var pos1 = 0,
            pos2 = 0,
            pos3 = 0,
            pos4 = 0;
        if (document.getElementById(elmnt.id + "header")) {
            /* if present, the header is where you move the DIV from:*/
            document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
        } else {
            /* otherwise, move the DIV from anywhere inside the DIV:*/
            elmnt.onmousedown = dragMouseDown;
        }

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }
</script>

<script>
    // Check if SmsAlert is present in the URL
    let smsAlert = '{{ request()->query('SmsAlert') }}';
    if (smsAlert !== '') {
        Swal.fire({
            title: smsAlert,
            icon: 'question',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Buy Now',
            cancelButtonText: 'Cancel',
            focusConfirm: false,
            preConfirm: () => {
                var paymentRoute = "{{ route('payment.payments') }}";
                window.open(paymentRoute, "_blank");
            }
        });
    }
</script>

<script>
    @if (Session::has('message'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.success("{{ session('message') }}");
    @endif

        @if (Session::has('error'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.error("{{ session::get('error') }}");
    @endif

        @if (Session::has('info'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.info("{{ session('info') }}");
    @endif

        @if (Session::has('warning'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.warning("{{ session('warning') }}");
    @endif

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.error("{{ $error }}");
    @endforeach
        @endif

        @if (Session::has('success'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.success("{{ session('success') }}");
    @endif
</script>

<script>
    // JavaScript
    let HTMLBox = document.getElementById("HTMLBox");
    let HTMLButton = document.getElementById("HTMLButton");

    HTMLButton.onclick = function () {
        HTMLBox.removeAttribute('disabled'); // temporarily remove disabled attribute
        HTMLBox.select();
        document.execCommand("copy");
        HTMLBox.setAttribute('disabled', 'disabled'); // set back the disabled attribute
        HTMLButton.innerText = "Link Copied";
    };
</script>


@stack('scripts')
</body>

</html>
