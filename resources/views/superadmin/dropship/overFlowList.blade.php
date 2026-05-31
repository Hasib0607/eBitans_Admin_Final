@extends('admin.layouts.main')
@push('styles')
    <style>
        .colToText {
            width: 3% !important;
            padding: 0;
            flex: unset;
        }

        @media (max-width: 768px) {
            .colToText {
                width: 100% !important;
            }
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('superadmin.dropship.top_nav_menu')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-12">
                    <h4>All Dropshipper</h4>
                </div>
            </div>

            <div class="row mt-1 productlist">
                <div class="col-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <div class="row">
                                <form action="{{ route('superadmin.overflow.list') }}"
                                      method="get"
                                      class="row">
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search"
                                                   value="{{ $search ?? '' }}"
                                                   class="form-control">
                                            <span class="input-group-text"
                                                  style="padding: 0.75rem 11px !important;">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-1" style="padding-left:0px;">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" style="width:100%">
                                    <thead>
                                    <tr>
                                        <td width="3%">SL</td>
                                        <th width="12%">Name</th>
                                        <th width="10%">URL</th>
                                        <th width="10%">Email</th>
                                        <th width="10%">Phone</th>
                                        <th width="15%">Total commission</th>
                                        <th width="10%">Option</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($stores))
                                        @foreach ($stores as $key => $store)
                                            <tr>
                                                <td>{{ ($stores->currentPage() - 1) * $stores->perPage() + $loop->iteration }}</td>
                                                <td>{{ $store->name?? '' }}</td>
                                                <td>{{ $store->url ?? '' }}</td>
                                                <td>{{ $store->user->email ?? '' }}</td>
                                                <td>{{ $store->user->phone ?? '' }}</td>
                                                <td>{{ $store->balance }} BDT</td>
                                                <td>
                                                    <div class="form-check form-switch" style="text-align:center;">
                                                        <input class="form-check-input switchstatus" type="checkbox"
                                                               data-id="{{ $store->id }}" id="flexSwitchCheckChecked"
                                                               name="checkstatus" style="margin:0 auto;"
                                                               @if ($store->status == "active") checked @endif>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                No Record Found
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="d-flex mt-4 mb-4 justify-content-between">
                                    {!! $stores->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script>
        $(document).ready(function () {

            // Update the accepted product status
            $(".switchstatus").on("change", function () {
                handleStatusChange($(this));
            });


            /**
             * Handles the change event for the 'switchstatus' input
             * @param {Object} element - The jQuery object representing the 'switchstatus' input
             */
            function handleStatusChange(element) {
                var value = element.val();
                var id = element.data('id');
                sendAjaxRequest('{{ route('superadmin.store.status.change') }}', {
                    value: value,
                    id: id
                });
            }

            /**
             * Sends an AJAX request and handles the response
             * @param {string} url - The URL for the AJAX request
             * @param {Object} requestData - The data to be sent in the request
             */
            function sendAjaxRequest(url, requestData) {
                $.get(url, requestData, function (data) {
                    if (data.status) {
                        Swal.fire(
                            'Success',
                            data.message,
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error',
                            data.message,
                            'error'
                        );
                    }
                });
            }
        });
    </script>
@endpush
