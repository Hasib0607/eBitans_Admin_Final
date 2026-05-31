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
            display: none;
        }

        img.thumb {
            width: 80px !important;
        }

        .autocomplete-suggestions {
            max-height: 200px;
            overflow-y: auto;
            background: white;
            border: 1px solid #00000020;
            display: none;
            z-index: 1000;
            border-top-right-radius: 0;
            border-top-left-radius: 0;
            width: calc(100% - 24px);
            top: 41px;
            left: 12px;
            position: absolute;
            scrollbar-width: thin;
        }

        /* Chrome, Safari, Edge */
        .autocomplete-suggestions::-webkit-scrollbar {
            width: 6px;
        }

        .autocomplete-suggestions::-webkit-scrollbar-track {
            background: transparent;
        }

        .autocomplete-suggestions::-webkit-scrollbar-thumb {
            background-color: transparent;
            border-radius: 4px;
        }

        /* Show on hover */
        .autocomplete-suggestions:hover::-webkit-scrollbar-thumb {
            background-color: #999;
        }

        /* Chrome, Safari, Edge (hide scrollbar) */
        .autocomplete-suggestions::-webkit-scrollbar {
            display: none;
        }

        .autocomplete-suggestions .list-group-item {
            cursor: pointer;
            border: none;
            border-top: 1px solid #00000020;
        }

        .list-group-item:first-child {
            border-top: none;
        }
    </style>
