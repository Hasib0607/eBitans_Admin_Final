@extends('admin.layouts.main')
@push('styles')
    <style>
        .checkeds {
            /*background-color:#ff5733;*/
            border: 2px solid #ff5733 !important;
            border-radius: 10px;
        }

        .checkeds p {
        }

        .actived {
            background-color: #ff5733;
            color: #fff;
        }

        .activeds {
            border: 2px solid #ff5733 !important;
            border-radius: 10px;
        }
    </style>
@endpush
@section('content')
    @php
        if (Auth::user()->type == 'admin' || Auth::user()->type == 'dropshipper') {
            $customer = DB::table('customers')
                ->where('uid', Auth::user()->id)
                ->first();
            $store_id = $customer->active_store;
        } elseif (Auth::user()->type == 'staff') {
            $staff = DB::table('staff')
                ->where('uid', Auth::user()->id)
                ->first();
            $store_id = $staff->store_id;
        } else {
            $store_id = 0;
        }
        if ($store_id != 0) {
            $store = DB::table('stores')->where('id', $store_id)->first();
            if ($store->expiry_date <= Carbon\Carbon::now()) {
                $exp = 1;
            } else {
                $exp = 0;
            }
        }
        $planorderss = DB::table('planorders')
            ->where('store_id', $store_id)
            ->where('status', 'Processing')
            ->orderBy('id', 'DESC')
            ->first();
    @endphp

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                </div>
            </div>
        </div>

        @if (isset($planorderss))
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center d-flex align-items-center justify-content-center"
                         style="height:60vh">
                        You Have Submitted Your Payment. Please wait 12 hour For Active your store.
                    </div>
                </div>
            </div>
        @endif

        <div class="container @if (isset($planorderss)) d-none @endif">
            <div class="py-5 text-center">
                <h2>Payment form</h2>
                <p class="lead">If Your Subscription Expire. Please Payment for Renew or Extends your package</p>
            </div>
            <form action="{{ route('placeplan') }}" method="post" class="needs-validation" novalidate>
                @csrf
                <div class="row" id="">
                    <div class="col-md-7 select " style="padding-left:0px">
                        <div class="mb-4">
                            <small style="color:#f1593A;margin-top:20px">Want to change your plan <a
                                    href="{{ route('planchoose') }}" style="color:red;font-weight:bold">Click
                                    Here</a></small>
                        </div>
                        <ul style="list-style:none;padding-left:0px">
                            <input type="checkbox" name="plansss" id="plansss" style="display:none">
                            <input type="checkbox" name="addonsss" id="addonsss" style="display:none">
                            <label for="plansss">
                                <li style="float:left;padding:10px 15px;border:1px solid #dee2e6;width:90px;text-align:center"
                                    class="actived" id="planss" onclick="selectplan('plan')">Package
                                </li>
                            </label>
                            <label for="addonsss">
                                <li style="float:left;padding:10px 15px;border:1px solid #dee2e6;width:90px;text-align:center"
                                    id="addonss" onclick="selectplan('addons')">Addons
                                </li>
                            </label>
                        </ul>
                    </div>
                    <div class="col-md-7 order-md-1">
                        <input type="checkbox" name="packagedetails" id="packagedetailsss" class="packdet"
                               style="display:none" onchange="changePack()">
                        <div class="row" id="packagedetails" style="padding:10px;border:2px solid transparent">
                            <h4 class="mb-3">Package Details</h4>
                            <div class="col-md-4 mb-3">
                                <label for="firstName">Select Plan</label>
                                @php
                                    $plansss = DB::table('plans')->where('status', 'active')->get();
                                @endphp
                                <select name="plan_id" class="form-control" id="plan_id" onchange="getComboB(this)"
                                        style="background-color:#fff">
                                    <option value="0" }}
                                    " @if (Session::has('plan_id') && Session::get('plan_id') == '0')
                                        selected
                                    @endif>None
                                    </option>
                                    @if (isset($plansss) && count($plansss) > 0)
                                        @foreach ($plansss as $planss)
                                            <option value="{{ $planss->id }}"
                                                    @if (Session::has('plan_id') && Session::get('plan_id') == $planss->id) selected @endif>{{ $planss->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="firstName">Select Month</label>
                                <select name="month" class="form-control" id="month" onchange="getComboA(this)"
                                        style="background-color:#fff">
                                    <option value="1"
                                            @if (Session::has('plan_month') && Session::get('plan_month') == '1') selected @endif>
                                        1 Month
                                    </option>
                                    <option value="6"
                                            @if (Session::has('plan_month') && Session::get('plan_month') == '6') selected @endif>
                                        6 Month
                                    </option>
                                    <option value="12"
                                            @if (Session::has('plan_month') && Session::get('plan_month') == '12') selected @endif>
                                        12 Month
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3" id="load1">
                                <div class="load1">
                                    <label for="lastName">Per Month</label>
                                    <input type="number" class="form-control" id="permonth" name="permonth"
                                           placeholder="" value="{{ $plan->price }}" readonly
                                           style="background-color:#f0f2f5 !important">
                                </div>
                            </div>
                        </div>
                        </label>
                        <div class="row mt-4" id="addonsdetails" style="display:none">
                            <h4>Addons</h4>
                            <input type="checkbox" name="mobileapps" value='mobile' id="mobile" class="mobile"
                                   style="display:none" onchange="valueChanged()">
                            <input type="checkbox" name="activitylog" value='activitylog' id="activitylog"
                                   class="adminpanel" style="display:none" onchange="valueChanged12()">
                            <label class="col-md-3 mt-2" for="mobile">
                                <div class="card @if (Session::has('addons')) checkeds @endif" id="mobile1">
                                    <div class="card-body p-3">
                                        <img
                                            src="https://d3nn873nee648n.cloudfront.net/HomeImages/Concept-and-Ideas.jpg"
                                            width="100%" height="120">
                                    </div>
                                    <div class="card-footer p-2 text-center">
                                        <p style="margin-bottom:5px;line-height:20px;color:#000">Website Mobile Apps</p>
                                        <p style="line-height:20px;font-size:14px;margin-bottom:0px">BDT. 100</p>
                                        <input type="hidden" name="mbm" value="1">
                                        <select class="form-control mt-1"
                                                style="line-height:13px; @if (Session::has('addons')) display:block @else display:none @endif"
                                                onchange="changemonth(this)" name="mobileappsmonth"
                                                id="mobileappsmonth">
                                            <option value="1"
                                                    @if (Session::has('addons_month') && Session::get('addons_month') == '1') selected
                                                    @endif
                                                    style="text-align:center">1 Month
                                            </option>
                                            <option value="6"
                                                    @if (Session::has('addons_month') && Session::get('addons_month') == '6') selected
                                                    @endif
                                                    style="text-align:center">6 Month
                                            </option>
                                            <option value="12"
                                                    @if (Session::has('addons_month') && Session::get('addons_month') == '12') selected
                                                    @endif
                                                    style="text-align:center">12 Month
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </label>
                            <label class="col-md-3 mt-2" for="activitylog">
                                <div class="card @if (Session::has('activityaddons')) checkeds @endif" id="activity">
                                    <div class="card-body p-3">
                                        <img
                                            src="https://d3nn873nee648n.cloudfront.net/HomeImages/Concept-and-Ideas.jpg"
                                            width="100%" height="120">
                                    </div>
                                    <div class="card-footer p-2 text-center">
                                        <p style="margin-bottom:5px;line-height:20px;color:#000">Activity Log</p>
                                        <p style="line-height:20px;font-size:14px;margin-bottom:0px">BDT. 50</p>
                                        <input type="hidden" name="ambm" value="1">
                                        <select class="form-control mt-1"
                                                style="line-height:13px; @if (Session::has('activityaddons')) display:block @else display:none @endif"
                                                name="activitymonth" id="activitymonth">
                                            <option value="1"
                                                    @if (Session::has('activityaddons_month') && Session::get('activityaddons_month') == '1') selected
                                                    @endif
                                                    style="text-align:center">1 Month
                                            </option>
                                            <option value="6"
                                                    @if (Session::has('activityaddons_month') && Session::get('activityaddons_month') == '6') selected
                                                    @endif
                                                    style="text-align:center">6 Month
                                            </option>
                                            <option value="12"
                                                    @if (Session::has('activityaddons_month') && Session::get('activityaddons_month') == '12') selected
                                                    @endif
                                                    style="text-align:center">12 Month
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-5 order-md-2 mb-4" id="load">
                        <div class="load">
                            <h4 class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Your Order</span>
                                <span class="badge badge-secondary badge-pill"></span>
                            </h4>
                            <ul class="list-group mb-3">
                                @php
                                    if (Session::has('plan_id')) {
                                        $plan_id = Session::get('plan_id');
                                        if ($plan_id == '0') {
                                            if (Session::has('addons_total')) {
                                                $addons_price = Session::get('addons_total');
                                                $subtotal = Session::get('addons_total');
                                                $discount = 0;
                                                $total = Session::get('addons_total');
                                            } else {
                                                $addons_price = null;
                                                $subtotal = null;
                                                $discount = null;
                                                $total = null;
                                            }
                                        } else {
                                            if (Session::has('plan_month')) {
                                                $month = Session::get('plan_month');
                                            } else {
                                                $month = '1';
                                            }
                                            $plan = DB::table('plans')->where('id', $plan_id)->first();
                                            $price = $plan->price;
                                            if ($plan->discount_type == 'fixed') {
                                                if ($month == '1') {
                                                    if (Session::has('addons') || Session::has('activityaddons')) {
                                                        $subtotal = $plan->price;
                                                        $discount_price = $plan->price - $plan->onedis;
                                                        $discount = $subtotal - $discount_price;
                                                        $subtotal = Session::get('addons_total') + $subtotal;
                                                        $discount_price =
                                                            Session::get('addons_total') + $discount_price;
                                                        $addons_price = Session::get('addons_total');
                                                        $subtotal = $subtotal;
                                                        $discount = $plan->price - $plan->onedis;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    } else {
                                                        $subtotal = $plan->price;
                                                        $discount_price = $plan->price - $plan->onedis;
                                                        $discount = $subtotal - $discount_price;
                                                        $addons_price = 0;
                                                        $subtotal = $subtotal;
                                                        $discount = $plan->price - $plan->onedis;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    }
                                                } elseif ($month == '6') {
                                                    if (Session::has('addons_total')) {
                                                        $subtotal = $plan->price * 6;
                                                        $discount_price = $plan->price * 6 - $plan->sixdis * 6;
                                                        $discount = $subtotal - $discount_price;
                                                        $subtotal = Session::get('addons_total') + $subtotal;
                                                        $discount_price =
                                                            Session::get('addons_total') + $discount_price;
                                                        $addons_price = Session::get('addons_total');
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->price - $plan->sixdis) * 6;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    } else {
                                                        $subtotal = $plan->price * 6;
                                                        $discount_price = $plan->price * 6 - $plan->sixdis * 6;
                                                        $discount = $subtotal - $discount_price;
                                                        $addons_price = 0;
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->price - $plan->sixdis) * 6;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    }
                                                } elseif ($month == '12') {
                                                    if (Session::has('addons_total')) {
                                                        $subtotal = $plan->price * 12;
                                                        $discount_price = $plan->price * 12 - $plan->sixdis * 12;
                                                        $discount = $subtotal - $discount_price;
                                                        $subtotal = Session::get('addons_total') + $subtotal;
                                                        $discount_price =
                                                            Session::get('addons_total') + $discount_price;
                                                        $addons_price = Session::get('addons_total');
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->price - $plan->twelvedis) * 12;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    } else {
                                                        $subtotal = $plan->price * 12;
                                                        $discount_price = $plan->price * 12 - $plan->twelvedis * 12;
                                                        $discount = $subtotal - $discount_price;
                                                        $addons_price = Session::get('addons_total');
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->price - $plan->twelvedis) * 12;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    }
                                                }
                                            } elseif ($plan->discount_type == 'percent') {
                                                if ($month == '1') {
                                                    if (Session::has('addons_total')) {
                                                        $subtotal = $plan->price * 1;
                                                        $discount_price = $subtotal - ($plan->onedis / 100) * $subtotal;
                                                        $discount = $subtotal - $discount_price;
                                                        $discount_price =
                                                            Session::get('addons_total') + $discount_price;
                                                        $addons_price = Session::get('addons_total');
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->onedis / 100) * $subtotal;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    } else {
                                                        $subtotal = $plan->price * 1;
                                                        $discount_price = $subtotal - ($plan->onedis / 100) * $subtotal;
                                                        $addons_price = 0;
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->onedis / 100) * $subtotal;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    }
                                                } elseif ($month == '6') {
                                                    if (Session::has('addons_total')) {
                                                        $subtotal = $plan->price * 6;
                                                        $discount_price = $subtotal - ($plan->sixdis / 100) * $subtotal;
                                                        $discount = $subtotal - $discount_price;
                                                        $discount_price =
                                                            Session::get('addons_total') + $discount_price;
                                                        $addons_price = Session::get('addons_total');
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->sixdis / 100) * $subtotal;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    } else {
                                                        $subtotal = $plan->price * 6;
                                                        $discount_price = $subtotal - ($plan->sixdis / 100) * $subtotal;
                                                        $discount = $subtotal - $discount_price;
                                                        $addons_price = 0;
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->sixdis / 100) * $subtotal;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    }
                                                } elseif ($month == '12') {
                                                    if (Session::has('addons_total')) {
                                                        $subtotal = $plan->price * 12;
                                                        $discount_price =
                                                            $subtotal - ($plan->twelvedis / 100) * $subtotal;
                                                        $discount = $subtotal - $discount_price;
                                                        $discount_price =
                                                            Session::get('addons_total') + $discount_price;
                                                        $addons_price = Session::get('addons_total');
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->twelvedis / 100) * $subtotal;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    } else {
                                                        $subtotal = $plan->price * 12;
                                                        $discount_price =
                                                            $subtotal - ($plan->twelvedis / 100) * $subtotal;
                                                        $discount = $subtotal - $discount_price;
                                                        $addons_price = 0;
                                                        $subtotal = $subtotal;
                                                        $discount = ($plan->twelvedis / 100) * $subtotal;
                                                        $total = $addons_price + $subtotal - $discount;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                <input type="hidden" name="selectpackage" value="{{ Session::get('plan_id') ?? '0' }}">
                                <input type="hidden" name="subtotal" value="{{ $subtotal ?? '0' }}">
                                <input type="hidden" name="total"
                                       value="{{ $total ?? (Session::get('addons_total') ?? '0') }}">
                                <input type="hidden" name="discount" value="{{ $discount ?? '0' }}">
                                <input type="hidden" name="addons" value="{{ Session::get('addons_price') ?? '0' }}">
                                <input type="hidden" name="subtotal4" value="{{ $subtotal ?? '0' }}">
                                <input type="hidden" name="total4" value="{{ $total ?? '0' }}">
                                <input type="hidden" name="total5" value="{{ $total ?? '0' }}">
                                <input type="hidden" name="discount4" value="{{ $discount ?? '0' }}">
                                <input type="hidden" name="addons4" value="{{ Session::get('addons_price') ?? '0' }}">
                                <input type="hidden" name="store_id" value="{{ $store_id }}">
                                <li class="list-group-item @if (Session::has('plan_id') && Session::get('plan_id') == '0') d-none @else d-flex @endif justify-content-between lh-condensed"
                                    id="packagenamessss">
                                    <div>
                                        <h6 class="my-2">Package name : {{ $plan->name ?? '' }}</h6>
                                    </div>
                                    <span class="text-muted my-3">{{ $plan->price }}/monthly</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Addons (BDT)</span>
                                    <p id="addonsssss"><strong
                                            id="addonsssss">{{ Session::get('addons_total') ?? '0' }}</strong></p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Sub Total (BDT)</span>
                                    <p id="subtotaltotal"><strong
                                            id="subtotal">{{ $subtotal ?? (Session::get('addons_total') ?? '0') }}</strong>
                                    </p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Discount (BDT)</span>
                                    <p id="discount"><strong id="discount">{{ $discount ?? '0' }}</strong></p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total (BDT)</span>
                                    <p id="finaltotal"><strong
                                            id="finaltotal">{{ $total ?? (Session::get('addons_total') ?? '0') }}</strong>
                                    </p>
                                </li>
                            </ul>
                            <div class="pt-3"
                                 style="padding:15px;background-color:#fff;border:1px solid #dee2e6;border-radius:5px;">
                                <div class="row">
                                    <div class="col-md-6" style="padding-right:0px;">
                                        <h4 class="mb-3">Payment</h4>
                                        <div class="d-block my-3">
                                            <div class="custom-control custom-radio d-none">
                                                <input id="credit" name="paymentMethod" type="radio"
                                                       class="custom-control-input" value="online" required>
                                                <label class="custom-control-label" for="credit">Online</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="html" name="paymentMethod" value="bkash"
                                                       checked>
                                                <label for="html">Bkash</label><br>
                                                <input type="radio" id="css" name="paymentMethod"
                                                       value="nagad">
                                                <label for="css">Nagad</label><br>
                                            </div>
                                            <div class="" id="bkash">
                                                <label>Bkash Number</label>
                                                <br>
                                                <input id="credit" name="bkash" type="tel"
                                                       class="form-control custom-control-input" required>
                                            </div>
                                            <div class="" id="bkash1">
                                                <label>Transaction Id</label>
                                                <input id="credit" name="bkash_transaction_id" type="text"
                                                       class="form-control custom-control-input" required>
                                            </div>
                                            <div class="" id="nagad" style="display:none">
                                                <label>Nagad Number</label>
                                                <br>
                                                <input id="credit" name="nagad" type="tel"
                                                       class="form-control custom-control-input" required>
                                            </div>
                                            <div class="" id="nagad1" style="display:none">
                                                <label>Transaction Id</label>
                                                <input id="credit" name="nagad_transaction_id" type="text"
                                                       class="form-control custom-control-input" required>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6 d-flex align-items-center justify-content-center"
                                         style="padding-left:0px;padding-right:0px;">
                                        <div class="d-flex flex-column">
                                            <ul style="list-style:none" id="bkashsetps">
                                                <li>You need to payment this Bkash number 01677515579</li>
                                                <li></li>
                                                <li>Step:</li>
                                                <li>1. Dial *247# to go mobile menu.</li>
                                                <li>2. Enter "1" to select send money option</li>
                                                <li>3. Enter "01677515579" and press send</li>
                                                <li>4. Enter Amount "<span id="finaltotal1"><strong
                                                            id="finaltotal1">{{ $total ?? Session::get('addons_total') }}</strong></span>"
                                                </li>
                                                <li>5. Enter Reference "123"</li>
                                                <li>6. Enter Your Bkash PIN to confirm</li>
                                            </ul>
                                            <ul style="list-style:none;display:none" id="nagadsetps">
                                                <li>You need to payment this Nagad number 01677515579</li>
                                                <li></li>
                                                <li>Step:</li>
                                                <li>1. Dial *167# to go mobile menu.</li>
                                                <li>2. Enter "2" to select send money option</li>
                                                <li>3. Enter "01677515579" and press send</li>
                                                <li>4. Enter Amount "<span id="finaltotal12"><strong
                                                            id="finaltotal12">{{ $discount_price ?? $plan->price }}</strong></span>"
                                                </li>
                                                <li>5. Enter Reference "12"</li>
                                                <li>6. Enter Your Bkash PIN to confirm</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary ms-2" type="submit"
                                            style="width:fit-content">Continue to checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
@push('scripts')
    <script src="{{ URL::to('/') }}/js/planpurchase.js"></script>
    <script type="text/javascript">
        function getComboA(selectObject) {

            var value = selectObject.value;
            // var value=$('#month').val();
            console.log(value);
            var plan_id = $("#plan_id").find(':selected').val();
            var month = $("#month").find(':selected').val();
            $.get('plancheck', {
                plan_id: plan_id,
                month: month
            }, function () {
                $('#load').load(location.href + ' .load');
                $('#load1').load(location.href + ' .load1');
                console.log("output select")
            });
        }

        function changemonth(selectObject) {
            debugger;
            var name = "mobileapps";
            var month = $("#mobileappsmonth").find(':selected').val();
            $.get('addonsadd', {
                name: name,
                month: month
            }, function () {
                $('#load').load(location.href + ' .load');
                $('#load1').load(location.href + ' .load1');
                console.log("output select In")
            });
        }
    </script>
@endpush
