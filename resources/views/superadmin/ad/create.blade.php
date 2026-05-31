@extends('admin.layouts.main')
@push('styles')
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
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.store_manage.category.top_bar_category')
        @if ((isset($product) && $product == '1') || Auth::user()->type == 'superadmin')
            <section class="container content-main">
                <div class="row">
                    <form action="{{ route('superadmin.pse.store') }}" method="post" enctype="multipart/form-data">
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
                                                নতুন বিজ্ঞাপন যোগ করুন
                                            @else
                                                Add New AD
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="#" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3 row">
                                                <label for="staticEmail" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        বিজ্ঞাপনের নাম
                                                    @else
                                                        Ad Name
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" id="staticEmail"
                                                        name="name" placeholder="Ad Name">
                                                    @error('name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="staticEmail" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        লিঙ্ক
                                                    @else
                                                        Link
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input type="text" placeholder="Type here" class="form-control"
                                                        id="link" name="link">
                                                    @error('link')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="category_id[]" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ক্যাটাগরি
                                                    @else
                                                        Category
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <select id='category_idpack' name="category_id[]" class="form-control"
                                                        multiple style="width:100% !important">
                                                        <option value="null">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                ক্যাটাগরি নির্বাচন করুন
                                                            @else
                                                                Select Category
                                                            @endif
                                                        </option>
                                                        @if (isset($catagories) && count($catagories) > 0)
                                                            @foreach ($catagories as $catagorie)
                                                                <option value='{{ $catagorie->id ?? '' }}'>
                                                                    {{ $catagorie->name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error('category_id')
                                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="banner" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ব্যানার
                                                    @else
                                                        Banner
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input type="file" class="form-control" id="banner"
                                                        name="banner">
                                                    @error('banner')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="image_type" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ছবি
                                                    @else
                                                        Image
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="image_type">
                                                        <option value="null">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                প্রকার নির্বাচন করুন
                                                            @else
                                                                Select Type
                                                            @endif
                                                        </option>
                                                        <option value="0">Landscape</option>
                                                        <option value="1">Portrait</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row" style="display: flex; align-items: center;">
                                                <label for="cateStatus" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        স্ট্যাটাস
                                                    @else
                                                        Status
                                                    @endif
                                                </label>
                                                <div class="col-md-8">
                                                    <div class="form-check form-switch is-filled"
                                                        style="text-align:center;">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="flexSwitchCheckChecked" name="status"
                                                            style="margin:0 auto;" checked>
                                                        <label class="form-check-label"
                                                            for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                    @error('status')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="position" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        অবস্থান
                                                    @else
                                                        Position
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control" id="position"
                                                        name="position" placeholder="0" autofocus="">
                                                    @error('position')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <div class="col-md-6" style="text-align:left">
                                                    <a href="{{ URL::previous() }}" class="btn btn-danger">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            বাতিল করুন
                                                        @else
                                                            Cancel
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="col-md-6" style="text-align:right">
                                                    <button type="submit" class="btn btn-info">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            জমা দিন
                                                        @else
                                                            Submit
                                                        @endif
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        @endif
    </main>
@endsection
@push('scripts')
@endpush
