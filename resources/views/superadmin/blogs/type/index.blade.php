@extends('admin.layouts.main')
@section('content')
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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal2" style="display:block;border-radius:0px !important">
                                Add Blog
                                Type
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row mt-5 productlist">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Add New Blog
                                @else
                                    Add New Blog
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.blog.type.store') }}" method="POST">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Blog Type
                                            @else
                                                Blog Type
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <input type="hidden" value="{{ $blogType->id ?? "" }}" name="id">
                                        <input type="text" placeholder="Type here" class="form-control" id="name"
                                               name="name" value="{{ $blogType->type ?? old('name') ?? "" }}" required>
                                        @error('name')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info mt-4 ml-3">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        প্রকাশ
                                    @else
                                        Publish
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form id="submitform" method="post" action="{{ route('blog.type.status.change') }}">
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
                                                class="fa fa-search"></i>
                                        </span>
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
                                        <th width="25%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Name
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্টেটাস
                                            @else
                                                Status
                                            @endif
                                        </th>
                                        <th width="20%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                তারিখ
                                            @else
                                                Date
                                            @endif
                                        </th>
                                        <th width="16%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এডিট/ডিলিট
                                            @else
                                                Action
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($blogTypes) > 0)
                                        @foreach ($blogTypes as $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>
                                                    <input type="checkbox" name="selectedid" value="{{ $item->id }}"
                                                           id="id"
                                                           class="checkSingle">
                                                </td>
                                                </td>
                                                <td>{{ $item->type }}</td>
                                                <td style="margin:0 auto;text-align:center;">
                                                    <div class="form-check form-switch"
                                                         style="text-align:center;display:inline-flex">
                                                        <input class="form-check-input switchstatus" type="checkbox"
                                                               data-id="{{ $item->id }}" id="flexSwitchCheckChecked"
                                                               style="margin:0 auto;"
                                                               @if($item->status == 1) checked @endif>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>
                                                    <a href="{{ route("superadmin.blog.type.index", ["id" => $item->id]) }}">
                                                        <img src="{{ asset('img/edit.png') }}" width="20px"
                                                             height="20px">
                                                    </a>
                                                    <a style="margin-left: 3px" href="#"
                                                       onclick="deleteBlogType(event,{{ $item->id }})">
                                                        <img src="{{ asset('img/delete.png') }}" width="25px"
                                                             height="25px">
                                                    </a>
                                                    <form id="deleteBlogType{{ $item->id }}" method="post"
                                                          action="{{ route('delete.blog.type', ['id' => $item->id]) }}">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">Data not found</td>
                                        </tr>
                                    @endif
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
            $('input[name=position]').change(function () {
                var value = $(this).val();
                var id = $(this).parent().parent().find("input[name=idss]").val();
                $url = "/update-position-page";
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
        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                // Use Blade syntax to inject the route URL into JavaScript
                const routeUrl = `{{ route('single.blog.type.status.change', ['id' => '__city_id__']) }}`;

                var value = $(this).val();
                var id = $(this).data('id');

                // Replace placeholder with actual city_id value
                const URL = routeUrl.replace('__city_id__', id);

                $.get(URL, {
                    value: value,
                    id: id
                }, function (data) {
                    console.log(data);
                });
            });
        });


        const deleteBlogType = (e, id) => {
            e.preventDefault();
            swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this blog type?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#deleteBlogType' + id).submit();
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        'Cancelled',
                        'Delete Cancel :)',
                        'error'
                    )
                }
            })

        }
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
