@extends('admin.layouts.main')
@section('content')
    <style>
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

        .form-select:focus {
            border-color: gainsboro !important;
        }
    </style>
    <main class="main-content position-relative h-100 border-radius-lg">
        @include('admin.super.share.plan-nav')
        <section class="container content-main">
            <div class="row">
                <form action="{{route('updateplan',$data->id)}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title">Edit Plan</h2>
                                </div>

                                <div class="col-md-6" style="text-align:right">
                                    <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button> -->
                                    <!-- <button class="btn btn-info rounded font-sm hover-up">Publich</button> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>Basic</h4>
                                </div>
                                <div class="card-body">

                                    <div class="row mb-4">
                                        <label for="product_name" class="form-label">Plan Name</label>
                                        <div class="col-md-8">
                                            <input type="text" placeholder="Type here" class="form-control" id="name"
                                                   name="name" value="{{$data->name}}">
                                            @error('name')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="product_name" class="form-label">Plan Subtitle</label>
                                        <div class="col-md-8">
                                            <input type="text" placeholder="Type here" class="form-control"
                                                   id="subtitle" name="subtitle" value="{{$data->subtitle}}">
                                            @error('subtitle')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Price</label>
                                        <div class="col-md-8">
                                            <input type="number" name="price" id="price" step="0.01"
                                                   class="form-control"
                                                   placeholder="per month" value="{{$data->price}}">
                                            @error('price')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Discount Type</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="discount_type" id="discount_type">
                                                <option value="no_discount"
                                                        @if($data->discount_type=="none") selected @endif>No Discount
                                                </option>
                                                <option value="percent"
                                                        @if($data->discount_type=="percent") selected @endif>Percent
                                                </option>
                                                <option value="fixed"
                                                        @if($data->discount_type=="fixed") selected @endif>Fixed
                                                </option>
                                            </select>
                                            @error('discount_type')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">1st Month Discount</label>
                                        <div class="col-md-8">
                                            <input type="number" name="onemdis" id="onemdis" class="form-control"
                                                   value="{{$data->onedis}}">
                                            @error('onemdis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">6 Month Discount</label>
                                        <div class="col-md-8">
                                            <input type="number" name="sixstmdis" id="sixstmdis" class="form-control"
                                                   value="{{$data->sixdis}}">
                                            @error('sixstmdis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">12 Month Discount</label>
                                        <div class="col-md-8">
                                            <input type="number" name="twelvestmdis" id="twelvestmdis"
                                                   class="form-control" value="{{$data->twelvedis}}">
                                            @error('twelvestmdis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">24 Month Discount</label>
                                        <div class="col-md-8">
                                            <input type="number" name="twentyfourstmdis" id="twentyfourstmdis"
                                                   class="form-control" value="{{$data->twentyfourdis}}">
                                            @error('twentyfourstmdis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    {{--for usd start here--}}
                                    <div class="row mb-4">
                                        <label class="form-label">USD Price</label>
                                        <div class="col-md-8">
                                            <input type="number" name="usd_price" value="{{$data->usd_price}}"
                                                   step="0.01" id="usd_price" class="form-control"
                                                   placeholder="per month">
                                            @error('usd_price')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">USD Discount Type</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="usd_discount_type"
                                                    id="usd_discount_type">
                                                <option value="no_discount"
                                                        @if($data->usd_discount_type=="none") selected @endif>No
                                                    Discount
                                                </option>
                                                <option value="percent"
                                                        @if($data->usd_discount_type=="percent") selected @endif>Percent
                                                </option>
                                                <option value="fixed"
                                                        @if($data->usd_discount_type=="fixed") selected @endif>Fixed
                                                </option>
                                            </select>
                                            @error('usd_discount_type')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">USD 1st Month Discount</label>
                                        <div class="col-md-8">
                                            <input type="number" name="usd_1_dis" value="{{$data->usd_1_dis}}"
                                                   id="usd_1_dis" class="form-control">
                                            @error('usd_1_dis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">USD 6 Month Discount</label>
                                        <div class="col-md-8">
                                            <input type="number" name="usd_6_dis" value="{{$data->usd_6_dis}}"
                                                   id="usd_6_dis" class="form-control">
                                            @error('usd_6_dis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">USD 12 Month Discount</label>
                                        <div class="col-md-8">
                                            <input type="number" name="usd_12_dis" value="{{$data->usd_12_dis}}"
                                                   id="usd_12_dis"
                                                   class="form-control">
                                            @error('usd_12_dis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">USD 24 Month Discount</label>
                                        <div class="col-md-8">
                                            <input type="number" name="usd_24_dis" value="{{$data->usd_24_dis}}"
                                                   id="usd_24_dis"
                                                   class="form-control">
                                            @error('usd_24_dis')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    {{--for usd end  here--}}

                                    <!--<div class="row mb-4">-->
                                    <!--    <label class="form-label">Branch</label>-->
                                    <!--    <div class="col-md-8">-->
                                    <!--    <input type="number" name="branch" id="branch" class="form-control" value="{{$data->branch}}">-->
                                    <!--    @error('branch')-->
                                    <!--            <p class="text-danger" role="alert">{{$message}}</p>-->
                                    <!--    @enderror-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="row mb-4">
                                        <label class="form-label">Staff</label>
                                        <div class="col-md-8">
                                            <input type="number" name="staff" id="staff" class="form-control"
                                                   value="{{$data->staff}}">
                                            @error('staff')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Product</label>
                                        <div class="col-md-8">
                                            <input type="number" name="product" id="product" class="form-control"
                                                   value="{{$data->product}}">
                                            @error('product')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Category</label>
                                        <div class="col-md-8">
                                            <input type="number" name="category" id="category" class="form-control"
                                                   value="{{$data->category}}">
                                            @error('category')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Sub Category</label>
                                        <div class="col-md-8">
                                            <input type="number" name="sub_category" id="sub_category"
                                                   class="form-control" value="{{$data->sub_category}}">
                                            @error('sub_category')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Inventory</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="inventory" id="inventory">
                                                <option value="Yes" @if($data->inventory=="Yes") selected @endif>Yes
                                                </option>
                                                <option value="No" @if($data->inventory=="No") selected @endif>No
                                                </option>
                                            </select>
                                            @error('inventory')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Google Ad</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="googlead" id="googlead">
                                                <option value="Yes" @if($data->google_ad=="Yes") selected @endif>Yes
                                                </option>
                                                <option value="No" @if($data->google_ad=="No") selected @endif>No
                                                </option>
                                            </select>
                                            @error('googlead')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Advance Report</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="advance_report" id="advance_report">
                                                <option value="Yes" @if($data->advance_report=="Yes") selected @endif>
                                                    Yes
                                                </option>
                                                <option value="No" @if($data->advance_report=="No") selected @endif>No
                                                </option>
                                            </select>
                                            @error('advance_report')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Website Setup</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="website_setup" id="website_setup">
                                                <option value="Yes" @if($data->website_setup=="Yes") selected @endif>
                                                    Yes
                                                </option>
                                                <option value="No" @if($data->website_setup=="No") selected @endif>No
                                                </option>
                                            </select>
                                            @error('website_setup')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="form-label">Order</label>
                                        <div class="col-md-8">
                                            <input type="number" name="order" id="order" class="form-control"
                                                   value="{{$data->order}}">
                                            @error('order')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-lg-8">
                                            <label class="form-label">Payment Processing Charge</label>
                                            <div class="row gx-2">
                                                <input placeholder="0" type="number" step="0.01"
                                                       class="form-control"
                                                       name="payment_processing_charge"
                                                       value="{{$data->payment_processing_charge ?? "0"}}">
                                                @error('payment_processing_charge')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-lg-8">
                                            <label class="form-label">Monthly Chat Support</label>
                                            <div class="row gx-2">
                                                <input placeholder="0" type="number" step="0.01"
                                                       class="form-control"
                                                       name="monthly_chat_support"
                                                       value="{{$data->monthly_chat_support ?? "0"}}">
                                                @error('monthly_chat_support')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-lg-8">
                                            <label class="form-label">Upload File Number</label>
                                            <div class="row gx-2">
                                                <input placeholder="0" type="number" step="1"
                                                       class="form-control"
                                                       name="upload_file_limit"
                                                       value="{{$data->upload_file_limit ?? "0"}}">
                                                @error('upload_file_limit')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-lg-8">
                                            <label class="form-label">Position</label>
                                            <div class="row gx-2">
                                                <input placeholder="0" type="number" class="form-control"
                                                       name="position" value="{{$data->position}}">
                                                @error('position')
                                                <p class="text-danger" role="alert">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <label for="staticEmail" class="col-md-2 col-form-label">Status</label>
                                                <div class="col-md-4">
                                                    <div class="form-check form-switch is-filled"
                                                         style="text-align:center;padding-top:14px;">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="flexSwitchCheckChecked" name="status"
                                                               style="margin:0 auto;"
                                                               @if($data->status=='active') checked="" @endif>
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                    @error('status')
                                                    <p class="text-danger" role="alert">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info mt-4 ml-3">Update</button>

                                </div>
                            </div> <!-- card end// -->

                        </div>
                        <div class="col-lg-4">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>Features Display</h4>
                                    <button type="button" id="add-feature" class="btn btn-info px-3">Add</button>
                                </div>
                                <div class="card-body" id="features">
                                    @foreach($plan_details as  $key => $plan_detail)
                                        <div class="has_keys d-none">{{$key}}</div>
                                        <div class="row mb-4 feature-item">
                                            <input type="hidden" name="{{"details[$key][id]"}}"
                                                   value="{{$plan_detail->id}}">
                                            <input type="hidden" name="{{"details[$key][plan_id]"}}"
                                                   value="{{$data->id}}">
                                            <div class="d-flex justify-content-between px-0">
                                                <label for="product_name" class="form-label">Plan Feature</label>
                                                <i class="remove-feature fa fa-times text-danger"
                                                   aria-hidden="true"></i>
                                            </div>
                                            <div class="col-md-9 px-1">
                                                <input type="text" placeholder="Type here" class="form-control"
                                                       id="titles"
                                                       name="{{"details[$key][title]"}}"
                                                       value="{{$plan_detail->title}}"
                                                       max="35">
                                            </div>
                                            <div class="col-md-3 px-1">
                                                <input type="number" step="1"
                                                       placeholder="Type here"
                                                       class="form-control"
                                                       id="positions"
                                                       value="{{$plan_detail->position}}"
                                                       name="{{"details[$key][position]"}}">
                                            </div>
                                            <div class="col-md-9 px-1 mt-2">
                                                <select class="form-select" name="{{"details[$key][type]"}}" id="type"
                                                        aria-label="select type">
                                                    <option @if($plan_detail->type == "package")selected
                                                            @endif  value="package">Package
                                                    </option>
                                                    <option @if($plan_detail->type == "all")selected @endif value="all">
                                                        All in One Solution
                                                    </option>
                                                    <option @if($plan_detail->type == "features")selected
                                                            @endif value="features">Features
                                                    </option>
                                                    <option @if($plan_detail->type == "addons")selected
                                                            @endif value="addons">Addons
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex form-check px-0 h-100 align-items-center">
                                                    <input class="form-check-input" name="{{"details[$key][status]"}}"
                                                           type="checkbox" @if($plan_detail->status) checked @endif>
                                                    <label class="form-check-label pt-3">
                                                        Checked
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div> <!-- card end// -->

                        </div>
                    </div>

                </form>
            </div>
        </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let count = $('.has_keys').last().text().trim() + 1;
            // Adding feature
            $('#add-feature').on("click", function () {
                const id = window.location.pathname.split('/') [3];
                console.log('key', count)
                var item = `<div class="row mb-4 feature-item">
                        <input type="hidden" name="details[${count}][id]" value="${null}">
                        <input type="hidden" name="details[${count}][plan_id]" value="${id}">
                        <div class="d-flex justify-content-between px-0">
                            <label for="product_name" class="form-label">Plan Feature</label>
                            <i class="remove-feature fa fa-times text-danger" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-9 px-1">
                            <input type="text" placeholder="Type here" class="form-control" id="titles"  name="details[${count}][title]" max="35">
                        </div>
                        <div class="col-md-3 px-1">
                            <input type="number" step="1" placeholder="Type here" class="form-control" id="positions" value="11"  name="details[${count}][position]">
                        </div>
                        <div class="col-md-9 px-1 mt-2">
                            <select class="form-select" name="details[${count}][type]" id="type" aria-label="select type">
                                <option value="package">Package</option>
                                <option value="all">All in One Solution</option>
                                <option value="features">Features</option>
                                <option value="addons">Addons</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex form-check px-0 h-100 align-items-center">
                                <input class="form-check-input" name="details[${count}][status]" type="checkbox" checked>
                                <label class="form-check-label pt-3">
                                    Checked
                                </label>
                            </div>
                        </div>
                    </div>`;
                count++;
                $('#features').append(item);
            });

            // Removing feature
            $(document).on('click', '.remove-feature', function () {
                $(this).closest('.feature-item').remove();
                console.log('Feature removed');
            });
        });
    </script>

    <script>

        jQuery('select[name="category"]').on('change', function () {
            debugger;
            var val = $(this).val();
            console.log(val);
            $('#subcategory').empty();
            var catid = $('select[name="category"]').val();
            $.get('/getsubcat', {catid: catid}, function (data) {
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('#subcategory').append(
                        '<option value="">select</option><option value="' + data[i].id + '">' + data[i].name + '</option>'
                    );
                }
            });
        });
    </script>
@endpush
