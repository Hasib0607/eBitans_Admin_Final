@extends('admin.layouts.main')

{{--banner styles--}}
@push('styles')
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

        /*.is-focused{*/
        /*    background-color:red !important;*/
        /*    width:60%;*/
        /*    padding:10px 0px;*/
        /*}*/
        input[type=radio] {
            opacity: 1;
        }

        .headerimg {
            width: 80%;
            height: 150px;
        }
    </style>
@endpush

{{--banner main section--}}
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">

        @include('superadmin.share.design-top-nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">

                {{--home page side nav section--}}
                @include('superadmin.storeDemoData.left-nav')

                {{--banner main container--}}
                <div class="col-md-9 rightmenu">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card h-100">

                                {{--header section--}}
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>
                                                All Header Color
                                            </h4>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <a href="{{ route('superadmin.store.header.create') }}"
                                               class="btn btn-primary">Create</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" width="100%">
                                            <thead>
                                            <tr>
                                                <th width="4%">SL</th>
                                                <th width="10%">Header Color</th>
                                                <th width="20%">Categories</th>
                                                <th width="11%">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($headers) && count($headers) > 0)
                                                @foreach($headers as $item)
                                                    <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{$item->header_color}} <p
                                                                style="width: 20px; height: 20px; border-radius: 10px; display: inline-block; margin-left: 10px; margin-bottom: -3px; background-color: {{ $item->header_color }}"></p>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $categoryIds = explode(',', $item->category_id);
                                                                $categoryNames = \App\Models\BusinessCategory::whereIn('id', $categoryIds)->pluck('name')->toArray();
                                                                echo implode(', ', $categoryNames);
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('superadmin.store.edit.header', ['id' => $item->id]) }}"><img
                                                                    src="{{asset('img/edit.png')}}" width="20px"
                                                                    height="20px"></a>
                                                            &nbsp;&nbsp;
                                                            <a href="{{ route('superadmin.store.delete.header', ['id' => $item->id]) }}"
                                                               class="delete-btn"><img
                                                                    src="{{asset('img/delete.png')}}" width="25px"
                                                                    height="25px"></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No record found!</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-end">
                                            {!! $headers->links() !!}
                                        </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(function (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');

                    swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>
@endpush
