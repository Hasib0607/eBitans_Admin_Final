@extends('admin.layouts.main')
@section('content')
    <!-- Modal -->
    <style>
        .fade:not(.show) {
            opacity: 0 !important;
        }
    </style>


    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            @if (Auth::user()->type == 'superadmin')
                                <li class="breadcrumb-item">
                                    <a href="{{route('staff.workAssign')}}">
                                        <img src="{{URL::to('/')}}/img/cubes.png"> <br> Work Assign
                                    </a>
                                </li>
                            @endif
                            <li class="breadcrumb-item">
                                <a href="{{route('staff.webSetUp')}}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br>Website Setup
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{route('staff.view.setup.data', ['id' => $store_id ?? ""])}}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br>Setup Info
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>Website Setup</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" class="btn btn-primary" style="display:block;border-radius:0px !important">Create New</a></li>-->
                        <!--<li style="padding:0px;border:0px;"><a href="javascript:void(0)" style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>-->
                    </ul>
                </div>
            </div>
            <div class="row mt-3 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header" style="padding: 15px 15px 5px;">
                            <h6>Setup Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if(isset($websiteSetupData) && $websiteSetupData->update_setting == 0)
                                    <form class="col-12 col-md-12"
                                          action="{{ route("staff.websitesetup.save.setup.details") }}"
                                          id="detailsForm" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" name="id" value="{{ $websiteSetupData->id ?? "" }}">
                                        <input type="hidden" name="store_id" value="{{ $store_id ?? "" }}">
                                        <div class="row form-row align-items-center">
                                            <!-- Facebook Link -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="facebook_link">Facebook Link (Optional)</label>
                                                <input
                                                    type="text"
                                                    id="facebook_link"
                                                    name="facebook_link"
                                                    placeholder="Facebook Link"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->facebook_link }}"
                                                />

                                                @error('facebook_link')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <!-- Instagram Link -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="instagram_link">Instagram Link (Optional)</label>
                                                <input
                                                    type="text"
                                                    id="instagram_link"
                                                    name="instagram_link"
                                                    placeholder="Instagram Link"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->instagram_link }}"
                                                />

                                                @error('instagram_link')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <!-- Mobile Number -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="mobile_number">Mobile Number <span
                                                        class="text-danger">*</span></label>
                                                <input
                                                    type="text"
                                                    id="mobile_number"
                                                    name="mobile_number"
                                                    placeholder="Mobile Number"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->mobile_number }}"
                                                />

                                                @error('mobile_number')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <!-- Whats App Number -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="whats_app_number">Whats App Number <span
                                                        class="text-danger">*</span></label>
                                                <input
                                                    type="text"
                                                    id="whats_app_number"
                                                    name="whats_app_number"
                                                    placeholder="Whats App Number"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->whats_app_number }}"
                                                />

                                                @error('whats_app_number')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <!-- Youtube Link -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="youtube_link">Youtube Link (Optional)</label>
                                                <input
                                                    type="text"
                                                    id="youtube_link"
                                                    name="youtube_link"
                                                    placeholder="Youtube Link"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->youtube_link }}"
                                                />

                                                @error('youtube_link')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <!-- Email Address -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="email">Email Address <span
                                                        class="text-danger">*</span></label>
                                                <input
                                                    type="text"
                                                    id="email"
                                                    name="email"
                                                    placeholder="Email Address"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->email }}"
                                                />

                                                @error('email')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <!-- Shipping Area -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="shipping_area">Shipping Area</label>
                                                <input
                                                    type="text"
                                                    id="shipping_area"
                                                    name="shipping_area"
                                                    placeholder="Shipping Area"
                                                    class="form-control"
                                                    value="Inside Dhaka"
                                                />

                                                @error('shipping_area')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <!-- Delivery Cost -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="delivery_cost">Delivery Cost <span
                                                        class="text-danger">*</span></label>
                                                <input
                                                    type="text"
                                                    id="delivery_cost"
                                                    name="delivery_cost"
                                                    placeholder="Delivery Cost"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->delivery_cost }}"
                                                />

                                                @error('delivery_cost')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <!-- Tax -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="tax">Tax (Optional)</label>
                                                <input
                                                    type="text"
                                                    id="tax"
                                                    name="tax"
                                                    placeholder="Tax"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->tax }}"
                                                />

                                                @error('tax')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <!-- Address -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="address">Address (Optional)</label>
                                                <input
                                                    type="text"
                                                    id="address"
                                                    name="address"
                                                    placeholder="Address"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->address }}"
                                                />

                                                @error('address')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                            @if(!empty($websiteSetupData->logo))
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="logo">Logo <span
                                                            class="text-danger">*</span></label>
                                                    <img
                                                        src=" {{ asset('assets/images/setting/'.$websiteSetupData->logo ) }}"
                                                        style="width:200px;"/>
                                                </div>
                                            @else
                                                <div class="form-group col-md-4 col-lg-3 mt-3">
                                                    <label for="logo">Logo <span
                                                            class="text-danger">*</span></label>
                                                    <input
                                                        type="file"
                                                        id="logo"
                                                        name="logo"
                                                        class="form-control"
                                                    />
                                                </div>
                                            @endif

                                            <!-- Theme color  -->
                                            <div class="form-group col-md-4 col-lg-3 mt-3">
                                                <label for="theme_color">Theme color <span
                                                        class="text-danger">*</span></label>
                                                <input
                                                    type="color"
                                                    id="theme_color"
                                                    name="theme_color"
                                                    class="form-control"
                                                    value="{{ $websiteSetupData->theme_color }}"
                                                />

                                                @error('theme_color')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>


                                            <!-- Short Description -->
                                            <div class="form-group col-md-6 col-lg-6 mt-3">
                                                <label for="short_description">Short Description (Optional)</label>
                                                <textarea
                                                    id="short_description"
                                                    name="short_description"
                                                    placeholder="Short Description"
                                                    class="form-control"
                                                    rows="3"
                                                >{{ $websiteSetupData->short_description }}</textarea>

                                                @error('short_description')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="d-flex mt-3">
                                            <button class="btn btn-primary" id="btnSubmit">Save Setting</button>
                                        </div>
                                    </form>
                                @else
                                    <span class="alert alert-success">{{ "Setting Updated" }}</span>
                                @endif

                                <div class="col-12 col-md-12">
                                    <div class="mt-5">
                                        <div class="col-md-12">
                                            <h6>Product Details</h6>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th rowspan="2">SL</th>
                                                    <th rowspan="2">Model No</th>
                                                    <th rowspan="2">Product Name</th>
                                                    <th rowspan="2">Description</th>
                                                    <th rowspan="2">Category</th>
                                                    <th rowspan="2">Sub-Category</th>
                                                    <th rowspan="2">Price</th>
                                                    <th rowspan="2">Brand</th>
                                                    <th rowspan="2">Supplier</th>
                                                    <th rowspan="2">Cost</th>
                                                    <th rowspan="2">Discount</th>
                                                    <th colspan="3">Variant</th>
                                                    <th rowspan="2">Other Info</th>
                                                    <th rowspan="2">Create Status</th>
                                                    <th rowspan="2">Action</th>
                                                </tr>
                                                <tr>
                                                    <th>Color</th>
                                                    <th>Size</th>
                                                    <th>Unit</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tableBody">
                                                {!! $productView !!}
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="model_no"
                                                               name="model_no" placeholder="Model No"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="product_name"
                                                               name="product_name" placeholder="Product Name"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="description"
                                                               name="description" placeholder="Description"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="category"
                                                               name="category" placeholder="Category"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="sub_category"
                                                               name="sub_category" placeholder="Sub-Category"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="price" name="price"
                                                               placeholder="Price"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="brand" name="brand"
                                                               placeholder="Brand"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="supplier"
                                                               name="supplier" placeholder="Supplier"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="cost" name="cost"
                                                               placeholder="Cost"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="discount"
                                                               name="discount" placeholder="Discount"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="color" name="color"
                                                               placeholder="Color"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="size" name="size"
                                                               placeholder="Size"/>
                                                    </td>
                                                    <td class="productTableTd">
                                                        <input type="text" class="productInput" value=""
                                                               id="unit" name="unit"
                                                               placeholder="Unit"/>
                                                    </td>
                                                    <td style="padding-top: 25px;">
                                                        <button class="btn btn-primary" id="btnAddProduct">Add
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                    </div>

                                    <div class="d-flex mt-5">
                                        <a class="btn btn-primary"
                                           href="{{ route('staff.websitesetup.run.product.create', ['store' => $store_id ?? ""]) }}">Create
                                            Product</a>

                                        <a class="btn btn-info" style="margin-left: 5px"
                                           href="{{ route('staff.websitesetup.upload.complete', ['store' => $store_id ?? ""]) }}">Complete
                                            Status</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        $("#btnAddProduct").on("click", function () {
            const store_id = '{{ $store_id ?? "" }}';
            const model_no = $("#model_no").val();
            const product_name = $("#product_name").val();
            const description = $("#description").val();
            const category = $("#category").val();
            const sub_category = $("#sub_category").val();
            const price = $("#price").val();
            const brand = $("#brand").val();
            const supplier = $("#supplier").val();
            const cost = $("#cost").val();
            const discount = $("#discount").val();
            const color = $("#color").val();
            const size = $("#size").val();
            const unit = $("#unit").val();

            if (product_name == "" || description == "" || category == "" || price == "" || price == "") {
                swal.fire({
                    "title": "Warning",
                    "text": "Please input Product Name, Category And Price",
                    "type": "error",
                });

                return false;
            }

            if (store_id == "") {
                swal.fire({
                    "title": "Warning",
                    "text": "Store ID missing",
                    "type": "error",
                });

                return false;
            }

            const formData = {
                "store_id": store_id,
                "model_no": model_no,
                "product_name": product_name,
                "description": description,
                "category": category,
                "sub_category": sub_category,
                "price": price,
                "brand": brand,
                "supplier": supplier,
                "cost": cost,
                "discount": discount,
                "color": color,
                "size": size,
                "unit": unit,
            }


            const url = "{{ route("staff.websitesetup.save.product") }}";
            axios.post(url, formData)
                .then(function (response) {
                    productFieldRest();

                    const result = response?.data || "";
                    const productList = response?.data?.data || "";

                    if (result?.status) {
                        swal.fire({
                            "title": "Success",
                            "text": result?.message,
                            "type": "success",
                        });

                        $("#tableBody").html(productList);
                    } else {
                        swal.fire({
                            "title": "Success",
                            "text": result?.message,
                            "type": "success",
                        });
                    }
                }).catch(function (error) {
                // console.log("error", error);
            });

        })

        const productFieldRest = () => {
            $("#model_no").val("");
            $("#product_name").val("");
            $("#description").val("");
            $("#category").val("");
            $("#sub_category").val("");
            $("#price").val("");
            $("#brand").val("");
            $("#supplier").val("");
            $("#cost").val("");
            $("#discount").val("");
            $("#color").val("");
            $("#size").val("");
            $("#unit").val("");
        }
    </script>
@endpush
