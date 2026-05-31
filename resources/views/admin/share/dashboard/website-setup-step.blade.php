<div class="row" id="websitesettptour">

    <div class="col-md-12 mt-3">
        @if(!isset($first))
            <h3>
                    <span class="nav-link-text ms-1">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            ওয়েবসাইট সেটআপ গাইড
                        @else
                            Website setup guide
                        @endif
                    </span>
            </h3>
        @endif
        <p style="font-size:10px; color: #424242">
                <span class="nav-link-text ">
                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                        আপনার ওয়েবসাইটির পরিপূরণ লুক আনতে, অনুগ্রহ করে নিচের ধাপগুলো সম্পূর্ণ
                        করুন।
                    @else
                        To give your website a proper look, please complete the steps below
                    @endif
                </span>
        </p>
    </div>
    <div class="col-md-12 mt-3">
        <div class="progress-bar mb-3" style="height:9px;background-color:#fff !important">
            <div class="progress-step @if (isset($done) && $done >= 1) is-active @endif">
            </div>
            <div
                class="progress-step @if (isset($done) && $done > 14.2857142857) is-active @endif">
            </div>
            <div
                class="progress-step @if (isset($done) && $done > 28.5714285714) is-active @endif">
            </div>
            <div
                class="progress-step @if (isset($done) && $done > 42.8571428571) is-active @endif">
            </div>
            <div
                class="progress-step @if (isset($done) && $done > 57.1428571428) is-active @endif">
            </div>
            <div
                class="progress-step @if (isset($done) && $done > 71.4285714285) is-active @endif">
            </div>
            <div
                class="progress-step @if (isset($done) && $done > 85.7142857142) is-active @endif">
            </div>
            <div
                class="progress-step @if (isset($done) && $done > 99.999999999) is-active @endif">
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-1">
        <div class="card">
            <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                    <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="material-icons @if (isset($cats) && count($cats) > 4) text-success @else text-dark @endif  text-gradient ">widgets</i>
                            </span>
                        <div class="timeline-content">
                            <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                id="@if(isset($first)) modelmaincategory @elseif(isset($second)) cats @else maincategory @endif">
                                @if (isset($cats) && count($cats) > 4)
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ক্যাটাগরি যোগ করা হয়েছে
                                        @else
                                            Category Added
                                        @endif
                                        </span>
                                @else
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার দোকানের কোন ক্যাটাগরি নেই
                                        @else
                                            Your website has no category, please add
                                            categories
                                        @endif
                                        </span>
                                @endif
                            </h6>
                            <!--<p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22 DEC 7:20 PM</p>-->
                            @if (isset($cats) && count($cats) > 4)
                            @else
                                <a id="@if(isset($first)) modelhidecategory @elseif(isset($second)) hcats@else hidecategory @endif"
                                   href="{{ URL::to('/') }}/category"
                                   class="btn btn-primary mt-1"
                                   style="font-size: 11px;padding: 8px 12px;">
                                        <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ক্যাটাগরি যোগ করুন
                                            @else
                                                Add Category
                                            @endif
                                        </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="material-icons @if (isset($productsss) && count($productsss) > 10) text-danger text-gradient @endif">shopping_cart</i>
                            </span>
                        <div class="timeline-content">
                            <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                id="@if(isset($first)) modelmainproducts @elseif(isset($second)) prods @else mainproducts @endif">
                                @if (isset($productsss) && count($productsss) > 10)
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            পণ্য যোগ করা হয়েছে
                                        @else
                                            Product Added
                                        @endif
                                        </span>
                                @else
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ওয়েবসাইটে কোন পণ্য নেই
                                        @else
                                            Your shop has no product, please add products
                                        @endif
                                        </span>
                                @endif
                            </h6>
                            <!--<p class="text-secondary font-weight-bold text-xs mt-1 mb-0">21 DEC 11 PM</p>-->
                            @if (isset($productsss) && count($productsss) > 10)
                            @else
                                <a id="@if(isset($first)) modelhideproducts @elseif(isset($second)) hprods @else hideproducts @endif"
                                   href="{{ route('admin.addproducts') }}"
                                   class="btn btn-primary mt-1"
                                   style="font-size: 11px;padding: 8px 12px;">
                                        <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পণ্য যোগ করুন
                                            @else
                                                Add products
                                            @endif
                                        </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="material-icons
                                    @if (isset($setting->logo) &&
                                    isset($setting->website_name) &&
                                    isset($setting->short_description) &&
                                    isset($setting->phone) &&
                                    isset($setting->email) &&
                                    isset($setting->address) &&
                                    isset($setting->facebook_link) &&
                                    isset($setting->instagram_link) &&
                                    isset($setting->youtube_link) &&
                                    isset($setting->messenger_link) &&
                                    isset($setting->whatsapp_phone) &&
                                    isset($setting->tax) &&
                                    isset($setting->shipping_area_1) &&
                                    isset($setting->shipping_area_1_cost) &&
                                    isset($setting->shipping_area_2) &&
                                    isset($setting->shipping_area_2_cost) &&
                                    isset($setting->shipping_area_3) &&
                                    isset($setting->shipping_area_3_cost)) text-info
                                    @else text-dark @endif text-gradient">
                                    settings
                                </i>
                            </span>
                        <div class="timeline-content">
                            <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                id="@if(isset($first)) modelmainsetting @elseif(isset($second)) sett @else mainsetting @endif">
                                @if (isset($setting->logo) &&
                                        isset($setting->website_name) &&
                                        isset($setting->short_description) &&
                                        isset($setting->phone) &&
                                        isset($setting->email) &&
                                        isset($setting->address) &&
                                        isset($setting->facebook_link) &&
                                        isset($setting->instagram_link) &&
                                        isset($setting->youtube_link) &&
                                        isset($setting->messenger_link) &&
                                        isset($setting->whatsapp_phone) &&
                                        isset($setting->tax) &&
                                        isset($setting->shipping_area_1) &&
                                        isset($setting->shipping_area_1_cost) &&
                                        isset($setting->shipping_area_2) &&
                                        isset($setting->shipping_area_2_cost) &&
                                        isset($setting->shipping_area_3) &&
                                        isset($setting->shipping_area_3_cost))
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            তথ্য আপডেট হয়েছে
                                        @else
                                            Information Updated
                                        @endif
                                        </span>
                                @else
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ওয়েবসাইটের তথ্য আপডেট করুন
                                        @else
                                            Update your website’s information
                                        @endif
                                        </span>
                                @endif
                            </h6>
                            <!--<p class="text-secondary font-weight-bold text-xs mt-1 mb-0">21 DEC 9:34 PM</p>-->
                            @if (isset($setting->logo) &&
                                    isset($setting->website_name) &&
                                    isset($setting->short_description) &&
                                    isset($setting->phone) &&
                                    isset($setting->email) &&
                                    isset($setting->address) &&
                                    isset($setting->facebook_link) &&
                                    isset($setting->instagram_link) &&
                                    isset($setting->youtube_link) &&
                                    isset($setting->whatsapp_phone) &&
                                    isset($setting->tax) &&
                                    isset($setting->shipping_area_1) &&
                                    isset($setting->shipping_area_1_cost))
                            @else
                                <a id="@if(isset($first)) modelhidesetting @elseif(isset($second))hsett @else hidesetting @endif"
                                   href="{{ route('admin.setting') }}"
                                   class="btn btn-primary mt-1"
                                   style="font-size: 11px;padding: 8px 12px;">
                                        <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                তথ্য আপডেট করুন
                                            @else
                                                Update information
                                            @endif
                                        </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="material-icons @if ($designs->template_id == '0') text-dark @else text-warning @endif text-gradient">palette</i>
                            </span>
                        <div class="timeline-content">
                            <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                id="@if(isset($first)) modelmaintheme @elseif(isset($second))them @else maintheme @endif">
                                @if ($designs->template_id == '0')
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ওয়েবসাইটের জন্য পছন্দমতন থিম নির্বাচন
                                            করুন
                                        @else
                                            Choose the desired theme for your website
                                        @endif
                                        </span>
                                @else
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            থিম পরিবর্তিত হয়েছে
                                        @else
                                            Theme Selected
                                        @endif
                                        </span>
                                @endif
                            </h6>
                            <!--<p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Change T</p>-->
                            @if ($designs->template_id == '0')
                                <a id="@if(isset($first)) modelhidetheme @elseif(isset($second))hthem @else hidetheme @endif"
                                   href="{{ route('design.theme') }}"
                                   class="btn btn-primary mt-1"
                                   style="font-size: 11px;padding: 8px 12px;">
                                        <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                থিম নির্বাচন করুন
                                            @else
                                                Change Theme
                                            @endif
                                        </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="material-icons @if (isset($headermenu) && count($headermenu) > 0) text-warning text-gradient @endif">menu</i>
                            </span>
                        <div class="timeline-content">
                            <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                id="@if(isset($first)) modelmainmenu @elseif(isset($second))menu @else mainmenu @endif">
                                @if (isset($headermenu) && count($headermenu) > 0)
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            হেডার মেনু যোগ করা হয়েছে
                                        @else
                                            Header Menu Added
                                        @endif
                                        </span>
                                @else
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ওয়েবসাইটের জন্য হেডার মেনু নির্বাচন করুন
                                        @else
                                            Select the header menu for your website
                                        @endif
                                        </span>
                                @endif
                            </h6>
                            <!--<p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Change T</p>-->
                            @if (isset($headermenu) && count($headermenu) > 0)
                            @else
                                <a id="@if(isset($first)) modelhidemenu @elseif(isset($second)) hmenu @else hidemenu @endif"
                                   href="{{route('admin.design.homepage.common_designs', ['column'=>'header']) }}"
                                   class="btn btn-primary mt-1"
                                   style="font-size: 11px;padding: 8px 12px;">
                                        <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                হেডার মেনু নির্বাচন করুন
                                            @else
                                                Select Header Menu
                                            @endif
                                        </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="material-icons @if (isset($slidersd) && count($slidersd) > 0) text-warning text-gradient @endif">collections</i>
                            </span>
                        <div class="timeline-content">
                            <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                id="@if(isset($first)) modelmainpage @else mainpage @endif">
                                @if (isset($slidersd) && count($slidersd) > 0)
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            পেইজ যোগ করা হয়েছে
                                        @else
                                            Slider Added
                                        @endif
                                        </span>
                                @else
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ওয়েবসাইটের জন্য আরও পেইজ যোগ করুন (যেমন:
                                            এবাউট ,
                                            কন্টাক্ট ইত্যাদি)
                                        @else
                                            Add Slider For Your Website
                                        @endif
                                        </span>
                                @endif
                            </h6>
                            <!--<p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Change T</p>-->
                            @if (isset($slidersd) && count($slidersd) > 0)
                            @else
                                <a id="@if(isset($first)) modelhidepage @else hidepage @endif"
                                   href="{{ route('admin.design.homepage.slider') }}"
                                   class="btn btn-primary mt-1"
                                   style="font-size: 11px;padding: 8px 12px;">
                                        <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পেইজ যোগ করুন
                                            @else
                                                Add Slider
                                            @endif
                                        </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="material-icons @if (isset($pagesd) && count($pagesd) > 0) text-warning text-gradient @endif">view_carousel</i>
                            </span>
                        <div class="timeline-content">
                            <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                id="@if(isset($first)) modelmainpage1 @elseif(isset($second)) page @else mainpage1 @endif">
                                @if (isset($pagesd) && count($pagesd) > 0)
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            পেইজ যোগ করা হয়েছে
                                        @else
                                            More Page Added
                                        @endif
                                        </span>
                                @else
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ওয়েবসাইটের জন্য আরও পেইজ যোগ করুন (যেমন:
                                            এবাউট ,
                                            কন্টাক্ট ইত্যাদি)
                                        @else
                                            Add more pages for your website (Ex: About,
                                            Contact,
                                            etc.)
                                        @endif
                                        </span>
                                @endif
                            </h6>
                            <!--<p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Change T</p>-->
                            @if (isset($pagesd) && count($pagesd) > 0)
                            @else
                                <a id="@if(isset($first)) modelhidepage @elseif(isset($second))hpage @else hidepage @endif"
                                   href="{{ route('admin.addpage') }}"
                                   class="btn btn-primary mt-1"
                                   style="font-size: 11px;padding: 8px 12px;">
                                        <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পেইজ যোগ করুন
                                            @else
                                                Page Add
                                            @endif
                                        </span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="timeline-block">
                            <span class="timeline-step">
                                <i class="material-icons @if (isset($domain) && count($domain) > 1) text-primary @else text-dark @endif text-gradient">vpn_lock</i>
                            </span>
                        <div class="timeline-content">
                            <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                id="@if(isset($first)) modelmaindomain @elseif(isset($second))domain @else maindomain @endif">
                                @if (isset($domain) && count($domain) > 1)
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ডোমেন যোগ করা হয়েছে
                                        @else
                                            Domain Added
                                        @endif
                                        </span>
                                @else
                                    <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ওয়েবসাইটের জন্য আপনার ব্যক্তিগত ডোমেন যোগ করুন
                                        @else
                                            Add your personal domain for the website
                                        @endif
                                        </span>
                                @endif
                            </h6>
                            <!--<p class="text-secondary font-weight-bold text-xs mt-1 mb-0">17 DEC</p>-->
                            @if (isset($domain) && count($domain) > 1)
                            @else
                                <a id="@if(isset($first)) modelhidedomain @elseif(isset($second)) hdomain @else hidedomain @endif"
                                   href="{{ route('admin.domain') }}"
                                   class="btn btn-primary mt-1"
                                   style="font-size: 11px;padding: 8px 12px;">
                                        <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ডোমেন যোগ করুন
                                            @else
                                                Add Domain
                                            @endif
                                        </span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

