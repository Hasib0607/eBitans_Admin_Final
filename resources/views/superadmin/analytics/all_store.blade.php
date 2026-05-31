@extends('admin.layouts.main')
@push('styles')
    {{-- <style>
        #map {
            height: 300px;
            border: 1px solid #000;
        }
    </style> --}}
@endpush
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.analytics.partials.header')


        <div class="row mt-4 mb-5 mb-md-0">
            <div class="col-sm-12">
                <div class="card h-100 mt-4 mt-md-0">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex align-items-center">
                            <h6>Pages</h6>

                            <a href="#"
                               class="btn btn-outline-success mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center ms-auto"">All
                            Url</a>
                        </div>
                    </div>
                    <div class="card-body px-3 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <h6 class="text-center"><u>All Store</u></h6>
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                <tr>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        SL
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Name
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Slug
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Category
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Page link
                                    </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($allStore))
                                    @foreach ($allStore as $key => $store)
                                        <tr>
                                            <td>{{ ($allStore->currentPage() - 1) * $allStore->perPage() + $loop->iteration }}</td>
                                            <td>
                                                {{ $store->name }}
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $store->slug }}</p>
                                            </td>

                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $store->type }} scend</p>
                                            </td>

                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">
                                                    <a href="{{ $store->url }}" target="_blank"
                                                       rel="noopener noreferrer">
                                                        {{ $store->url }}
                                                        {{-- {{ substr($url->url, 0, 52) }} --}}
                                                        {{-- {{ strlen($url->url) >= 52 ? '...' : '' }} --}}
                                                    </a>
                                                </p>
                                            </td>
                                            <td>
                                                <a href="{{ route('super.admin.ebitans.analytics.store.wise', $store->id) }}"
                                                   target="_blank" rel="noopener noreferrer">
                                                    <button class="btn btn-info btn-sm">view</button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">
                                            No Record Found
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="d-flex mt-4 mb-4 justify-content-start">
                                {!! $allStore->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </main>
@endsection
@push('scripts')
@endpush
