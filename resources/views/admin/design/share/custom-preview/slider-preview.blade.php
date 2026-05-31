<style>
    .table thead th {
        padding: 8px;
    }

    .form-control-sm {
        padding: 0.25rem 0.75rem !important;
    }

    div#sliderAddModal, div.editSliderModal {
        z-index: 999 !important;
    }

    .modal-backdrop.fade.show {
        z-index: 99 !important;
    }
</style>

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

{{--@dd($store_design)--}}

{{--slider preview create modal --}}
<div class="modal fade" id="sliderAddModal" tabindex="-1"
     aria-labelledby="sliderAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content px-2">
            <form action="{{route('admin.slider.save')}}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4>
                        @if(Session::has('lang') && Session::get('lang')=='bn')
                            নতুন স্লাইডার যোগ করুন
                        @else
                            Add New Slider
                        @endif
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="content-main">
                        <input type="hidden" name="index" value="1" id="index">
                        <div class="row">
                            <div class="mb-3">
                                <label for="product_name"
                                       class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        ছবি
                                    @else
                                        Image
                                    @endif
                                </label>
                                <br>
                                <div id="previewContainer">
                                    <div class="image-preview"
                                         style="position: relative; display: inline-block;">
                                        <img
                                            src="{{ URL::to('/') }}/img/upload.svg"
                                            style="height: 100px; border: 1px solid rgb(204, 204, 204); padding: 3px; margin-right: 10px;">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="image" name="image">

                                <button type="button" class="btn btn-outline-secondary browse-btn mt-2"
                                        onclick="standalonFileManagerModal('image', true, 'previewContainer');">
                                    <i class="fa fa-picture-o"></i> Browse
                                </button>
                                <p class="text-secondary" id="image_description"></p>
                                @error('image')
                                <p class="text-danger" role="alert">{{$message}}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 titleInput row">
                            <div class="col-md-8">
                                <label for="product_name"
                                       class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        শিরোনাম
                                    @else
                                        Title
                                    @endif</label>
                                <input
                                    id="title"
                                    type="text"
                                    placeholder="Type here"
                                    class="form-control "
                                    name="title"
                                >
                                @error('title')
                                <p class="text-danger" role="alert">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    @if(Session::has('lang') && Session::get('lang')=='bn')
                                        শিরোনামের রঙ
                                    @else
                                        Title Color
                                    @endif
                                </label>
                                <input
                                    type="color"
                                    placeholder="Type here"
                                    class="form-control "
                                    id="title_color"
                                    name="color"
                                    style="width:100%; height:42px"
                                    value="{{$store_design->title_color ?? ''}}"
                                >
                                @error('color')
                                <p class="text-danger" role="alert">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4 subtitleInput row">
                            <div class="col-md-8">
                                <label class="form-label">
                                    @if(Session::has('lang') && Session::get('lang')=='bn')
                                        উপ শিরোনাম
                                    @else
                                        Sub Title
                                    @endif</label>
                                <input
                                    type="text"
                                    placeholder="Type here"
                                    class="form-control subtitle"
                                    id="subtitle"
                                    name="subtitle"/>
                                @error('subtitle')
                                <p class="text-danger" role="alert">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    @if(Session::has('lang') && Session::get('lang')=='bn')
                                        উপ শিরোনামের রঙ
                                    @else
                                        Sub Title Color
                                    @endif
                                </label>
                                <input
                                    type="color"
                                    placeholder="Type here"
                                    class="form-control"
                                    id="subtitle_color"
                                    name="subtitle_color"
                                    style="width:100%; height:42px"
                                >
                                @error('subtitle_color')
                                <p class="text-danger" role="alert">{{$message}}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row buttonInput">
                            <div class="col-md-8">
                                <label for="product_name" class="form-label">
                                    @if(Session::has('lang') && Session::get('lang')=='bn')
                                        বোতাম
                                    @else
                                        Button
                                    @endif
                                </label>
                                <input
                                    type="text"
                                    placeholder="Type here"
                                    class="form-control"
                                    id="button"
                                    name="button"
                                    value="@isset($store_design->button) {{$store_design->button}} @endisset"

                                >
                                @error('button')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                            {{--                            @dd($store_design->button_color )--}}
                            <div class="col-md-4">
                                <label for="product_name" class="form-label">
                                    @if(Session::has('lang') && Session::get('lang')=='bn')
                                        বোতামের রঙ
                                    @else
                                        Button color
                                    @endif
                                </label>
                                <input
                                    type="color"
                                    placeholder="Type here"
                                    class="form-control"
                                    id="button_color"
                                    name="button_color"
                                    style="width:100%; height:42px"
                                    value="{{$store_design->button_color ?? ''}}"
                                >

                                @error('button_color')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label
                                class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                    লিঙ্ক
                                @else
                                    Link
                                @endif </label>
                            <input type="text" placeholder="Type here" class="form-control" id="link"
                                   name="link">
                            @error('link')
                            <p class="text-danger" role="alert">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">
                                @if(Session::has('lang') && Session::get('lang')=='bn')
                                    অবস্থান
                                @else
                                    Position
                                @endif <span class="req">*</span></label>
                            <input
                                type="number"
                                placeholder="Type here"
                                class="form-control"
                                id="position"
                                name="position"
                            >
                            @error('position')
                            <p class="text-danger" role="alert">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="mb-4 row">
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
                                           id="flexSwitchCheckChecked" name="status" style="margin:0 auto;"
                                           checked="">
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
                <div class="modal-footer">
                    <div>
                        <label class="form-label"></label>
                        <button class="btn btn-info rounded font-sm hover-up mb-0"
                                type="submit">@if(Session::has('lang') && Session::get('lang')=='bn')
                                প্রকাশ
                            @else
                                Publish
                            @endif
                        </button>
                        <button type="button" class="btn btn-danger mb-0" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{--slider main section--}}
