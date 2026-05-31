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

        button.btn {
            margin-bottom: 0;
        }
    </style>
@endpush

{{--Affiliate User main section--}}
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">

        {{--affiliate top nav section--}}
        @include('admin.productAffiliateMarketing.share.affiliate-nav')
        <div class="container-fluid mt-4" id="toplist">
            @include('admin.productAffiliateMarketing.share.withdraw-header')
            <div class="card">

                <div class="card-header">
                    <div class="row">
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success" style="color:#fff">
                                    {{ Session::get('success_message') }}</div>
                            @endif

                            {{-- Withdraw Request --}}
                            @include('admin.productAffiliateMarketing.share.withdraw-table')

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script>
        $(document).ready(function () {
            $(".operation").on("click", function () {
                $url = "/admin/withdraw-requests/change-status";
                var id = $(this).data('id');
                var status = $(this).data('status');
                var comment = $('#comment' + id).val();
                $.get($url, {
                    id: id,
                    status: status,
                    comment: comment
                }, function (data) {
                    if (data.status == 200) {
                        swal.fire(
                            'success!',
                            data.message + " 🥱",
                            'success'
                        ).then(function () {
                            window.location.reload();
                        });
                    } else {
                        swal.fire(
                            'Warning!',
                            data.message + " 🥱",
                            'warning'
                        );
                    }
                });
            });
        });
    </script>
@endsection

@push('script')
@endpush

