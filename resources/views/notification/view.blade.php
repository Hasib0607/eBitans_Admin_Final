@extends('admin.layouts.main')
@section('content')
    <style>
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

        .size {
            list-style-type: none;

        }

        .size li {
            float: left;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 35px !important;
        }

        .select2-container .select2-selection--single {
            height: 39px !important;
        }
    </style>

    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row new">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item active">
                                <a href="{{ route('notification.notification.list') }}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Notification List
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="container content-main">
            <div class="row mt-5">
                <form enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6" style="margin:0 auto;">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>Notification</h4>
                                </div>
                                <div class="card-body">
                                    @isset($notification->title)
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label">Message</label>
                                            <p style="background: #e5f1f1;
                                                padding: 5px 10px;
                                                border-radius: 5px;">{{$notification->title ?? "" }}</p>
                                        </div>
                                    @endif
                                    @isset($notification->body)
                                        <div class="mb-3">
                                            <label for="product_name" class="form-label">Body</label>
                                            <p style="background: #e5f1f1;
                                                padding: 5px 10px;
                                                border-radius: 5px;
                                                min-height: 200px;">
                                                {{$notification->body ?? ""}}
                                            </p>
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
    </script>
@endpush
