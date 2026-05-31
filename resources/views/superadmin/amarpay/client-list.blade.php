@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.amarpay.nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Client List</h4>
                </div>
            </div>
            <div class="row productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-3">
                                    <form method="GET"
                                          action="{{ route('superadmin.amaypay.client.list') }}">
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
                                        <th width="10%">Payment Gateway</th>
                                        <th width="10%">Withdraw Amount</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Create Date</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($kycList) && count($kycList)>0)
                                        @foreach($kycList as $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($kycList->currentPage() - 1) * $kycList->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->store->user_id ?? "" }}</td>
                                                <td>
                                                    {{ $item->store->name ?? "" }}
                                                    <br>
                                                    {{ $item->store->url ?? "" }}
                                                </td>
                                                <td>{{ ucfirst($item->payment_gatway) ?? "" }}</td>
                                                <td id="setWithdraw" class="d-flex flex-column gap-2">
                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2">
                                                        Min: <input name="minWithdraw" type="text"
                                                                    class="form-control"
                                                                    id="minWithdraw{{ $item->id }}"
                                                                    onchange="setWithdraw({{ $item->id }}, {{ $item->header->id }})"
                                                                    value="{{ $item->header->balance_min_withdraw ?? "" }}">
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-center align-items-center gap-2">
                                                        Max: <input name="maxWithdraw" type="text"
                                                                    class="form-control"
                                                                    id="maxWithdraw{{ $item->id }}"
                                                                    onchange="setWithdraw({{ $item->id }}, {{ $item->header->id }})"
                                                                    value="{{ $item->header->balance_max_withdraw ?? "" }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex form-check form-switch"
                                                         style="text-align:center;margin-left: -40px">
                                                        <input class="form-check-input switchstatus"
                                                               type="checkbox"
                                                               id="flexSwitchCheckChecked"
                                                               data-id="{{ $item->id }}"
                                                               style="margin:0 auto;"
                                                               @if ($item->status == 1) checked @endif
                                                        >
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                </td>
                                                <td>{{date('d-m-Y', strtotime($item->created_at))}}</td>
                                                <td>
                                                    <a href="{{route('superadmin.accept.kyc.view',$item->id)}}"
                                                       class="btn btn-info">View</a>
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
                                    {!! $kycList->links() !!}
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

        function setWithdraw(id, header_id) {
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
                    id: header_id,
                    min: min,
                    max: max,
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
    </script>
@endpush
