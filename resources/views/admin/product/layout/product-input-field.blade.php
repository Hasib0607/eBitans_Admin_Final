<style>
    .card {
        min-height: 135px;
        padding: 15px;
    }

    .col-lg-9, .col-lg-6, .col-lg-3, .col-lg-12 {
        padding: 3px;
    }

    .image-box {
        font-size: x-small;
        border: 1px dashed black;
        height: 95px;
        width: 95px;
        cursor: pointer;
    }

    .image-box input[type="file"] {
        opacity: 0;
        height: 95px;
        width: 95px;
        position: absolute;
        z-index: 2;
    }

    .image-box .content {
        position: relative;
        z-index: 1;
        color: #007bff;
    }

    .image-box .content h1 {
        font-size: 30px;
        margin: 0;
    }

    .image-box .content p {
        font-size: 10px;
    }

    img.thub {
        height: 95px;
        width: 95px;
    }

    .tutorial-section {

        color: #ffffff;
        min-height: 11vh;
        border-radius: 10px;
        font-size: 18px;
        font-weight: bold;
    }

    .center-cell {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .custom-scroll {
        height: 350px !important;
        /*overflow-y: scroll; !* Ensure vertical scrolling *!*/
        /*overflow-y: hidden; !* Hide vertical scrolling *!*/
        /*overflow-x: scroll; !* Enable horizontal scrolling *!*/
        /*white-space: nowrap;*/
        padding: 10px;
        box-sizing: border-box;
        outline: none; /* Adjust height as needed */
        overflow-y: auto;
        scroll-behavior: initial;
    }

    /* Custom Scrollbar for WebKit Browsers (e.g., Chrome, Safari) */
    .custom-scroll::-webkit-scrollbar {
        width: 8px; /* Vertical scrollbar width */
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: #f1d0c9;
        border-radius: 10px;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #dd8d7c;
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: padding-box;
    }

    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #f1593a;
    }

    .groupItemDiv {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    input.tagBro {
        width: inherit;
    }

    .groupItemDiv span {
        width: 100% !important;
    }

    .remove-color-pick img {
        display: none;
    }
</style>

<section class="container-fluid content-main mt-3">
    <div class="row ">
        <div class="col-lg-9 header-section">
            <div class="card h-100">
                <div class="d-flex">
                    <div class="input-upload d-flex" style="padding: 0;">
                        <input type="hidden" class="form-control" id="store_id" name="store_id"
                               value="{{ $store_id }}">
                        <output id="Filelist">
                            <ul class="thumb-Images overflow-x-auto" id="imgList">
                                <li class="image-box mx-2" style="height: 95px; width: 105px">
                                    <input type="file" class="form-control" id="image" name="image[]"
                                           multiple accept="image/*">
                                    @error('image')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror

                                    <div class="content text-center" id="placeholder">
                                        <p></p>
                                        <h1>+</h1>
                                        <p>Upload Image</p>
                                    </div>
                                </li>
                            </ul>
                        </output>
                        @if (isset($product) && $product['images'])
                            @php
                                $images = explode(',', $product['images']);
                            @endphp

                            @foreach ($images as $key => $image)
                                <div class="oldImg-wrap mx-2 imgWrapperDiv">
                                    <a href="javascript:void(0)"
                                           class="imageUploadRemoveBtn oldClose"
                                       data-remove-url="{{ URL::to('/') }}/product/removeimage/{{ $product['id'] }}/{{ $image }}">x</a>

                                    <img src="{{ URL::to('/') }}/assets/images/product/{{ $image }}"
                                         style="padding:10px;border:1px solid black;margin-bottom:5px;"
                                         width="105px" height="95px">


                                    <input type="hidden" class="form-control" id=""
                                           name="oldImage[]" value="{{ $image }}">
                                </div>
                            @endforeach
                        @endif

                        <br>
                        @if ($moduleIsNull == 1)
                            <label class="form-check"
                                   style="opacity:0; position:absolute; left:9999px;">
                                <input type="checkbox" class="form-check-input" id="is_checked"
                                       style="opacity:0; position:absolute; left:9999px;"
                                       name="is_checked"
                                       value="1"{{ $moduleIsNull == 1 ? 'checked' : 0 }}>
                                <span
                                    class="form-check-label">Yes, I converted it to webp file!</span>
                            </label>
                        @endif
                    </div>
                </div>
                <div class="image_position" style="position: absolute; right: 10px; top:2px">
                    @include('admin.product.share.layout-custom-design', ['title'=>'image', 'index' => '0'])
                </div>
            </div>
        </div>
        <div class="col-lg-3 header-section">
            <div class="card h-100" style="background-color: #F1593A;">
                <div class="card-body center-cell tutorial-section">
                    <div><i class="fa fa-play mr-2 " aria-hidden="true"></i> Tutorial</div>

                </div>
            </div>
        </div>
        <div class="col-lg-6 ">
            <div class="card h-100 p-4">
                @if (Session::has('error_message'))
                    <div class="alert alert-danger" style="color:#fff">
                        {{ Session::get('error_message') }}</div>
                @endif
                <div class="mb-4">
                    <label for="product_name" class="form-label d-flex justify-content-between">
                        <div>
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                পণ্য শিরোনাম
                            @else
                                Product Name
                            @endif
                            <span class="req">*</span>
                        </div>
                        @include('admin.product.share.layout-custom-design', ['title'=>'name', 'index' => '1'])
                    </label>
                    <input type="text" placeholder="Type here" class="form-control"
                           id="product_name"
                           name="product_name" value="{{ isset($product) ? $product['name'] : old('product_name') }}">
                    @error('product_name')
                    <p class="text-danger" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label  d-flex justify-content-between">
                        <div>
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                পূর্ণ বিবরণ
                            @else
                                Full description
                            @endif
                            <span class="req">*</span>
                        </div>
                        @include('admin.product.share.layout-custom-design', ['title'=>'description', 'index' => '2'])
                    </label>
                    <textarea hidden placeholder="Type here" class="form-control editor"
                              id="editor" rows="40"
                              name="description">{!! isset($product) ? $product['description'] : old('description') !!}</textarea>
                    @error('description')
                    <p class="text-danger" role="alert">{{ $message }}</p>
                    @enderror

                </div>

                @php
                    $userData = getUserData();
                    $store_id = $userData['store_id'];
                @endphp

                @if (ModulusStatus($store_id, 115))
                    <div class="col-md-12">
                        <div class="mb-4">
                            <label class="form-label  d-flex justify-content-between">
                                <div>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        Video Link
                                    @else
                                        Video Link
                                    @endif
                                    <span class="req">*</span>
                                </div>
                                @include('admin.product.share.layout-custom-design', ['title'=>'video_link', 'index' => '3'])
                            </label>
                            <div class="row gx-2">
                                <input placeholder="YouTube Embed Video Link" type="text"
                                       class="form-control" name="video_link"
                                       value="{{ isset($product) ? $product['video_link'] :old('video_link') }}">
                                @error('video_link')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                @if (ModulusStatus($store_id, 118))
                    <div class="col-md-12">
                        <div class="mb-4">
                            <label class="form-label">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    Expiry Date
                                @else
                                    Expiry Date
                                @endif
                            </label>
                            <div class="row gx-2">
                                <input type="date" class="form-control" id="expiry_date"
                                       value="{{ isset($product) ? $product['expiry_date'] : old('expiry_date') }}"
                                       name="expiry_date">
                                @error('expiry_date')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif


                    <?php

                    if (Illuminate\Support\Facades\Auth::check()) {
                        $user = Illuminate\Support\Facades\Auth::user();
                    }

                    //   ** if user is staff then there have to
                    //  findout store owner id which is into customers table **
                    if ($user->type == 'staff') {
                        $staff_assigned_store = DB::table('stores')
                            ->where('id', '=', $user->store_id)
                            ->first();
                        // owner/admin id from stores table
                        $admin_id = $staff_assigned_store->user_id;

                    }
                    // if user is not staff then set admin id as their user id
                    if ($user->type !== 'staff') {
                        $admin_id = $user->id;
                    }

                    $customer = DB::table('customers')
                        ->where('uid', '=', $admin_id)
                        ->first();


                    $digitalproductmodules = DB::table('moduluses')
                        ->where('id', '=', 110)
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

                    ?>

                {{--Digital product input--}}
                @if ($digitalproductstatus)
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="mb-4">
                                <label for="product_link" class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পণ্য লিংক
                                    @else
                                        Product link
                                    @endif
                                    <span class="req">*</span>
                                </label>
                                <input type="text" placeholder="Type here" class="form-control"
                                       id="product_link"
                                       name="product_link"
                                       value="{{ isset($product) ? $product['product_link'] : old('product_link') }}">
                                @error('product_link')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-4">
                            <label class="form-label d-flex justify-content-between">
                                <div>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        এসকেইউ
                                    @else
                                        SKU
                                    @endif
                                </div>
                                @include('admin.product.share.layout-custom-design', ['title'=>'SKU', 'index' => '4'])
                            </label>
                            <div class="row gx-2">
                                <input placeholder="SKU" type="text" class="form-control"
                                       name="SKU" value="{{ isset($product) ? $product['SKU']:old('SKU') }}">
                                @error('SKU')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-4">
                            <label class="form-label d-flex justify-content-between">
                                <div>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        নিয়মিত মূল্য
                                    @else
                                        Regular price ({{$current_currency->symbol}})
                                    @endif
                                    <span class="req">*</span>
                                </div>
                                @include('admin.product.share.layout-custom-design', ['title'=>'price', 'index' => '5'])
                            </label>
                            <div class="row gx-2">
                                <input placeholder="Regular price ({{$current_currency->symbol}})"
                                       type="number" step="0.01"
                                       min="0" class="form-control"
                                       name="regular_price"
                                       value="{{ isset($product) ? $product['regular_price'] : old('regular_price') }}">
                                @error('regular_price')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-4">
                            <label class="form-label">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    দ্রব্য মূল্য
                                @else
                                    Product Cost ({{$current_currency->code}})
                                @endif
                            </label>
                            <input placeholder="" type="number"
                                   min="0" step="0.01" class="form-control" name="cost"
                                   value="{{ isset($product) ? $product['cost'] : old('cost') }}">
                            @error('cost')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @php
                        $isQty = isset($product['unit']) && $product['unit'] == "qty" ? true : false;
                        $qtyOrVolume = 0;
                        $unitVariant = false;

                        if((isset($select_unitsss) && count($select_unitsss) > 0) || old('att') == 'unit'){
                            $unitVariant = true;
                            $qtyOrVolume= isset($product['unit']) && $product['unit'] == "qty" ? 0 : 1;
                        }
                    @endphp

                    <input type="hidden" id="qtyOrVolume" name="qtyOrVolume"
                           value="{{ old('qtyOrVolume') ?? $qtyOrVolume }}">
                    <div class="col-lg-4" id="productUnitDiv"
                         style="{{ $unitVariant || old('qtyOrVolume') == 1 ? 'display:block' : 'display:none' }}">
                        <div class="mb-4">
                            <label class="form-label">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ইউনিট
                                @else
                                    Unit
                                @endif
                                <span class="req">*</span>
                            </label>
                            <select name="productUnit" id="productUnit" class="form-control">
                                <option value=""> Select Unit</option>
                                    <?php
                                    $color = DB::table('units')
                                        ->where('store_id', $store_id)
                                        ->get();

                                    ?>
                                @if (isset($color))
                                    @foreach ($color as $cl)
                                        <option value="{{ $cl->name }}"
                                                @if ((isset($product['unit']) && $product['unit'] == $cl->name) || old('productUnit') == $cl->name) selected @endif>
                                            {{ $cl->name }}</option>
                                    @endforeach
                                    <option value="qty"
                                            @if(isset($product['unit']) && $product['unit'] == "qty") selected @endif>
                                        Quantity
                                    </option>
                                @endif
                            </select>

                            @error('productUnit')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4" id="productQtyDiv"
                         style="{{ ($unitVariant && !$isQty) || old('qtyOrVolume') == 1 ? 'display:none' : 'display:block' }}">
                        <div class="mb-4">
                            <label class="form-label d-flex justify-content-between">
                                <div>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পরিমাণ
                                    @else
                                        Quantity
                                    @endif
                                    <span class="req">*</span>
                                </div>
                                @include('admin.product.share.layout-custom-design', ['title'=>'quantity', 'index' => '6'])
                            </label>
                            <input placeholder="" type="number"
                                   min="0" class="form-control"
                                   name="quantity"
                                   value="{{ isset($product) ? $product['quantity'] : old('quantity') }}"
                                   id="productQty">
                            @error('quantity')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4" id="productVolumeDiv"
                         style="{{ ($unitVariant && !$isQty) || old('qtyOrVolume') == 1  ? 'display:block' : 'display:none' }}">
                        <div class="mb-4">
                            <label class="form-label">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    মোট আয়তন
                                @else
                                    Total Volume
                                @endif
                                <span class="req">*</span>
                            </label>
                            <input placeholder="" type="number"
                                   min="0" class="form-control"
                                   name="volume"
                                   value="{{ $product['volume'] ?? old('volume') }}"
                                   id="productVolume">
                            @error('volume')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label d-flex justify-content-between">
                            <div>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ডিসকাউন্ট টাইপ
                                @else
                                    Discount Type
                                @endif
                                <span class="req">*</span>
                            </div>
                            @include('admin.product.share.layout-custom-design', ['title'=>'discount_type', 'index' => '7'])
                        </label>
                        <select class="form-select" name="discount_type" id="discount_type">
                            <option value="no_discount"
                                {{ isset($product) && $product['discount_type'] == 'no_discount' ? 'selected' : '' }}>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    নো
                                    ডিসকাউন্ট
                                @else
                                    No Discount
                                @endif
                            </option>
                            <option value="fixed"
                                {{ isset($product) && $product['discount_type'] == 'fixed' ? 'selected' : '' }}>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ফিক্সড
                                @else
                                    Fixed
                                @endif
                            </option>
                            <option value="percent"
                                {{ isset($product) && $product['discount_type'] == 'percent' ? 'selected' : '' }}>
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    পার্সেন্ট
                                @else
                                    Percent
                                @endif
                            </option>

                        </select>
                        @error('discount_type')
                        <p class="text-danger" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-lg-4" id="discount_price"
                         style="{{ isset($product) && $product['discount_type'] != 'no_discount' ? 'display:block' : 'display:none' }}">
                        <div class="mb-4">
                            <label class="form-label d-flex justify-content-between">
                                <div>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ডিসকাউন্ট মূল্য
                                    @else
                                        Discount price ({{$current_currency->code}})
                                    @endif
                                </div>
                                @include('admin.product.share.layout-custom-design', ['title'=>'discount_price', 'index' => '8'])
                            </label>
                            <input placeholder="Discount price ({{$current_currency->code}})" type="number"
                                   min="0" step="0.01" class="form-control"
                                   name="promotional_price"
                                   id="promotional_price"
                                   value="{{ isset($product) ? $product['promotional_price'] : old('promotional_price') }}">
                            @error('promotional_price')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6" style="padding: 0 12px;">

                        <div class="mb-4">
                            <label class="form-label">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ট্যাক্সের
                                    ধরন
                                @else
                                    Tax Type
                                @endif
                            </label>
                            <select class="form-select" name="tax_type" id="tax_type">
                                <option value="no_tax"
                                    {{ isset($product) && $product['tax_type'] == 'no_tax' ? 'selected' : '' }}>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        নো ট্যাক্স
                                    @else
                                        No Tax
                                    @endif
                                </option>
                                <option value="fixed"
                                        @if (isset($product) && $product['tax_type'] == 'fixed') selected @endif>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        স্থির
                                    @else
                                        Fixed
                                    @endif
                                </option>
                                <option value="percent"
                                        @if (isset($product) && $product['tax_type'] == 'percent') selected @endif>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        শতাংশ
                                    @else
                                        Percent
                                    @endif
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6"
                         style="padding: 0 12px;{{ isset($product) && $product['tax_rate'] != 'no_tax' ? 'display:block' : 'display:none' }}"
                         id="tax_rate">
                        <div class="mb-4">
                            <label class="form-label d-flex justify-content-between">
                                <div>
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        করের হার
                                    @else
                                        Tax rate ({{$current_currency->symbol}})
                                    @endif
                                </div>
                                @include('admin.product.share.layout-custom-design', ['title'=>'tax_rate', 'index' => '9'])
                            </label>
                            <input placeholder="$" type="number" min="0" step="0.01"
                                   class="form-control" id="tax_price"
                                   value="{{ isset($product) ? $product['tax_rate'] : '' }}" name="tax_rate">
                        </div>
                    </div>

                    @if (isAddonActive(13))
                        <div class="col-lg-12" style="padding: 0 12px">
                            <div class="mb-4">
                                <label class="form-label d-flex justify-content-between">
                                    <div>
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            বার
                                            কোড
                                        @else
                                            Bar Code
                                        @endif
                                    </div>
                                    @include('admin.product.share.layout-custom-design', ['title'=>'bar_code', 'index' => '10'])
                                </label>
                                <input placeholder="" type="number"
                                       min="0" class="form-control"
                                       name="barcode"
                                       value="{{ isset($product) ? $product['barcode'] : old('barcode') }}">
                                @error('barcode')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
        <div class="col-lg-6 ">
            <div class="row px-2 ">
                <div class="col-lg-6">
                    <div class="card h-100 p-4">
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <label class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ক্যাটাগরি
                                    @else
                                        Category
                                    @endif
                                    <span class="req">*</span>
                                </label>

                                    <?php
                                    $category = DB::table('categories')
                                        ->where('parent', 0)
                                        ->where('store_id', $store_id)
                                        ->where('status', 'active')
                                        ->get();

                                    ?>

                                <div class="groupItemDiv">
                                    <select class="form-select" name="category[]" id="category" multiple>
                                        @foreach ($category as $cat)
                                            @isset($cat)
                                                <option value="{{ $cat->id }}"
                                                        @if ((isset($product) && in_array($cat->id, explode(',', $product['category'] ?? ""))) || old('category') == $cat->id ) selected @endif>{{ $cat->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <a href="{{ URL::to('category') }}" target="_blank"
                                       title="Add Category"><img src="{{ URL::to('/') }}/img/add.png" alt=""
                                                                 width="30px"></a>
                                </div>
                                @error('category')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        সাব ক্যাটাগরি
                                    @else
                                        Sub-category
                                    @endif
                                </label>
                                <div class="groupItemDiv">
                                    <select class="form-select" name="subcategory[]" id="subcategory" multiple>
                                        @if (isset($product) && isset($product['subcategory']))
                                                <?php
                                                $subcategory = DB::table('categories')
                                                    ->whereIn('id', explode(',', $product['subcategory'] ?? ""))
                                                    ->where('status', 'active')
                                                    ->get();
                                                ?>

                                            @foreach ($subcategory as $subcat)
                                                @isset($subcat)
                                                    <option value="{{ $subcat->id }}"
                                                            @if ((isset($product) && in_array($subcat->id, explode(',', $product['subcategory'] ?? ""))) ||  in_array($subcat->id, explode(',', old('subcategory') ?? ""))) selected @endif>{{ $subcat->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>

                                    <a href="{{ URL::to('subcategory') }}" target="_blank"
                                       title="Add Sub-Category"><img src="{{ URL::to('/') }}/img/add.png" alt=""
                                                                     width="30px"></a>
                                </div>
                                @error('subcategory')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ব্র্যান্ড
                                    @else
                                        Brand
                                    @endif
                                </label>
                                    <?php
                                    $brands = DB::table('brands')
                                        ->where('store_id', $store_id)
                                        ->get();
                                    ?>
                                <div class="groupItemDiv">
                                    <select class="form-select" name="brand" id="brand">
                                        <option value="null">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                ব্র্যান্ড নির্বাচন করুন
                                            @else
                                                Select Brand
                                            @endif
                                        </option>
                                        @foreach ($brands as $brand)
                                            @isset($brand)
                                                <option value="{{ $brand->id }}"
                                                        @if ((isset($product['brand']) && $product['brand'] == $brand->id) || old('brand') == $brand->id ) selected @endif>{{ $brand->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <a href="{{ URL::to('brand') }}" target="_blank"
                                       title="Add Brand"><img src="{{ URL::to('/') }}/img/add.png" alt=""
                                                              width="30px"></a>
                                </div>
                                @error('brand')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        সরবরাহকারী
                                    @else
                                        Supplier
                                    @endif
                                </label>
                                    <?php
                                    $suppliers = DB::table('suppliers')
                                        ->where('store_id', $store_id)
                                        ->get();
                                    ?>
                                <div class="groupItemDiv">
                                    <select class="form-select" name="supplier" id="brand">
                                        <option value="null">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                সরবরাহকারী নির্বাচন করুন
                                            @else
                                                Select Supplier
                                            @endif
                                        </option>
                                        @foreach ($suppliers as $supplier)
                                            @isset($supplier)
                                                <option
                                                    @if ((isset($product['supplier']) && $product['supplier'] == $supplier->id) || old('supplier') == $supplier->id ) selected
                                                    @endif
                                                    value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <a href="{{ URL::to('supplier') }}" target="_blank"
                                       title="Add Supplier"><img src="{{ URL::to('/') }}/img/add.png" alt=""
                                                                 width="30px"></a>
                                </div>
                                @error('supplier')
                                <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card p-4">
                        <div class="mb-2">
                            <label for="best_sell" class="form-label">
                                <input
                                    type="checkbox"
                                    id="best_sell"
                                    name="best_sell"
                                    @if (isset($product) && $product['best_sell'] == 1) checked @endif
                                >&nbsp;&nbsp;Best
                                Sell</label>
                            @error('best_sell')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="feature" class="form-label">
                                <input
                                    type="checkbox"
                                    id="feature"
                                    name="feature"
                                    @if (isset($product) && $product['feature'] == 1) checked @endif
                                >&nbsp;&nbsp;Feature</label>
                            @error('feature')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="pse" class="form-label">
                                <input
                                    type="checkbox"
                                    id="pse"
                                    name="pse"
                                    value="1"
                                    @if (isset($product) && $product['pse'] == 1) checked @endif
                                >&nbsp;&nbsp;Request For Product খুঁজো
                                List</label>

                            @error('pse')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="card p-4" style="margin-top: 7px">

                        <div class="mb-4">
                            <label for="product_name" class="form-label">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ট্যাগ
                                @else
                                    Tags
                                @endif
                            </label>
                            <input type="text" class="form-control" data-role="tagsinput" name="tags"
                                   value="{{ isset($product) ? $product['tags'] : '' }}"
                                   placeholder="Enter a comma after each
                                                        tag">
                            <div class="error" style="font-size: 11px; color: red;">Enter a comma after each
                                tag
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="product_name" class="form-label">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    এসইও
                                    কীওয়ার্ড
                                @else
                                    SEO Keywords
                                @endif
                            </label>
                            <input type="text" value="{{ isset($product) ? $product['seo_keywords'] : old('seo') }}"
                                   class="form-control"
                                   id="product_name" data-role="tagsinput" name="seo"
                                   style="width:100%;display: block;">
                            <div class="error" style="font-size: 11px; color: red;">
                                Enter a comma after each tag
                            </div>
                            @error('seo')
                            <p class="text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
            @if (ModulusStatus($store_id, 114))
                <div class="card p-4" style="margin-top: 3px; min-height: 500px">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ভেরিয়েন্ট টাইপ
                                @else
                                    Variantion Type
                                @endif
                            </label>

                            <div class="groupItemDiv">
                                <select class="form-control" name="att" id="attributes">
                                    <option value="none">Select</option>
                                    <option
                                        value="color"
                                        @if ((isset($attri_color) && count($attri_color) > 0) || old('att') == 'color' ) selected @endif
                                    >Color & size
                                    </option>
                                    <option
                                        value="onlycolor"
                                        @if ((isset($select_onlycolor) && count($select_onlycolor) > 0) || old('att') == 'onlycolor' ) selected @endif
                                    >Color
                                    </option>
                                    <option
                                        value="unit"
                                        @if ((isset($select_unitsss) && count($select_unitsss) > 0) || old('att') == 'unit' ) selected @endif
                                    >Unit
                                    </option>
                                    <option
                                        value="size"
                                        @if ((isset($select_sizess) && count($select_sizess) > 0) || old('att') == 'size' ) selected @endif
                                    >Size
                                    </option>
                                </select>
                                <a href="{{ URL::to('attribute') }}" id="addVariantBtn" target="_blank"
                                   title="Add Variantion"><img src="{{ URL::to('/') }}/img/add.png" alt=""
                                                               width="30px"></a>
                            </div>
                        </div>

                        {{--color and size variant--}}
                        <div id="colorrss" class="col-lg-12 mt-3  custom-scroll">
                            @if (isset($attri_colorss) && count($attri_colorss) > 0)
                                <div class="colorrss_ok table-responsive">
                                    <table class="table table-stripped" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="30%" style="text-align:center">Color</th>
                                            <th width="20%" style="text-align:center">Size</th>
                                            <th width="15%" style="text-align:center">Quantity</th>
                                            <th width="15%" style="text-align:center">(+/-)Price
                                            </th>
                                            <th width="10%" style="text-align:center">Media</th>
                                            <th width="10%" style="text-align:center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($attri_colorss as $keyss => $colorsss)
                                            <tr id="{{ $colorsss->id }}">
                                                <td class="mt-1" style="text-align:center;">
                                                    <div style="display: flex ; gap: 2px;">
                                                        <select name="cs_color[]" id="color"
                                                                class="form-control" step="any">
                                                                <?php
                                                                $colors = DB::table('colors')
                                                                    ->where('store_id', $store_id)
                                                                    ->get();
                                                                ?>
                                                            @if (isset($colors))
                                                                @foreach ($colors as $cl)
                                                                    <option value="{{ $cl->code }}"
                                                                            @if ($colorsss->color == $cl->code) selected @endif>
                                                                        {{ $cl->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(isset($colorsss->color_image))
                                                            <div class="oldImg-wrap"
                                                                 style="display: flex;justify-content: center;">
                                                                <a class="oldClose"
                                                                   href="{{ route('admin.variantColorImageDelete', ['id' => $colorsss->id]) }}">x</a>
                                                                <img
                                                                    src="{{ asset('assets/images/product/'.$colorsss->color_image) }}"
                                                                    style="border:1px solid black;width: 50px; height: 50px;margin-left: 2px"
                                                                    width="60px" height="60px">
                                                            </div>
                                                            <input type="hidden"
                                                                   name="cs_colorImageOld[{{ $keyss }}][]"
                                                                   value="{{ $colorsss->color_image }}">
                                                        @else
                                                            <div class="image-input-wrapper">
                                                                <label for="imageInput{{ $colorsss->id }}"
                                                                       class="image-input-button">
                                                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                                </label>
                                                                <input type="file"
                                                                       style="display: none"
                                                                       id="imageInput{{ $colorsss->id }}"
                                                                       class="form-control mt-2"
                                                                       onchange="variantImage(event)"
                                                                       accept="image/*"
                                                                       name="cs_color_updateImage[{{ $colorsss->color }}][]"
                                                                />
                                                            </div>
                                                        @endif

                                                    </div>
                                                </td>
                                                <td class="mt-1" style="text-align:center">
                                                    <div class="col-md-1 mt-1" hidden>
                                                        <input type="checkbox"
                                                               name="sid[{{ $keyss }}][]" checked>
                                                    </div>
                                                    <select name="cs_size[{{ $keyss }}][]"
                                                            id="sizs" class="form-control"
                                                            step="any">
                                                            <?php
                                                            $size = DB::table('sizes')
                                                                ->where('store_id', $store_id)
                                                                ->get();
                                                            ?>
                                                        @if (isset($size))
                                                            @foreach ($size as $key => $sz)
                                                                <option value="{{ $sz->name }}"
                                                                        @if ($colorsss->size == $sz->name) selected @endif>
                                                                    {{ $sz->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                                <td class="mt-1" style="text-align:center">
                                                    <input type="number" min="0.00"
                                                           name="cs_qty[{{ $keyss }}][]"
                                                           class="form-control colorSizeQty"
                                                           onchange="variantQtyCheck(this, 'color')"
                                                           id="qunty"
                                                           value="{{ $colorsss->quantity }}">
                                                </td>
                                                <input type="hidden" name="attriid" id="attriid"
                                                       value="{{ $colorsss->id }}">
                                                <input type="hidden" name="cs_attrId[{{ $keyss }}][]"
                                                       value="{{ $colorsss->id }}">
                                                <td class="mt-1" style="text-align:center"><input
                                                        type="number" min="0.00"
                                                        name="cs_price[{{ $keyss }}][]"
                                                        id="additionalpricess" class="form-control"
                                                        value="{{ $colorsss->additional_price ?? 0 }}">
                                                </td>
                                                <td style="display: flex; justify-content: center;">
                                                    @if($colorsss->image)
                                                        <div class="oldImg-wrap"
                                                             style="display: flex;justify-content: center;">
                                                            <a class="oldClose"
                                                               href="{{ route('admin.variantImageDelete', ['id' => $colorsss->id]) }}">x</a>
                                                            <img
                                                                src="{{ asset('assets/images/product/'.$colorsss->image) }}"
                                                                style="border:1px solid black;"
                                                                width="60px" height="60px">
                                                        </div>
                                                        <input type="hidden"
                                                               name="cs_ImageOld[{{ $keyss }}][]"
                                                               value="{{ $colorsss->image }}">
                                                    @else
                                                        <div class="image-input-wrapper">
                                                            <label for="imageInput{{$keyss}}"
                                                                   class="image-input-button">
                                                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                            </label>
                                                            <input type="file"
                                                                   style="display: none"
                                                                   id="imageInput{{$keyss}}"
                                                                   class="form-control"
                                                                   onchange="variantImage(event)"
                                                                   accept="image/*"
                                                                   name="cs_Image[{{ $keyss }}][]"
                                                            />
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="mt-1" style="text-align:center"><a
                                                        href="javascript:void(0)" class="deleteattri"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Delete"><img
                                                            src="{{ URL::to('/') }}/img/delete.png"
                                                            alt="" width="30px"></a></td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-stripped" id="officers-table">
                                    <tbody>

                                    @php
                                        if ($keyss ?? 0 > 0) {
                                            $keyss = $keyss + 1;
                                        }
                                    @endphp

                                        <?php $i = $keyss ?? 0; ?>

                                    <tr id="new" style="margin-top:5px;">
                                        <td>
                                            <label>Color:</label>
                                            <select name="cs_color[]" id="color"
                                                    class="form-control" step="any">
                                                <option readonly> Select Color</option>
                                                    <?php
                                                    $colors = DB::table('colors')
                                                        ->where('store_id', $store_id)
                                                        ->orderBy('position', 'asc')
                                                        ->get();

                                                    ?>
                                                @if (isset($colors))
                                                    @foreach ($colors as $cl)
                                                        <option value="{{ $cl->code }}">
                                                            {{ $cl->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <input type="file"
                                                   class="form-control mt-2"
                                                   onchange="variantImage(event)"
                                                   accept="image/*"
                                                   name="cs_color_image[{{ $i }}]"
                                            />
                                        </td>
                                        <td>
                                                <?php
                                                $size = DB::table('sizes')
                                                    ->where('store_id', $store_id)
                                                    ->orderBy('position', 'asc')
                                                    ->get();
                                                ?>
                                            @if (isset($size))
                                                @foreach ($size as $key => $sz)
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="row">
                                                                <div class="row-md-6">
                                                                    <label>size</label>
                                                                </div>
                                                                <div class="row-md-6">
                                                                    <div
                                                                        style="display: flex !important; gap: 10px !important;">
                                                                        <input type="checkbox"
                                                                               onclick="checkBox({{ $i + $key }})"
                                                                               id="checkBoxStatus{{ $i + $key }}"
                                                                               name="sid[{{ $i }}][]"
                                                                               value="yes">
                                                                        <input type="text"
                                                                               class="form-control"
                                                                               name="cs_size[{{ $i }}][]"
                                                                               value="{{ $sz->name }}"
                                                                               readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Quantity</label>
                                                            <input type="number" min="0.00"
                                                                   class="form-control colorSizeQty"
                                                                   name="cs_qty[{{ $i }}][]"
                                                                   id="checkBoxWrite{{ $i + $key }}"
                                                                   onchange="variantQtyCheck(this, 'color')"
                                                                   readonly
                                                                   placeholder="Enter Quantity"
                                                                   value="">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>(+/-)Price</label>
                                                            <input type="number" min="0.00"
                                                                   class="form-control"
                                                                   name="cs_price[{{ $i }}][]"
                                                                   placeholder="Price"
                                                                   value="0">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Media</label>
                                                            <input type="file"
                                                                   class="form-control"
                                                                   onchange="variantImage(event)"
                                                                   accept="image/*"
                                                                   name="cs_Image[{{ $i }}][]"
                                                            />
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <a class="remove-officer-button mt-3"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="Delete"><img
                                                    src="{{ URL::to('/') }}/img/delete.png"
                                                    alt="" width="30px"
                                                    style="margin-bottom:5px;"></a>
                                            <br>
                                            <a onclick="addRow({{ $i }})"
                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                               title="Add"><img src="{{ URL::to('/') }}/img/add.png"
                                                                alt="" width="30px"></a>
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{--onlycolor variant--}}
                        <div id="onlycolors" class="col-lg-12 mt-3  custom-scroll">

                            @if (isset($attri_onlycolor) && count($attri_onlycolor) > 0)
                                <table class="colorrss_ok table table-stripped" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="25%" style="text-align:center">Color</th>
                                        <th width="25%" style="text-align:center">Quantity</th>
                                        <th width="20%" style="text-align:center">(+/-)Price</th>
                                        <th width="15%" style="text-align:center">Media</th>
                                        <th width="15%" style="text-align:center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($attri_onlycolor as $colorssss)
                                        <tr>
                                            <td style="text-align:center">
                                                <select name="c_color[]" id="color"
                                                        class="form-control" step="any">
                                                        <?php
                                                        $colors = DB::table('colors')
                                                            ->where('store_id', $store_id)
                                                            ->get();
                                                        ?>
                                                    @if (isset($colors))
                                                        @foreach ($colors as $cl)
                                                            <option value="{{ $cl->code }}"
                                                                    @if ($cl->code == $colorssss->color) selected @endif>
                                                                {{ $cl->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td style="text-align:center">
                                                <input type="number" name="c_qty[]"
                                                       class="form-control onlyColorQty"
                                                       onchange="variantQtyCheck(this, 'onlycolor')"
                                                       id="" min="0"
                                                       value="{{ $colorssss->quantity }}">
                                            </td>
                                            <input type="hidden" name="attriid" id="attriid"
                                                   value="{{ $colorssss->id }}">
                                            <input type="hidden" name="c_attrId[]"
                                                   value="{{ $colorssss->id }}">
                                            <td style="text-align:center">
                                                <input type="number" name="c_price[]" id=""
                                                       class="form-control"
                                                       value="{{ $colorssss->additional_price }}"
                                                       min="0">
                                            </td>
                                            <td style="display: flex; justify-content: center;">
                                                @if(isset($colorssss->image))
                                                    <div class="oldImg-wrap"
                                                         style="display: flex;justify-content: center;">
                                                        <a class="oldClose"
                                                           href="{{ route('admin.variantImageDelete', ['id' => $colorssss->id]) }}">x</a>
                                                        <img
                                                            src="{{ asset('assets/images/product/'.$colorssss->image) }}"
                                                            style="border:1px solid black;"
                                                            width="60px" height="60px">
                                                    </div>
                                                    <input type="hidden"
                                                           name="c_ImageOld[{{$colorssss->id}}]"
                                                           value="{{ $colorssss->image }}">
                                                @else
                                                    <input type="file"
                                                           class="form-control"
                                                           name="c_Image[]"
                                                           onchange="variantImage(event)"
                                                           accept="image/*"
                                                    />
                                                @endif
                                            </td>
                                            <td style="text-align:center">
                                                <a href="javascript:void(0)"
                                                   class="deleteonlycolorattri"><img
                                                        src="{{ URL::to('/') }}/img/delete.png"
                                                        alt="" width="30px"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <table class="table table-stripped" id="officers-table3">
                                <tbody>
                                <tr id="new3" style="margin-top:5px;">
                                    <td>
                                        <div class="row">
                                            <div class="col-md-2">
                                                Color
                                            </div>
                                            <div class="col-md-3">
                                                Quantity
                                            </div>
                                            <div class="col-md-2">
                                                (+/-)Price
                                            </div>
                                            <div class="col-md-3">
                                                Media
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top:5px;">
                                            <div class="col-md-2">
                                                <select name="c_color[]" id="color"
                                                        class="form-control" step="any">
                                                    <option> Select Color</option>
                                                        <?php
                                                        $colorsss = DB::table('colors')
                                                            ->where('store_id', $store_id)
                                                            ->orderBy('position', 'asc')
                                                            ->get();
                                                        ?>
                                                    @if (isset($colorsss))
                                                        @foreach ($colorsss as $cl)
                                                            <option value="{{ $cl->code }}">
                                                                {{ $cl->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control onlyColorQty"
                                                       name="c_qty[]"
                                                       onchange="variantQtyCheck(this, 'onlycolor')"
                                                       placeholder="Enter Quantity"
                                                       min="0" value="">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control"
                                                       name="c_price[]" placeholder="Enter Price"
                                                       min="0" value="0">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="file"
                                                       class="form-control"
                                                       name="c_Image[]"
                                                       onchange="variantImage(event)"
                                                       accept="image/*"
                                                />
                                            </div>
                                            <div class="col-md-2">
                                                <a class="remove-officer-button3 mt-3"
                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                   title="Delete"><img
                                                        src="{{ URL::to('/') }}/img/delete.png"
                                                        alt="" width="30px"
                                                        style="margin-bottom:5px;"></a>
                                                <br>
                                                <a class="" onclick="addOnlycolor()"
                                                   data-bs-toggle="tooltip" data-bs-placement="top"
                                                   title="Add"><img
                                                        src="{{ URL::to('/') }}/img/add.png"
                                                        alt="" width="30px"></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        {{--unit variant--}}
                        <div id="unittss" class="col-lg-12 mt-3  custom-scroll">

                            @if (isset($attri_unitsss) && count($attri_unitsss) > 0)
                                <div class="table-responsive">
                                    <table class="colorrss_ok table table-stripped" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="20%" style="text-align:center">Volume</th>
                                            <th width="20%" style="text-align:center">Unit</th>
                                            <th width="15%" style="text-align:center">Quantity</th>
                                            <th width="15%" style="text-align:center">(+/-)Price
                                            </th>
                                            <th width="15%" style="text-align:center">Media</th>
                                            <th width="15%" style="text-align:center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($attri_unitsss as $unitssss)
                                            <tr>
                                                <td class="mt-1" style="text-align:center">
                                                    <input type="number" step="0.01"
                                                           class="form-control" name="u_volume[]"
                                                           id="" value="{{ $unitssss->volume }}">
                                                </td>
                                                <td class="mt-1">
                                                    <select name="u_unit[]" id=""
                                                            class="form-control" step="any">
                                                        <option> Select Unit</option>
                                                            <?php
                                                            $color = DB::table('units')
                                                                ->where('store_id', $store_id)
                                                                ->get();

                                                            ?>
                                                        @if (isset($color))
                                                            @foreach ($color as $cl)
                                                                <option value="{{ $cl->name }}"
                                                                        @if ($unitssss->unit == $cl->name) selected @endif>
                                                                    {{ $cl->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                                <td class="mt-1" style="text-align:center">
                                                    <input type="number" name="u_qty[]"
                                                           id="" class="form-control unitQty"
                                                           onchange="variantQtyCheck(this, 'unit')"
                                                           value="{{ $unitssss->quantity }}">
                                                </td>
                                                <input type="hidden" name="attriid" id="attriid"
                                                       value="{{ $unitssss->id }}">
                                                <input type="hidden" name="u_attrId[]"
                                                       value="{{ $unitssss->id }}">
                                                <td class="mt-1" style="text-align:center"><input
                                                        type="number" name="u_price[]" id=""
                                                        class="form-control"
                                                        value="{{ $unitssss->additional_price ?? 0 }}">
                                                </td>
                                                <td style="display: flex; justify-content: center;">
                                                    @if($unitssss->image)
                                                        <div class="oldImg-wrap"
                                                             style="display: flex;justify-content: center;">
                                                            <a class="oldClose"
                                                               href="{{ route('admin.variantImageDelete', ['id' => $unitssss->id]) }}">x</a>
                                                            <img
                                                                src="{{ asset('assets/images/product/'.$unitssss->image) }}"
                                                                style="border:1px solid black;"
                                                                width="60px" height="60px">
                                                        </div>
                                                        <input type="hidden"
                                                               name="u_ImageOld[{{$unitssss->id}}]"
                                                               value="{{ $unitssss->image }}">
                                                    @else
                                                        <input type="file"
                                                               class="form-control"
                                                               name="u_Image[]"
                                                               onchange="variantImage(event)"
                                                               accept="image/*"
                                                        />
                                                    @endif
                                                </td>
                                                <td class="mt-1" style="text-align:center">
                                                    <a href="javascript:void(0)" class="deleteunitattri"
                                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                                       title="Delete"><img
                                                            src="{{ URL::to('/') }}/img/delete.png"
                                                            alt="" width="30px"></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-stripped" id="officers-table1">
                                    <tbody>
                                    <tr id="new1" style="margin-top:5px;">
                                        <td class="mt-1">
                                            <div class="row">
                                                <div class="col-md-2 mt-1">
                                                    Volume
                                                </div>
                                                <div class="col-md-2 mt-1">
                                                    Unit
                                                </div>
                                                <div class="col-md-2 mt-1">
                                                    Quantity
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    (+/-)Price
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    Media
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top:5px;">
                                                <div class="col-md-2 mt-1">
                                                    <input type="number" step="0.01"
                                                           class="form-control" name="u_volume[]"
                                                           value="">
                                                </div>
                                                <div class="col-md-2 mt-1">
                                                    <select name="u_unit[]" id="color"
                                                            class="form-control" step="any">
                                                        <option> Select Unit</option>
                                                            <?php
                                                            $color = DB::table('units')
                                                                ->where('store_id', $store_id)
                                                                ->get();

                                                            ?>
                                                        @if (isset($color))
                                                            @foreach ($color as $cl)
                                                                <option value="{{ $cl->name }}">
                                                                    {{ $cl->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mt-1">
                                                    <input type="number" class="form-control unitQty"
                                                           name="u_qty[]"
                                                           onchange="variantQtyCheck(this, 'unit')"
                                                           placeholder="Enter Quantity" value="">
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <input type="number" class="form-control"
                                                           name="u_price[]"
                                                           placeholder="Enter Price"
                                                           value="0">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="file"
                                                           class="form-control"
                                                           name="u_Image[]"
                                                           onchange="variantImage(event)"
                                                           accept="image/*"
                                                    />
                                                </div>
                                            </div>
                                        </td>
                                        <td class="mt-1">
                                            <a class="remove-officer-button1  mt-3"
                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                               title="Delete"><img
                                                    src="{{ URL::to('/') }}/img/delete.png"
                                                    alt="" width="30px"
                                                    style="margin-bottom:5px;"></a>
                                            <br>
                                            <a onclick="addUnit()" data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="Add"><img
                                                    src="{{ URL::to('/') }}/img/add.png"
                                                    alt="" width="30px"></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{--Size variant--}}
                        <div id="sizess" class="col-lg-12 mt-3  custom-scroll"

                        @if (isset($attri_sizess) && count($attri_sizess) > 0)
                            <div class="table-responsive">
                                <table class="colorrss_ok table table-stripped" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="25%" style="text-align:center">Size</th>
                                        <th width="25%" style="text-align:center">Quantity</th>
                                        <th width="20%" style="text-align:center">(+/-)Price
                                        </th>
                                        <th width="15%" style="text-align:center">Media</th>
                                        <th width="15%" style="text-align:center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($attri_sizess as $sizesss)
                                        <tr>
                                            <td class="mt-1" style="text-align:center">
                                                <select name="s_size[]" id=""
                                                        class="form-control" step="any">
                                                        <?php
                                                        $size = DB::table('sizes')
                                                            ->where('store_id', $store_id)
                                                            ->get();
                                                        ?>
                                                    @if (isset($size))
                                                        @foreach ($size as $key => $sz)
                                                            <option value="{{ $sz->name }}"
                                                                    @if ($sizesss->size == $sz->name) selected @endif>
                                                                {{ $sz->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="mt-1" style="text-align:center"><input
                                                    type="number" name="s_qty[]" id=""
                                                    onchange="variantQtyCheck(this, 'size')"
                                                    class="form-control sizeQty"
                                                    value="{{ $sizesss->quantity }}"></td>
                                            <input type="hidden" name="attriid" id="attriid"
                                                   value="{{ $sizesss->id }}">
                                            <input type="hidden" name="s_attrId[]"
                                                   value="{{ $sizesss->id }}">
                                            <td class="mt-1" style="text-align:center"><input
                                                    type="number" name="s_price[]" id=""
                                                    class="form-control"
                                                    value="{{ $sizesss->additional_price ?? 0 }}">
                                            </td>
                                            <td style="display: flex; justify-content: center;">
                                                @if($sizesss->image)
                                                    <div class="oldImg-wrap"
                                                         style="display: flex;justify-content: center;">
                                                        <a class="oldClose"
                                                           href="{{ route('admin.variantImageDelete', ['id' => $sizesss->id]) }}">x</a>
                                                        <img
                                                            src="{{ asset('assets/images/product/'.$sizesss->image) }}"
                                                            style="border:1px solid black;"
                                                            width="60px" height="60px">
                                                    </div>
                                                    <input type="hidden"
                                                           name="s_ImageOld[{{$sizesss->id}}]"
                                                           value="{{ $sizesss->image }}">
                                                @else
                                                    <input type="file"
                                                           class="form-control"
                                                           name="s_Image[]"
                                                           onchange="variantImage(event)"
                                                           accept="image/*"
                                                    />
                                                @endif
                                            </td>
                                            <td class="mt-1" style="text-align:center">
                                                <a href="javascript:void(0)"
                                                   class="deletesizeattri"><img
                                                        src="{{ URL::to('/') }}/img/delete.png"
                                                        alt="" width="30px"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-stripped" id="officers-table2">
                                <tbody>
                                <tr id="new2" style="margin-top:5px;">
                                    <td class="mt-1">
                                        <div class="row">
                                            <div class="col-md-3 mt-1">
                                                size
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                Quantity
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                (+/-)Price
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                Media
                                            </div>
                                        </div>
                                            <?php
                                            $size = DB::table('sizes')
                                                ->where('store_id', $store_id)
                                                ->orderBy('position', 'asc')
                                                ->get();
                                            ?>
                                        @if (isset($size))
                                            @foreach ($size as $key => $sz)
                                                <div class="row" style="margin-top:5px;">
                                                    <div class="col-md-3 mt-1">
                                                        <input type="text" class="form-control"
                                                               name="s_size[]"
                                                               value="{{ $sz->name }}" readonly>
                                                    </div>
                                                    <div class="col-md-3 mt-1">
                                                        <input type="number"
                                                               class="form-control sizeQty"
                                                               name="s_qty[]"
                                                               onchange="variantQtyCheck(this, 'size')"
                                                               placeholder="Enter Quantity"
                                                               value="">
                                                    </div>
                                                    <div class="col-md-3 mt-1">
                                                        <input type="number"
                                                               class="form-control"
                                                               name="s_price[]"
                                                               placeholder="Enter Price"
                                                               value="0">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="file"
                                                               class="form-control"
                                                               name="s_Image[]"
                                                               onchange="variantImage(event)"
                                                               accept="image/*"
                                                        />
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-lg-12">
        @if(isset($customizable) && $customizable)
            @if(isset($editPage) && $editPage)
                <div class="card mb-4">
                    <div class="card-header  d-flex justify-content-between">
                        <h4>
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                মৌলিক
                            @else
                                Additional Details
                            @endif
                        </h4>
                        <div class="w-50">
                            <div class="input-group">
                                <select class="form-select h-100" id="type-define">
                                    <option selected>Choose..</option>
                                    <option value="title">Title</option>
                                    <option value="subtitle">Sub-Title</option>
                                    <option value="description">Description</option>
                                    <option value="button">Button</option>
                                    <option value="image">Image</option>
                                </select>
                                <button class="btn btn-primary" id="design-add" type="button">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="design-list">
                            @php
                                $count = 11;
                            @endphp
                            @if(isset($product['layout']) && count($product['layout']) > 0)
                                <script src="{{ asset('admin/dist/js/ckeditor.js') }}"></script>
                                @foreach($product['layout'] as $key=>$layout)
                                    @switch($layout['type'])
                                        @case('title')
                                            <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                <input type="hidden" name="layouts[{{$count}}][id]"
                                                       value="{{$layout['id']}}">
                                                <div class="d-flex justify-content-between">
                                                    <h6>Title - {{$count - 10}}</h6>
                                                    <a href="{{ route("admin.remove.layout.item", ['id' => $layout['id']]) }}"><i
                                                            class="fa fa-times cursor-pointer design-remove"></i></a>
                                                </div>
                                                <input type="hidden" name="layouts[{{$count}}][type]"
                                                       value="title">
                                                <div class="row">
                                                    <div class="mb-2 col">
                                                        <label for="product_name"
                                                               class="form-label d-flex justify-content-between">
                                                            <div>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    অবস্থান
                                                                @else
                                                                    Position
                                                                @endif
                                                                <span class="req">*</span>
                                                            </div>
                                                        </label>
                                                        <input type="text" placeholder="Type here"
                                                               class="form-control bg-white"
                                                               id="product_name"
                                                               value="{{$layout['position']}}"
                                                               name="layouts[{{$count}}][position]">
                                                        <input type="hidden"
                                                               name="layouts[{{$count}}][type]"
                                                               value="title">
                                                    </div>
                                                    <div class="mb-4 col-md-12">
                                                        <label class="form-label">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                উিরোনাম
                                                            @else
                                                                Title
                                                            @endif
                                                        </label>
                                                        <textarea placeholder="Type here"
                                                                  class="form-control" id="editor{{$count}}"
                                                                  rows="8"
                                                                  name="layouts[{{$count}}][text]">
                                                                        {!! Request::old('details', $layout['text'] ?? '') !!}
                                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @break
                                        @case('subtitle')
                                            <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                <input type="hidden" name="layouts[{{$count}}][id]"
                                                       value="{{$layout['id']}}">
                                                <div class="d-flex justify-content-between">
                                                    <h6>Sub-Title - {{$count - 10}}</h6>
                                                    <a href="{{ route("admin.remove.layout.item", ['id' => $layout['id']]) }}"><i
                                                            class="fa fa-times cursor-pointer design-remove"></i></a>
                                                </div>
                                                <input type="hidden" name="layouts[{{$count}}][type]"
                                                       value="title">
                                                <div class="row">
                                                    <div class="mb-2 col">
                                                        <label for="product_name"
                                                               class="form-label d-flex justify-content-between">
                                                            <div>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    অবস্থান
                                                                @else
                                                                    Position
                                                                @endif
                                                                <span class="req">*</span>
                                                            </div>
                                                        </label>
                                                        <input type="text" placeholder="Type here"
                                                               class="form-control bg-white"
                                                               id="product_name"
                                                               value="{{$layout['position']}}"
                                                               name="layouts[{{$count}}][position]">
                                                        <input type="hidden"
                                                               name="layouts[{{$count}}][type]"
                                                               value="subtitle">
                                                    </div>
                                                    <div class="mb-4 col-md-12">
                                                        <label class="form-label">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                উপ-শিরোনাম
                                                            @else
                                                                sub-title
                                                            @endif
                                                        </label>
                                                        <textarea placeholder="Type here"
                                                                  class="form-control" id="editor{{$count}}"
                                                                  rows="8"
                                                                  name="layouts[{{$count}}][text]">
                                                                        {!! Request::old('details', $layout['text'] ?? '') !!}
                                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @break
                                        @case('description')
                                            <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                <input type="hidden" name="layouts[{{$count}}][id]"
                                                       value="{{$layout['id']}}">
                                                <div class="d-flex justify-content-between">
                                                    <h6>Description - {{$count - 10}}</h6>
                                                    <a href="{{ route("admin.remove.layout.item", ['id' => $layout['id']]) }}"><i
                                                            class="fa fa-times cursor-pointer design-remove"></i></a>
                                                </div>
                                                <input type="hidden" name="layouts[{{$count}}][type]"
                                                       value="title">
                                                <div class="row">
                                                    <div class="mb-2 col">
                                                        <label for="product_name"
                                                               class="form-label d-flex justify-content-between">
                                                            <div>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    অবস্থান
                                                                @else
                                                                    Position
                                                                @endif
                                                                <span class="req">*</span>
                                                            </div>
                                                        </label>
                                                        <input type="text" placeholder="Type here"
                                                               class="form-control bg-white"
                                                               id="product_name"
                                                               value="{{$layout['position']}}"
                                                               name="layouts[{{$count}}][position]">
                                                        <input type="hidden"
                                                               name="layouts[{{$count}}][type]"
                                                               value="description">
                                                    </div>
                                                    <div class="mb-4 col-md-12">
                                                        <label class="form-label">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                বিস্তারিত
                                                            @else
                                                                Description
                                                            @endif
                                                        </label>
                                                        <textarea placeholder="Type here"
                                                                  class="form-control" id="editor{{$count}}"
                                                                  rows="8"
                                                                  name="layouts[{{$count}}][text]">
                                                                        {!! Request::old('details', $layout['text'] ?? '') !!}
                                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @break
                                        @case('button')
                                            <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                <input type="hidden" name="layouts[{{$count}}][id]"
                                                       value="{{$layout['id']}}">
                                                <div class="d-flex justify-content-between">
                                                    <h6>Button - {{$count - 10}}</h6>
                                                    <a href="{{ route("admin.remove.layout.item", ['id' => $layout['id']]) }}"><i
                                                            class="fa fa-times cursor-pointer design-remove"></i></a>
                                                </div>
                                                <input type="hidden" name="layouts[{{$count}}][type]"
                                                       value="title">
                                                <div class="row">
                                                    <div class="mb-4 col-md-6">
                                                        <label for="product_name"
                                                               class="form-label d-flex justify-content-between">
                                                            <div>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    বোতাম
                                                                @else
                                                                    Button
                                                                @endif
                                                                <span class="req">*</span>
                                                            </div>
                                                            {{--                                                                        @dd(['title' => $layout['type'], 'type' => $layout['type'], 'index' => $count])--}}
                                                            @include('admin.product.share.layout-custom-design', ['title' => $layout['type'], 'type' => $layout['type'], 'index' => $count])
                                                        </label>
                                                        <input type="text" placeholder="Type here"
                                                               class="form-control bg-white"
                                                               id="product_name"
                                                               name="layouts[{{$count}}][button]"
                                                               value="{{$layout['button']}}">
                                                    </div>
                                                    <input type="hidden" name="layouts[{{$count}}][type]"
                                                           value="button">
                                                    <div class="mb-2 col">
                                                        <label for="product_name"
                                                               class="form-label d-flex justify-content-between">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                লিঙ্ক
                                                            @else
                                                                Link
                                                            @endif
                                                        </label>
                                                        <input type="text" placeholder="Type here"
                                                               class="form-control bg-white"
                                                               id="product_name"
                                                               name="layouts[{{$count}}][link]"
                                                               value="{{$layout['link']}}">
                                                    </div>
                                                </div>
                                            </div>
                                            @break
                                        @case('image')
                                            <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                <input type="hidden" name="layouts[{{$count}}][id]"
                                                       value="{{$layout['id']}}">
                                                <div class="d-flex justify-content-between">
                                                    <h6>Image - {{$count - 10}}</h6>
                                                    <a href="{{ route("admin.remove.layout.item", ['id' => $layout['id']]) }}"><i
                                                            class="fa fa-times cursor-pointer design-remove"></i></a>
                                                </div>
                                                <input type="hidden" name="layouts[{{$count}}][type]"
                                                       value="image">
                                                <div class="row">
                                                    <div
                                                        class="mb-2 col-md-12 d-flex justify-content-center">
                                                        <img
                                                            src="/assets/images/product/{{$layout['link']}}"
                                                            alt="image" height="150px">
                                                    </div>
                                                    <div class="mb-2 col-md-6">
                                                        <label for="product_name" class="form-label">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                ছবি
                                                            @else
                                                                Image
                                                            @endif
                                                            <span class="req">*</span>
                                                        </label>
                                                        <input type="file" placeholder="Type here"
                                                               class="form-control bg-white"
                                                               id="product_name"
                                                               name="layouts[{{$count}}][link]"
                                                               value="{{$layout['link']}}">
                                                    </div>
                                                    <div class="mb-2 col">
                                                        <label for="product_name"
                                                               class="form-label d-flex justify-content-between">
                                                            <label>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    অবস্থান
                                                                @else
                                                                    Position
                                                                @endif
                                                                <span class="req">*</span>
                                                            </label>
                                                        </label>
                                                        <input type="text" placeholder="Type here"
                                                               class="form-control bg-white"
                                                               id="product_name"
                                                               value="{{$layout['position']}}"
                                                               name="layouts[{{$count}}][position]">
                                                        <input type="hidden"
                                                               name="layouts[{{$count}}][type]"
                                                               value="image">
                                                    </div>
                                                    <div class="mb-4 col-md-12">
                                                        <label class="form-label">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                বিস্তারিত
                                                            @else
                                                                Description
                                                            @endif
                                                        </label>
                                                        <textarea placeholder="Type here"
                                                                  class="form-control" id="editor{{$count}}"
                                                                  rows="8" name="layouts[{{$count}}][text]">
                                                                        {!! Request::old('details', $layout['text'] ?? '') !!}
                                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @break;
                                        @case('testimonial')
                                            <div class="bg-light design-item rounded pt-3 mb-3 px-3">
                                                <input type="hidden" name="layouts[{{$count}}][id]"
                                                       value="{{$layout['id']}}">
                                                <div class="d-flex justify-content-between">
                                                    <h6>Description - {{$count - 10}}</h6>
                                                    <a href="{{ route("admin.remove.layout.item", ['id' => $layout['id']]) }}"><i
                                                            class="fa fa-times cursor-pointer design-remove"></i></a>
                                                </div>
                                                <input type="hidden" name="layouts[{{$count}}][type]"
                                                       value="testimonial">
                                                <div class="row">
                                                    <div class="mb-2 col">
                                                        <label for="product_name"
                                                               class="form-label d-flex justify-content-between">
                                                            <div>
                                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                    অবস্থান
                                                                @else
                                                                    Position
                                                                @endif
                                                                <span class="req">*</span>
                                                            </div>
                                                        </label>
                                                        <input type="text" placeholder="Type here"
                                                               class="form-control bg-white"
                                                               id="testimonial"
                                                               value="{{$layout['position']}}"
                                                               name="layouts[{{$count}}][position]">
                                                        <input type="hidden"
                                                               name="layouts[{{$count}}][type]"
                                                               value="testimonial">
                                                    </div>
                                                    <div class="mb-4 col-md-12">
                                                        <label class="form-label">
                                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                                ্রশংসাপত্র
                                                            @else
                                                                Testimonial
                                                            @endif
                                                        </label>
                                                        <textarea placeholder="Type here"
                                                                  class="form-control" id="editor{{$count}}"
                                                                  rows="8"
                                                                  name="layouts[{{$count}}][text]">
                                                                        {!! Request::old('testimonial', $layout['text'] ?? '') !!}
                                                                    </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @break
                                        @default
                                            @break
                                    @endswitch
                                    <script>
                                        CKEDITOR.ClassicEditor.create(document.querySelector(`#editor{{$count}}`), {
                                            // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format

                                            ckfinder: {
                                                uploadUrl: '{{ route('superadmin.blog.ck') . '?_token=' . csrf_token() }}',
                                            },
                                            toolbar: {
                                                items: [
                                                    'exportPDF', 'exportWord', '|',
                                                    'findAndReplace', 'selectAll', '|',
                                                    'heading', '|',
                                                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript',
                                                    'removeFormat', '|',
                                                    'bulletedList', 'numberedList', 'todoList', '|',
                                                    'outdent', 'indent', '|',
                                                    'undo', 'redo',
                                                    '-',
                                                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                                    'alignment', '|',
                                                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed',
                                                    '|',
                                                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                                                    'textPartLanguage', '|',
                                                    'sourceEditing'
                                                ],
                                                shouldNotGroupWhenFull: true
                                            },
                                            // Changing the language of the interface requires loading the language file using the <script> tag.
                                            // language: 'es',
                                            list: {
                                                properties: {
                                                    styles: true,
                                                    startIndex: true,
                                                    reversed: true
                                                }
                                            },
                                            // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
                                            heading: {
                                                options: [{
                                                    model: 'paragraph',
                                                    title: 'Paragraph',
                                                    class: 'ck-heading_paragraph'
                                                },
                                                    {
                                                        model: 'heading1',
                                                        view: 'h1',
                                                        title: 'Heading 1',
                                                        class: 'ck-heading_heading1'
                                                    },
                                                    {
                                                        model: 'heading2',
                                                        view: 'h2',
                                                        title: 'Heading 2',
                                                        class: 'ck-heading_heading2'
                                                    },
                                                    {
                                                        model: 'heading3',
                                                        view: 'h3',
                                                        title: 'Heading 3',
                                                        class: 'ck-heading_heading3'
                                                    },
                                                    {
                                                        model: 'heading4',
                                                        view: 'h4',
                                                        title: 'Heading 4',
                                                        class: 'ck-heading_heading4'
                                                    },
                                                    {
                                                        model: 'heading5',
                                                        view: 'h5',
                                                        title: 'Heading 5',
                                                        class: 'ck-heading_heading5'
                                                    },
                                                    {
                                                        model: 'heading6',
                                                        view: 'h6',
                                                        title: 'Heading 6',
                                                        class: 'ck-heading_heading6'
                                                    }
                                                ]
                                            },
                                            // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
                                            placeholder: 'Enter your page details',
                                            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
                                            fontFamily: {
                                                options: [
                                                    'default',
                                                    'Arial, Helvetica, sans-serif',
                                                    'Courier New, Courier, monospace',
                                                    'Georgia, serif',
                                                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                                                    'Tahoma, Geneva, sans-serif',
                                                    'Times New Roman, Times, serif',
                                                    'Trebuchet MS, Helvetica, sans-serif',
                                                    'Verdana, Geneva, sans-serif'
                                                ],
                                                supportAllValues: true
                                            },
                                            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
                                            fontSize: {
                                                options: [10, 12, 14, 'default', 18, 20, 22],
                                                supportAllValues: true
                                            },
                                            // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
                                            // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
                                            htmlSupport: {
                                                allow: [{
                                                    name: /.*/,
                                                    attributes: true,
                                                    classes: true,
                                                    styles: true
                                                }]
                                            },
                                            // Be careful with enabling previews
                                            // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
                                            htmlEmbed: {
                                                showPreviews: true
                                            },
                                            // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
                                            link: {
                                                decorators: {
                                                    addTargetToExternalLinks: true,
                                                    defaultProtocol: 'https://',
                                                    toggleDownloadable: {
                                                        mode: 'manual',
                                                        label: 'Downloadable',
                                                        attributes: {
                                                            download: 'file'
                                                        }
                                                    }
                                                }
                                            },
                                            // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
                                            mention: {
                                                feeds: [{
                                                    marker: '@',
                                                    feed: [
                                                        '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes',
                                                        '@chocolate', '@cookie', '@cotton', '@cream',
                                                        '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread',
                                                        '@gummi', '@ice', '@jelly-o',
                                                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding',
                                                        '@sesame', '@snaps', '@soufflé',
                                                        '@sugar', '@sweet', '@topping', '@wafer'
                                                    ],
                                                    minimumCharacters: 1
                                                }]
                                            },
                                            // The "super-build" contains more premium features that require additional configuration, disable them below.
                                            // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
                                            removePlugins: [
                                                // These two are commercial, but you can try them out without registering to a trial.
                                                // 'ExportPdf',
                                                // 'ExportWord',
                                                'CKBox',
                                                'CKFinder',
                                                'EasyImage',
                                                // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                                                // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                                                // Storing images as Base64 is usually a very bad idea.
                                                // Replace it on production website with other solutions:
                                                // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                                                // 'Base64UploadAdapter',
                                                'RealTimeCollaborativeComments',
                                                'RealTimeCollaborativeTrackChanges',
                                                'RealTimeCollaborativeRevisionHistory',
                                                'PresenceList',
                                                'Comments',
                                                'TrackChanges',
                                                'TrackChangesData',
                                                'RevisionHistory',
                                                'Pagination',
                                                'WProofreader',
                                                // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                                                // from a local file system (file://) - load this site via HTTP server if you enable MathType
                                                'MathType'
                                            ]
                                        });
                                    </script>
                                    @php
                                        $count+= 1;
                                    @endphp
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="card mb-4">
                    <div class="card-header  d-flex justify-content-between">
                        <h4>
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                মৌলিক
                            @else
                                Additional Details
                            @endif
                        </h4>
                        <div class="w-50">
                            <div class="input-group">
                                <select class="form-select h-100" id="type-define">
                                    <option selected>Choose..</option>
                                    <option value="title">Title</option>
                                    <option value="subtitle">Sub-Title</option>
                                    <option value="description">Description</option>
                                    <option value="button">Button</option>
                                    <option value="image">Image</option>
                                    <option value="testimonial">Testimonial</option>
                                </select>
                                <button class="btn btn-primary" id="design-add" type="button">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="design-list">
                            @php
                                $count = 11;
                            @endphp
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
    <div class="col-lg-12">
        <div class="card p-3" style="min-height: 100%">
            <div class="d-flex align-items-center" id="submitBtnSection">
                <input type="hidden" id="updateInput" name="update" value="update">
                @isset($product)
                    <button class="btn btn-success rounded font-sm hover-up" id="updateBtn" type="submit"
                            onclick="setSubmitValue(event, 'update')">Update
                    </button>
                @endisset
                <button class="btn btn-info rounded font-sm hover-up mx-2" id="publishBtn" type="submit"
                        onclick="setSubmitValue(event, 'publish')">Publish
                </button>

                {{-- store_id, modulus_id --}}
                @if (isset($product) && ModulusStatus($store_id, 1))
                    <a href="{{ route('admin.product.duplicate', ["id" => $product['id']]) }}"
                       class="btn btn-info rounded font-sm hover-up" style="background:rebeccapurple;"
                       id="duplicateBtn">
                        Duplicate
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@push('scripts')
    <script>
        const discount_type = document.getElementById('discount_type');
        discount_type.addEventListener('change', function (event) {

            let discount_price = document.getElementById("discount_price");
            let promotional_price = document.getElementById("promotional_price");

            if (discount_type.value == "no_discount") {
                discount_price.style.cssText = 'display: none !important';
                promotional_price.value = "0";
            } else {
                discount_price.style.cssText = 'display: block !important';
            }

        });

        // Change event for tax type
        const tax_type = document.getElementById('tax_type');
        tax_type.addEventListener('change', function (event) {
            let tax_rate = document.getElementById("tax_rate");
            let tax_price = document.getElementById("tax_price");

            if (tax_type.value == "no_tax") {
                tax_rate.style.cssText = 'display: none !important';
                tax_price.value = "0";
            } else {
                tax_rate.style.cssText = 'display: block !important';
            }

        });

        // Change event for product unit
        const productUnit = document.getElementById('productUnit');
        productUnit.addEventListener('change', function (event) {
            if (productUnit.value == "qty") {
                $("#productQtyDiv").show().css("display", "block");
                $("#productVolumeDiv").hide().css("display", "none");

                $("#qtyOrVolume").val("0");
            } else {
                $("#qtyOrVolume").val("1");

                $("#productQtyDiv").hide().css("display", "none");
                $("#productVolumeDiv").show().css("display", "block");
            }
        });


        const setSubmitValue = (e, buttonValue) => {
            e.preventDefault();  // Prevent default form submission

            // Set the value of the hidden input field with the clicked button's value
            document.getElementById('updateInput').value = buttonValue;

            // Now submit the form
            e.target.form.submit();  // Submit the form programmatically
        };

    </script>

    <script>

        let scrollPosition = 0;

        document.addEventListener('show.bs.modal', function (event) {
            const triggerBtn = event.relatedTarget; // The button that triggered the modal
            const mainElement = document.querySelector('main#main');

            if (triggerBtn && mainElement) {
                // Get scroll position of the button inside the scrollable container
                const rect = triggerBtn.getBoundingClientRect();
                const containerRect = mainElement.getBoundingClientRect();

                scrollPosition = rect.top - containerRect.top + mainElement.scrollTop;

                if (mainElement) {
                    setTimeout(() => {
                        mainElement.scrollTo({
                            top: scrollPosition,
                            behavior: 'auto'
                        });
                    }, 10);
                }
            }
        });

        // Optional: Scroll back to the same position when modal is closed
        document.addEventListener('hidden.bs.modal', function () {
            const mainElement = document.querySelector('main#main');
            if (mainElement) {
                setTimeout(() => {
                    mainElement.scrollTo({
                        top: scrollPosition,
                        behavior: 'auto'
                    });
                }, 10);
            }
        });


    </script>

@endpush

