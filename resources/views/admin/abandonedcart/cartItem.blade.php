@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">

    <style>
        .colToText {
            width: 3% !important;
            padding: 0;
            flex: unset;
        }

        .tdInnerText {
            font-weight: 900;
            margin-bottom: 0;
        }

        .zoom {
            transition: transform .2s;
            /* Animation */
            margin: 0 auto;
        }

        .zoom:hover {
            transform: scale(2);
            /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }

        @media (max-width: 768px) {
            .colToText {
                width: 100% !important;
            }
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb" class="m-0">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item @if(request()->routeIs('admin.abandoned.cart.list')) active @endif">
                                <a href="{{ route('admin.abandoned.cart.list') }}">
                                    <img src="https://admin.ebitans.com/img/icons/box.png"> <br> <span
                                        class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            পরিত্যক্ত কার্ট
                                        @else
                                            Abandoned Cart
                                        @endif
                                    </span>
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-8">
                    <h4>Cart Product List</h4>
                </div>
            </div>
            <div class="row mt-4 mb-5 mb-md-0">
                <div class="col-12 mb-5">
                    <div class="card h-100 mt-4 mt-md-0">

                        <div class="card-body px-3 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ছবি
                                            @else
                                                Image
                                            @endif
                                        </th>
                                        <th width="20%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                নাম
                                            @else
                                                Name
                                            @endif
                                        </th>
                                        <th width="10%">SKU</th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                পরিমাণ
                                            @else
                                                Qty
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                দাম
                                            @else
                                                Price
                                            @endif
                                        </th>
                                        <th width="10%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                বৈকল্পিক
                                            @else
                                                Variant
                                            @endif
                                        </th>
                                        <th width="15%">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                তারিখ
                                            @else
                                                Date
                                            @endif
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($productList) && count($productList) > 0)
                                        @foreach($productList as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if(isset($item['product']['image']) && is_array($item['product']['image']) && count($item['product']['image']) > 0)
                                                        <img
                                                            src="{{ URL::to('/') }}/assets/images/product/{{ $item['product']['image'][0] }}"
                                                            class="zoom" width="50px" alt=""/>
                                                    @endif
                                                </td>
                                                <td>{{ $item['product']['name'] ?? '' }}</td>
                                                <td>{{ $item['product']['SKU'] ?? '' }}</td>
                                                <td>{{ $item['quantity'] ?? '' }}</td>
                                                <td>{{ number_format($item['price'], 2) }}</td>
                                                <td>
                                                    @if(isset($item['selected_variant']))
                                                        @if(isset($item['selected_variant']['color']))
                                                            <p class="tdInnerText">
                                                                Color: {{ $item['selected_variant']['color'] }}</p>
                                                        @endif
                                                        @if(isset($item['selected_variant']['size']))
                                                            <p class="tdInnerText">
                                                                Size: {{ $item['selected_variant']['size'] }}</p>
                                                        @endif
                                                        @if(isset($item['selected_variant']['volume']))
                                                            <p class="tdInnerText">
                                                                Volume: {{ $item['selected_variant']['volume'] }}</p>
                                                        @endif
                                                        @if(isset($item['selected_variant']['unit']))
                                                            <p class="tdInnerText">
                                                                Unit: {{ $item['selected_variant']['unit'] }}</p>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($item['created_at']))
                                                        {{ $item['created_at']->format('d M Y H:i A') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">No record found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

