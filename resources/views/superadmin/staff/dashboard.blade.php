@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="p-2" style="text-align: center;color: navajowhite;font-family: cursive;"> Welcome to
                        Staff Login</h3>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            @if(canAccess('clients'))
                @include('superadmin.share.paymentNotification')
            @else
                <div class="col-md-12" style="overflow: hidden;">
                    <img src="{{ asset('img/2738.jpg') }}" alt="bg image" class="zoom" width="100%">
                </div>
            @endif
        </div>
    </main>
@endsection
