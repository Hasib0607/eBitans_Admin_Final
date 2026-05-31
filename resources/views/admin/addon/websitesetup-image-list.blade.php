<output id="FilelistModal{{$product_id ?? ""}}"
        style="display: flex;flex-wrap: wrap;">
    <ul class="thumb-Images overflow-x-auto" id="imgListModal">
        <li class="image-box mx-2"
            style="height: 95px; width: 105px">
            <input type="file" class="form-control"
                   id="modalImage"
                   onchange="handleFileSelectModal(event, {{$product_id ?? ""}})"
                   name="image[]"
                   multiple accept="image/*">
            <div class="content text-center" id="placeholder">
                <p></p>
                <h1>+</h1>
                <p>Upload Image</p>
            </div>
        </li>
    </ul>
</output>
@if (isset($images))
    @foreach ($images as $key => $item)
        <div class="oldImg-wrap mx-2"
             id="imageWrapper{{ $item->id ?? "" }}">
            <a class="oldClose"
               data-href="{{  route('admin.delete.website.setup.image', ['id' => $item->id]) }}"
               onclick="deleteImage(event,{{ $item->id }}, {{$product_id ?? ""}})"
               id="removeImage{{ $item->id ?? "" }}">x</a>

            <img
                src="{{ asset('assets/images/setup') }}/{{ $item->image ?? "" }}"
                style="padding:10px;border:1px solid black;margin-bottom:5px;"
                width="105px" height="95px">

            <input type="hidden" class="form-control" id=""
                   name="oldImage[]"
                   value="{{ $item->image ?? "" }}">
        </div>
    @endforeach
@endif




