@extends('admin.layouts.main')
@push('styles')
    <style>
        .table td {
            text-align: left;
        }

        .table> :not(caption)>*>* {
            border-bottom-width: 0px !important;
        }

        #unuse1 {
            display: none;
        }

        #unuse {
            display: block;
        }

        @media only screen and (max-width:500px) {
            #unuse1 {
                display: block;
            }

            #unuse {
                display: none;
            }
        }

        .progress-bar {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(20px, 1fr));
        }

        .progress-step {
            text-align: center;
            position: relative;
            background-color: gray;
            margin-left: 2px;
            margin-right: 2px;
        }

        .progress-step:before,
        .progress-step:after {
            background-color: #c0a359;
            content: "";
            height: 2px;
            position: absolute;
            z-index: -1;
            top: 20px;
        }

        .progress-step:after {
            left: 50%;
            width: 100%;
        }

        .progress-bar .is-active {
            background-color: #f1593a !important;
        }

        .progress-step:last-of-type.is-active:after {
            background-color: #c0a359;
        }

        @media screen and (min-width: 640px) {
            .progress-step:first-of-type:before {
                right: 50%;
                width: 50%;
            }
        }

        @media screen and (max-width: 640px) {

            .progress-step:first-of-type:before,
            .progress-step:last-of-type:after {
                background-color: white !important;
            }
        }

        .progress-step:last-of-type:after {
            left: 50%;
            width: 50%;
        }

        .progress-step .step-count {
            background-color: #c0a359;
            height: 30px;
            width: 30px;
            margin: 0 auto;
            border-radius: 50%;
            color: white;
            line-height: 30px;
            z-index: 100;
            border: 7px solid white;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .progress-step .step-count:before {
            counter-increment: step-count;
            content: counter(step-count);
        }

        .progress-step.is-active .step-description {
            font-weight: 500;
        }

        .progress-step.is-active:after {
            background-color: #dad6ce;
        }

        .progress-step.is-active~.progress-step .step-count {
            background-color: #dad6ce;
        }

        .progress-step.is-active~.progress-step:after {
            background-color: #dad6ce;
        }

        .step-description {
            font-size: 0.8rem;
        }

        /*# sourceMappingURL=style.css.map */
        /*.div {*/
        /*  border: 2px solid #f1593a;*/
        /*  padding: 2px; */
        /*  width: 560px;*/
        /*  resize: both;*/
        /*  overflow: auto;*/
        /*}*/
        #mydiv {
            position: absolute;
            resize: both;
            /*width:560px;*/
            z-index: 99999999999999;
            top: 10%;
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
    </style>
@endpush
@section('content')
    @php
        $cats = DB::table('categories')->where('store_id', $store_id)->where('parent', 0)->get();
        $productsss = DB::table('products')->where('store_id', $store_id)->get();
        $setting = DB::table('headersettings')->where('store_id', $store_id)->first();
        $designs = DB::table('designs')->where('store_id', $store_id)->first();
        $headermenu = DB::table('menus')->where('store_id', $store_id)->get();
        $slidersd = DB::table('sliders')->where('store_id', $store_id)->get();
        $pagesd = DB::table('pages')->where('store_id', $store_id)->get();
        $domain = DB::table('domains')->where('store_id', $store_id)->get();
    @endphp

    @php
        $done1 = 0;
        if (isset($cats) && count($cats) > 4) {
            $done1 = $done1 + 14.2857142857;
        }

        if (isset($productsss) && count($productsss) > 10) {
            $done1 = $done1 + 14.2857142857;
        }

        if (isset($setting->short_description) && isset($setting->phone) && isset($setting->email) && isset($setting->address) && isset($setting->facebook_link) && isset($setting->instagram_link) && isset($setting->youtube_link) && isset($setting->messenger_link) && isset($setting->whatsapp_phone) && isset($setting->tax) && isset($setting->shipping_area_1) && isset($setting->shipping_area_1_cost) && isset($setting->shipping_area_2) && isset($setting->shipping_area_2_cost) && isset($setting->shipping_area_3) && isset($setting->shipping_area_3_cost)) {
            $done1 = $done1 + 14.2857142857;
        }

        if (isset($headermenu) && count($headermenu) > 0) {
            $done1 = $done1 + 14.2857142857;
        }

        if (isset($slidersd) && count($slidersd) > 0) {
            $done1 = $done1 + 14.2857142857;
        }

        if (isset($pagesd) && count($pagesd) > 0) {
            $done1 = $done1 + 14.2857142857;
        }

        if (isset($domain) && count($domain) > 1) {
            $done1 = $done1 + 14.2857142857;
        }

        if ($designs->template_id != '0') {
            $done1 = $done1 + 14.2857142857;
        }
    @endphp

    @if (isset($cats) &&
            count($cats) > 4 &&
            isset($productsss) &&
            count($productsss) > 10 &&
            isset($setting->logo) &&
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
            isset($setting->shipping_area_3_cost) &&
            isset($headermenu) &&
            count($headermenu) > 0 &&
            isset($pagesd) &&
            count($pagesd) > 0 &&
            isset($domain) &&
            count($domain) > 1)
    @else
        <div class="modal fade" id="myModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span class="nav-link-text ms-1">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ওয়েবসাইট সেটআপ গাইড
                                @else
                                    Website setup guide
                                @endif
                            </span></h4>
                        <button type="button" data-bs-dismiss="modal"
                            style="background-color:transparent;border-color:transparent"><img
                                src="https://img.icons8.com/material-rounded/24/000000/multiply--v1.png" /></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row" id="websitesettptour">
                            <div class="col-md-12 mt-3">
                                <p style="font:10px; color: #424242">
                                    <span class="nav-link-text ">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ওয়েবসাইটির পরিপূরণ লুক আনতে, অনুগ্রহ করে নিচের ধাপগুলো সম্পূর্ণ করুন।
                                        @else
                                            To give your website a proper look, please complete the steps below
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="progress-bar mb-3" style="height:9px;background-color:#fff !important">
                                    <div class="progress-step @if (isset($done1) && $done1 >= 1) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 14.2857142857) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 28.5714285714) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 42.8571428571) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 57.1428571428) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 71.4285714285) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 85.7142857142) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 99.999999999) is-active @endif">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-1">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="timeline timeline-one-side">
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($cats) && count($cats) > 4) text-success @else text-dark @endif  text-gradient ">widgets</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="maincategory">
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
                                                                    Your website has no category, please add categories
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    <!--<p class="text-secondary font-weight-bold text-xs mt-1 mb-0">22 DEC 7:20 PM</p>-->
                                                    @if (isset($cats) && count($cats) > 4)
                                                    @else
                                                        <a id="hidecategory" href="{{ URL::to('/') }}/category"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;">
                                                            <span class="nav-link-text ms-1">
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
                                                    <i
                                                        class="material-icons @if (isset($productsss) && count($productsss) > 10) text-danger text-gradient @endif">shopping_cart</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainproducts">
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
                                                    @if (isset($productsss) && count($productsss) > 10)
                                                    @else
                                                        <a id="hideproducts" href="{{ URL::to('/') }}/products/create"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    পণ্য যোগ করুন
                                                                @else
                                                                    Add products
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($setting->logo) &&
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
                                                                isset($setting->shipping_area_3_cost)) text-info @else text-dark @endif text-gradient">settings</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainsetting">
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
                                                            isset($setting->messenger_link) &&
                                                            isset($setting->whatsapp_phone) &&
                                                            isset($setting->tax) &&
                                                            isset($setting->shipping_area_1) &&
                                                            isset($setting->shipping_area_1_cost) &&
                                                            isset($setting->shipping_area_2) &&
                                                            isset($setting->shipping_area_2_cost) &&
                                                            isset($setting->shipping_area_3) &&
                                                            isset($setting->shipping_area_3_cost))
                                                    @else
                                                        <a id="hidesetting" href="{{ URL::to('/') }}/settings"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    তথ্য আপডেট করুন
                                                                @else
                                                                    Update information
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if ($designs->template_id == '0') text-dark @else text-warning @endif text-gradient">palette</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="maintheme">
                                                        @if ($designs->template_id == '0')
                                                            <span class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    আপনার ওয়েবসাইটের জন্য পছন্দমতন থিম নির্বাচন করুন
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
                                                        <a id="hidetheme" href="{{ URL::to('/') }}/design/theme"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    থিম নির্বাচন করুন
                                                                @else
                                                                    Change Theme
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($headermenu) && count($headermenu) > 0) text-warning text-gradient @endif">menu</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainmenu">
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
                                                    @if (isset($headermenu) && count($headermenu) > 0)
                                                    @else
                                                        <a id="hidemenu" href="{{ URL::to('/') }}/design/header"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    হেডার মেনু নির্বাচন করুন
                                                                @else
                                                                    Select Header Menu
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($slidersd) && count($slidersd) > 0) text-warning text-gradient @endif">collections</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainpage">
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
                                                                    আপনার ওয়েবসাইটের জন্য আরও পেইজ যোগ করুন (যেমন: এবাউট ,
                                                                    কন্টাক্ট ইত্যাদি)
                                                                @else
                                                                    Add Slider For Your Website
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    @if (isset($slidersd) && count($slidersd) > 0)
                                                    @else
                                                        <a id="hidepage" href="{{ URL::to('/') }}/design/slider"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    পেইজ যোগ করুন
                                                                @else
                                                                    Add Slider
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($pagesd) && count($pagesd) > 0) text-warning text-gradient @endif">view_carousel</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainpage">
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
                                                                    আপনার ওয়েবসাইটের জন্য আরও পেইজ যোগ করুন (যেমন: এবাউট ,
                                                                    কন্টাক্ট ইত্যাদি)
                                                                @else
                                                                    Add more pages for your website (Ex: About, Contact,
                                                                    etc.)
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    @if (isset($pagesd) && count($pagesd) > 0)
                                                    @else
                                                        <a id="hidepage" href="{{ URL::to('/') }}/pages/create"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    পেইজ যোগ করুন
                                                                @else
                                                                    Page Add
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($domain) && count($domain) > 1) text-primary @else text-dark @endif text-gradient">vpn_lock</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="maindomain">
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
                                                    @if (isset($domain) && count($domain) > 1)
                                                    @else
                                                        <a id="hidedomain" href="{{ URL::to('/') }}/domain"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    ডোমেন যোগ করুন
                                                                @else
                                                                    Add Domain
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="container-fluid py-4">
            @php
                $storesss = DB::table('stores')->where('id', $store_id)->first();
            @endphp

            @if ($storesss->plan_id == null && $storesss->pos_plan_id == null)
            @else
                <div class="col-lg-4 col-md-6 mt-3" id="unuse1">
                    @php
                        $cats = DB::table('categories')->where('store_id', $store_id)->where('parent', 0)->get();
                        $productsss = DB::table('products')->where('store_id', $store_id)->get();
                        $setting = DB::table('headersettings')->where('store_id', $store_id)->first();
                        $designs = DB::table('designs')->where('store_id', $store_id)->first();
                        $headermenu = DB::table('menus')->where('store_id', $store_id)->get();
                        $slidersd = DB::table('sliders')->where('store_id', $store_id)->get();
                        $pagesd = DB::table('pages')->where('store_id', $store_id)->get();
                        $domain = DB::table('domains')->where('store_id', $store_id)->get();

                        $done = 0;
                        if (isset($cats) && count($cats) > 4) {
                            $done = $done + 14.2857142857;
                        }
                        if (isset($productsss) && count($productsss) > 10) {
                            $done = $done + 14.2857142857;
                        }
                        if (isset($setting->short_description) && isset($setting->phone) && isset($setting->email) && isset($setting->address) && isset($setting->facebook_link) && isset($setting->instagram_link) && isset($setting->youtube_link) && isset($setting->messenger_link) && isset($setting->whatsapp_phone) && isset($setting->tax) && isset($setting->shipping_area_1) && isset($setting->shipping_area_1_cost) && isset($setting->shipping_area_2) && isset($setting->shipping_area_2_cost) && isset($setting->shipping_area_3) && isset($setting->shipping_area_3_cost)) {
                            $done = $done + 14.2857142857;
                        }
                        if (isset($headermenu) && count($headermenu) > 0) {
                            $done = $done + 14.2857142857;
                        }
                        if (isset($slidersd) && count($slidersd) > 0) {
                            $done = $done + 14.2857142857;
                        }
                        if (isset($pagesd) && count($pagesd) > 0) {
                            $done = $done + 14.2857142857;
                        }
                        if (isset($domain) && count($domain) > 1) {
                            $done = $done + 14.2857142857;
                        }
                        if ($designs->template_id != '0') {
                            $done = $done + 14.2857142857;
                        }
                    @endphp

                    @if (isset($cats) &&
                            count($cats) > 4 &&
                            isset($productsss) &&
                            count($productsss) > 10 &&
                            isset($setting->logo) &&
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
                            isset($setting->shipping_area_3_cost) &&
                            isset($headermenu) &&
                            count($headermenu) > 0 &&
                            isset($pagesd) &&
                            count($pagesd) > 0 &&
                            isset($domain) &&
                            count($domain) > 1)
                        <div class="ff" style="height:82px">
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h3><span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ওয়েবসাইট সেটআপ গাইড
                                        @else
                                            Website setup guide
                                        @endif
                                    </span></h3>
                                <p style="font:10px; color: #424242"><span class="nav-link-text ">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ওয়েবসাইটির পরিপূরণ লুক আনতে, অনুগ্রহ করে নিচের ধাপগুলো সম্পূর্ণ করুন।
                                        @else
                                            To give your website a proper look, please complete the steps below
                                        @endif
                                    </span></p>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="progress-bar mb-3" style="height:9px;background-color:#fff !important">
                                    <div class="progress-step @if (isset($done) && $done >= 1) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done) && $done > 14.2857142857) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done) && $done > 28.5714285714) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done) && $done > 42.8571428571) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done) && $done > 57.1428571428) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done) && $done > 71.4285714285) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done) && $done > 85.7142857142) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done) && $done > 99.999999999) is-active @endif">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12 mt-1">
                                <div class="card" id="websitesettuptour">
                                    <div class="card-body p-3">
                                        <div class="timeline timeline-one-side">
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($cats) && count($cats) > 4) text-success @else text-dark @endif  text-gradient ">widgets</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="cats">
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
                                                                    Your website has no category, please add categories
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    @if (isset($cats) && count($cats) > 4)
                                                    @else
                                                        <a id="hcats" href="{{ URL::to('/') }}/category"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    ক্যাটাগরি যোগ করুন
                                                                @else
                                                                    Add Category
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($productsss) && count($productsss) > 10) text-danger text-gradient @endif">shopping_cart</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="prods">
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
                                                    @if (isset($productsss) && count($productsss) > 10)
                                                    @else
                                                        <a id="hprods" href="{{ URL::to('/') }}/products/create"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    পণ্য যোগ করুন
                                                                @else
                                                                    Add products
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($setting->logo) &&
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
                                                                isset($setting->shipping_area_3_cost)) text-info @else text-dark @endif text-gradient">settings</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="sett">
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
                                                    @else
                                                        <a id="hsett" href="{{ URL::to('/') }}/settings"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    তথ্য আপডেট করুন
                                                                @else
                                                                    Update information
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if ($designs->template_id == '0') text-dark @else text-warning @endif text-gradient">palette</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="them">
                                                        @if ($designs->template_id == '0')
                                                            <span class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    আপনার ওয়েবসাইটের জন্য পছন্দমতন থিম নির্বাচন করুন
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
                                                    @if ($designs->template_id == '0')
                                                        <a id="hthem" href="{{ URL::to('/') }}/design/theme"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    থিম নির্বাচন করুন
                                                                @else
                                                                    Change Theme
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($headermenu) && count($headermenu) > 0) text-warning text-gradient @endif">menu</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="menu">
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
                                                    @if (isset($headermenu) && count($headermenu) > 0)
                                                    @else
                                                        <a id="hmenu" href="{{ URL::to('/') }}/design/header"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    হেডার মেনু নির্বাচন করুন
                                                                @else
                                                                    Select Header Menu
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($slidersd) && count($slidersd) > 0) text-warning text-gradient @endif">collections</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainpage">
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
                                                                    আপনার ওয়েবসাইটের জন্য আরও পেইজ যোগ করুন (যেমন: এবাউট ,
                                                                    কন্টাক্ট ইত্যাদি)
                                                                @else
                                                                    Add Slider For Your Website
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    @if (isset($slidersd) && count($slidersd) > 0)
                                                    @else
                                                        <a id="hidepage" href="{{ URL::to('/') }}/design/slider"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;">
                                                            <span class="nav-link-text ms-1">
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
                                                    <i
                                                        class="material-icons @if (isset($pagesd) && count($pagesd) > 0) text-warning text-gradient @endif">view_carousel</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="page">
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
                                                                    আপনার ওয়েবসাইটের জন্য আরও পেইজ যোগ করুন (যেমন: এবাউট ,
                                                                    কন্টাক্ট ইত্যাদি)
                                                                @else
                                                                    Add more pages for your website (Ex: About, Contact,
                                                                    etc.)
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    @if (isset($pagesd) && count($pagesd) > 0)
                                                    @else
                                                        <a id="hpage" href="{{ URL::to('/') }}/pages/create"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;">
                                                            <span class="nav-link-text ms-1">
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
                                                    <i
                                                        class="material-icons @if (isset($domain) && count($domain) > 1) text-primary @else text-dark @endif text-gradient">vpn_lock</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="domain">
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
                                                    @if (isset($domain) && count($domain) > 1)
                                                    @else
                                                        <a id="hdomain" href="{{ URL::to('/') }}/domain"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;">
                                                            <span class="nav-link-text ms-1">
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
                    @endif
                    <div class="card" style="margin-top:58px;">
                        <div class="card-header pb-0" style="border-radius: 0.75rem 0.75rem 0.75rem 0.75rem;">
                            <h6
                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        অ্যাকাউন্টের তথ্য
                                    @else
                                        Account Info
                                    @endif
                                </span>
                            </h6>
                            <p class="text-sm" style="padding-left:10px;">
                                @php
                                    $store = DB::table('stores')->where('id', $store_id)->first();
                                    $plan = DB::table('plans')
                                        ->where('id', $store->plan_id)
                                        ->first();
                                @endphp
                                <span style="font-weight:bold">Current Plan</span> : {{ $plan->name ?? '' }}
                                <br>
                                <span style="font-weight:bold">Price</span> :BDT. {{ $plan->price ?? '' }}
                                <br>
                                <span style="font-weight:bold">Purchase Date</span>: {{ $store->purchase_date ?? '' }}
                                <br>
                                <span style="font-weight:bold">Expiry Date</span>: {{ $store->expiry_date ?? '' }}
                                <br>
                                @if (isset($store->upcoming_plan_id))
                                    @php
                                        $upcoming_plan = DB::table('plans')
                                            ->where('id', $store->upcoming_plan_id)
                                            ->first();
                                    @endphp
                                    <span style="font-weight:bold">Upcoming Plan</span>: {{ $upcoming_plan->name }}
                                    <br>
                                    <span style="font-weight:bold">Upcoming Plan Month</span>:
                                    {{ $store->upcoming_plan_month }}
                                    <br>
                                    <span style="font-weight:bold">Upcoming Plan Expiry Date</span>:
                                    {{ $store->upcoming_plan_expiry_date }}
                                    <br>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body p-3">
                            <h6
                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        রিওয়ার্ড তথ্য
                                    @else
                                        Reward Info 1
                                    @endif
                                </span>
                            </h6>
                            <p class="text-sm" style="padding-left:10px;">
                                @php
                                    $cus = DB::table('customers')->where('id', $customer_id)->first();
                                @endphp
                                <span style="font-weight:bold">Reference Code</span> : {{ $cus->ref_code }}
                                <br><span style="font-weight:bold">Total Points</span> : {{ $cus->points }}
                                <br>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row mb-4" id="hre2">
                <div class="col-lg-8 col-md-8 mt-3">
                    <div id="toptoolstour">
                        <div class="row">
                            <div class="col-md-12 mt-3 mb-3">
                                <h3><span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            সর্বাধিক ব্যবহৃত
                                            টুলস
                                        @else
                                            Top used tools
                                        @endif
                                    </span></h3>
                                <p style="font:10px; color:#424242;"><span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            যে অপশনগুল আপনি সবচেয়ে বেশি ব্যবহার করেছেন
                                        @else
                                            The options that you used most
                                        @endif
                                    </span>
                                <p>
                            </div>
                        </div>
                        <div class="row">
                            @if (isset($use) && count($use) > 0)
                                @foreach ($use as $key => $uu)
                                    @if ($key < 6)
                                        <div class="col-md-6  mt-3">
                                            <a href="{{ URL::to('/') }}{{ $uu->url }}"
                                                style="display: block;width: 100%;padding: 20px 20px;background-color: #fff !important;border-radius:10px;">
                                                <img src="{{ URL::to('/') }}/img/icons/{{ $uu->image }}"
                                                    width="40" height="40">
                                                &nbsp;&nbsp;&nbsp;{{ $uu->name }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="row">
                                        <div class="col-lg-12 col-12">
                                            <h6
                                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                                <span class="nav-link-text ms-1">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        অর্ডার পরিসংখ্যান
                                                    @else
                                                        Order Statistic
                                                    @endif
                                                </span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body px-4 pb-2">
                                    <div class="table-responsive" style="padding-bottom: 20px;">
                                        <table class="table table-striped" style="margin-bottom:30px;" width="100%">
                                            <tbody>
                                                <tr>
                                                    <th width="25%">Pending</th>
                                                    <td width="25%">0</td>
                                                    <th width="25%">On Hold</th>
                                                    <td width="25%">0</td>
                                                </tr>
                                                <tr>
                                                    <th>Payment Failed</th>
                                                    <td>0</td>
                                                    <th>Processing</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Shipping</th>
                                                    <td>0</td>
                                                    <th>Delivered</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>Returned</th>
                                                    <td>0</td>
                                                    <th>Cancel</th>
                                                    <td>0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mt-3" id="unuse">
                    @php
                        $cats = DB::table('categories')->where('store_id', $store_id)->where('parent', 0)->get();
                        $productsss = DB::table('products')->where('store_id', $store_id)->get();
                        $setting = DB::table('headersettings')->where('store_id', $store_id)->first();
                        $designs = DB::table('designs')->where('store_id', $store_id)->first();
                        $headermenu = DB::table('menus')->where('store_id', $store_id)->get();
                        $slidersd = DB::table('sliders')->where('store_id', $store_id)->get();
                        $pagesd = DB::table('pages')->where('store_id', $store_id)->get();
                        $domain = DB::table('domains')->where('store_id', $store_id)->get();

                        $done1 = 0;
                        if (isset($cats) && count($cats) > 4) {
                            $done1 = $done1 + 14.2857142857;
                        }
                        if (isset($productsss) && count($productsss) > 10) {
                            $done1 = $done1 + 14.2857142857;
                        }
                        if (isset($setting->short_description) && isset($setting->phone) && isset($setting->email) && isset($setting->address) && isset($setting->facebook_link) && isset($setting->instagram_link) && isset($setting->youtube_link) && isset($setting->messenger_link) && isset($setting->whatsapp_phone) && isset($setting->tax) && isset($setting->shipping_area_1) && isset($setting->shipping_area_1_cost) && isset($setting->shipping_area_2) && isset($setting->shipping_area_2_cost) && isset($setting->shipping_area_3) && isset($setting->shipping_area_3_cost)) {
                            $done1 = $done1 + 14.2857142857;
                        }
                        if (isset($headermenu) && count($headermenu) > 0) {
                            $done1 = $done1 + 14.2857142857;
                        }
                        if (isset($slidersd) && count($slidersd) > 0) {
                            $done1 = $done1 + 14.2857142857;
                        }
                        if (isset($pagesd) && count($pagesd) > 0) {
                            $done1 = $done1 + 14.2857142857;
                        }
                        if (isset($domain) && count($domain) > 1) {
                            $done1 = $done1 + 14.2857142857;
                        }
                        if ($designs->template_id != '0') {
                            $done1 = $done1 + 14.2857142857;
                        }
                    @endphp

                    @if (isset($cats) &&
                            count($cats) > 4 &&
                            isset($productsss) &&
                            count($productsss) > 10 &&
                            isset($setting->logo) &&
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
                            isset($setting->shipping_area_3_cost) &&
                            isset($headermenu) &&
                            count($headermenu) > 0 &&
                            isset($pagesd) &&
                            count($pagesd) > 0 &&
                            isset($domain) &&
                            count($domain) > 1)
                        <div class="ff" style="height:34px"></div>
                        <div class="showhidebutton">
                            <button id="shh" style="padding: 10px;border: 1px solid gray;border-radius: 10px;">Play
                                Tutorial <img src="https://img.icons8.com/nolan/24/play.png" /></button>
                        </div>
                    @else
                        <div class="showhidebutton">
                            <button style="padding: 10px;border: 1px solid gray;border-radius: 10px;">Play Tutorial <img
                                    src="https://img.icons8.com/nolan/24/play.png" /></button>
                        </div>
                        <div class="row" id="websitesettptour">
                            <div class="col-md-12 mt-3">
                                <h3>
                                    <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ওয়েবসাইট সেটআপ গাইড
                                        @else
                                            Website setup guide
                                        @endif
                                    </span>
                                </h3>
                                <p style="font:10px; color: #424242">
                                    <span class="nav-link-text ">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ওয়েবসাইটির পরিপূরণ লুক আনতে, অনুগ্রহ করে নিচের ধাপগুলো সম্পূর্ণ করুন।
                                        @else
                                            To give your website a proper look, please complete the steps below
                                        @endif
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="progress-bar mb-3" style="height:9px;background-color:#fff !important">
                                    <div class="progress-step @if (isset($done1) && $done1 >= 1) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 14.2857142857) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 28.5714285714) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 42.8571428571) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 57.1428571428) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 71.4285714285) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 85.7142857142) is-active @endif">
                                    </div>
                                    <div class="progress-step @if (isset($done1) && $done1 > 99.999999999) is-active @endif">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-1">
                                <div class="card">
                                    <div class="card-body p-3">

                                        <div class="timeline timeline-one-side">
                                            <div class="timeline-block mb-3">

                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($cats) && count($cats) > 4) text-success @else text-dark @endif  text-gradient ">widgets</i>
                                                </span>
                                                <div class="timeline-content">

                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="maincategory">
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
                                                                    Your website has no category, please add categories
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    @if (isset($cats) && count($cats) > 4)
                                                    @else
                                                        <a id="hidecategory" href="{{ URL::to('/') }}/category"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;">
                                                            <span class="nav-link-text ms-1">
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
                                                    <i
                                                        class="material-icons @if (isset($productsss) && count($productsss) > 10) text-danger text-gradient @endif">shopping_cart</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainproducts">
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
                                                    @if (isset($productsss) && count($productsss) > 10)
                                                    @else
                                                        <a id="hideproducts" href="{{ URL::to('/') }}/products/create"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;">
                                                            <span class="nav-link-text ms-1">
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
                                                    <i
                                                        class="material-icons @if (isset($setting->logo) &&
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
                                                                isset($setting->shipping_area_3_cost)) text-info @else text-dark @endif text-gradient">settings</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainsetting">
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
                                                    @else
                                                        <a id="hidesetting" href="{{ URL::to('/') }}/settings"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;">
                                                            <span class="nav-link-text ms-1">
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
                                                    <i
                                                        class="material-icons @if ($designs->template_id == '0') text-dark @else text-warning @endif text-gradient">palette</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="maintheme">
                                                        @if ($designs->template_id == '0')
                                                            <span class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    আপনার ওয়েবসাইটের জন্য পছন্দমতন থিম নির্বাচন করুন
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
                                                    @if ($designs->template_id == '0')
                                                        <a id="hidetheme" href="{{ URL::to('/') }}/design/theme"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    থিম নির্বাচন করুন
                                                                @else
                                                                    Change Theme
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($headermenu) && count($headermenu) > 0) text-warning text-gradient @endif">menu</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainmenu">
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
                                                    @if (isset($headermenu) && count($headermenu) > 0)
                                                    @else
                                                        <a id="hidemenu" href="{{ URL::to('/') }}/design/header"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    হেডার মেনু নির্বাচন করুন
                                                                @else
                                                                    Select Header Menu
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($slidersd) && count($slidersd) > 0) text-warning text-gradient @endif">collections</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainpage">
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
                                                                    আপনার ওয়েবসাইটের জন্য আরও পেইজ যোগ করুন (যেমন: এবাউট ,
                                                                    কন্টাক্ট ইত্যাদি)
                                                                @else
                                                                    Add Slider For Your Website
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    @if (isset($slidersd) && count($slidersd) > 0)
                                                    @else
                                                        <a id="hidepage" href="{{ URL::to('/') }}/design/slider"
                                                            class="btn btn-primary mt-1"
                                                            style="font-size: 11px;padding: 8px 12px;"><span
                                                                class="nav-link-text ms-1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    পেইজ যোগ করুন
                                                                @else
                                                                    Add Slider
                                                                @endif
                                                            </span></a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i
                                                        class="material-icons @if (isset($pagesd) && count($pagesd) > 0) text-warning text-gradient @endif">view_carousel</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="mainpage">
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
                                                                    আপনার ওয়েবসাইটের জন্য আরও পেইজ যোগ করুন (যেমন: এবাউট ,
                                                                    কন্টাক্ট ইত্যাদি)
                                                                @else
                                                                    Add more pages for your website (Ex: About, Contact,
                                                                    etc.)
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    @if (isset($pagesd) && count($pagesd) > 0)
                                                    @else
                                                        <a id="hidepage" href="{{ URL::to('/') }}/pages/create"
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
                                                    <i
                                                        class="material-icons @if (isset($domain) && count($domain) > 1) text-primary @else text-dark @endif text-gradient">vpn_lock</i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0"
                                                        id="maindomain">
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
                                                    @if (isset($domain) && count($domain) > 1)
                                                    @else
                                                        <a id="hidedomain" href="{{ URL::to('/') }}/domain"
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
                    @endif
                    <div class="card" style="margin-top:58px;">
                        <div id="accinfotr" class="card-header pb-0"
                            style="border-radius: 0.75rem 0.75rem 0.75rem 0.75rem;">
                            <h6
                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        অ্যাকাউন্টের তথ্য
                                    @else
                                        Account Info
                                    @endif
                                </span>
                            </h6>
                            <p class="text-sm" style="padding-left:10px;">
                                @php
                                     $store = DB::table('stores')->where('id', $store_id)->first();
                                     $plan = DB::table('plans')
                                    ->where('id', $store->plan_id)
                                    ->first();
                                @endphp
                                <span style="font-weight:bold">Current Plan</span> : {{ $plan->name ?? '' }}
                                <br>

                                <span style="font-weight:bold">Price</span> :BDT. {{ $plan->price ?? '' }}
                                <br>
                                <span style="font-weight:bold">Purchase Date</span>: {{ $store->purchase_date ?? '' }}
                                <br>
                                <span style="font-weight:bold">Expiry Date</span>: {{ $store->expiry_date ?? '' }}
                                <br>
                                @if (isset($store->upcoming_plan_id))
                                    @php
                                        $upcoming_plan = DB::table('plans')
                                            ->where('id', $store->upcoming_plan_id)
                                            ->first();
                                    @endphp
                                    <span style="font-weight:bold">Upcoming Plan</span>: {{ $upcoming_plan->name }}
                                    <br>
                                    <span style="font-weight:bold">Upcoming Plan Month</span>:
                                    {{ $store->upcoming_plan_month }}
                                    <br>
                                    <span style="font-weight:bold">Upcoming Plan Expiry Date</span>:
                                    {{ $store->upcoming_plan_expiry_date }}
                                    <br>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body p-3">
                            <h6
                                style="padding-top: 10px;padding-bottom: 10px;padding-left:10px;background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);color: #fff;border-radius:0.75rem">
                                <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        রিওয়ার্ড তথ্য
                                    @else
                                        Reward Info
                                    @endif
                                </span>
                            </h6>
                            <p class="text-sm" style="padding-left:10px;">
                                @php
                                     $cus = DB::table('customers')->where('id', $customer_id)->first();
                                @endphp
                                <span style="font-weight:bold">Reference Code</span> : {{ $cus->ref_code }}
                                <br><span style="font-weight:bold">Total Points</span> : {{ $cus->points }}
                                <br>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
                </div>
            </div>
            <div class="row" id="hre1">
                <div class="col-md-12">
                    <h3>
                        <span class="nav-link-text ms-1">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                সর্বাধিক বিক্রিত পণ্য
                            @else
                                Top selling products
                            @endif
                        </span>
                    </h3>
                </div>
            </div>
            <div class="row" style="margin-bottom:30px;">
                @if (isset($vals) && count($vals) > 0)
                @php
                    $i = 0;
                    foreach ($vals as $key => $vall) {
                        $price[$i]['v'] = $vall;
                        $price[$i]['p'] = $key;
                        $i++;
                    }
                    rsort($price);
                    $j = 0;
                @endphp
                    @if (isset($price) && count($price) > 0)
                        @foreach ($price as $keys => $prices)
                            @if ($j < 6)
                                @php
                                    $product = DB::table('products')
                                        ->where('id', $prices['p'])
                                        ->where('store_id', $store_id)
                                        ->where('customer_id', $customer_id)
                                        ->first();
                                @endphp
                                @if (isset($product))
                                    <div class="col-xl-2 col-md-3 col-sm-6 mb-xl-0 mb-4"
                                        style="padding-left:5px !important;padding-right:5px !important;">
                                        <div class="card">
                                            <div class="card-header p-3 pt-2">
                                                @if ($product->images)
                                                    @php
                                                        $images = explode(',', $product->images);
                                                    @endphp
                                                    @foreach ($images as $key => $image)
                                                        @php
                                                            if ($key=="0"):
                                                        @endphp
                                                            <img src="{{ URL::to('/') }}/assets/images/product/{{ $image }}"
                                                            style="width:100%;height:250px;">
                                                        @php
                                                            endif;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            </div>
                                            <hr class="dark horizontal my-0">
                                            <div class="card-footer p-3">
                                                <p class="mb-0">
                                                    {{ Str::limit($product->name, 10) }}
                                                </p>
                                                <p>BDT. {{ $product->regular_price }}&nbsp;&nbsp;</p>
                                                <p>{{ $prices['v'] }} Sales</p>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $j++;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                    @endif
                @endif
            </div>
            <div id="mydiv" class="div" style="display:none">
                <div id="mydivheader">
                    <div style="position: absolute;top: 1px;font-size: 20px;right: 1px;padding: 0px 10px;background-color: red;border-radius: 100%;cursor: pointer;display:none"
                        id="hidebutton">X</div>
                    <figure style="padding:30px 10px 0px 10px;">
                        <iframe style="width:100%;min-height:300px" src="https://www.youtube.com/embed/OK3OD3YBC6c"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </figure>
                </div>
            </div>
        </div>
    </main>

