@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" src="{{ asset('admin/src/bootstrap-tagsinput.css') }}"/>
    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

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

        .badge {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out
        }

        .badge-primary {
            color: #fff;
            background-color: #007bff
        }

        a.badge-primary:focus,
        a.badge-primary:hover {
            color: #fff;
            background-color: #0062cc
        }

        a.badge-primary.focus,
        a.badge-primary:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .5)
        }

        .badge-secondary {
            color: #fff;
            background-color: #6c757d
        }

        a.badge-secondary:focus,
        a.badge-secondary:hover {
            color: #fff;
            background-color: #545b62
        }

        a.badge-secondary.focus,
        a.badge-secondary:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(108, 117, 125, .5)
        }

        .badge-success {
            color: #fff;
            background-color: #28a745
        }

        a.badge-success:focus,
        a.badge-success:hover {
            color: #fff;
            background-color: #1e7e34
        }

        a.badge-success.focus,
        a.badge-success:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(40, 167, 69, .5)
        }

        .badge-info {
            color: #fff;
            background-color: #17a2b8
        }

        a.badge-info:focus,
        a.badge-info:hover {
            color: #fff;
            background-color: #117a8b
        }

        a.badge-info.focus,
        a.badge-info:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(23, 162, 184, .5)
        }

        .badge-warning {
            color: #212529;
            background-color: #ffc107
        }

        a.badge-warning:focus,
        a.badge-warning:hover {
            color: #212529;
            background-color: #d39e00
        }

        a.badge-warning.focus,
        a.badge-warning:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(255, 193, 7, .5)
        }

        .badge-danger {
            color: #fff;
            background-color: #dc3545
        }

        a.badge-danger:focus,
        a.badge-danger:hover {
            color: #fff;
            background-color: #bd2130
        }

        a.badge-danger.focus,
        a.badge-danger:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(220, 53, 69, .5)
        }

        .badge-light {
            color: #212529;
            background-color: #f8f9fa
        }

        a.badge-light:focus,
        a.badge-light:hover {
            color: #212529;
            background-color: #dae0e5
        }

        a.badge-light.focus,
        a.badge-light:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(248, 249, 250, .5)
        }

        .badge-dark {
            color: #fff;
            background-color: #343a40
        }

        a.badge-dark:focus,
        a.badge-dark:hover {
            color: #fff;
            background-color: #1d2124
        }

        a.badge-dark.focus,
        a.badge-dark:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(52, 58, 64, .5)
        }

        .jumbotron {
            padding: 2rem 1rem;
            margin-bottom: 2rem;
            background-color: #e9ecef;
            border-radius: .3rem
        }

        @media (min-width: 576px) {
            .jumbotron {
                padding: 4rem 2rem
            }
        }

        .jumbotron-fluid {
            padding-right: 0;
            padding-left: 0;
            border-radius: 0
        }

        .alert {
            position: relative;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem
        }

    </style>