@endpush
@section('content')
    @php
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $store = DB::table('stores')->where('id', $store_id)->first();
        if ($store->expiry_date <= Carbon\Carbon::now()) {
            $exp = 1;
        } else {
            $exp = 0;
        }
    @endphp
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('admin.admin_top_bar_category.index')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            সমস্ত ক্যাটাগরি
                        @else
                            All Categories
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a data-href="/categoryexport"
                                                               onclick="exportCategory(event.target);"
                                                               style="display:block;border-radius:0px !important"
                                                               class="btn btn-secondary">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    এক্সপোর্ট
                                @else
                                    Excel
                                @endif
                            </a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    নতুন ক্যাটাগরি যোগ করুন
                                @else
                                    Add Category
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ URL::to('category') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="name" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            নাম
                                        @else
                                            Name
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-8 position-relative">
                                        <input type="text" class="form-control" id="name" name="name"
                                               placeholder="Category Name" autocomplete="off">
                                        <div id="suggestions"
                                             class="autocomplete-suggestions list-group position-absolute z-3 scrollable"></div>
                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="icon" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আইকন
                                        @else
                                            Icon
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <select id='iconpack' name="icon" class="form-control"
                                                style="width:100% !important">
                                            <option value="null">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    আইকন নির্বাচন করুন
                                                @else
                                                    Select Icon
                                                @endif
                                            </option>
                                                <?php
                                                $icons = DB::table('iconpacks')->get();
                                                ?>
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
                                    <label for="banner" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ব্যানার
                                        @else
                                            Banner
                                        @endif
                                    </label>

                                    <div class="col-md-8">
                                        <div id="previewContainer"></div>
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
                                    <label for="position" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            অবস্থান
                                        @else
                                            Position
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" id="position" name="position"
                                               placeholder="0" autofocus="">
                                        @error('position')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="position" class="col-md-3 col-form-label"></label>
                                    <div class="col-md-8" style="text-align:right">
                                        <button type="submit" id="submitBtn" class="btn btn-info">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                জমা দিন
                                            @else
                                                Submit
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form id="submitform" method="post"
                                          action="{{ route('admin.changecategorystatus') }}">
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

                                <div class="col-md-6"></div>
                                <div class="col-md-3">
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
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" width="100%" id="taskfilterresult">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll">
                                        </th>
                                        <th width="5%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আইকন
                                            @else
                                                Icon
                                            @endif
                                        </th>
                                        <th width="20%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ব্যানার
                                            @else
                                                Banner
                                            @endif
                                        </th>
                                        <th width="20%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Name
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পণ্য
                                            @else
                                                Product
                                            @endif
                                        </th>
                                        <th width="5%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অবস্থান
                                            @else
                                                Position
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্ট্যাটাস
                                            @else
                                                Status
                                            @endif
                                        </th>
                                        <th width="21%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এডিট/ডিলিট
                                            @else
                                                Action
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($catgories as $cat)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td><input type="checkbox" name="selectedid" value="{{ $cat->id }}"
                                                       id="id" class="checkSingle"></td>
                                            <td>
                                                <!--<i class="fa {{ $cat->icon }}"></i>-->
                                                <img src="{{ URL::to('/') }}/assets/images/icon/{{ $cat->icon }}"
                                                     width="40px">
                                            </td>
                                            <td>
                                                @if(!empty($cat->banner))
                                                    <img src="{{ getPath($cat->banner, "assets/images/category") }}"
                                                         width="60px">
                                                @endif
                                            </td>
                                            <td>{{ $cat->name }}</td>
                                            <td>{{ $cat->total_products ?? '0' }}</td>
                                            <td>
                                                <input type="hidden" name="idss" id="id"
                                                       value="{{ $cat->id }}">
                                                <input type="number" value="{{ $cat->position ?? '0' }}"
                                                       name="position" id="position" style="width:70%">
                                            </td>
                                            <td>
                                                <div class="form-check form-switch" style="text-align:center;">
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                           data-id="{{ $cat->id }}" id="flexSwitchCheckChecked"
                                                           name="checkstatus" style="margin:0 auto;"
                                                           @if ($cat->status == 'active') checked @endif>
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <!--<form action="{{ URL::to('/') }}/category/{{ $cat->id }}"  method="POST">-->
                                                <!--    <input type="hidden" name="_method" value="DELETE">-->
                                                <!--    <input type="hidden" name="_token" value="{{ csrf_token() }}">-->
                                                <!--    <a onclick="return confirm('Are you sure you want to delete this item?');"><img src="{{ asset('img/delete.png') }}" width="20px" height="20px"></a>-->
                                                <!--</form>                                    -->
                                                <a href="{{ URL::to('/') }}/category/{{ $cat->id }}/edit"><img
                                                        src="{{ asset('img/edit.png') }}" width="20px"
                                                        height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{ URL::to('/') }}/category/{{ $cat->id }}/delete"
                                                   onclick="del()"><img src="{{ asset('img/delete.png') }}"
                                                                        width="25px" height="25px"></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-3" id="mobiletable">
                                <table class="table" width="100%">
                                    @foreach ($catgories as $key => $cat)
                                        <tr class="mobilefirstrow">
                                            <th width="10%">
                                                <input type="checkbox" name="selectedid" value="{{ $cat->id }}"
                                                       id="id" class="checkSingle">
                                            </th>
                                            <th width="20%" style="color:#f1593a">
                                                Name:
                                            </th>
                                            <td width="60%" style="color:black">
                                                {{ $cat->name }}
                                            </td>
                                            <td width="10%">
                                                <a href="#" class="toggler" data-prod-cat="{{ $key }}">
                                                    <i class="fa fa-arrow-down" id="show{{ $key }}"
                                                       style="color:#f1593a"></i>
                                                    <i class="fa fa-arrow-up" id="up{{ $key }}"
                                                       style="display:none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Icon
                                            </th>
                                            <td width="60%">
                                                <img src="{{ URL::to('/') }}/assets/images/icon/{{ $cat->icon }}"
                                                     width="40px">
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Banner
                                            </th>
                                            <td width="60%">
                                                @if(!empty($cat->banner))
                                                    <img src="{{ getPath($cat->banner, "assets/images/category") }}"
                                                         width="60px">
                                                @endif
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Product
                                            </th>
                                            <td width="60%">
                                                {{ $cat->total_products }}
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Position
                                            </th>
                                            <td width="60%">
                                                <input type="hidden" name="idss" id="id"
                                                       value="{{ $cat->id }}">
                                                <input type="number" value="{{ $cat->position ?? '0' }}"
                                                       name="position" id="position" style="width:70%">
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Status
                                            </th>
                                            <td width="60%"
                                                style="display: flex;justify-content: center;align-items: center;">
                                                <div class="form-check form-switch" style="text-align:center;">
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                           id="flexSwitchCheckChecked" data-id="{{ $cat->id }}"
                                                           style="margin:0 auto;"
                                                           @if ($cat->status == 'active') checked @endif>
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Action
                                            </th>
                                            <td width="60%">
                                                <a href="{{ URL::to('/') }}/category/{{ $cat->id }}/edit"><img
                                                        src="{{ asset('img/edit.png') }}" width="20px"
                                                        height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{ URL::to('/') }}/category/{{ $cat->id }}/delete"
                                                   onclick="del()"><img src="{{ asset('img/delete.png') }}"
                                                                        width="25px" height="25px"></a>
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--<button class="btn btn-danger action-destroy"></button>-->
@endsection

@push('scripts')

    <script src="https://cdn.ckeditor.com/4.20.1/full-all/ckeditor.js"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('admin/dist/js/custom-ckeditor.js') }}"></script>

    <script>
        document.querySelector('input').addEventListener("click", function (event) {
            event.preventDefault()
        });
        $('#submit').on('click', function () {
            var form = $(this).parents('form');
            var note = $('#action').val();
            if (note != 'select') {
                swal.fire({
                    title: 'Are you sure?',
                    text: "you want to " + note + " this category?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + note + ' it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (note === "delete") {
                        if (result.value) {
                            swal.fire({
                                title: 'Are you sure?',
                                text: "Your all data will be deleted like product,subcategory, are you sure you want to " +
                                    note + "?",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, ' + note + ' it!',
                                cancelButtonText: 'No, cancel!',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.value) {
                                    $('#submitform').submit();
                                } else if (
                                    result.dismiss === Swal.DismissReason.cancel
                                ) {
                                    swal.fire(
                                        'Cancelled',
                                        'Deletion Cancel :)',
                                        'error'
                                    )
                                }
                            })
                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swal.fire(
                                'Cancelled',
                                'Deletion Cancel :)',
                                'error'
                            )
                        }
                    } else {
                        if (result.value) {
                            $('#submitform').submit();
                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swal.fire(
                                'Cancelled',
                                'Deletion Cancel :)',
                                'error'
                            )
                        }
                    }


                })
            }
        });
        $(document).ready(function () {
            $('input[name=position]').change(function () {
                var value = $(this).val();
                var id = $(this).parent().parent().find("input[name=idss]").val();
                $url = "/update-position-category";
                $.get($url, {
                    value: value,
                    id: id
                }, function (data) {
                    window.location.reload();
                });
            });
        });
    </script>
    <script>
        function del() {
            let av = confirm("are you sure you want to delete this category?");
            if (av) {
                return confirm("Your all data will be deleted like product,subcategory, are you sure you want to delete?");
            }
        }

        $('.icon').iconpicker();
        $('.action-destroy').on('click', function () {
            $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
        });
        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                $url = "/changecatstatus";
                var value = $(this).val();
                var id = $(this).data('id');
                $.get($url, {
                    value: value,
                    id: id
                }, function (data) {
                    window.location.reload();
                });
            });
        });
    </script>
    <script>
        $('#submitBtn').on('click', function () {
            this.disabled = true;
            this.form.submit();
        });

        $(document).ready(function () {
            let valuesArray = [];

            // Check all checkbox action
            $("#checkedAll").change(function () {
                if (this.checked) {
                    // If "checkedAll" is checked, check all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = true;
                        let value = $(this).val();
                        if (!valuesArray.includes(value)) {
                            valuesArray.push(value);
                        }
                    });
                } else {
                    // If "checkedAll" is unchecked, uncheck all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    valuesArray = [];
                }

                let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                $("#selectids").val(newAaluesArray);
                $("#selectdelids").val(newAaluesArray);
            });

            // Single check action
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    let value = $(this).val();

                    let isAllChecked = $(".checkSingle").length === $(".checkSingle:checked").length;
                    $("#checkedAll").prop("checked", isAllChecked);

                    if (!valuesArray.includes(value)) {
                        valuesArray.push(value);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
                } else {
                    $("#checkedAll").prop("checked", false);

                    let value = $(this).val();

                    let index = valuesArray.indexOf(value);

                    if (index === -1) {
                        valuesArray.push(value);
                    } else {
                        valuesArray.splice(index, 1);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
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

        function exportCategory(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }


        const input = document.getElementById('name');
        const suggestionBox = document.getElementById('suggestions');
        let debounceTimer;

        input.addEventListener('input', function () {
            const query = this.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < 2) {
                suggestionBox.innerHTML = '';
                suggestionBox.style.display = 'none';
                // Remove border radius when no suggestion
                input.style.borderBottomRightRadius = '';
                input.style.borderBottomLeftRadius = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                axios.get('/categories/suggestions', {
                    params: {q: query}
                })
                    .then(response => {
                        const suggestions = response.data;
                        suggestionBox.innerHTML = '';

                        if (suggestions.length > 0) {
                            suggestions.forEach(item => {
                                const div = document.createElement('div');
                                div.classList.add('list-group-item', 'list-group-item-action');
                                div.textContent = item;
                                div.addEventListener('click', function () {
                                    input.value = this.textContent;
                                    suggestionBox.innerHTML = '';
                                    suggestionBox.style.display = 'none';
                                    input.style.borderBottomRightRadius = '';
                                    input.style.borderBottomLeftRadius = '';
                                });
                                suggestionBox.appendChild(div);
                            });
                            suggestionBox.style.display = 'block';

                            // Apply radius removal
                            input.style.borderBottomRightRadius = '0';
                            input.style.borderBottomLeftRadius = '0';
                        } else {
                            suggestionBox.style.display = 'none';
                            input.style.borderBottomRightRadius = '';
                            input.style.borderBottomLeftRadius = '';
                        }
                    });
            }, 500);
        });

        document.addEventListener('click', function (e) {
            if (!suggestionBox.contains(e.target) && e.target !== input) {
                suggestionBox.innerHTML = '';
                suggestionBox.style.display = 'none';
                input.style.borderBottomRightRadius = '';
                input.style.borderBottomLeftRadius = '';
            }
        });
    </script>
@endpush
