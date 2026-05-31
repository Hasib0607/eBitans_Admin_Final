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

        <div class="my-3">
            @php($i = 1)
            @if(count($faqs) > 0)
                @foreach($faqs as $faq)
                    <div class="p-2 my-2" style="box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.05);">
                        <div class="row">
                            <div class="col-10">
                                <p class="fw-bolder">
                                    {{ $i++ }} - {{ $faq->question }}
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
                                    <iframe src="https://www.youtube.com/embed/z6llDxY5JFs" width="50%" frameborder="0"
                                            allowfullscreen></iframe>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="text-center mt-2">
        <a id="next" class="btn btn-outline-secondary m-1 filterbuttonss" type="submit" href="#">
            @if (Session::has('lang') && Session::get('lang') == 'bn')
                Back
            @else
                Back
            @endif
        </a>

        <a id="next" class="btn btn-danger m-1 filterbuttonss" type="submit" href="{{route('affiliate.faq2')}}">
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
        })
    </script>
@endpush




