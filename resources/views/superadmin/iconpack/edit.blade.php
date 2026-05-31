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
    </style>

    <main class="main-content position-relative h-100 border-radius-lg">
        @include('superadmin.share.design-top-nav')
        <section class="container content-main">
            <div class="row">
                <form action="{{route('superadmin.designupdate',$design->id)}}" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="index" value="1" id="index">
                    @csrf
                    <div class="row">
                        <div class="col-lg-9 mt-4 mb-4">
                            <div class="content-header row">
                                <div class="col-md-6">
                                    <h2 class="content-title"></h2>
                                </div>

                                <div class="col-md-6" style="text-align:right">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6" style="margin:0 auto;">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>Edit Design</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Type</label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="select">Select Design Type</option>
                                            <option value="header"
                                                    @if(isset($design->type) && $design->type=='header') selected @endif>
                                                Header
                                            </option>
                                            <option value="slider"
                                                    @if(isset($design->type) && $design->type=='slider') selected @endif>
                                                Slider
                                            </option>
                                            <option value="banner"
                                                    @if(isset($design->type) && $design->type=='banner') selected @endif>
                                                Banner
                                            </option>
                                            <option value="feature_category"
                                                    @if(isset($design->type) && $design->type=='feature_category') selected @endif>
                                                Feature Category
                                            </option>
                                            <option value="product"
                                                    @if(isset($design->type) && $design->type=='product') selected @endif>
                                                Product
                                            </option>
                                            <option value="feature_product"
                                                    @if(isset($design->type) && $design->type=='feature_product') selected @endif>
                                                Feature Products
                                            </option>
                                            <option value="best_sell_product"
                                                    @if(isset($design->type) && $design->type=='best_sell_product') selected @endif>
                                                Best Sell Products
                                            </option>
                                            <option value="new_arrival"
                                                    @if(isset($design->type) && $design->type=='new_arrival') selected @endif>
                                                New Arrival Products
                                            </option>
                                            <option value="testimonial"
                                                    @if(isset($design->type) && $design->type=='testimonial') selected @endif>
                                                Testimonial
                                            </option>
                                            <option value="footer"
                                                    @if(isset($design->type) && $design->type=='footer') selected @endif>
                                                Footer
                                            </option>
                                            <option value="auth"
                                                    @if(isset($design->type) && $design->type=='auth') selected @endif>
                                                Auth
                                            </option>
                                            <option value="single_product_page"
                                                    @if(isset($design->type) && $design->type=='single_product_page') selected @endif>
                                                Single Product Page
                                            </option>
                                            <option value="shop_page"
                                                    @if(isset($design->type) && $design->type=='shop_page') selected @endif>
                                                Shop Page
                                            </option>
                                            <option value="checkout_page"
                                                    @if(isset($design->type) && $design->type=='checkout_page') selected @endif>
                                                Checkout Page
                                            </option>
                                            <option value="login_page"
                                                    @if(isset($design->type) && $design->type=='login_page') selected @endif>
                                                Login Page
                                            </option>
                                            <option value="profile_page"
                                                    @if(isset($design->type) && $design->type=='profile_page') selected @endif>
                                                Profile Page
                                            </option>
                                            <option value="invoice"
                                                    @if(isset($design->type) && $design->type=='invoice') selected @endif>
                                                Invoice
                                            </option>
                                            <option value="product_card"
                                                    @if(isset($design->type) && $design->type=='product_card') selected @endif>
                                                Product Card
                                            </option>
                                            <option value="product_modal"
                                                    @if(isset($design->type) && $design->type=='product_modal') selected @endif>
                                                Product Modal
                                            </option>
                                            <option value="preloader"
                                                    @if(isset($design->type) && $design->type=='preloader') selected @endif>
                                                Preloader
                                            </option>
                                            <option value="mobile_bottom_menu"
                                                    @if(isset($design->type) && $design->type=='mobile_bottom_menu') selected @endif>
                                                Mobile Bottom Menu
                                            </option>
                                        </select>
                                        @error('type')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Image</label>
                                        <img src="{{URL::to('/')}}/assets/images/design/{{$design->image}}"
                                             width="150px" style="margin-bottom:10px;">
                                        <input type="file" placeholder="Type here" class="form-control" id="image"
                                               name="image">
                                        @error('image')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Name</label>
                                        <input type="text" placeholder="Type here" class="form-control" id="name"
                                               value="{{$design->name ?? ""}}" name="name">
                                        @error('name')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Value</label>
                                        <input type="text" placeholder="Type here" class="form-control" id="value"
                                               value="{{$design->value ?? ""}}" name="value">
                                        @error('value')
                                        <p class="text-danger" role="alert">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-4 row">
                                        <label for="staticEmail" class="col-md-2 col-form-label">Status</label>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch is-filled"
                                                 style="text-align:center;padding-top:14px;">
                                                <input class="form-check-input" type="checkbox"
                                                       id="flexSwitchCheckChecked" name="status" style="margin:0 auto;"
                                                       @if($design->status=="active") checked="" @endif>
                                                <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                            </div>
                                            @error('status')
                                            <p class="text-danger" role="alert">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label"></label>
                                        <button class="btn btn-info rounded font-sm hover-up" type="submit">Publish
                                        </button>
                                    </div>

                                </div>
                            </div> <!-- card end// -->

                        </div>

                    </div>
            </div>

            </form>
            </div>
        </section>
        </div>
    </main>
@endsection

@push('scripts')
@endpush
