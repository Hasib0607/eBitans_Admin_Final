@php
    $showPopup = false;
    $userData = getUserData();
    $store = $userData['store'] ?? NULL;
    $store_id = $userData['store_id'] ?? NULL;

    if((Auth::user()->type != 'superadmin' && Auth::user()->type != 'superstaff')){
        if(!isset($store->alert_popup) || is_null($store->alert_popup)){
            $showPopup = true;
        }else{
            if(in_array($store->alert_popup, [1, 4, 7, 10, 15, 20])){
                $popupMin = $store->alert_popup ?? 1;

                 if (isset($store) && isset($store->created_at)) {
                    $showPaymentTime = \Carbon\Carbon::parse($store->created_at);
                    $currentTime = \Carbon\Carbon::now();

                    $minuteDifference = $currentTime->diffInMinutes($showPaymentTime);

                    if ($popupMin <= $minuteDifference) {
                        $showPopup = true;
                    }
                }
            }
        }
    }

    if ($showPopup) {
        $allowedMinutes = [1, 4, 7, 10, 15, 20];
        $popupMin = $store->alert_popup ?? 1;
        $nextMin = NULL; // default null, in case there's no next

        // Find the index and set nextMin
        $currentIndex = array_search($popupMin, $allowedMinutes);
        if ($currentIndex !== false && isset($allowedMinutes[$currentIndex + 1])) {
            $nextMin = $allowedMinutes[$currentIndex + 1];
        }

        $store->alert_popup = $nextMin;
        $store->save();
    }
@endphp

