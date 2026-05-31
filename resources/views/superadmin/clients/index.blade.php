@extends('admin.layouts.main')

@push('styles')
    {{-- DataTables CSS --}}
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        /* Styling for DataTables */
        table.table-bordered.dataTable tbody th,
        table.table-bordered.dataTable tbody td {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            text-align: center;
            align-items: center;
        }
        
        /* Hide fixed plugin */
        #fixedplugin {
            display: none;
        }
    </style>
@endpush

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            {{-- Breadcrumb links based on user permissions --}}
                            @if ((isset($clientsPer) && $clientsPer == '1') || Auth::user()->type == 'superadmin')
                                <li class="breadcrumb-item">
                                    <a href="{{ URL::to('/') }}/clients">
                                        <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Clients
                                    </a>
                                </li>
                            @endif
                            @if ((isset($clients_Follow_UpPer) && $clients_Follow_UpPer == '1') || Auth::user()->type == 'superadmin')
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.paidClients') }}">
                                        <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Paid Clients
                                    </a>
                                </li>
                            @endif
                            @if ((isset($clients_ActivitiesPer) && $clients_ActivitiesPer == '1') || Auth::user()->type == 'superadmin')
                                <li class="breadcrumb-item active">
                                    <a href="{{ route('admin.clients.activities') }}">
                                        <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Clients Activities
                                    </a>
                                </li>
                            @endif
                            @if ((isset($clients_Follow_UpPer) && $clients_Follow_UpPer == '1') || Auth::user()->type == 'superadmin')
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.clients.followUp') }}">
                                        <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Clients Follow-up
                                    </a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active">
                                <h1 style="color:#fff;">{{ $last30Days ?? 00 }}</h1>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row card mb-4">
            <div class="col-lg-12 col-md-12 col-sm-12 p-4">
                {{-- DataTable for displaying client information --}}
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Store Name</th>
                            <th>Phone</th>
                            <th>FollowUp</th>
                            <th>Comment</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody style="justify-items: center;">
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- Modal for comments --}}
    <div id="modalAppand"></div>
@endsection

@push('scripts')
    {{-- jQuery and DataTables Scripts --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script>
        // Function to save comment via AJAX
        function okComment(id) {
            var text = $('#comment' + id).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.client.commnet') }}",
                data: {
                    id: id,
                    comment: text
                },
                success: function(data) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.success("Comment saved");
                }
            });
        }

        $(document).ready(function() {
            // Initialize DataTable with server-side processing
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('superAdmin.clients') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'store_name', name: 'store_name' },
                    { data: 'phone', name: 'phone' },
                    { data: 'followUp', name: 'followUp' },
                    { data: 'comment', name: 'comment' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        // Function to handle follow-up actions via AJAX
        function flowUp(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "{{ route('superAdmin.clients.modal') }}",
                data: { id: id },
                success: function(data) {
                    $('#modalAppand').html(data);
                    $('#AnalyticesCommentModal').modal('toggle');
                }
            });
        }
    </script>
@endpush
