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
                <form action="{{ route('superadmin.template.save') }}" method="post" enctype="multipart/form-data">
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
                                    <h4>Add New Template</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Category</label>
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
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Name</label>
                                        <input type="name" placeholder="Type here" class="form-control" id="name"
                                               name="name" value="{{ old('name') }}">
                                        @error('name')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Live Link</label>
                                        <input type="text" placeholder="Type here" class="form-control" id="link"
                                               name="link">
                                        @error('link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Feature Image</label>
                                        <input type="file" placeholder="Type here" class="form-control"
                                               id="feature_image" name="feature_image">
                                        @error('feature_image')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Main Image</label>
                                        <input type="file" placeholder="Type here" class="form-control" id="main_image"
                                               name="main_image">
                                        @error('main_image')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Value</label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="value" name="value" value="{{ old('value') }}">
                                        @error('value')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Short Description</label>
                                        <textarea class="form-control" name="short_description" id="short_description"
                                                  rows="4">{{ old('short_description') }}</textarea>
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
                                                    <option
                                                        value="null">None
                                                    </option>
                                                    @if (isset($designs[$key]) && count($designs[$key]) > 0)
                                                        @foreach ($designs[$key] as $index=>$item)
                                                            <option
                                                                value="{{ $item->value }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error($key)
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            @if($key == 'header' || $key == 'slider'|| $key == 'banner'|| $key == 'banner_bottom'|| $key == 'feature_category'|| $key == 'product'|| $key == 'feature_product'|| $key == 'best_sell_product' || $key == 'new_arrival' || $key == 'blog' || $key == 'testimonial' || $key == 'youtube' || $key == 'brand' || $key == 'footer')
                                                <div class="mb-3 col-md-3">
                                                    <label for="product_name" class="form-label"
                                                           style="width:100px; display:flex; ">Position</label>
                                                    <input type="number" placeholder="Type here" class="form-control"
                                                           id="{{$key.'_position'}}" name="{{$key.'_position'}}"
                                                           value="{{ old($key."_position") ?? 1 }}"
                                                    >
                                                    @error($key.'_position')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="is_premium" class="form-label">Free/Paid</label>
                                            <!--<input type="text" placeholder="Type here" class="form-control" id="auth" name="auth" value="{{ old('auth') }}">-->
                                            <select class="js-example-basic-single form-control" id="is_premium"
                                                    name="is_premium">
                                                <option value="Free">Free</option>
                                                <option value="Paid">Paid</option>
                                            </select>
                                            @error('is_premium')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="price" name="price" value="{{ old('price') }}">
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
                                                <option value="1">1</option>
                                                <option value="1.5">1.5</option>
                                                <option value="2">2</option>
                                                <option value="2.5">2.5</option>
                                                <option value="3">3</option>
                                                <option value="3.5">3.5</option>
                                                <option value="4">4</option>
                                                <option value="4.5">4.5</option>
                                                <option value="5">5</option>
                                            </select>
                                            @error('review')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="reviewer" class="form-label">Reviewer</label>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="reviewer" name="reviewer" value="">
                                            @error('reviewer')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="downlad" class="form-label">Number of Downlad</label>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   id="downlad" name="downlad" value="{{ old('downlad') }}">
                                            @error('downlad')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail" class="col-md-2 col-form-label">Position</label>
                                        <div class="col-md-4">
                                            <input type="number" name="mainposition" class="form-control">
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
                                                       checked="">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endpush
