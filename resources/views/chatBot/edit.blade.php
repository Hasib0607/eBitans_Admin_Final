@extends('admin.layouts.main')

@section('content')
    <main class="main-content position-relative border-radius-lg">
        @include('chatBot.top_nav_menu')

        <section class="container content-main" style="min-height: 100vh">
            <div class="row">
                <form id="chatbot-form" action="{{ route('chatBot.questions.update') }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ $groupData['group_id'] }}">
                    <div class="row">
                        <div class="col-lg-10 mt-4">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4>Update Question</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <!-- Questions Section -->
                                        <div class="col-md-12">
                                            <div class="row">
                                                <label for="question" class="col-2 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        প্রশ্ন
                                                    @else
                                                        Question
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="col-8" id="question_div">
                                                    @foreach($groupData['questions'] as $question)
                                                        <div class="row mb-3">
                                                            <div class="col-md-8">
                                                                <textarea
                                                                    name="questions[{{ $question['id'] }}]"
                                                                    class="form-control mb-2"
                                                                    cols="30" rows="2"
                                                                    placeholder="Type question here">{{ $question['question'] }}</textarea>
                                                            </div>
                                                            <div class="col-md-4 align-content-center">
                                                                <a href="{{ route('chatBot.question.delete', ['id' =>$question['id']]) }}"
                                                                   data-id="{{ $question['id'] }}"
                                                                   class="btn btn-danger remove-question-row mr-2 p-1"
                                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                                   title="Delete">
                                                                    <img src="{{ URL::to('/') }}/img/delete.png"
                                                                         alt="Delete" width="30px">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <!-- Template for New Question Rows -->
                                                    <div class="row mb-3 question_fields">
                                                        <div class="col-md-8">
                                                            <textarea name="new_questions[]" class="form-control mb-2"
                                                                      cols="30" rows="2"
                                                                      placeholder="Type question here"></textarea>
                                                        </div>
                                                        <div class="col-md-4 align-content-center">
                                                            <button type="button"
                                                                    class="btn btn-danger remove-question-row mr-2 p-1"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="Delete" style="display: none;">
                                                                <img src="{{ URL::to('/') }}/img/delete.png"
                                                                     alt="Delete" width="30px">
                                                            </button>
                                                            <button type="button" onclick="addQuestion()"
                                                                    class="btn btn-success p-1" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="Add">
                                                                <img src="{{ URL::to('/') }}/img/add.png" alt="Add"
                                                                     width="30px">
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @error('questions')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Answers Section -->
                                        <div class="col-md-12 mt-4">
                                            <div class="row">
                                                <label for="answer" class="col-2 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        উত্তর
                                                    @else
                                                        Answer
                                                    @endif
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="col-8" id="answer_div">
                                                    @foreach($groupData['answers'] as $answer)
                                                        <div class="row mb-3">
                                                            <div class="col-md-8">
                                                                <textarea name="answers[{{ $answer['id'] }}]"
                                                                          class="form-control mb-2"
                                                                          cols="30" rows="2"
                                                                          placeholder="Type answer here">{{ $answer['answer'] }}</textarea>
                                                            </div>
                                                            <div class="col-md-4 align-content-center">
                                                                <a href="{{ route('chatBot.answer.delete', ['id' =>$answer['id']]) }}"
                                                                   data-id="{{ $answer['id'] }}"
                                                                   class="btn btn-danger remove-answer-row mr-2 p-1"
                                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                                   title="Delete">
                                                                    <img src="{{ URL::to('/') }}/img/delete.png"
                                                                         alt="Delete" width="30px">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <!-- Template for New Answer Rows -->
                                                    <div class="row mb-3 answer_fields">
                                                        <div class="col-md-8">
                                                            <textarea name="new_answers[]" class="form-control mb-2"
                                                                      cols="30" rows="2"
                                                                      placeholder="Type answer here"></textarea>
                                                        </div>
                                                        <div class="col-md-4 align-content-center">
                                                            <button type="button"
                                                                    class="btn btn-danger remove-answer-row mr-2 p-1"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="Delete" style="display: none;">
                                                                <img src="{{ URL::to('/') }}/img/delete.png"
                                                                     alt="Delete" width="30px">
                                                            </button>
                                                            <button type="button" onclick="addAnswer()"
                                                                    class="btn btn-success p-1" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="Add">
                                                                <img src="{{ URL::to('/') }}/img/add.png" alt="Add"
                                                                     width="30px">
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @error('answers')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Question Type Section -->
                                        <div class="col-lg-12 mt-3">
                                            <div class="row">
                                                <label for="type" class="col-2 col-form-label">Question Type</label>
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <select class="form-select" name="type">
                                                        <option
                                                            value="0" {{ $groupData['type'] == 0 ? 'selected' : '' }}>
                                                            Sales
                                                        </option>
                                                        <option
                                                            value="1" {{ $groupData['type'] == 1 ? 'selected' : '' }}>
                                                            Tech
                                                        </option>
                                                    </select>
                                                    <div class="d-flex justify-content-center align-items-center"
                                                         style="margin-left: 10px">
                                                        <input type="checkbox" name="type_both"
                                                               @if($groupData['type_both'] == 1) checked @endif>&nbsp;&nbsp;Both
                                                    </div>
                                                </div>
                                                @error('type')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Language Section -->
                                        <div class="col-lg-12 mt-3">
                                            <div class="row">
                                                <label for="lang" class="col-2 col-form-label">Language</label>
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <select class="form-select" name="lang">
                                                        <option
                                                            value="0" {{ $groupData['lang'] == '0' ? 'selected' : '' }}>
                                                            English
                                                        </option>
                                                        <option
                                                            value="1" {{ $groupData['lang'] == '1' ? 'selected' : '' }}>
                                                            Bangla
                                                        </option>
                                                    </select>
                                                    <div class="d-flex justify-content-center align-items-center"
                                                         style="margin-left: 10px">
                                                        <input type="checkbox" name="lang_both"
                                                               @if($groupData['lang_both'] == 1) checked @endif>&nbsp;&nbsp;Both
                                                    </div>
                                                </div>
                                                @error('lang')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info mt-4 ml-3">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        // Function to add a new question row
        const addQuestion = () => {
            // Clone the last question row
            var newQuestionRow = $('.question_fields').last().clone();
            // Clear the textarea value
            newQuestionRow.find('textarea[name="new_questions[]"]').val('');
            // Clear the hidden input value
            newQuestionRow.find('input[name="question_ids[]"]').val('');
            // Append the cloned row to the question_div
            $('#question_div').append(newQuestionRow);
            // Show all remove buttons
            $('.remove-question-row').show();
        }

        // Function to remove a question row
        $(document).on('click', '.remove-question-row', function () {
            // Ensure there's more than one question field
            if ($('#question_div .question_fields').length > 1) {
                $(this).closest('.question_fields').remove();
            }
        });

        // Function to add a new answer row
        const addAnswer = () => {
            // Clone the last answer row
            var newAnswerRow = $('.answer_fields').last().clone();
            // Clear the textarea value
            newAnswerRow.find('textarea[name="new_answers[]"]').val('');
            // Clear the hidden input value
            newAnswerRow.find('input[name="answer_ids[]"]').val('');
            // Append the cloned row to the answer_div
            $('#answer_div').append(newAnswerRow);
            // Show all remove buttons
            $('.remove-answer-row').show();
        }

        // Function to remove an answer row
        $(document).on('click', '.remove-answer-row', function () {
            // Ensure there's more than one answer field
            if ($('#answer_div .answer_fields').length > 1) {
                $(this).closest('.answer_fields').remove();
            }
        });

        // Function to remove empty question and answer rows before form submission
        $('#chatbot-form').on('submit', function (e) {
            // Remove empty question rows
            $('#question_div .question_fields').each(function () {
                var question = $(this).find('textarea[name="new_questions[]"]').val().trim();
                if (question === '') {
                    $(this).remove();
                }
            });

            // Remove empty answer rows
            $('#answer_div .answer_fields').each(function () {
                var answer = $(this).find('textarea[name="new_answers[]"]').val().trim();
                if (answer === '') {
                    $(this).remove();
                }
            });
        });
    </script>
@endpush
