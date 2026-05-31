@extends('admin.layouts.main')
@push("styles")
    <style>
        .courierActiveBtn {
            background: #bf1b0e !important;
        }

        .widthAuto {
            width: fit-content;
        }

        .ml-2 {
            margin-left: 5px;
        }

        .order_info tr {
            border: 1px solid gray;
        }

        .order_info td,
        .order_info th {
            border: 1px solid gray;
            text-align: center;
        }

        .orderdetails table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        .orderdetails th,
        td {
            text-align: center;
            padding: 16px;
        }

        .orderdetails tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        @media only screen and (max-width: 600px) {
            .text-sm {
                font-size: 12px !important;
            }

            .img-sm {
                width: 50px !important;
                height: 50px !important;
            }
        }

        main.main-content.position-relative.border-radius-lg {
            margin-right: 5px;
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative border-radius-lg">
        @include('admin.order.share.order-nav')

        <?php

    $orderDetails = DB::table('orders')
        ->leftJoin('orderitems', 'orderitems.order_id', '=', 'orders.id')
        ->where('orders.id', $order->id)
        ->groupBy('orders.id') // Group by orders.id to aggregate quantities correctly
        ->select('orders.*', DB::raw('SUM(orderitems.quantity) as total_quantity'))
        ->first();
                    ?>

        @if(canAccess("courier"))
            @if(isset($courierInfo) && count($courierInfo) > 0 && $activeCourier)
                <div class="modal fade" id="courier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" style="background-color:transparent;border:0px">

                            <div class="modal-body" style="border:none">
                                <button data-bs-dismiss="modal" class="btn btn-danger sm" style="float: right; margin: 0px 8px;">X
                                </button>

                                <div class="row mt-1">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    @if(isset($courierInfo) && count($courierInfo) > 0)
                                                        @foreach($courierInfo as $courier)
                                                            @if($courier->courier_name == "pathao" && $courier->status == "1")
                                                                {{-- Pathao courier btn --}}
                                                                <div class="col-3 col-lg-2">
                                                                    <button id="pathao_btn" onclick="courierFormShow('pathao')"
                                                                        class="btn bg-orange-600 btn-danger mx-1px text-95">
                                                                        <i class="mr-1 fa fa-truck text-primary-m1 text-120"></i>
                                                                        Pathao
                                                                    </button>
                                                                </div>
                                                            @endif
                                                            @if($courier->courier_name == "steadfast" && $courier->status == "1")
                                                                {{-- Steadfast courier btn --}}
                                                                <div class="col-3 col-lg-2">
                                                                    <form method="POST" id="steadfastForm"
                                                                        action="{{ route('courier.createSteadfastOrder') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="order_ids" value="{{ $order->id }}">
                                                                        <button onclick="submitSteadfast(event)"
                                                                            class="btn bg-orange-600 btn-danger mx-1px text-95">
                                                                            <i class="mr-1 fa fa-truck text-primary-m1 text-120"></i>
                                                                            Steadfast
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                            @if($courier->courier_name == "ecourier" && $courier->status == "1")
                                                                {{-- eCourier courier btn --}}
                                                                <div class="col-3 col-lg-2">
                                                                    <button id="ecourier_btn" onclick="courierFormShow('ecourier')"
                                                                        class="btn bg-orange-600 btn-danger mx-1px text-95">
                                                                        <i class="mr-1 fa fa-truck text-primary-m1 text-120"></i>
                                                                        eCourier
                                                                    </button>
                                                                </div>
                                                            @endif
                                                            @if($courier->courier_name == "redx" && $courier->status == "1")
                                                                {{-- Redx courier btn --}}
                                                                <div class="col-3 col-lg-2">
                                                                    <button id="redx_btn" onclick="courierFormShow('redx')"
                                                                        class="btn bg-orange-600 btn-danger mx-1px text-95">
                                                                        <i class="mr-1 fa fa-truck text-primary-m1 text-120"></i>
                                                                        REDX
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>

                                                <hr />

                                                <div class="row">
                                                    <div class="col-12">
                                                        @if(isset($courierInfo) && count($courierInfo) > 0)
                                                            @foreach($courierInfo as $courier)
                                                                @if($courier->courier_name == "pathao" && $courier->status == "1")

                                                                                            <?php
                                                                    $cities = [];
                                                                    $citiesError = NULL;
                                                                    try {
                                                                        \App\Http\Controllers\Courier\CourierController::setCourierConfig("pathao");
                                                                        $cities = \Codeboxr\PathaoCourier\Facade\PathaoCourier::area()->city();
                                                                    } catch (\Exception $e) {
                                                                        $citiesError = "The courier credentials were incorrect";
                                                                    }
                                                                                                                                                                                                                        ?>


                                                                                            {{-- Pathao courier form--}}
                                                                                            <div id="pathao_form" style="display: none">
                                                                                                <form method="POST" action="{{ route('courier.createPathaoOrder') }}">
                                                                                                    @csrf
                                                                                                    <input type="hidden" name="order_ids" value="{{ $order->id }}">

                                                                                                    @if(!is_null($citiesError))
                                                                                                        <div class="alert alert-danger text-white">{{ $citiesError }}</div>
                                                                                                    @endif
                                                                                                    <h3>Pathao</h3>
                                                                                                    <div class="row">

                                                                                                        <div class="col-lg-6">
                                                                                                            <div class="mb-4">
                                                                                                                <label class="form-label">
                                                                                                                    Merchant Order ID
                                                                                                                </label>
                                                                                                                <div class="row gx-2">
                                                                                                                    <input type="text" class="form-control"
                                                                                                                        value="{{ $order->reference_no }}" readonly>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>


                                                                                                        <div class="col-lg-6">
                                                                                                            <div class="mb-4">
                                                                                                                <label class="form-label">
                                                                                                                    Quantity
                                                                                                                </label>
                                                                                                                <div class="row gx-2">
                                                                                                                    <input type="number" class="form-control"
                                                                                                                        value="{{ $orderDetails->total_quantity }}"
                                                                                                                        readonly>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>


                                                                                                        <div class="col-lg-6">
                                                                                                            <div class="mb-3">
                                                                                                                <label class="form-label">
                                                                                                                    Amount
                                                                                                                </label>
                                                                                                                <input type="number" class="form-control"
                                                                                                                    value="{{ $order->due }}" readonly>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-lg-6">
                                                                                                            <div class="mb-3">
                                                                                                                <label class="form-label">
                                                                                                                    Item weight
                                                                                                                    <span class="req">*</span>
                                                                                                                </label>
                                                                                                                <input type="number" name="item_weight"
                                                                                                                    class="form-control" value="" step="0.001">
                                                                                                                @error('item_weight')
                                                                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                                                @enderror
                                                                                                            </div>
                                                                                                        </div>


                                                                                                        <div class="col-lg-6">
                                                                                                            <div class="mb-4">
                                                                                                                <label class="form-label">
                                                                                                                    Recipient's Name
                                                                                                                    <span class="req">*</span>
                                                                                                                </label>
                                                                                                                <input type="text" class="form-control"
                                                                                                                    name="recipient_name" value="{{ $order->name }}">
                                                                                                                @error('recipient_name')
                                                                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                                                @enderror
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div class="col-lg-6">
                                                                                                            <div class="mb-4">
                                                                                                                <label class="form-label">
                                                                                                                    Recipient's Phone
                                                                                                                    <span class="req">*</span>
                                                                                                                </label>
                                                                                                                <input type="text" class="form-control"
                                                                                                                    name="recipient_phone" value="{{ $order->phone }}">
                                                                                                                @error('recipient_phone')
                                                                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                                                @enderror
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div class="col-lg-6">
                                                                                                            <div class="mb-4">
                                                                                                                <label class="form-label">
                                                                                                                    Recipient’s Address
                                                                                                                    <span class="req">*</span>
                                                                                                                </label>
                                                                                                                <textarea name="recipient_address"
                                                                                                                    class="form-control">{{ $order->address }}</textarea>
                                                                                                                @error('recipient_address')
                                                                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                                                @enderror
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div class="col-lg-6">
                                                                                                            <div class="mb-4">
                                                                                                                <label class="form-label">
                                                                                                                    Item Description & Price
                                                                                                                </label>
                                                                                                                <textarea name="item_description"
                                                                                                                    class="form-control">{{ $order->description }}</textarea>
                                                                                                                @error('item_description')
                                                                                                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                                                @enderror
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div class="col-lg-4">
                                                                                                            <label class="form-label">
                                                                                                                City
                                                                                                                <span class="req">*</span>
                                                                                                            </label>
                                                                                                            <select class="form-control" name="recipient_city"
                                                                                                                id="city_id" onchange="getZone()">
                                                                                                                <option value="">Select city
                                                                                                                </option>
                                                                                                                @if(isset($cities->data) && count($cities->data) > 0)
                                                                                                                    @foreach($cities->data as $city)
                                                                                                                        <option value="{{ $city->city_id }}">
                                                                                                                            {{ $city->city_name }}
                                                                                                                        </option>
                                                                                                                    @endforeach
                                                                                                                @endif
                                                                                                            </select>
                                                                                                            @error('recipient_city')
                                                                                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                                            @enderror
                                                                                                        </div>
                                                                                                        <div class="col-lg-4">
                                                                                                            <label class="form-label">
                                                                                                                Zone
                                                                                                                <span class="req">*</span>
                                                                                                            </label>
                                                                                                            <select class="form-control zone_list" name="recipient_zone"
                                                                                                                id="zone_id" onchange="getArea()">
                                                                                                                <option value="">Select zone
                                                                                                                </option>
                                                                                                            </select>
                                                                                                            @error('recipient_zone')
                                                                                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                                            @enderror
                                                                                                        </div>

                                                                                                        <div class="col-lg-4">
                                                                                                            <label class="form-label">
                                                                                                                Area
                                                                                                                <span class="req">*</span>
                                                                                                            </label>
                                                                                                            <select class="form-control area_list"
                                                                                                                name="recipient_area">
                                                                                                                <option value="">Select area
                                                                                                                </option>
                                                                                                            </select>
                                                                                                            @error('recipient_area')
                                                                                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                                            @enderror
                                                                                                        </div>

                                                                                                    </div>

                                                                                                    <div class="form-group mt-3">
                                                                                                        <label for="">Special
                                                                                                            Instructions</label>
                                                                                                        <textarea name="special_instruction"
                                                                                                            class="form-control">{{ $order->note }}</textarea>
                                                                                                        @error('special_instruction')
                                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                                        @enderror
                                                                                                    </div>

                                                                                                    @if(is_null($citiesError))
                                                                                                        <button class="btn bg-orange-600 btn-danger mx-1px mt-3 text-95">
                                                                                                            Save
                                                                                                        </button>
                                                                                                    @endif
                                                                                                </form>
                                                                                            </div>
                                                                @endif
                                                                @if($courier->courier_name == "ecourier" && $courier->status == "1")
                                                                    {{-- ecourier courier form --}}
                                                                    <div id="ecourier_form" style="display: none">
                                                                        <form method="POST" action="{{ route('courier.createEcourierOrder') }}">
                                                                            @csrf
                                                                            <input type="hidden" name="order_ids" value="{{ $order->id }}">

                                                                            <h3>eCourier</h3>
                                                                            <div class="row">

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Merchant Order ID
                                                                                        </label>
                                                                                        <div class="row gx-2">
                                                                                            <input type="text" class="form-control"
                                                                                                value="{{ $order->reference_no }}" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Quantity
                                                                                        </label>
                                                                                        <div class="row gx-2">
                                                                                            <input type="number" class="form-control"
                                                                                                value="{{ $orderDetails->total_quantity }}"
                                                                                                readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-3">
                                                                                        <label class="form-label">
                                                                                            Amount
                                                                                        </label>
                                                                                        <input type="number" class="form-control"
                                                                                            value="{{ $order->due }}" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-3">
                                                                                        <label class="form-label">
                                                                                            Payment method
                                                                                            <span class="req">*</span>
                                                                                        </label>
                                                                                        <select class="form-control" name="payment_method"
                                                                                            id="payment_method">
                                                                                            <option value="">Select
                                                                                                payment
                                                                                                method
                                                                                            </option>
                                                                                            <option value="COD">Cash On
                                                                                                Delivery
                                                                                            </option>
                                                                                            <option value="POS">Point of
                                                                                                Sale
                                                                                            </option>
                                                                                            <option value="MPAY">Mobile
                                                                                                Payment
                                                                                            </option>
                                                                                        </select>
                                                                                        @error('payment_method')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Recipient's Name
                                                                                            <span class="req">*</span>
                                                                                        </label>
                                                                                        <input type="text" class="form-control"
                                                                                            name="recipient_name" value="{{ $order->name }}">
                                                                                        @error('recipient_name')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Recipient's Mobile
                                                                                            <span class="req">*</span>
                                                                                        </label>
                                                                                        <input type="text" class="form-control"
                                                                                            name="recipient_mobile" value="{{ $order->phone }}">
                                                                                        @error('recipient_mobile')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Recipient’s Address
                                                                                            <span class="req">*</span>
                                                                                        </label>
                                                                                        <textarea name="recipient_address"
                                                                                            class="form-control">{{ $order->address }}</textarea>
                                                                                        @error('recipient_address')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Parcel Details
                                                                                        </label>
                                                                                        <textarea name="parcel_detail"
                                                                                            class="form-control">{{ $order->description }}</textarea>
                                                                                        @error('parcel_detail')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>


                                                                                <div class="col-lg-6 mb-4">
                                                                                    <label class="form-label">
                                                                                        City
                                                                                        <span class="req">*</span>
                                                                                    </label>
                                                                                    <select class="form-control" name="recipient_city"
                                                                                        id="city_name" onchange="getEcourierCity()">
                                                                                        <option value="">Select city
                                                                                        </option>
                                                                                        {{-- @foreach($cities->data as $city)--}}
                                                                                        {{-- <option--}} {{-- value="{{ $city->city_id }}">--}}
                                                                                            {{-- {{ $city->city_name }}--}}
                                                                                            {{-- </option>--}}
                                                                                            {{-- @endforeach--}}
                                                                                    </select>
                                                                                    @error('recipient_city')
                                                                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                    @enderror
                                                                                </div>
                                                                                <div class="col-lg-6 mb-4">
                                                                                    <label class="form-label">
                                                                                        Thana
                                                                                        <span class="req">*</span>
                                                                                    </label>
                                                                                    <select class="form-control thana_list"
                                                                                        name="recipient_thana" id="thana_name"
                                                                                        onchange="getEcourierThan()">
                                                                                        <option value="">Select zone
                                                                                        </option>
                                                                                    </select>
                                                                                    @error('recipient_thana')
                                                                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                    @enderror
                                                                                </div>

                                                                                <div class="col-lg-6 mb-4">
                                                                                    <label class="form-label">
                                                                                        Post Office
                                                                                        <span class="req">*</span>
                                                                                    </label>
                                                                                    <select class="form-control post_list" name="recipient_zip">
                                                                                        <option value="">Select area
                                                                                        </option>
                                                                                    </select>
                                                                                    @error('recipient_zip')
                                                                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                    @enderror
                                                                                </div>

                                                                                <div class="col-lg-6 mb-4">
                                                                                    <label class="form-label">
                                                                                        Area
                                                                                        <span class="req">*</span>
                                                                                    </label>
                                                                                    <select class="form-control area_list"
                                                                                        name="recipient_area">
                                                                                        <option value="">Select area
                                                                                        </option>
                                                                                    </select>
                                                                                    @error('recipient_area')
                                                                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                    @enderror
                                                                                </div>

                                                                                <div class="col-lg-6 mb-2">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Pick Address
                                                                                        </label>
                                                                                        <textarea name="pick_address"
                                                                                            class="form-control">{{ $order->address }}</textarea>
                                                                                        @error('pick_address')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6 mb-2">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Pick Hub
                                                                                        </label>
                                                                                        <textarea name="pick_hub"
                                                                                            class="form-control"></textarea>
                                                                                        @error('pick_hub')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                            <button class="btn bg-orange-600 btn-danger mx-1px text-95 mt-1">
                                                                                <i class="mr-1 fa fa-truck text-primary-m1 text-120"></i>
                                                                                eCourier
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                                @if($courier->courier_name == "redx" && $courier->status == "1")
                                                                    @php
                                                                        $redxAreas = [];
                                                                        $redxStores = [];
                                                                        $redxError = null;

                                                                        try {
                                                                            \App\Http\Controllers\Courier\CourierController::setCourierConfig("redx");
                                                                            $redxAreasResponse = \Codeboxr\RedxCourier\Facade\RedxCourier::area()->list();
                                                                            $redxStoresResponse = \Codeboxr\RedxCourier\Facade\RedxCourier::store()->list();

                                                                            $redxAreas = $redxAreasResponse->areas ?? [];
                                                                            $redxStores = $redxStoresResponse->pickup_stores ?? [];
                                                                        } catch (\Exception $e) {
                                                                            $redxError = "The RedX credentials were incorrect or RedX API is unavailable.";
                                                                        }
                                                                    @endphp

                                                                    <div id="redx_form" style="display: none">
                                                                        <form method="POST" action="{{ route('courier.createRedxOrder') }}">
                                                                            @csrf
                                                                            <input type="hidden" name="order_ids" value="{{ $order->id }}">
                                                                            <input type="hidden" name="delivery_area"
                                                                                id="redx_delivery_area_name" value="{{ old('delivery_area') }}">

                                                                            @if(!is_null($redxError))
                                                                                <div class="alert alert-danger text-white">{{ $redxError }}</div>
                                                                            @endif

                                                                            <h3>REDX</h3>

                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">Merchant Order ID</label>
                                                                                        <input type="text" class="form-control"
                                                                                            value="{{ $order->reference_no }}" readonly>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">Quantity</label>
                                                                                        <input type="number" class="form-control"
                                                                                            value="{{ $orderDetails->total_quantity }}"
                                                                                            readonly>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">Amount</label>
                                                                                        <input type="number" class="form-control"
                                                                                            value="{{ $order->due }}" readonly>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Parcel Weight (Gram)
                                                                                            <span class="req">*</span>
                                                                                        </label>
                                                                                        <input type="number" step="0.001" min="0.001"
                                                                                            name="parcel_weight" class="form-control"
                                                                                            value="{{ old('parcel_weight') }}">
                                                                                        @error('parcel_weight')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-12">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">Shipping Address</label>
                                                                                        <textarea class="form-control" rows="3"
                                                                                            readonly>{{ !empty($order->edited_address) ? $order->edited_address : $order->address }}</textarea>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Delivery Area
                                                                                            <span class="req">*</span>
                                                                                        </label>
                                                                                        <select name="delivery_area_id"
                                                                                            id="redx_delivery_area_id" class="form-control"
                                                                                            onchange="setRedxAreaName()" {{ !is_null($redxError) ? 'disabled' : '' }}>
                                                                                            <option value="">Select delivery area</option>
                                                                                            @foreach($redxAreas as $area)
                                                                                                <option value="{{ $area->id }}"
                                                                                                    data-name="{{ $area->name }}" {{ old('delivery_area_id') == $area->id ? 'selected' : '' }}>
                                                                                                    {{ $area->name }} -
                                                                                                    {{ $area->district_name ?? '' }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        @error('delivery_area_id')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                        @error('delivery_area')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">
                                                                                            Pickup Store
                                                                                            <span class="req">*</span>
                                                                                        </label>
                                                                                        <select name="pickup_store_id" class="form-control" {{ !is_null($redxError) ? 'disabled' : '' }}>
                                                                                            <option value="">Select pickup store</option>
                                                                                            @foreach($redxStores as $store)
                                                                                                <option value="{{ $store->id }}" {{ old('pickup_store_id') == $store->id ? 'selected' : '' }}>
                                                                                                    {{ $store->name }} -
                                                                                                    {{ $store->area_name ?? '' }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        @error('pickup_store_id')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">Parcel Value</label>
                                                                                        <input type="number" step="0.01" min="0" name="value"
                                                                                            class="form-control"
                                                                                            value="{{ old('value', $order->due) }}">
                                                                                        @error('value')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-4">
                                                                                        <label class="form-label">Instruction</label>
                                                                                        <textarea name="instruction" class="form-control"
                                                                                            rows="3">{{ old('instruction', $order->note) }}</textarea>
                                                                                        @error('instruction')
                                                                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            @if(is_null($redxError))
                                                                                <button class="btn bg-orange-600 btn-danger mx-1px text-95">
                                                                                    <i class="mr-1 fa fa-truck text-primary-m1 text-120"></i>
                                                                                    REDX
                                                                                </button>
                                                                            @endif
                                                                        </form>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div id="printable" class="container-fluid mt-4" style="background-color:#8080801f;padding: 0 13px;" id="toplist">
            <div class="row" style="background-color:#fff;display:flex;align-items:center;justify-content:center">
                <div class="col-md-4 pt-2">
                    <h4>Order ID #{{ $order->reference_no }}</h4>
                    @if(isset($order->order_no) && !empty($order->order_no))
                        <h6>Order No. #{{ $order->order_no ?? "" }}</h6>
                    @endif
                    <p>Placed on {{ date('h:i A d/m/Y', strtotime($order->created_at)) }}</p>
                </div>

                <div class="col-md-8 text-end">
                    <div class="row" style="justify-content: end;">
                        <div class="widthAuto">
                            <form action="{{ route('admin.order.changestatus') }}" id="changeStatusSubmit" method="post"
                                style="display: flex;align-items: baseline;">
                                @csrf
                                <input type="hidden" name="text2" id="selectids" value="{{ $order->id }}">

                                @php
                                    $type = $order->status ?? "";
                                @endphp
                                <span class="form-label" style="width: 160px;">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        অর্ডার স্ট্যাটাস
                                    @else
                                        Order status
                                    @endif
                                </span>
                                <select class="form-select ml-2" name="type" onchange="changeStatus(this.value)">
                                    <!--onchange="this.form.submit()" -->
                                    <option value="all" @if (isset($type) && $type == 'all') selected @endif>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            অর্ডার স্থিতি পরিবর্তন করুন
                                        @else
                                            Change Order Status
                                        @endif
                                    </option>

                                    <?php
    $userData = getUserData();
    $user = $userData['user'];
    $customer = $userData['customer'];

    $digitalproductmodules = DB::table('moduluses')
        ->where('id', '=', 110)
        ->where('status', '=', '1')
        ->first();
    $advancepaymentmodules = DB::table('moduluses')
        ->where('id', '=', 106)
        ->where('status', '=', '1')
        ->first();
    $bookingsystemmodules = DB::table('moduluses')
        ->where('id', '=', 108)
        ->where('status', '=', '1')
        ->first();
    if ($digitalproductmodules) {
        $digitalproductstatus = DB::table('buy_moduluses')
            ->where('modulus_id', '=', $digitalproductmodules->id)
            ->where('store_id', '=', $customer->active_store)
            ->where('status', '=', '1')
            ->first();
    } else {
        $digitalproductstatus = null;
    }
    if ($advancepaymentmodules) {
        $advancepaymentstatus = DB::table('buy_moduluses')
            ->where('modulus_id', '=', $advancepaymentmodules->id)
            ->where('store_id', '=', $customer->active_store)
            ->where('status', '=', '1')
            ->first();
    } else {
        $advancepaymentstatus = null;
    }
    if ($bookingsystemmodules) {
        $bookingsystemstatus = DB::table('buy_moduluses')
            ->where('modulus_id', '=', $bookingsystemmodules->id)
            ->where('store_id', '=', $customer->active_store)
            ->where('status', '=', '1')
            ->first();
    } else {
        $bookingsystemstatus = null;
    }
                                                    ?>


                                    @php
                                        $statuses = \App\Models\OrderStatus::getOrderStatus();
                                    @endphp

                                    @if(count($statuses))
                                        @foreach($statuses as $key => $item)
                                            @if($item->slug == "Payment Success")
                                                @if ($digitalproductstatus || $advancepaymentstatus)
                                                    <option value="{{ $item->slug }}" @if (isset($type) && $type == $item->slug) selected
                                                    @endif>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            {{ $item->name_bn }}
                                                        @else
                                                            {{ $item->name }}
                                                        @endif
                                                    </option>
                                                @endif
                                            @elseif($item->slug == "Booked")
                                                @if($bookingsystemstatus)
                                                    <option value="{{ $item->slug }}" @if (isset($type) && $type == $item->slug) selected
                                                    @endif>
                                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                            {{ $item->name_bn }}
                                                        @else
                                                            {{ $item->name }}
                                                        @endif
                                                    </option>
                                                @endif
                                            @else
                                                <option value="{{ $item->slug }}" @if (isset($type) && $type == $item->slug) selected
                                                @endif>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        {{ $item->name_bn }}
                                                    @else
                                                        {{ $item->name }}
                                                    @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </form>
                        </div>
                        @if(canAccess("courier"))
                            @if(isset($courierInfo) && count($courierInfo) > 0 && $activeCourier)
                                <button class="btn bg-orange-600 btn-danger mx-1px text-95 widthAuto ml-2" id="courierBtn"
                                    data-bs-toggle="modal" data-bs-target="#courier" data-title="Print">
                                    <i class="mr-1 fa fa-truck text-primary-m1 text-120"></i>
                                    Courier
                                </button>
                            @endif
                        @endif
                        @if (!empty($order->invoice->id))
                            <a class="btn bg-orange-500 btn-danger mx-1px text-95 widthAuto ml-2" style="margin-right: 10px;"
                                href="{{ route('admin.invoiceview', encrypt($order->invoice->id)) }}">
                                <i class="mr-1 fa fa-print text-primary-m1 text-120"></i>
                                Invoice
                            </a>
                        @endif

                        <!-- <a class="btn bg-orange-600 btn-danger mx-1px text-95 widthAuto ml-2" href="#"
                                    onclick="printDiv('printable')" data-title="Print" style="margin-right: 10px;">
                                    <i class="mr-1 fa fa-print text-primary-m1 text-120"></i>
                                    Print
                                </a> -->
                    </div>
                </div>
            </div>
        </div>
        <?php
    $store = DB::table('stores')
        ->where('id', $order->store_id)
        ->first();
                        ?>
        <div class="row mt-1 p-2"
            style="background-color:#fff;display:flex;align-items:center;justify-content:center;width: 100%; margin: 0;">
            <?php
    $orderitem = DB::table('orderitems')
        ->where('order_id', $order->id)
        ->get();

    $billing = DB::table('users')
        ->where('id', $order->uid)
        ->first();
                            ?>
            @if (isset($orderitem) && count($orderitem) > 0)
                <div class="col-6 mt-3 mb-3 text-sm">
                    Products
                </div>
                <!--<div class="col-2 mt-3 mb-3 text-sm">-->
                <!--    Product Name-->
                <!--</div>-->
                <div class="col-2 mt-3 mb-3 text-sm">
                    Quantity
                </div>
                <div class="col-2 mt-3 mb-3 text-sm">
                    Unit Price

                </div>
                <div class="col-2 mt-3 mb-3 text-sm">
                    Total Price
                </div>


                @foreach ($orderitem as $key => $oitm)
                    <div class="row border mb-2">
                        <?php        $product = DB::table('products')
                        ->where('id', $oitm->product_id)
                        ->first(); ?>

                        <div class="col-2 mt-3 mb-3">
                            @php
                                // ✅ Null-safe: $product may be null
                                $images = [];
                                $gallery_image = [];

                                if (isset($product) && $product) {
                                    if (!empty($product->images)) {
                                        $images = array_filter(explode(',', $product->images));
                                    }

                                    if (!empty($product->gallery_image)) {
                                        $gallery_image = array_filter(explode(',', $product->gallery_image));
                                    }
                                }

                                $mergedImages = array_values(array_unique(array_merge($gallery_image, $images)));

                                // Convert to full path/url using your helper
                                $images = array_map(function ($img) {
                                    return getPath($img, 'assets/images/product');
                                }, $mergedImages);
                            @endphp

                            @if (!empty($images))
                                <img class="img-sm" src="{{ $images[0] }}" width="100px" height="100px">
                            @endif
                        </div>
                        <div class="col-4 mt-3 mb-3 text-sm">
                            @if(isset($product))
                                <a href="{{ URL::to('/') }}/products/edit/{{ $product->id }}"
                                    style="color:inherit; text-decoration:none;" target="_blank">
                                    {{ $product->name }}

                                </a>
                                <br>
                                <a style="font-weight: bold;">
                                    SKU: {{ $product->SKU }}
                                </a>


                                <br>


                                @if (!empty($oitm->color))
                                    Color: <span
                                        style="display:inline-block; background: {{ $oitm->color }}; width:15px; height:15px;border-radius: 50%;">&nbsp
                                    </span>
                                @endif
                                @if (!empty($oitm->size))
                                    <span class="fw-bold">Size: </span>{{ $oitm->size }}
                                @endif
                                @if (!empty($oitm->unit))
                                    <span class="fw-bold">Unit: </span> {{ $oitm->volume }} {{ $oitm->unit }}
                                @endif
                                <br>
                                @if (!empty($product->product_link))
                                    <span class="fw-bold">Link: </span> <a style="color:blue; text-decoration:underline"
                                        href="{{ $product->product_link }}" target="_blank"> {{ $product->product_link }} </a>
                                @endif

                            @else
                                @php
                                    if (isset($oitm->product_snapshot)) {
                                        $product = json_decode($oitm->product_snapshot);
                                    }
                                @endphp

                                @if(isset($product))
                                    <a href="#" style="color:inherit; text-decoration:none;" target="_blank">
                                        {{ $product->name }}

                                    </a>
                                    <br>
                                    <a style="font-weight: bold;">
                                        SKU: {{ $product->SKU }}
                                    </a>
                                    <br>
                                    @if (!empty($oitm->color))
                                        Color: <span
                                            style="display:inline-block; background: {{ $oitm->color }}; width:15px; height:15px;border-radius: 50%;">&nbsp
                                        </span>
                                    @endif
                                    @if (!empty($oitm->size))
                                        <span class="fw-bold">Size: </span>{{ $oitm->size }}
                                    @endif
                                    @if (!empty($oitm->unit))
                                        <span class="fw-bold">Unit: </span> {{ $oitm->volume }} {{ $oitm->unit }}
                                    @endif
                                @else
                                    <span class="text text-danger text-lg">{{ "This product is no longer available." }}</span>
                                @endif
                            @endif
                        </div>
                        <div class="col-2 mt-3 mb-3 text-sm">
                            Qty. {{ $oitm->quantity }}
                        </div>
                        <div class="col-2 mt-3 mb-3 text-sm">
                            @if ($oitm->additional_price != '')
                                ৳ {{ $oitm->price + $oitm->additional_price }}
                            @else
                                {{ $oitm->price }}
                            @endif

                        </div>
                        <div class="col-2 mt-3 mb-3 text-sm">
                            @if ($oitm->additional_price != '')
                                ৳ {{ ($oitm->price + $oitm->additional_price) * $oitm->quantity }}
                            @else
                                ৳ {{ $oitm->price * $oitm->quantity }}
                            @endif


                        </div>

                        <div class="col-md-12">
                            <div class="mb-2 row ">
                                @if (!empty($oitm->orderfiles))
                                    @php
                                        $imagesd = explode(',', $oitm->orderfiles);
                                    @endphp
                                    @foreach ($imagesd as $key => $img)
                                        <div class="col md-1 p-1">
                                            @if (!empty($img))
                                                <img src="{{ URL::to('/') }}/orders/{{ $img }}"
                                                    onerror="this.src='https://ebitans.com/Image/cover/eBitans-logo-mockup4.jpg';" width="100%"
                                                    style="width:150px !important;">
                                                <a href="#"
                                                    onclick="event.preventDefault(); document.getElementById('download_Frm{{ $key }}').submit();">Download</a>
                                                <form id="download_Frm{{ $key }}" action="{{ route('admin.file.download') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="pathName" value="{{ 'orders/' . $img }}">
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            @if (!empty($oitm->sampleDescription))
                                <u><strong>Description:</strong></u>
                                <p class="p-2">
                                    {{ $oitm->sampleDescription ?? '' }}
                                </p>
                            @endif

                        </div>

                    </div>
                @endforeach
            @endif
        </div>
        <div class="row mt-2" style="background-color:#fff;display:flex;justify-content:center;width: 100%; margin: 0;">
            <div class="col-md-6 text-start py-3">
                <div class="row">
                    <div class="col-12 text-start py-3" style="border-right:8px solid #8080801f">
                        <h4>Payment Details</h4>
                        <p class="mb-0"><strong>Status: </strong>{{ $trx->status }}</p>
                        <p class="mb-0"><strong>Method: </strong> {{ $trx->mode }}</p>
                        @if(!empty($trx->transaction_id))
                            <p class="mb-0"><strong>Transaction ID: </strong>{{ $trx->transaction_id }}</p>
                        @endif
                        @if(!empty($trx->number))
                            <p class="mb-0"><strong>Number: </strong>{{ $trx->number }}</p>
                        @endif
                    </div>
                    @if (!empty($booking) && $order->status == 'Booked')
                        <div class="col-md-12 text-start py-3" style="border-right:8px solid #8080801f">
                            <h4>Booking Information</h4>
                            @if (!empty($booking->name))
                                <p><strong>Customer Name:</strong> {{ $booking->name }}</p>
                            @endif

                            @if (!empty($booking->phone))
                                <p><strong>Customer Phone:</strong> {{ $booking->phone }}</p>
                            @endif

                            @if (!empty($booking->email))
                                <p><strong>Customer Email:</strong> {{ $booking->email }}</p>
                            @endif

                            @if (!empty($booking->date))
                                <p><strong>Customer Date:</strong> {{ $booking->date }}</p>
                            @endif

                            @if (!empty($booking->start_date) && !empty($booking->end_date))
                                <p><strong>Customer Start Date:</strong> {{ $booking->start_date }}</p>
                                <p><strong>Customer End Date:</strong> {{ $booking->end_date }}</p>
                            @endif

                            @if (!empty($booking->pickup_location) && !empty($booking->drop_location))
                                <p><strong>Customer Pickup Location:</strong> {{ $booking->pickup_location }}</p>
                                <p><strong>Customer Drop Location:</strong> {{ $booking->drop_location }}</p>
                            @endif

                            @if (!empty($booking->time))
                                @php
                                    $formattedTime = \Carbon\Carbon::createFromFormat('H:i', $booking->time)->format('g:i A');
                                @endphp
                                <p><strong>Customer Time:</strong> {{ $formattedTime }}</p>
                            @endif

                            @if (!empty($booking->comment))
                                <p><strong>Customer Comment:</strong> {{ $booking->comment }}</p>
                            @endif
                        </div>
                    @else
                        <div class="col-md-12 text-start py-3" style="border-right:8px solid #8080801f">
                            <h4>Billing Address</h4>
                            <h6>{{ $billing->name ?? '' }}</h6>
                            <h6>{{ $billing->phone ?? '' }}</h6>
                            <h6>{{ $billing->email ?? '' }}</h6>
                            <h6>{{ $billing->address ?? '' }}</h6>
                        </div>
                        <div class="col-12 text-start py-3" style="border-right:8px solid #8080801f">
                            <h4>Shipping Address</h4>
                            <h6>{{ $order->name ?? "" }}</h6>
                            <h6>{{ $order->phone ?? "" }}</h6>
                            <h6>{{ $order->email ?? "" }}</h6>
                            <h6>{{ $order->district ?? "" }}</h6>
                            <h6 style="word-wrap: break-word;">{{ $order->address ?? "" }}</h6>
                            <div class="mt-3">
                                <p><strong>Note:</strong> {{ $order->note }}</p>
                            </div>
                            <div class="col-md-12 text-start py-3">
                                <div class="row">
                                    <div class="col-12">
                                        <strong>Comments:</strong>
                                    </div>
                                    <div class="col-12" style="display: flex;align-items: center;">
                                        <textarea name="order_comment" id="order_comment" rows="5" style="width: 100%"
                                            class="form-control ml-2"
                                            data-order-id="{{ $order->id }}">{{ $order->order_comment ?? "" }}</textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                @if(ModulusStatus($store->id, 122))
                                    @if(canAccess("order_update"))
                                        <form action="{{ route("admin.order.address.update") }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <div class="col-md-12 text-start py-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <strong>Edited Address:</strong>
                                                    </div>
                                                    <div class="col-12" style="display: flex;align-items: center;">
                                                        <textarea name="edited_address" id="edited_address" rows="5" style="width: 100%"
                                                            class="form-control ml-2">{{ $order->edited_address ?? "" }}</textarea>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-12" style="float: right;">
                                                        <button type="submit" class="col-3 btn btn-success mt-4">Update
                                                            address
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <div class="col-md-12 text-start py-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <strong>Edited Address:</strong>
                                                </div>
                                                <div class="col-12" style="display: flex;align-items: center;">
                                                    {{ $order->edited_address }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @php
                            $courier = \App\Models\CourierDelivery::where("merchant_order_id", $order->reference_no ?? null)->first();
                        @endphp

                        @if(isset($courier))
                            <div class="col-md-12 text-start py-3" style="border-right:8px solid #8080801f">
                                <h4>Courier Details</h4>
                                <p><strong>Courier :</strong> {{ $courier->courier_name ?? '' }}</p>
                                <p><strong>Consignment ID :</strong> {{ $courier->consignment_id ?? '' }}</p>
                                <p><strong>Tracking Code :</strong> {{ $courier->tracking_code ?? '' }}</p>
                                <p><strong>Create
                                        Time:</strong> {{ \Carbon\Carbon::parse($courier->created_at)->format('d-m-y h:i:s A') }}
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-md-6 text-start py-3">
                <div class="row">
                    <form action="{{ route("admin.order.details.update") }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="col-md-12 text-start py-3">
                            <h4>Total Summary</h4>
                            <div class="row">
                                <div class="col-6">
                                    Subtotal
                                </div>
                                <div class="col-6">
                                    {{$order->symbol}} {{ $order->subtotal }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    Shipping Fee
                                </div>
                                <div class="col-6" style="display: flex;align-items: center;">
                                    {{$order->symbol}}
                                    @if(ModulusStatus($store->id, 122))
                                        <input type="number" style="width: 30%" class="form-control ml-2" name="shipping"
                                            id="shipping" value="{{ $order->shipping }}" step="0.01">
                                    @else
                                        {{ $order->shipping }}
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    Discount
                                </div>
                                <div class="col-6">
                                    {{$order->symbol}} {{ $order->discount }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    Tax
                                </div>
                                <div class="col-6">
                                    {{$order->symbol}} {{ $order->tax ?? 0 }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    Total
                                </div>
                                <div class="col-6">
                                    {{$order->symbol}} {{ $order->total }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    Paid
                                </div>
                                <div class="col-6">
                                    {{$order->symbol}} {{ $order->paid ?? 0 }}
                                </div>
                            </div>
                            @if($order->due > 0)
                                                    <div class="row" {{ $order->due > 0 ? '' : 'hidden' }}>
                                                        <?php
                                $advancepaymentmodules = DB::table('moduluses')
                                    ->where('name', '=', 'Advance Payment')
                                    ->where('status', '=', '1')
                                    ->first();

                                $advancepaymentmodules_id = $advancepaymentmodules->id ?? 106;

                                if ($advancepaymentmodules) {
                                    $store_id = Auth::user()->store_id;
                                    $advancepaymentstatus = DB::table('buy_moduluses')
                                        ->where('modulus_id', '=', $advancepaymentmodules_id)
                                        // ->where('store_id', '=', $store_id)
                                        ->where('status', '=', '1')
                                        ->first();
                                } else {
                                    $advancepaymentstatus = null;
                                }

                                $transaction = \App\Models\Transaction::where('order_id', $order->id)->value('mode') ?? "";
                                $mode = $transaction == "online" ? true : false;
                                                                                                                            ?>


                                                        @if($advancepaymentstatus && $mode)
                                                            <div class="col-6">
                                                                Due pay
                                                            </div>
                                                            <div class="col-6" style="display: flex;align-items: center;">
                                                                {{$order->symbol}}
                                                                <input type="number" style="width: 30%" class="form-control ml-2" name="due_pay"
                                                                    id="due_pay" placeholder="Due Pay" value="" step="0.01">

                                                                {{-- <select name="payment_type" id="" style="width: 30%" class="form-control">--}}
                                                                    {{-- <option value="cash_on"> Cash-On</option>--}}
                                                                    {{-- <option value="online"> Online</option>--}}
                                                                    {{-- </select>--}}

                                                            </div>
                                                        @endif
                                                    </div>
                            @endif
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    Total Due
                                </div>
                                <div class="col-6">
                                    {{$order->symbol}} {{ $order->due }}
                                </div>

                                @if(ModulusStatus($store->id, 122))
                                    <div class="col-md-12" style="float: right;">
                                        <button type="submit" class="col-3 btn btn-success mt-4"> Update</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function () {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>

    <script>
        function printDiv(printable) {
            var printContents = document.getElementById(printable).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function setRedxAreaName() {
            const areaSelect = document.getElementById('redx_delivery_area_id');
            const hiddenAreaName = document.getElementById('redx_delivery_area_name');

            if (!areaSelect || !hiddenAreaName) return;

            const selectedOption = areaSelect.options[areaSelect.selectedIndex];
            hiddenAreaName.value = selectedOption.getAttribute('data-name') || '';
        }
    </script>

    <script>


        // Hide all form
        const courierFormHide = () => {
            $("#pathao_form").hide();
            $("#steadfast_form").hide();
            $("#ecourier_form").hide();
            $("#redx_form").hide();
        }

        // Hide all form
        const courierActiveBtn = (courier) => {
            $("#pathao_btn").removeClass('courierActiveBtn');
            $("#steadfast_btn").removeClass('courierActiveBtn');
            $("#ecourier_btn").removeClass('courierActiveBtn');
            $("#redx_btn").removeClass('courierActiveBtn');

            $("#" + courier + "_btn").addClass('courierActiveBtn');
        }

        // Show courier form
        const courierFormShow = (courier) => {
            courierFormHide();
            courierActiveBtn(courier);
            const form = "#" + courier + "_form";
            $(form).fadeIn();
        }

        // courierFormShow("pathao");

        // Get zone
        const getZone = () => {
            let city_id = $("#city_id").val();
            let zone_list = $(".zone_list");

            // Use Blade syntax to inject the route URL into JavaScript
            const routeUrl = `{{ route('courier.getPathaoZone', ['id' => '__city_id__']) }}`;

            // Replace placeholder with actual city_id value
            const URL = routeUrl.replace('__city_id__', city_id);
            $.get(URL, function (data, status) {
                if (data.status) {
                    zone_list.html(data.data);
                } else {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.error(data.message);
                }
            });
        }

        // Get area
        const getArea = () => {
            let zone_id = $("#zone_id").val();
            let area_list = $(".area_list");

            // Use Blade syntax to inject the route URL into JavaScript
            const routeUrl = `{{ route('courier.getPathaoArea', ['id' => '__zone_id__']) }}`;

            // Replace placeholder with actual zone_id value
            const URL = routeUrl.replace('__zone_id__', zone_id);
            $.get(URL, function (data, status) {
                if (data.status) {
                    area_list.html(data.data);
                } else {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.error(data.message);
                }
            });
        }


        @if(Session::has('courier_oprn'))
            $("#courierBtn").click();
            courierFormShow("{{ session('courier_oprn') }}");
        @endif

    </script>

    <script>
            function changeStatus(params) {
                if (params == 'Cancelled') {
                    swal.fire({
                        title: 'আপনি কি অর্ডারটি বাতিল করতে চাচ্ছেন?',
                        text: "আপনি অর্ডারটি বাতিল করার পর পুনরায় ডেলিভারি করতে পারবেন না",

                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            $('#changeStatusSubmit').submit();
                            swal.fire(
                                'আপনি অর্ডারটি বাতিল করে ফেলেছেন 🫢 ',
                                'আপনি অর্ডারটি বাতিল করতে সফল হয়েছেন।',
                                'success'
                            );


                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swal.fire(
                                'Cancelled',
                                'Deletion Cancel 🥱',
                                'error'
                            );
                        }
                    });
                } else if (params == 'Delivered') {
                    swal.fire({
                        title: 'আপনি কি অর্ডারটি ডেলিভারি করতে চাচ্ছেন?',
                        text: "আপনি অর্ডারটি বাতিল করার পর পুনরায় বাতিল করতে পারবেন না",

                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            $('#changeStatusSubmit').submit();
                            swal.fire(
                                'আপনি অর্ডারটি ডেলিভারি করে ফেলেছেন 🫢 ',
                                'আপনি অর্ডারটি ডেলিভারি করতে সফল হয়েছেন।',
                                'success'
                            );


                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            swal.fire(
                                'Cancelled',
                                'Deletion Cancel 🥱',
                                'error'
                            );
                        }
                    });
                } else {
                    $('#changeStatusSubmit').submit();
                }
            }


        const submitSteadfast = (e) => {
            e.preventDefault();

            swal.fire({
                title: 'Are you sure?',
                text: "You want Send Parcel to Steadfast?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#steadfastForm').submit();
                }
            })
        }

        function debounce(func, delay) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        const orderCommentInput = document.getElementById('order_comment');
        const orderId = orderCommentInput.dataset.orderId;

        const saveOrderComment = debounce(function () {
            const comment = this.value;
            const orderCommentURL = '{{ route("admin.order.comment.update", ["order" => ":orderId"]) }}'.replace(':orderId', orderId);

            axios.post(orderCommentURL, {
                order_comment: comment
            })
                .then((response) => {
                    const message = response.data.message || "Comment saved successfully!";
                    toastr.success(message);
                })
                .catch((error) => {
                    toastr.success("Failed to save comment.");
                });
        }, 500); // 500ms delay

        orderCommentInput.addEventListener('input', saveOrderComment);

    </script>
@endpush