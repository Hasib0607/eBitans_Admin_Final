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
                            All PSE Categories
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a data-href="/categoryexport" onclick="exportCategory(event.target);"
                                style="display:block;border-radius:0px !important" class="btn btn-secondary">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    এক্সপোর্ট
                                @else
                                    Excel
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @if (Session::has('message'))
                <div class="alert alert-success">
                    {{ Session::get('message') }}
                </div>
            @endif
            <div class="row mt-5 productlist">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    নতুন ক্যাটাগরি যোগ করুন
                                @else
                                    Add PSE Category
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pse.category.store') }}" method="post" enctype="multipart/form-data">
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
                                            aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                            <i class="fa fa-search"></i>
                                        </span>
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
                                        @foreach ($catagories as $category)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>
                                                    <input type="checkbox" name="selectedid" value="{{ $category->id }}"
                                                        id="id" class="checkSingle">
                                                </td>
                                                <td>
                                                    <img src="{{ URL::to('/') }}/assets/images/icon/{{ $category->icon }}"
                                                        width="40px">
                                                </td>
                                                <td>
                                                    <img src="{{ URL::to('/') }}/assets/images/category/{{ $category->banner }}"
                                                        width="60px">
                                                </td>
                                                <td>{{ $category->name }}</td>
                                                <td> {{ $category->totalProduct }} </td>
                                                <td>
                                                    <input type="hidden" name="position_id" id="id"
                                                        value="{{ $category->id }}" style="text-align: center;">
                                                    <input type="number" class="form-control" name="position"
                                                        value="{{ $category->position ?? 0 }}"
                                                        style="text-align: center;">
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch" style="text-align:center;">
                                                        <input class="form-check-input switchstatus" type="checkbox"
                                                            data-id="{{ $category->id }}" id="flexSwitchCheckChecked"
                                                            name="checkstatus" style="margin:0 auto;"
                                                            @if ($category->status == 'active') checked @endif>
                                                        <label class="form-check-label"
                                                            for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('pse.category.edit', $category->id) }}"><img
                                                            src="{{ asset('img/edit.png') }}" width="20px"
                                                            height="20px"></a>
                                                    &nbsp;&nbsp;
                                                    <a href="{{ route('pse.category.delete', $category->id) }}"><img
                                                            src="{{ asset('img/delete.png') }}" width="25px"
                                                            height="25px">
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
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
        /**
         * This function use to set the product position and status
         */
        $(document).ready(function() {
            // Triggered when the value of an input with name 'position' changes
            $('input[name=position]').change(function() {
                handlePositionChange($(this));
            });

            // Update the accepted product status
            $(".switchstatus").on("change", function() {
                handleStatusChange($(this));
            });

            /**
             * Handles the change event for the 'position' input
             * @param {Object} element - The jQuery object representing the 'position' input
             */
            function handlePositionChange(element) {
                var value = element.val();
                var id = element.closest('tr').find("input[name=position_id]").val();
                sendAjaxRequest('{{ route('pse.category.position') }}', {
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
                sendAjaxRequest('{{ route('pse.category.status') }}', {
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
                $.get(url, requestData, function(data) {
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
@endpush