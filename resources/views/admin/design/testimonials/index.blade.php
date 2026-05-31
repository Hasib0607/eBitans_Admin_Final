@extends('admin.layouts.main')
@section('content')

    {{--testimonial main section--}}
    <main class="main-content position-relative border-radius-lg">

        {{--design main top nav--}}
        @include('admin.design.share.designs-nav', ['testimonial' => true])

        <div class="container-fluid mt-4" id="toplist">
            {{--header section--}}
            <div class="row">
                <div class="col-md-6">
                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn')
                            সব প্রশংসাপত্র
                        @else
                            All Testimonials
                        @endif </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="{{route('admin.testimonials.create')}}"
                               class="btn btn-primary"
                               style="display:block;border-radius:0px !important">
                                @if(Session::has('lang') && Session::get('lang')=='bn')
                                    নতুন প্রশংসাপত্র যোগ করুন
                                @else
                                    Add New Testimonial
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{--main card section--}}
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form id="submitform" method="post" action="{{route('admin.changetestimonialssstatus')}}">
                                <div class="row">
                                    <div class="col-md-2" style="padding-right:1px;">
                                        @csrf
                                        <input type="hidden" name="text2" id="selectids">
                                        <select class='form-control' name="action" id="action">
                                            <option
                                                value="select">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    সিলেক্ট  অপসন
                                                @else
                                                    Select Option
                                                @endif</option>
                                            <option
                                                value="active">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    সক্রিয়
                                                @else
                                                    Active
                                                @endif</option>
                                            <option
                                                value="deactive">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    নিষ্ক্রিয়
                                                @else
                                                    Deactive
                                                @endif</option>
                                            <option
                                                value="delete">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    ডিলিট
                                                @else
                                                    Delete
                                                @endif</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1" style="padding-left:0px;">
                                        <p id="submit"
                                           class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                আবেদন
                                            @else
                                                Apply
                                            @endif</p>
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
                            </form>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{Session::get('success_message')}}</div>
                            @endif

                            {{--web view table section--}}
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped testi" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ছবি
                                            @else
                                                Image
                                            @endif </th>
                                        <th width="25%">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                নাম
                                            @else
                                                Name
                                            @endif</th>
                                        <th width="20%">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                পেশা
                                            @else
                                                Occupation
                                            @endif</th>
                                        <th width="10%">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                স্টেটাস
                                            @else
                                                Status
                                            @endif </th>
                                        <th width="5%">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                অবস্থান
                                            @else
                                                Position
                                            @endif </th>
                                        <th width="11%">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                এডিট/ডিলিট
                                            @else
                                                Action
                                            @endif</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($testimonials as $key=>$tst)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>
                                                <input
                                                    type="checkbox"
                                                    name="selectedid"
                                                    value="{{$tst->id}}"
                                                    id="id"
                                                    class="checkSingle">
                                            </td>
                                            <td>
                                                @if(!empty($tst->image))
                                                    <img src="{{ getPath($tst->image, "assets/images/testimonials") }}"
                                                         width="150">
                                                @endif
                                            </td>
                                            <td>{{$tst->name}}</td>
                                            <td>{{$tst->occupation}}</td>
                                            <td>
                                                <div class="form-check form-switch" style="text-align:center;">
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                           data-id="{{$tst->id}}" id="flexSwitchCheckChecked"
                                                           @if($tst->status=="active") checked=""
                                                           @endif style="margin:0 auto;">
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="hidden" name="idss" id="id" value="{{$tst->id}}">
                                                <input type="number" name="position" value="{{$tst->position}}"
                                                       id="position"></td>
                                            <td>
                                                <a href="{{route('admin.testimonials.edit',$tst->id)}}"><img
                                                        src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{route('admin.testimonials.delete',$tst->id)}}"
                                                   onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                        src="{{asset('img/delete.png')}}" width="25px"
                                                        height="25px"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{--mobile view table section--}}
                            <div class="table-responsive" id="mobiletable">
                                <table class="table" width="100%">
                                    @foreach($testimonials as $key=>$tst)
                                        <tr class="mobilefirstrow">
                                            <th width="10%">
                                                <input type="checkbox" name="selectedid" value="{{$tst->id}}" id="id"
                                                       class="checkSingle">
                                            </th>
                                            <th width="20%" style="color:#f1593a">
                                                Image
                                            </th>
                                            <td width="60%" style="color:black">
                                                <img src="{{URL::to('/')}}/assets/images/testimonials/{{$tst->image}}"
                                                     class="zoom" alt="{{$tst->title}}" width="50px" height="50px">
                                            </td>
                                            <td width="10%">
                                                <a href="#" class="toggler" data-prod-cat="{{$key}}">
                                                    <i class="fa fa-arrow-down" id="show{{$key}}"
                                                       style="color:#f1593a"></i>
                                                    <i class="fa fa-arrow-up" id="up{{$key}}" style="display:none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr class="cat{{$key}}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Name
                                            </th>
                                            <td width="60%">
                                                {{$tst->name}}
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{$key}}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Occupation
                                            </th>
                                            <td width="60%">
                                                {{$tst->occupation}}
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{$key}}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Position
                                            </th>
                                            <td width="60%">
                                                <input type="hidden" name="idss" id="id" value="{{$tst->id}}">
                                                <input type="number" name="position" value="{{$tst->position}}"
                                                       id="position"></td>
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{$key}}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Status
                                            </th>
                                            <td width="60%"
                                                style="display: flex;justify-content: center;align-items: center;">
                                                <div class="form-check form-switch" style="text-align:center;">
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                           data-id="{{$tst->id}}" id="flexSwitchCheckChecked"
                                                           @if($tst->status=="active") checked=""
                                                           @endif style="margin:0 auto;">
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                        <tr class="cat{{$key}}" style="display:none">
                                            <th width="10%"></th>
                                            <th width="20%">
                                                Action
                                            </th>
                                            <td width="60%">
                                                <a href="{{route('admin.testimonials.edit',$tst->id)}}"><img
                                                        src="{{asset('img/edit.png')}}" width="20px" height="20px"></a>
                                                &nbsp;&nbsp;
                                                <a href="{{route('admin.testimonials.delete',$tst->id)}}"
                                                   onclick="return confirm('Are you sure you want to delete this item?');"><img
                                                        src="{{asset('img/delete.png')}}" width="25px"
                                                        height="25px"></a>
                                            </td>
                                            <td width="10%"></td>
                                        </tr>
                                    @endforeach
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
        $('#submit').on('click', function () {
            var form = $(this).parents('form');
            var note = $('#action').val();
            if (note != 'select') {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You want to " + note + " this selected item",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + note + ' it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        console.log(form);
                        $('#submitform').submit();
                        form.submit();
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swal.fire(
                            'Cancelled',
                            '' + note + ' Cancel :)',
                            'error'
                        )
                    }
                })
            }
        })
        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                $url = "/changetestimonialsstatus";
                var value = $(this).val();
                var id = $(this).data('id');
                $.get($url, {value: value, id: id}, function (data) {
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('input[name=position]').change(function () {
                var value = $(this).val();
                var id = $(this).parent().parent().find("input[name=idss]").val();
                $url = "/update-position-testimonials";
                $.get($url, {value: value, id: id}, function (data) {
                    window.location.reload();
                    // $('#testimonials').load(location.href + ' .testi');
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            let valuesArray = [];

            // Check all checkbox action
            $("#checkedAll").change(function () {
                if (this.checked) {
                    // If "checkedAll" is checked, check all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = true;
                        let value = $(this).val();
                        if (!valuesArray.includes(value)) {
                            valuesArray.push(value);
                        }
                    });
                } else {
                    // If "checkedAll" is unchecked, uncheck all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    valuesArray = [];
                }

                let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                $("#selectids").val(newAaluesArray);
                $("#selectdelids").val(newAaluesArray);
            });

            // Single check action
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    let value = $(this).val();

                    let isAllChecked = $(".checkSingle").length === $(".checkSingle:checked").length;
                    $("#checkedAll").prop("checked", isAllChecked);

                    if (!valuesArray.includes(value)) {
                        valuesArray.push(value);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
                } else {
                    $("#checkedAll").prop("checked", false);

                    let value = $(this).val();

                    let index = valuesArray.indexOf(value);

                    if (index === -1) {
                        valuesArray.push(value);
                    } else {
                        valuesArray.splice(index, 1);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                    $("#selectdelids").val(newAaluesArray);
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
