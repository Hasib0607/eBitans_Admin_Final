@extends('admin.layouts.main')
@push('styles')
    <!-- glightbox css -->
    <link rel="stylesheet" href="{{ asset('chat/assets/libs/glightbox/css/glightbox.min.css') }}">

    <!-- swiper css -->
    <link rel="stylesheet" href="{{ asset('chat/assets/libs/swiper/swiper-bundle.min.css') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('chat/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
          type="text/css"/>
    <!-- Icons Css -->
    <link href="{{ asset('chat/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="{{ asset('chat/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css"/>
    <style>
        .fg-emoji-picker {
            bottom: 100px !important;
        }

        main.main-content {
            margin: 0 10px 0 20px;
            padding-top: 0;
        }

        div#chat-root {
            max-width: 1200px;
            margin: 0 auto;
        }

        main.main-content .layout-wrapper {
            background: #d6d6d6;
            padding: 0 1px;
        }

        main.main-content .layout-wrapper .chat-content.d-lg-flex .chat-input-section.p-3.p-lg-4 {
            padding: 25px 10px 30px 10px !important;
        }

        .nav.nav-pills {
            background: #2f2f34;
        }

        .side-menu.flex-lg-column {
            height: calc(100vh - 150px);
        }

        .chat-leftsidebar {
            height: calc(100vh - 150px);
        }

        .user-chat.w-100.overflow-hidden {
            height: calc(100vh - 150px);
        }

        div#users-chat {
            height: calc(100vh - 250px);
        }

        .chat-conversation {
            height: calc(100vh - 250px);
        }

        nav#navbarBlur {
            padding-top: 9px !important;
        }

        input#serachChatUser {
            margin-bottom: 14px;
        }

        button#searchbtn-addon {
            margin-bottom: 14px;
        }

        i.bx.bx-search.align-middle {
            font-size: 20px;
        }

        i.bx.bx-plus {
            font-size: 15px;
            margin-top: 4px;
        }

        #bodyss .ps__rail-x {
            display: none !important;
        }

        input#searchContactModal {
            margin-bottom: 16px;
        }

        .btn-light {
            box-shadow: none !important;
        }

        button.btn.btn-soft-primary.btn-sm {
            margin-bottom: 0;
        }

        button.btn.btn-link.text-decoration-none.btn-lg.waves-effect {
            margin-bottom: 0;
        }

        button.btn.btn-primary.btn-lg.chat-send.waves-effect.waves-light {
            margin-bottom: 0;
        }

        input#chat-input {
            width: 96%;
        }

        a.fixed-plugin-button1, a.fixed-plugin-button3 {
            bottom: 10px !important;
        }

        a.fixed-plugin-button.text-dark.position-fixed.px-3.py-2 {
            bottom: 10px;
        }

        @if(Auth::user()->type == "superadmin" || Auth::user()->type == "superstaff")
        a.fixed-plugin-button.text-dark.position-fixed.px-3.py-2 {
            display: none;
        }

        @endif
        .chatItem:hover {
            background: #373737;
        }

        .chatItem.activeChat {
            background: #373737;
        }

        .chatActive {
            background: #39393f
        }

        .chat-conversation .right .conversation-list .ctext-wrap .ctext-wrap-content {
            background-color: rgb(38 38 38);
        }

        .swiper-wrapper {
            width: 15%;
        }

        .ebitans_avatar {
            background: #d3d3d3;
            padding: 7px;
        }

        .user-profile-img .profile-img {
            object-fit: contain;
        }


        .sitename a {
            font-weight: 600;
            font-family: "Roboto Slab", sans-serif;
            font-size: 1.875rem;
            line-height: 1.375;
            color: #344767;
        }

        a {
            letter-spacing: 0rem;
            color: #344767;
        }

        .ps__thumb-y {
            display: none !important;
        }

        .user-chat-topbar, .chat-input-section, .user-chat-topbar {
            background-color: #2e2e2e80 !important;
            border-bottom: 1px solid #333333 !important;
        }

        #users-conversation {
            height: calc(100% - 40px) !important;
        }

        .chat-conversation .chat-conversation-list {
            margin-top: 65px !important;
        }

        .userinfoDiv {
            height: 63% !important;
        }

        @media (max-width: 1199.98px) {
            .user-profile-sidebar {
                position: initial;
            }
        }

        @media (max-width: 991.98px) {
            .chat-conversation {
                height: calc(100vh - 200px);
            }

            .user-chat {
                top: 90px !important;
                height: calc(100vh - 90px) !important;
                visibility: visible !important;
                transform: translateX(0%) !important;
            }

            div#users-chat {
                height: calc(100vh - 190px) !important;
            }

            .visibleRightSide {
                visibility: visible !important
            }

            .notVisibleRightSide {
                visibility: hidden !important
            }

            .visibleLeftSide {
                display: block !important
            }

            .notVisibleLeftSide {
                display: block !important
            }


            .chat-leftsidebar {
                margin-top: 16px;
            }
        }

        @media (max-width: 690px) {
            .user-chat {
                top: 110px !important;
                height: calc(100vh - 110px) !important;
            }
        }

        @media (max-width: 576px) {
            .user-chat {
                top: 130px !important;
                height: calc(100vh - 130px) !important;
            }
        }

        @media (max-width: 452px) {
            .user-chat {
                top: 175px !important;
                height: calc(100vh - 175px) !important;
            }
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative border-radius-lg" id="chat-page">
        <div id="chat-root">
            <div class="layout-wrapper d-lg-flex">
                @php
                    $userid = \Illuminate\Support\Facades\Auth::user()->id ?? "";
                @endphp
                <chat-body :socketurl='@json(env("SOCKET_URL"))' :userid='@json("$userid")'/>
            </div>
        </div>
    </main>

@endsection

@push('scripts')
    <!-- JAVASCRIPT -->
    <script src="{{ asset('chat/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('chat/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('chat/assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- glightbox js -->
    <script src="{{ asset('chat/assets/libs/glightbox/js/glightbox.min.js') }}"></script>

    <!-- Swiper JS -->
    <script src="{{ asset('chat/assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- fg-emoji-picker JS -->
    <script src="{{ asset('chat/assets/libs/fg-emoji-picker/fgEmojiPicker.js') }}"></script>

    <!-- page init -->
    <script src="{{ asset('chat/assets/js/pages/index.init.js') }}"></script>

    <script src="{{ asset('chat/assets/js/app.js') }}"></script>

    <script src="{{asset('js/app.js')}}"></script>

    <script>
        $(document).ready(function () {
            $("#bodyss").attr("data-bs-theme", "dark");
        });
    </script>
@endpush
