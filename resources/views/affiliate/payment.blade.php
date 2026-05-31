@extends('affiliate.layouts.main')

@section('content')

    <div class="modal fade" id="paymentModal" tabindex="-1"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content"
                 style="background-color:transparent;border:0px">

                <div class="modal-body" style="border:none">
                    <button class="btn btn-danger sm" data-bs-dismiss="modal"
                            style="float: right; margin: 0px 8px;">X
                    </button>

                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <form action="{{ route('affiliate.user.payment') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="amount" name="amount" value="{{ $charge ?? 0 }}">
                                            <input type="hidden" id="amount_usd" name="amount_usd"
                                                   value="{{ $charge_usd ?? 0 }}">
                                            <div class="m-auto" style="width: fit-content;">
                                                <button name="payment_method" type="submit"
                                                        class="btn btn-primary mt-3"
                                                        value="bkash">
                                                    Pay with Bkash
                                                </button>
                                            </div>
                                            {{--                                            <div class="m-auto" style="width: fit-content;">--}}
                                            {{--                                                <button name="payment_method" type="submit"--}}
                                            {{--                                                        class="btn btn-primary mt-1"--}}
                                            {{--                                                        value="amarpay">--}}
                                            {{--                                                    Card Payment--}}
                                            {{--                                                </button>--}}
                                            {{--                                            </div>--}}
                                            <div class="m-auto" style="width: fit-content;">
                                                <button name="payment_method" type="submit"
                                                        class="btn mt-1"
                                                        style="background-color: #ffc439"
                                                        value="paypal">
                                                    <img width="100px" src="{{ asset("img/pngegg.png") }}"
                                                         alt="paypal">
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="m-4 p-4">
        <h4>Please make payment to continue!</h4>

        <div class="row my-5">

            <div class="col-lg-4 col-md-4">
                <div class="m-auto rounded shadow d-flex justify-content-center align-items-center bg-danger"
                     style="width:100%; height: 190px;">

                    <div class="m-left bg-danger rounded shadow"
                         style="width:70%; height: 130px; background-color: white">
                        <h3 class="text-white text-center">{{$charge}} TK</h3>
                        <div class="col-12 text-center">
                            <button class="btn btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#paymentModal"
                                    data-title="Pay"
                                    id="paymentModalBtn">Pay Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
