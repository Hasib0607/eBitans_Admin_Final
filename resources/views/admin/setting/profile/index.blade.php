@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" src="{{asset('admin/src/bootstrap-tagsinput.css')}}"/>
    <style>
        .bootstrap-tagsinput {
            width: 100%;
        }

        .bootstrap-tagsinput {
            background-color: #fff;
            /*border: 1px solid #ccc;*/
            /*box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);*/
            display: inline-block;
            padding: 4px 6px;
            color: #555;
            vertical-align: middle;
            border-radius: 4px;
            max-width: 100%;
            line-height: 22px;
            cursor: text;
        }

        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white;
        }

        .label-info {
            background-color: #5bc0de;
        }

        .label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }

        .bootstrap-tagsinput .tag [data-role="remove"] {
            margin-left: 8px;
            cursor: pointer;
        }

        .bootstrap-tagsinput .tag [data-role="remove"]::after {
            content: "x";
            padding: 0px 2px;
        }

        .bootstrap-tagsinput .tag [data-role="remove"] {
            cursor: pointer;
        }

        .card {
            border: 1px solid rgba(222, 226, 230, 0.7);
        }

        .card .card-body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            padding: .5rem 1.5rem 1.5rem 1.5rem;
        }

        .card .card-header {
            padding: .5rem 1.5rem .5rem 1.5rem;
            border-bottom: 1px solid rgba(222, 226, 230, 0.7);
        }

        .size {
            list-style-type: none;

        }

        .size li {
            float: left;
        }

        .bootstrap-tagsinput {
            margin: 0;
            width: 100%;
            padding: 0.5rem 0.75rem 0;
            font-size: 1rem;
            line-height: 1.25;
            transition: border-color 0.15s ease-in-out;

            &.has-focus {
                background-color: #fff;
                border-color: #5cb3fd;
            }

            .label-info {
                display: inline-block;
                background-color: #636c72;
                padding: 0 .4em .15em;
                border-radius: .25rem;
                margin-bottom: 0.4em;
            }

            input {
                margin-bottom: 0.5em;
            }
        }

        .bootstrap-tagsinput .tag [data-role="remove"]:after {
            content: '\00d7';
        }

        .avatar-upload {
            position: relative;
            max-width: 205px;
            margin: 20px auto;
        }

        .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }

        .avatar-edit input {
            display: none;
        }

        .avatar-edit label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all .2s ease-in-out;
        }

        .avatar-edit label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .avatar-edit label:after {
            content: "\f040";
            font-family: 'FontAwesome';
            color: #757575;
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
            text-align: center;
            margin: auto;
        }

        .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }

        .avatar-preview > div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
@endpush
@section('content')
    {{--main container--}}
    <main class="main-content position-relative h-100 border-radius-lg">

        <!--setting nav bar component-->
        @include('admin.setting.share.setting-nav', ['user_profile'=>true])

        {{--profile container--}}
        <section class="container content-main">
            <div class="row">

                {{--profile update form--}}
                <form action="{{route('admin.updateprofile')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf

                    <!--header-->
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            প্রোফাইল
                                        @else
                                            Profile
                                        @endif</h2>
                                </div>

                                <div class="col-md-6" style="text-align:right">
                                    <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button> -->
                                    <!-- <button class="btn btn-info rounded font-sm hover-up">Publich</button> -->
                                </div>
                            </div>
                        </div>

                        {{--profile card--}}
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>Basic</h4>
                                </div>
                                @if (Session::has('error_message'))
                                    <div class="alert alert-danger"
                                         style="color:#fff">{{Session::get('error_message')}}</div>
                                @endif
                                <div class="card-body">

                                    <div class="mb-4">

                                        <div class="avatar-upload">

                                            <div class="avatar-edit">
                                                <input type='file' id="imgInp" name="userimage"
                                                       accept=".png, .jpg, .jpeg" onchange="loadFile(event)"/>

                                                <label for="imgInp"></label>
                                            </div>
                                            <div class="avatar-preview" style="overflow:hidden">
                                                @if(isset($user))
                                                    @if(isset($user->image))
                                                        <img id="output" class="center"
                                                             src={{asset('assets/images/img/' . $user->image)}}
                                                             style="width:200px;"/>
                                                        <!--<div id="blah" style="background-image: url(https://admin.ebitans.com/assets/images/img/{{$user->image}});">-->
                                                    @else
                                                        <img id="output" class="center"
                                                             src="http://i.pravatar.cc/500?img=7"
                                                             style="width:200px;"/>
                                                        <!--<div id="blah" style="background-image: url(http://i.pravatar.cc/500?img=7);">-->
                                                    @endif
                                                @else
                                                    <img id="output" class="center"
                                                         src="http://i.pravatar.cc/500?img=7"
                                                         style="width:200px;"/>
                                                    <!--<div id="blah" style="background-image: url(http://i.pravatar.cc/500?img=7);">-->
                                                @endif

                                            </div>
                                        </div>
                                        <div class="divv" style="text-align:center">
                                            <p style="font-weight:bold">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    ব্যবহারকারীর ছবি
                                                @else
                                                    User Image
                                                @endif</p>
                                        </div>
                                        @error('logo')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                নাম
                                            @else
                                                Name
                                            @endif</label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="name" name="name" value="{{$user->name ?? old('name')}}">
                                        @error('name')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ফোন
                                            @else
                                                Phone
                                            @endif</label>
                                        <input type="tel" placeholder="Type here" class="form-control"
                                               id="phone" name="phone" value="{{$user->phone ?? old('phone')}}"
                                               readonly>
                                        @error('phone')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name"
                                               class="form-label">
                                            @if(Session::has('lang') && Session::get('lang')=='bn')
                                                ইমেইল
                                            @else
                                                Email
                                            @endif
                                        </label>
                                        <input type="email" placeholder="Type here" class="form-control"
                                               id="email" name="email" value="{{$user->email ?? old('email')}}">
                                        @error('email')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name"
                                               class="form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ঠিকানা
                                            @else
                                                Address
                                            @endif </label>
                                        <input type="text" placeholder="Type here" class="form-control"
                                               id="address" name="address"
                                               value="{{$user->address ?? old('address')}}">
                                        @error('address')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <!-- <label class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" value="">
                                        <span class="form-check-label"> Make a template </span>
                                    </label> -->

                                </div>
                            </div> <!-- card end// -->
                            <button type="submit"
                                    class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn')
                                    আপডেট
                                @else
                                    Update
                                @endif
                            </button>

                        </div>
                        <!--</form>-->
                    </div>
            </form>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{asset('admin/src/bootstrap-tagsinput.js')}}"></script>
    <script src="{{asset('admin/src/bootstrap-tagsinput-angular.js')}}"></script>
    <script>
        var loadFile = function (event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
    <script>
        $(document).ready(function () {

            $('input[name="input"]').tagsinput({
                trimValue: true,
                confirmKeys: [13, 44, 32],
                focusClass: 'my-focus-class'
            });

            $('.bootstrap-tagsinput input').on('focus', function () {
                $(this).closest('.bootstrap-tagsinput').addClass('has-focus');
            }).on('blur', function () {
                $(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
            });

        });
    </script>
@endpush
