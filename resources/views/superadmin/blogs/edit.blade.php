@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" src="{{ asset('admin/src/bootstrap-tagsinput.css') }}"/>
    <style>
        /* Tags input section */
        .bootstrap-tagsinput {
            width: 100%;
        }

        .bootstrap-tagsinput {
            background-color: #fff;
            display: inline-block;
            padding: 4px 6px;
            color: #555;
            vertical-align: middle;
            border-radius: 4px;
            max-width: 100%;
            line-height: 22px;
            cursor: text;
        }

        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white;
        }


        .bootstrap-tagsinput .tag [data-role="remove"] {
            margin-left: 8px;
            cursor: pointer;
        }

        .bootstrap-tagsinput .tag [data-role="remove"]::after {
            content: "x";
            padding: 0px 2px;
        }

        .bootstrap-tagsinput .tag [data-role="remove"] {
            cursor: pointer;
        }

        .bootstrap-tagsinput {
            margin: 0;
            width: 100%;
            padding: 0.5rem 0.75rem 0;
            font-size: 1rem;
            line-height: 1.25;
            transition: border-color 0.15s ease-in-out;

            &.has-focus {
                background-color: #fff;
                border-color: #5cb3fd;
            }

            .label-info {
                display: inline-block;
                background-color: #636c72;
                padding: 0 .4em .15em;
                border-radius: .25rem;
                margin-bottom: 0.4em;
            }

            input {
                margin-bottom: 0em;
            }
        }

        .bootstrap-tagsinput .tag [data-role="remove"]:after {
            content: '\00d7';
        }

        /* End Tags input section */

        /* Image previewer section */
        .preview-container {
            display: none;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            /* Add other common styles here */
        }

        .preview-image {
            max-width: 100%;
            max-height: 100%;
            display: block;
            /* Add other common image styles here */
        }

        .thumbnail-preview-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
            /* Add specific styles for thumbnail preview container */
        }

        .image-preview-container {
            max-width: 100%;
            max-height: 100px;
            /* Add specific styles for image preview container */
        }

        /* CSS styles for image preview container */
        .preview-container {
            display: none;
            /* Hide by default */
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        .image-preview-container {
            max-width: 100%;
            /* Adjust as needed */
            max-height: 100px;
            /* Adjust as needed */
            overflow: hidden;
            /* Ensure image doesn't overflow container */
        }

        /* CSS styles for image preview */
        .preview-image {
            width: 100%;
            /* Make sure the image fills the container */
            height: auto;
            /* Maintain aspect ratio */
            display: block;
            /* Prevent extra space below the image */
        }

        /* End Image previewer section  */

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
    @php
        if (Auth::user()->type == 'admin') {
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
                    } elseif ($pr == 'testimonials') {
                        $tt = 1;
                    } elseif ($pr == 'designsettings') {
                        $ds = 1;
                    } else {
                    }
                }
            }
        }
    @endphp
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.blogs.type.sub_category')
        <section class="container content-main">
            <div class="row">
                <form action="{{ route('superadmin.blog.update', $editBlog->id) }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8 mt-4">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            Update Blog
                                        @else
                                            Update Blog
                                        @endif
                                    </h4>

                                    @if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'superstaff')
                                        <a href="{{ isset($editBlog->website) && $editBlog->website == 1 ? "https://www.ebitans.com.bd/en/resources/blogs" : 'https://ebitans.com/blog' }}/{{ $editBlog->permalink ?? $editBlog->slug ?? "" }}"
                                           target="_blank" class="d-flex align-items-center">
                                            <i class="fa fa-external-link" aria-hidden="true"></i>
                                            <span style="margin-left: 6px;margin-top: -1px;">Preview Link</span>
                                        </a>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label for="title" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Title
                                                @else
                                                    Title
                                                @endif
                                                <span class="req">*</span>
                                            </label>
                                            <input type="text" placeholder="Type here" class="form-control"
                                                   id="title" name="title"
                                                   value="{{ $editBlog->title ?? old('title') }}"
                                                   required>
                                            @error('title')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <label for="sub_title" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Meta Description
                                                @else
                                                    Meta Description
                                                @endif

                                            </label>
                                            <textarea name="sub_title" id="sub_title" placeholder="Type here"
                                                      class="form-control">{{ $editBlog->sub_title ?? old('sub_title') }}</textarea>
                                            @error('sub_title')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-md-5 mt-4">
                                            <label for="thumbnail" class="form-label">
                                                Thumbnail
                                            </label>
                                            <!-- Thumbnail preview container -->
                                            <div id="thumbnailPreviewContainer"
                                                 class="preview-container thumbnail-preview-container"
                                                 style="display: block;width: 1200px;height: 100px;max-width: 100%;">
                                                <img id="thumbnailPreview"
                                                     src="{{ asset('BlogImages/'.$editBlog->thumbnail) }}"
                                                     alt="Thumbnail Preview"
                                                     class="preview-image">
                                            </div>
                                            <!-- File input for thumbnail -->
                                            <input type="file" class="form-control" id="thumbnail" name="thumbnail"
                                                   onchange="previewThumbnail(event)">
                                            <!-- Error message for thumbnail -->
                                            <p class="text-danger" id="thumbnailError" style="display:none;"></p>
                                        </div>
                                        <div class="col-md-5 mt-4">
                                            <label for="image" class="form-label">
                                                Image
                                            </label>
                                            <!-- Image preview container -->
                                            <div class="mt-4 preview-container image-preview-container"
                                                 id="imagePreviewContainer" style="display:block; margin-top: 0px !important;
}">
                                                <img id="imagePreview" src="{{ asset('BlogImages/'.$editBlog->image) }}"
                                                     alt="Preview"
                                                     class="preview-image">
                                            </div>
                                            <!-- File input for image -->
                                            <input type="file" class="form-control" id="image" name="image"
                                                   onchange="previewImage(event)">
                                            <!-- Error message for image -->
                                            <p class="text-danger" id="imageError" style="display:none;"></p>
                                        </div>
                                        <div class="col-md-2 mt-4">
                                            <label for="position" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Blog position
                                                @else
                                                    Blog position
                                                @endif

                                            </label>
                                            <input type="text" placeholder="Type here" class="form-control"
                                                   id="position" name="position"
                                                   value="{{ $editBlog->position ?? old('position') }} " required>
                                            @error('posi')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                বিস্তারিত
                                            @else
                                                Description
                                            @endif
                                        </label>
                                        <textarea placeholder="Type here" class="form-control" id="editor" rows="8"
                                                  name="details">
                                            {!! Request::old('details', $editBlog->description ?? '') !!}
                                        </textarea>
                                        @error('details')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="{{ canSuperStaffAccess("blog") ? 'col-lg-4' : 'col-lg-6' }}">
                                            <div class="mb-4">
                                                <label class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        Type
                                                    @else
                                                        Type
                                                    @endif

                                                </label>
                                                <div class="row gx-2">
                                                    <select name="type" class="form-control">
                                                        <option value="">None</option>
                                                        @foreach ($blogTypes as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->id == ($editBlog->type ?? '') ? 'selected' : '' }}>
                                                                {{ $item->type ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('type')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="{{ canSuperStaffAccess("blog") ? 'col-lg-4' : 'col-lg-6' }}">
                                            <div class="mb-4">
                                                <label class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        Permalink
                                                    @else
                                                        Permalink
                                                    @endif

                                                </label>
                                                <div class="row gx-2">
                                                    <input type="text" placeholder="Type here" class="form-control"
                                                           id="permalink" name="permalink"
                                                           value="{{ $editBlog->permalink ?? old('permalink') }} ">
                                                    @error('permalink')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        @if (canSuperStaffAccess("blog"))
                                            <div class="col-lg-4">
                                                <div class="mb-4">
                                                    <label class="form-label">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            Website
                                                        @else
                                                            Website
                                                        @endif
                                                    </label>
                                                    <div class="row gx-2">
                                                        <select class="form-control" id="website" name="website">
                                                            <option
                                                                value="0" {{ isset($editBlog->website) && $editBlog->website == 0 ? "selected" : '' }}>
                                                                eBitans.com
                                                            </option>
                                                            <option
                                                                value="1" {{ isset($editBlog->website) && $editBlog->website == 1 ? "selected" : '' }}>
                                                                eBitans.com.bd
                                                            </option>
                                                        </select>
                                                        @error('website')
                                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <label class="form-label">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            Canonical Url
                                                        @else
                                                            Canonical Url
                                                        @endif
                                                    </label>
                                                    <div class="row gx-2">
                                                        <input type="text" placeholder="Type here" class="form-control"
                                                               id="canonical_url" name="canonical_url"
                                                               value="{{ $editBlog->canonical_url ?? old('canonical_url') ?? "" }} ">
                                                        @error('canonical_url')
                                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="mb-4">
                                                    <label class="form-label">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            Custom Script
                                                        @else
                                                            Custom Script
                                                        @endif
                                                    </label>
                                                    <div class="row gx-2">
                                                        <textarea placeholder="Type here" class="form-control"
                                                                  id="custom_script" rows="8"
                                                                  name="custom_script">{!! Request::old('custom_script', $editBlog->custom_script ?? '') !!}</textarea>

                                                        @error('custom_script')
                                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <br>
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <label for="staticEmail" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        জনপ্রিয় পোস্ট
                                                    @else
                                                        Popular Post
                                                    @endif
                                                </label>
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch is-filled"
                                                         style="text-align:center;padding-top:14px;">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="flexSwitchCheckChecked" name="popular_status"
                                                               data-id="{{ $editBlog->id }}" style="margin:0 auto;"
                                                               @if ($editBlog->popular == 1) checked @endif>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                    @error('popular_status')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <label for="staticEmail" class="col-md-3 col-form-label">
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
                                                               data-id="{{ $editBlog->id }}" style="margin:0 auto;"
                                                               @if ($editBlog->status == 1) checked @endif>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                    @error('status')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="blog_keyword" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    এসইও কীওয়ার্ড
                                                @else
                                                    SEO Keywords
                                                @endif
                                            </label>
                                            <input type="text" class="form-control" id="blog_keyword"
                                                   data-role="tagsinput" name="seo" style="width:100%;display: block;">
                                            <div class="error" style="font-size: 11px; color: red;">
                                                Enter a comma after each tag
                                            </div>
                                            @error('seo_keywords')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info mt-4 ml-3">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            প্রকাশ
                                        @else
                                            Publish
                                        @endif
                                    </button>
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
    <script src="{{ asset('admin/dist/js/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput-angular.js') }}"></script>

    <script>
        // Get the input field by its ID
        var blogKeywordInput = document.getElementById('blog_keyword');

        // Set the value of the input field to the joined string of keyword names
        blogKeywordInput.value = "{{ implode(',', $keywordNames) }}";

        // Function to preview the selected image or thumbnail.
        function previewFile(event, previewImage, previewContainer, errorMessage) {
            var input = event.target;
            var reader = new FileReader();

            reader.onload = function () {
                var dataURL = reader.result;
                previewImage.src = dataURL;
                previewContainer.style.display = dataURL ? 'block' : 'none';
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            } else {
                errorMessage.textContent = "Please select a file.";
                errorMessage.style.display = 'block';
            }
        }

        // Preview thumbnail
        function previewThumbnail(event) {
            var thumbnailPreview = document.getElementById('thumbnailPreview');
            var thumbnailPreviewContainer = document.getElementById('thumbnailPreviewContainer');
            var thumbnailError = document.getElementById('thumbnailError');
            previewFile(event, thumbnailPreview, thumbnailPreviewContainer, thumbnailError);
        }

        // Preview image
        function previewImage(event) {
            var imagePreview = document.getElementById('imagePreview');
            var imagePreviewContainer = document.getElementById('imagePreviewContainer');
            var imageError = document.getElementById('imageError');
            previewFile(event, imagePreview, imagePreviewContainer, imageError);
        }
    </script>

    <script>
        // This sample still does not showcase all CKEditor 5 features (!)
        // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
        CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
            // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format

            ckfinder: {
                uploadUrl: '{{ route('superadmin.blog.ck') . '?_token=' . csrf_token() }}',
            },
            toolbar: {
                items: [
                    'exportPDF', 'exportWord', '|',
                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript',
                    'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed',
                    '|',
                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                    'textPartLanguage', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            // Changing the language of the interface requires loading the language file using the <script> tag.
            // language: 'es',
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
            heading: {
                options: [{
                    model: 'paragraph',
                    title: 'Paragraph',
                    class: 'ck-heading_paragraph'
                },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
            placeholder: 'Enter your page details',
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
            fontSize: {
                options: [10, 12, 14, 'default', 18, 20, 22],
                supportAllValues: true
            },
            // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
            // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
            htmlSupport: {
                allow: [{
                    name: /.*/,
                    attributes: true,
                    classes: true,
                    styles: true
                }]
            },
            // Be careful with enabling previews
            // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
            htmlEmbed: {
                showPreviews: true
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
            link: {
                decorators: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://',
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
            mention: {
                feeds: [{
                    marker: '@',
                    feed: [
                        '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes',
                        '@chocolate', '@cookie', '@cotton', '@cream',
                        '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread',
                        '@gummi', '@ice', '@jelly-o',
                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding',
                        '@sesame', '@snaps', '@soufflé',
                        '@sugar', '@sweet', '@topping', '@wafer'
                    ],
                    minimumCharacters: 1
                }]
            },
            // The "super-build" contains more premium features that require additional configuration, disable them below.
            // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
            removePlugins: [
                // These two are commercial, but you can try them out without registering to a trial.
                // 'ExportPdf',
                // 'ExportWord',
                'CKBox',
                'CKFinder',
                'EasyImage',
                // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                // Storing images as Base64 is usually a very bad idea.
                // Replace it on production website with other solutions:
                // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                // 'Base64UploadAdapter',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                // from a local file system (file://) - load this site via HTTP server if you enable MathType
                'MathType'
            ]
        });
    </script>


    <script>
        $('.ck-placeholder').attr('data-placeholder', 'Enter your page details');
    </script>
@endpush
