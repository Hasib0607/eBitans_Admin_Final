@push('styles')
    <style>
        #is_buy_now_cart.check-toggle-round-flat:checked + label:after {
            margin-left: 33px;
        }
    </style>
@endpush
<div class="mb-4 row titleInput">
    <div class="col-md-8">
        <label for="title" class="form-label">Title</label>
        <input
            type="text"
            placeholder="Type here"
            value="{{ $store_design->title ?? '' }}"
            class="form-control"
            name="title"
            id="title"
        >
        @error('title')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="title_color" class="form-label">Title Color</label>
        <input
            type="color"
            placeholder="Type here"
            class="form-control"
            style="width:100%; height: 42px;"
            value="{{ $store_design->title_color ?? 'transparent' }}"
            name="title_color"
            id="title_color"
        >
        @error('title_color')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mb-4 row subtitleInput">
    <div class="col-md-8">
        <label for="subtitle" class="form-label">Sub Title</label>
        <input
            type="text"
            placeholder="Type here"
            value="{{ $store_design->subtitle ?? '' }}"
            class="form-control"
            name="subtitle"
            id="subtitle"
        >
        @error('subtitle')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="subtitle_color" class="form-label">Sub Title Color</label>
        <input
            type="color"
            placeholder="Type here"
            class="form-control"
            style="width:100%; height: 42px;"
            value="{{ $store_design->subtitle_color ?? 'transparent' }}"
            name="subtitle_color"
            id="subtitle_color"
        >
        @error('subtitle_color')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mb-4 row buttonInput">
    <div class="col-md-6">
        <label for="button" class="form-label">
            Button
            <div class="switch">
                <input id="is_buy_now_cart" name="is_buy_now_cart"
                       @if (isset($store_design->is_buy_now_cart) && $store_design->is_buy_now_cart == 1) checked @endif
                       class="check-toggle check-toggle-round-flat" type="checkbox">
                <label for="is_buy_now_cart" style="margin-bottom:0px"></label>
                <span class="on">Add</span>
                <span class="off">Buy</span>
            </div>
        </label>
        <input
            type="text"
            placeholder="Type here"
            class="form-control"
            id="button"
            name="button"
            value="{{ $store_design->button ?? '' }}"
        >

        @error('button')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="button_color" class="form-label">Color</label>
        <input
            type="color"
            placeholder="Type here"
            class="form-control "
            style="width:100%; height: 42px;"
            value="{{ $store_design->button_color ?? 'transparent' }}"
            name="button_color"
            id="button_color"
        >
        @error('button_color')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="button_bg_color" class="form-label">Background Color</label>
        <input
            type="color"
            placeholder="Type here"
            class="form-control "
            style="width:100%; height: 42px;"
            value="{{ $store_design->button_bg_color ?? 'transparent' }}"
            name="button_bg_color"
            id="button_bg_color"
        >
        @error('button_bg_color')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mb-4 row button1Input">
    <div class="col-md-6">
        <label for="button1" class="form-label">Button 1</label>
        <input
            type="text"
            placeholder="Type here"
            class="form-control"
            id="button1"
            name="button1"
            value="{{ $store_design->button1 ?? '' }}"
        >
        @error('button1')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
    <div class="col-md-2">
        <label for="button1_color" class="form-label">Color</label>
        <input
            type="color"
            placeholder="Type here"
            class="form-control "
            style="width:100%; height: 42px;"
            value="{{ $store_design->button1_color ?? 'transparent' }}"
            name="button1_color"
            id="button1_color"
        >
        @error('button1_color')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="button1_bg_color" class="form-label">Background Color</label>
        <input
            type="color"
            placeholder="Type here"
            class="form-control "
            style="width:100%; height: 42px;"
            value="{{ $store_design->button1_bg_color ?? 'transparent' }}"
            name="button1_bg_color"
            id="button1_bg_color"
        >
        @error('button1_bg_color')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mb-4 row linkInput">
    <div>
        <label for="link" class="form-label">Link</label>
        <input
            type="text"
            placeholder="Link here..."
            class="form-control"
            style="width:100%; height: 42px;"
            value="{{ $store_design->link ?? '' }}"
            name="link"
            id="link"
        >
        @error('link')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mb-4 row bgImageInput">
    <div class="col-md-8">
        <label for="bg_image" class="form-label">Background Image</label>
        <div id="previewContainer">
            <div class="image-preview"
                 style="position: relative; display: inline-block;">
                <img
                    src="{{ URL::to('/') }}/img/upload.svg"
                    style="height: 100px; border: 1px solid rgb(204, 204, 204); padding: 3px; margin-right: 10px;">
            </div>
        </div>
        <input type="hidden" class="form-control" id="bg_image" name="bg_image">

        <button type="button" class="btn btn-outline-secondary browse-btn mt-2"
                onclick="standalonFileManagerModal('bg_image', true, 'previewContainer');">
            <i class="fa fa-picture-o"></i> Browse
        </button>
        @error('bg_image')
        <p class="text-danger" role="alert">{{ $message }}</p>
        @enderror
    </div>
    <div class="col-md-4" style="display: flex ; justify-content: center; align-items: center;">
        <img src=""
             id="bgImageElement"
             alt="bg_image"
             width="100px" style="margin-bottom:10px;">
    </div>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.20.1/full-all/ckeditor.js"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('admin/dist/js/custom-ckeditor.js') }}"></script>

@endpush
