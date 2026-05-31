@extends('admin.layouts.main')
@push('styles')
    {{--main styles--}}
    <style>
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

        .avatar-preview > div {
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

        .productTableTd {
            padding: 0;
        }

        .productInput {
            height: 45px;
            border: 1px solid #ddd;
            background: #f4f4f4;
            padding-left: 10px;
        }

        .productInput:focus-visible {
            outline: 1px solid #777777;
        }
    </style>

    <style>
        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white;
        }

        .bootstrap-tagsinput .tag [data-role="remove"] {
            margin-left: 8px;
            cursor: pointer;
        }

        .bootstrap-tagsinput .tag [data-role="remove"]::after {
            content: "x";
            padding: 0px 2px;
        }

        .bootstrap-tagsinput .tag [data-role="remove"] {
            cursor: pointer;
        }

        .card {
            border: 1px solid rgba(222, 226, 230, 0.7);
        }

        .card .card-body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            padding: .5rem 1.5rem 1.5rem 1.5rem;
        }

        .card .card-header {
            padding: .5rem 1.5rem .5rem 1.5rem;
            border-bottom: 1px solid rgba(222, 226, 230, 0.7);
        }

        .size li {
            float: left;
        }

        .bootstrap-tagsinput .tag [data-role="remove"]:after {
            content: '\00d7';
        }


        /* This is copied from https://github.com/blueimp/jQuery-File-Upload/blob/master/css/jquery.fileupload.css */
        .fileinput-button {
            position: relative;
            overflow: hidden;
        }

        #imgList {
            display: contents;
        }

        .fileinput-button input {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            opacity: 0;
            -ms-filter: "alpha(opacity=0)";
            font-size: 200px;
            direction: ltr;
            cursor: pointer;
        }

        .thumb {
            height: 95px;
            width: 100%;
            border: 1px solid #000;
        }

        ul.thumb-Images li {
            width: 120px;
            float: left;
            display: inline-block;
            vertical-align: top;
            height: 100px;
        }

        .img-wrap {
            position: relative;
            display: inline-block;
            font-size: 0;
        }

        .img-wrap .close {
            position: absolute;
            top: 2px;
            right: 2px;
            z-index: 100;
            background-color: #d0e5f5;
            padding: 5px 2px 2px;
            color: #000;
            font-weight: bolder;
            cursor: pointer;
            opacity: 0.5;
            font-size: 23px;
            line-height: 10px;
            border-radius: 50%;
        }

        .img-wrap:hover .close {
            opacity: 1;
            background-color: #ff0000;
        }

        @media (max-width: 500px) {
            #submitBtnSection .btn, #submitBtnSection a {
                font-size: 10px;
                padding: 10px 13px;
            }
        }

        .input-upload.d-flex {
            flex-wrap: wrap;
        }
    </style>

    <style>
        .card {
            min-height: 135px;
            padding: 15px;
        }

        .col-lg-9, .col-lg-6, .col-lg-3, .col-lg-12 {
            padding: 3px;
        }

        .image-box {
            font-size: x-small;
            border: 1px dashed black;
            height: 95px;
            width: 95px;
            cursor: pointer;
        }

        .image-box input[type="file"] {
            opacity: 0;
            height: 95px;
            width: 95px;
            position: absolute;
            z-index: 2;
        }

        .image-box .content {
            position: relative;
            z-index: 1;
            color: #007bff;
        }

        .image-box .content h1 {
            font-size: 30px;
            margin: 0;
        }

        .image-box .content p {
            font-size: 10px;
        }

        img.thub {
            height: 95px;
            width: 95px;
        }

        input.tagBro {
            width: inherit;
        }

        .groupItemDiv span {
            width: 100% !important;
        }

    </style>
    
    <style>
        .oldImg-wrap {
            position: relative;
            display: inline-block;
            font-size: 0;
        }

        .oldImg-wrap .oldClose {
            position: absolute;
            top: 3px;
            right: 2px;
            z-index: 100;
            background-color: #d0e5f5;
            padding: 2px 3px 2px 3px;
            color: #000;
            font-weight: bolder;
            cursor: pointer;
            opacity: 0.5;
            font-size: 12px;
            line-height: 10px;
            border-radius: 50%;
        }

        .oldImg-wrap:hover .oldClose {
            opacity: 1;
            background-color: #ff0000;
        }

    </style>
    <style>
        /* This is copied from https://github.com/blueimp/jQuery-File-Upload/blob/master/css/jquery.fileupload.css */
        .fileinput-button {
            position: relative;
            overflow: hidden;
        }

        #imgListModal {
            display: contents;
        }

        .fileinput-button input {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            opacity: 0;
            -ms-filter: "alpha(opacity=0)";
            font-size: 200px;
            direction: ltr;
            cursor: pointer;
        }

        .thumb {
            height: 95px;
            width: 100%;
            border: 1px solid #000;
        }

        ul.thumb-Images li {
            width: 120px;
            float: left;
            display: inline-block;
            vertical-align: top;
            height: 100px;
        }

        .img-wrap {
            position: relative;
            display: inline-block;
            font-size: 0;
        }

        .img-wrap .close {
            position: absolute;
            top: 2px;
            right: 2px;
            z-index: 100;
            background-color: #d0e5f5;
            padding: 5px 2px 2px;
            color: #000;
            font-weight: bolder;
            cursor: pointer;
            opacity: 0.5;
            font-size: 23px;
            line-height: 10px;
            border-radius: 50%;
        }

        .img-wrap:hover .close {
            opacity: 1;
            background-color: #ff0000;
        }


        .input-upload.d-flex {
            flex-wrap: wrap;
        }
    </style>
    <style>
        .card {
            min-height: 135px;
            padding: 15px;
        }

        .col-lg-9, .col-lg-6, .col-lg-3, .col-lg-12 {
            padding: 3px;
        }

        .image-box {
            font-size: x-small;
            border: 1px dashed black;
            height: 95px;
            width: 95px;
            cursor: pointer;
        }

        .image-box input[type="file"] {
            opacity: 0;
            height: 95px;
            width: 95px;
            position: absolute;
            z-index: 2;
        }

        .image-box .content {
            position: relative;
            z-index: 1;
            color: #007bff;
        }

        .image-box .content h1 {
            font-size: 30px;
            margin: 0;
        }

        .image-box .content p {
            font-size: 10px;
        }

        img.thub {
            height: 95px;
            width: 95px;
        }

        input.tagBro {
            width: inherit;
        }

        .groupItemDiv span {
            width: 100% !important;
        }

        #toplist ul li {
            float: right;
            padding: 2px 5px;
            border: 1px solid #5e4c4c33;
            margin: 3px;
        }

        .overlayLoading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            z-index: 1000;
            border-radius: 12px;
        }

    </style>