@endpush
@section('content')
    <!-- The Modal -->
    <div class="modal fade" id="myDomainModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.savedomain') }}" method="post">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Connect Domain</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="input-group input-group-outline my-3">
                            <label class="form-label">Domain Name</label>
                            <input name="domain" id="domainInput" type="text"
                                   class="form-control">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="myDomainLoadingModal">
        <div class="modal-dialog"
             style="display: flex;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);width: 50%;height: 50%; border-radius: 10px;background: #fff;justify-content: center;align-items: center;">
            <div class="modal-content" style="border: none;">
                <div style=" display: flex;justify-content: center;align-items: center;flex-direction: column;">
                    <svg aria-hidden="true" role="status" style="width: 50px;margin-bottom: 15px;color: #f1593a;"
                         class="inline w-14 h-14 me-3 text-['#f1593a'] animate-spin" viewBox="0 0 100 101"
                         fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="#E5E7EB"/>
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentColor"/>
                    </svg>
                    Processing...
                </div>
            </div>
        </div>
    </div>

    {{--Domain Main Section--}}
    <main class="main-content position-relative h-100 border-radius-lg">
        <!--setting nav bar component-->
        @include('admin.setting.share.setting-nav', ['connect_domain'=>true])

        {{--Sub Main Section--}}
        <section class="container content-main">
            <div class="row">
                <div class="row">
                    {{--header section--}}
                    <div class="col-lg-9 mt-4 mb-4">
                        <div class="content-header row">
                            <div class="col-md-6">
                                <!--<h2 class="content-title">Settings </h2>-->
                            </div>

                            <div class="col-md-6" style="text-align:right">
                                <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button> -->
                                <!-- <button class="btn btn-info rounded font-sm hover-up">Publich</button> -->
                            </div>
                        </div>
                    </div>

                    {{--domain connection andlist card section--}}
                    <div class="col-lg-6">

                        {{--domain connection section--}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        একটি ডোমেন সংযুক্ত
                                        করুন
                                    @else
                                        Connect a Domain
                                    @endif
                                </h4>
                            </div>
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"
                                     style="color:#fff">{{ Session::get('error_message') }}</div>
                            @endif
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-12">

                                        <p>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আপনার
                                                দোকানের জন্য নিখুঁত ডোমেন সুরক্ষিত করুন যা গ্রাহকরা বিশ্বাস করতে পারেন
                                                এবং
                                                সহজেই খুঁজে পেতে পারেন। ebitans থেকে একটি নতুন ডোমেন কিনুন, অথবা Google
                                                ডোমেইন বা Godaddy-এর মতো তৃতীয় পক্ষ থেকে আপনি ইতিমধ্যেই কিনেছেন এমন
                                                একটি
                                                ডোমেন সংযুক্ত করুন৷
                                            @else
                                                Secure the perfect domain for your store that customers can trust and
                                                find
                                                easily. Buy a new domain from ebitans, or connect a domain you already
                                                purchase from a third -party like google domains or Godaddy.
                                            @endif
                                        </p>

                                        {{--domain connect modal trigger button--}}
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                           data-bs-target="#myDomainModal">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                বিদ্যমান
                                                ডোমেন সংযোগ করুন
                                            @else
                                                Connect existing domain
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- card end// -->

                        {{--domain list section--}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3>Domain</h3>
                            </div>
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger" style="color:#fff">{{ Session::get('error_message') }}
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <ul style="list-style:none;padding-left:0rem !important;">

                                            {{--domain list--}}
                                            @if (isset($domain) && count($domain) > 0)
                                                @foreach ($domain as $key => $dm)
                                                    <li style="padding:10px;border-bottom:1px solid gray">
                                                        @if ($dm->status == 'Active')
                                                            <input type="radio" name="domain" class="selectdomain"
                                                                   value="{{ $dm->id }}"
                                                                   @if ($store->url == $dm->name) checked @endif>
                                                        @else
                                                            &nbsp;&nbsp;
                                                        @endif &nbsp;&nbsp;&nbsp;{{ $dm->name }}
                                                        &nbsp;<span
                                                            @if ($dm->status == 'Active') class="badge badge-primary"
                                                            @elseif($dm->status == 'Requested') class="badge badge-danger"
                                                            @elseif($dm->status == 'Processing') class="badge badge-secondary"
                                                            @else class="badge badge-danger" @endif>
                                                                {{ $dm->status }}
                                                            </span>
                                                        @if($dm->status == 'Processing')
                                                            <a href="javascript:void(0);"
                                                               onclick="confirmDomainConnection('{{ route('admin.domain.connect.request', $dm->id) }}')"
                                                               class="btn btn-primary"
                                                               style="margin-bottom: 0; margin-left: 5px;">
                                                                Connect Domain
                                                            </a>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--dns section--}}
                    <div class="col-lg-6">
                        {{--dns name server card section--}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ডিএনএস নেম সার্ভার
                                    @else
                                        DNS Name Server
                                    @endif
                                </h4>
                            </div>
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"
                                     style="color:#fff">{{ Session::get('error_message') }}</div>
                            @endif
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আপনার
                                                <strong>Name Server 1:</strong> ns1.ebitans.com
                                                <br>
                                                <strong>Name Server 2:</strong> ns2.ebitans.com
                                                <br>
                                            @else

                                                <strong>Name Server 1:</strong> ns1.ebitans.com
                                                <br>
                                                <strong>Name Server 2:</strong> ns2.ebitans.com
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--dns name server changes details section--}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        How to Change DNS Name Server
                                    @else
                                        How to Change DNS Name Server
                                    @endif
                                </h4>
                            </div>
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"
                                     style="color:#fff">{{ Session::get('error_message') }}</div>
                            @endif

                            <!--details-->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12" style="line-height: 35px;">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            আপনার
                                            <strong class="mt-3">1st Step:</strong> Login to your domain user panel
                                            <br>
                                            <strong class="mt-3">2nd Step:</strong> Find Chage 'Name Server/ Manage Name
                                            Server
                                            <br>
                                            <strong class="mt-3">3rd Step:</strong> Type 'ns1.ebitans.com',
                                            'ns2.ebitans.com' as name server in input fields
                                            <br>
                                            <strong class="mt-3">4th Step:</strong> Press Update name server button.
                                            (with in 24 hours your name server will be updated)
                                            <br>
                                            <strong>N.B:</strong> <br> You can also ask to your domain provider to
                                            update your name server as well as you can google your domain provider
                                            company to
                                            chage your name serve.
                                        @else
                                            <strong class="mt-3">1st Step:</strong> Login to your domain user panel
                                            <br>
                                            <strong class="mt-3">2nd Step:</strong> Find Chage 'Name Server/ Manage Name
                                            Server
                                            <br>
                                            <strong class="mt-3">3rd Step:</strong> Type 'ns1.ebitans.com',
                                            'ns2.ebitans.com' as name server in input fields
                                            <br>
                                            <strong class="mt-3">4th Step:</strong> Press Update name server button.
                                            (with in 24 hours your name server will be updated)
                                            <br><br>
                                            <strong>N.B:</strong> <br> You can also ask to your domain provider to
                                            update your name server as well as you can google your domain provider
                                            company to chage your name serve.
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('admin/src/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('admin/src/bootstrap-tagsinput-angular.js') }}"></script>

    {{--change active domain--}}
    <script>
        $(document).ready(function () {
            $(".selectdomain").on("click", function () {
                $url = "/changedomain";
                var value = $(this).val();
                // console.log(value);
                $.get($url, {
                    value: value
                }, function (result) {
                    // console.log(result.data);
                    if (result.data == 0) {
                        toastr.error(result.message, 'Success');
                    } else {
                        toastr.success(result.message, 'Success');
                    }

                });
            });
        });

    </script>
    <script>
        var citynames = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name')
            , queryTokenizer: Bloodhound.tokenizers.whitespace
            , prefetch: {
                url: 'assets/citynames.json'
                , filter: function (list) {
                    return $.map(list, function (cityname) {
                        return {
                            name: cityname
                        };
                    });
                }
            }
        });
        citynames.initialize();

        $('input').tagsinput({
            // debugger;
            typeaheadjs: {
                name: 'citynames'
                , displayKey: 'name'
                , valueKey: 'name'
                , source: citynames.ttAdapter()
            }
        });

    </script>
    <script>
        var citynames = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name')
            , queryTokenizer: Bloodhound.tokenizers.whitespace
            , prefetch: {
                url: 'assets/citynames.json'
                , filter: function (list) {
                    return $.map(list, function (cityname) {
                        return {
                            name: cityname
                        };
                    });
                }
            }
        });
        citynames.initialize();

        $('input').seoinput({
            typeaheadjs: {
                name: 'citynames'
                , displayKey: 'name'
                , valueKey: 'name'
                , source: citynames.ttAdapter()
            }
        });

    </script>
    <script>
        $(document).ready(function () {
            $('#colorrss').hide();
            $('#unittss').hide();
            $('#sizess').hide();
            $('#attributes').on('change', function () {
                var l = this.value;
                if (l == 'none') {
                    $('#colorrss').hide();
                    $('#unittss').hide();
                    $('#sizess').hide();
                } else if (l == 'color') {
                    $('#colorrss').show();
                    $('#unittss').hide();
                    $('#sizess').hide();
                } else if (l == 'unit') {
                    $('#colorrss').hide();
                    $('#unittss').show();
                    $('#sizess').hide();
                } else {
                    $('#colorrss').hide();
                    $('#unittss').hide();
                    $('#sizess').show();
                }
            });
        })

    </script>
    <script>
        $(document).ready(function () {

            $('input[name="input"]').tagsinput({
                trimValue: true
                , confirmKeys: [13, 44, 32]
                , focusClass: 'my-focus-class'
            });

            $('.bootstrap-tagsinput input').on('focus', function () {
                $(this).closest('.bootstrap-tagsinput').addClass('has-focus');
            }).on('blur', function () {
                $(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
            });

        });


        $("#officers-table").on('click', '.remove-officer-button', function (e) {
            var whichtr = $(this).closest("tr");

            // alert('worked'); // Alert does not work
            whichtr.remove();
        });
        $("#officers-table1").on('click', '.remove-officer-button1', function (e) {
            var whichtr = $(this).closest("tr");

            // alert('worked'); // Alert does not work
            whichtr.remove();
        });

        function addUnit() {
            var col = $('#new1').html();
            $("#officers-table1 tbody").append('<tr>' + col + '</tr>');
        }

        function addSize() {
            var col = $('#new2').html();
            $("#officers-table2 tbody").append('<tr>' + col + '</tr>');
        }

        $("#officers-table2").on('click', '.remove-officer-button2', function (e) {
            var whichtr = $(this).closest("tr");

            // alert('worked'); // Alert does not work
            whichtr.remove();
        });

    </script>
    <script>
        jQuery('select[name="category"]').on('change', function () {
            debugger;
            var val = $(this).val();
            console.log(val);
            $('#subcategory').empty();
            var catid = $('select[name="category"]').val();
            $.get('/getsubcat', {
                catid: catid
            }, function (data) {
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('#subcategory').append(
                        '<option value="">select</option><option value="' + data[i].id + '">' + data[i]
                            .name + '</option>'
                    );
                }
            });
        });

    </script>


    <script>
        function confirmDomainConnection(url) {
            Swal.fire({
                title: 'Are you sure?',
                html: `Do you want to connect this domain? Before connecting make sure you update the name server that we have provided <br> <strong>Name Server 1:</strong> ns1.ebitans.com
                    <br>
                    <strong>Name Server 2:</strong> ns2.ebitans.com`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, connect it!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    openModal("myDomainLoadingModal")
                    window.location.href = url;
                }
            });
        }


        // Open the modal
        function openModal(id) {
            const modalElement = document.getElementById(id); // Find the modal by ID
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement); // Initialize a new modal instance
                modal.show();
            }
        }

        // Close the modal
        function closeModal(id) {
            const modalElement = document.getElementById(id); // Find the modal by ID
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement); // Get the existing modal instance
                if (modal) {
                    modal.hide();
                }
            }
        }

        document.getElementById("domainInput").addEventListener("input", function () {
            this.value = this.value.toLowerCase();
        });


    </script>
@endpush

