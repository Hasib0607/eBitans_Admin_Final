@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    @include("superadmin.order.top_nav")
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Request</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('superadmin.orderPlanrequest', 'Complete') }}"
                               class="btn btn-primary" style="display:block;border-radius:0px !important">Accept
                                List</a>
                        </li>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('superadmin.orderPlanrequest', 'Failed') }}" class="btn btn-primary"
                               style="display:block;border-radius:0px !important">Rejected List</a>
                        </li>
                        <li style="padding:0px;border:0px;"><a href="javascript:void(0)"
                                                               style="display:block;border-radius:0px !important"
                                                               class="btn btn-secondary">Export</a></li>
                    </ul>
                </div>
            </div>
            <div class="row productlist">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header">
                            <div class="row">
                                <form action="{{ route('superadmin.orderPlanrequest', ['status' => $status ?? "" ]) }}"
                                      method="get"
                                      style="display: flex;gap: 15px;">
                                    <div class="col-1 mt-1" style="display: flex;align-items: center;width: auto;">
                                        <label for="from_date">
                                            From
                                        </label>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <input type="date" id="from_date" name="from_date" class="form-control"
                                               value="{{ $from_date ?? "" }}">
                                    </div>
                                    <div class="col-1 mt-1" style="display: flex;align-items: center;width: auto;">
                                        <label for="to_date">
                                            To
                                        </label>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <input type="date" id="to_date" name="to_date" class="form-control"
                                               value="{{ $to_date ?? "" }}">
                                    </div>

                                    <div class="col-md-1 mt-2">
                                        <button type="submit" class="btn btn-info filterbtn"
                                                style="background-color: #7b809a ">
                                            Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th width="5%">Plan Name</th>
                                        <th width="5%">Package Name</th>
                                        <th Width="5%">Store</th>
                                        <th Width="5%">Modulus Name</th>
                                        <th width="20%">Month</th>
                                        {{-- <th width="5%">Amount</th> --}}
                                        <th width="5%">Total Amount</th>
                                        <th width="10%">Customer Name</th>
                                        <th width="5%">Payment Method</th>
                                        <th width="10%">Transaction Id</th>
                                        <th width="10%">Number</th>
                                        <th width="10%">Addons</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Create Date</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (isset($data) && count($data) > 0)
                                        @foreach ($data as $key => $dm)
                                            @php
                                                $pendingDueHistory = null;
                                                if (get_class($dm) == 'App\Models\AddonsOrder' && isset($dm->paymentHistories)) {
                                                    $pendingDueHistory = $dm->paymentHistories->firstWhere('due_amount_status', 'pending_acceptance');
                                                }
                                            @endphp
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>
                                                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                </td>

                                                @if(get_class($dm) == 'App\Models\AddonsOrder')
                                                        <?php

                                                        if (!empty($dm->plan_id)) {
                                                            $plan = DB::table('plans')
                                                                ->where('id', $dm->plan_id)
                                                                ->first();
                                                        } else {

                                                            $combopackages = $dm->combopackages;

                                                            if (isset($combopackages) && isset($combopackages[0]['id'])) {
                                                                $plan = DB::table('plans')
                                                                    ->where('id', $combopackages[0]['id'])
                                                                    ->first();
                                                            }
                                                        }
                                                        ?>
                                                    <td>
                                                        <strong>
                                                            @if (!empty($dm->plan_id))
                                                                {{ $dm->plan_type }}
                                                            @else
                                                                @if (isset($dm->combopackages))
                                                                    @foreach ($dm->combopackages as $key => $ite)
                                                                        @if (isset($key))
                                                                            {{ isset($ite['type']) ? +(int)$ite['type'] : '' }}
                                                                        @else
                                                                            {{ isset($ite['type']) ? (int)$ite['type'] : '' }}
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <span
                                                                        style="color: red;">No packages selected</span>
                                                                @endif
                                                            @endif

                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <strong>
                                                            @if (!empty($dm->plan_id))
                                                                {{ $dm->PlanName->name??'' }}
                                                            @else
                                                                @if ($dm->combopackages)
                                                                    @foreach ($dm->combopackages as $key => $ite)
                                                                        @php
                                                                            $planName = '';
                                                                            if(isset($ite['id'])){
                                                                                $planName = DB::table('plans')
                                                                                ->where('id', $ite['id'])
                                                                                ->first();
                                                                            }
                                                                        @endphp

                                                                        @if (isset($key))
                                                                            +{{ isset($planName->name) ? $planName->name : '' }}
                                                                        @else
                                                                            {{ isset($planName->name) ? $planName->name : '' }}
                                                                        @endif

                                                                    @endforeach
                                                                @else
                                                                    <span
                                                                        style="color: red;">No packages selected</span>
                                                                @endif
                                                            @endif

                                                        </strong>
                                                    </td>
                                                    <td>
                                                            <?php
                                                            $storesss = DB::table('stores')
                                                                ->where('id', $dm->store_id)
                                                                ->first();
                                                            ?>
                                                        {{ $storesss->name ?? 'empty' }}
                                                    </td>
                                                    <td>
                                                        {{ "Empty" }}
                                                    </td>
                                                    <td>
                                                        <strong>
                                                            @if (!empty($dm->plan_id))
                                                                {{ $dm->plan_month??'' }}
                                                            @else
                                                                @if ($dm->combopackages)
                                                                    @foreach ($dm->combopackages as $key => $ite)
                                                                        @if ($key)
                                                                            + {{ $ite['month']??'' }}
                                                                        @else
                                                                            {{ $ite['month']??'' }}
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <span
                                                                        style="color: red;">No packages selected</span>
                                                                @endif
                                                            @endif

                                                        </strong>
                                                    </td>
                                                    <td>
                                                        {{ $dm->total }} TK
                                                        @if(!is_null($dm->paid_amount) || !is_null($dm->due_amount))
                                                            <div class="small text-muted mt-1">
                                                                Paid: {{ (float) ($dm->paid_amount ?? 0) }} TK
                                                            </div>
                                                            <div class="small {{ ($dm->due_amount ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                                                Due: {{ (float) ($dm->due_amount ?? 0) }} TK
                                                            </div>
                                                            <div class="small text-muted">
                                                                {{ ucfirst(str_replace('_', ' ', $dm->due_amount_status ?? 'paid')) }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                        <?php
                                                        $user = DB::table('users')
                                                            ->where('id', $dm->user_id)
                                                            ->first();
                                                        ?>
                                                    <td>
                                                        <a href="{{ URL::to('/') }}/client/view/{{ $user->id ?? "" }}">{{ $user->name ?? '' }}</a>
                                                    </td>
                                                    <td>{{ $dm->payment_method }}</td>
                                                    <td>{{ $dm->transaction_id == env('NAGAD_PAYMENT_ACCEPT_CODE') ? "Cash Payment" : $dm->transaction_id }}</td>
                                                    <td>{{ $dm->payment_number }}</td>
                                                    <td>

                                                            <?php
                                                            $addonss = DB::table('addons')
                                                                ->where('plan_order_id', $dm->id)
                                                                ->get();
                                                            $i = 1;
                                                            ?>
                                                        @php
                                                            $countryCode = getVisitorInfo()->countryCode ?? "";
                                                        @endphp

                                                        @if (isset($dm->addons) && is_array($dm->addons) && count($dm->addons) > 0)
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="exampleModal{{ $key }}"
                                                                 tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                 aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">
                                                                                Addons Info
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="table-responsive">
                                                                                <table class="table">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>SL</th>
                                                                                        <th>Name</th>
                                                                                        <th>Month</th>
                                                                                        <th>Type</th>
                                                                                        <th>Price</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @if (isset($dm->addons)  && count($dm->addons) > 0)
                                                                                        @foreach ($dm->addons as $adonsItem)
                                                                                            <tr>
                                                                                                <td>
                                                                                                    {{ $i++ }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ $adonsItem['title'] }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ $adonsItem['months'] }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ $adonsItem['type'] }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    @if($countryCode == "BD")
                                                                                                        {{ isset($adonsItem['offerprice']) && $adonsItem['offerprice'] != 0  && $adonsItem['offerprice'] != "" ? $adonsItem['offerprice'] : ($adonsItem['price'] ?? 0) }}
                                                                                                    @else
                                                                                                        {{ isset($adonsItem['usd_offer_price']) && $adonsItem['usd_offer_price'] != 0  && $adonsItem['usd_offer_price'] != "" ? $adonsItem['usd_offer_price'] : ($adonsItem['usd_price'] ?? 0) }}
                                                                                                    @endif
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    @endif
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">Close
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!----Modal End---->
                                                        @endif
                                                        @if (isset($dm->addons)  && is_array($dm->addons) && count($dm->addons) > 0)
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                               class="btn btn-info btn-sm"
                                                               data-bs-target="#exampleModal{{ $key }}"
                                                               id="viewaddons" data-id="{{ $dm->id }}">view</a>
                                                    </td>
                                                @endif
                                                    <td>
                                                        @if($pendingDueHistory)
                                                            Due Request Pending
                                                        @else
                                                            {{ $dm->status }}
                                                        @endif
                                                    </td>
                                                    <td>{{ date('d-m-Y H:m:s', strtotime($dm->created_at)) }}</td>
                                                <td>
                                                    @if ($pendingDueHistory)
                                                        <a href="{{ route('superadmin.planorder.view.invoice', ['id' => $dm->id, 'payment_history_id' => $pendingDueHistory->id]) }}"
                                                           class="btn btn-secondary">View</a>
                                                        <a href="{{ route('superadmin.orderPlanrequest.accept-due', $pendingDueHistory->id) }}"
                                                           onclick="return confirm('Are you sure, you want to accept this due payment request?')"
                                                           class="btn btn-primary mt-2">Accept Due Request</a>
                                                    @elseif ($dm->status == "Complete")
                                                        <a href="{{ route('superadmin.planorder.view.invoice', $dm->id) }}"
                                                           class="btn btn-secondary">View</a>
                                                        @if (($dm->due_amount ?? 0) > 0)
                                                            <button type="button"
                                                                    class="btn btn-warning btn-sm mt-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#updatePaymentModal{{ $dm->id }}">
                                                                Update Due
                                                            </button>

                                                            <div class="modal fade" id="updatePaymentModal{{ $dm->id }}"
                                                                 tabindex="-1"
                                                                 aria-labelledby="updatePaymentModalLabel{{ $dm->id }}"
                                                                 aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <form method="post"
                                                                              action="{{ route('superadmin.orderPlanrequest.update-payment', $dm->id) }}">
                                                                            @csrf
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="updatePaymentModalLabel{{ $dm->id }}">
                                                                                    Update Due Payment
                                                                                </h5>
                                                                                <button type="button" class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body text-start">
                                                                                <div class="mb-3">
                                                                                    <strong>Order:</strong> {{ $dm->order_no ?? ('#' . $dm->id) }}<br>
                                                                                    <strong>Current Paid:</strong> {{ (float) ($dm->paid_amount ?? 0) }} TK<br>
                                                                                    <strong>Current Due:</strong> {{ (float) ($dm->due_amount ?? 0) }} TK
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Additional Paid Amount</label>
                                                                                    <input type="number"
                                                                                           step="0.01"
                                                                                           min="0.01"
                                                                                           max="{{ (float) ($dm->due_amount ?? 0) }}"
                                                                                           class="form-control"
                                                                                           name="additional_paid_amount"
                                                                                           required>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Payment Method</label>
                                                                                    <select class="form-control" name="payment_method" required>
                                                                                        <option value="hand_cash" {{ $dm->payment_method === 'hand_cash' ? 'selected' : '' }}>Hand Cash</option>
                                                                                        <option value="bank_transfer" {{ $dm->payment_method === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                                                                        <option value="bkash" {{ $dm->payment_method === 'bkash' ? 'selected' : '' }}>bKash</option>
                                                                                        <option value="nagad" {{ $dm->payment_method === 'nagad' ? 'selected' : '' }}>Nagad</option>
                                                                                        <option value="amarpay" {{ $dm->payment_method === 'amarpay' ? 'selected' : '' }}>AmarPay</option>
                                                                                        <option value="paypal" {{ $dm->payment_method === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                                                                        <option value="due" {{ $dm->payment_method === 'due' ? 'selected' : '' }}>Due</option>
                                                                                    </select>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Payment Number</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="payment_number"
                                                                                           value="{{ $dm->payment_number }}">
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Transaction ID</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="transaction_id"
                                                                                           value="{{ $dm->transaction_id }}">
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Bank Name</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="bank_name"
                                                                                           value="{{ $dm->bank_name }}">
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Account Number</label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           name="account_number"
                                                                                           value="{{ $dm->account_number }}">
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Note</label>
                                                                                    <textarea class="form-control"
                                                                                              name="note"
                                                                                              rows="3"
                                                                                              placeholder="Optional note about this due payment update"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary"
                                                                                        data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit" class="btn btn-primary">Save Payment</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('superadmin.newacceptplanorder.accept', $dm->id) }}"
                                                           id="acceptBtn{{$dm->id}}"
                                                           onclick="return handleAcceptButtonClick(event,{{$dm->id}})"
                                                           class="btn btn-primary">Accept</a>

                                                        <a href="{{ route('superadmin.planorder.new.reject', $dm->id) }}"
                                                           onclick="return confirm('Are you sure, you want to Reject this Order?')"
                                                           class="btn btn-secondary">Reject</a>
                                                    @endif
                                                </td>
                                                @else
                                                    <td>
                                                        <strong><span
                                                                style="color: red;">No packages selected</span></strong>
                                                    </td>
                                                    <td>
                                                        <strong><span
                                                                style="color: red;">No packages selected</span></strong>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $storesss = DB::table('stores')
                                                                ->where('id', $dm->store_id)
                                                                ->first();
                                                        @endphp
                                                        {{ $storesss->name ?? 'Empty' }}
                                                    </td>
                                                    <td>{{ $dm->getModulus->name }}</td>
                                                    <td>
                                                        <strong><span
                                                                style="color: red;">No packages selected</span></strong>
                                                    </td>
                                                    <td>{{ $dm->price }}</td>
                                                    <td>
                                                        <strong><span
                                                                style="color: red;">No packages selected</span></strong>
                                                    </td>
                                                    <td>{{ $dm->payment_type }}</td>
                                                    <td>{{ $dm->transaction_id }}</td>
                                                    <td>{{ $dm->number }}</td>
                                                    <td>
                                                        {{ 'Empty' }}
                                                    </td>
                                                    <td>
                                                        @if(isset($dm->status))
                                                            @if($dm->status == 1)
                                                                {{ 'Accepted' }}
                                                            @else
                                                                {{ 'Rejected' }}
                                                            @endif
                                                        @else
                                                            {{ 'Pending' }}
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($dm->created_at)->format('d-m-y h:i:s A') }}</td>

                                                    <td>
                                                        @if (isset($dm->status))
                                                            {{ 'Not Permitted' }}
                                                        @else
                                                            <a href="{{ route('superadmin.modulus.accept', $dm->id) }}"
                                                               onclick="return confirm('Are you sure, you want to accpect this Order?')"
                                                               class="btn btn-primary">Accept</a>
                                                            <a href="{{ route('superadmin.modulus.reject', $dm->id) }}"
                                                               onclick="return confirm('Are you sure, you want to Reject this Order?')"
                                                               class="btn btn-secondary">Reject</a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex mt-4 mb-4 justify-content-between">
                                <div class="d-flex justify-content-center align-items-center text-bold">
                                    Total Amount: <span class="px-2" style="color: #ff5733;">{{ $totalAmount }}</span>
                                    TK
                                </div>
                                {{ $data->appends(['from_date' => request('from_date'), 'to_date' => request('to_date')])->links() }}
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
        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }


        function handleAcceptButtonClick(e, id) {
            e.preventDefault();

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to accept this order?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Accept it!",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    var button = $("#acceptBtn" + id);

                    if (button.length) {
                        // Disable button and change text
                        button.prop("disabled", true).text("Processing...");

                        // Redirect to the href URL
                        window.location.href = button.attr("href");
                    }
                }
            });
        }


    </script>
@endpush
