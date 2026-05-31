@if(isset($staff) && $staff == true)
    @if(isset($products) && count($products) > 0)
        @foreach($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $product->model_no ?? "" }}
                </td>
                <td>
                    {{ $product->product_name ?? "" }}
                </td>
                <td>
                    {!! $product->description ?? "" !!}
                </td>
                <td>
                    {{ $product->category ?? "" }}
                </td>
                <td>
                    {{ $product->sub_category ?? "" }}
                </td>
                <td>
                    {{ $product->price ?? "" }}
                </td>
                <td>
                    {{ $product->brand ?? "" }}
                </td>
                <td>
                    {{ $product->supplier ?? "" }}
                </td>
                <td>
                    {{ $product->cost ?? "" }}
                </td>
                <td>
                    {{ $product->discount ?? "" }}
                </td>
                <td>
                    {{ $product->color ?? "" }}
                </td>
                <td>
                    {{ $product->size ?? "" }}
                </td>
                <td>
                    {{ $product->unit ?? "" }}
                </td>
                <td>
                    {{ $product->other_info ?? "" }}
                </td>
                <td>
                    @if(isset($product->save_status) && $product->save_status == 1)
                        {{ "Product Created" }}
                    @else
                        {{ "Not Created" }}
                    @endif
                </td>
                <td>
                    <a href="{{ route("staff.websitesetup.view.product", ['id' => $product->id ]) }}"
                       class="btn btn-secondary">View</a>
                    <a href="{{ route("staff.websitesetup.delete.product", ['id' => $product->id ]) }}"
                       onclick="return confirm('Are you sure you want to delete this record?');" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="16" class="text-center">Add your products
                here
            </td>
        </tr>
    @endif
@else
    @if(isset($products) && count($products) > 0)
        @foreach($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $product->product_name ?? "" }}
                </td>
                <td>
                    {{ $product->category ?? "" }}
                </td>
                <td>
                    {{ $product->price ?? "" }}
                </td>
                <td>
                    <div class="modal fade" id="imageModal{{$product->id ?? ""}}" tabindex="-1"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content"
                                 style="background-color:transparent;border:0px">

                                <div class="modal-body" style="border:none">
                                    <button class="btn btn-danger sm" data-bs-dismiss="modal"
                                            style="float: right; margin: 0px 8px;">X
                                    </button>

                                    <div class="row mt-1">
                                        <div class="col-12">
                                            <div class="card h-100 relative">
                                                <!-- Overlay Loading Screen -->
                                                <div id="overlay{{ $product->id ?? "" }}" style="
                                                    position: absolute;
                                                    top: 0; left: 0;
                                                    width: 100%; height: 100%;
                                                    background: rgba(0, 0, 0, 0.6);
                                                    display: none; /* Hidden by default */
                                                    justify-content: center;
                                                    align-items: center;
                                                    flex-direction: column;
                                                    color: white;
                                                    z-index: 1000;
                                                    border-radius: 12px;
                                                ">
                                                    <p>Uploading, please wait...</p>
                                                    <progress id="uploadProgress{{ $product->id ?? "" }}" value="0"
                                                              max="100"
                                                              style="width: 50%;"></progress>
                                                    <p id="uploadPercentage{{ $product->id ?? "" }}">0%</p>
                                                </div>

                                                <div class="overlayLoading" id="overlayLoading{{ $product->id ?? "" }}">
                                                    <p>Deleting, please wait...</p>
                                                </div>

                                                <div class="d-flex">
                                                    <div class="input-upload d-flex" style="padding: 0;"
                                                         id="imageListWrapper{{ $product->id ?? "" }}">
                                                        <output id="FilelistModal{{$product->id ?? ""}}"
                                                                style="display: flex;flex-wrap: wrap;">
                                                            <ul class="thumb-Images overflow-x-auto" id="imgListModal">
                                                                <li class="image-box mx-2"
                                                                    style="height: 95px; width: 105px">
                                                                    <input type="file" class="form-control"
                                                                           id="modalImage"
                                                                           onchange="handleFileSelectModal(event, {{$product->id ?? ""}})"
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
                                                        @if (isset($product->image))
                                                            @foreach ($product->image as $key => $item)
                                                                <div class="oldImg-wrap mx-2"
                                                                     id="imageWrapper{{ $item->id ?? "" }}">
                                                                    <a class="oldClose"
                                                                       data-href="{{  route('admin.delete.website.setup.image', ['id' => $item->id]) }}"
                                                                       onclick="deleteImage(event,{{ $item->id }}, {{ $product->id }})"
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
                                                    </div>
                                                </div>
                                                @if (isset($product->image) && count($product->image) > 0)
                                                    <div class="mt-3 text-end">
                                                        <button class="btn btn-primary mb-0" data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-secondary"
                            data-bs-toggle="modal"
                            data-bs-target="#imageModal{{$product->id ?? ""}}"
                            data-title="Pay"
                            id="imageModalBtn{{$product->id ?? ""}}">View Image
                    </button>
                </td>
                <td>
                    {{ $product->description ?? "" }}
                </td>
                <td>
                    {{ $product->other_info ?? "" }}
                </td>
                <td>
                    <a href="{{ route("admin.websitesetup.delete.product", ['id' => $product->id ]) }}"
                       onclick="return confirm('Are you sure you want to delete this record?');"><img
                            src="{{ asset('img/delete.png') }}" width="25px"
                            height="25px"></a>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="7" class="text-center">Add your products
                here
            </td>
        </tr>
    @endif
@endif



