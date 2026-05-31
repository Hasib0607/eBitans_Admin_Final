@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.share.domain-nav.nav')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Domain</h4>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12 d-flex" style="padding-right:1px;">
                                    <div class="col-md"></div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   aria-label="Dollar amount (with dot and two decimal places)"
                                                   id="taskfilter">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                    class="fa fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (Session::has('success_message'))
                                    <div class="alert alert-success">{{Session::get('success_message')}}</div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table" id="taskfilterresult" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="4%">SL</th>
                                            <th width="5%">ID</th>
                                            <th width="20%">Name</th>
                                            <th width="10%">Store Name</th>
                                            <th width="10%">Customer Name</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Create Date</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($domain) && count($domain)>0)
                                            @foreach($domain as $dm)
                                                <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                    <td>
                                                        {{ ($domain->currentPage() - 1) * $domain->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td>{{$dm->id}}</td>
                                                        <?php
                                                        $store = DB::table('stores')->where('id', $dm->store_id)->first();
                                                        ?>
                                                    <td>{{$dm->name}}</td>
                                                    <td>{{$store->name ?? ""}}</td>
                                                        <?php
                                                        $custo = DB::table('customers')->where('id', $dm->customer_id)->first();
                                                        ?>
                                                    <td>{{$custo->name ?? ""}}</td>
                                                    <td>{{$dm->status}}</td>
                                                    <td>{{date('d-m-Y', strtotime($dm->created_at))}}</td>
                                                    <td>
                                                        <a href="{{route('superadmin.domain.cpanel.delete',$dm->id)}}"
                                                           onclick="return confirm('Are you sure, you are remove this domain from cPanel?')"
                                                           class="btn btn-primary"
                                                           style="margin-bottom:0px !important;">Delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="">
                                        {!! $domain->links() !!}
                                    </div>
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
            $("#checkedAll").change(function () {
                debugger;
                if (this.checked) {
                    $(".checkSingle").each(function () {
                        debugger;
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
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
                    var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
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
