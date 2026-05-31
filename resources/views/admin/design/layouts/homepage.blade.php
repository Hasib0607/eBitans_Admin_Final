@extends('admin.layouts.main')
@section('content')
    <style>
        .left-menu {
            position: relative;
            top: 50% !important;
        }

        .left-menu ul li {
            float: unset !important;
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

        .rightmenu li {
            float: left !important;
            padding: 1px 16px !important;
        }

        .select2-container .select2-selection--multiple {
            min-height: 40px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: 0px solid black !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 4px !important;
        }
    </style>
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
                } elseif ($pr == 'testimonials') {
                    $tt = 1;
                } elseif ($pr == 'designsettings') {
                    $ds = 1;
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
    <main class="main-content position-relative  h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item ">
                                <a href="{{route('admin.design.slider')}}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Slider
                                </a>
                            </li>
                            @if(isset($banner) && $banner=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.design.banner')}}">
                                        <img src="{{URL::to('/')}}/img/categories1.png"> <br>Banner
                                    </a>
                                </li>
                            @endif
                            @if(isset($layout) && $layout=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{route('admin.design.layout.homepage')}}">
                                        <img src="{{URL::to('/')}}/img/subcategory.png"> <br>Invoice
                                    </a>
                                </li>
                            @endif
                            @if(isset($template) && $template=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.design.theme')}}">
                                        <img src="{{URL::to('/')}}/img/brand.png"> <br>Themes
                                    </a>
                                </li>
                            @endif
                            @if(isset($header) && $header=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.design.design')}}">
                                        <img src="{{URL::to('/')}}/img/sort-descending.png"><br>Header
                                    </a>
                                </li>
                            @endif
                            @if(isset($homepage) && $homepage=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.design.homepage.slider')}}">
                                        <img src="{{URL::to('/')}}/img/ribbon.png"> <br>HomePage
                                    </a>
                                </li>
                            @endif
                            <!--@if(isset($footer) && $footer=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')-->
                            <!--<li class="breadcrumb-item" aria-current="page">-->
                            <!--    <a href="#">-->
                            <!--        <img src="{{URL::to('/')}}/img/collection.png" > <br>Footer-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--@endif-->
                            <!--@if(isset($mobilemenu) && $mobilemenu=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')-->
                            <!--<li class="breadcrumb-item" aria-current="page">-->
                            <!--    <a href="#">-->
                            <!--        <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Mobile Menu-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--@endif-->
                            <!--@if(isset($product_display) && $product_display=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')-->
                            <!--<li class="breadcrumb-item" aria-current="page">-->
                            <!--    <a href="#">-->
                            <!--        <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Product Display-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--@endif-->
                            <!--@if(isset($product_grid) && $product_grid=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')-->
                            <!--<li class="breadcrumb-item" aria-current="page">-->
                            <!--    <a href="#">-->
                            <!--        <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Product Grid System-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--@endif-->
                            <!--@if(isset($shop_page) && $shop_page=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')-->
                            <!--<li class="breadcrumb-item" aria-current="page">-->
                            <!--    <a href="#">-->
                            <!--        <img src="{{URL::to('/')}}/img/browser-tab.png" > <br>Shop Page-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--@endif-->
                            @if(isset($tt) && $tt=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.testimonials')}}">
                                        <img src="{{URL::to('/')}}/img/browser-tab.png"> <br>Testimonials
                                    </a>
                                </li>
                            @endif
                            @if(isset($pages) && $pages=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.pages')}}">
                                        <img src="{{URL::to('/')}}/img/browser-tab.png"> <br>Page
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.design.homepage.invoice')}}">
                                        <img src="{{URL::to('/')}}/img/browser-tab.png"> <br>Invoice
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
                <!--<div class="col-md-3 left-menu card card-body mt-4">-->
                <!--    <ul style="padding-left:0rem;">-->
                <!--        <li class="active" style="margin-bottom:10px;border-radius:10px;"><a href="{{route('admin.design.layout.homepage')}}">Invoice</a></li>-->
                <!--    </ul>-->
                <!--</div>-->
                <div class="col-md-9 rightmenu mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>All Invoice</h4>
                                </div>
                                <div class="card-body">
                                    @if (Session::has('success_message'))
                                        <div class="alert alert-success">{{Session::get('success_message')}}</div>
                                    @endif
                                    <!--<form action="{{route('admin.saveinvoice')}}" method="post">-->
                                    @csrf
                                        <?php $header1 = DB::table('designs')->where('store_id', $store_id)->where('invoice', null)->first(); ?>
                                    <div class="form-group">
                                        <input type="radio" id="Homes" name="invoice" @if(isset($header1)) checked
                                               @endif value="null">&nbsp;&nbsp;&nbsp;
                                        <label for="Homes"> None </label> &nbsp;&nbsp;&nbsp;
                                    </div>
                                    @if(isset($design) && count($design)>0)
                                        @foreach($design as $key=>$dsg)
                                                <?php $header1 = DB::table('designs')->where('store_id', $store_id)->where('invoice', $dsg->value)->first(); ?>
                                            <div class="form-group">
                                                <input type="radio" id="Home{{$key}}" name="invoice"
                                                       class="changeinvoice" @if(isset($header1)) checked
                                                       @endif value="{{$dsg->value}}">&nbsp;&nbsp;&nbsp;
                                                <label for="Home{{$key}}">@if(isset($dsg->image))
                                                        <img src="{{URL::to('/')}}assets/images/design/{{$dsg->image}}"
                                                             class="img-fluid headerimg" alt=""
                                                             style="padding:10px;border:1px solid gray;transition-delay: 5s;">
                                                    @else
                                                        {{$dsg->name}}
                                                    @endif</label> &nbsp;&nbsp;&nbsp;
                                            </div>
                                        @endforeach
                                    @endif

                                    <!--    <button type="submit" class="btn btn-info mt-3">Submit</button>-->
                                    <!--</form>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $(".changeinvoice").on("click", function () {
                $url = "/changeinvoice";
                var value = $(this).val();
                console.log(value);
                $.get($url, {value: value}, function (data) {
                    console.log(data);
                    toastr.success('Invoice Design Successfully', 'Success');
                });
            });
        });
    </script>
    <script>
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
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
    </script>
@endpush
