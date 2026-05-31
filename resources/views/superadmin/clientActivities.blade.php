@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
    {{-- <style>
        #map {
            height: 300px;
            border: 1px solid #000;
        }
    </style> --}}
@endpush
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.share.client-top-nav')
        <div class="row card mb-4">
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form class="row" action="{{ route('superadmin.clients.activities.byDate') }}"
                                  method="post">
                                @csrf
                                <div class="col-md-1 text-end mt-1">
                                    <label for="formdate">From Date</label>
                                </div>

                                <div class="col-md-2">
                                    <input type="date" name="formdate" id="formdate" value="{{ $from ?? '' }}"
                                           class="form-control" required pattern="\d{4}-\d{2}-\d{2}">
                                </div>
                                <div class="col-md-1 text-end mt-1">
                                    <label for="todate">To Date</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="enddate" id="todate" value="{{ $to ?? '' }}"
                                           class="form-control" required pattern="\d{4}-\d{2}-\d{2}">
                                </div>
                                <div class="col-md-1 filterbtns">
                                    <button type="submit" class="btn btn-info filterbtn"
                                            style="background-color: #7b809a ">Filter
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 p-4">
                <table id="client-activities" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <th>Vistor</th>
                        <th>Details</th>
                        <th>FollowUp</th>
                        <th>Follow-Up Date, Time</th>
                        <th>Comment</th>
                        <th>Url</th>
                        <th>No of Pages / Visite</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>


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

    {{--<script>
        $(document).ready(function() {
            $('#client-activities').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, ':visible']
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 5]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>--}}

    <script>
        $(document).ready(function () {
            $('#client-activities').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.clients.activities.data') }}",  // Your data endpoint
                    type: 'GET',
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                        console.log('Server Response:', jqXHR.responseText);
                    }
                },
                columns: [
                    {data: 'id'},
                    {
                        data: 'user_id',
                        render: function (data, type, row) {
                            // return row.user_id;
                            return `<div>
                                        <a href="http://${row.get_store?.url ?? 'Unauthorized'}" target="_blank">
                                            ${row.get_store?.name ?? 'Unauthorized'}
                                        </a>
                                        (${row.get_user?.name ?? 'Name not found'} - ${row.get_user?.id ?? ''})
                                        <br>
                                        <a href="https://wa.me/88${row.get_user?.phone ?? 'Phone not found'}" target="_blank"
                                           style="text-decoration: none;">
                                            ${row.get_user?.phone ?? 'Phone not found'}
                                        </a>
                                    </div>`
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            const body = row.get_store?.activity_comments.length ? `
                            <form id="clientComment${row.id}" onsubmit="event.preventDefault();" class="p-3"
                               style="border: 1px dashed crimson;"
                               action="/clients-activities/comments"
                               method="POST">
                                @csrf
                            <div class="col-12">
                                <input type="hidden" name="user_id" value="${row.get_user?.id ?? ''}">
                                <input type="hidden" name="store_id"
                                       value="${row.store_id}">
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="clientStatus${row.id}"> Client Status </label>
                                    <select name="clientStatus" id="clientStatus${row.id}" required
                                            class="form-control">
                                        <option selected disabled>Select Client Status
                                        </option>
                                        <option value="Positive">Positive</option>
                                        <option value="thinking">Thinking</option>
                                        <option value="No Response">No Response</option>
                                        <option value="Interested">Interested</option>
                                        <option value="Maybe">Maybe</option>
                                        <option value="No interested">Not Interested</option>
                                        <option value="Freelancer">Freelancer</option>
                                        <option value="tut">Tut tut</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <label for="followUpData">Follow-Up Data</label>
                                    <input type="date" class="form-control"
                                           name="followUpData" id="followUpData">
                                </div>
                                <div class="col-md-6 mt-4">
                                    <label for="followUpTime">Follow-Up Time</label>
                                    <input type="time" class="form-control"
                                           name="followUpTime" id="followUpTime">
                                </div>

                                <div class="col-md-12 mt-4">
                                    <label for="comment"> Comments </label>
                                    <textarea name="comment" class="form-control" id="comment${row.id}" required cols="30" rows="5"></textarea>
                                </div>

                                <div class="col-12">
                                    <input type="hidden" id="contVal${row.id}" name="" value="{ $comments->count() }">
                                        <button type="button" style="border: 1px dashed;"
                                                class="btn btn-default mt-3">Total Follow-Up:
                                            <span id="conut${row.id}">${row.get_store?.activity_comments.lenght}</span>
                                        </button>

                                        <button type="submit" id="submitBtn${row.id}" onclick="SubMitFrom(${row.id})"
                                                style="float: right;"
                                                class="btn btn-info mt-3">Comment</button>

                                </div>
                            </div>
                        </form>` : `<h2>There is no Comments here yet</h2>`;
                            const view = row.get_store?.activity_comments.map(item => {
                                return `
                                <div class="col-md-12 mt-3" style="border: 1px dashed lightseagreen;padding: 10px">
                                    <h4 class="">${item.short_comment}
                                        <span
                                            style="float: right;font-size: 16px;font-weight: 500;">
                                            {{ date('d-m-Y, h:i:s A', strtotime('item.created_at' ?? '10:00:00')) }}
                                <br>
                                <small style="float: right;"><strong>-- ${item.comment_by ?? 'Kabir'}</strong></small>
                                        </span>
                                    </h4>
                                    <p class="m-0">
                                        <strong>Next Follow Up:</strong> <br>
                                        {{ date('d-m-Y', strtotime('item.follow_up_date' ?? '2000-01-01')) }},
                                        {{ date('h:i:s A', strtotime('item.follow_up_time' ?? '10:00:00')) }}
                                </p>
                                <p class="m-0">
                                    <strong>Comment:</strong> <br>
                                        ${item.comment}
                                    </p>
                                </div>`
                            })
                            return `<div>
                                        <div class="modal fade" id="AnalyticesCommentModal${row.id}" tabindex="-1"
                                        aria-labelledby="client-activitiesModalLabelClient" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header"
                                                    style="background-color: black; color:wheat;padding: 6px 25px 0px 25px;">
                                                    <h5 class="modal-title" id="client-activitiesModalLabelClient">
                                                        <a href="http://${row.get_store?.name ?? 'Unauthorized'}"
                                                            target="_blank">
                                                            ${row.get_store?.name ?? 'Unauthorized'}
                                                        </a>
                                                        <span style="font-size: 14px;">
                                                            ((${row.get_user?.name ?? 'Name not found'} - ${row.get_user?.id ?? ''}))
                                                        </span>

                                                        <p>${row.get_user?.phone ?? 'Phone not found'}</p>
                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>


                                                <div class="modal-body">
                                                    ${body}
                                                    <div id="resCmt${row.id}" class="row px-3">
                                                        ${view}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a style="pointer-events: ${(row.get_store?.name ?? '') != '' ? '' : 'none'}" href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-info btn-sm"
                                        data-bs-target="#AnalyticesCommentModal${row.id}" id="viewaddons"
                                        data-id="${row.id}">
                                        Comments
                                    </a>
                                    </div>`
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `<td id="shortCmt${row.id}">${row.get_store?.activity_comments[0]?.short_comment ?? 'follow Up Short Commnets'}</td>`;
                        }
                    },
                    {
                        data: 'date',
                        render: function (data, type, row) {
                            const current_date = moment().format('DD-MM-YYYY');
                            const comments = row.get_store?.activity_comments || [];
                            const comment = comments[0] || {};

                            // Fallback date and time
                            const fallbackDate = '0000-00-00';
                            const fallbackTime = '00:00:00';

                            // Format current time using moment.js
                            const currentTime = moment().format('DD-MM-YYYY'); // My time equivalent in JS

                            // Format the follow-up date and time (handle nulls like PHP's `??`)
                            const followUpDate = comment.follow_up_date ? moment(comment.follow_up_date).format('DD-MM-YYYY') : fallbackDate;
                            const followUpTime = comment.follow_up_time ? moment(comment.follow_up_time, 'HH:mm:ss').format('hh:mm:ss A') : moment(fallbackTime, 'HH:mm:ss').format('hh:mm:ss A');

                            // Date comparison logic
                            let outputHTML = '';

                            if (comment.follow_up_date || followUpDate === moment(comment.follow_up_date).format('DD-MM-YYYY')) {
                                outputHTML = `<span style="background-color: rgba(230, 247, 130, 0.308);color: black;" class="p-2">
                                    ${comment.follow_up_date || 'Follow Up Date, Time'}, ${followUpDate} ${followUpTime}
                                    </span>`;
                            } else if (moment(currentTime, 'DD-MM-YYYY').isAfter(moment(followUpDate, 'DD-MM-YYYY'))) {
                                outputHTML = `<span style="background-color: red;color: white;" class="p-2">
                                    ${comment.follow_up_date || 'Follow Up Date, Time'}, ${followUpDate} ${followUpTime}
                                    </span>`;
                            } else if (moment(currentTime, 'DD-MM-YYYY').isBefore(moment(followUpDate, 'DD-MM-YYYY'))) {
                                outputHTML = `<span style="background-color: rgb(238, 250, 71);color: black;" class="p-2">
                                    ${comment.follow_up_date || 'Follow Up Date, Time'}, ${followUpDate} ${followUpTime}
                                    </span>`;
                            } else if (moment(currentTime, 'DD-MM-YYYY').isSame(moment(followUpDate, 'DD-MM-YYYY'))) {
                                outputHTML = `<span style="background-color: #148fa9;color: white;" class="p-2">
                                    ${comment.follow_up_date || 'Follow Up Date, Time'}, ${followUpDate} ${followUpTime}
                                    </span>`;
                            } else {
                                outputHTML = `<span class="p-2">
                                    ${comment.follow_up_date || 'Follow Up Date, Time'}, ${followUpDate} ${followUpTime}
                                    </span>`;
                            }
                            return outputHTML;
                        }
                    },
                    {
                        data: 'status',
                        render: function (data, type, row) {
                            return `
                            <td id="okCommnet">
                                <textarea name="comment" class="form-control" id="comment${row.get_user?.id ?? 0}"
                                              onchange="okComment(${row.get_user?.id ?? 0})" cols="20" rows=""
                                              placeholder="Enter Your Comment">${row.get_user?.activity_comments?.comment ?? ''}</textarea>
                            </td>`
                        }
                    },
                    {
                        data: 'get_admin_analytics',
                        render: function (data, type, row) {
                            // First check if get_admin_analytics exists and is an array
                            if (!row.get_admin_analytics || !Array.isArray(row.get_admin_analytics)) {
                                return '<h2>There is no page here yet</h2>';
                            }
                            let table = '<h2>There is no page here yet</h2>';
                            let tableBody = ``;
                            row.get_admin_analytics.map((item, index) => {
                                tableBody += `
                                    <tr>
                                        <td>
                                            ${index + 1}
                                        </td>
                                        <td>
                                            ${item.number_of_visits}
                                        </td>
                                        <td>
                                            ${item.url}
                                        </td>
                                        <td>
                                            ${moment(item.updated_at).format('hh:mm:ss A - DD-MM-YYYY')}
                                        </td>
                                    </tr>`
                            })
                            if (row?.get_admin_analytics?.length) {
                                table = `
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Visite</th>
                                            <th>URL</th>
                                            <th>Updated At</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            ${
                                    tableBody
                                }
                                        </tbody>
                                    </table>
                                </div>`
                            }
                            return `
                            <div class="modal fade" id="Analyticesclient-activitiesModal${row.id}"
                                  tabindex="-1" aria-labelledby="client-activitiesModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="client-activitiesModalLabel">
                                                        <span style="color: orange"><a
                                                            href="http://${row.get_store?.url ?? 'Unauthorized'}"
                                                            target="_blank">
                                                                ${row.get_store?.name ?? 'Unauthorized'}
                                                            </a></span> Visite url
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ${table}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!----Modal End---->
                            <a href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-info btn-sm"
                            data-bs-target="#Analyticesclient-activitiesModal${row.id}"" id="viewaddons"
                            data-id="${row.id}"">
                                View
                            </a>`
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            const noPage = row?.get_admin_analytics.reduce((prev, current) => {
                                return prev + current.number_of_visits;
                            }, 0)
                            return `<td>${row?.get_admin_analytics?.length} / ${noPage}</td>`;
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `<td>${row.updated_at ? moment(row.updated_at).format('hh:mm A - DD-MM-YYYY') : ''}</td>`;
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `<td><a href="/client/view/${row.user_id}"
                                       class="btn btn-info btn-sm" target="_blank">View</a></td>`;
                        }
                    }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print', 'colvis'
                ],
                paging: true,
                searching: true,
                ordering: true, // Enable ordering
                info: true,
                lengthChange: true,
                pageLength: 10
            });
        });

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

            let status = $('#clientStatus' + vl).val();
            let comment = $('#comment' + vl).val();

            if (status != '' && comment != '') {
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
            } else {
                alert('Please Select client Status and client Comment');
            }
        }
    </script>
@endpush
