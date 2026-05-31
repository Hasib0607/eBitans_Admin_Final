<div class="table-responsive" id="desktoptable">
    <table class="table table-striped" width="100%" id="taskfilterresult">
        <thead>
        <tr>
            <th width="4%"><input type="checkbox" name="ids"
                                  id="checkedAll"></th>
            <th width="5%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    ছবি
                @else
                    Image
                @endif
            </th>
            <th width="30%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    নাম
                @else
                    Name
                @endif
            </th>
            <th width="20%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    দাম
                @else
                    Price
                @endif
            </th>
            @if (ModulusStatus($store_id, 9))
                <th width="10%">Position</th>
            @endif

            <th width="10%">SKU</th>
            <th width="10%">Barcode</th>
            <th width="10%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    স্ট্যাটাস
                @else
                    Status
                @endif
            </th>
            <th width="15%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    তারিখ
                @else
                    Date
                @endif
            </th>
            <th width="11%">
                @if (Session::has('lang') && Session::get('lang') == 'bn')
                    এডিট/ডিলিট
                @else
                    Action
                @endif
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                <td><input type="checkbox" name="selectedid"
                           value="{{ $product->id }}" id="id"
                           class="checkSingle"></td>
                <td>
                    @php
                        $images = array_filter(explode(',', $product->images));
                        $gallery_image = array_filter(explode(',', $product->gallery_image));
                        $mergedImages = array_unique(array_merge($gallery_image, $images));
                        $images = array_map(fn($img) => getPath($img, 'assets/images/product'), $mergedImages);
                    @endphp
                    @if (isset($images[0]))
                        <img src="{{ $images[0] }}"
                             class="zoom" width="30px" alt="">
                    @endif
                </td>
                <td>{{ Str::of($product->name)->limit(20) }}</td>
                <td>{{$product->symbol}}{{ $product->regular_price }}</td>

                @if (ModulusStatus($store_id, 9))
                    <td>
                        <input type="hidden" name="idss" id="id" value="{{ $product->id }}">
                        <input type="number" class="form-control" name="position" value="{{ $product->position??0 }}">
                    </td>
                @endif

                <td>
                    {{ $product->SKU??'' }}
                </td>

                <td>
                    @if (isset($product->barcode) && $product->barcode != '')
                        <div
                            class="barcode">{!! DNS1D::getBarcodeHTML(ucwords($product->barcode), 'C128', 1.4, 22) !!}</div>
                    @endif
                </td>
                <td>
                    <div class="form-check form-switch" style="text-align:center;">
                        <input class="form-check-input switchstatus switchInSerch" type="checkbox"
                               id="flexSwitchCheckChecked" data-id="{{ $product->id }}"
                               style="margin:0 auto;"
                               @if ($product->status == 'active') checked @endif>
                        <label class="form-check-label"
                               for="flexSwitchCheckChecked"></label>
                    </div>
                </td>
                <td>{{ date('d-m-Y', strtotime($product->created_at)) }}</td>
                <td>
                    <a href="{{ URL::to('/') }}/products/edit/{{ $product->id }}"><img
                            src="{{ asset('img/edit.png') }}" width="20px"
                            height="20px"></a>
                    &nbsp;&nbsp;
                    <a href="{{ URL::to('/') }}/deleteproduct/{{ $product->id }}"
                       onclick="return confirm('Are you sure you want to delete this item?');"><img
                            src="{{ asset('img/delete.png') }}" width="25px"
                            height="25px"></a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
    {{-- {!! $products->links() !!} --}}
</div>

