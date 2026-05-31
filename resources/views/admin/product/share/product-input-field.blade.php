<style>
    .product-wrapper {
        width: 100%;
        max-width: none;
        margin: 0;
        padding-left: 0;
        padding-right: 0;
    }

    .content-main {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    @media (min-width: 1200px) {
        .content-main {
            padding-left: 6px !important;
            padding-right: 12px !important;
        }
    }

    .product-form-row {
        --bs-gutter-x: 18px;
        --bs-gutter-y: 18px;
    }

    .product-form-row>[class*="col-"] {
        padding-left: 9px;
        padding-right: 9px;
    }

    .product-left-card,
    .product-right-card,
    .product-variant-card,
    .submit-action-card {
        border-radius: 12px;
    }

    .image-card,
    .tutorial-card {
        min-height: 122px;
    }

    .image-box {
        font-size: x-small;
        border: 1px dashed #888;
        height: 95px;
        width: 95px;
        cursor: pointer;
        flex: 0 0 auto;
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

    #previewContainer {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: flex-start;
        max-width: 100%;
    }

    .imgWrapperDiv,
    .oldImg-wrap {
        position: relative;
        flex: 0 0 auto;
    }

    .imgWrapperDiv img,
    .oldImg-wrap img {
        width: 95px;
        height: 95px;
        object-fit: cover;
    }

    .tutorial-card {
        background-color: #F1593A;
    }

    .tutorial-section {
        color: #ffffff;
        min-height: 92px;
        border-radius: 10px;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .center-cell {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .groupItemDiv {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .groupItemDiv span {
        width: 100% !important;
    }

    .input-upload .text-danger {
        position: absolute;
        bottom: -18px;
        font-size: 12px;
        left: 4px;
        color: red !important;
    }

    button.browse-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px !important;
        height: 40px;
        min-width: 44px;
    }

    .custom-scroll {
        padding: 10px;
        box-sizing: border-box;
        overflow-y: auto;
    }

    @media (min-width: 1200px) {
        .custom-scroll {
            max-height: 420px;
        }
    }

    @media (max-width: 1199.98px) {
        .custom-scroll {
            max-height: none;
            overflow-y: visible;
        }
    }

    .custom-scroll::-webkit-scrollbar {
        width: 8px;
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

    .side-stack-card {
        margin-top: 0;
    }

    .right-stack-gap {
        margin-top: 16px;
    }

    .product-variant-card {
        margin-top: 24px;
        margin-bottom: 24px;
    }

    .submit-action-card {
        min-height: auto !important;
        height: auto !important;
    }

    #submitBtnSection {
        min-height: auto !important;
        height: auto !important;
        justify-content: flex-start;
        gap: 10px;
    }

    #submitBtnSection .btn,
    #submitBtnSection a {
        margin-bottom: 0;
    }

    .left-column-gap {
        padding-right: 10px;
    }

    .right-column-gap {
        padding-left: 10px;
    }

    @media (max-width: 991.98px) {
        .groupItemDiv {
            flex-wrap: wrap;
        }

        .groupItemDiv a {
            margin-top: 6px;
        }

        .tutorial-section {
            min-height: 84px;
        }

        .left-column-gap,
        .right-column-gap {
            padding-left: 0;
            padding-right: 0;
        }
    }

    @media (max-width: 767.98px) {
        #submitBtnSection {
            flex-direction: column;
            align-items: stretch !important;
        }

        #submitBtnSection .btn,
        #submitBtnSection a {
            width: 100%;
            text-align: center;
        }

        .image-box {
            width: 90px;
            height: 90px;
        }

        #previewContainer {
            gap: 8px;
        }
    }
</style>

