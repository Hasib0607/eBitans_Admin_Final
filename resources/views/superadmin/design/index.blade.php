@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.share.design-top-nav')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Design</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a href="{{route('superadmin.design.create')}}"
                                                               class="btn btn-primary"
                                                               style="display:block;border-radius:0px !important">Create
                                New</a></li>
                        <li style="padding:0px;border:0px;"><a href="#"
                                                               style="display:block;border-radius:0px !important"
                                                               class="btn btn-secondary">Export</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form method="post" action="{{route('superadmin.changedesignssstatus')}}">
                                        @csrf
                                        <input type="hidden" name="text2" id="selectids">
                                        <select class='form-control' name="action" id="action">
                                            <option value="select">Select Option</option>
                                            <option value="active">Active</option>
                                            <option value="deactive">Deactive</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                </div>
                                <div class="col-md-1" style="padding-left:0px;">
                                    <button type="submit" class="btn btn-primary">Apply</button>
                                    </form>
                                </div>

                                <div class="col-md-5">

                                </div>
                                <div class="col-md-2">
                                    <form action="{{route('superadmin.design.typefilter')}}" method="get">

                                        <select class="form-select" name="type" onchange="this.form.submit()">
                                            <option value="all" @if(isset($stts) && $stts=='all') selected @endif>All
                                                Type
                                            </option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->type }}"
                                                        @if (isset($stts) && $stts == $type->type) selected @endif>{{ ucwords(str_replace('_', ' ', $type->type)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
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
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th width="10%">Name</th>
                                        <th width="20%">Image</th>
                                        <th width="15%">Value</th>
                                        <th width="15%">Type</th>
                                        <th width="15%">Category</th>
                                        <th width="15%">Status</th>
                                        <th width="11%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($design as $key=>$dsg)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td><input type="checkbox" name="selectedid" value="{{$dsg->id}}" id="id"
                                                       class="checkSingle"></td>
                                            <td>{{$dsg->name}}</td>
                                            <td>
                                                <img src="{{URL::to('/')}}/assets/images/design/{{$dsg->image}}"
                                                     alt="{{$dsg->name}}" width="100px">
                                            </td>
                                            <td>{{$dsg->value}}</td>
                                            <td>{{$dsg->type}}</td>
                                            <td>
                                                @php
                                                    $categoryIds = explode(',', $dsg->category);
                                                    $categories = \App\Models\BusinessCategory::whereIn('id', $categoryIds)->pluck('name')->toArray();
                                                    echo implode(', ', $categories);
                                                @endphp
                                            </td>
                                            <td>
                                                <div class="form-check form-switch" style="text-align:center;">
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                           data-id="{{$dsg->id}}" id="flexSwitchCheckChecked"
                                                           @if($dsg->status=="active") checked=""
                                                           @endif style="margin:0 auto;">
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{route('superadmin.editdesign',$dsg->id)}}"><img
                                                        src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{route('superadmin.deletedesign',$dsg->id)}}"
                                                   onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                        src="{{asset('img/delete.png')}}" width="25px"
                                                        height="25px"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                                <div class="d-flex justify-content-end">
                                    {{ $design->appends(['type' => request('type')])->links() }}
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
            $(".switchstatus").on("change", function () {
                $url = "/changedesignstatus";
                var value = $(this).val();
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {value: value, id: id}, function (data) {
                    console.log(data);
                });
            });
        });
    </script>
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
