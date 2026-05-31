@extends('admin.layouts.main')
@section('content')
    {{-- styles --}}
    @include('superadmin.addons.share.style')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        {{-- addons navbar --}}
        @include('superadmin.addons.share.addons-nav', ['addons'=>true])
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                {{-- header --}}
                <div class="col-md-6">
                    <h4>All Addons</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="javascript:void(0)" class="btn btn-primary" onclick="createNew()"
                               style="display:block;border-radius:0px !important">Create New</a></li>
                    </ul>
                </div>
                {{-- create model --}}
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{route('superadmin.addons.store')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create Addons</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- model input field --}}
                                    <div class="form-group py-3">
                                        <label for="addons_name">Addons Name</label>
                                        <input type="text" class="form-control" id="addons_name" name="title" value=""
                                               placeholder="Enter addons title">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group py-3">
                                                <input type="radio" id="oneTime" name="type" value="oneTime"> <label
                                                    for="oneTime">One Time</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group py-3">
                                                <input type="radio" id="monthly" name="type" value="monthly"> <label
                                                    for="monthly">Monthly</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group py-3">
                                                <input type="radio" id="counter" name="type" value="counter"> <label
                                                    for="counter">Counter</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="monthIncre">
                                        <div class="col-md-5">
                                            <div class="form-group pt-3">
                                                <label for="price">Addons price</label>
                                                <input style="display: inline-block;" type="number" class="form-control"
                                                       id="price" min="0" step="0.01" name=mprice[]" value=""
                                                       placeholder="Enter addons price">

                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="price">Addons price(USD)</label>
                                                <input type="number" class="form-control"
                                                       id="price" min="0" step="0.01" name=mouth_usd_price[]" value=""
                                                       placeholder="Enter addons price">

                                            </div>
                                        </div>
                                        <div class="col-md-4 px-0">
                                            <div class="form-group pt-3">
                                                <label for="offerPrice">Offer price</label>
                                                <input type="number"
                                                       class="form-control" min="0" step="0.01" id="offerPrice"
                                                       name=mofferPrice[]" value=""
                                                       placeholder="Enter addons price">
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="offerPrice">Offer price(USD)</label>
                                                <input type="number"
                                                       class="form-control" min="0" step="0.01" id="offerPrice"
                                                       name=mouth_usd_offer_price[]" value=""
                                                       placeholder="Enter addons price">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group pt-3">
                                                <label for="month">Month</label>
                                                <input type="number" class="form-control" id="month" name="month[]"
                                                       value="" placeholder="Enter Month">
                                            </div>
                                            <div style="padding-top: 35px" class="form-group pb-3">
                                                <button type="button" id="addNewh" class="btn btn-info m-0 mb-1 w-100">
                                                    Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="countIncre">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" id="name" name="name[]" value=""
                                                       placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" id="addNewhf" class="btn btn-info m-0 mb-1 w-100">+
                                            </button>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group py-3">
                                                <label for="numberOfValue">Value</label>
                                                <input type="number" class="form-control" id="numberOfValue"
                                                       name="numberOfValue[]" value=""
                                                       placeholder="Enter number Of Item">
                                            </div>
                                        </div>
                                        <div class="col-md-4 px-0">
                                            <div class="form-group pt-3">
                                                <label for="price">Addons price</label>
                                                <input style="display: inline-block;" type="number" class="form-control"
                                                       id="price" min="0" step="0.01" name=cprice[]" value=""
                                                       placeholder="Enter addons price">
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="price">Addons price(USD)</label>
                                                <input style="display: inline-block;" type="number" class="form-control"
                                                       id="price" min="0" step="0.01" name="count_usd_price[]" value=""
                                                       placeholder="Enter addons price">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group pt-3">
                                                <label for="price">Offer price</label>
                                                <input type="number"
                                                       class="form-control" min="0" step="0.01" id="price"
                                                       name=cofferPrice[]" value=""
                                                       placeholder="Enter addons price">
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="price">Offer price(USD)</label>
                                                <input type="number"
                                                       class="form-control" min="0" step="0.01" id="price"
                                                       name="count_usd_offer_price[]" value=""
                                                       placeholder="Enter addons price">
                                            </div>
                                        </div>
                                    </div>

                                    {{--once price--}}
                                    <div class="row" id="Gprice">
                                        <div class="col-md-6 px-2">
                                            <div class="form-group pt-3">
                                                <label for="price">Addons price</label>
                                                <input type="number" min="0" step="0.01" class="form-control" id="price"
                                                       name="price[]"
                                                       value="" placeholder="Enter addons price">
                                            </div>
                                        </div>
                                        <div class="col-md-6 px-2">
                                            <div class="form-group pt-3">
                                                <label for="price">Offer Price</label>
                                                <input type="number" min="0" step="0.01" class="form-control" id="price"
                                                       name="offerPrice[]"
                                                       value="" placeholder="Enter addons price">
                                            </div>
                                        </div>
                                        <div class="col-md-6 px-2">
                                            <div class="form-group pb-3">
                                                <label for="price">Addons price(USD)</label>
                                                <input style="display: inline-block;" type="number" class="form-control"
                                                       id="price" min="0" step="0.01" name="usd_price[]" value=""
                                                       placeholder="Enter addons price">

                                            </div>
                                        </div>
                                        <div class="col-md-6 px-2">
                                            <div class="form-group pb-3">
                                                <label for="offerPrice">Offer price(USD)</label>
                                                <input type="number"
                                                       class="form-control" min="0" step="0.01" id="offerPrice"
                                                       name="usd_offer_price[]" value=""
                                                       placeholder="Enter addons price">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group py-3">
                                        <label for="position">Position</label>
                                        <input type="number" class="form-control" value="0" id="position"
                                               name="position"
                                               placeholder="Position">
                                    </div>

                                    <div class="form-group py-3">
                                        <label for="image">Addons Thumbnail</label>
                                        <input type="file" class="form-control" id="image" name="image" value=""
                                               placeholder="Enter addons title">
                                    </div>
                                </div>
                                {{-- create modal footer --}}
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-12">
                    {{-- card --}}
                    <div class="card">
                        <div class="card-body">
                            {{--success alert--}}
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{Session::get('success_message')}}</div>
                            @endif
                            {{-- addons tables --}}
                            <div class="table-responsive">
                                <table class="table" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox"></th>
                                        <th width="5%">Addons Name</th>
                                        <th width="20%">thumbnail</th>
                                        <th width="10%">Price</th>
                                        <th width="10%">Type</th>
                                        <th width="10%">Position</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($data) && count($data)>0)
                                        @foreach($data as $key=>$dm)
                                            {{-- table body row --}}
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td><input type="checkbox" name="id" value="{{$dm->id ?? ''}}"></td>
                                                <td>
                                                    {{$dm->title ?? ""}}
                                                </td>
                                                <td>
                                                    <img src="{{ asset('addons/'.$dm->image) }}" class="zoom"
                                                         width="50px">
                                                </td>

                                                <td>
                                                    @foreach ($dm->price as $key => $item)
                                                        {{ $dm->price[$key] ?? "" }} tk,
                                                    @endforeach
                                                </td>
                                                <td> {{ $dm->type }}</td>
                                                <td> {{ $dm->position }}</td>
                                                <td>
                                                    <div class="form-check form-switch" style="text-align:center;">
                                                        <input class="form-check-input switchstatus" type="checkbox"
                                                               data-id="{{$dm->id}}" id="flexSwitchCheckChecked"
                                                               @if($dm->status== 1) checked=""
                                                               @endif style="margin:0 auto;">
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                <td>
                                                    {{-- <a href="javascript:void(0)" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $dm->id }}">Edit</a> --}}
                                                    <a href="javascript:void(0)" class="btn btn-primary"
                                                       onclick="edit({{ $dm->id }})">Edit</a>
                                                </td>
                                            </tr>

                                            {{-- addons edit modal --}}
                                            <div class="modal fade" id="editModal{{ $dm->id }}" tabindex="-1"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form method="post" action="{{route('superadmin.addons.store')}}"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                    Addons</h5>
                                                                <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                            </div>
                                                            {{-- addons edit input fields --}}
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id"
                                                                       value="{{ $dm->id ?? '' }}">
                                                                <div class="form-group py-3">
                                                                    <label for="addons_name">Addons Name</label>
                                                                    <input type="text" class="form-control"
                                                                           id="addons_name" name="title"
                                                                           value="{{ $dm->title }}"
                                                                           placeholder="Enter addons title">
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group py-3">
                                                                            <input type="radio"
                                                                                   onchange="EditoneTime({{ $dm->id }})"
                                                                                   name="type"
                                                                                   value="oneTime" {{ $dm->type == 'oneTime' ? 'checked':'' }}>
                                                                            <label for="oneTime">One Time</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group py-3">
                                                                            <input type="radio"
                                                                                   onchange="Editmonthly({{ $dm->id }})"
                                                                                   name="type"
                                                                                   value="monthly" {{ $dm->type == 'monthly' ? 'checked':'' }}>
                                                                            <label for="monthly">Monthly</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group py-3">
                                                                            <input type="radio"
                                                                                   onclick="Editcounter({{ $dm->id }})"
                                                                                   name="type"
                                                                                   value="counter" {{ $dm->type == 'counter' ? 'checked':'' }}>
                                                                            <label for="counter">Counter</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row" id="EditmonthIncre{{ $dm->id }}"
                                                                     style="display: {{ $dm->type == 'monthly' ? 'flex':'none' }}">
                                                                    @foreach ($dm->price as $key => $item)
                                                                        <div class="col-md-4">
                                                                            <div class="form-group pt-3">
                                                                                <label for="price">Addons price</label>
                                                                                <input style="display: inline-block;"
                                                                                       type="number"
                                                                                       min="0" step="0.01"
                                                                                       class="form-control" id="price"
                                                                                       name=mprice[]"
                                                                                       value="{{ $dm->price[$key] }}"
                                                                                       placeholder="Enter addons price">

                                                                            </div>
                                                                            <div class="form-group pb-3">
                                                                                <label for="price">Addons
                                                                                    price(USD)</label>
                                                                                <input style="display: inline-block;"
                                                                                       type="number"
                                                                                       min="0" step="0.01"
                                                                                       class="form-control" id="price"
                                                                                       name="mouth_usd_price[]"
                                                                                       value="{{ $dm->usd_price ? isset(json_decode($dm->usd_price)[$key]) ? json_decode($dm->usd_price)[$key] : 0 : 0}}"
                                                                                       placeholder="Enter addons price">

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <div class="form-group pt-3">
                                                                                <label for="offerPrice">Offer
                                                                                    price</label>
                                                                                <input type="number"
                                                                                       class="form-control"
                                                                                       min="0" step="0.01"
                                                                                       id="offerPrice"
                                                                                       name=mofferPrice[]"
                                                                                       value="{{ $dm->offerprice[$key] ?? 0 }}"
                                                                                       placeholder="Enter addons price">
                                                                            </div>
                                                                            <div class="form-group pb-3">
                                                                                <label for="offerPrice">Offer
                                                                                    price(USD)</label>
                                                                                <input type="number"
                                                                                       class="form-control"
                                                                                       min="0" step="0.01"
                                                                                       id="offerPrice"
                                                                                       name="mouth_usd_offer_price[]"
                                                                                       value="{{ $dm->usd_offer_price ? isset(json_decode($dm->usd_offer_price)[$key]) ? json_decode($dm->usd_offer_price)[$key] : 0 : 0}}"
                                                                                       placeholder="Enter addons price">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group py-3">
                                                                                <label for="month">Month</label>
                                                                                <input type="number"
                                                                                       class="form-control" id="month"
                                                                                       name="month[]"
                                                                                       value="{{ $dm->monthorvalue[$key]??0 }}"
                                                                                       placeholder="Enter Month">
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                    @php
                                                                        $item =[];
                                                                    @endphp
                                                                </div>

                                                                <div class="row" id="EditcountIncre{{ $dm->id }}"
                                                                     style="display: {{ $dm->type == 'counter' ? '':'none' }}">
                                                                    @foreach ($dm->price as $key => $item)

                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="name">Name</label>
                                                                                <input type="text" class="form-control"
                                                                                       id="name" name="name[]"
                                                                                       value="{{ $dm->name[$key]??0 }}"
                                                                                       placeholder="Enter Name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group py-3">
                                                                                <label for="numberOfValue">Value</label>
                                                                                <input type="number"
                                                                                       class="form-control"
                                                                                       id="numberOfValue"
                                                                                       name="numberOfValue[]"
                                                                                       value="{{ $dm->monthorvalue[$key]??0 }}"
                                                                                       placeholder="Enter number Of Item">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group pt-3">
                                                                                <label for="price">Addons price</label>
                                                                                <input style="display: inline-block;"
                                                                                       type="number"
                                                                                       min="0" step="0.01"
                                                                                       class="form-control" id="price"
                                                                                       name="cprice[]"
                                                                                       value="{{ $dm->price[$key]??0 }}"
                                                                                       placeholder="Enter addons price">
                                                                            </div>
                                                                            <div class="form-group pb-3">
                                                                                <label for="price">Addons
                                                                                    price(USD)</label>
                                                                                <input style="display: inline-block;"
                                                                                       type="number"
                                                                                       min="0" step="0.01"
                                                                                       class="form-control" id="price"
                                                                                       name="count_usd_price[]"
                                                                                       value="{{ $dm->usd_price ? isset(json_decode($dm->usd_price)[$key]) ? json_decode($dm->usd_price)[$key] : 0 : 0}}"
                                                                                       placeholder="Enter addons price">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-5">
                                                                            <div class="form-group pt-3">
                                                                                <label for="price">Offer price</label>
                                                                                <input type="number"
                                                                                       class="form-control" id="price"
                                                                                       name=cofferPrice[]"
                                                                                       min="0" step="0.01"
                                                                                       value="{{ $dm->offerprice[$key]??0 }}"
                                                                                       placeholder="Enter addons price">
                                                                            </div>
                                                                            <div class="form-group pb-3">
                                                                                <label for="price">Offer
                                                                                    price(USD)</label>
                                                                                <input type="number"
                                                                                       class="form-control" id="price"
                                                                                       name=count_usd_offer_price[]"
                                                                                       min="0" step="0.01"
                                                                                       value="{{ $dm->usd_offer_price ? isset(json_decode($dm->usd_offer_price)[$key]) ? json_decode($dm->usd_offer_price)[$key] : 0 : 0}}"
                                                                                       placeholder="Enter addons price">
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                <div class="col-md-12"
                                                                     id="EditcountIncreAction{{ $dm->id }}"
                                                                     style="display: {{ $dm->type == 'counter' ? '':'none' }}">
                                                                    <div class="form-group">
                                                                        <button type="button"
                                                                                onclick="addCounterForm({{ $dm->id }})"
                                                                                class="btn btn-info form-control">+ Add
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12"
                                                                     id="EditmonthIncreAction{{ $dm->id }}"
                                                                     style="display: {{ $dm->type == 'monthly' ? 'flex':'none' }}">
                                                                    <div class="form-group">
                                                                        <button type="button"
                                                                                onclick="addMonthForm({{ $dm->id }})"
                                                                                class="btn btn-info form-control">+ Add
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-3" id="EditGprice{{ $dm->id }}"
                                                                     style="display: {{ $dm->type == 'oneTime' ? 'flex':'none' }}">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group pt-3">
                                                                            <label for="price">Addons Price</label>
                                                                            <input type="number" class="form-control"
                                                                                   id="price" name="price[]" min="0"
                                                                                   step="0.01"
                                                                                   value="{{ $dm->price[0] }}"
                                                                                   placeholder="Enter addons price">
                                                                        </div>
                                                                        <div class="form-group pb-3 mt-2">
                                                                            <label for="price">Addons Price(USD)</label>
                                                                            <input type="number" class="form-control"
                                                                                   id="price" name="usd_price[]" min="0"
                                                                                   step="0.01"
                                                                                   value="{{ $dm->usd_price ? isset(json_decode($dm->usd_price)[$key]) ? json_decode($dm->usd_price)[$key] : 0 : 0}}"
                                                                                   placeholder="Enter addons price">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group pt-3">
                                                                            <label for="price">Offer Price</label>
                                                                            <input type="number" class="form-control"
                                                                                   id="price" name="offerPrice[]"
                                                                                   min="0" step="0.01"
                                                                                   value="{{ $dm->offerprice[0] }}"
                                                                                   placeholder="Enter addons price">
                                                                        </div>
                                                                        <div class="form-group pb-3 mt-2">
                                                                            <label for="price">Offer Price(USD)</label>
                                                                            <input type="number" class="form-control"
                                                                                   id="price" name="usd_offer_price[]"
                                                                                   min="0" step="0.01"
                                                                                   value="{{ $dm->usd_offer_price ? isset(json_decode($dm->usd_offer_price)[$key]) ? json_decode($dm->usd_offer_price)[$key] : 0 : 0}}"
                                                                                   placeholder="Enter addons price">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group py-3">
                                                                    <label for="position">Position</label>
                                                                    <input type="number" class="form-control"
                                                                           value="{{ $dm->position }}" id="position"
                                                                           name="position"
                                                                           placeholder="Position">
                                                                </div>

                                                                <div class="form-group py-3"
                                                                     style="text-align: center;">
                                                                    <label for="image">
                                                                        <h4>Addons Thumbnail</h4>
                                                                    </label><br>
                                                                    <img src="{{ asset('addons/'.$dm->image) }}"
                                                                         style="width: 100%;" class="zoom">
                                                                    <br><br>
                                                                    <input type="file" class="form-control" id="image"
                                                                           name="image" value=""
                                                                           placeholder="Enter addons title">
                                                                </div>

                                                            </div>
                                                            {{-- addons modal footer buttons --}}
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        @endforeach
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
@push('scripts')
    {{-- payment type handle --}}
    <script>
        $('#Gprice').hide();
        $('#monthIncre').hide();
        $('#countIncre').hide();


        $("#oneTime").change(function () {
            $('#Gprice').show();
            $('#monthIncre').hide();
            $('#countIncre').hide();
        });

        $("#monthly").change(function () {
            $('#monthIncre').show();
            $('#Gprice').hide();
            $('#countIncre').hide();
            // $('#countIncre').html("");
        });

        $("#counter").change(function () {
            $('#Gprice').hide();
            $('#monthIncre').hide();
            $('#countIncre').show();
            // $('#monthIncre').html("");
        });

        // edit payment mouth input field add function
        $("#addNewh").on('click', function () {
            var html = `<div class="col-md-5">
                            <div class="form-group pt-3">
                                <label for="price">Addons price</label>
                                <input style="display: inline-block;" type="number" min="0" step="0.01" class="form-control" id="price" name=mprice[]" value="" placeholder="Enter addons price">
                            </div>
                            <div class="form-group pb-3">
                                <label for="price">Addons price(USD)</label>
                                <input style="display: inline-block;" type="number" min="0" step="0.01" class="form-control" id="price" name=mouth_usd_price[]" value="" placeholder="Enter addons price">
                            </div>
                        </div>
                        <div class="col-md-4 px-0">
                            <div class="form-group pt-3">
                                <label for="offerPrice">Offer price</label>
                                <input type="number" class="form-control" min="0" step="0.01" id="offerPrice" name=mofferPrice[]" value="" placeholder="Enter addons price">
                            </div>
                            <div class="form-group pb-3">
                                <label for="offerPrice">Offer price(USD)</label>
                                <input type="number" class="form-control" min="0" step="0.01" id="offerPrice" name=mouth_usd_offer_price[]" value="" placeholder="Enter addons price">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group pt-3">
                                <label for="month">Month</label>
                                <input type="number" class="form-control" id="month" name="month[]" value="" placeholder="Enter Month">
                              </div>
                        </div>`
            $('#monthIncre').append(html);
        });

        // edit payment count input field add function
        $("#addNewhf").on('click', function () {
            var html = `<div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name[]" value="" placeholder="Enter Name">
                              </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group py-3">
                                <label for="numberOfValue">Value</label>
                                <input type="number" class="form-control" id="numberOfValue" name="numberOfValue[]" value="" placeholder="Enter number Of Item">
                              </div>
                        </div>
                        <div class="col-md-4 px-0">
                            <div class="form-group pt-3">
                                <label for="price">Addons price</label>
                                <input style="display: inline-block;" type="number" class="form-control" min="0" step="0.01" id="price" name=cprice[]" value="" placeholder="Enter addons price">
                            </div>
                            <div class="form-group pb-3">
                                <label for="price">Addons price(USD)</label>
                                <input style="display: inline-block;" type="number" class="form-control" min="0" step="0.01" id="price" name=count_usd_price[]" value="" placeholder="Enter addons price">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group pt-3">
                                <label for="price">Offer price</label>
                                <input type="number" class="form-control" min="0" step="0.01" id="price" name=cofferPrice[]" value="" placeholder="Enter addons price">
                            </div>
                            <div class="form-group pb-3">
                                <label for="price">Offer price</label>
                                <input type="number" class="form-control" min="0" step="0.01" id="price" name=count_usd_offer_price[]" value="" placeholder="Enter addons price">
                            </div>
                        </div>`
            $('#countIncre').append(html);
        });

        // edit
        // $('#EditGprice').hide();
        // $('#EditmonthIncre').hide();
        // $('#EditcountIncre').hide();

    </script>
    {{-- edit form handle --}}
    <script>
        // edit modal on
        function edit(id) {
            $('#editModal' + id).modal('toggle');
        }

        // edit form one radio option clicked function
        function EditoneTime(id) {

            $('#EditGprice' + id).css("display", "flex");
            $('#EditmonthIncre' + id).css("display", "none");
            $('#EditcountIncre' + id).css("display", "none");
            $('#EditcountIncreAction' + id).css("display", "none");
            $('#EditmonthIncreAction' + id).css("display", "none");
            $('#EditGprice' + id).html("");
            $('#EditmonthIncre' + id).html("");
            $('#EditcountIncre' + id).html("");
        }

        // edit form mouth radio option clicked function
        function Editmonthly(id) {
            $('#EditmonthIncre' + id).css("display", "flex");
            $('#EditmonthIncreAction' + id).css("display", "block");
            $('#EditGprice' + id).css("display", "none");
            $('#EditcountIncre' + id).css("display", "none");
            $('#EditcountIncreAction' + id).css("display", "none");
            $('#EditGprice' + id).html("");
            $('#EditmonthIncre' + id).html("");
            $('#EditcountIncre' + id).html("");
        }

        // edit form count redio option clicked function
        function Editcounter(id) {
            $('#EditGprice' + id).css("display", "none");
            $('#EditmonthIncre' + id).css("display", "none");
            $('#EditmonthIncreAction' + id).css("display", "none");
            $('#EditcountIncre' + id).css("display", "flex");
            $('#EditcountIncreAction' + id).css("display", "block");
            $('#EditGprice' + id).html("");
            $('#EditmonthIncre' + id).html("");
            $('#EditcountIncre' + id).html("");
        }

        // edit from add new count input fields
        function addCounterForm(id) {

            var html = `<div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name[]" value="" placeholder="Enter Name">
                              </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group py-3">
                                <label for="numberOfValue">Value</label>
                                <input type="number" class="form-control" id="numberOfValue" name="numberOfValue[]" value="" placeholder="Enter number Of Item">
                              </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group pt-3">
                                <label for="price">Addons price</label>
                                <input style="display: inline-block;" type="number" class="form-control" min="0" step="0.01" id="price" name="cprice[]" value="" placeholder="Enter addons price">
                            </div>
                            <div class="form-group pb-3">
                                <label for="price">Addons price(USD)</label>
                                <input style="display: inline-block;" type="number" class="form-control" min="0" step="0.01" id="price" name="count_usd_price[]" value="" placeholder="Enter addons price">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group pt-3">
                                <label for="price">Offer price</label>
                                <input type="number" class="form-control" min="0" step="0.01" id="price" name="cofferPrice[]" value="" placeholder="Enter addons price">
                            </div>
                            <div class="form-group pb-3">
                                <label for="price">Offer price(USD)</label>
                                <input type="number" class="form-control" min="0" step="0.01" id="price" name="count_usd_offer_price[]" value="" placeholder="Enter addons price">
                            </div>
                        </div>`;
            $('#EditcountIncre' + id).append(html);
        }

        // edit from mouth new count input fields
        function addMonthForm(id) {

            var html = `<div class="col-md-4">
                            <div class="form-group pt-3">
                                <label for="price">Addons price</label>
                                <input style="display: inline-block;" type="number" class="form-control" min="0" step="0.01" id="price" name="mprice[]" value="" placeholder="Enter addons price">
                            </div>
                            <div class="form-group pb-3">
                                <label for="price">Addons price(USD)</label>
                                <input style="display: inline-block;" type="number" class="form-control" min="0" step="0.01" id="price" name="mouth_usd_price[]" value="" placeholder="Enter addons price">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group pt-3">
                                <label for="offerPrice">Offer price</label>
                                <input type="number" class="form-control" min="0" step="0.01" id="offerPrice" name="mofferPrice[]" value="" placeholder="Enter addons price">
                            </div>
                            <div class="form-group pb-3">
                                <label for="offerPrice">Offer price(USD)</label>
                                <input type="number" class="form-control" min="0" step="0.01" id="offerPrice" name="mouth_usd_offer_price[]" value="" placeholder="Enter addons price">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group py-3">
                                <label for="month">Month</label>
                                <input type="number" class="form-control" id="month" name="month[]" value="" placeholder="Enter Month">
                              </div>
                        </div>`;
            $('#EditmonthIncre' + id).append(html);
        }

    </script>
    {{--addons status toggle function--}}
    <script>
        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                $url = "{{ route('superadmin.addons.status') }}";
                var value = $(this).val();
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {
                    value: value
                    , id: id
                }, function (data) {
                    if (data) {
                        Swal.fire(
                            'Congratulations Mr. {{ auth()->user()->name }}!'
                            , 'Successfuly Change Addons status'
                            , 'success'
                        )
                    }
                });
            });
        });

    </script>
    {{-- create form handle --}}
    <script>
        function createNew() {
            $('#createModal').modal('toggle');
        }

        {{-- Will Remve Future  --}}
        // $(document).ready(function () {
        //   $("#taskfilter").on("keyup", function () {
        //     var value = $(this).val().toLowerCase();
        //     $("#taskfilterresult tbody tr").filter(function () {
        //       $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        //     });
        //   });
        //
        // });

        {{-- Will Remove Future --}}
        // function exportTasks(_this) {
        //   let _url = $(_this).data('href');
        //   window.location.href = _url;
        // }

    </script>
@endpush
