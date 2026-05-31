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
    </style>
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('admin.super.share.plan-nav')
        <section class="container content-main">
            <div class="row">
                <form action="{{route('plan.payment.update')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title">Add New Processing Charge</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>{{ $title ?? "" }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <label class="form-label">Payment Gateway</label>
                                        <div class="col-md-8">
                                            <input type="hidden" name="id" value="{{ $paymentProcessor->id }}">
                                            <select class="form-control" name="payment_gateway" id="payment_gateway">
                                                <option value="">Select Payment Gateway</option>
                                                <option value="bkash"
                                                        @if($paymentProcessor->payment_gateway == "bkash") selected @endif>
                                                    Bkash
                                                </option>
                                                <option value="nagad"
                                                        @if($paymentProcessor->payment_gateway == "nagad") selected @endif>
                                                    Nagad
                                                </option>
                                                <option value="rocket"
                                                        @if($paymentProcessor->payment_gateway == "rocket") selected @endif>
                                                    Rocket
                                                </option>
                                                <option value="amarpay"
                                                        @if($paymentProcessor->payment_gateway == "amarpay") selected @endif>
                                                    Amar Pay
                                                </option>
                                            </select>
                                            @error('payment_gateway')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-lg-8">
                                            <label class="form-label">Payment Processing Charge</label>
                                            <div class="row gx-2">
                                                <input placeholder="2.0" type="number" step="0.01"
                                                       class="form-control"
                                                       name="payment_processing_charge"
                                                       value="{{ $paymentProcessor->payment_processing_charge }}"
                                                >
                                                @error('payment_processing_charge')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info mt-4 ml-3">Submit</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </section>
    </main>
@endsection
