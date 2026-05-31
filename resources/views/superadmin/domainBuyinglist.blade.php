@extends('admin.layouts.main')
@push('styles')
    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- The Modal -->
        <div class="modal fade" id="myDomainLoadingModal">
            <div class="modal-dialog"
                 style="display: flex;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);width: 50%;height: 50%; border-radius: 10px;background: #fff;justify-content: center;align-items: center;">
                <div class="modal-content" style="border: none;">
                    <div style=" display: flex;justify-content: center;align-items: center;flex-direction: column;">
                        <svg aria-hidden="true" role="status" style="width: 50px;margin-bottom: 15px;color: #f1593a;"
                             class="inline w-14 h-14 me-3 text-['#f1593a'] animate-spin" viewBox="0 0 100 101"
                             fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="#E5E7EB"/>
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentColor"/>
                        </svg>
                        Processing...
                    </div>
                </div>
            </div>
        </div>


        @include('superadmin.share.domain-nav.nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>Domain Buying List</h4>
                </div>
            </div>
            <div class="row mt-3 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               aria-label="Dollar amount (with dot and two decimal places)"
                                               id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                            {{--<input type="checkbox" id="idSearch">--}}
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{Session::get('success_message')}}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th width="5%">ID</th>
                                        <th width="20%">Name</th>
                                        <th width="20%">Email</th>
                                        <th width="10%">Create Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($domain) && count($domain)>0)
                                        @php
                                            $serialNumber = ($domain->currentPage() - 1) * $domain->perPage();
                                        @endphp
                                        @foreach($domain as $dm)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td>{{ $serialNumber + $loop->iteration }}</td>
                                                <td><a href="{{URL::to('/')}}/client/view/{{$dm->uid}}" target="_blank"
                                                       style="text-decoration:underline;color:blue">{{$dm->id}}</a></td>
                                                <td>{{$dm->name}}</td>
                                                <td>{{$dm->email ?? ""}}</td>
                                                <td>{{date('d-m-Y', strtotime($dm->created_at))}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            {{ $domain->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#taskfilterresult tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
@endpush
