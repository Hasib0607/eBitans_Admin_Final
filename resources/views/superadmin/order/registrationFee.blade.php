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
                                        <th width="">#</th>
                                        <th width="">Amount</th>
                                        <th width="">Status</th>
                                        <th width="">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>#</td>
                                        <td>{{ $data->price }}</td>
                                        <td>{{ $data->status == 1 ? 'Active' : 'inactive' }}</td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                               class="btn btn-primary">Edit</a>
                                        </td>
                                    </tr>
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



    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('superadmin.registrationFee.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Registration Fee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" min="0" step="0.01" name="price" class="form-control"
                                   value="{{ $data->price }}" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option>Select Status</option>
                                <option value="0" @if(isset($data->status) && $data->status == 0) selected @endif>
                                    Inactive
                                </option>
                                <option value="1" @if(isset($data->status) && $data->status == 1) selected @endif>
                                    Active
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
