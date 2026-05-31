@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.amarpay.nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Client List</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('superadmin.amaypay.withdraw.request.list', ['status' => 'completed']) }}"
                               class="btn btn-primary"
                               style="display:block;border-radius:0px !important">Completed List</a>
                        </li>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('superadmin.amaypay.withdraw.request.list', ['status' => 'approved']) }}"
                               class="btn btn-primary"
                               style="display:block;border-radius:0px !important">Approved List</a>
                        </li>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('superadmin.amaypay.withdraw.request.list', ['status' => 'reject']) }}"
                               class="btn btn-primary"
                               style="display:block;border-radius:0px !important">Rejected List</a>
                        </li>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('superadmin.amaypay.withdraw.request.list', ['status' => 'pending']) }}"
                               class="btn btn-primary" style="display:block;border-radius:0px !important">Pending
                                List</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-3">
                                    <form method="GET"
                                          action="{{ route('superadmin.amaypay.withdraw.request.list') }}">
                                        <div class="row">
                                            <div class="col-md-8" style="padding-right:1px;">
                                                <input type="text" class="form-control" name="search"
                                                       value="{{ $search ?? "" }}">
                                            </div>
                                            <div class="col-md-4" style="padding-left:5px;">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{Session::get('success_message')}}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%">SL.</th>
                                        <th width="20%">User ID</th>
                                        <th width="20%">Store URL</th>
                                        <th width="10%">Withdraw Amount</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Create Date</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($items) && count($items)>0)
                                        @foreach($items as $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->user_id ?? "" }}</td>
                                                <td>
                                                    {{ $item->store->name ?? "" }}
                                                    <br>
                                                    {{ $item->store->url ?? "" }}
                                                </td>
                                                <td>
                                                    {{ $item->withdraw_amount ?? "" }}
                                                </td>
                                                <td>
                                                    @if($item->status == 0)
                                                        <span class="text-warning">Pending</span>
                                                    @elseif($item->status == 1)
                                                        <span class="text-info">Approved</span>
                                                    @elseif($item->status == 2)
                                                        <span class="text-success">Completed</span>
                                                    @elseif($item->status == 3)
                                                        <span class="text-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>
                                                    <button
                                                        class="btn btn-info"
                                                        title="Show Withdraw Details"
                                                        data-account="{{ $item->kyc->bank_account_number }}"
                                                        data-bank="{{ $item->kyc->online_bank }}"
                                                        onclick="showBankDetails(this)">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    @if($item->status == 0)
                                                        <a href="{{route('superadmin.amaypay.withdraw.status.change', ['id' => $item->id, 'status' => 'approved'])}}"
                                                           onclick="return confirm('Are you sure, you want to Approved this Request?')"
                                                           class="btn btn-success">Approved</a>
                                                        <a href="{{route('superadmin.amaypay.withdraw.status.change', ['id' => $item->id, 'status' => 'reject'])}}"
                                                           onclick="return confirm('Are you sure, you want to Reject this Request?')"
                                                           class="btn btn-danger">Reject</a>
                                                    @elseif($item->status == 1)
                                                        <a href="{{route('superadmin.amaypay.withdraw.status.change', ['id' => $item->id, 'status' => 'completed'])}}"
                                                           onclick="return confirm('Are you sure, you want to Completed this Request?')"
                                                           class="btn btn-warning">Completed</a>
                                                    @elseif($item->status == 2)
                                                        <span class="text-success">Completed</span>
                                                    @elseif($item->status == 3)
                                                        <a href="{{route('superadmin.amaypay.withdraw.status.change', ['id' => $item->id, 'status' => 'pending'])}}"
                                                           onclick="return confirm('Are you sure, you want to Pending this Request?')"
                                                           class="btn btn-warning">Pending</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No Record Found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="">
                                    {!! $items->links() !!}
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
            $(".switchstatus").on("change", function () {
                $url = "{{ route('superadmin.merchant.active.status') }}";
                var value = $(this).val();
                var id = $(this).data('id');

                $.get($url, {
                    value: value,
                    id: id
                }, function (response) {
                    if (response.status) {
                        toastr.success("Status has been changed successfully!");
                    } else {
                        toastr.error("Status did not changed!");
                    }
                });
            });
        });

        function setWithdraw(id) {
            var min = $('#minWithdraw' + id).val();
            var max = $('#maxWithdraw' + id).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('superadmin.merchant.setWithdraw') }}",
                data: {
                    id: id,
                    min: min,
                    max: max
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success("Withdraw amount save");
                    } else {
                        toastr.error("Withdraw amount not save");
                    }
                }
            });
        }

        const showBankDetails = (el) => {
            const accountNumber = el.dataset.account || 'N/A';
            const onlineBank = el.dataset.bank || 'N/A';

            Swal.fire({
                title: 'Withdrawal Details',
                html: `
        <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
            <tbody>
                <tr>
                    <td style="width: 30%; padding: 8px; border: 1px solid #dee2e6; font-weight: bold; vertical-align: top;">Bank Account</td>
                    <td style="width: 70%; padding: 8px; border: 1px solid #dee2e6; word-break: break-all; vertical-align: top;">${accountNumber}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #dee2e6; font-weight: bold; vertical-align: top;">Online Transfer</td>
                    <td style="padding: 8px; border: 1px solid #dee2e6; word-break: break-word; vertical-align: top;">${onlineBank}</td>
                </tr>
            </tbody>
        </table>
    `,
                icon: 'info',
                confirmButtonText: 'Close',
                width: '600px'
            });
        };


    </script>
@endpush
