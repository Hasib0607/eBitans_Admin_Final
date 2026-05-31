@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative border-radius-lg" style="min-height: 100vh">

        {{-- Top nav bar --}}
        @include('superadmin.partials.top_nav_menu')

        <div class="container-fluid mt-4" id="toplist">

            <div class="row">
                <div class="col-md-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            All Questions
                        @else
                            All Questions
                        @endif
                    </h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="{{ route('affiliate.questions.create') }}" class="btn btn-primary"
                               style="display:block;border-radius:0px !important">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Add New Question
                                @else
                                    Add New Question
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row mt-3 productlist">
                <div class="col-12">
                    <div class="card">
                        {{--Table top action and search filter option--}}
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <form id="submitform" method="post"
                                          action="{{ route('affiliate.questions.action') }}">
                                        @csrf
                                        <input type="hidden" name="text2" id="selectids">
                                        <select class='form-control' name="action" id="action">
                                            <option value="select">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সিলেক্ট অপসন
                                                @else
                                                    Select Option
                                                @endif
                                            </option>
                                            <option value="active">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সক্রিয়
                                                @else
                                                    Active
                                                @endif
                                            </option>
                                            <option value="deactive">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    নিষ্ক্রিয়
                                                @else
                                                    Deactive
                                                @endif
                                            </option>
                                            <option value="delete">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    ডিলিট
                                                @else
                                                    Delete
                                                @endif
                                            </option>
                                        </select>
                                </div>
                                <div class="col-md-1" style="padding-left:0px;">
                                    <p id="submit" class="btn btn-primary filterbuttonss">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আবেদন
                                        @else
                                            Apply
                                        @endif
                                    </p>
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

                        {{--Table card--}}
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
                                        <th width="36%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Question
                                            @else
                                                Question
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Answer
                                            @else
                                                Answer
                                            @endif
                                        </th>
                                        <th Width="30%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Question Type
                                            @else
                                                Question Type
                                            @endif
                                        </th>
                                        <th width="5%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                স্টেটাস
                                            @else
                                                Status
                                            @endif
                                        </th>
                                        <th width="5%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                তারিখ
                                            @else
                                                Date
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এডিট/ডিলিট
                                            @else
                                                Action
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($questions as $item)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>
                                                <input type="checkbox" name="selectedid" value="{{ $item->id }}"
                                                       id="id" class="checkSingle">
                                            </td>
                                            <td>{{ Str::of($item->question)->limit(60) }}
                                            </td>
                                            <td style="text-align: left;">
                                                @if($item->answer_option_one)
                                                    <p style="font-size: 14px; margin: 0px">
                                                        1. {{ Str::of($item->answer_option_one)->limit(30) }}
                                                    </p>
                                                @endif
                                                @if($item->answer_option_two)
                                                    <p style="font-size: 14px; margin: 0px;">
                                                        2. {{ Str::of($item->answer_option_two)->limit(30) }}
                                                    </p>
                                                @endif
                                                @if($item->answer_option_three)
                                                    <p style="font-size: 14px; margin: 0px;">
                                                        3. {{ Str::of($item->answer_option_three)->limit(30) }}
                                                    </p>
                                                @endif
                                                @if($item->answer_option_four)
                                                    <p style="font-size: 14px; margin: 0px;">
                                                        4. {{ Str::of($item->answer_option_four)->limit(30) }}
                                                    </p>
                                                @endif
                                            </td>
                                            <td>{{ $item->question_type }}
                                            </td>
                                            <td>
                                                <div class="form-check form-switch" style="text-align:center;">
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                           data-id="{{ $item->id }}" id="flexSwitchCheckChecked"
                                                           name="checkstatus" style="margin:0 auto;"
                                                           @if ($item->status == 1) checked @endif>
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                            <td>
                                                <a href="{{ route('affiliate.questions.edit', $item->id) }}">
                                                    <img src="{{ asset('img/edit.png') }}" width="20px"
                                                         height="20px">
                                                </a>
                                                &nbsp;&nbsp;
                                                <a href="{{ route('affiliate.questions.delete', $item->id) }}"
                                                   onclick="return confirm('Are you sure to delete this?')">
                                                    <img src="{{ asset('img/delete.png') }}" width="25px"
                                                         height="25px">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! $questions->links() !!}
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
        /**
         * This function use to set the product position and status
         */
        $(document).ready(function () {
            // Update the accepted product status
            $(".switchstatus").on("change", function () {
                handleStatusChange($(this));
            });

            /**
             * Handles the change event for the 'switchstatus' input
             * @param {Object} element - The jQuery object representing the 'switchstatus' input
             */
            function handleStatusChange(element) {
                var value = element.val();
                var id = element.data('id');
                sendAjaxRequest('{{ route('affiliate.questions.status') }}', {
                    value: value,
                    id: id
                });
            }

            /**
             * Sends an AJAX request and handles the response
             * @param {string} url - The URL for the AJAX request
             * @param {Object} requestData - The data to be sent in the request
             */
            function sendAjaxRequest(url, requestData) {
                $.get(url, requestData, function (data) {
                    if (data) {
                        Swal.fire(
                            'Success',
                            data.status,
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                // Reload the browser window
                                window.location.reload();
                            }
                        });
                    }
                });
            }
        });
    </script>

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
