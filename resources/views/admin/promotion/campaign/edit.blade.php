@extends('admin.layouts.main')
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css"
          rel="stylesheet"/>
    <style>
        .rightmenu li {
            float: left !important;
            padding: 1px 16px !important;
        }

        .select2-container .select2-selection--multiple {
            min-height: 40px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: 0px solid black !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 4px !important;
            padding: 0 !important;
            padding-left: 20px !important;
        }

        select#shipping_area {
            width: 100% !important;
        }

        span.select2.select2-container.select2-container--default.select2-container--below {
            width: 100% !important;
        }

        span.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus.select2-container--open {
            width: 100% !important;
        }

        span.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus {
            width: 100% !important;
        }

        span.select2.select2-container.select2-container--default.select2-container--focus {
            width: 100% !important;
        }

        span.select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        textarea.select2-search__field {
            margin-bottom: 5px;
        }

        ul#select2-shipping_area-container {
            display: inline-flex;
            margin-bottom: 0;
        }

        .rightmenu ul li {
            float: left;
            padding: 1px 15px;
            border: 1px solid #5e4c4c33;
        }

        .form_check_switch {
            padding-left: 0;
            margin-top: 15px;
        }
    </style>
@endpush
@section('content')
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="productupdate" action="{{route('admin.campaign.productupdate',$campaign->id)}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="perami" name="ids" value="{{ $campaign->id }}">
                    <div class="modal-header">
                        <div class="row" style="width:100%">
                            <div class="col-md-9">
                                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                           aria-label="Dollar amount (with dot and two decimal places)" id="taskfilter">
                                    <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                            class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="text2" id="selectids">

                        <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
                            <table class="table table-stripped" id="taskfilterresult">
                                <thead>
                                <tr>
                                    <th><label><input type="checkbox" name="ids" id="checkedAll"></label></th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($products))
                                    @foreach($products as $product)
                                        @if(isset($product))
                                            <tr>
                                                <td><input type="checkbox" name="selectedid" id="id"
                                                           value="{{$product->id}}" class="checkSingle"></td>
                                                <td>{{Str::of($product->name)->limit(20)}}</td>
                                                <td>{{$product->SKU}}</td>
                                                <td>{{$product->symbol}}{{$product->regular_price}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="categoryupdate" action="{{route('admin.campaign.categoryupdate',$campaign->id)}}"
                      method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="text3" id="selectidss">
                        <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
                            <table class="table table-stripped">
                                <thead>
                                <tr>
                                    <th><label><input type="checkbox" name="idss" id="checkedAlls"></label></th>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($categories)>0)
                                    @foreach($categories as $category)
                                        <tr>
                                            <td><input type="checkbox" name="selectedids" id="ids"
                                                       value="{{$category->id}}"
                                                       class="checkSingles"></td>
                                            <td>{{$category->name}} {{$category->p_name ? "( $category->p_name )" : ''}}</td>
                                                <?php $ct = null; ?>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        {{--top navigation--}}
        @include('admin.promotion.share.promotion-nav')
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>@if(Session::has('lang') && Session::get('lang')=='bn')
                            প্রচারাভিযান আপডেট করুন
                        @else
                            Update Campaign
                        @endif</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="{{URL::to('/')}}/promotion/campaign"
                               class="btn btn-primary"
                               style="display:block;border-radius:0px !important">@if(Session::has('lang') && Session::get('lang')=='bn')
                                    তালিকায় ফিরে যান
                                @else
                                    Back to List
                                @endif
                            </a>
                        </li>
                        <li style="padding:0px;border:0px;">
                            <a href="#"
                               style="display:block;border-radius:0px !important"
                               class="btn btn-secondary">@if(Session::has('lang') && Session::get('lang')=='bn')
                                    এক্সপোর্ট
                                @else
                                    Export
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <input type="hidden" value="{{$campaign['symbol']}}" id="symbol">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.campaign.update',$campaign->id)}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="staticEmail"
                                           class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            নাম
                                        @else
                                            Name
                                        @endif </label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="staticEmail"
                                               value="{{$campaign->name}}" name="name" placeholder="Campaign Name">
                                        @error('name')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail"
                                           class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            দৈর্ঘ্য
                                        @else
                                            Length
                                        @endif </label>
                                    <div class="col-md-4">
                                        <select class="form-control" name="length" id="length">
                                            <option>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    নির্বাচন করুন
                                                @else
                                                    Select
                                                @endif</option>
                                            <option value="date_range"
                                                    @if($campaign->length_type=="date_range") selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    তারিখের পরিসীমা
                                                @else
                                                    Date Range
                                                @endif </option>
                                            <option value="specific_date"
                                                    @if($campaign->length_type=="specific_date") selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    নির্দিষ্ট তারিখ
                                                @else
                                                    Specific Date
                                                @endif</option>
                                            <!--<option value="repeat_date" @if($campaign->length_type=="repeat_date")
                                                selected























                                            @endif>Repeat Date</option>-->
                                        </select>
                                        @error('length')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row" id="specific_date"
                                     style="display:{{$campaign->length_type == "specific_date" ? '': 'none' }}">
                                    <label for="staticEmail"
                                           class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            তারিখ
                                        @else
                                            Date
                                        @endif </label>
                                    <div class="col-md-4">
                                        <input type="date" class="form-control" value="{{$campaign->specific_dates}}"
                                               id="staticEmail" name="specific_date">
                                        @error('specific_date')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row"
                                     style="display:{{ $campaign->length_type=="specific_date" ? 'none': '' }}">
                                    <div class="mb-3 row" id="date_range">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                শুরুর তারিখ
                                            @else
                                                Start Date
                                            @endif </label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" id="staticEmail"
                                                   value="{{$campaign->start_date}}" name="start_date">
                                            @error('start_date')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row" id="date_range1">
                                        <label for="staticEmail"
                                               class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                শেষ তারিখ
                                            @else
                                                End Date
                                            @endif </label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" id="staticEmail"
                                                   value="{{$campaign->end_date}}" name="end_date">
                                            @error('end_date')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="staticEmail"
                                           class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            সময়
                                        @else
                                            Time
                                        @endif </label>
                                    <div class="col-md-4">
                                        <input type="checkbox" class="times" name="time" id="time"
                                               @if(isset($campaign->start_time) && isset($campaign->end_time)) checked
                                               @endif value="1" onchange="valueChanged()">
                                        @error('time')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>


                                <div class="mb-3 row timerange"
                                     @if(empty($campaign->start_time) && empty($campaign->end_time)) style="display: none" @endif>
                                    <label for="staticEmail" class="col-md-2 col-form-label"></label>
                                    <div class="col-md-4">
                                        <input type="time" class="form-control" name="start_time"
                                               value="{{$campaign->start_time}}" id="start_time">
                                        @error('start_time')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                        <br>
                                        To
                                        <br>
                                        <input type="time" class="form-control" name="end_time"
                                               value="{{$campaign->end_time}}" id="end_time">
                                        @error('end_time')
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
                                        @endif </label>
                                    <div class="col-md-4">
                                        <select name="discount_type" class="form-control" id="discount_type"
                                                onchange="discountTypeChange()">
                                            <option>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    নির্বাচন করুন
                                                @else
                                                    Select
                                                @endif </option>
                                            <option value="fixed"
                                                    @if($campaign->discount_type=='fixed') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    ফিক্সড
                                                @else
                                                    Fixed
                                                @endif </option>
                                            <option value="percent"
                                                    @if($campaign->discount_type=='percent') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    পার্সেন্ট
                                                @else
                                                    Percent
                                                @endif </option>
                                            <option value="delivery_charge"
                                                    @if($campaign->discount_type=='delivery_charge') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    ডেলিভারি চার্জ
                                                @else
                                                    Delivery charge
                                                @endif </option>
                                        </select>
                                        @error('discount_type')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row" id="discount_input"
                                     @if($campaign->discount_type=='delivery_charge') style="display: none" @endif>
                                    <label for="staticEmail"
                                           class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            ডিসকাউন্ট মূল্য
                                        @else
                                            Discount Amount
                                            (<strong id="discountPercentange"
                                                     style="color: #ff5733;">{{$campaign->symbol}}</strong>)
                                        @endif </label>
                                    <div class="col-md-4">
                                        <input type="text" name="discount_amount" value="{{$campaign->discount_amount}}"
                                               class="form-control">
                                        @error('discount_amount')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row" id="shipping_area_div"
                                     style="{{ $campaign->discount_type == 'delivery_charge' ? '' : 'display: none' }}">
                                    <label for="shipping_area"
                                           class="col-md-2 col-form-label">
                                        @if(Session::has('lang') && Session::get('lang')=='bn')
                                            শিপিং এলাকা
                                        @else
                                            Shipping Area
                                        @endif
                                    </label>
                                    <div class="col-md-4">
                                        <select class="form-control" name="shipping_area[]" id="shipping_area" multiple>
                                            @if(isset($setting->shipping_area_1) && !is_null($setting->shipping_area_1) && !empty($setting->shipping_area_1))
                                                <option value="1"
                                                        @if(in_array('1', explode(',', $campaign->shipping_area))) selected @endif>
                                                    {{ $setting->shipping_area_1 }}
                                                </option>
                                            @endif
                                            @if(isset($setting->shipping_area_2) && !is_null($setting->shipping_area_2) && !empty($setting->shipping_area_2))
                                                <option value="2"
                                                        @if(in_array('2', explode(',', $campaign->shipping_area))) selected @endif>
                                                    {{ $setting->shipping_area_2 }}
                                                </option>
                                            @endif
                                            @if(isset($setting->shipping_area_3) && !is_null($setting->shipping_area_3) && !empty($setting->shipping_area_3))
                                                <option value="3"
                                                        @if(in_array('3', explode(',', $campaign->shipping_area))) selected @endif>
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
                                    <label for="staticEmail"
                                           class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            স্ট্যাটাস
                                        @else
                                            Status
                                        @endif </label>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch is-filled form_check_switch"
                                             style="text-align:center;">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                                   name="status" style="margin:0 auto;"
                                                   @if($campaign->status=='active')  checked="" @endif>
                                            <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                        </div>
                                        @error('status')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="staticEmail"
                                           class="col-md-2 col-form-label">@if(Session::has('lang') && Session::get('lang')=='bn')
                                            প্রচারের ধরন
                                        @else
                                            Campaign Type
                                        @endif </label>
                                    <div class="col-md-4">
                                        <select class="form-control" name="campaign_type" id="campaign_type">
                                            <option>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    নির্বাচন করুন
                                                @else
                                                    Select
                                                @endif </option>
                                            <option value="product"
                                                    @if($campaign->campaign_type=='product') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    পণ্য
                                                @else
                                                    Product
                                                @endif </option>
                                            <option value="category"
                                                    @if($campaign->campaign_type=='category') selected @endif>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                    বিভাগ
                                                @else
                                                    Category
                                                @endif </option>
                                        </select>
                                        @error('campaign_type')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <!--<input type="hidden" name="text2" id="selectids">-->
                                <!--<input type="hidden" name="text3" id="selectidss">-->

                                <div class="mb-3 row" id="editPro"
                                     style="display:{{ $campaign->campaign_type=='product' ? '':'none' }}">


                                    <div class="row selectrowproduct">
                                        <div class="col-md-8 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                            পণ্য সম্পাদনা করুন
                                                        @else
                                                            Edit Product
                                                        @endif</h6>
                                                </div>
                                                <div class=" card-body">
                                                    <div class="table-responsive"
                                                         style="max-height:400px;overflow-y:auto;">
                                                        <table class="table table-stripped">
                                                            <thead>
                                                            <tr>
                                                                <th>
                                                                    <input type="checkbox" name="idss"
                                                                           id="checkedAllss">
                                                                </th>
                                                                <th>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                                        নাম
                                                                    @else
                                                                        Name
                                                                    @endif</th>
                                                                <th>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                                        এসকেইউ
                                                                    @else
                                                                        SKU
                                                                    @endif</th>
                                                                <th>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                                        দাম
                                                                    @else
                                                                        Price
                                                                    @endif</th>
                                                                <th>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                                        ডিলিট
                                                                    @else
                                                                        Delete
                                                                    @endif</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if(isset($campaign_products))
                                                                @foreach($campaign_products as $product)
                                                                    @if(isset($product))
                                                                        <tr>
                                                                            <td>
                                                                                <input type="checkbox"
                                                                                       name="selectedidss" id="idss"
                                                                                       value="{{$product->id}}"
                                                                                       class="checkSingless">
                                                                            </td>
                                                                            <td>{{Str::of($product->name)->limit(20)}}</td>
                                                                            <td>{{$product->SKU}}</td>
                                                                            <td>{{$product->symbol}}{{$product->regular_price}}</td>
                                                                            <td>
                                                                                <a href="{{URL::to('/')}}/removefromcampro/{{$campaign->id}}/{{$product->id}}">
                                                                                    <img
                                                                                        src="https://admin.ebitans.com/img/delete.png"
                                                                                        width="25px" height="25px">
                                                                                </a></td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                        <button type="button" class='btn btn-primary' id="campprodel">
                                                            Delete
                                                        </button>
                                                        <button type="button" style="float: right;" class="btn btn-info"
                                                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            @if(Session::has('lang') && Session::get('lang')=='bn')
                                                                পণ্য যোগ করুন
                                                            @else
                                                                Add Product
                                                            @endif
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row" id="editCate"
                                     style="display:{{ $campaign->campaign_type == 'category' ? '': 'none' }}">

                                    <div class="row selectrowproduct">
                                        <div class="col-md-8 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6>
                                                        @if(Session::has('lang') && Session::get('lang')=='bn')
                                                            বিভাগ সম্পাদনা করুন
                                                        @else
                                                            Edit Category
                                                        @endif
                                                    </h6>
                                                </div>
                                                <div id="cateEIDpro" class="card-body">
                                                    <div class="table-responsive"
                                                         style="max-height:400px;overflow-y:auto;">
                                                        <table class="table table-stripped">
                                                            <thead>
                                                            <tr>
                                                                <th>
                                                                    <input type="checkbox" name="idsss"
                                                                           id="checkedAllsss">
                                                                </th>
                                                                <th>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                                        নাম
                                                                    @else
                                                                        Name
                                                                    @endif</th>
                                                                <th>@if(Session::has('lang') && Session::get('lang')=='bn')
                                                                        ডিলিট
                                                                    @else
                                                                        Delete
                                                                    @endif</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if(count($campaign_categories) > 0)
                                                                @foreach($campaign_categories as $category)
                                                                    @if(isset($category))
                                                                        <tr>
                                                                            <td>
                                                                                <input type="checkbox"
                                                                                       name="selectedidsss"
                                                                                       id="idsss"
                                                                                       value="{{$category->id}}"
                                                                                       class="checkSinglesss">
                                                                            </td>
                                                                            <td>{{$category->name}} {{$category->p_name ? "( $category->p_name )" : ""}} </td>
                                                                            <td>
                                                                                <a href="{{URL::to('/')}}/removefromcamcat/{{$campaign->id}}/{{$category->id}}">
                                                                                    <img
                                                                                        src="https://admin.ebitans.com/img/delete.png"
                                                                                        width="25px" height="25px">
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                        <button type="button" class="btn btn-primary" id="deletecatcam">
                                                            Delete
                                                        </button>

                                                        <button type="button" class="btn btn-info" style="float: right;"
                                                                data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                                            @if(Session::has('lang') && Session::get('lang')=='bn')
                                                                বিভাগ যোগ করুন
                                                            @else
                                                                Add Category
                                                            @endif
                                                        </button>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3 row">
                                    <label for="position" class="col-md-12 col-form-label"></label>
                                    <div class="col-md-12">
                                        <button type="submit"
                                                class="btn btn-info">@if(Session::has('lang') && Session::get('lang')=='bn')
                                                আপডেট
                                            @else
                                                Update
                                            @endif</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <form action="{{route('admin.multipledeletecampro')}}" method="post" id="camprodelform">
                            @csrf
                            <input type="hidden" name="campid" value="{{$campaign->id}}">
                            <input type="hidden" name="text31" id="ps">
                        </form>
                        <form action="{{route('admin.multipledeletecamcat')}}" method="post" id="camcatdelform">
                            @csrf
                            <input type="hidden" name="campid" value="{{$campaign->id}}">
                            <input type="hidden" name="text31" id="pc">
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <!--<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>-->

    <script>
        $(document).ready(function () {
            $("#productupdate").on("submit", function (event) {
                event.preventDefault();
                $('#exampleModal').modal('hide');
                $("#cateEIDpro").attr("");

                var formValues = $(this).serialize();

                $.post("{{route('admin.campaign.productupdate', $campaign->id)}}", formValues, function (data) {
                    // Display the returned data in browser
                    // console.log(data);
                });
            });
        });

        function valueChanged() {
            if ($('.times').is(":checked"))
                $(".timerange").show();
            else
                $(".timerange").hide();
        }

    </script>


    <script>
        $(document).ready(function () {
            $("#categoryupdate").on("submit", function (event) {
                event.preventDefault();
                $('#exampleModal1').modal('hide');
                var formValues = $(this).serialize();

                $.post("{{route('admin.campaign.categoryupdate',$campaign->id)}}", formValues, function (data) {
                    // Display the returned data in browser
                    // console.log(data);

                });
            });
        });
    </script>



    <script>


        $(function () {
            // $(".timerange").hide();
            // $('#date_range').hide();
            // $('#date_range1').hide();
            // $('#specific_date').hide();
            $('#repeat_date').hide();

            $('#length').change(function () {
                if ($('#length').val() == 'date_range') {
                    $('#date_range').show();
                    $('#date_range1').show();
                    $('#specific_date').hide();
                    // $('#repeat_date').hide();
                } else if ($('#length').val() == 'specific_date') {
                    $('#date_range').hide();
                    $('#date_range1').hide();
                    $('#specific_date').show();
                    $('#repeat_date').hide();
                } else if ($('#length').val() == 'repeat_date') {
                    $('#date_range').hide();
                    $('#date_range1').hide();
                    $('#specific_date').hide();
                    $('#repeat_date').show();
                } else {
                    // $('#date_range').hide();
                    // $('#date_range1').hide();
                    // $('#specific_date').hide();
                    // $('#repeat_date').hide();
                }
            });
        });

        //     $('.date').datepicker({
        //     multidate: true
        // });
        $('#campprodel').on('click', function () {
            $('#camprodelform').submit();
        })
        $('#deletecatcam').on('click', function () {
            $('#camcatdelform').submit();
        })
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
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
            $('#shipping_area').select2();
        });
        $(document).ready(function () {
            $("#checkedAll").change(function () {
                if (this.checked) {
                    $(".checkSingle").each(function () {
                        this.checked = true;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                } else {
                    $(".checkSingle").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectids").val(valuesArray);
                        $("#selectdelids").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAll").prop("checked", true);
                    }
                } else {
                    $("#checkedAll").prop("checked", false);
                    var valuesArray = $('input[name="selectedid"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#selectids").val(valuesArray);
                    $("#selectdelids").val(valuesArray);
                }
            });
        });
        $(document).ready(function () {
            $("#checkedAllss").change(function () {
                if (this.checked) {
                    $(".checkSingless").each(function () {
                        this.checked = true;
                        var valuesArray = $('input[name="selectedidss"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#ps").val(valuesArray);
                        $("#selectdelidss").val(valuesArray);
                    });
                } else {
                    $(".checkSingless").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#ps").val(valuesArray);
                    $("#selectdelidss").val(valuesArray);
                }
            });
            $(".checkSingless").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSingless").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedidss"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#ps").val(valuesArray);
                        $("#selectdelidss").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAllss").prop("checked", true);
                    }
                } else {
                    $("#checkedAllss").prop("checked", false);
                    var valuesArray = $('input[name="selectedidss"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#ps").val(valuesArray);
                    $("#selectdelidss").val(valuesArray);
                }
            });
        });
        $(document).ready(function () {
            $("#checkedAllsss").change(function () {
                if (this.checked) {
                    $(".checkSinglesss").each(function () {
                        this.checked = true;
                        var valuesArray = $('input[name="selectedidsss"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#pc").val(valuesArray);
                        $("#selectdelidsss").val(valuesArray);
                    });
                } else {
                    $(".checkSinglesss").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#pc").val(valuesArray);
                    $("#selectdelidsss").val(valuesArray);
                }
            });
            $(".checkSinglesss").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;
                    $(".checkSinglesss").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedidsss"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#pc").val(valuesArray);
                        $("#selectdelidsss").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAllsss").prop("checked", true);
                    }
                } else {
                    $("#checkedAllsss").prop("checked", false);
                    var valuesArray = $('input[name="selectedidsss"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    $("#pc").val(valuesArray);
                    $("#selectdelidsss").val(valuesArray);
                }
            });
        });
        $(function () {
            $('#products').hide();
            $('#categorys').hide();
            $('#campaign_type').change(function () {
                if ($('#campaign_type').val() == 'product') {
                    $('#products').show();
                    $('#editPro').show();
                    $('#categorys').hide();
                    $('#editCate').hide();
                } else if ($('#campaign_type').val() == 'category') {
                    $('#products').hide();
                    $('#editPro').hide();
                    $('#categorys').show();
                    $('#editCate').show();
                } else {
                    $('#products').hide();
                    $('#categorys').hide();
                }
            });
        });

        $(document).ready(function () {
            $("#checkedAlls").change(function () {
                debugger;
                if (this.checked) {
                    $(".checkSingles").each(function () {
                        this.checked = true;
                        debugger;
                        var valuesArray = $('input[name="selectedids"]:checked').map(function () {
                            return this.value;
                        }).get().join(",");
                        $("#selectidss").val(valuesArray);
                        $("#selectdelidss").val(valuesArray);
                    });
                } else {
                    debugger;
                    $(".checkSingles").each(function () {
                        this.checked = false;
                    });
                    var valuesArray = '';
                    $("#selectidss").val(valuesArray);
                    $("#selectdelidss").val(valuesArray);
                }
            });
            $(".checkSingles").click(function () {
                if ($(this).is(":checked")) {
                    debugger;
                    var isAllChecked = 0;
                    $(".checkSingles").each(function () {
                        if (!this.checked)
                            isAllChecked = 1;
                        var valuesArray = $('input[name="selectedids"]:checked').map(function () {
                            return this.value;

                        }).get().join(",");
                        debugger;
                        $("#selectidss").val(valuesArray);
                        debugger;
                        $("#selectdelidss").val(valuesArray);
                    });
                    if (isAllChecked == 0) {
                        $("#checkedAlls").prop("checked", true);
                    }
                } else {
                    $("#checkedAlls").prop("checked", false);
                    var valuesArray = $('input[name="selectedids"]:checked').map(function () {
                        return this.value;
                    }).get().join(",");
                    debugger;
                    $("#selectidss").val(valuesArray);
                    debugger;
                    $("#selectdelidss").val(valuesArray);
                }
            });
        });


        const discountTypeChange = () => {

            if ($("#discount_type").val() == 'percent') {
                $("#discountPercentange").text('%');
            } else {
                var symbol = $('#symbol').val();
                $("#discountPercentange").text(symbol);
            }

            let discount_type = $("#discount_type").val();
            let discount_input = $("#discount_input");
            let shipping_area_div = $("#shipping_area_div");

            if (discount_type === "delivery_charge") {
                discount_input.hide();
                shipping_area_div.show();
            } else if (discount_type === "fixed" || discount_type === "percent") {
                discount_input.show();
                shipping_area_div.hide();
            } else {
                discount_input.hide();
                shipping_area_div.hide();
            }


        }

    </script>
@endpush
