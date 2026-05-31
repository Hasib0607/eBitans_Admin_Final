<!-- Start Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
     navbar-scroll="true">
    <div class="container-fluid py-1 px-3">

        <!-- Store name -->
        <h3 class="sitename">
            @if (Auth::user()->type == 'superadmin')
                Super Admin
            @else
                <a href="http://{{ $store->url ?? '' }}" target="_blank"> {{ $store->name ?? '' }} </a>
            @endif
        </h3>

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">

            <!-- Icon -->
            <li class="nav-item d-xl-none d-flex align-items-center pe-1">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner" style="width: 27px;">
                        <i class="sidenav-toggler-line" style="height: 4px;"></i>
                        <i class="sidenav-toggler-line" style="height: 4px;"></i>
                        <i class="sidenav-toggler-line" style="height: 4px;"></i>
                    </div>
                </a>
            </li>

            <!-- Search input -->
            <div class="ms-md-auto pe-md-1 d-flex align-items-center" id="serchWab">
                @php
                    $toptools = DB::table('toptools')->get()->unique('name');
                @endphp
                <div class="input-group input-group-outline search1">
                    <input type="text" id="mySearch" placeholder="Search.."
                           style="width:100%;border-radius:5px;border:1px solid #d2d6da;background-color:transparent;height:33px;">
                    <span id="cross" style="cursor:pointer">X</span>
                    <ul id="myMenu" style="border-radius:10px;margin-top:3px;">
                        @if (isset($toptools) && count($toptools) > 0)
                            @foreach ($toptools as $tp)
                                <li>
                                    <a
                                        href="{{ URL::to('/') }}{{ $tp->url }}">{{ $tp->name }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <ul class="navbar-nav  justify-content-end">

                <!-- Change language -->
                <li class="nav-item d-flex align-items-center px-2" style="margin-right:10px;"
                    id="changelanguagetour">
                    <form action="{{ route('admin.changelang') }}" method="post" id="changelangform">
                        @csrf
                        <div class="switch">
                            <input id="language-toggle" name="langtoggle"
                                   @if (Session::has('lang') && Session::get('lang') == 'bn') checked @endif
                                   class="check-toggle check-toggle-round-flat" type="checkbox">
                            <label for="language-toggle" style="margin-bottom:0px"></label>
                            <span class="on">EN</span>
                            <span class="off">BN</span>
                        </div>
                    </form>
                </li>

                <!-- Create store -->
                @if (Auth::user()->type == 'admin')
                    <li class="nav-item d-flex align-items-center px-2" id="changestoretour">
                        <a @if (Auth::user()->type == 'staff') href="javascript:void(0)"
                           @else href="{{ route('admin.deactivestore') }}" @endif
                           class="nav-link text-body font-weight-bold px-0 tooltip"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create new Store"
                           onclick="createStoreBtn(event)" id="storeCreateBtn">
                            <img src="{{ asset('img/store.png') }}" class="zoom" width="16px">
                            <span class="tooltiptext tooltip-top">Store</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper')
                    <!-- Setting -->
                    <li class="nav-item px-1 d-flex align-items-center tooltip px-2">
                        <a href="{{ route('admin.setting') }}" class="nav-link text-body p-0"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Settings">
                            <img src="{{ asset('img/gear.png') }}" class="cursor-pointer zoom"
                                 width="18px">
                        </a>
                        <span class="tooltiptext tooltip-top">Settings</span>
                    </li>
                @endif

                {{--Socket notification start--}}
                <div id="socket-notification" class="d-flex">
                    @php
                        $userData = getUserData();
                        $user_id = (int)$userData['user_id'] ?? NULL;
                        $store_id = (int)$userData['store_id'] ?? NULL;
                        $user_type = $userData['user_type'] ?? NULL;
                        $type = 0;
                        if($user_type === "superadmin"){
                           $type = 1;
                        }
                    @endphp

                    <admin-notification
                        :socketurl='@json(env("SOCKET_URL"))'
                        :userid='@json($user_id)'
                        :usertype='@json($type)'
                        :storeid='@json($store_id)'
                    />
                </div>
                {{--Socket notification end--}}

                <!-- Visit website -->
                <li class="nav-item px-1 d-flex align-items-center px-2" id="visitwebsite">
                    @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')
                    @else
                        <a href="http://{{ $store->url ?? '' }}" target="_blank"
                           style="border: 2px dotted #ff5733; color: black!important; font-weight: bolder; padding: 0 25px!important;"
                           class="nav-link text-body p-0 tooltip" data-bs-toggle="tooltip"
                           data-bs-placement="bottom" title="Visit Website">
                            Visit Website
                        </a>
                    @endif
                </li>

            </ul>
        </div>

        <!-- Mobile menu Icon -->
        <div class="ms-md-auto pe-md-3 align-items-center mt-3" id="mobilesearchdiv"
             style="width:100%;display:none;top:0px;">
            @php
                $toptools = DB::table('toptools')->get()->unique('name');
            @endphp
            <div class="input-group input-group-outline">
                <input type="text" id="mySearch1" placeholder="Search.."
                       style="width:100%;border-radius:5px;border:2px solid #000;background-color:#fff;height:33px;">
                <span id="cross1">X</span>
                <ul id="myMenu1">
                    @if (isset($toptools) && count($toptools) > 0)
                        @foreach ($toptools as $tp)
                            <li><a href="{{ URL::to('/') }}{{ $tp->url }}">{{ $tp->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

    </div>
</nav>
<!-- End Navbar -->

@push('scripts')
    <script !src="">
        const createStoreBtn = (e) => {
            e.preventDefault();
            const url = document.getElementById("storeCreateBtn").href;

            swal.fire({
                title: 'Are you sure?',
                text: "You want to Leave this Store?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    window.location.href = url;
                }
            })
        }
    </script>
@endpush
