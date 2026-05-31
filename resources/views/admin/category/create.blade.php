@extends('admin.layouts.main')
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
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{URL::to('/')}}/products">
                                    <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span
                                        class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            পণ্য
                                        @else
                                            Products
                                        @endif</span>
                                </a>
                            </li>
                            @if(isset($category) && $category=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{URL::to('/')}}/category">
                                        <img src="{{URL::to('/')}}/img/icons/categories.png"> <br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ক্যাটাগরি
                                            @else
                                                Categories
                                            @endif</span>
                                    </a>
                                </li>
                            @endif
                            @if(isset($subcategory) && $subcategory=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.subcategory.index')}}">
                                        <img src="{{URL::to('/')}}/img/subcategory.png"> <br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                সাব ক্যাটাগরি
                                            @else
                                                Sub Categories
                                            @endif</span>
                                    </a>
                                </li>
                            @endif
                            @if(isset($attribute) && $attribute=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{URL::to('/')}}/attribute">
                                        <img src="{{URL::to('/')}}/img/icons/product.png"><br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                পণ্যের ধরণ
                                            @else
                                                Variants
                                            @endif</span>

                                    </a>
                                </li>
                            @endif
                            @if(isset($brand) && $brand=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{URL::to('/')}}/brand">
                                        <img src="{{URL::to('/')}}/img/icons/brand.png"> <br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ব্রান্ড
                                            @else
                                                Brands
                                            @endif</span>
                                    </a>
                                </li>
                            @endif

                            @if(isset($supplier) && $supplier=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{URL::to('/')}}/supplier">
                                        <img src="{{URL::to('/')}}/img/icons/supplier.png"> <br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                সরবরাহকারী
                                            @else
                                                Suppliers
                                            @endif</span>
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
                    <h4>Add Category</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active"><a href="{{URL::to('/')}}/category">Back to List</a></li>
                        <li><a href="">Import</a></li>
                        <li><a href="">Export</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <form action="{{URL::to('category')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">Name <span
                                            class="req">*</span></label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="staticEmail" name="name"
                                               placeholder="Category Name">
                                        @error('name')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Parent</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" name="parent">
                                            <option value="0">Select as Parent</option>
                                                <?php
                                                $categories = DB::table('categories')->where('parent', 0)->get();
                                                ?>
                                            @foreach($categories as $cats)
                                                <option value="{{$cats->id}}">{{$cats->name}}</option>
                                                    <?php
                                                    $subcats = DB::table('categories')->where('parent', $cats->id)->get();
                                                    ?>
                                                @if(isset($subcats))
                                                    @foreach($subcats as $subcat)
                                                        <option value="{{$subcat->id}}">--{{$subcat->name}}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('parent')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="icon" class="col-md-2 col-form-label">Icon <span
                                            class="req">*</span></label>
                                    <div class="col-md-4">
                                        <input type="file" class="form-control" id="icon" name="icon">
                                        @error('icon')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="banner" class="col-md-2 col-form-label">Banner <span
                                            class="req">*</span></label>
                                    <div class="col-md-4">
                                        <input type="file" class="form-control" id="banner" name="banner">
                                        @error('banner')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">Status</label>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch is-filled" style="text-align:center;">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                                   name="status" style="margin:0 auto;" checked="">
                                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                        </div>
                                        @error('status')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-2 col-form-label">Position <span
                                            class="req">*</span></label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" id="position" name="position"
                                               placeholder="0">
                                        @error('position')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-2 col-form-label"></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-info">Submit</button>
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
