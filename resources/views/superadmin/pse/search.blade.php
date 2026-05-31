<table class="table table-striped" width="100%">
    <thead>
    <tr>
        <th width="4%"><input type="checkbox" name="ids" id="checkedAll"></th>
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
        <th width="10%">Position</th>
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
            <td><input type="checkbox" name="selectedid" value="{{ $product->id }}" id="id"
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
            <td style="text-align: center;">
                {{ Str::of($product->name)->limit(40) }}
            </td>
            <td style="text-align: center;">৳{{ $product->regular_price }}</td>
            <td>
                <input type="hidden" name="idss" id="id" value="{{ $product->id }}"
                       style="text-align: center;">
                <input type="number" class="form-control" name="position" value="{{ $product->position ?? 0 }}"
                       style="text-align: center;">
            </td>
            <td class="centered-cell">
                @if (isset($product->barcode) && $product->barcode != '')
                    <div class="barcode" style="display: inline-block; vertical-align: middle;">
                        {!! DNS1D::getBarcodeHTML(ucwords($product->barcode), 'C128', 1.4, 22) !!}
                    </div>
                @endif
            </td>
            <td>
                <div class="form-check form-switch" style="text-align:center;">
                    <input class="form-check-input switchstatus" type="checkbox" id="flexSwitchCheckChecked"
                           data-id="{{ $product->id }}" style="margin:0 auto;"
                           @if ($product->status == 'active') checked @endif>
                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                </div>
            </td>
            <td>{{ date('d-m-Y', strtotime($product->created_at)) }}</td>
            <td>
                <a href="{{ route('superadmin.pse.select.view', $product->id) }}"><img
                        src="{{ asset('img/eye.png') }}" width="20px" height="20px">
                </a>
                &nbsp;&nbsp;
                <a
                    onclick="showConfirmation('{{ $product->id }}', {{ json_encode($categories->pluck('name', 'id')) }}); return false;">
                    <img src="{{ asset('img/accepted' . $product->pse . '.png') }}" width="20px"
                         height="20px">
                </a>
                &nbsp;&nbsp;
                <a href="{{ URL::to('/') }}/superadmin/pse-products/rejected?id={{ $product->id }}"
                   onclick="return confirm('Are you sure you want to Rejecte this item?');">
                    <img src="{{ asset('img/delete.png') }}" width="25px" height="25px">
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
