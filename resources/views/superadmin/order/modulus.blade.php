@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    @include("superadmin.order.top_nav")
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Request</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="javascript:void(0)" class="btn btn-primary"
                               style="display:block;border-radius:0px !important">Create New</a>
                        </li>
                        <li style="padding:0px;border:0px;">
                            <a href="javascript:void(0)" style="display:block;border-radius:0px !important"
                               class="btn btn-secondary">Export</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox"></th>
                                        <th Width="5%">Store</th>
                                        <th width="5%">Modulus Name</th>
                                        <th width="5%">Total Amount</th>
                                        <th width="5%">Payment Method</th>
                                        <th width="10%">Transaction Id</th>
                                        <th width="10%">Number</th>
                                        <th width="10%">Addons</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Create Date</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (isset($data) && count($data) > 0)
                                        @foreach ($data as $key => $dm)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>
                                                    <input type="checkbox" name="id" value="{{ $dm->id }}">
                                                </td>
                                                <td>
                                                    @php
                                                        $storesss = DB::table('stores')
                                                            ->where('id', $dm->store_id)
                                                            ->first();
                                                    @endphp
                                                    {{ $storesss->name ?? 'empty' }}
                                                </td>
                                                <td>{{ $dm->getModulus->name }}</td>
                                                <td>{{ $dm->price }}</td>
                                                <td>{{ $dm->payment_type }}</td>
                                                <td>{{ $dm->transaction_id }}</td>
                                                <td>{{ $dm->number }}</td>
                                                <td>
                                                    gdf
                                                </td>
                                                <td>{{ $dm->status ? 'Accpeted' : 'Pending' }}</td>
                                                <td>{{ date('d-m-Y H:m:s', strtotime($dm->created_at)) }}</td>
                                                <td><a href="{{ route('superadmin.modulus.accept', $dm->id) }}"
                                                       onclick="return confirm('Are you sure, you want to accpect this Order?')"
                                                       class="btn btn-primary">Accept</a>
                                                    <a href="{{ route('superadmin.modulus.reject', $dm->id) }}"
                                                       onclick="return confirm('Are you sure, you want to Reject this Order?')"
                                                       class="btn btn-secondary">Reject</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex mt-4 mb-4 justify-content-center">
                                {{ $data->links() }}
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
            $("#taskfilter").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#taskfilterresult tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
