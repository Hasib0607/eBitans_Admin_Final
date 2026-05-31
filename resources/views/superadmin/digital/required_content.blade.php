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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{route('superadmin.savecontent')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Write Content</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Store</label>
                            <select class="form-control" name="store">
                                <option value="0">Select Store</option>
                                @if(isset($lists) && count($lists) > 0)
                                    @foreach($lists as $list)
                                        <option value="{{$list->id}}">{{$list->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type">
                                <option value="0">Select Type</option>
                                <option value="Static Content">Static Content</option>
                                <option value="Video Content">Video Content</option>
                                <option value="Gify Content">Gify Content</option>
                                <option value="Caption Writting">Caption Wrtting</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Content</label>
                            <input type="file" name="content" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <textarea name="note" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="exampleModal22" tabindex="-1" aria-labelledby="exampleModalLabel22" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{route('updatecontent')}}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel22">Edit Content</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Store</label>
                            <input type="text" name="store" id="store" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" name="type" id="type" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Content</label>
                            <textarea name="content" rows="4" id="content" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <textarea name="note" rows="3" id="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{URL::to('/')}}/superadmin/digitalmarketing">
                                    <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span
                                        class="nav-link-text ms-1">@if(Session::has('lang') && Session::get('lang') == 'bn')
                                        ড্যাশবোর্ড @else Dashboard @endif</span>
                                </a>
                            </li>


                            <li class="breadcrumb-item active">
                                <a href="{{ route('superadmin.required.content') }}">
                                    <img src="{{URL::to('/')}}/img/icons/box.png"> <br> <span class="nav-link-text ms-1">
                                        @if(Session::has('lang') && Session::get('lang') == 'bn') প্রয়োজনীয় তথ্য @else
                                        Required Information @endif </span>
                                </a>
                            </li>


                            {{-- <li class="breadcrumb-item" aria-current="page">
                                <a href="{{URL::to('/')}}/superadmin/boosting">
                                    <img src="{{URL::to('/')}}/img/icons/categories.png"> <br><span
                                        class="nav-link-text ms-1">Boosting</span>
                                </a>
                            </li> --}}
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{URL::to('/')}}/superadmin/content">
                                    <img src="{{URL::to('/')}}/img/icons/categories.png"> <br><span
                                        class="nav-link-text ms-1"> @if(Session::has('lang') && Session::get('lang') == 'bn')
                                        কনটেন্ট @else Content @endif </span>
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">
            {{-- <div class="row">
                <div class="col-md-6">
                    <h4>Content</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a data-bs-toggle="modal" data-bs-target="#exampleModal"
                                style="display:block;border-radius:0px !important" class="btn btn-primary">Create
                                Content</a></li>
                        <li style="padding:0px;border:0px;"><a style="display:block;border-radius:0px !important"
                                class="btn btn-secondary">Print</a></li>
                    </ul>
                </div>
            </div> --}}
            <div class="row mt-5 productlist">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5> @if(Session::has('lang') && Session::get('lang') == 'bn') দোকান তালিকা @else Store List
                            @endif </h5>
                            <input type="text" id="live-search-box" class="form-control" placeholder="Store Search...">
                        </div>
                        <div class="card-body">
                            <ul class="listdigital">
                                @if(isset($lists) && count($lists) > 0)
                                    @foreach($lists as $list)
                                        <li @if(isset($id) && $list->id == $id) class="active" @endif><a
                                                href="{{route('superadmin.required.content.view', $list->id)}}">{{$list->name}}</a>
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
                            <h5 class="modal-title" id="exampleModalLabel">
                                @if(Session::has('lang') && Session::get('lang') == 'bn') প্রয়োজনীয় তথ্য ফর্ম @else Required
                                Information Form @endif </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="question_1"> @if(Session::has('lang') && Session::get('lang') == 'bn') আপনার
                                ব্যবসার ধরন এবং ক্রেতাদের ব্যপারে জানান @else Describe your business type and customers
                                    @endif <span style="color: red;">*</span> </label>
                                <textarea name="question_1" id="question_1" cols="30" rows="2" class="form-control"
                                    onfocus="focused(this)"
                                    onfocusout="defocused(this)">{{ $required_information->question_1 ?? "" }}</textarea>
                            </div>

                            <div class="form-group mt-3">
                                <label for="question_2">@if(Session::has('lang') && Session::get('lang') == 'bn') আপনার
                                ব্যবসায়িক যোগাযোগ নাম্বার দিন @else Enter your business contact number @endif <span
                                        style="color: red;">*</span> </label>
                                <input type="text" name="question_2" id="question_2" class="form-control"
                                    onfocus="focused(this)" onfocusout="defocused(this)"
                                    value="{{ $required_information->question_2 ?? "" }}">
                            </div>

                            <div class="form-group mt-3">
                                <label for="question_3"> @if(Session::has('lang') && Session::get('lang') == 'bn') আপনার
                                ওয়েবসাইটের লিংক শেয়ার করুন (যদি থাকে) @else Share your website link (if any) @endif
                                </label>
                                <textarea name="question_3" id="question_3" cols="30" rows="1"
                                    class="form-control">{{ $required_information->question_3 ?? "" }}</textarea>
                            </div>

                            <div class="form-group mt-3">
                                <label for="question_4"> @if(Session::has('lang') && Session::get('lang') == 'bn') আপনার
                                সোশ্যাল মিডিয়া পেজের লিংক শেয়ার করুন @else Share your social media page link @endif
                                </label>
                                <textarea name="question_4" id="question_4" cols="30" rows="1"
                                    class="form-control">{{ $required_information->question_4 ?? "" }}</textarea>
                            </div>

                            <div class="form-group mt-3">
                                <label for="question_5"> @if(Session::has('lang') && Session::get('lang') == 'bn') আপনার
                                ব্যবসার লোগো এবং ব্রান্ড গাইড লাইন (যদি থাকে) দিন @else Provide your business logo and
                                    brand guide line (if any) @endif </label>

                                <div class="row mb-3">
                                    @if ($required_information->question_5 ?? '')
                                        @php
                                            $RequiredInformation2 = json_decode($required_information->question_5);
                                        @endphp

                                        @foreach ($RequiredInformation2 as $key => $item)
                                            <div class="col-md-3 download">
                                                <img style="width: 100%;"
                                                    src="{{ asset('clientContent/RequiredInformation/' . $item) }}" alt="">
                                                <a class="btn btn-primary btn-sm download-btn" href="#"
                                                    onclick="event.preventDefault(); document.getElementById('download_Frm{{$key}}').submit();">Download</a>

                                                <form id="download_Frm{{$key}}"
                                                    action="{{ route('superadmin.required.content.download') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="pathName"
                                                        value="{{'clientContent/RequiredInformation/' . $item }}">
                                                </form>

                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            @if(Session::has('lang') && Session::get('lang') == 'bn') প্রয়োজনীয় বিষয়বস্তুর তালিকা @else
                            List of Required Content @endif
                        </div>
                        <div class="card-body">


                            @if(isset($required_information_contents) && count($required_information_contents) > 0)
                                <div class="panel-group" id="accordion">

                                    @foreach ($required_information_contents as $key => $required_information_content)

                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title" style="font-size: 18px;">
                                                    <a data-toggle="collapse" data-parent="#accordion"
                                                        href="#collapse_{{ $required_information_content->id}}">
                                                        @if(Session::has('lang') && Session::get('lang') == 'bn') বিভিন্ন বিষয়বস্তুর
                                                        জন্য প্রয়োজনীয় তথ্য @else Required Information for individual content
                                                        @endif
                                                        {{ date('d-m-Y', strtotime($required_information_content->created_at)) }}
                                                    </a>
                                                    <a href="{{ route('superadmin.required.content.delete', $required_information_content->id) }}"
                                                        class="btn btn-primary btn-sm" style="float: right;">
                                                        Delete
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse_{{ $required_information_content->id}}"
                                                class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="">
                                                        <form action="#" class="col-md-12" method="post"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="row" style="width: 100%;">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="question_11">
                                                                            @if(Session::has('lang') && Session::get('lang') == 'bn')
                                                                            প্রাইমারি কালার @else Primary Color @endif <span
                                                                                style="color: red;">*</span> </label>
                                                                        <input name="question_11" id="question_11" type="text"
                                                                            class="form-control" readonly
                                                                            value="{{ $required_information_content->question_11 ?? "" }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="question_12">@if(Session::has('lang') && Session::get('lang') == 'bn')
                                                                            ভাষা @else Language @endif <span
                                                                                style="color: red;">*</span> </label>
                                                                        <input name="question_12" id="question_12" type="text"
                                                                            class="form-control" readonly
                                                                            value="{{ $required_information_content->question_12 ?? "" }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label
                                                                    for="">@if(Session::has('lang') && Session::get('lang') == 'bn')
                                                                    আপনার কন্টেন্ট থিমের বিবরন দিন @else Describe the theme of your
                                                                    content @endif <span style="color: red;">*</span> </label>
                                                                <textarea name="" id="" cols="30" rows="2" class="form-control"
                                                                    readonly onfocus="focused(this)"
                                                                    onfocusout="defocused(this)">{{ $required_information_content->question_6 }}</textarea>
                                                            </div>


                                                            <div class="form-group mt-3">
                                                                <label>@if(Session::has('lang') && Session::get('lang') == 'bn')
                                                                    কন্টেন্ট এর মাধ্যমে কোন অফার, বা ইভেন্ট প্রোমোট করতে চান কি?
                                                                @else Want to promote an offer, or event through content? @endif
                                                                    <span style="color: red;">*</span> </label>
                                                                <br>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="flexRadioDefault" readonly id="flexRadioDefault1" {{ $required_information_content->question_7 ? '' : 'checked'}} />
                                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                                        @if(Session::has('lang') && Session::get('lang') == 'bn') না
                                                                        @else No @endif </label>
                                                                </div>

                                                                <!-- Default checked radio -->
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="flexRadioDefault" readonly id="flexRadioDefault2" {{ $required_information_content->question_7 ? 'checked' : ''}} />
                                                                    <label class="form-check-label"
                                                                        for="flexRadioDefault2">@if(Session::has('lang') && Session::get('lang') == 'bn')
                                                                        হ্যাঁ @else Yes @endif </label>
                                                                </div>

                                                                <textarea
                                                                    class="form-control {{ $required_information_content->question_7 ? '' : 'd-none'}}"
                                                                    name="" readonly id="coip" cols="30" rows="2" required=""
                                                                    onfocus="focused(this)"
                                                                    onfocusout="defocused(this)">{{ $required_information_content->question_7 }}</textarea>
                                                            </div>


                                                            <div class="form-group mt-3">
                                                                <label for="question_8">
                                                                    @if(Session::has('lang') && Session::get('lang') == 'bn') অন্য পেজ
                                                                        বা কম্পিটিটর যাদের কন্টেন্ট ভালো লাগে, রেফারেন্স হিসেবে দিন।
                                                                    (লিংক) @else Mention other pages or competitors you like. (link)
                                                                    @endif </label>
                                                                <textarea name="question_8" id="question_8" class="form-control"
                                                                    readonly onfocus="focused(this)" onfocusout="defocused(this)"
                                                                    cols="30"
                                                                    rows="2">{{ $required_information_content->question_8 }}</textarea>
                                                            </div>


                                                            <div class="form-group mt-3">
                                                                <label for="question_9">
                                                                    @if(Session::has('lang') && Session::get('lang') == 'bn') কন্টেন্ট
                                                                    এর জন্য আপনার পণ্য বা সেবার ছবি দিন @else Provide images of your
                                                                    products or services for content @endif </label>

                                                                <div class="row">
                                                                    @php
                                                                        $RequiredInformation33 = json_decode($required_information_content->question_9);
                                                                    @endphp

                                                                    @foreach ($RequiredInformation33 as $key => $item)
                                                                        <div class="col-md-3 download">
                                                                            <img style="width: 100%;"
                                                                                src="{{ asset('clientContent/RequiredInformation/forContent/' . $item) }}"
                                                                                alt="">
                                                                            <a class="btn btn-primary btn-sm download-btn" href="#"
                                                                                onclick="event.preventDefault(); document.getElementById('download_Frm2{{$key + 15}}').submit();">
                                                                                @if(Session::has('lang') && Session::get('lang') == 'bn')
                                                                                ভাষা @else Language @endif Download</a>

                                                                            <form id="download_Frm2{{$key + 15}}"
                                                                                action="{{ route('superadmin.required.content.download') }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="pathName"
                                                                                    value="{{'clientContent/RequiredInformation/forContent/' . $item }}">
                                                                            </form>

                                                                        </div>
                                                                    @endforeach



                                                                </div>
                                                            </div>

                                                            <div class="form-group mt-3">
                                                                <label for="question_10">
                                                                    @if(Session::has('lang') && Session::get('lang') == 'bn') অন্য পেজ
                                                                        বা কম্পিটিটর যাদের কন্টেন্ট ভালো লাগে, রেফারেন্স হিসেবে দিন।
                                                                    (ছবি) @else Mention other pages or competitors you like. (photo)
                                                                    @endif </label>
                                                                <div class="row">
                                                                    @php
                                                                        $RequiredInformation2 = json_decode($required_information_content->question_10);
                                                                    @endphp

                                                                    @foreach ($RequiredInformation2 as $key => $item)
                                                                        <div class="col-md-3 download">
                                                                            <img style="width: 100%;"
                                                                                src="{{ asset('clientContent/RequiredInformation/forContent/' . $item) }}"
                                                                                alt="">
                                                                            <a class="btn btn-primary btn-sm download-btn" href="#"
                                                                                onclick="event.preventDefault(); document.getElementById('download_Frm3{{$key}}').submit();">Download</a>

                                                                            <form id="download_Frm3{{$key}}"
                                                                                action="{{ route('superadmin.required.content.download') }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="pathName"
                                                                                    value="{{'clientContent/RequiredInformation/forContent/' . $item }}">
                                                                            </form>

                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>



                                                            {{-- <div class="modal-footer pb-0"
                                                                style="padding-right: 0px; border-top: none;">
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </div> --}}

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            @else
                                <h1> @if(Session::has('lang') && Session::get('lang') == 'bn') একটি দোকান নির্বাচন করুন @else
                                Please Select a Store @endif </h1>
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

            $('.listdigital li').each(function () {
                $(this).attr('data-search-term', $(this).text().toLowerCase());
            });

            $('#live-search-box').on('keyup', function () {

                var searchTerm = $(this).val().toLowerCase();

                $('.listdigital li').each(function () {

                    if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
                        $(this).show();
                    } else {
                        $(this).hide();
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