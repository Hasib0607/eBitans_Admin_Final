@extends('affiliate.layouts.main')

@section('content')
    <div class="m-4 p-4">
        @if(isset($examStatus) && $examStatus->user_status == "Approved")
            <h3>Congratulations! Welcome to eBitans affiliate marketing.</h3>
            <div class="row my-5">
                @if(isset($paymentStatus) && $paymentStatus->status == "Completed")
                    <div class="col-lg-2">
                        <div class="m-auto rounded shadow" style="width:90%; height: 130px; background-color: white">
                            <div class="row">
                                <div class="col-lg-2">

                                </div>
                                <div class="col-lg-8 pt-4">
                                    <h6>Total Customer</h6>
                                    <h5>{{ count($refers)}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="m-auto rounded shadow" style="width:90%; height: 130px; background-color: white">
                            <div class="row">
                                <div class="col-lg-2">

                                </div>
                                <div class="col-lg-8 pt-4">
                                    <h6>Monthly Customer</h6>
                                    <h5>{{count($refers_last_month)}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="m-auto rounded shadow" style="width:90%; height: 130px; background-color: white">
                            <div class="row">
                                <div class="col-lg-2">

                                </div>
                                <div class="col-lg-8 pt-4">
                                    <h6>Monthly Earning</h6>
                                    <h5>{{$montlyEarning}} TK</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="m-autorounded shadow " style="width:90%; height: 130px; background-color: white">
                            <div class="row">
                                <div class="col-lg-2">

                                </div>
                                <div class="col-lg-8 pt-4">
                                    <h6>Total Earning</h6>
                                    <h5>{{$totalEarning}} TK</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 ">
                        <div class="m-left bg-danger rounded shadow"
                             style="width:70%; height: 130px; background-color: white">
                            <p class="text-white text-center pt-1 pb-0 mb-0">Your Balance</p>

                            @if(isset($WithrawRequest))
                                <h3 class="text-white text-center">{{$WithrawRequest->balance ?? 0}} TK</h3>
                            @else
                                <h3 class="text-white text-center">{{$totalEarning ?? 0}} TK</h3>
                            @endif

                            @if(isset($WithrawRequest) && $WithrawRequest->withdraw_request_amount > 0 && $WithrawRequest->withdraw_request_status == 0)
                                <div class="col-12 text-center">
                                    <button class="btn btn-warning" type="button">Processing
                                        ({{$WithrawRequest->withdraw_request_amount}} TK)
                                    </button>
                                </div>
                            @elseif(isset($WithrawRequest) && $WithrawRequest->balance >= 500)
                                <form action="{{ route('affiliate.withdraw.request') }}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="text-center row m-0">
                                        <div class="col-6 text-center">
                                            <input type="number" name="withdraw_request_amount"
                                                   class="form-control bg-light"
                                                   placeholder="Type amount" required>
                                        </div>
                                        <div class="col-6 text-center">
                                            <button class="btn btn-light" type="submit">Withdraw Now</button>
                                        </div>
                                    </div>
                                </form>
                            @endif


                        </div>
                        <small class="text-warning fw-bolder ps-1 py-0 mt-0">Minimun 500tk balance to
                            Withdraw!</small>
                    </div>
                @else
                    <div class="col-lg-4 ">
                        <div class="m-left bg-danger rounded shadow"
                             style="width:70%; height: 130px; background-color: white">
                            <p class="text-white text-center pt-1 pb-0 mb-0">Your Balance</p>

                            @if(isset($WithrawRequest))
                                <h3 class="text-white text-center">{{$WithrawRequest->balance ?? 0}} TK</h3>
                            @else
                                <h3 class="text-white text-center">{{$totalEarning ?? 0}} TK</h3>
                            @endif
                        </div>
                        <small class="text-warning fw-bolder ps-1 py-0 mt-0">Minimun 500tk balance to
                            Withdraw!</small>
                    </div>
                @endif
            </div>
        @elseif(isset($examStatus) && $examStatus->user_status == "Hold")
            <h3>Welcome to eBitans affiliate marketing.</h3>
            <p>Your result is <span class="text-warning">Hold</span>. For any query please contact to the support!.</p>
        @elseif(isset($examStatus) && $examStatus->user_status == "Rejected")
            <h3>Welcome to eBitans affiliate marketing.</h3>
            <p>You are <span class="text-danger">Rejected</span> as Affiliate marketer for eBitans. For any query please
                contact to the support!.</p>
        @else
            <h3>Welcome to eBitans affiliate marketing.</h3>
            <p>You are not <span class="text-info">Approved</span>. Please contact to the support!.</p>
        @endif
    </div>

    @if(isset($examStatus) && $examStatus->user_status == "Approved")
        @if(isset($paymentStatus) && $paymentStatus->status == "Completed")
            <div class="row m-4 p-4">
                <div class="col-lg-8">
                    <h3>Affiliate Link:</h3>
                    @if(auth()->check())
                        <div class="d-flex">
                            <input id="HTMLBox" style="width:500px" class="form-control" type="text"
                                   value="{{ (request()->secure() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] }}/register?referral={{auth()->user()->referral}}"
                                   disabled/>
                            <button class="btn btn-light btn-sm fw-bolder" id="HTMLButton">Copy</button>
                        </div>
                    @endif
                    <small class="p-1 fw-bolder">Your Commision Rate: {{auth()->user()->referral_commission}}%</small>
                </div>
            </div>
        @endif
    @endif
@endsection

@push('scripts')
    <script>
        @if (Session::has('payment') && session('payment') == "success")
        let transactionID = "{{ Session::has('trxID') ? session('trxID') : "" }}";
        Swal.fire({
            title: "Payment successful",
            text: `Thank you for your payment Transaction ID: ${transactionID}`,
            icon: 'success',
        });
        @endif

        @if (Session::has('payment') && session('payment') == "failed")
        Swal.fire({
            title: "Payment failed",
            text: `Your transaction is failed`,
            icon: 'error',
            type: 'error',
        });
        @endif

        @if (Session::has('payment') && session('payment') == "cancel")
        Swal.fire({
            title: "Payment cancel",
            text: `Your payment is canceled`,
            icon: 'error',
            type: 'error',
        });
        @endif

    </script>
@endpush
