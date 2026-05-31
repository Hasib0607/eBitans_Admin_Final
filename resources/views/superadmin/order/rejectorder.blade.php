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
                        <li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary"
                                                               style="display:block;border-radius:0px !important">Create
                                New</a></li>
                        <li style="padding:0px;border:0px;"><a href="javascript:void(0)"
                                                               style="display:block;border-radius:0px !important"
                                                               class="btn btn-secondary">Export</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
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
                                        <th width="4%"><input type="checkbox"></th>
                                        <th width="5%">Plan Name</th>
                                        <th width="30%">Month</th>
                                        <th width="5%">Total Amount</th>
                                        <th width="10%">Customer Name</th>
                                        <th width="5%">Payment Method</th>
                                        <th width="10%">Transaction Id</th>
                                        <th width="10%">Number</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Create Date</th>
                                        <!--<th width="5%">Action</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($data) && count($data)>0)
                                        @foreach($data as $dm)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td><input type="checkbox" name="id" value="{{$dm->id}}"></td>
                                                    <?php
                                                    $plan = DB::table('plans')->where('id', $dm->plan_id)->first();
                                                    ?>
                                                <td>
                                                    {{$plan->name ?? ""}}
                                                </td>
                                                <td>{{$dm->total_month ?? ""}}</td>
                                                <td>{{$dm->total_amount}}</td>
                                                    <?php
                                                    $customer = DB::table('customers')->where('id', $dm->customer_id)->first();
                                                    $user = DB::table('users')->where('id', $customer->uid)->first();
                                                    ?>
                                                <td>{{$user->name ?? ""}}</td>
                                                <td>{{$dm->method}}</td>
                                                <td>{{$dm->transaction_id}}</td>
                                                <td>{{$dm->number}}</td>
                                                <td>{{$dm->status}}</td>
                                                <td>{{date('d-m-Y', strtotime($dm->created_at))}}</td>
                                                <!--<td>  <a href="{{route('superadmin.domainrequest.accept',$dm->id)}}" onclick="return confirm('Are you sure, you want to accpect this domain?')" class="btn btn-primary">Accept</a>-->
                                                <!-- <a href="{{route('superadmin.domainrequest.reject',$dm->id)}}" onclick="return confirm('Are you sure, you want to Reject this domain?')" class="btn btn-secondary">Reject</a>-->
                                                <!--</td>-->
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
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
