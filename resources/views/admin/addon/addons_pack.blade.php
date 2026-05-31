@extends('admin.layouts.main')

@push('styles')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap5.min.css">

    <style>
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {

            background-color: #f1593a;
        }

        .modal {
            position: fixed;
            /* top: 30%; */
            background: #00102496;
        }

        .modal-dialog {
            margin-top: 5%;
        }

        .btn-close {
            box-sizing: content-box;
            color: #ff5733;
            opacity: 1;
        }
    </style>
    <style>
        .avatar-upload {
            position: relative;
            /*max-width: 205px;*/
            /*margin: 20px auto;*/
        }

        .avatar-edit {
            position: absolute;
            /*right: 12px;*/
            margin-left: 135px;
            z-index: 1;
            top: 10px;
        }

        .avatar-edit input {
            display: none;
        }

        .avatar-edit label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all .2s ease-in-out;
        }

        .avatar-edit label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .avatar-edit label:after {
            content: "\f040";
            font-family: 'FontAwesome';
            color: #757575;
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
            text-align: center;
            margin: auto;
        }

        .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }

        .avatar-preview>div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .hh-grayBox {
            background-color: #F8F8F8;
            margin-bottom: 20px;
            padding: 35px;
            margin-top: 20px;
        }

        .pt45 {
            padding-top: 45px;
        }

        .order-tracking {
            text-align: center;
            width: 25%;
            position: relative;
            display: block;
        }

        .order-tracking .is-complete {
            display: block;
            position: relative;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            border: 0px solid #AFAFAF;
            background-color: #f7be16;
            margin: 0 auto;
            transition: background 0.25s linear;
            -webkit-transition: background 0.25s linear;
            z-index: 2;
        }

        .order-tracking .is-complete:after {
            display: block;
            position: absolute;
            content: '';
            height: 14px;
            width: 7px;
            top: -2px;
            bottom: 0;
            left: 5px;
            margin: auto 0;
            border: 0px solid #AFAFAF;
            border-width: 0px 2px 2px 0;
            transform: rotate(45deg);
            opacity: 0;
        }

        .order-tracking.completed .is-complete {
            border-color: #27aa80;
            border-width: 0px;
            background-color: #27aa80;
        }

        .order-tracking.completed .is-complete:after {
            border-color: #fff;
            border-width: 0px 3px 3px 0;
            width: 7px;
            left: 11px;
            opacity: 1;
        }

        .order-tracking p {
            color: #A4A4A4;
            font-size: 16px;
            margin-top: 8px;
            margin-bottom: 0;
            line-height: 20px;
        }

        .order-tracking p span {
            font-size: 14px;
        }

        .order-tracking.completed p {
            color: #000;
        }

        .order-tracking::before {
            content: '';
            display: block;
            height: 3px;
            width: calc(100% - 40px);
            background-color: #f7be16;
            top: 13px;
            position: absolute;
            left: calc(-50% + 20px);
            z-index: 0;
        }

        .order-tracking:first-child:before {
            display: none;
        }

        .order-tracking.completed:before {
            background-color: #27aa80;
        }
    </style>

    <style>
        .image-link {
            cursor: -webkit-zoom-in;
            cursor: -moz-zoom-in;
            cursor: zoom-in;
        }


        /* This block of CSS adds opacity transition to background */
        .mfp-with-zoom .mfp-container,
        .mfp-with-zoom.mfp-bg {
            opacity: 0;
            -webkit-backface-visibility: hidden;
            -webkit-transition: all 0.3s ease-out;
            -moz-transition: all 0.3s ease-out;
            -o-transition: all 0.3s ease-out;
            transition: all 0.3s ease-out;
        }

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
            transform: scale(3.5);
            /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }
    </style>
@endpush


