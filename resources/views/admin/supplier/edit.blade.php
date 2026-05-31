@extends('admin.layouts.main')
@section('content')
    @php
        if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
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
    @endphp
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('admin.admin_top_bar_category.index')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            এডিট সরবরাহকারী
                        @else
                            Edit Supplier
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active"><a href="{{ URL::to('/') }}/supplier">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    তালিকায় ফিরে যান
                                @else
                                    Back To List
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
                            <form action="{{ URL::to('/') }}/supplier/{{ $supplier->id }}" method="post"
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
                                               value="{{ $supplier->name }}">
                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            কোমপানির নাম
                                        @else
                                            Company Name
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="staticEmail" name="company_name"
                                               value="{{ $supplier->company_name }}">
                                        @error('company_name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ফোন
                                        @else
                                            Phone
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="staticEmail" name="phone"
                                               value="{{ $supplier->phone }}">
                                        @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ঠিকানা
                                        @else
                                            Address
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="staticEmail" name="address"
                                               value="{{ $supplier->address }}">
                                        @error('address')
                                        <p class="text-danger">{{ $message }}</p>
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
