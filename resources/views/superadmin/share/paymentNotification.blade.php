<div class="col-md-6">
    <div class="card h-100 mt-4 mt-md-0">
        <div class="card-header pb-0 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <h6>Payment Notification SMS</h6>
                <div>
                    @if (Auth::user()->type == 'superadmin')
                        <a href="{{ route("superadmin.index") }}" class="btn btn-success">
                            All Client
                        </a>
                        <a href="{{ route("superadmin.index", ['expire' => true]) }}" class="btn btn-info">
                            Expire Client
                        </a>
                        <a href="{{ route("superadmin.index", ['days' => 7]) }}" class="btn btn-secondary">
                            7 Day Left
                        </a>
                    @else
                        <a href="{{ route("staff.dashboard") }}" class="btn btn-success">
                            All Client
                        </a>
                        <a href="{{ route("staff.dashboard", ['expire' => true]) }}" class="btn btn-info">
                            Expire Client
                        </a>
                        <a href="{{ route("staff.dashboard", ['days' => 7]) }}" class="btn btn-secondary">
                            7 Day Left
                        </a>
                    @endif
                    <p id="btnSubmit" class="btn btn-primary filterbuttonss">
                        Send SMS
                    </p>
                </div>
            </div>
        </div>
        <form id="submitform" method="post"
              action="{{ route('superAdmin.sendMultiplePaySms') }}">
            @csrf
            <input type="hidden" name="storeIds" id="selectids">

        </form>
        <div class="card-body px-3 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0">
                    <thead>
                    <tr>
                        <th><input type="checkbox" name="ids"
                                   id="checkedAll"></th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Customer
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Store
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Comment
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($exstore) > 0)
                        @foreach ($exstore as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selectedid" value="{{ $item->id }}" id="id"
                                           class="checkSingle">
                                </td>
                                <td>
                                    <p class="text-sm mb-0" style="color:#344767">
                                        <small>{{ $item->getUser->name ?? 'Not Available' }}</small> <br>
                                        <strong>
                                            @if(isset($item->getUser->phone))
                                                <a href="https://wa.me/88{{ $item->getUser->phone }}" target="_blank"
                                                   style="text-decoration: none;">
                                                    {{ $item->getUser->phone ?? 'Not Available' }}
                                                </a>
                                            @endif
                                        </strong>

                                    </p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-normal mb-0">
                                        <strong>
                                            <a href="https://{{ $item->url }}" target="_blank">
                                                {{ $item->name }}
                                            </a>
                                        </strong> <br>
                                        @php
                                            $now = Carbon\Carbon::now();
                                            $end_date = $item->expiry_date;
                                            $cDate = Carbon\Carbon::parse($end_date);
                                            $count = $now->diffInDays($cDate);
                                            $count = -1;

                                            if ($cDate->gte($now)) {
                                                $count = $now->diffInDays($cDate);
                                            }
                                        @endphp

                                        @if($count >= 0)
                                            @if(isset($item->expiry_date))
                                                <small>{{ $item->expiry_date ?? 'Not Available' }} (<span
                                                        class="text-danger">{{ $count >= 0 ? $count + 1 . " Day left" : "Expired"}}</span>
                                                    )</small>
                                            @else
                                                <small>{{ $item->expiry_date ?? 'Not Available' }}</small>
                                            @endif
                                        @else
                                            <small class="text-danger">{{ $item->expiry_date ?? 'Not Available' }}
                                                ( {{ "Expired"}} )</small>
                                        @endif

                                        @if(isset($item->upcoming_plan_purchase_date))
                                            <br>
                                            <small>Next
                                                Renew: {{ Carbon\Carbon::parse($item->upcoming_plan_purchase_date)->format("Y-m-d") ?? '' }}</small>
                                            <br><small>(Already Renew)</small>
                                        @endif
                                    </p>
                                </td>
                                <td id="okCommnet">
                                    <textarea name="comment" class="form-control"
                                              id="cmt{{ $item->id }}"
                                              onchange="okComment({{ $item }})" cols="20" rows=""
                                              placeholder="Enter Your Comment">{{ $item->user->comment ?? "" }}</textarea>
                                    @if(isset($item->user->comment_date))
                                        <small>Update: {{ \Carbon\Carbon::parse($item->user->comment_date)->format('Y-m-d g:i A') ?? "" }}</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('superAdmin.changeCallStatus', $item->id) }}"
                                       class="btn btn-success btn-sm"
                                       style="background-color: #f1593a; margin-right: 5px">Make Call
                                        ({{ $item->call_status }})</a>

                                    @if($count >= 0)
                                        @if($item->pay_mail_status == 0)
                                            <a href="{{ route('superAdmin.payNotiByCustomer', $item->id) }}"
                                               class="btn btn-success btn-sm">{{ 'Not Send' }}</a>
                                        @else
                                            <a href="javascript:void(0)"
                                               class="btn btn-success btn-sm">{{ 'Delivary SMS' }} {{ $item->sms_status > 0 ? "(".$item->sms_status.")" : '' }}</a>
                                        @endif
                                    @else
                                        <a href="javascript:void(0)" onclick="smsModal({{ json_encode($item) }})"
                                           class="btn btn-success btn-sm">Custom SMS
                                            {{ $item->sms_status > 0 ? "(".$item->sms_status.")" : '' }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div>
                    @if(isset($filterDays))
                        {{ $exstore->appends(['days' => $filterDays, 'expire' => $expire])->links() }}
                    @else
                        {{ $exstore->links() }}
                    @endif
                </div>
            </div>
        </div>

        {{-- SMS Modal--}}
        <div class="modal fade" id="smsModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('superAdmin.sendCustomPaySms') }}" method="post">
                        @csrf
                        <input type="hidden" id="store_id" name="store_id" value="">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Custom SMS</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <label class="form-label">Message</label>
                            <div class="input-group input-group-outline mb-3">
                                <textarea name="message" id="message" class="form-control" cols="30"
                                          rows="10"></textarea>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="Submit" class="btn btn-info">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script !src="">

        const smsModal = (data) => {
            const user = data?.get_user?.name || "eBitans User";
            let message = `Dear ${user}, your store ${data?.name}. Please pay your website bill for uninterrupted service.\n\nFor any enquiry please call: {{ env('SUPPORT_NUMBER') }}`;

            $("#store_id").val(data?.id);
            $("#message").val(message);
            openModal("smsModal")
        }

        // Open the modal
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
    <script>
        function okComment(data) {
            var id = data?.get_user?.id;
            var text = $('#cmt' + data?.id).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.client.commnet') }}",
                data: {
                    id: id,
                    comment: text
                },
                success: function (data) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.success("Comment save");
                }
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            let valuesArray = [];

            // Check all checkbox action
            $("#checkedAll").change(function () {
                if (this.checked) {
                    // If "checkedAll" is checked, check all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = true;
                        let value = $(this).val();
                        if (!valuesArray.includes(value)) {
                            valuesArray.push(value);
                        }
                    });
                } else {
                    // If "checkedAll" is unchecked, uncheck all ".checkSingle" checkboxes
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    valuesArray = [];
                }

                let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                $("#selectids").val(newAaluesArray);
            });

            // Single check action
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    let value = $(this).val();

                    let isAllChecked = $(".checkSingle").length === $(".checkSingle:checked").length;
                    $("#checkedAll").prop("checked", isAllChecked);

                    if (!valuesArray.includes(value)) {
                        valuesArray.push(value);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                } else {
                    $("#checkedAll").prop("checked", false);

                    let value = $(this).val();

                    let index = valuesArray.indexOf(value);

                    if (index === -1) {
                        valuesArray.push(value);
                    } else {
                        valuesArray.splice(index, 1);
                    }

                    let newAaluesArray = valuesArray.join(","); // Convert array to comma-separated string

                    $("#selectids").val(newAaluesArray);
                }
            });

            $("#btnSubmit").click(function () {
                const ids = $("#selectids").val();

                if (ids != "") {
                    $("#submitform").submit();
                } else {
                    swal.fire({
                        title: "Warning",
                        text: "Please select store first!",
                        type: 'warning',
                    })
                }
            });
        });
    </script>

@endpush
