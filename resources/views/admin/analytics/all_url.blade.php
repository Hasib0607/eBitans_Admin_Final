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
        @include('admin.analytics.partials.header')

        <div class="container-fluid mt-2" id="toplist">
            {{--            <div class="row">--}}
            {{--                <div class="col-md-8">--}}
            {{--                    <h4>All URL</h4>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <div class="row mt-4 mb-5 mb-md-0">
                <div class="col-12 mb-5">
                    <div class="card h-100 mt-4 mt-md-0">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <form action="{{ route('admin.ebitans.analytics.all.url') }}" method="get" class="row">
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
                        </div>
                        <div class="card-body px-3 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="1%">
                                            SL
                                        </th>
                                        <th width="14%">
                                            Page Title
                                        </th>
                                        <th width="35%">
                                            Page URL
                                        </th>
                                        {{--                                        <th width="35%">--}}
                                        {{--                                            Refer Page URL--}}
                                        {{--                                        </th>--}}
                                        <th width="5%">
                                            Total Page Views
                                        </th>
                                        <th width="5%">
                                            Avg. Visit Time
                                        </th>
                                        {{--                                        <th width="5%">--}}
                                        {{--                                            Visits Per Day--}}
                                        {{--                                        </th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($reports) && count($reports) > 0)
                                        @foreach($reports as $report)
                                            <tr>
                                                <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration }}</td>
                                                <td>{{ $report->page_title }}</td>
                                                <td>{{ $report->page_url }}</td>
                                                {{--                                                <td>{{ $report->refer_page_url }}</td>--}}
                                                <td>{{ $report->total_page_views }}</td>
                                                <td>
                                                    @php
                                                        $time = round($report->avg_visit_time, 2);
                                                        $time = $time / $report->total_page_views ;
                                                        if($time < 10){
                                                            $time = rand(10,25);
                                                        }
                                                    @endphp
                                                    <p class="text-sm font-weight-normal mb-0">{{ ceil($time) }}
                                                        Sec</p>
                                                </td>
                                                {{--                                                <td>--}}
                                                {{--                                                    <p class="text-sm font-weight-normal mb-0">{{ $report->visits_per_day }}</p>--}}
                                                {{--                                                </td>--}}
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No record found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div>
                                    {!! $reports->appends(['search' => request('search'),'from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
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
@endpush
