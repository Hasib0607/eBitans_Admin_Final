@extends('admin.layouts.main')

@section('content')
    <main class="main-content position-relative border-radius-lg">
        @include('admin.order.share.order-nav')

        <div class="container-fluid mt-4" id="toplist">

            {{-- ===== Top bar (like your screenshot) ===== --}}
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0">
                        @if(Session::has('lang') && Session::get('lang') == 'bn')
                            সমস্ত চালান
                        @else
                            All Invoice
                        @endif
                    </h4>
                </div>

                <div class="col-md-6">
                    <div class="d-flex justify-content-end gap-2">
                        <a data-href="/invoiceexport" onclick="exportTasks(event.target);"
                            class="btn btn-secondary btn-sm px-3" style="border-radius: 4px;">
                            @if(Session::has('lang') && Session::get('lang') == 'bn')
                                এক্সপোর্ট
                            @else
                                Excel
                            @endif
                        </a>

                        <button type="button" onclick="printSelectedInvoices()" class="btn btn-primary btn-sm px-3"
                            style="border-radius: 4px;">
                            @if(Session::has('lang') && Session::get('lang') == 'bn')
                                প্রিন্ট
                            @else
                                Print
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            {{-- ===== Filter bar container (white, rounded, no heavy border) ===== --}}
            <div class="mt-4 p-3 bg-white shadow-sm rounded" style="border:0;">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <form method="GET" action="{{ url('/invoice') }}" class="d-flex flex-wrap align-items-center gap-2 m-0">
                        <input type="text" class="form-control" name="q" value="{{ $q ?? '' }}"
                            placeholder="@if(Session::has('lang') && Session::get('lang') == 'bn') চালান আইডি / অর্ডার আইডি... @else Invoice ID / Order ID... @endif"
                            style="width: 260px; max-width: 100%;">

                        {{-- Search button beside input (like screenshot) --}}
                        <button type="submit" class="btn btn-danger btn-sm px-4" style="border-radius: 4px;">
                            @if(Session::has('lang') && Session::get('lang') == 'bn')
                                সার্চ
                            @else
                                Search
                            @endif
                        </button>

                        {{-- Optional Clear (only when searching) --}}
                        @if(!empty($q))
                            <a href="{{ url('/invoice') }}" class="btn btn-secondary btn-sm px-3" style="border-radius: 4px;">
                                @if(Session::has('lang') && Session::get('lang') == 'bn')
                                    ক্লিয়ার
                                @else
                                    Clear
                                @endif
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- ===== Table container ===== --}}
            <div class="row mt-4 productlist">
                <div class="col-12">
                    <div class="p-3 bg-white shadow-sm rounded" style="border:0;">

                        @if (Session::has('success_message'))
                            <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                        @endif

                        {{-- ===================== DESKTOP TABLE ===================== --}}
                        <div class="table-responsive" id="desktoptable">
                            <table class="table table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" id="checkAllDesktop">
                                        </th>
                                        <th width="20%">
                                            @if(Session::has('lang') && Session::get('lang') == 'bn')
                                                চালান আইডি
                                            @else
                                                Invoice ID
                                            @endif
                                        </th>
                                        <th width="15%">
                                            @if(Session::has('lang') && Session::get('lang') == 'bn')
                                                অর্ডার আইডি
                                            @else
                                                Order ID
                                            @endif
                                        </th>
                                        <th width="25%">
                                            @if(Session::has('lang') && Session::get('lang') == 'bn')
                                                প্রকার
                                            @else
                                                Type
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if(Session::has('lang') && Session::get('lang') == 'bn')
                                                দেখুন
                                            @else
                                                View
                                            @endif
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="checkSingle" value="{{ $invoice->id }}">
                                            </td>
                                            <td>{{ $invoice->reference_no }}</td>
                                            <td>{{ $invoice->orders->reference_no ?? '' }}</td>
                                            <td>{{ $invoice->type }}</td>
                                            <td>
                                                <a href="{{ route('admin.invoiceview', encrypt($invoice->id)) }}"
                                                    class="btn btn-info">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                @if(Session::has('lang') && Session::get('lang') == 'bn')
                                                    কোনো ইনভয়েস পাওয়া যায়নি
                                                @else
                                                    No invoices found
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- ===================== MOBILE TABLE ===================== --}}
                        <div class="table-responsive" id="mobiletable">
                            <table class="table" width="100%">
                                @foreach($invoices as $key => $invoice)
                                    <tr class="mobilefirstrow">
                                        <th width="10%">
                                            <input type="checkbox" class="checkSingle" name="selectedid"
                                                value="{{ $invoice->id }}">
                                        </th>
                                        <th width="20%" style="color:#f1593a">Invoice Id:</th>
                                        <td width="60%" style="color:black">{{ $invoice->reference_no }}</td>
                                        <td width="10%">
                                            <a href="#" class="toggler" data-prod-cat="{{ $key }}">
                                                <i class="fa fa-arrow-down" id="show{{ $key }}" style="color:#f1593a"></i>
                                                <i class="fa fa-arrow-up" id="up{{ $key }}" style="display:none"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr class="cat{{ $key }}" style="display:none">
                                        <th width="10%"></th>
                                        <th width="20%">Order Id:</th>
                                        <td width="60%">{{ $invoice->order_id }}</td>
                                        <td width="10%"></td>
                                    </tr>

                                    <tr class="cat{{ $key }}" style="display:none">
                                        <th width="10%"></th>
                                        <th width="20%">Type</th>
                                        <td width="60%">{{ $invoice->type }}</td>
                                        <td width="10%"></td>
                                    </tr>

                                    <tr class="cat{{ $key }}" style="display:none">
                                        <th width="10%"></th>
                                        <th width="20%">View</th>
                                        <td width="60%">
                                            <a href="{{ route('admin.invoiceview', encrypt($invoice->id)) }}">View</a>
                                        </td>
                                        <td width="10%"></td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        {{-- ✅ Pagination --}}
                        @if(method_exists($invoices, 'links'))
                            <div class="mt-3 d-flex justify-content-end">
                                {{ $invoices->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Select all (desktop)
            $('#checkAllDesktop').on('change', function () {
                $('.checkSingle').prop('checked', $(this).is(':checked'));
            });

            // If any unchecked → uncheck select all
            $(document).on('change', '.checkSingle', function () {
                if (!$(this).is(':checked')) {
                    $('#checkAllDesktop').prop('checked', false);
                }
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }

        function printSelectedInvoices() {
            const ids = $('.checkSingle:checked').map(function () {
                return $(this).val();
            }).get();

            if (ids.length === 0) {
                alert('Please select at least one invoice to print.');
                return;
            }

            const url = "{{ route('admin.invoice.printSelected') }}" + "?ids=" + ids.join(',');
            window.open(url, '_blank');
        }
    </script>
@endpush