@if ($showPopup && paidTrial())
    @php
        $registrationFee = \App\Models\RegistrationFee::where("status", 1)->first();
        $addonsOrder = \App\Models\AddonsOrder::where("store_id", $store_id)->where("paid_registration", 1)->first();
        if (!isset($addonsOrder)) {
            $package = [
                "id" => 2,
                "name" => "Standard",
                "month" => "1",
                "type" => "package",
                "price" => $registrationFee->price,
                "usd_price" => 2,
                "usd_offer_price" => 2,
                "offerprice" => $registrationFee->price,
                "activeTime" => 1
            ];

            $addonsOrder = new \App\Models\AddonsOrder();
            $addonsOrder->user_id = $store->user_id;
            $addonsOrder->store_id = $store_id;
            $addonsOrder->currency_id = $store->currency;
            $addonsOrder->addons = [];
            $addonsOrder->package = json_encode($package) ?? null;
            $addonsOrder->payment_method = NULL;
            $addonsOrder->plan_id = 2;
            $addonsOrder->plan_month = $website_month ?? 1;
            $addonsOrder->plan_type = 'website';
            $addonsOrder->total = $registrationFee->price;
            $addonsOrder->plan_check = 1;
            $addonsOrder->status = 'Failed';
            $addonsOrder->paid_registration = 1;
            $addonsOrder->save();
        }
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto');

        body {
            font-family: 'Roboto', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .payment-method {
            border: 2px solid #81aabc;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #c2ebfd;
        }

        .payment-method:hover, .payment-method:active {
            border-color: #4a6bff;
            background-color: #f5f8ff;
        }

        .payment-method img {
            height: 50px;
        }

        .payment-method.selected {
            border-color: #4a6bff;
            background-color: #f5f8ff;
            box-shadow: 0 0 0 2px rgba(74, 107, 255, 0.3);
        }

        .payment-method:active {
            transform: scale(0.98);
        }

    </style>

    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">
                                        <span class="nav-link-text ms-1">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ওয়েবসাইট ফিচার্স
                                            @else
                                                Website Features
                                            @endif
                                        </span>
                    </h4>
                    <div>
                        <a href="http://{{ $store->url ?? '' }}" class="btn btn-primary mb-0"
                           style="margin-right: 20px;"
                           target="_blank">Visit
                            Website</a>
                        <button
                            type="button"
                            id="modalCloseButton"
                            style="background-color:transparent;border-color:transparent">
                            <img src="https://img.icons8.com/material-rounded/24/000000/multiply--v1.png"/>
                        </button>
                    </div>

                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    @php
                        $secondsLeft = 0;
                        $min = env("REGISTRATION_PAYMENT_DELAY", 20);
                        $expireTime = \Carbon\Carbon::parse($store->created_at)->addMinutes($min);
                        $now = \Carbon\Carbon::now();
                        $secondsLeft = max(0, $now->diffInSeconds($expireTime, false));
                    @endphp
                    <div id="countdown-timer" class="text-center p-4 rounded-lg"
                         style="background: #e4f5ff">
                        <h1 class="font-semibold mb-2 text-danger redirect-text">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                আর মাত্র বাকি
                            @else
                                আর মাত্র বাকি
                            @endif
                        </h1>
                        <div id="countdown" class="font-bold text-danger">
                            {{ floor($secondsLeft / 60) }}
                            :{{ str_pad($secondsLeft % 60, 2, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>

                    <form id="registrationForm1" action="{{ route("registrationFeePayment") }}"
                          method="POST"
                          style="text-align: center;">
                        @csrf
                        <input type="hidden" name="payment_method" id="payment_method1" value="bkash">
                        <input type="hidden" name="order_id" id="order_id1" value="{{ $addonsOrder->id ?? "" }}">

                        <h1 style="margin-bottom: 25px;font-weight: bold">শুধুমাত্র আপনার জন্য বিশাল ডিসকাউন্ট</h1>
                        <h4 style="margin-bottom: 5px;">রেগুলার প্রাইস স্ট্যান্ডার্ড প্যাকেজ <span class="line-through">১৪৯০</span>
                        </h4>
                        <h3 style="margin-top: 0; color: #2d3748; font-weight: bold;">অফার
                            প্রাইস {{ $addonsOrder->total ?? "0" }} টাকা</h3>

                        <div
                            style="margin: 20px 0; padding: 15px; background: #f3f4f6; border-radius: 8px; border: 1px solid #e5e7eb;">
                            <p style="margin-bottom: 10px;">পেমেন্ট করতে 'বিকাশ/ নগদ' বাছাই করুন</p>
                            <div style="display: flex; justify-content: center; gap: 15px;">
                                <div class="payment-method selected" onclick="selectPaymentMethod1('bkash', this)">
                                    <img src="{{ asset("img/payment/bkashLogo.png") }}" style="height: 50px;" alt="">
                                </div>
                                <div class="payment-method" onclick="selectPaymentMethod1('nagad', this)">
                                    <img src="{{ asset("img/payment/nagadLogo.png") }}" style="height: 50px;" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-primary" onclick="myFunction1(event)">Pay Now</button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Get the remaining time from server (in seconds)
                            let timeLeft = @json($secondsLeft);
                            const countdownElement = document.getElementById('countdown');
                            const countdownTimerElement = document.getElementById('countdown-timer');

                            // Only start countdown if there's time left
                            if (timeLeft > 0) {
                                // Update the countdown every second
                                const countdownInterval = setInterval(function () {
                                    timeLeft--;

                                    const minutes = Math.floor(timeLeft / 60);
                                    let seconds = timeLeft % 60;
                                    seconds = seconds < 10 ? '0' + seconds : seconds;

                                    if (timeLeft >= 0) {
                                        countdownElement.textContent = minutes + ':' + seconds;
                                    } else {
                                        countdownElement.textContent = "00:00";
                                        clearInterval(countdownInterval);
                                        // Optional: redirect or other action
                                    }
                                }, 1000);

                            } else {
                                // Time has already expired, show expired message
                                countdownElement.textContent = "00:00";
                            }
                        });

                        function myFunction1(e) {
                            e.preventDefault();
                            const payment_method1 = document.getElementById('payment_method1').value;
                            const order_id1 = document.getElementById('order_id1').value;

                            if (payment_method1 == "") {
                                swal.fire(
                                    'Warning!',
                                    "Please select a payment method!",
                                    'warning'
                                );
                                return false
                            }

                            if (order_id1 == '') {
                                swal.fire(
                                    'Warning!',
                                    "Order ID Missing!",
                                    'warning'
                                );
                                return false
                            }

                            const registrationForm1 = document.getElementById('registrationForm1');
                            registrationForm1.submit();
                        }

                        function selectPaymentMethod1(method, element) {
                            // Remove selected class from all payment methods
                            document.querySelectorAll('.payment-method').forEach(el => {
                                el.classList.remove('selected');
                            });

                            // Add selected class to clicked element
                            element.classList.add('selected');
                            const payment_method1 = document.getElementById('payment_method1');
                            payment_method1.value = method;
                        }
                    </script>
                </div>

                <!-- Modal footer -->
                {{--                <div class="modal-footer">--}}
                {{--                    <button id="modalCloseButton1" type="button" class="btn btn-danger">--}}
                {{--                        Close--}}
                {{--                    </button>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
@endif


@push('scripts')

    <!-- Facebook SDK -->
    <script async defer crossorigin="anonymous"
            src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v15.0"></script>


    <script>
        function fbPageLike() {
            const myModal = bootstrap.Modal.getInstance(document.getElementById('myModal'));
            myModal.hide();
        }

        document.getElementById('modalCloseButton').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default modal close action
            fbPageLike();
        });

        document.getElementById('modalCloseButton1').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default modal close action
            fbPageLike();
        });


    </script>

@endpush