@endpush
@section('content')
    <div class="modal fade" id="imageModal" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content"
                 style="background-color:transparent;border:0px">

                <div class="modal-body" style="border:none">
                    <button class="btn btn-danger sm" data-bs-dismiss="modal"
                            style="float: right; margin: 0px 8px;">X
                    </button>

                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="card h-100">
                                <div class="d-flex">
                                    <div class="input-upload d-flex" style="padding: 0;">
                                        <output id="Filelist">
                                            <ul class="thumb-Images overflow-x-auto" id="imgList">
                                                <li class="image-box mx-2" style="height: 95px; width: 105px">
                                                    <input type="file" class="form-control" id="image"
                                                           name="image[]"
                                                           multiple accept="image/*">
                                                    <div class="content text-center" id="placeholder">
                                                        <p></p>
                                                        <h1>+</h1>
                                                        <p>Upload Image</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </output>
                                    </div>
                                </div>
                                <div class="mt-3 text-end" id="imageOkBtn" style="display: none">
                                    <button class="btn btn-primary mb-0" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    {{--website setup main section--}}
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!--addon nav bar component-->
        @include('admin.addon.share.addons-nav', ["website_setup"=>true])

        {{--website setup container section--}}
        <div class="container-fluid mt-4" id="toplist">

            {{--header section--}}
            <div class="row">
                <div class="col-md-6">
                    <h4>Website Setup</h4>
                </div>
                <div class="col-md-6">
                </div>
            </div>

            {{--website setup card section--}}
            <div class="row mt-5 productlist">
                <div class="col-12">

                    {{--if having website setup request--}}
                    @if(isset($websitesetup))
                        @if(isset($websitesetup->data_submit) && $websitesetup->data_submit == 0)
                            <!-- Overlay Loading Screen -->
                            <div id="overlay" style="
                                position: fixed;
                                top: 0; left: 0;
                                width: 100%; height: 100%;
                                background: rgba(0, 0, 0, 0.6);
                                display: none; /* Hidden by default */
                                justify-content: center;
                                align-items: center;
                                flex-direction: column;
                                color: white;
                                z-index: 1000;
                            ">
                                <p>Uploading, please wait...</p>
                                <progress id="uploadProgress" value="0" max="100" style="width: 50%;"></progress>
                                <p id="uploadPercentage">0%</p>
                            </div>


                            <div class="card">
                                <div class="card-header" style="padding: 15px 15px 5px;">
                                    <h6>Setup Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <form class="col-12 col-md-12"
                                              action="{{ route("admin.websitesetup.save.setup.details") }}"
                                              id="detailsForm" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row form-row align-items-center">
                                                <!-- Facebook Link -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="facebook_link">Facebook Link (Optional)</label>
                                                    <input
                                                        type="text"
                                                        id="facebook_link"
                                                        name="facebook_link"
                                                        placeholder="Facebook Link"
                                                        class="form-control"
                                                        value="{{ old("facebook_link") }}"
                                                    />

                                                    @error('facebook_link')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Instagram Link -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="instagram_link">Instagram Link (Optional)</label>
                                                    <input
                                                        type="text"
                                                        id="instagram_link"
                                                        name="instagram_link"
                                                        placeholder="Instagram Link"
                                                        class="form-control"
                                                        value="{{ old("instagram_link") }}"
                                                    />

                                                    @error('instagram_link')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Mobile Number -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="mobile_number">Mobile Number <span
                                                            class="text-danger">*</span></label>
                                                    <input
                                                        type="text"
                                                        id="mobile_number"
                                                        name="mobile_number"
                                                        placeholder="Mobile Number"
                                                        class="form-control"
                                                        value="{{ old("mobile_number") }}"
                                                    />

                                                    @error('mobile_number')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Whats App Number -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="whats_app_number">Whats App Number <span
                                                            class="text-danger">*</span></label>
                                                    <input
                                                        type="text"
                                                        id="whats_app_number"
                                                        name="whats_app_number"
                                                        placeholder="Whats App Number"
                                                        class="form-control"
                                                        value="{{ old("whats_app_number") }}"
                                                    />

                                                    @error('whats_app_number')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Youtube Link -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="youtube_link">Youtube Link (Optional)</label>
                                                    <input
                                                        type="text"
                                                        id="youtube_link"
                                                        name="youtube_link"
                                                        placeholder="Youtube Link"
                                                        class="form-control"
                                                        value="{{ old("youtube_link") }}"
                                                    />

                                                    @error('youtube_link')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Email Address -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="email">Email Address <span
                                                            class="text-danger">*</span></label>
                                                    <input
                                                        type="text"
                                                        id="email"
                                                        name="email"
                                                        placeholder="Email Address"
                                                        class="form-control"
                                                        value="{{ old("email") }}"
                                                    />

                                                    @error('email')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Delivery Cost -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="delivery_cost">Delivery Cost <span
                                                            class="text-danger">*</span></label>
                                                    <input
                                                        type="text"
                                                        id="delivery_cost"
                                                        name="delivery_cost"
                                                        placeholder="Delivery Cost"
                                                        class="form-control"
                                                        value="{{ old("delivery_cost") }}"
                                                    />

                                                    @error('delivery_cost')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Tax -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="tax">Tax (Optional)</label>
                                                    <input
                                                        type="text"
                                                        id="tax"
                                                        name="tax"
                                                        placeholder="Tax"
                                                        class="form-control"
                                                        value="{{ old("tax") }}"
                                                    />

                                                    @error('tax')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Address -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="address">Address (Optional)</label>
                                                    <input
                                                        type="text"
                                                        id="address"
                                                        name="address"
                                                        placeholder="Address"
                                                        class="form-control"
                                                        value="{{ old("address") }}"
                                                    />

                                                    @error('address')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Logo -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="logo">Logo <span
                                                            class="text-danger">*</span></label>
                                                    <input
                                                        type="file"
                                                        id="logo"
                                                        name="logo"
                                                        class="form-control"
                                                    />

                                                    @error('logo')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                                <!-- Theme color  -->
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="theme_color">Theme color <span
                                                            class="text-danger">*</span></label>
                                                    <input
                                                        type="color"
                                                        id="theme_color"
                                                        name="theme_color"
                                                        class="form-control"
                                                        value="{{ old("theme_color") }}"
                                                    />

                                                    @error('theme_color')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>


                                                <!-- Short Description -->
                                                <div class="form-group col-md-6 col-lg-6 mt-3">
                                                    <label for="short_description">Short Description (Optional)</label>
                                                    <textarea
                                                        id="short_description"
                                                        name="short_description"
                                                        placeholder="Short Description"
                                                        class="form-control"
                                                        rows="3"
                                                    >
                                                        {{ old('short_description') }}
                                                    </textarea>

                                                    @error('short_description')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>

                                            </div>
                                        </form>

                                        <div class="col-12 col-md-12">
                                            <div class="mt-5">
                                                <div class="col-md-12">
                                                    <h6>Product Details</h6>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead class="thead-dark">
                                                        <tr>
                                                            <th width="5%">SL</th>
                                                            <th>Product Name</th>
                                                            <th>Category</th>
                                                            <th>Price</th>
                                                            <th>Image</th>
                                                            <th>Product Description</th>
                                                            <th>Other Info</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tableBody">
                                                        {!! $productView !!}
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <td class="productTableTd">
                                                                <div class="d-flex flex-column gap-2">
                                                                    <input type="text" class="productInput" value=""
                                                                           id="product_name"
                                                                           name="product_name"
                                                                           placeholder="Product Name"/>
                                                                    <input type="text" class="productInput" value=""
                                                                           id="category"
                                                                           name="category" placeholder="Category"/>
                                                                    <input type="text" class="productInput" value=""
                                                                           id="price" name="price"
                                                                           placeholder="Price"/>
                                                                </div>
                                                            </td>
                                                            <td class="productTableTd" colspan="2">
                                                                {{--                                                                <input type="text" class="productInput" value=""--}}
                                                                {{--                                                                       id="description"--}}
                                                                {{--                                                                       name="description" placeholder="Description"/>--}}
                                                                <textarea class="productInput" id="description"
                                                                          name="description"
                                                                          style="height: 125px; width: 100%"
                                                                          placeholder="Product Description"></textarea>
                                                            </td>
                                                            <td class="productTableTd">
                                                                <button class="btn btn-secondary"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#imageModal"
                                                                        data-title="Pay"
                                                                        id="imageModalBtn">Upload Image
                                                                </button>
                                                            </td>
                                                            <td class="productTableTd" colspan="2">
                                                                <textarea class="productInput" id="other_info"
                                                                          name="other_info"
                                                                          style="height: 125px; width: 100%"
                                                                          placeholder="Product Other info, SKU, Sub-category, brand, supplier,cost,discount,color,size,unit"></textarea>
                                                            </td>

                                                            <td style="padding-top: 25px;">
                                                                <button class="btn btn-primary" id="btnAddProduct">Add
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                            </div>

                                            <div class="d-flex mt-5">
                                                <button class="btn btn-primary" id="btnSubmit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="card">
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row">

                                            {{--showing process status--}}
                                            <div class="col-12 col-md-12 hh-grayBox pt45 pb20">
                                                <div class="row justify-content-between"
                                                     style="overflow:hidden">
                                                    <div
                                                        class="order-tracking @if($websitesetup->status == 'Pending') completed @elseif($websitesetup->status=='Processing') completed @elseif($websitesetup->status=='Working') completed @elseif($websitesetup->status=='Complete') completed @endif">
                                                        <span class="is-complete"></span>
                                                        <p>Pending<br>
                                                            <!--<span>Mon, June 24</span>-->
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="order-tracking @if($websitesetup->status=='Processing') completed @elseif($websitesetup->status=='Working') completed @elseif($websitesetup->status=='Complete') completed @endif">
                                                        <span class="is-complete"></span>
                                                        <p>Processing<br>
                                                            <!--<span>Tue, June 25</span>-->
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="order-tracking  @if($websitesetup->status=='Working') completed @elseif($websitesetup->status=='Complete') completed @endif">
                                                        <span class="is-complete"></span>
                                                        <p>Wroking<br>
                                                            <!--<span>Tue, June 25</span>-->
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="order-tracking @if($websitesetup->status=='Complete') completed @endif">
                                                        <span class="is-complete"></span>
                                                        <p>Complete<br>
                                                            <!--<span>Fri, June 28</span>-->
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h4 class="text-center">You Don't Have Any Active Website Setup
                                    Request </h4>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        //To save an array of attachments
        var AttachmentArray = [];
        const imagesArray = [];


        $("#btnAddProduct").on("click", function () {
            // const model_no = $("#model_no").val();
            const product_name = $("#product_name").val();
            const description = $("#description").val();
            const category = $("#category").val();
            // const sub_category = $("#sub_category").val();
            const price = $("#price").val();
            // const brand = $("#brand").val();
            // const supplier = $("#supplier").val();
            // const cost = $("#cost").val();
            // const discount = $("#discount").val();
            // const color = $("#color").val();
            // const size = $("#size").val();
            // const unit = $("#unit").val();
            const other_info = $("#other_info").val();

            if (product_name == "" || description == "" || category == "" || price == "") {
                swal.fire({
                    "title": "Warning",
                    "text": "Please input Product Name, Description, Category And Price",
                    "type": "error",
                });

                return false;
            }

            // const formData = {
            //     // "model_no": model_no,
            //     "product_name": product_name,
            //     "description": description,
            //     "category": category,
            //     // "sub_category": sub_category,
            //     "price": price,
            //     // "brand": brand,
            //     // "supplier": supplier,
            //     // "cost": cost,
            //     // "discount": discount,
            //     // "color": color,
            //     // "size": size,
            //     // "unit": unit,
            //     "other_info": other_info,
            // }


            const overlay = document.querySelector("#overlay");
            const progressBar = document.querySelector("#uploadProgress");
            const uploadPercentage = document.querySelector("#uploadPercentage");

            const formData = new FormData();

            formData.append("product_name", product_name);
            formData.append("description", description);
            formData.append("category", category);
            formData.append("price", price);
            formData.append("other_info", other_info);


            // Get the file input element
            const fileInput = document.querySelector("#image");

            if (fileInput.files.length > 0) {
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append("images[]", fileInput.files[i]); // Properly appending files
                }
            }

            // Show overlay before starting upload
            overlay.style.display = "flex";

            const url = "{{ route('admin.websitesetup.save.product') }}";

            axios.post(url, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
                onUploadProgress: function (progressEvent) {
                    // Calculate percentage
                    let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);

                    // Update progress bar and text
                    progressBar.value = percentCompleted;
                    uploadPercentage.innerText = percentCompleted + "%";
                }
            })
                .then(function (response) {
                    productFieldRest();

                    const result = response?.data || "";
                    const productList = response?.data?.data || "";

                    if (result?.status) {
                        swal.fire({
                            title: "Success",
                            text: result?.message,
                            type: "success",
                        });

                        $("#tableBody").html(productList);

                        overlay.style.display = "none";
                        progressBar.value = 100;
                    } else {
                        swal.fire({
                            title: "Error",
                            text: result?.message,
                            type: "error",
                        });
                    }
                })
                .catch(function (error) {
                    overlay.style.display = "none";
                    swal.fire({
                        title: "Error",
                        text: "Upload Failed!",
                        type: "error",
                    });
                });
        })

        const productFieldRest = () => {
            // $("#model_no").val("");
            $("#product_name").val("");
            $("#description").val("");
            $("#category").val("");
            // $("#sub_category").val("");
            $("#price").val("");
            // $("#brand").val("");
            // $("#supplier").val("");
            // $("#cost").val("");
            // $("#discount").val("");
            // $("#color").val("");
            // $("#size").val("");
            // $("#unit").val("");
            $("#other_info").val("")
        }

        $("#btnSubmit").on("click", function () {
            $("#detailsForm").submit();
        })


        //I added event handler for the file upload control to access the files properties.
        document.addEventListener("DOMContentLoaded", init, false);

        //counter for attachment array
        var arrCounter = 0;

        //to make sure the error message for number of files will be shown only one time.
        var filesCounterAlertStatus = false;

        //un ordered list to keep attachments thumbnails
        var ul = document.createElement("ul");
        ul.className = "thumb-Images";
        ul.id = "imgList";

        function init() {
            //add javascript handlers for the file upload event
            document
                .querySelector("#image")
                .addEventListener("change", handleFileSelect, false);
        }

        function updateHiddenField() {
            const imageField = document.getElementById("image");

            if (imageField) {
                const dataTransfer = new DataTransfer();

                imagesArray.forEach((file) => {
                    dataTransfer.items.add(file);
                });

                imageField.files = dataTransfer.files; // Directly assign files from DataTransfer
            }
        }

        //the handler for file upload event
        function handleFileSelect(e) {
            // Ensure the user selects files
            if (!e.target.files) return;

            // Obtain a File reference
            const files = e.target.files;

            // Loop through the FileList and render image files as thumbnails
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Instantiate a FileReader object to read its contents into memory
                const fileReader = new FileReader();

                // Closure to capture the file information and apply validation
                fileReader.onload = (function (readerEvt) {
                    return function (e) {
                        // Render attachments thumbnails
                        RenderThumbnail(e, readerEvt);

                        // Fill the array of attachments
                        FillAttachmentArray(e, readerEvt);

                        // Push the file into imagesArray
                        imagesArray.push(readerEvt);
                        updateHiddenField();

                        okBtnShowHide();
                    };
                })(file);

                // Read the image file as a data URL
                fileReader.readAsDataURL(file);
            }

            document
                .getElementById("image")
                .addEventListener("change", handleFileSelect, false);
        }


        const okBtnShowHide = () => {
            const arr = AttachmentArray.filter(item => item !== undefined && item !== null);

            if (arr.length > 0) {
                $("#imageOkBtn").show();
            } else {
                $("#imageOkBtn").hide();
            }
        }

        //To remove attachment once user click on x button
        jQuery(function ($) {
            $("div").on("click", ".img-wrap .close", function () {
                var id = $(this)
                    .closest(".img-wrap")
                    .find("img")
                    .data("id");

                //to remove the deleted item from array
                var elementPos = AttachmentArray.map(function (x) {
                    return x.FileName;
                }).indexOf(id);

                if (elementPos !== -1) {
                    AttachmentArray.splice(elementPos, 1);
                }

                //to remove image tag
                $(this)
                    .parent()
                    .find("img")
                    .not()
                    .remove();

                //to remove div tag that contain the image
                $(this)
                    .parent()
                    .find("div")
                    .not()
                    .remove();

                //to remove div tag that contain caption name
                $(this)
                    .parent()
                    .parent()
                    .find("div")
                    .not()
                    .remove();

                //to remove li tag
                var lis = document.querySelectorAll("#imgList li");
                for (var i = 0;
                     (li = lis[i]); i++) {
                    if (li.innerHTML == "") {
                        li.parentNode.removeChild(li);

                        okBtnShowHide();
                    }
                }
            });
        });

        //Render attachments thumbnails.
        function RenderThumbnail(e, readerEvt) {
            var li = document.createElement("li");
            ul.appendChild(li);
            li.innerHTML = [
                '<div class="img-wrap mx-2"> <span class="close">&times;</span>' +
                '<img class="thumb" src="',
                e.target.result,
                '" title="',
                escape(readerEvt.name),
                '" data-id="',
                readerEvt.name,
                '"/>' + "</div>"
            ].join("");

            document.getElementById("Filelist").insertBefore(ul, null);
        }

        //Fill the array of attachment
        function FillAttachmentArray(e, readerEvt) {
            AttachmentArray[arrCounter] = {
                AttachmentType: 1,
                ObjectType: 1,
                FileName: readerEvt.name,
                FileDescription: "Attachment",
                NoteText: "",
                MimeType: readerEvt.type,
                Content: e.target.result.split("base64,")[1],
                FileSizeInBytes: readerEvt.size
            };
            arrCounter = arrCounter + 1;
        }
    </script>

    <script>
        const deleteImage = (e, id, product_id) => {
            e.preventDefault();


            const imageWrapper = $("#imageWrapper" + id);
            const url = $("#removeImage" + id).data("href");

            // Show overlay before starting upload
            const overlayLoading = $("#overlayLoading" + product_id);

            if (overlayLoading.length) {
                overlayLoading.css("display", "flex");
            }


            axios.delete(url)
                .then(function (response) {
                    const result = response?.data || "";

                    if (overlayLoading.length) {
                        overlayLoading.css("display", "none");
                    }

                    if (result?.status) {
                        swal.fire({
                            title: "Success",
                            text: result?.message,
                            type: "success",
                        });

                        imageWrapper.remove();
                    } else {
                        swal.fire({
                            title: "Error",
                            text: result?.message,
                            type: "error",
                        });
                    }
                })
                .catch(function (error) {
                    if (overlayLoading.length) {
                        overlayLoading.css("display", "none");
                    }
                    swal.fire({
                        title: "Error",
                        text: "Something went wrong",
                        type: "error",
                    });
                });
        }

        //the handler for file upload event
        function handleFileSelectModal(e, id) {
            // Ensure the user selects files
            if (!e.target.files) return;

            // Obtain a File reference
            const files = e.target.files;

            const formData = new FormData();

            // Loop through the FileList and render image files as thumbnails
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                formData.append("images[]", file);
            }


            // Show overlay before starting upload
            const overlay = $("#overlay" + id);
            const progressBar = document.querySelector("#uploadProgress" + id);
            const uploadPercentage = document.querySelector("#uploadPercentage" + id);
            if (overlay.length) {
                overlay.css("display", "flex");
            }

            const url = "{{ route('admin.websitesetup.upload.product') }}";
            formData.append("product_id", id);

            axios.post(url, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
                onUploadProgress: function (progressEvent) {
                    // Calculate percentage
                    let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);

                    // Update progress bar and text
                    progressBar.value = percentCompleted;
                    uploadPercentage.innerText = percentCompleted + "%";
                }
            })
                .then(function (response) {
                    const result = response?.data || "";
                    const imageList = response?.data?.data || "";
                    if (overlay.length) {
                        progressBar.value = 100;
                        overlay.css("display", "none");
                    }

                    if (result?.status) {
                        swal.fire({
                            title: "Success",
                            text: result?.message,
                            type: "success",
                        });

                        $("#imageListWrapper" + id).html(imageList);
                    } else {
                        swal.fire({
                            title: "Error",
                            text: result?.message,
                            type: "error",
                        });
                    }
                })
                .catch(function (error) {
                    if (overlay.length) {
                        progressBar.value = 100;
                        overlay.css("display", "none");
                    }
                    swal.fire({
                        title: "Error",
                        text: "Upload Failed!",
                        type: "error",
                    });
                });


        }
    </script>

@endpush
