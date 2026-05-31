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
    </style>
@endpush
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.store_manage.category.top_bar_category')
        <section class="container content-main">
            @if ((isset($adGet) && $adGet == '1') || Auth::user()->type == 'superadmin')
                <div class="row">
                    <form action="{{ route('superadmin.pse.update', $adGet->id) }}" method="post"
                        enctype="multipart/form-data">
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
                                                এডিট বিজ্ঞাপন
                                            @else
                                                Edit Ad
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ছবি
                                                @else
                                                    Image
                                                @endif
                                            </label>
                                            <br>
                                            <img src="{{ URL::to('/') }}/assets/images/ads_pse_image/{{ $adGet->banner }}"
                                                alt="" width="250px"
                                                style="padding:10px;border:1px solid gray;margin-top:5px;margin-bottom:5px;">
                                            <br>
                                            <input type="file" placeholder="Type here" class="form-control"
                                                id="banner" name="banner">
                                            @error('banner')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    বিজ্ঞাপনের নাম
                                                @else
                                                    Ad Name
                                                @endif
                                            </label>
                                            <input type="text" placeholder="Type here" value="{{ $adGet->name }}"
                                                class="form-control" id="name" name="name">
                                            @error('name')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    লিঙ্ক
                                                @else
                                                    Link
                                                @endif
                                            </label>
                                            <input type="text" placeholder="Type here" value="{{ $adGet->link }}"
                                                class="form-control" id="link" name="link">
                                            @error('link')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ক্যাটাগরি
                                                @else
                                                    Category
                                                @endif
                                            </label>
                                            <select id='category_id' name="category_id[]" class="form-control" multiple
                                                style="width:100% !important">
                                                <option value="null">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        ক্যাটাগরি নির্বাচন করুন
                                                    @else
                                                        Select Category
                                                    @endif
                                                </option>
                                                @if (isset($categories) && count($categories) > 0)
                                                    @foreach ($categories as $category)
                                                        <option value='{{ $category->id }}'
                                                            {{ is_array($adGet->category_id) && in_array($category->id, $adGet->category_id) ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            @error('category_id')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
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
                                                    <option value="0" {{ $adGet->image_type == 0 ? 'selected' : '' }}>
                                                        Landscape
                                                    </option>
                                                    <option value="1" {{ $adGet->image_type == 1 ? 'selected' : '' }}>
                                                        Portrait</option>
                                                </select>
                                            </div>
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

                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                        id="flexSwitchCheckChecked" name="status"
                                                        data-id="{{ $adGet->id }}" style="margin:0 auto;"
                                                        @if ($adGet->status == 1) checked @endif>

                                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                                </div>
                                                @error('status')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    লিঙ্ক
                                                @else
                                                    Position
                                                @endif
                                            </label>
                                            <input type="text" placeholder="Type here" value="{{ $adGet->position }}"
                                                class="form-control" id="position" name="position">
                                            @error('position')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <a href="{{ URL::previous() }}"
                                                class="btn btn-danger rounded font-sm hover-up" style="float: left;">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    বাতিল করুন
                                                @else
                                                    Cancel
                                                @endif
                                            </a>

                                            <button type="submit" class="btn btn-info rounded font-sm hover-up"
                                                style="float: right;">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    আপডেট
                                                @else
                                                    Update
                                                @endif
                                            </button>
                                        </div>

                                    </div>
                                </div> <!-- card end// -->

                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </section>
    </main>
@endsection

@push('scripts')
@endpush
