@extends('admin.layouts.main')
@push('styles')
    <style>
        .image-link {
            cursor: -webkit-zoom-in;
            cursor: -moz-zoom-in;
            cursor: zoom-in;
        }


        /* This block of CSS adds opacity transition to background */
        /* .mfp-with-zoom .mfp-container,
                                    .mfp-with-zoom.mfp-bg {
                                        opacity: 0;
                                        -webkit-backface-visibility: hidden;
                                        -webkit-transition: all 0.3s ease-out;
                                        -moz-transition: all 0.3s ease-out;
                                        -o-transition: all 0.3s ease-out;
                                        transition: all 0.3s ease-out;
                                    } */

        .mfp-with-zoom.mfp-ready .mfp-container {
            opacity: 1;
        }

        .mfp-with-zoom.mfp-ready.mfp-bg {
            opacity: 0.8;
        }

        .mfp-with-zoom.mfp-removing .mfp-container,
        .mfp-with-zoom.mfp-removing.mfp-bg {
            opacity: 0;
        }



        /* padding-bottom and top for image */
        .mfp-no-margins img.mfp-img {
            padding: 0;
        }

        /* position of shadow behind the image */
        .mfp-no-margins .mfp-figure:after {
            top: 0;
            bottom: 0;
        }

        /* padding for main container */
        .mfp-no-margins .mfp-container {
            padding: 0;
        }



        /* aligns caption to center */
        .mfp-title {
            text-align: center;
            padding: 6px 0;
        }

        .image-source-link {
            color: #DDD;
        }

        .zoom {
            transition: transform .2s;
            /* Animation */
            margin: 0 auto;
        }

        .zoom:hover {
            transform: scale(7.5);
            /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }



        .panel {
            padding: 19px 15px 10px 15px;
            border: 1px solid gray;
            margin-bottom: 5px;
            background: #fff;
        }

        label {
            color: #000;
        }

        #accordion {
            height: 90vh;
            overflow-x: hidden;
            overflow-y: auto;
        }
    </style>
