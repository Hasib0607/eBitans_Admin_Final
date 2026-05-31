@extends('admin.layouts.main')
@section('content')
    <!-- The Modal -->
    <div class="modal fade" id="assignAgent">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('chatBot.conversationAssignAgent') }}" method="post">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Assign Agent</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label class="col col-form-label">Agent</label>
                            <input name="conversation_id" id="conversation_id" type="hidden">
                            <div class="col">
                                <select name="agent_id" id="agent_id" class="form-control">
                                    <option value="">Select Agent</option>
                                    <option value="bot">Assign Bot</option>
                                    @if(isset($agents) && count($agents))
                                        @foreach($agents as $item)
                                            @if(Auth::user()->type == 'superadmin')
                                                <option value="{{$item->id}}">{{ $item->name }}</option>
                                            @endif
                                            @if(Auth::user()->type == 'superstaff' && $item->type == "superstaff")
                                                <option value="{{$item->id}}">{{ $item->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="Submit" id="btnAssignClient" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <main class="main-content position-relative border-radius-lg" style="min-height: 100vh">

        {{-- Top nav bar --}}
        @include('chatBot.top_nav_menu')

        <div class="container-fluid mt-4" id="toplist">

            <div class="row">
                <div class="col">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            All Agent Conversation
                        @else
                            All Agent Conversation
                        @endif
                    </h4>
                </div>
            </div>

            <div class="row mt-3 productlist">
                <div class="col-12">
                    <div class="card">
                        {{--Table top action and search filter option--}}
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2">
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
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                            @endif
                            <div class="table-responsive" id="desktoptable">
                                <table class="table table-striped" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th width="16%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Name
                                            @else
                                                Name
                                            @endif
                                        </th>
                                        <th width="15%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Email
                                            @else
                                                Email
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Phone
                                            @else
                                                Phone
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Type
                                            @else
                                                Type
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Date
                                            @else
                                                Date
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Agent name
                                            @else
                                                Agent name
                                            @endif
                                        </th>
                                        <th width="15%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                Agent email
                                            @else
                                                Agent email
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                এডিট/ডিলিট
                                            @else
                                                Action
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($conversations as $conversation)
                                        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                            <td>
                                                {{ ($conversations->currentPage() - 1) * $conversations->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $conversation->visitor->visitor_name ?? "" }}
                                            </td>
                                            <td>
                                                {{ $conversation->visitor->visitor_email ?? "" }}
                                            </td>
                                            <td>
                                                {{ $conversation->visitor->visitor_phone ?? "" }}
                                            </td>
                                            <td>
                                                {{ $conversation->visitor->user->type ?? "" }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($conversation->updated_at)->format("d-m-Y h:m:s A") ?? "" }}
                                            </td>
                                            <td>
                                                {{ $conversation->agent->name ?? "" }}
                                            </td>
                                            <td>
                                                {{ $conversation->agent->email ?? "" }}
                                            </td>
                                            <td>
                                                <button class="btn btn-primary"
                                                        onclick="assignAgent({{ $conversation->id }}, {{ $conversation->agent->id }})">
                                                    Assign Agent
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="6">No data found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                {!! $conversations->links() !!}
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
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function () {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }


        const assignAgent = (conversationID, agentID) => {
            $("#conversation_id").val(conversationID || "");
            $("#agent_id").val(agentID || "");

            openModal("assignAgent");
        }


        function openModal(id) {
            const modalElement = document.getElementById(id); // Find the modal by ID
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement); // Initialize a new modal instance
                modal.show();
            }
        }

        // Close the modal
        function closeModal(id) {
            const modalElement = document.getElementById(id); // Find the modal by ID
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement); // Get the existing modal instance
                if (modal) {
                    modal.hide();
                }
            }
        }
    </script>
@endpush
