@extends('admin.layouts.main')
@section('content')
    {{--theme customization style--}}
    <style>
        .avatar-upload {
            position: relative;
            /*max-width: 205px;*/
            /*margin: 20px auto;*/
        }

        .avatar-edit {
            position: absolute;
            /*right: 12px;*/
            margin-left: 135px;
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

        .hh-grayBox {
            background-color: #F8F8F8;
            margin-bottom: 20px;
            padding: 35px;
            margin-top: 20px;
        }

        .pt45 {
            padding-top: 45px;
        }

        .order-tracking {
            text-align: center;
            width: 25%;
            position: relative;
            display: block;
        }

        .order-tracking .is-complete {
            display: block;
            position: relative;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            border: 0px solid #AFAFAF;
            background-color: #f7be16;
            margin: 0 auto;
            transition: background 0.25s linear;
            -webkit-transition: background 0.25s linear;
            z-index: 2;
        }

        .order-tracking .is-complete:after {
            display: block;
            position: absolute;
            content: '';
            height: 14px;
            width: 7px;
            top: -2px;
            bottom: 0;
            left: 5px;
            margin: auto 0;
            border: 0px solid #AFAFAF;
            border-width: 0px 2px 2px 0;
            transform: rotate(45deg);
            opacity: 0;
        }

        .order-tracking.completed .is-complete {
            border-color: #27aa80;
            border-width: 0px;
            background-color: #27aa80;
        }

        .order-tracking.completed .is-complete:after {
            border-color: #fff;
            border-width: 0px 3px 3px 0;
            width: 7px;
            left: 11px;
            opacity: 1;
        }

        .order-tracking p {
            color: #A4A4A4;
            font-size: 16px;
            margin-top: 8px;
            margin-bottom: 0;
            line-height: 20px;
        }

        .order-tracking p span {
            font-size: 14px;
        }

        .order-tracking.completed p {
            color: #000;
        }

        .order-tracking::before {
            content: '';
            display: block;
            height: 3px;
            width: calc(100% - 40px);
            background-color: #f7be16;
            top: 13px;
            position: absolute;
            left: calc(-50% + 20px);
            z-index: 0;
        }

        .order-tracking:first-child:before {
            display: none;
        }

        .order-tracking.completed:before {
            background-color: #27aa80;
        }

        .chatbox2 {
            width: 100%;
            height: 650px;
            /*position: fixed;*/
            /*bottom: 90px;*/
            right: 0;
        }

        .chatbox2 .receive {
            display: flex;
            align-items: flex-start;
            width: fit-content;
            border: 1px solid gray;
            border-radius: 20px;
            background-color: gray;
            color: #fff;
        }

        .chatbox2 .send {
            display: flex;
            justify-content: flex-end;
            width: 100%;
            flex-direction: column;
            align-items: end;
        }

        .chatbox2 .send p {
            border-radius: 20px;
            background-color: green;
            color: #fff;
            width: fit-content;
            padding: 10px;
        }
    </style>
    <main class="main-content position-relative border-radius-lg">

        <!--addon nav bar component-->
        @include('admin.addon.share.addons-nav', ['theme'=>true])

        <div class="container-fluid mt-4" id="toplist">
            @if(isset($theme_customize) && $theme_customize=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')

                <!--header-->
                <div class="row">
                    <div class="col-md-6">
                        <h4>Theme Customize Request</h4>
                    </div>
                    <div class="col-md-6">
                        <!--<ul>-->
                        <!--    <li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
                        <!--    <li style="padding:0px;border:0px;"><a href="javascript:void(0)" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>-->
                        <!--</ul>-->
                    </div>
                </div>
                @php
                    $reqsss = DB::table('themecustomizes')->where('store_id', $store_id)->first();
                @endphp
                {{--theme customize chat section--}}
                @if(isset($reqsss))
                        <?php
                        $messages = DB::table('trickets')->where('token', $reqsss->token)->get();
                        ?>
                    <div class="row mt-5 productlist">
                        <div class="col-12">
                            <div class="card chatbox2">
                                <div class="card-header" style="background-color:black">
                                    <div class="row">
                                        <div class="col-md-6" style="color:#fff">Chat Id: {{$reqsss->token}}</div>
                                    </div>
                                </div>
                                <div class="card-body" style="height:100px;overflow-y:auto" id="messagetoken">

                                    {{--success messagage section--}}
                                    @if (Session::has('success_message'))
                                        <div class="alert alert-success">{{Session::get('success_message')}}</div>
                                    @endif
                                    <ul style="list-style: none;padding-left: 0px;display: flex;flex-direction: column;overflow-y: auto;">

                                        {{--messaging area section--}}
                                        @if(isset($messages) && count($messages)>0)
                                            @foreach($messages as $msg)
                                                @if($msg->sender=='admin')
                                                    @if(isset($msg->image))
                                                        <li class="send" style="border:0px !important">
                                                            <p style="background-color:transparent !important;border:0px !important">
                                                                <img
                                                                    src="{{URL::to('/')}}/assets/images/token/{{$msg->image}}"
                                                                    width="100">
                                                            </p>
                                                            <span>{{$msg->created_at}}</span>
                                                        </li>
                                                    @endif
                                                    @if(isset($msg->message))
                                                        <li class="send" style="border:0px !important">
                                                            <p>{{$msg->message}}</p>
                                                            <span>{{$msg->created_at}}</span>
                                                        </li>

                                                    @endif
                                                @else
                                                    @if(isset($msg->image))
                                                        <li class="receive"
                                                            style="background-color:transparent !important;border:0px !important">
                                                            <p style="background-color:transparent !important;border:0px !important">
                                                                <img
                                                                    src="{{URL::to('/')}}/assets/images/token/{{$msg->image}}"
                                                                    width="100">
                                                            </p>

                                                        </li>
                                                        <span>{{$msg->created_at}}</span>

                                                    @endif
                                                    @if(isset($msg->message))
                                                        <li class="receive">
                                                            {{$msg->message}}
                                                        </li>
                                                        <span style="padding:2px 10px">{{$msg->created_at}}</span>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                    <div class="d-flex mt-4 mb-4 justify-content-center">
                                    </div>
                                </div>
                                <div class="card-footer" style="border-top:1px solid gray">
                                    {{--message input field section--}}
                                    <form action="{{route('admin.sendmessage.token',$reqsss->token)}}"
                                          method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-1">
                                                @csrf
                                                <label for="inputimg">
                                                    <img id="blah" alt="Insert Image" style="width:112%;height:auto"
                                                         src="https://img.icons8.com/dotty/80/000000/add-image.png"/>
                                                </label>
                                                <input type="file"
                                                       onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                                       id="inputimg" name="image" style="display:none"/>
                                            </div>
                                            <div class="col-10">
                                                <textarea id="text" name="details" class="form-control"></textarea>
                                            </div>
                                            <div class="col-1" style="display:flex;align-items:center">
                                                <button type="submit" class="btn btn-primary">Send</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{--main section if theme customize are not create--}}
                    <div class="row mt-5 productlist">
                        <div class="col-12">
                            @if($view=="Active")
                                <div class="card">
                                    <div class="card-header">
                                    </div>
                                    <div class="card-body">
                                        {{--theme custome create input field secton--}}
                                        <form action="{{route('admin.savecustomizinfo')}}" method="post"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3 row">
                                                <label for="staticEmail" class="col-md-2 col-form-label">Select
                                                    Theme</label>
                                                <div class="col-md-4">
                                                    <select class="form-control" name="theme">
                                                        <option>Select</option>
                                                        {{--load option from template--}}
                                                            <?php
                                                            $themess = DB::table('templates')->get();
                                                            ?>
                                                        @if(isset($themess) && count($themess))
                                                            @foreach($themess as $key=>$themesss)
                                                                <option
                                                                    value="{{$themesss->id}}">{{$themesss->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error('name')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="staticEmail" class="col-md-2 col-form-label">Customize
                                                    Details</label>
                                                <div class="col-md-4">
                                                    <textarea class="form-control" name="details"></textarea>
                                                    @error('details')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 row ">
                                                <label for="staticEmail" class="col-md-2 col-form-label">Phone</label>
                                                <div class="col-md-4">
                                                    <input type="tel" name="phone" class="form-control">
                                                    @error('phone')
                                                    <p class="text-danger">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="position" class="col-md-2 col-form-label"></label>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-info"> Send Request
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="card">
                                    <div class="card-header"></div>
                                    <div class="card-body">
                                        <h4 class="text-center">If You Want to Customize Your Theme, Please Add
                                            Domain </h4>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif
        </div>
        @endif
    </main>
@endsection
