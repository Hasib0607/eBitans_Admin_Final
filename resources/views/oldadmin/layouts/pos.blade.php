<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('fav-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('fav-icon.png') }}">
  <title>
    eBitans
  </title>
  
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{asset('admin/assets/css/nucleo-icons.css')}}" rel="stylesheet" />
  <link href="{{asset('admin/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
  <link href="{{asset('admin/assets/css/style.css')}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{asset('admin/dist/css/fontawesome-iconpicker.min.css')}}" rel="stylesheet">
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <link id="pagestyle" href="{{asset('admin/assets/css/material-dashboard.css?v=3.0.0')}}" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@stack('styles')
  @yield('head')
  <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#mytextarea',
        plugins: [
          'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
          'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
          'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
        ],
        toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
          'alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
      });
    </script>

</head>

<body class="g-sidenav-show  bg-gray-200">
  <main class="main-content position-relative  border-radius-lg ">
    <!-- Navbar -->
    
    <!-- End Navbar -->
@yield('content')
    <footer class="footer py-4  ">
        <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                document.write(new Date().getFullYear())
                </script> All Rights Received |
                Developed By
                <a href="https://www.wavebox.net" class="font-weight-bold" target="_blank">Wavebox</a>
            </div>
            </div>
        </div>
        </div>
    </footer>
    </div>
    </main>
    <div class="fixed-plugin">
        <!--<a class=" text-dark position-fixed px-3 py-2">-->
        <!--<i class="material-icons py-2">settings</i>-->
        <!--</a>-->
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
            <a href="javascript:void(0)" class="switch-trigger background-color">
            <div class="badge-colors my-2 text-start">
                <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
            </div>
            </a>
            <!-- Sidenav Type -->
            <div class="mt-3">
            <h6 class="mb-0">Sidenav Type</h6>
            <p class="text-sm">Choose between 2 different sidenav types.</p>
            </div>
            <div class="d-flex">
            <button class="btn bg-gradient-dark px-3 mb-2 active" data-class="bg-gradient-dark" onclick="sidebarType(this)">Dark</button>
            <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
            <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
            </div>
            <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
            <!-- Navbar Fixed -->
            <div class="mt-3 d-flex">
            <h6 class="mb-0">Navbar Fixed</h6>
            <div class="form-check form-switch ps-0 ms-auto my-auto">
                <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
            </div>
            </div>
            <hr class="horizontal dark my-3">
            <div class="mt-2 d-flex">
            <h6 class="mb-0">Light / Dark</h6>
            <div class="form-check form-switch ps-0 ms-auto my-auto">
                <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
            </div>
            </div>
            <hr class="horizontal dark my-sm-4">
        </div>
        </div>
    </div>
    <!--   Core JS Files   -->
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
      <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    
    <!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
    <!-- <script src="{{asset('admin/assets/js/core/popper.min.js')}}"></script> -->
    <script src="{{asset('admin/assets/js/core/bootstrap.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
    <script src="{{asset('admin/dist/js/fontawesome-iconpicker.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('js')
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
            $(function() {
                $('.action-destroy').on('click', function() {
                    $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
                });
                // Live binding of buttons
                $(document).on('click', '.action-placement', function(e) {
                    $('.action-placement').removeClass('active');
                    $(this).addClass('active');
                    $('.icp-opts').data('iconpicker').updatePlacement($(this).text());
                    e.preventDefault();
                    return false;
                });
                $('.action-create').on('click', function() {
                    $('.icp-auto').iconpicker();
                    $('.icp-dd').each(function() {
                        var $this = $(this);
                        $this.iconpicker({
                            title: 'Dropdown with picker',
                            container: $(' ~ .dropdown-menu:first', $this)
                        });
                    });
                    $('.icp-glyphs').iconpicker({
                        title: 'Using glypghicons',
                        icons: ['home', 'repeat', 'search',
                            'arrow-left', 'arrow-right', 'star'],
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
                    </div><hr></div>'}
                    }).data('iconpicker').show();
                }).trigger('click');


                // Events sample:
                // This event is only triggered when the actual input value is changed
                // by user interaction
                $('.icp').on('iconpickerSelected', function(e) {
                    $('.lead .picker-target').get(0).className = 'picker-target fa-3x ' +
                            e.iconpickerInstance.options.iconBaseClass + ' ' +
                            e.iconpickerInstance.getValue(e.iconpickerValue);
                });
            });
        </script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{asset('admin/assets/js/material-dashboard.min.js?v=3.0.0')}}"></script>
    @stack('scripts')
    </body>

    </html>

