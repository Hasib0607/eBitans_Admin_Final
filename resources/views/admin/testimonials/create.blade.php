@extends('admin.layouts.main')
@section('content')
    <style>
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

        .size {
            list-style-type: none;

        }

        .size li {
            float: left;
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
            height: 80px;
            width: 100px;
            border: 1px solid #000;
        }

        ul.thumb-Images li {
            width: 120px;
            float: left;
            display: inline-block;
            vertical-align: top;
            height: 120px;
        }

        .img-wrap {
            position: relative;
            display: inline-block;
            font-size: 0;
        }

        .oldImg-wrap {
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

        .img-wrap:hover .close {
            opacity: 1;
            background-color: #ff0000;
        }

        .oldImg-wrap:hover .oldClose {
            opacity: 1;
            background-color: #ff0000;
        }

        .FileNameCaptionStyle {
            font-size: 12px;
        }
    </style>

    <?php
    if (Auth::user()->type == 'admin') {
        $customer = DB::table('customers')
            ->where('uid', Auth::user()->id)
            ->first();
        $store_id = $customer->active_store;
    } elseif (Auth::user()->type == 'staff') {
        $staff = DB::table('staff')
            ->where('uid', Auth::user()->id)
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
                } elseif ($pr == 'testimonials') {
                    $tt = 1;
                } elseif ($pr == 'designsettings') {
                    $ds = 1;
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
    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row new">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            @if ((isset($template) && $template == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ route('admin.design.theme') }}">
                                        <img src="{{ URL::to('/') }}/img/icons/web-design.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ওয়েবসাইট থিম
                                            @else
                                                Website Themes
                                            @endif
                                        </span>
                                    </a>
                                </li>

                                @if ((isset($homepage) && $homepage == '1') || Auth::user()->type == 'admin')
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a href="{{ route('admin.design.homepage.slider') }}">
                                            <img src="{{ URL::to('/') }}/img/icons/landing-page.png"> <br><span
                                                class="nav-link-text ms-1">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    হোম পেজ ডিজাইন
                                                @else
                                                    HP Layout Design
                                                @endif
                                            </span>
                                        </a>
                                    </li>
                                @endif

                                @if ((isset($header) && $header == '1') || Auth::user()->type == 'admin')
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a href="{{ route('admin.design.design') }}">
                                            <img src="{{ URL::to('/') }}/img/icons/title.png"><br><span
                                                class="nav-link-text ms-1">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    হেডার ডিজাইন
                                                @else
                                                    Header Design
                                                @endif
                                            </span>

                                        </a>
                                    </li>
                                @endif

                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.design.slider') }}">
                                        <img src="{{ URL::to('/') }}/img/icons/slider.png"> <br> <span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্লাইডার
                                            @else
                                                Slider
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif

                            @if ((isset($banner) && $banner == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ route('admin.design.banner') }}">
                                        <img src="{{ URL::to('/') }}/img/icons/ads.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                বিজ্ঞাপন ব্যানার
                                            @else
                                                Ads Banner
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                            <!--@if ((isset($layout) && $layout == '1') || Auth::user()->type == 'admin')
    -->
                            <!--<li class="breadcrumb-item" aria-current="page">-->
                            <!--    <a href="{{ route('admin.design.layout.homepage') }}">-->
                            <!--        <img src="{{ URL::to('/') }}/img/icons/subcategory.png" > <br>Invoice-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--
    @endif-->


                            @if ((isset($tt) && $tt == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{ route('admin.testimonials') }}">
                                        <img src="{{ URL::to('/') }}/img/icons/testimonial.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                প্রশংসাপত্র
                                            @else
                                                Testimonials
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif

                            @if ((isset($pages) && $pages == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ route('admin.pages') }}">
                                        <img src="{{ URL::to('/') }}/img/icons/team.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অন্যান্য পেইজ
                                            @else
                                                Other Pages
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ route('admin.design.homepage.invoice') }}">
                                        <img src="{{ URL::to('/') }}/img/icons/bill-2.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                চালান টেমপ্লেট
                                            @else
                                                Invoice Template
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
        <section class="container content-main">
            <div class="row">
                <form action="{{ route('admin.testimonials.save') }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title"></h2>
                                </div>

                                <div class="col-md-6" style="text-align:right">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6" style="margin:0 auto;">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            নতুন প্রশংসাপত্র যোগ করুন
                                        @else
                                            Add New Testimonials
                                        @endif
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <output id="Filelist">
                                        </output>
                                        <br>
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ছবি
                                            @else
                                                Image
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <input type="file" placeholder="Type here" class="form-control" id="image"
                                            name="image">
                                        @error('image')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Name
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control" id="title"
                                            name="name" value="{{ Request::old('name') }}">
                                        @error('name')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পেশা
                                            @else
                                                Occupation
                                            @endif
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control" id="subtitle"
                                            name="occupation" value="{{ old('occupation') }}">
                                        @error('occupation')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                প্রতিক্রিয়া
                                            @else
                                                Feedback
                                            @endif
                                        </label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="link" name="feedback">-->
                                        <textarea class="form-control" name="feedback" id="feedback" rows="7">{{ old('feedback') }}</textarea>
                                        @error('feedback')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অবস্থান
                                            @else
                                                Position
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <input type="number" placeholder="Type here" class="form-control"
                                            id="position" name="position" value="{{ old('position') }}">
                                        @error('position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4 row">
                                        <label for="staticEmail" class="col-md-2 col-form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্টেটাস
                                            @else
                                                Status
                                            @endif
                                        </label>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch is-filled"
                                                style="text-align:center;padding-top:14px;">
                                                <input class="form-check-input" type="checkbox"
                                                    id="flexSwitchCheckChecked" name="status" style="margin:0 auto;"
                                                    checked="">
                                                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                            </div>
                                            @error('status')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label"></label>
                                        <button class="btn btn-info rounded font-sm hover-up" type="submit">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                প্রকাশ
                                            @else
                                                Publish
                                            @endif
                                        </button>
                                    </div>

                                </div>
                            </div> <!-- card end// -->

                        </div>

                    </div>
            </div>

            </form>
            </div>
        </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        //I added event handler for the file upload control to access the files properties.
        document.addEventListener("DOMContentLoaded", init, false);

        //To save an array of attachments
        var AttachmentArray = [];

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

        //the handler for file upload event
        function handleFileSelect(e) {
            //to make sure the user select file/files
            if (!e.target.files) return;

            //To obtaine a File reference
            var files = e.target.files;

            // Loop through the FileList and then to render image files as thumbnails.
            for (var i = 0, f;
                (f = files[i]); i++) {
                //instantiate a FileReader object to read its contents into memory
                var fileReader = new FileReader();

                // Closure to capture the file information and apply validation.
                fileReader.onload = (function(readerEvt) {
                    return function(e) {
                        //Apply the validation rules for attachments upload
                        ApplyFileValidationRules(readerEvt);

                        //Render attachments thumbnails.
                        RenderThumbnail(e, readerEvt);

                        //Fill the array of attachment
                        FillAttachmentArray(e, readerEvt);
                    };
                })(f);

                // Read in the image file as a data URL.
                // readAsDataURL: The result property will contain the file/blob's data encoded as a data URL.
                // More info about Data URI scheme https://en.wikipedia.org/wiki/Data_URI_scheme
                fileReader.readAsDataURL(f);
            }
            document
                .getElementById("image")
                .addEventListener("change", handleFileSelect, false);
        }

        //To remove attachment once user click on x button
        jQuery(function($) {
            $("div").on("click", ".img-wrap .close", function() {
                var id = $(this)
                    .closest(".img-wrap")
                    .find("img")
                    .data("id");

                //to remove the deleted item from array
                var elementPos = AttachmentArray.map(function(x) {
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
                    }
                }
            });
        });

        //Apply the validation rules for attachments upload
        function ApplyFileValidationRules(readerEvt) {
            //To check file type according to upload conditions
            if (CheckFileType(readerEvt.type) == false) {
                alert(
                    "The file (" +
                    readerEvt.name +
                    ") does not match the upload conditions, You can only upload jpg/png/gif files"
                );
                e.preventDefault();
                return;
            }

            //To check file Size according to upload conditions
            if (CheckFileSize(readerEvt.size) == false) {
                alert(
                    "The file (" +
                    readerEvt.name +
                    ") does not match the upload conditions, The maximum file size for uploads should not exceed 300 KB"
                );
                e.preventDefault();
                return;
            }

            //To check files count according to upload conditions
            if (CheckFilesCount(AttachmentArray) == false) {
                if (!filesCounterAlertStatus) {
                    filesCounterAlertStatus = true;
                    alert(
                        "You have added more than 10 files. According to upload conditions you can upload 10 files maximum"
                    );
                }
                e.preventDefault();
                return;
            }
        }

        //To check file type according to upload conditions
        function CheckFileType(fileType) {
            if (fileType == "image/jpeg") {
                return true;
            } else if (fileType == "image/png") {
                return true;
            } else if (fileType == "image/gif") {
                return true;
            } else {
                return false;
            }
            return true;
        }

        //To check file Size according to upload conditions
        function CheckFileSize(fileSize) {
            if (fileSize < 200000) {
                return true;
            } else {
                return false;
            }
            return true;
        }

        //To check files count according to upload conditions
        function CheckFilesCount(AttachmentArray) {
            //Since AttachmentArray.length return the next available index in the array,
            //I have used the loop to get the real length
            var len = 0;
            for (var i = 0; i < AttachmentArray.length; i++) {
                if (AttachmentArray[i] !== undefined) {
                    len++;
                }
            }
            //To check the length does not exceed 10 files maximum
            if (len > 9) {
                return false;
            } else {
                return true;
            }
        }

        //Render attachments thumbnails.
        function RenderThumbnail(e, readerEvt) {
            var li = document.createElement("li");
            ul.appendChild(li);
            li.innerHTML = [
                '<div class="img-wrap"> <span class="close">&times;</span>' +
                '<img class="thumb" src="',
                e.target.result,
                '" title="',
                escape(readerEvt.name),
                '" data-id="',
                readerEvt.name,
                '"/>' + "</div>"
            ].join("");

            // var div = document.createElement("div");
            // div.className = "FileNameCaptionStyle";
            // li.appendChild(div);
            // div.innerHTML = [readerEvt.name].join("");

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
@endpush
