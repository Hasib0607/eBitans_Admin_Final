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
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item active">
                                <a href="{{ URL::to('/') }}/admin/affiliate-marketing">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Affiliate Marketing
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row card mb-4 mt-4">
            <div class="col-lg-12 col-md-12 col-sm-12 p-4">
                <table id="example" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <th>Name</th>
                        <th>Store Name</th>
                        <th>Web+Digital+POS</th>
                        <th>Phone</th>
                        <th>Referral</th>
                        <th>Referral Commission</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($refers as $key => $refer)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $refer->user_name ?? '' }}</td>
                            <td>{{ $refer->store_name ?? '' }}</td>
                            <td>
                                {{ $refer->plan_name ?? '' }}
                                @if ($refer->plan_name && $refer->digital_plan_name)
                                    +
                                @endif
                                {{ $refer->digital_plan_name ?? '' }}
                                @if (($refer->digital_plan_name && $refer->pos_plan_name) || ($refer->plan_name && $refer->pos_plan_name))
                                    +
                                @endif
                                {{ $refer->pos_plan_name ?? '' }}
                            </td>
                            <td>{{ $refer->user_phone ?? '' }}</td>
                            <td>
                                {{ $refer->referral_id ?? '' }}
                            </td>
                            <td>{{ $refer->commission_price ?? '0' }} tk</td>
                            <td>{{ date('d-m-Y', strtotime($refer->created_at)) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
        function okComment(id) {
            var text = $('#comment' + id).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('superadmin.update.referral_commission') }}",
                data: {
                    id: id,
                    comment: text
                },
                success: function (data) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.success("Comment save");
                }
            });
        }
    </script>

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
                            columns: [0, 1, 2, 5]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
@endpush
