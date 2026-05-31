@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item active">
                                <a href="{{ URL::to('/') }}/superadmin/planorderrequest">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Old Plan Order Request
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/') }}/superadmin/order-plan-request">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Plan Order Request
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/') }}/superadmin/planorderrequest/rejected">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Rejected Plan Order
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/') }}/superadmin/planorderrequest-today">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Today Plan Order
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/') }}/superadmin/invoiceorder">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Invoice Order Request
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/') }}/superadmin/allinvoiceorder">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Invoice Order
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('superadmin.customizerequest') }}">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Customize Request
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('superadmin.modulus.request') }}">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Modulus Request
                                </a>
                            </li>


                            <li class="breadcrumb-item">
                                <a href="{{ route('superadmin.registrationFee') }}">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br>Registration Fee
                                </a>
                            </li>

                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4 mb-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Request</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary"
                                                               style="display:block;border-radius:0px !important">Create
                                New</a></li>
                        <li style="padding:0px;border:0px;">
                            <a data-href="/tasks" onclick="htmlTableToExcel('xlsx')"
                               style="display:block;border-radius:0px !important" class="btn btn-secondary">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    এক্সপোর্ট
                                @else
                                    Download Excel
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 pb-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox"></th>
                                        <th width="5%">Plan Name</th>
                                        <th width="5%">Package Name</th>
                                        <th Width="5%">Store</th>
                                        <th width="20%">Month</th>
                                        <th width="5%">Total Amount</th>
                                        <th width="10%">Customer Name</th>
                                        <th width="5%">Payment Method</th>
                                        <th width="10%">Transaction Id</th>
                                        <th width="10%">Number</th>
                                        <th width="10%">Addons</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Create Date</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if (isset($data) && count($data) > 0)
                                        @foreach ($data as $key => $dm)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td><input type="checkbox" name="id" value="{{ $dm->id }}">
                                                </td>
                                                    <?php
                                                    $plan = DB::table('plans')
                                                        ->where('id', $dm->plan_id)
                                                        ->first();
                                                    // dd($plan);
                                                    ?>

                                                @if ($dm->plan_id != null && $dm->pos_plan_id != null && $dm->digital_plan_id != null)
                                                    <td>
                                                        <strong>POS+WEB+SMM</strong>
                                                    </td>
                                                @elseif ($dm->plan_id != null && $dm->pos_plan_id != null && $dm->digital_plan_id == null)
                                                    <td>
                                                        <strong>WEB+POS</strong>
                                                    </td>
                                                @elseif ($dm->plan_id == null && $dm->pos_plan_id != null && $dm->digital_plan_id != null)
                                                    <td>
                                                        <strong>POS+SMM</strong>
                                                    </td>
                                                @elseif ($dm->plan_id != null && $dm->pos_plan_id == null && $dm->digital_plan_id != null)
                                                    <td>
                                                        <strong>WEB+SMM</strong>
                                                    </td>
                                                @elseif ($dm->plan_id != null && $dm->pos_plan_id == null && $dm->digital_plan_id == null)
                                                    <td>
                                                        <strong>WebSite</strong>
                                                    </td>
                                                @elseif ($dm->pos_plan_id != null && $dm->plan_id == null && $dm->digital_plan_id == null)
                                                    <td>
                                                        <strong>POS</strong>
                                                    </td>
                                                @elseif ($dm->digital_plan_id != null && $dm->pos_plan_id == null && $dm->plan_id == null)
                                                    <td>
                                                        <strong>Digital</strong>
                                                    </td>
                                                @endif

                                                {{-- plan name  --}}
                                                @if ($dm->plan_id != null && $dm->pos_plan_id != null && $dm->digital_plan_id != null)
                                                    <td>
                                                        <strong>{{ $dm->posPlan->name ?? '' }}
                                                            +{{ $dm->webPlan->name ?? '' }}
                                                            +{{ $dm->smmPlan->name ?? '' }}</strong>
                                                    </td>
                                                @elseif ($dm->plan_id != null && $dm->pos_plan_id != null && $dm->digital_plan_id == null)
                                                    <td>
                                                        <strong>{{ $dm->webPlan->name ?? '' }}
                                                            +{{ $dm->posPlan->name ?? '' }}</strong>
                                                    </td>
                                                @elseif ($dm->plan_id == null && $dm->pos_plan_id != null && $dm->digital_plan_id != null)
                                                    <td>
                                                        <strong>{{ $dm->posPlan->name ?? '' }}
                                                            +{{ $dm->smmPlan->name ?? '' }}</strong>
                                                    </td>
                                                @elseif ($dm->plan_id != null && $dm->pos_plan_id == null && $dm->digital_plan_id != null)
                                                    <td>
                                                        <strong>{{ $dm->webPlan->name ?? '' }}
                                                            +{{ $dm->smmPlan->name ?? '' }}</strong>
                                                    </td>
                                                @elseif ($dm->plan_id != null && $dm->pos_plan_id == null && $dm->digital_plan_id == null)
                                                    <td>
                                                        <strong> {{ $dm->webPlan->name ?? '' }} </strong>
                                                    </td>
                                                @elseif ($dm->pos_plan_id != null && $dm->plan_id == null && $dm->digital_plan_id == null)
                                                    <td>
                                                        <strong>{{ $dm->posPlan->name ?? '' }}</strong>
                                                    </td>
                                                @elseif ($dm->digital_plan_id != null && $dm->pos_plan_id == null && $dm->plan_id == null)
                                                    <td>
                                                        <strong>{{ $dm->smmPlan->name ?? '' }}</strong>
                                                    </td>
                                                @endif
                                                {{-- end plan name  --}}
                                                <td>
                                                        <?php
                                                        $storesss = DB::table('stores')
                                                            ->where('id', $dm->store_id)
                                                            ->first();
                                                        ?>
                                                    {{ $storesss->name ?? 'empty' }}
                                                </td>


                                                {{-- plan month  --}}
                                                @if ($dm->plan_id != null && $dm->pos_plan_id != null && $dm->digital_plan_id != null)
                                                    <td>
                                                        <strong>{{ $dm->pos_plan_month ?? '0' }}
                                                            +{{ $dm->total_month ?? '0' }}
                                                            +{{ $dm->digital_plan_month ?? '0' }}</strong>
                                                    </td>
                                                @elseif ($dm->plan_id != null && $dm->pos_plan_id != null && $dm->digital_plan_id == null)
                                                    <td>
                                                        <strong>{{ $dm->total_month ?? '0' }}
                                                            +{{ $dm->pos_plan_month ?? '0' }}</strong>
                                                    </td>
                                                @elseif ($dm->plan_id == null && $dm->pos_plan_id != null && $dm->digital_plan_id != null)
                                                    <td>
                                                        <strong>{{ $dm->pos_plan_month ?? '0' }}
                                                            +{{ $dm->digital_plan_month ?? '0' }}</strong>

                                                    </td>
                                                @elseif ($dm->plan_id != null && $dm->pos_plan_id == null && $dm->digital_plan_id != null)
                                                    <td>
                                                        <strong>{{ $dm->total_month ?? '0' }}
                                                            +{{ $dm->digital_plan_month ?? '0' }}</strong>
                                                    </td>
                                                @elseif ($dm->plan_id != null && $dm->pos_plan_id == null && $dm->digital_plan_id == null)
                                                    <td>
                                                        <strong>{{ $dm->total_month ?? '0' }}</strong>
                                                    </td>
                                                @elseif ($dm->pos_plan_id != null && $dm->plan_id == null && $dm->digital_plan_id == null)
                                                    <td>
                                                        <strong>{{ $dm->pos_plan_month ?? '0' }}</strong>
                                                    </td>
                                                @elseif ($dm->digital_plan_id != null && $dm->pos_plan_id == null && $dm->plan_id == null)
                                                    <td>
                                                        <strong>{{ $dm->digital_plan_month ?? '0' }}</strong>

                                                    </td>
                                                @endif
                                                {{-- end plan month  --}}


                                                <td>{{ $dm->total_amount??'' }}</td>
                                                    <?php
                                                    $customer = DB::table('customers')
                                                        ->where('id', $dm->customer_id ?? '')
                                                        ->first();
                                                    $user = DB::table('users')
                                                        ->where('id', $customer->uid ?? '')
                                                        ->first();
                                                    ?>
                                                <td>
                                                    <a href="{{ URL::to('/') }}/client/view/{{ $user->id??'' }}">{{ $user->name ?? '' }}
                                                    </a>
                                                </td>
                                                <td>{{ $dm->method }}</td>
                                                <td>{{ $dm->transaction_id }}</td>
                                                <td>{{ $dm->number }}</td>
                                                <td>
                                                        <?php
                                                        $addonss = DB::table('addons')
                                                            ->where('plan_order_id', $dm->id)
                                                            ->get();
                                                        $i = 1;
                                                        ?>
                                                    @if (isset($addonss) && count($addonss) > 0)
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="exampleModal{{ $key }}"
                                                             tabindex="-1" aria-labelledby="exampleModalLabel"
                                                             aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Addons</h5>
                                                                        <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>SL</th>
                                                                                    <th>Name</th>
                                                                                    <th>Month</th>
                                                                                    <th>Expiry Date</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                @if (isset($addonss) && count($addonss) > 0)
                                                                                    @foreach ($addonss as $add)
                                                                                        <tr>
                                                                                            <td>{{ $i++ }}
                                                                                            </td>
                                                                                            <td>{{ $add->name }}
                                                                                            </td>
                                                                                            <td>{{ $add->month }}
                                                                                            </td>
                                                                                            <td>{{ $add->expiry_date }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!----Modal End---->
                                                    @endif
                                                    @if (isset($addonss) && count($addonss) > 0)
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                           data-bs-target="#exampleModal{{ $key }}"
                                                           id="viewaddons" data-id="{{ $dm->id }}">view</a>
                                                </td>
                                                @endif
                                                <td>{{ $dm->status }}</td>
                                                <td>{{ date('d-m-Y H:m:s', strtotime($dm->created_at)) }}</td>
                                                <td><a href="{{ route('superadmin.planorder.accept', $dm->id) }}"
                                                       onclick="return confirm('Are you sure, you want to accpect this Order?')"
                                                       class="btn btn-primary">Accept</a>
                                                    <a href="{{ route('superadmin.planorder.reject', $dm->id) }}"
                                                       onclick="return confirm('Are you sure, you want to Reject this Order?')"
                                                       class="btn btn-secondary">Reject</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="d-flex mt-4 mb-4 justify-content-center">
                                {{ $data->links() }}
                            </div> --}}
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




    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function htmlTableToExcel(type) {
            var data = document.getElementById('taskfilterresult');
            var excelFile = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(excelFile, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(excelFile, 'ExportedFile:{{ auth()->user()->name }}.' + type);
        }
    </script>
@endpush
