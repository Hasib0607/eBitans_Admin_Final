@extends('admin.layouts.main')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.share.report.nav')

        <div class="container-fluid mt-2" id="toplist">
            <div class="row">
                <div class="col-md-8">
                    <h4>All Clients</h4>
                </div>
            </div>
            <div class="row mt-1 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <form action="{{ route('superadmin.sms.log.report', ['id' => $store_id ?? '']) }}"
                                      method="get" class="row">

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
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="taskfilterresult">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="1%">SL</th>
                                        <th>Store Name</th>
                                        <th>Purpose</th>
                                        <th>Phone</th>
                                        <th>Text</th>
                                        <th>Create Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($smsList))
                                        @foreach ($smsList as $item)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ ($smsList->currentPage() - 1) * $smsList->perPage() + $loop->iteration }}</td>
                                                <td>
                                                    @if($item->user_type == "1")
                                                        System Generated
                                                    @else
                                                        <a style="display: block;font-size: 14px; color:{{ $str->name ?? '#ff5733;' }}"
                                                           href="http://{{ $item->store->url ?? '#' }}" target="_blank"
                                                           rel="noopener noreferrer">
                                                            {{ $item->store->name ?? 'Store name not set yet' }}
                                                        </a>
                                                        @if(isset($item->store->getUser->phone) && !empty($item->store->getUser->phone))
                                                            <p style="font-weight: 900;margin-bottom: 0">
                                                                <a href="https://wa.me/88{{ $item->store->getUser->phone }}"
                                                                   target="_blank"
                                                                   style="text-decoration: none;">
                                                                    {{ $item->store->getUser->phone ?? '' }}
                                                                </a>
                                                            </p>
                                                        @endif
                                                        @if(isset($item->store->getUser->email) && !empty($item->store->getUser->email))
                                                            <p style="font-weight: 900;margin-bottom: 0">
                                                                {{ $item->store->getUser->email ?? '' }}
                                                            </p>
                                                        @endif
                                                        @if(isset($item->store->getUser->id))
                                                            <p>User ID: {{ $item->store->getUser->id ?? 0 }}</p>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $item->purpose }}
                                                </td>
                                                <td>
                                                    {{ $item->phone }}
                                                </td>
                                                <td style="width: 200px; white-space: normal; font-size: 14px;">
                                                    <span>{{ $item->text }}</span>
                                                </td>
                                                <td>
                                                    <p class="m-0">
                                                        {{ date('j M, Y h:m:s A', strtotime($item->created_at ?? '2000-01-01')) }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">
                                                No Record Found
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="d-flex mt-4 mb-4 justify-content-between">
                                    {!! $smsList->appends(['from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection



