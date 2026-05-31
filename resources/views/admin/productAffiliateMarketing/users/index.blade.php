@extends('admin.layouts.main')

{{--banner styles--}}
@push('styles')
    <style>
        .left-menu {
            position: relative;
            top: 50% !important;
        }

        .left-menu ul li {
            float: unset !important;
        }

        .card {
            border: 1px solid rgba(222, 226, 230, 0.7);
        }

        .card .card-body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            padding: .5rem 1.5rem 1.5rem 1.5rem;
        }

        .card .card-header {
            padding: .5rem 1.5rem .5rem 1.5rem;
            border-bottom: 1px solid rgba(222, 226, 230, 0.7);
        }

        .rightmenu li {
            float: left !important;
            padding: 1px 16px !important;
        }

        .select2-container .select2-selection--multiple {
            min-height: 40px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: 0px solid black !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 4px !important;
        }

        /*.is-focused{*/
        /*    background-color:red !important;*/
        /*    width:60%;*/
        /*    padding:10px 0px;*/
        /*}*/
        input[type=radio] {
            opacity: 1;
        }

        .headerimg {
            width: 80%;
            height: 150px;
        }

        button.btn {
            margin-bottom: 0;
        }

        div#commissionsTableContainer\ table-responsive {
            width: 100%;
            overflow-x: auto;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
@endpush

