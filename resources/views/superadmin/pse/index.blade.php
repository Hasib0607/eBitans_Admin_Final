@extends('admin.layouts.main')
@push('styles')
    <style>
        .image-link {
            cursor: -webkit-zoom-in;
            cursor: -moz-zoom-in;
            cursor: zoom-in;
        }

        /* This block of CSS adds opacity transition to background */
        .mfp-with-zoom .mfp-container,
        .mfp-with-zoom.mfp-bg {
            opacity: 0;
            -webkit-backface-visibility: hidden;
            -webkit-transition: all 0.3s ease-out;
            -moz-transition: all 0.3s ease-out;
            -o-transition: all 0.3s ease-out;
            transition: all 0.3s ease-out;
        }

        .mfp-with-zoom.mfp-ready .mfp-container {
            opacity: 1;
        }

        .mfp-with-zoom.mfp-ready.mfp-bg {
            opacity: 0.8;
        }

        .mfp-with-zoom.mfp-removing .mfp-container,
        .mfp-with-zoom.mfp-removing.mfp-bg {
            opacity: 0;
        }

        /* padding-bottom and top for image */
        .mfp-no-margins img.mfp-img {
            padding: 0;
        }

        /* position of shadow behind the image */
        .mfp-no-margins .mfp-figure:after {
            top: 0;
            bottom: 0;
        }

        /* padding for main container */
        .mfp-no-margins .mfp-container {
            padding: 0;
        }

        /* aligns caption to center */
        .mfp-title {
            text-align: center;
            padding: 6px 0;
        }

        .image-source-link {
            color: #DDD;
        }

        .zoom {
            transition: transform .2s;
            /* Animation */
            margin: 0 auto;
        }

        .zoom:hover {
            transform: scale(7.5);
            /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        }

        .centered-cell {
            text-align: center;
        }

        .barcode {
            margin: auto;
            display: inline-block;
            /* This ensures that the margin: auto; works for block-level elements within an inline container */
        }

        /* Custom styling for radio buttons in SweetAlert2 */
        .swal2-radio input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 15px;
            height: 15px;
            border: 2px solid #f1593a;
            /* Default border color */
            border-radius: 50%;
            outline: none;
            margin-right: 5px;
        }

        /* Custom styling for checked radio buttons in SweetAlert2 */
        .swal2-radio input[type="radio"]:checked {
            background-color: #f1593a;
            /* Change the background color when checked */
            border-color: #f1593a;
            /* Change the border color when checked */
        }

        .swal2-radio {
            display: block;
        }

        .swal2-radio input {
            margin-right: 5px;
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative  h-100 border-radius-lg">
        @include('superadmin.store_manage.category.top_bar_category')
        <div class="container-fluid mt-4" id="toplist">
            @if (canSuperStaffAccess('pse'))
                <div class="row mt-5 productlist">
                    <div class="col-12">
                        <div class="alert alert-info pt-2 pb-3"
                             style="background-image: linear-gradient(195deg, #b5b5b5 0%, #485059 100%);" role="alert">
                            <span style="color:#fff">
                                All PSE Requested Products
                            </span>
                            <ul style="display: unset;">
                                <li style="padding:0px;border:0px;">
                                    <a href="{{ route('superadmin.pse.rejected') }}" class="btn btn-primary btn-sm"
                                       style="display:block;border-radius:0px !important">
                                        Rejected
                                    </a>
                                </li>
                                <li style="padding:0px;border:0px;">
                                    <a href="{{ route('superadmin.pse.accepted') }}"
                                       style="display:block;border-radius:0px !important"
                                       class="btn btn-secondary btn-sm">
                                        Accepted
                                    </a>
                                </li>
                                <li style="padding:0px;border:0px;">
                                    <a href="{{ route('superadmin.product.pse') }}"
                                       style="display:block;border-radius:0px !important" class="btn btn-info btn-sm">
                                        Requested
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-3" style="padding-right:1px;">
                                        <input type="hidden" name="text2" id="selectids">
                                        <input type="hidden" name="type" id="type" value="Product">
                                        <select class='form-control' name="action" id="action">
                                            <option value="select">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    সিলেক্ট অপসন
                                                @else
                                                    Select Option
                                                @endif
                                            </option>
                                            <option value="accept">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Accepte
                                                @else
                                                    Accepte
                                                @endif
                                            </option>
                                            <option value="rejecte">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    Rejecte
                                                @else
                                                    Rejecte
                                                @endif
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-1" style="padding-left:0px;">
                                        <p id="submit" class="btn btn-primary filterbuttonss">
                                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                আবেদন
                                            @else
                                                Apply
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-6" style="float:right;">
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                   aria-label="Dollar amount (with dot and two decimal places)"
                                                   id="taskfilter">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;"><i
                                                    class="fa fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (Session::has('success_message'))
                                    <div class="alert alert-success" style="color:#fff">
                                        {{ Session::get('success_message') }}
                                    </div>
                                @endif
                                <div class="table-responsive" id="desktoptable">
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
                                            <th width="55%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    নাম
                                                @else
                                                    Name
                                                @endif
                                            </th>
                                            <th width="5%">
                                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                                    দাম
                                                @else
                                                    Price
                                                @endif
                                            </th>
                                            <th width="5%">
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
                                                    <input type="checkbox" name="selectedid" value="{{ $product->id }}"
                                                           id="id" class="checkSingle">
                                                </td>
                                                <td>
                                                    @php
                                                        $images = array_filter(explode(',', $product->productImage));
                                                        $gallery_image = array_filter(explode(',', $product->gallery_image));
                                                        $mergedImages = array_unique(array_merge($gallery_image, $images));
                                                    @endphp
                                                    @if(count($mergedImages) && isset($mergedImages[0]) && !empty($mergedImages[0]))
                                                        <img
                                                            src="{{ getPath($mergedImages[0], "assets/images/product") }}"
                                                            class="zoom" width="30px">
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <p style="color:#000">{{ Str::of($product->name)->limit(120) }}
                                                        <img style="height:18px; width:18px;"
                                                             src="{{ $product->expiry_date }}" alt=""
                                                             srcset="">
                                                    </p>
                                                    <p>
                                                        User Id
                                                        :
                                                        {{ $product->uid }}</p>
                                                </td>
                                                <td style="text-align: center;">৳{{ $product->regular_price }}</td>
                                                <td>{{ date('d-m-Y', strtotime($product->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route('superadmin.pse.select.view', $product->id) }}"><img
                                                            src="{{ asset('img/eye.png') }}" width="20px"
                                                            height="20px">
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a
                                                        onclick="showConfirmation('{{ $product->id }}', {{ json_encode($categories->pluck('name', 'id')) }}, '{{ $product->main_category_name }}', '{{ $product->subcategory_name }}', '{{ $product->category_id }}'); return false;">
                                                        <img src="{{ asset('img/accepted' . $product->pse . '.png') }}"
                                                             width="20px" height="20px">
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a
                                                        onclick="rejectConfirmation('{{ $product->id }}'); return false;">
                                                        <img src="{{ asset('img/delete.png') }}" width="25px"
                                                             height="25px">
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {!! $products->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection
@push('scripts')
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        //accepeted method
        function showConfirmation(productId, categories, mainCategoryName, subcategoryName) {
            // Extract category names and IDs from the object
            const categoryIds = Object.keys(categories);
            const categoryNames = Object.values(categories);

            // Create HTML for categories checkboxes and labels
            const categoriesHTML = categoryIds.map((categoryId, index) => `
                <div style="display: flex; align-items: center;">
                    <input type="checkbox" id="category${index}" name="category${index}" value="${categoryId}">
                    <label for="category${index}" style="margin: 5px;">${categoryNames[index]}</label>
                </div>
            `).join('');

            // Include main_category_name and subcategory_name if they exist
            const mainCategoryHTML = mainCategoryName ?
                `<div><strong>Category:<strong> ${mainCategoryName}</strong></div>` :
                '';
            const subcategoryHTML = subcategoryName ? `<div>Subcategory:<strong> ${subcategoryName}</strong></div>` : '';

            Swal.fire({
                title: 'Are you sure you want to accept this?',
                icon: 'question',
                type: 'warning',
                html: `
                    <div>
                        ${mainCategoryHTML}
                        ${subcategoryHTML}
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 10px;">
                            ${categoriesHTML}
                        </div>
                    `,
                showCancelButton: true,
                confirmButtonText: 'Accepted',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                didOpen: () => {
                    // Override the default validation message styling
                    Swal.getValidationMessage().style.padding = '10px';
                    Swal.getValidationMessage().style.borderRadius = '5px';
                },
                preConfirm: () => {
                    // Get the product ID
                    const selectedProductId = productId;

                    // Get only the selected category IDs
                    const selectedCategoryIds = categoryIds
                        .filter((categoryId, index) => {
                            const checkbox = document.getElementById(`category${index}`);
                            return checkbox.checked;
                        });

                    // Check if at least one category is selected
                    if (selectedCategoryIds.length === 0) {
                        Swal.showValidationMessage('Select At Least One Category');
                        setTimeout(() => {
                            Swal.close(); // Close the modal after a delay
                        }, 10000); // 10000 milliseconds = 10 seconds
                        return false; // Prevent the modal from closing
                    }

                    // Return the values in an array
                    return [selectedProductId, selectedCategoryIds];
                },
            })
                .then((result) => {
                    if (result.isConfirmed) {
                        // Access the values returned from preConfirm
                        const [selectedProductId, selectedCategoryIds] = result.value;

                        sendAjaxRequest('{{ route('superadmin.pse.accepted') }}', {
                            productId: selectedProductId,
                            categoryIds: selectedCategoryIds
                        });
                    }
                });
        }

        /**
         * Sends an AJAX request and handles the response
         * @param {string} url - The URL for the AJAX request
         * @param {Object} requestData - The data to be sent in the request
         */
        function sendAjaxRequest(url, requestData) {
            $.get(url, requestData, function (data) {
                if (data) {
                    Swal.fire(
                        'Congratulations Mr. {{ auth()->user()->name }}!',
                        data.status,
                        'success'
                    ).then((result) => {
                        if (result.isConfirmed) {
                            // Reload the browser window
                            window.location.reload();
                        }
                    });
                }
            });
        }

        function rejectConfirmation(productId) {
            Swal.fire({
                title: 'Rejecte Popup',
                text: 'This is a special popup for "rejecte" action.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Rejected',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                preConfirm: () => {
                    // Get the product ID
                    const selectedProductId = productId;

                    // Check if at least one category is selected
                    if (selectedProductId.length === 0) {
                        Swal.showValidationMessage('Select At Least One Category');
                        setTimeout(() => {
                            Swal.close(); // Close the modal after a delay
                        }, 10000); // 10000 milliseconds = 10 seconds
                        return false; // Prevent the modal from closing
                    }

                    // Return the values in an array
                    return [selectedProductId];
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    // Access the values returned from preConfirm
                    const [selectedProductId] = result.value;

                    sendAjaxRequest('{{ route('superadmin.pse.rejected') }}', {
                        productId: selectedProductId
                    });
                }
            });
        }

        // Function to handle checkbox behavior
        function handleCheckboxChange() {
            // Get all checkboxes with class 'checkSingle'
            var checkboxes = document.getElementsByClassName('checkSingle');
            var selectedIds = [];

            // Loop through all 'checkSingle' checkboxes
            for (var i = 0; i < checkboxes.length; i++) {
                // If checkbox is checked, add its value to selectedIds array
                if (checkboxes[i].checked) {
                    selectedIds.push(checkboxes[i].value);
                }
            }

            // Update the hidden input value with selected IDs
            document.getElementById('selectids').value = selectedIds.join(',');
        }

        // Function to handle checkedAll checkbox behavior
        function handleCheckedAllChange() {
            var checkedAll = document.getElementById('checkedAll');
            var checkboxes = document.getElementsByClassName('checkSingle');

            // Check if checkedAll checkbox is checked
            if (checkedAll.checked) {
                // If checked, mark all 'checkSingle' checkboxes as checked
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = true;
                }
            } else {
                // If not checked, mark all 'checkSingle' checkboxes as unchecked
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = false;
                }
            }

            // Update the state of 'checkSingle' checkboxes and selected IDs
            handleCheckboxChange();
        }

        // Function to handle Apply button click
        document.getElementById('submit').addEventListener('click', function () {
            var selectedAction = document.getElementById('action').value;
            var selectedIds = document.getElementById('selectids').value;

            console.log("selectedIds", selectedIds);

            // Check if any checkboxes are selected and an action is selected
            if (selectedIds && selectedAction !== 'select') {
                if (selectedAction === 'rejecte') {
                    // Show special popup for "rejecte"
                    rejectConfirmation(selectedIds);
                } else {
                    // Swal alert with selected IDs
                    showConfirmation(selectedIds, {!! json_encode($categories->pluck('name', 'id')) !!});
                }

            } else {
                // Show Swal alert if no checkboxes are selected or action is not selected
                Swal.fire({
                    title: 'Error!',
                    text: 'Please select at least one item and an action.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });

        // Attach event listener to checkedAll checkbox
        document.getElementById('checkedAll').addEventListener('change', handleCheckedAllChange);

        // Attach event listener to 'checkSingle' checkboxes
        var checkSingleCheckboxes = document.getElementsByClassName('checkSingle');
        for (var i = 0; i < checkSingleCheckboxes.length; i++) {
            checkSingleCheckboxes[i].addEventListener('change', handleCheckboxChange);
        }

        $(document).ready(function () {
            $("#taskfilter").on("keyup", function () {
                var value = $(this).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'get',
                    url: "{{ route('superadmin.pse.search') }}",
                    data: {
                        search: value
                    },
                    success: function (data) {
                        $('#taskfilterresult').html(data);
                    }
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>

    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function htmlTableToExcel(type) {
            var data = document.getElementById('excelDownload');
            var excelFile = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(excelFile, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(excelFile, 'ExportedFile:{{ auth()->user()->name }}.' + type);
        }
    </script>
@endpush
