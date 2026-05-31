<div class="table-responsive" id="desktoptable">
    <table class="table table-striped text-center" width="100%" id="taskfilterresult">
        <thead>
        <tr>
            <th width="12.5%">Name</th>
            <th width="12.5%">Phone</th>
            <th width="12.5%">Email</th>
            <th width="12.5%">Amount</th>
            <th width="12.5%">Date</th>
            <th width="12.5%">Comment</th>
            <th width="12.5%">Status</th>
            <th width="12.5%">Action</th>
        </tr>
        </thead>
        <tbody>
        @if(count($withdraws))
            @foreach ($withdraws as $key=>$withdraw)
                <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                    <td>{{$withdraw->name}}</td>
                    <td>{{$withdraw->phone ?? ''}}</td>
                    <td>{{$withdraw->email?? ''}}</td>
                    <td>{{$withdraw->amount}} {{$withdraw->currency_symbol ?? ""}}</td>
                    <td>{{ date('d-m-Y', strtotime($withdraw->created_at)) }}</td>
                    <td>{{$withdraw->comment ?? ""}}</td>
                    <td style="text-align: center;display: flex;align-items: center;justify-content: center;padding: 15px 0;">
                        @switch($withdraw->status)
                            @case(1)
                                <button class="btn btn-success rounded-1 btn-sm">
                                    Approved
                                </button>
                                @break
                            @case(2)
                                <button class="btn btn-danger rounded-1 btn-sm">
                                    Rejected
                                </button>
                                @break
                            @default
                                <button class="btn btn-warning rounded-1 btn-sm">
                                    Pending
                                </button>
                                @break
                        @endswitch
                    </td>
                    <td>
                        @if($withdraw->status == 0)
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#affiliateAcceptModal{{ $key }}" style="margin-top: 7px">Approved
                            </button>
                            <a href="{{ route('admin.reject.product.affiliate.withdraw', ['id' => $withdraw->id]) }}"
                               class="btn btn-danger btn-sm operation" style="margin-bottom: -7px">
                                Rejected
                            </a>
                        @else
                            {{--                            <button class="btn btn-info rounded-1 btn-sm">--}}
                            {{--                                completed--}}
                            {{--                            </button>--}}
                        @endif
                    </td>
                </tr>


                <!-- Modal -->
                <div class="modal fade" id="affiliateAcceptModal{{ $key }}" data-bs-backdrop="static"
                     data-bs-keyboard="false" tabindex="-1" aria-labelledby="#affiliateAcceptModal{{ $key }}Label"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('admin.approved.product.affiliate.withdraw') }}"
                                  method="post" class="modal-content">
                                @csrf
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Affiliate Withdraw
                                        Approval</h1>
                                </div>
                                <div class="modal-body container">
                                    <div class="row">
                                        <div class="col-md-3 mt-2">
                                            Name :
                                        </div>
                                        <div class="col-md-9 mt-2 text-gray-500 ">
                                            {{$withdraw->name ?? 'name not found'}}
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            Phone :
                                        </div>
                                        <div class="col-md-9 mt-2 text-gray-500 fst-italic">
                                            {{$withdraw->phone ?? 'Phone not found'}}
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            Email :
                                        </div>
                                        <div class="col-md-9 mt-2 text-gray-500 fst-italic">
                                            {{$withdraw->email ?? 'email not found'}}
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            Amount :
                                        </div>
                                        <div class="col-md-9 mt-2 text-gray-500 ">
                                            {{$withdraw->amount ?? '0.00'}} {{$withdraw->currency_symbol ?? ""}}
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            Comment :
                                        </div>
                                        <div class="col-md-9 mt-2 text-gray-500 ">
                                            <input type="hidden" name="id" value="{{ $withdraw->id }}">
                                            <textarea class="form-control" name="comment"
                                                      placeholder="Leave a comment here"
                                                      id="comment{{$withdraw->id}}" style="height: 100px"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel
                                    </button>
                                    <button
                                        type="submit" class="btn btn-primary operation" data-id="{{$withdraw->id}}"
                                        data-status="1">Approval
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <tr>
                <td colspan="8">Record not found</td>
            </tr>
        @endif
        </tbody>
    </table>
    {!! $withdraws->links() !!}
</div>
