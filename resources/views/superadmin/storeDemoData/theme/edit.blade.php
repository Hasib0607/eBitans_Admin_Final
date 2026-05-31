@extends('admin.layouts.main')

{{--banner styles--}}
@push('styles')
    <style>
        .left-menu {
            position: relative;
            top: 50% !important;
        }

        .left-menu ul li {
            float: unset !important;
        }

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

        .rightmenu li {
            float: left !important;
            padding: 1px 16px !important;
        }

        .select2-container .select2-selection--multiple {
            min-height: 40px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: 0px solid black !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 4px !important;
        }

        /*.is-focused{*/
        /*    background-color:red !important;*/
        /*    width:60%;*/
        /*    padding:10px 0px;*/
        /*}*/
        input[type=radio] {
            opacity: 1;
        }

        .headerimg {
            width: 80%;
            height: 150px;
        }
    </style>
@endpush

{{--banner main section--}}
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">

        @include('superadmin.share.design-top-nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">

                {{--home page side nav section--}}
                @include('superadmin.storeDemoData.left-nav')

                {{--banner main container--}}
                <div class="col-md-9 rightmenu">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card h-100">

                                {{--header section--}}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>
                                                Edit Theme
                                            </h4>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <a href="{{ route('superadmin.store.theme.list') }}"
                                               class="btn btn-primary">List</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row d-flex justify-content-center">
                                        <form action="{{ route("superadmin.store.update.theme") }}" method="POST"
                                              class="col-md-6"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <div class="mb-4">
                                                <label for="theme_value" class="form-label">Theme</label>
                                                <select id='theme_value' name="theme_value" class="form-control">
                                                    <option value="">Select Theme</option>
                                                    @php
                                                        $designs = DB::table('designlists')->select('value')->distinct()->pluck('value');
                                                    @endphp
                                                    @if (isset($designs) && count($designs) > 0)
                                                        @foreach ($designs as $design)
                                                            <option
                                                                value='{{ $design }}'
                                                                {{ (isset($data->theme_value) && $data->theme_value == $design) ? 'selected' : '' }}
                                                            >{{ ucfirst($design) }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('theme_value')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                            @php
                                                $selectedCategories = explode(',', $data->category_id ?? '');
                                            @endphp
                                            <div class="mb-4">
                                                <label for="category_id" class="form-label">Selected Category</label>
                                                <select class="form-control js-example-basic-multiple"
                                                        name="category_id[]"
                                                        id="category_id" multiple="multiple">
                                                    @foreach($categories as $parent)
                                                        {{-- Parent category --}}
                                                        <option
                                                            value="{{ $parent->id }}" {{ in_array($parent->id, $selectedCategories) ? 'selected' : '' }}>
                                                            {{ $parent->name }}
                                                        </option>

                                                        {{-- Subcategories --}}
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
                                                @error('category_id')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <button class="btn btn-info rounded font-sm hover-up" type="submit">
                                                    Publish
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