<div class="table-responsive mt-3" id="mobiletable">
    <table class="table" width="100%">
        @foreach ($products as $key => $product)
            <tr class="mobilefirstrow">
                <th width="10%">
                    <input type="checkbox" name="selectedid" value="{{ $product->id }}"
                           id="id" class="checkSingle">
                </th>
                <th width="20%" style="color:#f1593a">
                    Name:
                </th>
                <td width="60%" style="color:black">
                    {{ Str::of($product->name)->limit(20) }}
                </td>
                <td width="10%">
                    <a href="#" class="toggler"
                       data-prod-cat="{{ $key }}">
                        <i class="fa fa-arrow-down" id="show{{ $key }}"
                           style="color:#f1593a"></i>
                        <i class="fa fa-arrow-up" id="up{{ $key }}"
                           style="display:none"></i>
                    </a>
                </td>
            </tr>
            <tr class="cat{{ $key }}" style="display:none">
                <th width="10%"></th>
                <th width="20%">
                    Image
                </th>
                <td width="60%">
                    @php
                        $images = array_filter(explode(',', $product->images));
                        $gallery_image = array_filter(explode(',', $product->gallery_image));
                        $mergedImages = array_unique(array_merge($gallery_image, $images));
                        $images = array_map(fn($img) => getPath($img, 'assets/images/product'), $mergedImages);
                    @endphp
                    @if (isset($images[0]))
                        <img src="{{ $images[0] }}"
                             class="zoom" width="30px" alt="">
                    @endif
                </td>
                <td width="10%"></td>
            </tr>
            <tr class="cat{{ $key }}" style="display:none">
                <th width="10%"></th>
                <th width="20%">
                    Price
                </th>
                <td width="60%">
                    ৳{{ $product->regular_price }}
                </td>
                <td width="10%"></td>
            </tr>
            <tr class="cat{{ $key }}" style="display:none">
                <th width="10%"></th>
                <th width="20%">
                    SKU
                </th>
                <td width="60%">
                    {{ $product->SKU??'' }}
                </td>
                <td width="10%"></td>
            </tr>
            <tr class="cat{{ $key }}" style="display:none">
                <th width="10%"></th>
                <th width="20%">
                    Barcode
                </th>
                <td width="60%">
                    @if (isset($product->barcode) && $product->barcode != '')
                        {!! DNS1D::getBarcodeHTML(ucwords($product->barcode), 'C128', 1.4, 22) !!}
                    @endif
                </td>
                <td width="10%"></td>
            </tr>
            <tr class="cat{{ $key }}" style="display:none">
                <th width="10%"></th>
                <th width="20%">
                    Status
                </th>
                <td width="60%"
                    style="display: flex;justify-content: center;align-items: center;">
                    <div class="form-check form-switch" style="text-align:center;">
                        <input class="form-check-input switchstatus switchInSerch" type="checkbox"
                               id="flexSwitchCheckChecked" data-id="{{ $product->id }}"
                               style="margin:0 auto;"
                               @if ($product->status == 'active') checked @endif>
                        <label class="form-check-label"
                               for="flexSwitchCheckChecked"></label>
                    </div>
                </td>
                <td width="10%"></td>
            </tr>
            <tr class="cat{{ $key }}" style="display:none">
                <th width="10%"></th>
                <th width="20%">
                    Date
                </th>
                <td width="60%">
                    {{ date('d-m-Y', strtotime($product->created_at)) }}
                </td>
                <td width="10%"></td>
            </tr>
            <tr class="cat{{ $key }}" style="display:none">
                <th width="10%"></th>
                <th width="20%">
                    Action
                </th>
                <td width="60%">
                    <a href="{{ URL::to('/') }}/products/edit/{{ $product->id }}"><img
                            src="{{ asset('img/edit.png') }}" width="20px"
                            height="20px"></a>
                    &nbsp;&nbsp;
                    <a href="{{ URL::to('/') }}/deleteproduct/{{ $product->id }}"
                       onclick="return confirm('Are you sure you want to delete this item?');"><img
                            src="{{ asset('img/delete.png') }}" width="25px"
                            height="25px"></a>
                </td>
                <td width="10%"></td>
            </tr>
        @endforeach

    </table>
    {{-- {!! $products->links() !!} --}}
</div>

