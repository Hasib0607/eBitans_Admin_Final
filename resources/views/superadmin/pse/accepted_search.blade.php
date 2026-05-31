<table class="table table-striped" width="100%" id="taskfilterresult">
    <thead>
        <tr>
            <th width="4%"><input type="checkbox" name="ids" id="checkedAll">
            </th>
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
            <th width="10%">SKU</th>
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
                <td>
                    <input type="checkbox" name="selectedid" value="{{ $product->id }}" id="id"
                        class="checkSingle">
                </td>
                <td>
                    @if ($product->productImage)
                        @php
                            $images = is_array($product->productImage)
                                ? $product->productImage
                                : explode(',', $product->productImage);
                        @endphp
                        @foreach ($images as $key => $image)
                            @if ($key == '0')
                                <img src="{{ URL::to('/') }}/assets/images/product/{{ $image }}" class="zoom"
                                    width="30px">
                            @endif
                        @endforeach
                    @endif
                </td>
                <td style="text-align: center;">
                    <p style="color:#000">{{ Str::of($product->name)->limit(40) }}
                        <img style="height:18px; width:18px;" src="{{ $product->expiry_date }}" alt=""
                            srcset="">
                    </p>
                    <p>
                        User Id
                        :
                        {{ $product->uid }}</p>
                </td>
                <td style="text-align: center;">৳{{ $product->regular_price }}</td>
                <td>
                    @if (isset($product->SKU) && $product->SKU != '')
                        {{ $product->SKU ?? '' }}
                    @endif
                </td>
                <td>
                    <input type="hidden" name="position_id" id="id" value="{{ $product->id }}"
                        style="text-align: center;">
                    <input type="number" class="form-control" name="position"
                        value="{{ $product->appr_position ?? 0 }}" style="text-align: center;">
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
                            @if ($product->status == 1) checked @endif>
                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                    </div>
                </td>
                </td>
                <td>{{ date('d-m-Y', strtotime($product->created_at)) }}</td>
                <td>
                    <a href="{{ route('superadmin.pse.select.view', $product->product_id) }}"><img
                            src="{{ asset('img/eye.png') }}" width="20px" height="20px">
                    </a>
                    &nbsp;&nbsp;
                    <a
                        onclick="showConfirmation('{{ $product->product_id }}', {{ json_encode($categories->pluck('name', 'id')) }}, '{{ $product->main_category_name }}', '{{ $product->subcategory_name }}', {{ $product->category_id }}); return false;">
                        <img src="{{ asset('img/accepted' . $product->pse . '.png') }}" width="20px" height="20px">
                    </a>
                    &nbsp;&nbsp;
                    <a onclick="deleteConfirmation('{{ $product->id }}'); return false;">
                        <img src="{{ asset('img/delete.png') }}" width="25px" height="25px">
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
