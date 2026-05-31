<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('fav-icon.png')}}">
  <link rel="icon" type="image/png" href="{{ asset('fav-icon.png') }}">
  <title>
    eBitans
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{asset('admin/assets/css/nucleo-icons.css')}}" rel="stylesheet" />
  <link href="{{asset('admin/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{asset('admin/assets/css/material-dashboard.css?v=3.0.0')}}" rel="stylesheet" />
  <style>
      .error-text{
          color:red !important;
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

  </style>
</head>

<body class="bg-gray-200">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid ps-2 pe-0">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="https://ebitans.com">
              <img src="{{ asset('logo.png') }}" height="25px" width="100px" alt="">
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
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
                  <a class="nav-link me-2" href="{{route('staff.login')}}">
                    <i class="fa fa-money opacity-6 text-dark me-1"></i>
                    Staff Login
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ route('register') }}">
                    <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                    Sign Up
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
    <div class="page-header align-items-start min-vh-100" style="background-image: url('{{URL::to('/')}}/assets/images/ebitans-login.jpg');">
      <span class="mask bg-gradient-dark opacity-0"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 id="logintop"  class="text-white font-weight-bolder text-center mt-2 mb-0">Log In</h4>
                  <h4 id="forgettop"  class="text-white font-weight-bolder text-center mt-2 mb-0">Forget Password</h4>
                  <div class="row mt-3">
                    <div class="col-2 text-center ms-auto" id="facebook">
                      <a class="btn btn-link px-3" href="https://www.facebook.com/wavebox.net">
                        <i class="fa fa-facebook text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center me-auto" id="google">
                      <a class="btn btn-link px-3" href="https://www.instagram.com/ebitans0607/">
                        <i class="fa fa-instagram text-white text-lg"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body" id="login">
                          <x-auth-session-status class="mb-4" :status="session('status')" />

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4 error-text" :errors="$errors" />
                <form class="text-start" action="{{ route('login') }}" method="POST">
                   @csrf
                  <!--<div class="input-group input-group-outline my-3">-->
                  <!--  <label class="form-label">Phone</label>-->
                  <!--  <input name="phone" type="number" class="form-control">-->
                  <!--</div>-->
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label" style="width:100%;height:100%;font-size:0.6875rem !important;display:flex;line-height:1.25 !important;background-color:#fff !important">Phone</label>
                    <input type="number" name="phone" class="form-control"  autocomplete="on" onfocus="focused(this)" onfocusout="defocused(this)" id="phonesss">
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label" style="width:100%;height:100%;font-size:0.6875rem !important;display:flex;line-height:1.25 !important;background-color:#fff !important">Password</label>
                    <input name="password" type="password" id="upassword"  autocomplete="on" onfocus="focused(this)" onfocusout="focused(this)" class="form-control" style="border-top-right-radius: 0.375rem !important;border-bottom-right-radius: 0.375rem !important;">
                    <input type="checkbox" id="show-password" style="display:none;position:absolute">
                        <label for="show-password" style="position:absolute;right:10px;top:10px;cursor:pointer;z-index:99999999"><i class="fa fa-eye" aria-hidden="true"></i>
</label>
                  </div>

                  <div class="form-check form-switch d-flex align-items-center mb-3" style="justify-content:space-between">
                      <div style="display:flex;align-items:center;">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label mb-0 ms-2" for="rememberMe">Remember me</label>
                    </div>
                    <a href="javascript:void(0)" id="forgetpass" style="font-size:12px;">Forget Password?</a>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2" id="loging">Log in</button>
                    <button class="buttonload btn bg-gradient-primary w-100 my-4 mb-2">
                      <i class="fa fa-spinner fa-spin"></i>&nbsp;Loading
                    </button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Sign up</a>
                  </p>
                </form>
              </div>
              <div class="card-body" id="forget">
                          <x-auth-session-status class="mb-4" :status="session('status')" />

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4 error-text" :errors="$errors" />
                <form role="form" class="text-start" action="{{ route('sendotpfp') }}" method="POST">
                    @csrf
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label" style="width:100%;height:100%;font-size:0.6875rem !important;display:flex;line-height:1.25 !important;background-color:#fff !important">Phone</label>
                    <input name="phonenumber" type="number" class="form-control">
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Send OTP</button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    Back to login?
                    <a href="Javascript:void(0)" id="backlogin" class="text-primary text-gradient font-weight-bold">Login</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100 align-items">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
              <div class="copyright text-center text-sm text-black text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script> All Rights Received |
                Developed By
                <a href="https://www.wavebox.net" class="font-weight-bold text-black" target="_blank">WAVE BOX</a>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
      <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="{{ asset('admin/assets/js/core/popper.min.js')}}"></script>
  <script src="{{ asset('admin/assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{ asset('admin/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{ asset('admin/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script>
  $(".buttonload").hide();
  $("#loging").on('click',function(){
     $("#loging").hide();
     $(".buttonload").show();
  });
  $(document).ready(function() {
        $("#show-password").change(function(){
            $(this).prop("checked") ?  $("#upassword").prop("type", "text") : $("#upassword").prop("type", "password");
        });
    });
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
      <script>
        $(document).ready(function(){
          //use event delegation
          $('#forgettop').hide();
          $('#forget').hide();
          $(document).on('click','#forgetpass',function(){
              $('#logintop').hide();
              $('#forgettop').show();
              $('#login').hide();
              $('#forget').show();
          });
          $(document).on('click','#backlogin',function(){
              $('#logintop').show();
              $('#forgettop').hide();
              $('#login').show();
              $('#forget').hide();
              $('#phonesss').val("");
          });
        });
    </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('admin/assets/js/material-dashboard.min.js?v=3.0.0')}}"></script>
</body>

</html>
