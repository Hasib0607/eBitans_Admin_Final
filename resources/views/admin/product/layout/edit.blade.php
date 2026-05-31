@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/src/bootstrap-tagsinput.css') }}"/>

    <!-- Include stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('admin/src/select2/select2.min.css') }}"/>
    <style>
        .bootstrap-tagsinput {
            width: 100%;
        }

        .tagBro {
            width: inherit !important;
        }

        .bootstrap-tagsinput {
            background-color: #fff;
            /*border: 1px solid #ccc;*/
            /*box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);*/
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

        .label-info {
            background-color: #5bc0de;
        }

        .label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
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


        /* This is copied from https://github.com/blueimp/jQuery-File-Upload/blob/master/css/jquery.fileupload.css */
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

        .oldImg-wrap {
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

        .oldImg-wrap .oldClose {
            position: absolute;
            top: 3px;
            right: 2px;
            z-index: 100;
            background-color: #d0e5f5;
            padding: 2px 3px 2px 3px;
            color: #000;
            font-weight: bolder;
            cursor: pointer;
            opacity: 0.5;
            font-size: 12px;
            line-height: 10px;
            border-radius: 50%;
        }

        .img-wrap:hover .close {
            opacity: 1;
            background-color: #ff0000;
        }

        .oldImg-wrap:hover .oldClose {
            opacity: 1;
            background-color: #ff0000;
        }

        .FileNameCaptionStyle {
            font-size: 12px;
        }

        #onlycolors, #colorrss, #unittss, #sizess {
            font-size: 14px;
            /*display: none;*/
        }

        .ck-editor__editable {
            height: 400px; /* Set the fixed height */
        }

        @media (max-width: 500px) {
            #submitBtnSection .btn, #submitBtnSection a {
                font-size: 10px;
                padding: 10px 13px;
            }
        }

        .input-upload.d-flex {
            flex-wrap: wrap;
        }

        .image-input-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .image-input-button {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            font-size: 20px;
        }

        .image-input-button:hover {
            background-color: #0056b3;
        }

        .image-preview {
            display: block;
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border: 2px solid #ccc;
            border-radius: 8px;
        }

        #imageInput {
            display: none;
        }

        label.image-input-button {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }
    </style>

@endpush
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">

        {{-- Page top bar menu --}}
        @include('admin.admin_top_bar_category.index')

        <?php
        $attri = DB::table('veriants')
            ->where('pid', $product['id'])
            ->get();
        $attri_color = DB::table('veriants')
            ->where('pid', $product['id'])
            ->select('color')
            ->get();
        $attri_unit = DB::table('veriants')
            ->where('pid', $product['id'])
            ->where('color', null)
            ->where('size', null)
            ->select('volume')
            ->get();
        $attri_size = DB::table('veriants')
            ->where('pid', $product['id'])
            ->where('color', null)
            ->select('size')
            ->get();
        $attri_onlycolor = DB::table('veriants')
            ->where('pid', $product['id'])
            ->where('size', null)
            ->select('color')
            ->get();
        $select_sizess = DB::table('veriants')
            ->where('pid', $product['id'])
            ->where('color', null)
            ->where('size', '!=', null)
            ->get();
        $select_unitsss = DB::table('veriants')
            ->where('pid', $product['id'])
            ->where('color', null)
            ->where('size', null)
            ->where('volume', '!=', null)
            ->get();
        $select_onlycolor = DB::table('veriants')
            ->where('pid', $product['id'])
            ->where('color', '!=', null)
            ->where('size', null)
            ->get();
        $size = DB::table('sizes')
            ->where('store_id', $store_id)
            ->get();
        $colors = DB::table('colors')
            ->where('store_id', $store_id)
            ->get();
        ?>

        {{--color and size variant--}}
        <?php
        if (isset($product)) {
            $attri_colorss = DB::table('veriants')
                ->select('veriants.*', 'c.symbol', 'c.code')
                ->join('products as p', 'p.id', '=', 'veriants.pid')
                ->join('currencies as c', 'p.currency_id', '=', 'c.id')
                ->when('p.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                    function ($query) use ($current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(veriants.additional_price / c.rate * " . $current_currency->rate . " , 2) as additional_price"),
                            DB::raw("'{$current_currency->symbol}' as symbol"),
                            DB::raw("'{$current_currency->code}' as code"),
                        ]);
                    })
                ->when('p.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                    function ($query) use ($store, $current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.additional_price / {$store->currency_rate}, 2) as additional_price"),
                            DB::raw("'{$current_currency->symbol}' as symbol"),
                            DB::raw("'{$current_currency->code}' as code"),
                        ]);
                    })
                ->where('veriants.pid', $product['id'])
                ->where('veriants.color', '!=', null)
                ->where('veriants.size', '!=', null)
                ->get();
        }

        ?>

        {{--onlycolor variant--}}
        <?php
        if (isset($product)) {
            $attri_onlycolor = DB::table('veriants')
                ->select('veriants.*', 'c.symbol', 'c.code')
                ->join('products as p', 'p.id', '=', 'veriants.pid')
                ->join('currencies as c', 'p.currency_id', '=', 'c.id')
                ->when('p.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                    function ($query) use ($current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(veriants.additional_price / c.rate * " . $current_currency->rate . " , 2) as additional_price"),
                            DB::raw("'{$current_currency->symbol}' as symbol"),
                            DB::raw("'{$current_currency->code}' as code"),
                        ]);
                    })
                ->when('p.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                    function ($query) use ($store, $current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.additional_price / {$store->currency_rate}, 2) as additional_price"),
                            DB::raw("'{$current_currency->symbol}' as symbol"),
                            DB::raw("'{$current_currency->code}' as code"),
                        ]);
                    })
                ->where('veriants.pid', $product['id'])
                ->where('veriants.size', null)
                ->where('veriants.color', '!=', null)
                ->get();
        }
        ?>

        {{--unit variant--}}
        <?php
        if (isset($product)) {
            $attri_unitsss = DB::table('veriants')
                ->select('veriants.*', 'c.symbol', 'c.code')
                ->join('products as p', 'p.id', '=', 'veriants.pid')
                ->join('currencies as c', 'p.currency_id', '=', 'c.id')
                ->when('p.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                    function ($query) use ($current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(veriants.additional_price / c.rate * " . $current_currency->rate . " , 2) as additional_price"),
                            DB::raw("'{$current_currency->symbol}' as symbol"),
                            DB::raw("'{$current_currency->code}' as code"),
                        ]);
                    })
                ->when('p.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                    function ($query) use ($store, $current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.additional_price / {$store->currency_rate}, 2) as additional_price"),
                            DB::raw("'{$current_currency->symbol}' as symbol"),
                            DB::raw("'{$current_currency->code}' as code"),
                        ]);
                    })
                ->where('veriants.pid', $product['id'])
                ->where('veriants.color', null)
                ->where('veriants.size', null)
                ->where('veriants.volume', '!=', null)
                ->get();
        }
        ?>


        {{--Size variant--}}
        <?php
        if (isset($product)) {
            $attri_sizess = DB::table('veriants')
                ->select('veriants.*', 'c.symbol', 'c.code')
                ->join('products as p', 'p.id', '=', 'veriants.pid')
                ->join('currencies as c', 'p.currency_id', '=', 'c.id')
                ->when('p.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                    function ($query) use ($current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(veriants.additional_price / c.rate * " . $current_currency->rate . " , 2) as additional_price"),
                            DB::raw("'{$current_currency->symbol}' as symbol"),
                            DB::raw("'{$current_currency->code}' as code"),
                        ]);
                    })
                ->when('p.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                    function ($query) use ($store, $current_currency) {
                        $query->addSelect([
                            DB::raw("ROUND(products.additional_price / {$store->currency_rate}, 2) as additional_price"),
                            DB::raw("'{$current_currency->symbol}' as symbol"),
                            DB::raw("'{$current_currency->code}' as code"),
                        ]);
                    })
                ->where('veriants.pid', $product['id'])
                ->where('veriants.color', null)
                ->where('veriants.size', '!=', null)
                ->get();
        }
        ?>


        {{--Form input section--}}
        <section class="container content-main">
            <form action="{{ route('admin.updateproduct', $product['id']) }}" method="post"
                  enctype="multipart/form-data" id="productUpdateForm">
                <input type="hidden" name="index" value="1" id="index">
                <input type="hidden" name="page_type" value="landing">
                @csrf

                @include('admin.product.layout.product-input-field', ['editPage' => true])
            </form>
        </section>
    </main>
