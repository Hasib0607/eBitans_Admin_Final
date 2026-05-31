@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.amarpay.nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>{{ $item->store->name ?? "" }} KYC Data</h4>
                </div>
            </div>
            <div class="row productlist">
                <div class="col-6 mb-5">
                    <div class="card">
                        <div class="card-body">
                            <form class="form-horizontal">
                                <div class="form-group mb-3">
                                    <label for="">NID <span class="text-danger">*</span></label>
                                    <input type="text" name="nid"
                                           class="form-control"
                                           value="{{ $item->nid ?? "" }}" disabled>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="">NID Front Side Photo <span
                                            class="text-danger">*</span></label>
                                    <div class="mb-2">
                                        @if(isset($item->nid_front) && !empty($item->nid_front))
                                            <img
                                                src="{{ asset('/assets/images/kyc')."/$item->nid_front" }}"
                                                alt="NID Front" style="width: 175px;height: 85px">
                                        @else
                                            <p>Not Uploaded</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">NID Back Side Photo <span
                                            class="text-danger">*</span></label>
                                    <div class="mb-2">
                                        @if(isset($item->nid_back) && !empty($item->nid_back))
                                            <img
                                                src="{{ asset('/assets/images/kyc')."/$item->nid_back" }}"
                                                alt="NID Back" style="width: 175px;height: 85px">
                                        @else
                                            <p>Not Uploaded</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">Current Bill Copy <span
                                            class="text-danger">*</span></label>
                                    <div class="mb-2">
                                        @if(isset($item->current_bill_copy) && !empty($item->current_bill_copy))
                                            <img
                                                src="{{ asset('/assets/images/kyc')."/$item->current_bill_copy" }}"
                                                alt="Current Bill Copy" style="width: 175px;height: 85px">
                                        @else
                                            <p>Not Uploaded</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">DBID</label>
                                    <input type="text" name="dbid"
                                           class="form-control"
                                           value="{{ $item->dbid ?? "" }}" disabled>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">DBID Front Side Photo</label>
                                    <div class="mb-2">
                                        @if(isset($item->dbid_front) && !empty($item->dbid_front))
                                            <img
                                                src="{{ asset('/assets/images/kyc')."/$item->dbid_front" }}"
                                                alt="DBID Front" style="width: 175px;height: 85px">
                                        @else
                                            <p>Not Uploaded</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">DBID Back Side Photo</label>
                                    <div class="mb-2">
                                        @if(isset($item->dbid_back) && !empty($item->dbid_back))
                                            <img
                                                src="{{ asset('/assets/images/kyc')."/$item->dbid_back" }}"
                                                alt="DBID Back" style="width: 175px;height: 85px">
                                        @else
                                            <p>Not Uploaded</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">Trade Licence</label>
                                    <input type="text" name="trade_licence"
                                           class="form-control"
                                           value="{{ $item->trade_licence ?? "" }}" disabled>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">Trade Licence Photo</label>
                                    <div class="mb-2">
                                        @if(isset($item->trade_licence_image) && !empty($item->trade_licence_image))
                                            <img
                                                src="{{ asset('/assets/images/kyc')."/$item->trade_licence_image" }}"
                                                alt="Trade Licence" style="width: 175px;height: 85px">
                                        @else
                                            <p>Not Uploaded</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">TIN <span class="text-danger">*</span></label>
                                    <input type="text" name="tin"
                                           class="form-control"
                                           value="{{ $item->tin ?? "" }}" disabled>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">TIN Photo <span class="text-danger">*</span></label>
                                    <div class="mb-2">
                                        @if(isset($item->tin_image) && !empty($item->tin_image))
                                            <img
                                                src="{{ asset('/assets/images/kyc')."/$item->tin_image" }}"
                                                alt="TIN" style="width: 175px;height: 85px">
                                        @else
                                            <p>Not Uploaded</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">BIN</label>
                                    <input type="text" name="bin"
                                           class="form-control"
                                           value="{{ $item->bin ?? "" }}" disabled>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">BIN Photo</label>
                                    <div class="mb-2">
                                        @if(isset($item->bin_image) && !empty($item->bin_image))
                                            <img
                                                src="{{ asset('/assets/images/kyc')."/$item->bin_image" }}"
                                                alt="BIN" style="width: 175px;height: 85px">
                                        @else
                                            <p>Not Uploaded</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">Bank Account</label>
                                    <textarea name="bank_account_number" class="form-control" id=""
                                              cols="30"
                                              rows="5">{{ $item->bank_account_number ?? "" }}</textarea>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">Online Banking</label>
                                    <textarea name="online_bank" class="form-control" id="" cols="30"
                                              rows="5">{{ $item->online_bank ?? "" }}</textarea>
                                </div>
                            </form>
                        </div>
                        {{--                        <div class="col-12">--}}
                        {{--                            <div class="d-flex gap-2 mx-2">--}}
                        {{--                                @if($item->status == 0)--}}
                        {{--                                    <a href="{{route('superadmin.amaypay.kyc.status.change', ['id' => $item->id, 'status' => 'active'])}}"--}}
                        {{--                                       class="btn btn-success"--}}
                        {{--                                       style="display:block;border-radius:5px !important">Accept</a>--}}
                        {{--                                    <a href="{{route('superadmin.amaypay.kyc.status.change', ['id' => $item->id, 'status' => 'reject'])}}"--}}
                        {{--                                       class="btn btn-danger"--}}
                        {{--                                       style="display:block;border-radius:5px !important">Rejected</a>--}}
                        {{--                                @elseif($item->status == 1)--}}
                        {{--                                    <a href="{{route('superadmin.amaypay.kyc.status.change', ['id' => $item->id, 'status' => 'pending'])}}"--}}
                        {{--                                       class="btn btn-info"--}}
                        {{--                                       style="display:block;border-radius:5px !important">Pending</a>--}}

                        {{--                                    <a href="{{route('superadmin.amaypay.kyc.status.change', ['id' => $item->id, 'status' => 'reject'])}}"--}}
                        {{--                                       class="btn btn-danger"--}}
                        {{--                                       style="display:block;border-radius:5px !important">Rejected</a>--}}
                        {{--                                @else--}}
                        {{--                                    <a href="{{route('superadmin.amaypay.kyc.status.change', ['id' => $item->id, 'status' => 'pending'])}}"--}}
                        {{--                                       class="btn btn-info"--}}
                        {{--                                       style="display:block;border-radius:5px !important">Pending</a>--}}
                        {{--                                @endif--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
