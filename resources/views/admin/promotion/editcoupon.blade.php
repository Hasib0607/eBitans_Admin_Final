@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative border-radius-lg">

        {{--top navigation--}}
        @include('admin.promotion.share.promotion-nav')
        <div class="container-fluid mt-4" id="toplist">
            @if(isset($coupon) && $coupon=='1' || Auth::user()->type=='admin' || Auth::user()->type == 'dropshipper')
                <div class="row">
                    <div class="col-md-6">
                        <h4>@if(Session::has('lang') && Session::get('lang')=='bn')
                                আপডেট   কুপন
                            @else
                                Update Coupon
                            @endif</h4>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li class="active"><a
                                    href="{{URL::to('/')}}/promotions/coupon">@if(Session::has('lang') && Session::get('lang')=='bn')
                                        তালিকায় ফিরে যান
                                    @else
                                        Back to List
                                    @endif </a></li>
                        </ul>
                    </div>
                </div>
                <div class="row mt-5 productlist">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <form action="{{route('admin.coupon.update',$coupon->id)}}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                নাম
                                            @else
                                                Name
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="staticEmail" name="name"
                                                   value="{{$coupon->name}}">
                                            @error('name')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                কোড
                                            @else
                                                Code
                                            @endif<span class="req">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" id="staticEmail" name="code"
                                                   value="{{$coupon->code}}">
                                            @error('code')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                শুরুর তারিখ
                                            @else
                                                Start Date
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" id="staticEmail"
                                                   value="{{$coupon->start_date}}" name="start_date">
                                            @error('start_date')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                শেষ তারিখ
                                            @else
                                                End Date
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" id="staticEmail"
                                                   value="{{$coupon->end_date}}" name="end_date">
                                            @error('end_date')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ন্যূনতম ক্রয়
                                            @else
                                                Minimum Purchase ({{$coupon->symbol}})
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-4">
                                            <input type="number" step="0.01" class="form-control" id="staticEmail"
                                                   value="{{$coupon->min_purchase}}" name="min_purchase">
                                            @error('min_purchase')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                সর্বোচ্চ ক্রয়
                                            @else
                                                Maximum Purchase({{$coupon->symbol}})
                                            @endif </label>
                                        <div class="col-md-4">
                                            <input type="number" step="0.01" class="form-control" id="staticEmail"
                                                   value="{{$coupon->max_purchase}}" name="max_purchase">
                                            @error('max_purchase')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ছাড়ের ধরন
                                            @else
                                                Discount Type
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="discount_type" id="discount_type"
                                                    onchange="discountTypeChange()">
                                                <option value="percent"
                                                        @if($coupon->discount_type=='percent') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                        পার্সেন্ট
                                                    @else
                                                        Percent
                                                    @endif </option>
                                                <option value="fixed"
                                                        @if($coupon->discount_type=='fixed') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                        ফিক্সড
                                                    @else
                                                        Fixed
                                                    @endif </option>
                                                <option value="delivery_charge"
                                                        @if($coupon->discount_type=='delivery_charge') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                        ডেলিভারি চার্জ
                                                    @else
                                                        Delivery charge
                                                    @endif</option>
                                            </select>
                                            @error('discount_type')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row" id="discount_input"
                                         @if($coupon->discount_type=='delivery_charge') style="display: none" @endif>
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                ডিসকাউন্ট মূল্য
                                            @else
                                                Discount Amount
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-4">
                                            <input type="number" step="0.01" class="form-control" id="staticEmail"
                                                   name="discount_amount" value="{{$coupon->discount_amount}}">
                                            @error('discount_amount')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                প্রতি ব্যবহারকারী সর্বোচ্চ ব্যবহার
                                            @else
                                                Max Use per User
                                            @endif <span class="req">*</span></label>
                                        <div class="col-md-4">
                                            <input type="number" step="0.01" class="form-control" id="staticEmail"
                                                   value="{{$coupon->max_use}}" name="max_use">
                                            @error('max_use')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="shipping_area"
                                               class="col-md-2 col-form-label">
                                            @if(Session::has('lang') && Session::get('lang')=='bn')
                                                শিপিং এলাকা
                                            @else
                                                Shipping Area
                                            @endif
                                        </label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="shipping_area" id="shipping_area">
                                                <option value="" @if($coupon->shipping_area=='') selected @endif>
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        শিপিং এরিয়া নির্বাচন করুন
                                                    @else
                                                        Select Shipping Area
                                                    @endif
                                                </option>
                                                @if(isset($setting->shipping_area_1) && !is_null($setting->shipping_area_1) && !empty($setting->shipping_area_1))
                                                    <option value="1" @if($coupon->shipping_area=='1') selected @endif>
                                                        {{ $setting->shipping_area_1 }}
                                                    </option>
                                                @endif
                                                @if(isset($setting->shipping_area_2) && !is_null($setting->shipping_area_2) && !empty($setting->shipping_area_2))
                                                    <option value="2" @if($coupon->shipping_area=='2') selected @endif>
                                                        {{ $setting->shipping_area_2 }}
                                                    </option>
                                                @endif
                                                @if(isset($setting->shipping_area_3) && !is_null($setting->shipping_area_3) && !empty($setting->shipping_area_3))
                                                    <option value="3" @if($coupon->shipping_area=='3') selected @endif>
                                                        {{ $setting->shipping_area_3 }}
                                                    </option>
                                                @endif
                                            </select>
                                            @error('shipping_area')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="payment_method" class="col-md-2 col-form-label">
                                            @if(Session::has('lang') && Session::get('lang')=='bn')
                                                পেমেন্ট মেথড
                                            @else
                                                Payment Method
                                            @endif
                                        </label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="payment_method" id="payment_method">
                                                <option value="">
                                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                        শপেমেন্ট মেথড নির্বাচন করুন
                                                    @else
                                                        Select Payment Method
                                                    @endif
                                                </option>
                                                @if(isset($setting->cod) && $setting->cod  == "active")
                                                    <option value="cod"
                                                            @if($coupon->payment_method=='cod') selected @endif>
                                                        {{ $setting->cod_text ?? "Cash On Delivery" }}
                                                    </option>
                                                @endif

                                                @if(isset($setting->online) && $setting->online  == "active")
                                                    <option value="online"
                                                            @if($coupon->payment_method=='ssl') selected @endif>
                                                        {{ "SSL Payment" }}
                                                    </option>
                                                @endif

                                                @if(isset($setting->bkash) && $setting->bkash  == "active")
                                                    <option value="bkash"
                                                            @if($coupon->payment_method=='bkash') selected @endif>
                                                        {{ $setting->bkash_text ?? "BKash Payment" }}
                                                    </option>
                                                @endif

                                                @if(isset($setting->nagad) && $setting->nagad  == "active")
                                                    <option value="nagad"
                                                            @if($coupon->payment_method=='nagad') selected @endif>
                                                        {{ "Nagad Payment" }}
                                                    </option>
                                                @endif

                                                @if(isset($setting->uddoktapay) && $setting->uddoktapay  == "active")
                                                    <option value="uddoktapay"
                                                            @if($coupon->payment_method=='uddoktapay') selected @endif>
                                                        {{ $setting->uddoktapay_text ?? "Uddokta Pay" }}
                                                    </option>
                                                @endif

                                                @if (ModulusStatus($store_id, 106))
                                                    <option value="ap"
                                                            @if($coupon->payment_method=='ap') selected @endif>
                                                        {{ $setting->ap_text ?? "Advance Payment" }}
                                                    </option>
                                                @endif

                                                @php
                                                    $marchenPayment = \App\Models\MarchantPaymentGetway::where("store_id", $store_id)->first();
                                                    $amarPayStatus = isset($marchenPayment->amarpay) && $marchenPayment->amarpay == 1 ? 1 : 0;
                                                @endphp
                                                @if (ModulusStatus($store_id, 125) && $amarPayStatus)
                                                    <option value="amarpay"
                                                            @if($coupon->payment_method=='amarpay') selected @endif>
                                                        {{ $setting->amarpay_text ?? "Amar Pay" }}
                                                    </option>
                                                @endif
                                            </select>
                                            @error('payment_method')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="auto_apply" class="col-md-2 col-form-label">
                                            @if(Session::has('lang') && Session::get('lang')=='bn')
                                                অটো প্রয়োগ
                                            @else
                                                Auto Apply
                                            @endif
                                        </label>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch is-filled pt-3"
                                                 style="text-align:center;">
                                                <input class="form-check-input" type="checkbox"
                                                       id="flexSwitchCheckChecked" name="auto_apply"
                                                       style="margin:0 auto;"
                                                       @if($coupon->auto_apply=='1') checked @endif>
                                                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                            </div>

                                            @error('auto_apply')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                স্ট্যাটাস
                                            @else
                                                Status
                                            @endif </label>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch is-filled" style="text-align:center;">
                                                <input class="form-check-input" type="checkbox"
                                                       id="flexSwitchCheckChecked" name="status" style="margin:0 auto;"
                                                       @if($coupon->status=='active') checked="" @endif>
                                                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                            </div>
                                            @error('status')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="position" class="col-md-2 col-form-label"></label>
                                        <div class="col-md-4">
                                            <button type="submit"
                                                    class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    আপডেট
                                                @else
                                                    Update
                                                @endif </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        const discountTypeChange = () => {
            let discount_type = $("#discount_type").val();
            let discount_input = $("#discount_input");

            if (discount_type === "delivery_charge") {
                discount_input.hide();
            } else {
                discount_input.show();
            }

        }

    </script>
@endpush
