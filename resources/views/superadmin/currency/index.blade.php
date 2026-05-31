@extends('admin.layouts.main')
@section('content')
    <style>
        .btn {
            margin-bottom: 0 !important;;
        }
    </style>
    <main class="main-content position-relative h-100 border-radius-lg">


        @include('superadmin.share.settings-top-nav')

        <div class="modal fade" id="CurrencyModal" tabindex="-1" aria-labelledby="CurrencyLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('super_admin.settings.currency_store') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6>Create Currency</h6>
                        </div>
                        <div class="modal-body" style="border:none">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="currency_country" class="form-label">Country</label>
                                    <input type="text" placeholder="Country" class="form-control" id="currency_country"
                                           name="country">
                                    @error('country')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="currency_code" class="form-label">Code</label>
                                    <input type="text" placeholder="BDT, USD ...." class="form-control"
                                           id="currency_code" name="code">
                                    @error('code')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="currency_symbol" class="form-label">Symbol</label>
                                    <input type="text" placeholder="Symbol" class="form-control" id="currency_symbol"
                                           name="symbol">
                                    @error('symbol')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="mb-4 d-flex">
                                    <label for="currency_customize_rate_status" class="form-label">Customize Rate Status</label>
                                    <div class="form-switch">
                                        <input
                                            class="form-check-input text-center"
                                            type="checkbox"
                                            name="customize_rate_status"
                                            id="flexSwitchCheckChecked currency_customize_rate_status"
                                            style="margin:0 auto;"
                                            checked
                                        >
                                    </div>
                                    @error('currency_customize_rate_status')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="mb-4 d-flex">
                                    <label for="currency_status" class="form-label">Status</label>
                                    <div class="form-switch">
                                        <input
                                            class="form-check-input text-center"
                                            type="checkbox"
                                            name="status"
                                            id="flexSwitchCheckChecked currency_status"
                                            style="margin:0 auto;"
                                            checked
                                        >
                                    </div>
                                    @error('status')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Save</button>
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Currencies</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <span
                                data-bs-toggle="modal" data-bs-target="#CurrencyModal"
                                class="btn btn-primary"
                                style="display:block; border-radius:0px !important">
                                Create New
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">
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
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th width="20%">Country</th>
                                        <th width="20%">Code</th>
                                        <th width="15%">Symbol</th>
                                        <th width="15%">Rate</th>
                                        <th width="15%">Customize Rate Status</th>
                                        <th width="15%">Status</th>
                                        <th width="11%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($currencies as $key=>$currency)
                                        <div class="modal fade" id="CurrencyModal{{$key}}" tabindex="-1" aria-labelledby="CurrencyLabel{{$key}}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{ route('super_admin.settings.currency_update', $currency->id) }}" method="POST"
                                                      enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6>Edit Currency</h6>
                                                        </div>
                                                        <div class="modal-body" style="border:none">
                                                            <div class="form-group">
                                                                <div class="mb-4">
                                                                    <label for="currency_country" class="form-label">Country</label>
                                                                    <input type="text" placeholder="Country" class="form-control" id="currency_country"
                                                                           name="country" value="{{$currency->country}}">
                                                                    @error('country')
                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="mb-4">
                                                                    <label for="currency_code" class="form-label">Code</label>
                                                                    <input type="text" placeholder="BDT, USD ...." class="form-control"
                                                                           id="currency_code" name="code" value="{{$currency->code}}">
                                                                    @error('code')
                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="mb-4">
                                                                    <label for="currency_symbol" class="form-label">Symbol</label>
                                                                    <input type="text" placeholder="Symbol" class="form-control" id="currency_symbol"
                                                                           name="symbol" value="{{$currency->symbol}}">
                                                                    @error('symbol')
                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="mb-4 d-flex">
                                                                    <label for="currency_customize_rate_status" class="form-label">Customize Rate Status</label>
                                                                    <div class="form-switch">
                                                                        <input
                                                                            class="form-check-input text-center"
                                                                            type="checkbox"
                                                                            name="customize_rate_status"
                                                                            id="flexSwitchCheckChecked currency_customize_rate_status"
                                                                            style="margin:0 auto;"
                                                                            @if($currency->customize_rate_status)checked @endif
                                                                        >
                                                                    </div>
                                                                    @error('currency_customize_rate_status')
                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="mb-4 d-flex">
                                                                    <label for="currency_status" class="form-label">Status</label>
                                                                    <div class="form-switch">
                                                                        <input
                                                                            class="form-check-input text-center"
                                                                            type="checkbox"
                                                                            name="status"
                                                                            id="flexSwitchCheckChecked currency_status"
                                                                            style="margin:0 auto;"
                                                                            @if($currency->status)checked @endif
                                                                        >
                                                                    </div>
                                                                    @error('status')
                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="submit">Save</button>
                                                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td><input type="checkbox" name="selectedid" value="{{$currency->id}}"
                                                       id="id" class="checkSingle"></td>
                                            <td>{{$currency->country}}</td>
                                            <td>{{$currency->code}}</td>
                                            <td>{{$currency->symbol}}</td>
                                            <td>{{$currency->rate}}</td>
                                            <td class=" form-switch">
                                                <input class="form-check-input text-center switch_customizable_status"
                                                       type="checkbox"
                                                       data-id="{{$currency->id}}"
                                                       id="flexSwitchCheckChecked" @if($currency->customize_rate_status) checked @endif
                                                       style="margin:0 auto;">
                                            </td>
                                            <td class=" form-switch">
                                                <input class="form-check-input text-center switch_status"
                                                       type="checkbox"
                                                       data-id="{{$currency->id}}"
                                                       id="flexSwitchCheckChecked" @if($currency->status)checked @endif
                                                       style="margin:0 auto;">
                                            </td>
                                            <td>
                                                <span data-bs-toggle="modal" data-bs-target="#CurrencyModal{{$key}}"><img
                                                        src="{{asset('img/edit.png')}}" width="20px" height="20px"></span>
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
    <script>
        $(document).ready(function () {
            $(".switch_status").on("change", function () {
                $url = "/superadmin/settings/currency/change-status";
                var id = $(this).data('id');
                $.post($url, {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                }, function (data) {
                    swal.fire(
                        'success!',
                        "Status Change Successfuly 🥱",
                        'success'
                    );
                });
            });
        });
        $(document).ready(function () {
            $(".switch_customizable_status").on("change", function () {
                $url = "/superadmin/settings/currency/change-rate-status";
                var id = $(this).data('id');
                $.post($url, {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id
                }, function (data) {
                    swal.fire(
                        'success!',
                        "Currency Customizable Status Change Successfuly 🥱",
                        'success'
                    );
                });
            });
        });
    </script>
@endsection
