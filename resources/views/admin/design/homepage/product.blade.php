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

@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">

        {{--design top nav section--}}
        @include('admin.design.share.designs-nav',['home_page'=>true])
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">

                {{--home page side nav section--}}
                @include('admin.design.share.home-page-nav', ['product'=>true])

                <div class="col-md-5 rightmenu mt-4">
                    <div class="row  h-100">
                        <div class="col-md-12">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4> @if(Session::has('lang') && Session::get('lang')=='bn')
                                                    সমস্ত পণ্য
                                                @else
                                                    All Product
                                                @endif
                                            </h4>
                                        </div>

                                        {{--category filter--}}
                                        @include('admin.design.share.home-page-category-filter', ['current_page' => 'product', 'column' => 'product'])
                                    </div>

                                </div>

                                {{--home page layout options--}}
                                @include('admin.design.share.home-page-options', ['column'=>'product'])
                            </div>
                        </div>
                    </div>
                </div>
                <!--previews-->
                @include('admin.design.share.home-page-preview')
            </div>

        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $(".changeproduct").on("click", function () {
                $url = "/changeproduct";
                var value = $(this).val();
                $.get($url, {value: value}, function (data) {
                    toastr.success('Product Design Successfully', 'Success');
                });
            });
        });
    </script>
    <script>
        var myModal = document.getElementById('myModal')
        var myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', function () {
            myInput.focus()
        })
    </script>


    <script>
        function modalRE(val) {
            $('.show').removeClass("modal-backdrop");
            $('#exampleModal' + val).toggle();
        }
    </script>
@endpush
