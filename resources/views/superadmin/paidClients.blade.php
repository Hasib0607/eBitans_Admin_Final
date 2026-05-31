@extends('admin.layouts.main')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.share.client-top-nav')
        <div class="container-fluid mt-2" id="toplist">
            <div class="row">
                <div class="col-md-8">
                    <h4>All Clients</h4>
                </div>
                <div class="col-4" style="text-align:end">
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
                                <div class="col-md-3">
                                    <form method="get" action="{{ route('admin.paidClients') }}" class="row">
                                        <div class="col-md-8" style="padding-right:1px;">
                                            <select class='form-control' name="status" id="action">
                                                <option value="select">Select Option</option>
                                                <option value="active">Active</option>
                                                <option value="deactive">Deactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" style="padding-left:0px;">
                                            <button type="submit" class="btn btn-primary">Apply</button>
                                        </div>
                                    </form>
                                </div>
                                <form action="{{ route('admin.paidClients') }}" method="get" class="col-md-7 row">
                                    <div class="col-md-1 text-end mt-1">
                                        <label for="formdate">From Date</label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="date" name="formdate" id="formdate" value="{{ $from ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-1 text-end mt-1">
                                        <label for="todate">To Date</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="enddate" id="todate" value="{{ $to ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-2 filterbtns">
                                        <button type="submit" class="btn btn-info filterbtn"
                                                style="background-color: #7b809a ">Filter
                                        </button>
                                    </div>
                                </form>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               aria-label="Dollar amount (with dot and two decimal places)"
                                               id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                            {{--<input type="checkbox" id="idSearch">--}}
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="taskfilterresultTable">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="1%">#</th>
                                        @if (Auth::user()->type == 'superadmin')
                                            <th><input type="checkbox" name="ids" id="checkedAll"></th>
                                        @endif

                                        <th>Name</th>
                                        <th>Follow-Up</th>
                                        <th>Store Name</th>
                                        <th>Id</th>
                                        <!--<th width="30%">Email</th>-->
                                        {{-- <th width="20%">Phone</th> --}}
                                        <th>Comment</th>
                                        <th>Plan</th>
                                        <th>Active Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($paidClients as $key => $client)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>{{ ($paidClients->currentPage() - 1) * $paidClients->perPage() + $loop->iteration }}</td>
                                            @if (Auth::user()->type == 'superadmin')
                                                <td>
                                                    <input type="checkbox" name="selectedid"
                                                           value="{{ $client->getUser->id ?? 0 }}" id="id"
                                                           class="checkSingle">
                                                </td>
                                            @endif

                                            <td>
                                                <h6>{{ $client->getUser->name ?? 'Empty' }}</h6>
                                                <strong style="color:green; font-size:14px">Paid</strong>
                                            </td>


                                            <td>
                                                    <?php
                                                    $comments = DB::table('client_activitie_comments')
                                                        ->where('store_id', $client->id ?? '')
                                                        ->orderBy('updated_at', 'DESC')
                                                        ->get();
                                                    ?>

                                                @if ($comments)
                                                    <!-- Modal -->
                                                    <div class="modal fade"
                                                         id="AnalyticesCommentModal{{ $key }}" tabindex="-1"
                                                         aria-labelledby="exampleModalLabelClient" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content" style="text-align: left;">
                                                                <div class="modal-header"
                                                                     style="background-color: black; color:wheat;padding: 6px 25px 0px 25px;">
                                                                    <h5 class="modal-title"
                                                                        id="exampleModalLabelClient">
                                                                        <a href="http://{{ $client->url ?? 'Unauthorized' }}"
                                                                           target="_blank">
                                                                            {{ $client->name ?? 'Unauthorized' }}
                                                                        </a>
                                                                        <span style="font-size: 14px;">
                                                                                ({{ $client->getUser->name ?? 'Name not found' }}
                                                                                -{{ $client->getUser->id ?? '' }})
                                                                            </span>

                                                                        <p>
                                                                            @if(isset($client->getUser->phone))
                                                                                <a href="https://wa.me/88{{ $client->getUser->phone }}"
                                                                                   target="_blank"
                                                                                   style="text-decoration: none;">
                                                                                    {{ $client->getUser->phone ?? 'Phone not found' }}
                                                                                </a>
                                                                            @endif
                                                                        </p>
                                                                    </h5>

                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>


                                                                <div class="modal-body">
                                                                    @if (!empty($comments))
                                                                        <form
                                                                            id="clientComment{{ $client->getUser->id ?? 0 }}"
                                                                            class="p-3"
                                                                            style="border: 1px dashed crimson;"
                                                                            action="{{ route('superadmin.clients.activities.comments') }}"
                                                                            method="POST">
                                                                            @csrf

                                                                            <div class="col-12">
                                                                                <input type="hidden" name="user_id"
                                                                                       value="{{ $client->getUser->id ?? 'empty' }}">
                                                                                <input type="hidden" name="store_id"
                                                                                       value="{{ $client->id ?? null }}">
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <label
                                                                                        for="clientStatus{{ $client->getUser->id ?? 0 }}">
                                                                                        Client Status </label>
                                                                                    <select name="clientStatus"
                                                                                            id="clientStatus{{ $client->getUser->id ?? 0 }}"
                                                                                            required
                                                                                            class="form-control">
                                                                                        <option selected disabled>Select
                                                                                            Client Status
                                                                                        </option>
                                                                                        <option value="Positive">
                                                                                            Positive
                                                                                        </option>
                                                                                        <option value="thinking">
                                                                                            Thinking
                                                                                        </option>
                                                                                        <option value="No Response">No
                                                                                            Response
                                                                                        </option>
                                                                                        <option value="Interested">
                                                                                            Interested
                                                                                        </option>
                                                                                        <option value="Maybe">Maybe
                                                                                        </option>
                                                                                        <option value="No interested">
                                                                                            Not Interested
                                                                                        </option>
                                                                                        <option value="Freelancer">
                                                                                            Freelancer
                                                                                        </option>
                                                                                        <option value="tut">Tut tut
                                                                                        </option>
                                                                                    </select>
                                                                                </div>

                                                                                <div class="col-md-6 mt-4">
                                                                                    <label for="followUpData">Follow-Up
                                                                                        Data</label>
                                                                                    <input type="date"
                                                                                           class="form-control"
                                                                                           name="followUpData"
                                                                                           id="followUpData" required>
                                                                                </div>
                                                                                <div class="col-md-6 mt-4">
                                                                                    <label for="followUpTime">Follow-Up
                                                                                        Time</label>
                                                                                    <input type="time"
                                                                                           class="form-control"
                                                                                           name="followUpTime"
                                                                                           id="followUpTime">
                                                                                </div>

                                                                                <div class="col-md-12 mt-4">
                                                                                    <label for="comment"> Comments
                                                                                    </label>
                                                                                    <textarea name="comment"
                                                                                              class="form-control"
                                                                                              id="comment" required
                                                                                              cols="30"
                                                                                              rows="5"></textarea>
                                                                                </div>

                                                                                <div class="col-12">
                                                                                    <input type="hidden"
                                                                                           id="contVal{{ $client->getUser->id ?? 0 }}"
                                                                                           name=""
                                                                                           value="{{ $comments->count() }}">
                                                                                    <button type="button"
                                                                                            style="border: 1px dashed;"
                                                                                            class="btn btn-default mt-3">
                                                                                        Total
                                                                                        Follow-Up:
                                                                                        <span
                                                                                            id="conut{{ $client->getUser->id ?? 0 }}">{{ $comments->count() }}</span>
                                                                                    </button>

                                                                                    <button type="submit"
                                                                                            id="submitBtn{{ $client->getUser->id ?? 0 }}"
                                                                                            onclick="SubMitFrom({{ $client->getUser->id ?? 0 }})"
                                                                                            style="float: right;"
                                                                                            class="btn btn-info mt-3">
                                                                                        Comment
                                                                                    </button>

                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    @else
                                                                        <h2>There is no Comments here yet</h2>
                                                                    @endif

                                                                    <div id="resCmt{{ $client->getUser->id ?? 0 }}"
                                                                         class="row px-3">
                                                                        @foreach ($comments as $item)
                                                                            <div class="col-md-12 mt-3"
                                                                                 style="border: 1px dashed lightseagreen;padding: 10px">
                                                                                <h4 class="">
                                                                                    {{ $item->short_comment }} <span
                                                                                        style="float: right;font-size: 16px;font-weight: 500;">{{ date('d-m-Y, h:i:s A', strtotime($item->created_at ?? '10:00:00')) }}</span>
                                                                                </h4>
                                                                                <p class="m-0">
                                                                                    <strong>Next Follow Up:</strong>
                                                                                    <br>
                                                                                    {{ date('d-m-Y', strtotime($item->follow_up_date ?? '2000-01-01')) }}
                                                                                    ,
                                                                                    {{ date('h:i:s A', strtotime($item->follow_up_time ?? '10:00:00')) }}
                                                                                </p>
                                                                                <p class="m-0">
                                                                                    <strong>Comment:</strong> <br>
                                                                                    {{ $item->comment }}
                                                                                </p>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!----Modal End---->
                                                @endif

                                                <a style="background-color: {{ $comments != '[]' ? '' : 'red' }}"
                                                   href="javascript:void(0)" data-bs-toggle="modal"
                                                   class="btn btn-info btn-sm"
                                                   data-bs-target="#AnalyticesCommentModal{{ $key }}"
                                                   id="viewaddons" data-id="{{ $client->getUser->id ?? 0 }}">
                                                    Follow-Up
                                                </a>
                                            </td>


                                                <?php
                                                $customer = DB::table('customers')
                                                    ->where('uid', $client->getUser->id ?? 0)
                                                    ->first();

                                                $str = DB::table('stores')
                                                    ->where('id', $customer->active_store ?? 0)
                                                    ->first();
                                                $totalSotre = DB::table('stores')
                                                    ->where('user_id', $client->getUser->id ?? 0)
                                                    ->count();
                                                ?>


                                            <td>
                                                <a style="display: block;font-size: 14px; color:{{ $str->name ?? '#ff5733;' }}"
                                                   href="http://{{ $str->url ?? '#' }}" target="_blank"
                                                   rel="noopener noreferrer">
                                                    <strong style="font-size: 10px;"> {{ $totalSotre }} </strong> -
                                                    {{ $client->name ?? 'Store is not built yet' }}
                                                    <strong
                                                        style="font-size: 9px; color: {{ $str->name ?? '' != '' ? 'green' : '#ff5733;' }}">{{ $str->name ?? '' != '' ? 'Active' : 'Inactive' }}</strong>

                                                </a>
                                                <span style="font-weight: 900;">
                                                    @if(isset($client->getUser->phone))
                                                        <a href="https://wa.me/88{{ $client->getUser->phone }}"
                                                           target="_blank"
                                                           style="text-decoration: none;">
                                                                                    {{ $client->getUser->phone ?? '' }}
                                                                                </a>
                                                    @endif
                                                    </span>
                                            </td>
                                            <td>{{ $client->getUser->id ?? '' }}</td>
                                                <?php
                                                $cus = DB::table('customers')
                                                    ->where('uid', $client->getUser->id ?? '')
                                                    ->first();
                                                ?>
                                            <td id="okCommnet">
                                                    <textarea name="comment" class="form-control"
                                                              id="comment{{ $client->getUser->id ?? 0 }}"
                                                              onchange="okComment({{ $client->getUser->id ?? 0 }})"
                                                              cols="20" rows=""
                                                              placeholder="Enter Your Comment">{{ $client->getUser->comment ?? 0 }}</textarea>
                                            </td>
                                            <td>
                                                <h6 class="m-0">{{ $client->getPlan->name ?? 'empty' }}</h6>
                                            </td>
                                            <td>
                                                <p class="m-0">
                                                    {{ date('j M, Y', strtotime($client->purchase_date ?? '2000-01-01')) }}
                                                </p>

                                                <span
                                                    style="color:red;font-size: 14px;">{{ date('j M, Y', strtotime($client->expiry_date ?? '2000-01-01')) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ URL::to('/') }}/client/view/{{ $client->getUser->id ?? 0 }}"
                                                   class="btn btn-info" target="_blank">View</a>

                                                @if (Auth::user()->type == 'superadmin')
                                                    &nbsp;&nbsp;
                                                    <a href="#"
                                                       onclick="deleteStore({{ $client->getUser->id ?? 0 }})"
                                                       class="btn btn-danger">
                                                        Delete
                                                    </a>
                                                @endif


                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div style="text-align: center;">
                                    {!! $paidClients->appends(['formdate' => request('formdate'), 'enddate' => request('enddate')])->links() !!}
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
        const paidClientsData = @json($paidClientsExport);
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
                var value = $(this).val();
                // var idSearch = $("#idSearch").val();
                // alert(idSearch);
                //     if($("#idSearch").is(":checked")){
                //     console.log("Checkbox is checked.");
                // }


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


        function download_table_as_csv(table_id, separator = ',') {
            // Select rows from table_id
            var rows = document.querySelectorAll('#' + table_id + ' tr');

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
