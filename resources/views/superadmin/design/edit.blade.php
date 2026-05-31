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


        .size li {
            float: left;
        }
    </style>
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.share.design-top-nav')
        <section class="container content-main">
            <form action="{{ route('superadmin.designupdate', $design->id) }}" method="post"
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

                    <div class="col-lg-9" style="margin:0 auto;">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Edit Design</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Type</label>
                                    <select class="form-control" name="type" id="type">
                                        <option>Select Design Type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->type }}"
                                                    @if ($design->type == $type->type) selected @endif>{{ ucwords(str_replace('_', ' ', $type->type)) }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                                @php
                                    $selectedCategories = explode(',', $design->category ?? '');
                                @endphp
                                <div class="mb-4">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-control js-example-basic-multiple" name="category[]"
                                            id="category" multiple="multiple">
                                        @foreach($categories as $parent)
                                            <option
                                                value="{{ $parent->id }}" {{ in_array($parent->id, $selectedCategories) ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>

                                            @if($parent->subcategories && $parent->subcategories->count())
                                                @foreach($parent->subcategories as $sub)
                                                    <option
                                                        value="{{ $sub->id }}" {{ in_array($sub->id, $selectedCategories) ? 'selected' : '' }}>
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
                                <div class="mb-4">
                                    <label for="image" class="form-label">Image</label>
                                    <img src="{{ URL::to('/') }}/assets/images/design/{{ $design->image }}" alt="image"
                                         width="150px" style="margin-bottom:10px;">
                                    <input type="file" placeholder="Type here" class="form-control" id="image"
                                           name="image">
                                    @error('image')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="bg_image" class="form-label">Background Image</label>
                                    <img src="{{ URL::to('/') }}/assets/images/design/{{ $design->bg_image }}"
                                         alt="bg_image"
                                         width="150px" style="margin-bottom:10px;">
                                    <input type="file" placeholder="Type here" class="form-control" id="bg_image"
                                           name="bg_image">
                                    @error('bg_image')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Name</label>
                                    <input type="text" placeholder="Type here" class="form-control"
                                           id="name" value="{{ $design->name ?? '' }}" name="name">
                                    @error('name')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Value</label>
                                    <input type="text" placeholder="Type here" class="form-control"
                                           id="value" value="{{ $design->value ?? '' }}" name="value">
                                    @error('value')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4 row" id="titleInput">
                                    <div class="col-md-8">
                                        <label for="product_name" class="form-label">Default Title</label>
                                        <input type="text" placeholder="Type here" value="{{ $design->title ?? '' }}"
                                               class="form-control"
                                               id="title" name="title">
                                        @error('title')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="product_name" class="form-label">Default Title Color</label>
                                        <input type="color" placeholder="Type here" class="form-control"
                                               style="width:100%; height: 50px;"
                                               value="{{ $design->title_color ?? '' }}"
                                               id="title_color" name="title_color">
                                        @error('title_color')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row" id="subtitleInput">
                                    <div class="col-md-8">
                                        <label for="product_name" class="form-label">Default Sub Title</label>
                                        <input
                                            type="text"
                                            placeholder="Type here"
                                            class="form-control"
                                            id="subtitle"
                                            name="subtitle"
                                            value="{{ $design->subtitle ?? '' }}"
                                        >
                                        @error('subtitle')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="product_name" class="form-label">Default Sub Title Color</label>
                                        <input
                                            type="color"
                                            placeholder="Type here"
                                            class="form-control"
                                            style="width:100%; height: 50px;"
                                            id="subtitle_color"
                                            name="subtitle_color"
                                            value="{{ $design->subtitle_color ?? '' }}"
                                        >
                                        @error('subtitle_color')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <div class="col-md-6">
                                        <label for="product_name" class="form-label">Button</label>
                                        <input
                                            type="text"
                                            placeholder="Type here"
                                            class="form-control"
                                            id="button"
                                            name="button"
                                            value="{{ $design->button ?? '' }}"
                                        >
                                        @error('button')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="product_name" class="form-label">Color</label>
                                        <input
                                            type="color"
                                            placeholder="Type
                                                here"
                                            class="form-control"
                                            style="width:100%; height: 50px;"
                                            id="button_color"
                                            name="button_color"
                                            value="{{ $design->button_color ?? 'transparent' }}">
                                        @error('button_color')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="button_bg_color" class="form-label">Background Color</label>
                                        <input
                                            type="color"
                                            placeholder="Type
                                                here"
                                            class="form-control"
                                            style="width:100%; height: 50px;"
                                            id="button_bg_color"
                                            name="button_bg_color"
                                            value="{{ $design->button_bg_color ?? 'transparent' }}">
                                        @error('button_bg_color')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <div class="col-md-6">
                                        <label for="button1" class="form-label">Button 1</label>
                                        <input
                                            type="text"
                                            placeholder="Type here"
                                            class="form-control"
                                            id="button1"
                                            name="button1"
                                            value="{{ $design->button1 ?? '' }}"
                                        >
                                        @error('button1')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="button1_color" class="form-label">Color</label>
                                        <input
                                            type="color"
                                            placeholder="Type
                                                here"
                                            class="form-control"
                                            style="width:100%; height: 50px;"
                                            id="button1_color"
                                            name="button1_color"
                                            value="{{ $design->button1_color ?? 'transparent' }}">
                                        @error('button1_color')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="button1_bg_color" class="form-label">Background Color</label>
                                        <input
                                            type="color"
                                            placeholder="Type
                                                here"
                                            class="form-control"
                                            style="width:100%; height: 50px;"
                                            id="button1_bg_color"
                                            name="button1_bg_color"
                                            value="{{ $design->button1_bg_color ?? 'transparent' }}">
                                        @error('button1_bg_color')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <div class="col-md-12">
                                        <label for="link" class="form-label">Link</label>
                                        <input
                                            type="text"
                                            placeholder="Link here"
                                            class="form-control"
                                            style="width:100%; height: 50px;"
                                            id="link"
                                            name="link"
                                            value="{{ $design->link ?? '' }}">
                                        @error('link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4" id="imageDescription">
                                    <label for="product_name" class="form-label">Image Description</label>
                                    <input type="text" placeholder="Type here" class="form-control"
                                           value="{{ $design->image_description ?? '' }}"
                                           id="image_description" name="image_description">
                                    @error('image_description')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">Status</label>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch is-filled"
                                             style="text-align:center;padding-top:14px;">
                                            <input class="form-check-input" type="checkbox"
                                                   id="flexSwitchCheckChecked" name="status" style="margin:0 auto;"
                                                   @if ($design->status == 'active') checked="" @endif>
                                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                        </div>
                                        @error('status')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4">
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
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });

        $(document).ready(function () {
            var selectedValue = $('#type').val();
            if (selectedValue === 'slider' || selectedValue === 'banner' || selectedValue === 'banner_bottom') {
                $('#imageDescription').show();  // Show the header input
            } else {
                $('#imageDescription').hide();  // Hide the header input
            }
        });

        $(document).ready(function () {
            $('#type').on('change', function () {
                var selectedValue = $(this).val();

                if (selectedValue === 'slider' || selectedValue === 'banner' || selectedValue === 'banner_bottom') {
                    $('#imageDescription').show();  // Show the header input
                } else {
                    $('#imageDescription').hide();  // Hide the header input
                }
            });
        });
    </script>
@endpush
