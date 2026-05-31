@extends('admin.layouts.main')
@push('styles')
    {{--design style--}}
    <style>
        .themes .card-title {
            font-weight: 300;
            font-size: 13px;
            /*text-shadow: 0 0 2px #000;*/
            border-top-right-radius: 67.5px;
            background: #f1593a;
            color: #fff;
            padding: 6px 19px;
            margin-bottom: 14px;
        }

        .themes .product-card .card {
            margin: 20px;
            overflow: hidden;
        }

        .themes .product-card .card .card-content {
            padding: 5px;
        }

        .themes .product-card .card .price {
            width: 70px;
            height: 70px;
            font-weight: 600;
            font-size: 1.45rem;
            line-height: 70px;
            margin: 10px;
            position: absolute;
            top: 0;
            letter-spacing: 0;
        }

        .themes .product-card ul.card-action-buttons {
            /*margin: -24px 4px 0 0;*/
            text-align: right;
        }

        .themes .product-card ul.card-action-buttons li {
            display: inline-block;
            padding-left: 7px;
        }

        .themes .product-card ul.card-action-buttons li > a > i {
            color: #4a4a4a;
        }

        .themes .product-card ul.card-action-buttons li a:hover {
            background-color: #f1593a;
            color: #fff;
        }

        .themes .product-card ul.card-action-buttons li a:hover > a > i {
            color: #fff !important;
        }

        .themes .product {
            width: 20%;
            padding: 10px;
        }

        .themes .product .card {
            margin: 0;
        }

        .themes .product .card .card-content {
            padding: 5px 10px;
        }

        .themeactive {
            background-color: #f1593a;
            color: #fff;
        }

        .themeactive > a > i {
            color: #fff !important;
        }

        @media only screen and (max-width: 500px) {
            .container1 .card {
                width: 95% !important;
            }
        }

        @media only screen and (max-width: 320px) {
            .themes .card-title {
                font-size: 10px;
            }
        }

        .container1 .card {
            position: relative;
            width: 102%;
            height: 300px;
            margin: 30px 0px;
            overflow: hidden;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            /*display: flex;*/
            /*justify-content: center;*/
            /*align-items: center;*/
        }

        .container1 .card .content {
            position: absolute;
            bottom: -160px;
            width: 100%;
            height: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            flex-direction: column;
            /*background-color: #474747;*/
            background-color: rgba(0, 0, 0, 0.7);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            box-shadow: 0 -10px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            transition: bottom 0.5s;
            transition-delay: 0.65s;
        }

        .container1 .card:hover .content {
            bottom: 0;
            transition-delay: 0s;
        }

        .container1 .card .content .contentBx h3 {
            text-transform: uppercase;
            color: #f1593a;
            letter-spacing: 2px;
            font-weight: 500;
            font-size: 16px;
            text-align: center;
            margin: 20px 0 15px;
            line-height: 1.1em;
            transition: 0.5s;
            transition-delay: 0.6s;
            opacity: 0;
            transform: translateY(-20px);
            padding: 5px;
        }

        .container1 .card:hover .content .contentBx h3 {
            opacity: 1;
            transform: translateY(0);
        }

        .container1 .card .content .contentBx h3 span {
            font-size: 12px;
            font-weight: 300;
            text-transform: initial;
        }

        .container1 .card .content .sci {
            position: relative;
            bottom: 10px;
            display: flex;
        }

        .container1 .card .content .sci li {
            list-style: none;
            margin: 0 10px;
            transform: translateY(40px);
            transition: 0.5s;
            opacity: 0;
            transition-delay: calc(0.2s * var(--i));
        }

        .container1 .card:hover .content .sci li {
            transform: translateY(0);
            opacity: 1;
            color: white;
        }

        .container1 .card .content .sci li a {
            color: #f1593a;
            font-size: 24px;
        }

        .container1 .card .content .sci li a:hover i {
            color: #fff !important;
        }

        .badge-overlay {
            position: absolute;
            left: 0%;
            top: 0px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 100;
            -webkit-transition: width 1s ease, height 1s ease;
            -moz-transition: width 1s ease, height 1s ease;
            -o-transition: width 1s ease, height 1s ease;
            transition: width 0.4s ease, height 0.4s ease
        }

        /* ================== Badge CSS ========================*/
        .badge {
            margin: 0;
            padding: 0;
            color: white;
            padding: 10px 10px;
            font-size: 15px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            line-height: normal;
            text-transform: uppercase;
            background: #ed1b24;
        }

        .badge::before, .badge::after {
            content: '';
            position: absolute;
            top: 0;
            margin: 0 -1px;
            width: 100%;
            height: 100%;
            background: inherit;
            min-width: 55px
        }

        .badge::before {
            right: 100%
        }

        .badge::after {
            left: 100%
        }

        .top-right {
            position: absolute;
            top: 0;
            right: 0;
            -ms-transform: translateX(30%) translateY(0%) rotate(45deg);
            -webkit-transform: translateX(30%) translateY(0%) rotate(45deg);
            transform: translateX(30%) translateY(0%) rotate(45deg);
            -ms-transform-origin: top left;
            -webkit-transform-origin: top left;
            transform-origin: top left;
        }

        .badge.red {
            background: #ed1b24;
        }
    </style>
