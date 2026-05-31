@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
        </div>

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <!--<h4>All Customer</h4>-->
                </div>
                <div class="col-md-6">
                    <ul>
                        <!--<li class="active"><a href="{{ URL::to('/') }}/clients">Back</a></li>-->
                        <!--<li><a href="">Import</a></li>-->
                        <!--<li><a data-href="/tasks" onclick="exportTasks(event.target);">Export</a></li>-->
                    </ul>
                </div>
            </div>
            @php
                $supperStaff = NULL;
                $staff = \Illuminate\Support\Facades\Auth::user() ?? NULL;
                if(isset($staff->type) && ($staff->type == "superadmin" || $staff->type == "superstaff")){
                    $supperStaff = $staff;
                }
            @endphp

            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>{{ $user->name ?? '' }}</h4>
                            <img src="{{ URL::to('/') }}/assets/images/img/{{ $user->image }}" alt=""
                                 width="100px">
                        </div>
                        @php
                            $customer = DB::table('customers')
                                        ->where('uid', $user->id ?? '')
                                        ->first();
                            $store = \App\Models\Store::with("branches")
                                ->where('customer_id', $customer->id ?? '')
                                ->get();

                            $domainlist = DB::table('domains')
                                ->where('customer_id', $customer->id ?? '')
                                ->get();
                            $str = DB::table('stores')
                                ->where('id', $customer->active_store ?? '')
                                ->first();
                            $plan = DB::table('plans')
                                ->where('id', $str->plan_id ?? '')
                                ->first();
                            $product = DB::table('products')
                                ->where('customer_id', $customer->id ?? '')
                                ->get();
                        @endphp
                        <div class="card-body">
                            <div class="col-12 table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $user->name }}</td>
                                        <th>Phone</th>
                                        <td>{{ $user->phone }}</td>
                                        <th>Email</th>
                                        <td>{{ $user->email }}</td>
                                        <th>Address</th>
                                        <td>{{ $user->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Store</th>
                                        <td>{{ count($store) }}</td>
                                        <th>Plan Name</th>
                                        <td>{{ $plan->name ?? '' }}</td>
                                        <th>Plan Id</th>
                                        <td>{{ $plan->id ?? '' }}</td>
                                        <th>Plan Expire Date</th>
                                        <td>
                                            @if(isset($str->expiry_date))
                                                {{ \Carbon\Carbon::parse($str->expiry_date)->format("d-m-Y") }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Domain</th>
                                        <td>{{ count($domainlist) ?? '0' }}</td>
                                        <th>Total Product</th>
                                        <td>{{ count($product) ?? '' }}</td>
                                        <th>Customer Create Date</th>
                                        <td>
                                            {{ $user->created_at }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Website Link</th>
                                        <td>
                                            @if (isset($store) && count($store) > 0)
                                                @foreach ($store as $key => $str)
                                                    <div class="d-flex flex-column">
                                                        <p><a href="https://{{ $str->url }}"
                                                              target="_blank">{{ $str->url }}</a>

                                                            - {{ $str->purpose ?? 'Empty' }}

                                                            <span class="btn btn-primary mb-2 mt-2"
                                                                  style="margin-left: 7px"
                                                                  data-bs-toggle="modal"
                                                                  data-bs-target="#editStoreModal{{$key}}">Edit Store
                                                        </span>
                                                        </p>
                                                    </div>

                                                    <div class="modal fade" id="editStoreModal{{$key}}" tabindex="-1"
                                                         aria-labelledby="exampleModalLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="post"
                                                                  action="{{route('superadmin.update.store.name')}}"
                                                                  enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Client Access</h5>
                                                                        <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"><i
                                                                                class="fa fa-times"
                                                                                aria-hidden="true"
                                                                                style="color: #000"></i></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group py-3 text-start">
                                                                            <label for="name">Store
                                                                                Name</label>
                                                                            <input type="hidden" name="store_id"
                                                                                   value="{{ $str->id }}">
                                                                            <input type="text" class="form-control"
                                                                                   id="name"
                                                                                   name="name"
                                                                                   value="{{ $str->name ?? "" }}"
                                                                                   placeholder="Enter Store Name">
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">
                                                                            Close
                                                                        </button>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            Update
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>
                                        <th>Store ( Branch )</th>
                                        <td>
                                            @if (isset($store) && $store->isNotEmpty())
                                                @foreach ($store as $str)
                                                    @php
                                                        $branchCount = $str->branches->count() ?? '0';
                                                        $planTypes = [];

                                                        if ($str->plan_id != null) $planTypes[] = 'WEB';
                                                        if ($str->pos_plan_id != null) $planTypes[] = 'POS';
                                                        if ($str->digital_plan_id != null) $planTypes[] = 'SMM';

                                                        // Handle special cases for single plan types
                                                        $planString = match(count($planTypes)) {
                                                            1 => match($planTypes[0]) {
                                                                'WEB' => '<strong>WebSite</strong>',
                                                                'SMM' => '<strong>Digital</strong>',
                                                                default => '<strong>' . $planTypes[0] . '</strong>'
                                                            },
                                                            default => empty($planTypes) ? '' : '<strong>' . implode('+', $planTypes) . '</strong>'
                                                        };
                                                    @endphp

                                                    <div class="d-flex flex-row">
                                                        <p>
                                                            {{ $str->name ?? '' }} ({{ $branchCount }})
                                                            {!! $planString !!}
                                                        </p>
                                                        <div class="form-check form-switch d-inline-block ms-2"
                                                             style="margin-left: 20px !important; margin-top: 2px; }">
                                                            <input class="form-check-input store-status-toggle"
                                                                   type="checkbox"
                                                                   role="switch"
                                                                   id="store-toggle-{{ $str->id }}"
                                                                   data-store-id="{{ $str->id }}"
                                                                {{ $str->store_status == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                   for="store-toggle-{{ $str->id }}"></label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Comment</th>
                                        <td id="okCommnet">
                                        <textarea name="comment" class="form-control" id="comment{{ $user->id }}"
                                                  onchange="okComment({{ $user->id }})" cols="20" rows=""
                                                  placeholder="Enter Your Comment">{{ $user->comment }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            @if(isset($supperStaff) && is_null($supperStaff->store_id))
                                                <button class="btn btn-primary mb-0 mt-2" data-bs-toggle="modal"
                                                        data-bs-target="#createModal">Access client
                                                </button>
                                            @else

                                                <form method="post"
                                                      action="{{route('superadmin.staff.client.remove.access')}}"
                                                      enctype="multipart/form-data">
                                                    @csrf
                                                    <button class="btn btn-primary mb-0 mt-2">Remove client Access
                                                    </button>
                                                </form>
                                            @endif
                                        </th>
                                    </tr>
                                </table>
                            </div>
                            @if(isset($supperStaff) && is_null($supperStaff->store_id))
                                <div class="modal fade" id="createModal" tabindex="-1"
                                     aria-labelledby="exampleModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="post" action="{{route('superadmin.staff.client.access')}}"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Client Access</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"><i class="fa fa-times"
                                                                                  aria-hidden="true"
                                                                                  style="color: #000"></i></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group py-3">
                                                        <label for="addons_name">Access Key</label>
                                                        <input type="text" class="form-control" id="access_key"
                                                               name="access_key" value="{{ old('access_key') }}"
                                                               placeholder="Enter access">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        function okComment(id) {
            var text = $('#comment' + id).val();
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

        document.querySelectorAll('.store-status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const storeId = this.dataset.storeId;
                const isActive = this.checked;
                const action = isActive ? 'activate' : 'deactivate';

                swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to ${action} this store?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: `Yes, ${action} it!`,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        axios.patch(`/stores/${storeId}/toggle-status`, {
                            is_active: isActive
                        })
                            .then(response => {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response?.data?.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            })
                            .catch(error => {
                                this.checked = !isActive; // Revert on error
                                Swal.fire({
                                    title: 'Error!',
                                    text: error.response?.data?.message || 'Failed to update status',
                                    icon: 'error'
                                });
                            });
                    } else {
                        this.checked = !isActive; // Revert if cancelled
                    }
                });
            });
        });


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
    </script>
@endpush
