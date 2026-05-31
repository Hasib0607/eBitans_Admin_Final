@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.amarpay.nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Request</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('superadmin.amaypay.kyc', ['status' => 'reject']) }}"
                               class="btn btn-primary"
                               style="display:block;border-radius:0px !important">Rejected List</a>
                        </li>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('superadmin.amaypay.kyc', ['status' => 'pending']) }}"
                               class="btn btn-primary" style="display:block;border-radius:0px !important">Pending
                                List</a>
                        </li>
                    </ul>
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
                                        <th width="20%">Name</th>
                                        <th width="20%">Store URL</th>
                                        <th width="10%">Payment Gateway</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Info</th>
                                        <th width="10%">Create Date</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($kycList) && count($kycList)>0)
                                        @foreach($kycList as $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($kycList->currentPage() - 1) * $kycList->perPage() + $loop->iteration }}</td>
                                                <td>{{ $item->store->name ?? "" }}</td>
                                                <td>{{ $item->store->url ?? "" }}</td>
                                                <td>{{ ucfirst($item->payment_gatway) ?? "" }}</td>
                                                <td>
                                                    @if($item->status == 0)
                                                        <span class="text-info">Pending</span>
                                                    @elseif($item->status == 1)
                                                        <span class="text-success">Active</span>
                                                    @elseif($item->status == 2)
                                                        <span class="text-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                <td><a href="{{route('superadmin.amaypay.kyc.view',$item->id)}}"
                                                       class="btn btn-info">View</a></td>
                                                <td>{{date('d-m-Y', strtotime($item->created_at))}}</td>
                                                <td>
                                                    @if($item->status == 0)
                                                        <a href="{{route('superadmin.amaypay.kyc.status.change', ['id' => $item->id, 'status' => 'active'])}}"
                                                           onclick="return confirm('Are you sure, you want to Active this Request?')"
                                                           class="btn btn-success">Active</a>
                                                        <a href="{{route('superadmin.amaypay.kyc.status.change', ['id' => $item->id, 'status' => 'reject'])}}"
                                                           onclick="return confirm('Are you sure, you want to Reject this Request?')"
                                                           class="btn btn-danger">Reject</a>
                                                    @else
                                                        <a href="{{route('superadmin.amaypay.kyc.status.change', ['id' => $item->id, 'status' => 'pending'])}}"
                                                           onclick="return confirm('Are you sure, you want to Pending this Request?')"
                                                           class="btn btn-warning">Pending</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">No Record Found</td>
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
