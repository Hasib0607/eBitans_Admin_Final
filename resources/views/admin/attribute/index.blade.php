@extends('admin.layouts.main')
@section('content')
    <style>
        .left-menu {
            position: relative;
            top: 50% !important;
        }

        .left-menu ul li {
            float: unset !important;
        }

        .card {
            border: 1px solid rgba(222, 226, 230, 0.7);
        }

        .card .card-body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            padding: .5rem 1.5rem 1.5rem 1.5rem;
        }

        .card .card-header {
            padding: .5rem 1.5rem .5rem 1.5rem;
            border-bottom: 1px solid rgba(222, 226, 230, 0.7);
        }

        .rightmenu li {
            float: left !important;
            padding: 1px 16px !important;
        }

        .select2-container .select2-selection--multiple {
            min-height: 40px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: 0px solid black !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 4px !important;
        }
    </style>
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('admin.admin_top_bar_category.index')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-3 left-menu card card-body mt-4">
                    <ul style="padding-left:0rem;">
                        <li class="active" style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ URL::to('/') }}/attribute" style="display:block;">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    রঙ
                                @else
                                    Color
                                @endif
                            </a></li>
                        <li style="background-color:#FFFF;margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.attribute.size') }}" style="display:block;">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    আকার
                                @else
                                    Size
                                @endif
                            </a></li>
                        <li style="background-color:#FFFF;margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.attribute.unit') }}" style="display:block;">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ইউনিট
                                @else
                                    Unit
                                @endif
                            </a></li>
                    </ul>
                </div>
                <div class="col-md-9 rightmenu mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            সব রঙ
                                        @else
                                            All Color
                                        @endif
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.savecolor') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="color" class="col-md-4">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        রঙ
                                                        চয়ন করুন
                                                    @else
                                                        Choose Color
                                                    @endif
                                                </label>
                                                <div class="col-md-4">
                                                    <input type="color" name="color">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-4">
                                            <div class="row">
                                                <label for="color" class="col-md-4">Color Name</label>
                                                <div class="col-md-4">
                                                    <input type="text" name="color_name" class="form-control"
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-info mt-3">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>All Color</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th style="padding:10px 8px">
                                                    <input type="checkbox" name="id">
                                                </th>
                                                <th style="padding:10px 0px">Color Name</th>
                                                <th style="padding:10px 0px">Color Code</th>
                                                <th style="padding:10px 0px">Position</th>
                                                <th style="padding:10px 0px">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if (isset($color) && count($color) > 0)
                                                @foreach ($color as $clr)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" value="{{ $clr->id }}">
                                                        </td>
                                                        <td>
                                                            {{ $clr->name }}
                                                        </td>
                                                        <td>
                                                            <input type="color" value="{{ $clr->code }}"
                                                                   disabled>
                                                            <!--{{ $clr->code }}-->
                                                        </td>
                                                        <td class="d-flex">
                                                            <form class="d-flex"
                                                                  action="{{ URL::to('attribute/position') }}"
                                                                  method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $clr->id }}">
                                                                <input type="number" style="width: 50px; height: 30px"
                                                                       class="form-control" id="position"
                                                                       name="position" placeholder="0"
                                                                       value="{{ $clr->position }}">
                                                                <button
                                                                    style="float:center;margin-right:5px;margin-left:5px; border:none">
                                                                    <img
                                                                        src="{{ URL::to('/') }}/img/update.png"
                                                                        width="25px" height="25px"></button>
                                                            </form>
                                                        </td>
                                                        <th>
                                                            <a href="{{ route('admin.color.delete', $clr->id) }}"
                                                               style="float:center;margin-right:5px;"><img
                                                                    src="{{ URL::to('/') }}/img/delete.png"
                                                                    width="25px" height="25px"></a>
                                                        </th>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
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
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
    </script>
@endpush
