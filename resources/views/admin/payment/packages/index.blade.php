@extends('admin.layouts.main')
@push('styles')
@endpush
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">
        @include('admin.payment.share.payment-nav')
        <div id="vue-root">
            <packages
                :plans='@json($plans)'
                :visitor='@json(getVisitorInfo())'
            />
        </div>
        <script src="{{asset('js/app.js')}}"></script>
    </main>

@endsection