<section class="container-fluid content-main mt-3">
    <div class="product-wrapper">
        <div class="row product-form-row">

            {{-- LEFT SECTION --}}
            <div class="col-12 col-xl-7 left-column-gap">
                <div class="row product-form-row">

                    {{-- TUTORIAL for mobile--}}
                    <div class="col-12 d-block d-xl-none">
                        <div class="card product-right-card tutorial-card p-3">
                            <div class="card-body center-cell tutorial-section">
                                <div><i class="fa fa-play mr-2" aria-hidden="true"></i> Tutorial</div>
                            </div>
                        </div>
                    </div>

                    {{-- IMAGE SECTION --}}
                    <div class="col-12">
                        <div class="card product-left-card image-card p-3">
                            <div class="input-upload d-flex flex-column">
                                <div id="previewContainer" class="mt-1">
                                    @if (isset($product) && isset($product['images']) && !empty($product['images']))
                                        @php
                                            $images = explode(',', $product['images']);
                                        @endphp

                                        @foreach ($images as $key => $image)
                                            <div class="oldImg-wrap imgWrapperDiv">
                                                <a href="javascript:void(0)" class="oldClose imageUploadRemoveBtn"
                                                    data-remove-url="{{ URL::to('/') }}/product/removeimage/{{ $product['id'] }}/{{ $image }}">x</a>

                                                <img src="{{ getPath($image, 'assets/images/product') }}"
                                                    style="padding:10px;border:1px solid black;margin-bottom:5px;">
                                            </div>
                                        @endforeach
                                        <input type="hidden" class="form-control" name="oldImage"
                                            value="{{ $product['images'] }}">
                                    @endif

                                    @if (isset($product) && isset($product['gallery_image']) && !empty($product['gallery_image']))
                                        @php
                                            $images = explode(',', $product['gallery_image']);
                                        @endphp

                                        @foreach ($images as $key => $image)
                                            <div class="imgWrapperDiv">
                                                <img src="{{ URL::to('/') }}/{{ $image }}"
                                                    style="border: 1px solid rgb(204, 204, 204); padding: 3px;">
                                                <a href="javascript:void(0)" class="imageUploadRemoveBtn"
                                                    data-remove-url="{{ URL::to('/') }}/product/removegalleryimage/{{ $product['id'] }}/{{ $image }}">×</a>
                                            </div>
                                        @endforeach
                                        <input type="hidden" class="form-control" name="oldGalleryImage"
                                            value="{{ $product['gallery_image'] }}">
                                    @endif

                                    <div id="imgAddBtn"
                                        onclick="standalonFileManagerModal('imageUrlsInput', false ,'previewContainer', 'imgAddBtn');"
                                        class="image-box">
                                        <div id="standAlonLMF"
                                            style="height: 100%; width: 100%; display: flex; justify-content: center; align-items: center;">
                                            <input type="hidden" name="gallery_image" id="imageUrlsInput"
                                                value="{{ old('gallery_image', isset($product) ? $product['gallery_image'] : '') }}">

                                            @error('gallery_image')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror

                                            <div class="content text-center" id="lfm">
                                                <p></p>
                                                <h1>+</h1>
                                                <p>Upload Image</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MAIN PRODUCT FORM --}}
                    <div class="col-12">
                        <div class="card product-left-card p-4">
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger" style="color:#fff">
                                    {{ Session::get('error_message') }}
                                </div>
                            @endif

                            <div class="mb-4">
                                <label for="product_name" class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পণ্য শিরোনাম
                                    @else
                                        Product name
                                    @endif
                                    <span class="req">*</span>
                                </label>
                                <input type="text" placeholder="Type here" class="form-control" id="product_name"
                                    name="product_name"
                                    value="{{ old('product_name', isset($product) ? $product['name'] : '') }}">
                                @error('product_name')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পূর্ণ বিবরণ
                                    @else
                                        Full description
                                    @endif
                                    <span class="req">*</span>
                                </label>
                                <textarea hidden placeholder="Type here" class="form-control editor" id="editor"
                                    rows="40"
                                    name="description">{!! old('description', isset($product) ? $product['description'] : '') !!}</textarea>
                                @error('description')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            @php
                                $userData = getUserData();
                                $store_id = $userData['store_id'];
                            @endphp

                            @if (ModulusStatus($store_id, 115))
                                <div class="mb-4">
                                    <label class="form-label">
                                        Video Link
                                    </label>
                                    <input placeholder="YouTube Embed Video Link" type="text" class="form-control"
                                        name="video_link"
                                        value="{{ old('video_link', isset($product) ? $product['video_link'] : '') }}">
                                    @error('video_link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            @if (ModulusStatus($store_id, 118))
                                <div class="mb-4">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control" id="expiry_date"
                                        value="{{ old('expiry_date', isset($product) ? $product['expiry_date'] : '') }}"
                                        name="expiry_date">
                                    @error('expiry_date')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            @php
                                if (Illuminate\Support\Facades\Auth::check()) {
                                    $user = Illuminate\Support\Facades\Auth::user();
                                }

                                if ($user->type == 'staff') {
                                    $staff_assigned_store = DB::table('stores')->where('id', '=', $user->store_id)->first();
                                    $admin_id = $staff_assigned_store->user_id;
                                }

                                if ($user->type !== 'staff') {
                                    $admin_id = $user->id;
                                }

                                $customer = DB::table('customers')->where('uid', '=', $admin_id)->first();

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
                            @endphp

                            @if ($digitalproductstatus)
                                <div class="mb-4">
                                    <label for="product_link" class="form-label">
                                        Product link
                                    </label>
                                    <input type="text" placeholder="Type here" class="form-control" id="product_link"
                                        name="product_link"
                                        value="{{ old('product_link', isset($product) ? $product['product_link'] : '') }}">
                                    @error('product_link')
                                        <p class="text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            @php
                                $isQty = isset($product['unit']) && $product['unit'] == 'qty' ? true : false;
                                $qtyOrVolume = 0;
                                $unitVariant = false;

                                if ((isset($select_unitsss) && count($select_unitsss) > 0) || old('att') == 'unit') {
                                    $unitVariant = true;
                                    $qtyOrVolume = isset($product['unit']) && $product['unit'] == 'qty' ? 0 : 1;
                                }

                                $discountType = old('discount_type', isset($product) ? $product['discount_type'] : 'no_discount');
                                $taxType = old('tax_type', isset($product) ? $product['tax_type'] : 'no_tax');
                            @endphp

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">SKU</label>
                                        <input placeholder="SKU" type="text" class="form-control" name="SKU"
                                            value="{{ old('SKU', isset($product) ? $product['SKU'] : '') }}">
                                        @error('SKU')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Regular price ({{ $current_currency->symbol }}) <span
                                                class="req">*</span></label>
                                        <input placeholder="Regular price ({{ $current_currency->symbol }})"
                                            type="number" step="0.01" min="0" class="form-control" name="regular_price"
                                            value="{{ old('regular_price', isset($product) ? $product['regular_price'] : '') }}">
                                        @error('regular_price')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Product Cost ({{ $current_currency->code }})</label>
                                        <input type="number" min="0" step="0.01" class="form-control" name="cost"
                                            value="{{ old('cost', isset($product) ? $product['cost'] : '') }}">
                                        @error('cost')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <input type="hidden" id="qtyOrVolume" name="qtyOrVolume"
                                    value="{{ old('qtyOrVolume') ?? $qtyOrVolume }}">

                                <div class="col-12 col-md-4" id="productUnitDiv"
                                    style="{{ $unitVariant || old('qtyOrVolume') == 1 ? 'display:block' : 'display:none' }}">
                                    <div class="mb-4">
                                        <label class="form-label">Unit <span class="req">*</span></label>
                                        <select name="productUnit" id="productUnit" class="form-control">
                                            <option value="">Select Unit</option>
                                            @php
                                                $units = DB::table('units')->where('store_id', $store_id)->get();
                                            @endphp
                                            @foreach ($units as $cl)
                                                <option value="{{ $cl->name }}" {{ old('productUnit', isset($product) ? $product['unit'] : '') == $cl->name ? 'selected' : '' }}>
                                                    {{ $cl->name }}
                                                </option>
                                            @endforeach
                                            <option value="qty" {{ old('productUnit', isset($product) ? $product['unit'] : '') == 'qty' ? 'selected' : '' }}>
                                                Quantity
                                            </option>
                                        </select>
                                        @error('productUnit')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-4" id="productQtyDiv"
                                    style="{{ ($unitVariant && !$isQty) || old('qtyOrVolume') == 1 ? 'display:none' : 'display:block' }}">
                                    <div class="mb-4">
                                        <label class="form-label">Quantity <span class="req">*</span></label>
                                        <input type="number" min="0" class="form-control" name="quantity"
                                            value="{{ old('quantity', isset($product) ? $product['quantity'] : '') }}"
                                            id="productQty">
                                        @error('quantity')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-4" id="productVolumeDiv"
                                    style="{{ ($unitVariant && !$isQty) || old('qtyOrVolume') == 1 ? 'display:block' : 'display:none' }}">
                                    <div class="mb-4">
                                        <label class="form-label">Total Volume <span class="req">*</span></label>
                                        <input type="number" min="0" class="form-control" name="volume"
                                            value="{{ old('volume', $product['volume'] ?? '') }}" id="productVolume">
                                        @error('volume')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Discount Type <span class="req">*</span></label>
                                        <select class="form-select" name="discount_type" id="discount_type">
                                            <option value="no_discount" {{ $discountType == 'no_discount' ? 'selected' : '' }}>No Discount</option>
                                            <option value="fixed" {{ $discountType == 'fixed' ? 'selected' : '' }}>Fixed
                                            </option>
                                            <option value="percent" {{ $discountType == 'percent' ? 'selected' : '' }}>
                                                Percent</option>
                                        </select>
                                        @error('discount_type')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-4" id="discount_price"
                                    style="{{ $discountType != 'no_discount' ? 'display:block' : 'display:none' }}">
                                    <div class="mb-4">
                                        <label class="form-label">Discount price ({{ $current_currency->code }})</label>
                                        <input placeholder="Discount price ({{ $current_currency->code }})"
                                            type="number" min="0" step="0.01" class="form-control"
                                            name="promotional_price" id="promotional_price"
                                            value="{{ old('promotional_price', isset($product) ? $product['promotional_price'] : '') }}">
                                        @error('promotional_price')
                                            <p class="text-danger" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Tax Type</label>
                                        <select class="form-select" name="tax_type" id="tax_type">
                                            <option value="no_tax" {{ $taxType == 'no_tax' ? 'selected' : '' }}>No Tax
                                            </option>
                                            <option value="fixed" {{ $taxType == 'fixed' ? 'selected' : '' }}>Fixed
                                            </option>
                                            <option value="percent" {{ $taxType == 'percent' ? 'selected' : '' }}>Percent
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6" id="tax_rate"
                                    style="{{ $taxType != 'no_tax' ? 'display:block' : 'display:none' }}">
                                    <div class="mb-4">
                                        <label class="form-label">Tax rate ({{ $current_currency->symbol }})</label>
                                        <input type="number" min="0" step="0.01" class="form-control" id="tax_price"
                                            value="{{ old('tax_rate', isset($product) ? $product['tax_rate'] : '') }}"
                                            name="tax_rate">
                                    </div>
                                </div>

                                @if (isAddonActive(13))
                                    <div class="col-12">
                                        <div class="mb-0">
                                            <label class="form-label">Bar Code</label>
                                            <input type="number" min="0" class="form-control" name="barcode"
                                                value="{{ old('barcode', isset($product) ? $product['barcode'] : '') }}">
                                            @error('barcode')
                                                <p class="text-danger" role="alert">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SECTION --}}
            <div class="col-12 col-xl-5 right-column-gap">
                <div class="row product-form-row">

                    {{-- TUTORIAL for desktop --}}
                    <div class="col-12 d-none d-xl-block">
                        <div class="card product-right-card tutorial-card p-3">
                            <div class="card-body center-cell tutorial-section">
                                <div><i class="fa fa-play mr-2" aria-hidden="true"></i> Tutorial</div>
                            </div>
                        </div>
                    </div>

                    {{-- CATEGORY --}}
                    <div class="col-12">
                        <div class="card product-right-card p-4 right-stack-gap">
                            @php
                                $category = DB::table('categories')
                                    ->where('parent', 0)
                                    ->where('store_id', $store_id)
                                    ->where('status', 'active')
                                    ->get();

                                $selectedCategories = old('category', isset($product) ? explode(',', $product['category'] ?? '') : []);
                                $selectedSubcategories = old('subcategory', isset($product) ? explode(',', $product['subcategory'] ?? '') : []);
                                $brands = DB::table('brands')->where('store_id', $store_id)->get();
                                $suppliers = DB::table('suppliers')->where('store_id', $store_id)->get();

                                $subcategory = collect();
                                if (isset($product) && isset($product['subcategory'])) {
                                    $subcategory = DB::table('categories')
                                        ->whereIn('id', explode(',', $product['subcategory'] ?? ''))
                                        ->where('status', 'active')
                                        ->get();
                                }
                            @endphp

                            <div class="mb-4">
                                <label class="form-label">Category <span class="req">*</span></label>
                                <div class="groupItemDiv">
                                    <select class="form-select" name="category[]" id="category" multiple>
                                        @foreach ($category as $cat)
                                            <option value="{{ $cat->id }}" {{ in_array($cat->id, $selectedCategories) ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <a href="{{ URL::to('category') }}" target="_blank" title="Add Category">
                                        <img src="{{ URL::to('/') }}/img/add.png" alt="" width="30px">
                                    </a>
                                </div>
                                @error('category')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Sub-category</label>
                                <div class="groupItemDiv">
                                    <select class="form-select" name="subcategory[]" id="subcategory" multiple>
                                        @foreach ($subcategory as $subcat)
                                            <option value="{{ $subcat->id }}" {{ in_array($subcat->id, $selectedSubcategories) ? 'selected' : '' }}>
                                                {{ $subcat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <a href="{{ URL::to('subcategory') }}" target="_blank" title="Add Sub-Category">
                                        <img src="{{ URL::to('/') }}/img/add.png" alt="" width="30px">
                                    </a>
                                </div>
                                @error('subcategory')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Brand</label>
                                <div class="groupItemDiv">
                                    <select class="form-select" name="brand" id="brand">
                                        <option value="null">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand', $product['brand'] ?? '') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <a href="{{ URL::to('brand') }}" target="_blank" title="Add Brand">
                                        <img src="{{ URL::to('/') }}/img/add.png" alt="" width="30px">
                                    </a>
                                </div>
                                @error('brand')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Supplier</label>
                                <div class="groupItemDiv">
                                    <select class="form-select" name="supplier" id="supplier">
                                        <option value="null">Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier', $product['supplier'] ?? '') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <a href="{{ URL::to('supplier') }}" target="_blank" title="Add Supplier">
                                        <img src="{{ URL::to('/') }}/img/add.png" alt="" width="30px">
                                    </a>
                                </div>
                                @error('supplier')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- BEST SELL --}}
                    <div class="col-12">
                        <div class="card product-right-card p-4 right-stack-gap">
                            <div class="mb-2">
                                <label for="best_sell" class="form-label">
                                    <input type="checkbox" id="best_sell" name="best_sell" value="1" {{ old('best_sell', isset($product) ? $product['best_sell'] : 0) ? 'checked' : '' }}>
                                    &nbsp;&nbsp;Best Sell
                                </label>
                                @error('best_sell')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="feature" class="form-label">
                                    <input type="checkbox" id="feature" name="feature" value="1" {{ old('feature', isset($product) ? $product['feature'] : 0) ? 'checked' : '' }}>
                                    &nbsp;&nbsp;Feature
                                </label>
                                @error('feature')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label for="pse" class="form-label">
                                    <input type="checkbox" id="pse" name="pse" value="1" {{ old('pse', isset($product) ? $product['pse'] : 0) ? 'checked' : '' }}>
                                    &nbsp;&nbsp;Request For Product খুঁজো List
                                </label>
                                @error('pse')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- TAGS --}}
                    <div class="col-12">
                        <div class="card product-right-card p-4 right-stack-gap">
                            <div class="mb-4">
                                <label class="form-label">Tags</label>
                                <input type="text" class="form-control" data-role="tagsinput" name="tags"
                                    value="{{ old('tags', isset($product) ? $product['tags'] : '') }}"
                                    placeholder="Enter a comma after each tag">
                                <div class="error" style="font-size: 11px; color: red;">Enter a comma after each tag
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">SEO Keywords</label>
                                <input type="text" class="form-control" data-role="tagsinput" name="seo"
                                    value="{{ old('seo', isset($product) ? $product['seo_keywords'] : '') }}">
                                <div class="error" style="font-size: 11px; color: red;">Enter a comma after each tag
                                </div>
                                @error('seo')
                                    <p class="text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- VARIANT FULL WIDTH --}}
            @if (ModulusStatus($store_id, 114))
                        <div class="col-12">
                            <div class="card p-4 product-variant-card">
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
                                                <option value="color" @if ((isset($attri_color) && count($attri_color) > 0) || old('att') == 'color') selected @endif>Color & size</option>
                                                <option value="onlycolor" @if ((isset($select_onlycolor) && count($select_onlycolor) > 0) || old('att') == 'onlycolor') selected @endif>Color</option>
                                                <option value="unit" @if ((isset($select_unitsss) && count($select_unitsss) > 0) || old('att') == 'unit') selected @endif>Unit</option>
                                                <option value="size" @if ((isset($select_sizess) && count($select_sizess) > 0) || old('att') == 'size') selected @endif>Size</option>
                                            </select>
                                            <a href="{{ URL::to('attribute') }}" id="addVariantBtn" target="_blank"
                                                title="Add Variantion">
                                                <img src="{{ URL::to('/') }}/img/add.png" alt="" width="30px">
                                            </a>
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
                                                                        <select name="cs_color[]" id="color" class="form-control"
                                                                            step="any">
                                                                            @php
                                                                                $colors = DB::table('colors')->where('store_id', $store_id)->get();
                                                                            @endphp
                                                                            @if($colors)
                                                                                @foreach($colors as $cl)
                                                                                    <option value="{{ $cl->code }}" @if($colorsss->color == $cl->code)
                                                                                    selected @endif>
                                                                                        {{ $cl->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>

                                                                        @if(isset($colorsss->color_image))
                                                                            <div class="oldImg-wrap"
                                                                                style="display: flex;justify-content: center;">
                                                                                <a class="oldClose"
                                                                                    href="{{ route('admin.variantColorImageDelete', ['id' => $colorsss->id]) }}">x</a>
                                                                                <img src="{{ getPath($colorsss->color_image, 'assets/images/product') }}"
                                                                                    style="border:1px solid black;width: 50px; height: 50px;margin-left: 2px"
                                                                                    width="60px" height="60px">
                                                                            </div>
                                                                            <input type="hidden" name="cs_colorImageOld[{{ $keyss }}][]"
                                                                                value="{{ $colorsss->color_image }}">
                                                                        @else
                                                                            <div class="image-input-wrapper">
                                                                                <div class="input-group">
                                                                                    <!-- FIXED: type="file" -->
                                                                                    <input type="file" style="display: none"
                                                                                        id="imageInput{{ $colorsss->id }}"
                                                                                        class="form-control mt-2" onchange="variantImage(event)"
                                                                                        accept="image/*"
                                                                                        name="cs_color_updateImage[{{ $colorsss->color }}][]" />
                                                                                    <button type="button"
                                                                                        class="btn btn-outline-secondary browse-btn"
                                                                                        onclick="tiggerFileSelect(this)"
                                                                                        style="margin-left: 5px;">
                                                                                        <i class="fa fa-picture-o"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </td>

                                                                <td class="mt-1" style="text-align:center">
                                                                    <select name="cs_size[{{ $keyss }}][]" id="sizs" class="form-control"
                                                                        step="any">
                                                                        @php
                                                                            $size = DB::table('sizes')->where('store_id', $store_id)->get();
                                                                        @endphp
                                                                        @if($size)
                                                                            @foreach($size as $sz)
                                                                                <option value="{{ $sz->name }}" @if($colorsss->size == $sz->name)
                                                                                selected @endif>
                                                                                    {{ $sz->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </td>

                                                                <td class="mt-1" style="text-align:center">
                                                                    <input type="number" min="0.00" name="cs_qty[{{ $keyss }}][]"
                                                                        class="form-control colorSizeQty"
                                                                        onchange="variantQtyCheck(this, 'color')"
                                                                        value="{{ $colorsss->quantity }}">
                                                                </td>

                                                                <td class="mt-1" style="text-align:center">
                                                                    <input type="number" min="0.00" name="cs_price[{{ $keyss }}][]"
                                                                        class="form-control" value="{{ $colorsss->additional_price ?? 0 }}">
                                                                </td>

                                                                <td style="display: flex; justify-content: center;">
                                                                    @if($colorsss->image)
                                                                        <div class="oldImg-wrap" style="display: flex;justify-content: center;">
                                                                            <a class="oldClose"
                                                                                href="{{ route('admin.variantImageDelete', ['id' => $colorsss->id]) }}">x</a>
                                                                            <img src="{{ getPath($colorsss->image, 'assets/images/product') }}"
                                                                                style="border:1px solid black;" width="60px" height="60px">
                                                                        </div>
                                                                        <input type="hidden" name="cs_ImageOld[{{ $keyss }}][]"
                                                                            value="{{ $colorsss->image }}">
                                                                    @else
                                                                        <div class="image-input-wrapper">
                                                                            <div class="input-group">
                                                                                <!-- FIXED: type="file" -->
                                                                                <input type="file" style="display: none"
                                                                                    id="imageInput{{$keyss}}" class="form-control"
                                                                                    onchange="variantImage(event)" accept="image/*"
                                                                                    name="cs_Image[{{ $keyss }}][]" />
                                                                                <button type="button"
                                                                                    class="btn btn-outline-secondary browse-btn"
                                                                                    onclick="tiggerFileSelect(this)">
                                                                                    <i class="fa fa-picture-o"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </td>

                                                                <td class="mt-1" style="text-align:center">
                                                                    <input type="hidden" name="attriid" value="{{ $colorsss->id }}">
                                                                    <input type="hidden" name="cs_attrId[{{ $keyss }}][]"
                                                                        value="{{ $colorsss->id }}">
                                                                    <a href="javascript:void(0)" class="deleteattri"
                                                                        data-variant-id="{{ $colorsss->id }}"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                                        <img src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px">
                                                                    </a>
                                                                </td>
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
                                                        $i = isset($keyss) ? ((int) $keyss + 1) : 0;
                                                    @endphp
                                                    <input type="hidden" id="colorSizeRowIndex" value="{{ $i }}">

                                                    <tr id="new" style="margin-top:5px;">
                                                        <td>
                                                            <label>Color:</label>
                                                            <select name="cs_color[]" id="color" class="form-control" step="any">
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
                                                                            {{ $cl->name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <div class="input-group">
                                                                <input type="hidden" class="form-control mt-2"
                                                                    onchange="variantImage(event)" accept="image/*"
                                                                    name="cs_color_image[{{ $i }}]" />
                                                                <button type="button" class="btn btn-outline-secondary browse-btn"
                                                                    style="margin-top: 7px;" onclick="tiggerFileSelect(this)">
                                                                    <i class="fa fa-picture-o"></i> Browse
                                                                </button>
                                                            </div>
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
                                                                                        @php
                                                                                            $checkBoxIndex = ($i * 1000) + $key;
                                                                                        @endphp
                                                                                        <input type="checkbox"
                                                                                            onclick="checkBox({{ $checkBoxIndex }})"
                                                                                            id="checkBoxStatus{{ $checkBoxIndex }}"
                                                                                            name="sid[{{ $i }}][]" value="yes">
                                                                                        <input type="text" class="form-control"
                                                                                            name="cs_size[{{ $i }}][]"
                                                                                            value="{{ $sz->name }}" readonly>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>Quantity</label>
                                                                            <input type="number" min="0.00"
                                                                                class="form-control colorSizeQty" name="cs_qty[{{ $i }}][]"
                                                                                id="checkBoxWrite{{ $checkBoxIndex }}"
                                                                                onchange="variantQtyCheck(this, 'color')" readonly
                                                                                placeholder="Enter Quantity" value="">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>(+/-)Price</label>
                                                                            <input type="number" min="0.00" class="form-control"
                                                                                name="cs_price[{{ $i }}][]" placeholder="Price" value="0">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>Media</label>
                                                                            <div class="input-group">
                                                                                <input type="hidden" class="form-control"
                                                                                    onchange="variantImage(event)" accept="image/*"
                                                                                    name="cs_Image[{{ $i }}][]" />
                                                                                <button type="button"
                                                                                    class="btn btn-outline-secondary browse-btn"
                                                                                    onclick="tiggerFileSelect(this)">
                                                                                    <i class="fa fa-picture-o"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a class="remove-officer-button mt-3" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Delete"><img
                                                                    src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px"
                                                                    style="margin-bottom:5px;"></a>
                                                            <br>
                                                            <a onclick="addRow({{ $i }})" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Add"><img
                                                                    src="{{ URL::to('/') }}/img/add.png" alt="" width="30px"></a>
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
                                                                                        <select name="c_color[]" id="color" class="form-control" step="any">
                                                                                            <?php
                                                        $colors = DB::table('colors')
                                                            ->where('store_id', $store_id)
                                                            ->get();
                                                                                                                                                ?>
                                                                                            @if (isset($colors))
                                                                                                @foreach ($colors as $cl)
                                                                                                    <option value="{{ $cl->code }}" @if ($cl->code == $colorssss->color)
                                                                                                    selected @endif>
                                                                                                        {{ $cl->name }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </select>
                                                                                    </td>
                                                                                    <td style="text-align:center">
                                                                                        <input type="number" name="c_qty[]" class="form-control onlyColorQty"
                                                                                            onchange="variantQtyCheck(this, 'onlycolor')" id="" min="0"
                                                                                            value="{{ $colorssss->quantity }}">
                                                                                    </td>
                                                                                    <input type="hidden" name="attriid" id="attriid" value="{{ $colorssss->id }}">
                                                                                    <input type="hidden" name="c_attrId[]" value="{{ $colorssss->id }}">
                                                                                    <td style="text-align:center">
                                                                                        <input type="number" name="c_price[]" id="" class="form-control"
                                                                                            value="{{ $colorssss->additional_price }}" min="0">
                                                                                    </td>
                                                                                    <td style="display: flex; justify-content: center;">
                                                                                        @if(isset($colorssss->image))
                                                                                            <div class="oldImg-wrap" style="display: flex;justify-content: center;">
                                                                                                <a class="oldClose"
                                                                                                    href="{{ route('admin.variantImageDelete', ['id' => $colorssss->id]) }}">x</a>
                                                                                                <img src="{{ getPath($colorssss->image, 'assets/images/product') }}"
                                                                                                    style="border:1px solid black;" width="60px" height="60px">
                                                                                            </div>
                                                                                            <input type="hidden" name="c_ImageOld[{{$colorssss->id}}]"
                                                                                                value="{{ $colorssss->image }}">
                                                                                        @else
                                                                                            <div class="input-group">
                                                                                                <input type="hidden" class="form-control" name="c_Image[]"
                                                                                                    onchange="variantImage(event)" accept="image/*" />
                                                                                                <button type="button" class="btn btn-outline-secondary browse-btn"
                                                                                                    onclick="tiggerFileSelect(this)">
                                                                                                    <i class="fa fa-picture-o"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td style="text-align:center">
                                                                                        <a href="javascript:void(0)" class="deleteonlycolorattri"
                                                                                            data-variant-id="{{ $colorssss->id }}"><img
                                                                                                src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px"></a>
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
                                                                <select name="c_color[]" id="color" class="form-control" step="any">
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
                                                                                {{ $cl->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="number" class="form-control onlyColorQty"
                                                                    name="c_qty[]" onchange="variantQtyCheck(this, 'onlycolor')"
                                                                    placeholder="Enter Quantity" min="0" value="">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="number" class="form-control" name="c_price[]"
                                                                    placeholder="Enter Price" min="0" value="0">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="input-group">
                                                                    <input type="hidden" class="form-control" name="c_Image[]"
                                                                        onchange="variantImage(event)" accept="image/*" />
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary browse-btn"
                                                                        onclick="tiggerFileSelect(this)">
                                                                        <i class="fa fa-picture-o"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <a class="remove-officer-button3 mt-3" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="Delete"><img
                                                                        src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px"
                                                                        style="margin-bottom:5px;"></a>
                                                                <br>
                                                                <a class="" onclick="addOnlycolor()" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top" title="Add"><img
                                                                        src="{{ URL::to('/') }}/img/add.png" alt=""
                                                                        width="30px"></a>
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
                                                                                                <input type="number" step="0.01" class="form-control" name="u_volume[]"
                                                                                                    id="" value="{{ $unitssss->volume }}">
                                                                                            </td>
                                                                                            <td class="mt-1">
                                                                                                <select name="u_unit[]" id="" class="form-control" step="any">
                                                                                                    <option> Select Unit</option>
                                                                                                    <?php
                                                            $color = DB::table('units')
                                                                ->where('store_id', $store_id)
                                                                ->get();

                                                                                                                                                            ?>
                                                                                                    @if (isset($color))
                                                                                                        @foreach ($color as $cl)
                                                                                                            <option value="{{ $cl->name }}" @if ($unitssss->unit == $cl->name)
                                                                                                            selected @endif>
                                                                                                                {{ $cl->name }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    @endif
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="mt-1" style="text-align:center">
                                                                                                <input type="number" name="u_qty[]" id="" class="form-control unitQty"
                                                                                                    onchange="variantQtyCheck(this, 'unit')"
                                                                                                    value="{{ $unitssss->quantity }}">
                                                                                            </td>
                                                                                            <input type="hidden" name="attriid" id="attriid"
                                                                                                value="{{ $unitssss->id }}">
                                                                                            <input type="hidden" name="u_attrId[]" value="{{ $unitssss->id }}">
                                                                                            <td class="mt-1" style="text-align:center"><input type="number"
                                                                                                    name="u_price[]" id="" class="form-control"
                                                                                                    value="{{ $unitssss->additional_price ?? 0 }}">
                                                                                            </td>
                                                                                            <td style="display: flex; justify-content: center;">
                                                                                                @if($unitssss->image)
                                                                                                    <div class="oldImg-wrap" style="display: flex;justify-content: center;">
                                                                                                        <a class="oldClose"
                                                                                                            href="{{ route('admin.variantImageDelete', ['id' => $unitssss->id]) }}">x</a>
                                                                                                        <img src="{{ getPath($unitssss->image, 'assets/images/product') }}"
                                                                                                            style="border:1px solid black;" width="60px" height="60px">
                                                                                                    </div>
                                                                                                    <input type="hidden" name="u_ImageOld[{{$unitssss->id}}]"
                                                                                                        value="{{ $unitssss->image }}">
                                                                                                @else
                                                                                                    <div class="input-group">
                                                                                                        <input type="hidden" class="form-control" name="u_Image[]"
                                                                                                            onchange="variantImage(event)" accept="image/*" />
                                                                                                        <button type="button" class="btn btn-outline-secondary browse-btn"
                                                                                                            onclick="tiggerFileSelect(this)">
                                                                                                            <i class="fa fa-picture-o"></i>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </td>
                                                                                            <td class="mt-1" style="text-align:center">
                                                                                                <a href="javascript:void(0)" class="deleteunitattri"
                                                                                                    data-variant-id="{{ $unitssss->id }}"
                                                                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><img
                                                                                                        src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px"></a>
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
                                                                    <input type="number" step="0.01" class="form-control"
                                                                        name="u_volume[]" value="">
                                                                </div>
                                                                <div class="col-md-2 mt-1">
                                                                    <select name="u_unit[]" id="color" class="form-control"
                                                                        step="any">
                                                                        <option> Select Unit</option>
                                                                        <?php
                $color = DB::table('units')
                    ->where('store_id', $store_id)
                    ->get();

                                                                                    ?>
                                                                        @if (isset($color))
                                                                            @foreach ($color as $cl)
                                                                                <option value="{{ $cl->name }}">
                                                                                    {{ $cl->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2 mt-1">
                                                                    <input type="number" class="form-control unitQty" name="u_qty[]"
                                                                        onchange="variantQtyCheck(this, 'unit')"
                                                                        placeholder="Enter Quantity" value="">
                                                                </div>
                                                                <div class="col-md-3 mt-1">
                                                                    <input type="number" class="form-control" name="u_price[]"
                                                                        placeholder="Enter Price" value="0">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="input-group">
                                                                        <input type="hidden" class="form-control" name="u_Image[]"
                                                                            onchange="variantImage(event)" accept="image/*" />
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary browse-btn"
                                                                            onclick="tiggerFileSelect(this)">
                                                                            <i class="fa fa-picture-o"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="mt-1">
                                                            <a class="remove-officer-button1  mt-3" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Delete"><img
                                                                    src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px"
                                                                    style="margin-bottom:5px;"></a>
                                                            <br>
                                                            <a onclick="addUnit()" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Add"><img src="{{ URL::to('/') }}/img/add.png" alt=""
                                                                    width="30px"></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{--Size variant--}}
                                    <div id="sizess" class="col-lg-12 mt-3  custom-scroll" @if (isset($attri_sizess) && count($attri_sizess) > 0) <div class="table-responsive">
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
                                                                                        <select name="s_size[]" id="" class="form-control" step="any">
                                                                                            <?php
                                                        $size = DB::table('sizes')
                                                            ->where('store_id', $store_id)
                                                            ->get();
                                                                                                                                                ?>
                                                                                            @if (isset($size))
                                                                                                @foreach ($size as $key => $sz)
                                                                                                    <option value="{{ $sz->name }}" @if ($sizesss->size == $sz->name) selected
                                                                                                    @endif>
                                                                                                        {{ $sz->name }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </select>
                                                                                    </td>
                                                                                    <td class="mt-1" style="text-align:center"><input type="number" name="s_qty[]"
                                                                                            id="" onchange="variantQtyCheck(this, 'size')"
                                                                                            class="form-control sizeQty" value="{{ $sizesss->quantity }}"></td>
                                                                                    <input type="hidden" name="attriid" id="attriid" value="{{ $sizesss->id }}">
                                                                                    <input type="hidden" name="s_attrId[]" value="{{ $sizesss->id }}">
                                                                                    <td class="mt-1" style="text-align:center"><input type="number" name="s_price[]"
                                                                                            id="" class="form-control"
                                                                                            value="{{ $sizesss->additional_price ?? 0 }}">
                                                                                    </td>
                                                                                    <td style="display: flex; justify-content: center;">
                                                                                        @if($sizesss->image)
                                                                                            <div class="oldImg-wrap" style="display: flex;justify-content: center;">
                                                                                                <a class="oldClose"
                                                                                                    href="{{ route('admin.variantImageDelete', ['id' => $sizesss->id]) }}">x</a>
                                                                                                <img src="{{ getPath($sizesss->image, 'assets/images/product') }}"
                                                                                                    style="border:1px solid black;" width="60px" height="60px">
                                                                                            </div>
                                                                                            <input type="hidden" name="s_ImageOld[{{$sizesss->id}}]"
                                                                                                value="{{ $sizesss->image }}">
                                                                                        @else
                                                                                            <div class="input-group">
                                                                                                <input type="hidden" class="form-control" name="s_Image[]"
                                                                                                    onchange="variantImage(event)" accept="image/*" />
                                                                                                <button type="button" class="btn btn-outline-secondary browse-btn"
                                                                                                    onclick="tiggerFileSelect(this)">
                                                                                                    <i class="fa fa-picture-o"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="mt-1" style="text-align:center">
                                                                                        <a href="javascript:void(0)" class="deletesizeattri"
                                                                                            data-variant-id="{{ $sizesss->id }}"><img
                                                                                                src="{{ URL::to('/') }}/img/delete.png" alt="" width="30px"></a>
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
                                                                        <input type="text" class="form-control" name="s_size[]"
                                                                            value="{{ $sz->name }}" readonly>
                                                                    </div>
                                                                    <div class="col-md-3 mt-1">
                                                                        <input type="number" class="form-control sizeQty" name="s_qty[]"
                                                                            onchange="variantQtyCheck(this, 'size')"
                                                                            placeholder="Enter Quantity" value="">
                                                                    </div>
                                                                    <div class="col-md-3 mt-1">
                                                                        <input type="number" class="form-control" name="s_price[]"
                                                                            placeholder="Enter Price" value="0">
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="input-group">
                                                                            <input type="hidden" class="form-control" name="s_Image[]"
                                                                                onchange="variantImage(event)" accept="image/*" />
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary browse-btn"
                                                                                onclick="tiggerFileSelect(this)">
                                                                                <i class="fa fa-picture-o"></i>
                                                                            </button>
                                                                        </div>
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
                        </div>
            @endif

            {{-- SUBMIT --}}
            <div class="col-12">
                <div class="card p-3 submit-action-card">
                    <div class="d-flex align-items-center flex-wrap gap-2" id="submitBtnSection">
                        <input type="hidden" id="updateInput" name="update" value="update">

                        @isset($product)
                            <button class="btn btn-success rounded font-sm hover-up" id="updateBtn" type="submit"
                                onclick="setSubmitValue(event, 'update')">
                                Update
                            </button>
                        @endisset

                        <button class="btn btn-info rounded font-sm hover-up" id="publishBtn" type="submit"
                            onclick="setSubmitValue(event, 'publish')">
                            Publish
                        </button>

                        @if (isset($product) && ModulusStatus($store_id, 1))
                            <a href="{{ route('admin.product.duplicate', ['id' => $product['id']]) }}"
                                class="btn rounded font-sm hover-up text-white" style="background: rebeccapurple;"
                                id="duplicateBtn">
                                Duplicate
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@push('scripts')
    <script>
        const discount_type = document.getElementById('discount_type');
        if (discount_type) {
            discount_type.addEventListener('change', function () {
                let discount_price = document.getElementById("discount_price");
                let promotional_price = document.getElementById("promotional_price");

                if (discount_type.value == "no_discount") {
                    discount_price.style.cssText = 'display: none !important';
                    if (promotional_price) promotional_price.value = "0";
                } else {
                    discount_price.style.cssText = 'display: block !important';
                }
            });
        }

        const tax_type = document.getElementById('tax_type');
        if (tax_type) {
            tax_type.addEventListener('change', function () {
                let tax_rate = document.getElementById("tax_rate");
                let tax_price = document.getElementById("tax_price");

                if (tax_type.value == "no_tax") {
                    tax_rate.style.cssText = 'display: none !important';
                    if (tax_price) tax_price.value = "0";
                } else {
                    tax_rate.style.cssText = 'display: block !important';
                }
            });
        }

        const productUnit = document.getElementById('productUnit');
        if (productUnit) {
            productUnit.addEventListener('change', function () {
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
        }

        const setSubmitValue = (e, buttonValue) => {
            e.preventDefault();
            document.getElementById('updateInput').value = buttonValue;
            e.target.form.submit();
        };
    </script>
@endpush
