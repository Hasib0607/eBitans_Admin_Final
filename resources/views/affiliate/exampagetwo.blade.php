@extends('affiliate.layouts.main')

@section('content')
    <div class="card m-4 p-4">

        <div class="">
            <h3>Exams For Affiliation</h3>
            <p>Please unlock all of the FAQs, and read all carefully. You can't go to the next page without unlocking
                it.</p>
        </div>

        <div class="col-md-8">

            <form action="{{ route('affiliate.questions.answer.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="page" value="last"/>

                @if(isset($questions) && count($questions)>0)
                    @foreach($questions as $question)

                        <div class="form-group">
                            <label for="question_1" class="fw-bolder">
                                @if(Session::has('lang') && Session::get('lang')=='bn')
                                    {{$question->question}}
                                @else
                                    {{$loop->index +1 }}. {{$question->question}}
                                @endif </label>

                            @if($question->question_type == 'radio')

                                <div class="div">
                                    <div class="form-group">
                                        @if($question->answer_option_one)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="qus[{{$question->id}}]"
                                                       value="{{$question->answer_option_one}}" required>
                                                <label class="form-check-label"
                                                       for="male">{{$question->answer_option_one}}</label>
                                            </div>
                                        @endif
                                        @if($question->answer_option_two)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="qus[{{$question->id}}]"
                                                       value="{{$question->answer_option_two}}" required>
                                                <label class="form-check-label"
                                                       for="female">{{$question->answer_option_two}}</label>
                                            </div>
                                        @endif
                                        @if($question->answer_option_three)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="qus[{{$question->id}}]"
                                                       value="{{$question->answer_option_three}}" required>
                                                <label class="form-check-label"
                                                       for="other">{{$question->answer_option_three}}</label>
                                            </div>
                                        @endif
                                        @if($question->answer_option_four)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="qus[{{$question->id}}]"
                                                       value="{{$question->answer_option_four}}" required>
                                                <label class="form-check-label"
                                                       for="other">{{$question->answer_option_four}}</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            @else
                                <textarea name="qus[{{$question->id}}]" id="question_1" cols="30" rows="1"
                                          class="form-control" onfocus="focused(this)" onfocusout="defocused(this)"
                                          required></textarea>
                            @endif
                        </div>

                    @endforeach
                @endif
                <input type="hidden" name="page" value="two">
                <div class="text-end mt-2">
                    <button name="Approve" value="Approve" class="btn btn-danger m-1 filterbuttonss" type="button">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            <span>{{$exam_started_at}}</span>
                        @else
                            <span id="timer"></span>
                        @endif
                    </button>

                    @php
                        $time_up = true;
                    @endphp


                    <button id="next" class="btn btn-outline-secondary m-1 filterbuttonss" type="submit"
                            @if($time_up) disabled @endif >
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            Submit
                        @else
                            Submit
                        @endif
                    </button>
                </div>

            </form>


        </div>


    </div>
@endsection

@push('scripts')
    <script>
        // Get the PHP variable value
        var examStartedAt = new Date("{{ $exam_started_at }}");

        // Function to update the timer and disable button if time is up
        function updateTimer() {
            var now = new Date();
            var timeDiff = (now - examStartedAt) / 1000; // Time difference in seconds
            var remainingTime = (10 * 60) - timeDiff; // 10 minutes in seconds minus elapsed time

            // Check if time is up
            var timeUp = remainingTime <= 0;

            // Disable the button if time is up
            document.getElementById("next").disabled = timeUp;

            // If time is up, display a message
            if (timeUp) {
                document.getElementById("timer").innerHTML = "Time's up!";
            } else {
                // Calculate remaining minutes and seconds
                var minutes = Math.floor(remainingTime / 60);
                var seconds = Math.floor(remainingTime % 60);

                // Add leading zeros if necessary
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                // Display the remaining time
                document.getElementById("timer").innerHTML = minutes + ":" + seconds;
            }
        }

        // Update the timer every second
        setInterval(updateTimer, 1000);

        // Initial update
        updateTimer();
    </script>
@endpush
