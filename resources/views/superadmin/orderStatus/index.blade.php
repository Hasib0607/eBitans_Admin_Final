@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            All Status
                        @else
                            All Status
                        @endif
                    </h4>
                </div>
                {{--                <div class="col-md-6">--}}
                {{--                    <ul>--}}
                {{--                        <li style="padding:0px;border:0px;">--}}
                {{--                            <a href="{{ route('superadmin.order.status.index') }}" class="btn btn-primary"--}}
                {{--                               data-bs-toggle="modal"--}}
                {{--                               data-bs-target="#exampleModal2" style="display:block;border-radius:0px !important">--}}
                {{--                                Status--}}
                {{--                            </a>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                </div>--}}
            </div>

            <div class="row mt-3 productlist">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Add New Status
                                @else
                                    Add New Status
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.order.status.store') }}" method="POST">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Name
                                            @else
                                                Name
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <input type="hidden" value="{{ $status->id ?? "" }}" name="id">
                                        <input type="text" placeholder="Type here" class="form-control" id="name"
                                               name="name" value="{{ $status->name ?? old('name') ?? "" }}" required>
                                        @error('name')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <label for="name_bn" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Name Bangla
                                            @else
                                                Name Bangla
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control" id="name_bn"
                                               name="name_bn" value="{{ $status->name_bn ?? old('name_bn') ?? "" }}">
                                        @error('name_bn')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <label for="slug" class="form-label">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Slug
                                            @else
                                                Slug
                                            @endif
                                            <span class="req">*</span>
                                        </label>
                                        <input type="text" placeholder="Type here" class="form-control" id="slug"
                                               name="slug" value="{{ $status->slug ?? old('slug') ?? "" }}"
                                               @if(isset($status->slug_edit) && $status->slug_edit == "0") disabled
                                               readonly @endif>
                                        <small class="text-warning">[N.B]: Already created status slug change be will
                                            dangerous thing</small>
                                        @error('slug')
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
                                    <form id="submitform" method="post"
                                          action="{{ route('superadmin.order.status.change') }}">
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
                                        <th width="25%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Name BN
                                            @endif
                                        </th>
                                        <th width="20%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Slug
                                            @else
                                                Slug
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্টেটাস
                                            @else
                                                Status
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
                                    @foreach ($statuses as $item)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>
                                                <input type="checkbox" name="selectedid" value="{{ $item->id }}" id="id"
                                                       class="checkSingle">
                                            </td>
                                            <td>{{ $item->name ?? "" }}</td>
                                            <td>{{ $item->name_bn ?? "" }}</td>
                                            <td>{{ $item->slug ?? "" }}</td>
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
                                            <td>
                                                <a href="{{ route("superadmin.order.status.index", ["id" => $item->id]) }}">
                                                    <img src="{{ asset('img/edit.png') }}" width="20px"
                                                         height="20px">
                                                </a>
                                                {{--                                                <a style="margin-left: 3px" href="#"--}}
                                                {{--                                                   onclick="deleteOrderStatus(event,{{ $item->id }})">--}}
                                                {{--                                                    <img src="{{ asset('img/delete.png') }}" width="25px"--}}
                                                {{--                                                         height="25px">--}}
                                                {{--                                                </a>--}}
                                                {{--                                                <form id="deleteOrderStatus{{ $item->id }}" method="post"--}}
                                                {{--                                                      action="{{ route('superadmin.delete.order.status', ['id' => $item->id]) }}">--}}
                                                {{--                                                    @csrf--}}
                                                {{--                                                    <input type="hidden" name="id" value="{{ $item->id }}">--}}
                                                {{--                                                </form>--}}
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
    </script>

    <script>
        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                // Use Blade syntax to inject the route URL into JavaScript
                const routeUrl = `{{ route('superadmin.single.order.status.change', ['id' => '__status_id__']) }}`;

                var value = $(this).val();
                var id = $(this).data('id');

                // Replace placeholder with actual status_id value
                const URL = routeUrl.replace('__status_id__', id);

                $.get(URL, {
                    value: value,
                    id: id
                }, function (data) {
                    console.log(data);
                });
            });
        });


        const deleteOrderStatus = (e, id) => {
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
                    $('#deleteOrderStatus' + id).submit();
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
                if (this.checked) {
                    $(".checkSingle").each(function () {
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
                debugger;
                $("#taskfilterresult tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)

                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
