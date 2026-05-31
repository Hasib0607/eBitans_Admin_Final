<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11209759977"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'AW-11209759977');
    </script>


    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('fav-icon.png')}}">
    <link rel="icon" type="image/png" href="{{ asset('fav-icon.png') }}">
    <title>
        eBitans
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700"/>
    <!-- Nucleo Icons -->
    <link href="{{asset('admin/assets/css/nucleo-icons.css')}}" rel="stylesheet"/>
    <link href="{{asset('admin/assets/css/nucleo-svg.css')}}" rel="stylesheet"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rampart+One&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    {{--    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{asset('admin/assets/css/material-dashboard.css?v=3.0.0')}}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <style>
        .error-text {
            color: red !important;
        }

        .input-group.input-group-outline .form-label + .form-control, .input-group.input-group-outline .form-label + .form-control {
            border-color: gray !important;
            border-top-color: gray;
            border-top-color: transparent !important;
            box-shadow: inset 1px 0 transparent, inset -1px 0 transparent, inset 0 -1px transparent;
        }

        .input-group.input-group-outline .form-label::before, .input-group.input-group-outline .form-label::after {
            content: "";
            border-top: gray solid 1px;
            border-top-color: gray;
            border-top-width: 1px;
            border-top-color: gray;
            pointer-events: none;
            margin-top: 0.375rem;
            box-sizing: border-box;
            display: block;
            height: 0.5rem;
            width: 0.625rem;
            border-width: 1px 0 0;
            border-color: transparent;
            border-top-color: transparent;
        }

        .input-group.input-group-outline .form-label::before {
            content: "";
            margin-right: 4px;
            border-left: solid 1px transparent;
            border-left-color: transparent;
            border-left-width: 1px;
            border-radius: 4px 0;
        }

        .input-group.input-group-outline .form-label::before,
        .input-group.input-group-outline .form-label::after,
        .input-group.input-group-outline .form-label::before,
        .input-group.input-group-outline .form-label::after {

            border-top-color: gray;
            box-shadow: inset 0 1px gray;

        }

        .input-group.input-group-outline .form-label::before,
        .input-group.input-group-outline .form-label::after,
        .input-group.input-group-outline .form-label::before,
        .input-group.input-group-outline .form-label::after {
            opacity: 1;
        }

        .input-group.input-group-outline .form-label::before,
        .input-group.input-group-outline .form-label::after {
            content: "";
            border-top: solid 1px;
            border-top-color: currentcolor;
            border-top-width: 1px;
            border-top-color: #d2d6da;
            pointer-events: none;
            margin-top: 0.375rem;
            box-sizing: border-box;
            display: block;
            height: 0.5rem;
            width: 0.625rem;
            border-width: 1px 0 0;
            border-color: transparent;
            border-top-color: transparent;
        }

        .input-group.input-group-outline .form-label::after {
            content: "";
            flex-grow: 1;
            margin-left: 4px;
            border-right: solid 1px transparent;
            border-right-color: transparent;
            border-right-width: 1px;
            border-radius: 0 5px;
        }

        .input-group.input-group-outline .form-label,
        .input-group.input-group-outline .form-label {
            font-size: 0.6875rem !important;
            color: gray;
            line-height: 1.25 !important;
        }

        .hide-password {
            display: none
        }

        input:-internal-autofill-selected {
            appearance: menulist-button;
            background-image: none !important;
            background-color: transparent !important;
            color: fieldtext !important;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:focus {
            transition: background-color 600000s 0s, color 600000s 0s;
        }

        input[data-autocompleted] {
            background-color: transparent !important;
        }

        .captchar {
            font-family: 'Rampart One', cursive;
            color: black;
            letter-spacing: 10px;
        }

        .emailStatusMsg {
            font-size: 12px;
            padding: 0 5px;
            margin-bottom: 20px;
            margin-top: -10px;
            color: red;
        }

        p.text-danger {
            margin-top: -14px;
            font-size: 14px;
        }
    </style>

</head>


<body class="">
<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav
                class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
                <div class="container-fluid ps-2 pe-0">
                    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="https://admin.ebitans.com/">
                        <img src="{{asset('logo.png')}}" height="25px" width="100px" alt="">
                    </a>
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                            aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navigation">
                        <ul class="navbar-nav" style="margin-left: auto !important">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="/">
                                    <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-2" href="{{ route('login') }}">
                                    <i class="fas fa-key opacity-6 text-dark me-1"></i>
                                    Sign In
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row">
                    <div
                        class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
                        <div
                            class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center"
                            style="background-image: url('{{asset('admin/assets/img/illustrations/eBitans-Registration.jpg')}}'); background-size: cover;">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                        <div class="card card-plain">
                            <div class="card-header">
                                <h4 class="font-weight-bolder">Sign Up</h4>
                                <p class="mb-0">Enter your email and password to register</p>
                            </div>
                            <div class="card-body">
                                <x-auth-session-status class="mb-4" :status="session('status')"/>
                                <form class="text-start" action="{{ route('register') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="merchant">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label"
                                               style="width:100%;height:100%;font-size:0.6875rem !important;display:flex;line-height:1.25 !important;background-color:#fff !important">Name</label>
                                        <input name="name" value="{{old('name')}}" type="text" class="form-control">
                                    </div>
                                    @error('name')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label"
                                               style="width:100%;height:100%;font-size:0.6875rem !important;display:flex;line-height:1.25 !important;background-color:#fff !important">Email/Phone</label>

                                        <input type="text" class="form-control" id="email_or_phone"
                                               name="email_or_phone"
                                               autocomplete="on"
                                               oninput="checkEmailOrPhone()"
                                               maxlength="255" onfocus="focused(this)" onfocusout="defocused(this)"
                                               value="{{old('email_or_phone')}}" required>
                                    </div>
                                    <div id="emailOrPhoneError" class="emailStatusMsg" role="alert"></div>
                                    @if ($errors->has('email_or_phone'))
                                        <p class="text-danger" role="alert">{{ $errors->first('email_or_phone') }}</p>
                                    @endif

                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label"
                                               style="width:100%;height:100%;font-size:0.6875rem !important;display:flex;line-height:1.25 !important;background-color:#fff !important">Password</label>
                                        <input name="password" type="password" id="upassword" class="form-control"
                                               style="border-top-right-radius: 0.375rem !important;border-bottom-right-radius: 0.375rem !important;">
                                        <input type="checkbox" id="show-password"
                                               style="display:none;position:absolute">
                                        <label for="show-password"
                                               style="position:absolute;right:10px;top:10px;cursor:pointer;z-index:99999999"><i
                                                class="fa fa-eye" aria-hidden="true"></i>
                                        </label>
                                    </div>
                                    @error('password')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror

                                    <input placeholder="Optional" name="referral" type="hidden" class="form-control"
                                           value="{{ $referral ?? '' }}" readonly>

                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label"
                                               style="width:100%;height:100%;font-size:0.6875rem !important;display:flex;line-height:1.25 !important;background-color:#fff !important">
                                            User type
                                        </label>
                                        <select class="form-control" name="user_type" id="user_type">
                                            <option
                                                value="admin" @if(old('user_type') == "admin")
                                                {{ 'selected' }}
                                                @endif>
                                                Admin
                                            </option>
                                            <option
                                                value="affiliate" @if(old('user_type') == "affiliate")
                                                {{ 'selected' }}
                                                @endif>
                                                Affiliate
                                            </option>
                                            <option
                                                value="dropshipper" @if(old('user_type') == "dropshipper")
                                                {{ 'selected' }}
                                                @endif>
                                                Drop shipper
                                            </option>
                                        </select>
                                    </div>
                                    @error('user_type')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror

                                    <div class="input-group input-group-outline mb-3">
                                        {!! app('mathcaptcha')->reset() !!}

                                        <div class="captchar"
                                             style="width: 100%; hight:50px; background:#ff573375; text-align: center; font-size:25px;">
                                            {{ app('mathcaptcha')->label() }}
                                        </div>
                                        {!! app('mathcaptcha')->input(['class' => 'form-control input-group-outline',
                                        'id' => 'mathgroup', 'value' => '' ,'placeholder' =>'Fill up this CAPTCHA Number']) !!}

                                    </div>
                                    @error('mathcaptcha')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror

                                    <div class="form-check form-check-info text-start ps-0 d-flex">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"
                                               required style="width: 38px;margin-right: 8px;">

                                        <label class="form-check-label" for="flexCheckDefault">
                                            I have read and agree with the eBitans <a
                                                href="https://www.ebitans.com.bd/terms-and-conditions"
                                                target="_blank"
                                                class="text-dark font-weight-bolder">Terms &
                                                Conditions</a>, <a href="https://www.ebitans.com.bd/privacy-policy"
                                                                   target="_blank"
                                                                   class="text-dark font-weight-bolder">Privacy
                                                Policy</a> and <a
                                                href="https://www.ebitans.com.bd/return-and-refund-policy"
                                                target="_blank"
                                                class="text-dark font-weight-bolder">Refund Policy</a>

                                        </label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit"
                                                class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Sign Up
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-2 text-sm mx-auto">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Log
                                        In</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!--   Core JS Files   -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{asset('admin/assets/js/core/popper.min.js')}}"></script>
<script src="{{asset('admin/assets/js/core/bootstrap.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('admin/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script>
    $(document).ready(function () {
        $("#show-password").change(function () {
            $(this).prop("checked") ? $("#upassword").prop("type", "text") : $("#upassword").prop("type", "password");
        });
    });
    @if(Session::has('message'))
        toastr.options =
        {
            "closeButton": true,
            "progressBar": true
        }
    toastr.success("{{ session('message') }}");
    @endif

        @if(Session::has('error'))
        toastr.options =
        {
            "closeButton": true,
            "progressBar": true
        }
    toastr.error("{{ session('error') }}");
    @endif

        @if(Session::has('info'))
        toastr.options =
        {
            "closeButton": true,
            "progressBar": true
        }
    toastr.info("{{ session('info') }}");
    @endif

        @if(Session::has('warning'))
        toastr.options =
        {
            "closeButton": true,
            "progressBar": true
        }
    toastr.warning("{{ session('warning') }}");
    @endif
</script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    const checkEmailOrPhone = () => {
        const emailOrPhone = document.getElementById('email_or_phone').value.trim();
        const emailOrPhoneError = document.getElementById('emailOrPhoneError');

        emailOrPhoneError.textContent = ''; // Clear previous errors

        // Email regex pattern
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        // Phone regex pattern (6 to 15 digits, optional leading +)
        const phonePattern = /^\+?[0-9]{6,15}$/;

        if (!emailPattern.test(emailOrPhone) && !phonePattern.test(emailOrPhone)) {
            emailOrPhoneError.textContent = 'Please enter a valid email or phone number.';
            return;
        }
    }

</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{asset('admin/assets/js/material-dashboard.min.js?v=3.0.0')}}"></script>
</body>

</html>