@endsection
        @push('scripts')
            <script src="{{ asset('admin/assets/js/plugins/chartjs.min.js') }}"></script>
            <script src="https://www.youtube.com/iframe_api"></script>
            <script>
                const dasht = localStorage.getItem("dashtutorial");
                if (dasht === 'done') {
                    $("#mydiv").hide();
                } else {
                    $("#mydiv").show();
                }
            </script>

            <script>
                $(document).ready(function() {
                    $('#myModal').modal('show');
                });
                var tour1 = new Tour({
                    debug: true,
                    name: "GeneralTour",
                    storage: window.localStorage,
                    steps: [{
                            element: "#visitwebsite",
                            title: "Visit Website",
                            content: "You Can Visit Your Website By Click this icon",
                            placement: "right",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#changestoretour",
                            title: "Store",
                            content: "You Can Create another store and change store by click this icon",
                            placement: "right",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#changelanguagetour",
                            title: "Language",
                            content: "You Can Change your website language by click this icon",
                            placement: "right",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#websitesettptour",
                            title: "Incomplate Things",
                            content: "Your website incomplete things are here, if you want to fully settup your store, you need to setup all items into the list",
                            placement: "top",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#toptoolstour",
                            title: "TopTools",
                            content: "Here is Your most uses items",
                            placement: "top",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#accinfotr",
                            title: "Account",
                            content: "Here is Your Account information",
                            placement: "top",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#sidenav-collapse-main",
                            title: "Menu",
                            content: "Here is Your Menubar",
                            placement: "left",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#producttour",
                            title: "Product",
                            content: "All Products Here",
                            placement: "left",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#ordertour",
                            title: "Order",
                            content: "All Order Here",
                            placement: "left",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#designtour",
                            title: "Design",
                            content: "All Design Here",
                            placement: "left",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#paymenttour",
                            title: "Payment",
                            content: "All Payment Here",
                            placement: "left",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        },
                        {
                            element: "#settingtour",
                            title: "Setting",
                            content: "All Settings Here",
                            placement: "left",
                            smartPlacement: true,
                            animation: true,
                            container: "body",
                            backdrop: true,
                            backdropContainer: 'body',
                        }
                    ]
                });

                // Initialize the tour
                tour1.init();

                // Start the tour
                tour1.start();
                $(document).ready(function() {
                    $("#hideproducts").hide();
                    $("#mainproducts").on("click", function() {
                        $("#hideproducts").toggle();
                        $("#hidecategory").hide();
                        $("#hidesetting").hide();
                        $("#hidetheme").hide();
                        $("#hidemenu").hide();
                        $("#hidepage").hide();
                        $("#hidedomain").hide();
                    });
                    $("#maincategory").on("click", function() {
                        $("#hidecategory").toggle();
                        $("#hideproducts").hide();
                        $("#hidesetting").hide();
                        $("#hidetheme").hide();
                        $("#hidemenu").hide();
                        $("#hidepage").hide();
                        $("#hidedomain").hide();
                    });
                    $("#hidesetting").hide();
                    $("#mainsetting").on("click", function() {
                        $("#hidesetting").toggle();
                        $("#hidecategory").hide();
                        $("#hideproducts").hide();
                        $("#hidetheme").hide();
                        $("#hidemenu").hide();
                        $("#hidepage").hide();
                        $("#hidedomain").hide();
                    });
                    $("#hidetheme").hide();
                    $("#maintheme").on("click", function() {
                        $("#hidetheme").toggle();
                        $("#hidecategory").hide();
                        $("#hideproducts").hide();
                        $("#hidesetting").hide();
                        $("#hidemenu").hide();
                        $("#hidepage").hide();
                        $("#hidedomain").hide();
                    });
                    $("#hidemenu").hide();
                    $("#mainmenu").on("click", function() {
                        $("#hidemenu").toggle();
                        $("#hidecategory").hide();
                        $("#hideproducts").hide();
                        $("#hidesetting").hide();
                        $("#hidetheme").hide();
                        $("#hidepage").hide();
                        $("#hidedomain").hide();
                    });
                    $("#hidepage").hide();
                    $("#mainpage").on("click", function() {
                        $("#hidepage").toggle();
                        $("#hidecategory").hide();
                        $("#hideproducts").hide();
                        $("#hidesetting").hide();
                        $("#hidetheme").hide();
                        $("#hidemenu").hide();
                        $("#hidedomain").hide();
                    });
                    $("#hidedomain").hide();
                    $("#maindomain").on("click", function() {
                        $("#hidedomain").toggle();
                        $("#hidecategory").hide();
                        $("#hideproducts").hide();
                        $("#hidesetting").hide();
                        $("#hidetheme").hide();
                        $("#hidemenu").hide();
                        $("#hidepage").hide();
                    });
                })

                $("#prods").on("click", function() {
                    debugger;
                    $("#hprods").toggle();
                    $("#hcats").hide();
                    $("#hsett").hide();
                    $("#hthem").hide();
                    $("#hmenu").hide();
                    $("#hpage").hide();
                    $("#hdomain").hide();
                });
                $("#hsett").hide();
                $("#sett").on("click", function() {
                    $("#hsett").toggle();
                    $("#hcats").hide();
                    $("#hprods").hide();
                    $("#hthem").hide();
                    $("#hmenu").hide();
                    $("#hpage").hide();
                    $("#hdomain").hide();
                });
                $("#hthem").hide();
                $("#them").on("click", function() {
                    $("#hthem").toggle();
                    $("#hcats").hide();
                    $("#hprods").hide();
                    $("#hsett").hide();
                    $("#hmenu").hide();
                    $("#hpage").hide();
                    $("#hdomain").hide();
                });
                $("#hmenu").hide();
                $("#menu").on("click", function() {
                    $("#hmenu").toggle();
                    $("#hcats").hide();
                    $("#hprods").hide();
                    $("#hsett").hide();
                    $("#hthem").hide();
                    $("#hpage").hide();
                    $("#hdomain").hide();
                });
                $("#hpage").hide();
                $("#page").on("click", function() {
                    $("#hpage").toggle();
                    $("#hcats").hide();
                    $("#hprods").hide();
                    $("#hsett").hide();
                    $("#hthem").hide();
                    $("#hmenu").hide();
                    $("#hdomain").hide();
                });
                $("#hdomain").hide();
                $("#domain").on("click", function() {
                    $("#hdomain").toggle();
                    $("#hcats").hide();
                    $("#hprods").hide();
                    $("#hsett").hide();
                    $("#hthem").hide();
                    $("#hmenu").hide();
                    $("#hpage").hide();
                });
            </script>
            <script>
                $("#shh").on('click', function() {
                    $("#mydiv").toggle();
                })
                $('#hidebutton').on('click', function() {
                    $("#mydiv").hide();
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
@endpush
