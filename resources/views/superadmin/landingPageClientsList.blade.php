@extends('admin.layouts.main')
@push('styles')
    <style>
        .colToText {
            width: 3% !important;
            padding: 0;
            flex: unset;
        }

        @media (max-width: 768px) {
            .colToText {
                width: 100% !important;
            }
        }
    </style>
@endpush
@section('content')
    <!-- The Modal -->
    <div class="modal fade" id="messageModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('superadmin.saveWhatsAppMessage') }}" method="post">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">What's App Custom Message</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                            <i class="fa fa-times" aria-hidden="true" style="color: #000;font-size: 20px;"></i>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" id="message" cols="10"
                                  rows="10">{{ $message->message ?? old("message") ?? "" }}</textarea>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-info">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.share.client-top-nav')


        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-8">
                    <h4>All Clients</h4>
                </div>
                <div class="col-4" style="text-align:end">
                    <button class="btn btn-dark" data-bs-toggle="modal"
                            data-bs-target="#messageModal">Custom Message
                    </button>
                    @if (Auth::user()->type == 'superadmin')
                        <a href="#" class="btn btn-primary"
                           onclick="download_data_as_csv();">Export</a>
                    @endif
                </div>
            </div>
            <div class="row mt-1 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <form action="{{ route('landingPageClientsList') }}" method="get" class="row">
                                    <div class="col-md-2" style="padding-right:1px;">
                                        <select class='form-control' name="website" id="website">
                                            <option value="" {{ isset($website) && $website == "" ? 'selected' : '' }}>
                                                Select
                                                Option
                                            </option>
                                            @if(isset($fromWebsite) && count($fromWebsite) > 0)
                                                @foreach($fromWebsite as $item)
                                                    <option
                                                        value="{{ $item ?? "" }}"
                                                        {{ isset($website) && $website == ($item ?? "") ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-2" style="padding-right:1px;">
                                        <select class='form-control' name="type" id="type">
                                            <option value="" {{ isset($type) && $type == "" ? 'selected' : '' }}>Select
                                                Option
                                            </option>
                                            <option
                                                value="0" {{ isset($type) && $type == "0" ? 'selected' : '' }}>
                                                Free Registration
                                            </option>
                                            <option
                                                value="1" {{ isset($type) && $type == "1" ? 'selected' : '' }}>
                                                Paid Registration
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <input type="date" name="from_date" id="from_date"
                                               value="{{ $from_date ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col colToText text-center mt-1">
                                        <label for="to_date">To</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="to_date" id="to_date" value="{{ $to_date ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                                                   class="form-control">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-1" style="padding-left:0px;">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="taskfilterresult">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="1%">SL</th>
                                        <th>Name</th>
                                        <th>Store Name</th>
                                        <th>Id</th>
                                        <th>Type</th>
                                        <th>Plan</th>
                                        <th>Active Date</th>
                                        <th>Store Create</th>
                                        <th>User Create</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($paidClients))
                                        @foreach ($paidClients as $key => $client)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($paidClients->currentPage() - 1) * $paidClients->perPage() + $loop->iteration }}</td>
                                                <td>
                                                    <h6>{{ $client->name ?? 'Empty' }}</h6>
                                                </td>
                                                <td>
                                                    <a style="display: block;font-size: 14px; color:{{ isset($client->getStore->name) ? '#344767' : '#ff5733' }}"
                                                       href="http://{{ $client->getStore->url ?? '#' }}" target="_blank"
                                                       rel="noopener noreferrer">
                                                        <strong
                                                            style="font-size: 10px;"> {{ $client->total_store_count ?? 0 }} </strong>
                                                        -
                                                        {{ $client->getStore->name ?? 'Store is not built yet' }}

                                                        @if(isset($client->getStore->name) && !empty($client->getStore->name))
                                                            <strong
                                                                style="font-size: 9px; color: green;">
                                                                {{ 'Active' }}
                                                            </strong>
                                                        @else
                                                            <strong
                                                                style="font-size: 9px; color: #ff5733;">
                                                                {{ 'Inactive' }}
                                                            </strong>
                                                        @endif
                                                    </a>
                                                    @if(isset($client->phone) && !empty($client->phone))
                                                        <p style="font-weight: 900;margin-bottom: 0">
                                                            <a href="https://wa.me/88{{ $client->phone }}{{ isset($message->message) ? "?text=" . $message->message : "" }}"
                                                               target="_blank"
                                                               style="text-decoration: none;">
                                                                {{ $client->phone ?? '' }}
                                                            </a>
                                                        </p>
                                                    @endif
                                                    @if(isset($client->email) && !empty($client->email))
                                                        <p style="font-weight: 900;margin-bottom: 0">
                                                            {{ $client->email ?? '' }}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td>{{ $client->id ?? 0 }}</td>
                                                <td>{{ $client->type ?? "" }}</td>
                                                <td>
                                                    <h6 class="m-0">{{ $client->getStore->getPlan->name ?? 'empty' }}</h6>
                                                </td>
                                                <td>
                                                    <p class="m-0">
                                                        {{ isset($client->getStore->purchase_date) ? date('j M, Y', strtotime($client->getStore->purchase_date)) : "" }}
                                                    </p>

                                                    <span
                                                        style="color:red;font-size: 14px;">{{ isset($client->getStore->expiry_date) ? date('j M, Y', strtotime($client->getStore->expiry_date)) : "" }}</span>
                                                </td>
                                                <td>
                                                    <p class="m-0">
                                                        {{ isset($client->getStore->created_at) ? date('j M, Y h:i:s A', strtotime($client->getStore->created_at)) : "" }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="m-0">
                                                        {{ isset($client->created_at) ? date('j M, Y h:i:s A', strtotime($client->created_at)) : "" }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <a href="{{ URL::to('/') }}/client/view/{{ $client->id ?? 0 }}"
                                                       class="btn btn-info" target="_blank">View</a>

                                                    @if (Auth::user()->type == 'superadmin')
                                                        &nbsp;&nbsp;
                                                        <a href="#"
                                                           onclick="deleteStore({{ $client->id ?? 0 }})"
                                                           class="btn btn-danger">
                                                            Delete
                                                        </a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                No Record Found
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="d-flex mt-4 mb-4 justify-content-between">
                                    <div class="d-flex flex-column">
                                        @if(isset($totalClients))
                                            <p class="text-bold" style="margin-bottom: 5px;">Total Record: <span
                                                    class="px-2"
                                                    style="color: #ff5733;">{{ $totalClients }}</span>
                                            </p>
                                        @endif
                                        @if(isset($activeCount))
                                            <p class="text-bold" style="margin-bottom: 5px;">Total Active: <span
                                                    class="px-2"
                                                    style="color: #ff5733;">{{ $activeCount }}</span></p>
                                        @endif
                                        @if(isset($inactiveCount))
                                            <p class="text-bold" style="margin-bottom: 5px;">Total Inactive: <span
                                                    class="px-2"
                                                    style="color: #ff5733;">{{ $inactiveCount }}</span></p>
                                        @endif
                                    </div>

                                    {!! $paidClients->appends(['website' => request('website'), 'type' => request('type'), 'search' => request('search'), 'from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- confirmation alert --}}
        <form action="{{ route('admin.deleteclient') }}" id="deleteSubmit" method="POST">
            @csrf
            <input type="hidden" id="DeleteID" name="id" value="">
        </form>
        {{-- confirmation alert end --}}
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
    </script>

    <script>
        const paidClientsData = @json($paidClientsExport);
    </script>

    <script>
        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }

        const table = document.getElementById('taskfilterresult');
        const exportBtn = document.getElementById('export');

        exportBtn.addEventListener('click', function () {
            // Export to csv
            const csv = toCsv(table);

            // Download it
            download(csv, 'download.csv');
        });
        const toCsv = function (table) {
            // Query all rows
            const rows = table.querySelectorAll('tr');

            return [].slice
                .call(rows)
                .map(function (row) {
                    // Query all cells
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

        // Quick and simple export target #table_id into a csv
        function download_table_as_csv(table_id, separator = ',') {
            // Select rows from table_id
            var rows = document.querySelectorAll('table#' + table_id + ' tr');
            // Construct csv
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var row = [],
                    cols = rows[i].querySelectorAll('td, th');
                for (var j = 0; j < cols.length; j++) {
                    // Clean innertext to remove multiple spaces and jumpline (break csv)
                    var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                    // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                    data = data.replace(/"/g, '""');
                    // Push escaped string
                    row.push('"' + data + '"');
                }
                csv.push(row.join(separator));
            }
            var csv_string = csv.join('\n');
            // Download it
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

        function download_data_as_csv(filename = 'export.csv', separator = ',') {
            let dataArray = paidClientsData;

            if (!Array.isArray(dataArray) || dataArray.length === 0) {
                alert("No data to export.");
                return;
            }

            // Extract headers
            const headers = Object.keys(dataArray[0]);
            const csvRows = [];

            // Add header row
            csvRows.push(headers.join(separator));

            // Add data rows
            for (const row of dataArray) {
                const values = headers.map(header => {
                    let cell = row[header] !== null ? row[header] : '';
                    cell = cell.toString().replace(/"/g, '""'); // Escape quotes
                    return `"${cell}"`;
                });
                csvRows.push(values.join(separator));
            }

            // Create CSV string
            const csvString = csvRows.join('\n');

            // Download
            const link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvString));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endpush
