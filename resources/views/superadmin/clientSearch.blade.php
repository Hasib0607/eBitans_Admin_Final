<table class="table" width="100%">
    <thead>
    <tr>
        <th width="1%">#</th>
        @if (Auth::user()->type == 'superadmin')
            <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
        @endif

        <th width="5%">Name</th>
        <th width="5%">Follow-Up</th>
        <th width="5%">Store Name</th>
        <th width="5%">Id</th>
        <th width="10%">Comment</th>
        @if (Auth::user()->type == 'superstaff' || Auth::user()->type == 'superadmin')
            <th width="10%">Seller</th>
        @endif
        @if (Auth::user()->type == 'superadmin')
            <th width="10%">Assign Seller</th>
        @endif
        <th width="15%">Created At</th>
        <th width="11%">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($clients as $key => $client)
        <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
            <td>{{ $key + 1 }}</td>
            @if (Auth::user()->type == 'superadmin')
                <td>
                    <input type="checkbox" name="selectedid" value="{{ $client->id }}" id="id" class="checkSingle">
                </td>
            @endif

            <td>
                {{ $client->name ?? '' }}
            </td>


            <td>
                @php
                    $comments = $client->follow_up_comments ?? collect();
                    $activeStore = $client->active_store ?? $client->getStore;
                    $activeCustomer = $client->active_customer ?? $client->customer;
                @endphp

                @if ($comments)
                    <!-- Modal -->
                    <div class="modal fade"
                         id="AnalyticesCommentModal{{ $key }}" tabindex="-1"
                         aria-labelledby="exampleModalLabelClient" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="text-align: left;">
                                <div class="modal-header"
                                     style="background-color: black; color:wheat;padding: 6px 25px 0px 25px;">
                                    <h5 class="modal-title"
                                        id="exampleModalLabelClient">
                                        <a href="http://{{ $activeStore->url ?? 'Unauthorized' }}"
                                           target="_blank">
                                            {{ $activeStore->name ?? 'Unauthorized' }}
                                        </a>
                                        <span style="font-size: 14px;">
                                                ({{ $client->name ?? 'Name not found' }}
                                                -{{ $client->id ?? '' }})
                                            </span>

                                        <p>{{ $client->phone ?? 'Phone not found' }}
                                        </p>
                                    </h5>

                                    <button type="button" class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>


                                <div class="modal-body">
                                    @if (!empty($comments))
                                        <form id="clientComment{{ $client->id }}" onsubmit="event.preventDefault();"
                                              class="p-3"
                                              style="border: 1px dashed crimson;"
                                              action="{{ route('superadmin.clients.activities.comments') }}"
                                              method="POST">
                                            @csrf

                                            <div class="col-12">
                                                <input type="hidden" name="user_id"
                                                       value="{{ $client->id ?? 'empty' }}">
                                                <input type="hidden" name="store_id"
                                                       value="{{ $activeStore->id ?? null }}">
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label
                                                        for="clientStatus{{ $client->id }}">
                                                        Client Status </label>
                                                    <select name="clientStatus"
                                                            id="clientStatus{{ $client->id }}"
                                                            required class="form-control">
                                                        <option selected disabled>Select
                                                            Client Status
                                                        </option>
                                                        <option value="Positive">
                                                            Positive
                                                        </option>
                                                        <option value="thinking">
                                                            Thinking
                                                        </option>
                                                        <option value="No Response">No
                                                            Response
                                                        </option>
                                                        <option value="Interested">
                                                            Interested
                                                        </option>
                                                        <option value="Maybe">Maybe
                                                        </option>
                                                        <option value="No interested">
                                                            Not Interested
                                                        </option>
                                                        <option value="Freelancer">
                                                            Freelancer
                                                        </option>
                                                        <option value="tut">Tut tut
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 mt-4">
                                                    <label for="followUpData">Follow-Up
                                                        Data</label>
                                                    <input type="date"
                                                           class="form-control"
                                                           name="followUpData"
                                                           id="followUpData" required>
                                                </div>
                                                <div class="col-md-6 mt-4">
                                                    <label for="followUpTime">Follow-Up
                                                        Time</label>
                                                    <input type="time"
                                                           class="form-control"
                                                           name="followUpTime"
                                                           id="followUpTime">
                                                </div>

                                                <div class="col-md-12 mt-4">
                                                    <label for="comment"> Comments
                                                    </label>
                                                    <textarea name="comment" class="form-control"
                                                              id="comment{{ $client->id }}" required cols="30"
                                                              rows="5"></textarea>
                                                </div>

                                                <div class="col-12">
                                                    <input type="hidden"
                                                           id="contVal{{ $client->id }}"
                                                           name=""
                                                           value="{{ $comments->count() }}">
                                                    <button type="button"
                                                            style="border: 1px dashed;"
                                                            class="btn btn-default mt-3">Total
                                                        Follow-Up:
                                                        <span
                                                            id="conut{{ $client->id }}">{{ $comments->count() }}</span>
                                                    </button>

                                                    <button type="submit"
                                                            id="submitBtn{{ $client->id }}"
                                                            onclick="SubMitFrom({{ $client->id }})"
                                                            style="float: right;"
                                                            class="btn btn-info mt-3">Comment
                                                    </button>

                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <h2>There is no Comments here yet</h2>
                                    @endif

                                    <div id="resCmt{{ $client->id }}"
                                         class="row px-3">
                                        @foreach ($comments as $item)
                                            <div class="col-md-12 mt-3"
                                                 style="border: 1px dashed lightseagreen;padding: 10px">
                                                <h4 class="">
                                                    {{ $item->short_comment }}
                                                    <span style="float: right;font-size: 16px;font-weight: 500;">
                                                            {{ date('d-m-Y, h:i:s A', strtotime($item->created_at ?? '10:00:00')) }}
                                                            <br>
                                                            <small
                                                                style="float: right;"><strong>-- {{ $item->comment_by?? 'Kabir' }}</strong></small>
                                                        </span>
                                                </h4>
                                                <p class="m-0">
                                                    <strong>Next Follow Up:</strong>
                                                    <br>
                                                    {{ date('d-m-Y', strtotime($item->follow_up_date ?? '2000-01-01')) }}
                                                    ,
                                                    {{ date('h:i:s A', strtotime($item->follow_up_time ?? '10:00:00')) }}
                                                </p>
                                                <p class="m-0">
                                                    <strong>Comment:</strong> <br>
                                                    {{ $item->comment }}
                                                </p>
                                            </div>
                                        @endforeach
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

                <a style="background-color: {{ $comments->isNotEmpty()?'':'red' }};pointer-events: {{ ($activeStore->name??'') !=''?'':'none' }}"
                   href="javascript:void(0)" data-bs-toggle="modal"
                   class="btn btn-info btn-sm"
                   data-bs-target="#AnalyticesCommentModal{{ $key }}"
                   id="viewaddons" data-id="{{ $client->id }}">
                    Follow-Up
                </a>
            </td>


            @php
                $customer = $activeCustomer;
                $str = $activeStore;
            @endphp

            <td>
                <a style="display: block;font-size: 14px;color:{{ $str->name?? '#ff5733;'}}"
                   href="http://{{ $str->url ?? '#' }}" target="_blank"
                   rel="noopener noreferrer">
                    {{  $client->getStore->name ?? 'Store is not built yet' }}
                    <strong
                        style="font-size: 9px; color: {{ $str->name ?? '' != '' ? 'green': '#ff5733;' }}">{{ $str->name ?? '' != '' ? 'Active': 'Inactive' }}</strong>
                </a>
                @if(isset($client->phone) && !empty($client->phone))
                    <p style="font-weight: 900;margin-bottom: 0">
                        <a href="https://wa.me/88{{ $client->phone }}" target="_blank"
                           style="text-decoration: none;">
                            {{ $client->phone ?? '' }}
                        </a>
                    </p>
                @endif
                @if(isset($client->email) && !empty($client->email))
                    <p style="font-weight: 900;margin-bottom: 0">
                        {{ $client->email ?? '' }}
                    </p>
                @endif
                @if(isset($client->type) && !empty($client->type))
                    <small>
                        Type : {{ $client->type ?? '' }}
                    </small>
                @endif
            </td>
            <td>{{ $client->id }}</td>

                <?php
                $cus = DB::table('customers')
                    ->where('uid', $client->id)
                    ->first();
                ?>
            <td id="okCommnet">
                    <textarea name="comment" class="form-control" id="comment{{ $client->id }}"
                              onchange="okComment({{ $client->id }})" cols="20" rows=""
                              placeholder="Enter Your Comment">{{ $client->comment }}</textarea>
            </td>
            @if (Auth::user()->type == 'superstaff')
                @php
                    $staffID = $client->customerInfo->staff->uid ?? null;
                @endphp
                <td>
                    @if(is_null($client->customerInfo) || (isset($staffID) && Auth::user()->id == $staffID))
                        <input type="checkbox" id="makeMyCustomer"
                               @if(Auth::user()->id == $staffID) checked
                               @endif
                               onchange="makeMyCustomer({{ $client }})"
                               style="cursor: pointer">
                    @else
                        <input type="checkbox" checked disabled style="cursor: pointer">
                    @endif
                </td>
            @endif
            @if (Auth::user()->type == 'superadmin')
                <td>
                    <input type="checkbox" @if(isset($client->customerInfo)) checked @endif disabled
                           style="cursor: pointer">
                </td>
                <td>
                    <button class="btn btn-primary" onclick="assignSeller({{$client}})">Assign Customer</button>
                </td>
            @endif

            <td>{{ date('j M, Y', strtotime($cus->created_at ?? '2000-01-01')) }}</td>
            <td>
                <a href="{{ URL::to('/') }}/client/view/{{ $client->id }}"
                   class="btn btn-info" target="_blank">View</a>

                @if ((Auth::user()->type == 'superstaff' || Auth::user()->type == 'superadmin') && ($client->stores_count ?? 0) == 0)
                    <a href="{{ route('superstaff.access.admin', ['id' => $client->id]) }}"
                       class="btn btn-danger">Create Store</a>
                @endif
                @if (Auth::user()->type == 'superstaff')
                    <a href="{{ route('superstaff.access.admin.store', ['id' => $client->id]) }}"
                       class="btn btn-danger">Make Payment</a>
                @endif

                @if (Auth::user()->type == 'superadmin')
                    &nbsp;&nbsp;
                    <a href="#" onclick="deleteStore({{ $client->id }})"
                       class="btn btn-danger">Delete</a>
                @endif


            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{-- <div style="text-align: center;">
    {!! $clients->links() !!}
</div> --}}
