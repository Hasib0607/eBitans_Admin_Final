@extends('admin.layouts.main')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include("admin.report.top-nav")

        <div class="container-fluid mt-2" id="toplist">
            <div class="row">
                <div class="col-md-8">
                    <h4>Product Transfer Report</h4>
                </div>
            </div>
            <div class="row mt-1 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <form action="{{ route('admin.report.productTransferReport') }}" method="get"
                                      class="row">
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
                                        <input type="date" name="to_date" id="to_date" value="{{ $to_date ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                                                   class="form-control">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;">
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
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="taskfilterresult">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="1%">SL</th>
                                        <th>Product Name</th>
                                        <th>Branch (From)</th>
                                        <th>Branch (To)</th>
                                        <th>Previous Qty</th>
                                        <th>Transfer Qty</th>
                                        <th>Present Qty</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($products) && count($products))
                                        @foreach ($products as $key => $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                                                <td class="text-truncate">{{ $item->product->name ?? "" }}</td>
                                                <td>{{ $item->branchFrom->name ?? "" }}</td>
                                                <td>{{ $item->branchTo->name ?? "" }}</td>
                                                <td>{{ $item->old_qty ?? "" }}</td>
                                                <td>{{ $item->transfer_qty ?? "" }}</td>
                                                <td>{{ number_format((float)($item->old_qty ?? 0) - (float)($item->transfer_qty ?? 0), 2) }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format("Y-m-d h:i:s A") }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8">
                                                No Record Found
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div>
                                    {!! $products->appends(['search' => request('search'),'from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

