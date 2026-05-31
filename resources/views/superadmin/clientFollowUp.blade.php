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
                            <div class="row">

                                <div class="col-md-1 text-end mt-1">
                                    <label for="formdate">From Date</label>
                                </div>

                                <div class="col-md-2">
                                    <form action="{{ route('superadmin.clients.activities.byDate') }}" method="post">
                                        @csrf
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
                                    </form>
                                </div>


                                <div class="col-md-1 text-end mt-1">
                                    <label for="followUp">Follow Up Date</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" onchange="followUp()" name="followUp" id="followUp"
                                           value="{{ $to ?? '' }}"
                                           class="form-control" required pattern="\d{4}-\d{2}-\d{2}">
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               aria-label="Dollar amount (with dot and two decimal places)"
                                               id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                            <input type="checkbox" id="idSearch">
                                            <i class="fa fa-search"></i></span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="findSearchResult" style="overflow: auto;" class="col-lg-12 col-md-12 col-sm-12 p-4">
                <table id="taskfilterresult" class="table" style="width:100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <th>Vistor</th>

                        <th>Details</th>
                        <th>FollowUp</th>
                        <th>Follow-Up Date, Time</th>
                        <th>Comment</th>
                        <th>Url</th>
                        <th style="cursor: pointer;" onclick="shortting()">No of Pages / Visite</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($analyticsInfo as $key => $traffic)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <a href="http://{{ $traffic->getStore->url ?? 'Unauthorized' }}" target="_blank">
                                    {{ $traffic->getStore->name ?? 'Unauthorized' }}
                                </a>
                                ({{ isset($traffic->getUser) && $traffic->getUser->name ?? 'Name not found' }}
                                -{{ isset($traffic->getUser) && $traffic->getUser->id ??'' }})
                                <br>
                                {{ isset($traffic->getUser) && $traffic->getUser->phone ?? 'Phone not found' }}
                            </td>


                            <td>
                                    <?php
                                    $comments = DB::table('client_activitie_comments')
                                        ->where('store_id', $traffic->store_id)
                                        ->orderBy('updated_at', 'DESC')
                                        ->get();
                                    ?>
                                    <!-- Modal -->
                                <div class="modal fade" id="AnalyticesCommentModal{{ $key }}" tabindex="-1"
                                     aria-labelledby="exampleModalLabelClient" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                 style="background-color: black; color:wheat;padding: 6px 25px 0px 25px;">
                                                <h5 class="modal-title" id="exampleModalLabelClient">
                                                    <a href="http://{{ $traffic->getStore->url ?? 'Unauthorized' }}"
                                                       target="_blank">
                                                        {{ $traffic->getStore->name ?? 'Unauthorized' }}
                                                    </a>
                                                    <span style="font-size: 14px;">
                                                            ({{ isset($traffic->getUser) && $traffic->getUser->name ?? 'Name not found' }} -{{ isset($traffic->getUser) && $traffic->getUser->id ??'' }})</span>

                                                    <p>{{ isset($traffic->getUser) && $traffic->getUser->phone ?? 'Phone not found' }}</p>
                                                </h5>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>


                                            <div class="modal-body">
                                                @if (!empty($comments))
                                                    <form id="clientComment{{ $traffic->id }}"
                                                          onsubmit="event.preventDefault();" class="p-3"
                                                          style="border: 1px dashed crimson;"
                                                          action="{{ route('superadmin.clients.activities.comments') }}"
                                                          method="POST">
                                                        @csrf

                                                        <div class="col-12">
                                                            <input type="hidden" name="user_id"
                                                                   value="{{ isset($traffic->getUser) && $traffic->getUser->id }}">
                                                            <input type="hidden" name="store_id"
                                                                   value="{{ $traffic->store_id }}">
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="clientStatus{{ $traffic->id }}"> Client
                                                                    Status </label>
                                                                <select name="clientStatus"
                                                                        id="clientStatus{{ $traffic->id }}" required
                                                                        class="form-control">
                                                                    <option selected disabled>Select Client Status
                                                                    </option>
                                                                    <option value="Tut Tut">Tut Tut</option>
                                                                    <option value="Thinking">Thinking</option>
                                                                    <option value="Maybe">Maybe</option>
                                                                    <option value="Positive">Positive</option>
                                                                    <option value="No Response">No Response</option>
                                                                    <option value="Interested">Interested</option>
                                                                    <option value="No interested">No interested</option>
                                                                    <option value="Freelancer">Freelancer</option>
                                                                    <option value="Paid">Paid</option>
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
                                                                <textarea name="comment" class="form-control"
                                                                          id="comment{{ $traffic->id }}" required
                                                                          cols="30" rows="5"></textarea>
                                                            </div>

                                                            <div class="col-12">
                                                                <input type="hidden" id="contVal{{ $traffic->id }}"
                                                                       name="" value="{{ $comments->count() }}">
                                                                <button type="button" style="border: 1px dashed;"
                                                                        class="btn btn-default mt-3">Total Follow-Up:
                                                                    <span
                                                                        id="conut{{ $traffic->id }}">{{ $comments->count() }}</span>
                                                                </button>

                                                                <button type="submit" id="submitBtn{{ $traffic->id }}"
                                                                        onclick="SubMitFrom({{ $traffic->id }})"
                                                                        style="float: right;"
                                                                        class="btn btn-info mt-3">Comment
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </form>
                                                @else
                                                    <h2>There is no Comments here yet</h2>
                                                @endif

                                                <div id="resCmt{{ $traffic->id }}" class="row px-3">
                                                    @foreach ($comments as $item)
                                                        <div class="col-md-12 mt-3"
                                                             style="border: 1px dashed lightseagreen;padding: 10px">
                                                            <h4 class="">{{ $item->short_comment }} <span
                                                                    style="float: right;font-size: 16px;font-weight: 500;">
                                                                        {{ date('d-m-Y, h:i:s A', strtotime($item->created_at ?? '10:00:00')) }}
                                                                        <br>
                                                                         <small
                                                                             style="float: right;"><strong>-- {{ $item->comment_by?? 'Kabir' }}</strong></small>
                                                                    </span>
                                                            </h4>
                                                            <p class="m-0">
                                                                <strong>Next Follow Up:</strong> <br>
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
                                <a style="pointer-events: {{ ($traffic->getStore->name??'') !=''?'':'none' }}"
                                   href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-info btn-sm"
                                   data-bs-target="#AnalyticesCommentModal{{ $key }}" id="viewaddons"
                                   data-id="{{ $traffic->id }}">
                                    Comments
                                </a>
                            </td>

                            <td id="shortCmt{{ $traffic->id }}">{{ $comments[0]->short_comment ?? 'follow Up Short Commnets' }}</td>
                            <td>
                                @php
                                    $mytime = Carbon\Carbon::now()->format('d-m-Y');
                                @endphp
                                @if (empty($comments[0]->follow_up_date) || date('d-m-Y', strtotime($comments[0]->follow_up_date?? '0000-00-00')) == date('d-m-Y', strtotime('0000-00-00')))
                                    <span style="background-color: rgba(230, 247, 130, 0.308);color: black;"
                                          class="p-2">
                                    {{ $comments[0]->follow_up_date ?? 'follow Up Date, Time' }},
                                    {{ date('h:i:s A', strtotime($comments[0]->follow_up_time ?? '00:00:00')) }}
                                </span>
                                @elseif ($mytime > date('d-m-Y', strtotime($comments[0]->follow_up_date ?? '01-01-2090')))
                                    <span style="background-color: red;color: white;" class="p-2">
                                        {{ $comments[0]->follow_up_date ?? 'follow Up Date, Time' }},
                                        {{ date('h:i:s A', strtotime($comments[0]->follow_up_time ?? '00:00:00')) }}
                                    </span>
                                @elseif ($mytime < date('d-m-Y', strtotime($comments[0]->follow_up_date ?? '01-01-2090')))
                                    <span style="background-color: rgb(238, 250, 71);color: black;" class="p-2">
                                        {{ $comments[0]->follow_up_date ?? 'follow Up Date, Time' }},
                                        {{ date('h:i:s A', strtotime($comments[0]->follow_up_time ?? '00:00:00')) }}
                                    </span>
                                @elseif($mytime == date('d-m-Y', strtotime($comments[0]->follow_up_date ?? '01-01-2090')))
                                    <span style="background-color: #148fa9;color: white;" class="p-2">
                                        {{ $comments[0]->follow_up_date ?? 'follow Up Date, Time' }},
                                        {{ date('h:i:s A', strtotime($comments[0]->follow_up_time ?? '00:00:00')) }}
                                    </span>
                                @else
                                    <span class="p-2">
                                        {{ $comments[0]->follow_up_date ?? 'follow Up Date, Time' }},
                                        {{ date('h:i:s A', strtotime($comments[0]->follow_up_time ?? '00:00:00')) }}
                                    </span>
                                @endif
                            </td>

                            <td id="okCommnet">
                                    <textarea name="comment" class="form-control"
                                              id="comment{{ isset($traffic->getUser) && $traffic->getUser->id }}"
                                              onchange="okComment({{ isset($traffic->getUser) && $traffic->getUser->id }})"
                                              cols="20" rows=""
                                              placeholder="Enter Your Comment">{{ isset($traffic->getUser) && $traffic->getUser->comment }}</textarea>
                            </td>

                            <td>
                                    <?php
                                    $urls = DB::table('admin_user_analytics')
                                        ->where('store_id', $traffic->store_id)
                                        ->orderBy('updated_at', 'DESC')
                                        ->get();
                                    ?>
                                    <!-- Modal -->
                                <div class="modal fade" id="AnalyticesExampleModal{{ $key }}"
                                     tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                        <span style="color: orange"><a
                                                                href="http://{{ $traffic->getStore->url ?? 'Unauthorized' }}"
                                                                target="_blank">
                                                                {{ $traffic->getStore->name ?? 'Unauthorized' }}
                                                            </a></span> Visite url
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if (!empty($urls))
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
                                                            @php
                                                                $nopage = 0;
                                                            @endphp
                                                            @foreach ($urls as $i => $item)
                                                                <tr>
                                                                    <td>
                                                                        {{ $i + 1 }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->number_of_visits }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->url }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->updated_at }}
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $nopage += $item->number_of_visits;
                                                                @endphp
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <h2>There is no page here yet</h2>
                                                @endif

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
                                <a href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-info btn-sm"
                                   data-bs-target="#AnalyticesExampleModal{{ $key }}" id="viewaddons"
                                   data-id="{{ $traffic->id }}">
                                    View
                                </a>
                            </td>

                            <td style="text-align: center;">{{ $i + 1 }} / {{ $nopage }}</td>
                            <td>{{ $urls[0]->updated_at ?? $urls[0]->created_at }}</td>
                            <td>
                                <a href="{{ URL::to('/') }}/client/view/{{ isset($traffic->user_id) && $traffic->user_id }}"
                                   class="btn btn-info btn-sm" target="_blank">View</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div style="text-align: center;">
                    {!! $analyticsInfo->links() !!}
                </div>
            </div>
        </div>

        <div class="row mt-3">More informations</div>


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


    <script>
        function shortting() {
            // alert('Ok');
        }
    </script>


    <script>
        function followUp() {

            var text = $('#followUp').val();
            // alert(text);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.clients.followUpDate') }}",
                data: {
                    followUp: text
                },
                success: function (data) {
                    $('#findSearchResult').html(data);
                }
            });
        }
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
        $(document).ready(function () {
            $('#example').DataTable({
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
    </script>

    <script>
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
                        $('#taskfilterresult').html(data);
                    }
                });
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
