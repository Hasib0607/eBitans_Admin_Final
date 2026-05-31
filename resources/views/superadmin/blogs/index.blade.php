@extends('admin.layouts.main')
@php
    $userData = getUserData();
    $store_id = $userData['store_id'];
    $user_type = $userData['user_type'];
    $imageSize = 200 ;
    $module_id = 107;
    $sizeMsg = "200KB";
    $moduleStatus = ModulusStatus($store_id,$module_id);
    if($moduleStatus || $user_type == "superadmin"){
        $imageSize = 5120 ;
        $sizeMsg = "5MB";
    }
@endphp
@section('content')
    <div class="modal fade" id="addCoverImage" tabindex="-1"
         aria-labelledby="addCoverImageLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content px-2">
                <form action="{{route('superadmin.blog.update.cover.image')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4>
                            @if(Session::has('lang') && Session::get('lang')=='bn')
                                নতুন কভার ছবি যোগ করুন
                            @else
                                Add Cover Image
                            @endif
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="content-main">
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
                                    @if(isset($coverImage->image))
                                        <img id="imagePreview" src="{{ asset("BlogImages") }}/{{$coverImage->image}}"
                                             alt=""
                                             style="max-width: 100px;margin-bottom: 20px;vertical-align: baseline;cursor:pointer">
                                    @else
                                        <img id="imagePreview" src="{{ URL::to('/') }}/img/upload.svg" alt=""
                                             style="max-width: 100px;margin-bottom: 20px;vertical-align: baseline;cursor:pointer">
                                    @endif

                                    <br>
                                    <input type="file" placeholder="Type here" class="form-control"
                                           id="image" name="image">
                                    <p class="text-secondary" id="image_description"></p>
                                    @error('image')
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
                                    সংরক্ষণ করুন
                                @else
                                    Save
                                @endif
                            </button>
                            <button type="button" class="btn btn-danger mb-0" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <main class="main-content position-relative border-radius-lg">
        @include('superadmin.blogs.type.sub_category')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            All Blogs
                        @else
                            All Blogs
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('superadmin.blog.create') }}" class="btn btn-primary"
                               style="display:block;border-radius:0px !important">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Add New Blog
                                @else
                                    Add New Blog
                                @endif
                            </a>
                        </li>

                        <li style="padding:0px;border:0px; margin-right: 10px">
                            <button class="btn btn-primary"
                                    data-bs-target="#addCoverImage"
                                    data-bs-toggle="modal"
                                    style="display:block;border-radius:0px !important">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Blog Cover Image
                                @else
                                    Blog Cover Image
                                @endif
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form id="submitform" method="post" action="{{ route('blog.action.change') }}">
                                        @csrf
                                        <input type="hidden" name="text2" id="selectids">
                                        <select class='form-control' name="action" id="action">
                                            <option value="select">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সিলেক্ট অপসন
                                                @else
                                                    Select Option
                                                @endif
                                            </option>
                                            <option value="active">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সক্রিয়
                                                @else
                                                    Active
                                                @endif
                                            </option>
                                            <option value="deactive">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    নিষ্ক্রিয়
                                                @else
                                                    Deactive
                                                @endif
                                            </option>
                                            <option value="delete">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ডিলিট
                                                @else
                                                    Delete
                                                @endif
                                            </option>
                                        </select>
                                </div>
                                <div class="col-md-1" style="padding-left:0px;">
                                    <p id="submit" class="btn btn-primary filterbuttonss">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আবেদন
                                        @else
                                            Apply
                                        @endif
                                    </p>
                                    </form>
                                </div>
                                <div class="col-md-7"></div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               aria-label="Dollar amount (with dot and two decimal places)"
                                               id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                class="fa fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th width="46%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Title
                                            @endif
                                        </th>
                                        <th Width="30%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                লিঙ্ক
                                            @else
                                                Sub Title
                                            @endif
                                        </th>
                                        <th width="5%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অবস্থান
                                            @else
                                                Position
                                            @endif
                                        </th>
                                        <th width="5%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্টেটাস
                                            @else
                                                Status
                                            @endif
                                        </th>
                                        <th width="5%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                তারিখ
                                            @else
                                                Date
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এডিট/ডিলিট
                                            @else
                                                Action
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($blogs) > 0)
                                        @foreach ($blogs as $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>
                                                    <input type="checkbox" name="selectedid" value="{{ $item->id }}"
                                                           id="id" class="checkSingle">
                                                </td>
                                                <td>{{ Str::of($item->title)->limit(60) }}
                                                </td>
                                                <td>{{ Str::of($item->sub_title)->limit(30) }}</td>
                                                <td>
                                                    <input type="hidden" name="position_id" id="id"
                                                           value="{{ $item->id }}" style="text-align: center;">
                                                    <input type="number" class="form-control" name="position"
                                                           value="{{ $item->position ?? 0 }}"
                                                           style="text-align: center;">
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch" style="text-align:center;">
                                                        <input class="form-check-input switchstatus" type="checkbox"
                                                               data-id="{{ $item->id }}" id="flexSwitchCheckChecked"
                                                               name="checkstatus" style="margin:0 auto;"
                                                               @if ($item->status == 1) checked @endif>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route('superadmin.blog.edit', $item->id) }}">
                                                        <img src="{{ asset('img/edit.png') }}" width="20px"
                                                             height="20px">
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a href="{{ route('superadmin.blog.delete', $item->id) }}"
                                                       onclick="return confirm('Are you sure to delete this?')">
                                                        <img src="{{ asset('img/delete.png') }}" width="25px"
                                                             height="25px">
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">Data not found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                {!! $blogs->links() !!}
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
        /**
         * This function use to set the product position and status
         */
        $(document).ready(function () {
            // Triggered when the value of an input with name 'position' changes
            $('input[name=position]').change(function () {
                handlePositionChange($(this));
            });

            // Update the accepted product status
            $(".switchstatus").on("change", function () {
                handleStatusChange($(this));
            });

            /**
             * Handles the change event for the 'position' input
             * @param {Object} element - The jQuery object representing the 'position' input
             */
            function handlePositionChange(element) {
                var value = element.val();
                var id = element.closest('tr').find("input[name=position_id]").val();
                sendAjaxRequest('{{ route('superadmin.blog.position') }}', {
                    value: value,
                    id: id
                });
            }

            /**
             * Handles the change event for the 'switchstatus' input
             * @param {Object} element - The jQuery object representing the 'switchstatus' input
             */
            function handleStatusChange(element) {
                var value = element.val();
                var id = element.data('id');
                sendAjaxRequest('{{ route('superadmin.blog.status') }}', {
                    value: value,
                    id: id
                });
            }

            /**
             * Sends an AJAX request and handles the response
             * @param {string} url - The URL for the AJAX request
             * @param {Object} requestData - The data to be sent in the request
             */
            function sendAjaxRequest(url, requestData) {
                $.get(url, requestData, function (data) {
                    if (data) {
                        Swal.fire(
                            'Congratulations Mr. {{ auth()->user()->name }}!',
                            data.status,
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                // Reload the browser window
                                window.location.reload();
                            }
                        });
                    }
                });
            }
        });
    </script>

    <script>
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
        });


        $(document).ready(function () {
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


    </script>

    <script>
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
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function () {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
