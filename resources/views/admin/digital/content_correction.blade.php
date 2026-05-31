@extends('admin.layouts.main')
@push('styles')
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
            transition: transform .2s; /* Animation */
            margin: 0 auto;
        }

        .zoom:hover {
            transform: scale(7.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }

        .slider_desc {
            margin: 16px;
            margin-top: 0px;
            color: #333333;
            font-family: Arial;
            font-size: 14px;
            line-height: 18px;
            text-align: justify;
            overflow: hidden;
            transition: all 0.5s ease 0s;
            max-height: 38px;
        }

        .slider_desc_toogler {
            border-top: silver 1px dotted;
            margin-bottom: 30px;
            margin-top: 20px;
            width: 70%;
            margin-left: auto;
            margin-right: auto;
        }

        .slider_desc_toogler i {
            position: absolute;
            text-align: center;
            color: silver;
            font-size: 25px;
            font-family: fontawesome;
            left: calc(50% - 10px);
            margin-top: -13px;
            background: #fff;
        }

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

        .chatbox2 {
            width: 100%;
            height: 650px;
            /*position: fixed;*/
            /*bottom: 90px;*/
            right: 0;
        }

        .chatbox2 .receive {
            display: flex;
            align-items: flex-start;
            width: fit-content;
            border: 1px solid gray;
            border-radius: 20px;
            background-color: gray;
            color: #fff;
        }

        .chatbox2 .send {
            display: flex;
            justify-content: flex-end;
            width: 100%;
            flex-direction: column;
            align-items: end;
        }

        .chatbox2 .send p {
            border-radius: 20px;
            background-color: green;
            color: #fff;
            width: fit-content;
            padding: 10px;
        }


        .alert-info {
            color: #0c5460 !important;
            background-color: #d1ecf1 !important;
            border-color: #bee5eb !important;
            background-image: none;
        }
    </style>
@endpush
@section('content')

    <?php
    if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
        $customer = DB::table('customers')->where('uid', Auth::user()->id)->first();
        $store_id = $customer->active_store;
    } elseif (Auth::user()->type == 'staff') {
        $staff = DB::table('staff')->where('uid', Auth::user()->id)->first();
        $store_id = $staff->store_id;
        $role = DB::table("roles")->where('id', $staff->role_id)->first();
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
    $store = DB::table('stores')->where('id', $store_id)->first();
    if ($store->expiry_date <= Carbon\Carbon::now()) {
        $exp = 1;
    } else {
        $exp = 0;
    }
    ?>
        <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Content Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>Note</label>
                    <textarea class="form-control" rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <main class="main-content position-relative  h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{URL::to('/')}}/digital_marketing">
                                    <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span
                                        class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            ড্যাশবোর্ড
                                        @else
                                            Dashboard
                                        @endif</span>
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.required.information') }}">
                                    <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span
                                        class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            প্রয়োজনীয় তথ্য
                                        @else
                                            Required Information
                                        @endif</span>
                                </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{URL::to('/')}}/content_download">
                                    <img src="{{URL::to('/')}}/img/icons/categories.png"> <br><span
                                        class="nav-link-text ms-1"> @if(Session::has('lang') && Session::get('lang')=='bn')
                                            কনটেন্ট ডাউনলোড
                                        @else
                                            Content Download
                                        @endif  </span>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{route('admin.content_correction')}}">
                                    <img src="{{URL::to('/')}}/img/subcategory.png"> <br><span
                                        class="nav-link-text ms-1"> @if(Session::has('lang') && Session::get('lang')=='bn')
                                            বিষয়বস্তু সংশোধন
                                        @else
                                            Content Correction
                                        @endif </span>
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
            @if(isset($product) && $product=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                <div class="row">
                    <div class="col-md-6">
                        <h4>All Content</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            **Per content maximum 2 times review/correction can be requested
                        </div>
                    </div>
                </div>
                    <?php
                    $messages = DB::table('trickets')->where('token', $token)->get();
                    ?>
                <div class="row mt-5 productlist">
                    <div class="col-12">
                        <div class="card chatbox2">
                            <div class="card-header" style="background-color:black">
                                <div class="row">
                                    <div class="col-md-6" style="color:#fff">Chat Id: {{$token}}</div>
                                </div>
                            </div>
                            <div class="card-body" style="height:100px;overflow-y:auto" id="messagetoken">
                                @if (Session::has('success_message'))
                                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                                @endif
                                <ul style="list-style: none;padding-left: 0px;display: flex;flex-direction: column;overflow-y: auto;">
                                    @if(isset($messages) && count($messages)>0)
                                        @foreach($messages as $msg)
                                            @if($msg->sender=='admin')
                                                @if(isset($msg->image))
                                                    <li class="send" style="border:0px !important">
                                                        <p style="background-color:transparent !important;border:0px !important">
                                                            <img
                                                                src="{{URL::to('/')}}/assets/images/token/{{$msg->image}}"
                                                                width="100">
                                                        </p>
                                                        <span>{{$msg->created_at}}</span>
                                                    </li>
                                                @endif
                                                @if(isset($msg->message))
                                                    <li class="send" style="border:0px !important">
                                                        <p>{{$msg->message}}</p>
                                                        <span>{{$msg->created_at}}</span>
                                                    </li>

                                                @endif
                                            @else
                                                @if(isset($msg->image))
                                                    <li class="receive"
                                                        style="background-color:transparent !important;border:0px !important">
                                                        <p style="background-color:transparent !important;border:0px !important">
                                                            <img
                                                                src="{{URL::to('/')}}/assets/images/token/{{$msg->image}}"
                                                                width="100">
                                                        </p>

                                                    </li>
                                                    <span>{{$msg->created_at}}</span>

                                                @endif
                                                @if(isset($msg->message))
                                                    <li class="receive">
                                                        {{$msg->message}}
                                                    </li>
                                                    <span style="padding:2px 10px">{{$msg->created_at}}</span>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                                <div class="d-flex mt-4 mb-4 justify-content-center">
                                </div>
                            </div>
                            <div class="card-footer" style="border-top:1px solid gray">
                                <div class="row">
                                    <div class="col-1">
                                        <form action="{{route('admin.sendmessage.token',$token)}}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <label for="inputimg">
                                                <img id="blah" alt="Insert Image" style="width:112%;height:auto"
                                                     src="https://img.icons8.com/dotty/80/000000/add-image.png"/>
                                            </label>
                                            <input type="file"
                                                   onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                                   id="inputimg" name="image" style="display:none">
                                    </div>
                                    <div class="col-10">
                                        <textarea id="text" name="details" class="form-control"></textarea>
                                    </div>
                                    <div class="col-1" style="display:flex;align-items:center">
                                        <button type="submit" class="btn btn-primary">Send</button>
                                        </form>
                                    </div>
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
        $(".slider_desc_toogler").on("click", function () {
            $('.slider_desc_toogler > i').toggleClass('fa-arrow-circle-down')
            $('.slider_desc_toogler > i').toggleClass('fa-arrow-circle-up')
            if ($(".slider_desc_toogler > i").hasClass("fa-arrow-circle-down")) {
                $(".slider_desc").css("max-height", "38px");
            } else $(".slider_desc").css("max-height", "500px");
        });
        $('#submit').on('click', function () {
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
        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                $url = "/changeprostatus";
                var value = $(this).val();
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {value: value, id: id}, function (data) {
                    console.log(data);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#checkedAll").change(function () {
                debugger;
                if (this.checked) {
                    $(".checkSingle").each(function () {
                        debugger;
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
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
                    var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function () {
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
@endpush
