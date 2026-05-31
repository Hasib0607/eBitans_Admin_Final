@extends('admin.layouts.main')
@push('styles')
    <style>
        .listdigital {
            list-style: none;
            padding-left: 0px;
        }

        .listdigital li {
            float: unset !important;
        }


        .panel {
            padding: 19px 15px 10px 15px;
            border: 1px solid gray;
            margin-bottom: 5px;
            background: #fff;
        }

        label {
            color: #000;
        }

        #accordion {
            height: 90vh;
            overflow-x: hidden;
            overflow-y: auto;
        }


        /* image hover  */

        .download {
            position: relative;
            display: inline-block;
        }

        .download .download-btn {
            display: none;
            position: absolute;
            left: 30%;
            bottom: 5%;
            z-index: 99;
        }

        .download:hover .download-btn {
            display: inline;
        }

        #download {
            position: relative;
            display: inline-block;
        }

        #download .download-btn {
            display: none;
            position: absolute;
            left: 30%;
            bottom: 5%;
            z-index: 99;
        }

        #download:hover .download-btn {
            display: inline;
        }


    </style>
@endpush
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg" style="min-height: 100vh">


        @include('superadmin.partials.top_nav_menu')

        <div class="container-fluid mt-4" id="toplist">

            <div class="row mt-5 productlist">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5> @if(Session::has('lang') && Session::get('lang')=='bn')
                                    Affiliate Lists
                                @else
                                    Affiliate Lists
                                @endif </h5>
                            <input type="text" id="live-search-box" class="form-control"
                                   placeholder="Affiliate Search...">
                        </div>


                        <div class="card-body">
                            <ul class="listdigital">
                                @if(isset($users) && count($users)>0)
                                    @foreach($users as $user)
                                            <?php
                                            $user_status = $user->user_status ?? "Hold";
                                            $className = "text-info";

                                            if ($user_status == "Hold") {
                                                $className = "text-warning";
                                            } elseif ($user_status == "Approved") {
                                                $className = "text-success";
                                            } elseif ($user_status == "Rejected") {
                                                $className = "text-danger";
                                            }

                                            ?>
                                        <li class="my-2 rounded justify-content-between" style="display: flex">
                                            <a href="{{ route('affiliate.questions.answers.show', $user->id) }}">
                                                @if(isset($user->name) && !empty($user->name))
                                                    {{ $user->name }}
                                                @elseif(isset($user->phone) && !empty($user->phone))
                                                    {{ $user->phone }}
                                                @elseif(isset($user->email) && !empty($user->email))
                                                    {{ $user->email }}
                                                @else
                                                    {{ $user->id ?? "Name not set Yet" }}
                                                @endif
                                                <span class="{{ $className }}" style="font-size: 10px;">({{ $user_status }})</span>
                                            </a>
                                            @if(isset($user->answer_submitted_at))
                                                <span>{{ Carbon\Carbon::parse($user->answer_submitted_at)->format("d-m-Y h:i:s A") }}</span>
                                            @endif
                                        </li>

                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="modal-content mb-3">
                        <div class="modal-header">
                            <h5 class="modal-title"
                                id="exampleModalLabel">@if(Session::has('lang') && Session::get('lang')=='bn')
                                    Sample Affiliate Questions
                                @else
                                    Sample Affiliate Questions
                                @endif </h5>
                        </div>
                        <div class="modal-body">

                            @if(isset($questions) && count($questions)>0)
                                @foreach($questions as $question)

                                    <div class="form-group">
                                        <label for="question_1"> @if(Session::has('lang') && Session::get('lang')=='bn')
                                                {{$question->question}}
                                            @else
                                                {{$question->question}}
                                            @endif </label>
                                        @if($question->question_type == 'radio')

                                            <div class="div">
                                                <label
                                                    for="question_1_option_1 p-0 m-0">1. {{$question->answer_option_one}}</label><br>
                                                <label
                                                    for="question_1_option_2 p-0 m-0">2. {{$question->answer_option_two}}</label><br>
                                                <label
                                                    for="question_1_option_3 p-0 m-0">3. {{$question->answer_option_three}}</label><br>
                                                <label
                                                    for="question_1_option_4 p-0 m-0">4. {{$question->answer_option_four}}</label><br>
                                            </div>

                                        @else
                                            <textarea name="question_1" id="question_1" cols="30" rows="2"
                                                      class="form-control" onfocus="focused(this)"
                                                      onfocusout="defocused(this)">{{ $required_information->question_1 ?? "" }}</textarea>
                                        @endif
                                    </div>

                                    <hr/>
                                @endforeach
                            @endif

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </main>
@endsection
@push('scripts')

    <script>
        jQuery(document).ready(function ($) {

            // Set data-search-term attribute for each list item
            $('.listdigital li').each(function () {
                var searchText = $(this).find('a').text().trim().toLowerCase();
                $(this).attr('data-search-term', searchText);
            });


            $('#live-search-box').on('keyup', function () {
                var searchTerm = $(this).val().trim().toLowerCase();

                $('.listdigital li').each(function () {
                    if (searchTerm != "") {
                        if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    } else {
                        $(this).show();
                    }
                });

            });

        });
    </script>

    <script>
        function edit(id) {
            console.log(id);
            $.get('/contentdetails', {
                id: id
            }, function (data) {
                $('#id').val(data.id);
                $('#store').val(data.storename);
                $('#ttype').val(data.type);
                $('#name').val(data.name);
                $('#content').val(data.content);
                $('#note').val(data.note);
                $('#exampleModal22').modal('show');
            })
        }
    </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

@endpush
