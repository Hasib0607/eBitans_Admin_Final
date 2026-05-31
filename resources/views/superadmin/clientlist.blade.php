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
    @if (Auth::user()->type == 'superadmin')
        <!-- The Modal -->
        <div class="modal fade" id="assignCustomer">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('assign.client.inSeller') }}" method="post">
                        @csrf
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Assign Client</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <label class="col col-form-label">Staff</label>
                                <input name="sales_id" id="sales_id" type="hidden">
                                <input name="user_id" id="client_id" type="hidden">
                                <div class="col">
                                    <select name="staff_id" id="staff_id" class="form-control" onclick="changeStaff()">
                                        <option value="">Select Staff</option>
                                        @if(count($staff) > 0)
                                            @foreach($staff as $item)
                                                <option value="{{$item->id}}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col col-form-label">New Client Commission</label>
                                <div class="col">
                                    <input name="new_commission" id="new_commission" type="text"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col col-form-label">Renew Client Commission</label>
                                <div class="col">
                                    <input name="renew_commission" id="renew_commission" type="text"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col col-form-label">Setup Commission</label>
                                <div class="col">
                                    <input name="setup_commission" id="setup_commission" type="text"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col col-form-label">Setup Amount</label>
                                <div class="col">
                                    <input name="setup_amount" id="setup_amount" type="text"
                                           class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="Submit" id="btnAssignClient" disabled class="btn btn-info">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif


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
                                @if (Auth::user()->type == 'superadmin')
                                    <div class="col-md-3">
                                        <form method="post" action="{{ route('superadmin.changeclientssstatus') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-8" style="padding-right:1px;">
                                                    <input type="hidden" name="text2" id="selectids">
                                                    <select class='form-control' name="action" id="action">
                                                        <option value="select">Select Option</option>
                                                        <option value="delete">Delete</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4" style="padding-left:0px;">
                                                    <button type="submit" class="btn btn-primary">Apply</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                <div class="col-md-7">
                                    <form action="{{ route('superadmin.clientlistdatefilter') }}" method="get">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="date" name="formdate" id="formdate"
                                                       value="{{ $formdate ?? '' }}"
                                                       class="form-control">
                                            </div>
                                            <div class="col colToText text-center mt-1">
                                                <label for="to_date">To</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="date" name="enddate" id="todate"
                                                       value="{{ $enddate ?? '' }}"
                                                       class="form-control">
                                            </div>
                                            <div class="col-md-3" style="padding-right:1px;">
                                                <select class='form-control' name="category" id="category">
                                                    <option selected value="">Select Category</option>
                                                    @if(isset($categories) && count($categories))
                                                        @foreach($categories as $parent)
                                                            {{-- Parent category --}}
                                                            <option value="{{ $parent->id }}"
                                                                {{ (isset($category) && $parent->id == $category) ? 'selected' : '' }}>
                                                                {{ $parent->name }}
                                                            </option>

                                                            {{-- Subcategories --}}
                                                            @if($parent->subcategories && $parent->subcategories->count())
                                                                @foreach($parent->subcategories as $sub)
                                                                    <option value="{{ $sub->id }}"
                                                                        {{ (isset($category) && $sub->id == $category) ? 'selected' : '' }}>
                                                                        &nbsp;&nbsp;↳ {{ $sub->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-2 filterbtns">
                                                <button type="submit" class="btn btn-info filterbtn"
                                                        style="background-color: #7b809a ">Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               aria-label="Dollar amount (with dot and two decimal places)"
                                               id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                            <input type="checkbox" id="idSearch">
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
                            <div class="table-responsive" id="taskfilterresult">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="1%">#</th>
                                        @if (Auth::user()->type == 'superadmin')
                                            <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        @endif

                                        <th width="5%">Name</th>
                                        <th width="5%">Follow-Up</th>
                                        <th width="5%">Store Name</th>
                                        <th width="5%">Id</th>
                                        <th width="10%">Comment</th>
                                        @if (Auth::user()->type == 'superstaff' || Auth::user()->type == 'superadmin')
                                            <th width="10%">Seller</th>
                                        @endif
                                        @if (Auth::user()->type == 'superstaff')
                                            <th width="10%">Setup Amount</th>
                                        @endif
                                        @if (Auth::user()->type == 'superadmin')
                                            <th width="10%">Assign Seller</th>
                                        @endif
                                        <th width="15%">Created At</th>
                                        <th width="11%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($clients as $key => $client)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>{{ ($clients->currentPage() - 1) * $clients->perPage() + $loop->iteration }}</td>
                                            @if (Auth::user()->type == 'superadmin')
                                                <td>
                                                    <input type="checkbox" name="selectedid" value="{{ $client->id }}"
                                                           id="id" class="checkSingle">
                                                </td>
                                            @endif

                                            <td>
                                                {{ $client->name ?? 'Empty' }}
                                            </td>

                                            <td>
                                                    <?php
                                                    $comments = DB::table('client_activitie_comments')
                                                        ->where('store_id', $client->getStore->id ?? '')
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
                                                                        <a href="http://{{ $client->getStore->url ?? 'Unauthorized' }}"
                                                                           target="_blank">
                                                                            {{ $client->getStore->name ?? 'Unauthorized' }}
                                                                        </a>
                                                                        <span style="font-size: 14px;">
                                                                                ({{ $client->name ?? 'Name not found' }}
                                                                                -{{ $client->id ?? '' }})
                                                                            </span>

                                                                        <p>{{ $client->phone ?? 'Phone not found' }}
                                                                        </p>
                                                                    </h5>

                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>


                                                                <div class="modal-body">
                                                                    @if (!empty($comments))
                                                                        <form id="clientComment{{ $client->id }}"
                                                                              onsubmit="event.preventDefault();"
                                                                              class="p-3"
                                                                              style="border: 1px dashed crimson;"
                                                                              action="{{ route('superadmin.clients.activities.comments') }}"
                                                                              method="POST">
                                                                            @csrf

                                                                            <div class="col-12">
                                                                                <input type="hidden" name="user_id"
                                                                                       value="{{ $client->id ?? 'empty' }}">
                                                                                <input type="hidden" name="store_id"
                                                                                       value="{{ $client->getStore->id ?? null }}">
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <label
                                                                                        for="clientStatus{{ $client->id }}">
                                                                                        Client Status </label>
                                                                                    <select name="clientStatus"
                                                                                            id="clientStatus{{ $client->id }}"
                                                                                            class="form-control"
                                                                                            required>
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
                                                                                    <label
                                                                                        for="comment{{ $client->id }}">
                                                                                        Comments
                                                                                    </label>
                                                                                    <textarea name="comment"
                                                                                              class="form-control"
                                                                                              id="comment{{ $client->id }}"
                                                                                              required cols="30"
                                                                                              rows="5"></textarea>
                                                                                </div>

                                                                                <div class="col-12">
                                                                                    <input type="hidden"
                                                                                           id="contVal{{ $client->id }}"
                                                                                           name=""
                                                                                           value="{{ $comments->count() }}">
                                                                                    <button type="button"
                                                                                            style="border: 1px dashed;"
                                                                                            class="btn btn-default mt-3">
                                                                                        Total
                                                                                        Follow-Up:
                                                                                        <span
                                                                                            id="conut{{ $client->id }}">{{ $comments->count() }}</span>
                                                                                    </button>

                                                                                    <button type="submit"
                                                                                            id="submitBtn{{ $client->id }}"
                                                                                            onclick="SubMitFrom({{ $client->id }})"
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

                                                                    <div id="resCmt{{ $client->id }}"
                                                                         class="row px-3">
                                                                        @foreach ($comments as $item)
                                                                            <div class="col-md-12 mt-3"
                                                                                 style="border: 1px dashed lightseagreen;padding: 10px">
                                                                                <h4 class="">
                                                                                    {{ $item->short_comment }}
                                                                                    <span
                                                                                        style="float: right;font-size: 16px;font-weight: 500;">
                                                                                            {{ date('d-m-Y, h:i:s A', strtotime($item->created_at ?? '10:00:00')) }}<br>
                                                                                            {{ date('h:i:s A', strtotime($item->created_at ?? '10:00:00')) }}
                                                                                            <br>
                                                                                            <small
                                                                                                style="float: right;"><strong>-- {{ $item->comment_by?? 'Kabir' }}</strong></small>
                                                                                        </span>
                                                                                </h4>
                                                                                <p class="m-0">
                                                                                    <strong>Next Follow Up:</strong>
                                                                                    <br>
                                                                                    {{ date('d-m-Y', strtotime($item->follow_up_date ?? '2000-01-01')) }}
                                                                                    ,
                                                                                    {{ date('h:i:s A', strtotime($item->follow_up_time ?? '10:00:00')) }}
                                                                                </p>
                                                                                <div class="m-0">
                                                                                    <strong>Comment:</strong> <br>
                                                                                    <textarea class="form-control"
                                                                                              readonly>{{ $item->comment }}</textarea>
                                                                                </div>
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

                                                <a style="background-color: {{ $comments !='[]'?'':'red' }};pointer-events: {{ ($client->getStore->name??'') !=''?'':'none' }}"
                                                   href="javascript:void(0)" data-bs-toggle="modal"
                                                   class="btn btn-info btn-sm"
                                                   data-bs-target="#AnalyticesCommentModal{{ $key }}"
                                                   id="viewaddons" data-id="{{ $client->id }}">
                                                    Follow-Up
                                                </a>
                                            </td>


                                                <?php
                                                $customer = DB::table('customers')
                                                    ->where('uid', $client->id)
                                                    ->first();

                                                $str = DB::table('stores')
                                                    ->where('id', $customer->active_store ?? 0)
                                                    ->first();
                                                ?>

                                            <td>
                                                <a style="display: block;font-size: 14px;color:{{ $str->name?? '#ff5733;'}}"
                                                   href="http://{{ $str->url ?? '#' }}" target="_blank"
                                                   rel="noopener noreferrer">
                                                    {{  $client->getStore->name ?? 'Store is not built yet' }}
                                                    <strong
                                                        style="font-size: 9px; color: {{ $str->name ?? '' != '' ? 'green': '#ff5733;' }}">{{ $str->name ?? '' != '' ? 'Active': 'Inactive' }}</strong>
                                                </a>
                                                @if(isset($client->phone) && !empty($client->phone))
                                                    <p style="font-weight: 900;margin-bottom: 0">
                                                        <a href="https://wa.me/88{{ $client->phone }}" target="_blank"
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
                                                @if(isset($client->type) && !empty($client->type))
                                                    <small>
                                                        Type : {{ $client->type ?? '' }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>{{ $client->id }}</td>

                                            <td id="okCommnet">
                                                <textarea name="comment" class="form-control"
                                                          id="cmt{{ $client->id }}"
                                                          onchange="okComment({{ $client->id }})" cols="20" rows=""
                                                          placeholder="Enter Your Comment">{{ $client->comment }}</textarea>
                                            </td>
                                            @if (Auth::user()->type == 'superstaff')
                                                @php
                                                    $staffID = \App\Models\User::getStaff($client->customerInfo->staff_id ?? null);
                                                @endphp
                                                <td>
                                                    @if((is_null($client->customerInfo) || (isset($staffID) && Auth::user()->id == $staffID)) && $client->complete_orders_count == 0)
                                                        <input type="checkbox" id="makeMyCustomer{{$client->id}}"
                                                               @if(Auth::user()->id == $staffID) checked
                                                               @endif
                                                               onchange="makeMyCustomer({{ $client }})"
                                                               style="cursor: pointer">
                                                    @else
                                                        <input type="checkbox" checked disabled style="cursor: pointer">
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $client->customerInfo->setup_amount ?? "Not Set Yet" }}
                                                </td>
                                            @endif
                                            @if (Auth::user()->type == 'superadmin')
                                                <td>
                                                    <input type="checkbox" @if(isset($client->customerInfo)) checked
                                                           @endif disabled style="cursor: pointer">
                                                    <p>{{ $client->customerInfo->staff->name ?? "" }}</p>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary" onclick="assignSeller({{$client}})">
                                                        Assign Customer
                                                    </button>
                                                </td>
                                            @endif
                                            <td>
                                                {{ date('j M, Y', strtotime($client->created_at ?? '2000-01-01')) }}<br>
                                                {{ date('h:i:s A', strtotime($client->created_at ?? '10:00:00')) }}
                                            </td>
                                            <td>
                                                <a href="{{ URL::to('/') }}/client/view/{{ $client->id }}"
                                                   class="btn btn-info" target="_blank">View</a>

                                                @if ((Auth::user()->type == 'superstaff' || Auth::user()->type == 'superadmin') && \App\Models\User::storeCount($client->id) == 0)
                                                    <a href="{{ route('superstaff.access.admin', ['id' => $client->id]) }}"
                                                       class="btn btn-danger">Create Store</a>
                                                @endif
                                                @if (Auth::user()->type == 'superstaff')
                                                    <a href="{{ route('superstaff.access.admin.store', ['id' => $client->id]) }}"
                                                       class="btn btn-danger">Make Payment</a>
                                                @endif

                                                @if (Auth::user()->type == 'superadmin')
                                                    <a href="#" onclick="deleteStore({{ $client->id }})"
                                                       class="btn btn-danger">Delete</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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

                                    <div style="text-align: center;">
                                        @if(isset($formdate) && isset($enddate))
                                            {!! $clients->appends(['formdate' => $formdate, 'enddate' => $enddate, 'category' => $category])->links() !!}
                                        @else
                                            {!! $clients->links() !!}
                                        @endif
                                    </div>
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
                        preConfirm: (login) => {
                            $url = "{{ route('superadmin.store.store.delete.auth.check') }}";
                            $.get($url, {
                                value: login,
                                id: login
                            }, function (data) {
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
            var text = $('#cmt' + id).val();
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

    @if (Auth::user()->type == 'superadmin')
        <script>
            let setStaff = null;
            const assignSeller = (client) => {
                const customer = client.customer_info;
                $("#client_id").val(client.id || "");

                if (customer) {
                    $("#sales_id").val(customer.id || "");

                    setStaff = customer || null;

                    const staffId = String(customer.staff_id) || "";
                    $("#staff_id").val(staffId).change();

                    $("#new_commission").val(customer.new_commission || "");
                    $("#renew_commission").val(customer.renew_commission || "");
                    $("#setup_commission").val(customer.setup_commission || "");
                    $("#setup_amount").val(customer.setup_amount || "");

                    $("#btnAssignClient").prop("disabled", false);
                } else {
                    setStaff = customer || null;

                    $("#btnAssignClient").prop("disabled", true);

                    $("#staff_id").val("").change();
                    $("#new_commission").val("");
                    $("#renew_commission").val("");
                    $("#setup_commission").val("");
                    $("#setup_amount").val("");
                }

                openModal("assignCustomer");
            }

            const changeStaff = () => {
                const staff_id = $("#staff_id").val();
                const staffs = JSON.parse('{!! $staff !!}');

                if (staff_id !== "") {
                    const numericStaffId = Number(staff_id);
                    const staff = staffs.find((item) => item.id === numericStaffId);

                    const new_commission = setStaff?.new_commission || staff.new_commission;
                    const renew_commission = setStaff?.renew_commission || staff.renew_commission;
                    const setup_commission = setStaff?.setup_commission || staff.setup_commission;
                    const setup_amount = setStaff?.setup_amount;

                    if (staff) {
                        $("#new_commission").val(new_commission || "");
                        $("#renew_commission").val(renew_commission || "");
                        $("#setup_commission").val(setup_commission || "");
                        $("#setup_amount").val(setup_amount || "");
                    } else {
                        $("#new_commission").val("");
                        $("#renew_commission").val("");
                        $("#setup_commission").val("");
                        $("#setup_amount").val("");
                    }
                    $("#btnAssignClient").prop("disabled", false);
                } else {
                    $("#btnAssignClient").prop("disabled", true);
                    $("#new_commission").val("");
                    $("#renew_commission").val("");
                    $("#setup_commission").val("");
                    $("#setup_amount").val("");
                }
            }

            // Open the modal
            function openModal(id) {
                const modalElement = document.getElementById(id); // Find the modal by ID
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement); // Initialize a new modal instance
                    modal.show();
                }
            }

            // Close the modal
            function closeModal(id) {
                const modalElement = document.getElementById(id); // Find the modal by ID
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement); // Get the existing modal instance
                    if (modal) {
                        modal.hide();
                    }
                }
            }
        </script>
    @endif

    <script>
        const clientsData = @json($clientsExport);
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

        const makeMyCustomer = (client) => {
            const customer = client.customer_info;

            if (customer) {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You want to remove this client",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        createMySeller(client.id, true);
                    } else {
                        const ceckID = "#makeMyCustomer" + client.id;
                        $(ceckID).prop("checked", true);
                    }
                })
            } else {
                createMySeller(client.id);
            }
        }


        const createMySeller = (clientID, refresh = false) => {
            if (clientID != "") {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.client.seller.status') }}",
                    data: {
                        id: clientID
                    },
                    success: function (data) {
                        if (data.status) {
                            if (refresh) {
                                swal.fire({
                                    title: 'Success',
                                    text: data.message,
                                    type: 'success',
                                }).then((result) => {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                toastr.success(data.message);
                            }
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            }
        }

        $(document).ready(function () {
            let debounceTimer;

            $("#taskfilter").on("input", function () {
                clearTimeout(debounceTimer); // clear the previous timer

                debounceTimer = setTimeout(function () {
                    var value = $("#taskfilter").val();
                    var idSearch = $('#idSearch').is(':checked');

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
                }, 300); // Delay in milliseconds (300ms = 0.3s)
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
            let dataArray = clientsData;

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
