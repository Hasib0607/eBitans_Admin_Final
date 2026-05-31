@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.5.0/css/select.dataTables.min.css">

    <style>
        .fade:not(.show) {
            opacity: 1 !important;
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item @if (url()->current() == route('superadmin.store.manage')) active @endif">
                                <a href="{{ route('superadmin.store.manage') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span
                                        class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            সব দোকান
                                        @else
                                            All Store
                                        @endif
                                    </span>
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">
            <div class="row mt-5 productlist">
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <div class="card">
                        <div class="card-header pb-0">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            সমস্ত দোকান
                                        @else
                                            All Store
                                        @endif
                                    </h4>
                                </div>
                                <div class="col-md-3" style="padding-right:1px;">
                                    <form id="multiSubmitform" method="post"
                                          action="{{ route('superadmin.store.delete.multi') }}">
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
                                    <p onclick="multiDeleteSubmit()" class="btn btn-primary filterbuttonss">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আবেদন
                                        @else
                                            Apply
                                        @endif
                                    </p>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <ul>
                                        <li style="padding:0px;border:0px;">
                                            <a data-href="#" style="display:block;border-radius:0px !important"
                                               class="btn btn-secondary">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Combo
                                                @else
                                                    Combo
                                                @endif
                                            </a>
                                        </li>
                                        <li style="padding:0px;border:0px;">
                                            <a data-href="#" style="display:block;border-radius:0px !important"
                                               class="btn btn-secondary">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    SMK
                                                @else
                                                    SMK
                                                @endif
                                            </a>
                                        </li>
                                        <li style="padding:0px;border:0px;">
                                            <a data-href="#" style="display:block;border-radius:0px !important"
                                               class="btn btn-secondary">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    POS
                                                @else
                                                    POS
                                                @endif
                                            </a>
                                        </li>
                                        <li style="padding:0px;border:0px;">
                                            <a data-href="#" style="display:block;border-radius:0px !important"
                                               class="btn btn-secondary">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Website
                                                @else
                                                    Website
                                                @endif
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" id="desktoptable">
                                <table id="example" class="display" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                কাস্টমার নাম্বার
                                            @else
                                                Customer Mobile
                                            @endif
                                        </th>
                                        <th>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Name
                                            @endif
                                        </th>
                                        <th>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ডোমেইন
                                            @else
                                                Domain
                                            @endif
                                        </th>
                                        <th>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                প্রকার
                                            @else
                                                Type
                                            @endif
                                        </th>
                                        <th>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                প্যাকেজ
                                            @else
                                                Package
                                            @endif
                                        </th>
                                        <th>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                মেয়াদ শেষ
                                            @else
                                                Expiry
                                            @endif
                                        </th>
                                        <th>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্ট্যাটাস
                                            @else
                                                Status
                                            @endif
                                        </th>
                                        <th>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এডিট/ডিলিট
                                            @else
                                                Action
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($allStore as $store)
                                        <tr>
                                            <td><input type="checkbox" name="selectedid" value="{{ $store->id }}"
                                                       id="id" class="checkSingle"></td>
                                            <td>{{ $store->storeCustomer->phone ?? 'Empty' }}</td>
                                            <td> {{ Str::of($store->name)->limit(15, '...') }}</td>
                                            <td><a href="http://{{ $store->url }}" class="btn btn-info btn-sm mb-0"
                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                   data-bs-custom-class="custom-tooltip"
                                                   data-bs-title="{{ $store->url }}" target="_blank"
                                                   rel="noopener noreferrer">Vist Store</a></td>
                                            <td> {{ $store->type }} </td>
                                            <td> {{ $store->storePlan->name ?? 'Empty' }} </td>
                                            <td> {{ $store->expiry_date }} </td>
                                            <td style="text-align: center;">

                                                @if ($store->expiry_date >= date('Y-m-d'))
                                                    <span
                                                        style="color: white;padding: 2px 10px;border-radius: 4px; background: #08ad08;">
                                                            Active
                                                        </span>
                                                @else
                                                    <span
                                                        style="color: white;padding: 2px 10px;border-radius: 4px; background: red;">
                                                            Expired
                                                        </span>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="#" onclick="deleteStore({{ $store->id }})">
                                                    <img src="{{ asset('img/delete.png') }}" width="25px"
                                                         height="25px">
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>


                                {{-- confirmation alert --}}
                                <form action="{{ route('superadmin.store.delete') }}" id="deleteSubmit" method="POST">
                                    @csrf
                                    <input type="hidden" id="DeleteID" name="id" value="">
                                </form>
                                {{-- confirmation alert end --}}

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
    <script>
        // document.querySelector('input').addEventListener('mouseup', (e) => {
        //     e.preventDefault();
        //     debugger;
        // });
        document.querySelector('input').addEventListener("click", function (event) {
            event.preventDefault()
        });

        function deleteStore(id) {

            var form = $(this).parents('form');

            swal.fire({
                title: 'Are you sure?',
                text: "You want to this Store?",

                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    swal.fire({
                        title: 'Again Confirmation for Delete',
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        text: "If your confirm to delete store now entry your root password",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, it!',
                        cancelButtonText: 'No, cancel!',
                        showLoaderOnConfirm: true,
                        reverseButtons: true,
                        showLoaderOnConfirm: true,
                        preConfirm: (login) => {
                            $url = "{{ route('superadmin.store.store.delete.auth.check') }}";
                            $.get($url, {
                                value: login,
                                id: login
                            }, function (data) {
                                // console.log(data);
                                if (data.status == false) {
                                    swal.fire(
                                        'Incorrect Password!',
                                        'Incorrect Password Mr. {{ auth()->user()->name }} try again 🥱',
                                        'error'
                                    );
                                } else {
                                    swal.fire({
                                        title: 'Are you sure?',
                                        text: "Correct Password Mr. {{ auth()->user()->name }} try again 🥱'",

                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Yes, it!',
                                        cancelButtonText: 'No, cancel!',
                                        reverseButtons: true
                                    }).then((result) => {
                                        if (result.value) {
                                            $('#DeleteID').val(id);
                                            $('#deleteSubmit').submit();
                                        } else if (
                                            result.dismiss === Swal.DismissReason.cancel
                                            // alert('soory Boro');
                                        ) {
                                            swal.fire(
                                                'Cancelled',
                                                'Deletion Cancel 🥱',
                                                'error'
                                            )
                                        }
                                    })
                                }
                            });

                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((data) => {
                        if (data.status) {
                            // $('#submitform').submit();
                            alert('Sorry Boro  ' + data.status);
                            // alert(result.value);
                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                            // alert('soory Boro');
                        ) {
                            swal.fire(
                                'Cancelled',
                                'Deletion Cancel 🥱',
                                'error'
                            )
                        }
                    })
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        'Cancelled',
                        'Deletion Cancel 🥱',
                        'error'
                    )
                }
            })

        };

        //   multiDeleteSubmit
        function multiDeleteSubmit() {

            swal.fire({
                title: 'Are you sure?',
                text: "You want to this Store?",

                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    swal.fire({
                        title: 'Again Confirmation for Delete',
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        text: "If your confirm to delete store now entry your root password",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, it!',
                        cancelButtonText: 'No, cancel!',
                        showLoaderOnConfirm: true,
                        reverseButtons: true,
                        showLoaderOnConfirm: true,
                        preConfirm: (login) => {
                            $url = "{{ route('superadmin.store.store.delete.auth.check') }}";
                            $.get($url, {
                                value: login,
                                id: login
                            }, function (data) {
                                console.log(data);
                                if (data.status == false) {
                                    swal.fire(
                                        'Incorrect Password!',
                                        'Incorrect Password Mr. {{ auth()->user()->name }} try again 🥱',
                                        'error'
                                    );
                                } else {
                                    swal.fire({
                                        title: 'Are you sure?',
                                        text: "Correct Password Mr. {{ auth()->user()->name }} try again 🥱'",

                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Yes, it!',
                                        cancelButtonText: 'No, cancel!',
                                        reverseButtons: true
                                    }).then((result) => {
                                        if (result.value) {
                                            $('#DeleteID').val(id);
                                            $('#multiSubmitform').submit();
                                        } else if (
                                            result.dismiss === Swal.DismissReason.cancel
                                            // alert('soory Boro');
                                        ) {
                                            swal.fire(
                                                'Cancelled',
                                                'Deletion Cancel 🥱',
                                                'error'
                                            )
                                        }
                                    })
                                }
                            });

                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((data) => {
                        if (data.status) {
                            // $('#submitform').submit();
                            alert('Sorry Boro  ' + data.status);
                            // alert(result.value);
                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                            // alert('soory Boro');
                        ) {
                            swal.fire(
                                'Cancelled',
                                'Deletion Cancel 🥱',
                                'error'
                            )
                        }
                    })
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        'Cancelled',
                        'Deletion Cancel 🥱',
                        'error'
                    )
                }
            })

        };


        $(document).ready(function () {
            $('input[name=position]').change(function () {
                var value = $(this).val();
                var id = $(this).parent().parent().find("input[name=idss]").val();
                $url = "{{ route('superadmin.store.category.updateposition') }}";
                $.get($url, {
                    value: value,
                    id: id
                }, function (data) {
                    window.location.reload();
                    // $('#testimonials').load(location.href + ' .testi');
                });
            });
        });
    </script>








    <script>
        // $('.icon').iconpicker();
        $('.action-destroy').on('click', function () {
            $.iconpicker.batch('.icp.iconpicker-element', 'destroy');
        });
        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                $url = "{{ route('superadmin.store.category.changecatstatus') }}";
                var value = $(this).val();
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {
                    value: value,
                    id: id
                }, function (data) {
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
                    // alert(valuesArray);
                    console.log(valuesArray);
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

        function exportCategory(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                // select: {
                //     style: 'multi'
                // }
            });
        });
    </script>
@endpush
