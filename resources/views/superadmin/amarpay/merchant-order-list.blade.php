@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.amarpay.nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row mb-6 d-flex justify-content-center">
                <div class="col-lg-3  d-flex justify-content-center">
                    <div class="m-left bg-info rounded shadow"
                         style="width:70%; height: 100px; background-color: white">
                        <p class="text-white text-center pt-1 pb-0 mb-0 text-bold">Total Amount</p>
                        <h3 class="text-white text-center">{{ $totalMerchantAmount ?? 0 }} TK</h3>
                    </div>
                </div>
                <div class="col-lg-3  d-flex justify-content-center">
                    <div class="m-left bg-secondary rounded shadow"
                         style="width:70%; height: 100px; background-color: white">
                        <p class="text-white text-center pt-1 pb-0 mb-0 text-bold">Withdraw Amount</p>
                        <h3 class="text-white text-center">{{ $withdrawAmount ?? 0 }} TK</h3>
                    </div>
                </div>
                <div class="col-lg-3  d-flex justify-content-center">
                    <div class="m-left bg-warning rounded shadow"
                         style="width:70%; height: 100px; background-color: white">
                        <p class="text-white text-center pt-1 pb-0 mb-0 text-bold">Pending Amount</p>
                        <h3 class="text-white text-center">{{ $pendingAmount ?? 0 }} TK</h3>
                    </div>
                </div>
                <div class="col-lg-3  d-flex justify-content-center">
                    <div class="m-left bg-primary rounded shadow"
                         style="width:70%; height: 100px; background-color: white">
                        <p class="text-white text-center pt-1 pb-0 mb-0 text-bold">Total Profit</p>
                        <h3 class="text-white text-center">{{ $totalProfit ?? 0 }} TK</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h4>All Order List</h4>
                </div>
            </div>
            <div class="row productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-3">
                                    <form method="GET"
                                          action="{{ route('superadmin.merchant.order.list',['store' => $store]) }}">
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
                                        <th width="20%">Order ID</th>
                                        <th width="20%">Customer ID</th>
                                        <th width="20%">Transaction ID</th>
                                        <th width="20%">Order Amount</th>
                                        <th width="20%">Commission(%)</th>
                                        <th width="20%">Total Commission</th>
                                        <th width="20%">Store Amount</th>
                                        <th width="20%">Currency</th>
                                        <th width="20%">Payment type</th>
                                        <th width="20%">Card Number</th>
                                        <th width="20%">Bank Trxid</th>
                                        <th width="20%">Approval Code</th>
                                        <th width="20%">Payment Processor</th>
                                        <th width="20%">Processed Date</th>
                                        <th width="20%">Processing Commission(%)</th>
                                        <th width="20%">Processing Fee</th>
                                        <th width="20%">Total Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($orders) && count($orders)>0)
                                        @foreach($orders as $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->order_id ?? "" }}</td>
                                                <td>{{ $item->customer_id ?? "" }}</td>
                                                <td>{{ $item->transactionId ?? "" }}</td>
                                                <td>{{ $item->amountPaid ?? "" }}</td>
                                                <td>{{ $item->merchant_processing_ratio ?? "" }}</td>
                                                <td>{{ $item->merchant_processing_charge ?? "" }}</td>
                                                <td>{{ $item->merchant_amount ?? "" }}</td>
                                                <td>{{ $item->currency ?? "" }}</td>
                                                <td>{{ $item->payment_type ?? "" }}</td>
                                                <td>{{ $item->cardnumber ?? "" }}</td>
                                                <td>{{ $item->bank_trxid ?? "" }}</td>
                                                <td>{{ $item->approval_code ?? "" }}</td>
                                                <td>{{ $item->payment_processor ?? "" }}</td>
                                                <td>{{ $item->date_processed ?? "" }}</td>
                                                <td>{{ $item->processing_ratio ?? "" }}</td>
                                                <td>{{ $item->processing_charge ?? "" }}</td>
                                                <td>{{ $item->store_amount ?? "" }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="15">No Record Found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="">
                                    {!! $orders->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

