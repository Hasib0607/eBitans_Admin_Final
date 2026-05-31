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
            <form action="{{route('admin.submitboosting')}}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Boosting Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type">
                                <option value="0">Select Type</option>
                                <option value="Get Started with customize app">Get Started with customize app</option>
                                <option value="Get More Messages">Get More Messages</option>
                                <option value="Get More Calls">Get More Calls</option>
                                <option value="Get More Website Visitor">Get More Website Visitor</option>
                                <option value="Get More Leads">Get More Leads</option>
                                <option value="Boost a Post">Boost a Post</option>
                                <option value="Boost and Instagram Post">Boost and Instagram Post</option>
                                <option value="Promote your App">Promote your App</option>
                                <option value="Promote your Page">Promote your Page</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount ($)</label>
                            <input type="number" min="20" name="amount" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>From</label>
                            <input type="date" name="from" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>To</label>
                            <input type="date" name="to" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Content</label>
                            <input type="text" name="content" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <textarea name="note" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
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
                            <li class="breadcrumb-item" aria-current="page">
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
                        <h4>All Boosting Information</h4>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li style="padding:0px;border:0px;"><a style="display:block;border-radius:0px !important"
                                                                   class="btn btn-secondary">Print</a></li>
                            <li style="padding:0px;border:0px;"><a style="display:block;border-radius:0px !important"
                                                                   class="btn btn-primary" data-bs-toggle="modal"
                                                                   data-bs-target="#exampleModal">Boosting Request</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row mt-5 productlist">
                    @if(isset($boostings) && count($boostings)>0)
                        @foreach($boostings as $boost)
                            <div class="col-md-4">
                                <div class="card mt-2">
                                    <div class="card-header">
                                        Facebook Boosting
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Type</th>
                                                <td>{{$boost->type}}</td>
                                            </tr>
                                            <tr>
                                                <th>Day</th>
                                                <td>{{$boost->from}} to {{$boost->to}}</td>
                                            </tr>
                                            <tr>
                                                <th>Amount</th>
                                                <td>${{$boost->amount}}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>{{$boost->status}}</td>
                                            </tr>
                                            <tr>
                                                <th>Reached</th>
                                                <td>{{$boost->reached}}</td>
                                            </tr>
                                            <tr>
                                                <th>Content</th>
                                                <td>{{$boost->content}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
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
