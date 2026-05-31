@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid mt-4" id="toplist">
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div style="text-align: center;">
                                    <h1>Congratulations !! Your payment has been successfully done.</h1>
                                </div>
                                <br><br>
                                <div style="text-align: center;">
                                    @if (Session::has('success'))
                                        <h2>{{ session("success") }}</h2>
                                    @endif

                                    @if (Session::has('transaction_id'))
                                        <h4>Transaction ID is : {{ session("transaction_id") }}</h4>
                                    @endif
                                </div>
                                <div class="m-auto" style="width: fit-content;">
                                    <a href="{{ route('dropshipper.dropship.commission') }}"
                                       class="btn btn-primary mt-3">
                                        Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

