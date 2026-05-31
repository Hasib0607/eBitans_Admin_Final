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
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.dropship.top_nav_menu')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-12">
                    <h4>All Dropshipper</h4>
                </div>
            </div>

            <div class="row mt-1 productlist">
                <div class="col-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <div class="row">
                                <form action="{{ route('superadmin.dropshipper') }}" method="get"
                                      class="row">
                                    <div class="col-md-2">
                                        <input type="date" name="from_date" id="from_date"
                                               value="{{ $from_date ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col colToText text-center mt-1">
                                        <label for="to_date">To</label>
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

                                    <div class="col-md-1" style="padding-left:0px;">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" style="width:100%">
                                    <thead>
                                    <tr>
                                        <td width="3%">SL</td>
                                        <th width="12%">Name</th>
                                        <th width="10%">URL</th>
                                        <th width="10%">Type</th>
                                        <th width="15%">Total commission</th>
                                        <th width="10%">Commission percentage</th>
                                        <th width="10%">Overflow Commission</th>
                                        <th width="10%">Pull Order</th>
                                        <th width="10%">Created At</th>
                                        <th width="10%">Option</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($stores))
                                        @foreach ($stores as $key => $store)
                                            <tr>
                                                <td>{{ ($stores->currentPage() - 1) * $stores->perPage() + $loop->iteration }}</td>
                                                <td>{{ $store->name?? 'Unauthorized' }}</td>
                                                <td>{{ $store->url ?? 'Unauthorized' }}</td>
                                                <td>{{ $store->type ?? 'Unauthorized' }}</td>
                                                <td>{{ $store->balance }} BDT</td>
                                                <td id="okCommnet">
                                                    <input name="comment" type="text" class="form-control"
                                                           id="comment{{ $store->id }}"
                                                           onchange="okComment({{ $store->id }})"
                                                           value="{{ $store->dropship_commission }}">
                                                </td>
                                                <td id="overFlowCommission">
                                                    <input name="comment" type="text" class="form-control"
                                                           id="overFlowCommission{{ $store->id }}"
                                                           onchange="overFlowCommission({{ $store->id }})"
                                                           value="{{ $store->overflow_commission }}">
                                                </td>
                                                <td id="pullCommission">
                                                    <select name="pullCommission" class="form-control cursor-pointer"
                                                            id="pullCommission{{ $store->id }}"
                                                            onchange="pullCommission({{ $store->id }})">
                                                        <option
                                                            {{ $store->order_pull == "0" ? "selected" : "" }} value="0">
                                                            When
                                                            place
                                                            order
                                                        </option>
                                                        <option
                                                            {{ $store->order_pull == "1" ? "selected" : "" }} value="1">
                                                            When
                                                            delivered order
                                                        </option>
                                                    </select>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($store->created_at)->format('d-m-y h:i:s A') }}</td>
                                                <td>
                                                    <a href="{{ route('superadmin.dropship.order.details', ['id' => $store->id]) }}"
                                                       class="btn btn-info">Details</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10" class="text-center">
                                                No Record Found
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="d-flex mt-4 mb-4 justify-content-between">
                                    {!! $stores->appends(['search' => request('search'), 'from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

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
                url: "{{ route('superadmin.update.dropship.commission') }}",
                data: {
                    id: id,
                    commission: text
                },
                success: function (data) {
                    if (data.status) {
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: 'success',
                            type: 'success',
                        });
                    } else {
                        Swal.fire({
                            title: "Warning",
                            text: data.message,
                            icon: 'warning',
                            type: 'warning',
                        });
                    }
                }
            });
        }

        const pullCommission = (id) => {
            var order_pull = $('#pullCommission' + id).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('superadmin.update.dropship.order.pull') }}",
                data: {
                    id: id,
                    order_pull: order_pull
                },
                success: function (data) {
                    if (data.status) {
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: 'success',
                            type: 'success',
                        });
                    } else {
                        Swal.fire({
                            title: "Warning",
                            text: data.message,
                            icon: 'warning',
                            type: 'warning',
                        });
                    }
                }
            });
        }

        const overFlowCommission = (id) => {
            var overflow_commission = $('#overFlowCommission' + id).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('superadmin.update.dropship.order.overflow.commission') }}",
                data: {
                    id: id,
                    overflow_commission: overflow_commission
                },
                success: function (data) {
                    if (data.status) {
                        Swal.fire({
                            title: "Success",
                            text: data.message,
                            icon: 'success',
                            type: 'success',
                        });
                    } else {
                        Swal.fire({
                            title: "Warning",
                            text: data.message,
                            icon: 'warning',
                            type: 'warning',
                        });
                    }
                }
            });
        }
    </script>

@endpush
