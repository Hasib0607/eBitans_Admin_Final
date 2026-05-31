<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
          integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/tour.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.12.0/js/bootstrap-tour-standalone.min.js"></script>
    <title>eBitans - Plans</title>
    <style>
        ul {
            list-style: none;
            padding-left: 0px;
        }

        ul li {
            padding: 20px 2px;
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

        .card {
            background: #f1593a;
            color: white;
            text-align: center;
        }

        .card h5 {
            font-weight: bold;
        }

        .btn-primary {
            color: #fff;
            background-color: #000;
            border-color: #000;
        }

        .btn-primary:hover {
            border-color: #000;
            background: none;
            color: black;
        }

        .card-body {
            height: 210px;
        }
    </style>
</head>

<body>
<div class="container-fluid" id="maindiv">
    <div class="row">
        <div class="col-md-6 topreduce"
             style="padding-left:70px;padding-right:0px;position:relative;padding-top:120px;">
            <div class="row">
                <div class="col-md-6 logomiddle">
                    <!--<h1>eBitans</h1>-->
                    <img src="{{ URL::to('/') }}/logo-dark.png" class="logoebit" width="150px">
                    <p style="font-size:25px;font-weight:700; padding-top:15px;">Welcome {{ Auth::user()->name }}
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <ul style="list-style:none;">
                        <li style="float:right;border:0px;">
                            <button href='#' class="zoom"
                                    onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                                    style="color:#f1593a;background-color:transparent;border:0px;font-weight:bold;margin-left:10px;"
                                    data-toggle="tooltip" data-placement="top" title="Logout"><img
                                    src="https://img.icons8.com/ios/17/000000/exit.png"/></button>
                        </li>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <li style="float:right;border:0px">
                            <p style="font-size:20px;font-weight:400;">Logout</p>
                        </li>

                    </ul>


                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <div class="card-body">
                            <form style="margin-top: 12%;height: -webkit-fill-available" class="position-relative"
                                  id="fromID1" action="{{ route('savestore') }}" method="post">
                                @csrf
                                <input type="hidden" name="purpose" value="{{ Session::get('purpose') }} ">
                                <input type="hidden" name="name" value="{{ Session::get('storeName') }} ">
                                <input type="hidden" name="domainName" value="{{ Session::get('domainName') }} ">
                                <input type="hidden" name="type" value="{{ Session::get('storeType') }} ">
                                <input type="hidden" name="package_type" value="ecw">
                                <h5 class="my-0 mb-2">ই-কমার্স ওয়েবসাইট </h5>
                                <p>শুরু করতে</p>
                                <button type="button" onclick="myFunction(1)" style="bottom: 0px;"
                                        class="btn btn-block btn-primary position-absolute">এখানে ক্লিক করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <div class="card-body">
                            <form style="margin-top: 12%;height: -webkit-fill-available" class="position-relative"
                                  id="fromID2" action="{{ route('savestore') }}" method="post">
                                @csrf
                                <input type="hidden" name="purpose" value="{{ Session::get('purpose') }} ">
                                <input type="hidden" name="name" value="{{ Session::get('storeName') }} ">
                                <input type="hidden" name="domainName" value="{{ Session::get('domainName') }} ">
                                <input type="hidden" name="type" value="{{ Session::get('storeType') }} ">
                                <input type="hidden" name="package_type" value="pos">
                                <h5 class="my-0 mb-2">পয়েন্ট অফ সেল (POS)</h5>
                                <p>শুরু করতে</p>

                                <button type="button" onclick="myFunction(2)" style="bottom: 0px;"
                                        class="btn btn-block btn-primary position-absolute">এখানে ক্লিক করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <div class="card-body">
                            <form style="margin-top: 12%;height: -webkit-fill-available" class="position-relative"
                                  id="fromID3" action="{{ route('savestore') }}" method="post">
                                @csrf
                                <input type="hidden" name="purpose" value="{{ Session::get('purpose') }} ">
                                <input type="hidden" name="name" value="{{ Session::get('storeName') }} ">
                                <input type="hidden" name="domainName" value="{{ Session::get('domainName') }} ">
                                <input type="hidden" name="type" value="{{ Session::get('storeType') }} ">
                                <input type="hidden" name="package_type" value="smm">

                                <h5 class="my-0 mb-2">সোশ্যাল মিডিয়া মার্কেটিং</h5>
                                <p>শুরু করতে</p>

                                <button type="button" onclick="myFunction(3)" style="bottom: 0px;"
                                        class="btn btn-block btn-primary position-absolute">এখানে ক্লিক করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-6" style="padding:20px 30px; padding-left:80px!important;">
            <img src="{{ URL::to('/') }}/assets/images/eBitans_store.jpg"
                 style="border-radius:10px; height:96vh !important" class="mainimg img-fluid">
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
            <!--<p class="content__container__text">-->
            <!--  Hello-->
            <!--</p>-->

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
        <!--<div class="d-flex" style="padding-left:176px;margin-bottom:30px;">-->
        <!--<img src="https://bestanimations.com/media/fireworks/671801409ba-awesome-coloful-fireworks-animated-gif-image-3.gif#.YtVK7D6IjIc.link" height="50px">-->
        <!--</div>-->
        <p style="line-height:28px;">Success, Your store is ready to go!</p>
    </div>
</div>


<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
</script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
    integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"
    integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous">
</script>
-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script>
    var tour2 = new Tour({
        debug: true,
        name: "StoreTour",
        storage: window.localStorage,
        steps: [{
            element: "#create",
            title: "Create Store",
            content: "You need to create a store for getting a new website",
            placement: "bottom",
            smartPlacement: true,
            animation: true,
            container: "body",
            backdrop: true,
            backdropContainer: 'body',
        }]
    });

    // Initialize the tour
    tour2.init();

    // Start the tour
    tour2.start();
</script>
<script>
    document.getElementById("finaltext").style.display = "none";
    var myVar;
    var myVar1;
    var myId;

    function myFunction(id) {
        myId = id;
        document.getElementById("maindiv").style.display = "none";
        document.getElementById("loader").style.display = "block";
        document.getElementById("preloaderdiv").style.display = "block";
        document.getElementById("content").style.display = "block";
        document.getElementById("finaltext").style.display = "none";

        myVar = setTimeout(showfinal, 6300);
        // alert("#fromID" + id);
    }

    function showfinal() {
        document.getElementById("maindiv").style.display = "none";
        document.getElementById("loader").style.display = "none";
        document.getElementById("preloaderdiv").style.display = "none";
        document.getElementById("content").style.display = "none";
        document.getElementById("finaltext").style.display = "block";
        // document.getElementById("createstore").style.display = "none";

        myVar1 = setTimeout(showPage, 1000);
    }


    function showPage() {
        $("#fromID" + myId).submit();
        // document.getElementById("maindiv").style.display = "none";
        // document.getElementById("preloaderdiv").style.display = "none";
        // document.getElementById("loader").style.display = "none";
        // document.getElementById("content").style.display = "none";
        // // document.getElementById("myDiv").style.display = "none";
        // document.getElementById("finaltext").style.display = "none";
        // document.getElementById("createstore").style.display = "none";
    }
</script>

<script>
    $(document).ready(function () {
        $('.urlcheck').hide();
        $('.urltext').hide();
        $('#createbutton').hide();
        $('#type').hide();
        $('#storename').on('keyup', function () {
            var url = "/checkurlstore";
            var name = $('#storename').val();
            if (name != "") {
                $.get('/checkurlstore', {
                    name: name
                }, function (data) {
                    $(".urlcheck").empty();
                    if (data['status'] == 1) {
                        $(".urlcheck").append(
                            "<p class='text-danger mt-2'>Your Domain <span style='font-weight:bold'> " +
                            data['url'] + " </span> Not Available.</p>").fadeIn(500);
                        $('.urltext').show();
                        $('#type').hide();
                    } else {
                        $(".urlcheck").append(
                            "<p class='text-success mt-2'>Your Domain <span style='font-weight:bold'> " +
                            data['url'] + " </span> is Available.</p>").fadeIn(500);
                        $('.urltext').show();
                        $('#type').show();
                    }
                });
            } else {
                $('.urlcheck').hide();
                $('.urltext').hide();
                $('#createbutton').hide();
                $("#type").hide();
            }
        });
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

    // $(document).ready(function() {
    //     //use event delegation
    //     $('#createstore').hide();
    //     $('#back').hide();
    //     $(document).on('click', '#create', function() {
    //         $('#storelist').hide();
    //         $('#createstore').show();
    //         $('#create').hide();
    //         $('#back').show();
    //     });
    //     $(document).on('click', '#back', function() {
    //         $('#storelist').show();
    //         $('#createstore').hide();
    //         $('#create').show();
    //         $('#back').hide();
    //     });
    // });
</script>
</body>

</html>
