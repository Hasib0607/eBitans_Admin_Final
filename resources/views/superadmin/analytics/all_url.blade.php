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
        {{-- <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <div id="map"></div>
                </div>
            </div>
        </div> --}}

        <div class="row mt-4 mb-5 mb-md-0">
            <div class="col-sm-12">
                <div class="card h-100 mt-4 mt-md-0">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex align-items-center">
                            <h6>Pages</h6>
                            {{-- <button type="button"
                                class="btn btn-icon-only btn-rounded btn-outline-success mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center ms-auto"
                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                data-bs-original-title="Data is based from sessions and is 100% accurate">
                                <i class="material-icons text-sm">done</i>
                            </button> --}}
                            <a href="#"
                                class="btn btn-outline-success mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center ms-auto"">All
                                Url</a>
                        </div>
                    </div>
                    <div class="card-body px-3 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <h6 class="text-center"> <u>All visited link</u> </h6>
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Page Title
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Page
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Page Views</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Avg. Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($url_infos as $key => $url)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }}.  {{ $url->page_title }}
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">
                                                    <a href="{{ $url->url }}" target="_blank"
                                                        rel="noopener noreferrer">
                                                        {{ $url->url }}
                                                        {{-- {{ substr($url->url, 0, 52) }} --}}
                                                        {{-- {{ strlen($url->url) >= 52 ? '...' : '' }} --}}
                                                    </a>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $url->visitors }}</p>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-normal mb-0">{{ $url->isTime }} scend</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </main>
@endsection
@push('scripts')
@endpush