<div class=" mt-4" id="toplist">
    <div class="row">
        <div class="col-md-6">
            <h4>@if(Session::has('lang') && Session::get('lang')=='bn')
                    সব ব্যানার
                @else
                    All Slider
                @endif</h4>
        </div>
        <div class="col-md-6">
            <ul>
                <li style="padding:0px;border:0px;">
                    <span
                        data-bs-target="#sliderAddModal"
                        data-bs-toggle="modal"
                        class="btn btn-primary btn-sm"
                        style="display:block;border-radius:0px !important">
                        @if(Session::has('lang') && Session::get('lang')=='bn')
                            নতুন ব্যানার যোগ করুন
                        @else
                            Add New Slider
                        @endif
                    </span>
                </li>
            </ul>
        </div>
    </div>

    {{--slider change status form--}}
    <form id="submitform" method="post" action="{{route('admin.changesliderssstatus')}}">
        <div class="row">
            <div class="col-md-4" style="padding-right:1px;">
                @csrf
                <input type="hidden" name="text2" id="selectids">
                <select class='form-control form-control-sm' name="action" id="action">
                    <option value="select">
                        @if(Session::has('lang') && Session::get('lang')=='bn')
                            সিলেক্ট  অপসন
                        @else
                            Select Option
                        @endif</option>
                    <option value="active">
                        @if(Session::has('lang') && Session::get('lang')=='bn')
                            সক্রিয়
                        @else
                            Active
                        @endif</option>
                    <option value="deactive">
                        @if(Session::has('lang') && Session::get('lang')=='bn')
                            নিষ্ক্রিয়
                        @else
                            Deactive
                        @endif</option>
                    <option value="delete">
                        @if(Session::has('lang') && Session::get('lang')=='bn')
                            ডিলিট
                        @else
                            Delete
                        @endif</option>
                </select>
            </div>
            <div class="col-md-2" style="padding-left:0px;">
                <p id="submit"
                   class="btn btn-primary btn-sm filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn')
                        আবেদন
                    @else
                        Apply
                    @endif</p>

            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm"
                           aria-label="Dollar amount (with dot and two decimal places)"
                           id="taskfilter">
                    <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                            class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
    </form>
    <div class="table-responsive ">
        <table class="table table-striped w-100" id="taskfilterresult">
            <thead>
            <tr>
                <th><input type="checkbox" name="ids" id="checkedAll"></th>
                <th>@if(Session::has('lang') && Session::get('lang')=='bn')
                        ছবি
                    @else
                        Image
                    @endif</th>
                <th width="20%">@if(Session::has('lang') && Session::get('lang')=='bn')
                        লিঙ্ক
                    @else
                        Link
                    @endif</th>
                <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn')
                        অবস্থান
                    @else
                        Position
                    @endif</th>
                <th> @if(Session::has('lang') && Session::get('lang')=='bn')
                        স্টেটাস
                    @else
                        Status
                    @endif </th>
                <th>@if(Session::has('lang') && Session::get('lang')=='bn')
                        এডিট/ডিলিট
                    @else
                        Action
                    @endif</th>
            </tr>
            </thead>
            <tbody>

            {{--slider preview table body--}}
            @foreach($sliders as $key=>$slider)

                {{--slider edit section--}}
                <div class="modal fade editSliderModal" id="sliderEditModal{{ $key }}" tabindex="-1"
                     aria-labelledby="sliderEditModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content px-2">
                            <form action="{{route('admin.slider.update',$slider->id)}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4>
                                        @if(Session::has('lang') && Session::get('lang')=='bn')
                                            এডিট ব্যানার
                                        @else
                                            Edit Slider
                                        @endif
                                    </h4>
                                    {{--<div class="btn btn-danger mb-0" onclick="sliderModalClose({{$key}})">X</div>--}}
                                </div>
                                <div class="modal-body">
                                    <div class="content-main">
                                        <input type="hidden" name="index" value="1" id="index">
                                        <div class="row">
                                            <div class="mb-3">
                                                <label for="product_name"
                                                       class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                        ছবি
                                                    @else
                                                        Image
                                                    @endif </label>
                                                <br>
                                                <div id="editPreviewContainer{{ $key }}">
                                                    @if(!empty($slider->image))
                                                        <div class="image-preview"
                                                             style="position: relative; display: inline-block;">
                                                            <img
                                                                src="{{ getPath($slider->image, "assets/images/slider") }}"
                                                                style="height: 100px; border: 1px solid rgb(204, 204, 204); padding: 3px; margin-right: 10px;">
                                                            <a href="{{ route('admin.removeSliderImage', ['id' => $slider->id]) }}"
                                                               onclick="deleteImage(event, this)"
                                                               class="imageUploadRemoveBtn">×</a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <input type="hidden" class="form-control" id="editImage{{ $key }}"
                                                       name="image">

                                                <button type="button" class="btn btn-outline-secondary browse-btn mt-2"
                                                        onclick="standalonFileManagerModal('editImage{{ $key }}', true, 'editPreviewContainer{{ $key }}');">
                                                    <i class="fa fa-picture-o"></i> Browse
                                                </button>
                                                @error('image')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label
                                                class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    লিঙ্ক
                                                @else
                                                    Link
                                                @endif </label>
                                            <input type="text" placeholder="Type here" value="{{$slider->link}}"
                                                   class="form-control" id="link" name="link">
                                            @error('link')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label
                                                class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    অবস্থান
                                                @else
                                                    Position
                                                @endif <span class="req">*</span></label>
                                            <input type="number" placeholder="Type here" class="form-control"
                                                   value="{{$slider->position}}" name="position">
                                            @error('position')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4 row">
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
                                                           style="margin:0 auto;"
                                                           @if($slider->status=='active') checked="" @endif>
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
                                <div class="modal-footer">
                                    <div>
                                        <label class="form-label"></label>
                                        <button class="btn btn-info rounded font-sm hover-up mb-0"
                                                type="submit">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                আপডেট
                                            @else
                                                Update
                                            @endif
                                        </button>
                                        <button type="button" class="btn btn-danger mb-0" data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                {{--slider table row--}}
                <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                    <td><input type="checkbox" name="selectedid" value="{{$slider->id}}" id="id" class="checkSingle">
                    </td>
                    <td>
                        @if(!empty($slider->image))
                            <img src="{{ getPath($slider->image, "assets/images/slider") }}"
                                 class="zoom" alt="" width="100px">
                        @endif
                    </td>
                    <td>{{\Illuminate\Support\Str::limit($slider->link, 20, $end='...') }}</td>
                    <td>
                        <input type="hidden" name="idss" id="id" value="{{$slider->id}}">
                        <input type="number" value="{{$slider->position ?? '0'}}" name="position"
                               style="width: 60px">
                    </td>
                    <td style="display: flex;justify-content: center;align-items: center;border-bottom-width:0px;min-height: 75px;">
                        <div class="form-check form-switch" style="text-align:center;padding-left:0px;">
                            <input class="form-check-input switchstatus" type="checkbox" data-id="{{$slider->id}}"
                                   id="flexSwitchCheckChecked" @if($slider->status=="active") checked=""
                                   @endif style="margin:0 auto;">
                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                        </div>
                    </td>
                    <td>

                        <span data-bs-toggle="modal" data-bs-target="#sliderEditModal{{ $key }}">
                            <img src="{{asset('img/edit.png')}}" width="20px" height="20px">
                        </span>
                        &nbsp;&nbsp;
                        <a href="{{route('admin.slider.delete',$slider->id)}}"
                           onclick="return confirm('Are you sure you want to delete this item?');"><img
                                src="{{asset('img/delete.png')}}" width="25px" height="25px"></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>



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


        $('#submit').on('click', function () {
            var form = $(this).parents('form');
            var note = $('#action').val();
            if (note != 'select') {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You want to " + note + " this selected item",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + note + ' it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        console.log(form);
                        $('#submitform').submit();
                        form.submit();
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swal.fire(
                            'Cancelled',
                            '' + note + ' Cancel :)',
                            'error'
                        )
                    }
                })
            }
        })
        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                $url = "/changesliderstatus";
                var value = $(this).val();
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {value: value, id: id}, function (data) {
                    console.log(data);
                });
            });


            $("#image").on("change", function (e) {
                if (!e.target.files || !e.target.files[0]) return;

                // Obtain a File reference
                var file = e.target.files[0];

                // Initialize FileReader to read the file
                var fileReader = new FileReader();

                // Capture the file information and display the preview
                fileReader.onload = function (event) {
                    //Apply the validation rules for attachments upload
                    const validation = ApplyFileValidationRules(file);
                    if (validation == false) {
                        $('#image').val("");
                        event.preventDefault();
                        return false;
                    }

                    $('#imagePreview').attr('src', event.target.result);
                };

                // Read the file as a DataURL (base64 encoded image)
                fileReader.readAsDataURL(file);
            });


            $("#editImage").on("change", function (e) {
                if (!e.target.files || !e.target.files[0]) return;

                // Obtain a File reference
                var file = e.target.files[0];

                // Initialize FileReader to read the file
                var fileReader = new FileReader();

                // Capture the file information and display the preview
                fileReader.onload = function (event) {
                    //Apply the validation rules for attachments upload
                    const validation = ApplyFileValidationRules(file);
                    if (validation == false) {
                        $('#editImage').val("");
                        event.preventDefault();
                        return false;
                    }

                    $('#editPreview').attr('src', event.target.result);
                };

                // Read the file as a DataURL (base64 encoded image)
                fileReader.readAsDataURL(file);
            });

            //Apply the validation rules for attachments upload
            function ApplyFileValidationRules(readerEvt) {
                //To check file type according to upload conditions
                if (CheckFileType(readerEvt.type) == false) {
                    swal.fire(
                        'Error!',
                        "The file (" +
                        readerEvt.name +
                        ") does not match the upload conditions, You can only upload jpg/png/gif/webp files 🥱",
                        'error'
                    );
                    return false;
                }

                //To check file Size according to upload conditions
                if (CheckFileSize(readerEvt.size) == false) {
                    swal.fire(
                        'Error!',
                        "The file (" + readerEvt.name + ") does not match the upload conditions, The maximum file size for uploads should not exceed {{ $sizeMsg }} 🥱",
                        'error'
                    );
                    return false;
                }
                return true;
            }

            //To check file type according to upload conditions
            function CheckFileType(fileType) {
                if (fileType == "image/jpeg") {
                    return true;
                } else if (fileType == "image/png") {
                    return true;
                } else if (fileType == "image/gif") {
                    return true;
                } else if (fileType == "image/webp") {
                    return true;
                } else {
                    return false;
                }
            }

            //To check file Size according to upload conditions
            function CheckFileSize(fileSize) {
                const size = "{{ $imageSize * 1000 ?? 200 }}";
                if (fileSize < size) {
                    return true;
                } else {
                    return false;
                }
            }

        });
        $(document).ready(function () {
            $("#checkedAll").change(function () {
                debugger;
                if (this.checked) {
                    $(".checkSingle").each(function () {
                        debugger;
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAll").prop("checked", true);
                    }
                } else {
                    $("#checkedAll").prop("checked", false);
                    var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#taskfilterresult tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }

        function sliderModalClose(key) {
            $('.show.modal-backdrop').remove();
            $('.modal.fade.show').removeClass('show');
            $('#sliderEditModal' + key).toggle();
        }

    </script>
@endpush
