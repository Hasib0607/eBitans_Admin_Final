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
                                <a href="{{route('admin.notification')}}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Notification
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="container content-main">
            <div class="row">
                <form action="{{route('admin.notification.update',$notification->id)}}" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title"></h2>
                                </div>

                                <div class="col-md-6" style="text-align:right">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6" style="margin:0 auto;">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>Edit Notification</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Message</label>
                                        <input type="text" placeholder="Type here" class="form-control" id="message"
                                               name="message" value="{{$notification->message ?? old('message')}}"
                                               required>
                                        @error('message')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Body</label>
                                        <textarea class="form-control" name="body" value="{{old('body')}}" required
                                                  rows="4">{{$notification->body ?? old('body')}}</textarea>
                                        @error('body')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    {{-- <div class="mb-3">
                                        <label for="product_name" class="form-label">Link</label>
                                        <input type="text" placeholder="Type here" class="form-control" id="link" name="link" value="{{$notification->link ?? '#'}}">
                                        @error('link')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div> --}}

                                    <div class="mb-3">
                                        <label class="form-label"></label>
                                        <button class="btn btn-info rounded font-sm hover-up" type="submit">Update
                                        </button>
                                    </div>
                                </div>
                            </div> <!-- card end// -->

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
