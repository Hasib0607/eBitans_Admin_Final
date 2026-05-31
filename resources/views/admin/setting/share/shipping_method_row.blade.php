<div class="row shipping-method-row align-items-center mb-3" data-index="{{ $index }}">
    <input type="hidden" name="shipping_methods[{{ $index }}][id]" value="{{ $index + 1 }}">

    <div class="col-1 text-center">
        <input type="radio" name="selected_shipping_area" value="{{ $method->id ?? $index }}"
               class="onchangeShippingMethod"
            {{ old('selected_shipping_area', $data->selected_shipping_area  ?? 0) == ($method->id ?? $index) ? 'checked' : '' }}>
    </div>
    <div class="col-5">
        <label class="form-label">Shipping Area</label>
        <input type="text" name="shipping_methods[{{ $index }}][area]" class="form-control"
               value="{{ $method->area ?? '' }}" placeholder="Ex. Inside Dhaka / Outside Dhaka">
    </div>
    <div class="col-4">
        <label class="form-label">Cost</label>
        <input type="number" name="shipping_methods[{{ $index }}][cost]" class="form-control"
               placeholder="Cost" value="{{ $method->cost ?? '' }}">
    </div>
    @if($index != 0)
        <div class="col-2 text-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeShippingMethod(this)">Delete</button>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        let shippingIndex = document.querySelectorAll('.shipping-method-row').length;

        function addShippingMethod() {
            shippingIndex++;
            const wrapper = document.getElementById('shipping-methods-wrapper');

            const row = document.createElement('div');
            row.className = 'row shipping-method-row align-items-center mb-3';
            row.dataset.index = shippingIndex;

            row.innerHTML = `
            <input type="hidden" name="shipping_methods[${shippingIndex - 1}][id]" value="${shippingIndex}">
            <div class="col-1 text-center">
                <input type="radio" name="selected_shipping_area" value="${shippingIndex}" class="onchangeShippingMethod">
            </div>
            <div class="col-5">
                <label class="form-label">Shipping Area</label>
                <input type="text" name="shipping_methods[${shippingIndex - 1}][area]" class="form-control"
                       placeholder="Ex. Inside Dhaka / Outside Dhaka" required>
            </div>
            <div class="col-4">
                <label class="form-label">Cost</label>
                <input type="number" name="shipping_methods[${shippingIndex - 1}][cost]" class="form-control"
                       placeholder="Cost" required>
            </div>
            <div class="col-2 text-end">
                <label class="form-label"></label>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeShippingMethod(this)">Delete</button>
            </div>
        `;

            wrapper.appendChild(row);
        }


        function removeShippingMethod(button) {
            const row = button.closest('.shipping-method-row');
            row.remove();
        }

        // let defaultChecked = $('input[name="shipping_area"]:checked').val();
        //
        // function attachOnChangeToNewRadios() {
        //     document.querySelectorAll('.onchangeShippingMethod').forEach(el => {
        //         el.addEventListener('click', function () {
        //             let shippingArea = $(this).val();
        //
        //             if (shippingArea) {
        //                 swal.fire({
        //                     title: "You you sure to change default shipping area?",
        //                     type: 'warning',
        //                     showCancelButton: true,
        //                     confirmButtonText: 'Yes',
        //                     cancelButtonText: 'No, cancel!',
        //                     reverseButtons: true
        //                 }).then((result) => {
        //                     if (result.value) {
        //                         $url = "/update/default-shipping-area";
        //                         $.get($url, {
        //                             id: shippingArea
        //                         }, function (data) {
        //                             if (data.status) {
        //                                 defaultChecked = shippingArea;
        //                                 swal.fire(
        //                                     'success!',
        //                                     "Update default shipping area successfully",
        //                                     'success'
        //                                 );
        //                             } else {
        //                                 $('input[name="shipping_area"][value="' + defaultChecked + '"]').prop('checked', true);
        //                                 swal.fire(
        //                                     'error!',
        //                                     "Default shipping area not updated!",
        //                                     'error'
        //                                 );
        //                             }
        //                         });
        //                     } else if (
        //                         result.dismiss === Swal.DismissReason.cancel
        //                     ) {
        //                         $('input[name="shipping_area"][value="' + defaultChecked + '"]').prop('checked', true);
        //                         swal.fire(
        //                             'Cancelled', 'Cancel :)', 'error'
        //                         );
        //                     }
        //                 });
        //             }
        //         });
        //     });
        // }

        // Attach when DOM is ready
        document.addEventListener("DOMContentLoaded", attachOnChangeToNewRadios);
    </script>

@endpush
