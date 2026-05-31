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
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-6">
                    <h4>All Order</h4>
                </div>
                <div class="col-6">
                    <div class="text-end">
                        <button class="btn bg-orange-600 btn-danger mx-1px text-95"
                                data-bs-toggle="modal"
                                data-bs-target="#payCommission"
                                data-title="Commission pay">
                            Pay {{ $totalBalance . " " . $storeCurrency->symbol ?? '' }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="payCommission" tabindex="-1"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content"
                         style="background-color:transparent;border:0px">

                        <div class="modal-body" style="border:none">
                            <button class="btn btn-danger sm" data-bs-dismiss="modal"
                                    style="float: right; margin: 0px 8px;">X
                            </button>

                            <div class="row mt-1">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <form method="post"
                                                      action="{{ route("dropshipper.dropship.commission.pay") }}">
                                                    @csrf

                                                    <div class="">
                                                        <label>Pay amount</label>
                                                        <br>
                                                        <input name="amount" type="number"
                                                               class="form-control"
                                                               required pattern="[0-9]{11}" min="0.01" step="0.01"
                                                               value="{{ $totalBalance ?? "" }}">
                                                    </div>
                                                    @if($storeCurrency->id == 1 )
                                                        <div class="m-auto" style="width: fit-content;">
                                                            <button name="payment_method" type="submit"
                                                                    class="btn btn-primary mt-3"
                                                                    value="bkash">
                                                                Pay with Bkash
                                                            </button>
                                                        </div>
                                                        {{--                                                        <div class="m-auto" style="width: fit-content;">--}}
                                                        {{--                                                            <button name="payment_method" type="submit"--}}
                                                        {{--                                                                    class="btn btn-primary mt-1"--}}
                                                        {{--                                                                    value="amarpay">--}}
                                                        {{--                                                                Card Payment--}}
                                                        {{--                                                            </button>--}}
                                                        {{--                                                        </div>--}}
                                                    @else
                                                        <div class="m-auto" style="width: fit-content;">
                                                            <button name="payment_method" type="submit"
                                                                    class="btn mt-3"
                                                                    style="background-color: #ffc439"
                                                                    value="paypal">
                                                                <img width="100px" src="{{ asset("img/pngegg.png") }}"
                                                                     alt="paypal">
                                                            </button>
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-1 productlist">
                <div class="col-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <div class="row">
                                <form action="{{ route('dropshipper.dropship.commission') }}" method="get"
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
                                        <th width="7%">Order ID</th>
                                        <th width="10%">Order Amount</th>
                                        <th width="10%">Commission Percentage</th>
                                        <th width="10%">Payment Amount</th>
                                        <th width="10%">Method</th>
                                        <th width="10%">Number</th>
                                        <th width="10%">Transaction ID</th>
                                        <th width="10%">DR</th>
                                        <th width="10%">CR</th>
                                        <th width="10%">Balance</th>
                                        <th width="10%">Created At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($storeOrders))
                                        @foreach ($storeOrders as $key => $item)
                                            <tr>
                                                <td>{{ ($storeOrders->currentPage() - 1) * $storeOrders->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->order->reference_no ?? '' }}</td>
                                                <td>{{ isset($item->product_order_amount) && $item->product_order_amount > 0 ? $item->product_order_amount : '' }} {{ isset($item->product_order_amount) && $item->product_order_amount > 0 ? $item->currency->symbol : '' }}</td>
                                                <td>{{ isset($item->commission_percent) && $item->commission_percent > 0 ? $item->commission_percent : '' }} {{ isset($item->commission_percent) && $item->commission_percent > 0 ? $item->currency->symbol : '' }}</td>
                                                <td>{{ $item->payment_amount ?? "" }} {{ isset($item->payment_amount) && $item->payment_amount > 0 ? $item->currency->symbol : '' }}</td>
                                                <td>{{ $item->payment_method ?? "" }}</td>
                                                <td>{{ $item->payment_number ?? "" }}</td>
                                                <td>{{ $item->transaction_id ?? "" }}</td>
                                                <td>{{ $item->converted_dr ?? "0" }} {{ $item->currency->symbol ?? '' }}</td>
                                                <td>{{ $item->converted_cr ?? "0" }} {{ $item->currency->symbol ?? '' }}</td>
                                                <td>{{ $item->converted_balance ?? "0" }} {{ $item->currency->symbol ?? '' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-y h:i:s A') }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                No Record Found
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="d-flex mt-4 mb-4 justify-content-between">
                                    {!! $storeOrders->appends(['search' => request('search'), 'from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
