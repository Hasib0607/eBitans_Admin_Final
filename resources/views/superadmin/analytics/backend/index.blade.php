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


        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <form action="{{ route('super.admin.ebitans.backend.analytics') }}" method="get"
                                  class="row">
                                <div class="col-md-2" style="padding-right:1px;">
                                    <select class='form-control' name="type" id="action">
                                        <option value="" {{ isset($type) && $type == "" ? 'selected' : '' }}>Select
                                            Option
                                        </option>
                                        <option
                                            value="admin" {{ isset($type) && $type == "admin" ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                        <option
                                            value="dropshipper" {{ isset($type) && $type == "dropshipper" ? 'selected' : '' }}>
                                            Drop shipper
                                        </option>
                                        <option
                                            value="affiliate" {{ isset($type) && $type == "affiliate" ? 'selected' : '' }}>
                                            Affiliate
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-1 text-end mt-1">
                                    <label for="from_date">From Date</label>
                                </div>

                                <div class="col-md-2">
                                    <input type="date" name="from_date" id="from_date"
                                           value="{{ $from_date ?? '' }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-1 text-end mt-1">
                                    <label for="to_date">To Date</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="to_date" id="to_date" value="{{ $to_date ?? '' }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                                               class="form-control">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </span>
                                    </div>
                                </div>

                                <div class="col-md-2" style="padding-left:0px;">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                <div class="card">
                                    <div class="card-header p-3 pt-2">
                                        <div
                                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                            <i class="material-icons opacity-10">weekend</i>
                                        </div>
                                        <div class="text-end pt-1">
                                            <p class="text-sm mb-0 text-capitalize">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    মোট দোকান
                                                @else
                                                    Total Stores
                                                @endif
                                            </p>

                                            <div id="productCountLoading" class="loadingDiv" style="display: none">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <span>Loading...</span>
                                            </div>
                                            <h4 class="mb-0" id="productCount">{{ $totalStore ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                <div class="card">
                                    <div class="card-header p-3 pt-2">
                                        <div
                                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                            <i class="material-icons opacity-10">person</i>
                                        </div>
                                        <div class="text-end pt-1">
                                            <p class="text-sm mb-0 text-capitalize">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    মোট পাতা
                                                @else
                                                    Total Pages
                                                @endif
                                            </p>
                                            <div id="userCountLoading" class="loadingDiv" style="display: none">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <span>Loading...</span>
                                            </div>
                                            <h4 class="mb-0" id="userCount">{{ $totalPage ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                <div class="card">
                                    <div class="card-header p-3 pt-2">
                                        <div
                                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                            <i class="material-icons opacity-10">person</i>
                                        </div>
                                        <div class="text-end pt-1">
                                            <p class="text-sm mb-0 text-capitalize">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    মোট পাতা দেখা
                                                @else
                                                    Total Page Views
                                                @endif
                                            </p>
                                            <div id="userCountLoading" class="loadingDiv" style="display: none">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                <span>Loading...</span>
                                            </div>
                                            <h4 class="mb-0" id="userCount">{{ $totalPageView ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row card mb-4 mt-3">
            <div class="col-lg-12 col-md-12 col-sm-12 p-4">
                <table id="example" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <th>Vistor</th>
                        <th>Store Name</th>
                        <th>Phone</th>
                        <th>Url</th>
                        <th>County</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Device</th>
                        <th>Browser</th>
                        <th>Timezone</th>
                        <th>IP</th>
                        <th>Latitude, Longitude</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($analyticsInfo as $key => $traffic)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $traffic->getUser->name?? 'Unauthorized' }}</td>
                            <td>{{ $traffic->getStore->name?? 'Unauthorized' }}</td>
                            <td>{{ $traffic->getUser->phone ?? 'Unauthorized' }}</td>


                            <td>
                                    <?php
                                    $urls = DB::table('admin_user_analytics')
                                        ->where('store_id', $traffic->store_id)->orderBy('updated_at', 'DESC')
                                        ->get();
                                    ?>
                                    <!-- Modal -->
                                <div class="modal fade" id="AnalyticesExampleModal{{ $key }}"
                                     tabindex="-1" aria-labelledby="exampleModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    <span
                                                        style="color: orange">{{ $traffic->getStore->name?? 'Unauthorized' }}</span>
                                                    Visite url
                                                </h5>
                                                <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if (!empty($urls))
                                                    <div class="table-responsive">
                                                        <table class="table">

                                                            <thead>
                                                            <tr>
                                                                <th>SL</th>
                                                                <th>Visite</th>
                                                                <th>URL</th>
                                                                <th>Updated At</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach ($urls as $i=> $item)
                                                                <tr>
                                                                    <td>
                                                                        {{ $i }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->number_of_visits }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->url }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->updated_at }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <h2>There is no page here yet</h2>
                                                @endif

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
                                <a href="javascript:void(0)" data-bs-toggle="modal" class="btn btn-info btn-sm"
                                   data-bs-target="#AnalyticesExampleModal{{ $key }}"
                                   id="viewaddons" data-id="{{ $traffic->id }}">
                                    View
                                </a>
                            </td>


                            <td>{{ $traffic->countryName ?? 'Unknown' }}</td>
                            <td>{{ $traffic->cityName ?? 'Unknown' }}</td>
                            <td>{{ $traffic->regionName ?? 'Unknown' }}</td>
                            <td>{{ $traffic->platform ?? 'Unknown' }}</td>
                            <td>{{ $traffic->browser ?? 'Unknown' }}.{{ $traffic->browser_version ?? 'Unknown' }}</td>
                            <td>{{ $traffic->timezone ?? 'Unknown' }}</td>
                            <td>{{ $traffic->ip ?? 'Unknown' }}</td>
                            <td>{{ $traffic->latitude ?? 'Unknown' }}, {{ $traffic->longitude ?? 'Unknown' }}</td>
                            <td>{{ $traffic->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-3" style="text-align: center;">
                    {!! $analyticsInfo->appends(['type' => request('type'),'search' => request('search'),'from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
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
                            columns: [0, 1, 2, 5]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
@endpush
