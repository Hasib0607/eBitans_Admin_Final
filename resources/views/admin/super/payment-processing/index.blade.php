@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        @include('admin.super.share.plan-nav')
        <div class="container-fluid mt-4" id="toplist">

            <div class="row">
                <div class="col-md-6">
                    <h4>All Payment List</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active"><a
                                href="{{route('plan.payment.create', ["type" => $type ?? "", "id" => $id ?? ""])}}">Create
                                New</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%">SL.</th>
                                        <th width="10%">Plan</th>
                                        <th width="10%">Payment Gateway</th>
                                        <th width="19%">Payment Processing Charge</th>
                                        <th width="11%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lists as $item)
                                        <tr>
                                            <td>
                                                {{ ++$loop->iteration }}
                                            </td>
                                            <td>{{$item->plan_name}}</td>
                                            <td>{{ ucfirst($item->payment_gateway) }}</td>
                                            <td>{{$item->payment_processing_charge}}</td>
                                            <td>
                                                <a href="{{route('plan.payment.edit',$item->id)}}"
                                                   class="btn btn-secondary">Edit</a>
                                                <a href="{{route('plan.payment.delete',$item->id)}}"
                                                   class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
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
