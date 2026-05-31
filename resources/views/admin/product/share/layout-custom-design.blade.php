@php
    $_type = isset($type) ? $type :'product_'.$title;
    $color = '#000000';
    $hover_color = '#F1593A';
    $bg_color = '#ffffff';
    $size = 0;
    $position = isset($index) ? (int)$index : 0;
    $index = isset($index) ? (int)$index : 10;
    $design_id = 0;
    $layout_id = 0;

    if(isset($type) && isset($product['layout'][(int)$index - 11])) {
        $_index =(int)$index - 11;
        $layout_id = $product['layout'][$_index]['id'];
        $design_id = $product['layout'][$_index]['design']['id'];
        $color = $product['layout'][$_index]['design']['color'];
        $bg_color = $product['layout'][$_index]['design']['bg_color'];
        $hover_color = $product['layout'][$_index]['design']['hover_color'];
        $size = $product['layout'][$_index]['design']['size'];
        $position = $product['layout'][$_index]['position'];
    }elseif (!isset($type) && isset($product[$_type])){
        $layout_id = $product[$_type]['id'];
        $design_id = $product[$_type]['design']['id'];
        $color = $product[$_type]['design']['color'];
        $bg_color = $product[$_type]['design']['bg_color'];
        $hover_color = $product[$_type]['design']['hover_color'];
        $size = $product[$_type]['design']['size'];
        $position = $product[$_type]['position'];
    }
@endphp

@if(isset($customizable) && $customizable)
    <i class="fa fa-pencil cursor-pointer" aria-hidden="true" data-bs-toggle="modal"
       data-bs-target="#modal_{{$index}}"></i>


    <!-- Modal -->
    <div class="modal fade" id="modal_{{$index}}" tabindex="-1" aria-labelledby="modal_label_{{$index}}"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_label_{{$index}}">
                        Product {{ucwords(str_replace('_', ' ', $title)) ?? ''}} Customize</h1>
                    <i class="fa fa-times cursor-pointer" aria-hidden="true" data-bs-dismiss="modal"></i>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="position" class="col-sm-4 col-form-label">Positions</label>
                        <div class="col-sm-6">
                            <input type="number" step="1" min="0" value="{{ $position }}"
                                   name="layouts[{{$index}}][position]"
                                   class="form-control" id="position">
                        </div>
                    </div>
                    <input type="hidden" name="layouts[{{$index}}][type]" value="{{$_type}}">
                    @if($design_id > 0 && $layout_id > 0)
                        <input type="hidden" name="design[{{$index}}][id]" value="{{$design_id}}">
                        <input type="hidden" name="layouts[{{$index}}][id]" value="{{$layout_id}}">
                    @endif

                    <div class="mb-3 row">
                        <label for="color" class="col-sm-4 col-form-label"> TextColor</label>
                        <div class="col-sm-6 remove-color-pick" style="height: 40px">
                            <input type="color" class="form-control h-100" name="design[{{$index}}][color]" id="color"
                                   value="{{$color}}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="color" class="col-sm-4 col-form-label">Background Color</label>
                        <div class="col-sm-6 remove-color-pick" style="height: 40px">
                            <input type="color" class="form-control h-100" name="design[{{$index}}][bg_color]"
                                   id="color" value="{{$bg_color}}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="color" class="col-sm-4 col-form-label">Hover Color</label>
                        <div class="col-sm-6 remove-color-pick" style="height: 40px">
                            <input type="color" class="form-control h-100" name="design[{{$index}}][hover_color]"
                                   id="color" value="{{$hover_color}}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="color" class="col-sm-4 col-form-label">Size</label>
                        <div class="col-sm-6 ">
                            <input type="text" class="form-control" name="design[{{$index}}][size]" max="50"
                                   value="{{$size}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

