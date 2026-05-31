@extends('admin.layouts.main')

@section('content')
    <main class="main-content position-relative border-radius-lg" style="min-height: 100vh">
        @include('chatBot.top_nav_menu')

        <div class="container-fluid mt-4">
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h4 class="mb-1">Support Chat Analytics</h4>
                    <p class="text-sm text-muted mb-0">Live support performance from website chat conversations and the Python bot learning queue.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('chatBot.unansweredQuestions.list') }}" class="btn btn-outline-primary">
                        Open Learning Queue
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-sm text-muted mb-1">Total Conversations</p>
                            <h3 class="mb-0">{{ $summary['total_conversations'] }}</h3>
                            <small class="text-muted">Today: {{ $summary['today_conversations'] }} | This month: {{ $summary['month_conversations'] }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-sm text-muted mb-1">Bot Waiting</p>
                            <h3 class="mb-0">{{ $summary['open_bot_conversations'] }}</h3>
                            <small class="text-muted">Open support learning items: {{ $summary['open_learning_count'] }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-sm text-muted mb-1">Agent Conversations</p>
                            <h3 class="mb-0">{{ $summary['assigned_agent_conversations'] }}</h3>
                            <small class="text-muted">Registered visitors: {{ $summary['registered_visitors'] }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-sm text-muted mb-1">Today Messages</p>
                            <h3 class="mb-0">{{ $summary['today_messages'] }}</h3>
                            <small class="text-muted">Guests: {{ $summary['guest_visitors'] }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">Message Breakdown</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <p class="text-sm text-muted mb-1">Visitor</p>
                                    <h4>{{ $summary['visitor_messages'] }}</h4>
                                </div>
                                <div class="col-4">
                                    <p class="text-sm text-muted mb-1">Agent</p>
                                    <h4>{{ $summary['agent_messages'] }}</h4>
                                </div>
                                <div class="col-4">
                                    <p class="text-sm text-muted mb-1">Bot</p>
                                    <h4>{{ $summary['bot_messages'] }}</h4>
                                </div>
                            </div>
                            <hr>
                            <p class="mb-0 text-sm text-muted">Total support messages: {{ $summary['total_messages'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">Top Support Agents</h6>
                        </div>
                        <div class="card-body">
                            @forelse($topAgents as $agent)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>{{ $agent->agent->name ?? 'Unknown Agent' }}</div>
                                    <strong>{{ $agent->total_conversations }}</strong>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No assigned agent conversations yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">Last 7 Days Message Trend</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Visitor</th>
                                        <th>Agent</th>
                                        <th>Bot</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($dailyMessageSeries as $day)
                                        <tr>
                                            <td>{{ $day->date }}</td>
                                            <td>{{ $day->visitor_count }}</td>
                                            <td>{{ $day->agent_count }}</td>
                                            <td>{{ $day->bot_count }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No recent message data found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">Top Monthly Support Usage</h6>
                        </div>
                        <div class="card-body">
                            @forelse($monthlyUsage as $usage)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>{{ $usage->store->storename ?? ('Store #' . $usage->store_id) }}</div>
                                    <strong>{{ $usage->total_support }}</strong>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No monthly support usage data found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
