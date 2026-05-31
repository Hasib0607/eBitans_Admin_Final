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

        @media (min-width: 768px) {
            .applyBtn {
                margin-left: -50px;
            }
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.share.report.nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-8">
                    <h4>All Clients</h4>
                </div>
                <div class="col-4" style="text-align:end">
                    <a href="#" class="btn btn-primary"
                       onclick="downloadExcelReport();">Export</a>
                </div>
            </div>
            <div class="row mt-2 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    <form action="{{ route('paidClientsList') }}" method="get" id="searchForm"
                                          class="row">
                                        <input type="hidden" name="report" id="report_type" value="">
                                        <div class="col-md-2" style="padding-right:1px;">
                                            <select class='form-control' name="type" id="action">
                                                <option value="" {{ isset($type) && $type == "" ? 'selected' : '' }}>
                                                    Select
                                                    Option
                                                </option>
                                                <option
                                                    value="paid" {{ isset($type) && $type == "paid" ? 'selected' : '' }}>
                                                    Paid
                                                </option>
                                                <option
                                                    value="active" {{ isset($type) && $type == "active" ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option
                                                    value="renew" {{ isset($type) && $type == "renew" ? 'selected' : '' }}>
                                                    Renew
                                                </option>
                                                <option
                                                    value="new_customer" {{ isset($type) && $type == "new_customer" ? 'selected' : '' }}>
                                                    New
                                                </option>
                                                <option
                                                    value="expired" {{ isset($type) && $type == "expired" ? 'selected' : '' }}>
                                                    Expired
                                                </option>
                                                <option
                                                    value="setup_buy" {{ isset($type) && $type == "setup_buy" ? 'selected' : '' }}>
                                                    Setup Buy
                                                </option>
                                                <option
                                                    value="setup_not_buy" {{ isset($type) && $type == "setup_not_buy" ? 'selected' : '' }}>
                                                    Setup Not Buy
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
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                                                       class="form-control">
                                                <span class="input-group-text"
                                                      style="padding: 0.75rem 11px !important;">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </span>
                                            </div>
                                        </div>

                                        <div class="col-md-2" style="padding-left:0px;">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <form method="post" action="{{ route('superadmin.changeClientSetupStatus') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-8" style="padding-right:1px;">
                                                <input type="hidden" name="text2" id="selectids">
                                                <select class='form-control' name="action" id="action">
                                                    <option value="">Select Option</option>
                                                    <option value="1">Mark Setup</option>
                                                    <option value="0">Unmark Setup</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4" style="padding-left:5px;">
                                                <button type="submit" class="btn btn-primary">Apply</button>
                                            </div>
                                        </div>
                                    </form>
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
                                        <th width="1%">SL</th>
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th>Name</th>
                                        <th>Follow-Up</th>
                                        <th>Store Name</th>
                                        <th>Id</th>
                                        <th>Package</th>
                                        <th>Addon</th>
                                        <th>Purchase Amount</th>
                                        <th>Comment</th>
                                        <th>Plan</th>
                                        <th>Active Date</th>
                                        <th>Create Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($paidClients))
                                        @foreach ($paidClients as $key => $client)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($paidClients->currentPage() - 1) * $paidClients->perPage() + $loop->iteration }}</td>
                                                <td>
                                                    <input type="checkbox" name="selectedid" value="{{ $client->id }}"
                                                           id="id" class="checkSingle">
                                                </td>
                                                <td>
                                                    <h6>{{ $client->getUser->name ?? 'Empty' }}</h6>
                                                    <strong style="color:green; font-size:14px">Paid</strong>
                                                    <p>Setup: <strong
                                                            style="color:{{ $client->setup_status ? 'green' : 'red' }}; font-size:14px">
                                                            {{ $client->setup_status ? 'Buy' : 'Not Buy' }}
                                                        </strong></p>
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
                                                             aria-labelledby="exampleModalLabelClient"
                                                             aria-hidden="true">
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

                                                                            <p>{{ $client->getUser->phone ?? 'Phone not found' }}
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
                                                                                            <option selected disabled>
                                                                                                Select
                                                                                                Client Status
                                                                                            </option>
                                                                                            <option value="Positive">
                                                                                                Positive
                                                                                            </option>
                                                                                            <option value="thinking">
                                                                                                Thinking
                                                                                            </option>
                                                                                            <option value="No Response">
                                                                                                No
                                                                                                Response
                                                                                            </option>
                                                                                            <option value="Interested">
                                                                                                Interested
                                                                                            </option>
                                                                                            <option value="Maybe">Maybe
                                                                                            </option>
                                                                                            <option
                                                                                                value="No interested">
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
                                                                                               id="followUpData"
                                                                                               required>
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
                                                    @if(isset($client->getUser->phone) && !empty($client->getUser->phone))
                                                        <p style="font-weight: 900;margin-bottom: 0">
                                                            <a href="https://wa.me/88{{ $client->getUser->phone }}"
                                                               target="_blank"
                                                               style="text-decoration: none;">
                                                                {{ $client->getUser->phone ?? '' }}
                                                            </a>
                                                        </p>
                                                    @endif
                                                    @if(isset($client->getUser->email) && !empty($client->getUser->email))
                                                        <p style="font-weight: 900;margin-bottom: 0">
                                                            {{ $client->getUser->email ?? '' }}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td>{{ $client->getUser->id ?? 0 }}</td>

                                                @php
                                                    $storeTotalAddons = 0;
                                                    $storeTotalPackage = 0;
                                                    $pageTotalData = $client->addonsOrders;
                                                    foreach ($pageTotalData as $item) {
                                                        $addons = $item->addons;
                                                        if (is_string($addons)) {
                                                            $addons = json_decode($addons, true); // Decode the JSON string to array
                                                        }

                                                        // If 'addons' is an array, calculate the total price of addons
                                                        if (is_array($addons)) {
                                                            foreach ($addons as $addon) {
                                                                $addonPrice = isset($addon['price']) ? (float)$addon['price'] : 0;
                                                                $storeTotalAddons += $addonPrice;
                                                            }
                                                        }

                                                        $package = $item->package;
                                                        if (is_string($package)) {
                                                            $package = json_decode($package, true); // Decode the JSON string to array
                                                        }

                                                        // If 'package' is an array and has a price, calculate the total package price
                                                        if (is_array($package) && isset($package['offerprice'])) {
                                                            $storeTotalPackage += (float)$package['offerprice'];
                                                        }
                                                    }
                                                @endphp
                                                <td>
                                                    {{ $storeTotalPackage ?? "" }}
                                                </td>
                                                <td>
                                                    {{ $storeTotalAddons ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $client->addonsOrders->sum('total') ?? '' }}
                                                </td>
                                                <td id="okCommnet">
                                                      <textarea
                                                          name="comment"
                                                          class="form-control"
                                                          id="comment{{ $client->getUser->id ?? 0 }}"
                                                          onchange="okComment({{ $client->getUser->id ?? 0 }})"
                                                          cols="20"
                                                          rows=""
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
                                                    <p class="m-0">
                                                        {{ date('j M, Y', strtotime($client->created_at ?? '2000-01-01')) }}
                                                    </p>
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
                                    @else
                                        <tr>
                                            <td colspan="14">
                                                No Record Found
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="d-flex mt-4 mb-4 justify-content-between">
                                    <div class="d-flex flex-column">
                                        <p class="text-bold" style="margin-bottom: 5px;">Total Record: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $paidClients->total() }}</span>
                                        </p>

                                        <hr>
                                        <p class="text-bold" style="margin-bottom: 5px;">Page Total Package: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $pageTotalPackage }}</span>
                                            TK</p>
                                        <p class="text-bold" style="margin-bottom: 5px;">Page Total Addon: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $pageTotalAddons }}</span>
                                            TK</p>
                                        <p class="text-bold" style="margin-bottom: 5px;">Page Total Amount: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $pageTotalAmount }}</span>
                                            TK</p>

                                        <hr>
                                        <p class="text-bold" style="margin-bottom: 5px;">Total Package: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $totalPackage }}</span>
                                            TK</p>
                                        <p class="text-bold" style="margin-bottom: 5px;">Total Addon: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $totalAddons }}</span>
                                            TK</p>
                                        <p class="text-bold" style="margin-bottom: 5px;">Total Amount: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $totalAmount }}</span>
                                            TK</p>
                                    </div>

                                    {!! $paidClients->appends(['type' => request('type'),'search' => request('search'),'from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
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
        // const exportBtn = document.getElementById('export');
        //
        // exportBtn.addEventListener('click', function () {
        //     // Export to csv
        //     const csv = toCsv(table);
        //
        //     // Download it
        //     download(csv, 'download.csv');
        // });
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


        const downloadExcelReport = () => {
            const form = document.getElementById("searchForm");
            document.getElementById("report_type").value = "excel";

            // Create a spinner
            Swal.fire({
                title: 'Please wait...',
                html: 'Generating Excel Report...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Send AJAX to download
            const formData = new FormData(form);
            const queryString = new URLSearchParams(formData).toString();

            fetch(`{{ route('paidClientsList') }}?${queryString}`, {
                headers: {
                    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                }
            })
                .then(response => {
                    if (response.ok) return response.blob();
                    else throw new Error("Failed to download file");
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "paid_clients_report.xlsx";
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);

                    Swal.close();
                    toastr.success("Excel report downloaded successfully");
                })
                .catch(err => {
                    Swal.close();
                    toastr.error("Failed to export report");
                });

            // Reset hidden field
            document.getElementById("report_type").value = "";
        }
    </script>
@endpush