@endpush
@section('content')

    <?php
    use Illuminate\Support\Facades\DB;
    if (auth()->user()->type == 'admin') {
        $customer = DB::table('customers')
            ->where('uid', auth()->user()->id)
            ->first();
        $store_id = $customer->active_store;
    } elseif (auth()->user()->type == 'staff') {
        $staff = DB::table('staff')
            ->where('uid', auth()->user()->id)
            ->first();
        $store_id = $staff->store_id;
        $role = DB::table('roles')
            ->where('id', $staff->role_id)
            ->first();
        if (isset($role)) {
            $permission = explode(',', $role->permission);
            foreach ($permission as $key => $pr) {
                if ($pr == 'branch') {
                    $branch = 1;
                } elseif ($pr == 'product') {
                    $product = 1;
                } elseif ($pr == 'category') {
                    $category = 1;
                } elseif ($pr == 'subcategory') {
                    $subcategory = 1;
                } elseif ($pr == 'brand') {
                    $brand = 1;
                } elseif ($pr == 'attribute') {
                    $attribute = 1;
                } elseif ($pr == 'supplier') {
                    $supplier = 1;
                } elseif ($pr == 'collection') {
                    $collection = 1;
                } elseif ($pr == 'global_tab') {
                    $global_tab = 1;
                } elseif ($pr == 'coupon') {
                    $coupon = 1;
                } elseif ($pr == 'campaign') {
                    $campaign = 1;
                } elseif ($pr == 'offer') {
                    $offer = 1;
                } elseif ($pr == 'slider') {
                    $slider = 1;
                } elseif ($pr == 'banner') {
                    $banner = 1;
                } elseif ($pr == 'layouts') {
                    $layouts = 1;
                } elseif ($pr == 'template') {
                    $template = 1;
                } elseif ($pr == 'header') {
                    $header = 1;
                } elseif ($pr == 'homepage') {
                    $homepage = 1;
                } elseif ($pr == 'footer') {
                    $footer = 1;
                } elseif ($pr == 'mobilemenu') {
                    $mobilemenu = 1;
                } elseif ($pr == 'product_display') {
                    $product_display = 1;
                } elseif ($pr == 'product_grid') {
                    $product_grid = 1;
                } elseif ($pr == 'shop_page') {
                    $shop_page = 1;
                } elseif ($pr == 'pages') {
                    $pages = 1;
                } elseif ($pr == 'customer') {
                    $customer = 1;
                } elseif ($pr == 'staff') {
                    $staff = 1;
                } elseif ($pr == 'invoice') {
                    $invoice = 1;
                } elseif ($pr == 'setting') {
                    $setting = 1;
                } elseif ($pr == 'role_permission') {
                    $role_permission = 1;
                } elseif ($pr == 'pos') {
                    $pos = 1;
                } else {
                }
            }
        }
    }
    $store = DB::table('stores')
        ->where('id', $store_id)
        ->first();
    if ($store->expiry_date <= Carbon\Carbon::now()) {
        $exp = 1;
    } else {
        $exp = 0;
    }
    ?>

    <main class="main-content position-relative  h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/') }}/digital_marketing">
                                    <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ড্যাশবোর্ড
                                        @else
                                            Dashboard
                                        @endif
                                    </span>
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                <a href="{{ route('admin.required.information') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            প্রয়োজনীয় তথ্য
                                        @else
                                            Required Information
                                        @endif
                                    </span>
                                </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{ URL::to('/') }}/content_download">
                                    <img src="{{ URL::to('/') }}/img/icons/categories.png"> <br><span
                                        class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            কনটেন্ট ডাউনলোড
                                        @else
                                            Content Download
                                        @endif
                                    </span>
                                </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{ route('admin.content_correction') }}">
                                    <img src="{{ URL::to('/') }}/img/subcategory.png"> <br><span
                                        class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            বিষয়বস্তু সংশোধন
                                        @else
                                            Content Correction
                                        @endif
                                    </span>
                                </a>
                            </li>
                            {{-- <li class="breadcrumb-item" aria-current="page">
                            <a href="{{URL::to('/')}}/boosting">
                                <img src="{{URL::to('/')}}/img/icons/product.png"><br><span
                                    class="nav-link-text ms-1">Boosting</span>

                            </a>
                        </li> --}}
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">
            @if ((isset($product) && $product == '1') || auth()->user()->type == 'admin')

                <div class="row mt-3">
                    <div class="col-md-6">
                        <form action="{{ route('admin.required.information.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার ব্যবসা এর বিবরণ দিন
                                        @else
                                            Enter your business information
                                        @endif
                                    </h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="form-group">
                                        <label for="question_1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আপনার ব্যবসার ধরন এবং ক্রেতাদের ব্যপারে জানান
                                            @else
                                                Describe your business type and customers
                                            @endif
                                            <span style="color: red;">*</span>
                                        </label>
                                        <textarea name="question_1" id="question_1" cols="30" rows="2" class="form-control" onfocus="focused(this)"
                                            onfocusout="defocused(this)">{{ $required_information->question_1 ?? '' }}</textarea>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="question_2">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আপনার ব্যবসায়িক যোগাযোগ নাম্বার দিন
                                            @else
                                                Enter your business contact number
                                            @endif
                                            <span style="color: red;">*</span>
                                        </label>
                                        <input type="text" name="question_2" id="question_2" class="form-control"
                                            onfocus="focused(this)" onfocusout="defocused(this)"
                                            value="{{ $required_information->question_2 ?? '' }}">
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="question_3">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আপনার ওয়েবসাইটের লিংক শেয়ার করুন (যদি থাকে)
                                            @else
                                                Share your website link (if any)
                                            @endif
                                        </label>
                                        <textarea name="question_3" id="question_3" cols="30" rows="1" class="form-control">{{ $required_information->question_3 ?? '' }}</textarea>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="question_4">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আপনার সোশ্যাল মিডিয়া পেজের লিংক শেয়ার করুন
                                            @else
                                                Share your social media page link
                                            @endif
                                        </label>
                                        <textarea name="question_4" id="question_4" cols="30" rows="1" class="form-control">{{ $required_information->question_4 ?? '' }}</textarea>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="question_5">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আপনার ব্যবসার লোগো এবং ব্রান্ড গাইড লাইন (যদি থাকে) দিন
                                            @else
                                                Provide your business logo and brand guide line (if any)
                                            @endif
                                        </label>

                                        <div class="row mb-3">
                                            @if ($required_information->question_5 ?? '')
                                                @php
                                                    $RequiredInformation2 = json_decode($required_information->question_5);
                                                    if ($RequiredInformation2 == null) {
                                                        $RequiredInformation2 = [];
                                                    }
                                                @endphp

                                                @foreach ($RequiredInformation2 as $item)
                                                    <div class="col-md-3">
                                                        <img style="width: 100%;"
                                                            src="{{ asset('clientContent/RequiredInformation/' . $item) }}"
                                                            alt="">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <input type="file" name="question_5[]" id="question_5" multiple
                                            class="form-control" onfocus="focused(this)" onfocusout="defocused(this)">
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>



                    {{-- Required Information for individual content --}}
                    <div class="col-md-6">

                        <div style="background: #f1593a;" class="">
                            <h6 class="p-3" style="color: white;">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    আপনার কন্টেন্ট এর বিবরণ দিন
                                @else
                                    Required Information for individual content
                                @endif

                            </h6>
                        </div>

                        <div style="background: #2c2c2f; padding: 20px;" class="mb-2">
                            <button type="submit" class="btn btn-primary btn-sm mb-0" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" id="addRequiredInfo">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    প্রয়োজনীয় তথ্য যোগ করুন
                                @else
                                    Add Required Info
                                @endif
                            </button>
                        </div>

                        <div class="panel-group" id="accordion">

                            @foreach ($required_information_contents as $key => $required_information_content)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title" style="font-size: 18px;">
                                            <a data-toggle="collapse" data-parent="#accordion"
                                                href="#collapse_{{ $required_information_content->id }}">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    বিষয়বস্তুর
                                                @else
                                                    Content-
                                                @endif
                                                {{ date('d-m-Y', strtotime($required_information_content->created_at)) }}
                                            </a>
                                            <a href="{{ route('admin.required.information.individual.content.delete', $required_information_content->id) }}"
                                                class="btn btn-primary btn-sm" style="float: right;">
                                                Delete
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_{{ $required_information_content->id }}"
                                        class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="">
                                                <form action="#"class="col-md-12" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="row" style="width: 100%;">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="question_11">
                                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                        প্রাইমারি কালার
                                                                    @else
                                                                        Primary Color
                                                                    @endif
                                                                    <span style="color: red;">*</span>
                                                                </label>
                                                                <input name="question_11" id="question_11" type="text"
                                                                    class="form-control" readonly
                                                                    value="{{ $required_information_content->question_11 ?? '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="question_12">
                                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                        ভাষা
                                                                    @else
                                                                        Language
                                                                    @endif
                                                                    <span style="color: red;">*</span>
                                                                </label>
                                                                <input name="question_12" id="question_12" type="text"
                                                                    class="form-control" readonly
                                                                    value="{{ $required_information_content->question_12 ?? '' }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mt-3">
                                                        <label for="">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                আপনার কন্টেন্ট থিমের বিবরন দিন
                                                            @else
                                                                Describe the theme of your content
                                                            @endif
                                                            <span style="color: red;">*</span>
                                                        </label>
                                                        <textarea name="" id="" cols="30" rows="2" class="form-control" readonly
                                                            onfocus="focused(this)" onfocusout="defocused(this)">{{ $required_information_content->question_6 }}</textarea>
                                                    </div>


                                                    <div class="form-group mt-3">
                                                        <label>
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                কন্টেন্ট এর মাধ্যমে কোন অফার, বা ইভেন্ট প্রোমোট করতে চান কি?
                                                            @else
                                                                Want to promote an offer, or event through content?
                                                            @endif
                                                            <span style="color: red;">*</span>
                                                        </label>
                                                        <br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="flexRadioDefault" readonly id="flexRadioDefault1"
                                                                {{ $required_information_content->question_7 ? '' : 'checked' }} />
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    না
                                                                @else
                                                                    No
                                                                @endif
                                                            </label>
                                                        </div>

                                                        <!-- Default checked radio -->
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="flexRadioDefault" readonly id="flexRadioDefault2"
                                                                {{ $required_information_content->question_7 ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="flexRadioDefault2">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    হ্যাঁ
                                                                @else
                                                                    Yes
                                                                @endif
                                                            </label>
                                                        </div>




                                                        <textarea class="form-control {{ $required_information_content->question_7 ? '' : 'd-none' }}" name=""
                                                            readonly id="coip" cols="30" rows="2" required="" onfocus="focused(this)"
                                                            onfocusout="defocused(this)">{{ $required_information_content->question_7 }}</textarea>
                                                    </div>


                                                    <div class="form-group mt-3">
                                                        <label for="question_8">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                অন্য পেজ বা কম্পিটিটর যাদের কন্টেন্ট ভালো লাগে, রেফারেন্স
                                                                হিসেবে দিন। (লিংক)
                                                            @else
                                                                Mention other pages or competitors you like. (link)
                                                            @endif
                                                        </label>
                                                        <textarea name="question_8" id="question_8" class="form-control" readonly onfocus="focused(this)"
                                                            onfocusout="defocused(this)" cols="30" rows="2">{{ $required_information_content->question_8 }}</textarea>
                                                    </div>


                                                    <div class="form-group mt-3">
                                                        <label for="question_9">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                কন্টেন্ট এর জন্য আপনার পণ্য বা সেবার ছবি দিন
                                                            @else
                                                                Provide images of your products or services for content
                                                            @endif
                                                        </label>

                                                        <div class="row">
                                                            @php
                                                                $RequiredInformation2 = json_decode($required_information_content->question_9);
                                                            @endphp

                                                            @foreach ($RequiredInformation2 as $item)
                                                                <div class="col-md-3">
                                                                    <img style="width: 100%;"
                                                                        src="{{ asset('clientContent/RequiredInformation/forContent/' . $item) }}"
                                                                        alt="">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="form-group mt-3">
                                                        <label for="question_10">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                অন্য পেজ বা কম্পিটিটর যাদের কন্টেন্ট ভালো লাগে, রেফারেন্স
                                                                হিসেবে দিন। (ছবি)
                                                            @else
                                                                Mention other pages or competitors you like. (photo)
                                                            @endif
                                                        </label>
                                                        <div class="row">
                                                            @php
                                                                $RequiredInformation2 = json_decode($required_information_content->question_10);
                                                            @endphp

                                                            @foreach ($RequiredInformation2 as $item)
                                                                <div class="col-md-3">
                                                                    <img style="width: 100%;"
                                                                        src="{{ asset('clientContent/RequiredInformation/forContent/' . $item) }}"
                                                                        alt="">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>



                                                    {{-- <div class="modal-footer pb-0" style="padding-right: 0px; border-top: none;">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div> --}}

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" style="">
                                    <form action="{{ route('admin.required.information.individual.content.store') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        বিভিন্ন বিষয়বস্তুর জন্য প্রয়োজনীয় তথ্য
                                                    @else
                                                        Required Information for individual content
                                                    @endif
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label for="question_11">
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    প্রাইমারি কালার
                                                                @else
                                                                    Primary Color
                                                                @endif
                                                                <span style="color: red;">*</span>
                                                            </label>
                                                            <input name="question_11" id="question_11" type="text"
                                                                class="form-control" value=""
                                                                placeholder="red, black, white, etc" required=""
                                                                onfocus="focused(this)" onfocusout="defocused(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="question_12">ভাষা<span
                                                                    style="color: red;">*</span> </label>
                                                            <select class="form-control" name="question_12"
                                                                id="question_12" required="" onfocus="focused(this)"
                                                                onfocusout="defocused(this)">
                                                                <option value="">Select Language</option>
                                                                <option
                                                                    value="@if (Session::has('lang') && Session::get('lang') == 'bn') ইংরেজি @else English @endif ">
                                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                        ইংরেজি
                                                                    @else
                                                                        English
                                                                    @endif
                                                                </option>
                                                                <option
                                                                    value="@if (Session::has('lang') && Session::get('lang') == 'bn') বাংলা  @else Bangla @endif ">
                                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                        বাংলা
                                                                    @else
                                                                        Bangla
                                                                    @endif
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mt-3">
                                                    <label for="question_6">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            আপনার কন্টেন্ট থিমের বিবরন দিন
                                                        @else
                                                            Describe the theme of your content
                                                        @endif
                                                        <span style="color: red;">*</span>
                                                    </label>
                                                    <textarea name="question_6" id="question_6" cols="30" rows="2" class="form-control" required=""
                                                        onfocus="focused(this)" onfocusout="defocused(this)"></textarea>
                                                </div>



                                                <div class="form-group mt-3">
                                                    <label>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            কন্টেন্ট এর মাধ্যমে কোন অফার, বা ইভেন্ট প্রোমোট করতে চান কি?
                                                        @else
                                                            Want to promote an offer, or event through content?
                                                        @endif
                                                        <span style="color: red;">*</span>
                                                    </label>
                                                    <br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="flexRadioDefault" id="flexRadioDefault3" checked />
                                                        <label class="form-check-label" for="flexRadioDefault3">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                না
                                                            @else
                                                                No
                                                            @endif
                                                        </label>
                                                    </div>

                                                    <!-- Default checked radio -->
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="flexRadioDefault" id="flexRadioDefault4" />
                                                        <label class="form-check-label" for="flexRadioDefault4">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                হ্যাঁ
                                                            @else
                                                                Yes
                                                            @endif
                                                        </label>
                                                    </div>

                                                    <textarea class="form-control d-none" name="question_7" id="coip1" cols="30" rows="2"
                                                        onfocus="focused(this)" onfocusout="defocused(this)"></textarea>
                                                </div>

                                                <div class="form-group mt-3">
                                                    <label for="question_8">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            অন্য পেজ বা কম্পিটিটর যাদের কন্টেন্ট ভালো লাগে, রেফারেন্স হিসেবে
                                                            দিন। (লিংক)
                                                        @else
                                                            Mention other pages or competitors you like. (link)
                                                        @endif
                                                    </label>
                                                    <textarea name="question_8" id="question_8" class="form-control" required="" onfocus="focused(this)"
                                                        onfocusout="defocused(this)" cols="30" rows="2"></textarea>
                                                </div>


                                                <div class="form-group mt-3">
                                                    <label for="question_9">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            কন্টেন্ট এর জন্য আপনার পণ্য বা সেবার ছবি দিন
                                                        @else
                                                            Provide images of your products or services for content
                                                        @endif
                                                    </label>
                                                    <input type="file" name="question_9[]" id="question_9"
                                                        class="form-control" required="" multiple
                                                        onfocus="focused(this)" onfocusout="defocused(this)">
                                                </div>

                                                <div class="form-group mt-3">
                                                    <label for="question_10">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            অন্য পেজ বা কম্পিটিটর যাদের কন্টেন্ট ভালো লাগে, রেফারেন্স হিসেবে
                                                            দিন। (ছবি)
                                                        @else
                                                            Mention other pages or competitors you like. (photo)
                                                        @endif
                                                    </label>
                                                    <input type="file" name="question_10[]" id="question_10"
                                                        class="form-control" multiple onfocus="focused(this)"
                                                        onfocusout="defocused(this)">
                                                </div>


                                            </div>
                                            <div class="modal-footer">
                                                @if ($required_information->id ?? '')
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                @else
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" disabled class="btn btn-primary">প্রয়োজনীয়
                                                        তথ্য ফরম পূরণ করেন</button>
                                                @endif

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>


                    </div>

                </div>
            @endif
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $('#flexRadioDefault1').on('click', function() {
            $("#coip").addClass("d-none");
        });
        $('#flexRadioDefault2').on('click', function() {
            $("#coip").removeClass("d-none");
        });

        $('#flexRadioDefault3').on('click', function() {
            $("#coip1").addClass("d-none");
        });
        $('#flexRadioDefault4').on('click', function() {
            $("#coip1").removeClass("d-none");
        });


        $('#submit').on('click', function() {
            var form = $(this).parents('form');
            var note = $('#action').val();
            if (note != 'select') {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You want to " + note + " this selected item",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + note + ' it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        console.log(form);
                        $('#submitform').submit();
                        form.submit();
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swal.fire(
                            'Cancelled',
                            '' + note + ' Cancel :)',
                            'error'
                        )
                    }
                })
            }
        })

        $(document).ready(function() {
            $(".switchstatus").on("change", function() {
                $url = "/changeprostatus";
                var value = $(this).val();
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {
                    value: value,
                    id: id
                }, function(data) {
                    console.log(data);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#checkedAll").change(function() {
                debugger;
                if (this.checked) {
                    $(".checkSingle").each(function() {
                        debugger;
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function() {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function() {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function() {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAll").prop("checked", true);
                    }
                } else {
                    $("#checkedAll").prop("checked", false);
                    var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
        $(document).ready(function() {
            $("#taskfilter").on("keyup", function() {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function() {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
@endpush
