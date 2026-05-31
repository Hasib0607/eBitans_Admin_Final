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
    </style>
    <main class="main-content position-relative h-100 border-radius-lg">

        @include('superadmin.share.design-top-nav')
        <section class="container content-main">
            <form action="{{route('superadmin.design.save')}}" method="post" enctype="multipart/form-data">
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
                                <h4>Add New Design</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Type</label>
                                    <select class="form-control" name="type" id="type">
                                        <option>Select Design Type</option>
                                        @foreach($types as $type)
                                            <option
                                                value="{{ $type->type }}">{{ ucwords(str_replace('_', ' ', $type->type)) }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-control js-example-basic-multiple" name="category[]"
                                            id="category" multiple="multiple">
                                        @foreach($categories as $parent)
                                            {{-- Parent category --}}
                                            <option value="{{ $parent->id }}"
                                                {{ (isset($category->parent_id) && $parent->id == $category->parent_id) ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>

                                            {{-- Subcategories --}}
                                            @if($parent->subcategories && $parent->subcategories->count())
                                                @foreach($parent->subcategories as $sub)
                                                    <option value="{{ $sub->id }}"
                                                        {{ (isset($category->parent_id) && $sub->id == $category->parent_id) ? 'selected' : '' }}>
                                                        {{ $sub->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" placeholder="Type here" class="form-control" id="image"
                                           name="image">
                                    @error('image')
                                    <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="bg_image" class="form-label">Background Image</label>
                                    <input type="file" placeholder="Type here" class="form-control" id="bg_image"
                                           name="bg_image">
                                    @error('bg_image')
                                    <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Name</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="name"
                                           name="name">
                                    @error('name')
                                    <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Value</label>
                                    <input type="text" placeholder="Type here" class="form-control" id="value"
                                           name="value">
                                    @error('value')
                                    <p class="text-danger" role="alert">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-4 row">
                                    <div class="col-md-8">
                                        <label for="product_name" class="form-label">Default Title</label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="title" name="title">
                                        @error('title')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="product_name" class="form-label">Default Title Color</label>
                                        <input type="color" placeholder="Type here" class="form-control"
                                               style="width:100%; height: 50px;"
                                               id="title_color" name="title_color">
                                        @error('title_color')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <div class="col-md-8">
                                        <label for="product_name" class="form-label">Default Sub Title</label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="subtitle" name="subtitle">
                                        @error('subtitle')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="product_name" class="form-label">Default Sub Title Color</label>
                                        <input type="color" placeholder="Type here" class="form-control"
                                               style="width:100%; height: 50px;"
                                               id="subtitle_color" name="subtitle_color">
                                        @error('subtitle_color')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <div class="col-md-6">
                                        <label for="product_name" class="form-label">Button</label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="button" name="button">
                                        @error('button')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="product_name" class="form-label">Color</label>
                                        <input type="color" placeholder="Type here" class="form-control"
                                               style="width:100%; height: 50px;"
                                               id="button_color" name="button_color" value="transparent">
                                        @error('button_color')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="button_bg_color" class="form-label">Background Color</label>
                                        <input
                                            type="color"
                                            placeholder="Type here"
                                            class="form-control"
                                            style="width:100%; height: 50px;"
                                            id="button_bg_color"
                                            name="button_bg_color"
                                            value="transparent">
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
                                            value=""
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
                                            value="transparent">
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
                                            value="transparent">
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
                                            value="">
                                        @error('link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4" id="imageDescription">
                                    <label for="product_name" class="form-label">Image Description</label>
                                    <input type="text" placeholder="Type here" class="form-control"
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
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                                   name="status" style="margin:0 auto;" checked="">
                                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                        </div>
                                        @error('status')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label"></label>
                                    <button class="btn btn-info rounded font-sm hover-up" type="submit">Publish</button>
                                </div>

                            </div>
                        </div> <!-- card end// -->

                    </div>

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

        $('#subtitleInput').hide();
        $('#titleInput').hide();
        $('#imageDescription').hide();

        $(document).ready(function () {
            $('#type').on('change', function () {
                var selectedValue = $(this).val();

                if (selectedValue === 'slider' || selectedValue === 'banner' || selectedValue === 'banner_bottom') {
                    $('#subtitleInput').show();  // Show the header input
                    $('#titleInput').show();  // Show the header input
                    $('#imageDescription').show();  // Show the header input
                } else {
                    $('#subtitleInput').hide();  // Hide the header input
                    $('#titleInput').hide();  // Hide the header input
                    $('#imageDescription').hide();  // Hide the header input
                }
            });
        });
    </script>
@endpush