@endpush
@section('content')

    {{--template main section--}}
    <main class="main-content position-relative border-radius-lg">

        {{--navigations section--}}
        @include('admin.design.share.designs-nav', ['theme'=>true])

        {{--template main container section--}}
        <div class="container-fluid mt-4" id="toplist">

            {{--template header--}}
            <div class="row">
                <div class="col-md-6">
                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn')
                            সব থিম
                        @else
                            All Themes
                        @endif</h4>
                </div>
            </div>

            {{--template main card--}}
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">

                        {{--search section--}}
                        <div class="card-header">
                            <form action="{{route('admin.searchtheme')}}" method="get">
                                <div class="d-flex justify-content-end">
                                    <div style="padding-right:5px;">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                aria-label="Dollar amount (with dot and two decimal places)"
                                                id="taskfilter"
                                                name="keyword">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                    class="fa fa-search"></i></span>
                                        </div>
                                    </div>
                                    <div style="padding-left:0px;">
                                        <button type="submit"
                                                class="btn btn-primary filterbuttonss">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                সাবমিট
                                            @else
                                                Submit
                                            @endif</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{--template view section--}}
                        <div class="card-body d-flex flex-wrap flex-row justify-content-between"
                             style="padding-right: 5px">
                            <div class="row" style="width:100%">
                                @foreach($designed_templates as $key=> $template)
                                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12 themes'>
                                        <div class="container1">
                                            <div class="card">
                                                <div class="imgBx">
                                                    <img
                                                        src="{{URL::to('/')}}/assets/images/template/{{$template->feature_image}}"
                                                        alt="" width="100%" height="300"
                                                    />
                                                </div>

                                                {{--checking is template is active--}}
                                                @isset($template->design_id)
                                                    <div class="badge-overlay">
                                                        <span class="top-right badge red">Active</span>
                                                    </div>
                                                @endisset
                                                <div class="content">
                                                    <div class="contentBx">
                                                        <h3>{{$template->name}}
                                                            <br/><span>{{$template->short_description}}</span>
                                                        </h3>
                                                    </div>
                                                    <ul class="sci">
                                                        <li style="--i: 1;padding:0px;border:none;">
                                                            <a @if(isset($template->liveurl)) href="{{$template->liveurl}}"
                                                               @else href="{{route('admin.design.theme.view',$template->id)}}"
                                                               @endif class="btn-floating waves-effect waves-light red accent-2 "
                                                               target="_blank"
                                                               style="padding:5px 10px;border:1px solid #f1593a">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li style="--i: 2;padding:0px;border:none;">
                                                            @if(isset($headerSetting->theme_lock) && $headerSetting->theme_lock == 1)
                                                                <a href="#"
                                                                   onclick="showAlert(event)"
                                                                   class="themeactive btn-floating waves-effect waves-light text-light"
                                                                   style="padding:5px 11px;border:1px solid #f1593a">
                                                                    <i class="fa fa-check" style="color:#fff"></i>
                                                                </a>
                                                            @else
                                                                <a href="{{route('admin.design.theme.active',$template->id)}}"
                                                                   class="themeactive btn-floating waves-effect waves-light text-light"
                                                                   style="padding:5px 11px;border:1px solid #f1593a">
                                                                    <i class="fa fa-check" style="color:#fff"></i>
                                                                </a>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script !src="">
        const showAlert = (e) => {
            e.preventDefault();

            swal.fire({
                "title": "Warning",
                "text": "To Active this Theme Please Update Theme Lock Setting",
                "type": "warning",
            })
        }

    </script>
@endpush
