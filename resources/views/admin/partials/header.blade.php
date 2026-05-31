<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('fav-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('fav-icon.png') }}">
    <title>
        @yield('title') eBitans
    </title>

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Nucleo Icons -->
    <link href="{{ asset('admin/assets/css/nucleo-icons.css') }}" rel="stylesheet"/>
    <link href="{{ asset('admin/assets/css/nucleo-svg.css') }}" rel="stylesheet"/>
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet"/>
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="{{ asset('admin/dist/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('admin/assets/css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.12.0/css/bootstrap-tour-standalone.css" />-->
    <link rel="stylesheet" href="{{ asset('css/tour.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.12.0/js/bootstrap-tour-standalone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.9/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.9/sweetalert2.all.min.js"></script>
    <script>
        window.API_URL = @json(env('APP_URL'));
        window.csrfToken = '{{ csrf_token() }}';
    </script>
    <style>
        .alert-success {
            background-image: linear-gradient(195deg, #0670cb9c 0%, #09b311 100%);
        }

        .alert-success {
            color: #ffffff;
            background-color: #00ff1b9c;
            border-color: #c9e7cb;
        }

        #mydiv {
            /* position: absolute; */
            position: fixed;
            resize: both;
            width: 560px;
            z-index: 99999999999999;
            bottom: 6.7%;
            right: 1.6%;
        }

        #mydivheader {
            cursor: move;
            resize: both;
            width: 560px;
            z-index: 10;
            background-color: #f1593a;
            color: #fff;
        }

        #mydiv:hover #hidebutton {
            display: block !important;
        }

        #mydiv #hidebutton {
            position: absolute;
            top: 2px;
            font-size: 15px;
            right: 2px;
            padding: 0px 7px;
            background-color: rgba(0, 0, 0, .8);
            border-radius: 100%;
            cursor: pointer;
            display: none;
        }

        #mydiv #menubutton {
            position: absolute;
            top: 2px;
            font-size: 15px;
            right: 2px;
            padding: 0px 7px;
            background-color: rgba(0, 0, 0, .8);
            border-radius: 100%;
            cursor: pointer;
            display: none;
        }

        #serchWab {
            width: 100%;
            padding-left: 15px;
        }

        @media screen and (max-width: 575px) {
            #mobilesearchdiv {
                display: flex !important;
            }

            #serchWab {
                display: none !important;
            }

        }

        @media screen and (max-width: 640px) {
            #mydiv {
                width: 70%;
                bottom: 9.7%;
            }

            #mydivheader {
                width: 100%;
                height: 185px;
            }

            figure #youTubeUrl {
                min-height: 112px !important;
                max-height: 150px !important;
            }

            #mydiv #hidebutton {
                display: block !important;
            }

        }
    </style>

    @if (Session::has('lang') && Session::get('lang') == 'bn')
        <style>
            input.check-toggle-round-flat + label:after {
                top: 4px;
                left: -7px;
                bottom: 4px;
                width: 33px;
                background-color: #fff;
                -webkit-border-radius: 52px;
                -moz-border-radius: 52px;
                -ms-border-radius: 52px;
                -o-border-radius: 52px;
                border-radius: 5px;
                -webkit-transition: margin 0.2s;
                -moz-transition: margin 0.2s;
                -o-transition: margin 0.2s;
                transition: margin 0.2s;
            }
        </style>
    @else
        <style>
            input.check-toggle-round-flat + label:after {
                top: 4px;
                left: 2px;
                bottom: 4px;
                width: 33px;
                background-color: #fff;
                -webkit-border-radius: 52px;
                -moz-border-radius: 52px;
                -ms-border-radius: 52px;
                -o-border-radius: 52px;
                border-radius: 5px;
                -webkit-transition: margin 0.2s;
                -moz-transition: margin 0.2s;
                -o-transition: margin 0.2s;
                transition: margin 0.2s;
            }
        </style>
    @endif

    @yield('head')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea',
            plugins: [
                'a11ychecker', 'advlist', 'advcode', 'advtable', 'autolink', 'checklist', 'export',
                'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks',
                'powerpaste', 'fullscreen', 'formatpainter', 'insertdatetime', 'media', 'table', 'help',
                'wordcount'
            ],
            toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
        });
    </script>

    <style>
        .imageUploadLoading {
            align-items: center;
            background: #ffffffb0;
            display: flex;
            height: 100vh;
            justify-content: center;
            left: 0;
            position: fixed;
            top: 0;
            transition: opacity 0.2s linear;
            width: 100%;
            z-index: 9999;
            opacity: 1;
            transform: opacity 1s linear;
            display: none;
        }

        .ps .ps__rail-x:hover, .ps .ps__rail-y:hover, .ps .ps__rail-x:focus, .ps .ps__rail-y:focus, .ps .ps__rail-x.ps--clicking, .ps .ps__rail-y.ps--clicking {
            background-color: transparent;
            opacity: 0.9;
        }

        .ps--active-x > .ps__rail-x, .ps--active-y > .ps__rail-y {
            display: block !important;
        }

        .ps__thumb-y {
            display: block !important;
            opacity: 1 !important;
            width: 8px !important;
        }

        .ps__rail-y:hover > .ps__thumb-y, .ps__rail-y:focus > .ps__thumb-y, .ps__rail-y.ps--clicking .ps__thumb-y {
            width: 8px !important;
            background-color: #999 !important;
        }

        div#sidenav-collapse-main {
            max-height: fit-content !important;
            height: auto !important;
        }
    </style>

    <style>
        .guide-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #bae5ff;
            border-radius: 10px;
            border: 1px solid #8cb8d2;
            width: 200px;
            height: 175px;
            margin: 6px;
        }

        .guide-card-logo i {
            font-size: 60px;
        }

        .guide-row {
            display: flex;
            justify-content: center;
        }

        .redirect-text {
            font-size: 28px;
        }

        #countdown, #countdown-regular {
            font-size: 72px;
        }

        form#registrationForm {
            padding: 30px 30px 20px;
        }

        .line-through {
            text-decoration: line-through;
        }

        #registrationForm1 .line-through {
            color: red;
        }

        @media (max-width: 991px) {
            .guide-card {
                width: 180px !important;
                height: 150px !important;
            }

            form#registrationForm h1 {
                font-size: 25px;
            }

            form#registrationForm h3 {
                font-size: 18px;
            }

            div#countdown-timer h1 {
                font-size: 22px;
            }

            div#countdown {
                font-size: 50px;
            }


        }

        @media (max-width: 768px) {
            div#myModalGuide .modal-header a {
                display: none !important;
            }

            div#myModal .modal-header a {
                display: none !important;
            }
        }

        @media (max-width: 477px) {
            .guide-card {
                width: 128px !important;
                height: 128px !important;
            }
        }

        body.element-no-scroll .max-height-vh-100 {
            max-height: unset !important;
        }

        .sidenav-header a.navbar-brand {
            padding: 5px !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .sidenav-header a.navbar-brand img {
            max-height: 65px !important;
        }

        #cke_notifications_area_editor {
            display: none !important;
        }

        .imageUploadRemoveBtn {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff0000;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            line-height: 20px;
            padding: 0;
        }

        #fileManagerCKEditorModal {
            z-index: 10099 !important; /* Higher than default Bootstrap modal */
        }

        /*.modal-backdrop.show {*/
        /*    z-index: 10099 !important; !* Ensure backdrop also appears below modal *!*/
        /*    display: none !important;*/
        /*    opacity: 0 !important;*/
        /*}*/
        #tree .m-3.d-block.d-lg-none {
            display: none !important;
        }

        button.modalClose.close {
            float: right;
            border: none;
            border-radius: 5px;
            width: 40px;
            height: 40px;
            color: #ff2d2d;
            font-size: 33px;
            padding: 0px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            cursor: pointer;
        }

        main.main-content {
            min-height: calc(100vh - 145px);
        }

        :root {
            --admin-sidenav-width: 17.125rem;
        }

        body.g-sidenav-show {
            overflow-x: hidden;
        }

        body.g-sidenav-show > main.main-content {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        main.main-content > main.main-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            min-height: auto !important;
            padding-top: 0 !important;
            overflow-x: hidden;
        }

        main.main-content .container-fluid,
        main.main-content .row,
        main.main-content .card,
        main.main-content .table-responsive {
            max-width: 100%;
        }

        @media (min-width: 1200px) {
            body.g-sidenav-show:not(.g-sidenav-hidden) > main.main-content {
                margin-left: calc(var(--admin-sidenav-width) + 0.5rem) !important;
                width: calc(100% - var(--admin-sidenav-width) - 1rem) !important;
                max-width: calc(100% - var(--admin-sidenav-width) - 1rem) !important;
            }
        }

        @media (max-width: 1199.98px) {
            body.g-sidenav-show > main.main-content {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>

    @stack('styles')

</head>