{{--Affiliate User main section--}}
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">

        {{--design top nav section--}}
        @include('admin.productAffiliateMarketing.share.affiliate-nav')

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6" style="display: flex;align-items: center;margin-bottom: 10px;">
                    <h4>User List</h4>
                    <button class="btn bg-orange-600 btn-danger mx-1px text-95"
                            style="margin-left: 10px"
                            data-bs-toggle="modal"
                            data-bs-target="#minWithdraw"
                            data-title="Print">
                        Set Minimum Withdraw Amount
                    </button>
                </div>
            </div>

            {{--Set Minimum Withdraw Amount modal--}}
            <div class="modal fade" id="minWithdraw" tabindex="-1"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content"
                         style="background-color:transparent;border:0px">

                        <div class="modal-body" style="border:none">
                            <button class="btn btn-danger sm" data-bs-dismiss="modal"
                                    style="float: right; margin: 0px 8px;">X
                            </button>
                            @php
                                $store_id = getUserData()['store_id'] ?? "";
                                $headerSetting = \App\Models\Headersetting::where("store_id", $store_id)->first();
                                $affiliate_min_withdraw = $headerSetting->affiliate_min_withdraw;
                            @endphp
                            <div class="row mt-1">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <form method="post" action="{{ route("admin.affiliateMinWithdraw") }}">
                                                    @csrf

                                                    <div class="">
                                                        <label>Minimum Withdraw Amount</label>
                                                        <br>
                                                        <input name="affiliate_min_withdraw" type="number"
                                                               class="form-control"
                                                               required pattern="[0-9]{11}" min="1"
                                                               value="{{ $affiliate_min_withdraw ?? "" }}">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-3"
                                                            style="margin-top: 5px">Submit
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card">

                <div class="card-header">
                    <div class="row">
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success" style="color:#fff">
                                    {{ Session::get('success_message') }}</div>
                            @endif

                            {{-- For dasktop user --}}
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped text-center" width="100%" id="taskfilterresult">
                                    <thead>
                                    <tr>
                                        <th width="12.5%">Name</th>
                                        <th width="12.5%">phone</th>
                                        <th width="12.5%">email</th>
                                        <th width="12.5%">Referral ID</th>
                                        <th width="12.5%">Commission Percent</th>
                                        <th width="12.5%">Create At</th>
                                        <th width="12.5%">status</th>
                                        <th width="12.5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($affiliates as $key=>$affiliate)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>{{$affiliate->name}}</td>
                                            <td>{{$affiliate->phone ?? ''}}</td>
                                            <td>{{$affiliate->email?? ''}}</td>
                                            <td>{{$affiliate->referral_code}}</td>
                                            <td>
                                                <input
                                                    name="commission_percent"
                                                    type="text" class="form-control"
                                                    step="0.01"
                                                    id="commission{{ $affiliate->id }}"
                                                    onchange="changeCommission({{ $affiliate->id }})"
                                                    value="{{ $affiliate->commission_percent }}"
                                                >
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($affiliate->created_at)) }}</td>
                                            <td style="text-align: center;display: flex;align-items: center;justify-content: center;padding-left: 0px;">
                                                <div class="form-check form-switch"
                                                     style="text-align:center; padding: 15px 0">
                                                    <input class="form-check-input switchstatus" type="checkbox"
                                                           id="flexSwitchCheckChecked" data-id="{{$affiliate->id}}"
                                                           name="status" style="margin:0 auto;"
                                                           @if($affiliate->status) checked @endif>
                                                    <label class="form-check-label"
                                                           for="flexSwitchCheckChecked"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <button data-bs-toggle="modal" class="btn btn-transparent border-0 mx-2"
                                                        onclick="loadCommissions({{ $affiliate->user_id }})">
                                                    <img
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#commissionsModal"
                                                        src="{{asset('img/eye.png')}}"
                                                        width="20px"
                                                        height="20px"
                                                        data-user-id="{{ $affiliate->user_id }}"
                                                        class="commission commission{{$affiliate->user_id}}"
                                                    />

                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! $affiliates->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="commissionsModal" tabindex="-1" aria-labelledby="commissionsModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="commissionsModalLabel">Affiliate Commissions</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="commissionsTableContainer" class="text-center table-responsive"></div>
                            <div id="commissionsPagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('scripts')
    {{--    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>--}}
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>
    <script>
        let user_id = '';

        // Function to load commissions via AJAX and display them in the modal
        function loadCommissions(userId, page = 1) {
            user_id = userId;
            $.ajax({
                url: '{{ route("admin.product_affiliate.user.customer-details") }}', // Adjust this to your route
                type: 'GET',
                data: {
                    user_id: userId,
                    page: page
                },
                success: function (response) {
                    if (response.success && response.data.data.length > 0) {
                        let html = `<table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Reference No</th>
                                        <th>Customer Name</th>
                                        <th>Phone</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Commission (%)</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                            <tbody>`;
                        response.data.data.forEach(commission => {
                            html += `<tr>
                                <td>${commission.order.reference_no}</td>
                                <td>${commission.order.name}</td>
                                <td>${commission.order.phone}</td>
                                <td>${commission.product.name}</td>
                                <td>${commission.product_price}</td>
                                <td>${commission.commission_percent}</td>
                                <td>${commission.amount}</td>
                            </tr>`;
                        });
                        html += '</tbody></table>';
                        $('#commissionsTableContainer').html(html);

                        // Pagination
                        let paginationHtml = '';
                        if (response.data.links.length > 3) {
                            response.data.links.forEach(link => {
                                if (link.label === "pagination.previous" || link.label === "pagination.next") {
                                    paginationHtml += `<button
                                            onclick="paginateCommission('${link.url}')"
                                            class="btn btn-transparent btn-sm mx-1 rounded-circle">
                                                ${link.label === "pagination.previous" ? '<' : '>'}
                                        </button>`;
                                } else {
                                    paginationHtml += `<button
                                            onclick="paginateCommission('${link.url}')"
                                            class="${link.active ? 'btn btn-primary btn-sm ' : 'btn btn-transparent'} rounded-circle mx-1">
                                                ${link.label}
                                        </button>`;
                                }
                            });
                        }
                        $('#commissionsPagination').html(paginationHtml); // Display pagination
                    } else {
                        $('#commissionsTableContainer').html('<p>No commissions found.</p>');
                    }
                    $('#commissionsModal').modal('show');
                }
            });
        }

        function paginateCommission(url) {
            const urlParams = new URLSearchParams(new URL(url).search);
            const page = urlParams.get('page');
            loadCommissions(user_id, page);
        }

        function changeCommission(id) {
            var text = $('#commission' + id).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.product_affiliate.user.change_commission') }}",
                data: {
                    id: id,
                    commission_percent: text
                },
                success: function (data) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.success("Commission Set Successfully");
                }
            });
        }

        $(document).ready(function () {
            $('#taskfilterresult').DataTable();

            // Event listener for status change
            $(document).on("change", ".switchstatus", function () {
                $url = "/admin/product-affiliate/user-change-status";
                var id = $(this).data('id');
                $.get($url, {
                    id: id
                }, function (data) {
                    if (data.status == 200) {
                        swal.fire(
                            'success!',
                            data.message,
                            'success'
                        );
                    } else {
                        var isChecked = $(this).is(':checked');

                        if (isChecked) {
                            $(".switchstatus").prop('checked', false);
                        } else {
                            $(".switchstatus").prop('checked', true);
                        }

                        swal.fire(
                            'Warning!',
                            data.message,
                            'warning'
                        );
                    }
                });
            });
        });


    </script>
@endpush

