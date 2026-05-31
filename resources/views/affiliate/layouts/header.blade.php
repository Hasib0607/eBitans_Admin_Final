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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.9/sweetalert2.all.min.js"></script>
    <script>
        window.API_URL = @json(env('APP_URL'));
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

    @stack('styles')
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
    <style>

    </style>

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
    </style>

</head>

<body class="g-sidenav-show  bg-gray-200" id="bodyss">
