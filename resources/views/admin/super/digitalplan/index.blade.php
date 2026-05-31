@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{route('plans')}}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Website Plans
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('posplans')}}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Pos Plans
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{route('digitalplans')}}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Digital Plans
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">

            <div class="row">
                <div class="col-md-6">
                    <h4>All Digital Plans</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li class="active"><a href="{{route('digitalplans.create')}}">Create New</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form method="post" action="{{route('superadmin.changedigitalplansssstatus')}}">
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
                                <div class="col-md-7"></div>
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

                            <div class="table-responsive">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th width="10%">Name</th>
                                        <th width="10%">Price</th>
                                        <!--<th width="5%">Branch</th>-->
                                        <th width="5%">Page Setup</th>
                                        <th width="5%">Static Content</th>
                                        <th width="5%">Google AD</th>
                                        <th width="5%">Video Content</th>
                                        <th width="5%">Gify Content</th>
                                        <th width="19%">Payment Processing Charge</th>
                                        <th width="19%">Monthly Chat</th>
                                        <th width="19%">Position</th>
                                        <th width="11%">Status</th>
                                        <th width="11%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($plans as $key=>$plan)
                                        <tr>
                                            <td><input type="checkbox" name="selectedid" value="{{$plan->id}}" id="id"
                                                       class="checkSingle"></td>
                                            <td>{{$plan->name}}</td>
                                            <td>{{$plan->price}}</td>
                                            <!--<td>{{$plan->branch}}</td>-->
                                            <td>{{$plan->page_setup}}</td>
                                            <td>{{$plan->static_content}}</td>
                                            <td>{{$plan->google_ad}}</td>
                                            <td>{{$plan->video_content}}</td>
                                            <td>{{$plan->gify_content}}</td>
                                            <td>
                                                <a href="{{route('plan.payment.list', ["type" => "digitalplan", "id" => $plan->id])}}"
                                                   class="btn btn-primary">Edit</a>
                                            </td>
                                            <td>{{$plan->monthly_chat_support ?? 0}}</td>
                                            <td>
                                                <input type="number" name="position" value="{{$plan->position}}"
                                                       id="position" style="width:40%">
                                            </td>
                                            <td>
                                                <div class="form-check form-switch" style="text-align:center;">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="flexSwitchCheckChecked" style="margin:0 auto;"
                                                           @if($plan->status=='active') checked @endif>
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{route('editdigitalplan',$plan->id)}}"
                                                   class="btn btn-secondary">Edit</a>
                                                <a href="{{route('deletedigitalplan',$plan->id)}}"
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
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function () {
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
