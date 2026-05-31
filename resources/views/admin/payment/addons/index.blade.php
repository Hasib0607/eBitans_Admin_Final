@extends('admin.layouts.main')
@push('styles')
@endpush
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">
        @include('admin.payment.share.payment-nav')

        <div id="vue-root">
            <addons
                :addons='@json($addons)'
                :visitor='@json(getVisitorInfo())'
                :plan='@json($plan)'
                :pos='@json($posPlan)'
                :due-orders='@json($dueOrders)'
                :late-fee='@json($late_fee ?? 0)'
                :late-fee-days='@json($late_fee_days ?? 0)'
                :late-fee-reason='@json($late_fee_reason ?? null)'
                :user-type='@json(Auth::user()->type)'
            />
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
    </main>
@endsection
