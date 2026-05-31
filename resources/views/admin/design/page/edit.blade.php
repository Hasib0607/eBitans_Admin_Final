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
    </style>
@endpush
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">

        {{--design main top nav--}}
        @include('admin.design.share.designs-nav', ['page' => true])

        <section class="container content-main">
            <div class="row">
                <form action="{{ route('admin.updatepage', $singleData->id) }}" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            এডিট পেইজ
                                        @else
                                            Edit Page
                                        @endif
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>Basic</h4>
                                </div>
                                <div class="card-body">

                                    <div class="row mb-4">
                                        <label for="product_name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পেজের টাইটেল
                                            @else
                                                Page title
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <div class="col-md-8">
                                            <input type="text" placeholder="Type here" class="form-control"
                                                   id="name" value="{{ $singleData->name }}" name="name">
                                            @error('name')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ফিচার ছবি
                                            @else
                                                Feature Image
                                            @endif
                                        </label>
                                        <div id="previewContainer">
                                            @if(!empty($singleData->feature_image))
                                                <div class="image-preview"
                                                     style="position: relative; display: inline-block;">
                                                    <img
                                                        src="{{ getPath($singleData->feature_image, "assets/images/page") }}"
                                                        style="height: 100px; border: 1px solid rgb(204, 204, 204); padding: 3px; margin-right: 10px;">
                                                    <a href="{{ route('admin.removePageFeatureImage', ['id' => $singleData->id]) }}"
                                                       onclick="deleteImage(event, this)"
                                                       class="imageUploadRemoveBtn">×</a>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="hidden" class="form-control" id="feature_image"
                                               name="feature_image">

                                        <button type="button" class="btn btn-outline-secondary browse-btn mt-2"
                                                onclick="standalonFileManagerModal('feature_image', true, 'previewContainer');">
                                            <i class="fa fa-picture-o"></i> Browse
                                        </button>
                                        @error('feature_image')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                বিস্তারিত
                                            @else
                                                Details
                                            @endif
                                        </label>
                                        <textarea placeholder="Type here" class="form-control" id="editor" rows="8"
                                                  name="details">{!! Request::old('details', $singleData->details) !!}</textarea>
                                        @error('details')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-4">
                                                <label class="form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        লিঙ্ক
                                                    @else
                                                        Link
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="row gx-2">
                                                    <select name="link" class="form-control">
                                                        <option value="none"
                                                                @if ($singleData->link == 'none') selected @endif>None
                                                        </option>
                                                        <option value="about"
                                                                @if ($singleData->link == 'about') selected @endif>About
                                                        </option>
                                                        <option value="contact"
                                                                @if ($singleData->link == 'contact') selected @endif>
                                                            Contact
                                                        </option>
                                                        <option value="help"
                                                                @if ($singleData->link == 'help') selected @endif>Help
                                                        </option>
                                                        <option value="terms_and_condition"
                                                                @if ($singleData->link == 'terms_and_condition') selected @endif>
                                                            Terms and
                                                            Conditions
                                                        </option>
                                                        <option value="privacy_policy"
                                                                @if ($singleData->link == 'privacy_policy') selected @endif>
                                                            Privacy
                                                            Policy
                                                        </option>
                                                        <option value="return_policy"
                                                                @if ($singleData->link == 'return_policy') selected @endif>
                                                            Return Policy
                                                        </option>
                                                    </select>
                                                    @error('link')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-lg-8">
                                            <div class="row">
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
                                                               style="margin:0 auto;"
                                                               @if ($singleData->status == 'active') checked="" @endif>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                    @error('status')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info mt-4 ml-3">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপডেট
                                        @else
                                            Update
                                        @endif
                                    </button>

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
    <script src="https://cdn.ckeditor.com/4.20.1/full-all/ckeditor.js"></script>
    <script src="{{ asset('admin/dist/js/ckeditor-config.js') }}"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>

    <script>
        CKEDITOR.replace('editor', window.CKEditorOption);
        // After initialization, insert content
        CKEDITOR.instances['editor'].on('instanceReady', function () {
            const description = {!! json_encode(isset($singleData->details) ? $singleData->details : old('details')) !!};
            console.log("Setting CKEditor description:", description);

            CKEDITOR.instances['editor'].setData(description);
        });
    </script>
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

        jQuery('select[name="category"]').on('change', function () {
            debugger;
            var val = $(this).val();
            console.log(val);
            $('#subcategory').empty();
            var catid = $('select[name="category"]').val();
            $.get('/getsubcat', {
                catid: catid
            }, function (data) {
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('#subcategory').append(
                        '<option value="">select</option><option value="' + data[i].id + '">' +
                        data[i].name + '</option>'
                    );
                }
            });
        });


    </script>
@endpush
