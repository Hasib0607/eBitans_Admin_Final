@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.partials.top_nav_menu')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>Rejected Requests</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('affiliate.payment.lists.approved', 'Complete') }}"
                               class="btn btn-primary" style="display:block;border-radius:0px !important">Accept
                                List</a>
                        </li>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('affiliate.payment.lists.rejected', 'Failed') }}" class="btn btn-primary"
                               style="display:block;border-radius:0px !important">Rejected List</a>
                        </li>
                        <li style="padding:0px;border:0px;"><a href="javascript:void(0)"
                                                               style="display:block;border-radius:0px !important"
                                                               class="btn btn-secondary">Export</a></li>
                    </ul>
                    <!-- <ul>
                        <li style="padding:0px;border:0px;">
                            <a data-href="/tasks" onclick="htmlTableToExcel('xlsx')"
                                style="display:block;border-radius:0px !important" class="btn btn-secondary">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                        এক্সপোর্ট




                    @else
                        Download Excel




                    @endif
                    </a>
                </li>
            </ul> -->
                </div>
            </div>

            <div class="row card">
                <div class="col-lg-6">
                    <form action="{{ route('affiliate.payment.charge') }}" method="POST"
                          style="display: flex;align-items: center;">
                        @csrf
                        <input class="form-control bg-light my-2" style="width:200px" type="text" id="affiliate_charge"
                               name="affiliate_charge" placeholder="Affiliate Charge" value="{{$charge}}" required>
                        <button class="btn btn-danger m-0" style="margin-left: 10px !important;" type="submit"
                                id="bKash_button">Charge
                        </button>
                    </form>
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
                                        <th width="5%">User Name</th>
                                        <th Width="5%">Phone</th>
                                        <th width="5%">Payment Method</th>
                                        <th width="5%">Transaction Id</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Payment Date</th>
                                        <!-- <th width="5%">Action</th> -->
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if (isset($data) && count($data) > 0)
                                        @foreach ($data as $key => $dm)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td><input type="checkbox" name="id" value="{{ $dm->id }}"></td>
                                                <td>{{ $dm->name }}</td>
                                                <td>{{ $dm->phone }}</td>
                                                <td>{{ $dm->payment_method }}</td>
                                                <td>{{ $dm->transaction_id }}</td>
                                                @if($dm->status == 'Completed')
                                                    <td class="text-success fw-bolder">{{ $dm->status }}</td>
                                                @else
                                                    <td class="text-danger fw-bolder">{{ $dm->status }}</td>
                                                @endif
                                                <td>{{ date('d-m-Y H:m:s', strtotime($dm->created_at)) }}</td>
                                                <!-- <td> <a href="{{ route('superadmin.planorder.accept', $dm->id) }}"
                                                            onclick="return confirm('Are you sure, you want to accpect this Order?')"
                                                            class="btn btn-primary">Accept</a>
                                                        <a href="{{ route('superadmin.planorder.reject', $dm->id) }}"
                                                            onclick="return confirm('Are you sure, you want to Reject this Order?')"
                                                            class="btn btn-secondary">Reject</a>
                                                    </td> -->
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td colspan="7">
                                                <p class="text-center">Data not found!</p>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="d-flex mt-4 mb-4 justify-content-center">
                                {{ $data->links() }}
                            </div> --}}
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


    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function htmlTableToExcel(type) {
            var data = document.getElementById('taskfilterresult');
            var excelFile = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(excelFile, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(excelFile, 'ExportedFile:{{ auth()->user()->name }}.' + type);
        }
    </script>
@endpush
