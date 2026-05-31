@extends('admin.layouts.main')
@push('styles')
    <style>
        .fade:not(.show) {
            opacity: 1 !important;
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.store_manage.category.top_bar_category')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            এডিট ক্যাটাগরি
                        @else
                            Edit Category
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active">
                            <a href="{{ route('superadmin.store.category') }}">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    তালিকায় ফিরে যান
                                @else
                                    Back to List
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.store.category.edit.update', $category->id) }}"
                                method="post" enctype="multipart/form-data">
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
                                <!-- <div class="mb-3 row">
                                                <label for="inputPassword" class="col-sm-2 col-form-label">Parent</label>
                                                <div class="col-sm-4">
                                                <select class="form-select" name="parent">
                                                    <option value="0" @if ($category->parent == '0') selected @endif>Select as Parent</option>
                                                    <?php
                                                    $categories = DB::table('categories')
                                                        ->where('parent', 0)
                                                        ->get();
                                                    ?>
                                                    @foreach ($categories as $cats)
    <option value="{{ $cats->id }}" @if ($category->parent == $cats->id) selected @endif>{{ $cats->name }}</option>
                                                    <?php
                                                    $subcats = DB::table('categories')
                                                        ->where('parent', $cats->id)
                                                        ->get();
                                                    ?>
                                                    @if (isset($subcats))
    @foreach ($subcats as $subcat)
    <option value="{{ $subcat->id }}" @if ($category->parent == $subcat->id) selected @endif>--{{ $subcat->name }}</option>
    @endforeach
    @endif
    @endforeach
                                                </select>
                                                @error('parent')
        <p class="text-danger" role="alert">{{ $message }}</p>
    @enderror
                                                </div>
                                            </div> -->
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
                                                @if ($category->status == 'active') checked="" @endif>
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
        $('.icon').iconpicker();
        $('.action-destroy').on('click', function() {
            $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
        });
    </script>
@endpush
