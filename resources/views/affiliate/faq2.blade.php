@extends('affiliate.layouts.main')
@push('styles')
    <style>
        .video-container {
            position: relative;
            height: 450px;
            overflow: hidden;
            max-width: 100%;
            width: 100%;
            background: #000;
            margin: 0 auto;
        }

        @media only screen and (min-width: 992px) {
            .video-container {
                width: 60%;
            }
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
@endpush

@section('content')

    <div class="card m-4 p-4">
        <h3>Welcome to eBitans Affiliate Marketing</h3>
        <p>Please unlock all of the FAQs, and read all carefully. You can't go to the next page without unlocking
            it.</p>

        <div class="my-5">

            @php($i = $countStart ?? 0)
            @if(count($faqs) > 0)
                @foreach($faqs as $faq)
                    <div class="p-2 my-2" style="box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.05);">
                        <div class="row">
                            <div class="col-10">
                                <p class="fw-bolder">
                                    {{ ++$i }} - {{ $faq->question }}
                                </p>
                            </div>
                            <div class="col-2" style="text-align:right">
                                <a href="javascript:void(0)" id="attrishow{{$faq->id}}"><i class="fa fa-arrow-down"></i></a>
                                <a href="javascript:void(0)" id="attrihide{{$faq->id}}"><i
                                        class="fa fa-arrow-up"></i></a>
                            </div>
                        </div>
                        <div class="row px-3" id="attri-div{{$faq->id}}">
                            {{ $faq->answer ?? "" }}
                            @if(isset($faq->video_link) && !empty($faq->video_link))
                                <div class="video-container mt-4">
                                    <iframe src="{{ $faq->video_link }}" width="50%" frameborder="0"
                                            allowfullscreen></iframe>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="text-center mt-5">
        <a id="next" class="btn btn-outline-secondary m-1 filterbuttonss" type="submit"
           href="{{route('affiliate.faq')}}">
            @if (Session::has('lang') && Session::get('lang') == 'bn')
                Back
            @else
                Back
            @endif
        </a>

        <a id="next" class="btn btn-danger m-1 filterbuttonss" type="submit" href="{{route('affiliate.exams.rules')}}">
            @if (Session::has('lang') && Session::get('lang') == 'bn')
                Next
            @else
                Next
            @endif
        </a>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            @if(count($faqs) > 0)
            @foreach($faqs as $faq)
            $('#attrihide{{ $faq->id }}').hide();
            $('#attri-div{{ $faq->id }}').hide();

            $('#attrishow{{ $faq->id }}').on('click', function () {
                $('#attri-div{{ $faq->id }}').show();
                $('#attrihide{{ $faq->id }}').show();
                $('#attrishow{{ $faq->id }}').hide();
            });
            $('#attrihide{{ $faq->id }}').on('click', function () {
                $('#attri-div{{ $faq->id }}').hide();
                $('#attrihide{{ $faq->id }}').hide();
                $('#attrishow{{ $faq->id }}').show();
            });
            @endforeach
            @endif

            // $('#attrihide').hide();
            // $('#attri-div').hide();
            //
            // $('#attrishow').on('click', function () {
            //     $('#attri-div').show();
            //     $('#attrihide').show();
            //     $('#attrishow').hide();
            // });
            // $('#attrihide').on('click', function () {
            //     $('#attri-div').hide();
            //     $('#attrihide').hide();
            //     $('#attrishow').show();
            // });
            //
            // $('#attrihide2').hide();
            // $('#attri-div2').hide();
            //
            // $('#attrishow2').on('click', function () {
            //     $('#attri-div2').show();
            //     $('#attrihide2').show();
            //     $('#attrishow2').hide();
            // });
            // $('#attrihide2').on('click', function () {
            //     $('#attri-div2').hide();
            //     $('#attrihide2').hide();
            //     $('#attrishow2').show();
            // });
            //
            // $('#attrihide3').hide();
            // $('#attri-div3').hide();
            //
            // $('#attrishow3').on('click', function () {
            //     $('#attri-div3').show();
            //     $('#attrihide3').show();
            //     $('#attrishow3').hide();
            // });
            // $('#attrihide3').on('click', function () {
            //     $('#attri-div3').hide();
            //     $('#attrihide3').hide();
            //     $('#attrishow3').show();
            // });
            //
            // $('#attrihide4').hide();
            // $('#attri-div4').hide();
            //
            // $('#attrishow4').on('click', function () {
            //     $('#attri-div4').show();
            //     $('#attrihide4').show();
            //     $('#attrishow4').hide();
            // });
            // $('#attrihide4').on('click', function () {
            //     $('#attri-div4').hide();
            //     $('#attrihide4').hide();
            //     $('#attrishow4').show();
            // });
            //
            // $('#attrihide5').hide();
            // $('#attri-div5').hide();
            //
            // $('#attrishow5').on('click', function () {
            //     $('#attri-div5').show();
            //     $('#attrihide5').show();
            //     $('#attrishow5').hide();
            // });
            // $('#attrihide5').on('click', function () {
            //     $('#attri-div5').hide();
            //     $('#attrihide5').hide();
            //     $('#attrishow5').show();
            // });
            //
            // $('#attrihide6').hide();
            // $('#attri-div6').hide();
            //
            // $('#attrishow6').on('click', function () {
            //     $('#attri-div6').show();
            //     $('#attrihide6').show();
            //     $('#attrishow6').hide();
            // });
            // $('#attrihide6').on('click', function () {
            //     $('#attri-div6').hide();
            //     $('#attrihide6').hide();
            //     $('#attrishow6').show();
            // });

        })
    </script>

@endpush
