@extends('affiliate.layouts.main')

@section('content')

    <div class="card m-4 p-4">
        <h3>Exam for Affiliation</h3>
        <p>Please unlock all of the FAQs, and read all carefully. You can't go to the next page without unlocking
            it.</p>

        <h4 class="text-danger my-2">Terms and Condition for Affiliation Exam (Read carefully)</h4>

        <div class="row my-3 m-1 p-2">
            <div class="col-lg-10">
                <p>
                    <span class="fw-bolder">1. Compliance with Terms of Service: </span>Affiliates must adhere to
                    Ebitans' terms of service and guidelines for affiliate marketing. Violation of these terms may
                    result in termination of the affiliate partnership.
                </p>
                <p>
                    <span class="fw-bolder">2. Ethical Marketing Practices: </span>Affiliates must engage in ethical
                    marketing practices, including honesty, transparency, and integrity in all promotional activities
                    related to Ebitans products and services.
                </p>
                <p>
                    <span class="fw-bolder">3. Quality Content: </span>Affiliates must adhere to Ebitans' terms of
                    service and guidelines for affiliate marketing. Violation of these terms may result in termination
                    of the affiliate partnership.
                </p>
                <p>
                    <span class="fw-bolder">4. No Misleading Claims: </span> Affiliates must not make misleading or
                    false claims about Ebitans' products or services. All statements and representations must be
                    accurate and supported by evidence.
                </p>
                <p>
                    <span class="fw-bolder">5. Disclosure of Affiliate Relationship: </span>Affiliates must clearly
                    disclose their relationship with Ebitans and the nature of their affiliate partnership in accordance
                    with relevant laws and regulations, including FTC guidelines.
                </p>
            </div>
        </div>

    </div>

    <div class="text-center mt-5 d-flex justify-content-center">
        <a id="next" class="btn btn-outline-secondary m-1 filterbuttonss" type="submit"
           href="{{route('affiliate.faq2')}}">
            @if (Session::has('lang') && Session::get('lang') == 'bn')
                Back
            @else
                Back
            @endif
        </a>

        <form action="{{ route('affiliate.exams.start') }}" method="post" enctype="multipart/form-data">
            @csrf

            <button name="Reject" value="Reject" class="btn btn-primary m-1 filterbuttonss" type="submit">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    Start Exam
                @else
                    Start Exam
                @endif
            </button>

        </form>

    </div>

@endsection
