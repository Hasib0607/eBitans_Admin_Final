@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
        </div>
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Customer</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active"><a href="{{ route('admin.addcustomer') }}">Create New</a></li>
                        <li><a href="">Import</a></li>
                        <li><a data-href="/tasks" onclick="exportTasks(event.target);">Export</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form method="post" action="{{ route('superadmin.deleteallcustomer') }}">
                                        @csrf
                                        <input type="hidden" name="text2" id="selectids">
                                        <select class='form-control' name="action" id="action">
                                            <option value="select">Select Option</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                </div>
                                <div class="col-md-1" style="padding-left:0px;">
                                    <button type="submit" class="btn btn-primary">Apply</button>
                                    </form>
                                </div>
                                <div class="col-md-7"></div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                class="fa fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                            <th width="10%">Image</th>
                                            <th width="10%">Name</th>
                                            <th width="10%">Email</th>
                                            <th width="10%">Phone</th>
                                            <th width="10%">Address</th>
                                            <th width="10%">Status</th>
                                            <th width="9%">Create Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $customer)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td><input type="checkbox" name="selectedid" value="{{ $customer->id }}"
                                                        id="id" class="checkSingle"></td>
                                                <td>
                                                    <img src="{{ URL::to('/') }}/assets/images/img/{{ $customer->image }}"
                                                        alt="" width="60px">
                                                </td>
                                                <td>{{ $customer->name }}</td>
                                                <td>
                                                    {{ $customer->email }}
                                                </td>
                                                <td>{{ $customer->phone }}</td>
                                                <td>{{ $customer->address }}</td>
                                                <td>
                                                    @if ($customer->otp == 'NULL')
                                                        Verified
                                                    @else
                                                        Unverified
                                                    @endif
                                                </td>
                                                <td>{{ $customer->created_at }}</td>
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
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#checkedAll").change(function() {
                debugger;
                if (this.checked) {
                    $(".checkSingle").each(function() {
                        debugger;
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function() {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function() {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function() {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAll").prop("checked", true);
                    }
                } else {
                    $("#checkedAll").prop("checked", false);
                    var valuesArray = $('input[name="selectedid"]:checked').map(function() {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
        $(document).ready(function() {
            $("#taskfilter").on("keyup", function() {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function() {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
