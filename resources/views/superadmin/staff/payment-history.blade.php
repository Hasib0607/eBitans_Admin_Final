@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @if (Auth::user()->type == 'superstaff')
            @include('superadmin.staff.commission-nav')
        @elseif (Auth::user()->type == 'superadmin')
            @include('superadmin.share.staff-role-permission-nav.nav')
        @endif
        <div class="container-fluid mt-5" id="toplist">
            <div class="row">
                <div class="col-md-8">
                    <h4>Payment History</h4>
                </div>
            </div>
            <div class="row mt-1 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
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
                                        <input type="date" name="to_date" id="to_date" value="{{ $to_date ?? '' }}"
                                               class="form-control">
                                    </div>

                                    <div class="col-md-2" style="padding-left:0px;">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
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
                                        <th width="1%">#</th>
                                        <th width="5%">Store Name</th>
                                        <th width="5%">Id</th>
                                        <th width="10%">Purchase Amount</th>
                                        <th width="10%">Amount</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Balance</th>
                                        <th width="15%">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($commission as $key => $item)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>{{ ($commission->currentPage() - 1) * $commission->perPage() + $loop->iteration }}</td>
                                            <td>
                                                @if(isset($item->commission_id))
                                                    <a style="display: block;font-size: 14px;color:{{ $str->name?? '#ff5733;'}}"
                                                       href="http://{{ $item->parent->store->url ?? '#' }}"
                                                       target="_blank"
                                                       rel="noopener noreferrer">
                                                        {{  $item->parent->store->name ?? 'Store is not built yet' }}
                                                        <strong
                                                            style="font-size: 9px; color: {{ $item->store->name ?? '' != '' ? 'green': '#ff5733;' }}">{{ $item->parent->store->name ?? '' != '' ? 'Active': 'Inactive' }}
                                                        </strong>
                                                    </a>
                                                    <span style="font-weight: 900;">
                                                    @if(isset($item->parent->user->phone))
                                                            <a href="https://wa.me/88{{ $item->parent->user->phone }}"
                                                               target="_blank"
                                                               style="text-decoration: none;">
                                                            {{ $item->parent->user->phone ?? '' }}
                                                        </a>
                                                        @elseif(isset($item->parent->user->email))
                                                            {{ $item->parent->user->email ?? '' }}
                                                        @endif
                                                </span>
                                                @else
                                                    {{ "Custom Payment" }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->parent->user->name ?? "" }}
                                                <br>
                                                @if(isset($item->parent->user->id))
                                                    {{ "User ID : ". $item->parent->user->id ?? "" }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($item->commission_id))
                                                    {{ $item->parent->total_amount }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->pay_status == 1)
                                                    {{ $item->dr ?? "" }}
                                                @else
                                                    {{ $item->cr ?? "" }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->pay_status == 1)
                                                    <span class="text-success">Paid</span>
                                                @else
                                                    <span class="text-danger">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->balance ?? "" }}</td>
                                            <td>
                                                {{ date('j M, Y', strtotime($item->created_at ?? '2000-01-01')) }}<br>
                                                {{ date('h:i:s A', strtotime($item->created_at ?? '10:00:00')) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div style="text-align: center;">
                                    {!! $commission->appends(['from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection


