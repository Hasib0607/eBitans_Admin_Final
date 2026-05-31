@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative border-radius-lg">
        @include('superadmin.partials.top_nav_menu')

        <section class="container content-main" style="min-height: 100vh">
            <div class="row">
                <form action="{{ route('affiliate.questions.update', $question->id) }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-10 mt-4">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            Update Question
                                        @else
                                            Update Question
                                        @endif
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">

                                        <div class="col-md-12 mb-4">
                                            <label for="sub_title" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Question
                                                @else
                                                    Question
                                                @endif
                                                <span class="req">*</span>
                                            </label>
                                            <textarea name="question" id="question" placeholder="Type here"
                                                      class="form-control" required>{{$question->question}}</textarea>
                                            @error('question')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-lg-8">
                                            <div class="row">
                                                <label for="staticEmail" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        জনপ্রিয় পোস্ট
                                                    @else
                                                        Question Options
                                                    @endif
                                                </label>
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch is-filled"
                                                         style="text-align:center;padding-top:14px;">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="flexSwitchCheckChecked" name="question_type"
                                                               @if($question->question_type == "radio") checked @endif
                                                               style="margin:0 auto;" onchange="handleOption()">
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                    @error('question_type')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2"></div>


                                        <div class="col-md-3 mt-4 " id="option1"
                                             style="display:@if($question->question_type == "radio") {{ 'block' }} @else {{ 'none' }} @endif">
                                            <label for="position" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Question Option
                                                @else
                                                    Question Option
                                                @endif
                                            </label>
                                            <input type="text" placeholder="Type here" class="form-control"
                                                   id="position" name="answer_option_one"
                                                   value="{{$question->answer_option_one}}"
                                            >
                                            @error('posi')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mt-4" id="option2"
                                             style="display:@if($question->question_type == "radio") {{ 'block' }} @else {{ 'none' }} @endif">
                                            <label for="position" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Question Option
                                                @else
                                                    Question Option
                                                @endif
                                            </label>
                                            <input type="text" placeholder="Type here" class="form-control"
                                                   value="{{$question->answer_option_two}}"
                                                   id="position" name="answer_option_two"
                                            >
                                            @error('posi')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mt-4" id="option3"
                                             style="display:@if($question->question_type == "radio") {{ 'block' }} @else {{ 'none' }} @endif">
                                            <label for="position" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Question Option
                                                @else
                                                    Question Option
                                                @endif
                                            </label>
                                            <input type="text" placeholder="Type here" class="form-control"
                                                   value="{{$question->answer_option_three}}"
                                                   id="position" name="answer_option_three"
                                            >
                                            @error('posi')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-3 mt-4 mb-2" id="option4"
                                             style="display:@if($question->question_type == "radio") {{ 'block' }} @else {{ 'none' }} @endif">
                                            <label for="position" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Question Option
                                                @else
                                                    Question Option
                                                @endif
                                            </label>
                                            <input type="text" placeholder="Type here" class="form-control"
                                                   value="{{$question->answer_option_four}}"
                                                   id="position" name="answer_option_four"
                                            >
                                            @error('posi')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>


                                        <div class="col-lg-8">
                                            <div class="row">
                                                <label for="staticEmail" class="col-md-3 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        স্টেটাস
                                                    @else
                                                        Status
                                                    @endif
                                                </label>
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch is-filled"
                                                         style="text-align:center;padding-top:14px;">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="flexSwitchCheckChecked" name="status"
                                                               style="margin:0 auto;"
                                                               @if($question->status == 1)checked @endif>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                    @error('status')
                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <button type="submit" class="btn btn-info mt-4 ml-3">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            প্রকাশ
                                        @else
                                            Update
                                        @endif
                                    </button>
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
        function handleOption() {
            var checkbox = document.getElementById('flexSwitchCheckChecked');
            var option1 = document.getElementById('option1');
            var option2 = document.getElementById('option2');
            var option3 = document.getElementById('option3');
            var option4 = document.getElementById('option4');

            if (checkbox.checked) {
                option1.style.display = 'block';
                option2.style.display = 'block';
                option3.style.display = 'block';
                option4.style.display = 'block';
            } else {
                option1.style.display = 'none';
                option2.style.display = 'none';
                option3.style.display = 'none';
                option4.style.display = 'none';
            }
        }
    </script>
@endpush
