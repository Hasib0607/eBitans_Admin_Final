@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
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


        <div class="row card mb-4">
            <div class="col-lg-12 col-md-12 col-sm-12 p-4">
                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <td>#</td>
                            <th>Vistor</th>
                            <th>Phone</th>
                            <th>Page Title</th>
                            <th>Category</th>
                            <th>Url</th>
                            <th>no. of visits</th>
                            <th>County</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Location</th>
                            <th>Device</th>
                            <th>OS</th>
                            <th>IP</th>
                            <th>Visit Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allTraffic as $key => $traffic)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $traffic->user->name ?? 'Unauthorized' }}</td>
                                <td>{{ $traffic->user->phone ?? 'Unauthorized' }}</td>
                                <td>{{ $traffic->page_title ?? '' }}</td>
                                <td>{{ $traffic->category_id ?? '' }}</td>
                                <td>
                                    <p class="text-sm font-weight-normal mb-0">
                                        <a href="{{ $traffic->url }}" target="_blank"
                                            rel="noopener noreferrer">{{ substr($traffic->url, 0, 40) }}
                                            {{ strlen($traffic->url) >= 40 ? '...' : '' }}</a>
                                </td>
                                <td>61</td>
                                <td>{{ $traffic->country_name ?? 'Unknown' }}</td>
                                <td>{{ $traffic->city ?? 'Unknown' }}</td>
                                <td>{{ $traffic->state ?? 'Unknown' }}</td>
                                <td>{{ $traffic->location ?? 'Unknown' }}</td>
                                <td>{{ $traffic->device ?? 'Unknown' }}</td>
                                <td>{{ $traffic->os ?? 'Unknown' }}</td>
                                <td>{{ $traffic->ip ?? 'Unknown' }}</td>
                                <td>{{ $traffic->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                  <div style="text-align: center;">
                        {!! $allTraffic->links() !!}
                    </div>
            </div>
        </div>

        <div class="row mt-3">More informations </div>


    </main>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, ':visible']
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 5]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
@endpush
