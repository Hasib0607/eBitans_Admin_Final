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

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 35px !important;
        }

        .select2-container .select2-selection--single {
            height: 39px !important;
        }
    </style>

    <?php
    if (Auth::user()->type == 'admin') {
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
                } elseif ($pr == 'smm') {
                    $smm = 1;
                } elseif ($pr == 'testimonials') {
                    $tt = 1;
                } elseif ($pr == 'designsettings') {
                    $ds = 1;
                } else {

                }
            }
        }
    }

    ?>
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.share.staff-role-permission-nav.nav')
        <section class="container content-main">
            <div class="row">
                <form action="{{ route('superadmin.staff.update', $staff->id) }}" method="post"
                      enctype="multipart/form-data">
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
                                    <h4>Update Staff</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Name</label>
                                        <input type="text" placeholder="Type here" class="form-control" id="name"
                                               name="name" value="{{ $staff->name ?? old('name') }}">
                                        @error('name')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" placeholder="Type here" class="form-control" id="username"
                                               name="username" value="{{ $staff->username }}">
                                        @error('username')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="tel" placeholder="Type here" class="form-control" id="phone"
                                               name="phone" value="{{ $staff->phone }}">
                                        @error('phone')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" placeholder="Type here" class="form-control" id="email"
                                               name="email" value="{{ $staff->email ?? old('email') }}">
                                        @error('email')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" placeholder="Type here" class="form-control" id="address"
                                               name="address" value="{{ $staff->address ?? old('address') }}">
                                        @error('address')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_commission" class="form-label">New Customer Commission</label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="new_commission"
                                               name="new_commission"
                                               value="{{ $staff->new_commission ?? old('new_commission')}}">
                                        @error('new_commission')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="renew_commission" class="form-label">Renew Customer
                                            Commission</label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="renew_commission"
                                               name="renew_commission"
                                               value="{{ $staff->renew_commission ?? old('renew_commission')}}">
                                        @error('renew_commission')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="setup_commission" class="form-label">Website setup
                                            Commission</label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="setup_commission"
                                               name="setup_commission"
                                               value="{{ $staff->setup_commission ?? old('setup_commission')}}">
                                        @error('setup_commission')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" placeholder="*********" class="form-control"
                                               id="password"
                                               name="password" value="{{ old('password') }}">
                                        @error('password')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-control" name="role" id="role">
                                                <?php
                                                $role = DB::table('superroles')->get();
                                                ?>
                                            @if (isset($role) && count($role) > 0)
                                                @foreach ($role as $roles)
                                                    <option value="{{ $roles->id }}"
                                                            @if ($staff->role_id == $roles->id) selected @endif>
                                                        {{ $roles->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('role')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail" class="col-md-2 col-form-label">Status</label>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch is-filled"
                                                 style="text-align:center;padding-top:14px;">
                                                <input class="form-check-input" type="checkbox"
                                                       id="flexSwitchCheckChecked" name="status" style="margin:0 auto;"
                                                       @if ($staff->status == 'active') checked="" @endif>
                                                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                            </div>
                                            @error('status')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"></label>
                                        <button class="btn btn-info rounded font-sm hover-up"
                                                type="submit">Publish
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
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endpush