@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.themecustomize') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/color-scheme.png"> <br> <span
                                        class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            থিম কাস্টমাইজ করুন
                                        @else
                                            Theme Customization
                                        @endif
                                    </span>
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.addonss') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/ecommerce.png"> <br> <span
                                        class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ই-কমার্স মোবাইল অ্যাপ
                                        @else
                                            E-Commerce Mobile App
                                        @endif
                                    </span>
                                </a>
                            </li>
                            <li class="breadcrumb-item ">
                                <a href="{{ route('admin.websitesetup') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/ecommerce.png"> <br> <span
                                        class="nav-link-text ms-1">Website Setup</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item ">
                                <a href="{{ route('admin.paymentgateway') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/ecommerce.png"> <br> <span
                                        class="nav-link-text ms-1">Payment Gateway</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{ route('admin.addon.pack') }}">
                                    <img src="https://img.icons8.com/ios-filled/20/ffffff/camera-addon-identification.png">
                                    <br> <span class="nav-link-text ms-1">Addons Pack</span>
                                </a>
                            </li>
                            <?php
                            $act = DB::table('activities')
                                ->where('store_id', $store_id)
                                ->whereDate('expiry_date', '>=', Carbon\Carbon::now())
                                ->first();
                            ?>
                            @if (isset($act))
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.activitylog') }}">
                                        <img src="{{ URL::to('/') }}/img/icons/ecommerce.png"> <br> <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                কার্য বিবরণ
                                            @else
                                                Activity Log
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif

                            <li class="breadcrumb-item">
                                <a href="{{route('admin.modulus')}}">
                                    <img src="{{URL::to('/')}}/img/icons/resume.png"> <br> <span class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn') মডুলাস @else Modulus @endif</span>
                                </a>
                            </li>

                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4">

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add addons pack</h5>
                            <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"
                                onclick="modalToggol()">x</button>
                        </div>
                        <form action="{{ route('admin.addon.pack.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="form-outline mb-3">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" id="title" name="title" class="form-control"
                                        placeholder="Enter your title" />
                                </div>

                                <div class="form-outline mb-3">
                                    <label class="form-label" for="heading">Heading</label>
                                    <input type="text" id="heading" name="heading" class="form-control"
                                        placeholder="Enter your heading" />
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-outline mb-3">
                                            <label class="form-label" for="thumbnail">Thumbnail</label>
                                            <input type="file" id="thumbnail" name="thumbnail" required
                                                onchange="previewFile(this);" class="form-control"
                                                placeholder="Enter your thumbnail" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <img id="previewImg" src="https://ebitans.com/eBitans-Laptop-Logo-Intro.gif"
                                            style="width: 100%;max-height: 90px;" alt="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-outline mb-3">
                                            <label class="form-label" for="price">Price</label>
                                            <input type="number" id="price" name="price" class="form-control"
                                                placeholder="Enter your price" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-outline mb-3">
                                            <label class="form-label" for="type">Types</label>
                                            <select name="type" id="type" class="form-control">
                                                <option value="">Select your addons type</option>
                                                <option value="One Time">One Time</option>
                                                <option value="Weekly">Weekly</option>
                                                <option value="Monthly">Monthly</option>
                                                <option value="Yearly">Yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-outline mb-3">
                                            <label class="form-label" for="rating">Rating</label>
                                            <select name="rating" id="rating" class="form-control">
                                                <option value="">Select your addons rating</option>
                                                <option value="1.0">1.0 star</option>
                                                <option value="1.5">1.5 star</option>
                                                <option value="2.0">2.0 star</option>
                                                <option value="2.5">2.5 star</option>
                                                <option value="3.0">3.0 star</option>
                                                <option value="3.5">3.5 star</option>
                                                <option value="4.0">4.0 star</option>
                                                <option value="4.5">4.5 star</option>
                                                <option value="5.0">5.0 star</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-outline mb-3">
                                            <label class="form-label" for="total_rating">Total rating</label>
                                            <input type="number" id="total_rating" name="total_rating"
                                                class="form-control" placeholder="Enter your total_rating" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-outline mb-3">
                                    <label class="form-label" for="review">Review</label>
                                    <textarea id="review" name="review" class="form-control" placeholder="Enter your review"></textarea>
                                </div>

                                <div class="form-outline mb-3">
                                    <input type="checkbox" name="status" id="status">
                                    <label class="form-label"for="status">Status</label>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal"
                                    onclick="modalToggol()">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="row mt-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pt-3 pb-1">
                            <h4>
                                Addons Pack
                                <!-- Button trigger modal -->
                                <button style="float: right;" type="button" onclick="modalToggol()"
                                    class="btn btn-primary sm" data-mdb-toggle="modal" data-mdb-target="#exampleModal">
                                    Add addons pack
                                </button>
                            </h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive" id="desktoptable">
                                <table id="example" class="table table-striped dt-responsive nowrap"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Thumbnail</th>
                                            <th>Title</th>
                                            <th>Heading</th>
                                            <th>Price</th>
                                            <th>Type</th>
                                            <th>Ratting</th>
                                            <th>T. Rating</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($addons as $addon)
                                            <tr>
                                                <td style="text-align: center">
                                                    <img class="zoom" src="{{ asset('addon_image/' . $addon->image) }}"
                                                        alt="" width="50px" height="30px" srcset="">
                                                </td>
                                                <td>{{ $addon->title }}</td>
                                                <td>{{ $addon->heading }}</td>
                                                <td>{{ $addon->price }}</td>
                                                <td>{{ $addon->type }}</td>
                                                <td>{{ $addon->rating }}</td>
                                                <td>{{ $addon->total_rating }}</td>
                                                <td style="text-align: right">
                                                    <button style="color: #ff5733;padding: 0px 5px;" type="button"
                                                        class="btn m-0 sm"
                                                        onclick="editModalToggol({{ $addon->id }})"> <i
                                                            class="fas fa-edit    "></i> </button>
                                                    <button style="color: #ff5733;padding: 0px 5px;" type="button"
                                                        onclick="deleteAddon({{ $addon->id }})" class="btn m-0 sm">
                                                        <i class="fa fa-trash"></i> </button>
                                                </td>
                                            </tr>


                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editModal{{ $addon->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add addons pack
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-mdb-dismiss="modal" aria-label="Close"
                                                                onclick="editModalToggol({{ $addon->id }})">x</button>
                                                        </div>
                                                        <form action="{{ route('admin.addon.pack.update') }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $addon->id }}">
                                                            <div class="modal-body">
                                                                <div class="form-outline mb-3">
                                                                    <label class="form-label" for="title">Title</label>
                                                                    <input type="text" id="title" name="title"
                                                                        class="form-control" value="{{ $addon->title }}"
                                                                        placeholder="Enter your title" />
                                                                </div>

                                                                <div class="form-outline mb-3">
                                                                    <label class="form-label"
                                                                        for="heading">Heading</label>
                                                                    <input type="text" id="heading" name="heading"
                                                                        class="form-control"
                                                                        value="{{ $addon->heading }}"
                                                                        placeholder="Enter your heading" />
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <div class="form-outline mb-3">
                                                                            <label class="form-label"
                                                                                for="thumbnail">Thumbnail</label>
                                                                            <input type="file"
                                                                                id="thumbnailEidt{{ $addon->id }}"
                                                                                onchange="thumbnailEdit({{ $addon->id }}, this)"
                                                                                name="thumbnail" class="form-control"
                                                                                placeholder="Enter your thumbnail" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="hidden" name="oldImage"
                                                                            value="{{ $addon->image }}">
                                                                        <img id="previewImgEdit{{ $addon->id }}"
                                                                            src="{{ asset('addon_image/' . $addon->image) }}"
                                                                            style="width: 100%;max-height: 90px;"
                                                                            alt="">
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-outline mb-3">
                                                                            <label class="form-label"
                                                                                for="price">Price</label>
                                                                            <input type="number" id="price"
                                                                                name="price" class="form-control"
                                                                                value="{{ $addon->price }}"
                                                                                placeholder="Enter your price" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-outline mb-3">
                                                                            <label class="form-label"
                                                                                for="type">Types</label>
                                                                            <select name="type" id="type"
                                                                                class="form-control">
                                                                                <option value="">Select your addons
                                                                                    type</option>
                                                                                <option value="One Time"
                                                                                    {{ $addon->type == 'One Time' ? 'selected' : '' }}>
                                                                                    One Time</option>
                                                                                <option value="Weekly"
                                                                                    {{ $addon->type == 'Weekly' ? 'selected' : '' }}>
                                                                                    Weekly</option>
                                                                                <option value="Monthly"
                                                                                    {{ $addon->type == 'Monthly' ? 'selected' : '' }}>
                                                                                    Monthly</option>
                                                                                <option value="Yearly"
                                                                                    {{ $addon->type == 'Yearly' ? 'selected' : '' }}>
                                                                                    Yearly</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-outline mb-3">
                                                                            <label class="form-label"
                                                                                for="rating">Rating</label>
                                                                            <select name="rating" id="rating"
                                                                                class="form-control">
                                                                                <option value="">Select your addons
                                                                                    rating</option>
                                                                                <option value="1.0"
                                                                                    {{ $addon->rating == '1.0' ? 'selected' : '' }}>
                                                                                    1.0 star</option>
                                                                                <option value="1.5"
                                                                                    {{ $addon->rating == '1.5' ? 'selected' : '' }}>
                                                                                    1.5 star</option>
                                                                                <option value="2.0"
                                                                                    {{ $addon->rating == '2.0' ? 'selected' : '' }}>
                                                                                    2.0 star</option>
                                                                                <option value="2.5"
                                                                                    {{ $addon->rating == '2.5' ? 'selected' : '' }}>
                                                                                    2.5 star</option>
                                                                                <option value="3.0"
                                                                                    {{ $addon->rating == '3.0' ? 'selected' : '' }}>
                                                                                    3.0 star</option>
                                                                                <option value="3.5"
                                                                                    {{ $addon->rating == '3.5' ? 'selected' : '' }}>
                                                                                    3.5 star</option>
                                                                                <option value="4.0"
                                                                                    {{ $addon->rating == '4.0' ? 'selected' : '' }}>
                                                                                    4.0 star</option>
                                                                                <option value="4.5"
                                                                                    {{ $addon->rating == '4.5' ? 'selected' : '' }}>
                                                                                    4.5 star</option>
                                                                                <option value="5.0"
                                                                                    {{ $addon->rating == '5.0' ? 'selected' : '' }}>
                                                                                    5.0 star</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-outline mb-3">
                                                                            <label class="form-label"
                                                                                for="total_rating">Total rating</label>
                                                                            <input type="number" id="total_rating"
                                                                                name="total_rating" class="form-control"
                                                                                value="{{ $addon->total_rating }}"
                                                                                placeholder="Enter your total_rating" />
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-outline mb-3">
                                                                    <label class="form-label"
                                                                        for="review">Review</label>

                                                                    <textarea id="review" name="review" class="form-control" placeholder="Enter your review">{{ $addon->review }}</textarea>
                                                                </div>

                                                                <div class="form-outline mb-3">
                                                                    <input type="checkbox" name="status" id="status"
                                                                        {{ $addon->status == 1 ? 'checked' : '' }}>
                                                                    <label class="form-label"for="status">Status</label>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-mdb-dismiss="modal"
                                                                    onclick="editModalToggol({{ $addon->id }})">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive" id="mobiletable">
                                <table class="table" width="100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

    <script>
        function modalToggol() {
            $('#exampleModal').toggle();
        }

        function editModalToggol(id) {
            $('#editModal' + id).toggle();
        }
    </script>

    <script>
        function previewFile(input) {
            var file = $("input[type=file]").get(0).files[0];

            if (file) {
                var reader = new FileReader();

                reader.onload = function() {
                    $("#previewImg").attr("src", reader.result);
                }

                reader.readAsDataURL(file);
            }
        }

        function thumbnailEdit(id, input) {
            var file = $("#thumbnailEidt" + id).get(0).files[0];
            if (file) {
                var reader = new FileReader();

                reader.onload = function() {
                    $("#previewImgEdit" + id).attr("src", reader.result);
                }

                reader.readAsDataURL(file);
            }
        }

        function deleteAddon(id) {
            $.get("{{ route('admin.addon.pack.delete') }}", {
                id: id
            }, function(data) {
                location.reload();
            });
        }
    </script>
@endpush
