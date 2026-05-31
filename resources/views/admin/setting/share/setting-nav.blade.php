@php
    /*extract user_id, user_type, store_id, customer_id*/
    extract(getUserData());

    /*check is store is expire or not */
    if (isset($store_id) && $store_id != 0) {
        $store = DB::table('stores')->where('id', $store_id)->first();
        if ($store->plan_id != 'NULL') {
            if ($store->expiry_date <= Carbon\Carbon::now()) {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date <= Carbon\Carbon::now()) {
                        $exp = 1;
                    } else {
                        $exp = 0;
                    }
                } else {
                    $exp = 1;
                }
            } else {
                $exp = 0;
            }
        } else {
            if (isset($store->pos_plan_id) && $store->pos_plan_expiry_date >= Carbon\Carbon::now()) {
                $exp = 1;
            } else {
                $exp = 0;
            }
        }
    }
@endphp
<div class="container-fluid navbars"
     style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
    <div class="row new">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color:transparent;color:#fff">

                    @if (isset($exp) && $exp != '1')
                        <li class="breadcrumb-item @if(isset($website_settings)) active @endif">
                            <a
                                href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.setting') }} @endif @endif">
                                <img src="{{ URL::to('/') }}/img/icons/settings-2.png"> <br> <span
                                    class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ওয়েবসাইট
                                        সেটিংস
                                    @else
                                        Website Settings
                                    @endif
                                        </span>
                            </a>
                        </li>
                        <li class="breadcrumb-item @if(isset($connect_domain)) active @endif">
                            <a
                                href="@if (isset($exp)) @if ($exp == '1') # @else {{ route('admin.domain') }} @endif @endif">
                                <img src="{{ URL::to('/') }}/img/icons/domain-2.png"> <br> <span
                                    class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ডোমেইন সংযোগ
                                        করুন
                                    @else
                                        Connect Domain
                                    @endif
                                        </span>
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item @if(isset($user_profile)) active @endif">
                        <a href="{{ route('admin.profile') }}">
                            <img src="{{ URL::to('/') }}/img/icons/resume.png"> <br> <span
                                class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    প্রোফাইল
                                @else
                                    User Profile
                                @endif
                                    </span>
                        </a>
                    </li>
                    @if(isset($modulus_config))
                        <li class="breadcrumb-item active">
                            <a href="{{ route('admin.modulus') }}">
                                <img src="{{ URL::to('/') }}/img/icons/resume.png"> <br> <span
                                    class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        মডুলাস
                                    @else
                                        Modulus
                                    @endif
                                    </span>
                            </a>
                        </li>
                    @endif

                </ol>
            </nav>
        </div>
    </div>
</div>
