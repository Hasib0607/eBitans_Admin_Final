@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            @if (Auth::user()->type == 'superadmin')
                                <li class="breadcrumb-item">
                                    <a href="{{route('staff.workAssign')}}">
                                        <img src="{{URL::to('/')}}/img/cubes.png"> <br> Work Assign
                                    </a>
                                </li>
                            @endif
                            <li class="breadcrumb-item">
                                <a href="{{route('staff.webSetUp')}}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br>Website Setup
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{route('staff.view.setup.data', ['id' => $product->store_id ?? ""])}}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br>Setup Info
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>Website Setup</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
                        <!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>-->
                    </ul>
                </div>
            </div>
            <div class="row mt-3 productlist">
                <div class="col-12 mb-5">
                    <div class="card">
                        <div class="card-header" style="padding: 15px 15px 5px;">
                            <h6>Product Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <form class="col-9 col-md-9"
                                      action="{{ route("staff.websitesetup.update.product") }}"
                                      id="detailsForm" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="id" value="{{ $product->id ?? "" }}">
                                    <input type="hidden" name="store_id" value="{{ $product->store_id ?? "" }}">

                                    <div class="row form-row align-items-center">
                                        <!-- Product Name -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="facebook_link">Product Title <span class="text-danger">*</span></label>
                                            <input
                                                type="text"
                                                id="product_name"
                                                name="product_name"
                                                placeholder="Product Name"
                                                class="form-control"
                                                value="{{ $product->product_name }}"
                                            />

                                            @error('product_name')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <!-- Product SKU -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="model_no">Product SKU</label>
                                            <input
                                                type="text"
                                                id="model_no"
                                                name="model_no"
                                                placeholder="Product SKU"
                                                class="form-control"
                                                value="{{ $product->model_no }}"
                                            />

                                            @error('model_no')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <!-- Product category -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="category">Category <span class="text-danger">*</span> (<small
                                                    class="text-warning">Separate with
                                                    comma(,) for
                                                    multiple</small>)</label>
                                            <input
                                                type="text"
                                                id="category"
                                                name="category"
                                                placeholder="Category"
                                                class="form-control"
                                                value="{{ $product->category }}"
                                            />

                                            @error('category')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="sub_category">Sub Category (<small class="text-warning">Separate
                                                    with comma(,) for
                                                    multiple</small>)</label>
                                            <input
                                                type="text"
                                                id="sub_category"
                                                name="sub_category"
                                                placeholder="Sub Category"
                                                class="form-control"
                                                value="{{ $product->sub_category }}"
                                            />

                                            @error('sub_category')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>


                                        <!-- Product price -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="price">Price <span class="text-danger">*</span></label>
                                            <input
                                                type="text"
                                                id="price"
                                                name="price"
                                                placeholder="Price"
                                                class="form-control"
                                                value="{{ $product->price }}"
                                            />

                                            @error('price')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>


                                        <!-- Product brand -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="brand">Brand</label>
                                            <input
                                                type="text"
                                                id="brand"
                                                name="brand"
                                                placeholder="Brand"
                                                class="form-control"
                                                value="{{ $product->brand }}"
                                            />

                                            @error('brand')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>


                                        <!-- Product supplier -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="supplier">Supplier</label>
                                            <input
                                                type="text"
                                                id="supplier"
                                                name="supplier"
                                                placeholder="Supplier"
                                                class="form-control"
                                                value="{{ $product->supplier }}"
                                            />

                                            @error('supplier')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>


                                        <!-- Product cost -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="cost">Cost</label>
                                            <input
                                                type="text"
                                                id="cost"
                                                name="cost"
                                                placeholder="Cost"
                                                class="form-control"
                                                value="{{ $product->cost }}"
                                            />

                                            @error('cost')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>


                                        <!-- Product quantity -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                            <input
                                                type="text"
                                                id="quantity"
                                                name="quantity"
                                                placeholder="Quantity"
                                                class="form-control"
                                                value="{{ $product->quantity }}"
                                            />

                                            @error('quantity')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <!-- Product discount_type -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="discount_type">Discount Type</label>

                                            <select id="discount_type" name="discount_type" class="form-control">
                                                <option
                                                    value="no_discount" {{ $product->discount_type == "no_discount" ? "selected" : "" }}>
                                                    No Discount
                                                </option>
                                                <option
                                                    value="fixed" {{ $product->discount_type == "fixed" ? "selected" : "" }}>
                                                    Fixed
                                                </option>
                                                <option
                                                    value="percent" {{ $product->discount_type == "percent" ? "selected" : "" }}>
                                                    Percent
                                                </option>
                                            </select>

                                            @error('discount_type')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <!-- Product discount -->
                                        <div class="form-group col-12 col-md-6 col-lg-4 mt-3">
                                            <label for="discount">Discount</label>
                                            <input
                                                type="text"
                                                id="discount"
                                                name="discount"
                                                placeholder="Discount"
                                                class="form-control"
                                                value="{{ $product->discount }}"
                                            />

                                            @error('discount')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <!-- Product Description -->
                                        <div class="form-group col-md-12 col-lg-12 mt-3">
                                            <label for="description">Product Description <span
                                                    class="text-danger">*</span></label>
                                            <textarea
                                                id="description"
                                                name="description"
                                                placeholder="Product Description"
                                                class="form-control editor"
                                                rows="10"
                                            >{{ $product->description }}</textarea>

                                            @error('description')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex mt-3">
                                        <button class="btn btn-primary mb-0" type="submit">Update</button>
                                    </div>
                                </form>

                                <div class="col-3 col-md-3">
                                    <div class="form-group col-md-12 col-lg-12 mt-3">
                                        <label for="other_info">Other Info</label>
                                        <textarea
                                            disabled
                                            id="other_info"
                                            name="other_info"
                                            placeholder="Other Info"
                                            class="form-control"
                                            rows="10"
                                        >{{ $product->other_info }}</textarea>
                                    </div>
                                    <div class="form-group col-md-12 col-lg-12 mt-3">
                                        <label for="other_info">Product Image</label>
                                        <div style="display: flex;justify-content: center;">
                                            @if(isset($images) && count($images))
                                                @foreach($images as $item)
                                                    <div style="display: flex;padding: 5px 10px;position: relative;">
                                                        <a href="{{ asset('assets/images/setup') }}/{{ $item->image ?? "" }}"
                                                           download="{{ $item->image ?? "" }}"
                                                           style="
                                                           position: absolute;
                                                           top: 4px;
                                                           left: 9px;
                                                           background: #00000057;
                                                           width: 86%;
                                                           height: 88%;
                                                           display: flex;
                                                           justify-content: center;
                                                           align-items: center;
                                                           color: #ff5733;
                                                           "><i class="fa fa-download" style="font-size: 30px;"
                                                                aria-hidden="true"></i></a>
                                                        <img
                                                            src="{{ asset('assets/images/setup') }}/{{ $item->image ?? "" }}"
                                                            alt=""
                                                            style="padding:10px;border:1px solid black;margin-bottom:5px;"
                                                            width="105px" height="95px">
                                                    </div>
                                                @endforeach
                                            @else
                                                {{ "Image not available" }}
                                            @endif
                                        </div>
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
    <!-- Include the Quill library -->
    <script src="{{ asset('admin/dist/js/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput-angular.js') }}"></script>
    <script src="{{ asset('admin/src/select2/select2.min.js') }}"></script>

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
                        '@cupcake', '@danish', '@donut', '@dragÃ©e', '@fruitcake', '@gingerbread',
                        '@gummi', '@ice', '@jelly-o',
                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding',
                        '@sesame', '@snaps', '@soufflÃ©',
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
@endpush
