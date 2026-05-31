@extends('admin.layouts.main')

@push('styles')
    <style>
        .table td, .table th {
            vertical-align: middle !important;
        }

        .filterbtn {
            min-width: 44px;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: end;
            margin-top: 20px;
        }

        .action-btns {
            display: flex;
            gap: 6px;
            flex-wrap: nowrap;
            align-items: center;
        }

        .action-btns .btn {
            white-space: nowrap;
            min-width: 110px;
            text-align: center;
        }

        #desktoptable table {
            table-layout: fixed;
        }

        #desktoptable td,
        #desktoptable th {
            vertical-align: middle !important;
        }

        .mobile-card-table th,
        .mobile-card-table td {
            padding: 8px !important;
        }

        .export-note {
            font-size: 12px;
            color: #6c757d;
            margin-top: 6px;
        }
    </style>
@endpush

@section('content')
    <main class="main-content position-relative border-radius-lg">
        @include('admin.order.share.order-nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            রিটার্ন / ক্যানসেল / রিস্টক অর্ডার
                        @else
                            Returned / Cancelled / Restocked Orders
                        @endif
                    </h4>
                </div>
                <div class="col-md-6 text-end">
                    <a href="javascript:void(0);"
                       onclick="exportSelectedOrders()"
                       class="btn btn-secondary">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            এক্সপোর্ট
                        @else
                            Excel Export
                        @endif
                    </a>
                </div>
            </div>

            <div class="row mt-4 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('admin.returned') }}" method="get" id="filterForm">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label mb-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অনুসন্ধান
                                            @else
                                                Search
                                            @endif
                                        </label>
                                        <input type="text"
                                               name="search"
                                               class="form-control"
                                               value="{{ request('search') }}"
                                               placeholder="Reference / Phone / Name">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label mb-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                তারিখ
                                            @else
                                                Date
                                            @endif
                                        </label>
                                        <input type="date" name="date" class="form-control"
                                               value="{{ request('date') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label mb-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                গ্রাহকের ধরন
                                            @else
                                                Customer Type
                                            @endif
                                        </label>
                                        <select class="form-select" name="type">
                                            <option value="all" {{ request('type', 'all') == 'all' ? 'selected' : '' }}>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সব
                                                @else
                                                    All
                                                @endif
                                            </option>
                                            <option value="walking_customer" {{ request('type') == 'walking_customer' ? 'selected' : '' }}>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    হাঁটা গ্রাহক
                                                @else
                                                    Walking Customer
                                                @endif
                                            </option>
                                            <option value="website_customer" {{ request('type') == 'website_customer' ? 'selected' : '' }}>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ওয়েবসাইট গ্রাহক
                                                @else
                                                    Website Customer
                                                @endif
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label mb-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্ট্যাটাস
                                            @else
                                                Status
                                            @endif
                                        </label>
                                        <select class="form-select" name="status">
                                            <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সব অর্ডার
                                                @else
                                                    All Orders
                                                @endif
                                            </option>
                                            <option value="Returned" {{ request('status') == 'Returned' ? 'selected' : '' }}>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    রিটার্ন প্রোডাক্ট
                                                @else
                                                    Returned Product
                                                @endif
                                            </option>
                                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ক্যানসেল প্রোডাক্ট
                                                @else
                                                    Cancelled Product
                                                @endif
                                            </option>
                                            <option value="Restock" {{ request('status') == 'Restock' ? 'selected' : '' }}>
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    রিস্টক সম্পন্ন
                                                @else
                                                    Restock Completed
                                                @endif
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ফিল্টার
                                            @else
                                                Filter
                                            @endif
                                        </button>

                                        <a href="{{ route('admin.returned') }}" class="btn btn-info filterbtn"
                                           style="background-color: #7b809a;">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif

                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="3%">
                                            <input type="checkbox" id="checkedAll">
                                        </th>
                                        <th width="12%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অর্ডারের তারিখ
                                            @else
                                                Order Date
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                অর্ডার আইডি
                                            @else
                                                Order ID
                                            @endif
                                        </th>
                                        <th width="14%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                গ্রাহকের নাম
                                            @else
                                                Customer Name
                                            @endif
                                        </th>
                                        <th width="12%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                গ্রাহক ফোন
                                            @else
                                                Customer Phone
                                            @endif
                                        </th>
                                        <th width="8%">Subtotal</th>
                                        <th width="8%">Discount</th>
                                        <th width="8%">Shipping</th>
                                        <th width="8%">Tax</th>
                                        <th width="8%">Total</th>
                                        <th width="8%">Status</th>
                                        <th width="8%">Type</th>
                                        <th width="18%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td>
                                                <input type="checkbox"
                                                       class="checkSingle"
                                                       name="selectedid"
                                                       value="{{ $order->id }}">
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                                            <td>{{ $order->reference_no }}</td>
                                            <td>{{ $order->name }}</td>
                                            <td>{{ $order->phone }}</td>
                                            <td>{{ $order->subtotal }}</td>
                                            <td>{{ $order->discount }}</td>
                                            <td>{{ $order->shipping }}</td>
                                            <td>{{ $order->tax }}</td>
                                            <td>{{ $order->total }}</td>
                                            <td>
                                                <span class="badge"
                                                      @if ($order->status == 'Pending') style="background-color:#f0ad4e;color:#fff"
                                                      @elseif ($order->status == 'On Hold') style="background-color:#777;color:#fff"
                                                      @elseif ($order->status == 'Restock') style="background-color:#5cb85c;color:#fff"
                                                      @elseif ($order->status == 'Delivered') style="background-color:#d9534f;color:#fff"
                                                      @elseif ($order->status == 'Payment Failed') style="background-color:#d9534f;color:#fff"
                                                      @elseif ($order->status == 'Processing') style="background-color:#5cb85c;color:#fff"
                                                      @elseif ($order->status == 'Shipping') style="background-color:#337ab7;color:#fff"
                                                      @elseif ($order->status == 'Completed') style="background-color:#a2cca2;color:#fff"
                                                      @elseif ($order->status == 'Cancelled') style="background-color:#dba4a2;color:#fff"
                                                      @elseif ($order->status == 'Returned') style="background-color:#f0ad4e;color:#fff" @endif>
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td>{{ $order->type }}</td>
                                            <td>
                                                <div class="action-btns">
                                                    <a href="{{ route('admin.order.view', $order->id) }}"
                                                       class="btn btn-info btn-sm">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            দেখুন
                                                        @else
                                                            View
                                                        @endif
                                                    </a>

                                                    @if (in_array($order->status, ['Returned', 'Cancelled']))
                                                        <a href="{{ route('admin.order.restock', $order->id) }}"
                                                        onclick="return confirm('Are you sure you want to add quantity?')"
                                                        class="btn btn-primary btn-sm">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                রি স্টক
                                                            @else
                                                                Restock
                                                            @endif
                                                        </a>
                                                    @elseif ($order->status == 'Restock')
                                                        <button type="button" class="btn btn-success btn-sm" disabled>
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                রিস্টক সম্পন্ন
                                                            @else
                                                                Restocked
                                                            @endif
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center">No data found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive" id="mobiletable">
                                <table class="table mobile-card-table" width="100%">
                                    @forelse ($orders as $key => $order)
                                        <tr class="mobilefirstrow">
                                            <th width="10%">
                                                <input type="checkbox"
                                                       class="checkSingle"
                                                       name="selectedid"
                                                       value="{{ $order->id }}">
                                            </th>
                                            <th width="25%" style="color:#f1593a">
                                                Reference No:
                                            </th>
                                            <td width="45%" style="color:black">
                                                {{ $order->reference_no }}
                                            </td>
                                            <td width="20%">
                                                <a href="#" class="toggler" data-prod-cat="{{ $key }}">
                                                    <i class="fa fa-arrow-down" id="show{{ $key }}" style="color:#f1593a"></i>
                                                    <i class="fa fa-arrow-up" id="up{{ $key }}" style="display:none"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Order Date</th>
                                            <td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Name</th>
                                            <td>{{ $order->name }}</td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Phone</th>
                                            <td>{{ $order->phone }}</td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Subtotal</th>
                                            <td>{{ $order->subtotal }}</td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Discount</th>
                                            <td>{{ $order->discount }}</td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Shipping</th>
                                            <td>{{ $order->shipping }}</td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Tax</th>
                                            <td>{{ $order->tax }}</td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Total</th>
                                            <td>{{ $order->total }}</td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge"
                                                      @if ($order->status == 'Pending') style="background-color:#f0ad4e;color:#fff"
                                                      @elseif ($order->status == 'On Hold') style="background-color:#777;color:#fff"
                                                      @elseif ($order->status == 'Restock') style="background-color:#5cb85c;color:#fff"
                                                      @elseif ($order->status == 'Delivered') style="background-color:#d9534f;color:#fff"
                                                      @elseif ($order->status == 'Payment Failed') style="background-color:#d9534f;color:#fff"
                                                      @elseif ($order->status == 'Processing') style="background-color:#5cb85c;color:#fff"
                                                      @elseif ($order->status == 'Shipping') style="background-color:#337ab7;color:#fff"
                                                      @elseif ($order->status == 'Completed') style="background-color:#a2cca2;color:#fff"
                                                      @elseif ($order->status == 'Cancelled') style="background-color:#dba4a2;color:#fff"
                                                      @elseif ($order->status == 'Returned') style="background-color:#f0ad4e;color:#fff" @endif>
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Order Type</th>
                                            <td>{{ $order->type }}</td>
                                            <td></td>
                                        </tr>
                                        <tr class="cat{{ $key }}" style="display:none">
                                            <th></th>
                                            <th>Action</th>
                                            <td>
                                                <div class="action-btns">
                                                    <a href="{{ route('admin.order.view', $order->id) }}"
                                                       class="btn btn-info btn-sm">
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            দেখুন
                                                        @else
                                                            View
                                                        @endif
                                                    </a>

                                                    @if (in_array($order->status, ['Returned', 'Cancelled']))
                                                        <a href="{{ route('admin.order.restock', $order->id) }}"
                                                        onclick="return confirm('Are you sure you want to add quantity?')"
                                                        class="btn btn-primary btn-sm">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                রি স্টক
                                                            @else
                                                                Restock
                                                            @endif
                                                        </a>
                                                    @elseif ($order->status == 'Restock')
                                                        <button type="button" class="btn btn-success btn-sm" disabled>
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                রিস্টক সম্পন্ন
                                                            @else
                                                                Restocked
                                                            @endif
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center">No data found</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>

                            <div class="pagination-wrapper">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="selectedExportIds" value="">
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const storageKey = 'returned_order_selected_ids';

            function getSelectedIds() {
                let selected = localStorage.getItem(storageKey);
                return selected ? JSON.parse(selected) : [];
            }

            function setSelectedIds(ids) {
                localStorage.setItem(storageKey, JSON.stringify(ids));
                $('#selectedExportIds').val(ids.join(','));
            }

            function syncCheckboxesFromStorage() {
                let selectedIds = getSelectedIds();

                $('.checkSingle').each(function () {
                    let rowId = $(this).val().toString();
                    $(this).prop('checked', selectedIds.includes(rowId));
                });

                let totalCheckboxes = $('.checkSingle').length;
                let checkedCheckboxes = $('.checkSingle:checked').length;

                $('#checkedAll').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
                $('#selectedExportIds').val(selectedIds.join(','));
            }

            syncCheckboxesFromStorage();

            $('#checkedAll').on('change', function () {
                let selectedIds = getSelectedIds();

                $('.checkSingle').each(function () {
                    let rowId = $(this).val().toString();

                    if ($('#checkedAll').is(':checked')) {
                        $(this).prop('checked', true);
                        if (!selectedIds.includes(rowId)) {
                            selectedIds.push(rowId);
                        }
                    } else {
                        $(this).prop('checked', false);
                        selectedIds = selectedIds.filter(id => id !== rowId);
                    }
                });

                setSelectedIds(selectedIds);
            });

            $(document).on('change', '.checkSingle', function () {
                let rowId = $(this).val().toString();
                let selectedIds = getSelectedIds();

                if ($(this).is(':checked')) {
                    if (!selectedIds.includes(rowId)) {
                        selectedIds.push(rowId);
                    }
                } else {
                    selectedIds = selectedIds.filter(id => id !== rowId);
                }

                setSelectedIds(selectedIds);

                let totalCheckboxes = $('.checkSingle').length;
                let checkedCheckboxes = $('.checkSingle:checked').length;
                $('#checkedAll').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
            });

            // Mobile toggler
            $('.toggler').on('click', function (e) {
                e.preventDefault();
                let id = $(this).data('prod-cat');
                $('.cat' + id).toggle();
                $('#show' + id).toggle();
                $('#up' + id).toggle();
            });

            // Reset selection when filter form submit? No.
            // Keep selection across pagination/filter pages intentionally.
        });

        function exportSelectedOrders() {
            let selectedIds = localStorage.getItem('returned_order_selected_ids');
            selectedIds = selectedIds ? JSON.parse(selectedIds) : [];

            let params = new URLSearchParams(window.location.search);

            if (selectedIds.length > 0) {
                params.set('ids', selectedIds.join(','));
            }

            window.location.href = `/orderexport?${params.toString()}`;
        }
    </script>
@endpush