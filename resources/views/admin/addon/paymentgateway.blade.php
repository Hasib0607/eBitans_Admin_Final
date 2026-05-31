@extends('admin.layouts.main')
@section('content')
    {{--payment gateway page style--}}
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

    </style>

    {{--main section--}}
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!--addon nav bar component-->
        @include('admin.addon.share.addons-nav', ["payment_gateway"=>true])

        {{--payment gateway section--}}
        <div class="container-fluid mt-4" id="toplist">

            {{--payment gateway header section--}}
            <div class="row">
                <div class="col-md-6">
                    <h4>Payment Gateway Setup</h4>
                </div>
                <div class="col-md-6">
                </div>
            </div>

            {{--payment gateway card section--}}
            <div class="row mt-5 productlist">
                <div class="col-12">

                    {{--if having payment gateway setup--}}
                    @if($view=="Active")
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-4 mx-auto">

                                        {{--select payment method input field--}}
                                        <div class="form-group">
                                            <label for="">Payment Company</label>
                                            <select class="form-control" name="payment_company" id="payment_company">
                                                <option value="0">Select</option>
                                                @foreach ($req as $item)
                                                    <option
                                                        value="{{$item->payment_company ?? ""}}">{{$item->payment_company ?? ""}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{--other input field on depend of payment method--}}
                                        @foreach ($req as $item)
                                            @switch($item->payment_company)
                                                {{--payment method is Nagad--}}
                                                @case('Nagad')

                                                    <form action="{{route('admin.savepaymentinfo')}}" method="post">
                                                        @csrf
                                                        <div class="nagad">
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                            <div class="form-group">
                                                                <label for="">App Key</label>
                                                                <input type="text" name="app_key"
                                                                       class="form-control"
                                                                       value="{{$item->app_key ?? ""}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">App Secret</label>
                                                                <input type="text" name="app_secret"
                                                                       class="form-control"
                                                                       value="{{$item->app_secret ?? ""}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">App Key</label>
                                                                <input type="text" name="app_key"
                                                                       class="form-control"
                                                                       value="{{$item->app_key ?? ""}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">App Secret</label>
                                                                <input type="text" name="app_secret"
                                                                       class="form-control"
                                                                       value="{{$item->app_secret ?? ""}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">API Username</label>
                                                                <input type="text" name="api_username"
                                                                       class="form-control"
                                                                       value="{{$item->api_username ?? ""}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">API Password</label>
                                                                <input type="text" name="api_password"
                                                                       class="form-control"
                                                                       value="{{$item->api_password ?? ""}}">
                                                            </div>
                                                            <div class="form-group my-4">
                                                                <button type="submit" class="btn btn-primary">Save
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    @break
                                                    {{--payment method is Bkash--}}
                                                @case('bKash')

                                                    <form action="{{route('admin.savepaymentinfo')}}" method="post">
                                                        @csrf
                                                        <div class="bkash">
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                            <div class="form-group">
                                                                <label for="">App Key</label>
                                                                <input type="text" name="app_key" class="form-control"
                                                                       value="{{$item->app_key ?? ""}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">App Secret</label>
                                                                <input type="text" name="app_secret"
                                                                       class="form-control"
                                                                       value="{{$item->app_secret ?? ""}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">API Username</label>
                                                                <input type="text" name="api_username"
                                                                       class="form-control"
                                                                       value="{{$item->api_username ?? ""}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">API Password</label>
                                                                <input type="text" name="api_password"
                                                                       class="form-control"
                                                                       value="{{$item->api_password ?? ""}}">
                                                            </div>
                                                            <div class="form-group my-4">
                                                                <button type="submit" class="btn btn-primary">Save
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    @break
                                                    {{--payment method is SSL--}}
                                                @case('SSL')

                                                    <form action="{{route('admin.savepaymentinfo')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <div class="ssl">
                                                            <div class="form-group">
                                                                <label for="">Store Id</label>
                                                                <input type="text" name="store_id"
                                                                       class="form-control"
                                                                       value="{{$item->ssl_store_id ?? ""}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Store Password</label>
                                                                <input type="text" name="store_password"
                                                                       class="form-control"
                                                                       value="{{$item->ssl_store_password ?? ""}}">
                                                            </div>
                                                            <div class="form-group my-4">
                                                                <button type="submit" class="btn btn-primary">Save
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    @break

                                                @default
                                            @endswitch

                                        @endforeach

                                    </div>

                                </div>
                            </div>
                        </div>

                        {{--not having payment gateway setup--}}
                    @else
                        <div class="card">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h4 class="text-center">You Don't Have Any Payment Gateway Setup </h4>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')

    {{--selecte payment method operations--}}
    <script>
        $(".ssl").hide();
        $(".bkash").hide();
        $(".nagad").hide();

        $("#payment_company").on('change', function () {
            const value = $(this).find(":selected").val();
            if (value === 'SSL') {
                $(".ssl").show();
                $(".bkash").hide();
                $(".nagad").hide();
            } else if (value === 'bKash') {
                $(".nagad").hide();
                $(".bkash").show();
                $(".ssl").hide();
            } else if (value === 'Nagad') {
                $(".nagad").show();
                $(".bkash").hide();
                $(".ssl").hide();
            } else {
                $(".nagad").hide();
                $(".ssl").hide();
                $(".bkash").hide();
            }
        })
    </script>
@endpush
