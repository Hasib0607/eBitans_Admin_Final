@if ((isset($done) && $done < 99) || isset($second))
    @if(isset($first))
        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="myModalGuide">
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
                                id="modalCloseButtonGuide"
                                style="background-color:transparent;border-color:transparent">
                                <img src="https://img.icons8.com/material-rounded/24/000000/multiply--v1.png"/>
                            </button>
                        </div>

                    </div>

                    <!-- Modal body -->
                    <div class="modal-body fit-modal-body">
                        <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                            <div id="fb-like" class="fb-page"
                                 data-href="https://www.facebook.com/ebitans"
                                 data-tabs="timeline"
                                 data-width="800"
                                 data-height=""
                                 data-small-header="false"
                                 data-adapt-container-width="true"
                                 data-hide-cover="false"
                                 data-show-facepile="true">
                            </div>

                            <a id="fb-group-link" href="https://www.facebook.com/groups/ebitans" target="_blank"
                               class="btn btn-primary">
                                Join Facebook Group
                            </a>
                        </div>

                        @if(paidTrial())
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
                                <div id="countdown-regular" class="font-bold text-danger">
                                    {{ floor($secondsLeft / 60) }}
                                    :{{ str_pad($secondsLeft % 60, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    // Get the remaining time from server (in seconds)
                                    let timeLeft = @json($secondsLeft);
                                    const countdownElement = document.getElementById('countdown-regular');
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
                            </script>
                        @endif
                        @include('admin.share.dashboard.website-setup-step-popup')

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button id="modalCloseButtonGuide1" type="button" class="btn btn-danger">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include('admin.share.dashboard.website-setup-step')
    @endif
@endif

@push('scripts')

    <!-- Facebook SDK -->
    <script async defer crossorigin="anonymous"
            src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v15.0"></script>


    <script>
        document.getElementById('modalCloseButtonGuide').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default modal close action
            toggleGuideModal();
        });

        document.getElementById('modalCloseButtonGuide1').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default modal close action
            toggleGuideModal();
        });

        const toggleGuideModal = () => {
            const myModalGuide = bootstrap.Modal.getInstance(document.getElementById('myModalGuide'));
            myModalGuide.hide();
        }

    </script>

@endpush
