@extends('admin.layouts.main')
@section('content')
    @if (Auth::user()->type == 'superadmin')
        <!-- The Modal -->
        <div class="modal fade" id="paymentModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('superadmin.pay.staff.commission') }}" method="post">
                        @csrf
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Staff Commission Payment</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Amount</label>
                                <input name="amount" id="amount" type="number"
                                       class="form-control">
                                <input type="hidden" name="staff_id" value="{{ $staff_id ?? "" }}">
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            @if(isset($staff_id))
                                <button type="Submit" class="btn btn-info">Submit</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif


    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @if (Auth::user()->type == 'superstaff')
            @include('superadmin.staff.commission-nav')
        @elseif (Auth::user()->type == 'superadmin')
            @include('superadmin.share.staff-role-permission-nav.nav')
        @endif
        <div class="container-fluid mt-5" id="toplist">
            <div class="row mb-6 d-flex justify-content-center">
                <div class="col-lg-3 ">
                    <div class="m-left bg-danger rounded shadow"
                         style="width:70%; height: 100px; background-color: white">
                        <p class="text-white text-center pt-1 pb-0 mb-0">Balance</p>
                        <h3 class="text-white text-center">{{$balance ?? 0}} TK</h3>
                        @if (Auth::user()->type == 'superadmin')
                            <div class="text-center">
                                <button class="btn btn-primary mt-1" id="payStaffCommission" data-bs-toggle="modal"
                                        data-bs-target="#paymentModal">Pay Now
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h4>All Commission</h4>
                </div>
            </div>
            <div class="row mt-1 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-2 text-end mt-1">
                                        <form action="{{ route('superadmin.superstaff.commission.pay.unpay') }}"
                                              method="post" class="row">
                                            @csrf
                                            <div class="col">
                                                <input type="hidden" name="text2" id="selectids">
                                                <select class='form-control' name="action" id="action">
                                                    <option value="">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            Select Action
                                                        @else
                                                            Select Action
                                                        @endif
                                                    </option>
                                                    <option value="paid">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            Paid
                                                        @else
                                                            Paid
                                                        @endif
                                                    </option>
                                                    <option value="unpaid">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            Unpaid
                                                        @else
                                                            Unpaid
                                                        @endif
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-2" style="padding-left:0px;">
                                                <button type="submit" class="btn btn-primary">Apply</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col" style="margin-left: 70px;">
                                        <form action="" method="get" class="row">
                                            <div class="col-md-1 text-end mt-1">
                                                <label for="from_date">From Date</label>
                                            </div>

                                            <div class="col-md-2">
                                                <input type="date" name="from_date" id="from_date"
                                                       value="{{ $from_date ?? '' }}"
                                                       class="form-control">
                                            </div>
                                            <div class="col-md-1 text-end mt-1">
                                                <label for="to_date">To Date</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="date" name="to_date" id="to_date"
                                                       value="{{ $to_date ?? '' }}"
                                                       class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <input type="text" name="search" id="search"
                                                           value="{{ $search ?? '' }}"
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
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif
                            <div class="table-responsive" id="taskfilterresult">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="1%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th width="5%">Store Name</th>
                                        <th width="5%">Id</th>
                                        <th width="5%">Pay Status</th>
                                        <th width="10%">Commission For</th>
                                        <th width="10%">Commission(%)</th>
                                        <th width="10%">Purchase Amount</th>
                                        <th width="10%">Commission Amount</th>
                                        <th width="15%">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $pageTotalAmount = 0;
                                        $pageTotalCommissionAmount = 0;
                                    @endphp
                                    @foreach ($commission as $key => $item)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>
                                                <input type="checkbox" name="selectedid"
                                                       value="{{ $item->id }}" id="id"
                                                       class="checkSingle"/>
                                            </td>
                                            <td>
                                                <a style="display: block;font-size: 14px;color:{{ $str->name?? '#ff5733;'}}"
                                                   href="http://{{ $item->store->url ?? '#' }}" target="_blank"
                                                   rel="noopener noreferrer">
                                                    {{  $item->store->name??'Store is not built yet' }}
                                                    <strong
                                                        style="font-size: 9px; color: {{ $item->store->name ?? '' != '' ? 'green': '#ff5733;' }}">{{ $item->store->name ?? '' != '' ? 'Active': 'Inactive' }}
                                                    </strong>
                                                </a>
                                                <span style="font-weight: 900;">
                                                    @if(isset($item->user->phone))
                                                        <a href="https://wa.me/88{{ $item->user->phone }}"
                                                           target="_blank"
                                                           style="text-decoration: none;">
                                                            {{ $item->user->phone ?? '' }}
                                                        </a>
                                                    @elseif(isset($item->user->email))
                                                        {{ $item->user->email ?? '' }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                {{ $item->user->name ?? "" }}
                                                <br>
                                                @if(isset($item->user->id))
                                                    {{ "User ID : ". $item->user->id ?? "" }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->pay_status == 1)
                                                    <span class="text-success">Paid</span>
                                                @else
                                                    <span>Unpaid</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->isSetup)
                                                    {{ "Setup" }}
                                                @elseif($item->isNew)
                                                    {{ "New" }}
                                                @elseif($item->isRenew)
                                                    {{ "Renew" }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->isSetup)
                                                    {{ $item->setup_commission ?? "" }}
                                                @elseif($item->isNew)
                                                    {{ $item->new_commission ?? "" }}
                                                @elseif($item->isRenew)
                                                    {{ $item->renew_commission ?? "" }}
                                                @endif
                                            </td>
                                            <td>{{ $item->total_amount ?? "" }} TK</td>
                                            <td>{{ $item->commission_amount ?? "" }} TK</td>
                                            <td>
                                                {{ date('j M, Y', strtotime($item->created_at ?? '2000-01-01')) }}<br>
                                                {{ date('h:i:s A', strtotime($item->created_at ?? '10:00:00')) }}
                                            </td>
                                        </tr>
                                        @php
                                            $pageTotalAmount += $item->total_amount;
                                            $pageTotalCommissionAmount += $item->commission_amount;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex mt-4 mb-4 justify-content-between">
                                    <div class="d-flex flex-column">
                                        <p class="text-bold" style="margin-bottom: 5px;">Page Total Sell: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $pageTotalAmount }}</span>
                                            TK</p>
                                        <p class="text-bold" style="margin-bottom: 5px;">Page Total Commission: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $pageTotalCommissionAmount }}</span>
                                            TK</p>

                                        <hr>
                                        <p class="text-bold" style="margin-bottom: 5px;">Total Sell: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $totalAmount }}</span>
                                            TK</p>
                                        <p class="text-bold" style="margin-bottom: 5px;">Total Commission: <span
                                                class="px-2"
                                                style="color: #ff5733;">{{ $totalCommission }}</span>
                                            TK</p>
                                    </div>

                                    {!! $commission->appends(['from_date' => request('from_date'), 'to_date' => request('to_date'), 'search' => request('search')])->links() !!}
                                </div>
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
        $(document).ready(function () {
            let valuesArray = [];

            // Check all checkbox action
            $("#checkedAll").change(function () {
                if (this.checked) {
                    // If "checkedAll" is checked, check all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = true;
                        let value = $(this).val();
                        if (!valuesArray.includes(value)) {
                            valuesArray.push(value);
                        }
                    });
                } else {
                    // If "checkedAll" is unchecked, uncheck all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    valuesArray = [];
                }

                let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                $("#selectids").val(newAaluesArray);
                $("#selectdelids").val(newAaluesArray);
            });

            // Single check action
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    let value = $(this).val();

                    let isAllChecked = $(".checkSingle").length === $(".checkSingle:checked").length;
                    $("#checkedAll").prop("checked", isAllChecked);

                    if (!valuesArray.includes(value)) {
                        valuesArray.push(value);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
                } else {
                    $("#checkedAll").prop("checked", false);

                    let value = $(this).val();

                    let index = valuesArray.indexOf(value);

                    if (index === -1) {
                        valuesArray.push(value);
                    } else {
                        valuesArray.splice(index, 1);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
                }
            });
        });
    </script>
@endpush


