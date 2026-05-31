<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('fav-icon.png')}}">
  <link rel="icon" type="image/png" href="{{ asset('fav-icon.png') }}">
  <title>
    eBitans-Staff Login
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
  <link rel="stylesheet" type="text/css"
     href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <style>
      .error-text{
          color:red !important;
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
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="../pages/dashboard.html">
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
                    Admin Login
                  </a>
                </li>
                <!--<li class="nav-item">-->
                <!--  <a class="nav-link me-2" href="#">-->
                <!--    <i class="fa fa-money opacity-6 text-dark me-1"></i>-->
                <!--    Pricing-->
                <!--  </a>-->
                <!--</li>-->
                <!--<li class="nav-item">-->
                <!--  <a class="nav-link me-2" href="{{ route('register') }}">-->
                <!--    <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>-->
                <!--    Sign Up-->
                <!--  </a>-->
                <!--</li>-->
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('{{URL::to('/')}}/ebitans_login (2).jpg');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 id="logintop"  class="text-white font-weight-bolder text-center mt-2 mb-0">Staff Log In</h4>
                  <h4 id="forgettop"  class="text-white font-weight-bolder text-center mt-2 mb-0">Forget Password</h4>
                  <div class="row mt-3">
                    <div class="col-2 text-center ms-auto" id="facebook">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-facebook text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center me-auto" id="google">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-google text-white text-lg"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body" id="login">
                          <x-auth-session-status class="mb-4" :status="session('status')" />

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4 error-text" :errors="$errors" />
                <form role="form" class="text-start" action="{{ route('staff.submitlogin') }}" method="POST">
                    @csrf
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Username</label>
                    <input name="username" type="text" class="form-control">

                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Password</label>
                    <input name="password" type="password" class="form-control">

                  </div>
                  <div class="form-check form-switch d-flex align-items-center mb-3" style="justify-content:space-between">
                      <div style="display:flex;align-items:center;">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label mb-0 ms-2" for="rememberMe">Remember me</label>
                    </div>
                    <!--<a href="javascript:void(0)" id="forgetpass" style="font-size:12px;">Forget Password?</a>-->
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Log in</button>
                  </div>
                  <!--<p class="mt-4 text-sm text-center">-->
                  <!--  Don't have an account?-->
                  <!--  <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Sign up</a>-->
                  <!--</p>-->
                </form>
              </div>
              <div class="card-body" id="forget">
                          <x-auth-session-status class="mb-4" :status="session('status')" />

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-4 error-text" :errors="$errors" />
                <form role="form" class="text-start" action="{{ route('sendotpfp') }}" method="POST">
                    @csrf
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Phone</label>
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
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script> All Rights Received |
                Developed By
                <a href="https://www.wavebox.net" class="font-weight-bold text-white" target="_blank">WAVE BOX</a>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js" integrity="sha512-chZc2Mx8B1GzGSNMfJRH63jW7uYZXzX0a/UlWRrTvl4kxxYqUHNMtyTTA5IDQ7gTl4ATLoXlZthsialW3muS0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>-->
  <script src="{{ asset('admin/assets/js/core/popper.min.js')}}"></script>
  <script src="{{ asset('admin/assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{ asset('admin/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{ asset('admin/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
  <script>
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
          });
        });
    </script>
    <script>
  @if(Session::has('message'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.success("{{ session('message') }}");
  @endif

  @if(Session::has('error'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.error("{{ session('error') }}");
  @endif

  @if(Session::has('info'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.info("{{ session('info') }}");
  @endif

  @if(Session::has('warning'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.warning("{{ session('warning') }}");
  @endif
</script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('admin/assets/js/material-dashboard.min.js?v=3.0.0')}}"></script>
</body>

</html>
