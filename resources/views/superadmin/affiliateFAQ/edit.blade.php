@extends('admin.layouts.main')
@section('content')

    <main class="main-content position-relative border-radius-lg">
        @include('superadmin.partials.top_nav_menu')

        <section class="container content-main" style="min-height: 100vh">
            <div class="row">
                <form action="{{ route('affiliate.faq.question.update') }}" method="post"
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

                                        <input type="hidden" name="question_id" value="{{ $faq->id }}">
                                        <div class="col-md-12 mb-4">
                                            <label for="sub_title" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Question
                                                @else
                                                    Question
                                                @endif
                                                <span class="req">*</span>
                                            </label>
                                            <input type="text" name="question" id="question" placeholder="Type here"
                                                   class="form-control" value="{{ $faq->question }}"/>
                                            @error('question')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-4">
                                            <label for="sub_title" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Answer
                                                @else
                                                    Answer
                                                @endif
                                                <span class="req">*</span>
                                            </label>
                                            <textarea name="answer" id="answer" placeholder="Type here"
                                                      class="form-control" cols="30"
                                                      rows="5">{{ $faq->answer }}</textarea>
                                            @error('answer')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-4">
                                            <label for="sub_title" class="form-label">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Video URL
                                                @else
                                                    Video URL
                                                @endif
                                            </label>
                                            <input type="text" name="video_link" id="video_link" placeholder="Type here"
                                                   class="form-control" value="{{ $faq->video_link }}"/>
                                            @error('video_link')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-lg-8">
                                            <div class="row">
                                                <label for="staticEmail" class="col-md-2 col-form-label">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        স্টেটাস
                                                    @else
                                                        Status
                                                    @endif
                                                </label>
                                                <div class="col-md-2">
                                                    <div class="form-check form-switch is-filled"
                                                         style="text-align:center;padding-top:14px;">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="flexSwitchCheckChecked" name="status"
                                                               style="margin: 0 0 0 -50px;"
                                                               @if($faq->status == 1)checked @endif>
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