@endsection

@push('scripts')
    <!-- Include the Quill library -->
    <script src="{{ asset('admin/dist/js/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput-angular.js') }}"></script>
    <script src="{{ asset('admin/src/select2/select2.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var layoutCount = {{ isset($product['layout']) ? count($product['layout']) : 0 }};
            var count = {{$count ?? 11 }};
            count = count + layoutCount;

            async function fetchButtonDesign(type, count) {
                try {
                    return await $.ajax({
                        url: '{{ route("admin.get.layout.custom.design") }}', // Laravel route
                        method: 'POST',
                        data: {
                            product: @json($product ?? null), // Safely pass product object
                            customizable: {{ $customizable ?? 'false' }},
                            index: count,
                            type: type,
                            _token: '{{ csrf_token() }}' // Required for POST in Laravel
                        }
                    });
                } catch (error) {
                    return '';
                }
            }

            $('#design-add').on('click', async function () {
                const type = $('#type-define').val();
                const designList = $('#design-list');

                let buttonDesign = "";
                if (type == "button") {
                    let button = $(this);
                    button.prop('disabled', true); // Disable button to prevent multiple clicks
                    button.html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`);

                    buttonDesign = await fetchButtonDesign(type, count);

                    // Restore button text after loading
                    button.prop('disabled', false);
                    button.html('ADD');
                }

                let positionLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn') অবস্থান @else Position @endif`;
                let imageLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                ছবি
                @else
                Image
               @endif`;
                let linkLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                লিঙ্ক
                @else
                Link
              @endif`;
                let buttonLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                বোতাম
@else
                Button
@endif`;

                let descriptionLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                বিস্তারিত
@else
                Description
@endif`;

                let subtitleLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                উপ-শিরোনাম
@else
                Sub Title
@endif`;
                let titleLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                উিরোনাম
@else
                Title
@endif`;
                let testimonialLabel = `@if (Session::has('lang') && Session::get('lang') == 'bn')
                প্রশংসাপত্র
@else
                Testimonial
@endif`;

                let oldDetails = `{!! Request::old('details', '') !!}`;
                let errorDetails = `@error('details')
                <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror`;

                let oldTestimonial = `{!! Request::old('testimonial', '') !!}`;
                let errorTestimonial = `@error('testimonial')
                <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror`;

                let position = `<div class="mb-2 col">
                                        <label for="position" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${positionLabel} <!-- Blade-generated position label -->
                                                <span class="req">*</span>
                                            </div>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control bg-white" id="position"
                                               value="${count}" name="layouts[${count}][position]">
                                        <input type="hidden" name="layouts[${count}][type]" value="${type}">
                                    </div>`
                let image = `<div class="mb-2 col-md-6">
                                        <label for="image" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${imageLabel} <!-- Blade-generated position label -->
                                                <span class="req">*</span>
                                            </div>
                                        </label>
                                        <input type="file" placeholder="Type here" class="form-control bg-white" id="image"
                                                name="layouts[${count}][link]">
                                    </div>`
                let link = `<div class="mb-2 col">
                                        <label for="link" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${linkLabel} <!-- Blade-generated position label -->
                                                <span class="req">*</span>
                                            </div>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control bg-white" id="link"
                                                name="layouts[${count}][link]">
                                    </div>`
                let button_link = `<div class="mb-2 col">
                                        <label for="button_link" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${linkLabel} <!-- Blade-generated position label -->
                                                <span class="req">*</span>
                                            </div>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control bg-white" id="button_link"
                                                name="layouts[${count}][link]" value="/checkout">
                                    </div>`
                let button = `<div class="mb-4 col-md-6">
                                        <label for="button" class="form-label d-flex justify-content-between">
                                            <div>
                                                ${buttonLabel}
                                                <span class="req">*</span>
                                            </div>
                                            ${buttonDesign} <!-- Blade content injected here -->
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control bg-white" id="button"
                                               name="layouts[${count}][button]" value='Checkout'>
                                    </div>`
                let description = `<div class="mb-4 col-md-12">
                                        <label class="form-label">
                                            ${descriptionLabel} <!-- Blade-generated description label -->
                                        </label>
                                        <textarea placeholder="Type here" class="form-control" id="editor${count}" rows="8" name="layouts[${count}][text]">
                                            ${oldDetails}
                                        </textarea>
                                        ${errorDetails}
                                    </div>`;
                let title = `<div class="mb-4 col-md-12">
                                        <label class="form-label">
                                            ${titleLabel} <!-- Blade-generated description label -->
                                        </label>
                                        <textarea placeholder="Type here" class="form-control" id="editor${count}" rows="8" name="layouts[${count}][text]">
                                            ${oldDetails}
                                        </textarea>
                                        ${errorDetails}
                                    </div>`;
                let subtitle = `<div class="mb-4 col-md-12">
                                        <label class="form-label">
                                            ${subtitleLabel} <!-- Blade-generated description label -->
                                        </label>
                                        <textarea placeholder="Type here" class="form-control" id="editor${count}" rows="8" name="layouts[${count}][text]">
                                            ${oldDetails}
                                        </textarea>
                                        ${errorDetails}
                                    </div>`;

                let testimonial = `<div class="mb-4 col-md-12">
                                        <label class="form-label">
                                            ${testimonialLabel} <!-- Blade-generated description label -->
                                        </label>
                                        <textarea placeholder="Type here" class="form-control" id="editor${count}" rows="8" name="layouts[${count}][text]">
                                            ${oldTestimonial}
                                        </textarea>
                                        ${errorTestimonial}
                                    </div>`;

                switch (type) {
                    case 'title':
                        let titleItem = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Title - ${count - 10}</h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="title">
                                <div class="row">
                                    ${position}
                                    ${title}
                                </div>
                            </div>`;
                        count++;
                        designList.append(titleItem);
                        break;
                    case 'subtitle':
                        let subtitleItem = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Subtitle - ${count - 10}</h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="subtitle">
                                <div class="row">
                                    ${position}
                                    ${subtitle}
                                </div>
                            </div>`;
                        count++;
                        designList.append(subtitleItem);
                        break;
                    case 'description':
                        let item = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Description - ${count - 10}</h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="description">
                                <div class="row">
                                    ${position}
                                    ${description}
                                </div>
                            </div>`;
                        count++;
                        designList.append(item);
                        break;
                    case 'button':
                        var buttonItem = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Button - ${count - 10} </h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="button" >
                                <div class="row">
                                    ${button}
                                    ${button_link}
                                </div>
                            </div>`;
                        count++;
                        designList.append(buttonItem);
                        break;
                    case 'image':
                        let imageItem = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Image - ${count - 10}</h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="image">
                                <div class="row">
                                    ${image}
                                    ${position}
                                    ${description}
                                </div>
                            </div>`;
                        count++;
                        designList.append(imageItem);
                        break;
                    case 'testimonial':
                        let testimonialItem = `<div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6>Testimonial - ${count - 10}</h6>
                                    <i class="fa fa-times cursor-pointer design-remove"></i>
                                </div>
                                <input type="hidden" name="layouts[${count}][type]" value="testimonial">
                                <div class="row">
                                    ${position}
                                    ${testimonial}
                                </div>
                            </div>`;
                        count++;
                        designList.append(testimonialItem);
                        break;
                    default:
                    // code block
                }

                // Removing feature
                $(document).on('click', '.design-remove', function () {
                    $(this).closest('.design-item').remove();
                });

                const id = '#editor' + (count - 1);

                CKEDITOR.ClassicEditor.create(document.querySelector(`#editor${count - 1}`), {
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
                    placeholder: 'Enter your text here...',
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

            })

        });
    </script>

    <script>
        CKEDITOR.ClassicEditor.create(document.querySelector(".editor"), {
            ckfinder: {
                uploadUrl: '{{ route('admin.productImage.ck') . '?_token=' . csrf_token() }}',
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
                        '@cupcake', '@danish', '@donut', '@drag├йe', '@fruitcake', '@gingerbread',
                        '@gummi', '@ice', '@jelly-o',
                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding',
                        '@sesame', '@snaps', '@souffl├й',
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

    @php
        $subcategory = '';
        if (isset($product) && isset($product['subcategory'])){
            $subcategory = DB::table('categories')
                ->whereIn('id', explode(',', $product['subcategory'] ?? ""))
                ->where('status', 'active')
                ->pluck("id")->toArray();
            $subcategory = implode(",", $subcategory);
        }
    @endphp


    <script>
        function normalizeProductImagePathForCompare(path) {
            if (!path) {
                return '';
            }
            path = String(path).trim();
            try {
                if (/^https?:\/\//i.test(path)) {
                    return new URL(path).pathname.replace(/^\//, '');
                }
            } catch (err) {
            }
            return path.replace(/^\//, '');
        }

        function syncProductImageHiddenFieldsAfterRemove(removeUrl) {
            var galleryMatch = removeUrl.match(/\/product\/removegalleryimage\/[^/]+\/(.+)$/);
            if (galleryMatch) {
                var removed = decodeURIComponent(galleryMatch[1]);
                var targetNorm = normalizeProductImagePathForCompare(removed);
                var $galleryInput = $('#imageUrlsInput');
                if ($galleryInput.length) {
                    var parts = $galleryInput.val().split(',').map(function (s) {
                        return s.trim();
                    }).filter(Boolean);
                    parts = parts.filter(function (p) {
                        return normalizeProductImagePathForCompare(p) !== targetNorm;
                    });
                    $galleryInput.val(parts.join(','));
                }
                $('input[name="oldGalleryImage"]').val(function (_, cur) {
                    if (!cur) {
                        return '';
                    }
                    var op = cur.split(',').map(function (s) {
                        return s.trim();
                    }).filter(Boolean);
                    op = op.filter(function (p) {
                        return normalizeProductImagePathForCompare(p) !== targetNorm;
                    });
                    return op.join(',');
                });
                return;
            }
            var mainMatch = removeUrl.match(/\/product\/removeimage\/[^/]+\/([^/?]+)$/);
            if (mainMatch) {
                var removedMain = decodeURIComponent(mainMatch[1]);
                var $oldImg = $('input[name="oldImage"]');
                if (!$oldImg.length) {
                    return;
                }
                var cur = $oldImg.val() || '';
                var mparts = cur.split(',').map(function (s) {
                    return s.trim();
                }).filter(Boolean);
                mparts = mparts.filter(function (p) {
                    return p !== removedMain;
                });
                $oldImg.val(mparts.join(','));
            }
        }

        $(document).on('click', '.imageUploadRemoveBtn', function (e) {
                let $btn = $(this);
                let url = $btn.data('remove-url');
                if (!url) {
                    return;
                }
                e.preventDefault();

                const $imageWrapper = $btn.closest('.imgWrapperDiv');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This image will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed || result.value === true) {
                        $imageWrapper.css('display', 'none');

                        $.ajax({
                            url: `${url}`,
                            type: 'GET',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                $imageWrapper.remove();
                                syncProductImageHiddenFieldsAfterRemove(url);
                            },
                            error: function (xhr) {
                                $imageWrapper.css('display', 'inline-block');

                                Swal.fire(
                                    'Error!',
                                    'Failed to delete image.',
                                    'error'
                                );
                            }
                        });
                    }
                });
        });
        
        
        // $('#tagBro').attr("placeholder", "input seo keywords");
        // $('.tagBro').hide();
        $(document).ready(function () {
            $('#category').select2();
            $('#subcategory').select2();

            // Get sub-category on select category
            $("#category").on('change', function () {
                var catid = $(this).val();
                $('#subcategory').empty();

                $.get('/getsubcat', {
                    catid: catid
                }, function (data) {
                    for (var i = 0; i < data.length; i++) {
                        $('#subcategory').append(
                            '<option value="' + data[i].id + '">' + data[i].name + '</option>'
                        ).trigger('change');
                    }
                    $('#subcategory').val([{{ $subcategory }}]).trigger('change');
                });
            });


            $("#productUpdateForm").on("submit", function (e) {
                $("#publishBtn").prop("disabled", true).text("Processing...");
                $("#updateBtn").prop("disabled", true);
                $("#duplicateBtn").prop("disabled", true);
            });
        });
    </script>

    <script>
        function mouseOverShipping() {
            if ($('#shippingCheck').val() == 1) {
                $('#shipping-div').show();
                $('#shiphide').show();
                $('#shipshow').hide();
            }

        }

        function mouseOverShippingmouseOut() {
            if ($('#shippingCheck').val() == 1) {
                $('#shipping-div').hide();
                $('#shipshow').show();
                $('#shiphide').hide();
            }
        }

        function openShipping() {
            if ($('#shippingCheck').val() == 1) {
                $('#shippingCheck').val(0);
                $('#shiphide i').css("color", "#f1593a");
                $('#shipping-div').show();
                $('#shiphide').show();
                $('#shipshow').hide();
            } else {
                $('#shippingCheck').val(1)
            }

        }

        function mouseOverVariant() {
            if ($('#attriCheck').val() == 1) {
                $('#attri-div').show();
                $('#attrihide').show();
                $('#attrishow').hide();
            }

        }

        function mouseOverVariantmouseOut() {
            if ($('#attriCheck').val() == 1) {
                $('#attri-div').hide();
                $('#attrishow').show();
                $('#attrihide').hide();
            }
        }

        function openAttri() {
            if ($('#attriCheck').val() == 1) {
                $('#attriCheck').val(0);
                $('#attrihide i').css("color", "#f1593a");
            } else {
                $('#attriCheck').val(1)
            }

            $('#attri-div').show();
            $('#attrihide').show();
            $('#attrishow').hide();
        }
    </script>


    <script>
        //I added event handler for the file upload control to access the files properties.
        document.addEventListener("DOMContentLoaded", init, false);

        //To save an array of attachments
        var AttachmentArray = [];
        const imagesArray = [];

        //counter for attachment array
        var arrCounter = 0;

        //to make sure the error message for number of files will be shown only one time.
        var filesCounterAlertStatus = false;

        //un ordered list to keep attachments thumbnails
        var ul = document.createElement("ul");
        ul.className = "thumb-Images";
        ul.id = "imgList";

        function init() {
            //add javascript handlers for the file upload event
            document
                .querySelector("#image")
                .addEventListener("change", handleFileSelect, false);
        }


        function updateHiddenField() {
            const imageField = document.getElementById("image");

            if (imageField) {
                const dataTransfer = new DataTransfer();

                imagesArray.forEach((file) => {
                    dataTransfer.items.add(file);
                });

                imageField.files = dataTransfer.files; // Directly assign files from DataTransfer
            }
        }


        //the handler for file upload event
        function handleFileSelect(e) {
            // Ensure the user selects files
            if (!e.target.files) return;

            // Obtain a File reference
            const files = e.target.files;

            // Loop through the FileList and render image files as thumbnails
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Instantiate a FileReader object to read its contents into memory
                const fileReader = new FileReader();

                // Closure to capture the file information and apply validation
                fileReader.onload = (function (readerEvt) {
                    return function (e) {
                        // Apply validation rules for attachments upload
                        const validate = ApplyFileValidationRules(readerEvt);

                        if (validate) {
                            // Render attachments thumbnails
                            RenderThumbnail(e, readerEvt);

                            // Fill the array of attachments
                            FillAttachmentArray(e, readerEvt);

                            // Push the file into imagesArray
                            imagesArray.push(readerEvt);
                            updateHiddenField();
                        }
                    };
                })(file);

                // Read the image file as a data URL
                fileReader.readAsDataURL(file);
            }

            document
                .getElementById("image")
                .addEventListener("change", handleFileSelect, false);
        }

        //To remove attachment once user click on x button
        jQuery(function ($) {
            $("div").on("click", ".img-wrap .close", function () {
                var id = $(this)
                    .closest(".img-wrap")
                    .find("img")
                    .data("id");

                //to remove the deleted item from array
                var elementPos = AttachmentArray.map(function (x) {
                    return x.FileName;
                }).indexOf(id);
                if (elementPos !== -1) {
                    AttachmentArray.splice(elementPos, 1);
                }

                //to remove image tag
                $(this)
                    .parent()
                    .find("img")
                    .not()
                    .remove();

                //to remove div tag that contain the image
                $(this)
                    .parent()
                    .find("div")
                    .not()
                    .remove();

                //to remove div tag that contain caption name
                $(this)
                    .parent()
                    .parent()
                    .find("div")
                    .not()
                    .remove();

                //to remove li tag
                var lis = document.querySelectorAll("#imgList li");
                for (var i = 0;
                     (li = lis[i]); i++) {
                    if (li.innerHTML == "") {
                        li.parentNode.removeChild(li);
                    }
                }
            });
        });

        //Apply the validation rules for attachments upload
        function ApplyFileValidationRules(readerEvt) {
            var moduleIsNull = "{{ $moduleIsNull }}";

            //To check file type according to upload conditions
            if (CheckFileType(readerEvt.type) == false) {
                $('#image').val('');
                swal.fire(
                    'Error!',
                    "The file (" +
                    readerEvt.name +
                    ") does not match the upload conditions, You can only upload JPG/JPEG/jpg/png/gif/webp/jpeg files",
                    'error'
                );
                e.preventDefault();
                return false;
            }

            // //To check file Size according to upload conditions
            if (moduleIsNull == 1) {
                if (CheckFileSize(readerEvt.size, 6000000) == false) {
                    handleSizeError(6);
                    return false;
                }
            } else {
                if (CheckFileSize(readerEvt.size, 200000) == false) {
                    handleSizeError(200);
                    return false;
                }
            }

            //To check files count according to upload conditions
            if (CheckFilesCount(AttachmentArray) == false) {
                if (!filesCounterAlertStatus) {
                    filesCounterAlertStatus = true;
                    $('#image').val('');
                    swal.fire(
                        'Error!',
                        "You have added more than 10 files. According to upload conditions you can upload 10 files maximum",
                        'error'
                    );
                }
                e.preventDefault();
                return false;
            }

            return true;
        }

        function CheckFileSize(fileSize, maxSize) {
            return fileSize < maxSize;
        }

        // Helper function to handle size error
        function handleSizeError(maxSizeInMB) {
            var moduleIsNull = "{{ $moduleIsNull }}";
            var message = "The file does not match the upload conditions. ";

            if (moduleIsNull == 1) {
                message += "The maximum file size for uploads should not exceed " + maxSizeInMB + " MB";
            } else {
                message += "The maximum file size for uploads should not exceed " + maxSizeInMB + " KB";
            }

            $('#image').val('');
            swal.fire('Error!', message, 'error');
            e.preventDefault();
        }

        //To check file type according to upload conditions
        function CheckFileType(fileType) {
            const allowedTypes = ["image/jpeg", "image/png", "image/svg", "image/webp", "image/jpg"];

            return allowedTypes.includes(fileType.toLowerCase());
        }

        //To check files count according to upload conditions
        function CheckFilesCount(AttachmentArray) {
            //Since AttachmentArray.length return the next available index in the array,
            //I have used the loop to get the real length
            var len = 0;
            for (var i = 0; i < AttachmentArray.length; i++) {
                if (AttachmentArray[i] !== undefined) {
                    len++;
                }
            }
            //To check the length does not exceed 10 files maximum
            if (len > 9) {
                return false;
            } else {
                return true;
            }
        }

        //Render attachments thumbnails.
        function RenderThumbnail(e, readerEvt) {
            var li = document.createElement("li");
            ul.appendChild(li);
            li.innerHTML = [
                '<div class="img-wrap"> <span class="close">&times;</span>' +
                '<img class="thumb" src="',
                e.target.result,
                '" title="',
                escape(readerEvt.name),
                '" data-id="',
                readerEvt.name,
                '"/>' + "</div>"
            ].join("");

            // var div = document.createElement("div");
            // div.className = "FileNameCaptionStyle";
            // li.appendChild(div);
            // div.innerHTML = [readerEvt.name].join("");
            document.getElementById("Filelist").insertBefore(ul, null);
        }

        //Fill the array of attachment
        function FillAttachmentArray(e, readerEvt) {
            AttachmentArray[arrCounter] = {
                AttachmentType: 1,
                ObjectType: 1,
                FileName: readerEvt.name,
                FileDescription: "Attachment",
                NoteText: "",
                MimeType: readerEvt.type,
                Content: e.target.result.split("base64,")[1],
                FileSizeInBytes: readerEvt.size
            };
            arrCounter = arrCounter + 1;
        }
    </script>

    <script>
        $('.deleteattri').on('click', function () {
            var id = $(this).closest('tr').find('#attriid').val();
            var quantity = $(this).closest('tr').find('#qunty').val();
            var size = $(this).closest('tr').find('#sizs').val();
            var color = $(this).closest('tr').find('#clor').val();
            var additional_price = $(this).closest('tr').find('#additionalpricess').val();
            console.log(id);
            console.log(quantity);
            console.log(size);
            console.log(color);
            $.get('/deleteattribute', {
                id: id,
                quantity: quantity,
                size: size,
                color: color,
                additional_price: additional_price
            }, function (data) {
                console.log(data);
                window.location.reload();
            });
        });
        $('.deletesizeattri').on('click', function () {
            var id = $(this).closest('tr').find('#attriid').val();
            var quantity = $(this).closest('tr').find('#qunty').val();
            var size = $(this).closest('tr').find('#sizs').val();
            var additional_price = $(this).closest('tr').find('#additionalpricess').val();
            console.log(id);
            console.log(quantity);
            console.log(size);
            $.get('/deletesizeattribute', {
                id: id,
                quantity: quantity,
                size: size,
                additional_price: additional_price
            }, function (data) {
                console.log(data);
                window.location.reload();
            });
        });
        $('.deleteonlycolorattri').on('click', function () {
            var id = $(this).closest('tr').find('#attriid').val();
            var quantity = $(this).closest('tr').find('#qunty').val();
            var color = $(this).closest('tr').find('#color').val();
            var additional_price = $(this).closest('tr').find('#additionalpricess').val();
            $.get('/deleteonlycolorattribute', {
                id: id,
                quantity: quantity,
                color: color,
                additional_price: additional_price
            }, function (data) {
                window.location.reload();
            });
        });
        $('.deleteunitattri').on('click', function () {
            var id = $(this).closest('tr').find('#attriid').val();
            var quantity = $(this).closest('tr').find('#qunty').val();
            var volume = $(this).closest('tr').find('#volumess').val();
            var unit = $(this).closest('tr').find('#unitss').val();
            var additional_price = $(this).closest('tr').find('#additionalpricess').val();
            $.get('/deleteunitattribute', {
                id: id,
                quantity: quantity,
                volume: volume,
                unit: unit,
                additional_price: additional_price
            }, function (data) {
                window.location.reload();
            });
        });

        $(document).ready(function () {
                <?php
            if (isset($attri_colorss) && count($attri_colorss) > 0){

            }else{
                ?>
            $('#colorrss').hide();
                <?php
            }
                ?>
                <?php
            if (isset($attri_unitsss) && count($attri_unitsss) > 0){

            }else{
                ?>
            $('#unittss').hide();
                <?php
            }
                ?>
                <?php
            if (isset($attri_sizess) && count($attri_sizess) > 0){

            }else{
                ?>
            $('#sizess').hide();
                <?php
            }
                ?>
                <?php
            if (isset($attri_onlycolor) && count($attri_onlycolor) > 0){

            }else{
                ?>
            $('#onlycolors').hide();
                <?php
            }
                ?>
            $('#shiphide').hide();
            $('#shipping-div').hide();
            $('#attrihide').hide();
            $('#attri-div').hide();
            $('#attributes').on('change', function () {
                var l = this.value;
                if (l == 'none') {
                    changeVarient(l);
                    // $('#colorrss').hide();
                    // $('#unittss').hide();
                    // $('#sizess').hide();
                    // $('#onlycolors').hide();
                } else if (l == 'color') {
                    changeVarient(l);
                    // $('#colorrss').show();
                    // $('#unittss').hide();
                    // $('#sizess').hide();
                    // $('#onlycolors').hide();
                } else if (l == 'unit') {
                    changeVarient(l);
                    // $('#colorrss').hide();
                    // $('#unittss').show();
                    // $('#sizess').hide();
                    // $('#onlycolors').hide();
                } else if (l == 'onlycolor') {
                    changeVarient(l);
                    // $('#colorrss').hide();
                    // $('#unittss').hide();
                    // $('#sizess').hide();
                    // $('#onlycolors').show();
                } else {
                    changeVarient(l);
                    // $('#colorrss').hide();
                    // $('#unittss').hide();
                    // $('#sizess').show();
                    // $('#onlycolors').hide();
                }
            });
            $('#shipshow').on('click', function () {
                $('#shipping-div').show();
                $('#shiphide').show();
                $('#shipshow').hide();
            });
            $('#shiphide').on('click', function () {
                $('#shipping-div').hide();
                $('#shiphide').hide();
                $('#shipshow').show();
            });
            $('#attrishow').on('click', function () {
                $('#attri-div').show();
                $('#attrihide').show();
                $('#attrishow').hide();
            });
            $('#attrihide').on('click', function () {
                $('#attri-div').hide();
                $('#attrihide').hide();
                $('#attrishow').show();
            });
        });
    </script>

    <script>
        function checkBox(p) {
            if ($('#checkBoxStatus' + p).is(":checked")) {
                $('#checkBoxWrite' + p).attr("readonly", false);
                $('#checkBoxWrite' + p).attr("required", true);
            } else {
                $('#checkBoxWrite' + p).val("");
                $('#checkBoxWrite' + p).attr("readonly", true);
                $('#checkBoxWrite' + p).attr("required", false);
            }

        }

        $(document).ready(function () {
            $('input[name="input"]').tagsinput({
                trimValue: true,
                confirmKeys: [13, 44, 32],
                focusClass: 'my-focus-class'
            }).attr('min', 0);

            $('.bootstrap-tagsinput input').on('focus', function () {
                $(this).closest('.bootstrap-tagsinput').addClass('has-focus');
            }).on('blur', function () {
                $(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
            });
        });
    </script>

    @if (ModulusStatus($store_id, 114))
        <script>
            // Color and size variant row add
            function addRow(i) {
                i++;

                var colors = {!! json_encode($colors, JSON_HEX_TAG) !!};
                // console.log(colors);
                color = [];
                colors.forEach(function (data) {
                    color += ` <option value="` + data.code + `">` + data.name + `</option>`
                });
                console.log(color);
                var sizes = {!! json_encode($size, JSON_HEX_TAG) !!};
                size = [];
                // index = document.getElementById('index').value;

                // i = document.getElementById('index').value = index + 1;
                var j = 0;
                var p = 8;
                var o = 1;
                sizes.forEach(function (data) {
                    // console.log(data.name);
                    o++
                    p = o + i + j + p + 2;
                    size += ` <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="row-md-6">
                                        <label>size</label>
                                    </div>
                                    <div class="row-md-6">
                                        <div
                                            style="display: flex !important; gap: 10px !important;">
                                            <input type="checkbox" onclick="checkBox(` + p + `)" id="checkBoxStatus` + p + `"  name="sid[` + i + `][]" value="yes">
                                            <input type="text" class="form-control" name="cs_size[` + i + `][]" value="` + data.name + `" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Quantity</label>
                                <input type="number" class="form-control colorSizeQty" onchange="variantQtyCheck(this, 'color')" id="checkBoxWrite` + p + `" readonly name="cs_qty[` + i + `][]" placeholder="Enter Quantity" value="">
                            </div>
                            <div class="col-md-3">
                                <label>(+/-)Price</label>
                                 <input type="number" class="form-control" name="cs_price[` + i + `][]" placeholder="Enter Price" value="0">
                            </div>
                            <div class="col-md-3">
                                <label>Media</label>
                                <input type="file" class="form-control" onchange="variantImage(event)" accept="image/*" name="cs_Image[` + i + `][]" />
                            </div>
                        </div>`;
                    j++;

                });
                // i++;
                // console.log(size);

                var col = `<tr id="new" style="margin-top:5px;">
                                                <td>
                                                <label>Color:</label>
                                                    <select name="cs_color[]" id="color" class="form-control" step="any">
                                                        <option> Select Color</option>
                                                        ` + color + `
                                                    </select>
                                                    <input type="file"
                                                       class="form-control mt-2"
                                                       onchange="variantImage(event)"
                                                       accept="image/*"
                                                       name="cs_color_image[]"
                                                    />
                                                </td>
                                                <td>
                                                  ` + size + `
                                                </td>
                                                <td>
                                                    <a class="remove-officer-button mt-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><img src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px" style="margin-bottom:5px;"></a>
                                                    <br>
                                                    <a  onclick="addRow(` + i + `)" data-bs-toggle="tooltip" data-bs-placement="top" title="Add"><img src="{{ URL::to('/') }}/img/add.png" alt="" width="30px"></a>
                                                </td>
                                            </tr>`
                $("#officers-table tbody").append(col);

            }

            // Add unit variant row
            function addUnit() {
                var col = $('#new1').html();
                $("#officers-table1 tbody").append('<tr>' + col + '</tr>');
            }

            // Add size variant row
            function addSize() {
                var col = $('#new2').html();
                $("#officers-table2 tbody").append('<tr>' + col + '</tr>');
            }

            // Add only color variant row
            function addOnlycolor() {
                var col = $('#new3').html();
                $("#officers-table3 tbody").append('<tr>' + col + '</tr>');
            }

            // Remove color and size row
            $("#officers-table").on('click', '.remove-officer-button', function (e) {
                var whichtr = $(this).closest("tr");

                // alert('worked'); // Alert does not work
                whichtr.remove();
            });

            // Remove only color row
            $("#officers-table3").on('click', '.remove-officer-button3', function (e) {
                var whichtr = $(this).closest("tr");

                // alert('worked'); // Alert does not work
                whichtr.remove();
            });

            // Remove unit row
            $("#officers-table1").on('click', '.remove-officer-button1', function (e) {
                var whichtr = $(this).closest("tr");

                // alert('worked'); // Alert does not work
                whichtr.remove();
            });


            // Variant quantity check with product total quantity
            const variantQtyCheck = (e, variantType) => {
                const productQty = document.getElementById('productQty').value;
                const variant_qty = e.value;

                let totalVariantQty = 0;

                switch (variantType) {
                    case 'color':
                        totalVariantQty = getVariantTotalQty("colorSizeQty");
                        break;
                    case 'onlycolor':
                        totalVariantQty = getVariantTotalQty("onlyColorQty");
                        break;
                    case 'unit':
                        totalVariantQty = getVariantTotalQty("unitQty");
                        break;
                    case 'size':
                        totalVariantQty = getVariantTotalQty("sizeQty");
                        break;
                    default:
                        totalVariantQty = 0;
                }

                document.getElementById('productQty').value = totalVariantQty;
            }

            // Get total input quantity of color and size variant
            function getVariantTotalQty(className) {
                // Select elements by class name
                var inputs = document.getElementsByClassName(className);

                let totalQty = 0;

                // Iterate through the elements
                for (var i = 0; i < inputs.length; i++) {
                    // Access input values
                    var value = inputs[i].value;

                    if (value.trim() !== '') {
                        totalQty = (parseFloat(totalQty) + parseFloat(value));
                    }
                }

                return totalQty;
            }

            // Variant image file handler
            function variantImage(e) {
                if (!e.target.files[0]) return;

                //To obtaine a File reference
                var file = e.target.files[0];

                const reader = new FileReader();

                // Closure to capture the file information and apply validation.
                reader.onload = (function (readerEvt) {
                    return function (e) {
                        //Apply the validation rules for attachments upload
                        ApplyFileValidationRules(readerEvt);
                    };
                })(file);

                reader.readAsDataURL(file);
            }

        </script>
    @endif

    <script>
        // Variant delete on change variant
        function changeVarient(l) {
            swal.fire({
                title: 'আপনি ভেরিয়েন্ট পরিবর্তন করতে চাচ্ছেন?',
                text: "আপনি ভ্যারিয়েন্ট পরিবর্তন করলে, যদি আগে কোনো ভেরিয়েন্ট অ্যাড করে থাকেন সেটি ডিলিট হয়ে যাবে।",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    if (l == 'none') {
                        $('#colorrss').hide();
                        $('#unittss').hide();
                        $('#sizess').hide();
                        $('#onlycolors').hide();

                        $("#productQtyDiv").show().css("display", "block");
                        $("#productVolumeDiv").hide().css("display", "none");
                        $("#productUnitDiv").hide().css("display", "none");
                        $("#qtyOrVolume").val("0");
                    } else if (l == 'color') {
                        $('#colorrss').show();
                        $('#unittss').hide();
                        $('#sizess').hide();
                        $('#onlycolors').hide();

                        $("#productQtyDiv").show().css("display", "block");
                        $("#productVolumeDiv").hide().css("display", "none");
                        $("#productUnitDiv").hide().css("display", "none");
                        $("#qtyOrVolume").val("0");
                    } else if (l == 'unit') {
                        $('#colorrss').hide();
                        $('#unittss').show();
                        $('#sizess').hide();
                        $('#onlycolors').hide();

                        $("#productQtyDiv").hide();
                        $("#productVolumeDiv").show();
                        $("#productUnitDiv").show();
                        $("#qtyOrVolume").val("1");
                    } else if (l == 'onlycolor') {
                        $('#colorrss').hide();
                        $('#unittss').hide();
                        $('#sizess').hide();
                        $('#onlycolors').show();

                        $("#productQtyDiv").show().css("display", "block");
                        $("#productVolumeDiv").hide().css("display", "none");
                        $("#productUnitDiv").hide().css("display", "none");
                        $("#qtyOrVolume").val("0");
                    } else {
                        $('#colorrss').hide();
                        $('#unittss').hide();
                        $('#sizess').show();
                        $('#onlycolors').hide();

                        $("#productQtyDiv").show().css("display", "block");
                        $("#productVolumeDiv").hide().css("display", "none");
                        $("#productUnitDiv").hide().css("display", "none");
                        $("#qtyOrVolume").val("0");
                    }

                    $url = "{{ route('admin.variantDelete', $product['id']) }}";
                    // alert($url);
                    $.get($url, {
                        product_id: {{ $product['id'] }}
                    }, function (data) {
                        $('.colorrss_ok').html('');
                        if (Object.keys(data).length !== 0) {
                            location.reload();
                        }
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        'Cancelled',
                        'Deletion Cancel',
                        'error'
                    );
                }
            })

        };

    </script>
@endpush
