@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('admin.amarpay.nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-lg-4 ">
                    <div class="m-left bg-danger rounded shadow"
                         style="width:70%; height: 130px; background-color: white">
                        <p class="text-white text-center pt-1 pb-0 mb-0">Your Balance</p>

                        <h3 class="text-white text-center">{{$balance ?? 0}} TK</h3>

                        @if(isset($isPending))
                            <div class="col-12 text-center">
                                <button class="btn btn-warning" type="button">Processing
                                    ({{$isPending->withdraw_amount}} TK)
                                </button>
                            </div>
                        @elseif(isset($balance) && $balance >= $headerSetting->balance_min_withdraw)
                            <form action="{{ route('admin.amarpay.payment.withdraw.request') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="text-center row m-0">
                                    <div class="col-6 text-center">
                                        <input type="number" name="withdraw_amount" value="{{ old('withdraw_amount') }}"
                                               class="form-control bg-light"
                                               placeholder="Type amount" required>
                                    </div>
                                    <div class="col-6 text-center">
                                        <button class="btn btn-light" type="submit">Withdraw Now</button>
                                    </div>
                                </div>
                            </form>
                        @endif

                    </div>
                    <small
                        class="text-warning fw-bolder ps-1 py-0 mt-0">Minimun {{ $headerSetting->balance_min_withdraw }}
                        Tk balance to
                        Withdraw!</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h4>All Withdraw List</h4>
                </div>
            </div>
            <div class="row productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{Session::get('success_message')}}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%">SL.</th>
                                        <th width="20%">Amount</th>
                                        <th width="20%">Status</th>
                                        <th width="20%">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($items) && count($items)>0)
                                        @foreach($items as $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->withdraw_amount ?? "" }}</td>
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
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">No Record Found</td>
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

