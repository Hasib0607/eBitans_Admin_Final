<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
          integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/tour.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.12.0/js/bootstrap-tour-standalone.min.js"></script>
    <title>eBitans - Store List</title>
    <style>
        ul {
            list-style: none;
            padding-left: 0px;
        }

        ul li {
            padding: 18px 2px;
            border: 1px solid #f0ecec;
            margin-bottom: 5px;
        }

        ul li a {
            text-decoration: none;
            padding: 0px 10px;
            color: black;
        }

        ul li a:hover {
            text-decoration: none;
            color: black;
        }

        .zoom {
            transition: transform .2s;
            /* Animation */
        }

        .zoom:hover {
            transform: scale(1.1);
            /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }

        .zoom1 {
            transition: transform .2s;
            /* Animation */
        }

        .zoom1:hover {
            transform: scale(1.03);
            /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }

        @media only screen and (max-width: 767px) {
            .mainimg {
                display: none;
            }

            #create {
                font-size: 12px;
                margin-top: 5px;
            }

            #back {
                font-size: 12px;
                margin-top: 5px;
            }

            .logomiddle {
                text-align: center;
            }
        }

        /* Center the loader */
        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 120px;
            height: 120px;
            margin: -76px 0 0 -76px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #f1593a;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0px;
                opacity: 1
            }
        }

        @keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0;
                opacity: 1
            }
        }

        #myDiv {
            display: none;
            text-align: center;
        }


        #finaltext {
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 160px;
            font-family: 'Lato', sans-serif;
            font-size: 30px;
            line-height: 0px;
            color: #000;

        }

        #finaltext p {
            font-weight: 800;
            text-align: center;
        }

        #createdstore {
            position: absolute;
            top: 50%;
            left: 65%;
            transform: translate(-50%, -50%);
            height: 160px;
            font-family: 'Lato', sans-serif;
            font-size: 25px;
            line-height: 0px;
            color: #000;
        }

        #createdstore p {
            font-weight: 800;
        }

        .content {
            position: absolute;
            top: 68%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 160px;

            font-family: 'Lato', sans-serif;
            font-size: 15px;
            line-height: 0px;
            color: #000;
        }

        .content__container {
            font-weight: 500;
            overflow: hidden;
            height: 40px;
            padding: 0 20px;
        }

        .content__container:before {
            content: '';
            left: 0;
        }

        .content__container:after {
            content: '';
            position: absolute;
            right: 0;
        }

        .content__container:after,
        .content__container:before {
            position: absolute;
            top: 0;

            color: #16a085;
            font-size: 22px;
            line-height: 40px;

            -webkit-animation-name: opacity;
            -webkit-animation-duration: 3s;
            -webkit-animation-iteration-count: infinite;
            animation-name: opacity;
            animation-duration: 3s;
            animation-iteration-count: infinite;
        }

        .content__container__text {
            display: inline;
            float: left;
            margin: 0;
        }

        .content__container__list {
            margin-top: 0;
            /*padding-left: 110px;*/
            text-align: center;
            list-style: none;

            -webkit-animation-name: change;
            -webkit-animation-duration: 10s;
            -webkit-animation-iteration-count: infinite;
            animation-name: change;
            animation-duration: 10s;
            animation-iteration-count: infinite;
        }

        .content__container__item {
            line-height: 40px;
            margin: 0;

        }

        .content__container__list__item {
            border: 0px solid #fff !important;
            /*text-align:center;*/
        }


        @-webkit-keyframes opacity {

            0%,
            100% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }
        }

        @-webkit-keyframes change {

            0%,
            12.66%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            16.66%,
            29.32% {
                transform: translate3d(0, -25%, 0);
            }

            33.32%,
            45.98% {
                transform: translate3d(0, -50%, 0);
            }

            49.98%,
            62.64% {
                transform: translate3d(0, -75%, 0);
            }

            /*66.64%,79.3% {transform:translate3d(0,-100%,0);}*/
            /*83.3%,95.96% {transform:translate3d(0,-25%,0);}*/
        }

        @-o-keyframes opacity {

            0%,
            100% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }
        }

        @-o-keyframes change {

            0%,
            12.66%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            16.66%,
            29.32% {
                transform: translate3d(0, -25%, 0);
            }

            33.32%,
            45.98% {
                transform: translate3d(0, -50%, 0);
            }

            49.98%,
            62.64% {
                transform: translate3d(0, -75%, 0);
            }

            /*66.64%,79.3% {transform:translate3d(0,-50%,0);}*/
            /*83.3%,95.96% {transform:translate3d(0,-25%,0);}*/
        }

        @-moz-keyframes opacity {

            0%,
            100% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }
        }

        @-moz-keyframes change {

            0%,
            12.66%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            16.66%,
            29.32% {
                transform: translate3d(0, -25%, 0);
            }

            33.32%,
            45.98% {
                transform: translate3d(0, -50%, 0);
            }

            49.98%,
            62.64% {
                transform: translate3d(0, -75%, 0);
            }

            /*66.64%,79.3% {transform:translate3d(0,-50%,0);}*/
            /*83.3%,95.96% {transform:translate3d(0,-25%,0);}*/
        }

        @keyframes opacity {

            0%,
            100% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }
        }

        @keyframes change {

            0%,
            12.66%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            16.66%,
            29.32% {
                transform: translate3d(0, -25%, 0);
            }

            33.32%,
            45.98% {
                transform: translate3d(0, -50%, 0);
            }

            49.98%,
            62.64% {
                transform: translate3d(0, -75%, 0);
            }

            /*66.64%,79.3% {transform:translate3d(0,-50%,0);}*/
            /*83.3%,95.96% {transform:translate3d(0,-25%,0);}*/
        }

        .logoebit {
            display: none;
        }

        @media only screen and (max-width: 500px) {
            .createcontent {
                line-height: 28px;
            }

            .content__container__list__item {
                line-height: 28px;
            }

            .logoebit {
                display: block;
                margin: 0 auto;
            }

            .topreduce {
                padding-top: 25px !important;
                padding-left: 40px !important;
                padding-right: 40px !important;
            }

            .topreduce1 {
                margin-top: 20px !important;
            }
        }
    </style>

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

            .btnnnn {
                display: block !important;
            }

            .logOutBtn {
                margin-top: -10px !important;
            }
        }
    </style>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto');

        .hind-siliguri-light {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 300;
            font-style: normal;
        }

        .hind-siliguri-regular {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .hind-siliguri-medium {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 500;
            font-style: normal;
        }

        .hind-siliguri-semibold {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 600;
            font-style: normal;
        }

        .hind-siliguri-bold {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 700;
            font-style: normal;
        }


        body {
            font-family: 'Roboto', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
        }

        i {
            margin-right: 10px;
        }

        /*------------------------*/
        input:focus,
        button:focus,
        .form-control:focus {
            outline: none;
            box-shadow: none;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #fff;
        }

        /*----------step-wizard------------*/
        .d-flex {
            display: flex;
        }

        .justify-content-center {
            justify-content: center;
        }

        .align-items-center {
            align-items: center;
        }

        /*---------signup-step-------------*/
        .bg-color {
            background-color: #333;
        }

        .signup-step-container {
            padding: 0px 0px;
            padding-bottom: 60px;
        }

        .wizard .nav-tabs {
            position: relative;
            margin-bottom: 0;
            border-bottom-color: transparent;
        }

        .wizard > div.wizard-inner {
            position: relative;
            margin-bottom: 50px;
            text-align: center;
        }

        .connecting-line {
            height: 2px;
            background: #e0e0e0;
            position: absolute;
            width: 85%;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: 15px;
            z-index: 1;
        }

        .wizard .nav-tabs > li.active > a,
        .wizard .nav-tabs > li.active > a:hover,
        .wizard .nav-tabs > li.active > a:focus {
            color: #555555;
            cursor: default;
            border: 0;
            border-bottom-color: transparent;
        }

        span.round-tab {
            width: 30px;
            height: 30px;
            line-height: 30px;
            display: inline-block;
            border-radius: 50%;
            background: #fff;
            z-index: 2;
            position: absolute;
            left: 0;
            text-align: center;
            font-size: 16px;
            color: #0e214b;
            font-weight: 500;
            border: 1px solid #ddd;
        }

        span.round-tab i {
            color: #555555;
        }

        .wizard li.active span.round-tab {
            background: #0db02b;
            color: #fff;
            border-color: #0db02b;
        }

        .wizard li.active span.round-tab i {
            color: #5bc0de;
        }

        .wizard .nav-tabs > li.active > a i {
            color: #0db02b;
        }

        .wizard .nav-tabs > li {
            width: 15%;
        }

        ul.nav.nav-tabs {
            justify-content: space-between;
        }

        .wizard li:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 0;
            margin: 0 auto;
            bottom: 0px;
            border: 5px solid transparent;
            border-bottom-color: red;
            transition: 0.1s ease-in-out;
        }

        .wizard .nav-tabs > li a {
            width: 30px;
            height: 30px;
            margin: 20px auto;
            border-radius: 100%;
            padding: 0;
            background-color: transparent;
            position: relative;
            top: 0;
        }

        .wizard-inner {
            margin-left: -25px;
        }

        .wizard .nav-tabs > li a i {
            position: absolute;
            top: -15px;
            font-style: normal;
            font-weight: 400;
            white-space: nowrap;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: 700;
            color: #000;
        }

        .wizard .nav-tabs > li a:hover {
            background: transparent;
        }

        .wizard .tab-pane {
            position: relative;
            padding-top: 20px;
        }

        .wizard-inner ul li {
            padding: 0;
            border: none;
            margin-bottom: 5px;
        }

        .wizard h3 {
            margin-top: 0;
        }

        .prev-step,
        .next-step {
            font-size: 13px;
            padding: 8px 24px;
            border: none;
            border-radius: 4px;
            margin-top: 30px;
        }

        .next-step {
            background-color: #0db02b;
        }

        .skip-btn {
            background-color: #cec12d;
        }

        .step-head {
            font-size: 20px;
            text-align: center;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .term-check {
            font-size: 14px;
            font-weight: 400;
        }

        .custom-file {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 40px;
            margin-bottom: 0;
        }

        .custom-file-input {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 40px;
            margin: 0;
            opacity: 0;
        }

        .custom-file-label {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1;
            height: 40px;
            padding: .375rem .75rem;
            font-weight: 400;
            line-height: 2;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }

        .custom-file-label::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            display: block;
            height: 38px;
            padding: .375rem .75rem;
            line-height: 2;
            color: #495057;
            content: "Browse";
            background-color: #e9ecef;
            border-left: inherit;
            border-radius: 0 .25rem .25rem 0;
        }

        .footer-link {
            margin-top: 30px;
        }

        .all-info-container {
        }

        .list-content {
            margin-bottom: 10px;
        }

        .list-content a {
            padding: 10px 15px;
            width: 100%;
            display: inline-block;
            background-color: #f5f5f5;
            position: relative;
            color: #565656;
            font-weight: 400;
            border-radius: 4px;
        }

        .list-content a[aria-expanded="true"] i {
            transform: rotate(180deg);
        }

        .list-content a i {
            text-align: right;
            position: absolute;
            top: 15px;
            right: 10px;
            transition: 0.5s;
        }

        .form-control[disabled],
        .form-control[readonly],
        fieldset[disabled] .form-control {
            background-color: #fdfdfd;
        }

        .list-box {
            padding: 10px;
        }

        .signup-logo-header .logo_area {
            width: 200px;
        }

        .signup-logo-header .nav > li {
            padding: 0;
        }

        .signup-logo-header .header-flex {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .list-inline li {
            display: inline-block;
        }

        .pull-right {
            float: right;
        }

        /*-----------custom-checkbox-----------*/
        /*----------Custom-Checkbox---------*/
        input[type="checkbox"] {
            position: relative;
            display: inline-block;
            margin-right: 5px;
        }

        input[type="checkbox"]::before,
        input[type="checkbox"]::after {
            position: absolute;
            content: "";
            display: inline-block;
        }

        input[type="checkbox"]::before {
            height: 16px;
            width: 16px;
            border: 1px solid #999;
            left: 0px;
            top: 0px;
            background-color: #fff;
            border-radius: 2px;
        }

        input[type="checkbox"]::after {
            height: 5px;
            width: 9px;
            left: 4px;
            top: 4px;
        }

        input[type="checkbox"]:checked::after {
            content: "";
            border-left: 1px solid #fff;
            border-bottom: 1px solid #fff;
            transform: rotate(-45deg);
        }

        input[type="checkbox"]:checked::before {
            background-color: #18ba60;
            border-color: #18ba60;
        }

        @media (max-width: 767px) {
            .sign-content h3 {
                font-size: 40px;
            }

            .wizard .nav-tabs > li a i {
                display: none;
            }

            .signup-logo-header .navbar-toggle {
                margin: 0;
                margin-top: 8px;
            }

            .signup-logo-header .logo_area {
                margin-top: 0;
            }

            .signup-logo-header .header-flex {
                display: block;
            }
        }

        .wizard ul li {
            border: none !important;
        }

        .pulse-wrapper {
            overflow: hidden;
            position: relative;
        }

        .pulse-wrapper-top-div {
            display: none !important;
        }

        .pulse-text {
            font-size: 3.5rem;
            font-weight: 900;
            font-family: "Hind Siliguri", sans-serif;
            color: #fff;
            transform-origin: center;
            justify-content: right;
            display: flex;
            width: 100%;
            padding-right: 50px;
            line-height: 80px;
            word-spacing: 10px;
        }

        .pulse-text-top {
            font-size: 3.5rem;
            font-weight: 900;
            font-family: "Hind Siliguri", sans-serif;
            color: #fff;
            transform-origin: center;
            justify-content: right;
            display: flex;
            width: 100%;
            line-height: 80px;
            word-spacing: 3px;
        }

        .p-animation {
            animation: pulse 2s infinite;
            transform-origin: center;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        @media (max-width: 768px) {
            .pulse-text {
                font-size: 2rem;
                display: none !important;
            }

            .pulse-text-top {
                font-size: 25px;
                display: block !important;
            }

            .pulse-text-top h1 {
                font-size: 24px;
            }

            .pulse-text-top h1 span.p-animation {
                font-size: 32px !important;
            }

            .pulse-wrapper-top-div, .pulse-wrapper-top-div .pulse-wrapper {
                display: block !important;
            }

            .row.topreduce1.pulse-wrapper-top-div h1.pulse-text {
                display: block !important;
            }

            .pulse-wrapper {
                display: none !important;
            }

            .col-sm-12.col-md-6.topreduce {
                margin-bottom: 50px;
            }
        }

        .payment-method {
            border: 2px solid #81aabc;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #c2ebfd;
        }

        .payment-method:hover, .payment-method:active {
            border-color: #4a6bff;
            background-color: #f5f8ff;
        }

        .payment-method img {
            height: 50px;
        }

        .payment-method.selected {
            border-color: #4a6bff;
            background-color: #f5f8ff;
            box-shadow: 0 0 0 2px rgba(74, 107, 255, 0.3);
        }

        .payment-method:active {
            transform: scale(0.98);
        }

    </style>
</head>

<body>
<div class="container-fluid" id="maindiv">
    <div class="row main-wrapper" style="height: 100vh;">
        <div class="col-sm-12 col-md-6 topreduce"
             style="position:relative;padding: 0px 8%;padding-top:120px;">
            <div class="row">
                <div class="col-md-6 logomiddle">
                    <!--<h1>eBitans</h1>-->
                    <img src="{{ URL::to('/') }}/logo-dark.png" class="logoebit" width="150px">
                    <p
                        style="font-size:25px;font-weight:700; padding-top:15px; line-height: 1.2; padding-bottom:15px;">
                        Welcome {{ Auth::user()->name }}
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <ul style="list-style:none;">
                        <li style="float:right;border:0px;">
                            <button href='#' class="zoom logOutBtn"
                                    onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                                    style="color:#f1593a;background-color:transparent;border:0px;font-weight:bold;margin-left:10px;"
                                    data-toggle="tooltip" data-placement="top" title="Logout">
                                Logout
                                <img src="https://img.icons8.com/ios/17/000000/exit.png"/>
                            </button>
                        </li>
                        <button id="shh"
                                style="padding: 10px;border: 1px solid gray;border-radius: 10px; display:none;"
                                class="btn btn-sm btnnnn" value="https://www.youtube.com/embed/N7zGnqsrWL0">Play
                            Tutorial <img src="https://img.icons8.com/nolan/24/play.png"/></button>
                        <div>
                            <form id="frm-logout" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            {{-- <li style="float:right;border:0px;">
                            <p style="font-size:20px;font-weight:400;">Logout</p>
                        </li> --}}
                        </div>
                    </ul>
                </div>
            </div>
            <div class="showhidebutton">
            </div>
            <div class="row">
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

            </div>

            <div class="row topreduce1 pulse-wrapper-top-div"
                 style="margin-top:40px;padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                <div class="col-sm-12 col-md-6 text-center pulse-wrapper" style="margin: auto; padding: 20px 0;">
                    <div class="pulse-text-top" style="display: flex;color: #ff3737;">
                        <h1>
                            মাত্র
                            <span
                                class="p-animation"
                                style="display:inline-block;font-size: 80px; padding-right:0px; font-weight: bold; color:white; ">দুটি
                        ধাপ </span>
                            সম্পন্ন করেই,
                        </h1>
                        <h1>
                            আপনার
                            <span class="p-animation"
                                  style="display:inline-block;font-size: 80px; padding-right:0px;font-weight: bold; color:white;">বিক্রি
                        শুরু </span>
                            করুন।
                        </h1>
                    </div>
                </div>
            </div>

            <div class="row topreduce1"
                 style="margin-top:40px;padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                <div class="col-md-6 col-sm-6 col-6">
                    <h1 style="font-size:30px;">Store List</h1>
                </div>
                <div class="col-md-6 col-sm-6 col-6 text-right"
                     style="display: flex;align-items: center;justify-content: end;">
                    <a href="#" class="btn btn-secondary zoom" id="create"
                       style="background-color:#f1593a;border:0px;border-radius:0.75rem;display: none;">Create Store</a>
                    <a href="#"
                       class="btn btn-secondary zoom"
                       id="back"
                       style="background-color:#f1593a;border:0px;border-radius:0.75rem;display: none;">Store List</a>
                </div>
            </div>

            <div id="storelist" class="row mt-4 p-4"
                 style="height: 50vh;overflow-y: scroll; border: 1px solid #999b9e; padding: 10px; border-radius: 10px;display: none;margin-bottom: 20px;">
                <div class="col-md-12">
                    <ul>
                        @foreach ($store as $stores)
                            <li class="zoom1"
                                style="display:flex;justify-content:space-between;align-items:center;border-radius:10px;">
                                <a href="{{ route('activestore', $stores->id) }}"
                                   style="display:block;width:100%">{{ $stores->name }} </a><span
                                    style="margin-right:10px;"
                                    class="badge @if ($stores->expiry_date <= Carbon\Carbon::now()) badge-danger  @else badge-success @endif">
                                        @if ($stores->expiry_date <= Carbon\Carbon::now())
                                        Deactive
                                    @else
                                        Active
                                    @endif
                                    </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div id="createstore" class="row"
                 style="padding-top:50px;display: none">
                <section class="signup-step-container" style="width: 100%;">
                    <div class="container">
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-12">
                                <div class="wizard">
                                    <div class="wizard-inner" style="margin-left: -30px;">
                                        <div class="connecting-line"></div>
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#step1" data-toggle="tab" aria-controls="step1"
                                                   role="tab" aria-expanded="true">
                                                        <span class="round-tab" style="pointer-events: none;">1
                                                        </span>
                                                </a>
                                            </li>
                                            <li role="presentation" class="disabled">
                                                <a href="#step2" data-toggle="tab" aria-controls="step2"
                                                   role="tab"><span
                                                        class="round-tab" style="pointer-events: none;">2</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <form id="createStoreForm" action="{{ route('ChooseProductsInfoSubmit') }}"
                                          method="post"
                                          class="login-box">
                                        @csrf
                                        <div class="tab-content" id="main_form">
                                            <div class="tab-pane active" role="tabpanel" id="step1">
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <div class="form-group">
                                                            <label>@if(isset(auth()->user()->register_from) && auth()->user()->register_from == "ebitans.com.bd")
                                                                    {{ 'আপনার ব্যবসার নাম' }}
                                                                @else
                                                                    {{ 'Your business name' }}
                                                                @endif </label>
                                                            <input class="form-control audio-trigger" type="text"
                                                                   data-audio="{{ asset('audio/input/business-name.mp3') }}"
                                                                   data-speech="Type your business name"
                                                                   name="storeName" id="storeNames"
                                                                   placeholder="Type your business name" required>
                                                        </div>
                                                        <span id="storeNameMessage"></span>
                                                    </div>
                                                    <div class="col-md-12 mt-1">
                                                        <div class="form-group">
                                                            <label>@if(isset(auth()->user()->register_from) && auth()->user()->register_from == "ebitans.com.bd")
                                                                    {{ 'আপনার ব্যবসার ধরন বাছাই করুন' }}
                                                                @else
                                                                    {{ 'Select your business type' }}
                                                                @endif </label>
                                                            <select name="type" class="form-control audio-trigger"
                                                                    data-audio="{{ asset('audio/input/business-type.mp3') }}"
                                                                    data-speech="Select your business type"
                                                                    onchange="StoreType()"
                                                                    id="storeType" required>
                                                                <option disabled selected>Select Type</option>
                                                                @php
                                                                    $parentCategories = \App\Models\BusinessCategory::with('subcategories')->whereNull('parent_id')->get();
                                                                @endphp

                                                                @foreach($parentCategories as $parent)
                                                                    {{-- Parent category --}}
                                                                    <option value="{{ $parent->id }}"
                                                                        {{ (isset($category->parent_id) && $parent->id == $category->parent_id) ? 'selected' : '' }}>
                                                                        {{ $parent->name }}
                                                                    </option>

                                                                    {{-- Subcategories --}}
                                                                    @if($parent->subcategories && $parent->subcategories->count())
                                                                        @foreach($parent->subcategories as $sub)
                                                                            <option value="{{ $sub->id }}"
                                                                                {{ (isset($category->parent_id) && $sub->id == $category->parent_id) ? 'selected' : '' }}>
                                                                                &nbsp;&nbsp;↳ {{ $sub->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    @if(!isset(auth()->user()->phone) || empty(auth()->user()->phone))
                                                        <div class="col-md-12 mt-1">
                                                            <div class="form-group">
                                                                <label>@if(isset(auth()->user()->register_from) && auth()->user()->register_from == "ebitans.com.bd")
                                                                        {{ 'আপনার মোবাইল নম্বর' }}
                                                                    @else
                                                                        {{ 'Phone Number' }}
                                                                    @endif</label>
                                                                <input class="form-control audio-trigger"
                                                                       @if(isset(auth()->user()->register_from) && auth()->user()->register_from == "ebitans.com.bd")
                                                                           data-audio="{{ asset('audio/input/mobile-number.mp3') }}"
                                                                       @endif
                                                                       data-speech="Enter your phone number"
                                                                       type="text"
                                                                       name="phone" id="phone"
                                                                       placeholder="Type your mobile number" required>
                                                            </div>
                                                            <span id="phoneMessage"></span>
                                                        </div>
                                                    @endif

                                                    @if(env("PAID_REGISTRATION"))
                                                        <input type="hidden" id="payment_method" name="payment_method"
                                                               value="bkash">
                                                        @php
                                                            $registrationFees = DB::table("registration_fees")->where("status", 1)->first();
                                                        @endphp
                                                        <input type="hidden" id="registration_fee"
                                                               name="registration_fee"
                                                               value="{{ isset($registrationFees) ? $registrationFees->price : '' }}">
                                                    @endif

                                                </div>
                                                <ul id="step1b" class="pull-right">
                                                    <li style="border: none!important">
                                                        <button type="button"
                                                                class="default-btn next-step audio-trigger-focus"
                                                                data-audio="{{ asset('audio/input/next-step.mp3') }}"
                                                                data-speech="Continue to next step">
                                                            Continue to next step
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-pane" role="tabpanel" id="step2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>আপনার SUB-DOMAIN নির্বাচন করুন</label>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text">https://</span>
                                                                <input type="text" name="slug"
                                                                       id="storename" placeholder="domain name"
                                                                       autocomplete="off"
                                                                       class="form-control audio-trigger"
                                                                       data-audio="{{ asset('audio/input/domain.mp3') }}"
                                                                       data-speech="Enter domain name"
                                                                       required onpaste="return false;"
                                                                       onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57)">
                                                                <span
                                                                    class="input-group-text">.{{ env("STORE_SUB_DOMAIN") }}</span>

                                                            </div>
                                                            <p class="urlcheck"></p>
                                                            <p class="urltext">This is a temporary URL to start
                                                                setting up your Ebitans store. Once
                                                                you setup your store, you can choose to keep it
                                                                as-is, or replace it with a custom
                                                                domain name.</p>
                                                            <input type="hidden" name="package_type" value="ecw">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-4">
                                                        <div class="form-group">
                                                            <label>Currency</label>
                                                            @php
                                                                $currency = DB::table("currencies")->get();
                                                            @endphp
                                                            <select name="currency" class="form-control"
                                                                    id="currency" required>
                                                                @if(count($currency))
                                                                    @foreach($currency as $item)
                                                                        <option
                                                                            value="{{ $item->id }}">{{ $item->code }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="list-inline pull-right">
                                                    <li>
                                                        <button type="button"
                                                                class="default-btn prev-step">Back
                                                        </button>
                                                    </li>
                                                    <li id="step2b">
                                                        <button type="button" onclick="myFunction()"
                                                                class="default-btn next-step audio-trigger-focus"
                                                                data-audio="{{ asset('audio/input/create.mp3') }}"
                                                                data-speech="Continue to create store">Create Website
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        {{--        <div class="col-sm-12 col-md-6" style="margin:auto;">--}}

        {{--            <iframe align="left" style="border-radius:10px; " class="mainimg" width="765" height="425"--}}
        {{--                    src="https://www.youtube.com/embed/N7zGnqsrWL0?si=r5j1Nmxr6KhTYuzD?autoplay=1&mute=1&loop=1"--}}
        {{--                    title="Create an E-Commerce Website in eBitans (Tutorial 2)"--}}
        {{--                    frameborder="0" allow="accelerometer;--}}
        {{--                            clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"--}}
        {{--                    allowfullscreen></iframe>--}}
        {{--        </div>--}}
        <div class="col-sm-12 col-md-6 text-center pulse-wrapper relative"
             style="
             margin: auto;
                margin-top: 0;
                margin-bottom: 0;
                padding: 0;
                display: flex;
                align-items: center;
                background-image: url(https://admin.ebitans.com/store-bg.webp);
                background-repeat: no-repeat;
                background-position: bottom left;
                background-size: cover;
                 ">
            <div class="pulse-text" style="display: flex;color: #ff3737;">
                <h1>
                    মাত্র
                    <span
                        class="p-animation"
                        style="display:inline-block;font-size: 80px; width:285px; padding-left:20px; padding-right:0px; font-weight: bold; color:white; ">দুটি
                        ধাপ </span>
                    সম্পন্ন করেই,

                    <br>আপনার
                    <span class="p-animation"
                          style="display:inline-block;font-size: 80px; width:355px; padding-left:20px; padding-right:0px;font-weight: bold; color:white;">বিক্রি
                        শুরু </span>
                    করুন।
                </h1>
            </div>
            <img src="{{ asset("ebitans.png") }}" alt=""
                 style="position: absolute; right: -14px; bottom: -10px; width: 400px;">
        </div>


    </div>
</div>

<div class="container d-flex flex-wrap align-items-center h-100" id="preloaderdiv" style="display:none">
    <div id="loader" style="display:none"></div>
    <div id="createdstore" style="display:none">

    </div>
    <div class="content" id="content" style="display:none">
        <p style="font-size:30px;font-weight:600;text-align:center;padding-bottom:10px;padding-top:20px;"
           class="createcontent">Creating Your Store</p>
        <div class="content__container">
            <ul class="content__container__list">
                <li class="content__container__list__item">1 of 4: Confirm Your Account</li>
                <li class="content__container__list__item">2 of 4: Initializing Your Store</li>
                <li class="content__container__list__item">3 of 4: Applying Store Settings</li>
                <li class="content__container__list__item">4 of 4: Finalizing Your Store Keep Your Browser Window
                    Open
                </li>
            </ul>
        </div>
    </div>
    <div id="finaltext">
        <p style="line-height:28px;">Success, Your store is ready to go!</p>
    </div>
</div>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
</script>

@include('admin.share.input-audio')

<!-- Option 2: Separate Popper and Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Initialize the tour
    // tour2.init();

    // Start the tour
    // tour2.start();
</script>
<script>
    document.getElementById("finaltext").style.display = "none";
    var myVar;

    function myFunction() {
        $('#payment_method').val("");

        let storeNames = $("#storeNames").val();
        let storeType = $("#storeType").val();
        let domainName = $("#storename").val();
        let phone = $("#phone").val();
        if (storeNames == "" || storeType == "" || domainName == "") {
            swal.fire(
                'Warning!',
                "Field must not be empty 🥱",
                'warning'
            );
            return false;
        }

        const isEbitansUser = @json(isset(auth()->user()->register_from) && auth()->user()->register_from == "ebitans.com.bd");
        if (isEbitansUser) {
            if (phone == "") {
                swal.fire(
                    'Warning!',
                    "Phone must not be empty 🥱",
                    'warning'
                );
                return false;
            }
        }

        const paidRegistration = @json(env("PAID_REGISTRATION"));
        let registrationFee = @json(isset($registrationFees) ? $registrationFees->price : null);

        if (!registrationFee || isNaN(registrationFee)) {
            const feeInput = $("#registration_fee").val();
            registrationFee = parseFloat(feeInput) || 0; // Default to 0 if invalid
        }

        // Ensure it's a number and format if needed
        registrationFee = Number(registrationFee);

        {{--if (paidRegistration && registrationFee > 0) {--}}
        {{--    Swal.fire({--}}
        {{--        title: 'রেজিস্ট্রেশন করতে </br>পেমেন্ট সম্পন্ন করুন',--}}
        {{--        html: `<div style="text-align: center;">--}}
        {{--                    <p style="margin-bottom: 5px;">আপনার রেজিস্ট্রেশন ফি</p>--}}
        {{--                    <h3 style="margin-top: 0; color: #2d3748; font-weight: bold;">${registrationFee} TK</h3>--}}
        {{--    --}}
        {{--                    <div style="margin: 20px 0; padding: 15px; background: #f3f4f6; border-radius: 8px; border: 1px solid #e5e7eb;">--}}
        {{--                        <p style="margin-bottom: 10px;">পেমেন্ট করতে 'বিকাশ/ নগদ' বাছাই করুন</p>--}}
        {{--                        <div style="display: flex; justify-content: center; gap: 15px;">--}}
        {{--                            <div class="payment-method selected" onclick="selectPaymentMethod('bkash', this)">--}}
        {{--                                <img src="{{ asset("img/payment/bkashLogo.png") }}" style="height: 50px;" alt="">--}}
        {{--                            </div>--}}
        {{--                            <div class="payment-method" onclick="selectPaymentMethod('nagad', this)">--}}
        {{--                                <img src="{{ asset("img/payment/nagadLogo.png") }}" style="height: 50px;" alt="">--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--        `,--}}
        {{--        showConfirmButton: true,--}}
        {{--        showCancelButton: false,--}}
        {{--        allowOutsideClick: false,--}}
        {{--        confirmButtonColor: '#3085d6',--}}
        {{--        cancelButtonColor: '#d33',--}}
        {{--        confirmButtonText: 'Pay Now',--}}
        {{--        cancelButtonText: 'Cancel',--}}
        {{--        focusConfirm: false,--}}
        {{--        reverseButtons: true,--}}
        {{--    }).then((result) => {--}}
        {{--        if (result.value) {--}}
        {{--            const payment_method = $('#payment_method').val();--}}
        {{--            if (payment_method == "") {--}}
        {{--                swal.fire(--}}
        {{--                    'Warning!',--}}
        {{--                    "Please select a payment method!",--}}
        {{--                    'warning'--}}
        {{--                );--}}
        {{--                return false--}}
        {{--            }--}}

        {{--            document.getElementById("maindiv").style.display = "none";--}}
        {{--            document.getElementById("loader").style.display = "block";--}}
        {{--            document.getElementById("preloaderdiv").style.display = "block";--}}
        {{--            document.getElementById("content").style.display = "block";--}}
        {{--            document.getElementById("finaltext").style.display = "none";--}}
        {{--            document.getElementById("createstore").style.display = "block";--}}
        {{--            myVar = setTimeout(showfinal, 6000);--}}
        {{--        } else {--}}
        {{--            return false--}}
        {{--        }--}}
        {{--    });--}}
        {{--} else {--}}
        document.getElementById("maindiv").style.display = "none";
        document.getElementById("loader").style.display = "block";
        document.getElementById("preloaderdiv").style.display = "block";
        document.getElementById("content").style.display = "block";
        document.getElementById("finaltext").style.display = "none";
        document.getElementById("createstore").style.display = "block";
        myVar = setTimeout(showfinal, 6000);
        // }
    }

    function selectPaymentMethod(method, element) {
        // Remove selected class from all payment methods
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });

        // Add selected class to clicked element
        element.classList.add('selected');

        // Set selected method in hidden input
        $('#payment_method').val(method);

        // Close the SweetAlert popup
        // Swal.close();
        //
        // if (method) {
        //     document.getElementById("maindiv").style.display = "none";
        //     document.getElementById("loader").style.display = "block";
        //     document.getElementById("preloaderdiv").style.display = "block";
        //     document.getElementById("content").style.display = "block";
        //     document.getElementById("finaltext").style.display = "none";
        //     document.getElementById("createstore").style.display = "block";
        //     myVar = setTimeout(showfinal, 6000);
        // }
    }


    var myVar1

    function showfinal() {
        document.getElementById("maindiv").style.display = "none";
        document.getElementById("loader").style.display = "none";
        document.getElementById("preloaderdiv").style.display = "none";
        document.getElementById("content").style.display = "none";
        document.getElementById("finaltext").style.display = "block";
        document.getElementById("createstore").style.display = "none";
        myVar1 = setTimeout(showPage, 1000);
    }

    function showPage() {
        $("#createStoreForm").submit();
        document.getElementById("maindiv").style.display = "none";
        document.getElementById("preloaderdiv").style.display = "none";
        document.getElementById("loader").style.display = "none";
        document.getElementById("content").style.display = "none";
        document.getElementById("myDiv").style.display = "none";
        document.getElementById("finaltext").style.display = "none";
        document.getElementById("createstore").style.display = "none";
    }
</script>

<script>
    // Store name validation with message
    $('#storeType').prop('disabled', true);
    $(document).ready(function () {
        // Debounce function to delay function calls
        function debounce(func, delay) {
            let debounceTimer;
            return function () {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            };
        }

        $('#storeNames').on('keyup', debounce(checkStoreName, 500));

        // Reset message color when input field is focused
        // $('#storeNames').on('focus', function () {
        //     $('#storeNameMessage').css('color', ''); // Reset color to default
        // });

        function checkStoreName() {
            var name = $('#storeNames').val();
            if (name.trim() === "") {
                $('#storeNameMessage').text("");
                $('#storeNameMessage').hide();
                $('#storeType').prop('disabled', true);
                disableContinueButton();
                return;
            }

            var url = "{{ route('check.store.name') }}";

            $.get(url, {name: name}, function (data) {
                var message = data.storeName['message'];
                $('#storeNameMessage').text(message);
                if (data.storeName['status'] == 200) {
                    $('#storeNameMessage').css('color', 'green');
                    $('#storeType').prop('disabled', false);
                    const domainName = name.replace(/\s+/g, '').toLowerCase();
                    $("#storename").val(domainName);
                    checkDomain();
                    enableContinueButton();
                } else {
                    $('#storeNameMessage').css('color', 'red');
                    $('#storeType').prop('disabled', true);
                    disableContinueButton();
                }
                $('#storeNameMessage').show();

                // Enable or disable "Continue to next step" button based on storeType's disabled property
                if ($('#storeType').prop('disabled')) {
                    disableContinueButton();
                } else {
                    enableContinueButton();
                }
            });
        }

        // Trigger checkStoreName initially
        checkStoreName();


        // Declare debounce timer globally
        let phoneDebounceTimer;

        // Debounced version of toggleNextButton
        function debounceToggleNextButton(value) {
            clearTimeout(phoneDebounceTimer);
            phoneDebounceTimer = setTimeout(() => {
                toggleNextButton(value);
            }, 500); // Adjust delay as needed
        }

        const phoneInput = document.getElementById('phone');

        // If the phone input exists, start checking it
        if (phoneInput) {
            // Initial check
            toggleNextButton(phoneInput.value);

            // Listen to input and Debounced input listener
            phoneInput.addEventListener('input', function () {
                debounceToggleNextButton(this.value);
            });
        }

        function normalizePhoneNumber(phone) {
            // Remove spaces, dashes, parentheses, and non-digit characters
            return phone.replace(/[^\d+]/g, '');
        }

        function toggleNextButton(value) {
            const cleanedValue = normalizePhoneNumber(value);
            $("#phone").val(cleanedValue);

            const isValid = value.trim() !== '' && isValidBdMobileNumber(cleanedValue);

            if (!isValid) {
                $('#step1b button.next-step').prop('disabled', true).hide();
            } else {
                $.post('{{ route('admin.check.user.phone') }}', {
                    phone: cleanedValue
                })
                    .done(function (response) {
                        $("#phoneMessage").html("");
                        if (response.status) {
                            $("#phone").val(response.data || cleanedValue);
                            $('#step1b button.next-step').prop('disabled', false).show();
                        } else {
                            $('#step1b button.next-step').prop('disabled', true).hide();
                            const message = `<p style="color: red">${response.message}</p>`;
                            $("#phoneMessage").html(message);
                        }
                    })
                    .fail(function (xhr, status, error) {
                        const errorResponse = xhr.responseText || error;
                        $('#step1b button.next-step').prop('disabled', true).hide();
                        const responseMessage = JSON.parse(errorResponse);
                        const message = `<p style="color: red">${responseMessage.message}</p>`;
                        $("#phoneMessage").html(message);
                    });
            }
        }

        function isValidBdMobileNumber(phone) {
            const isEbitansUser = @json(isset(auth()->user()->register_from) && auth()->user()->register_from == "ebitans.com.bd");
            const cleanedPhone = normalizePhoneNumber(phone);

            if (!isEbitansUser) {
                // Basic international phone validation (at least 5 digits)
                return /^[+]?[0-9\s-]{5,}$/.test(cleanedPhone.trim());
            }

            // Strict Bangladeshi validation for ebitans users
            const bdMobileRegex = /^(?:\+?88)?01[3-9]\d{8}$/;
            return bdMobileRegex.test(cleanedPhone.trim());
        }

    });

    // Function to enable "Continue to next step" button
    function enableContinueButton() {
        $('#step1b button.next-step').prop('disabled', false);
    }

    // Function to disable "Continue to next step" button
    function disableContinueButton() {
        $('#step1b button.next-step').prop('disabled', true);
    }

    // End store name validation with message

    function checkDomain() {
        var url = "/checkurlstore";
        var name = $('#storename').val();
        name = name.trim().replace(/[^a-zA-Z0-9 ]/g, '');

        let length = name.length;

        if (name != "" && length > 0) {
            $('.urlcheck').hide();
            $('.urltext').hide();
            $('#createbutton').hide();
            $("#type").hide();
            $('#step2b').hide();

            $.get(url, {
                name: name
            }, function (data) {
                $(".urlcheck").empty();
                if (data['status'] == 1) {
                    $(".urlcheck").append(
                        "<p class='text-danger mt-2'>Your Domain <span style='font-weight:bold'> " +
                        data['url'] + " </span> Not Available.</p>").fadeIn(500);
                    $('.urltext').show();
                    $('#type').hide();
                    $('#step2b').hide();
                } else {
                    $(".urlcheck").append(
                        "<p class='text-success mt-2'>Your Domain <span style='font-weight:bold'> " +
                        data['url'] + " </span> is Available.</p>").fadeIn(500);
                    $('.urltext').show();
                    $('#type').show();

                    $('#step2b').show();
                }
            });
        } else {
            $('.urlcheck').hide();
            $('.urltext').hide();
            $('#createbutton').hide();
            $("#type").hide();
            $('#step2b').hide();
        }
    }

    $(document).ready(function () {
        $('.urlcheck').hide();
        $('.urltext').hide();
        $('#createbutton').hide();
        $('#type').hide();
        $('#storename').on('keyup', checkDomain);
        $('select').on('change', function () {
            const val = this.value;
            if (val == 'null') {
                $('#createbutton').hide();
            } else {
                $('#createbutton').show();
            }
        });
    });
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
    toastr.error("{{ session('error') }}");
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
</script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $(document).ready(function () {
        //use event delegation
        $('#createstore').hide();
        $('#back').hide();
        $(document).on('click', '#create', function () {
            $('#storelist').hide();
            $('#createstore').show();
            $('#create').hide();
            $('#back').show();
        });
        $(document).on('click', '#back', function () {
            $('#storelist').show();
            $('#createstore').hide();
            $('#create').show();
            $('#back').hide();
        });

        const storeCount = '{{ count($store) }}';

        if (storeCount == 0) {
            $('#create').hide();
            $('#storelist').hide();
            $('#createstore').show();
            $('#back').show();
        } else {
            $('#create').show();
            $('#storelist').show();
            $('#createstore').hide();
            $('#back').hide();
        }

    });
</script>

<script>
    $('#step1b').hide();
    $('#step2b').hide();
    $('#sb').hide();

    $(document).ready(function () {
        $("#storeNames").keypress(function () {
            if ($("#storeNames").val() && $("#storeType").val()) {
                $('#step1b').show();
            } else {
                $('#step1b').hide();
            }
        });
    });

    $(document).ready(function () {
        $("#storename").keypress(function () {
            if ($("#storename").val() && $("#storeType").val()) {
                $('#step2b').show();
            }
        });
    });

    function StoreType() {
        if ($("#storeNames").val() && $("#storeType").val()) {
            $('#step1b').show();
        } else {
            $('#step1b').hide();
        }
    }

    function selectDiv(vl) {
        document.getElementById("selectedDiv1").style.backgroundColor = "#FBB5A7";
        document.getElementById("selectedDiv2").style.backgroundColor = "#FBB5A7";
        document.getElementById("selectedDiv3").style.backgroundColor = "#FBB5A7";
        document.getElementById("selectedDiv4").style.backgroundColor = "#FBB5A7";

        document.getElementById("selectedDiv1").style.color = "black";
        document.getElementById("selectedDiv2").style.color = "black";
        document.getElementById("selectedDiv3").style.color = "black";
        document.getElementById("selectedDiv4").style.color = "black";

        document.getElementById("selectedDiv" + vl).style.backgroundColor = "#F1593A";
        document.getElementById("selectedDiv" + vl).style.color = "white";
        $('#purpose').val($("#selectedDiv" + vl).html());
        $('#sb').show();

    }
</script>


<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="{{ asset('admin/assets/js/material-dashboard.min.js?v=3.0.0') }}"></script>
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
        if ((screen.width) < 769) {
            $("#mydiv").show();
        }

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
    // ------------step-wizard-------------
    $(document).ready(function () {
        $('.nav-tabs > li a[title]').tooltip();

        //Wizard
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

            var target = $(e.target);

            if (target.parent().hasClass('disabled')) {
                return false;
            }
        });

        $(".next-step").click(function (e) {

            var active = $('.wizard .nav-tabs li.active');
            active.next().removeClass('disabled');
            nextTab(active);

        });
        $(".prev-step").click(function (e) {

            var active = $('.wizard .nav-tabs li.active');
            prevTab(active);

        });
    });

    function nextTab(elem) {
        $(elem).next().find('a[data-toggle="tab"]').click();
    }

    function prevTab(elem) {
        $(elem).prev().find('a[data-toggle="tab"]').click();
    }


    $('.nav-tabs').on('click', 'li', function () {
        $('.nav-tabs li.active').removeClass('active');
        $(this).addClass('active');
    });
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

</body>

</html>
