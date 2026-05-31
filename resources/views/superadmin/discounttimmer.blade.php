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
                                <a href="{{URL::to('/')}}/superadmin/popupimage">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Pop Up Image
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{URL::to('/')}}/superadmin/discounttimmer">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Discount Timer
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
                    <h4>Discount Timmer</h4>
                </div>
                <div class="col-md-6">
                    <!--<ul>-->
                    <!--    <li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
                    <!--    <li style="padding:0px;border:0px;"><a href="javascript:void(0)" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>-->
                    <!--</ul>-->
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-4" style="margin:0 auto">
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{Session::get('success_message')}}</div>
                            @endif
                            <form action="{{route('savediscounttimmer')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mt-3">
                                    <label for="">Title</label>
                                    <input type="text" name="title" value="{{$st->title}}" class="form-control"
                                           style="width:60%">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">Title 2</label>
                                    <input type="text" name="title2" value="{{$st->title2}}" class="form-control"
                                           style="width:60%">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">Sub Title</label>
                                    <input type="text" name="subtitle" value="{{$st->subtitle}}" class="form-control"
                                           style="width:60%">
                                </div>
                                <div class="form-group mt-3">
                                    <label for="">Discount</label>
                                    <input type="number" min="0" step="0.01" name="discount" value="{{$st->discount}}"
                                           class="form-control" style="width:60%">
                                </div>
                                <div class="form-group mt-3">
                                    <img src="{{URL::to('/')}}/assets/images/setting/{{$st->img}}"
                                         style="width:200px;padding:10px;margin-bottom:10px;">
                                    <input type="file" name="image" class="form-control" style="width:60%">

                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
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
