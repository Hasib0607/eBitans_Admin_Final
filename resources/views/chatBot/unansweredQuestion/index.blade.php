@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative border-radius-lg" style="min-height: 100vh">

        {{-- Top nav bar --}}
        @include('chatBot.top_nav_menu')

        <div class="container-fluid mt-4" id="toplist">

            <div class="row align-items-center">
                <div class="col-md-7">
                    <h4 class="mb-1">Support Learning Queue</h4>
                    <p class="text-sm text-muted mb-0">Open support-chat questions captured by the Python bot and waiting for a manual answer.</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('chatBot.support.analytics') }}" class="btn btn-outline-primary">
                        View Support Analytics
                    </a>
                </div>
            </div>

            <div class="row mt-3 productlist">
                <div class="col-12">
                    <div class="card">
                        {{--Table top action and search filter option--}}
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-gradient-warning">Status: Open</span>
                                        <span class="badge bg-gradient-info">Bot Type: Support</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               aria-label="Dollar amount (with dot and two decimal places)"
                                               id="taskfilter">
                                        <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                class="fa fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Table card--}}
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="8%">ID</th>
                                        <th width="38%">Question</th>
                                        <th width="10%">Bot Type</th>
                                        <th width="10%">Status</th>
                                        <th width="14%">Created</th>
                                        <th width="10%">Session</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($questions as $question)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>{{ $question['id'] ?? '-' }}</td>
                                            <td style="text-align: left;">
                                                {{ $question['question'] ?? 'N/A' }}
                                            </td>
                                            <td>{{ ucfirst($question['bot_type'] ?? 'support') }}</td>
                                            <td>{{ ucfirst($question['status'] ?? 'open') }}</td>
                                            <td>{{ $question['created_at'] ?? '-' }}</td>
                                            <td style="font-size: 12px;">{{ \Illuminate\Support\Str::limit($question['session_id'] ?? '-', 18) }}</td>
                                            <td>
                                                <a href="{{ route('chatBot.unansweredQuestions.create', ['id' => $question['id']]) }}"
                                                   class="btn btn-sm btn-primary">
                                                    Resolve
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="6">No data found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                {!! $questions->links() !!}
                            </div>
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
