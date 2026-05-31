@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.amarpay.nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Payment List</h4>
                </div>
            </div>
            <div class="row productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route('superadmin.amaypay.payment.list') }}">
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
                                        <th width="20%">Name</th>
                                        <th width="20%">URL</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($orders) && count($orders)>0)
                                        @foreach($orders as $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->store->user_id ?? "" }}</td>
                                                <td>{{ $item->store->name ?? "" }}</td>
                                                <td>{{ $item->store->url ?? "" }}</td>
                                                <td>
                                                    <a href="{{route('superadmin.merchant.order.list',$item->store_id)}}"
                                                       class="btn btn-info">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">No Record Found</td>
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

