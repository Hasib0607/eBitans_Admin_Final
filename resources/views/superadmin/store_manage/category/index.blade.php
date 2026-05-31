@extends('admin.layouts.main')
@push('styles')
    <style>
        .fade:not(.show) {
            opacity: 1 !important;
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.store_manage.category.top_bar_category')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            সমস্ত ক্যাটাগরি
                        @else
                            All Marketplace Categories
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a data-href="/categoryexport"
                                onclick="exportCategory(event.target);" style="display:block;border-radius:0px !important"
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
                                    Add Marketplace Category
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.store.category.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            নাম
                                        @else
                                            Name
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="staticEmail" name="name"
                                            placeholder="Category Name">
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
                                    <label for="banner" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            ব্যানার
                                        @else
                                            Banner
                                        @endif
                                        <span class="req">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="file" class="form-control" id="banner" name="banner">
                                        @error('banner')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail" class="col-md-3 col-form-label">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            স্ট্যাটাস
                                        @else
                                            Status
                                        @endif
                                    </label>
                                    <div class="col-md-8">
                                        <div class="form-check form-switch is-filled" style="text-align:center;">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                                name="status" style="margin:0 auto;" checked="">
                                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                        </div>
                                        @error('status')
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
                                        <button type="submit" class="btn btn-info">
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
                                    <form id="submitform" method="post" action="{{ route('admin.changecategorystatus') }}">
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
                                            aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
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
                                        @foreach ($catagories as $cat)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td><input type="checkbox" name="selectedid" value="{{ $cat->id }}"
                                                        id="id" class="checkSingle"></td>
                                                <td>
                                                    <img src="{{ URL::to('/') }}/assets/images/icon/{{ $cat->icon }}"
                                                        width="40px">
                                                </td>
                                                <td><img src="{{ URL::to('/') }}/assets/images/category/{{ $cat->banner }}"
                                                        width="60px"></td>
                                                <td>{{ $cat->name }}</td>

                                                <td> {{ $cat->products_count }} </td>
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
                                                    <a href="{{ route('superadmin.store.category.edit', $cat->id) }}"><img
                                                            src="{{ asset('img/edit.png') }}" width="20px"
                                                            height="20px"></a>
                                                    &nbsp;&nbsp;
                                                    <a href="{{ route('superadmin.store.category.deletecat', $cat->id) }}"
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
                                    @foreach ($catagories as $key => $cat)
                                        <tr class="mobilefirstrow">
                                            <th width="10%">
                                                <input type="checkbox" name="selectedid" value="{{ $cat->id }}"
                                                    id="id" class="checkSingle">
                                            </th>
                                            <th width="20%" style="color:#f1593a">
                                                Name:dsadsadsad
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
                                                <img src="{{ URL::to('/') }}/assets/images/category/{{ $cat->banner }}"
                                                    width="60px">
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Product
                                            </th>
                                            <td width="60%">
                                            <td> terte </td>
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
                                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
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
                                                <a href="{{ route('superadmin.store.category.catAdd', $cat->id) }}">
                                                    <img src="{{ asset('img/icons/add.png') }}" width="20px"
                                                        height="20px">
                                                </a>
                                                &nbsp;&nbsp;
                                                <a href="{{ route('superadmin.store.category.edit', $cat->id) }}"><img
                                                        src="{{ asset('img/edit.png') }}" width="20px"
                                                        height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{ route('superadmin.store.category.deletecat', $cat->id) }}"
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
@endsection

@push('scripts')
    <script>
        document.querySelector('input').addEventListener("click", function(event) {
            event.preventDefault()
        });
        $('#submit').on('click', function() {
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
                })
            }
        });
        $(document).ready(function() {
            $('input[name=position]').change(function() {
                var value = $(this).val();
                var id = $(this).parent().parent().find("input[name=idss]").val();
                $url = "{{ route('superadmin.store.category.updateposition') }}";
                $.get($url, {
                    value: value,
                    id: id
                }, function(data) {
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
        $('.action-destroy').on('click', function() {
            $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
        });
        $(document).ready(function() {
            $(".switchstatus").on("change", function() {
                $url = "{{ route('superadmin.store.category.changecatstatus') }}";
                var value = $(this).val();
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {
                    value: value,
                    id: id
                }, function(data) {
                    console.log(data);
                    if (data) {
                        Swal.fire(
                            'Congratulations Mr. {{ auth()->user()->name }}!',
                            data.status,
                            'success'
                        )
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#checkedAll").change(function() {
                debugger;
                if (this.checked) {
                    $(".checkSingle").each(function() {
                        debugger;
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function() {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function() {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function() {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function() {
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
                    var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
        $(document).ready(function() {
            $("#taskfilter").on("keyup", function() {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function() {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportCategory(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
