@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid mt-4" id="toplist">
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div style="text-align: center">
                                    <h1>Sorry !! Payment Failed, Please try again later.</h1>
                                </div>
                                <br><br>
                                <div style="text-align: center; color: red;">
                                    @if (Session::has('error'))
                                        {{ session("error") }}
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

