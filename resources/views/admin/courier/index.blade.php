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
    <main class="main-content position-relative  h-100 border-radius-lg">

        {{-- Page top bar menu --}}
        @include('admin.courier.layouts.top_bar')

        <div class="container-fluid mt-4" id="toplist">

            {{--courier header section--}}
            <div class="row">
                <div class="col-md-6">
                    <h4>Courier</h4>
                </div>
                <div class="col-md-6">
                </div>
            </div>

            {{--courier card section--}}
            <div class="row mt-1">
                <div class="col-12">
                    <div class="row card mb-4">
                        <div class="col-lg-12 col-md-12 col-sm-12 p-4">
                            <table id="example" class="display" style="width:100%">
                                <thead>
                                <tr>
                                    <td width="3%">SL</td>
                                    <th width="10%">Name</th>
                                    <th width="10%">Store ID</th>
                                    <th width="17%">Consignment ID</th>
                                    <th width="20%">Tracking Code</th>
                                    <th width="10%">Invoice No</th>
                                    <th width="10%">Status</th>
                                    <th width="20%">Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($couriers) && count($couriers) > 0)
                                    @foreach ($couriers as $courier)
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            <td>{{ $courier->courier_name?? '' }}</td>
                                            <td>{{ $courier->courier_store_id ?? '' }}</td>
                                            <td>{{ $courier->consignment_id ?? '' }}</td>
                                            <td>
                                                @if(!is_null($courier->tracking_code) && !empty($courier->tracking_code))
                                                    @if(isset($courier->courier_name) && $courier->courier_name == "steadfast")
                                                        <a href="https://steadfast.com.bd/t/{{ $courier->tracking_code ?? '' }}"
                                                           target="_blank">{{ $courier->tracking_code ?? '' }}</a>
                                                    @else
                                                        {{ $courier->tracking_code ?? '' }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $courier->merchant_order_id ?? '' }}</td>
                                            <td>{{ $courier->delivery_status ?? '' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($courier->created_at)->format('d-m-y h:i:s A') }}</td>
                                        </tr>
                                    @endforeach
                                @endif
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function () {
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
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
@endpush
