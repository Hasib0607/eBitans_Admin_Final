@extends('admin.layouts.main')
@section('head')
    <style>
        form {
            padding: 40px;
        }

        form label {
            font-weight: bold;
        }

        form input {
            border: 1px solid #e0e0e0;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3"
                             style="min-height: 100px;">
                            <h6 class="text-white text-capitalize ps-3" style="float: left">Plans</h6>
                            <a class="btn btn-success" href="{{ route('admin.plans') }}"
                               style="float: right; margin-right: 1rem !important;">All Plans</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <form action="{{ route('admin.store-plan') }}" method="POST">
                            @csrf
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Name</label>
                                <input name="name" type="text" class="form-control">
                            </div>
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Number Of Products</label>
                                <input name="products" type="text" class="form-control">
                            </div>
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Number Of Orders</label>
                                <input name="order" type="number" class="form-control">
                            </div>
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Number Of Employees</label>
                                <input name="order" type="number" class="form-control">
                            </div>
                            <div class="form-check form-switch d-flex align-items-center mb-3">
                                <input class="form-check-input" type="checkbox" id="google">
                                <label class="form-check-label mb-0 ms-2" for="google">Google Adsent</label>
                            </div>
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Price</label>
                                <input name="price" type="number" step="0.01" class="form-control">
                            </div>
                            <div class=" selec input-group input-group-outline mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value=""></option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('js')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"
                    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
            <script>
                $('select').on('click', function () {
                    $('.selec').toggleClass("focused is-focused");
                });

                $('.selec').on('change', function () {
                    if ($('.selec').val() != null) {
                        $('.selec').addClass("is-filled");
                    } else {
                        $('.selec').removeClass("is-filled");
                    }
                });
            </script>
@endsection
