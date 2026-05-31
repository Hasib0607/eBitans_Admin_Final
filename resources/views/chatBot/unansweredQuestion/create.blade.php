@extends('admin.layouts.main')

@section('content')
    <main class="main-content position-relative border-radius-lg">
        @include('chatBot.top_nav_menu')

        <section class="container content-main" style="min-height: 100vh">
            <div class="row">
                <form id="chatbot-form" action="{{ route('chatBot.unansweredQuestions.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $question['id'] ?? '' }}">
                    <input type="hidden" name="question_text" value="{{ $question['question'] ?? '' }}">

                    <div class="row">
                        <div class="col-lg-10 mt-4">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <h4 class="mb-0">Resolve Support Learning Question</h4>
                                        <a href="{{ route('chatBot.unansweredQuestions.list') }}" class="btn btn-outline-secondary btn-sm">
                                            Back to Queue
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <label class="col-2 col-form-label">Question</label>
                                                <div class="col-8">
                                                    <textarea class="form-control" rows="4" readonly>{{ $question['question'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-4">
                                            <div class="row">
                                                <label class="col-2 col-form-label">Meta</label>
                                                <div class="col-8">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <input class="form-control" value="{{ ucfirst($question['bot_type'] ?? 'support') }}" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <input class="form-control" value="{{ ucfirst($question['status'] ?? 'open') }}" readonly>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <input class="form-control" value="{{ $question['created_at'] ?? '' }}" readonly>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input class="form-control" value="{{ $question['session_id'] ?? '' }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <div class="row">
                                                <label for="manual_answer" class="col-2 col-form-label">
                                                    Answer
                                                    <span class="req">*</span>
                                                </label>
                                                <div class="col-8">
                                                    <textarea name="manual_answer" class="form-control" rows="5"
                                                              placeholder="Type support answer here">{{ old('manual_answer', $question['manual_answer'] ?? '') }}</textarea>
                                                    @error('manual_answer')
                                                    <p class="text-danger mt-2" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-4">
                                            <div class="row">
                                                <label for="training_content" class="col-2 col-form-label">Training Content</label>
                                                <div class="col-8">
                                                    <textarea name="training_content" class="form-control" rows="6"
                                                              placeholder="Optional training content for the Python support bot">{{ old('training_content') }}</textarea>
                                                    <small class="text-muted d-block mt-2">
                                                        Leave this blank if you want the system to generate training content from the question and your answer.
                                                    </small>
                                                    @error('training_content')
                                                    <p class="text-danger mt-2" role="alert">{{ $message }}</p>
                                                    @enderror

                                                    <div class="form-check mt-3">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                               id="add_to_training" name="add_to_training"
                                                               {{ old('add_to_training', '1') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="add_to_training">
                                                            Add resolved answer to support training
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-info mt-4 ml-3">Resolve &amp; Sync</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
