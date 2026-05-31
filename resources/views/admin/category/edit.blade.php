@extends('admin.layouts.main')
@push('styles')
    <style>
        .fade:not(.show) {
            opacity: 1 !important;
        }

        img.thumb {
            width: 80px !important;
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
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('admin.admin_top_bar_category.index')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            এডিট ক্যাটাগরি
                        @else
                            Edit Category
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active"><a href="{{ URL::to('/') }}/category">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    তালিকায় ফিরে যান
                                @else
                                    Back to List
                                @endif
                            </a></li>
                        <!--<li><a href="">Import</a></li>-->
                        <!--<li><a href="">Export</a></li>-->
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <form action="{{ URL::to('/') }}/category/{{ $category->id }}" method="post"
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
                                               value="{{ $category->name }}">
                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="icon" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আইকন
                                        @else
                                            Icon
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <img src="{{ URL::to('/') }}/assets/images/icon/{{ $category->icon }}"
                                             width="50px" style="margin-bottom:10px;">
                                        <br>
                                        <select id='iconpack' name="icon" class="form-control">
                                            <option value="null">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    আইকন নির্বাচন করুন
                                                @else
                                                    Select Icon
                                                @endif
                                            </option>
                                            @php
                                                $icons = DB::table('iconpacks')->get();
                                            @endphp
                                            @if (isset($icons) && count($icons) > 0)
                                                @foreach ($icons as $icon)
                                                    <option value='{{ $icon->image }}'>{{ $icon->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('icon')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="banner" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ব্যানার
                                        @else
                                            Banner
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <div id="previewContainer">
                                            @if(!empty($category->banner))
                                                <div class="image-preview"
                                                     style="position: relative; display: inline-block;">
                                                    <img
                                                        src="{{ getPath($category->banner, "assets/images/category") }}"
                                                        style="height: 100px; border: 1px solid rgb(204, 204, 204); padding: 3px; margin-right: 10px;">
                                                    <a href="{{ route('admin.removeCategoryImage', ['id' => $category->id]) }}"
                                                       onclick="deleteImage(event, this)"
                                                       class="imageUploadRemoveBtn">×</a>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="hidden" class="form-control" id="banner" name="banner">

                                        <button type="button" class="btn btn-outline-secondary browse-btn mt-2"
                                                onclick="standalonFileManagerModal('banner', true, 'previewContainer');">
                                            <i class="fa fa-picture-o"></i> Browse
                                        </button>
                                        @error('banner')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            স্ট্যাটাস
                                        @else
                                            Status
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch is-filled" style="text-align:center;">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                                   name="status" style="margin:0 auto;"
                                                   @if ($category->status == 'active') checked="" @endif>
                                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                        </div>
                                        @error('status')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-2 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            অবস্থান
                                        @else
                                            Position
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" id="position" name="position"
                                               value="{{ $category->position }}">
                                        @error('position')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-2 col-form-label"></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-info">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                হালনাগাদ
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

        $('.icon').iconpicker();
        $('.action-destroy').on('click', function () {
            $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
        });
    </script>
@endpush
