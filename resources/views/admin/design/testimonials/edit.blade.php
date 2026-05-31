@extends('admin.layouts.main')

{{--styles--}}
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
    </style>
@endpush
@section('content')
    @php
        $userData = getUserData();
        $store_id = $userData['store_id'];
        $imageSize = 200 ;
        $module_id = 107;
        $sizeMsg = "200KB";
        $moduleStatus = ModulusStatus($store_id,$module_id);
        if($moduleStatus){
            $imageSize = 5120 ;
            $sizeMsg = "5MB";
        }
    @endphp

    <main class="main-content position-relative h-100 border-radius-lg">

        {{--design main top nav--}}
        @include('admin.design.share.designs-nav', ['testimonial' => true])

        <section class="container content-main">
            {{--edit form--}}
            <form action="{{ route('admin.testimonials.update', $data->id) }}" method="post"
                  enctype="multipart/form-data">
                <div class="row">
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
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            এডিট প্রশংসাপত্র
                                        @else
                                            Edit Testimonials
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

                                        <div id="previewContainer">
                                            @if(!empty($data->image))
                                                <div class="image-preview"
                                                     style="position: relative; display: inline-block;">
                                                    <img
                                                        src="{{ getPath($data->image, "assets/images/testimonials") }}"
                                                        style="height: 100px; border: 1px solid rgb(204, 204, 204); padding: 3px; margin-right: 10px;">
                                                    <a href="{{ route('admin.removeTestimonialImage', ['id' => $data->id]) }}"
                                                       onclick="deleteImage(event, this)"
                                                       class="imageUploadRemoveBtn">×</a>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="hidden" class="form-control" id="image" name="image">

                                        <button type="button" class="btn btn-outline-secondary browse-btn mt-2"
                                                onclick="standalonFileManagerModal('image', true, 'previewContainer');">
                                            <i class="fa fa-picture-o"></i> Browse
                                        </button>
                                        @error('image')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Name
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <input type="text" placeholder="Type here" value="{{ $data->name }}"
                                               class="form-control" id="title" name="name">
                                        @error('name')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পেশা
                                            @else
                                                Occupation
                                            @endif
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="subtitle" name="occupation" value="{{ $data->occupation }}">
                                        @error('occupation')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                প্রতিক্রিয়া
                                            @else
                                                Feedback
                                            @endif
                                        </label>
                                        <!--<input type="text" placeholder="Type here" class="form-control" id="link" name="feedback" value="{{ $data->feedback }}">-->
                                        <textarea class="form-control" name="feedback" id="feedback"
                                                  rows="7">{{ $data->feedback }}</textarea>
                                        @error('feedback')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অবস্থান
                                            @else
                                                Position
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <input type="number" placeholder="Type here" class="form-control"
                                               id="position" name="position" value="{{ $data->position }}">
                                        @error('position')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
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
                                                <input class="form-check-input" type="checkbox"
                                                       id="flexSwitchCheckChecked" name="status"
                                                       @if ($data->status == 'active') checked="" @endif
                                                       style="margin:0 auto;">
                                                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                            </div>
                                            @error('status')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label"></label>
                                        <button class="btn btn-info rounded font-sm hover-up" type="submit">
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
                </div>
            </form>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.20.1/full-all/ckeditor.js"></script>
    <script src="{{ asset('admin/dist/js/ckeditor-config.js') }}"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('admin/dist/js/custom-ckeditor.js') }}"></script>

    <script>
        const testimonialFeedbackEditorOption = Object.assign({}, window.CKEditorOption, {
            versionCheck: false,
            on: {}
        });

        CKEDITOR.replace('feedback', testimonialFeedbackEditorOption);

        const deleteImage = (event, el) => {
            event.preventDefault();

            const url = el.getAttribute("href");

            if (!url) {
                console.error("No URL found.");
                return;
            }

            const imageWrapper = el.closest('.image-preview');
            if (!imageWrapper) return;

            // Show confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true,
            }).then((result) => {
                if (result.value) {
                    // Hide temporarily
                    imageWrapper.style.display = 'none';

                    axios.delete(url)
                        .then(response => {
                            // If successful, remove permanently
                            imageWrapper.remove();
                        })
                        .catch(error => {
                            // If failed, restore display
                            imageWrapper.style.display = 'inline-block';
                            Swal.fire('Error!', 'Image could not be deleted.', 'error');
                        });
                }
            });
        };

    </script>
@endpush
