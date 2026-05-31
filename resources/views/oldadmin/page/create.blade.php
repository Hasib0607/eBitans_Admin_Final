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
    <?php
    if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
        $customer = DB::table('customers')->where('uid', Auth::user()->id)->first();
        $store_id = $customer->active_store;
    } elseif (Auth::user()->type == 'staff') {
        $staff = DB::table('staff')->where('uid', Auth::user()->id)->first();
        $store_id = $staff->store_id;
        $role = DB::table("roles")->where('id', $staff->role_id)->first();
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
    $store = DB::table('stores')->where('id', $store_id)->first();
    if ($store->expiry_date <= Carbon\Carbon::now()) {
        $exp = 1;
    } else {
        $exp = 0;
    }
    ?>
    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row new">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            @if(isset($template) && $template=='1' || Auth::user()->type=='admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.design.theme')}}">
                                        <img src="{{URL::to('/')}}/img/icons/web-design.png"> <br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ওয়েবসাইট থিম
                                            @else
                                                Website Themes
                                            @endif</span>
                                    </a>
                                </li>

                                @if(isset($homepage) && $homepage=='1' || Auth::user()->type=='admin')
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a href="{{route('admin.design.homepage.slider')}}">
                                            <img src="{{URL::to('/')}}/img/icons/landing-page.png"> <br><span
                                                class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    হোম পেজ ডিজাইন
                                                @else
                                                    HP Layout Design
                                                @endif</span>
                                        </a>
                                    </li>
                                @endif

                                @if(isset($header) && $header=='1' || Auth::user()->type=='admin')
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a href="{{route('admin.design.design')}}">
                                            <img src="{{URL::to('/')}}/img/icons/title.png"><br><span
                                                class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    হেডার ডিজাইন
                                                @else
                                                    Header Design
                                                @endif</span>

                                        </a>
                                    </li>
                                @endif

                                <li class="breadcrumb-item">
                                    <a href="{{route('admin.design.slider')}}">
                                        <img src="{{URL::to('/')}}/img/icons/slider.png"> <br> <span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                স্লাইডার
                                            @else
                                                Slider
                                            @endif</span>
                                    </a>
                                </li>
                            @endif

                            @if(isset($banner) && $banner=='1' || Auth::user()->type=='admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.design.banner')}}">
                                        <img src="{{URL::to('/')}}/img/icons/ads.png"> <br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                বিজ্ঞাপন ব্যানার
                                            @else
                                                Ads Banner
                                            @endif</span>
                                    </a>
                                </li>
                            @endif
                            <!--@if(isset($layout) && $layout=='1' || Auth::user()->type=='admin')-->
                            <!--<li class="breadcrumb-item" aria-current="page">-->
                            <!--    <a href="{{route('admin.design.layout.homepage')}}">-->
                            <!--        <img src="{{URL::to('/')}}/img/icons/subcategory.png" > <br>Invoice-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--@endif-->


                            @if(isset($tt) && $tt=='1' || Auth::user()->type=='admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.testimonials')}}">
                                        <img src="{{URL::to('/')}}/img/icons/testimonial.png"> <br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                প্রশংসাপত্র
                                            @else
                                                Testimonials
                                            @endif</span>
                                    </a>
                                </li>
                            @endif

                            @if(isset($pages) && $pages=='1' || Auth::user()->type=='admin')
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="{{route('admin.pages')}}">
                                        <img src="{{URL::to('/')}}/img/icons/team.png"> <br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                অন্যান্য পেইজ
                                            @else
                                                Other Pages
                                            @endif</span>
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->type=='admin')
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{route('admin.design.homepage.invoice')}}">
                                        <img src="{{URL::to('/')}}/img/icons/bill-2.png"> <br><span
                                            class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                চালান টেমপ্লেট
                                            @else
                                                Invoice Template
                                            @endif</span>
                                    </a>
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="container content-main">
            <div class="row">
                <form action="{{route('admin.savepage')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            নতুন পেইজ যোগ করুন
                                        @else
                                            Add New Page
                                        @endif</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn')
                                            মৌলিক
                                        @else
                                            Basic
                                        @endif</h4>
                                </div>
                                <div class="card-body">

                                    <div class="row mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                পেজের টাইটেল
                                            @else
                                                Page title
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" placeholder="Type here" class="form-control" id="name"
                                                   name="name" value="{{old('name')}}">
                                            @error('name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--<div id="container">-->
                                    <!--    <div id="editor" mytextarea>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="mb-4">
                                        <label
                                            class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                বিস্তারিত
                                            @else
                                                Details
                                            @endif</label>
                                        <textarea placeholder="Type here" class="form-control" id="editor" rows="8"
                                                  name="details">{{old('details')}}</textarea>

                                        @error('details')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-4">
                                                <label
                                                    class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                        লিঙ্ক
                                                    @else
                                                        Link
                                                    @endif <span class="req">*</span></label>
                                                <div class="row gx-2">
                                                    <select name="link" class="form-control">
                                                        <option value="none">None</option>
                                                        <option value="about">About</option>
                                                        <option value="help">Help</option>
                                                        <option value="terms_and_condition">Terms and Conditions
                                                        </option>
                                                        <option value="privacy_policy">Privacy Policy</option>
                                                        <option value="return_policy">Return Policy</option>
                                                    </select>
                                                    @error('link')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <label for="staticEmail"
                                                       class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                        স্টেটাস
                                                    @else
                                                        Status
                                                    @endif </label>
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch is-filled"
                                                         style="text-align:center;padding-top:14px;">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="flexSwitchCheckChecked" name="status"
                                                               style="margin:0 auto;" checked="">
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                    @error('status')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit"
                                            class="btn btn-info mt-4 ml-3">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            প্রকাশ
                                        @else
                                            Publish
                                        @endif </button>

                                </div>
                            </div> <!-- card end// -->

                        </div>
                    </div>

                </form>
            </div>
        </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/super-build/ckeditor.js"></script>
    <!--
        Uncomment to load the Spanish translation
        <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/super-build/translations/es.js"></script>
    -->
    <script>
        // This sample still does not showcase all CKEditor 5 features (!)
        // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
        CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
            // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
            toolbar: {
                items: [
                    'exportPDF', 'exportWord', '|',
                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
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
                options: [
                    {model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
                    {model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'},
                    {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'},
                    {model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'},
                    {model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'},
                    {model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5'},
                    {model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6'}
                ]
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
            placeholder: 'Welcome to CKEditor 5!',
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
                allow: [
                    {
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }
                ]
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
                feeds: [
                    {
                        marker: '@',
                        feed: [
                            '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                            '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                            '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                            '@sugar', '@sweet', '@topping', '@wafer'
                        ],
                        minimumCharacters: 1
                    }
                ]
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

        jQuery('select[name="category"]').on('change', function () {
            debugger;
            var val = $(this).val();
            console.log(val);
            $('#subcategory').empty();
            var catid = $('select[name="category"]').val();
            $.get('/getsubcat', {catid: catid}, function (data) {
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('#subcategory').append(
                        '<option value="">select</option><option value="' + data[i].id + '">' + data[i].name + '</option>'
                    );
                }
            });
        });
    </script>
@endpush
