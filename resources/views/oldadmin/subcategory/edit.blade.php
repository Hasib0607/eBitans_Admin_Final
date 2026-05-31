@extends('admin.layouts.main')
@push('styles')
    <style>
        .fade:not(.show) {
            opacity: 1 !important;
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

        .FileNameCaptionStyle {
            font-size: 12px;
        }
    </style>
@endpush
@section('content')
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
                    $categorys = 1;
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
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/') }}/products">
                                    <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            পণ্য
                                        @else
                                            Products
                                        @endif
                                    </span>
                                </a>
                            </li>
                            @if ((isset($category) && $category == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/category">
                                        <img src="{{ URL::to('/') }}/img/icons/categories.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ক্যাটাগরি
                                            @else
                                                Categories
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if ((isset($subcategory) && $subcategory == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{ route('admin.subcategory.index') }}">
                                        <img src="{{ URL::to('/') }}/img/subcategory.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                সাব ক্যাটাগরি
                                            @else
                                                Sub Categories
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                            @if ((isset($attribute) && $attribute == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/attribute">
                                        <img src="{{ URL::to('/') }}/img/icons/product.png"><br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পণ্যের ধরণ
                                            @else
                                                Variants
                                            @endif
                                        </span>

                                    </a>
                                </li>
                            @endif
                            @if ((isset($brand) && $brand == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/brand">
                                        <img src="{{ URL::to('/') }}/img/icons/brand.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ব্রান্ড
                                            @else
                                                Brands
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif

                            @if ((isset($supplier) && $supplier == '1') || Auth::user()->type == 'admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ URL::to('/') }}/supplier">
                                        <img src="{{ URL::to('/') }}/img/icons/supplier.png"> <br><span
                                            class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                সরবরাহকারী
                                            @else
                                                Suppliers
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
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            এডিট সাব ক্যাটাগরি
                        @else
                            Edit Subcategory
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active"><a href="{{ URL::to('/') }}/subcategory">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    তালিকায় ফিরে যান
                                @else
                                    Back to List
                                @endif
                            </a></li>
                        <!--<li><a href="">Export</a></li>-->
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <form action="{{ URL::to('/') }}/subcategory/{{ $category->id }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            নাম
                                        @else
                                            Name
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="staticEmail" name="name"
                                            value="{{ $category->name }}">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ক্যাটাগরি
                                        @else
                                            Category
                                        @endif
                                    </label>
                                    <div class="col-sm-4">
                                        <select class="form-select" name="parent">
                                            <?php
                                            $categories = DB::table('categories')
                                                ->where('parent', 0)
                                                ->where('store_id', $store_id)
                                                ->get();
                                            ?>
                                            @foreach ($categories as $cats)
                                                <option value="{{ $cats->id }}"
                                                    @if ($category->parent == $cats->id) selected @endif>{{ $cats->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('parent')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="icon" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আইকন
                                        @else
                                            Icon
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <!--<i class="fa {{ $category->icon }}"></i>-->
                                        <img src="{{ URL::to('/') }}/assets/images/icon/{{ $category->icon }}"
                                            width="50px" style="margin-bottom:10px;">
                                        <br>
                                        <!--<input type="text" class="form-control icon" id="icon" name="icon" style="height:40px">-->
                                        <select id='iconpack' name="icon" class="form-control">
                                            <option value="null">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    আইকন নির্বাচন করুন
                                                @else
                                                    Select Icon
                                                @endif
                                            </option>
                                            <?php
                                            $icons = DB::table('iconpacks')->get();
                                            ?>
                                            @if (isset($icons) && count($icons) > 0)
                                                @foreach ($icons as $icon)
                                                    <option value='{{ $icon->image }}'>{{ $icon->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('icon')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="banner" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ব্যানার
                                        @else
                                            Banner
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <img src="{{ URL::to('/') }}/assets/images/category/{{ $category->banner }}"
                                            width="150px" style="margin-bottom:10px;">
                                        <br>

                                        <output id="Filelist"></output>
                                        <input type="file" class="form-control" id="banner" name="banner">
                                        @error('banner')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            স্ট্যাটাস
                                        @else
                                            Status
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch is-filled" style="text-align:center;">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                                name="status" style="margin:0 auto;"
                                                @if ($category->status = 'active') checked="" @endif>
                                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                        </div>
                                        @error('status')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            অবস্থান
                                        @else
                                            Position
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" id="position" name="position"
                                            value="{{ $category->position }}">
                                        @error('position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-2 col-form-label"></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-info">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                হালনাগাদ
                                            @else
                                                Update
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
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
                .querySelector("#banner")
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
                .getElementById("banner")
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


    <script>
        $('.icon').iconpicker();
        $('.action-destroy').on('click', function() {
            $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
        });
    </script>
@endpush
