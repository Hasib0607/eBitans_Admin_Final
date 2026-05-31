@extends('admin.layouts.main')
@push('styles')
@endpush
@section('content')
    <div id="vue-root">
        <payments></payments>
    </div>
    <script src="{{asset('js/app.js')}}"></script>

@endsection
