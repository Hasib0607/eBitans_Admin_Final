@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative border-radius-lg" style="min-height: 100vh">
        @include('chatBot.top_nav_menu')

        <section class="container content-main">
            <div class="row">
                <form action="{{ route('chatBot.questions.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-10 mt-4">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4>Add New Question</h4>
                                </div>

                                <div class="card-body">
                                    <div class="row mb-3">

                                        <div class="col-md-12">
                                            <div class="row">
                                                <label for="sub_title" class="col-2 form-label">Question <span
                                                        class="req">*</span></label>
                                                <div class="col-8" id="question_div">
                                                    <div class="row" id="question_fields">
                                                        <div class="col-md-8">
                                                            <textarea name="question[]" id="question"
                                                                      placeholder="Type question here"
                                                                      class="form-control mb-2" cols="30"
                                                                      rows="2"></textarea>
                                                        </div>
                                                        <div class="col-md-4 align-content-center">
                                                            <button type="button"
                                                                    class="btn btn-danger remove-question-row mr-2 p-1"
                                                                    style="display: none;" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="Delete"><img
                                                                    src="{{ URL::to('/') }}/img/delete.png" alt=""
                                                                    width="30px"></button>
                                                            <button type="button" onclick="addQuestion()"
                                                                    class="btn btn-success p-1" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="Add"
                                                                    style="margin-left: 8px;"><img
                                                                    src="{{ URL::to('/') }}/img/add.png" alt=""
                                                                    width="30px"></button>
                                                        </div>
                                                    </div>
                                                    @error('question')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-4">
                                            <div class="row">
                                                <label for="answer" class="col-2 col-form-label">Answer <span
                                                        class="req">*</span></label>
                                                <div class="col-8" id="answer_div">
                                                    <div class="row" id="answer_fields">
                                                        <div class="col-md-8">
                                                            <textarea name="answer[]" id="answer"
                                                                      placeholder="Type answer here"
                                                                      class="form-control mb-2" cols="30"
                                                                      rows="2"></textarea>
                                                        </div>
                                                        <div class="col-md-4 align-content-center">
                                                            <button type="button"
                                                                    class="btn btn-danger remove-answer-row mr-2 p-1"
                                                                    style="display: none;" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="Delete"><img
                                                                    src="{{ URL::to('/') }}/img/delete.png" alt=""
                                                                    width="30px"></button>
                                                            <button type="button" onclick="addAnswer()"
                                                                    class="btn btn-success p-1" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="Add"
                                                                    style="margin-left: 8px;"><img
                                                                    src="{{ URL::to('/') }}/img/add.png" alt=""
                                                                    width="30px"></button>
                                                        </div>
                                                    </div>
                                                    @error('answer')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-3">
                                            <div class="row">
                                                <label for="staticEmail" class="col-2 col-form-label">Question
                                                    Type</label>
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <select class="form-select" name="type">
                                                        <option value="0" {{ old('type') != 'on' ? 'selected' : '' }}>
                                                            Sales
                                                        </option>
                                                        <option value="1" {{ old('type') == 'on' ? 'selected' : '' }}>
                                                            Tech
                                                        </option>
                                                    </select>
                                                    <div class="d-flex justify-content-center align-items-center"
                                                         style="margin-left: 10px">
                                                        <input type="checkbox" name="type_both">&nbsp;&nbsp;Both
                                                    </div>
                                                </div>
                                                @error('type')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-3">
                                            <div class="row">
                                                <label for="staticEmail" class="col-2 col-form-label">Language</label>
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <select class="form-select" name="lang">
                                                        <option value="0" {{ old('lang') == '0' ? 'selected' : '' }}>
                                                            English
                                                        </option>
                                                        <option value="1" {{ old('lang') == '1' ? 'selected' : '' }}>
                                                            Bangla
                                                        </option>
                                                    </select>
                                                    <div class="d-flex justify-content-center align-items-center"
                                                         style="margin-left: 10px">
                                                        <input type="checkbox" name="lang_both">&nbsp;&nbsp;Both
                                                    </div>
                                                </div>
                                                @error('lang')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info mt-4 ml-3">Submit</button>
                                </div>
                            </div> <!-- card end// -->
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

@endsection

@push('scripts')
    <script>
        // Function to add new question row
        const addQuestion = () => {
            var newQuestionRow = $('#question_fields').last().clone();
            newQuestionRow.find('textarea[name="question[]"]').val('');
            $('#question_div').append(newQuestionRow);

            // Show the delete button now that there are multiple rows
            $('#question_div .remove-question-row').show();
        }

        // Function to remove question row
        $(document).on('click', '.remove-question-row', function () {
            // Check if there is more than one 'question_fields' present
            if ($('#question_div #question_fields').length > 1) {
                // Remove the closest parent 'question_fields' div
                $(this).closest('#question_fields').remove();
            }
        });


        // Function to add new answer row
        const addAnswer = () => {
            var newAnswerRow = $('#answer_fields').last().clone();
            newAnswerRow.find('textarea[name="answer[]"]').val('');
            $('#answer_div').append(newAnswerRow);

            // Show the delete button now that there are multiple rows
            $('#answer_div .remove-answer-row').show();
        }

        // Function to remove answer row
        $(document).on('click', '.remove-answer-row', function () {
            // Check if there is more than one 'answer_fields' present
            if ($('#answer_div #answer_fields').length > 1) {
                // Remove the closest parent 'answer_fields' div
                $(this).closest('#answer_fields').remove();
            }
        });

    </script>
@endpush
