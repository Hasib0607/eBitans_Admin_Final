@extends('admin.layouts.main')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.share.report.nav')

        <div class="container-fluid mt-2" id="toplist">
            <div class="row">
                <div class="col-md-8">
                    <h4>Addon Sell Report</h4>
                </div>
            </div>

            <div class="row mt-1 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('addonSellReport') }}" method="get" class="row g-2 align-items-center">
                                <div class="col-md-2">
                                    <select class="form-control" name="type" id="action">
                                        <option value="all" {{ isset($type) && $type == "all" ? 'selected' : '' }}>All
                                        </option>
                                        <option value="addon" {{ isset($type) && $type == "addon" ? 'selected' : '' }}>Addon
                                        </option>
                                        <option value="package" {{ isset($type) && $type == "package" ? 'selected' : '' }}>
                                            Package</option>
                                        <option value="module" {{ isset($type) && $type == "module" ? 'selected' : '' }}>
                                            Module</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select class="form-control" name="due_status" id="due_status">
                                        <option value="">All Payment Status</option>
                                        <option value="paid" {{ isset($due_status) && $due_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="partial_due" {{ isset($due_status) && $due_status === 'partial_due' ? 'selected' : '' }}>Partial Due</option>
                                        <option value="due" {{ isset($due_status) && $due_status === 'due' ? 'selected' : '' }}>Due</option>
                                        <option value="cleared" {{ isset($due_status) && $due_status === 'cleared' ? 'selected' : '' }}>Cleared</option>
                                    </select>
                                </div>

                                <div class="col-md-1 text-end">
                                    <label for="from_date" class="mb-0">From</label>
                                </div>

                                <div class="col-md-2">
                                    <input type="date" name="from_date" id="from_date" value="{{ $from_date ?? '' }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-1 text-end">
                                    <label for="to_date" class="mb-0">To</label>
                                </div>

                                <div class="col-md-2">
                                    <input type="date" name="to_date" id="to_date" value="{{ $to_date ?? '' }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-1">
                                    <div class="input-group">
                                        <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                                            class="form-control" placeholder="Search">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100 mb-0">Filter</button>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif

                            <div class="table-responsive" id="taskfilterresult">
                                <table class="table" width="100%">
                                    <thead>
                                        <tr>
                                            @if($type == "addon" || $type == "package")
                                                <th width="1%">SL</th>
                                                <th>Name</th>
                                                <th>Number of sale</th>
                                                <th>Total Amount</th>
                                            @elseif($type == "module")
                                                <th width="1%">SL</th>
                                                <th>Name</th>
                                                <th>Store</th>
                                                <th>Module</th>
                                                <th>Price</th>
                                                <th>Date</th>
                                            @else
                                                <th width="1%">SL</th>
                                                <th>Name</th>
                                                <th>Store</th>
                                                <th>Addons</th>
                                                <th>Packages</th>
                                                <th>Total</th>
                                                <th>Paid</th>
                                                <th>Due</th>
                                                <th>Comment</th>
                                                <th>Date</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp

                                        @if($type == "package")
                                            @if(isset($extraData) && count($extraData))
                                                @foreach ($extraData as $item)
                                                    @if(isset($item->total_price))
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $item->name ?? '' }}</td>
                                                            <td>{{ $item->total_sales ?? '' }}</td>
                                                            <td>{{ $item->total_price ?? '' }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">No Record Found</td>
                                                </tr>
                                            @endif

                                        @elseif($type == "addon")
                                            @if(isset($extraData) && count($extraData))
                                                @foreach ($extraData as $item)
                                                    @if(isset($item['total_price']))
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $item['name'] ?? '' }}</td>
                                                            <td>{{ $item['total_sales'] ?? '' }}</td>
                                                            <td>{{ $item['total_price'] ?? '' }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">No Record Found</td>
                                                </tr>
                                            @endif

                                        @elseif($type == "module")
                                            @if(isset($moduleList) && count($moduleList))
                                                @foreach ($moduleList as $item)
                                                    <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                        <td>{{ ($moduleList->currentPage() - 1) * $moduleList->perPage() + $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            <h6>{{ $item->store->user->name ?? 'Empty' }}</h6>
                                                            @if(isset($item->store->user->phone) && !empty($item->store->user->phone))
                                                                <p style="font-weight: 900;margin-bottom: 0">
                                                                    <a href="https://wa.me/88{{ $item->store->user->phone }}"
                                                                        target="_blank" style="text-decoration: none;">
                                                                        {{ $item->store->user->phone ?? '' }}
                                                                    </a>
                                                                </p>
                                                            @endif
                                                            @if(isset($item->store->user->email) && !empty($item->store->user->email))
                                                                <p style="font-weight: 900;margin-bottom: 0">
                                                                    {{ $item->store->user->email ?? '' }}
                                                                </p>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a style="display: block;font-size: 14px; color:{{ $item->store->name ?? '#ff5733' }}"
                                                                href="http://{{ $item->store->url ?? '#' }}" target="_blank"
                                                                rel="noopener noreferrer">
                                                                {{ $item->store->name ?? 'Store is not built yet' }}
                                                                <strong
                                                                    style="font-size: 9px; color: {{ $item->store->name ?? '' != '' ? 'green' : '#ff5733' }}">
                                                                    {{ $item->store->name ?? '' != '' ? 'Active' : 'Inactive' }}
                                                                </strong>
                                                            </a>
                                                            User ID: {{ $item->store->user_id ?? "" }}
                                                        </td>
                                                        <td>{{ $item->module->name ?? "" }}</td>
                                                        <td>{{ $item->price ?? '' }}</td>
                                                        <td>
                                                            <p class="m-0">
                                                                {{ date('j M, Y h:i:s A', strtotime($item->created_at ?? '2000-01-01')) }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6">No Record Found</td>
                                                </tr>
                                            @endif

                                        @else
                                            @if(isset($addonsList) && count($addonsList))
                                                @foreach ($addonsList as $item)
                                                    <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                        <td>{{ ($addonsList->currentPage() - 1) * $addonsList->perPage() + $loop->iteration }}
                                                        </td>

                                                        <td>
                                                            <h6>{{ $item->user->name ?? 'Empty' }}</h6>
                                                            @if(isset($item->user->phone) && !empty($item->user->phone))
                                                                <p style="font-weight: 900;margin-bottom: 0">
                                                                    <a href="https://wa.me/88{{ $item->user->phone }}" target="_blank"
                                                                        style="text-decoration: none;">
                                                                        {{ $item->user->phone ?? '' }}
                                                                    </a>
                                                                </p>
                                                            @endif
                                                            @if(isset($item->user->email) && !empty($item->user->email))
                                                                <p style="font-weight: 900;margin-bottom: 0">
                                                                    {{ $item->user->email ?? '' }}
                                                                </p>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <a style="display: block;font-size: 14px; color:{{ $item->store->name ?? '#ff5733' }}"
                                                                href="http://{{ $item->store->url ?? '#' }}" target="_blank"
                                                                rel="noopener noreferrer">
                                                                {{ $item->store->name ?? 'Store is not built yet' }}
                                                                <strong
                                                                    style="font-size: 9px; color: {{ $item->store->name ?? '' != '' ? 'green' : '#ff5733' }}">
                                                                    {{ $item->store->name ?? '' != '' ? 'Active' : 'Inactive' }}
                                                                </strong>
                                                            </a>
                                                            User ID: {{ $item->user_id }}
                                                        </td>

                                                        <td>
                                                            @if(isset($item->addons) && is_array($item->addons) && count($item->addons))
                                                                @foreach($item->addons as $addons)
                                                                    @if(isset($addons['title']))
                                                                        <p class="mb-1">Addon: {{ $addons['title'] ?? "" }}</p>
                                                                    @endif
                                                                    @if(isset($addons['price']))
                                                                        <p class="mb-1">Price: {{ $addons['price'] ?? "" }}</p>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @php
                                                                $package = isset($item->package) ? json_decode($item->package) : null;
                                                            @endphp
                                                            @if(isset($package))
                                                                @if(isset($package->name))
                                                                    <p class="mb-1">Package: {{ $package->name ?? "" }}</p>
                                                                @endif
                                                                @if(isset($package->price))
                                                                    <p class="mb-1">Price: {{ $package->price ?? "" }}</p>
                                                                @endif
                                                            @endif
                                                        </td>

                                                        <td>{{ $item->total ?? 0 }}</td>
                                                        <td>{{ $item->paid_amount ?? 0 }}</td>
                                                        <td>{{ $item->due_amount ?? 0 }}</td>
                                                        <td>{{ $item->manual_discount_comment ?? '' }}</td>

                                                        <td>
                                                            <p class="m-0">
                                                                {{ date('j M, Y h:i:s A', strtotime($item->created_at ?? '2000-01-01')) }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10">No Record Found</td>
                                                </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>

                                @if($type == "all" || $type == "module")
                                    <div class="d-flex mt-4 mb-4 justify-content-between align-items-start flex-wrap gap-3">
                                        <div class="d-flex flex-column">
                                            <p class="text-bold mb-1">
                                                Page Total Amount:
                                                <span class="px-2" style="color: #ff5733;">{{ $pageTotalAmount ?? 0 }}</span> TK
                                            </p>
                                            <p class="text-bold mb-1">
                                                Page Paid Amount:
                                                <span class="px-2" style="color: #28a745;">{{ $pagePaidAmount ?? 0 }}</span> TK
                                            </p>
                                            <p class="text-bold mb-1">
                                                Page Due Amount:
                                                <span class="px-2" style="color: #dc3545;">{{ $pageDueAmount ?? 0 }}</span> TK
                                            </p>

                                            <hr class="my-2">

                                            <p class="text-bold mb-1">
                                                Total Amount:
                                                <span class="px-2" style="color: #ff5733;">{{ $totalAmount ?? 0 }}</span> TK
                                            </p>
                                            <p class="text-bold mb-1">
                                                Total Paid Amount:
                                                <span class="px-2" style="color: #28a745;">{{ $totalPaidAmount ?? 0 }}</span> TK
                                            </p>
                                            <p class="text-bold mb-1">
                                                Total Due Amount:
                                                <span class="px-2" style="color: #dc3545;">{{ $totalDueAmount ?? 0 }}</span> TK
                                            </p>
                                        </div>

                                        @if($type == "module")
                                                                    {!! $moduleList->appends([
                                                'type' => request('type'),
                                                'search' => request('search'),
                                                'from_date' => request('from_date'),
                                                'to_date' => request('to_date'),
                                                'due_status' => request('due_status')
                                            ])->links() !!}
                                        @else
                                                                    {!! $addonsList->appends([
                                                'type' => request('type'),
                                                'search' => request('search'),
                                                'from_date' => request('from_date'),
                                                'to_date' => request('to_date'),
                                                'due_status' => request('due_status')
                                            ])->links() !!}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <form action="{{ route('admin.deleteclient') }}" id="deleteSubmit" method="POST">
            @csrf
            <input type="hidden" id="DeleteID" name="id" value="">
        </form>
    </main>
@endsection

@push('scripts')
    <script>
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
                                            $('#deleteSubmit').submit();
                                        } else if (result.dismiss === Swal.DismissReason.cancel) {
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
                            alert('Sorry Boro  ' + data.status);
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            swal.fire(
                                'Cancelled',
                                'Deletion Cancel 🥱',
                                'error'
                            )
                        }
                    })
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swal.fire(
                        'Cancelled',
                        'Deletion Cancel 🥱',
                        'error'
                    )
                }
            })
        };
    </script>

    <script>
        function okComment(id) {
            var text = $('#comment' + id).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.client.commnet') }}",
                data: {
                    id: id,
                    comment: text
                },
                success: function (data) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.success("Comment save");
                }
            });
        }
    </script>

    <script>
        function SubMitFrom(vl) {
            $('#shortCmt' + vl).html($('#clientStatus' + vl).val());
            $('#conut' + vl).html(parseFloat($('#contVal' + vl).val()) + 1);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#submitBtn' + vl).html('Please Wait...');
            $("#submitBtn" + vl).attr("disabled", true);

            $.ajax({
                url: "{{ route('superadmin.clients.activities.comments') }}",
                type: "POST",
                data: $('#clientComment' + vl).serialize(),
                success: function (response) {
                    $('#resCmt' + vl).html(response);
                    $('#follow' + vl).css("background-color", "#1a73e8");
                    $('#submitBtn' + vl).html('Submit');
                    $("#submitBtn" + vl).attr("disabled", false);

                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }

                    toastr.success("Comment save");
                    document.getElementById("clientComment" + vl).reset();
                }
            });
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
                        if (!this.checked) isAllChecked = 1;
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
                var value = $(this).val();

                if ($('#idSearch').is(':checked')) {
                    var idSearch = true;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'get',
                    url: "{{ route('admin.clients.search') }}",
                    data: {
                        idSearch: idSearch,
                        search: value
                    },
                    success: function (data) {
                        console.log(data);
                        $('#taskfilterresult').html(data);
                    }
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }

        const table = document.getElementById('taskfilterresult');
        const exportBtn = document.getElementById('export');

        if (exportBtn) {
            exportBtn.addEventListener('click', function () {
                const csv = toCsv(table);
                download(csv, 'download.csv');
            });
        }

        const toCsv = function (table) {
            const rows = table.querySelectorAll('tr');

            return [].slice
                .call(rows)
                .map(function (row) {
                    const cells = row.querySelectorAll('th,td');
                    return [].slice
                        .call(cells)
                        .map(function (cell) {
                            return cell.textContent;
                        })
                        .join(',');
                })
                .join('\n');
        };

        const download = function (text, fileName) {
            const link = document.createElement('a');
            link.setAttribute('href', `data:text/csv;charset=utf-8,${encodeURIComponent(text)}`);
            link.setAttribute('download', fileName);

            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        function download_table_as_csv(table_id, separator = ',') {
            var rows = document.querySelectorAll('table#' + table_id + ' tr');
            var csv = [];

            for (var i = 0; i < rows.length; i++) {
                var row = [],
                    cols = rows[i].querySelectorAll('td, th');

                for (var j = 0; j < cols.length; j++) {
                    var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
                    data = data.replace(/"/g, '""');
                    row.push('"' + data + '"');
                }

                csv.push(row.join(separator));
            }

            var csv_string = csv.join('\n');
            var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
            var link = document.createElement('a');

            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endpush