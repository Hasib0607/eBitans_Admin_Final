@if (ModulusStatus($store_id, 114))
    <div class="card mb-4" onmouseover="mouseOverVariant()"
         onmouseout="mouseOverVariantmouseOut()">
        <div class="card-header" onmouseover="mouseOverVariant()" onclick="openAttri()"
             onmouseout="mouseOverVariantmouseOut()">
            <input type="hidden" id="attriCheck" value="1">
            <div class="row">
                <div class="col-6">
                    <h4>
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            ভেরিয়েন্ট
                        @else
                            Attributes
                        @endif
                    </h4>
                </div>
                <div class="col-6" style="text-align:right">
                    <a href="javascript:void(0)" id="attrishow"><i class="fa fa-arrow-down"></i></a>
                    <a href="javascript:void(0)" id="attrihide"><i
                            class="fa fa-arrow-up"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row" id="attri-div">
                <div class="col-md-2">
                    <label for="">
                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                            ভেরিয়েন্ট
                            টাইপ
                        @else
                            Variantion Type
                        @endif
                    </label>
                    <select class="form-control" name="att" id="attributes">
                        <option value="none">Select</option>
                        <option value="color"
                                @if (isset($attri_color) && count($attri_color) > 0) selected @endif>
                            Color & Size
                        </option>
                        <option value="onlycolor"
                                @if (isset($select_onlycolor) && count($select_onlycolor) > 0) selected @endif>
                            Color
                        </option>
                        <option value="unit"
                                @if (isset($select_unitsss) && count($select_unitsss) > 0) selected @endif>
                            Unit
                        </option>
                        <option value="size"
                                @if (isset($select_sizess) && count($select_sizess) > 0) selected @endif>
                            Size
                        </option>
                    </select>
                </div>


                {{--color and size variant--}}
                <div id="colorrss" class="col-lg-12 mt-3">
                        <?php
                        if (isset($product)) {
                            $attri_colorss = DB::table('veriants')
                                ->select('veriants.*', 'c.symbol', 'c.code')
                                ->join('products as p', 'p.id', '=', 'veriants.pid')
                                ->join('currencies as c', 'p.currency_id', '=', 'c.id')
                                ->when('p.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                                    function ($query) use ($current_currency) {
                                        $query->addSelect([
                                            DB::raw("ROUND(veriants.additional_price / c.rate * " . $current_currency->rate . " , 2) as additional_price"),
                                            DB::raw("'{$current_currency->symbol}' as symbol"),
                                            DB::raw("'{$current_currency->code}' as code"),
                                        ]);
                                    })
                                ->when('p.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                                    function ($query) use ($store, $current_currency) {
                                        $query->addSelect([
                                            DB::raw("ROUND(products.additional_price / {$store->currency_rate}, 2) as additional_price"),
                                            DB::raw("'{$current_currency->symbol}' as symbol"),
                                            DB::raw("'{$current_currency->code}' as code"),
                                        ]);
                                    })
                                ->where('veriants.pid', $product['id'])
                                ->where('veriants.color', '!=', null)
                                ->where('veriants.size', '!=', null)
                                ->get();
                        }

                        ?>
                    @if (isset($attri_colorss) && count($attri_colorss) > 0)
                        <div class="colorrss_ok table-responsive">
                            <table class="table table-stripped" width="100%">
                                <thead>
                                <tr>
                                    <th width="20%" style="text-align:center">Color</th>
                                    <th width="20%" style="text-align:center">Size</th>
                                    <th width="15%" style="text-align:center">Quantity</th>
                                    <th width="15%" style="text-align:center">Additional Price
                                    </th>
                                    <th width="15%" style="text-align:center">Media</th>
                                    <th width="15%" style="text-align:center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($attri_colorss as $keyss => $colorsss)
                                    <tr id="{{ $colorsss->id }}">
                                        <td class="mt-1" style="text-align:center">
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
                                                <input type="file"
                                                       class="form-control"
                                                       onchange="variantImage(event)"
                                                       accept="image/*"
                                                       name="cs_Image[{{ $keyss }}][]"
                                                />
                                            @endif
                                        </td>
                                        <td class="mt-1" style="text-align:center"><a
                                                href="javascript:void(0)" class="deleteattri"
                                                data-variant-id="{{ $colorsss->id }}"
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
                    {{-- @if (isset($attri_colorss) && count($attri_colorss) > 0) --}}
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
                                            @php
                                                $checkBoxIndex = ($i * 1000) + $key;
                                            @endphp
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
                                                                       onclick="checkBox({{ $checkBoxIndex }})"
                                                                       id="checkBoxStatus{{ $checkBoxIndex }}"
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
                                                           id="checkBoxWrite{{ $checkBoxIndex }}"
                                                           onchange="variantQtyCheck(this, 'color')"
                                                           readonly
                                                           placeholder="Enter Quantity"
                                                           value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Additional Price</label>
                                                    <input type="number" min="0.00"
                                                           class="form-control"
                                                           name="cs_price[{{ $i }}][]"
                                                           placeholder="Additional Price"
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
                                    <a onclick="addRow()"
                                       data-bs-toggle="tooltip" data-bs-placement="top"
                                       title="Add"><img src="{{ URL::to('/') }}/img/add.png"
                                                        alt="" width="30px"></a>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- @endif --}}
                </div>

                {{--onlycolor variant--}}
                <div id="onlycolors" class="col-lg-12 mt-3">
                        <?php
                        if (isset($product)) {
                            $attri_onlycolor = DB::table('veriants')
                                ->select('veriants.*', 'c.symbol', 'c.code')
                                ->join('products as p', 'p.id', '=', 'veriants.pid')
                                ->join('currencies as c', 'p.currency_id', '=', 'c.id')
                                ->when('p.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                                    function ($query) use ($current_currency) {
                                        $query->addSelect([
                                            DB::raw("ROUND(veriants.additional_price / c.rate * " . $current_currency->rate . " , 2) as additional_price"),
                                            DB::raw("'{$current_currency->symbol}' as symbol"),
                                            DB::raw("'{$current_currency->code}' as code"),
                                        ]);
                                    })
                                ->when('p.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                                    function ($query) use ($store, $current_currency) {
                                        $query->addSelect([
                                            DB::raw("ROUND(products.additional_price / {$store->currency_rate}, 2) as additional_price"),
                                            DB::raw("'{$current_currency->symbol}' as symbol"),
                                            DB::raw("'{$current_currency->code}' as code"),
                                        ]);
                                    })
                                ->where('veriants.pid', $product['id'])
                                ->where('veriants.size', null)
                                ->where('veriants.color', '!=', null)
                                ->get();
                        }
                        ?>
                    @if (isset($attri_onlycolor) && count($attri_onlycolor) > 0)
                        <table class="colorrss_ok table table-stripped" width="100%">
                            <thead>
                            <tr>
                                <th width="25%" style="text-align:center">Color</th>
                                <th width="25%" style="text-align:center">Quantity</th>
                                <th width="20%" style="text-align:center">Additional Price</th>
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
                                           class="deleteonlycolorattri"
                                           data-variant-id="{{ $colorssss->id }}"><img
                                                src="{{ URL::to('/') }}/img/delete.png"
                                                alt="" width="30px"></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                    {{-- @if (isset($attri_onlycolor) && count($attri_onlycolor) > 0) --}}
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
                                        Additional Price
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
                    {{-- @endif --}}
                </div>

                {{--unit variant--}}
                <div id="unittss" class="col-lg-12 mt-3">
                        <?php
                        if (isset($product)) {
                            $attri_unitsss = DB::table('veriants')
                                ->select('veriants.*', 'c.symbol', 'c.code')
                                ->join('products as p', 'p.id', '=', 'veriants.pid')
                                ->join('currencies as c', 'p.currency_id', '=', 'c.id')
                                ->when('p.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                                    function ($query) use ($current_currency) {
                                        $query->addSelect([
                                            DB::raw("ROUND(veriants.additional_price / c.rate * " . $current_currency->rate . " , 2) as additional_price"),
                                            DB::raw("'{$current_currency->symbol}' as symbol"),
                                            DB::raw("'{$current_currency->code}' as code"),
                                        ]);
                                    })
                                ->when('p.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                                    function ($query) use ($store, $current_currency) {
                                        $query->addSelect([
                                            DB::raw("ROUND(products.additional_price / {$store->currency_rate}, 2) as additional_price"),
                                            DB::raw("'{$current_currency->symbol}' as symbol"),
                                            DB::raw("'{$current_currency->code}' as code"),
                                        ]);
                                    })
                                ->where('veriants.pid', $product['id'])
                                ->where('veriants.color', null)
                                ->where('veriants.size', null)
                                ->where('veriants.volume', '!=', null)
                                ->get();
                        }
                        ?>
                    @if (isset($attri_unitsss) && count($attri_unitsss) > 0)
                        <div class="table-responsive">
                            <table class="colorrss_ok table table-stripped" width="100%">
                                <thead>
                                <tr>
                                    <th width="20%" style="text-align:center">Volume</th>
                                    <th width="20%" style="text-align:center">Unit</th>
                                    <th width="15%" style="text-align:center">Quantity</th>
                                    <th width="15%" style="text-align:center">Additional Price
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
                                               data-variant-id="{{ $unitssss->id }}"
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
                    {{-- @if (isset($attri_unitsss) && count($attri_unitsss) > 0) --}}
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
                                            Additional Price
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
                    {{-- @endif --}}
                </div>

                {{--Size variant--}}
                <div id="sizess" class="col-lg-12 mt-3">
                        <?php
                        if (isset($product)) {
                            $attri_sizess = DB::table('veriants')
                                ->select('veriants.*', 'c.symbol', 'c.code')
                                ->join('products as p', 'p.id', '=', 'veriants.pid')
                                ->join('currencies as c', 'p.currency_id', '=', 'c.id')
                                ->when('p.currency_id' !== $store->currency && $current_currency->customize_rate_status === 0,
                                    function ($query) use ($current_currency) {
                                        $query->addSelect([
                                            DB::raw("ROUND(veriants.additional_price / c.rate * " . $current_currency->rate . " , 2) as additional_price"),
                                            DB::raw("'{$current_currency->symbol}' as symbol"),
                                            DB::raw("'{$current_currency->code}' as code"),
                                        ]);
                                    })
                                ->when('p.currency_id' !== $store->currency && $store->current_currency->customize_rate_status,
                                    function ($query) use ($store, $current_currency) {
                                        $query->addSelect([
                                            DB::raw("ROUND(products.additional_price / {$store->currency_rate}, 2) as additional_price"),
                                            DB::raw("'{$current_currency->symbol}' as symbol"),
                                            DB::raw("'{$current_currency->code}' as code"),
                                        ]);
                                    })
                                ->where('veriants.pid', $product['id'])
                                ->where('veriants.color', null)
                                ->where('veriants.size', '!=', null)
                                ->get();
                        }
                        ?>
                    @if (isset($attri_sizess) && count($attri_sizess) > 0)
                        <div class="table-responsive">
                            <table class="colorrss_ok table table-stripped" width="100%">
                                <thead>
                                <tr>
                                    <th width="25%" style="text-align:center">Size</th>
                                    <th width="25%" style="text-align:center">Quantity</th>
                                    <th width="20%" style="text-align:center">Additional Price
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
                                               class="deletesizeattri"
                                               data-variant-id="{{ $sizesss->id }}"><img
                                                    src="{{ URL::to('/') }}/img/delete.png"
                                                    alt="" width="30px"></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    {{-- @if (isset($attri_sizess) && count($attri_sizess) > 0) --}}
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
                                            Additional Price
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
                    {{-- @endif --}}
                </div>
            </div>

            <!-- </form> -->
        </div>
    </div> <!-- card end// -->
@endif


