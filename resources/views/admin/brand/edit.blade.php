@extends('admin.layouts.main')

@push('styles')
    <style>
        .fade:not(.show) {
            opacity: 1 !important;
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

        .img-wrap:hover .close {
            opacity: 1;
            background-color: #ff0000;
        }

        .FileNameCaptionStyle {
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('admin.admin_top_bar_category.index')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            এডিট ব্র্যান্ড
                        @else
                            Edit Brand
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active"><a href="{{ URL::to('/') }}/brand">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    তালিকায় ফিরে যান
                                @else
                                    Back To List
                                @endif
                            </a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <form action="{{ URL::to('/') }}/brand/{{ $brand->id }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            নাম
                                        @else
                                            Name
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="staticEmail" name="name"
                                               value="{{ $brand->name }}">
                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="image" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ছবি
                                        @else
                                            Image
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <div id="previewContainer">
                                            @if(!empty($brand->image))
                                                <div class="image-preview"
                                                     style="position: relative; display: inline-block;">
                                                    <img
                                                        src="{{ getPath($brand->image, "assets/images/brand") }}"
                                                        style="height: 100px; border: 1px solid rgb(204, 204, 204); padding: 3px; margin-right: 10px;">
                                                    <a href="{{ route('admin.removeBrandImage', ['id' => $brand->id]) }}"
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
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-2 col-form-label"></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-info">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আপডেট
                                            @else
                                                Update
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.20.1/full-all/ckeditor.js"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('admin/dist/js/custom-ckeditor.js') }}"></script>

    <script>
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
