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
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.share.design-top-nav')
        <section class="container content-main">
            <div class="row">
                <form action="{{ route('superadmin.template.update', $template->id) }}" method="post"
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
                                    <h4>Edit Template</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Category</label>
                                        <select class="form-control js-example-basic-multiple" name="category[]"
                                                id="category" data-categories="{{$template->category}}"
                                                multiple="multiple">
                                            <option value="select">Select Design Category</option>
                                            @foreach($categories as $parent)
                                                <option
                                                    value="{{ $parent->id }}">
                                                    {{ $parent->name }}
                                                </option>

                                                @if($parent->subcategories && $parent->subcategories->count())
                                                    @foreach($parent->subcategories as $sub)
                                                        <option
                                                            value="{{ $sub->id }}">
                                                            {{ $sub->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Name</label>
                                        <input type="name" placeholder="Type here" class="form-control" id="name"
                                               name="name" value="{{ $template->name ?? old('name') }}">
                                        @error('name')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Live Link</label>
                                        <input type="text" placeholder="Type here" class="form-control" id="link"
                                               name="link" value="{{ $template->liveurl ?? old('link') }}">
                                        @error('link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Feature Image</label>
                                        <img
                                            src="{{ URL::to('/') }}/assets/images/template/{{ $template->feature_image }}"
                                            width="150px" style="margin-bottom:10px">
                                        <input type="file" placeholder="Type here" class="form-control"
                                               id="feature_image" name="feature_image">
                                        @error('feature_image')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Main Image</label>
                                        <img src="{{ URL::to('/') }}/assets/images/template/{{ $template->main_image }}"
                                             width="150px" height="300px" style="margin-bottom:10px">
                                        <input type="file" placeholder="Type here" class="form-control" id="main_image"
                                               name="main_image">
                                        @error('main_image')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Value</label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="value" name="value"
                                               value="{{ $template->value ?? old('value') }}">
                                        @error('value')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Short Description</label>
                                        <textarea class="form-control" name="short_description" id="short_description"
                                                  rows="4">{{ $template->short_description ?? old('short_description') }}</textarea>
                                        @error('short_description')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    @foreach($designs as $key=>$design)
                                        <div class="row">
                                            <div class="mb-3 col">
                                                <label for="product_name"
                                                       class="form-label">{{ucwords(str_replace('_', ' ', $key))}}</label>
                                                <select class="js-example-basic-single form-control" name="{{$key}}">
                                                    <option value="null">None</option>
                                                    @if (isset($designs[$key]) && count($designs[$key]) > 0)
                                                        @foreach ($design as $index=>$item)
                                                            <option value="{{ $item->value }}"
                                                                    @if($template[$key] == $item->value || $template['new_arrival'] == $item->value) selected @endif >{{ $item->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error($key)
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            @if($key == 'header' || $key == 'slider'|| $key == 'banner'|| $key == 'banner_bottom'|| $key == 'feature_category'|| $key == 'product'|| $key == 'feature_product'|| $key == 'best_sell_product' || $key == 'new_arrival' || $key == 'blog' || $key == 'testimonial' || $key == 'youtube' || $key == 'brand' || $key == 'footer')
                                                <div class="mb-3 col-md-3">
                                                    @php
                                                        $position = 1;
                                                    @endphp
                                                    @foreach($template_position as $tempVal)
                                                        @if($tempVal->name == $key)
                                                            @php
                                                                $position = $tempVal->position; // Update position if match found
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    <label for="product_name" class="form-label"
                                                           style="width:100px; display:flex; ">Position</label>
                                                    <input type="number" placeholder="Type here"
                                                           class="form-control"
                                                           id="{{$key.'_position'}}" name="{{$key.'_position'}}"
                                                           value="{{ old($key."_position") ?? $position ?? 1 }}"
                                                    >
                                                    @error($key.'_position')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    {{--<div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Header</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="header" name="header" value="{{ old('header') }}">-->
                                            <select class="js-example-basic-single form-control" name="header">
                                                    <?php
                                                    $dheader = DB::table('designlists')
                                                        ->where('type', 'header')
                                                        ->get();
                                                    ?>
                                                <option value="none" @if ($template->header == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dheader) && count($dheader) > 0)
                                                    @foreach ($dheader as $header)
                                                        <option value="{{ $header->value }}"
                                                                @if ($template->header == $header->value) selected @endif>
                                                            {{ $header->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('header')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Header Position</label>
                                                <?php
                                                $tt = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'header')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="header_position" name="header_position"
                                                   value="{{ $tt->position ?? 1 }}">
                                            @error('header_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Slider</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="slider" name="slider" value="{{ old('slider') }}">-->
                                            <select class="js-example-basic-single form-control" name="slider">
                                                    <?php
                                                    $dslider = DB::table('designlists')
                                                        ->where('type', 'slider')
                                                        ->get();
                                                    ?>
                                                <option value="none" @if ($template->slider == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dslider) && count($dslider) > 0)
                                                    @foreach ($dslider as $slider)
                                                        <option value="{{ $slider->value }}"
                                                                @if ($template->slider == $slider->value) selected @endif>
                                                            {{ $slider->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('slider')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $sp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'hero_slider')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="slider_position" name="slider_position"
                                                   value="{{ $sp->position ?? 1 }}">
                                            @error('slider_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Banner</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="banner" name="banner" value="{{ old('banner') }}">-->
                                            <select class="js-example-basic-single form-control" name="banner">
                                                    <?php
                                                    $dbanner = DB::table('designlists')
                                                        ->where('type', 'banner')
                                                        ->get();
                                                    ?>
                                                <option value="none" @if ($template->banner == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dbanner) && count($dbanner) > 0)
                                                    @foreach ($dbanner as $banner)
                                                        <option value="{{ $banner->value }}"
                                                                @if ($template->banner == $banner->value) selected @endif>
                                                            {{ $banner->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('banner')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $bp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'banner')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="banner_position" name="banner_position"
                                                   value="{{ $bp->position ?? 1 }}">
                                            @error('banner_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Banner Bottom</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="banner" name="banner" value="{{ old('banner') }}">-->
                                            <select class="js-example-basic-single form-control" name="banner_bottom">
                                                    <?php
                                                    $dbanner = DB::table('designlists')
                                                        ->where('type', 'banner_bottom')
                                                        ->get();
                                                    ?>
                                                <option value="none"
                                                        @if ($template->banner_bottom == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dbanner) && count($dbanner) > 0)
                                                    @foreach ($dbanner as $banner)
                                                        <option value="{{ $banner->value }}"
                                                                @if ($template->banner_bottom == $banner->value) selected @endif>
                                                            {{ $banner->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('banner_bottom')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $bp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'banner_bottom')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="banner_bottom_position" name="banner_bottom_position"
                                                   value="{{ $bp->position ?? 1 }}">
                                            @error('banner_bottom_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Feature Category</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="feature_category" name="feature_category" value="{{ old('feature_category') }}">-->
                                            <select class="js-example-basic-single form-control"
                                                    name="feature_category">
                                                    <?php
                                                    $dfcat = DB::table('designlists')
                                                        ->where('type', 'feature_category')
                                                        ->get();
                                                    ?>
                                                <option value="none"
                                                        @if ($template->feature_category == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dfcat) && count($dfcat) > 0)
                                                    @foreach ($dfcat as $fcat)
                                                        <option value="{{ $fcat->value }}"
                                                                @if ($template->feature_category == $fcat->value) selected @endif>
                                                            {{ $fcat->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('feature_category')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $fcp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'feature_category')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="feature_category_position" name="feature_category_position"
                                                   value="{{ $fcp->position ?? 1 }}">
                                            @error('feature_category_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Product</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="product" name="product" value="{{ old('product') }}">-->
                                            <select class="js-example-basic-single form-control" name="product">
                                                    <?php
                                                    $dproduct = DB::table('designlists')
                                                        ->where('type', 'product')
                                                        ->get();
                                                    ?>
                                                <option value="none" @if ($template->product == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dproduct) && count($dproduct) > 0)
                                                    @foreach ($dproduct as $product)
                                                        <option value="{{ $product->value }}"
                                                                @if ($template->product == $product->value) selected @endif>
                                                            {{ $product->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('product')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $pp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'product')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="product_position" name="product_position"
                                                   value="{{ $pp->position ?? 1 }}">
                                            @error('product_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Feature Product</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="feature_product" name="feature_product" value="{{ old('feature_product') }}">-->
                                            <select class="js-example-basic-single form-control" name="feature_product">
                                                    <?php
                                                    $dfpro = DB::table('designlists')
                                                        ->where('type', 'feature_product')
                                                        ->get();
                                                    ?>
                                                <option value="none"
                                                        @if ($template->feature_product == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dfpro) && count($dfpro) > 0)
                                                    @foreach ($dfpro as $fpro)
                                                        <option value="{{ $fpro->value }}"
                                                                @if ($template->feature_product == $fpro->value) selected @endif>
                                                            {{ $fpro->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('feature_product')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $fp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'feature_product')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="feature_product_position" name="feature_product_position"
                                                   value="{{ $fp->position ?? 1 }}">
                                            @error('feature_product_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Best Sell Product</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="best_sell_product" name="best_sell_product" value="{{ old('best_sell_product') }}">-->
                                            <select class="js-example-basic-single form-control"
                                                    name="best_sell_product">
                                                    <?php
                                                    $dbsp = DB::table('designlists')
                                                        ->where('type', 'best_sell_product')
                                                        ->get();
                                                    ?>
                                                <option value="none"
                                                        @if ($template->best_sell_product == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dbsp) && count($dbsp) > 0)
                                                    @foreach ($dbsp as $bsp)
                                                        <option value="{{ $bsp->value }}"
                                                                @if ($template->best_sell_product == $bsp->value) selected @endif>
                                                            {{ $bsp->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('best_sell_product')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $bsp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'best_sell_product')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="best_sell_product_position" name="best_sell_product_position"
                                                   value="{{ $bsp->position ?? 1 }}">
                                            @error('best_sell_product_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">New Arrival Product</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="new_arrival" name="new_arrival" value="{{ old('new_arrival') }}">-->
                                            <select class="js-example-basic-single form-control" name="new_arrival">
                                                    <?php
                                                    $dnap = DB::table('designlists')
                                                        ->where('type', 'new_arrival_product')
                                                        ->get();
                                                    ?>
                                                <option value="none"
                                                        @if ($template->new_arrival == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dnap) && count($dnap) > 0)
                                                    @foreach ($dnap as $nap)
                                                        <option value="{{ $nap->value }}"
                                                                @if ($template->new_arrival == $nap->value) selected @endif>
                                                            {{ $nap->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('new_arrival')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $napp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'new_arrival')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="new_arrival_product_position" name="new_arrival_product_position"
                                                   value="{{ $napp->position ?? 1 }}">
                                            @error('new_arrival_product_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Testimonial</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="testimonial" name="testimonial" value="{{ old('testimonial') }}">-->
                                            <select class="js-example-basic-single form-control" name="testimonial">
                                                    <?php
                                                    $dtesti = DB::table('designlists')
                                                        ->where('type', 'testimonial')
                                                        ->get();
                                                    ?>
                                                <option value="none"
                                                        @if ($template->testimonial == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dtesti) && count($dtesti) > 0)
                                                    @foreach ($dtesti as $testi)
                                                        <option value="{{ $testi->value }}"
                                                                @if ($template->testimonial == $testi->value) selected @endif>
                                                            {{ $testi->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('testimonial')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $tp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'testimonial')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="testimonial_position" name="testimonial_position"
                                                   value="{{ $tp->position ?? 1 }}">
                                            @error('testimonial_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div style="display:flex">
                                        <div class="mb-3" style="width: 400px">
                                            <label for="product_name" class="form-label">Footer</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="footer" name="footer" value="{{ old('footer') }}">-->
                                            <select class="js-example-basic-single form-control" name="footer">
                                                    <?php
                                                    $dfooter = DB::table('designlists')
                                                        ->where('type', 'footer')
                                                        ->get();
                                                    ?>
                                                <option value="none" @if ($template->footer == 'none') selected @endif>
                                                    None
                                                </option>
                                                @if (isset($dfooter) && count($dfooter) > 0)
                                                    @foreach ($dfooter as $footer)
                                                        <option value="{{ $footer->value }}"
                                                                @if ($template->footer == $footer->value) selected @endif>
                                                            {{ $footer->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('footer')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3" style="margin-left: 30px;">
                                            <label for="product_name" class="form-label"
                                                   style="width:100px; display:flex; ">Position</label>
                                                <?php
                                                $ffp = DB::table('tempositions')
                                                    ->where('template_id', $template->id)
                                                    ->where('name', 'footer')
                                                    ->first();
                                                ?>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="footer_position" name="footer_position"
                                                   value="{{ $ffp->position ?? 1 }}">
                                            @error('footer_position')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Auth</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="auth">
                                                <?php
                                                $dauth = DB::table('designlists')
                                                    ->where('type', 'auth')
                                                    ->get();
                                                ?>
                                            @if (isset($dauth) && count($dauth) > 0)
                                                @foreach ($dauth as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->auth == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('auth')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Single Product Page</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="single_product_page">
                                                <?php
                                                $spps = DB::table('designlists')
                                                    ->where('type', 'single_product_page')
                                                    ->get();
                                                ?>
                                            @if (isset($spps) && count($spps) > 0)
                                                @foreach ($spps as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->single_product_page == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('single_product_page')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Shop Page</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="shop_page">
                                                <?php
                                                $ssps = DB::table('designlists')
                                                    ->where('type', 'shop_page')
                                                    ->get();
                                                ?>
                                            @if (isset($ssps) && count($ssps) > 0)
                                                @foreach ($ssps as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->shop_page == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('shop_page')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Checkout Page</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="checkout_page">
                                                <?php
                                                $checkoutpage = DB::table('designlists')
                                                    ->where('type', 'checkout_page')
                                                    ->get();
                                                ?>
                                            @if (isset($checkoutpage) && count($checkoutpage) > 0)
                                                @foreach ($checkoutpage as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->checkout_page == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('checkout_page')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Login Page</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="login_page">
                                                <?php
                                                $loginpage = DB::table('designlists')
                                                    ->where('type', 'login_page')
                                                    ->get();
                                                ?>
                                            @if (isset($loginpage) && count($loginpage) > 0)
                                                @foreach ($loginpage as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->login_page == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('login_page')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Profile Page</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="profile_page">
                                                <?php
                                                $profilepage = DB::table('designlists')
                                                    ->where('type', 'profile_page')
                                                    ->get();
                                                ?>
                                            @if (isset($profilepage) && count($profilepage) > 0)
                                                @foreach ($profilepage as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->profile_page == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('profile_page')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Invoice</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="invoice">
                                                <?php
                                                $invoice = DB::table('designlists')
                                                    ->where('type', 'invoice')
                                                    ->get();
                                                ?>
                                            @if (isset($invoice) && count($invoice) > 0)
                                                @foreach ($invoice as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->invoice == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('invoice')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Product Cart</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="product_card">
                                                <?php
                                                $productcard = DB::table('designlists')
                                                    ->where('type', 'product_card')
                                                    ->get();
                                                ?>
                                            @if (isset($productcard) && count($productcard) > 0)
                                                @foreach ($productcard as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->product_card == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('product_card')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Product Modal</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="product_modal">
                                                <?php
                                                $productmodal = DB::table('designlists')
                                                    ->where('type', 'product_modal')
                                                    ->get();
                                                ?>
                                            @if (isset($productmodal) && count($productmodal) > 0)
                                                @foreach ($productmodal as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->product_modal == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('product_modal')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Preloader</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="preloader">
                                                <?php
                                                $preloader = DB::table('designlists')
                                                    ->where('type', 'preloader')
                                                    ->get();
                                                ?>
                                            @if (isset($preloader) && count($preloader) > 0)
                                                @foreach ($preloader as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->preloader == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('preloader')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Mobile Bottom Menu</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="mobile_bottom_menu">
                                                <?php
                                                $mobilebottommenu = DB::table('designlists')
                                                    ->where('type', 'mobile_bottom_menu')
                                                    ->get();
                                                ?>
                                            @if (isset($mobilebottommenu) && count($mobilebottommenu) > 0)
                                                @foreach ($mobilebottommenu as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->mobile_bottom_menu == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('mobile_bottom_menu')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Offer</label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                        <select class="js-example-basic-single form-control" name="offer">
                                                <?php
                                                $offersss = DB::table('designlists')
                                                    ->where('type', 'offer')
                                                    ->get();
                                                ?>
                                            @if (isset($offersss) && count($offersss) > 0)
                                                @foreach ($offersss as $auth)
                                                    <option value="{{ $auth->value }}"
                                                            @if ($template->offer == $auth->value) selected @endif>
                                                        {{ $auth->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('offer')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>--}}

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="is_premium" class="form-label">Free/Paid</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                            <select class="js-example-basic-single form-control" id="is_premium"
                                                    name="is_premium">
                                                <option {{ $template->is_premium == 'Free' ? 'selected' : '' }}
                                                        value="Free">Free
                                                </option>
                                                <option {{ $template->is_premium == 'Paid' ? 'selected' : '' }}
                                                        value="Paid">Paid
                                                </option>
                                            </select>
                                            @error('is_premium')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="price" name="price"
                                                   value="{{ $template->price ?? old('price') }}">
                                            @error('price')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="review" class="form-label">Review</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                            <select class="js-example-basic-single form-control" id="review"
                                                    name="review">
                                                <option {{ $template->review == 1 ? 'selected' : '' }} value="1">1
                                                </option>
                                                <option {{ $template->review == 1.5 ? 'selected' : '' }} value="1.5">
                                                    1.5
                                                </option>
                                                <option {{ $template->review == 2 ? 'selected' : '' }} value="2">2
                                                </option>
                                                <option {{ $template->review == 2.5 ? 'selected' : '' }} value="2.5">
                                                    2.5
                                                </option>
                                                <option {{ $template->review == 3 ? 'selected' : '' }} value="3">3
                                                </option>
                                                <option {{ $template->review == 3.5 ? 'selected' : '' }} value="3.5">
                                                    3.5
                                                </option>
                                                <option {{ $template->review == 4 ? 'selected' : '' }} value="4">4
                                                </option>
                                                <option {{ $template->review == 4.5 ? 'selected' : '' }} value="4.5">
                                                    4.5
                                                </option>
                                                <option {{ $template->review == 5 ? 'selected' : '' }} value="5">5
                                                </option>
                                            </select>
                                            @error('review')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label for="reviewer" class="form-label">Reviewer</label>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="reviewer" name="reviewer"
                                                   value="{{ $template->reviewer ?? old('reviewer') }}">
                                            @error('reviewer')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="downlad" class="form-label">Number of Downlad</label>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="downlad" name="downlad"
                                                   value="{{ $template->downlad ?? old('downlad') }}">
                                            @error('downlad')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="staticEmail" class="col-md-2 col-form-label">Position</label>
                                        <div class="col-md-4">
                                            <input type="number" name="mainposition" class="form-control"
                                                   value="{{ $template->position }}">
                                            @error('mainposition')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail" class="col-md-2 col-form-label">Status</label>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch is-filled"
                                                 style="text-align:center;padding-top:14px;">
                                                <input class="form-check-input" type="checkbox"
                                                       id="flexSwitchCheckChecked" name="status" style="margin:0 auto;"
                                                       @if ($template->status == 'active') checked="" @endif>
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
                </form>
            </div>
        </section>
    </main>
    @php
        $business_category = explode(',',$template->category);
    @endphp
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
            var businessCategory = {!! json_encode($business_category) !!};
            $('#category').val(businessCategory).trigger('change');
        });
    </script>
@endpush
